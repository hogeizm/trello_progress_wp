function showTrelloProgressTable($args) {
    include( WP_CONTENT_DIR . "/myphp/progress.php");

    ob_start();
    $boards = getBoards($args);
    include( WP_CONTENT_DIR . "/myphp/progress-view.php");
    $output = ob_get_contents();
    ob_get_clean();

    return $output;
}
add_shortcode('progress', 'showTrelloProgressTable');
