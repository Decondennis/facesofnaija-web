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

$posts_data = array();

$limit = (!empty($_POST['limit']) && is_numeric($_POST['limit']) && $_POST['limit'] > 0 && $_POST['limit'] <= 50) ? (int) Wo_Secure($_POST['limit']) : 20;
$after_post_id = (!empty($_POST['after_post_id']) && is_numeric($_POST['after_post_id']) && $_POST['after_post_id'] > 0) ? (int) Wo_Secure($_POST['after_post_id']) : 0;
if (empty($after_post_id) && !empty($_POST['offset']) && is_numeric($_POST['offset']) && $_POST['offset'] > 0) {
    $after_post_id = (int) Wo_Secure($_POST['offset']);
}

$feed_query = array(
    'limit' => $limit,
    'publisher_id' => 0,
    'after_post_id' => $after_post_id,
    'placement' => 'multi_image_post',
    'anonymous' => true
);
if (!empty($_POST['post_type']) && in_array($_POST['post_type'], array('photos', 'video', 'music', 'files', 'maps', 'text'))) {
    $feed_query['filter_by'] = Wo_Secure($_POST['post_type']);
}

$posts = Wo_GetPosts($feed_query);
if (!is_array($posts)) {
    $posts = array();
}

if (empty($after_post_id) || empty($posts)) {
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
        foreach ($posts as $feed_post) {
            if (!empty($feed_post['id']) && (int) $feed_post['id'] === $latest_feed_id) {
                $exists_in_feed = true;
                break;
            }
        }
        if ($exists_in_feed == false) {
            array_unshift($posts, $latest_feed[0]);
        }
    }

    if (!empty($wo['user']['user_id'])) {
        $my_latest = Wo_GetPosts(array(
            'limit' => 1,
            'publisher_id' => $wo['user']['user_id'],
            'placement' => 'multi_image_post',
            'anonymous' => true
        ));
        if (!empty($my_latest) && !empty($my_latest[0]['id'])) {
            $my_post_id = (int) $my_latest[0]['id'];
            $exists_my_post = false;
            foreach ($posts as $feed_post) {
                if (!empty($feed_post['id']) && (int) $feed_post['id'] === $my_post_id) {
                    $exists_my_post = true;
                    break;
                }
            }
            if ($exists_my_post == false) {
                array_unshift($posts, $my_latest[0]);
            }
        }
    }
}

foreach ($posts as $key => $value) {
    $posts[$key]['shared_info'] = null;

    if (!empty($posts[$key]['postFile'])) {
        $posts[$key]['postFile'] = Wo_GetMedia($posts[$key]['postFile']);
    }
    if (!empty($posts[$key]['postFileThumb'])) {
        $posts[$key]['postFileThumb'] = Wo_GetMedia($posts[$key]['postFileThumb']);
    }
    if (!empty($posts[$key]['postPlaytube'])) {
        $posts[$key]['postText'] = strip_tags($posts[$key]['postText']);
    }

    if (!empty($posts[$key]['publisher'])) {
        foreach ($non_allowed as $value4) {
            unset($posts[$key]['publisher'][$value4]);
        }
        if (!empty($posts[$key]['publisher']['avatar']) && !Wo_IsUrl($posts[$key]['publisher']['avatar'])) {
            $posts[$key]['publisher']['avatar'] = Wo_GetMedia($posts[$key]['publisher']['avatar']);
        }
        if (!empty($posts[$key]['publisher']['cover']) && !Wo_IsUrl($posts[$key]['publisher']['cover'])) {
            $posts[$key]['publisher']['cover'] = Wo_GetMedia($posts[$key]['publisher']['cover']);
        }
    } else {
        $posts[$key]['publisher'] = null;
    }

    if (!empty($posts[$key]['user_data'])) {
        foreach ($non_allowed as $value4) {
            unset($posts[$key]['user_data'][$value4]);
        }
        if (!empty($posts[$key]['user_data']['avatar']) && !Wo_IsUrl($posts[$key]['user_data']['avatar'])) {
            $posts[$key]['user_data']['avatar'] = Wo_GetMedia($posts[$key]['user_data']['avatar']);
        }
        if (!empty($posts[$key]['user_data']['cover']) && !Wo_IsUrl($posts[$key]['user_data']['cover'])) {
            $posts[$key]['user_data']['cover'] = Wo_GetMedia($posts[$key]['user_data']['cover']);
        }
    } else {
        $posts[$key]['user_data'] = null;
    }

    if (!empty($posts[$key]['parent_id'])) {
        $shared_info = Wo_PostData($posts[$key]['parent_id']);
        if (!empty($shared_info)) {
            if (!empty($shared_info['postFile'])) {
                $shared_info['postFile'] = Wo_GetMedia($shared_info['postFile']);
            }
            if (!empty($shared_info['postFileThumb'])) {
                $shared_info['postFileThumb'] = Wo_GetMedia($shared_info['postFileThumb']);
            }
            if (!empty($shared_info['publisher'])) {
                foreach ($non_allowed as $value4) {
                    unset($shared_info['publisher'][$value4]);
                }
                if (!empty($shared_info['publisher']['avatar']) && !Wo_IsUrl($shared_info['publisher']['avatar'])) {
                    $shared_info['publisher']['avatar'] = Wo_GetMedia($shared_info['publisher']['avatar']);
                }
                if (!empty($shared_info['publisher']['cover']) && !Wo_IsUrl($shared_info['publisher']['cover'])) {
                    $shared_info['publisher']['cover'] = Wo_GetMedia($shared_info['publisher']['cover']);
                }
            } else {
                $shared_info['publisher'] = null;
            }

            if (!empty($shared_info['user_data'])) {
                foreach ($non_allowed as $value4) {
                    unset($shared_info['user_data'][$value4]);
                }
                if (!empty($shared_info['user_data']['avatar']) && !Wo_IsUrl($shared_info['user_data']['avatar'])) {
                    $shared_info['user_data']['avatar'] = Wo_GetMedia($shared_info['user_data']['avatar']);
                }
                if (!empty($shared_info['user_data']['cover']) && !Wo_IsUrl($shared_info['user_data']['cover'])) {
                    $shared_info['user_data']['cover'] = Wo_GetMedia($shared_info['user_data']['cover']);
                }
            } else {
                $shared_info['user_data'] = null;
            }
        }
        $posts[$key]['shared_info'] = $shared_info;
    }
}

$response_data = array(
    'api_status' => 200,
    'api_text' => 'success',
    'data' => $posts,
    'posts' => $posts,
    'stories' => $posts,
    'count' => count($posts)
);
