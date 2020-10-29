<?php

    if (isset($_POST['userfile'])){
        //pre_r($_FILES);

        $phpFileUploadErrors = array(
            0 => 'There is no error, the file uploaded with success',
            1 => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
            2 => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
            3 => 'The uploaded file was only partially uploaded',
            4 => 'No file was uploaded',
            6 => 'Missing a temporary folder',
            7 => 'Failed to write file to disk',
            8 => 'A PHP extension stopped the file upload',
        );

        $ext_error = false;
        //a list of extensions that we allo to be uploaded
        $extensions = array('doc','docx','csv','jpg','png','txt','ppt','odt','pdf','zip','rar','exe','svg','mp3','mp4');
        $file_ext = explode('.',$_POST["userfile"]["name"]);
        $file_ext = end($file_ext);
        //pre_r($file_ext);

        if(!in_array($file_ext, $extensions)){
            $ext_error = true;
        }

        //if the error of the upload is not equal to 0
        if($_POST["userfile"]["error"]){
            ?> <div class="alert alert-danger=> <?php echo $phpFileUploadErrors[$_POST["userfile"]["error"]]; ?>
            </div> <?php
        }elseif($ext_error){
            ?> <div class="alert alert-danger"> <?php echo "Invalid file extension! ". "Please upload files with these extensions only: (";
            foreach($extensions as $key => $extensions){
                echo ".".$extensions." ";
            }
            echo ")";
            ?> </div> <?php
        }
        else{
            ?>
            <div class="alert alert-success"> <?php
            $destination = '../../root/'.$_POST["userfile"]["name"];//Abra que añadir el trozo del path que falta :)
            move_uploaded_file(($_POST['userfile']['tmp_name']), $destination);
            echo "Succes! The file has been uploaded";
            ?>
            </div>
            <?php
        }
    }
    /*
    header('Location:',"../../index.html");
    exit();*/

    function pre_r($array){
        echo '<pre>';
        print_r($array);
        echo '</pre>';
    }

    /*
    <form action="" method="POST" enctype="multipart/form-data">
    <input type="file" name="userfile" />
    <input type="submit" value="Upload" />
    </form>
    */
?>