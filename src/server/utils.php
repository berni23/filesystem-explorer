<?php

// relative path to the root folder

function relPath()
{
    return  '../../root';
}

// relative path to the trash folder

function trashPath()
{
    return  '../../trash';
}

// array of valid extension

function validExtensions()
{
    return   array('doc', 'docx', 'csv', 'jpg', 'png', 'txt', 'ppt', 'odt', 'pdf', 'zip', 'rar', 'exe', 'svg', 'mp3', 'mp4');
}

// array of errors when uploading

function uploadErors()
{
    return array(
        0 => 'There is no error, the file uploaded with success',
        1 => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
        2 => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
        3 => 'The uploaded file was only partially uploaded',
        4 => 'No file was uploaded',
        6 => 'Missing a temporary folder',
        7 => 'Failed to write file to disk',
        8 => 'A PHP extension stopped the file upload',
    );
}


// get all  sub paths recursively given a path

function getAllPaths($path)
{
    $rootContent = [];
    $directory = new RecursiveDirectoryIterator($path, RecursiveDirectoryIterator::SKIP_DOTS);
    $iterator = new RecursiveIteratorIterator($directory, RecursiveIteratorIterator::SELF_FIRST);
    foreach ($iterator as $info) {
        $fileObj = new File($info);
        array_push($rootContent, $fileObj);
    }
    return $rootContent;
}
function getFolderContent($path)
{
    $contents = scandir($path);  // gives the path of inner files
    $arrayFiles =  [];
    foreach ($contents as $filename) {
        if ($filename[0] != '.') {
            $file = new File(new SplFileInfo($path . '/' . $filename));
            array_push($arrayFiles, $file);
        }
    }
    return $arrayFiles;
}

function getFile($path)
{
    return new File(new SplFileInfo($path));
}

function searchFiles($path, $search)
{
    $content = [];
    $directory = new RecursiveDirectoryIterator($path, RecursiveDirectoryIterator::SKIP_DOTS);
    $iterator = new RecursiveIteratorIterator($directory, RecursiveIteratorIterator::SELF_FIRST);
    foreach ($iterator as $info) {
        $name = $info->getBasename();
        if (stripos($name, $search) !== false) {
            $fileObj = new File($info);
            array_push($content, $fileObj);
        }
    }
    return $content;
}


/*

class file used to pass information of a file from php to javascript.
The constructor expects a class of type SplFileInfo. as optional parameter, there is a boolean,
the class retrieves all the information if it is true, else just a subset of it necessary for populating
the initial fields.

*/
class File
{
    function __construct($info)
    {
        $absPath = $info->getPath();
        if (strlen($absPath) < strlen(relPath())) $this->name = '';
        else  $this->name = trim($info->getBasename());
        $path =  str_replace("\\", "", substr($info->getPath(), strlen(relPath() . '/')));
        if ((trim($path)))  $this->path = trim($path) . '/' . $this->name;
        else $this->path = $this->name;
        $this->parentPath = $path;
        if ($info->isDir()) $this->extension = 'folder';
        else $this->extension = $info->getExtension();
        $size = ($info->getSize()) / 1000; // Kbytes
        if ($size > 1000) $this->size = round($size / 1000) . ' MB';
        else $this->size = round($size) . ' KB';
        $date = date('d/m/yy H:i:s', $info->getMTime());
        $this->modified =  str_replace("\\", "", $date);
        $this->creationDate = date('d/m/yy H:i:s', $info->getCTime());
    }

    // Properties
    public $name;
    public $path;
    public $parentPath;
    public $extension;
    public $creationDate;
    public $modified;
}
