<?php 
if ($f == "update_images_setting") {
    $errors = array();
    $data   = array(
        'status' => 400,
        'errors' => array($error_icon . $wo['lang']['please_check_details'])
    );
    if (isset($_POST['user_id']) && is_numeric($_POST['user_id']) && $_POST['user_id'] > 0 && Wo_CheckSession($hash_id) === true) {
        $Userdata = Wo_UserData($_POST['user_id']);
        if (!empty($Userdata['user_id'])) {
            $has_upload = false;
            if (isset($_FILES['avatar']['name']) && !empty($_FILES['avatar']['name'])) {
                $has_upload = true;
                $avatar_upload = Wo_UploadImage($_FILES["avatar"]["tmp_name"], $_FILES['avatar']['name'], 'avatar', $_FILES['avatar']['type'], $_POST['user_id']);
                if ($avatar_upload === true) {
                    $Userdata = Wo_UserData($_POST['user_id']);
                }
                else if (is_array($avatar_upload) && !empty($avatar_upload['invalid_file'])) {
                    $data = $avatar_upload;
                }
                else {
                    $errors[] = $error_icon . $wo['lang']['please_check_details'];
                }
            }
            if (isset($_FILES['cover']['name']) && !empty($_FILES['cover']['name'])) {
                $has_upload = true;
                $cover_upload = Wo_UploadImage($_FILES["cover"]["tmp_name"], $_FILES['cover']['name'], 'cover', $_FILES['cover']['type'], $_POST['user_id']);
                if ($cover_upload === true) {
                    $Userdata = Wo_UserData($_POST['user_id']);
                }
                else if (is_array($cover_upload) && !empty($cover_upload['invalid_file'])) {
                    $data = $cover_upload;
                }
                else {
                    $errors[] = $error_icon . $wo['lang']['please_check_details'];
                }
            }
            if ($has_upload === false) {
                $errors[] = $error_icon . $wo['lang']['please_upload_image'];
            }
            if (empty($errors) && empty($data['invalid_file'])) {
                $Update_data = array(
                    'lastseen' => time()
                );
                if (Wo_UpdateUserData($_POST['user_id'], $Update_data)) {
                    $userdata2 = Wo_UserData($_POST['user_id']);
                    $data      = array(
                        'status' => 200,
                        'message' => $success_icon . $wo['lang']['setting_updated'],
                        'cover' => $userdata2['cover'],
                        'avatar' => $userdata2['avatar']
                    );
                }
            }
        }
    }
    header("Content-type: application/json");
    if (isset($errors)) {
        if (!empty($errors)) {
            echo json_encode(array(
                'status' => 400,
                'errors' => $errors
            ));
        }
        else {
            echo json_encode($data);
        }
    } else {
        echo json_encode($data);
    }
    exit();
}
