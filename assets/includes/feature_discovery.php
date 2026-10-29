<?php
// +------------------------------------------------------------------------+
// | FacesOfNaija Feature Discovery Backend Helper
// | Progressive onboarding across Web, Android, and iOS
// +------------------------------------------------------------------------+

if (!defined('T_FEATURE_DISCOVERY')) {
    define('T_FEATURE_DISCOVERY', 'Wo_FeatureDiscovery');
}

function Wo_EnsureFeatureDiscoveryTableExists() {
    global $sqlConnect;
    static $checked = false;
    if ($checked) {
        return true;
    }
    $query = "CREATE TABLE IF NOT EXISTS `" . T_FEATURE_DISCOVERY . "` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `user_id` int(11) NOT NULL DEFAULT '0',
        `version` int(11) NOT NULL DEFAULT '1',
        `completed_intro_count` int(11) NOT NULL DEFAULT '0',
        `last_acknowledged_intro` int(11) NOT NULL DEFAULT '0',
        `last_login_id` varchar(100) NOT NULL DEFAULT '',
        `completed_at` int(11) NOT NULL DEFAULT '0',
        `updated_at` int(11) NOT NULL DEFAULT '0',
        PRIMARY KEY (`id`),
        UNIQUE KEY `user_id_version` (`user_id`, `version`),
        KEY `user_id` (`user_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
    $res = mysqli_query($sqlConnect, $query);
    $checked = true;
    return $res;
}

function Wo_GetFeatureDiscoveryIntros() {
    return array(
        1 => array(
            'id' => 1,
            'step' => 1,
            'total' => 5,
            'feature_key' => 'community',
            'title' => 'Your Community, Your People',
            'description' => 'Connect with people, discover communities, share interests, and build meaningful connections with others on FacesOfNaija.',
            'icon' => 'users',
            'accent_color' => '#1B5E20'
        ),
        2 => array(
            'id' => 2,
            'step' => 2,
            'total' => 5,
            'feature_key' => 'news_timeline',
            'title' => 'Stay Informed. Stay Connected.',
            'description' => 'Explore news and updates, catch up on posts from people you follow, and discover conversations happening around you.',
            'icon' => 'newspaper-o',
            'accent_color' => '#0D47A1'
        ),
        3 => array(
            'id' => 3,
            'step' => 3,
            'total' => 5,
            'feature_key' => 'stories_sharing',
            'title' => 'Share Your Moments',
            'description' => 'Post updates, share your experiences, and discover stories and moments shared by other members of the FacesOfNaija community.',
            'icon' => 'camera',
            'accent_color' => '#E65100'
        ),
        4 => array(
            'id' => 4,
            'step' => 4,
            'total' => 5,
            'feature_key' => 'messenger',
            'title' => 'Conversations That Bring Us Closer',
            'description' => 'Use Messenger to connect with people through private conversations and keep in touch with your connections.',
            'icon' => 'comments',
            'accent_color' => '#6A1B9A'
        ),
        5 => array(
            'id' => 5,
            'step' => 5,
            'total' => 5,
            'feature_key' => 'explore_express',
            'title' => 'Explore More. Express Yourself.',
            'description' => 'Discover more of FacesOfNaija, personalize your experience, and explore the tools available to help you connect, share, and express yourself.',
            'icon' => 'compass',
            'accent_color' => '#A84849'
        ),
    );
}

function Wo_GetFeatureDiscoveryStatus($user_id, $version = 1) {
    global $sqlConnect;
    if (empty($user_id) || !is_numeric($user_id) || $user_id <= 0) {
        return array('status' => 400, 'error' => 'Invalid user ID');
    }
    Wo_EnsureFeatureDiscoveryTableExists();

    $user_id = (int)$user_id;
    $version = (int)$version;

    $query = "SELECT * FROM `" . T_FEATURE_DISCOVERY . "` WHERE `user_id` = {$user_id} AND `version` = {$version} LIMIT 1";
    $sql = mysqli_query($sqlConnect, $query);

    $completed_intro_count = 0;
    $last_acknowledged_intro = 0;
    $completed_at = 0;
    $last_login_id = '';

    if ($sql && mysqli_num_rows($sql) > 0) {
        $row = mysqli_fetch_assoc($sql);
        $completed_intro_count = (int)$row['completed_intro_count'];
        $last_acknowledged_intro = (int)$row['last_acknowledged_intro'];
        $completed_at = (int)$row['completed_at'];
        $last_login_id = $row['last_login_id'];
    } else {
        // Initialize default record
        $now = time();
        @mysqli_query($sqlConnect, "INSERT IGNORE INTO `" . T_FEATURE_DISCOVERY . "` (`user_id`, `version`, `completed_intro_count`, `last_acknowledged_intro`, `updated_at`) VALUES ({$user_id}, {$version}, 0, 0, {$now})");
    }

    $intros = Wo_GetFeatureDiscoveryIntros();
    $total_intros = count($intros);

    $should_show = ($completed_intro_count < $total_intros);
    $next_intro_id = $completed_intro_count + 1;
    $current_intro = ($should_show && isset($intros[$next_intro_id])) ? $intros[$next_intro_id] : null;

    return array(
        'status' => 200,
        'feature_discovery_version' => $version,
        'completed_intro_count' => $completed_intro_count,
        'last_acknowledged_intro' => $last_acknowledged_intro,
        'last_login_id' => $last_login_id,
        'completed_at' => $completed_at,
        'should_show' => $should_show,
        'next_intro_id' => $should_show ? $next_intro_id : null,
        'current_intro' => $current_intro,
        'next_intro' => $current_intro,
        'total_intros' => $total_intros
    );
}

function Wo_AcknowledgeFeatureDiscovery($user_id, $intro_id, $login_id = '', $version = 1) {
    global $sqlConnect;
    if (empty($user_id) || !is_numeric($user_id) || $user_id <= 0) {
        return array('status' => 400, 'error' => 'Invalid user ID');
    }
    Wo_EnsureFeatureDiscoveryTableExists();

    $user_id = (int)$user_id;
    $intro_id = (int)$intro_id;
    $version = (int)$version;
    $safe_login_id = Wo_Secure($login_id);
    $now = time();

    if ($intro_id < 1 || $intro_id > 5) {
        return array('status' => 400, 'error' => 'Invalid intro ID (must be between 1 and 5)');
    }

    $currentStatus = Wo_GetFeatureDiscoveryStatus($user_id, $version);
    $currentCount = $currentStatus['completed_intro_count'];

    // Idempotent update:
    // If user acknowledges the current eligible intro ($currentCount + 1), advance count.
    // If user acknowledges an intro that is <= $currentCount, accept as duplicate acknowledgment without double incrementing.
    if ($intro_id == $currentCount + 1) {
        $newCount = $intro_id;
        $completed_at_sql = ($newCount >= 5) ? ", `completed_at` = {$now}" : "";
        $updateQuery = "UPDATE `" . T_FEATURE_DISCOVERY . "` 
            SET `completed_intro_count` = {$newCount},
                `last_acknowledged_intro` = {$intro_id},
                `last_login_id` = '{$safe_login_id}',
                `updated_at` = {$now}
                {$completed_at_sql}
            WHERE `user_id` = {$user_id} AND `version` = {$version}";
        mysqli_query($sqlConnect, $updateQuery);
    } elseif ($intro_id <= $currentCount) {
        // Just touch last_login_id / updated_at if provided
        if (!empty($safe_login_id)) {
            mysqli_query($sqlConnect, "UPDATE `" . T_FEATURE_DISCOVERY . "` SET `last_login_id` = '{$safe_login_id}', `updated_at` = {$now} WHERE `user_id` = {$user_id} AND `version` = {$version}");
        }
    }

    return Wo_GetFeatureDiscoveryStatus($user_id, $version);
}
