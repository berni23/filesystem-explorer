<?php

// create file


session_start();


// check if file exists
// check if  the extension is right  -> json file with right extensions ( validation)


if (isset($_GET["makeFile"])) {

    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['path']) && isset($data['filename'])) {

        $path = '../../root/' . $data['path'];

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
