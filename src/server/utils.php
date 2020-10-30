
<?php

function relPath()
{
    return  '../../root/';
}

function absPath()
{
    return  RealPath(relPath());
}

function validExtensions()
{
    return   array('doc', 'docx', 'csv', 'jpg', 'png', 'txt', 'ppt', 'odt', 'pdf', 'zip', 'rar', 'exe', 'svg', 'mp3', 'mp4');
}

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

class File
{
    function __construct($info)

    {

        $path =  str_replace("\\", '', substr($info->getPath(), strlen(relPath())));
        // $arrayPath =   explode("/", $path);

        // array_pop($arrayPath);
        $name = $info->getBasename();

        $this->name = trim($name);
        if ((trim($path)))  $this->path = trim($path) . '/' . trim($name);
        else $this->path = trim($name);

        $this->parentPath = $path;

        // implode("/", $arrayPath);
        if ($info->isDir()) $this->extension = 'folder';
        else $this->extension = $info->getExtension();

        // if all, populate the rest of the fields
    }

    // Properties

    public $name;
    public $path;
    public $parentPath;
    public $extension;


    // Methods

    public function get_name()
    {
        return $this->name;
    }

    public function get_path()
    {
        return  $this->path;
    }

    public function get_extension()
    {
        return $this->extension;
    }
}
