<?php 
if ($f == 'register_community_add') {
    if (!empty($_GET['user_id']) && !empty($_GET['community_id'])) {
        $register_add = Wo_RegsiterCommunityAdd($_GET['user_id'], $_GET['community_id']);
        if ($register_add === true) {
            $data = array(
                'status' => 200
            );
        }
    }
    header("Content-type: application/json");
    echo json_encode($data);
    exit();
}
