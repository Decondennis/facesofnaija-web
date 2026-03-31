<?php 
if ($f == 'update_community_avatar_picture') {
    $data = array('status' => 400, 'message' => 'Invalid request');
    if (isset($_FILES['avatar']['name']) && !empty($_POST['community_id']) && is_numeric($_POST['community_id']) && $_POST['community_id'] > 0) {
        $can_update_community_media = Wo_IsCanCommunityUpdate($_POST['community_id'], 'avatar') || Wo_IsCommunityUserExists($wo['user']['user_id'], $_POST['community_id']);
        if ($can_update_community_media) {
            if (Wo_UploadImage($_FILES["avatar"]["tmp_name"], $_FILES['avatar']['name'], 'avatar', $_FILES['avatar']['type'], $_POST['community_id'], 'community')) {
                $img  = Wo_CommunityData($_POST['community_id']);
                $data = array(
                    'status' => 200,
                    'img' => $img['avatar']
                );
            }
        }
    }
    header("Content-type: application/json");
    echo json_encode($data);
    exit();
}
