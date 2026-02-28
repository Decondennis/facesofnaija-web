<?php
/*
 * Add this function to assets/includes/functions_two.php or create a new file and include it
 */

function Wo_GetCommunityJoinButton($community_id = 0) {
    global $wo;
    if ($wo["loggedin"] == false) {
        return false;
    }
    if (empty($community_id) || !is_numeric($community_id) or $community_id < 0) {
        return false;
    }
    if (Wo_IsCommunityOnwer($community_id)) {
        return false;
    }
    $community_id = Wo_Secure($community_id);
    $community    = $wo["join"] = Wo_CommunityData($community_id);
    if (!isset($wo["join"]["id"])) {
        return false;
    }
    $logged_user_id        = Wo_Secure($wo["user"]["user_id"]);
    $join_button           = "buttons/community-join";
    $leave_button          = "buttons/community-leave";
    $accept_request_button = "buttons/community-join-requested";
    
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
?>
