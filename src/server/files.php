<?php
include "utils.php";

session_start();

$root = relPath();

// check if file exists
// check if  the extension is right  -> json file with right extensions ( validation)

if (isset($_GET["makeFile"])) {
    $data = json_decode(file_get_contents('php://input'), true);
    if (isset($data['path']) && isset($data['filename'])) {
        $path =  $root . $data['path']."/".$data['filename'];
        if (file_exists($path)) echo json_encode(array('status' => 400, 'message' => 'file exists'));
        else {
            $myfile = fopen($path, "w");
            fclose($myfile);
            $objFile = new File(new SplFileInfo($path));
            echo json_encode(array($objFile, array('status' => 200, 'message' => 'file created')));
        }
    }
}

if (isset($_GET["getAllPaths"])) echo json_encode(getAllPaths($root));
if (isset($_GET["inputSearch"])) echo json_encode(searchFiles($root, $_GET["inputSearch"]));
if (isset($_GET["folderContent"])) echo json_encode(getFolderContent($root  . $_GET["folderContent"]));
