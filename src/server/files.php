<?php


include "utils.php";

session_start();


$root = relPath();


// check if file exists
// check if  the extension is right  -> json file with right extensions ( validation)


if (isset($_GET["makeFile"])) {

    $data = json_decode(file_get_contents('php://input'), true);
    if (isset($data['path']) && isset($data['filename'])) {
        $path =  $root . $data['path'];

        if (file_exists($path)) {
            echo  json_encode(array('status' => 400, 'message' => 'file exists'));
        } else {
            $myfile = fopen($path, "w");
            fclose($myfile);
            chmod($path, 0777);
            echo json_encode(array('status' => 200, 'message' => 'file created'));
            exit;
        }
    }
}


if (isset($_GET["getAllPaths"]))  echo json_encode(getAllPaths($root));

if (isset($_GET["folderContent"])) {

    $path = $root . $_GET["folderContent"];
    echo json_encode(getFolderContent($path, true));
}
