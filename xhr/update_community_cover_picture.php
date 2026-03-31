<?php 
if ($f == 'update_community_cover_picture') {
    $data = array('status' => 400, 'message' => 'Invalid request');
    if (isset($_FILES['cover']['name']) && !empty($_POST['community_id']) && is_numeric($_POST['community_id']) && $_POST['community_id'] > 0) {
        $can_update_community_media = Wo_IsCanCommunityUpdate($_POST['community_id'], 'avatar') || Wo_IsCommunityUserExists($wo['user']['user_id'], $_POST['community_id']);
        if ($can_update_community_media) {
            if (Wo_UploadImage($_FILES["cover"]["tmp_name"], $_FILES['cover']['name'], 'cover', $_FILES['cover']['type'], $_POST['community_id'], 'community')) {
                $img  = Wo_CommunityData($_POST['community_id']);
                $data = array(
                    'status' => 200,
                    'img' => $img['cover']
                );
            }
        }
    }
    header("Content-type: application/json");
    echo json_encode($data);
    exit();
}
