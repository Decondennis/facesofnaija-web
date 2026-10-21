<?php
if ($wo['loggedin'] == false) {
    header("Location: " . Wo_SeoLink('index.php?link1=welcome'));
    exit();
}

$wo['description'] = !empty($wo['config']['siteDesc']) ? $wo['config']['siteDesc'] : '';
$wo['keywords']    = !empty($wo['config']['siteKeywords']) ? $wo['config']['siteKeywords'] : '';
$wo['page']        = 'announcements';
$wo['title']       = !empty($wo['lang']['announcements']) ? $wo['lang']['announcements'] : 'News & Announcements';
if (empty($wo['title']) || $wo['title'] == 'announcements') {
    $wo['title'] = 'News & Announcements';
}

$wo['announcements']       = function_exists('Wo_GetAllUserAnnouncements') ? Wo_GetAllUserAnnouncements(100) : array();
$wo['announcements_count'] = function_exists('Wo_CountActiveAnnouncements') ? Wo_CountActiveAnnouncements() : count($wo['announcements']);
$wo['rss_news']            = function_exists('Wo_GetRssNewsHeadlines') ? Wo_GetRssNewsHeadlines(4) : array();

$wo['content']     = Wo_LoadPage('announcements/content');
