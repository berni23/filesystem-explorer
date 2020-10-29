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


    getUserRoot();
    function getUserRoot()
    {
        $root = []; // empty object

        $directory = new RecursiveDirectoryIterator(relPath(), RecursiveDirectoryIterator::SKIP_DOTS);
        $iterator = new RecursiveIteratorIterator($directory, RecursiveIteratorIterator::SELF_FIRST);

        foreach ($iterator as $info) {
            $tmpInfo = new stdClass;
            $tmpInfo->path = substr($info->getPath(), 6);
            $tmpInfo->name = $info->getBasename();
            $tmpInfo->size = $info->getSize();
            $tmpInfo->creationDate = $info->getCtime();
            $tmpInfo->modificationDate = $info->getMTime();
            if ($info->isDir()) {
                $tmpInfo->folders = true;
            } else {
                $tmpInfo->folders = false;
            }
            array_push($root, $tmpInfo);
        }

        return $root;
    }



    /* $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(relPath()));
    $filtered = new RegexIterator($files, '/./');
    $arrayPaths  = array();

    foreach ($filtered as $file) {

        $path =  $file->getPath();
        array_push($arrayPaths, $path);
    }

    echo  json_encode($arrayPaths);

    */
}

// var_dump(json_encode(scanAllDir(relPath())));
exit();



function scanAllDir($dir)
{
    $result = array("content" => array(), "isDir" => true);
    foreach (scandir($dir) as $filename) {
        if ($filename[0] === '.') continue;
        $filePath = $dir . '/' . $filename;
        if (is_dir($filePath)) {
            $child = array("content" => array(), "path" => $filePath, "isDir" => true);
            foreach (scanAllDir($filePath) as $childFilename) {
                array_push($child["content"], array("name" => $childFilename, "path" => $filename . '/' . $childFilename));
            }
        } else {
            array_push($result["content"], array("path" => $filePath, "isDir" => false));
        }
    }
    return $result;
}

/*// METHOD 1  all paths

    $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(relPath()));
    $filtered = new RegexIterator($files, '/[?!^.][?!^..][^\S+$]/');
    $arrayPaths  = array();

    foreach ($filtered as $file) {

        $path =  $file->getRealPath();
        $path2 = str_replace(absPath(), "", $path);
        array_push($arrayPaths, $path2);
    }

    echo  json_encode($arrayPaths);
}

*/



if (isset($_GET["getTreePath"])) {

    $rec = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(relPath()), RecursiveIteratorIterator::CHILD_FIRST);
    $r = array();
    foreach ($rec as $splFileInfo) {

        echo $splFileInfo;
        exit;

        $path = $splFileInfo->isDir() ? array($splFileInfo->getFilename() => array())  : array($splFileInfo->getFilename());
        for ($depth = $rec->getDepth() - 1; $depth >= 0; $depth--) {
            $path = array($rec->getSubIterator($depth)->current()->getFilename() => $path);
        }
        $r = array_merge_recursive($r, $path);
    }

    print_r($r);
    exit();
}
