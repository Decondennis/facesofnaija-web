<?php
// +------------------------------------------------------------------------+
// | @author Chibuike Mba (Kemonai)
// | @author_url 1: http://www.kemonai.com
// | @author_email: info@kemonai.com   
// +------------------------------------------------------------------------+
// | WoWonder Addon - Community feature for wowonder
// | Copyright (c) 2021 Kemonai. All rights reserved.
// +------------------------------------------------------------------------+

function Wo_RegisterCommunity($registration_data = array()) {
    global $wo, $sqlConnect;
    if (empty($registration_data)) {
        return false;
    }
    if (!empty($registration_data['category'])) {
        if (is_array($wo['community_categories']) && !in_array($registration_data['category'], array_keys($wo['community_categories']))) {
            $registration_data['category'] = 1;
        }
    }
    
    $registration_data['registered'] = date('n') . '/' . date("Y");
    $fields                          = '`' . implode('`, `', array_keys($registration_data)) . '`';
    $data                            = '\'' . implode('\', \'', $registration_data) . '\'';
    $query                           = mysqli_query($sqlConnect, "INSERT INTO " . T_COMMUNITIES . " ({$fields}) VALUES ({$data})");
    if ($query) {
        $query_id = mysqli_insert_id($sqlConnect);
        Wo_RegisterCommunityJoin($query_id, $wo['user']['user_id']);
        @mysqli_query($sqlConnect, "INSERT INTO " . T_COMMUNITY_MODERATORS . " (`id`,`user_id`,`community_id`,`members`,`delete_community`) VALUES (null,{$wo['user']['user_id']},$query_id,1,1)");
        return true;
    } else {
        return false;
    }
}
function Wo_RegisterCommunityJoin($community_id = 0, $user_id = 0) {
    global $wo, $sqlConnect;
    if ($wo['loggedin'] == false) {
        return false;
    }
    if (!isset($community_id) or empty($community_id) or !is_numeric($community_id) or $community_id < 1) {
        return false;
    }
    if (!isset($user_id) or empty($user_id) or !is_numeric($user_id) or $user_id < 1) {
        return false;
    }
    $community_id    = Wo_Secure($community_id);
    $user_id     = Wo_Secure($user_id);
    //$community_onwer = Wo_GetUserIdFromCommunityId($community_id);
    $active      = 1;
    if (Wo_IsCommunityJoined($community_id, $user_id) === true) {
        return false;
    }
    $community_data = Wo_CommunityData($community_id);
    if ($community_data['join_privacy'] == 2) {
        $active = 0;
    }
    $query = mysqli_query($sqlConnect, " INSERT INTO " . T_COMMUNITY_MEMBERS . " (`user_id`,`community_id`,`active`,`time`) VALUES ({$user_id},{$community_id},'{$active}'," . time() . ")");
    if ($query) {
        /*if ($active == 1) {
            $notification_data = array(
                'recipient_id' => $community_onwer,
                'notifier_id' => $user_id,
                'type' => 'joined_community',
                'community_id' => $community_id,
                'url' => 'index.php?link1=timeline&u=' . $community_data['community_name']
            );
            Wo_RegisterNotification($notification_data);
        } else if ($active == 0) {
            $notification_data = array(
                'recipient_id' => $community_onwer,
                'notifier_id' => $user_id,
                'type' => 'requested_to_join_community',
                'community_id' => $community_id,
                'url' => 'index.php?link1=community-setting&community=' . $community_data['community_name'] . '&link3=requests'
            );
            Wo_RegisterNotification($notification_data);
        }*/
    }
    return true;
}

/*function Wo_GetUserIdFromCommunityId($community_id = 0) {
    global $sqlConnect;
    if (empty($community_id) or !is_numeric($community_id) or $community_id < 1) {
        return false;
    }
    $community_id      = Wo_Secure($community_id);
    $query_one     = "SELECT `user_id` FROM " . T_COMMUNITIES . " WHERE `id` = {$community_id}";
    $sql_query_one = mysqli_query($sqlConnect, $query_one);
        if (mysqli_num_rows($sql_query_one) == 1) {
            $sql_fetch_one = mysqli_fetch_assoc($sql_query_one);
            return $sql_fetch_one['user_id'];
        }
        return false;
        
}*/
function Wo_IsCommunityJoined($community_id = 0, $user_id = 0) {
    global $wo, $sqlConnect;
    if ($wo['loggedin'] == false) {
        return false;
    }
    if (empty($community_id) || !is_numeric($community_id) || $community_id < 0) {
        return false;
    }
    $user_id = Wo_Secure($user_id);
    if (empty($user_id) || !is_numeric($user_id) || $user_id < 0) {
        $user_id = Wo_Secure($wo['user']['user_id']);
    }
    $community_id  = Wo_Secure($community_id);
    $query_one = mysqli_query($sqlConnect, "SELECT COUNT(`id`) FROM " . T_COMMUNITY_MEMBERS . " WHERE `user_id` = '{$user_id}' AND `community_id` = {$community_id} AND `active` = '1'");
    return (Wo_Sql_Result($query_one, 0) == 1) ? true : false;
}

function Wo_CommunityData($community_id = 0) {
    global $wo, $sqlConnect, $cache;
    if (empty($community_id) || !is_numeric($community_id) || $community_id < 1) {
        return false;
    }
    $data            = array();
    $community_id        = Wo_Secure($community_id);
    $query_one       = "SELECT * FROM " . T_COMMUNITIES . " WHERE `id` = {$community_id}";
    $hashed_community_id = md5($community_id);
    if ($wo['config']['cacheSystem'] == 1) {
        $fetched_data = $cache->read($hashed_community_id . '_COMMUNITY_Data.tmp');
        if (empty($fetched_data)) {
            $sql          = mysqli_query($sqlConnect, $query_one);
            if (mysqli_num_rows($sql)) {
                $fetched_data = mysqli_fetch_assoc($sql);
                $cache->write($hashed_community_id . '_COMMUNITY_Data.tmp', $fetched_data);
            }
                
        }
    } else {
        $sql          = mysqli_query($sqlConnect, $query_one);
        if (mysqli_num_rows($sql)) {
            $fetched_data = mysqli_fetch_assoc($sql);
        }
            
    }
    if (empty($fetched_data)) {
        return array();
    }
    $fetched_data['community_id']    = $fetched_data['id'];
    $fetched_data['avatar']      = Wo_GetMedia($fetched_data['avatar']);
    $fetched_data['cover']       = Wo_GetMedia($fetched_data['cover']);
    $fetched_data['url']         = Wo_SeoLink('index.php?link1=timeline&u=' . $fetched_data['community_name']);
    $fetched_data['name']        = $fetched_data['community_title'];
    $fetched_data['category_id'] = $fetched_data['category'];
    $fetched_data['type']        = 'community';
    $fetched_data['username']    = $fetched_data['community_name'];
    $fetched_data['category']    = $wo['community_categories'][$fetched_data['category']];
    $fetched_data['is_reported'] = Wo_IsReportExists($fetched_data['id'], 'community');
    $fetched_data['community_sub_category'] = '';
    if (!empty($fetched_data['sub_category']) && !empty($wo['community_sub_categories'][$fetched_data['category_id']])) {
        foreach ($wo['community_sub_categories'][$fetched_data['category_id']] as $key => $value) {
            if ($value['id'] == $fetched_data['sub_category']) {
                $fetched_data['community_sub_category'] = $value['lang'];
            }
        }
    }
    $fetched_data['fields'] = array();
    $fields = Wo_GetCustomFields('community'); 
    if (!empty($fields)) {
        foreach ($fields as $key => $field) {
            if (in_array($field['fid'], array_keys($fetched_data) ) ) {
                $fetched_data['fields'][$field['fid']] = $fetched_data[$field['fid']];
            }
        }
    }
    if (Wo_IsCommunityJoinRequested($fetched_data['community_id'])) {
        $fetched_data['is_community_joined'] = 2;
    }
    elseif (Wo_IsCommunityJoined($fetched_data['community_id'])) {
        $fetched_data['is_community_joined'] = 1;
    }
    else{
        $fetched_data['is_community_joined'] = 0;
    }
    $fetched_data['members_count'] = Wo_CountCommunityMembers($fetched_data['community_id']);

    
    return $fetched_data;
}
function Wo_IsCommunityJoinRequested($community_id = 0, $user_id = 0) {
    global $sqlConnect, $wo;
    if ($wo['loggedin'] == false) {
        return false;
    }
    $user_id = Wo_Secure($user_id);
    if (!isset($user_id) or empty($user_id) or !is_numeric($user_id) or $user_id < 1) {
        $user_id = Wo_Secure($wo['user']['user_id']);
    }
    if (!is_numeric($community_id) or $community_id < 1) {
        return false;
    }
    $community_id  = Wo_Secure($community_id);
    $query     = "SELECT `id` FROM " . T_COMMUNITY_MEMBERS . " WHERE `community_id` = {$community_id} AND `user_id` = {$user_id} AND `active` = '0'";
    $sql_query = mysqli_query($sqlConnect, $query);
    if (mysqli_num_rows($sql_query) > 0) {
        return true;
    }
}
function Wo_CountCommunityRequests($community_id) {
    global $wo, $sqlConnect;
    $data = array();
    if (empty($community_id) or !is_numeric($community_id) or $community_id < 1) {
        return false;
    }
    $community_id     = Wo_Secure($community_id);
    $user_id   = $wo['user']['user_id'];
    $query        = mysqli_query($sqlConnect, "SELECT COUNT(`id`) AS count FROM " . T_COMMUNITY_MEMBERS . " WHERE `community_id` = {$community_id} AND `user_id` != {$user_id} AND `active` = '0'");
    if (mysqli_num_rows($query)) {
        $fetched_data = mysqli_fetch_assoc($query);
        return $fetched_data['count'];
    }
    return false;
        
}
function Wo_CountCommunityMembers($community_id = 0) {
    global $wo, $sqlConnect;
    $data = array();
    if (empty($community_id) or !is_numeric($community_id) or $community_id < 1) {
        return false;
    }
    $community_id     = Wo_Secure($community_id);
    $query        = mysqli_query($sqlConnect, "SELECT COUNT(`community_id`) AS count FROM " . T_COMMUNITY_MEMBERS . " WHERE `community_id` = {$community_id} AND `active` = '1' ");
    if (mysqli_num_rows($query)) {
        $fetched_data = mysqli_fetch_assoc($query);
        return $fetched_data['count'];
    }
    return false;
        
}
function Wo_GetMyCommunities() {
    global $sqlConnect, $wo;
    if ($wo['loggedin'] == false) {
        return false;
    }
    $data       = array();
    $user_id    = Wo_Secure($wo['user']['user_id']);
    $query_text = "SELECT `id` FROM " . T_COMMUNITIES . " WHERE `id` IN (SELECT `community_id` FROM ".T_COMMUNITY_MODERATORS." WHERE `user_id` = {$user_id})";
    if(Wo_IsAdmin() || Wo_IsModerator()) {
        $query_text = "SELECT `id` FROM " . T_COMMUNITIES;
    }
    
    $query_one  = mysqli_query($sqlConnect, $query_text);
    if (mysqli_num_rows($query_one)) {
        while ($fetched_data = mysqli_fetch_assoc($query_one)) {
            if (is_array($fetched_data)) {
                $data[] = Wo_CommunityData($fetched_data['id']);
            }
        }
    }
        

    return  $data;
}


function Wo_GetRegisteredCommunities($total = 100) {
    global $sqlConnect, $wo;
    $data = array();
    if (empty($sqlConnect) || !($sqlConnect instanceof mysqli)) {
        return $data;
    }
    $total = (int)$total;
    if ($total <= 0) {
        $total = 100;
    }
    $query_text = "SELECT `id`, `community_name`, `community_title` FROM " . T_COMMUNITIES . " ORDER BY `id` DESC LIMIT 0, {$total}";
    $query_one  = mysqli_query($sqlConnect, $query_text);
    if ($query_one && mysqli_num_rows($query_one)) {
        while ($fetched_data = mysqli_fetch_assoc($query_one)) {
            if (!empty($fetched_data['community_title'])) {
                $title = html_entity_decode(strip_tags($fetched_data['community_title']), ENT_QUOTES, 'UTF-8');
                $uname = !empty($fetched_data['community_name']) ? $fetched_data['community_name'] : $fetched_data['id'];
                $url   = Wo_SeoLink('index.php?link1=timeline&u=' . $uname);
                $data[] = array(
                    'id'    => $fetched_data['id'],
                    'name'  => $uname,
                    'title' => $title,
                    'url'   => $url
                );
            }
        }
    }
    return $data;
}

function Wo_GetCommunitiesNames($total = 100) {
    $communities = Wo_GetRegisteredCommunities($total);
    $data = array();
    foreach ($communities as $c) {
        $data[] = $c['title'];
    }
    return $data;
}

function Wo_GetNewsFromAnnouncements($total = 30) {
    global $sqlConnect, $wo;
    $data = array();
    if (empty($sqlConnect) || !($sqlConnect instanceof mysqli)) {
        return $data;
    }
    $total = (int)$total;
    if ($total <= 0) {
        $total = 30;
    }
    $query = mysqli_query($sqlConnect, "SELECT `id`, `text` FROM " . T_ANNOUNCEMENT . " WHERE `active` = '1' ORDER BY `id` DESC LIMIT {$total}");
    if ($query && mysqli_num_rows($query)) {
        while ($row = mysqli_fetch_assoc($query)) {
            if (!empty($row['text'])) {
                $clean_text = trim(html_entity_decode(strip_tags($row['text']), ENT_QUOTES, 'UTF-8'));
                $clean_text = trim(str_replace("\xc2\xa0", ' ', $clean_text));
                if (!empty($clean_text)) {
                    $data[] = array(
                        'id'   => $row['id'],
                        'text' => $clean_text
                    );
                }
            }
        }
    }
    return $data;
}

/**
 * Nigerian & World News RSS Ticker with Multi-Path Caching & Guaranteed Fallback
 * Sources: PunchNG, Vanguard, PremiumTimes, ChannelsTV, BBCWorld, AlJazeera
 */
function Wo_GetRssNewsHeadlines($limit_per_feed = 4, $force_refresh = false) {
    global $wo;

    $cache_paths = array(
        __DIR__ . '/../../cache/rss_ticker_cache.json',
        __DIR__ . '/../../rss_ticker_cache.json',
        'C:/xampp/htdocs/facesofnaija-web/cache/rss_ticker_cache.json',
        'C:/xampp/htdocs/facesofnaija-web/rss_ticker_cache.json',
        sys_get_temp_dir() . '/rss_ticker_cache.json'
    );

    $cache_time = 3600; // 1 hour cache duration
    $existing_cache = null;
    $existing_mtime = 0;

    // 1. Look for existing cache file across all known paths
    foreach ($cache_paths as $cpath) {
        if (file_exists($cpath)) {
            $content = @file_get_contents($cpath);
            if (!empty($content)) {
                $decoded = json_decode($content, true);
                if (!empty($decoded) && is_array($decoded) && count($decoded) >= 6) {
                    $existing_cache = $decoded;
                    $existing_mtime = filemtime($cpath);
                    break;
                }
            }
        }
    }

    // 2. Return fresh cache immediately if within cache_time and not forced
    if (!$force_refresh && !empty($existing_cache) && (time() - $existing_mtime < $cache_time)) {
        return $existing_cache;
    }

    // 3. Define RSS Feeds (Nigeria & World)
    $feeds = array(
        'PunchNG'      => 'https://rss.punchng.com/v1/category/latest_news',
        'Vanguard'     => 'https://www.vanguardngr.com/feed/',
        'PremiumTimes' => 'https://www.premiumtimesng.com/feed',
        'ChannelsTV'   => 'https://www.channelstv.com/feed/',
        'BBCWorld'     => 'https://feeds.bbci.co.uk/news/world/rss.xml',
        'AlJazeera'    => 'https://www.aljazeera.com/xml/rss/all.xml'
    );

    $limit_per_feed = max(1, (int)$limit_per_feed);
    $headlines = array();

    // 4. Fetch feeds in parallel using curl_multi for fast performance
    if (function_exists('curl_multi_init') && function_exists('curl_init')) {
        $mh = curl_multi_init();
        $curl_handles = array();

        foreach ($feeds as $source => $url) {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36');
            curl_multi_add_handle($mh, $ch);
            $curl_handles[$source] = $ch;
        }

        $running = null;
        do {
            $status = curl_multi_exec($mh, $running);
            if ($running) {
                curl_multi_select($mh, 0.1);
            }
        } while ($running > 0 && $status == CURLM_OK);

        $id_counter = 1;
        foreach ($curl_handles as $source => $ch) {
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $xml_data = curl_multi_getcontent($ch);
            curl_multi_remove_handle($mh, $ch);
            curl_close($ch);

            if ($http_code === 200 && !empty($xml_data)) {
                libxml_use_internal_errors(true);
                $xml = simplexml_load_string($xml_data, 'SimpleXMLElement', LIBXML_NOCDATA);
                if ($xml && isset($xml->channel->item)) {
                    $count = 0;
                    foreach ($xml->channel->item as $item) {
                        if ($count >= $limit_per_feed) break;
                        $raw_title = (string)$item->title;
                        $clean_title = html_entity_decode(strip_tags($raw_title), ENT_QUOTES, 'UTF-8');
                        $clean_title = trim(str_replace("\xc2\xa0", ' ', $clean_title));
                        $link = trim((string)$item->link);
                        $pub_time = strtotime((string)$item->pubDate) ?: time();

                        if (!empty($clean_title) && !empty($link)) {
                            $headlines[] = array(
                                'id'     => $id_counter++,
                                'source' => $source,
                                'title'  => $clean_title,
                                'text'   => $clean_title,
                                'link'   => $link,
                                'time'   => $pub_time
                            );
                            $count++;
                        }
                    }
                }
                libxml_clear_errors();
            }
        }
        curl_multi_close($mh);
    }

    // 5. If live fetch succeeded, sort and update all cache paths
    if (!empty($headlines) && count($headlines) >= 6) {
        usort($headlines, function($a, $b) {
            return $b['time'] <=> $a['time'];
        });
        foreach ($headlines as $k => $v) {
            $headlines[$k]['id'] = $k + 1;
        }

        $json_data = json_encode($headlines);
        foreach ($cache_paths as $cpath) {
            $dir = dirname($cpath);
            if (!is_dir($dir)) {
                @mkdir($dir, 0777, true);
            }
            @file_put_contents($cpath, $json_data);
        }
        return $headlines;
    }

    // 6. Stale-while-revalidate: return previous cache if network failed
    if (!empty($existing_cache)) {
        return $existing_cache;
    }

    // 7. Embedded Seed fallback of 24 Nigerian & World News Headlines (Zero-failure guarantee)
    $seed_headlines = array(
        array('id' => 1, 'source' => 'PunchNG', 'title' => "Sowore's AAC seeks approval to inaugurate campaign council in Aso Villa", 'text' => "Sowore's AAC seeks approval to inaugurate campaign council in Aso Villa", 'link' => 'https://punchng.com/sowores-aac-seeks-approval-to-inaugurate-campaign-council-in-aso-villa/', 'time' => time()),
        array('id' => 2, 'source' => 'PunchNG', 'title' => 'Fasoranti stood for justice, true federalism — Tunji-Ojo', 'text' => 'Fasoranti stood for justice, true federalism — Tunji-Ojo', 'link' => 'https://punchng.com/fasoranti-stood-for-justice-true-federalism-tunji-ojo/', 'time' => time() - 600),
        array('id' => 3, 'source' => 'PunchNG', 'title' => 'Police arrest suspected cultist in Ogun', 'text' => 'Police arrest suspected cultist in Ogun', 'link' => 'https://punchng.com/police-arrest-suspected-cultist-in-ogun-3/', 'time' => time() - 1200),
        array('id' => 4, 'source' => 'PunchNG', 'title' => 'NBTI unveils $600m proposed investment, solar energy pipeline', 'text' => 'NBTI unveils $600m proposed investment, solar energy pipeline', 'link' => 'https://punchng.com/nbti-unveils-600m-proposed-investment-solar-energy-pipeline/', 'time' => time() - 1800),
        array('id' => 5, 'source' => 'Vanguard', 'title' => '2027: Court dismisses suit seeking registration of new political party', 'text' => '2027: Court dismisses suit seeking registration of new political party', 'link' => 'https://www.vanguardngr.com/2026/10/2027-court-dismisses-suit-seeking-registration-of-new-political-party/', 'time' => time() - 2400),
        array('id' => 6, 'source' => 'PremiumTimes', 'title' => 'Abia APGA 2023 governorship candidate Gregory Ibe dies', 'text' => 'Abia APGA 2023 governorship candidate Gregory Ibe dies', 'link' => 'https://www.premiumtimesng.com/regional/ssouth-east/918823-abia-apga-2023-governorship-candidate-gregory-ibe-dies.html', 'time' => time() - 2600),
        array('id' => 7, 'source' => 'Vanguard', 'title' => 'Ligue 1 game postponed as unrest grips France', 'text' => 'Ligue 1 game postponed as unrest grips France', 'link' => 'https://www.vanguardngr.com/2026/10/ligue-1-game-postponed-as-unrest-grips-france/', 'time' => time() - 2800),
        array('id' => 8, 'source' => 'Vanguard', 'title' => 'Cross River Assembly passes State Police Bill', 'text' => 'Cross River Assembly passes State Police Bill', 'link' => 'https://www.vanguardngr.com/2026/10/cross-river-assembly-passes-state-police-bill-2/', 'time' => time() - 3000),
        array('id' => 9, 'source' => 'Vanguard', 'title' => 'Man arrested after allegedly taping wife to chair to watch football in peace', 'text' => 'Man arrested after allegedly taping wife to chair to watch football in peace', 'link' => 'https://www.vanguardngr.com/2026/10/man-arrested-after-allegedly-taping-wife-to-chair-to-watch-football-in-peace/', 'time' => time() - 3200),
        array('id' => 10, 'source' => 'BBCWorld', 'title' => 'US citizen Stephen Hubbard freed by Russia as Trump envoy meets Putin', 'text' => 'US citizen Stephen Hubbard freed by Russia as Trump envoy meets Putin', 'link' => 'https://www.bbc.co.uk/news/articles/c6m24l9v3745o', 'time' => time() - 3400),
        array('id' => 11, 'source' => 'PremiumTimes', 'title' => 'EFCC arraigns four for N44m contract fraud in Maiduguri', 'text' => 'EFCC arraigns four for N44m contract fraud in Maiduguri', 'link' => 'https://www.premiumtimesng.com/regional/nnorth-east/918819-efcc-arraigns-four-for-n44m-contract-fraud-in-maiduguri.html', 'time' => time() - 3600),
        array('id' => 12, 'source' => 'ChannelsTV', 'title' => 'Police Arrest Suspected Armed Robber In Hotel Hideout', 'text' => 'Police Arrest Suspected Armed Robber In Hotel Hideout', 'link' => 'https://www.channelstv.com/2026/10/21/police-arrest-suspected-armed-robber-in-hotel-hideout/', 'time' => time() - 3800),
        array('id' => 13, 'source' => 'ChannelsTV', 'title' => 'Reps Call For Early Isolation Camp Preparations To Prevent Ebola Spread', 'text' => 'Reps Call For Early Isolation Camp Preparations To Prevent Ebola Spread', 'link' => 'https://www.channelstv.com/2026/10/21/reps-call-for-early-isolation-camp-preparations-to-prevent-ebola-spread/', 'time' => time() - 4000),
        array('id' => 14, 'source' => 'PremiumTimes', 'title' => 'Akwa Ibom doctors celebrates colleague’s elevation to ANA fellowship', 'text' => 'Akwa Ibom doctors celebrates colleague’s elevation to ANA fellowship', 'link' => 'https://www.premiumtimesng.com/entertainment/918799-akwa-ibom-doctors-celebrates-colleagues-elevation-to-ana-fellowship.html', 'time' => time() - 4200),
        array('id' => 15, 'source' => 'AlJazeera', 'title' => 'Cost of living protests are not just happening in Europe', 'text' => 'Cost of living protests are not just happening in Europe', 'link' => 'https://www.aljazeera.com/video/newsfeed/2026/10/21/cost-of-living-protests-arent-just-happening-in-europe', 'time' => time() - 4400),
        array('id' => 16, 'source' => 'AlJazeera', 'title' => 'Kvaratskhelia, Dembele and Marquinhos out injured after PSG’s win', 'text' => 'Kvaratskhelia, Dembele and Marquinhos out injured after PSG’s win', 'link' => 'https://www.aljazeera.com/sports/2026/10/21/kvaratskhelia-dembele-and-marquinhos-out-injured-after-psgs-win', 'time' => time() - 4600),
        array('id' => 17, 'source' => 'AlJazeera', 'title' => 'Why are French protesters chanting Bismillah, c’est les ténèbres?', 'text' => 'Why are French protesters chanting Bismillah, c’est les ténèbres?', 'link' => 'https://www.aljazeera.com/news/2026/10/21/why-are-french-protesters-chanting-bismillah-cest-les', 'time' => time() - 4800),
        array('id' => 18, 'source' => 'PremiumTimes', 'title' => 'Shut down hazardous gas pipelines, HOMEF tells FG after Rivers explosion', 'text' => 'Shut down hazardous gas pipelines, HOMEF tells FG after Rivers explosion', 'link' => 'https://www.premiumtimesng.com/regional/south-south-regional/918785-shut-down-hazardous-gas-pipelines-homef-tells-fg-after-rivers-explosion.html', 'time' => time() - 5000),
        array('id' => 19, 'source' => 'AlJazeera', 'title' => 'UK approves development of Jackdaw gasfield in North Sea', 'text' => 'UK approves development of Jackdaw gasfield in North Sea', 'link' => 'https://www.aljazeera.com/news/2026/10/21/uk-approves-development-of-jackdaw-gasfield-in-north-sea', 'time' => time() - 5200),
        array('id' => 20, 'source' => 'ChannelsTV', 'title' => 'PSG’s Kvaratskhelia Set For Long Absence With Torn ACL', 'text' => 'PSG’s Kvaratskhelia Set For Long Absence With Torn ACL', 'link' => 'https://www.channelstv.com/2026/10/21/psgs-kvaratskhelia-set-for-long-absence-with-torn-acl/', 'time' => time() - 5400),
        array('id' => 21, 'source' => 'ChannelsTV', 'title' => 'EFCC Arrests Abuja Businessman Over Alleged $120m Fraud', 'text' => 'EFCC Arrests Abuja Businessman Over Alleged $120m Fraud', 'link' => 'https://www.channelstv.com/2026/10/21/efcc-arrests-abuja-businessman-over-alleged-120m-fraud/', 'time' => time() - 5600),
        array('id' => 22, 'source' => 'BBCWorld', 'title' => 'Australian MP loses both legs after alleged car attack, husband says', 'text' => 'Australian MP loses both legs after alleged car attack, husband says', 'link' => 'https://www.bbc.co.uk/news/articles/ckwyqdqxw6d0o', 'time' => time() - 5800),
        array('id' => 23, 'source' => 'BBCWorld', 'title' => 'Katya Adler: France divided over who to blame for riots and what to learn from them', 'text' => 'Katya Adler: France divided over who to blame for riots and what to learn from them', 'link' => 'https://www.bbc.co.uk/news/articles/cq0me4g9p970o', 'time' => time() - 6000),
        array('id' => 24, 'source' => 'BBCWorld', 'title' => 'Trump and Zelensky appear at loggerheads despite new optimism', 'text' => 'Trump and Zelensky appear at loggerheads despite new optimism', 'link' => 'https://www.bbc.co.uk/news/articles/ckrey51jrgnro', 'time' => time() - 6200)
    );

    // Save seed to cache paths if writable
    $seed_json = json_encode($seed_headlines);
    foreach ($cache_paths as $cpath) {
        $dir = dirname($cpath);
        if (!is_dir($dir)) {
            @mkdir($dir, 0777, true);
        }
        @file_put_contents($cpath, $seed_json);
    }

    return $seed_headlines;
}

function Wo_GetNewsForMarquee($total = 30) {
    $rss = Wo_GetRssNewsHeadlines(4);
    if (!empty($rss) && is_array($rss)) {
        $total = (int)$total;
        if ($total > 0 && count($rss) > $total) {
            return array_slice($rss, 0, $total);
        }
        return $rss;
    }
    return $rss;
}


function Wo_GetAllUserAnnouncements($limit = 100, $offset = 0) {
    global $sqlConnect, $wo;
    $data = array();
    $limit = (int)$limit;
    if ($limit <= 0) {
        $limit = 100;
    }
    $offset = (int)$offset;
    $offset_query = '';
    if ($offset > 0) {
        $offset_query = " AND `id` < {$offset} ";
    }
    $query = mysqli_query($sqlConnect, "SELECT * FROM " . T_ANNOUNCEMENT . " WHERE `active` = '1' {$offset_query} ORDER BY `id` DESC LIMIT {$limit}");
    if ($query && mysqli_num_rows($query)) {
        while ($row = mysqli_fetch_assoc($query)) {
            $formatted_text = $row['text'];
            if (function_exists('Wo_Markup')) {
                $formatted_text = Wo_Markup($formatted_text);
            }
            if (function_exists('Wo_Emo')) {
                $formatted_text = Wo_Emo($formatted_text);
            }
            $views = 0;
            if (function_exists('Wo_GetAnnouncementViews')) {
                $views = (int)Wo_GetAnnouncementViews($row['id']);
            }
            $row['formatted_text'] = $formatted_text;
            $row['views'] = $views;
            $data[] = $row;
        }
    }
    return $data;
}

function Wo_CountActiveAnnouncements() {
    global $sqlConnect;
    $query = mysqli_query($sqlConnect, "SELECT COUNT(`id`) as `count` FROM " . T_ANNOUNCEMENT . " WHERE `active` = '1'");
    if ($query && mysqli_num_rows($query)) {
        $row = mysqli_fetch_assoc($query);
        return (int)$row['count'];
    }
    return 0;
}

function Wo_GetMyCommunitiesAPI($limit = 0,$offset = 0,$sort = '') {
    global $sqlConnect, $wo;
    if ($wo['loggedin'] == false) {
        return false;
    }
    $data       = array();
    $user_id    = Wo_Secure($wo['user']['user_id']);
    $limit_query = '';
    if (!empty($limit)) {
        $limit    = Wo_Secure($limit);
        $limit_query = " LIMIT $limit";
    }
    $offset_query = '';
    if (!empty($offset)) {
        $offset    = Wo_Secure($offset);
        $offset_query = " `id` < $offset AND ";
    }
    $sort_query = '';
    if (!empty($sort)) {
        $sort    = Wo_Secure($sort);
        $sort_query = " ORDER BY `id` $sort ";
    }
    $query_text = "SELECT `id` FROM " . T_COMMUNITIES . " WHERE $offset_query (`id` IN (SELECT `community_id` FROM ".T_COMMUNITY_MODERATORS." WHERE `user_id` = {$user_id})) $sort_query $limit_query ";
    $query_one  = mysqli_query($sqlConnect, $query_text);
    
    if (mysqli_num_rows($query_one)) {
        while ($fetched_data = mysqli_fetch_assoc($query_one)) {
            if (is_array($fetched_data)) {
                $data[] = Wo_CommunityData($fetched_data['id']);
            }
        }
    }
   
    return  $data;
}
function Wo_IsCommunityUserExists($user_id = false, $community_id = false) {
    global $sqlConnect, $wo;
    if ($wo['loggedin'] == false || !is_numeric($user_id) || !is_numeric($community_id)) {
        return false;
    }
    $sql       = " SELECT `id` FROM " . T_COMMUNITY_MODERATORS. " WHERE `user_id` = {$user_id} AND `community_id` = {$community_id} ";
    $data_rows = mysqli_query($sqlConnect, $sql);
    return mysqli_num_rows($data_rows) > 0;
}
function Wo_LeaveCommunity($community_id = 0, $user_id = 0) {
    global $wo, $sqlConnect;
    if ($wo['loggedin'] == false) {
        return false;
    }
    if (!isset($community_id) or empty($community_id) or !is_numeric($community_id) or $community_id < 1) {
        return false;
    }
    if (!isset($user_id) or empty($user_id) or !is_numeric($user_id) or $user_id < 1) {
        return false;
    }
    $community_id = Wo_Secure($community_id);
    $user_id  = Wo_Secure($user_id);
    $active   = 1;
    if (Wo_IsCommunityJoined($community_id, $user_id) === false && Wo_IsCommunityJoinRequested($community_id, $user_id) === false) {
        return false;
    }
    $query = mysqli_query($sqlConnect, " DELETE FROM " . T_COMMUNITY_MEMBERS . " WHERE `user_id` = {$user_id} AND `community_id` = '{$community_id}'");
    if ($query) {
        @mysqli_query($sqlConnect, "DELETE FROM " . T_COMMUNITY_MODERATORS . " WHERE `user_id` = {$user_id} AND `community_id` = {$community_id}");
        return true;
    }
}
function Wo_CommunitySug($limit = 20) {
    global $wo, $sqlConnect;
    if (!is_numeric($limit)) {
        return false;
    }
    $data      = array();
    $user_id   = Wo_Secure($wo['user']['user_id']);
    $query_one = " SELECT `id` FROM " . T_COMMUNITIES . " WHERE `active` = '1' AND `id` NOT IN (SELECT `community_id` FROM " . T_COMMUNITY_MEMBERS . " WHERE `user_id` = {$user_id}) AND `user_id` <> {$user_id}";
    if (isset($limit)) {
        $query_one .= " ORDER BY RAND() LIMIT {$limit}";
    }
    $sql = mysqli_query($sqlConnect, $query_one);
    /*if (mysqli_num_rows($sql)) {
        while ($fetched_data = mysqli_fetch_assoc($sql)) {
            $data[] = Wo_CommunityData($fetched_data['id']);
        }
    }*/
        
    return $data;
}
function Wo_GetUsersCommunities($user_id = 0, $limit = 12, $placement = array(), $offset = 0) {
    global $wo, $sqlConnect;
    $data = array();
    if (empty($user_id) or !is_numeric($user_id) or $user_id < 1) {
        return false;
    }
    $offset_text = '';
    if (!empty($offset) && is_numeric($offset) && $offset > 0) {
        $offset = Wo_Secure($offset);
        $offset_text = ' AND `community_id` > '.$offset;
    }
    $user_id   = Wo_Secure($user_id);
    $query     = " SELECT `community_id` FROM " . T_COMMUNITY_MEMBERS . " WHERE `user_id` = {$user_id} AND `active` = '1' {$offset_text} ORDER BY `id` LIMIT {$limit}";
    if (!empty($placement)) {
        if ($placement['in'] == 'profile_sidebar' && is_array($placement['communities_data'])) {
            foreach ($placement['communities_data'] as $key => $id) {
                $user_data   = Wo_CommunityData($id);
                if (!empty($user_data)) {
                    $data[]  = $user_data;
                }
            }
            return $data;
        }
    }
    $sql_query = mysqli_query($sqlConnect, $query);
    if (mysqli_num_rows($sql_query)) {
        while ($fetched_data = mysqli_fetch_assoc($sql_query)) {
            $data[] = Wo_CommunityData($fetched_data['community_id']);
        }
    }
        
    return $data;
}
function Wo_GetUsersCommunitiesAPI($user_id,$limit = 0,$offset = 0) {
    global $wo, $sqlConnect;
    $data = array();
    if (empty($user_id) or !is_numeric($user_id) or $user_id < 1) {
        return false;
    }
    $limit_query = '';
    if (!empty($limit)) {
        $limit    = Wo_Secure($limit);
        $limit_query = " LIMIT $limit";
    }
    $offset_query = '';
    if (!empty($offset)) {
        $offset    = Wo_Secure($offset);
        $offset_query = " AND `community_id` < $offset ";
    }
    $user_id   = Wo_Secure($user_id);
    $query     = " SELECT `community_id` FROM " . T_COMMUNITY_MEMBERS . " WHERE `user_id` = {$user_id} AND `active` = '1' $offset_query  ORDER BY `community_id` DESC  $limit_query";
    $sql_query = mysqli_query($sqlConnect, $query);
    if (mysqli_num_rows($sql_query)) {
        while ($fetched_data = mysqli_fetch_assoc($sql_query)) {
            $data[] = Wo_CommunityData($fetched_data['community_id']);
        }
    }
        
    return $data;
}
function Wo_GetRandomCommunitiesAPI($limit = 0,$offset = 0) {
    global $wo, $sqlConnect;
    $data = array();

    $limit_query = '';
    if (!empty($limit)) {
        $limit    = Wo_Secure($limit);
        $limit_query = " LIMIT $limit";
    }
    $offset_query = '';
    if (!empty($offset)) {
        $offset    = Wo_Secure($offset);
        $offset_query = " AND `community_id` < $offset ";
    }
    
    $query     = " SELECT `community_id` FROM " . T_COMMUNITY_MEMBERS . " WHERE `active` = '1' $offset_query  ORDER BY `community_id` DESC";//  $limit_query
        
    $sql_query = mysqli_query($sqlConnect, $query);
    if (mysqli_num_rows($sql_query)) {
        while ($fetched_data = mysqli_fetch_assoc($sql_query)) {
            $data[] = Wo_CommunityData($fetched_data['community_id']);
        }
    }
        
    return $data;
}
function Wo_GetCommunityJoinButton($community_id = 0) {
    global $wo;
    if ($wo['loggedin'] == false) {
        return false;
    }
    if (empty($community_id) || !is_numeric($community_id) or $community_id < 0) {
        return false;
    }
    /*if (Wo_IsCommunityOnwer($community_id)) {
        return false;
    }*/
    $community_id = Wo_Secure($community_id);
    $community    = $wo['join'] = Wo_CommunityData($community_id);
    if (!isset($wo['join']['id'])) {
        return false;
    }
    $logged_user_id        = Wo_Secure($wo['user']['user_id']);
    $join_button           = 'buttons/join-community';
    $leave_button          = 'buttons/leave-community';
    $accept_request_button = 'buttons/join-community-requested';
    if (Wo_IsCommunityJoined($community_id, $logged_user_id) === true) {
        return Wo_LoadPage($leave_button);
    } else {
        if (Wo_IsCommunityJoinRequested($community_id) === true) {
            return Wo_LoadPage($accept_request_button);
        } else {
            return Wo_LoadPage($join_button);
        }
    }
}
function Wo_CommunityIdFromCommunityname($community_name = '') {
    global $sqlConnect;
    if (empty($community_name)) {
        return false;
    }
    $community_name = Wo_Secure($community_name);
    $query      = mysqli_query($sqlConnect, "SELECT `id` FROM " . T_COMMUNITIES . " WHERE `community_name` = '{$community_name}'");
    return Wo_Sql_Result($query, 0, 'id');
}
/*function Wo_IsCommunityOnwer($community_id = 0, $user_id = 0) {
    global $sqlConnect, $wo;
    if ($wo['loggedin'] == false) {
        return false;
    }
    if (empty($community_id) || !is_numeric($community_id) || $community_id < 0) {
        return false;
    }
    if (empty($user_id) || !is_numeric($user_id) || $user_id < 0) {
        $user_id = Wo_Secure($wo['user']['user_id']);
    }
    if (empty($user_id) || !is_numeric($user_id) || $user_id < 0) {
        return false;
    }
    $query = mysqli_query($sqlConnect, " SELECT COUNT(`user_id`) FROM " . T_COMMUNITIES . " WHERE `id` = {$community_id} AND `user_id` = {$user_id} AND `active` = '1'");
    return (Wo_Sql_Result($query, '0') == 1 || Wo_IsCommunityUserExists($user_id,$community_id)) ? true : false;
}*/


function Wo_CommunityExists($community_name = '') {
    global $sqlConnect;
    if (empty($community_name)) {
        return false;
    }
    $community_name = Wo_Secure($community_name);
    $query      = mysqli_query($sqlConnect, "SELECT COUNT(`id`) FROM " . T_COMMUNITIES . " WHERE `community_name`= '{$community_name}' AND `active` = '1'");
    return (Wo_Sql_Result($query, 0) == 1) ? true : false;
}
function Wo_CommunityActive($community_name) {
    global $sqlConnect;
    if (empty($community_name)) {
        return false;
    }
    $community_name = Wo_Secure($community_name);
    $query      = mysqli_query($sqlConnect, "SELECT COUNT(`id`) FROM " . T_COMMUNITIES . "  WHERE `community_name` = '{$community_name}' AND `active` = '1'");
    return (Wo_Sql_Result($query, 0) == 1) ? true : false;
}
function Wo_CanBeOnCommunity($community_id) {
    global $sqlConnect;
    if (empty($community_id)) {
        return false;
    }
    $community_id = Wo_Secure($community_id);
    if (Wo_IsAdmin() || Wo_IsModerator()) {
        return true;
    }
    $community = Wo_CommunityData($community_id);
    if (empty($community)) {
        return false;
    }
    if ($community['privacy'] == 2) {
        if (Wo_IsCommunityJoined($community_id) === true) {
            return true;
        }
    } else if ($community['privacy'] == 1) {
        return true;
    } else {
        return false;
    }
}
function Wo_GetCommunityPostPublisherBox($community_id = 0) {
    global $wo;
    if ($wo['loggedin'] == false) {
        return false;
    }
    if (!is_numeric($community_id) or $community_id < 1 or !is_numeric($community_id)) {
        return false;
    }
    $community_id = Wo_Secure($community_id);
    $continue = false;
    if (Wo_CanBeOnCommunity($community_id) === true) {
        $community = Wo_CommunityData($community_id);

        if ($community['privacy'] == 2) {
            if (Wo_IsCommunityJoined($community_id) === true) {
                $continue = true;
            }
        } else if ($community['privacy'] == 1) {
            //if (Wo_IsCommunityJoined($community_id) === true) {
                $continue = true;
            //}
        } else {
            $continue = false;
        }
    }
    if ($continue == true) {
        return Wo_LoadPage('story/publisher-box');
    }
}
function Wo_UpdateCommunityData($community_id = 0, $update_data) {
    global $wo, $sqlConnect, $cache;
    if ($wo['loggedin'] == false) {
        return false;
    }
    if (empty($community_id) || !is_numeric($community_id) || $community_id < 0) {
        return false;
    }
    if (empty($update_data)) {
        return false;
    }
    $community_id = Wo_Secure($community_id);
    if (Wo_IsAdmin() === false && Wo_IsModerator() === false) {
        $user_id = !empty($wo['user']['user_id']) ? (int) $wo['user']['user_id'] : 0;
        if (Wo_IsCommunityUserExists($user_id, $community_id) === false) {
            return false;
        }
    }
    if (!empty($update_data['category'])) {
        if (!array_key_exists($update_data['category'], $wo['community_categories'])) {
            $update_data['category'] = 1;
        }
    }
    $update = array();
    foreach ($update_data as $field => $data) {
        $update[] = '`' . $field . '` = \'' . Wo_Secure($data, 0) . '\'';
    }

    $impload   = implode(', ', $update);
    $query_one = " UPDATE " . T_COMMUNITIES . " SET {$impload} WHERE `id` = {$community_id} ";
    $query     = mysqli_query($sqlConnect, $query_one);
    if ($wo['config']['cacheSystem'] == 1) {
        $cache->delete(md5($community_id) . '_community_Data.tmp');
    }
    if ($query) {
        return true;
    } else {
        return false;
    }
}
function Wo_GetCommunityIdFromPostId($post_id = 0) {
    global $sqlConnect;
    if (empty($post_id) or !is_numeric($post_id) or $post_id < 1) {
        return false;
    }
    $post_id       = Wo_Secure($post_id);
    $query_one     = "SELECT `community_id` FROM " . T_POSTS . " WHERE `id` = {$post_id}";
    $sql_query_one = mysqli_query($sqlConnect, $query_one);
        if (mysqli_num_rows($sql_query_one) == 1) {
            $sql_fetch_one = mysqli_fetch_assoc($sql_query_one);
            return $sql_fetch_one['community_id'];
        }
    return false;
        
}

function Wo_DeleteCommunity($community_id = 0) {
    global $wo, $sqlConnect, $cache;
    if ($wo['loggedin'] == false) {
        return false;
    }
    if (empty($community_id) || !is_numeric($community_id) || $community_id < 1) {
        return false;
    }
    $community_id = Wo_Secure($community_id);
    if (Wo_IsAdmin() === false && Wo_IsModerator() === false) {
        //if (Wo_IsCommunityOnwer($community_id) === false) {
            return false;
        //}
    }
    
    $query_one_delete_photos = mysqli_query($sqlConnect, " SELECT `avatar`,`cover` FROM " . T_COMMUNITIES . " WHERE `id` = {$community_id}");
    if (mysqli_num_rows($query_one_delete_photos)) {
        $fetched_data            = mysqli_fetch_assoc($query_one_delete_photos);
        if (isset($fetched_data['avatar']) && !empty($fetched_data['avatar']) && $fetched_data['avatar'] != $wo['groupDefaultAvatar']) {
            @unlink($fetched_data['avatar']);
        }
        if (isset($fetched_data['cover']) && !empty($fetched_data['cover']) && $fetched_data['cover'] != $wo['userDefaultCover']) {
            @unlink($fetched_data['cover']);
        }
    }
        
    $query_two_delete_media = mysqli_query($sqlConnect, " SELECT `postFile` FROM " . T_POSTS . " WHERE `community_id` = {$community_id}");
        if (mysqli_num_rows($query_two_delete_media) > 0) {
            while ($fetched_data = mysqli_fetch_assoc($query_two_delete_media)) {
                if (isset($fetched_data['postFile']) && !empty($fetched_data['postFile'])) {
                    @unlink($fetched_data['postFile']);
                }
            }
        }
        
    $query_four_delete_media = mysqli_query($sqlConnect, "SELECT `id`,`post_id` FROM " . T_POSTS . " WHERE `community_id` = {$community_id}");
        if (mysqli_num_rows($query_four_delete_media) > 0) {
            while ($fetched_data = mysqli_fetch_assoc($query_four_delete_media)) {
                $delete_posts = Wo_DeletePost($fetched_data['id']);
                $delete_posts = Wo_DeletePost($fetched_data['post_id']);
            }
        }
        
    if ($wo['config']['cacheSystem'] == 1) {
        $cache->delete(md5($community_id) . '_community_Data.tmp');
        $query_two = mysqli_query($sqlConnect, "SELECT `id`,`post_id` FROM " . T_POSTS . " WHERE `community_id` = {$community_id}");
            if (mysqli_num_rows($query_two) > 0) {
                while ($fetched_data_two = mysqli_fetch_assoc($query_two)) {
                    $cache->delete(md5($fetched_data_two['id']) . '_community_Data.tmp');
                    $cache->delete(md5($fetched_data_two['post_id']) . '_community_Data.tmp');
                }
            }
            
    }
    $query_one = mysqli_query($sqlConnect, "DELETE FROM " . T_COMMUNITIES . " WHERE `id` = {$community_id}");
    $query_one .= mysqli_query($sqlConnect, "DELETE FROM " . T_COMMUNITY_MEMBERS . " WHERE `community_id` = {$community_id}");
    $query_one .= mysqli_query($sqlConnect, "DELETE FROM " . T_NOTIFICATION . " WHERE `community_id` = {$community_id}");
    $query_one .= mysqli_query($sqlConnect, "DELETE FROM " . T_COMMUNITY_MODERATORS . " WHERE `community_id` = {$community_id}");
    $query_one .= mysqli_query($sqlConnect, "DELETE FROM " . T_POSTS . " WHERE `community_id` = {$community_id}");
    if ($query_one) {
        return true;
    }
}
function Wo_GetCommunityRequests($community_id) {
    global $wo, $sqlConnect;
    $data      = array();
    $community_id  = Wo_Secure($community_id);
    $user_id   = $wo['user']['user_id'];
    $query_one = " SELECT `user_id` FROM " . T_COMMUNITY_MEMBERS . " WHERE `community_id` = {$community_id} AND `user_id` != {$user_id} AND `active` = '0' ORDER BY `id` DESC";
    $sql       = mysqli_query($sqlConnect, $query_one);
    if (mysqli_num_rows($sql)) {
        while ($fetched_data = mysqli_fetch_assoc($sql)) {
            $data[] = Wo_UserData($fetched_data['user_id']);
        }
    }
        
    return $data;
}
function Wo_CountCommunityPosts($community_id = 0) {
    global $wo, $sqlConnect;
    $data = array();
    if (empty($community_id) or !is_numeric($community_id) or $community_id < 1) {
        return false;
    }
    $community_id     = Wo_Secure($community_id);
    $query        = mysqli_query($sqlConnect, "SELECT COUNT(`id`) AS count FROM " . T_POSTS . " WHERE `community_id` = {$community_id}");
    if (mysqli_num_rows($query)) {
        $fetched_data = mysqli_fetch_assoc($query);
        return $fetched_data['count'];
    }
    return false;
        
}
function Wo_CountCommunityJoinedThisWeek($community_id = 0) {
    global $wo, $sqlConnect;
    $data = array();
    $time = strtotime("-1 week");
    if (empty($community_id) or !is_numeric($community_id) or $community_id < 1) {
        return false;
    }
    $community_id     = Wo_Secure($community_id);
    $query        = mysqli_query($sqlConnect, "SELECT COUNT(`community_id`) AS count FROM " . T_COMMUNITY_MEMBERS . " WHERE `community_id` = {$community_id} AND `active` = '1' AND (`time` between {$time} AND " . time() . ")");
    if (mysqli_num_rows($query)) {
        $fetched_data = mysqli_fetch_assoc($query);
        return $fetched_data['count'];
    }
    return false;
        
}
function Wo_GetCommunityMembers($community_id = 0) {
    global $wo, $sqlConnect;
    $data = array();
    if (empty($community_id) or !is_numeric($community_id) or $community_id < 1) {
        return false;
    }
    $community_id  = Wo_Secure($community_id);
    $query     = " SELECT `user_id` FROM " . T_COMMUNITY_MEMBERS . " WHERE `community_id` = {$community_id} AND `active` = '1'";
    $sql_query = mysqli_query($sqlConnect, $query);
    if (mysqli_num_rows($sql_query)) {
        while ($fetched_data = mysqli_fetch_assoc($sql_query)) {
            $data[] = Wo_UserData($fetched_data['user_id']);
        }
    }
        
    return $data;
}
function Wo_GetCommunitySettingMembers($community_id = 0,$limit = 0,$offset = 0) {
    global $wo, $sqlConnect;
    $data = array();
    if (empty($community_id) or !is_numeric($community_id) or $community_id < 1) {
        return false;
    }
    $limit_query = '';
    if (!empty($limit)) {
        $limit    = Wo_Secure($limit);
        $limit_query = " LIMIT $limit";
    }
    $offset_query = '';
    if (!empty($offset)) {
        $offset    = Wo_Secure($offset);
        $offset_query = " AND `id` > $offset ";
    }
    $community_id  = Wo_Secure($community_id);
    $query     = " SELECT `user_id`,`id` FROM " . T_COMMUNITY_MEMBERS . " WHERE `community_id` = {$community_id} AND `active` = '1' $offset_query $limit_query";
    $sql_query = mysqli_query($sqlConnect, $query);
    if (mysqli_num_rows($sql_query)) {
        while ($fetched_data = mysqli_fetch_assoc($sql_query)) {
            $user_data = Wo_UserData($fetched_data['user_id']);
            $user_data['member_id'] = $fetched_data['id'];
            $data[] = $user_data;
        }
    }
        
    return $data;
}
function Wo_CountUserCommunities($user_id) {
    global $wo, $sqlConnect;
    $data = array();
    if (empty($user_id) or !is_numeric($user_id) or $user_id < 1) {
        return false;
    }
    $user_id      = Wo_Secure($user_id);
    $query        = mysqli_query($sqlConnect, "SELECT COUNT(`id`) AS count FROM " . T_COMMUNITY_MEMBERS . " WHERE `user_id` = {$user_id} AND `active` = '1' ");
    if (mysqli_num_rows($query)) {
        $fetched_data = mysqli_fetch_assoc($query);
        return $fetched_data['count'];
    }
    return false;
        
}
function Wo_GetAllCommunities($limit = '', $after = '') {
    global $wo, $sqlConnect;
    $data      = array();
    $query_one = " SELECT `id` FROM " . T_COMMUNITIES;
    if (!empty($after) && is_numeric($after) && $after > 0) {
        $query_one .= " WHERE `id` < " . Wo_Secure($after);
    }
    $query_one .= " ORDER BY `id` DESC";
    if (isset($limit) and !empty($limit)) {
        $query_one .= " LIMIT {$limit}";
    }
    $sql = mysqli_query($sqlConnect, $query_one);
    if (mysqli_num_rows($sql)) {
        while ($fetched_data = mysqli_fetch_assoc($sql)) {
            $community_data            = Wo_CommunityData($fetched_data['id']);
            $community_data['members'] = Wo_CountCommunityMembers($fetched_data['id']);
            $community_data['owner']   = Wo_UserData($community_data['user_id']);
            $data[]                = $community_data;
        }
    }
        
    return $data;
}

function Wo_CountCommunitiesNotMember($community_id) {
    global $sqlConnect, $wo;
    if ($wo['loggedin'] == false) {
        return false;
    }
    if (empty($community_id) || !is_numeric($community_id)) {
        return false;
    }
    $user_id      = Wo_Secure($wo['user']['user_id']);
    $community_id     = Wo_Secure($community_id);
    $query_one    = mysqli_query($sqlConnect, "SELECT COUNT(`id`) as count FROM " . T_FOLLOWERS . " WHERE `follower_id` = {$user_id} AND `active` = '1' AND `following_id` NOT IN (SELECT `user_id` FROM " . T_COMMUNITY_MEMBERS . " WHERE `community_id` = {$community_id})");
    if (mysqli_num_rows($query_one)) {
        $fetched_data = mysqli_fetch_assoc($query_one);
        return $fetched_data['count'];
    }
    return false;
        
}
function Wo_GetCommunitiesNotMember($community_id) {
    global $sqlConnect, $wo;
    if ($wo['loggedin'] == false) {
        return false;
    }
    if (empty($community_id) || !is_numeric($community_id)) {
        return false;
    }
    $data      = array();
    $community_id  = Wo_Secure($community_id);
    $user_id   = Wo_Secure($wo['user']['user_id']);
    $query_one = mysqli_query($sqlConnect, "SELECT `following_id` FROM " . T_FOLLOWERS . " WHERE `follower_id` = {$user_id} AND `active` = '1' AND `following_id` NOT IN (SELECT `user_id` FROM " . T_COMMUNITY_MEMBERS . " WHERE `community_id` = {$community_id})");
    if (mysqli_num_rows($query_one)) {
        while ($fetched_data = mysqli_fetch_assoc($query_one)) {
            $data[] = Wo_UserData($fetched_data['following_id']);
        }
    }
        
    return $data;
}
function Wo_IsCanCommunityUpdate($community_id,$page)
{
    global $sqlConnect, $wo;
    $array = array(
        "general",
        "privacy",
        "avatar",
        "members",
        "analytics",
        "delete_community"
    );
    if ($wo['loggedin'] == false) {
        return false;
    }
    
    if (empty($community_id) || !is_numeric($community_id) || $community_id < 0 || empty($page) || !in_array($page, $array)) {
        return false;
    }
    
    if (Wo_IsAdmin() || Wo_IsModerator()) {
        return true;
    }

    $user_id = $wo['user']['id'];
    if(!in_array($page, ['analytics', ])) {
        $page = Wo_Secure($page);
    }
    $community_id = Wo_Secure($community_id);
    $query = mysqli_query($sqlConnect, " SELECT COUNT(*) FROM " . T_COMMUNITY_MODERATORS . " WHERE `community_id` = {$community_id} AND `user_id` = {$user_id} AND `{$page}` = '1'");
    
    return (Wo_Sql_Result($query, '0') == 1) ? true : false;

}
function Wo_GetAllowedCommunityPages($community_id)
{
    global $sqlConnect, $wo;
    if ($wo['loggedin'] == false) {
        return false;
    }
    if (empty($community_id) || !is_numeric($community_id) || $community_id < 0) {
        return false;
    }
    $array = array('general' => 'general-setting', 'privacy' => 'privacy-setting', 'avatar' => 'avatar-setting', 'members' => 'community-members', 'analytics' => 'analytics', 'delete_community' => 'delete-community');
    $data = array();
    $user_id = $wo['user']['id'];
    $community_id = Wo_Secure($community_id);
    $query = mysqli_query($sqlConnect, " SELECT * FROM " . T_COMMUNITY_MODERATORS . " WHERE `community_id` = {$community_id} AND `user_id` = {$user_id}");
    if (mysqli_num_rows($query)) {
        $fetched_data = mysqli_fetch_assoc($query);
        if (!empty($fetched_data)) {
            foreach ($fetched_data as $key => $value) {
                if (in_array($key, array_keys($array)) && $value == 1) {
                    $data[] = $array[$key]; 
                }
            }
        }
    }
        
    return $data;
}
function Wo_GetCommunityCategoriesKeys($table)
{
    global $sqlConnect, $wo;
    $data = array();
    $categories = mysqli_query($sqlConnect, "SELECT * FROM " . $table);
    if (mysqli_num_rows($categories)) {
        while ($fetched_data = mysqli_fetch_assoc($categories)) {
            $data[$fetched_data['id']] = $fetched_data['lang_key'];
        }
        if ($table == 'wo_products_categories') {
            $data[0] = 'all_';
        }
        else{
            $data[1] = 'other';
        }
        return $data;
    }
    return false;
}
function Wo_CountCommunityData($type) {
    global $wo, $sqlConnect;
    $type_table = T_PAGES;
    $type_id    = 'id';
    $where      = '';
    if ($type == 'members') {
        $type_table = T_COMMUNITY_MEMBERS;
        $where      = "`active` = '1'";
        $type_id    = 'id';
    } else if ($type == 'communities_posts') {
        $type_table = T_POSTS;
        $where      = "`community_id` <> 0";
        $type_id    = 'id';
    } else if ($type == 'join_requests') {
        $type_table = T_COMMUNITY_MEMBERS;
        $where      = "`active` = '0'";
        $type_id    = 'id';
    }
    $query_one    = mysqli_query($sqlConnect, "SELECT COUNT($type_id) as count FROM {$type_table} WHERE {$where}");
    if (mysqli_num_rows($query_one)) {
        $fetched_data = mysqli_fetch_assoc($query_one);
        return $fetched_data['count'];
    }
    return false;
        
}
function Wo_CommunityExistsByID($id) {
    global $sqlConnect;
    if (empty($id)) {
        return false;
    }
    $id    = Wo_Secure($id);
    $query = mysqli_query($sqlConnect, "SELECT COUNT(`id`) FROM " . T_COMMUNITIES . " WHERE `id`= '{$id}' AND `active` = '1'");
    return (Wo_Sql_Result($query, 0) == 1) ? true : false;
}
function Wo_RegsiterCommunityAdd($user_id, $community_id) {
    global $sqlConnect, $wo;
    if ($wo['loggedin'] == false) {
        return false;
    }
    if (empty($user_id) || !is_numeric($user_id)) {
        return false;
    }
    if (empty($community_id) || !is_numeric($community_id)) {
        return false;
    }
    if (Wo_IsCommunityJoined($community_id, $user_id) === true) {
        return false;
    }
    if (Wo_CommunityExistsByID($community_id) === false) {
        return false;
    }
    if (Wo_UserExistsById($user_id) === false) {
        return false;
    }
    $logged_user_id = Wo_Secure($wo['user']['user_id']);
    $community_data     = Wo_CommunityData($community_id);
    $user_id        = Wo_Secure($user_id);
    $query_one      = mysqli_query($sqlConnect, " INSERT INTO " . T_COMMUNITY_MEMBERS . " (`user_id`,`community_id`,`active`,`time`) VALUES ({$user_id},{$community_id},'1'," . time() . ")");
    if ($query_one) {
        $notification_data_array = array(
            'recipient_id' => $user_id,
            'type' => 'added_you_to_community',
            'community_id' => $community_id,
            'url' => 'index.php?link1=timeline&u=' . $community_data['community_name']
        );
        Wo_RegisterNotification($notification_data_array);
        return true;
    }
}
function Wo_RequestCommunity($request_data = array()) {
    global $wo, $sqlConnect;
    if (empty($request_data)) {
        return false;
    }
    $fields                          = "`" . implode("`, `", array_keys($request_data)) . "`";
    $data                            = '\'' . implode('\', \'', $request_data) . '\'';
    
    $query                           = mysqli_query($sqlConnect, "INSERT INTO " . T_COMMUNITY_REQUEST . " ({$fields}) VALUES ({$data})");
    if ($query) {
        return true;
    } else {
        return false;
    }
}

function Wo_AddCommunityModerator($user_id = false, $community_id = false) {
    global $sqlConnect, $wo;
    if ($wo['loggedin'] == false || !is_numeric($user_id) || !is_numeric($community_id)) {
        return false;
    }
    $user_id  = Wo_Secure($user_id);
    $community_id = Wo_Secure($community_id);
    $code     = false;
    if (Wo_IsCommunityUserExists($wo['user']['id'], $community_id)) {
        return true;
    }
    //if (Wo_IsCommunityUserExists($user_id, $community_id)) {
        //@mysqli_query($sqlConnect, "DELETE FROM " . T_COMMUNITY_MODERATORS . " WHERE `user_id` = {$user_id} AND `community_id` = {$community_id}");
        //$code = 0;
    //} else {
        @mysqli_query($sqlConnect, "INSERT INTO " . T_COMMUNITY_MODERATORS . " (`id`,`user_id`,`community_id`,`members`,`delete_community`) VALUES (null,$user_id,$community_id,1,1)");
        $community                   = Wo_CommunityData($community_id);
        $notification_data_array = array(
            'recipient_id' => $user_id,
            'type' => 'community_moderator',
            'user_id' => $wo['user']['id'],
            'url' => 'index.php?link1=timeline&u=' . $community['community_name']
        );
        Wo_RegisterNotification($notification_data_array);
        $code = 1;
    //}
    return $code;
}
function Wo_CheckCommunityAdminPassword($password = false, $community_id = false) {
    global $sqlConnect, $wo;
    if ($wo['loggedin'] == false || !is_numeric($password) || !is_numeric($community_id)) {
        return false;
    }
    $user_id  = Wo_Secure($wo['user']['user_id']);
    $community_id = Wo_Secure($community_id);
    $match    = false;
    if (Wo_IsCommunityUserExists($user_id, $community_id)) {
        $sql  = "SELECT `password` FROM " . T_USERS . " WHERE `user_id` = {$user_id}";
        $data = mysqli_query($sqlConnect, $sql);
        if (mysqli_num_rows($data) == 1) {
            $fetched_data = mysqli_fetch_assoc($data);
            if (Wo_HashPassword($password, $fetched_data['password'])) {
                $match = true;
            }
        }
    }
    return $match;
}
function Wo_UpdateCommunityModeratorData($community_id, $update_data,$user_id) {
    global $wo, $sqlConnect, $cache;
    if ($wo['loggedin'] == false) {
        return false;
    }
    if (empty($community_id) || !is_numeric($community_id) || $community_id < 0) {
        return false;
    }
    if (empty($user_id) || !is_numeric($user_id) || $user_id < 0) {
        return false;
    }
    if (empty($update_data)) {
        return false;
    }
    $user_id = Wo_Secure($user_id);
    $community_id = Wo_Secure($community_id);
    $update = array();
    foreach ($update_data as $field => $data) {
        $update[] = '`' . $field . '` = \'' . Wo_Secure($data, 0) . '\'';
    }
    $impload   = implode(', ', $update);
    $query_one = " UPDATE " . T_COMMUNITY_MODERATORS . " SET {$impload} WHERE `community_id` = {$community_id} AND `user_id` = '{$user_id}' ";
    $query     = mysqli_query($sqlConnect, $query_one);
    if ($query) {
        return true;
    } else {
        return false;
    }
}
function Wo_CheckCommunityModeratorPassword($password = false, $community_id = false) {
    global $sqlConnect, $wo;
    if ($wo['loggedin'] == false || !is_numeric($password) || !is_numeric($community_id)) {
        return false;
    }
    $user_id  = Wo_Secure($wo['user']['user_id']);
    $community_id = Wo_Secure($community_id);
    $match    = false;
    if (Wo_IsCommunityUserExists($user_id, $community_id)) {
        $sql  = "SELECT `password` FROM " . T_USERS . " WHERE `user_id` = {$user_id}";
        $data = mysqli_query($sqlConnect, $sql);
        if (mysqli_num_rows($data) == 1) {
            $fetched_data = mysqli_fetch_assoc($data);
            if (Wo_HashPassword($password, $fetched_data['password'])) {
                $match = true;
            }
        }
    }
    return $match;
}
function Wo_IsJoinCommunityRequested($community_id = 0, $user_id = 0) {
    global $sqlConnect, $wo;
    if ($wo['loggedin'] == false) {
        return false;
    }
    $user_id = Wo_Secure($user_id);
    if (!isset($user_id) or empty($user_id) or !is_numeric($user_id) or $user_id < 1) {
        $user_id = Wo_Secure($wo['user']['user_id']);
    }
    if (!is_numeric($community_id) or $community_id < 1) {
        return false;
    }
    $community_id  = Wo_Secure($community_id);
    $query     = "SELECT `id` FROM " . T_COMMUNITY_MEMBERS . " WHERE `community_id` = {$community_id} AND `user_id` = {$user_id} AND `active` = '0'";
    $sql_query = mysqli_query($sqlConnect, $query);
    if (mysqli_num_rows($sql_query) > 0) {
        return true;
    }
}
function Wo_DeleteJoinCommunityRequest($user_id, $community_id) {
    global $wo, $sqlConnect;
    if ($wo['loggedin'] == false) {
        return false;
    }
    if (!isset($user_id) or empty($user_id) or !is_numeric($user_id) or $user_id < 1) {
        return false;
    }
    if (!isset($community_id) or empty($community_id) or !is_numeric($community_id) or $community_id < 1) {
        return false;
    }
    $community_id = Wo_Secure($community_id);
    $user_id  = Wo_Secure($user_id);
    if (Wo_IsJoinCommunityRequested($community_id, $user_id) === false) {
        return false;
    }
    if (Wo_IsCommunityJoined($community_id, $user_id) === true) {
        return false;
    }
    $query     = "SELECT `id` FROM " . T_COMMUNITY_MEMBERS . " WHERE `community_id` = {$community_id} AND `user_id` = {$user_id} AND `active` = '0'";
    $sql_query = mysqli_query($sqlConnect, $query);
    if (mysqli_num_rows($sql_query) == 0) {
        return false;
    }
    $query = mysqli_query($sqlConnect, "DELETE FROM " . T_COMMUNITY_MEMBERS . " WHERE `user_id` = {$user_id} AND `community_id` = {$community_id} AND `active` = '0'");
    if ($query) {
        return true;
    }
}
function Wo_AcceptJoinCommunityRequest($user_id, $community_id) {
    global $wo, $sqlConnect;
    if ($wo['loggedin'] == false) {
        return false;
    }
    if (!isset($user_id) or empty($user_id) or !is_numeric($user_id) or $user_id < 1) {
        return false;
    }
    if (!isset($community_id) or empty($community_id) or !is_numeric($community_id) or $community_id < 1) {
        return false;
    }
    $community_id = Wo_Secure($community_id);
    $user_id  = Wo_Secure($user_id);

    if (Wo_IsJoinCommunityRequested($community_id, $user_id) === false) {
        return false;
    }
    if (Wo_IsCommunityJoined($community_id, $user_id) === true) {
        return false;
    }

    $query     = "SELECT `id` FROM " . T_COMMUNITY_MEMBERS . " WHERE `community_id` = {$community_id} AND `user_id` = {$user_id} AND `active` = '0'";
    $sql_query = mysqli_query($sqlConnect, $query);
    if (mysqli_num_rows($sql_query) == 0) {
        return false;
    }
    $query = mysqli_query($sqlConnect, "UPDATE " . T_COMMUNITY_MEMBERS . " SET `active` = '1' WHERE `user_id` = {$user_id} AND `community_id` = {$community_id} AND `active` = '0'");
    if ($query) {
        $community                   = Wo_CommunityData($community_id);
        $notification_data_array = array(
            'recipient_id' => $user_id,
            'notifier_id' => $community['user_id'],
            'type' => 'accepted_community_join_request',
            'community_id' => $community_id,
            'url' => 'index.php?link1=timeline&u=' . $community['community_name']
        );
        Wo_RegisterNotification($notification_data_array);
        return true;
    }
}

function Wo_GetCommunitiesByParent($table, $parent_id = 0)
{
    global $sqlConnect;
    $data = array();
    $communities = mysqli_query($sqlConnect, "SELECT * FROM " . $table ." WHERE `parent_id` = {$parent_id} AND `active` = '1'");

    if (mysqli_num_rows($communities)) {
        while ($fetched_data = mysqli_fetch_assoc($communities)) {
            if (is_array($fetched_data)) {
                $data[] = Wo_CommunityData($fetched_data['id']);
            }
        }
    }

    return $data;
}

function Wo_GetCommunityMessages($args = array()) {
    global $wo, $sqlConnect, $db;
    if ($wo['loggedin'] == false) {
        return false;
    }
    $options        = array(
        "id" => false,
        "offset" => 0,
        "community_id" => false,
        "limit" => 50,
        "old" => false,
        "new" => false
    );
    $args           = array_merge($options, $args);
    $offset         = Wo_Secure($args['offset']);
    $id             = Wo_Secure($args['id']);
    $community_id   = Wo_Secure($args['community_id']);
    $limit          = Wo_Secure($args['limit']);
    $new            = Wo_Secure($args['new']);
    $old            = Wo_Secure($args['old']);
    $query_one      = '';
    $data           = array();
    $logged_user_id = Wo_Secure($wo['user']['user_id']);
    $message_data   = array();
    if (empty($community_id) || !is_numeric($community_id) || $community_id < 0) {
        return false;
    }
    if ($id && is_numeric($id) && $id > 0) {
        $query_one .= " AND `id` = '$id' ";
    }
    if ($new && $offset && $offset > 0 && !$old) {
        $query_one .= " AND `id` > {$offset} AND `id` <> {$offset} ";
    }
    if ($old && $offset && $offset > 0 && !$new) {
        $query_one .= " AND `id` < {$offset} AND `id` <> {$offset} ";
    }
    $query_one        = " SELECT * FROM " . T_MESSAGES . " WHERE `community_id` = '$community_id' {$query_one} ";
    $sql_query_one    = mysqli_query($sqlConnect, $query_one);
    $query_limit_from = mysqli_num_rows($sql_query_one) - 50;
    if ($query_limit_from < 1) {
        $query_limit_from = 0;
    }
    if (isset($limit)) {
        $query_one .= " ORDER BY `id` ASC LIMIT {$query_limit_from}, 50";
    }
    $query = mysqli_query($sqlConnect, $query_one);
    if (mysqli_num_rows($query)) {
        while ($fetched_data = mysqli_fetch_assoc($query)) {
            $fetched_data['user_data'] = Wo_UserData($fetched_data['from_id']);
            $fetched_data['text']      = Wo_Markup($fetched_data['text']);
            $fetched_data['text']      = Wo_Emo($fetched_data['text']);
            $fetched_data['onwer']     = ($fetched_data['user_data']['user_id'] == $wo['user']['user_id']) ? 1 : 0;
            $fetched_data['reply']     = array();
            if (!empty($fetched_data['reply_id'])) {
                $fetched_data['reply'] = GetMessageById($fetched_data['reply_id']);
            }
            $fetched_data['pin'] = 'no';
            $mute                = $db->where('user_id', $wo['user']['id'])->where('message_id', $fetched_data['id'])->where('pin', 'yes')->getOne(T_MUTE);
            if (!empty($mute)) {
                $fetched_data['pin'] = 'yes';
            }
            $fetched_data['fav'] = 'no';
            $mute                = $db->where('user_id', $wo['user']['id'])->where('message_id', $fetched_data['id'])->where('fav', 'yes')->getOne(T_MUTE);
            if (!empty($mute)) {
                $fetched_data['fav'] = 'yes';
            }
            $message_data[] = $fetched_data;
        }
    }
    return $message_data;
}

function Wo_GetCommunityMessagesAPP($args = array()) {
    global $wo, $sqlConnect;
    if ($wo['loggedin'] == false) {
        return false;
    }
    $options        = array(
        "id" => false,
        "offset" => 0,
        "community_id" => false,
        "limit" => 50,
        "old" => false,
        "new" => false
    );
    $args           = array_merge($options, $args);
    $offset         = Wo_Secure($args['offset']);
    $id             = Wo_Secure($args['id']);
    $community_id       = Wo_Secure($args['community_id']);
    $limit          = Wo_Secure($args['limit']);
    $new            = Wo_Secure($args['new']);
    $old            = Wo_Secure($args['old']);
    $query_one      = '';
    $data           = array();
    $logged_user_id = Wo_Secure($wo['user']['user_id']);
    $message_data   = array();
    if (empty($community_id) || !is_numeric($community_id) || $community_id < 0) {
        return false;
    }
    if ($id && is_numeric($id) && $id > 0) {
        $query_one .= " AND `id` = '$id' ";
    }
    if ($new && $offset && $offset > 0 && !$old) {
        $query_one .= " AND `id` > {$offset} AND `id` <> {$offset} ";
    }
    if ($old && $offset && $offset > 0 && !$new) {
        $query_one .= " AND `id` < {$offset} AND `id` <> {$offset} ";
    }
    $query_one     = " SELECT * FROM " . T_MESSAGES . " WHERE `community_id` = '$community_id' {$query_one} ";
    $sql_query_one = mysqli_query($sqlConnect, $query_one);
    if (isset($limit)) {
        $query_one .= " ORDER BY `id` DESC LIMIT {$limit}";
    }
    $query = mysqli_query($sqlConnect, $query_one);
    if (mysqli_num_rows($query)) {
        while ($fetched_data = mysqli_fetch_assoc($query)) {
            $fetched_data['user_data']    = Wo_UserData($fetched_data['from_id']);
            $fetched_data['orginal_text'] = Wo_EditMarkup($fetched_data['text']);
            $fetched_data['text']         = Wo_Markup($fetched_data['text']);
            $fetched_data['text']         = Wo_Emo($fetched_data['text']);
            $fetched_data['onwer']        = ($fetched_data['user_data']['user_id'] == $wo['user']['user_id']) ? 1 : 0;
            $fetched_data['reply']        = array();
            if (!empty($fetched_data['reply_id'])) {
                $fetched_data['reply'] = GetMessageById($fetched_data['reply_id']);
            }
            $message_data[] = $fetched_data;
        }
    }
    return $message_data;
}

function Wo_RegisterMessageCommunity($ms_data = array()) {
    global $wo, $sqlConnect;
    if ($wo['loggedin'] == false) {
        return false;
    }
    if (empty($ms_data)) {
        return false;
    }
    if (empty($ms_data['community_id']) || !is_numeric($ms_data['community_id']) || $ms_data['community_id'] < 0) {
        return false;
    }
    if (empty($ms_data['from_id']) || !is_numeric($ms_data['from_id']) || $ms_data['from_id'] < 0) {
        return false;
    }
    if (!isset($ms_data['stickers'])) {
        if (empty($ms_data['text']) || !isset($ms_data['text']) || strlen($ms_data['text']) < 0) {
            if (empty($ms_data['media']) || !isset($ms_data['media']) || strlen($ms_data['media']) < 0) {
                return false;
            }
        }
    }
    $link_regex = '/(http\:\/\/|https\:\/\/|www\.)([^\ ]+)/i';
    $i          = 0;
    preg_match_all($link_regex, $ms_data['text'], $matches);
    foreach ($matches[0] as $match) {
        $match_url       = strip_tags($match);
        $syntax          = '[a]' . urlencode($match_url) . '[/a]';
        $ms_data['text'] = str_replace($match, $syntax, $ms_data['text']);
    }
    $mention_regex = '/@([A-Za-z0-9_]+)/i';
    preg_match_all($mention_regex, $ms_data['text'], $matches);
    foreach ($matches[1] as $match) {
        $match         = Wo_Secure($match);
        $match_user    = Wo_UserData(Wo_UserIdFromUsername($match));
        $match_search  = '@' . $match;
        $match_replace = '@[' . $match_user['user_id'] . ']';
        if (isset($match_user['user_id'])) {
            $ms_data['text'] = str_replace($match_search, $match_replace, $ms_data['text']);
            $mentions[]      = $match_user['user_id'];
        }
    }
    $hashtag_regex = '/#([^`~!@$%^&*\#()\-+=\\|\/\.,<>?\'\":;{}\[\]* ]+)/i';
    preg_match_all($hashtag_regex, $ms_data['text'], $matches);
    foreach ($matches[1] as $match) {
        if (!is_numeric($match)) {
            $hashdata = Wo_GetHashtag($match);
            if (is_array($hashdata)) {
                $match_search  = '#' . $match;
                $match_replace = '#[' . $hashdata['id'] . ']';
                if (mb_detect_encoding($match_search, 'ASCII', true)) {
                    $ms_data['text'] = preg_replace("/$match_search\b/i", $match_replace, $ms_data['text']);
                } else {
                    $ms_data['text'] = str_replace($match_search, $match_replace, $ms_data['text']);
                }
                //$ms_data['text']      = preg_replace("/$match_search\b/i", $match_replace,  $ms_data['text']);
                $hashtag_query = " UPDATE " . T_HASHTAGS . " SET `last_trend_time` = " . time() . ", `trend_use_num` = " . ($hashdata['trend_use_num'] + 1) . " WHERE `id` = " . $hashdata['id'];
            }
        }
    }
    $fields = '`' . implode('`, `', array_keys($ms_data)) . '`';
    $data   = '\'' . implode('\', \'', $ms_data) . '\'';
    $query  = mysqli_query($sqlConnect, " INSERT INTO " . T_MESSAGES . " ({$fields}) VALUES ({$data})");
    if ($query) {
        $message_id = mysqli_insert_id($sqlConnect);
        if (!empty($ms_data['from_id'])) {
            $from_id = $ms_data['from_id'];
        }
        return $message_id;
    } else {
        return false;
    }
}
function Wo_RegisterCommunityMessage($ms_data = array()) {
    global $wo, $sqlConnect;
    if ($wo['loggedin'] == false) {
        return false;
    }
    if (empty($ms_data)) {
        return false;
    }
    if (empty($ms_data['community_id']) || !is_numeric($ms_data['community_id']) || $ms_data['community_id'] < 0) {
        return false;
    }
    if (empty($ms_data['from_id']) || !is_numeric($ms_data['from_id']) || $ms_data['from_id'] < 0) {
        return false;
    }
    if (empty($ms_data['text']) || !isset($ms_data['text']) || strlen($ms_data['text']) < 0) {
        if (empty($ms_data['media']) || !isset($ms_data['media']) || strlen($ms_data['media']) < 0) {
            return false;
        }
    }
    $link_regex = '/(http\:\/\/|https\:\/\/|www\.)([^\ ]+)/i';
    $i          = 0;
    preg_match_all($link_regex, $ms_data['text'], $matches);
    foreach ($matches[0] as $match) {
        $match_url       = strip_tags($match);
        $syntax          = '[a]' . urlencode($match_url) . '[/a]';
        $ms_data['text'] = str_replace($match, $syntax, $ms_data['text']);
    }
    $mention_regex = '/@([A-Za-z0-9_]+)/i';
    preg_match_all($mention_regex, $ms_data['text'], $matches);
    foreach ($matches[1] as $match) {
        $match         = Wo_Secure($match);
        $match_user    = Wo_UserData(Wo_UserIdFromUsername($match));
        $match_search  = '@' . $match;
        $match_replace = '@[' . $match_user['user_id'] . ']';
        if (isset($match_user['user_id'])) {
            $ms_data['text'] = str_replace($match_search, $match_replace, $ms_data['text']);
            $mentions[]      = $match_user['user_id'];
        }
    }
    $hashtag_regex = '/#([^`~!@$%^&*\#()\-+=\\|\/\.,<>?\'\":;{}\[\]* ]+)/i';
    preg_match_all($hashtag_regex, $ms_data['text'], $matches);
    foreach ($matches[1] as $match) {
        if (!is_numeric($match)) {
            $hashdata = Wo_GetHashtag($match);
            if (is_array($hashdata)) {
                $match_search  = '#' . $match;
                $match_replace = '#[' . $hashdata['id'] . ']';
                if (mb_detect_encoding($match_search, 'ASCII', true)) {
                    $ms_data['text'] = preg_replace("/$match_search\b/i", $match_replace, $ms_data['text']);
                } else {
                    $ms_data['text'] = str_replace($match_search, $match_replace, $ms_data['text']);
                }
                $hashtag_query = " UPDATE " . T_HASHTAGS . " SET `last_trend_time` = " . time() . ", `trend_use_num` = " . ($hashdata['trend_use_num'] + 1) . " WHERE `id` = " . $hashdata['id'];
            }
        }
    }
    $fields = '`' . implode('`, `', array_keys($ms_data)) . '`';
    $data   = '\'' . implode('\', \'', $ms_data) . '\'';
    $query  = mysqli_query($sqlConnect, " INSERT INTO " . T_MESSAGES . " ({$fields}) VALUES ({$data})");
    if ($query) {
        $message_id = mysqli_insert_id($sqlConnect);
        return $message_id;
    } else {
        return false;
    }
}
