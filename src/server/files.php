<?php


include "utils.php";
// create file


session_start();


// check if file exists
// check if  the extension is right  -> json file with right extensions ( validation)


if (isset($_GET["makeFile"])) {

    $data = json_decode(file_get_contents('php://input'), true);
    if (isset($data['path']) && isset($data['filename'])) {
        $path =  relPath() . $data['path'];

        if (file_exists($path)) {
            echo  json_encode(array('status' => 400, 'message' => 'file exists'));
            exit;
        } else {
            $myfile = fopen($path, "w");
            fclose($myfile);
            chmod($path, 0777);
            echo json_encode(array('status' => 200, 'message' => 'file created'));
            exit;
        }
    }
}
if (isset($_GET["getAllPaths"])) {


    // METHOD 1  all paths

    $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(relPath()));
    $filtered = new RegexIterator($files, '/\./');
    $arrayPaths  = array();

    foreach ($filtered as $file) {

        $path =  $file->getRealPath();
        $path2 = str_replace(absPath(), "", $path);
        array_push($arrayPaths, $path2);
    }

    echo  json_encode($arrayPaths);
}



if (isset($_GET["getTreePath"])) {


    // METHOD 2 array tree path

    $rec = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(relPath()), RecursiveIteratorIterator::CHILD_FIRST);
    $r = array();
    foreach ($rec as $splFileInfo) {
        $path = $splFileInfo->isDir() ? array($splFileInfo->getFilename() => array())  : array($splFileInfo->getFilename());

        for ($depth = $rec->getDepth() - 1; $depth >= 0; $depth--) {
            $path = array($rec->getSubIterator($depth)->current()->getFilename() => $path);
        }
        $r = array_merge_recursive($r, $path);
    }

    print_r($r);
    exit();
}

// METHOD 2 scandir
