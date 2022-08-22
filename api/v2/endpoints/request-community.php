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
$response_data   = array(
    'api_status' => 400
);

$required_fields = array(
    'name',
    'country',
    'state',
    'lga',
    'privacy',
    'about',
);

foreach ($required_fields as $key => $value) {
    if (empty($_POST[$value]) && empty($error_code)) {
        $error_code    = 3;
        $error_message = $value . ' (POST) is missing';
    }
}

if (empty($error_code)) {
    $name     = Wo_Secure($_POST['name']);
    $country    = Wo_Secure($_POST['country']);
    $state       = Wo_Secure($_POST['state']);
    $lga       = Wo_Secure($_POST['lga']);
    $about          = Wo_Secure($_POST['about']);

    $is_exist = Wo_IsNameExist($_POST['name'], 0);
    
    if (in_array(true, $is_exist) || in_array($_POST['name'], $wo['site_pages'])) {
        $error_code    = 4;
        $error_message = 'Community name is already exists.';
    } else if (strlen($_POST['name']) < 5 OR strlen($_POST['name']) > 32) {
        $error_code    = 5;
        $error_message = 'Community name must be between 5 / 32';
    } /*else if (!preg_match('/^[\w]+$/', $_POST['name'])) {
        $error_code    = 6;
        $error_message = 'Invalid community name characters';
    }*/
    $privacy = 1;
    if (!empty($_POST['privacy'])) {
        if ($_POST['privacy'] == 2) {
            $privacy = 2;
        }
    }
    
    if (empty($error_code)) {
        $sub_category = '';
        if (!empty($_POST['community_sub_category']) && !empty($wo['community_sub_categories'][$_POST['category']])) {
            foreach ($wo['group_sub_categories'][$_POST['category']] as $key => $value) {
                if ($value['id'] == $_POST['community_sub_category']) {
                    $sub_category = $value['id'];
                }
            }
        }
    	$community_data  = array(
            'name' => $name,
            'user_id' => $wo['user']['user_id'],
            'country' => $country,
            'about' => $about,
            'state' => $state,
            'privacy' => Wo_Secure($privacy),
            'lga' => $lga
        );
        
        $fields = Wo_GetCustomFields('group'); 
        if (!empty($fields)) {
            foreach ($fields as $key => $field) {
                if ($field['required'] == 'on' && empty($_POST['fid_'.$field['id']])) {
                    $response_data       = array(
                        'api_status'     => '404',
                        'errors'         => array(
                            'error_id'   => 7,
                            'error_text' => 'please check details required field'
                        )
                    );
                    echo json_encode($response_data, JSON_PRETTY_PRINT);
                    exit();
                }
                elseif (!empty($_POST['fid_'.$field['id']])) {
                    $community_data['fid_'.$field['id']] = Wo_Secure($_POST['fid_'.$field['id']]);
                }
            }
        }

        $request_community    = Wo_RequestCommunity($community_data);
        
        if ($request_community) {
            $response_data = array(
                'api_status' => 200,
                'community_data' => $_POST['name']//Wo_CommunityData(Wo_CommunityIdFromCommunityname($_POST['name']))
            );
        }
    }
}