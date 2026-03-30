<?php 
if ($f == 'get_user_profile_cover_image_post') {
    $data = array('status' => 400, 'message' => 'Image not found');
    if (!empty($_POST['image'])) {
        $getUserImage = Wo_GetUserProfilePicture(Wo_Secure($_POST['image'], 0));
        if (!empty($getUserImage)) {
            $data = array(
                'status' => 200,
                'post_id' => $getUserImage
            );
        }
    }
    header("Content-type: application/json");
    echo json_encode($data);
    exit();
}
