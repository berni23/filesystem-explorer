<?php


include 'utils.php';

if (isset($_FILES['file'])) {

    $location_file = $_GET["location"];
    $ext_error = false;
    $path_info = pathinfo($_FILES['file']['name']);
    $uploadErrors = uploadErors(); // list of possible errors when uploading a file
    $extensions = validExtensions();

    // case no extension set
    if (!isset($path_info['extension'])) {

        echo  json_encode(array(null, array("status" => 400, "message" => 'please provide a file extension')));
    } elseif (!in_array($path_info['extension'], $extensions)) {

        $valid_ext = implode(", ", $extensions);
        $message = "Invalid file extension! " . "Please upload files with these extensions only:" . $valid_ext;
        echo json_encode(array("status" => 400, "message" => $message));
    }
    //if the error of the upload is not equal to 0
    elseif ($_FILES["file"]["error"] != 0) {
        echo  json_encode(array(null, array("status" => 400, "message" => $uploaderrors[[$_FILES["file"]["error"]]])));
    } else {

        $destination =  relPath() . $location_file . '/' . basename($_FILES['file']['name']);
        move_uploaded_file(($_FILES['file']['tmp_name']), $destination);
        chmod($destination, 0777);
        $uploadedFile = new File(new SplFileInfo($destination));
        echo  json_encode(array($uploadedFile, array("status" => 200, "message" => "Success! The file has been uploaded")));
    }
}
