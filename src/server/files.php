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
            $path =  $root . '/' . $data['path'] . "/" . $data['filename'];
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
        $path = $root . '/' . $data['path'] . '/' . $data['filename'];
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

if (isset($_GET["getAllPaths"])) echo json_encode(getAllPaths($root . '/'));
if (isset($_GET["inputSearch"])) echo json_encode(searchFiles($root . '/', $_GET["inputSearch"]));
if (isset($_GET["folderContent"])) echo json_encode(getFolderContent($root . '/'  . $_GET["folderContent"]));
if (isset($_GET["getFile"])) echo json_encode(getFile($root . '/' . $_GET["getFile"]));
if (isset($_GET["move"])) {

    $data = json_decode(file_get_contents('php://input'), true);
    $end = $root . '/' . $data['end'];
    $origin = $root . '/' . $data['origin'];
    $file = is_dir($data['origin']) ? 'folder' : 'file';
    $name = pathinfo($origin)['basename'];
    if (strlen(realpath($end)) < strlen(realpath($root)) || !is_dir($end)) echo json_encode(array(null, array('status' => 400, 'message' => "error,root/" . $data['end'] . " is not a directory ")));

    else {

        $result = rename($origin, $end . $name);
        if ($result) echo json_encode(array(new File(new SplFileInfo($end  . $name)), array('status' => 200, 'message' => $file . " sucessfully relocated")));
        else  echo json_encode(array(null, array('status' => 400, 'message' => "Unknown error, $file could not be relocated")));
    }
}

if (isset($_GET["delete"])) {
    $path = $root . '/' . $_GET["delete"];
    $name = pathinfo($path)["basename"];
    $file = is_dir($path) ? 'folder' : 'file';
    $oldFile = new File(new SplFileInfo($path));
    $result = rename($path, $trash . '/' . $name);
    if ($result) echo json_encode(array($oldFile, array('status' => 200, 'message' => "$file sent to trash")));
    else  echo json_encode(array(null, array('status' => 400, 'message' => "unknown error, $file  could not be sent to trash")));
}


if (isset($_GET['edit'])) {

    $data = json_decode(file_get_contents('php://input'), true);
    $path = $root . '/' . $data['path'];
    $infoPath = pathinfo($path);
    $name = $infoPath['basename'];
    $newPath = $infoPath['dirname'] . '/' . $data['newname'];
    $file = is_dir($path) ? 'folder' : 'file';

    if (is_dir($newPath) || file_exists($newPath)) echo json_encode(array(null, array('status' => 400, 'message' => "a file or directory with that name already exists")));
    else {

        $result = rename($path, $newPath);
        if ($result) echo json_encode(array(new File(new SplFileInfo($newPath)), array('status' => 200, 'message' => $file . ' successfully renamed')));
        else echo json_encode(array(null, array('status' => 400, 'message' => "unkown error, $file could not be renamed")));
    }
}

// to do: isset ( edit); ( only valid names, use validation from moving)
