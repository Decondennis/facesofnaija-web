<?php 
if ($f == 'upload_image') {
    $data = array('status' => 400, 'message' => 'Invalid file');
    if (isset($_FILES['image']['name'])) {
        $fileInfo = array(
            'file' => $_FILES["image"]["tmp_name"],
            'name' => $_FILES['image']['name'],
            'size' => $_FILES["image"]["size"],
            'type' => $_FILES["image"]["type"]
        );
        $media    = Wo_ShareFile($fileInfo);
        if (!empty($media)) {
            $mediaFilename    = $media['filename'];
            $mediaName        = $media['name'];
            $_SESSION['file'] = $mediaFilename;
            $data             = array(
                'status' => 200,
                'image' => Wo_GetMedia($mediaFilename),
                'image_src' => $mediaFilename
            );
        }
    }
    header("Content-type: application/json");
    echo json_encode($data);
    exit();
}
