<?php
if (isset($_GET['c'])) {
    if (Wo_GroupExists($_GET['c']) === true && Wo_GroupActive($_GET['c'])) {
        $group_id            = Wo_GroupIdFromGroupname($_GET['c']);
        $wo['community_profile'] = Wo_GroupData($group_id);
    } else {
        //header("Location: " . Wo_SeoLink('index.php?link1=404'));
        exit();
    }
} else {
    header("Location: " . $wo['config']['site_url']);
    exit();
}
if ($wo['config']['groups'] == 0) {
    header("Location: " . Wo_SeoLink('index.php?link1=welcome'));
    exit();
}
$wo['description'] = $wo['community_profile']['about'];
$wo['keywords']    = '';
$wo['page']        = 'community';
$wo['title']       = $wo['community_profile']['name'];
$wo['content']     = Wo_LoadPage('community/content');