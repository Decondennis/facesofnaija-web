<?php 
//echo 'chibex';
if ($f == 'load-more-communities') {
    $offset = (isset($_GET['offset']) && is_numeric($_GET['offset'])) ? $_GET['offset'] : false;
    $query  = $_GET['query'];
    $html   = "";
    $data   = array(
        "status" => 404,
        "html" => $html
    );
    if ($offset) {
        $communities = Wo_GetSearchAdv($query, 'communities', $offset);
        if (count($communities) > 0) {
            foreach ($communities as $wo['result']) {
                // if ($wo['config']['theme'] == 'sunshine') {
                //     $html .= Wo_LoadPage('search/group-result');
                // }
                // else{
                //     $html .= Wo_LoadPage('search/result');
                // }
                $html .= Wo_LoadPage('search/community-result');
            }
            $data['status'] = 200;
            $data['html']   = $html;
        }
    }
    header("Content-type: application/json");
    echo json_encode($data);
    exit();
}
