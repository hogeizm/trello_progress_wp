# Trello Progress WP

## What's this?

Wordpress向けに、Trello API呼び出して複数のボード内のリストとカードを取得し、進捗を表示するプログラムです。  
ショートコードにて、以下のように現在のTrelloの状態から生成された進捗テーブル表示することができます。
![trello_progress_wp_img](https://user-images.githubusercontent.com/38904945/67152693-f367b180-f316-11e9-8c3d-540eca08a6d2.png)
ボード名、ボード詳細、``shortUrl``、それとリストとカードから生成したテーブルで構成されます。

## [STEP1] Trello APIのキーの発行とtokenの取得

Trelloサーバーと認証するために、あなた自身が専用のキーを発行する必要があります。  
Trelloにログインした状態で以下のURLにアクセスし、Keyを発行します。  
また、同ページのリンク（APIキーのすぐ下にあるリンク）からtokenを取得します。  
これらはAPI発行時に毎回付与する必要がある情報です。  

https://trello.com/app-key

![APIキーの発行とtokenの取得](https://user-images.githubusercontent.com/38904945/67152704-62dda100-f317-11e9-9ecb-137b2de69441.png)

## [STEP2] Wordpressへ配置する

WordPressがインストールされているフォルダに、以下のディレクトリを作成します。

```
/wp-contents/myphp/
```

git cloneなどで取得した、以下のファイルを配置します。

```
progress-view.php
progress.php
```

### functon.phpへ追記する

取得したfunction.phpの中身をすべてコピーし、あなたが使用中のテーマのfunction.phpに追記(貼り付け)します。  
そのまま配置するものではありません。

### progress-style.cssの適用

この作業は必須では有りません。  
しかし、progress-style.cssを適用することで、デフォルトのデザインが適用されます。  
trello progress wpはtableタグを使用しており、CSS3のborder-radiusの適用には若干コツがあります。  
そのため、CSS3の仕様と向き合うのが面倒であれば、これをそのまま適用して、カラーやマージンの調整などを行うのが近道かもしれません。  
  
cssの適用については、Wordpressの「外観」>「カスタマイズ」のメニューの「追加CSS」の設定にて追記することもできます。  
しかし、これでは全体に適用されてしまうため、おすすめはできません。  
できればページごとにCSSを設定できるようなプラグインを導入し、そこにCSSを設定することをおすすめします。  

## [STEP3] ショートコードで呼び出す

ショートコードにて、以下の形式で呼び出します。
入力としたボードの``shortUrl``の数だけ表が生成されます。

```
[progress ユーザー名 APIキー トークン '1つ目のボードのshortUrl,2つ目のボードのshortUrl,...']
```

### ユーザー名

ログイン状態で表示される画面右上のアイコンをクリックするとユーザー名を確認できます。  

以下は例です。  
ご自分のTrelloボードにて、ユーザー名を確認してください。  
![ユーザー名の確認例](https://user-images.githubusercontent.com/38904945/67150160-e1721880-f2ee-11e9-84b7-14b762e31b23.png)

### shortUrl

ボードの``shortUrl``は間にスペースは入れず、カンマ区切りで指定してください。  
ボードの``shortUrl``とは、Trelloのボードにアクセスした際に表示されるURLの、 ボード名の前のスラッシュ以降を含まないURLを指します。  
  
例としては、以下のとおりです。  

```
https://trello.com/b/PjmzAjhE/hogeland
 => https://trello.com/b/PjmzAjhE
```

## [STEP4] 進捗を上げましょう

上手く行けばSTEP3までで、進捗状況が公開されるでしょう。  
  
あとは自分自身との戦いです。  
検討を祈ります。  

## 動作仕様

* ショートコードからfunction.phpパラメータ（ユーザ名 APIキー token URLs）を受け渡します。
* progress.phpが、ボードのURLをもとに、TrelloのBatch APIにて１つ以上のボードの情報を取得します
* ボード情報に含まれるボードIDより、全てのリストの一覧を取得し、ボード情報に関連付けて設定します
* ボード情報に含まれるボードIDより、全てのカードの一覧を取得し、ボード情報内のリストに関連付けて設定します
* progerss.phpで取得した情報をもとに、progress-view.phpにてHTMLを構成し、Wordressに返却します

## 動作確認済みの環境

Wordpress 5.6 
PHP 7.4

## Trello APIの感想

* TrelloのREST APIドキュメントは見切れてるし、無駄に派生系のAPIが多くてわかりづらい。  
https://developers.trello.com/reference
* APIのレスポンスが正規化されているのは良いが、されすぎていて複合した情報を得るために数回APIを発行する必要がある。
* APIの発行回数を抑えるためにはBatch APIを使う必要があるが、Batch APIの仕様に癖がある。  
（APIのURLを区切るためには予めカンマを%2Cに置き換え、更にカンマ区切りのURLパラメータURLエンコードする必要がある。）
* 進捗は大事。ありがとうTrello。

## Lisence

PDS (Public Domain Software)  

誰でも自由に使用、変更、および商用化できます。  
著作表記も必要ありません。  
  
ただし、元の著作者であるhogeizmは、  
本ソフトウェア、および派生するソフトウェアについて一切の責任を負わず、なんら保証もしません。  
（質問事項は twitter:@hogeizm まで）

## ひとこと

Have a good your life!
