
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
    function __construct($name, $path)
    {
        $this->name = $name;
        $this->path = $path;
    }

    // Properties

    public $name;
    public $path;

    // Methods
    public  function set_name($name)
    {
        $this->name = $name;
    }
    public function get_name()
    {
        return $this->name;
    }
    public function set_path($path)
    {
        $this->path = $path;
    }
    public function get_path()
    {
        return  $this->path;
    }
}

class folder
{

    function __construct($name, $path)
    {
        $this->name = $name;
        $this->path = $path;
    }

    // Properties

    public $name;
    public $path;
    public $content = array();

    // Methods
    public function set_name($name)
    {
        $this->name = $name;
    }
    public function get_name()
    {
        return $this->name;
    }
    public function set_path($path)
    {
        $this->path = $path;
    }
    public function add_content($file)
    {
        array_push($content, $file);
    }
}
