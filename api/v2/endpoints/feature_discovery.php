<?php
// +------------------------------------------------------------------------+
// | API v2 Endpoint: Feature Discovery
// | Returns or updates progressive feature discovery for the authenticated user
// +------------------------------------------------------------------------+

$response_data = array(
    'api_status' => 400
);

if (empty($wo['loggedin']) || empty($wo['user']['user_id'])) {
    $error_code = 2;
    $error_message = 'Not authorized';
} else {
    $user_id = (int)$wo['user']['user_id'];
    $action = '';
    if (!empty($_POST['action'])) {
        $action = Wo_Secure($_POST['action']);
    } elseif (!empty($_GET['action'])) {
        $action = Wo_Secure($_GET['action']);
    }

    if ($action == 'acknowledge' || $action == 'ack') {
        $intro_id = 0;
        if (isset($_POST['intro_id'])) {
            $intro_id = (int)$_POST['intro_id'];
        } elseif (isset($_GET['intro_id'])) {
            $intro_id = (int)$_GET['intro_id'];
        }

        $login_id = '';
        if (!empty($_POST['login_id'])) {
            $login_id = Wo_Secure($_POST['login_id']);
        } elseif (!empty($_GET['login_id'])) {
            $login_id = Wo_Secure($_GET['login_id']);
        }

        if ($intro_id >= 1 && $intro_id <= 5) {
            $status = Wo_AcknowledgeFeatureDiscovery($user_id, $intro_id, $login_id);
            $response_data = array_merge(array('api_status' => 200), $status);
        } else {
            $error_code = 4;
            $error_message = 'Invalid intro_id (must be 1-5)';
        }
    } else {
        // Default action: retrieve current discovery status
        $status = Wo_GetFeatureDiscoveryStatus($user_id);
        $response_data = array_merge(array('api_status' => 200), $status);
    }
}
