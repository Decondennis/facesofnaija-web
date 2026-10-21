<?php
// +------------------------------------------------------------------------+
// | Facesofnaija - Announcements & Breaking News API
// +------------------------------------------------------------------------+

$data = array();

// 1. Fetch live RSS news items (source of truth from PDF & web app)
if (function_exists('Wo_GetRssNewsHeadlines')) {
    $rss_items = Wo_GetRssNewsHeadlines(4);
    if (!empty($rss_items) && is_array($rss_items)) {
        foreach ($rss_items as $item) {
            $src = !empty($item['source']) ? $item['source'] : 'News';
            $tit = !empty($item['title']) ? $item['title'] : '';
            $lnk = !empty($item['link']) ? $item['link'] : '';
            $tm  = !empty($item['time']) ? $item['time'] : time();
            $data[] = array(
                'id'          => 'rss_' . (!empty($item['id']) ? $item['id'] : md5($tit)),
                'text'        => "[$src] " . $tit,
                'text_decode' => $tit,
                'source'      => $src,
                'url'         => $lnk,
                'link'        => $lnk,
                'time'        => (string)$tm,
                'time_text'   => function_exists('Wo_Time_Elapsed_String') ? Wo_Time_Elapsed_String($tm) : date('M j, Y', $tm),
                'active'      => '1',
                'is_viewed'   => false,
                'type'        => 'news'
            );
        }
    }
}

// 2. Fetch database admin announcements
if (!empty($sqlConnect)) {
    $query = mysqli_query($sqlConnect, "SELECT id FROM " . T_ANNOUNCEMENT . " WHERE active = '1' ORDER BY id DESC");
    if ($query) {
        while ($row = mysqli_fetch_assoc($query)) {
            $announcement = Wo_GetAnnouncement($row['id']);
            if (!empty($announcement) && is_array($announcement)) {
                $announcement['is_viewed'] = Wo_IsViewedAnnouncement($announcement['id']);
                $announcement['text_decode'] = strip_tags($announcement['text']);
                $announcement['time_text'] = Wo_Time_Elapsed_String($announcement['time']);
                $announcement['source'] = 'Announcement';
                $announcement['url'] = '';
                $announcement['type'] = 'announcement';
                $data[] = $announcement;
            }
        }
    }
}

$response_data = array(
    'api_status'    => '200',
    'api_text'      => 'success',
    'api_version'   => '2.0',
    'announcements' => $data,
    'count'         => count($data)
);
