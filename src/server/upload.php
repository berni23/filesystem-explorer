<?php


include 'utils.php';


if (isset($_FILES['file'])) {

    $ext_error = false;
    $extensions = validExtensions(); //a list of extensions that we allow to be uploaded
    $path_info = pathinfo($_FILES['file']['name']);
    $uploadErrors = uploadErors(); // list of possible errors when uploading a file

    if (!in_array($path_info['extension'], $extensions))  $ext_error = true;

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
        echo  json_encode(array("status" => 200, "message" =>   "Succes! The file has been uploaded"));
    }
}
