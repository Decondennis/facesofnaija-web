<?php
// +------------------------------------------------------------------------+
// | XHR Endpoint: Feature Discovery
// | Handles Web AJAX requests for progressive feature discovery
// +------------------------------------------------------------------------+

if ($f == 'feature_discovery') {
    $data = array('status' => 400);

    if ($wo['loggedin'] == false || empty($wo['user']['user_id'])) {
        $data = array('status' => 401, 'message' => 'Please login to continue.');
    } else {
        $user_id = (int)$wo['user']['user_id'];
        $action = isset($_REQUEST['action']) ? Wo_Secure($_REQUEST['action']) : 'get';

        if ($action == 'ack' || $action == 'acknowledge') {
            $intro_id = isset($_REQUEST['intro_id']) ? (int)$_REQUEST['intro_id'] : 0;
            $login_id = isset($_REQUEST['login_id']) ? Wo_Secure($_REQUEST['login_id']) : '';

            if ($intro_id >= 1 && $intro_id <= 5) {
                $res = Wo_AcknowledgeFeatureDiscovery($user_id, $intro_id, $login_id);
                $data = array_merge(array('status' => 200), $res);
            } else {
                $data = array('status' => 400, 'message' => 'Invalid intro_id');
            }
        } else {
            $res = Wo_GetFeatureDiscoveryStatus($user_id);
            $data = array_merge(array('status' => 200), $res);
        }
    }

    header("Content-type: application/json");
    echo json_encode($data);
    exit();
}
