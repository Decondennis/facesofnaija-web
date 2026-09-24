<?php
// +------------------------------------------------------------------------+
// | @author Deen Doughouz (DoughouzForest)
// | @author_url 1: http://www.wowonder.com
// | @author_url 2: http://codecanyon.net/user/doughouzforest
// | @author_email: wowondersocial@gmail.com   
// +------------------------------------------------------------------------+
// | WoWonder - The Ultimate Social Networking Platform
// | Copyright (c) 2016 WoWonder. All rights reserved.
// +------------------------------------------------------------------------+
$json_error_data   = array();
$json_success_data = array();
if (empty($_GET['type']) || !isset($_GET['type'])) {
    $json_error_data = array(
        'api_status' => '400',
        'api_text' => 'failed',
        'api_version' => $api_version,
        'errors' => array(
            'error_id' => '1',
            'error_text' => 'Bad request, no type specified.'
        )
    );
    header("Content-type: application/json");
    echo json_encode($json_error_data, JSON_PRETTY_PRINT);
    exit();
}
$type = Wo_Secure($_GET['type'], 0);
if ($type == 'get_news_list' || $type == 'get_news_feed') {
    if (empty($_POST['user_id'])) {
        $json_error_data = array(
            'api_status' => '400',
            'api_text' => 'failed',
            'api_version' => $api_version,
            'errors' => array(
                'error_id' => '3',
                'error_text' => 'No user id sent.'
            )
        );
    } else if (empty($_POST['s'])) {
        $json_error_data = array(
            'api_status' => '400',
            'api_text' => 'failed',
            'api_version' => $api_version,
            'errors' => array(
                'error_id' => '5',
                'error_text' => 'No session sent.'
            )
        );
    }
    if (empty($json_error_data)) {
        $user_id         = $_POST['user_id'];
        $s      = Wo_Secure($_POST['s']);
        $user_login_data = Wo_UserData($user_id);
        if (empty($user_login_data)) {
            $json_error_data = array(
                'api_status' => '400',
                'api_text' => 'failed',
                'api_version' => $api_version,
                'errors' => array(
                    'error_id' => '6',
                    'error_text' => 'Username is not exists.'
                )
            );
            header("Content-type: application/json");
            echo json_encode($json_error_data, JSON_PRETTY_PRINT);
            exit();
        } else if (Wo_CheckUserSessionID($user_id, $s, 'phone') === false) {
            $json_error_data = array(
                'api_status' => '400',
                'api_text' => 'failed',
                'api_version' => $api_version,
                'errors' => array(
                    'error_id' => '6',
                    'error_text' => 'Session id is wrong.'
                )
            );
            header("Content-type: application/json");
            echo json_encode($json_error_data, JSON_PRETTY_PRINT);
            exit();
        } else {
            $wo['lang'] = Wo_LangsFromDB($user_login_data['language']);
            $timezone = new DateTimeZone($user_login_data['timezone']);

            $limit = 20;
            if (!empty($_POST['limit']) && is_numeric($_POST['limit']) && $_POST['limit'] > 0 && $_POST['limit'] <= 50) {
                $limit = Wo_Secure($_POST['limit']);
            }
            $after_post_id = 0;
            if (!empty($_POST['after_post_id']) && is_numeric($_POST['after_post_id']) && $_POST['after_post_id'] > 0) {
                $after_post_id = Wo_Secure($_POST['after_post_id']);
            } else if (!empty($_POST['offset']) && is_numeric($_POST['offset']) && $_POST['offset'] > 0) {
                $after_post_id = Wo_Secure($_POST['offset']);
            }

            $posts_data = array(
                'limit' => $limit,
                'publisher_id' => 0,
                'after_post_id' => $after_post_id,
                'placement' => 'multi_image_post',
                'anonymous' => true
            );
            if (!empty($_POST['post_type']) && in_array($_POST['post_type'], array('photos', 'video', 'music', 'files', 'maps', 'text'))) {
                $posts_data['filter_by'] = Wo_Secure($_POST['post_type']);
            }

            $stories = Wo_GetPosts($posts_data);

            if (empty($after_post_id) || empty($stories)) {
                $latest_feed = Wo_GetPosts(array(
                    'limit' => 1,
                    'publisher_id' => 0,
                    'after_post_id' => 0,
                    'placement' => 'multi_image_post',
                    'anonymous' => true
                ));
                if (!empty($latest_feed) && !empty($latest_feed[0]['id'])) {
                    $latest_feed_id = (int) $latest_feed[0]['id'];
                    $exists_in_feed = false;
                    foreach ($stories as $feed_post) {
                        if (!empty($feed_post['id']) && (int) $feed_post['id'] === $latest_feed_id) {
                            $exists_in_feed = true;
                            break;
                        }
                    }
                    if ($exists_in_feed == false) {
                        array_unshift($stories, $latest_feed[0]);
                    }
                }
            }

            foreach ($stories as $story) {
                foreach ($non_allowed as $value) {
                    unset($story['publisher'][$value]);
                }
                $story['get_post_comments'] = array();
                if (!empty($story['postFile'])) {
                    $story['postFile'] = Wo_GetMedia($story['postFile']);
                }
                if (!empty($story['postFileThumb'])) {
                    $story['postFileThumb'] = Wo_GetMedia($story['postFileThumb']);
                }
                $story['time_text'] = Wo_Time_Elapsed_String($story['time']);
                array_push($json_success_data, $story);
            }
        }
    } else {
        header("Content-type: application/json");
        echo json_encode($json_error_data);
        exit();
    }
}
header("Content-type: application/json");
echo json_encode(array(
    'api_status' => '200',
    'api_text' => 'success',
    'api_version' => $api_version,
    'posts' => $json_success_data,
    'stories' => $json_success_data
));
exit();
?>