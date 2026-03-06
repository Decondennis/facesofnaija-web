<?php
require_once('assets/init.php');

// Ensure $wo is an array to avoid stdClass vs array access issues and null offsets
if (!isset($wo) || $wo === null) {
    $wo = array();
} elseif (is_object($wo)) {
    $wo = json_decode(json_encode($wo), true);
}
// Provide safe defaults used throughout this router
$wo['config'] = $wo['config'] ?? array();
$wo['loggedin'] = $wo['loggedin'] ?? false;
$wo['user'] = $wo['user'] ?? array('banned' => 0);

// Temporary debug: log incoming requests and fatal errors to requests_debug.log
@ini_set('display_errors', 1);
error_reporting(E_ALL);
$__debug_file = __DIR__ . '/requests_debug.log';
file_put_contents($__debug_file, "\n----\n".date('c')." REQUEST: " . $_SERVER['REQUEST_METHOD'] . " " . $_SERVER['REQUEST_URI'] . "\nGET: " . print_r($_GET, true) . "\nPOST: " . print_r($_POST, true) . "\nCOOKIE: " . print_r($_COOKIE, true) . "\nSERVER: " . print_r(array('HTTP_HOST'=>$_SERVER['HTTP_HOST'],'HTTP_REFERER'=>($_SERVER['HTTP_REFERER']??''),'HTTP_USER_AGENT'=>($_SERVER['HTTP_USER_AGENT']??''),'HTTP_X_REQUESTED_WITH'=>($_SERVER['HTTP_X_REQUESTED_WITH']??'')), true) . "\n", FILE_APPEND);
set_exception_handler(function($e) use ($__debug_file) {
    file_put_contents($__debug_file, "EXCEPTION: " . $e->getMessage() . "\n" . $e->getTraceAsString() . "\n", FILE_APPEND);
});
// Convert PHP errors/warnings/notices into exceptions so we capture stack traces
set_error_handler(function($errno, $errstr, $errfile, $errline) use ($__debug_file) {
    $ex = new ErrorException($errstr, 0, $errno, $errfile, $errline);
    file_put_contents($__debug_file, "ERROR_CONVERTED: " . $errstr . " in " . $errfile . ":" . $errline . "\n" . $ex->getTraceAsString() . "\n", FILE_APPEND);
    throw $ex;
});
register_shutdown_function(function() use ($__debug_file) {
    $err = error_get_last();
    if ($err) {
        file_put_contents($__debug_file, "FATAL: " . print_r($err, true) . "\n", FILE_APPEND);
    }
});
$f = '';
$s = '';
if (isset($_GET['f'])) {
    $f = Wo_Secure($_GET['f'], 0);
}
if (isset($_GET['s'])) {
    $s = Wo_Secure($_GET['s'], 0);
}
$hash_id = '';
if (!empty($_POST['hash_id'])) {
    $hash_id = $_POST['hash_id'];
} else if (!empty($_GET['hash_id'])) {
    $hash_id = $_GET['hash_id'];
} else if (!empty($_GET['hash'])) {
    $hash_id = $_GET['hash'];
} else if (!empty($_POST['hash'])) {
    $hash_id = $_POST['hash'];
}
$data            = array();
$allow_array     = array(
    'upgrade',
    'paystack',
    'cashfree',
    'payment',
    'pay_with_bitcoin',
    'coinpayments_callback',
    'paypro_with_bitcoin',
    'upload-blog-image',
    'wallet',
    'download_user_info',
    'movies',
    'funding',
    'stripe',
    'coinbase',
    'load_more_products',
    'yoomoney',
    'debug_admin_session',
    'iyzipay',
);
if ($f == 'certification' && $s == 'download_user_certification' && !empty($_GET['id']) && is_numeric($_GET['id']) && $_GET['id'] > 0) {
    $allow_array[] = 'certification';
}
$non_login_array = array(
    'session_status',
    'open_lightbox',
    'get_welcome_users',
    'load_posts',
    'save_user_location',
    'load-more-groups',
    'load-more-communities', //added this
    'load-more-pages',
    'load-more-users',
    'load_profile_posts',
    'confirm_user_unusal_login',
    'confirm_user',
    'confirm_sms_user',
    'resned_code',
    'resned_code_ac',
    'resned_ac_email',
    'contact_us',
    'google_login',
    'login',
    'register',
    'recover',
    'recoversms',
    'reset_password',
    'search',
    'get_search_filter',
    'update_announcement_views',
    'get_more_hashtag_posts',
    'open_album_lightbox',
    'get_next_album_image',
    'get_previous_album_image',
    'get_next_product_image',
    'get_previous_product_image',
    'open_multilightbox',
    'get_next_image',
    'get_previous_image',
    'load-blogs',
    'load-recent-blogs',
    'get_no_posts_name',
    'search-blog-read',
    'search-blog',
    'coinbase',
    'load_more_products',
    'yoomoney',
    'iyzipay',
);
if (!empty($wo['config']['membership_system']) && $wo['config']['membership_system'] == 1) {
    $non_login_array[] = 'pro_register';
    $non_login_array[] = 'get_payment_method';
    $non_login_array[] = 'cashfree';
    $non_login_array[] = 'paystack';
    $non_login_array[] = 'pay_using_wallet';
    $non_login_array[] = 'get_paypal_url';
    $non_login_array[] = 'stripe_payment';
    $non_login_array[] = 'paypro_with_bitcoin';
    $non_login_array[] = '2checkout_pro';
    $non_login_array[] = 'bank_transfer';
    $non_login_array[] = 'stripe';
}
if (!in_array($f, $allow_array)) {
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH'])) {
        if (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
            exit("Restrcited Area");
        }
    } else {
        exit("Restrcited Area");
    }
}
if (!in_array($f, $non_login_array)) {
    if (empty($wo['loggedin']) && ($s != 'load_more_posts')) {
        if ($s != 'load-comments') {
            exit("Please login or signup to continue.");
        }
    }
}
if (!empty($wo['loggedin']) && !empty($wo['user']['banned']) && $wo['user']['banned'] == 1 && !in_array($f, $non_login_array)) {
    exit();
}
$files = scandir('xhr');
unset($files[0]);
unset($files[1]);
if (file_exists('xhr/' . $f . '.php') && in_array($f . '.php', $files)) {
    include 'xhr/' . $f . '.php';
}
elseif (!empty($_GET['mode_type']) && in_array($_GET['mode_type'], array('linkedin'))) {
    include 'xhr/modes/' . Wo_Secure($_GET['mode_type']) . '.php';
}
mysqli_close($sqlConnect);
unset($wo);
exit();