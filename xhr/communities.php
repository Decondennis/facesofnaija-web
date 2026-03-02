<?php 
if ($f == 'communities') {
    if ($s == 'create_community') {
        // Temporary debug logging: capture create community requests
        try {
            $log = array(
                'time' => date('c'),
                'type' => 'create_community',
                'GET' => $_GET,
                'POST' => $_POST,
                'SERVER' => array('REQUEST_URI' => isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : ''),
                'SESSION_main_hash' => (!empty($_SESSION['main_hash_id']) ? $_SESSION['main_hash_id'] : ''),
                'SESSION_hash_id' => (!empty($_SESSION['hash_id']) ? $_SESSION['hash_id'] : '')
            );
            @file_put_contents(dirname(__FILE__) . '/../php-cli-test/create_community_debug.log', json_encode($log, JSON_UNESCAPED_SLASHES) . PHP_EOL, FILE_APPEND);
        } catch (Exception $e) {
            // ignore logging failures
        }
        // Validate individual fields with specific messages
        $validation_errors = array();
        if (empty($_POST['community_name'])) {
            $label = (!empty($wo['lang']['community_name'])) ? $wo['lang']['community_name'] : 'Community URL';
            $validation_errors[] = $error_icon . ' ' . $label . ' is required.';
        }
        if (empty($_POST['community_title'])) {
            $label = (!empty($wo['lang']['community_title'])) ? $wo['lang']['community_title'] : 'Community title';
            $validation_errors[] = $error_icon . ' ' . $label . ' is required.';
        }
        if (empty(Wo_Secure(@$_POST['community_title']))) {
            $label = (!empty($wo['lang']['community_title_invalid_characters'])) ? $wo['lang']['community_title_invalid_characters'] : 'Community title contains invalid characters';
            $validation_errors[] = $error_icon . ' ' . $label;
        }
        if (Wo_CheckSession($hash_id) === false && Wo_CheckMainSession($hash_id) === false) {
            $label = (!empty($wo['lang']['session_expired'])) ? $wo['lang']['session_expired'] : 'Session expired';
            $validation_errors[] = $error_icon . ' ' . $label;
        }
        if (!empty($validation_errors)) {
            // Log validation details
            try {
                $debug_fail = array(
                    'time' => date('c'),
                    'type' => 'create_community_validation_fail',
                    'validation_errors' => $validation_errors,
                    'POST' => $_POST,
                    'SESSION_main_hash' => (!empty($_SESSION['main_hash_id']) ? $_SESSION['main_hash_id'] : ''),
                    'SESSION_hash_id' => (!empty($_SESSION['hash_id']) ? $_SESSION['hash_id'] : '')
                );
                @file_put_contents(dirname(__FILE__) . '/../php-cli-test/create_community_debug.log', json_encode($debug_fail, JSON_UNESCAPED_SLASHES) . PHP_EOL, FILE_APPEND);
            } catch (Exception $e) {}
            $errors = array_merge((isset($errors) ? $errors : array()), $validation_errors);
        } else {
            $is_exist = Wo_IsNameExist($_POST['community_name'], 0);
            $name_in_site_pages = in_array($_POST['community_name'], $wo['site_pages']);
            $name_length_ok = (strlen($_POST['community_name']) >= 5 && strlen($_POST['community_name']) <= 32);
            $name_pattern_ok = preg_match('/^[\w]+$/', $_POST['community_name']);
            if (in_array(true, $is_exist)) {
                $errors[] = $error_icon . $wo['lang']['community_name_exists'];
            }
            if ($name_in_site_pages) {
                $errors[] = $error_icon . $wo['lang']['community_name_invalid_characters'];
            }
            if (!$name_length_ok) {
                $errors[] = $error_icon . $wo['lang']['community_name_characters_length'];
            }
            if (!$name_pattern_ok) {
                $errors[] = $error_icon . $wo['lang']['community_name_invalid_characters'];
            }
            // debug details for this submission
            $debug_details = array(
                'name' => @$_POST['community_name'],
                'is_exist' => $is_exist,
                'name_in_site_pages' => $name_in_site_pages,
                'name_length_ok' => $name_length_ok,
                'name_pattern_ok' => (bool)$name_pattern_ok
            );
            try {
                @file_put_contents(dirname(__FILE__) . '/../php-cli-test/create_community_debug.log', json_encode(array('time' => date('c'), 'type' => 'create_debug_details', 'details' => $debug_details), JSON_UNESCAPED_SLASHES) . PHP_EOL, FILE_APPEND);
            } catch (Exception $e) {}
            if (empty($_POST['category'])) {
                $_POST['category'] = 1;
            }
        }
        $privacy = 1;
        if (!empty($_POST['privacy'])) {
            if ($_POST['privacy'] == 2) {
                $privacy = 2;
            }
        }
        if (empty($errors)) {
            $sub_category = '';
            if (!empty($_POST['community_sub_category']) && !empty($wo['community_sub_categories'][$_POST['category']])) {
                foreach ($wo['community_sub_categories'][$_POST['category']] as $key => $value) {
                    if ($value['id'] == $_POST['community_sub_category']) {
                        $sub_category = $value['id'];
                    }
                }
            }
            $re_community_data = array(
                'community_name' => Wo_Secure($_POST['community_name']),
                'parent_id' => Wo_Secure($_POST['parent_id']),
                'community_title' => Wo_Secure($_POST['community_title']),
                'about' => Wo_Secure($_POST['about']),
                'category' => Wo_Secure($_POST['category']),
                'sub_category' => $sub_category,
                'privacy' => Wo_Secure($privacy),
                'active' => '1',
                'time' => time()
            );
            if ($privacy == 2) {
                $re_community_data['join_privacy'] = 2;
            }
            $fields = Wo_GetCustomFields('community'); 
            $missing_required = array();
            if (!empty($fields)) {
                foreach ($fields as $key => $field) {
                    if ($field['required'] == 'on' && empty($_POST['fid_'.$field['id']])) {
                        $missing_required[] = $field['name'];
                    }
                    elseif (!empty($_POST['fid_'.$field['id']])) {
                        $re_community_data['fid_'.$field['id']] = Wo_Secure($_POST['fid_'.$field['id']]);
                    }
                }
                if (!empty($missing_required)) {
                    $msg = $error_icon . ' ' . $wo['lang']['please_check_details'] . ' Required fields: ' . implode(', ', $missing_required);
                    $errors[] = $msg;
                    // Log missing fields for debugging
                    try {
                        $dbg = array('time' => date('c'), 'type' => 'create_missing_fields', 'missing' => $missing_required, 'POST' => $_POST);
                        @file_put_contents(dirname(__FILE__) . '/../php-cli-test/create_community_debug.log', json_encode($dbg, JSON_UNESCAPED_SLASHES) . PHP_EOL, FILE_APPEND);
                    } catch (Exception $e) {}
                }
            }

            $register_community = Wo_RegisterCommunity($re_community_data);

            if ($register_community) {
                //if ($privacy == 2) {
                    //$community_id            = Wo_CommunityIdFromCommunityname(Wo_Secure($_POST['community_name']));
                    //$user_id = $wo['user']['id'];
                    //$active = 1;
                    //$query = mysqli_query($sqlConnect, " INSERT INTO " . T_COMMUNITY_MEMBERS . " (`user_id`,`community_id`,`active`,`time`) VALUES ({$user_id},{$community_id},'{$active}'," . time() . ")");

                    //$query = mysqli_query($sqlConnect, " INSERT INTO " . T_COMMUNITY_MODERATORS . " (`user_id`,`community_id`,`members`,`delete_community`) VALUES ({$user_id},{$community_id}, 1,1)");
                //}
                $data = array(
                    'status' => 200,
                    'location' => Wo_SeoLink('index.php?link1=timeline&u=' . Wo_Secure($_POST['community_name']))
                );
            }
        }
        // Normalize empty/icon-only errors to a readable message
        if (isset($errors) && is_array($errors)) {
            foreach ($errors as $ei => $err) {
                if (trim(strip_tags($err)) === '') {
                    $errors[$ei] = $error_icon . ' ' . $wo['lang']['please_check_details'];
                }
            }
        }
        // Prepare response and log it for debugging
        $response = (isset($errors)) ? array('errors' => $errors) : $data;
        // If server returned only the generic please_check_details, augment with concrete checks
        if (!empty($response['errors']) && count($response['errors']) == 1) {
            $only = trim(strip_tags($response['errors'][0]));
            if ($only == trim($wo['lang']['please_check_details']) || $only == '') {
                $aug = array();
                // check community name
                $cname = isset($_POST['community_name']) ? $_POST['community_name'] : '';
                if (empty($cname)) {
                    $aug[] = $error_icon . ' Community URL is required.';
                } else {
                    if (strlen($cname) < 5 || strlen($cname) > 32) {
                        $aug[] = $error_icon . ' Community URL must be between 5 and 32 characters.';
                    }
                    if (!preg_match('/^[\w]+$/', $cname)) {
                        $aug[] = $error_icon . ' Community URL contains invalid characters.';
                    }
                    $is_exist = Wo_IsNameExist($cname, 0);
                    if (in_array(true, (array)$is_exist)) {
                        $aug[] = $error_icon . ' Community URL already exists.';
                    }
                }
                // check title
                $ctitle = isset($_POST['community_title']) ? trim($_POST['community_title']) : '';
                if (empty($ctitle)) {
                    $aug[] = $error_icon . ' Community title is required.';
                }
                // check custom required fields
                $fields = Wo_GetCustomFields('community');
                if (!empty($fields)) {
                    $missing = array();
                    foreach ($fields as $f) {
                        if ($f['required'] == 'on' && empty($_POST['fid_'.$f['id']])) {
                            $missing[] = $f['name'];
                        }
                    }
                    if (!empty($missing)) {
                        $aug[] = $error_icon . ' Required custom fields missing: ' . implode(', ', $missing);
                    }
                }
                if (!empty($aug)) {
                    $response['errors'] = $aug;
                }
            }
        }
        try {
            $log_response = array(
                'time' => date('c'),
                'type' => 'create_community_response',
                'response' => $response
            );
            @file_put_contents(dirname(__FILE__) . '/../php-cli-test/create_community_debug.log', json_encode($log_response, JSON_UNESCAPED_SLASHES) . PHP_EOL, FILE_APPEND);
        } catch (Exception $e) {
            // ignore
        }
        header("Content-type: application/json");
        echo json_encode($response);
        exit();
    }
    if ($s == 'request_community') {
        // Temporary debug logging: capture community request submissions
        try {
            $log = array(
                'time' => date('c'),
                'type' => 'request_community',
                'GET' => $_GET,
                'POST' => $_POST,
                'SERVER' => array('REQUEST_URI' => isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : ''),
                'SESSION_main_hash' => (!empty($_SESSION['main_hash_id']) ? $_SESSION['main_hash_id'] : ''),
                'SESSION_hash_id' => (!empty($_SESSION['hash_id']) ? $_SESSION['hash_id'] : '')
            );
            @file_put_contents(dirname(__FILE__) . '/../php-cli-test/create_community_debug.log', json_encode($log, JSON_UNESCAPED_SLASHES) . PHP_EOL, FILE_APPEND);
        } catch (Exception $e) {
            // ignore logging failures
        }
        $data = array();
        if (Wo_IsAdmin() || Wo_IsModerator()) {
            $errors[] = $error_icon . 'Admins can create communities directly. Please use the Create Community button.';
        }
        elseif (empty($_POST['community_name']) || empty($_POST['community_title']) || empty($_POST['reason']) || (Wo_CheckSession($hash_id) === false && Wo_CheckMainSession($hash_id) === false)) {
            $errors[] = $error_icon . $wo['lang']['please_check_details'];
        } else {
            $is_exist = Wo_IsNameExist($_POST['community_name'], 0);
            if (in_array(true, $is_exist)) {
                $errors[] = $error_icon . $wo['lang']['community_name_exists'];
            }
            if (in_array($_POST['community_name'], $wo['site_pages'])) {
                $errors[] = $error_icon . $wo['lang']['community_name_invalid_characters'];
            }
            if (strlen($_POST['community_name']) < 5 OR strlen($_POST['community_name']) > 32) {
                $errors[] = $error_icon . $wo['lang']['community_name_characters_length'];
            }
            if (!preg_match('/^[\w]+$/', $_POST['community_name'])) {
                $errors[] = $error_icon . $wo['lang']['community_name_invalid_characters'];
            }
            if (empty($_POST['category'])) {
                $_POST['category'] = 1;
            }
        }
        $privacy = 1;
        if (!empty($_POST['privacy'])) {
            if ($_POST['privacy'] == 2) {
                $privacy = 2;
            }
        }
        if (empty($errors)) {
            $sub_category = '';
            if (!empty($_POST['community_sub_category']) && !empty($wo['community_sub_categories'][$_POST['category']])) {
                foreach ($wo['community_sub_categories'][$_POST['category']] as $key => $value) {
                    if ($value['id'] == $_POST['community_sub_category']) {
                        $sub_category = $value['id'];
                    }
                }
            }

            // Insert request into database
            $insert_data = array(
                'user_id' => $wo['user']['user_id'],
                'community_name' => Wo_Secure($_POST['community_name']),
                'community_title' => Wo_Secure($_POST['community_title']),
                'about' => Wo_Secure($_POST['about']),
                'category' => Wo_Secure($_POST['category']),
                'sub_category' => $sub_category,
                'privacy' => Wo_Secure($privacy),
                'reason' => Wo_Secure($_POST['reason']),
                'status' => 'pending',
                'time' => time()
            );

            $fields_str = '`' . implode('`, `', array_keys($insert_data)) . '`';
            $values_str = '\'' . implode('\', \'', $insert_data) . '\'';

            $query = mysqli_query($sqlConnect, "INSERT INTO Wo_Community_Requests ({$fields_str}) VALUES ({$values_str})");

            if ($query) {
                $data = array(
                    'status' => 200,
                    'message' => $success_icon . 'Your community creation request has been submitted successfully! An administrator will review it soon.'
                );
            } else {
                $errors[] = $error_icon . 'Failed to submit request. Please try again.';
            }
        }
        // Normalize empty/icon-only errors to a readable message
        if (isset($errors) && is_array($errors)) {
            foreach ($errors as $ei => $err) {
                if (trim(strip_tags($err)) === '') {
                    $errors[$ei] = $error_icon . ' ' . $wo['lang']['please_check_details'];
                }
            }
        }
        // Prepare response and log it for debugging
        $response = (isset($errors)) ? array('errors' => $errors) : $data;
        try {
            $log_response = array(
                'time' => date('c'),
                'type' => 'request_community_response',
                'response' => $response
            );
            @file_put_contents(dirname(__FILE__) . '/../php-cli-test/create_community_debug.log', json_encode($log_response, JSON_UNESCAPED_SLASHES) . PHP_EOL, FILE_APPEND);
        } catch (Exception $e) {
            // ignore
        }
        header("Content-type: application/json");
        echo json_encode($response);
        exit();
    }
    if ($s == 'update_information_setting') {
        if (!empty($_POST['page_id']) && is_numeric($_POST['page_id']) && $_POST['page_id'] > 0) {
            $PageData = Wo_PageData($_POST['page_id']);
            if (!empty($_POST['website'])) {
                if (!filter_var($_POST['website'], FILTER_VALIDATE_URL)) {
                    $errors[] = $error_icon . $wo['lang']['website_invalid_characters'];
                }
            }
            if (empty($errors)) {
                $Update_data = array(
                    'website' => $_POST['website'],
                    'page_description' => $_POST['page_description'],
                    'company' => $_POST['company'],
                    'address' => $_POST['address'],
                    'phone' => $_POST['phone']
                );
                if (Wo_UpdatePageData($_POST['page_id'], $Update_data)) {
                    $data = array(
                        'status' => 200,
                        'message' => $success_icon . $wo['lang']['setting_updated']
                    );
                }
            }
        }
        header("Content-type: application/json");
        if (isset($errors)) {
            echo json_encode(array(
                'errors' => $errors
            ));
        } else {
            echo json_encode($data);
        }
        exit();
    }
    if ($s == 'update_privacy_setting') {
        if (!empty($_POST['community_id']) && is_numeric($_POST['community_id']) && $_POST['community_id'] > 0 && Wo_CheckSession($hash_id) === true) {
            $community_data     = Wo_CommunityData($_POST['community_id']);
            $privacy      = 1;
            $join_privacy = 1;
            $array        = array(
                1,
                2
            );
            if (!empty($_POST['privacy'])) {
                if (in_array($_POST['privacy'], $array)) {
                    $privacy = $_POST['privacy'];
                }
            }
            if (!empty($_POST['join_privacy'])) {
                if (in_array($_POST['join_privacy'], $array)) {
                    $join_privacy = $_POST['join_privacy'];
                }
            }
            if ($community_data['user_id'] == $wo['user']['id'] || Wo_IsCanCommunityUpdate($_POST['community_id'],'privacy')) {
                if (empty($errors)) {
                    $Update_data = array(
                        'privacy' => $privacy,
                        'join_privacy' => $join_privacy
                    );
                    if (Wo_UpdateCommunityData($_POST['community_id'], $Update_data)) {
                        $data = array(
                            'status' => 200,
                            'message' => $success_icon . $wo['lang']['setting_updated']
                        );
                    }
                }
            }
            else{
                $errors[] = $error_icon . $wo['lang']['please_check_details'];
            }
        }
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }
    if ($s == 'update_images_setting') {
        if (isset($_POST['community_id']) && is_numeric($_POST['community_id']) && $_POST['community_id'] > 0 && Wo_CheckSession($hash_id) === true) {
            $Userdata = Wo_CommunityData($_POST['community_id']);
            if (!empty($Userdata['id'])) {
                if (!empty($_FILES['avatar']['name'])) {
                    if (Wo_UploadImage($_FILES["avatar"]["tmp_name"], $_FILES['avatar']['name'], 'avatar', $_FILES['avatar']['type'], $_POST['community_id'], 'community') === true) {
                        $page_data = Wo_CommunityData($_POST['community_id']);
                    }
                }
                if (!empty($_FILES['cover']['name'])) {
                    if (Wo_UploadImage($_FILES["cover"]["tmp_name"], $_FILES['cover']['name'], 'cover', $_FILES['cover']['type'], $_POST['community_id'], 'community') === true) {
                        $page_data = Wo_CommunityData($_POST['community_id']);
                    }
                }
                if ($Userdata['user_id'] == $wo['user']['id'] || Wo_IsCanCommunityUpdate($_POST['community_id'],'avatar')) {
                    if (empty($errors)) {
                        $Update_data = array(
                            'active' => '1'
                        );
                        if (Wo_UpdateCommunityData($_POST['community_id'], $Update_data)) {
                            $userdata2 = Wo_CommunityData($_POST['community_id']);
                            $data      = array(
                                'status' => 200,
                                'message' => $success_icon . $wo['lang']['setting_updated'],
                                'cover' => $userdata2['cover'],
                                'avatar' => $userdata2['avatar']
                            );
                        }
                    }
                }
                else{
                    $errors[] = $error_icon . $wo['lang']['please_check_details'];
                }
            }
        }
        header("Content-type: application/json");
        if (isset($errors)) {
            echo json_encode(array(
                'errors' => $errors
            ));
        } else {
            echo json_encode($data);
        }
    }
    if ($s == 'update_general_settings') {
        if (!empty($_POST['community_id']) && is_numeric($_POST['community_id']) && $_POST['community_id'] > 0 && Wo_CheckSession($hash_id) === true) {
            $community_data = Wo_CommunityData($_POST['community_id']);
            if (empty($_POST['community_name']) OR empty($_POST['community_category']) OR empty($_POST['community_title']) OR empty(Wo_Secure($_POST['community_title']))) {
                $errors[] = $error_icon . $wo['lang']['please_check_details'];
            } else {
                if ($_POST['community_name'] != $community_data['community_name']) {
                    $is_exist = Wo_IsNameExist($_POST['community_name'], 0);
                    if (in_array(true, $is_exist)) {
                        $errors[] = $error_icon . $wo['lang']['community_name_exists'];
                    }
                }
                if (in_array($_POST['community_name'], $wo['site_pages'])) {
                    $errors[] = $error_icon . $wo['lang']['community_name_invalid_characters'];
                }
                if (strlen($_POST['community_name']) < 5 || strlen($_POST['community_name']) > 32) {
                    $errors[] = $error_icon . $wo['lang']['community_name_characters_length'];
                }
                if (!preg_match('/^[\w]+$/', $_POST['community_name'])) {
                    $errors[] = $error_icon . $wo['lang']['community_name_invalid_characters'];
                }
                if (empty($_POST['community_category'])) {
                    $_POST['community_category'] = 1;
                }
                //if ($community_data['user_id'] == $wo['user']['id'] || Wo_IsCanCommunityUpdate($_POST['community_id'],'general')) {
                    if (empty($errors)) {
                        $sub_category = '';
                        if (!empty($_POST['community_sub_category']) && !empty($wo['community_sub_categories'][$_POST['community_category']])) {
                            foreach ($wo['community_sub_categories'][$_POST['community_category']] as $key => $value) {
                                if ($value['id'] == $_POST['community_sub_category']) {
                                    $sub_category = $value['id'];
                                }
                            }
                        }
                        $Update_data = array(
                            'community_name' => $_POST['community_name'],
                            'community_title' => $_POST['community_title'],
                            'category' => $_POST['community_category'],
                            'sub_category' => $sub_category,
                            'about' => $_POST['about']
                        );

                        $fields = Wo_GetCustomFields('community'); 
                        if (!empty($fields)) {
                            foreach ($fields as $key => $field) {
                                if ($field['required'] == 'on' && empty($_POST['fid_'.$field['id']])) {
                                    $errors[] = $error_icon . $wo['lang']['please_check_details'];
                                    header("Content-type: application/json");
                                    echo json_encode(array(
                                        'errors' => $errors
                                    ));
                                    exit();
                                }
                                elseif (!empty($_POST['fid_'.$field['id']])) {
                                    $Update_data['fid_'.$field['id']] = Wo_Secure($_POST['fid_'.$field['id']]);
                                }
                            }
                        }

                        if (Wo_UpdateCommunityData($_POST['community_id'], $Update_data)) {
                            $data = array(
                                'status' => 200,
                                'message' => $success_icon . $wo['lang']['setting_updated']
                            );

                            Wo_AddCommunityModerator($wo['user']['id'], $_POST['community_id']);
                        }
                    }
                /*}
                else{
                    $errors[] = $error_icon . $wo['lang']['please_check_details'];
                }*/
            }
        }
        header("Content-type: application/json");
        if (isset($errors)) {
            echo json_encode(array(
                'errors' => $errors
            ));
        } else {
            echo json_encode($data);
        }
        exit();
    }
    if ($s == 'delete_community') {
        if (!empty($_POST['community_id']) && is_numeric($_POST['community_id']) && $_POST['community_id'] > 0 && Wo_CheckSession($hash_id) === true) {
            if (!Wo_HashPassword($_POST['password'], $wo['user']['password']) && !Wo_CheckCommunityModeratorPassword($_POST['password'], $_POST['community_id'])) {
                $errors[] = $error_icon . $wo['lang']['current_password_mismatch'];
            }
            $community_data = Wo_CommunityData($_POST['community_id']);
            if ($community_data['user_id'] == $wo['user']['id'] || Wo_IsCanCommunityUpdate($_POST['community_id'],'delete_community')) {

                if (empty($errors)) {
                    if (Wo_DeleteCommunity($_POST['community_id']) === true) {
                        $data = array(
                            'status' => 200,
                            'message' => $success_icon . $wo['lang']['community_deleted'],
                            'location' => Wo_SeoLink('index.php?link1=comminities')
                        );
                    }
                }
            }
            else{
                $errors[] = $error_icon . $wo['lang']['please_check_details'];
            }
        }
        header("Content-type: application/json");
        if (isset($errors)) {
            echo json_encode(array(
                'errors' => $errors
            ));
        } else {
            echo json_encode($data);
        }
        exit();
    }
    if ($s == 'accept_request') {
        if (isset($_GET['user_id']) && is_numeric($_GET['user_id']) && $_GET['user_id'] > 0 && !empty($_GET['community_id']) && is_numeric($_GET['community_id']) && $_GET['community_id'] > 0) {
            if (Wo_AcceptJoinCommunityRequest($_GET['user_id'], $_GET['community_id']) === true) {
                $data = array(
                    'status' => 200
                );
            }
        }
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }
    if ($s == 'delete_request') {
        if (isset($_GET['user_id']) && is_numeric($_GET['user_id']) && $_GET['user_id'] > 0 && !empty($_GET['community_id']) && is_numeric($_GET['community_id']) && $_GET['community_id'] > 0) {
            
        if (Wo_DeleteJoinCommunityRequest($_GET['user_id'], $_GET['community_id']) === true) {
                $data = array(
                    'status' => 200
                );
            }
        }
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }
    if ($s == 'delete_joined_user') {
        if (isset($_GET['user_id']) && is_numeric($_GET['user_id']) && $_GET['user_id'] > 0 && !empty($_GET['community_id']) && is_numeric($_GET['community_id']) && $_GET['community_id'] > 0) {
            $community_data = Wo_CommunityData($_GET['community_id']);
            if ($community_data['user_id'] == $wo['user']['id'] || Wo_IsCanCommunityUpdate($_GET['community_id'],'members')) {
                if (Wo_LeaveCommunity($_GET['community_id'], $_GET['user_id']) === true) {
                    $data = array(
                        'status' => 200
                    );
                }
            }
        }
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }
    if ($s == 'add_moderator') {
        if (isset($_GET['user_id']) && is_numeric($_GET['user_id']) && $_GET['user_id'] > 0 && !empty($_GET['community_id']) && is_numeric($_GET['community_id']) && $_GET['community_id'] > 0) {
            $community_data = Wo_CommunityData($_GET['community_id']);
            //var_dump($community_data);
            if (Wo_IsCanCommunityUpdate($_GET['community_id'],'members')) {

                $member = Wo_Secure($_GET['user_id']);
                $community  = Wo_Secure($_GET['community_id']);
                $data   = array(
                    'status' => 304
                );
                $code   = Wo_AddCommunityModerator($member, $community);
                if ($code === 1) {
                    $data['status'] = 200;
                    $data['code']   = 1;
                } elseif ($code === 0) {
                    $data['status'] = 200;
                    $data['code']   = 0;
                }
            }
        }
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }
    if ($s == 'privileges') {
        if (!empty($_POST['community_id']) && is_numeric($_POST['community_id']) && $_POST['community_id'] > 0 && !empty($_POST['user_id']) && is_numeric($_POST['user_id']) && $_POST['user_id'] > 0) {
            $community_data = Wo_CommunityData($_POST['community_id']);
            if ($community_data['user_id'] == $wo['user']['id'] || Wo_IsCanCommunityUpdate($_POST['community_id'],'members')) {

                $update_array = array('general' => 0 , 'privacy' => 0 , 'avatar' => 0 , 'members' => 0 , 'analytics' => 0 ,'delete_community' => 0);
                if (!empty($_POST['general']) && $_POST['general'] == 1) {
                    $update_array['general'] = 1;
                }
                if (!empty($_POST['privacy']) && $_POST['privacy'] == 1) {
                    $update_array['privacy'] = 1;
                }
                if (!empty($_POST['avatar']) && $_POST['avatar'] == 1) {
                    $update_array['avatar'] = 1;
                }
                if (!empty($_POST['members']) && $_POST['members'] == 1) {
                    $update_array['members'] = 1;
                }
                if (!empty($_POST['analytics']) && $_POST['analytics'] == 1) {
                    $update_array['analytics'] = 1;
                }
                if (!empty($_POST['delete_community']) && $_POST['delete_community'] == 1) {
                    $update_array['delete_community'] = 1;
                }

                if (Wo_UpdateCommunityModeratorData($_POST['community_id'], $update_array,$_POST['user_id'])) {
                    $data = array(
                        'status' => 200,
                        'message' => $success_icon . $wo['lang']['setting_updated']
                    );
                }
            }
            else{
                $errors[] = $error_icon . $wo['lang']['please_check_details'];
            }
        }
        else{
            $errors[] = $error_icon . $wo['lang']['please_check_details'];
        }
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }
}
