<?php
$response_data = array("api_status" => "400");

try {
    if (!empty($_POST["user_id"])) {
        $uid = intval($_POST["user_id"]);
        global $sqlConnect, $wo;
        
        $post_text = Wo_Secure($_POST["postText"] ?? "");
        $privacy = intval($_POST["postPrivacy"] ?? 0);
        
        if (empty($wo["user"]["user_id"])) {
            $ud = Wo_UserData($uid);
            if (!empty($ud)) {
                $wo["loggedin"] = true;
                $wo["user"] = $ud;
            }
        }
        
        $post_photo = "";
        $post_file = "";
        $post_filename = "";
        $multi_image = 0;
        
        function save_single_file($tmp, $orig_name, $uid, $type = "image") {
            if (empty($tmp) || !is_string($tmp)) return "";
            $orig_str = is_string($orig_name) ? $orig_name : "file";
            $ext = strtolower(pathinfo($orig_str, PATHINFO_EXTENSION));
            if (empty($ext)) {
                $ext = ($type == "video") ? "mp4" : (($type == "audio") ? "mp3" : "jpg");
            }
            $month = date("Y") . "/" . date("m");
            $dir = "/var/www/html/upload/photos/$month";
            if (!is_dir($dir)) mkdir($dir, 0755, true);
            $filename = $uid . "_" . time() . "_" . rand(1000, 9999) . "." . $ext;
            $dest = "$dir/$filename";
            $rel = "upload/photos/$month/$filename";
            if (move_uploaded_file($tmp, $dest)) return $rel;
            if (copy($tmp, $dest)) return $rel;
            $raw = @file_get_contents($tmp);
            if ($raw !== false && @file_put_contents($dest, $raw) !== false) return $rel;
            return "";
        }
        
        $uploaded_photos = array();
        $photos = !empty($_FILES["postPhotos"]) ? $_FILES["postPhotos"] : (!empty($_FILES["postPhotos[]"]) ? $_FILES["postPhotos[]"] : null);
        
        if (!empty($photos["tmp_name"])) {
            if (is_array($photos["tmp_name"])) {
                foreach ($photos["tmp_name"] as $i => $tmp) {
                    if (!empty($tmp) && is_string($tmp)) {
                        $fname = is_array($photos["name"]) ? ($photos["name"][$i] ?? "image_$i.jpg") : "image_$i.jpg";
                        $saved = save_single_file($tmp, $fname, $uid, "image");
                        if (!empty($saved)) {
                            $uploaded_photos[] = $saved;
                        }
                    }
                }
            } else if (is_string($photos["tmp_name"])) {
                $fname = is_string($photos["name"] ?? null) ? $photos["name"] : "image.jpg";
                $saved = save_single_file($photos["tmp_name"], $fname, $uid, "image");
                if (!empty($saved)) {
                    $uploaded_photos[] = $saved;
                }
            }
        }
        
        // Support indexed field names (postPhotos_0, postPhotos0, postPhotos[0], etc.)
        if (empty($uploaded_photos)) {
            foreach ($_FILES as $k => $f) {
                if (strpos($k, "postPhotos") === 0) {
                    if (is_array($f["tmp_name"])) {
                        foreach ($f["tmp_name"] as $idx => $t) {
                            if (!empty($t) && is_string($t)) {
                                $fn = is_array($f["name"]) ? ($f["name"][$idx] ?? "image.jpg") : "image.jpg";
                                $s = save_single_file($t, $fn, $uid, "image");
                                if (!empty($s)) $uploaded_photos[] = $s;
                            }
                        }
                    } else if (!empty($f["tmp_name"]) && is_string($f["tmp_name"])) {
                        $s = save_single_file($f["tmp_name"], $f["name"] ?? "image.jpg", $uid, "image");
                        if (!empty($s)) {
                            $uploaded_photos[] = $s;
                        }
                    }
                }
            }
        }
        
        if (count($uploaded_photos) > 1) {
            $multi_image = 1;
            $post_photo = '';
            $post_file = '';
            $post_filename = '';
        } elseif (count($uploaded_photos) == 1) {
            $multi_image = 0;
            $post_photo = '';
            $post_file = $uploaded_photos[0];
            $post_filename = is_array($photos["name"] ?? null) ? ($photos["name"][0] ?? "image.jpg") : ($photos["name"] ?? "image.jpg");
        }
        
        if (!empty($_FILES["postVideo"]["tmp_name"]) && is_string($_FILES["postVideo"]["tmp_name"])) {
            $saved = save_single_file($_FILES["postVideo"]["tmp_name"], $_FILES["postVideo"]["name"] ?? "video.mp4", $uid, "video");
            if (!empty($saved)) { $post_file = $saved; $post_filename = $_FILES["postVideo"]["name"] ?? "video.mp4"; }
        }
        if (!empty($_FILES["postMusic"]["tmp_name"]) && is_string($_FILES["postMusic"]["tmp_name"])) {
            $saved = save_single_file($_FILES["postMusic"]["tmp_name"], $_FILES["postMusic"]["name"] ?? "audio.mp3", $uid, "audio");
            if (!empty($saved)) { $post_file = $saved; $post_filename = $_FILES["postMusic"]["name"] ?? "audio.mp3"; }
        }
        if (!empty($_FILES["postFile"]["tmp_name"]) && is_string($_FILES["postFile"]["tmp_name"])) {
            $saved = save_single_file($_FILES["postFile"]["tmp_name"], $_FILES["postFile"]["name"] ?? "file", $uid, "file");
            if (!empty($saved)) { $post_file = $saved; $post_filename = $_FILES["postFile"]["name"] ?? "file"; }
        }
        
        $post_data = array(
            "user_id" => $uid,
            "postText" => $post_text,
            "postPrivacy" => $privacy,
            "time" => time(),
            "active" => 1
        );
        if ($multi_image) {
            $post_data["multi_image"] = 1;
        }
        if (!empty($post_photo)) { 
            $post_data["postPhoto"] = $post_photo;
        }
        if (!empty($post_file)) { $post_data["postFile"] = $post_file; }
        if (!empty($post_filename)) { $post_data["postFileName"] = $post_filename; }
        
        $id = Wo_RegisterPost($post_data);
        
        if (!empty($id)) {
            if ($multi_image == 1) {
                foreach ($uploaded_photos as $photo) {
                    if (function_exists('Wo_RegisterAlbumMedia')) {
                        Wo_RegisterAlbumMedia($id, $photo);
                    } else {
                        $p = mysqli_real_escape_string($sqlConnect, $photo);
                        mysqli_query($sqlConnect, "INSERT INTO " . T_ALBUMS_MEDIA . " (`post_id`, `image`, `parent_id`) VALUES ({$id}, '{$p}', 0)");
                    }
                }
            }
            $response_data = array("api_status" => "200", "post_data" => array("post_id" => strval($id)), "post_html" => "");
        } else {
            $time = time();
            $pt = mysqli_real_escape_string($sqlConnect, $post_text);
            $pp = mysqli_real_escape_string($sqlConnect, $post_photo);
            $pf = mysqli_real_escape_string($sqlConnect, $post_file);
            $pfn = mysqli_real_escape_string($sqlConnect, $post_filename);
            $mi = intval($multi_image);
            
            $cols = "`user_id`,`postText`,`postPrivacy`,`time`,`active`,`community_id`";
            $vals = "'$uid','$pt','$privacy','$time','1','0'";
            if ($mi == 1) {
                $cols .= ",`multi_image`";
                $vals .= ",'1'";
            }
            if (!empty($post_photo)) { 
                $cols .= ",`postPhoto`"; 
                $vals .= ",'$pp'"; 
            }
            if (!empty($post_file)) { $cols .= ",`postFile`"; $vals .= ",'$pf'"; }
            if (!empty($post_filename)) { $cols .= ",`postFileName`"; $vals .= ",'$pfn'"; }
            
            $sql = "INSERT INTO " . T_POSTS . " ($cols) VALUES ($vals)";
            $query = mysqli_query($sqlConnect, $sql);
            
            if ($query) {
                $id = mysqli_insert_id($sqlConnect);
                mysqli_query($sqlConnect, "UPDATE " . T_POSTS . " SET `post_id` = {$id} WHERE `id` = {$id}");
                if ($mi == 1) {
                    foreach ($uploaded_photos as $photo) {
                        if (function_exists('Wo_RegisterAlbumMedia')) {
                            Wo_RegisterAlbumMedia($id, $photo);
                        } else {
                            $p = mysqli_real_escape_string($sqlConnect, $photo);
                            mysqli_query($sqlConnect, "INSERT INTO " . T_ALBUMS_MEDIA . " (`post_id`, `image`, `parent_id`) VALUES ({$id}, '{$p}', 0)");
                        }
                    }
                }
                $response_data = array("api_status" => "200", "api_text" => "success", "post_data" => array("post_id" => strval($id)), "post_html" => "");
            } else {
                $response_data = array("api_status" => "400", "errors" => array("error_id" => 99, "error_text" => "SQL Error: " . mysqli_error($sqlConnect)));
            }
        }
    }
} catch (\Throwable $e) {
    $response_data = array(
        "api_status" => "400",
        "errors" => array(
            "error_id" => 500,
            "error_text" => "Server error: " . $e->getMessage()
        )
    );
}
?>
