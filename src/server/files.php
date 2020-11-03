<?php
include "utils.php";

session_start();

$root = relPath();
$trash = trashPath();

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
                chmod($path, 0777);
                fclose($myfile);
                $objFile = new File(new SplFileInfo($path));
                echo json_encode(array($objFile, array('status' => 200, 'message' => 'file successfully created')));
            }
        }
    } else if ($data['type'] == 'folder') {
        $path = $root . $data['path'] . '/' . $data['filename'];
        if (file_exists($path)) echo json_encode(array(null, array('status' => 400, 'message' => 'file exists')));
        else {
            $result = mkdir($path, 0777);
            if ($result) {
                echo json_encode(array(new File(new splFileInfo($path)), array('status' => 200, 'message' => 'directory successfully created')));
                chmod($path, 0777);
            } else echo json_encode(array(null, array('status' => 400, 'message' => 'unknown error, directory could not be created')));
        }
    }
}

if (isset($_GET["getAllPaths"])) echo json_encode(getAllPaths($root));
if (isset($_GET["inputSearch"])) echo json_encode(searchFiles($root, $_GET["inputSearch"]));
if (isset($_GET["folderContent"])) echo json_encode(getFolderContent($root  . $_GET["folderContent"]));
if (isset($_GET["getFile"])) echo json_encode(getFile($root . $_GET["getFile"]));
if (isset($_GET["move"])) {

    $data = json_decode(file_get_contents('php://input'), true);
    $file = is_dir($data['origin']) ? 'folder' : 'file';
    $end = $root . ($data['end'] ? $data['end'] . '/' : '');
    $origin = $root  . $data['origin'];
    if (!is_dir($end))  echo json_encode(array(null, array('status' => 400, 'message' => "error," . $data['end'] . " is not a directory ")));

    else {
        $result = rename($origin, $end . $data['filename']);
        if ($result) echo json_encode(array(new File(new SplFileInfo($end  . $data['filename'])), array('status' => 200, 'message' => $file . " sucessfully relocated")));
        else  echo json_encode(array(null, array('status' => 400, 'message' => "Unknown error, $file could not be relocated")));
    }
}


if (isset($_GET["sendToDelete"])) {

    $data = json_decode(file_get_contents('php://input'), true);
    $file = is_dir($data['origin']) ? 'folder' : 'file';
    $end = $root . '/' . $data['end'];
    $origin = $root . '/' . $data['origin'];
    $result = rename($origin, $end . '/' . $data['filename']);
    if ($result) echo json_encode(array(new File(new SplFileInfo($data['end'] . '/' . $data['filename'])), 'status' => 200, 'message' => "$file sent to trash"));
    else  echo json_encode(array(new File(new SplFileInfo($data['end'] . '/' . $data['filename'])), 'status' => 400, 'message' => "unknown error, $file  could not be sent to trash"));
}
