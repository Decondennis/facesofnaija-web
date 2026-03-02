<?php 

if ($f == 'join_community') {
    // Log join attempts for debugging Suggested Communities join failures
    $log_path = dirname(__DIR__) . '/php-cli-test/join_debug.log';
    $log_entry = "[" . date('c') . "] JOIN_ATTEMPT GET=" . json_encode($_GET) . " POST=" . json_encode($_POST) . " SESSION_main_hash=" . (isset($_SESSION['main_hash_id']) ? $_SESSION['main_hash_id'] : 'null') . "\n";
    @file_put_contents($log_path, $log_entry, FILE_APPEND);

    if (isset($_GET['community_id']) && Wo_CheckMainSession($hash_id) === true) {
        $community_id = Wo_Secure($_GET['community_id']);
        if (Wo_IsCommunityJoined($community_id) === true || Wo_IsCommunityJoinRequested($community_id, $wo['user']['user_id']) === true) {
            if (Wo_LeaveCommunity($community_id, $wo['user']['user_id'])) {
                $data = array(
                    'status' => 200,
                    'html' => Wo_GetCommunityJoinButton($community_id)
                );
            }
        } else {
            if (Wo_RegisterCommunityJoin($community_id, $wo['user']['user_id'])) {
                $data = array(
                    'status' => 200,
                    'html' => Wo_GetCommunityJoinButton($community_id)
                );
                if (Wo_CanSenEmails()) {
                    $data['can_send'] = 1;
                }
            }
        }
    }
    if ($wo['loggedin'] == true) {
        Wo_CleanCache();
    }
    header("Content-type: application/json");
    echo json_encode($data);
    exit();
}
