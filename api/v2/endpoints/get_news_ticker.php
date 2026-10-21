<?php
// +------------------------------------------------------------------------+
// | Facesofnaija - Nigerian & World News RSS Ticker API
// +------------------------------------------------------------------------+

$limit = (!empty($_POST['limit']) && is_numeric($_POST['limit']) && $_POST['limit'] > 0 && $_POST['limit'] <= 50) ? (int)$_POST['limit'] : 4;
$force_refresh = !empty($_POST['force_refresh']) && ($_POST['force_refresh'] === 'true' || $_POST['force_refresh'] == 1);

$news_items = function_exists('Wo_GetRssNewsHeadlines') ? Wo_GetRssNewsHeadlines($limit, $force_refresh) : array();

$response_data = array(
    'api_status' => 200,
    'api_text'   => 'success',
    'data'       => $news_items,
    'count'      => count($news_items)
);
