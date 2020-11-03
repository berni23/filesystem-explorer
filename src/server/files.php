<?php
include "utils.php";

session_start();

$root = relPath();

// check if file exists
// check if  the extension is right  -> json file with right extensions ( validation)

if (isset($_GET["makeFile"])) {

    $data = json_decode(file_get_contents('php://input'), true);

    if ($data['type'] == 'file') {
        if (isset($data['path']) && isset($data['filename'])) {
            $path =  $root . $data['path'] . "/" . $data['filename'];
            if (file_exists($path)) echo json_encode(array('status' => 400, 'message' => 'file exists'));
            else {
                $myfile = fopen($path, "w");
                chmod($destination, 0777);
                fclose($myfile);
                $objFile = new File(new SplFileInfo($path));
                echo json_encode(array($objFile, array('status' => 200, 'message' => 'file created')));
            }
        }
    } else if ($data['type'] == 'folder') {
        $path = $root . $data['path'] . '/' . $data['filename'];
        if (file_exists($path)) echo json_encode(array(null, array('status' => 400, 'message' => 'file exists')));
        else {
            $result = mkdir($path, 0777);
            if ($result) {
                echo json_encode(array(new File(new splFileInfo($path)), array('status' => 200, 'message' => 'directory successfully created')));
                chmod($destination, 0777);
            } else echo json_encode(array(null, array('status' => 400, 'message' => 'unknown error, directory could not be created')));
        }
    }
}

if (isset($_GET["getAllPaths"])) echo json_encode(getAllPaths($root));
if (isset($_GET["inputSearch"])) echo json_encode(searchFiles($root, $_GET["inputSearch"]));
if (isset($_GET["folderContent"])) echo json_encode(getFolderContent($root  . $_GET["folderContent"]));
if (isset($_GET["getFile"])) echo json_encode(getFile($root . $_GET["getFile"]));
