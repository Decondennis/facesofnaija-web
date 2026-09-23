<?php
// +------------------------------------------------------------------------+
// | @author Deen Doughouz (DoughouzForest)
// | @author_url 1: http://www.wowonder.com
// | @author_url 2: http://codecanyon.net/user/doughouzforest
// | @author_email: wowondersocial@gmail.com   
// +------------------------------------------------------------------------+
// | WoWonder - The Ultimate Social Networking Platform
// | Copyright (c) 2016 WoWonder. All rights reserved.
// +------------------------------------------------------------------------+
$json_error_data     = array();
$json_success_data   = array();
$follow_data = array();
$type                = Wo_Secure($_GET['type'], 0);
if ($type == 'reset_pass') {
    if (empty($_POST['email'])) {
        $json_error_data = array(
            'api_status' => '400',
            'api_text' => 'failed',
            'api_version' => $api_version,
            'errors' => array(
                'error_id' => '3',
                'error_text' => 'No email address sent.'
            )
        );
    } else if (Wo_EmailExists($_POST['email']) === false) {
        $json_error_data = array(
            'api_status' => '400',
            'api_text' => 'failed',
            'api_version' => $api_version,
            'errors' => array(
                'error_id' => '5',
                'error_text' => 'E-mail does not exist.'
            )
        );
    } 
    if (empty($json_error_data)) {
        $user_id                   = Wo_UserIdFromEmail($_POST['email']);
        $user_recover_data         = Wo_UserData($user_id);
        $subject                   = $wo['config']['siteName'] . ' ' . (isset($wo['lang']['password_rest_request']) ? $wo['lang']['password_rest_request'] : 'Password Reset Request');
        $code                      = md5(rand(111, 999) . uniqid() . time());
        $user_recover_data['link'] = Wo_Link('index.php?link1=reset-password&code=' . $user_recover_data['user_id'] . '_' . $code);
        $time                      = time() + (60 * 60 * 12);
        mysqli_query($sqlConnect, "UPDATE " . T_USERS . " SET `email_code` = '{$code}', `time_code_sent` = '{$time}' WHERE `user_id` = {$user_recover_data['user_id']}");
        $wo['recover']             = $user_recover_data;
        $body                      = Wo_LoadPage('emails/recover');
        $body                      = str_replace(array('\r\n', '\r', '\n', "\r\n", "\r", "\n"), '', $body);
        $send_message_data         = array(
            'from_email' => $wo['config']['siteEmail'],
            'from_name' => $wo['config']['siteName'],
            'to_email' => $_POST['email'],
            'to_name' => $user_recover_data['username'],
            'subject' => $subject,
            'charSet' => 'utf-8',
            'message_body' => $body,
            'is_html' => true
        );
        $send                      = Wo_SendMessage($send_message_data);
        if ($send) {
            $json_success_data22 = array(
                'api_status' => '200',
                'api_text' => 'success',
                'api_version' => $api_version,
                'message' => 'Password reset email sent successfully.'
            );
            header("Content-type: application/json");
            echo json_encode($json_success_data22);
            exit();
        } else {
            $json_error_data = array(
                'api_status' => '400',
                'api_text' => 'failed',
                'api_version' => $api_version,
                'errors' => array(
                    'error_id' => '7',
                    'error_text' => 'Failed to send password reset email. Please check server mail settings.'
                )
            );
            header("Content-type: application/json");
            echo json_encode($json_error_data, JSON_PRETTY_PRINT);
            exit();
        }
    } else {
        header("Content-type: application/json");
        echo json_encode($json_error_data, JSON_PRETTY_PRINT);
        exit();
    }
}
?>
