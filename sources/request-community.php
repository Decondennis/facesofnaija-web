<?php
// +------------------------------------------------------------------------+
// | @author Deen Doughouz (DoughouzForest)
// | @author_url 1: http://www.wowonder.com
// | @author_url 2: http://codecanyon.net/user/doughouzforest
// | @author_email: wowondersocial@gmail.com
// +------------------------------------------------------------------------+
// | WoWonder - The Ultimate Social Networking Platform
// | Copyright (c) 2022 WoWonder. All rights reserved.
// +------------------------------------------------------------------------+

if ($wo['loggedin'] == false) {
  header("Location: " . Wo_SeoLink('index.php?link1=welcome'));
  exit();
}

if ($wo['config']['communities'] == 0) {
    header("Location: " . Wo_SeoLink('index.php?link1=welcome'));
    exit();
}

$wo['description'] = $wo['config']['siteDesc'];
$wo['keywords']    = $wo['config']['siteKeywords'];
$wo['page']        = 'request_community';
$wo['title']       = $wo['lang']['my_community_requests'] ?? 'My Community Requests';
$wo['content']     = Wo_LoadPage('community/request-community');
