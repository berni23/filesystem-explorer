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
        } else {
            $myfile = fopen($path, "w");
            fclose($myfile);
            chmod($path, 0777);
            echo json_encode(array('status' => 200, 'message' => 'file created'));
            exit;
        }
    }
}


if (isset($_GET["getAllPaths"]))  echo json_encode(getAllPaths());


function getAllPaths()
{

    $rootContent = []; // empty object
    $directory = new RecursiveDirectoryIterator(relPath(), RecursiveDirectoryIterator::SKIP_DOTS);
    $iterator = new RecursiveIteratorIterator($directory, RecursiveIteratorIterator::SELF_FIRST);
    foreach ($iterator as $info) {
        $fileObj = new File($info);

        array_push($rootContent, $fileObj);
    }

    return $rootContent;
}
