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
    $query_text = "SELECT `id` FROM " . T_COMMUNITIES . " WHERE `id` IN (SELECT `community_id` FROM ".T_COMMUNITY_MEMBERS." WHERE `user_id` = {$user_id})";
    if(Wo_IsAdmin() || Wo_IsModerator()) {
        $query_text = "SELECT `id` FROM " . T_COMMUNITIES;
    }

    $query_one  = mysqli_query($sqlConnect, $query_text);
    /*
     * Some installs used a slightly different table name (Wo_CommunityMembers)
     * without the underscore. If the primary query returns zero rows try
     * the alternate table name so existing membership records aren't missed.
     */
    if (mysqli_num_rows($query_one) == 0 && !Wo_IsAdmin() && !Wo_IsModerator()) {
        $alt_table = str_replace('_', '', T_COMMUNITY_MEMBERS); // e.g. Wo_CommunityMembers
        if ($alt_table != T_COMMUNITY_MEMBERS) {
            $alt_q = "SELECT `id` FROM " . T_COMMUNITIES . " WHERE `id` IN (SELECT `community_id` FROM {$alt_table} WHERE `user_id` = {$user_id})";
            $alt_res = @mysqli_query($sqlConnect, $alt_q);
            if ($alt_res && mysqli_num_rows($alt_res) > 0) {
                $query_one = $alt_res;
                // expose alternate-table detection in debug for troubleshooting
                $wo['joined_diag_alt_table'] = $alt_table;
            }
        }
    }

    if (mysqli_num_rows($query_one)) {
        while ($fetched_data = mysqli_fetch_assoc($query_one)) {
            if (is_array($fetched_data)) {
                $data[] = Wo_CommunityData($fetched_data['id']);
            }
        }
    }


    return  $data;
}


function Wo_GetCommunitiesNames($total = 100) {
    global $sqlConnect, $wo;
    if ($wo['loggedin'] == false) {
        return false;
    }
    $data       = array();
    $query_text = "SELECT `community_title` FROM " . T_COMMUNITIES . " LIMIT 0, {$total}";
    
    $query_one  = mysqli_query($sqlConnect, $query_text);
    if (mysqli_num_rows($query_one)) {
        while ($fetched_data = mysqli_fetch_assoc($query_one)) {
            if (is_array($fetched_data)) {
                $data[] = $fetched_data['community_title'];
            }
        }
    }
        

    return  $data;
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
    $query_one = " SELECT `id` FROM " . T_COMMUNITIES . " WHERE `active` = '1' AND `id` NOT IN (SELECT `community_id` FROM " . T_COMMUNITY_MEMBERS . " WHERE `user_id` = {$user_id})";
    if (isset($limit)) {
        $query_one .= " ORDER BY RAND() LIMIT {$limit}";
    }
    $sql = mysqli_query($sqlConnect, $query_one);
    if (mysqli_num_rows($sql)) {
        while ($fetched_data = mysqli_fetch_assoc($sql)) {
            $data[] = Wo_CommunityData($fetched_data['id']);
        }
    }

    // If no suggestions found (user joined all active communities),
    // fallback to returning random active communities so the UI isn't empty.
    if (empty($data)) {
        $fallback = array();
        $query_fb = " SELECT `id` FROM " . T_COMMUNITIES . " WHERE `active` = '1' ORDER BY RAND() LIMIT {$limit} ";
        $sql_fb = mysqli_query($sqlConnect, $query_fb);
        if (mysqli_num_rows($sql_fb)) {
            while ($f = mysqli_fetch_assoc($sql_fb)) {
                $fallback[] = Wo_CommunityData($f['id']);
            }
        }
        return $fallback;
    }

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
        return false;
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
function Wo_UpdateCommunityData($community_id = 0, $update_data = array()) {
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
        //if (Wo_IsCommunityOnwer($community_id) === false) {
            return false;
        //}
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
    
    if (!Wo_IsAdmin() && !Wo_IsModerator()) {
        return false;
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
