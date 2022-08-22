<?php
// +------------------------------------------------------------------------+
// | @author Chibuike Mba (Facesofnaija)
// | @author_url 1: http://www.facesofnaija.com
// | @author_url 2: http://codecanyon.net/user/chibexme
// | @author_email: chibexme@gmail.com
// +------------------------------------------------------------------------+
// | Facesofnaija - The Ultimate Social Networking Platform
// | Copyright (c) 2022. All rights reserved.
// +------------------------------------------------------------------------+

//added this
$communitiesNames = Wo_GetCommunitiesNames();

shuffle($communitiesNames);
$namesStr = '';
for ($i=0; $i < count($communitiesNames); $i++) { 
    if ($i >= 10) {
        break;
    }
    
    if ($namesStr != '') {
        $namesStr .= ', ';
    }
    $namesStr .= $communitiesNames[$i];
}

$response_data = array(
    'api_status' => 200,
    'data' => $namesStr
);
