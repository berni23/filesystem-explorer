<?php

// create file


session_start();


// check if file exists
// check if  the extension is right  -> json file with right extensions ( validation)


if (isset($_GET["makefile"])) {


    $data = json_decode(file_get_contents('php://input'), true);


    if (isset($data['path']) && isset($data['filename'])) {

        $myfile = fopen($data['filename'], "w");

        fclose($myfile);

        echo 'success';
    }
}
