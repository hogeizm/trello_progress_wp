<?php

class CurlOption {
    public $url;
    public $useDigest;
    public $digestUser;
    public $digestPassword;
}

class NetworkUtil {
    private $ch;

    function __construct() {
        $this->ch = curl_init();
    }

    function __destruct()
    {
        curl_close($this->ch);
    }

    public function GetJsonByCurl(CurlOption $curlOption) {
        curl_setopt($this->ch, CURLOPT_URL, $curlOption->url);
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($this->ch, CURLOPT_TIMEOUT, 10);
        if ($curlOption->useDigest) {
            curl_setopt($this->ch, CURLOPT_HTTPAUTH, CURLAUTH_DIGEST);
            curl_setopt($this->ch, CURLOPT_USERPWD, "$curlOption->digestUser:$curlOption->digestPassword");
        }
        $response = curl_exec($this->ch);
        $error = curl_error($this->ch);
        $errno = curl_errno($this->ch);
        if ($errno != CURLE_OK) {
            throw new RuntimeException($error, $errno);
        }
        return json_decode($response);
    }
}

class TrelloUtil
{
    private $userName;
    private $key;
    private $token;

    function __construct($userName, $key, $token)
    {
        $this->userName = $userName;
        $this->key = $key;
        $this->token = $token;
    }

    public function GetBoardsFromShortUrls(array $shortUrls) : array
    {
        $curlOption = new CurlOption();
        $curlOption->url = "https://trello.com/1/members/{$this->userName}/boards?key={$this->key}&token={$this->token}&fields=name,desc,shortUrl,prefs";
        try {
            $network = new NetworkUtil();
            $response = [];
            $boards = $network->GetJsonByCurl($curlOption);
            foreach($boards as $val) {
                if (in_array($val->shortUrl, $shortUrls, true)) {
                    $is_public = $val->prefs->permissionLevel === "public";
                    unset($val->prefs);
                    $val->isPublic = $is_public;
                    $response[] = $val;
                }
            }
            return $response;
        } catch (RuntimeException $re) {
            return [];
        }
    }

    public function SetListsIntoBoards(array $boards) : array
    {
        $curlOption = new CurlOption();
        $curlOption->url = 'https://api.trello.com/1/batch/?urls=';
        $urls = "";
        foreach ($boards as $board) {
            $urls .= urlencode("/boards/{$board->id}/lists?fields=name").',';
        }
        $curlOption->url .= substr($urls, 0, -1) . "&key={$this->key}&token={$this->token}";
        try {
            $network = new NetworkUtil();
            $api_responses = $network->GetJsonByCurl($curlOption);
            $i = 0;
            foreach($api_responses as $api_response) {
                foreach($api_response as $code => $lists) {
                    if ($code === "200") {
                        $boards[$i]->lists = $lists;
                    } else {
                        throw new RuntimeException();
                    }
                    break;
                }
                $i++;
            }
            return $boards;
        } catch (\RuntimeException $re) {
            return [];
        }
    }
    public function SetCardsIntoListsAtBoards(array $boards) {
        $curlOption = new CurlOption();
        $curlOption->url = 'https://api.trello.com/1/batch/?urls=';
        $urls = "";
        foreach ($boards as $board) {
            $urls .= urlencode("/boards/{$board->id}/cards?fields=name%2Cbadges%2CdateLastActivity%2CidList").',';
        }
        $curlOption->url .= substr($urls, 0, -1) . "&key={$this->key}&token={$this->token}";
        try {
            $networkUtil = new NetworkUtil();
            $api_responses = $networkUtil->GetJsonByCurl($curlOption);

            $cards_in_boards = [];
            foreach($api_responses as $api_response) {
                foreach($api_response as $code => $cards) {
                    if ($code !== "200") {
                        throw new Exception("Faild HTTP Access:$code");
                    }
                    $cards_in_boards[] = $cards;
                }
            }
            for($i = 0; $i < count($boards); $i++) {
                $cards = $cards_in_boards[$i];
                for($j = 0; $j < count($boards[$i]->lists); $j++) {
                    $list = $boards[$i]->lists[$j];
                    $boards[$i]->lists[$j]->cards = [];
                    for($k = 0; $k < count($cards); $k++) {
                        $card = $cards[$k];
                        if (isset($card->idList) && $list->id === $card->idList) {
                            $new_card = self::RemoveUnnecessaryItemsInCard($card);
                            $boards[$i]->lists[$j]->cards[] = $new_card;
                        }
                    }
                }
            }
        } catch (Exception $re) {
            return ['Exception' => $re];
        }
        return $boards;
    }

    private static function RemoveUnnecessaryItemsInCard($card) {
        $card->checkItems = $card->badges->checkItems;
        $card->checkItemsChecked = $card->badges->checkItemsChecked;
        unset($card->badges);
        unset($card->idList);
        return $card;
    }
}


function getBoards($args) {
    if (!isset($args[0]) || !isset($args[1]) || !isset($args[2]) || !isset($args[3])) {
        return 'パラメータ誤り：' . var_export($args, true);
    }
    $urls = explode(',', $args[3]);
    $trelloUtil = new TrelloUtil($args[0], $args[1], $args[2]);
    $boards = $trelloUtil->GetBoardsFromShortUrls($urls);
    $boards = $trelloUtil->SetListsIntoBoards($boards);
    $boards = $trelloUtil->SetCardsIntoListsAtBoards($boards);
    return $boards;
}
