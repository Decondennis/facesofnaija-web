<?php
// +------------------------------------------------------------------------+
// | WoWonder - Social Login HybridAuth Configuration
// | Reads provider credentials from the site config (wo_config table)
// +------------------------------------------------------------------------+

$callback = $config['site_url'] . '/login-with.php';

$LoginWithConfig = [
    'callback' => $callback,
    'providers' => [
        'Google' => [
            'enabled' => !empty($wo['config']['googleLogin']) && $wo['config']['googleLogin'] != 0,
            'keys' => [
                'id'     => $wo['config']['googleAppId']  ?? '',
                'secret' => $wo['config']['googleAppKey'] ?? '',
            ],
            'scope' => 'openid profile email',
        ],
        'Facebook' => [
            'enabled' => !empty($wo['config']['facebookLogin']) && $wo['config']['facebookLogin'] != 0,
            'keys' => [
                'id'     => $wo['config']['facebookAppId']  ?? '',
                'secret' => $wo['config']['facebookAppKey'] ?? '',
            ],
            'trustForwarded' => false,
        ],
        'Twitter' => [
            'enabled' => !empty($wo['config']['twitterLogin']) && $wo['config']['twitterLogin'] != 0,
            'keys' => [
                'key'    => $wo['config']['twitterAppId']  ?? '',
                'secret' => $wo['config']['twitterAppKey'] ?? '',
            ],
        ],
        'LinkedIn' => [
            'enabled' => !empty($wo['config']['linkedinLogin']) && $wo['config']['linkedinLogin'] != 0,
            'keys' => [
                'id'     => $wo['config']['linkedinAppId']  ?? '',
                'secret' => $wo['config']['linkedinAppKey'] ?? '',
            ],
        ],
        'Vkontakte' => [
            'enabled' => !empty($wo['config']['VkontakteLogin']) && $wo['config']['VkontakteLogin'] != 0,
            'keys' => [
                'id'     => $wo['config']['VkontakteAppId']  ?? '',
                'secret' => $wo['config']['VkontakteAppKey'] ?? '',
            ],
        ],
        'Instagram' => [
            'enabled' => !empty($wo['config']['instagramLogin']) && $wo['config']['instagramLogin'] != 0,
            'keys' => [
                'id'     => $wo['config']['instagramAppId']  ?? '',
                'secret' => $wo['config']['instagramAppkey'] ?? '',
            ],
        ],
        'QQ' => [
            'enabled' => !empty($wo['config']['qqLogin']) && $wo['config']['qqLogin'] != 0,
            'keys' => [
                'id'     => $wo['config']['qqAppId']  ?? '',
                'secret' => $wo['config']['qqAppkey'] ?? '',
            ],
        ],
        'WeChat' => [
            'enabled' => !empty($wo['config']['WeChatLogin']) && $wo['config']['WeChatLogin'] != 0,
            'keys' => [
                'id'     => $wo['config']['WeChatAppId']  ?? '',
                'secret' => $wo['config']['WeChatAppkey'] ?? '',
            ],
        ],
        'Discord' => [
            'enabled' => !empty($wo['config']['DiscordLogin']) && $wo['config']['DiscordLogin'] != 0,
            'keys' => [
                'id'     => $wo['config']['DiscordAppId']  ?? '',
                'secret' => $wo['config']['DiscordAppkey'] ?? '',
            ],
        ],
        'Mailru' => [
            'enabled' => !empty($wo['config']['MailruLogin']) && $wo['config']['MailruLogin'] != 0,
            'keys' => [
                'id'     => $wo['config']['MailruAppId']  ?? '',
                'secret' => $wo['config']['MailruAppkey'] ?? '',
            ],
        ],
    ],
];
