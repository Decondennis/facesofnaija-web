<?php
// +------------------------------------------------------------------------+
// | Facesofnaija - Nigerian & World News RSS Ticker API
// +------------------------------------------------------------------------+

$limit = (!empty($_REQUEST['limit']) && is_numeric($_REQUEST['limit']) && $_REQUEST['limit'] > 0 && $_REQUEST['limit'] <= 50) ? (int)$_REQUEST['limit'] : 4;
$force_refresh = !empty($_REQUEST['force_refresh']) && ($_REQUEST['force_refresh'] === 'true' || $_REQUEST['force_refresh'] == 1);

$news_items = function_exists('Wo_GetRssNewsHeadlines') ? Wo_GetRssNewsHeadlines($limit, $force_refresh) : array();

$ticker_parts = array();
foreach ($news_items as $r) {
    $src = !empty($r['source']) ? strtoupper($r['source']) : 'NEWS';
    $tit = !empty($r['title']) ? $r['title'] : '';
    if (!empty($tit)) {
        $ticker_parts[] = "[$src] $tit";
    }
}
$ticker_text = implode('   •   ', $ticker_parts);

$response_data = array(
    'api_status'   => 200,
    'api_text'     => 'success',
    'ticker_text'  => $ticker_text,
    'data'         => $news_items,
    'news'         => $news_items,
    'count'        => count($news_items)
);
