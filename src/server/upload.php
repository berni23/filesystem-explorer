<?php


include 'utils.php';


if (isset($_FILES['file'])) {

    $ext_error = false;
    $path_info = pathinfo($_FILES['file']['name']);
    $uploadErrors = uploadErors(); // list of possible errors when uploading a file


    // case no extension
    if (!isset($path_info['extension'])) {

        echo  json_encode(array("status" => 400, "message" => 'please provide a file extension'));
        exit;
    }


    if (!in_array($path_info['extension'], validExtensions()))  $ext_error = true;

    //if the error of the upload is not equal to 0
    if ($_FILES["file"]["error"]) {

        echo  json_encode(array("status" => 400, "message" => $uploaderrors[[$_FILES["file"]["error"]]]));
    } elseif ($ext_error) {
        $valid_ext = $extensions . implode(", ");
        $message = "Invalid file extension! " . "Please upload files with these extensions only:" . $valid_ext;
        echo json_encode(array("status" => 400, "message" => $message));
    } else {

        $destination =  relPath() . basename($_FILES['file']['name']);

        move_uploaded_file(($_FILES['file']['tmp_name']), $destination);

        chmod($destination, 0777);
        echo  json_encode(array("status" => 200, "message" =>   "Succes! The file has been uploaded"));
    }
}
