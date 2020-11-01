

<?php
// relative path of the root folder

function relPath()
{
    return  '../../root/';
}

// absolute path of the root folder
function absPath()
{
    return  RealPath(relPath());
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




function getFolderContent($path, $all = false)
{
    $contents = scandir($path);
    $arrayFiles = [];

    foreach ($contents as $filename) {
        if ($filename[0] != '.') {
            $file = new File(new SplFileInfo($path . $filename), $all);
            array_push($arrayFiles, $file);
        }
    }
    return $arrayFiles;
}

/*

class file used to pass information of a file from php to javascript.
The constructor expects a class of type SplFileInfo. as optional parameter, there is a boolean,
the class retrieves all the information if it is true, else just a subset of it necessary for populating
the initial fields.

*/
class File
{
    function __construct($info, bool $all = false)
    {
        $path =  str_replace("\\", '', substr($info->getPath(), strlen(relPath())));
        $name = $info->getBasename();
        $this->name = trim($name);
        if ((trim($path)))  $this->path = trim($path) . '/' . trim($name);
        else $this->path = trim($name);
        $this->parentPath = $path;
        if ($info->isDir()) $this->extension = 'folder';
        else $this->extension = $info->getExtension();

        if ($all) {
            $this->size = $info->getSize();
            $this->modified = $info->getMTime();
            $this->creationDate = $info->getCTime();
        };
    }

    // Properties

    public $name;
    public $path;
    public $parentPath;
    public $extension;
}
