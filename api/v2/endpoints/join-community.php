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
    	$join_message = 'invalid';
        if (Wo_IsCommunityJoined($community_id) === true || Wo_IsJoinCommunityRequested($community_id, $wo['user']['user_id']) === true) {
            if (Wo_LeaveCommunity($community_id, $wo['user']['user_id'])) {
                $join_message = 'left';
            }
        } else {
            if (Wo_RegisterCommunityJoin($community_id, $wo['user']['user_id'])) {
                if ($group_data['join_privacy'] == 2) {
                    $join_message = 'requested';
                }
                else{
                    $join_message = 'joined';
                }
            }
        }
        $response_data = array(
		    'api_status' => 200,
		    'join_status' => $join_message
		);
    }
}