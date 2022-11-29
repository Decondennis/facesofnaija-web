<?php
if ($wo['loggedin'] == false) {
	header("Location: " . $wo['config']['site_url']);
    exit();
}
if (empty($_GET['community'])) {
	header("Location: " . $wo['config']['site_url']);
    exit();
}
if ($wo['config']['communities'] == 0) {
    header("Location: " . Wo_SeoLink('index.php?link1=welcome'));
    exit();
}
$wo['setting']['admin'] = false;
if (isset($_GET['community']) && !empty($_GET['community'])) {
    if (Wo_CommunityExists($_GET['community']) === false) {
        header("Location: " . Wo_SeoLink('index.php?link1=404'));
        exit();
    }
    $community_id  = Wo_CommunityIdFromCommunityname($_GET['community']);
    $wo['setting']['admin'] = true;
    if (empty($community_id)) {
	    header("Location: " . $wo['config']['site_url']);
        exit();
    }
    $wo['setting'] = Wo_CommunityData($community_id);
}

//if (Wo_IsGroupOnwer($group_id) === false) {
if (Wo_IsAdmin() === false && Wo_IsModerator() === false) {
    header("Location: " . $wo['config']['site_url']);
    exit();
}
//}

$array = array('general-setting' => 'general','privacy-setting' => 'privacy','avatar-setting' => 'avatar','community-members' => 'members','analytics' => 'analytics','delete-community' => 'delete_community');
$s_page = 'general';
if (!empty($_GET['link3']) && in_array($_GET['link3'], array_keys($array))) {
    $s_page = $array[$_GET['link3']];
}
if (!Wo_IsAdmin() && !Wo_IsModerator() && !Wo_IsCanCommunityUpdate($wo['setting']['id'],$s_page)) {
    $allowed = Wo_GetAllowedCommunityPages($community_id);
    if (!empty($allowed) && !empty($allowed[0])) {
        $_GET['link3'] = $allowed[0];
    }
    else{
        header("Location: " . $wo['config']['site_url']);
        exit();
    }
}

$wo['description'] = $wo['config']['siteDesc'];
$wo['keywords']    = $wo['config']['siteKeywords'];
$wo['page']        = 'community_setting';
$wo['title']       = $wo['lang']['setting'];
$wo['content']     = Wo_LoadPage('community-setting/content');