<?php
// +------------------------------------------------------------------------+
// | @author Deen Doughouz (DoughouzForest)
// | @author_url 1: http://www.wowonder.com
// | @author_url 2: http://codecanyon.net/user/doughouzforest
// | @author_email: wowondersocial@gmail.com
// +------------------------------------------------------------------------+
// | WoWonder - The Ultimate Social Networking Platform
// | Copyright (c) 2018 WoWonder. All rights reserved.
// +------------------------------------------------------------------------+
$response_data = array(
    'api_status' => 400,
);
if (empty($_POST['community_id'])) {
    $error_code    = 3;
    $error_message = 'community_id (POST) is missing';
}

if (empty($error_code)) {
    $community_id   = Wo_Secure($_POST['community_id']);
    $community_data = Wo_CommunityData($community_id);
    if (empty($community_data)) {
        $error_code    = 6;
        $error_message = 'Community not found';
    } else {
        $response_data = array('api_status' => 200);
        
        foreach ($non_allowed as $key => $value) {
            unset($community_data[$value]);
        }
        $group_data['post_count'] = Wo_CountCommunityPosts($community_data['community_id']);
        //$group_data['is_joined'] = Wo_IsGroupJoined($group_data['group_id']);
        //$group_data['is_owner'] = Wo_IsGroupOnwer($group_data['group_id']);

        $response_data['community_data'] = $community_data;
    }
}