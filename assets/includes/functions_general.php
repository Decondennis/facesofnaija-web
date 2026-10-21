<?php
// +------------------------------------------------------------------------+
// | @author Deen Doughouz (DoughouzForest)
// | @author_url 1: http://www.wowonder.com
// | @author_url 2: http://codecanyon.net/user/doughouzforest
// | @author_email: wowondersocial@gmail.com
// +------------------------------------------------------------------------+
// | WoWonder - The Ultimate Social Networking Platform
// | Copyright (c) 2022 WoWonder. All rights reserved.
// +------------------------------------------------------------------------+
function sanitize_output($buffer) {
    $search  = array(
        '/\>[^\S ]+/s', // strip whitespaces after tags, except space
        '/[^\S ]+\</s', // strip whitespaces before tags, except space
        '/(\s)+/s', // shorten multiple whitespace sequences
        '/<!--(.|\s)*?-->/'
        // Remove HTML comments
    );
    $replace = array(
        '>',
        '<',
        '\\1',
        ''
    );
    $buffer  = preg_replace($search, $replace, $buffer);
    return $buffer;
}
function Wo_LoadPage($page_url = '') {
    global $wo, $db;
    $create_file = false;
    if ($page_url == 'sidebar/content' && $wo['loggedin'] == true && $wo['config']['cache_sidebar'] == 1) {
        $file_path = './cache/sidebar-' . $wo['user']['user_id'] . '.tpl';
        if (file_exists($file_path)) {
            $get_file = file_get_contents($file_path);
            if (!empty($get_file)) {
                return $get_file;
            }
        } else {
            $create_file = true;
        }
    }
    $page         = './themes/' . $wo['config']['theme'] . '/layout/' . $page_url . '.phtml';
    $page_content = '';
    ob_start();
    require($page);
    $page_content = ob_get_contents();
    ob_end_clean();
    if ($create_file == true && $wo['config']['cache_sidebar'] == 1) {
        $create_sidebar_file = file_put_contents($file_path, $page_content);
        setcookie("last_sidebar_update", time(), time() + (10 * 365 * 24 * 60 * 60));
    }
    return $page_content;
}
function Wo_CleanCache($user_id = '', $where = 'sidebar') {
    global $wo;
    if ($wo['config']['cache_sidebar'] == 0 || $wo['loggedin'] == false) {
        return false;
    }
    $file_path = './cache/sidebar-' . $wo['user']['user_id'] . '.tpl';
    if (file_exists($file_path)) {
        unlink($file_path);
    }
}
function Wo_CustomCode($a = false, $code = array()) {
    global $wo;
    $theme       = $wo['config']['theme'];
    $data        = array();
    $result      = false;
    $custom_code = array(
        "themes/$theme/custom/js/head.js",
        "themes/$theme/custom/js/footer.js",
        "themes/$theme/custom/css/style.css"
    );
    if ($a == 'g') {
        foreach ($custom_code as $key => $filepath) {
            if (is_readable($filepath)) {
                $data[$key] = file_get_contents($filepath);
            }
        }
        $result = $data;
    } else if ($a == 'p' && !empty($code)) {
        foreach ($code as $key => $content) {
            if (is_writable($custom_code[$key])) {
                @file_put_contents($custom_code[$key], $content);
            }
        }
        $result = true;
    }
    return $result;
}
function Wo_LoadAdminPage($page_url = '') {
    global $wo, $db;
    $page         = './admin-panel/pages/' . $page_url . '.phtml';
    $page_content = '';
    ob_start();
    require($page);
    $page_content = ob_get_contents();
    ob_end_clean();
    return $page_content;
}
function Wo_LoadAdminLinkSettings($link = '') {
    global $site_url;
    return $site_url . '/admin-cp/' . $link;
}
function Wo_LoadAdminLink($link = '') {
    global $site_url;
    return $site_url . '/admin-panel/' . $link;
}
function Wo_SizeUnits($bytes = 0) {
    if ($bytes >= 1073741824) {
        $bytes = round(($bytes / 1073741824)) . ' GB';
    } elseif ($bytes >= 1048576) {
        $bytes = round(($bytes / 1048576)) . ' MB';
    } elseif ($bytes >= 1024) {
        $bytes = round(($bytes / 1024)) . ' KB';
    }
    return $bytes;
}
function Wo_MultipleArrayFiles($file_post) {
    if (!is_array($file_post)) {
        return array();
    }
    $wo_file_array = array();
    $wo_file_count = count($file_post['name']);
    $wo_file_keys  = array_keys($file_post);
    for ($i = 0; $i < $wo_file_count; $i++) {
        foreach ($wo_file_keys as $key) {
            $wo_file_array[$i][$key] = $file_post[$key][$i];
        }
    }
    return $wo_file_array;
}
function Wo_IsValidMimeType($mimeTypes = array()) {
    if (!is_array($mimeTypes) || empty($mimeTypes)) {
        return false;
    }
    $result = true;
    foreach ($mimeTypes as $value) {
        $type = explode('/', $value);
        if ($type[0] != 'image' && $type[0] != 'video') {
            $result = false;
            break;
        }
    }
    return $result;
}
function url_slug($str, $options = array()) {
    // Make sure string is in UTF-8 and strip invalid UTF-8 characters
    $str      = mb_convert_encoding((string) $str, 'UTF-8', mb_list_encodings());
    $defaults = array(
        'delimiter' => '-',
        'limit' => null,
        'lowercase' => true,
        'replacements' => array(),
        'transliterate' => true
    );
    // Merge options
    $options  = array_merge($defaults, $options);
    $char_map = array(
        // Latin
        'À' => 'A',
        'Á' => 'A',
        'Â' => 'A',
        'Ã' => 'A',
        'Ä' => 'A',
        'Å' => 'A',
        'Æ' => 'AE',
        'Ç' => 'C',
        'È' => 'E',
        'É' => 'E',
        'Ê' => 'E',
        'Ë' => 'E',
        'Ì' => 'I',
        'Í' => 'I',
        'Î' => 'I',
        'Ï' => 'I',
        'Ð' => 'D',
        'Ñ' => 'N',
        'Ò' => 'O',
        'Ó' => 'O',
        'Ô' => 'O',
        'Õ' => 'O',
        'Ö' => 'O',
        'Ő' => 'O',
        'Ø' => 'O',
        'Ù' => 'U',
        'Ú' => 'U',
        'Û' => 'U',
        'Ü' => 'U',
        'Ű' => 'U',
        'Ý' => 'Y',
        'Þ' => 'TH',
        'ß' => 'ss',
        'à' => 'a',
        'á' => 'a',
        'â' => 'a',
        'ã' => 'a',
        'ä' => 'a',
        'å' => 'a',
        'æ' => 'ae',
        'ç' => 'c',
        'è' => 'e',
        'é' => 'e',
        'ê' => 'e',
        'ë' => 'e',
        'ì' => 'i',
        'í' => 'i',
        'î' => 'i',
        'ï' => 'i',
        'ð' => 'd',
        'ñ' => 'n',
        'ò' => 'o',
        'ó' => 'o',
        'ô' => 'o',
        'õ' => 'o',
        'ö' => 'o',
        'ő' => 'o',
        'ø' => 'o',
        'ù' => 'u',
        'ú' => 'u',
        'û' => 'u',
        'ü' => 'u',
        'ű' => 'u',
        'ý' => 'y',
        'þ' => 'th',
        'ÿ' => 'y',
        // Latin symbols
        '©' => '(c)',
        // Greek
        'Α' => 'A',
        'Β' => 'B',
        'Γ' => 'G',
        'Δ' => 'D',
        'Ε' => 'E',
        'Ζ' => 'Z',
        'Η' => 'H',
        'Θ' => '8',
        'Ι' => 'I',
        'Κ' => 'K',
        'Λ' => 'L',
        'Μ' => 'M',
        'Ν' => 'N',
        'Ξ' => '3',
        'Ο' => 'O',
        'Π' => 'P',
        'Ρ' => 'R',
        'Σ' => 'S',
        'Τ' => 'T',
        'Υ' => 'Y',
        'Φ' => 'F',
        'Χ' => 'X',
        'Ψ' => 'PS',
        'Ω' => 'W',
        'Ά' => 'A',
        'Έ' => 'E',
        'Ί' => 'I',
        'Ό' => 'O',
        'Ύ' => 'Y',
        'Ή' => 'H',
        'Ώ' => 'W',
        'Ϊ' => 'I',
        'Ϋ' => 'Y',
        'α' => 'a',
        'β' => 'b',
        'γ' => 'g',
        'δ' => 'd',
        'ε' => 'e',
        'ζ' => 'z',
        'η' => 'h',
        'θ' => '8',
        'ι' => 'i',
        'κ' => 'k',
        'λ' => 'l',
        'μ' => 'm',
        'ν' => 'n',
        'ξ' => '3',
        'ο' => 'o',
        'π' => 'p',
        'ρ' => 'r',
        'σ' => 's',
        'τ' => 't',
        'υ' => 'y',
        'φ' => 'f',
        'χ' => 'x',
        'ψ' => 'ps',
        'ω' => 'w',
        'ά' => 'a',
        'έ' => 'e',
        'ί' => 'i',
        'ό' => 'o',
        'ύ' => 'y',
        'ή' => 'h',
        'ώ' => 'w',
        'ς' => 's',
        'ϊ' => 'i',
        'ΰ' => 'y',
        'ϋ' => 'y',
        'ΐ' => 'i',
        // Turkish
        'Ş' => 'S',
        'İ' => 'I',
        'Ç' => 'C',
        'Ü' => 'U',
        'Ö' => 'O',
        'Ğ' => 'G',
        'ş' => 's',
        'ı' => 'i',
        'ç' => 'c',
        'ü' => 'u',
        'ö' => 'o',
        'ğ' => 'g',
        // Russian
        'А' => 'A',
        'Б' => 'B',
        'В' => 'V',
        'Г' => 'G',
        'Д' => 'D',
        'Е' => 'E',
        'Ё' => 'Yo',
        'Ж' => 'Zh',
        'З' => 'Z',
        'И' => 'I',
        'Й' => 'J',
        'К' => 'K',
        'Л' => 'L',
        'М' => 'M',
        'Н' => 'N',
        'О' => 'O',
        'П' => 'P',
        'Р' => 'R',
        'С' => 'S',
        'Т' => 'T',
        'У' => 'U',
        'Ф' => 'F',
        'Х' => 'H',
        'Ц' => 'C',
        'Ч' => 'Ch',
        'Ш' => 'Sh',
        'Щ' => 'Sh',
        'Ъ' => '',
        'Ы' => 'Y',
        'Ь' => '',
        'Э' => 'E',
        'Ю' => 'Yu',
        'Я' => 'Ya',
        'а' => 'a',
        'б' => 'b',
        'в' => 'v',
        'г' => 'g',
        'д' => 'd',
        'е' => 'e',
        'ё' => 'yo',
        'ж' => 'zh',
        'з' => 'z',
        'и' => 'i',
        'й' => 'j',
        'к' => 'k',
        'л' => 'l',
        'м' => 'm',
        'н' => 'n',
        'о' => 'o',
        'п' => 'p',
        'р' => 'r',
        'с' => 's',
        'т' => 't',
        'у' => 'u',
        'ф' => 'f',
        'х' => 'h',
        'ц' => 'c',
        'ч' => 'ch',
        'ш' => 'sh',
        'щ' => 'sh',
        'ъ' => '',
        'ы' => 'y',
        'ь' => '',
        'э' => 'e',
        'ю' => 'yu',
        'я' => 'ya',
        // Ukrainian
        'Є' => 'Ye',
        'І' => 'I',
        'Ї' => 'Yi',
        'Ґ' => 'G',
        'є' => 'ye',
        'і' => 'i',
        'ї' => 'yi',
        'ґ' => 'g',
        // Czech
        'Č' => 'C',
        'Ď' => 'D',
        'Ě' => 'E',
        'Ň' => 'N',
        'Ř' => 'R',
        'Š' => 'S',
        'Ť' => 'T',
        'Ů' => 'U',
        'Ž' => 'Z',
        'č' => 'c',
        'ď' => 'd',
        'ě' => 'e',
        'ň' => 'n',
        'ř' => 'r',
        'š' => 's',
        'ť' => 't',
        'ů' => 'u',
        'ž' => 'z',
        // Polish
        'Ą' => 'A',
        'Ć' => 'C',
        'Ę' => 'e',
        'Ł' => 'L',
        'Ń' => 'N',
        'Ó' => 'o',
        'Ś' => 'S',
        'Ź' => 'Z',
        'Ż' => 'Z',
        'ą' => 'a',
        'ć' => 'c',
        'ę' => 'e',
        'ł' => 'l',
        'ń' => 'n',
        'ó' => 'o',
        'ś' => 's',
        'ź' => 'z',
        'ż' => 'z',
        // Latvian
        'Ā' => 'A',
        'Č' => 'C',
        'Ē' => 'E',
        'Ģ' => 'G',
        'Ī' => 'i',
        'Ķ' => 'k',
        'Ļ' => 'L',
        'Ņ' => 'N',
        'Š' => 'S',
        'Ū' => 'u',
        'Ž' => 'Z',
        'ā' => 'a',
        'č' => 'c',
        'ē' => 'e',
        'ģ' => 'g',
        'ī' => 'i',
        'ķ' => 'k',
        'ļ' => 'l',
        'ņ' => 'n',
        'š' => 's',
        'ū' => 'u',
        'ž' => 'z'
    );
    // Make custom replacements
    $str      = preg_replace(array_keys($options['replacements']), $options['replacements'], $str);
    // Transliterate characters to ASCII
    if ($options['transliterate']) {
        $str = str_replace(array_keys($char_map), $char_map, $str);
    }
    // Replace non-alphanumeric characters with our delimiter
    $str = preg_replace('/[^\p{L}\p{Nd}]+/u', $options['delimiter'], $str);
    // Remove duplicate delimiters
    $str = preg_replace('/(' . preg_quote($options['delimiter'], '/') . '){2,}/', '$1', $str);
    // Truncate slug to max. characters
    $str = mb_substr($str, 0, ($options['limit'] ? $options['limit'] : mb_strlen($str, 'UTF-8')), 'UTF-8');
    // Remove delimiter from ends
    $str = trim($str, $options['delimiter']);
    return $options['lowercase'] ? mb_strtolower($str, 'UTF-8') : $str;
}
function Wo_SeoLink($query = '') {
    global $wo, $config;
    if ($wo['config']['seoLink'] == 1) {
        $query = preg_replace(array(
            '/^index\.php\?link1=developers&page=(.*)$/i',
            '/^index\.php\?link1=reviews&id=(.*)$/i',
            '/^index\.php\?link1=order&id=(.*)$/i',
            '/^index\.php\?link1=customer_order&id=(.*)$/i',
            '/^index\.php\?link1=edit_fund&id=([A-Za-z0-9_]+)$/i',
            '/^index\.php\?link1=show_fund&id=([A-Za-z0-9_]+)$/i',
            '/^index\.php\?link1=timeline&u=([A-Za-z0-9_]+)&type=([A-Za-z0-9_]+)&id=([A-Za-z0-9_]+)$/i',
            '/^index\.php\?link1=jobs$/i',
            '/^index\.php\?link1=forumaddthred&fid=(\d+)$/i',
            '/^index\.php\?link1=welcome&link2=password_reset&user_id=([A-Za-z0-9_]+)$/i',
            '/^index\.php\?link1=welcome&last_url=(.*)$/i',
            '/^index\.php\?link1=([^\/]+)&query=$/i',
            '/^index\.php\?link1=post&id=(.*)$/i',
            '/^index\.php\?link1=post&id=([A-Za-z0-9_]+)&ref=([A-Za-z0-9_]+)$/i',
            '/^index\.php\?link1=terms&page=contact-us$/i',
            '/^index\.php\?link1=([^\/]+)&u=([A-Za-z0-9_]+)$/i',
            '/^index\.php\?link1=timeline&u=([A-Za-z0-9_]+)&type=([A-Za-z0-9_]+)$/i',
            '/^index\.php\?link1=messages&user=([A-Za-z0-9_]+)$/i',
            '/^index\.php\?link1=setting&page=([A-Za-z0-9_-]+)$/i',
            '/^index\.php\?link1=setting&user=([A-Za-z0-9_]+)&page=([A-Za-z0-9_-]+)$/i',
            '/^index\.php\?link1=([^\/]+)&app_id=([A-Za-z0-9_]+)$/i',
            '/^index\.php\?link1=([^\/]+)&hash=([^\/]+)$/i',
            '/^index\.php\?link1=([^\/]+)&link2=([^\/]+)$/i',
            '/^index\.php\?link1=([^\/]+)&type=([^\/]+)$/i',
            '/^index\.php\?link1=([^\/]+)&p=([^\/]+)$/i',
            '/^index\.php\?link1=([^\/]+)&g=([^\/]+)$/i',
            '/^index\.php\?link1=page-setting&page=([A-Za-z0-9_]+)&link3=([A-Za-z0-9_-]+)&name=([A-Za-z0-9_-]+)$/i',
            '/^index\.php\?link1=page-setting&page=([A-Za-z0-9_]+)&link3=([A-Za-z0-9_-]+)$/i',
            '/^index\.php\?link1=page-setting&page=([^\/]+)$/i',
            '/^index\.php\?link1=group-setting&group=([A-Za-z0-9_]+)&link3=([A-Za-z0-9_-]+)&name=([A-Za-z0-9_-]+)$/i',
            '/^index\.php\?link1=group-setting&group=([A-Za-z0-9_]+)&link3=([A-Za-z0-9_-]+)$/i',
            '/^index\.php\?link1=group-setting&group=([^\/]+)$/i',
            '/^index\.php\?link1=community-setting&community=([A-Za-z0-9_]+)&link3=([A-Za-z0-9_-]+)&name=([A-Za-z0-9_-]+)$/i', //added this
            '/^index\.php\?link1=community-setting&community=([A-Za-z0-9_]+)&link3=([A-Za-z0-9_-]+)$/i',
            '/^index\.php\?link1=community-setting&community=([^\/]+)$/i',
            '/^index\.php\?link1=admincp&page=([^\/]+)$/i',
            '/^index\.php\?link1=game&id=([^\/]+)$/i',
            '/^index\.php\?link1=albums&user=([A-Za-z0-9_]+)$/i',
            '/^index\.php\?link1=create-album&album=([A-Za-z0-9_]+)$/i',
            '/^index\.php\?link1=edit-product&id=([A-Za-z0-9_]+)$/i',
            '/^index\.php\?link1=products&c_id=([A-Za-z0-9_]+)$/i',
            '/^index\.php\?link1=products&c_id=([A-Za-z0-9_]+)&sub_id=([A-Za-z0-9_]+)$/i',
            '/^index\.php\?link1=site-pages&page_name=(.*)$/i',
            '/^index\.php\?link1=create-blog$/i',
            '/^index\.php\?link1=my-blogs$/i',
            '/^index\.php\?link1=forum$/i',
            '/^index\.php\?link1=forumsadd&fid=(\d+)$/i',
            '/^index\.php\?link1=forums&fid=(\d+)$/i',
            '/^index\.php\?link1=showthread&tid=(\d+)$/i',
            '/^index\.php\?link1=threadreply&tid=(\d+)$/i',
            '/^index\.php\?link1=threadquote&tid=(\d+)$/i',
            '/^index\.php\?link1=editreply&tid=(\d+)$/i',
            '/^index\.php\?link1=edithread&tid=(\d+)$/i',
            '/^index\.php\?link1=mythreads$/i',
            '/^index\.php\?link1=mymessages$/i',
            '/^index\.php\?link1=read-blog&id=([^\/]+)$/i',
            '/^index\.php\?link1=blog-category&id=([^\/]+)$/i',
            '/^index\.php\?link1=edit-blog&id=([^\/]+)$/i',
            '/^index\.php\?link1=forum-members$/i',
            '/^index\.php\?link1=forum-members-byname&char=([a-zA-Z])$/i',
            '/^index\.php\?link1=forum-search$/i',
            '/^index\.php\?link1=forum-search-result$/i',
            '/^index\.php\?link1=forum-events$/i',
            '/^index\.php\?link1=forum-help$/i',
            '/^index\.php\?link1=events$/i',
            '/^index\.php\?link1=show-event&eid=(\d+)$/i',
            '/^index\.php\?link1=create-event$/i',
            '/^index\.php\?link1=edit-event&eid=(\d+)$/i',
            '/^index\.php\?link1=events-going$/i',
            '/^index\.php\?link1=events-invited$/i',
            '/^index\.php\?link1=events-interested$/i',
            '/^index\.php\?link1=events-past$/i',
            '/^index\.php\?link1=my-events$/i',
            '/^index\.php\?link1=movies$/i',
            '/^index\.php\?link1=movies-genre&genre=([A-Za-z-]+)$/i',
            '/^index\.php\?link1=movies-country&country=([A-Za-z-]+)$/i',
            '/^index\.php\?link1=watch-film&film-id=(\d+)$/i',
            '/^index\.php\?link1=advertise$/i',
            '/^index\.php\?link1=wallet$/i',
            '/^index\.php\?link1=create-ads$/i',
            '/^index\.php\?link1=edit-ads&id=(\d+)$/i',
            '/^index\.php\?link1=chart-ads&id=(\d+)$/i',
            '/^index\.php\?link1=manage-ads&id=(\d+)$/i',
            '/^index\.php\?link1=create-status$/i',
            '/^index\.php\?link1=friends-nearby$/i',
            '/^index\.php\?link1=([^\/]+)$/i',
            '/^index\.php\?link1=welcome$/i'
        ), array(
            $config['site_url'] . '/developers?page=$1',
            $config['site_url'] . '/reviews/$1',
            $config['site_url'] . '/order/$1',
            $config['site_url'] . '/customer_order/$1',
            $config['site_url'] . '/edit_fund/$1',
            $config['site_url'] . '/show_fund/$1',
            $config['site_url'] . '/$1/$2&id=$3',
            $config['site_url'] . '/jobs',
            $config['site_url'] . '/forums/add/$1/',
            $config['site_url'] . '/password-reset/$1',
            $config['site_url'] . '/welcome/?last_url=$1',
            $config['site_url'] . '/search/$2',
            $config['site_url'] . '/post/$1',
            $config['site_url'] . '/post/$1?ref=$2',
            $config['site_url'] . '/terms/contact-us',
            $config['site_url'] . '/$2',
            $config['site_url'] . '/$1/$2',
            $config['site_url'] . '/messages/$1',
            $config['site_url'] . '/setting/$1',
            $config['site_url'] . '/setting/$1/$2',
            $config['site_url'] . '/$1/$2',
            $config['site_url'] . '/$1/$2',
            $config['site_url'] . '/$1/$2',
            $config['site_url'] . '/$1/$2',
            $config['site_url'] . '/p/$2',
            $config['site_url'] . '/g/$2',
            $config['site_url'] . '/page-setting/$1/$2?name=$3',
            $config['site_url'] . '/page-setting/$1/$2',
            $config['site_url'] . '/page-setting/$1',
            $config['site_url'] . '/group-setting/$1/$2?name=$3',
            $config['site_url'] . '/group-setting/$1/$2',
            $config['site_url'] . '/group-setting/$1',
            $config['site_url'] . '/community-setting/$1/$2?name=$3', //added this
            $config['site_url'] . '/community-setting/$1/$2',
            $config['site_url'] . '/community-setting/$1',
            $config['site_url'] . '/admincp/$1',
            $config['site_url'] . '/game/$1',
            $config['site_url'] . '/albums/$1',
            $config['site_url'] . '/create-album/$1',
            $config['site_url'] . '/edit-product/$1',
            $config['site_url'] . '/products/$1',
            $config['site_url'] . '/products/$1/$2',
            $config['site_url'] . '/site-pages/$1',
            $config['site_url'] . '/create-blog/',
            $config['site_url'] . '/my-blogs/',
            $config['site_url'] . '/forum/',
            $config['site_url'] . '/forums/add/$1/',
            $config['site_url'] . '/forums/$1/',
            $config['site_url'] . '/forums/thread/$1/',
            $config['site_url'] . '/forums/thread/reply/$1/',
            $config['site_url'] . '/forums/thread/quote/$1/',
            $config['site_url'] . '/forums/thread/edit/$1/',
            $config['site_url'] . '/forums/user/threads/edit/$1/',
            $config['site_url'] . '/forums/user/threads/',
            $config['site_url'] . '/forums/user/messages/',
            $config['site_url'] . '/read-blog/$1',
            $config['site_url'] . '/blog-category/$1',
            $config['site_url'] . '/edit-blog/$1',
            $config['site_url'] . '/forum/members/',
            $config['site_url'] . '/forum/members/$1/',
            $config['site_url'] . '/forum/search/',
            $config['site_url'] . '/forum/search-result/',
            $config['site_url'] . '/forum/events/',
            $config['site_url'] . '/forum/help/',
            $config['site_url'] . '/events/',
            $config['site_url'] . '/events/$1/',
            $config['site_url'] . '/events/create-event/',
            $config['site_url'] . '/events/edit/$1/',
            $config['site_url'] . '/events/going/',
            $config['site_url'] . '/events/invited/',
            $config['site_url'] . '/events/interested/',
            $config['site_url'] . '/events/past/',
            $config['site_url'] . '/events/my/',
            $config['site_url'] . '/movies/',
            $config['site_url'] . '/movies/genre/$1/',
            $config['site_url'] . '/movies/country/$1/',
            $config['site_url'] . '/movies/watch/$1/',
            $config['site_url'] . '/advertise',
            $config['site_url'] . '/wallet/',
            $config['site_url'] . '/ads/create/',
            $config['site_url'] . '/ads/edit/$1/',
            $config['site_url'] . '/ads/chart/$1/',
            $config['site_url'] . '/admin/ads/edit/$1/',
            $config['site_url'] . '/status/create/',
            $config['site_url'] . '/friends-nearby/',
            $config['site_url'] . '/$1',
            $config['site_url']
        ), $query);
    } else {
        $query = $config['site_url'] . '/' . $query;
    }
    return $query;
}
function Wo_IsLogged() {
    if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
        $id = Wo_GetUserFromSessionID($_SESSION['user_id']);
        if (is_numeric($id) && !empty($id)) {
            return true;
        }
    } else if (!empty($_COOKIE['user_id']) && !empty($_COOKIE['user_id'])) {
        $id = Wo_GetUserFromSessionID($_COOKIE['user_id']);
        if (is_numeric($id) && !empty($id)) {
            return true;
        }
    } else {
        return false;
    }
}
function Wo_Redirect($url) {
    return header("Location: {$url}");
}
function Wo_Link($string) {
    global $site_url;
    return $site_url . '/' . $string;
}
function Wo_Sql_Result($res, $row = 0, $col = 0) {
    $numrows = mysqli_num_rows($res);
    if ($numrows && $row <= ($numrows - 1) && $row >= 0) {
        mysqli_data_seek($res, $row);
        $resrow = (is_numeric($col)) ? mysqli_fetch_row($res) : mysqli_fetch_assoc($res);
        if (isset($resrow[$col])) {
            return $resrow[$col];
        }
    }
    return false;
}
function Wo_UrlDomain($url) {
    $host = @parse_url($url, PHP_URL_HOST);
    if (!$host) {
        $host = $url;
    }
    if (substr($host, 0, 4) == "www.") {
        $host = substr($host, 4);
    }
    if (strlen($host) > 50) {
        $host = substr($host, 0, 47) . '...';
    }
    return $host;
}
function Wo_Secure($string, $censored_words = 1, $br = true, $strip = 0) {
    global $sqlConnect, $mysqlMaria;
    $mysqlMaria->setSQLType($sqlConnect);
    $string = trim($string);
    $string = cleanString($string);
    $string = mysqli_real_escape_string($sqlConnect, $string);
    $string = htmlspecialchars($string, ENT_QUOTES);
    if ($br == true) {
        $string = str_replace('\r\n', " <br>", $string);
        $string = str_replace('\n\r', " <br>", $string);
        $string = str_replace('\r', " <br>", $string);
        $string = str_replace('\n', " <br>", $string);
    } else {
        $string = str_replace('\r\n', "", $string);
        $string = str_replace('\n\r', "", $string);
        $string = str_replace('\r', "", $string);
        $string = str_replace('\n', "", $string);
    }
    if ($strip == 1) {
        $string = stripslashes($string);
    }
    $string = str_replace('&amp;#', '&#', $string);
    if ($censored_words == 1) {
        global $config;
        $censored_words = @explode(",", $config['censored_words']);
        foreach ($censored_words as $censored_word) {
            $censored_word = trim($censored_word);
            $string        = str_replace($censored_word, '****', $string);
        }
    }
    return $string;
}
function Wo_BbcodeSecure($string) {
    global $sqlConnect;
    $string = trim($string);
    $string = mysqli_real_escape_string($sqlConnect, $string);
    $string = htmlspecialchars($string, ENT_QUOTES);
    $string = str_replace('\r\n', "[nl]", $string);
    $string = str_replace('\n\r', "[nl]", $string);
    $string = str_replace('\r', "[nl]", $string);
    $string = str_replace('\n', "[nl]", $string);
    $string = str_replace('&amp;#', '&#', $string);
    $string = strip_tags($string);
    $string = stripslashes($string);
    return $string;
}
function Wo_Decode($string) {
    return htmlspecialchars_decode($string);
}
function Wo_GenerateKey($minlength = 20, $maxlength = 20, $uselower = true, $useupper = true, $usenumbers = true, $usespecial = false) {
    $charset = '';
    if ($uselower) {
        $charset .= "abcdefghijklmnopqrstuvwxyz";
    }
    if ($useupper) {
        $charset .= "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    }
    if ($usenumbers) {
        $charset .= "123456789";
    }
    if ($usespecial) {
        $charset .= "~@#$%^*()_+-={}|][";
    }
    if ($minlength > $maxlength) {
        $length = mt_rand($maxlength, $minlength);
    } else {
        $length = mt_rand($minlength, $maxlength);
    }
    $key = '';
    for ($i = 0; $i < $length; $i++) {
        $key .= $charset[(mt_rand(0, strlen($charset) - 1))];
    }
    return $key;
}
$can = 0;
function Wo_CropAvatarImage($file = '', $data = array()) {
    global $wo;
    if (empty($file)) {
        return false;
    }
    if (!isset($data['x']) || !isset($data['y']) || !isset($data['w']) || !isset($data['h'])) {
        return false;
    }
    if (!file_exists($file)) {
        $get_media = file_put_contents($file, file_get_contents(Wo_GetMedia($file)));
    }
    if (!file_exists($file)) {
        return false;
    }
    $imgsize = @getimagesize($file);
    if (empty($imgsize)) {
        return false;
    }
    $width    = $data['w'];
    $height   = $data['h'];
    $source_x = $data['x'];
    $source_y = $data['y'];
    $mime     = $imgsize['mime'];
    $image    = "imagejpeg";
    switch ($mime) {
        case 'image/gif':
            $image_create = "imagecreatefromgif";
            break;
        case 'image/png':
            $image_create = "imagecreatefrompng";
            break;
        case 'image/jpeg':
            $image_create = "imagecreatefromjpeg";
            break;
        default:
            return false;
            break;
    }
    $dest = imagecreatetruecolor($width, $height);
    $src  = $image_create($file);
    $file = str_replace('_full', '', $file);
    imagecopy($dest, $src, 30, 30, $source_x, $source_y, $width, $height);
    $to_crop_array = array(
        'x' => $source_x,
        'y' => $source_y,
        'width' => $width,
        'height' => $height
    );
    $dest          = imagecrop($src, $to_crop_array);
    imagejpeg($dest, $file, 100);
    Wo_Resize_Crop_Image($wo['profile_picture_width_crop'], $wo['profile_picture_height_crop'], $file, $file, 80);
    $s3 = Wo_UploadToS3($file);
    return true;
}
function Wo_Resize_Crop_Image($max_width, $max_height, $source_file, $dst_dir, $quality = 80) {
    $imgsize = @getimagesize($source_file);
    $width   = $imgsize[0];
    $height  = $imgsize[1];
    $mime    = $imgsize['mime'];
    $image   = "imagejpeg";
    switch ($mime) {
        case 'image/gif':
            $image_create = "imagecreatefromgif";
            break;
        case 'image/png':
            $image_create = "imagecreatefrompng";
            break;
        case 'image/jpeg':
            $image_create = "imagecreatefromjpeg";
            break;
        default:
            return false;
            break;
    }
    $dst_img = @imagecreatetruecolor($max_width, $max_height);
    $src_img = @$image_create($source_file);
    if (function_exists('exif_read_data')) {
        $exif          = @exif_read_data($source_file);
        $another_image = false;
        if (!empty($exif['Orientation'])) {
            switch ($exif['Orientation']) {
                case 3:
                    $src_img = @imagerotate($src_img, 180, 0);
                    @imagejpeg($src_img, $dst_dir, $quality);
                    $another_image = true;
                    break;
                case 6:
                    $src_img = @imagerotate($src_img, -90, 0);
                    @imagejpeg($src_img, $dst_dir, $quality);
                    $another_image = true;
                    break;
                case 8:
                    $src_img = @imagerotate($src_img, 90, 0);
                    @imagejpeg($src_img, $dst_dir, $quality);
                    $another_image = true;
                    break;
            }
        }
        if ($another_image == true) {
            $imgsize = @getimagesize($dst_dir);
            if ($width > 0 && $height > 0) {
                $width  = $imgsize[0];
                $height = $imgsize[1];
            }
        }
    }
    @$width_new = $height * $max_width / $max_height;
    @$height_new = $width * $max_height / $max_width;
    if ($width_new > $width) {
        $h_point = (($height - $height_new) / 2);
        @imagecopyresampled($dst_img, $src_img, 0, 0, 0, $h_point, $max_width, $max_height, $width, $height_new);
    } else {
        $w_point = (($width - $width_new) / 2);
        @imagecopyresampled($dst_img, $src_img, 0, 0, $w_point, 0, $max_width, $max_height, $width_new, $height);
    }
    @imagejpeg($dst_img, $dst_dir, $quality);
    if ($dst_img)
        @imagedestroy($dst_img);
    if ($src_img)
        @imagedestroy($src_img);
    return true;
}
function str_replace_first($search, $replace, $subject) {
    $pos = strpos($subject, $search);
    if ($pos !== false) {
        return substr_replace($subject, $replace, $pos, strlen($search));
    }
    return $subject;
}
function substitute($stringOrFunction, $number) {
    //$string = $stringOrFunction;
    return $number . ' ' . $stringOrFunction;
}
function Wo_Time_Elapsed_String($ptime) {
    global $wo;
    $etime = (time()) - $ptime;
    if ($etime < 1) {
        //return '0 seconds';
        return 'Now';
    }
    $seconds = abs($etime);
    $minutes = $seconds / 60;
    $hours   = $minutes / 60;
    $days    = $hours / 24;
    $weeks   = $days / 7;
    $years   = $days / 365;
    if ($seconds < 45) {
        return substitute($wo['lang']['now'], '');
    } elseif ($seconds < 90) {
        return substitute($wo['lang']['_time_m'], 1);
    } elseif ($minutes < 45) {
        return substitute($wo['lang']['_time_m'], round($minutes));
    } elseif ($minutes < 90) {
        return substitute($wo['lang']['_time_h'], 1);
    } elseif ($hours < 24) {
        return substitute($wo['lang']['_time_hrs'], round($hours));
    } elseif ($hours < 42) {
        return substitute($wo['lang']['_time_d'], 1);
    } elseif ($days < 7) {
        return substitute($wo['lang']['_time_d'], round($days));
    } elseif ($weeks < 2) {
        return substitute($wo['lang']['_time_w'], 1);
    } elseif ($weeks < 52) {
        return substitute($wo['lang']['_time_w'], round($weeks));
    } elseif ($years < 1.5) {
        return substitute($wo['lang']['_time_y'], 1);
    } else {
        return substitute($wo['lang']['_time_yrs'], round($years));
    }
    // $a        = array(
    //     365 * 24 * 60 * 60 => $wo['lang']['year'],
    //     30 * 24 * 60 * 60 => $wo['lang']['month'],
    //     24 * 60 * 60 => $wo['lang']['day'],
    //     60 * 60 => $wo['lang']['hour'],
    //     60 => $wo['lang']['minute'],
    //     1 => $wo['lang']['second']
    // );
    // $a_plural = array(
    //     $wo['lang']['year'] => $wo['lang']['years'],
    //     $wo['lang']['month'] => $wo['lang']['months'],
    //     $wo['lang']['day'] => $wo['lang']['days'],
    //     $wo['lang']['hour'] => $wo['lang']['hours'],
    //     $wo['lang']['minute'] => $wo['lang']['minutes'],
    //     $wo['lang']['second'] => $wo['lang']['seconds']
    // );
    // foreach ($a as $secs => $str) {
    //     $d = $etime / $secs;
    //     if ($d >= 1) {
    //         $r = round($d);
    //         if ($wo['language_type'] == 'rtl') {
    //             //$time_ago = $wo['lang']['time_ago'] . ' ' . $r . ' ' . ($r > 1 ? $a_plural[$str] : $str);
    //             if ($secs > 1) {
    //                 $time_ago = $r . ' ' . ($r > 1 ? $a_plural[$str] : $str);
    //             }
    //             else{
    //                 $time_ago = $wo['lang']['now'];
    //             }
    //         } else {
    //             //$time_ago = $r . ' ' . ($r > 1 ? $a_plural[$str] : $str) . ' ' . $wo['lang']['time_ago'];
    //             if ($secs > 1) {
    //                 $time_ago = $r . ' ' . ($r > 1 ? $a_plural[$str] : $str);
    //             }
    //             else{
    //                 $time_ago = $wo['lang']['now'];
    //             }
    //         }
    //         return $time_ago;
    //     }
    // }
}
function Wo_FolderSize($dir) {
    $count_size = 0;
    $count      = 0;
    $dir_array  = scandir($dir);
    foreach ($dir_array as $key => $filename) {
        if ($filename != ".." && $filename != "." && $filename != ".htaccess") {
            if (is_dir($dir . "/" . $filename)) {
                $new_foldersize = Wo_FolderSize($dir . "/" . $filename);
                $count_size     = $count_size + $new_foldersize;
            } else if (is_file($dir . "/" . $filename)) {
                $count_size = $count_size + filesize($dir . "/" . $filename);
                $count++;
            }
        }
    }
    return $count_size;
}
function Wo_SizeFormat($bytes) {
    $kb = 1024;
    $mb = $kb * 1024;
    $gb = $mb * 1024;
    $tb = $gb * 1024;
    if (($bytes >= 0) && ($bytes < $kb)) {
        return $bytes . ' B';
    } elseif (($bytes >= $kb) && ($bytes < $mb)) {
        return ceil($bytes / $kb) . ' KB';
    } elseif (($bytes >= $mb) && ($bytes < $gb)) {
        return ceil($bytes / $mb) . ' MB';
    } elseif (($bytes >= $gb) && ($bytes < $tb)) {
        return ceil($bytes / $gb) . ' GB';
    } elseif ($bytes >= $tb) {
        return ceil($bytes / $tb) . ' TB';
    } else {
        return $bytes . ' B';
    }
}
function Wo_ClearCache() {
    $path = 'cache';
    if ($handle = opendir($path)) {
        while (false !== ($file = readdir($handle))) {
            if (strripos($file, '.tmp') !== false) {
                @unlink($path . '/' . $file);
            }
        }
    }
}
function Wo_GetThemes() {
    global $wo;
    $themes = glob('themes/*', GLOB_ONLYDIR);
    return $themes;
}
function Wo_ReturnBytes($val) {
    $val  = trim($val);
    $last = strtolower($val[strlen($val) - 1]);
    switch ($last) {
        case 'g':
            $val *= 1024;
        case 'm':
            $val *= 1024;
        case 'k':
            $val *= 1024;
    }
    return $val;
}
function getBaseUrl() {
    $currentPath = $_SERVER['PHP_SELF'];
    $pathInfo    = pathinfo($currentPath);
    $hostName    = $_SERVER['HTTP_HOST'];
    return $hostName . $pathInfo['dirname'];
}
function Wo_MaxFileUpload() {
    //select maximum upload size
    $max_upload   = Wo_ReturnBytes(ini_get('upload_max_filesize'));
    //select post limit
    $max_post     = Wo_ReturnBytes(ini_get('post_max_size'));
    //select memory limit
    $memory_limit = Wo_ReturnBytes(ini_get('memory_limit'));
    // return the smallest of them, this defines the real limit
    return min($max_upload, $max_post, $memory_limit);
}
function Wo_CompressImage($source_url, $destination_url, $quality) {
    $imgsize = getimagesize($source_url);
    $finfof  = $imgsize['mime'];
    $image_c = 'imagejpeg';
    if ($finfof == 'image/jpeg') {
        $image = @imagecreatefromjpeg($source_url);
    } else if ($finfof == 'image/gif') {
        $image = @imagecreatefromgif($source_url);
    } else if ($finfof == 'image/png') {
        $image = @imagecreatefrompng($source_url);
    } else {
        $image = @imagecreatefromjpeg($source_url);
    }
    $quality = 50;
    if (function_exists('exif_read_data')) {
        $exif = @exif_read_data($source_url);
        if (!empty($exif['Orientation'])) {
            switch ($exif['Orientation']) {
                case 3:
                    $image = @imagerotate($image, 180, 0);
                    break;
                case 6:
                    $image = @imagerotate($image, -90, 0);
                    break;
                case 8:
                    $image = @imagerotate($image, 90, 0);
                    break;
            }
        }
    }
    @imagejpeg($image, $destination_url, $quality);
    return $destination_url;
}
function get_ip_address() {
    if (!empty($_SERVER['HTTP_X_FORWARDED']) && validate_ip($_SERVER['HTTP_X_FORWARDED']))
        return $_SERVER['HTTP_X_FORWARDED'];
    if (!empty($_SERVER['HTTP_X_CLUSTER_CLIENT_IP']) && validate_ip($_SERVER['HTTP_X_CLUSTER_CLIENT_IP']))
        return $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
    if (!empty($_SERVER['HTTP_FORWARDED_FOR']) && validate_ip($_SERVER['HTTP_FORWARDED_FOR']))
        return $_SERVER['HTTP_FORWARDED_FOR'];
    if (!empty($_SERVER['HTTP_FORWARDED']) && validate_ip($_SERVER['HTTP_FORWARDED']))
        return $_SERVER['HTTP_FORWARDED'];
    return $_SERVER['REMOTE_ADDR'];
}
function validate_ip($ip) {
    if (strtolower($ip) === 'unknown')
        return false;
    $ip = ip2long($ip);
    if ($ip !== false && $ip !== -1) {
        $ip = sprintf('%u', $ip);
        if ($ip >= 0 && $ip <= 50331647)
            return false;
        if ($ip >= 167772160 && $ip <= 184549375)
            return false;
        if ($ip >= 2130706432 && $ip <= 2147483647)
            return false;
        if ($ip >= 2851995648 && $ip <= 2852061183)
            return false;
        if ($ip >= 2886729728 && $ip <= 2887778303)
            return false;
        if ($ip >= 3221225984 && $ip <= 3221226239)
            return false;
        if ($ip >= 3232235520 && $ip <= 3232301055)
            return false;
        if ($ip >= 4294967040)
            return false;
    }
    return true;
}
function Wo_Backup($sql_db_host, $sql_db_user, $sql_db_pass, $sql_db_name, $tables = false, $backup_name = false) {
    $mysqli = new mysqli($sql_db_host, $sql_db_user, $sql_db_pass, $sql_db_name);
    $mysqli->select_db($sql_db_name);
    $mysqli->query("SET NAMES 'utf8'");
    $queryTables = $mysqli->query('SHOW TABLES');
    while ($row = $queryTables->fetch_row()) {
        $target_tables[] = $row[0];
    }
    if ($tables !== false) {
        $target_tables = array_intersect($target_tables, $tables);
    }
    $content = "-- phpMyAdmin SQL Dump
-- http://www.phpmyadmin.net
--
-- Host Connection Info: " . $mysqli->host_info . "
-- Generation Time: " . date('F d, Y \a\t H:i A ( e )') . "
-- Server version: " . mysqli_get_server_info($mysqli) . "
-- PHP Version: " . PHP_VERSION . "
--\n
SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";
SET time_zone = \"+00:00\";\n
/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;\n\n";
    foreach ($target_tables as $table) {
        $result        = $mysqli->query('SELECT * FROM ' . $table);
        $fields_amount = $result->field_count;
        $rows_num      = $mysqli->affected_rows;
        $res           = $mysqli->query('SHOW CREATE TABLE ' . $table);
        $TableMLine    = $res->fetch_row();
        $content       = (!isset($content) ? '' : $content) . "
-- ---------------------------------------------------------
--
-- Table structure for table : `{$table}`
--
-- ---------------------------------------------------------
\n" . $TableMLine[1] . ";\n";
        for ($i = 0, $st_counter = 0; $i < $fields_amount; $i++, $st_counter = 0) {
            while ($row = $result->fetch_row()) {
                if ($st_counter % 100 == 0 || $st_counter == 0) {
                    $content .= "\n--
-- Dumping data for table `{$table}`
--\n\nINSERT INTO " . $table . " VALUES";
                }
                $content .= "\n(";
                for ($j = 0; $j < $fields_amount; $j++) {
                    $row[$j] = str_replace("\n", "\\n", addslashes($row[$j]));
                    if (isset($row[$j])) {
                        $content .= '"' . $row[$j] . '"';
                    } else {
                        $content .= '""';
                    }
                    if ($j < ($fields_amount - 1)) {
                        $content .= ',';
                    }
                }
                $content .= ")";
                if ((($st_counter + 1) % 100 == 0 && $st_counter != 0) || $st_counter + 1 == $rows_num) {
                    $content .= ";\n";
                } else {
                    $content .= ",";
                }
                $st_counter = $st_counter + 1;
            }
        }
        $content .= "";
    }
    $content .= "
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;";
    if (!file_exists('script_backups/' . date('d-m-Y'))) {
        @mkdir('script_backups/' . date('d-m-Y'), 0777, true);
    }
    if (!file_exists('script_backups/' . date('d-m-Y') . '/' . time())) {
        mkdir('script_backups/' . date('d-m-Y') . '/' . time(), 0777, true);
    }
    if (!file_exists("script_backups/" . date('d-m-Y') . '/' . time() . "/index.html")) {
        $f = @fopen("script_backups/" . date('d-m-Y') . '/' . time() . "/index.html", "a+");
        @fwrite($f, "");
        @fclose($f);
    }
    if (!file_exists('script_backups/.htaccess')) {
        $f = @fopen("script_backups/.htaccess", "a+");
        @fwrite($f, "deny from all\nOptions -Indexes");
        @fclose($f);
    }
    if (!file_exists("script_backups/" . date('d-m-Y') . "/index.html")) {
        $f = @fopen("script_backups/" . date('d-m-Y') . "/index.html", "a+");
        @fwrite($f, "");
        @fclose($f);
    }
    if (!file_exists('script_backups/index.html')) {
        $f = @fopen("script_backups/index.html", "a+");
        @fwrite($f, "");
        @fclose($f);
    }
    $folder_name = "script_backups/" . date('d-m-Y') . '/' . time();
    $put         = @file_put_contents($folder_name . '/SQL-Backup-' . time() . '-' . date('d-m-Y') . '.sql', $content);
    if ($put) {
        $rootPath = realpath('./');
        $zip      = new ZipArchive();
        $open     = $zip->open($folder_name . '/Files-Backup-' . time() . '-' . date('d-m-Y') . '.zip', ZipArchive::CREATE | ZipArchive::OVERWRITE);
        if ($open !== true) {
            return false;
        }
        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($rootPath), RecursiveIteratorIterator::LEAVES_ONLY);
        foreach ($files as $name => $file) {
            if (!preg_match('/\bscript_backups\b/', $file)) {
                if (!$file->isDir()) {
                    $filePath     = $file->getRealPath();
                    $relativePath = substr($filePath, strlen($rootPath) + 1);
                    $zip->addFile($filePath, $relativePath);
                }
            }
        }
        $zip->close();
        $mysqli->query("UPDATE " . T_CONFIG . " SET `value` = '" . date('d-m-Y') . "' WHERE `name` = 'last_backup'");
        $mysqli->close();
        return true;
    } else {
        return false;
    }
}
function Wo_isSecure() {
    return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443;
}
function copy_directory($src, $dst) {
    $dir = opendir($src);
    @mkdir($dst);
    while (false !== ($file = readdir($dir))) {
        if (($file != '.') && ($file != '..')) {
            if (is_dir($src . '/' . $file)) {
                copy_directory($src . '/' . $file, $dst . '/' . $file);
            } else {
                copy($src . '/' . $file, $dst . '/' . $file);
            }
        }
    }
    closedir($dir);
}
function delete_directory($dirname) {
    if (is_dir($dirname))
        $dir_handle = opendir($dirname);
    if (!$dir_handle)
        return false;
    while ($file = readdir($dir_handle)) {
        if ($file != "." && $file != "..") {
            if (!is_dir($dirname . "/" . $file))
                unlink($dirname . "/" . $file);
            else
                delete_directory($dirname . '/' . $file);
        }
    }
    closedir($dir_handle);
    rmdir($dirname);
    return true;
}
function Wo_CheckUserSessionID($user_id = 0, $session_id = '', $platform = 'web') {
    global $wo, $sqlConnect;
    if (empty($user_id) || !is_numeric($user_id) || $user_id < 0) {
        return false;
    }
    if (empty($session_id)) {
        return false;
    }
    $platform  = Wo_Secure($platform);
    $query     = mysqli_query($sqlConnect, "SELECT COUNT(`id`) as `session` FROM " . T_APP_SESSIONS . " WHERE `user_id` = '{$user_id}' AND `session_id` = '{$session_id}'");
    $query_sql = mysqli_fetch_assoc($query);
    if ($query_sql['session'] > 0) {
        return true;
    }
    return false;
}
function Wo_ValidateAccessToken($access_token = '') {
    global $wo, $sqlConnect;
    if (empty($access_token)) {
        return false;
    }
    $access_token = Wo_Secure($access_token);
    $query        = mysqli_query($sqlConnect, "SELECT user_id FROM " . T_APP_SESSIONS . " WHERE `session_id` = '{$access_token}' LIMIT 1");
    $query_sql    = mysqli_fetch_assoc($query);
    if ($query_sql['user_id'] > 0) {
        return $query_sql['user_id'];
    }
    return false;
}
function ip_in_range($ip, $range) {
    if (strpos($range, '/') == false) {
        $range .= '/32';
    }
    // $range is in IP/CIDR format eg 127.0.0.1/24
    list($range, $netmask) = explode('/', $range, 2);
    $range_decimal    = ip2long($range);
    $ip_decimal       = ip2long($ip);
    $wildcard_decimal = pow(2, (32 - $netmask)) - 1;
    $netmask_decimal  = ~$wildcard_decimal;
    return (($ip_decimal & $netmask_decimal) == ($range_decimal & $netmask_decimal));
}
function br2nl($st) {
    $breaks   = array(
        "\r\n",
        "\r",
        "\n"
    );
    $st       = str_replace($breaks, "", $st);
    $st_no_lb = preg_replace("/\r|\n/", "", $st);
    return preg_replace('/<br(\s+)?\/?>/i', "\r", $st_no_lb);
}
function br2nlf($st) {
    $breaks   = array(
        "\r\n",
        "\r",
        "\n"
    );
    $st       = str_replace($breaks, "", $st);
    $st_no_lb = preg_replace("/\r|\n/", "", $st);
    $st       = preg_replace('/<br(\s+)?\/?>/i', "\r", $st_no_lb);
    return str_replace('[nl]', "\r", $st);
}
use Aws\S3\S3Client;
function makeFTPdir($ftp, $dir) {
}
use Google\Cloud\Storage\StorageClient;
function Wo_UploadToS3($filename, $config = array()) {
    global $wo;
    if ($wo['config']['amazone_s3'] == 0 && $wo['config']['ftp_upload'] == 0 && $wo['config']['spaces'] == 0 && $wo['config']['cloud_upload'] == 0 && $wo['config']['wasabi_storage'] == 0) {
        return false;
    }
    if ($wo['config']['ftp_upload'] == 1) {
        include_once('assets/libraries/ftp/vendor/autoload.php');
        $ftp = new \FtpClient\FtpClient();
        $ftp->connect($wo['config']['ftp_host'], false, $wo['config']['ftp_port']);
        $login = $ftp->login($wo['config']['ftp_username'], $wo['config']['ftp_password']);
        if ($login) {
            if (!empty($wo['config']['ftp_path'])) {
                if ($wo['config']['ftp_path'] != "./") {
                    $ftp->chdir($wo['config']['ftp_path']);
                }
            }
            $file_path      = substr($filename, 0, strrpos($filename, '/'));
            $file_path_info = explode('/', $file_path);
            $path           = '';
            if (!$ftp->isDir($file_path)) {
                foreach ($file_path_info as $key => $value) {
                    if (!empty($path)) {
                        $path .= '/' . $value . '/';
                    } else {
                        $path .= $value . '/';
                    }
                    if (!$ftp->isDir($path)) {
                        $mkdir = $ftp->mkdir($path);
                    }
                }
            }
            $ftp->chdir($file_path);
            $ftp->pasv(true);
            if ($ftp->putFromPath($filename)) {
                if (empty($config['delete'])) {
                    if (empty($config['amazon'])) {
                        @unlink($filename);
                    }
                }
                $ftp->close();
                return true;
            }
            $ftp->close();
        }
    } else if ($wo['config']['amazone_s3'] == 1) {
        if (empty($wo['config']['amazone_s3_key']) || empty($wo['config']['amazone_s3_s_key']) || empty($wo['config']['region']) || empty($wo['config']['bucket_name'])) {
            return false;
        }
        include_once('assets/libraries/s3/vendor/autoload.php');
        $s3 = new S3Client(array(
            'version' => 'latest',
            'region' => $wo['config']['region'],
            'credentials' => array(
                'key' => $wo['config']['amazone_s3_key'],
                'secret' => $wo['config']['amazone_s3_s_key']
            )
        ));
        $s3->putObject(array(
            'Bucket' => $wo['config']['bucket_name'],
            'Key' => $filename,
            'Body' => fopen($filename, 'r+'),
            'ACL' => 'public-read',
            'CacheControl' => 'max-age=3153600'
        ));
        if (empty($config['delete'])) {
            if ($s3->doesObjectExist($wo['config']['bucket_name'], $filename)) {
                if (empty($config['amazon'])) {
                    @unlink($filename);
                }
                return true;
            }
        } else {
            return true;
        }
    } else if ($wo['config']['wasabi_storage'] == 1) {
        if (empty($wo['config']['wasabi_bucket_name']) || empty($wo['config']['wasabi_access_key']) || empty($wo['config']['wasabi_secret_key']) || empty($wo['config']['wasabi_bucket_region'])) {
            return false;
        }
        include_once('assets/libraries/s3/vendor/autoload.php');
        $s3 = new S3Client(array(
                'version' => 'latest',
                'endpoint' => 'https://s3.wasabisys.com',
                'region' => $wo['config']['wasabi_bucket_region'],
                'credentials' => array(
                    'key' => $wo['config']['wasabi_access_key'],
                    'secret' => $wo['config']['wasabi_secret_key']
                )
            ));
        $s3->putObject(array(
            'Bucket' => $wo['config']['wasabi_bucket_name'],
            'Key' => $filename,
            'Body' => fopen($filename, 'r+'),
            'ACL' => 'public-read',
            'CacheControl' => 'max-age=3153600'
        ));
        if (empty($config['delete'])) {
            if ($s3->doesObjectExist($wo['config']['wasabi_bucket_name'], $filename)) {
                if (empty($config['wasabi'])) {
                    //@unlink($filename);
                }
                return true;
            }
        } else {
            return true;
        }
    } else if ($wo['config']['spaces'] == 1) {
        include_once("assets/libraries/spaces/spaces.php");
        $key        = $wo['config']['spaces_key'];
        $secret     = $wo['config']['spaces_secret'];
        $space_name = $wo['config']['space_name'];
        $region     = $wo['config']['space_region'];
        $space      = new SpacesConnect($key, $secret, $space_name, $region);
        $upload     = $space->UploadFile($filename, "public");
        if ($upload) {
            if (empty($config['delete'])) {
                if ($space->DoesObjectExist($filename)) {
                    if (empty($config['amazon'])) {
                        @unlink($filename);
                    }
                    return true;
                }
            } else {
                return true;
            }
            return true;
        }
    } elseif ($wo['config']['cloud_upload'] == 1) {
        require_once 'assets/libraries/cloud/vendor/autoload.php';
        try {
            $storage       = new StorageClient(array(
                'keyFilePath' => $wo['config']['cloud_file_path']
            ));
            // set which bucket to work in
            $bucket        = $storage->bucket($wo['config']['cloud_bucket_name']);
            $fileContent   = file_get_contents($filename);
            // upload/replace file
            $storageObject = $bucket->upload($fileContent, array(
                'name' => $filename
            ));
            if (!empty($storageObject)) {
                if (empty($config['delete'])) {
                    if (empty($config['amazon'])) {
                        @unlink($filename);
                    }
                }
                return true;
            }
        }
        catch (Exception $e) {
            // maybe invalid private key ?
            // print $e;
            // exit();
            return false;
        }
    }
    return false;
}
function Wo_DeleteFromToS3($filename, $config = array()) {
    global $wo;
    if ($wo['config']['amazone_s3'] == 0 && $wo['config']['ftp_upload'] == 0 && $wo['config']['spaces'] == 0 && $wo['config']['cloud_upload'] == 0 && $wo['config']['amazone_s3_2'] == 0 && $wo['config']['wasabi_storage'] == 0) {
        return false;
    }
    if ($wo['config']['ftp_upload'] == 1) {
        include_once('assets/libraries/ftp/vendor/autoload.php');
        $ftp = new \FtpClient\FtpClient();
        $ftp->connect($wo['config']['ftp_host'], false, $wo['config']['ftp_port']);
        $login = $ftp->login($wo['config']['ftp_username'], $wo['config']['ftp_password']);
        if ($login) {
            if (!empty($wo['config']['ftp_path'])) {
                if ($wo['config']['ftp_path'] != "./") {
                    $ftp->chdir($wo['config']['ftp_path']);
                }
            }
            $file_path      = substr($filename, 0, strrpos($filename, '/'));
            $file_name      = substr($filename, strrpos($filename, '/') + 1);
            $file_path_info = explode('/', $file_path);
            $path           = '';
            if (!$ftp->isDir($file_path)) {
                return false;
            }
            $ftp->chdir($file_path);
            $ftp->pasv(true);
            if ($ftp->remove($file_name)) {
                return true;
            }
        }
    } else if ($wo['config']['amazone_s3'] == 1) {
        include_once('assets/libraries/s3/vendor/autoload.php');
        if (empty($wo['config']['amazone_s3_key']) || empty($wo['config']['amazone_s3_s_key']) || empty($wo['config']['region']) || empty($wo['config']['bucket_name'])) {
            return false;
        }
        $s3 = new S3Client(array(
            'version' => 'latest',
            'region' => $wo['config']['region'],
            'credentials' => array(
                'key' => $wo['config']['amazone_s3_key'],
                'secret' => $wo['config']['amazone_s3_s_key']
            )
        ));
        $s3->deleteObject(array(
            'Bucket' => $wo['config']['bucket_name'],
            'Key' => $filename
        ));
        if (!$s3->doesObjectExist($wo['config']['bucket_name'], $filename)) {
            return true;
        }
    } else if ($wo['config']['wasabi_storage'] == 1) {
        include_once('assets/libraries/s3/vendor/autoload.php');
        if (empty($wo['config']['wasabi_bucket_name']) || empty($wo['config']['wasabi_access_key']) || empty($wo['config']['wasabi_secret_key']) || empty($wo['config']['wasabi_bucket_region'])) {
            return false;
        }
        $s3 = new S3Client(array(
                'version' => 'latest',
                'endpoint' => 'https://s3.wasabisys.com',
                'region' => $wo['config']['wasabi_bucket_region'],
                'credentials' => array(
                    'key' => $wo['config']['wasabi_access_key'],
                    'secret' => $wo['config']['wasabi_secret_key']
                )
            ));
        $s3->deleteObject(array(
            'Bucket' => $wo['config']['wasabi_bucket_name'],
            'Key' => $filename
        ));
        if (!$s3->doesObjectExist($wo['config']['wasabi_bucket_name'], $filename)) {
            return true;
        }
    } else if ($wo['config']['spaces'] == 1) {
        include_once("assets/libraries/spaces/spaces.php");
        $key        = $wo['config']['spaces_key'];
        $secret     = $wo['config']['spaces_secret'];
        $space_name = $wo['config']['space_name'];
        $region     = $wo['config']['space_region'];
        $space      = new SpacesConnect($key, $secret, $space_name, $region);
        $delete     = $space->DeleteObject($filename);
        if (!$space->DoesObjectExist($filename)) {
            return true;
        }
    } else if ($wo['config']['cloud_upload'] == 1) {
        require_once 'assets/libraries/cloud/vendor/autoload.php';
        try {
            $storage = new StorageClient(array(
                'keyFilePath' => $wo['config']['cloud_file_path']
            ));
            // set which bucket to work in
            $bucket  = $storage->bucket($wo['config']['cloud_bucket_name']);
            $object  = $bucket->object($filename);
            $delete  = $object->delete();
            if ($delete) {
                return true;
            }
        }
        catch (Exception $e) {
            // maybe invalid private key ?
            // print $e;
            // exit();
            return false;
        }
    }
    if ($wo['config']['amazone_s3_2'] == 1) {
        include_once('assets/libraries/s3/vendor/autoload.php');
        if (empty($wo['config']['amazone_s3_key_2']) || empty($wo['config']['amazone_s3_s_key_2']) || empty($wo['config']['region_2']) || empty($wo['config']['bucket_name_2'])) {
            return false;
        }
        $s3 = new S3Client(array(
            'version' => 'latest',
            'region' => $wo['config']['region_2'],
            'credentials' => array(
                'key' => $wo['config']['amazone_s3_key_2'],
                'secret' => $wo['config']['amazone_s3_s_key_2']
            )
        ));
        $s3->deleteObject(array(
            'Bucket' => $wo['config']['bucket_name_2'],
            'Key' => $filename
        ));
        if (!$s3->doesObjectExist($wo['config']['bucket_name_2'], $filename)) {
            return true;
        }
    }
}
if (!function_exists('glob_recursive')) {
    function glob_recursive($pattern, $flags = 0) {
        $files = glob($pattern, $flags);
        foreach (glob(dirname($pattern) . '/*', GLOB_ONLYDIR | GLOB_NOSORT) as $dir) {
            $files = array_merge($files, glob_recursive($dir . '/' . basename($pattern), $flags));
        }
        return $files;
    }
}
function unzip_file($file, $destination) {
    // create object
    $zip = new ZipArchive();
    // open archive
    if ($zip->open($file) !== true) {
        return false;
    }
    // extract contents to destination directory
    $zip->extractTo($destination);
    // close archive
    $zip->close();
    return true;
}
if (!is_writable("./sources/server.php")) {
    @chmod("./sources/server.php", 0777);
}
function Wo_CanBlog() {
    global $wo;
    if ($wo['config']['blogs'] == 1) {
        if ($wo['config']['can_blogs'] == 0) {
            if (Wo_IsAdmin()) {
                return true;
            }
            return false;
        }
        return true;
    }
    return false;
}
function shuffle_assoc($list) {
    if (!is_array($list))
        return $list;
    $keys = array_keys($list);
    shuffle($keys);
    $random = array();
    foreach ($keys as $key) {
        $random[$key] = $list[$key];
    }
    return $random;
}
function Wo_GetIcon($icon) {
    global $wo;
    return $wo['config']['theme_url'] . '/icons/png/' . $icon . '.png';
}
function Wo_IsFileAllowed($file_name) {
    global $wo;
    $new_string        = pathinfo($file_name, PATHINFO_FILENAME) . '.' . strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    $extension_allowed = explode(',', $wo['config']['allowedExtenstion']);
    $file_extension    = pathinfo($new_string, PATHINFO_EXTENSION);
    if (!in_array($file_extension, $extension_allowed)) {
        return false;
    }
    return true;
}
function Wo_IsVideoNotAllowedMime($file_type) {
    global $wo;
    $mime_types = explode(',', $wo['config']['ffmpeg_mime_types']);
    if (!in_array($file_type, $mime_types)) {
        return true;
    }
    return false;
}
function Wo_IsFfmpegFileAllowed($file_name) {
    global $wo;
    $new_string        = pathinfo($file_name, PATHINFO_FILENAME) . '.' . strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    $extension_allowed = explode(',', $wo['config']['allowedffmpegExtenstion']);
    $file_extension    = pathinfo($new_string, PATHINFO_EXTENSION);
    if (in_array($file_extension, $extension_allowed)) {
        return true;
    }
    return false;
}
function Wo_ShortText($text = "", $len = 100) {
    if (empty($text) || !is_string($text) || !is_numeric($len) || $len < 1) {
        return "****";
    }
    if (strlen($text) > $len) {
        $text = mb_substr($text, 0, $len, "UTF-8") . "..";
    }
    return $text;
}
function Wo_DelexpiredEnvents() {
    global $wo, $sqlConnect;
    $t_events     = T_EVENTS;
    $t_events_inv = T_EVENTS_INV;
    $t_events_go  = T_EVENTS_GOING;
    $t_events_int = T_EVENTS_INT;
    $t_posts      = T_POSTS;
    $sql          = "SELECT `id` FROM `$t_events` WHERE `end_date` < CURDATE()";
    @mysqli_query($sqlConnect, "DELETE FROM `$t_posts` WHERE `event_id` IN ({$sql})");
    @mysqli_query($sqlConnect, "DELETE FROM `$t_posts` WHERE `page_event_id` IN ({$sql})");
    @mysqli_query($sqlConnect, "DELETE FROM `$t_events_inv` WHERE `event_id` IN ({$sql})");
    @mysqli_query($sqlConnect, "DELETE FROM `$t_events_go` WHERE `event_id` IN ({$sql})");
    @mysqli_query($sqlConnect, "DELETE FROM `$t_events_int` WHERE `event_id` IN ({$sql})");
    @mysqli_query($sqlConnect, "DELETE FROM `$t_events` WHERE `end_date` < CURDATE()");
}
function ToObject($array) {
    $object = new stdClass();
    foreach ($array as $key => $value) {
        if (is_array($value)) {
            $value = ToObject($value);
        }
        if (isset($value)) {
            $object->$key = $value;
        }
    }
    return $object;
}
function ToArray($obj) {
    if (is_object($obj))
        $obj = (array) $obj;
    if (is_array($obj)) {
        $new = array();
        foreach ($obj as $key => $val) {
            $new[$key] = ToArray($val);
        }
    } else {
        $new = $obj;
    }
    return $new;
}
function fetchDataFromURL($url = '') {
    if (empty($url)) {
        return false;
    }
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, false);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.0; en-US; rv:1.7.12) Gecko/20050915 Firefox/1.0.7");
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    return curl_exec($ch);
}
function getBrowser() {
    $u_agent  = $_SERVER['HTTP_USER_AGENT'];
    $bname    = 'Unknown';
    $platform = 'Unknown';
    $version  = "";
    // First get the platform?
    if (preg_match('/macintosh|mac os x/i', $u_agent)) {
        $platform = 'mac';
    } elseif (preg_match('/windows|win32/i', $u_agent)) {
        $platform = 'windows';
    } elseif (preg_match('/iphone|IPhone/i', $u_agent)) {
        $platform = 'IPhone Web';
    } elseif (preg_match('/android|Android/i', $u_agent)) {
        $platform = 'Android Web';
    } else if (preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $u_agent)) {
        $platform = 'Mobile';
    } else if (preg_match('/linux/i', $u_agent)) {
        $platform = 'linux';
    }
    // Next get the name of the useragent yes seperately and for good reason
    $ub    = '';
    $bname = '';
    if (preg_match('/MSIE/i', $u_agent) && !preg_match('/Opera/i', $u_agent)) {
        $bname = 'Internet Explorer';
        $ub    = "MSIE";
    } elseif (preg_match('/Firefox/i', $u_agent)) {
        $bname = 'Mozilla Firefox';
        $ub    = "Firefox";
    } elseif (preg_match('/Chrome/i', $u_agent)) {
        $bname = 'Google Chrome';
        $ub    = "Chrome";
    } elseif (preg_match('/Safari/i', $u_agent)) {
        $bname = 'Apple Safari';
        $ub    = "Safari";
    } elseif (preg_match('/Opera/i', $u_agent)) {
        $bname = 'Opera';
        $ub    = "Opera";
    } elseif (preg_match('/Netscape/i', $u_agent)) {
        $bname = 'Netscape';
        $ub    = "Netscape";
    }
    // finally get the correct version number
    $known   = array(
        'Version',
        $ub,
        'other'
    );
    $pattern = '#(?<browser>' . join('|', $known) . ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
    if (!preg_match_all($pattern, $u_agent, $matches)) {
        // we have no matching number just continue
    }
    // see how many we have
    $i = count($matches['browser']);
    if ($i != 1) {
        //we will have two since we are not using 'other' argument yet
        //see if version is before or after the name
        if (strripos($u_agent, "Version") < strripos($u_agent, $ub)) {
            $version = $matches['version'][0];
        } else {
            $version = $matches['version'][1];
        }
    } else {
        $version = $matches['version'][0];
    }
    // check if we have a number
    if ($version == null || $version == "") {
        $version = "?";
    }
    return array(
        'userAgent' => $u_agent,
        'name' => $bname,
        'version' => $version,
        'platform' => $platform,
        'pattern' => $pattern,
        'ip_address' => get_ip_address()
    );
}
function Wo_RunInBackground($data = array()) {
    if (!empty(ob_get_status())) {
        ob_end_clean();
        header("Content-Encoding: none");
        header("Connection: close");
        ignore_user_abort();
        ob_start();
        if (!empty($data)) {
            header('Content-Type: application/json');
            echo json_encode($data);
        }
        $size = ob_get_length();
        header("Content-Length: $size");
        ob_end_flush();
        flush();
        session_write_close();
        if (is_callable('fastcgi_finish_request')) {
            fastcgi_finish_request();
        }
    }
}
function watermark_image($target) {
    global $wo;
    include_once('assets/libraries/SimpleImage-master/vendor/autoload.php');
    if ($wo['config']['watermark'] != 1) {
        return false;
    }
    try {
        $image = new \claviska\SimpleImage();
        $image->fromFile($target)->autoOrient()->overlay("./themes/{$wo['config']['theme']}/img/icon.png", 'top left', 1, 30, 30)->toFile($target, 'image/jpeg');
        return true;
    }
    catch (Exception $err) {
        return $err->getMessage();
    }
}
function Wo_IsMobile() {
    $useragent = $_SERVER['HTTP_USER_AGENT'];
    if (preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i', $useragent) || preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', substr($useragent, 0, 4))) {
        return true;
    }
    return false;
}
function cleanString($string) {
    return $string = preg_replace("/&#?[a-z0-9]+;/i", "", $string);
}

$morningGreetings = [
    "Good morning! May today bring you fresh strength, clear direction, and reasons to smile.",
    "Good morning! Every new day is another opportunity to become better, stronger, and wiser.",
    "Good morning! May the doors you need open, the obstacles before you move, and your efforts produce good results.",
    "Wake up with hope. Yesterday is gone, and today carries new possibilities. Good morning!",
    "Good morning! May you have the courage to pursue your dreams and the patience to trust the process.",
    "Today is a fresh page. Write something beautiful on it. Good morning!",
    "Good morning! May your mind be peaceful, your heart be joyful, and your steps be purposeful.",
    "Rise and shine! You have another opportunity to make progress, no matter how small.",
    "Good morning! May what you couldn't accomplish yesterday become possible today.",
    "Start this day believing that something good can happen. Good morning!",
    "Good morning! Don't worry about having everything figured out. Take the next right step.",
    "A new morning means new strength, new ideas, and new opportunities. Make today count!",
    "Good morning! May your hard work eventually speak louder than every doubt you have faced.",
    "Today, choose hope over fear, courage over doubt, and progress over perfection. Good morning!",
    "Good morning! Your story is still being written. Don't let one difficult chapter define the whole book.",
    "May today remind you that you are capable of more than you think. Good morning!",
    "Good morning! Keep moving forward. Even slow progress is still progress.",
    "Wake up grateful for another chance to try, grow, love, and succeed. Good morning!",
    "Good morning! May unexpected blessings find you and may your efforts bring meaningful results.",
    "Don't allow yesterday's disappointment to steal today's opportunity. Good morning!",
    "Good morning! Trust that better days can still come, even when today begins quietly.",
    "May today bring you wisdom for every decision and strength for every challenge. Good morning!",
    "Good morning! Whatever you face today, remember that you have survived difficult days before.",
    "Start your morning with gratitude, walk through the day with confidence, and end it with satisfaction.",
    "Good morning! May your plans succeed, your worries become lighter, and your heart remain hopeful.",
    "Today doesn't need to be perfect. It only needs to move you forward. Good morning!",
    "Good morning! Believe in the possibilities ahead of you, even when you cannot see the whole path.",
    "May today bring you closer to the things you've been praying, working, and hoping for. Good morning!",
    "Good morning! Never underestimate what one determined person can accomplish in one focused day.",
    "Rise with confidence. You have faced enough challenges to know that you can handle whatever comes your way.",
    "Good morning! May you find strength where you feel weak and clarity where you feel uncertain.",
    "Every sunrise is proof that endings can make room for new beginnings. Good morning!",
    "Good morning! Keep your heart hopeful and your hands busy. Your season of results will come.",
    "Today, don't just wish for a better life—take one step toward creating it. Good morning!",
    "Good morning! May your efforts today plant seeds that produce beautiful results tomorrow.",
    "You may not know what today holds, but you can choose how you meet it. Meet it with courage. Good morning!",
    "Good morning! May peace guard your heart and wisdom guide your decisions throughout the day.",
    "Another morning, another opportunity to get it right. Keep going. Good morning!",
    "Good morning! Don't compare your journey with someone else's. Focus on the road in front of you.",
    "May today bring you closer to your purpose and farther away from unnecessary worry. Good morning!",
    "Good morning! Your current situation is not necessarily your final destination. Keep believing and keep building.",
    "Wake up knowing that you still have something valuable to contribute to the world. Good morning!",
    "Good morning! May your confidence be stronger than your fears and your determination stronger than your excuses.",
    "Today, give yourself permission to start again. There is no shame in rebuilding. Good morning!",
    "Good morning! May opportunities recognize your preparation and may your hard work meet the right doors.",
    "Whatever didn't work yesterday, learn from it and try again today. Good morning!",
    "Good morning! You don't need to see the entire staircase; take the next step.",
    "May your morning begin with peace and your day end with something worth being grateful for.",
    "Good morning! Sometimes the breakthrough begins with simply refusing to give up.",
    "Rise with gratitude. You have another day to change something, improve something, or inspire someone.",
    "Good morning! May your thoughts be positive, your decisions wise, and your actions productive.",
    "Don't let fear make decisions for you today. Be brave enough to pursue what matters. Good morning!",
    "Good morning! May you receive the strength to handle what you cannot change and the wisdom to change what you can.",
    "Today is not a repeat of yesterday. Give it a fresh chance. Good morning!",
    "Good morning! Keep planting good seeds—kindness, discipline, patience, and hard work. They will produce results.",
    "May your efforts be rewarded, your heart remain peaceful, and your hope stay alive. Good morning!",
    "Good morning! The fact that you woke up today means there is still something ahead worth discovering.",
    "Don't count yourself out. Some of your best chapters may still be ahead. Good morning!",
    "Good morning! May today surprise you with good news, genuine smiles, and unexpected opportunities.",
    "Start where you are. Use what you have. Do what you can. Good morning!",
    "Good morning! Your dreams deserve your discipline, not just your wishes.",
    "May today give you another reason to believe that things can get better. Good morning!",
    "Good morning! Whatever challenge comes your way, face it one step, one decision, and one moment at a time.",
    "Don't carry yesterday's burdens into today's opportunities. Let today be today. Good morning!",
    "Good morning! May your journey be guided by wisdom and your destination be worth the effort.",
    "Every morning is a reminder that you have another chance to make a difference. Good morning!",
    "Good morning! Be patient with yourself. Growth takes time, but every effort matters.",
    "May you have the courage to begin, the discipline to continue, and the wisdom to know when to change direction.",
    "Good morning! Don't wait for everything to be perfect before you begin. Start and improve along the way.",
    "Today, focus on what you can control and release what you cannot. Good morning!",
    "Good morning! May your day be productive without being stressful, successful without losing your peace, and busy without losing yourself.",
    "Your past may explain where you came from, but it doesn't have to determine where you're going. Good morning!",
    "Good morning! Keep your vision clear, your heart humble, and your determination strong.",
    "May the right people, opportunities, and ideas find their way into your day. Good morning!",
    "Good morning! Don't be discouraged by how far you have to go. Be encouraged by how far you've already come.",
    "Every great achievement begins with someone deciding not to quit. Keep going. Good morning!",
    "Good morning! May today bring progress in places where you've been waiting patiently.",
    "Your circumstances may change slowly, but your attitude can change this morning. Choose hope. Good morning!",
    "Good morning! Make today meaningful, not merely busy.",
    "May you have enough courage to chase your goals and enough wisdom to appreciate the journey. Good morning!",
    "Good morning! Sometimes the answer is not to work harder but to think differently. Stay open to new possibilities.",
    "You don't have to know exactly how everything will work out. Take the next step with faith and courage. Good morning!",
    "Good morning! May every setback teach you something and every success remind you to remain grateful.",
    "Today, give your best without worrying about being better than everyone else. Good morning!",
    "Good morning! May your quiet efforts eventually become visible results.",
    "Don't allow a difficult season to convince you that your entire life will be difficult. Better days are possible. Good morning!",
    "Good morning! May you find joy in the little things while working toward the bigger things.",
    "You have another opportunity today to become the person you've been working toward. Good morning!",
    "Good morning! Let your actions today be stronger than the excuses that held you back yesterday.",
    "May your day be filled with productive conversations, meaningful opportunities, and peaceful moments. Good morning!",
    "Good morning! Remember: you don't need everyone's approval to pursue what is right for your life.",
    "Start today with a grateful heart and a determined mind. Good morning!",
    "Good morning! May your patience be rewarded, your courage strengthened, and your dreams brought closer.",
    "Don't be afraid of starting small. Great things often begin quietly. Good morning!",
    "Good morning! May you have the wisdom to recognize an opportunity and the courage to act when it appears.",
    "Every day doesn't have to bring a major breakthrough. Sometimes simply continuing is the victory. Good morning!",
    "Good morning! May you walk into today with confidence, knowing that you are capable of handling more than you realize.",
    "Whatever you're building, don't give up because the results haven't appeared yet. Keep building. Good morning!",
    "Good morning! May today be kinder than yesterday, brighter than you expected, and more productive than you imagined.",
    "Good morning! May this new day bring you renewed strength, fresh opportunities, peace in your heart, and the courage to keep becoming everything you were created to be.",
    "Good morning! May your life continue to blossom like a garden planted by the Lord, and may your presence bring hope, peace, and fragrance wherever you go.",
    "Good morning! Remember, every sunrise is God's invitation to rise above yesterday's limitations and embrace today's possibilities with faith and courage.",
    "Good morning! You may not control the wind, but with God's wisdom, you can adjust your sails and navigate your way toward destiny.",
    "Good morning! The same God who paints the sky with beautiful colours has deposited gifts within you. Arise and let your life become a masterpiece.",
    "Good morning! Never underestimate the power of a renewed mind. With faith in God, a positive attitude, and consistent action, you can turn obstacles into opportunities.",
    "Good morning! You are not an accident, and your life is not without purpose. Walk boldly today, knowing that the Creator has a reason for your existence.",
    "Good morning! A seed does not become a mighty tree overnight. Be patient with your growth, faithful in your efforts, and confident in God's timing.",
    "Good morning! Your words can heal, your smile can encourage, and your kindness can restore hope. Make today beautiful by being a blessing to someone.",
    "Good morning! Do not allow yesterday's failures to become today's fears. Every new morning carries the grace to begin again and the wisdom to do better.",
    "Good morning! Greatness begins in the mind before it becomes visible in your hands. Think with wisdom, speak with faith, and act with purpose.",
    "Good morning! Like the eagle that rises above the storm, may you find the strength to rise above your worries and see your challenges from a higher perspective.",
    "Good morning! You carry more potential than your present circumstances reveal. Keep learning, keep growing, and never allow temporary struggles to define your permanent identity.",
    "Good morning! A heart filled with God's peace can remain steady in a world full of uncertainty. Guard your heart, renew your mind, and let faith guide your decisions.",
    "Good morning! Every act of obedience, every seed of kindness, and every effort made with a sincere heart contributes to a life of lasting impact. Never despise small beginnings.",
    "Good morning! Step into this beautiful day with the confidence that you are loved by God, equipped with a sound mind, and empowered to make meaningful contributions to your generation. Let your light shine!",
    "Good morning! You may not be able to change every situation around you, but you can change your response, strengthen your character, and grow through every experience.",
    "Good morning! The presence of challenges does not mean the absence of God. Keep believing, keep moving, and keep trusting Him even when the road seems unclear.",
    "Good morning! Let gratitude be your attitude and hope be your companion. When you appreciate what you have, you create room in your heart for greater possibilities.",
    "Good morning! Your life is a message that the world is reading. Let your integrity be the ink, your kindness the language, and your faith the inspiration.",
    "Good morning! Do not wait for perfect conditions before you take the first step. Start with what you have, use what you know, and trust God for the wisdom to continue.",
];

$afternoonGreetings = [
    "Good afternoon! Whatever the morning brought, you still have the rest of the day to make progress.",
    "Good afternoon! Take a deep breath, reset your mind, and keep moving forward.",
    "The day isn't over yet. There is still time for something beautiful to happen. Good afternoon!",
    "Good afternoon! May the second half of your day bring clarity, productivity, and unexpected good news.",
    "Don't let a slow morning convince you that the whole day is lost. Start again. Good afternoon!",
    "Good afternoon! Keep going. Sometimes the breakthrough comes after the moment you feel like giving up.",
    "Whatever didn't go according to plan this morning, don't carry the disappointment into the afternoon. Reset and continue.",
    "Good afternoon! May your efforts today produce results that make the waiting worthwhile.",
    "Half the day may be gone, but half the day is still ahead. Make it count!",
    "Good afternoon! You don't need a perfect day to make meaningful progress.",
    "May the rest of your day bring you peace where there has been pressure and solutions where there have been problems.",
    "Good afternoon! Keep your heart hopeful and your mind focused. Better moments may still be ahead.",
    "The afternoon is another reminder that you can pause, breathe, and begin again. Good afternoon!",
    "Good afternoon! May your hard work today become tomorrow's reason to celebrate.",
    "Don't measure your entire day by how it started. Sometimes the best part comes later.",
    "Good afternoon! Stay consistent. Small efforts repeated daily can create extraordinary results.",
    "May you find the strength to finish what you started and the wisdom to know what truly deserves your energy.",
    "Good afternoon! Whatever you're working toward, keep taking those small steps.",
    "A difficult morning doesn't cancel a promising afternoon. Keep believing. Good afternoon!",
    "Good afternoon! May the rest of today be filled with productive moments and peaceful thoughts.",
    "You have survived every difficult day that came before this one. Keep going. Good afternoon!",
    "Good afternoon! Don't allow temporary frustration to make permanent decisions for you.",
    "There is still time to turn things around. Stay focused and keep moving. Good afternoon!",
    "Good afternoon! May an opportunity find you when you least expect it.",
    "Sometimes progress is simply refusing to stop. Keep going this afternoon.",
    "Good afternoon! Your goals haven't disappeared just because today has been challenging.",
    "May the rest of your day remind you that patience and persistence are never wasted.",
    "Good afternoon! Focus on what you can do now instead of worrying about what you should have done earlier.",
    "The afternoon is a chance to adjust your plans, renew your energy, and continue with purpose.",
    "Good afternoon! May your remaining hours be more peaceful, productive, and rewarding than you imagined.",
    "Don't let one bad moment become the story of your entire day. Good afternoon!",
    "Good afternoon! Keep showing up. Consistency often accomplishes what motivation alone cannot.",
    "May your mind remain calm, your decisions wise, and your efforts fruitful this afternoon.",
    "Good afternoon! You may be tired, but remember why you started.",
    "There is still something good waiting to happen today. Keep your heart open. Good afternoon!",
    "Good afternoon! Take things one task at a time. You don't have to solve everything at once.",
    "May your afternoon bring answers to questions you've been carrying and peace to things you cannot control.",
    "Good afternoon! Don't underestimate what you can accomplish with a few focused hours.",
    "The day is still in your hands. Use the remaining hours wisely. Good afternoon!",
    "Good afternoon! May your determination be stronger than your distractions.",
    "When the day gets difficult, slow down—but don't give up. Good afternoon!",
    "Good afternoon! Your effort may not be visible yet, but that doesn't mean it isn't producing something.",
    "Keep planting, keep building, keep believing. Results often take time. Good afternoon!",
    "Good afternoon! May your next conversation bring encouragement, your next decision bring progress, and your next step bring you closer to your goal.",
    "Don't spend the afternoon regretting the morning. Use it to create a better evening.",
    "Good afternoon! A fresh perspective can turn an ordinary situation into a new opportunity.",
    "May you have the strength to finish strong and the grace to appreciate how far you've come.",
    "Good afternoon! Your journey doesn't have to look like anyone else's. Keep walking your path.",
    "There is no wasted day when you learn, grow, or move forward. Good afternoon!",
    "Good afternoon! May the remainder of your day bring you closer to something you've been hoping for.",
    "Stay encouraged. The work you're doing today may become the foundation for something much bigger tomorrow.",
    "Good afternoon! Don't let temporary circumstances determine your permanent outlook.",
    "May you find a reason to smile before this day ends. Good afternoon!",
    "Good afternoon! Sometimes all you need is a short pause, a clear mind, and another attempt.",
    "Your progress doesn't need to impress everyone. It simply needs to keep moving. Good afternoon!",
    "Good afternoon! May your remaining hours be filled with wisdom, favor, and meaningful progress.",
    "If today hasn't gone the way you hoped, there's still time to change how it ends. Good afternoon!",
    "Good afternoon! Stay patient. Some things take longer because they're worth building properly.",
    "Don't give up because you're tired. Rest if necessary, then continue. Good afternoon!",
    "Good afternoon! May you have clarity for your decisions and courage for your next move.",
    "The afternoon sun is a reminder that the day is still shining. Keep your hope alive.",
    "Good afternoon! One productive hour can change the direction of an entire day.",
    "May your afternoon be free from unnecessary worry and filled with things that truly matter.",
    "Good afternoon! You don't have to do everything today. Just make meaningful progress.",
    "Keep your vision ahead of you and your excuses behind you. Good afternoon!",
    "Good afternoon! May today's challenges become tomorrow's wisdom.",
    "Don't let disappointment steal the energy you need to create something better. Good afternoon!",
    "May you find unexpected strength for whatever remains on today's journey.",
    "Good afternoon! Keep working quietly. Not every seed needs to be seen before it grows.",
    "Your best moment today may still be ahead of you. Keep going. Good afternoon!",
    "Good afternoon! Choose peace over unnecessary arguments and purpose over unnecessary distractions.",
    "May your afternoon bring a fresh perspective and a renewed sense of purpose.",
    "Good afternoon! You have enough strength for the next step. Take it.",
    "Don't count the hours you've lost. Make the remaining hours count. Good afternoon!",
    "Good afternoon! May your efforts attract opportunities and your preparation meet the right moment.",
    "Whatever you're facing today, remember that difficult moments are temporary. Keep moving.",
    "Good afternoon! Let go of what you cannot change and put your energy into what you can.",
    "May this afternoon restore your confidence and remind you of what you're capable of achieving.",
    "Good afternoon! Progress may be slow, but slow progress is still progress.",
    "The day isn't finished writing its story. Keep showing up for your own story. Good afternoon!",
    "Good afternoon! May you finish today stronger, wiser, and more hopeful than you started.",
    "Don't allow a mistake from earlier today to stop you from making a better decision now.",
    "Good afternoon! Sometimes the greatest victory is simply choosing to keep going.",
    "May the rest of your day bring calm to your mind and joy to your heart.",
    "Good afternoon! Your future needs the effort you make today.",
    "Don't wait for tomorrow to correct what you can improve this afternoon. Start now.",
    "Good afternoon! May every challenge you face today teach you something that makes you stronger tomorrow.",
    "There is still room for good news, unexpected opportunities, and beautiful moments today. Good afternoon!",
    "Good afternoon! Keep your heart grateful even while you work toward something better.",
    "Good afternoon! May your afternoon be productive without stealing your peace.",
    "Good afternoon! You may be closer to your breakthrough than you realize. Keep going.",
    "Don't let tiredness make you forget your purpose. Rest, recharge, and continue.",
    "Good afternoon! May your remaining hours be guided by wisdom and filled with meaningful progress.",
    "Whatever you're building, give it patience. Great things rarely happen overnight.",
    "Good afternoon! You don't need to have everything figured out. Just take the next right step.",
    "May this afternoon bring a little more hope, a little more strength, and a lot more reasons to smile.",
    "Good afternoon! Keep believing in better possibilities while doing the work required to reach them.",
    "The morning may be gone, but your opportunity to make today meaningful remains. Good afternoon!",
    "Good afternoon! Finish the day with gratitude for what you have learned, pride in what you accomplished, and hope for what comes next.",
    "Good afternoon! Whatever today has brought so far, remember that the story is not finished. Keep your heart hopeful, your mind focused, and your steps steady. There is still time for something good to happen.",
];

$eveningGreetings = [
    "Good evening! As the day comes to an end, remember that you did your best with what you had. Be proud of yourself.",
    "Good evening! Whatever today brought, tomorrow gives you another opportunity to try again.",
    "Let the evening remind you to slow down, breathe, and appreciate how far you've come. Good evening!",
    "Good evening! Not every day will be perfect, but every day can teach you something valuable.",
    "As the sun sets, release the worries of today and make room for the possibilities of tomorrow. Good evening!",
    "Good evening! You may not have accomplished everything today, but every honest effort counts.",
    "Take a moment tonight to appreciate the little victories that often go unnoticed. Good evening!",
    "Good evening! Don't carry today's disappointments into tomorrow. Learn from them, release them, and keep moving.",
    "The day is ending, but your journey continues. Rest tonight and return stronger tomorrow.",
    "Good evening! Sometimes the greatest progress is simply having the courage to keep going.",
    "May your evening bring peace to your mind and gratitude to your heart. Good evening!",
    "Good evening! Whatever didn't work today is not the end of the story. There is always another opportunity.",
    "Close the day with gratitude for what you have, wisdom from what you experienced, and hope for what lies ahead.",
    "Good evening! You survived every challenge that came your way today. Give yourself credit.",
    "Let go of what you cannot change tonight and focus on what tomorrow gives you the power to change.",
    "Good evening! Rest is not weakness. Sometimes stepping back is exactly what you need to move forward.",
    "May the quietness of this evening give you clarity for the decisions ahead. Good evening!",
    "Good evening! Don't judge your entire journey by one difficult day.",
    "Whatever happened today, don't forget that you are still growing, learning, and becoming better.",
    "Good evening! May tonight bring you the peace that a busy day could not give you.",
    "The sun has set, but hope doesn't have to. Keep believing in better days. Good evening!",
    "Good evening! Celebrate the progress you made today, no matter how small it may seem.",
    "Tonight, give yourself permission to rest without feeling guilty. Tomorrow needs a renewed version of you.",
    "Good evening! Your mistakes today can become the wisdom you need tomorrow.",
    "As this day ends, remember that setbacks are moments, not destinations. Good evening!",
    "May your heart be lighter tonight and your confidence stronger tomorrow. Good evening!",
    "Good evening! You don't need to have everything figured out tonight. Some answers come with time.",
    "Let tonight be a reminder that every ending creates space for a new beginning.",
    "Good evening! Be thankful for the lessons, the people, the opportunities, and even the challenges that shaped today.",
    "Whatever you're building, give it time. Good things often grow quietly. Good evening!",
    "Good evening! Don't let today's disappointments convince you that tomorrow won't be better.",
    "May tonight restore your energy and tomorrow renew your determination.",
    "Good evening! Sometimes you win, sometimes you learn. Either way, you move forward.",
    "Take a deep breath. The day's work is done. Give your mind permission to rest. Good evening!",
    "Good evening! Your worth isn't measured by how much you accomplished today.",
    "Tonight, be gentle with yourself. You're still becoming the person you want to be.",
    "Good evening! Tomorrow doesn't require you to be perfect—only willing to try again.",
    "May the peace of this evening replace the pressure of the day. Good evening!",
    "Good evening! If today didn't go as planned, don't lose hope. Plans can change, and opportunities can return.",
    "Before you sleep, remember at least one thing from today that deserves your gratitude.",
    "Good evening! The chapter of today may be closing, but your story is far from finished.",
    "Let go of the day's stress. Tomorrow is another canvas waiting for you. Good evening!",
    "Good evening! Your journey may be taking longer than expected, but that doesn't mean you're going in the wrong direction.",
    "May tonight remind you that you have made it through difficult seasons before. You will get through this one too.",
    "Good evening! Rest knowing that you don't have to solve every problem in one day.",
    "Don't let one bad experience erase all the good things that happened today. Good evening!",
    "Good evening! Be grateful for every small step that brought you closer to where you want to be.",
    "May your evening be peaceful, your thoughts be calm, and your heart be hopeful.",
    "Good evening! Some battles are won simply by refusing to give up.",
    "As the day closes, release the pressure to have everything figured out. Give yourself time to grow.",
    "Good evening! What matters is not whether today was perfect, but whether you kept moving.",
    "May tonight give you the strength to wake up tomorrow with renewed purpose.",
    "Good evening! Every experience carries a lesson if you're willing to see it.",
    "The day may be ending, but possibilities are never limited to today. Good evening!",
    "Good evening! Don't punish yourself for what you didn't know yesterday. Learn and move forward.",
    "May the quiet of tonight help you hear the wisdom that was difficult to hear during the busy day.",
    "Good evening! You are allowed to rest, reset, and start again.",
    "Leave today's worries where they belong—in today. Tomorrow deserves a fresh mind.",
    "Good evening! Sometimes the best thing you can accomplish at the end of a difficult day is finding peace.",
    "May your evening be filled with gratitude for what worked, acceptance of what didn't, and hope for what comes next.",
    "Good evening! Keep believing that your efforts will eventually produce something meaningful.",
    "Tonight, remember how many things once seemed impossible that you eventually overcame.",
    "Good evening! Don't compare your progress with someone else's timeline. Your journey has its own pace.",
    "May you find comfort in knowing that every day gives you another chance to improve.",
    "Good evening! The fact that you're still trying is already evidence of your strength.",
    "Let tonight be a pause, not a surrender. Rest and prepare to continue tomorrow.",
    "Good evening! You may have had a difficult day, but you don't have to have a difficult night.",
    "May your mind find peace, your body find rest, and your heart find hope tonight.",
    "Good evening! Sometimes tomorrow's breakthrough begins with tonight's decision not to give up.",
    "Don't carry anger, disappointment, or unnecessary worry into your rest. Protect your peace.",
    "Good evening! Be proud of the person who kept showing up even when things weren't easy.",
    "May tonight remind you that progress doesn't always look dramatic. Sometimes it looks like simply continuing.",
    "Good evening! Your future is still full of possibilities you haven't discovered yet.",
    "Take tonight to recharge, reflect, and prepare for another opportunity tomorrow.",
    "Good evening! The things you're working toward may take time, but patience doesn't mean you're standing still.",
    "May you sleep tonight knowing that you did what you could with the day you were given.",
    "Good evening! Don't allow today's failures to overshadow tomorrow's possibilities.",
    "Sometimes the strongest thing you can say at the end of a difficult day is, \"I'll try again tomorrow.\"",
    "Good evening! May your heart be free from yesterday's regrets and tomorrow's anxieties.",
    "Let the sunset remind you that even the busiest days eventually come to an end. Good evening!",
    "Good evening! You don't have to carry every burden alone. Allow yourself to pause and breathe.",
    "May tonight bring you perspective about the things that seemed overwhelming earlier today.",
    "Good evening! Every day you keep going, you become a little stronger than the person you were yesterday.",
    "Rest tonight without fear. You can face tomorrow one step at a time.",
    "Good evening! Don't underestimate the power of a peaceful mind and a rested body.",
    "May you end this day with more gratitude than regret and more hope than worry.",
    "Good evening! Sometimes life changes quietly before the results become visible. Keep trusting the process.",
    "Whatever you're waiting for, don't let the waiting steal your ability to enjoy today. Good evening!",
    "Good evening! There is something beautiful about knowing you can begin again tomorrow.",
    "Let tonight be a reminder that you are not defined by your worst moment, your biggest mistake, or your hardest season.",
    "Good evening! May the lessons of today become the wisdom that guides you tomorrow.",
    "The day is over. Give yourself permission to stop worrying about what you cannot change tonight.",
    "Good evening! You made it through today. That's something worth acknowledging.",
    "May tomorrow bring new opportunities, but tonight, give yourself the gift of rest.",
    "Good evening! Keep your dreams alive, even when the journey feels slower than you expected.",
    "Tonight, focus less on what went wrong and more on what you can do differently tomorrow.",
    "Good evening! Your story isn't finished because today was difficult. There are still better chapters ahead.",
    "May your evening be a peaceful bridge between everything you've experienced today and everything you're hoping for tomorrow.",
    "Good evening! Close today with gratitude, release what you cannot control, and prepare your heart for a fresh beginning.",
    "Good evening! As today comes to an end, may you find peace in what you've accomplished, wisdom in what you've learned, and hope for what tomorrow will bring. Rest well—you have another beautiful opportunity waiting for you.",
];

function Wo_RandomMorningGreeting()
{
    global $morningGreetings;

    return $morningGreetings[rand(0, count($morningGreetings)-1)];
}

function Wo_RandomAfternoonGreeting()
{
    global $afternoonGreetings;

    return $afternoonGreetings[rand(0, count($afternoonGreetings)-1)];
}

function Wo_RandomEveningGreeting()
{
    global $eveningGreetings;

    return $eveningGreetings[rand(0, count($eveningGreetings)-1)];
}
