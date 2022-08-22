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
    "Good Morning! May your day be filled with positive things and full of blessings.",
    "Good morning to you. May every step you make be filled with happiness, love, and peace.",
    "May this morning offer you new hope for life! May you be happy and enjoy every moment of it. Good morning!",
    "Good Morning! I hope my good morning text will bring a smile on your face at the very beginning of the day.",
    "Every morning is a new blessing, a second chance that life gives you because you're so worth it. Have a great day ahead. Good morning!",
    "Get up early in the morning and don't forget to say thank you to God for giving you another day! Good morning!",
    "Good morning, my friend! Life gives us new opportunities every day, so hoping today will be full of good luck and prosperity for you!",
    "Good Morning, dear! May everything you dreamed about come true!",
    "Good morning beautiful. I hope you have a wonderful day.",
    "Sending you good vibes to start your morning with positive energy! Good morning!",
    "Life never gives you a second chance. So, enjoy every bit of it. Why not start with this beautiful morning. Good morning!",
    "Life is full of uncertainties. But there will always be a sunrise after every sunset. Good morning!",
    "God has granted you yet another day to make your dreams come true. Accept it with all your heart. Let's give your life a new start. Good morning!",
    "Each day is an opportunity to grow. I hope we make the most of it. Wishing you a perfect morning.",
    "If you want to gain health and beauty, you should wake up early. Good morning!",
    "Every morning brings you new hopes and new opportunities. Don't miss any one of them while you're sleeping. Good morning!",
    "Every sunrise marks the rise of life over death, hope over despair, and happiness over suffering. Wishing you a delightful morning today!",
    "Wake up and make yourself a part of this beautiful morning. A beautiful world is waiting outside your door. Have an enjoyable time!",
    "Welcome this beautiful morning with a smile on your face. I hope you'll have a great day today. Wishing you a very good morning!",
    "The best way to start a day is waking up early in the morning and enjoying nature with a cup of coffee. I hope you're doing this right now. Good morning!",
    "It's time to wake up, take a deep breath, and enjoy the sweetness of nature with all your heart. Good morning! Have a good time!",
    "Mornings define our day. It's all about how we start every morning. So, get up and make a good start of yet another beautiful day. Good morning!",
    "Breathing in the fresh morning air makes you healthier and wiser. Don't ignore the blessings that every morning offers to us. Good morning and have a good time.",
    "I know you slept tight all night. Now wake up and welcome a new sun so bright, here to make your day right. Good morning!",
    "You have been blessed with yet another day. What a wonderful way of welcoming the blessing with such a beautiful morning! Good morning to you!",
    "May your day goes as bright as the sun is today! Good morning to you!",
    "Good morning! May the day ahead bring you blessings and God's abundant kindness!",
    "Waking up in such a beautiful morning is a guaranty for a day that's beyond amazing. I hope you'll make the best of it. Good morning!",
    "Nothing is more refreshing than a beautiful morning that calms your mind and gives you reasons to smile. Good morning! Wishing you a great day.",
    "Another day has just started. Welcome the blessings of this beautiful morning. Rise and shine like you always do. Wishing you a wonderful morning!",
    "Good morning, no matter how hard yesterday was, today is a new beginning, so buckle up and start your day.",
    "I hope this day brightens up your life and makes you energized for work. Good morning!",
    "May the freshness of this morning keep your mind fresh and calm the whole day. Good morning!",
    "Wake up like the superstar you are and let the world know you're not going to stop until you get what you deserve. Good morning my dear!",
    "A new day has come with so many new opportunities for you. Grab them all and make the best out of your day. Here's me wishing you a good morning!",
    "The darkness of night has ended. A new sun is up there to guide you towards life so bright and blissful. Good morning dear!",
    "Wake up, have your cup of morning tea, and let the morning wind freshen you up like a happiness pill. Wishing you a good morning and a good day ahead!",
    "No matter how difficult yesterday was, just know that today is your day. Stay positive at every moment of your life. Good morning.",
    "Rise and shine, and get ready for another exciting sunny day! Good morning!",
    "Good morning! Start your morning with the right attitude and make the best out of the day!",
    "Every day is a new opportunity to thrive anew, so don't stay stuck in yesterday's mistakes. Good morning!",
    "Today, let us remember that nothing in life is guaranteed. It is uncertain, and the future can not be foretold. Hence, we must learn to make the best out of the present. Be sure to make the most out of your day today. Good morning.",
    "Good morning! Sprinkle some positive thoughts onto your life right after you wake up!",
    "Good morning! The gifts of the world await your presence, get up and indulge in nature!",
    "If you can wake up early in the morning, you are among the few blessed people who know how good it feels to breathe in the fresh air. Good morning!",
    "Good morning. In life, you will come across several kinds of people. Some will hurt you; some will test you. Some will use you, while some will bring out the best in you.",
    "Good morning! Focus on the blessings life has given you and forget the sorrows, and you'll surely be happy.",
    "You cannot change your life in a minute, but one wrong decision will be more than enough to change your life in a blink. Therefore, be wise, stay calm and always think twice before making your decisions this morning.",
    "Life is the most precious of all gifts. So, enjoy every moment of it. Don't miss the most of it in sleeping too late. Good morning!",
    "A beautiful morning does not solve all your problems, but it gives you a good start to solve any of your problems. Good morning!",
    "Enjoy the moments you have now because once they are gone, they are gone forever. Good morning!",
    "Good morning, buddy. Each new day comes with a new opportunity, so make sure you don't miss any of the opportunities life gives you.",
    "Enjoy the morning and have faith in yourself because you can do it. Good morning!",
    "Good morning, friend. Wake up and enjoy the beautiful morning. May this day bring you peace and joy.",
    "Good morning, my friend. I'm just here to tell you how amazing you are, and I'm so lucky to have a friend like you. I hope you will have a good day.",
    "I thank god every morning for giving me such a good friend like you. You deserve to be happy today and every day. Good morning!",
    "Good morning best friend. Forget what happened yesterday, focus on the day you have and keep your eye on the things that tomorrow offers.",
    "Good morning my friend. Every morning life gives us another chance to improve ourselves - so don't lose hope. I hope you have a great day ahead.",
    "It doesn't matter how bad was your yesterday. Today, you are going to make it a good one. Wishing you a good morning!",
    "Waking up every morning knowing that I have a friend like you gives me so much courage and fills my heart with hopes. Good morning to you!",
    "Open your eyes and have a look at this beautiful morning. You'll find happiness in every moment of it. Good morning to you!",
    "Your friendship is an asset to keep. I thank god every day for making you a part of my life. Good morning my friend. A good day waits for you!",
    "Dear friend, don't stress over the worries of yesterday, rather accept what today has to offer with wide arms! Good morning to you!",
    "Good morning, buddy! I hope you start this day with a smile on your face and hope in your heart!",
    "Life is nothing but a daily struggle to make your dreams come true. And every morning is an opportunity to make yourself prepared for it. Good morning!",
    "Rise and shine, bud! Sending all my warm thoughts your way so you can start your day with a fresh mind and determination! Have a nice day!",
    "Life seems so beautiful because of the supportive people we have around us. You are one of them. Good morning, dear friend. Have a nice day!",
    "May you have a wonderful day to remember today. Good morning my friend!",
    "It's another beautiful day full of hope and new possibilities. Don't miss the goodness of the morning and try to use every moment of the day. Good morning, friend.",
];

$afternoonGreetings = [
    "Good, better, best. Never rest until your good is better and your better is best. Good afternoon.",
    "As you climb the ladder of success, occasionally check to make sure it is leaning against the right wall. Good afternoon and good day!",
    "Never stop believing in hope because miracles happen every day. Good afternoon.",
    "Learning history is so easy but making history is so difficult. Make a history of yourself and make others learn it! Good afternoon!",
    "Under certain circumstances, there are few hours in life more agreeable than the hour dedicated to the ceremony known as afternoon tea.", 
    "If I were a dove, I would bring you peace. A sheep, I would bring you miracles, an angel everyone you love, but since I'm only human, I can only wish you the best. Good afternoon.",
    "I love the afternoons so much because the beautiful rays of sunshine are a reminder of your beautiful face even when you are miles away.",
    "Wishing you the best of the best that the afternoon has to bring to the table, Have a good afternoon!",
    "This afternoon is a prize for you after a restless day. However, expose yourself to this refreshing afternoon wind and let it fill you with expectation, dream, and determination. Good afternoon!",
    "Being in love with you is the sweetest feeling I have ever known. However, I want to say thank you for making my days brilliant. Good afternoon!",
    "This bright afternoon sun always reminds me of how you brighten my life with all the happiness. I miss you a lot this afternoon. Make some good memories!",
    "Time to recall sweet people in your life. Also, I know I will be first on the list. Thanks for that. Good afternoon my dear!",
    "Every moment of my life is enjoyable if we spent them with you. Therefore, let's make this afternoon an important one together. Good afternoon, my love!",
    "My friends, it is already late and soon the workday will reach a conclusion, so it is time to put forth a last attempt and prepare ourselves to return home. I trust you have a good afternoon.",
    "A relaxing afternoon wind and the sweet pleasure of your organisation can fill my heart with joy completely. Missing you so severely during this time of the day! Good afternoon!",
    "My love, I trust you are doing great at work and that, you recollect, I will wait for you at home with my arms open to spoil you and give you all my love. Also, to wish you a good afternoon!",
    "I want you when I get up in the morning; I want you when I rest at night and I want you when I relax under the sun in the afternoon!",
    "The Morning spent with your family; the afternoon spent with your works; the evening spent with your friends; the night spent with your dreams, all cause a perfect day, to have a happy noon!",
    "The dark blue sky of this bright afternoon reminds me of the deepness of your heart and the brightness of your spirit. However, May you have an essential afternoon!",
    "Mornings are for starting another work, Afternoons are for remembering, Evenings are for refreshing, Nights are for relaxing, So recollect individuals, who are remembering you, Have a happy noon!",
    "Also, May your good afternoon be light, blessed, enlightened, productive and happy.",
    "Good afternoon! May the sweet harmony be part of your heart today and always and there is life shining through your sigh. May you have a lot of light and harmony.",
    "Fall in love with the energy of the mornings, trace your fingers along the lull of the afternoons, take the spirit of the evenings in your arms, kiss it profoundly and afterward have intercourse to the tranquillity of the nights.",
    "Wishing you an afternoon experience so sweet that vibe is thankful to be alive today. Also, May you have the best afternoon of your life today!",
    "I petition god that he keeps me close to you so we can enjoy these beautiful afternoons together until the end of time! Wishing you a good time this afternoon!",
    "May this beautiful afternoon fill your heart with boundless happiness and give you new would like to start yours with. However, May you have a lot of fun! Good afternoon dear!",
    "As the blazing sun gradually starts making its way to the west, I want you to know that this beautiful afternoon is here to favour your life with progress and harmony. Good afternoon!",
    "Regardless of what time of the day it is, no matter what I am doing, no matter what is right and what is wrong, I still recollect you like this time, Good Afternoon!",
    "Your love is like the platelets that run inside my veins. Your presence in life is more than a necessity to me. Also, want to say I love you! Good afternoon my love!",
    "The afternoon is a perfect time to energise your drained-up self after a toiling day. Therefore, this afternoon is supportive of you to take a full breath and start the excursion by and by.",
    "In you, I have discovered the sweetest gift of my life. However, your love causes me to feel grateful to God. Good afternoon dear!",
    "Afternoons like this make me think about you more. I desire so profoundly to be with you in one of these afternoons just to tell you the amount I love you. Good afternoon my love!",
    "There are few hours in life more pleasant than the hour dedicated to the service known as afternoon tea. Take it and energise yourself for the rest of the day.",
    "You could get a simple idea one afternoon that could completely transform you. Great innovators just needed one idea to make an enormous difference. Also, to continue striving and you will arrive at success.",
    "There are no mistakes, no coincidences, all events are blessings given to us to learn. An excursion of a thousand miles started with just one step. Good Afternoon!",
    "Wishing a fantastic afternoon for the most beautiful soul I have ever met. I trust you are having a good time relaxing and enjoying the beauty of this time!",
    "Nature looks quieter and more beautiful at this time of the day! Also, you truly don't want to miss the beauty of this time! Wishing you a happy afternoon!",
    "You are all of special to me, just like a relaxing afternoon is special after a toiling noon. Thinking of my special one in this special time of the day!",
    "As you prepare yourself to wave goodbye to another wonderful day, I want you to know that I am thinking of you constantly. Good afternoon!",
    "Every afternoon spent with you gives me extra reasons to fall in love with you again and again. Also, you mean the entire world to me. Good afternoon!",
    "This afternoon is here to quiet your canine tired mind after a hectic day. Enjoy the blessings it offers you and be thankful always. Good afternoon!",
    "The day has come to a halt, realising that I am yet to wish you a splendid afternoon. My dear, if you thought you were forgotten, you're so off-base. Good afternoon!",
    "Every afternoon is to recollect the one whom my heart beats for. Therefore, you're the one I live and sure can die for. Expectation you doing good there, my love. Missing your face.",
    "With a blue sky over my head and a relaxing wind around me, the main thing I am missing right now is the sight of you. I wish you a refreshing afternoon!",
    "With you every day is my lucky day. So lucky being your love and don't know what else to state. Morning, night and noon, you fill my heart with joy.",
    "Things are changing. I see everything turning around in my favour. Also, the last time I checked, it's courtesy of your love. 1000 kisses from me to you. I love you truly and wishing you a happy noon.",
    "Denotation of noon is not just media of the day, but it is the impenetrable time to do anything in our life. So covering this noon happily, good afternoon.",
    "However, May we bless regularly your life from dusk till sunrise. Good afternoon to my dearest friend. Enjoy this beautiful time of the day to the fullest!",
    "I trust you had a wonderful morning with my morning wishes. Here I am sending my afternoon wish to you to have outstanding noon, Happy noon my dear friend!",
    "The gentle afternoon wind feels like a sweet embrace from you. Also, you are in all my thoughts on this wonderful afternoon. Expectation you are enjoying the time!",
    "My wishes will always be with you, Morning wish to cause you to feel fresh, Afternoon wishes to accompany you, Evening wishes to refresh you, Night wishes to comfort you with rest, Good Afternoon Dear!",
    "You're always in my thoughts and petitions. I appeal to God for your afternoon to be filled with blessings. Good afternoon.",
    "Noon time - it's time to have a brief break. Breathe the warmth of the sun, Who is shining up in between the clouds, Good afternoon!",
    "How I wish the sun could obey me for a second, to stop its scorching ride on my angel. So sorry it will be hot there. Don't worry, the evening will before long come. I love you.",
    "Darling, though I am not with you right now, I trust your afternoon is as beautiful as you may be. Good afternoon my love. I love you.",
    "You are the cure that I need to take three times every day, in the morning, at the night and in the afternoon. I am missing you a lot right now. Good afternoon!",
    "May this afternoon bring a lot of pleasant surprises for you and fill your heart with infinite delight. Wishing you a warm and love filled afternoon!",
    "I trust you have a delightful afternoon and that you keep up your optimism because the mind is powerful enough to do great things.",
    "You are a blessed soul if you are still alive to experience this amazing afternoon today. Take your inspiration from this bright sun and make your life wonderful.",
    "I wish you have a pleasant afternoon, perform activities in which you feel comfortable, and encircle yourself with the individuals that you love the most.",
    "At the point when friendship is your greatest weakness, you will be the strongest person on the planet! 'Good Afternoon'",
    "Your love is sweeter than what I read in romantic novels and fulfilling more than I find in epic films. I couldn't have been me, without you. Good afternoon, I love you!",
    "Learning history is so natural but making history is so difficult. Make a history of yourself and make others to learn it! Good Afternoon!",
    "You must be so tired after a taxing day, but do you what? The day is still so young and loaded with positive energy for you to absorb. Good afternoon!",
    "My heart needs for your organization constantly. A beautiful afternoon like this can be made more enjoyable if you just decide to go through it with me. Good afternoon!",
    "I wish I were with you this time of the day. We barely have a beautiful afternoon like this nowadays. Wishing you a tranquil afternoon!",
    "Afternoon has come to indicate you, Half of your day's worth of effort is finished, Just another a large portion of a day to go, Be brisk and continue enjoying your works, Have a happy noon!",
    "Your presence could make this afternoon considerably more pleasurable for me. Your organisation is what I cherish constantly. Good afternoon!",
    "Be bright like the afternoon sun and let everyone who sees you feel inspired by all the great things you do. You have one life here on earth. Make the most of it in whatever way you can. Good Afternoon!",
    "My wishes will always be with you. However, I wish you a morning wish to cause you to feel fresh, afternoon wishes to accompany you, evening wishes to refresh you, Night wishes to comfort you with rest, good afternoon dear!",
    "Good times always appear after the hardest time in your life. Just like a relaxing afternoon takes place after a hectic noon. Wish you all the best for the coming days.",
    "Sleeplessness at night is not always a medical condition for certain individuals. Sometimes it's the punishment from God for those who rest during such a beautiful afternoon!",
    "I will always be there for you as long as I have a beating heart. However, you are my. Wishing my love a glorious afternoon!",
    "The best thing about this afternoon is that I have you with me to share it together. I expect making it a vital one. Good afternoon!",
    "At the point when I take a gander at you, there is this charming beauty in you. I can't resist your charm. Good afternoon.",
    "If you want to skip the hectic noon every day in your life, you rest late at night and wake up after the noon. Good afternoon my friend. You're doing it so right!",
    "We ought to contemplate more sunsets next to the individuals we want the same number of them to look like beautiful paintings that nature gives us to contemplate, relax and reflect. Good afternoon!",
    "What a wonderful afternoon to finish your day with! I trust you're having a great time sitting in your gallery, enjoying this afternoon's beauty!",
    "SUCCESS is century make it, PROBLEM is yorker face it, FAILURE is bouncer leave it, LUCK is full toss use it, but an OPPORTUNITY is a free hit never miss it, Good Afternoon my friends.",
    "The biggest motivation is your own thoughts, so think big and motivate yourself to win. Good Afternoon!",
    "Good, better, best. Never let it rest. Till your good is better and your better is best. 'Good Afternoon'",
    "I was enjoying this beautiful afternoon so much when I thought of you and said to myself, that idiot friend of mine must be sleeping. What a misfortune of you to miss such a beautiful afternoon!",
    "I trust things have been outstanding in the morning, but if they were not, so don't worry, you still have a few hours in the afternoon to finish the day in the best way. I miss you definitely and I will send you all my good vibes.",
    "Let go of all your troubles and light up your reality with the brightness of this afternoon. Also, you have a long way to go still. I wish you an afternoon that's brimming with inspiration!",
    "Your dream doesn't have an expiration date. Take a full breath and try again. Good Afternoon Dear.",
    "If you feel tired and sluggish, you could utilise a nap. Also, it will assist you with recovering your energy and feeling improved to finish the day. Have a beautiful afternoon!",
    "Life is a magic. The beauty of life is the next second, which hides thousands of secrets. Therefore, I wish every subsequent will be wonderful in your life. Good Afternoon!",
    "You can go to your balcony and take a full breath, examine the bright sky and plan for tomorrow. But you decide to take a nap. That's a good afternoon for you, old buddy!",
    "Sweet things to be recalled, good things to be recollected, nice persons to be recollected. Afternoon is the best time for it. Just think of me. All the above will be get done. Good noon!",
    "There are good and bad moments, but when a friend is next to you, everything is different. Also, to tell you happiness turns out to be more immense and pity is easier to cope with, so I wish you a sunny afternoon, dear friend.",
    "I don't always say good afternoon to anybody, but when I state, I express it to imply that I'm prepared for the afternoon party!",
    "As you climb the ladder of success, check occasionally to make sure it is leaning against the right wall. Also, to wish you Good Afternoon and Good Day!",
    "The afternoon knows what the morning never suspected.",
    "Good, better, best. Never let it rest. 'Til your good is better and your better is best. Good Afternoon.", 
    "Good afternoon guys, just remember that no matter where you are right now focused and a positive mindset can lead you to where you want to be in life.",
    "Happiness is a hot bath on a Sunday afternoon.",
    "If you can spend a perfectly useless afternoon perfectly uselessly, you have learned how to live.",
    "Afternoons are hard. Mornings are pure evil from the pits of hell, which is why I don't do them anymore.",
    "Spend the afternoon. You can't take it with you.",
    "I write in the morning, I walk in the afternoon and I read in the evening. However, it's a very easy, lovely life.", 
    "There are few hours in life more agreeable than the hour dedicated to the ceremony known as afternoon tea.",
    "Have you stuck again in an afternoon with nothing to do? Start with your dreams, decide where you want to be and create a plan to reach there. Every little step will lead you closer to your destiny.",
    "The afternoon is not only the middle of the day, it is the time to complete our essential task and go ahead in life.",
];

$eveningGreetings = [
    "Evenings allow you to forget the bitter worries of the day and get ready for the sweet dreams of night. Evenings give you relaxation from a stressful day. Good evening!",
    "A very good evening to you. Just wish that you have a good time. Let the fun begin with that super wide grin. Have a great time. Good evening!",
    "Stay happy and the situation will also turn happy, stay positive and keep smiling, and always stay in bliss. Good evening!",
    "Evenings are great not because it is the coolest time of the day, but because it lets you reflect on your whole day and forget your yesterday.",
    "Good evening!Sunsets are amazing, hope you are enjoying your evening perfectly!",
    "Good evening, hope you had a wonderful day and may you have a celebratory night!",
    "Evening is beautiful, it comes with so many feelings, hope you have a good one!",
    "Take a look at the sunset and see the beautiful colors of life, good evening!Good evening to the most gorgeous person just like a sunset.",
    "It's a thing about evenings, it brings joy and a feeling of home. Good evening!Good evening, hope you are enjoying your day, may you always love what you do!",
    "Hope you are having a productive day and may you have a calculative tomorrow. Good evening and enjoy the rest of the day!",
    "Good evening to the most beautiful person, you are the reason for your smile and happiness everyday. Keep spreading love and light!",
    "Sunsets are beautiful to watch, may all you problems set with the setting sun, hope you have a better tomorrow. Good evening!",
    "Some days are going to be tough, some are going to be good but you have to keep your head strong and believe in yourself. Good evening!",
    "Every day brings unknown opportunities and challenges. But it ends with a peaceful evening. Hope you are enjoying this evening after a rewarding day. Good Evening dear friend!",
    "Good evening! I know your love for gossips, and evening is the perfect time to start that. Enjoy the company of your friends.",
    "The evening is the time to forget all worries and welcome a good night sleep. Happy Good Evening.",
    "I miss your presence every evening. Our hot cup of coffee, long talks and game of chess. Miss you a lot, good evening.",
    "Do you know why you receive good evening message every day? Because I miss your presence every evening. Good Evening love!",
    "After you have left me, the evening has become extremely lonely. I miss you every day and the most in the evening. Sending warm wishes for Good Evening.",
    "There are two things I miss here every evening. Your gossip, and a cup of tea. Love you darling!",
    "Celebrate each moment of life, be it morning or evening. Have a great evening.",
    "From morning till evening, life is full of momentous moments. Have a happy evening!",
    "Every evening says the day is about to end, and so your struggle. Be calm and relax. Get ready for the next challenge. Good Evening!",
    "It's the evening, a time to register your mistakes and get ready for the next day. Good Evening and all the best for the next day.",
    "Good Evening! Without you guys, the evening seems so dull and boring. And with you, it is the part of the day, which is most happening and amazing.",
    "Good evening dear friends! You were the reason I used to feel so glad in the evening.",
    "May the serenity of this beautiful evening touch your soul and put your mind at ease. Wish you a lovely evening!",
    "No matter how busy I stay, you are always on my mind. Have a wonderful evening, love.",
    "Wishing you a great evening and a wonderful life to you, my dear friend. Never forget how special you are!",
    "May the setting sun take down all your sufferings with it and make you hopeful for a new day. Good evening!",
    "Thank you for making my days beautiful and evenings full of joy. You are the reason behind all my smiles and laughs. Wishing you a good evening.",
    "It doesn't matter how hectic your day was, you can't help admiring the beauty of this evening. I hope you are having a good time right now! Good evening!",
    "Good evening dear. Thank you for making my evenings so beautiful and full of love.",
    "Whether your day was good or bad, it has come to an end. Good evening and good luck for tomorrow.",
    "Evening is a good time to look back at your day and think about everything you have done. Enjoy your evening with positive thoughts.",
    "I wish you an amazing evening full of gossip and coffee. Just know that you are always in my mind. Enjoy this evening to the fullest!",
    "It doesn't matter where I am and what I do, you will always be in my mind and in my heart. I am missing you a lot at this evening!",
    "Happiness can't be behind sorrow, It is your choice to make a better tomorrow, Enjoy this beautiful day with a lovely smile, Good evening!",
    "Evenings are your chance to forget the mistakes you made during the day, so you can have the way for the sweetest of dreams. Good evening!",
    "Evening is a good time to look back at the day and think about all the good things you have done. I wish you an evening so full of satisfaction and inspiration.",
    "Good evening my friend, take a sip of your coffee and forget the troubles of the day.",
    "If I have another life to live, I'd still choose another lifetime with you. I can only find real joy and happiness in your arms. I love you. Good evening.",
    "Evenings are just like you, full of colors and new hopes. I wish you a good evening my love.",
    "It's a perfect time to get rid of your worries and make yourself prepared for what's coming tomorrow. Make this evening the beginning of a wonderful journey.",
    "Evenings are for relaxing with a cup of tea and preparing yourself for tomorrow. Good evening buddy!",
    "The sun sets every evening with a promise to rise once again at dawn. Evenings are so full of hope and inspiration. Wishing you a very wonderful evening!",
    "I hope you are having a refreshing evening as I am having here thinking of you. Good evening, my love.",
    "Sometimes, the best thing you can do is not think, wonder, imagine, and not obsess. Just breathe and have faith that everything will work out for the best. Good evening!",
    "I want to see all the beautiful sunsets of my life only with you my love. Good evening!",
    "As you look at the setting sun, forget everything that's bothering you. Good evening!",
    "Friends like you are the reason why there's never a sunset in my life's happiness. Good evening.",
    "Good evening my dear friend. No matter how hard your day was, stay optimistic and start afresh tomorrow.",
    "I know today was hard for you but I also know that tomorrow will come with new hopes and aspirations. Good evening my friend, keep fighting.",
    "Evenings are simply the blessings of looking at your mistakes and working on them. Never miss your chance to overview your day with your evening tea. Good evening bestie!",
    "Hope you relax your day with a fine cup of coffee and have a blessed evening enjoying the beauty of nature. Have a great evening, friend.",
    "Dear friend, enjoy this beautiful evening with a cup of tea and forget about your tiredness and loneliness.",
    //"If you are seeing this message you are an idiot because only an idiot can ignore the beauty of this evening by checking random messages on his cell phone!",
    "Good evening my friend. We haven't met for so many days. Let's meet in this beautiful evening and catch up on things.",
    "Have a look at the horizon where the sun sets and make a promise to yourself that you'll do better tomorrow. Wishing you a very good evening.",
    "Evening welcomes darkness into this world. And the one that welcomes darkness also welcomes the ghosts. I wish you an evening full of ghostly experiences!",
    "You know the time when you start to feel a bit sleepy, but you can't go to bed because mommy says its time to study. Guess what, It's a good evening dear friend!",
    "The sun sets in the evening today with the promise that it will rise again tomorrow. Here's hoping that this awesome day comes to a close with the promise that there will be better tomorrow. Good evening.",
    "Wish you some good snacks and nonstop laughter on this lovely evening.",
    "Here is my wish for you to have a great evening, Have a cup of coffee, relax and finish off the day's work, Good evening and have a great time!",
    "There's no need to add sugar to your evening coffee because you've just been poked by a sweet person like me. Good evening friend.",
    "Your character does not depend on your situation in life, It all depends on your will and spirit to succeed in life, So stay in bliss, Good evening!",
    "I hope our friendship always remains as beautiful and breathtaking as a picturesque sunset. Good evening.",
    "I wish you have a good evening; spending more time with yourself and find out what do you really want to do! Evenings are pure blessings.",
    "As the sun sets in the evening, it actually gives you another chance to be bold and fierce. Take your chances and renovate yourself for the new day.",
    "Evenings are comforting because they mark the endings of our hectic days. So, enjoy the darkness before life gets busy again.",
    "It Doesn't matter whether you are spending the evening at home or going outside, I just hope that you're having a good time!",
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
