<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- libraries-->

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"
        integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"
        integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous">
    </script>

    <title>Upload</title>
</head>
<body>
<?php
    if (isset($_FILES['userfile'])){
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
        $file_ext = explode('.',$_FILES["userfile"]["name"]);
        $file_ext = end($file_ext);
        //pre_r($file_ext);

        if(!in_array($file_ext, $extensions)){
            $ext_error = true;
        }

        //if the error of the upload is not equal to 0
        if($_FILES["userfile"]["error"]){
            ?> <div class="alert alert-danger=> <?php echo $phpFileUploadErrors[$_FILES["userfile"]["error"]]; ?>
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
            $destination = '../../root/'.$_FILES["userfile"]["name"];//Abra que añadir el trozo del path que falta :)
            move_uploaded_file(($_FILES['userfile']['tmp_name']), $destination);
            echo "Succes! The file has been uploaded";
            ?>
            </div>
            <?php
        }
    }

    function pre_r($array){
        echo '<pre>';
        print_r($array);
        echo '</pre>';
    }

?>
    <form action="" method="POST" enctype="multipart/form-data">
    <input type="file" name="userfile" />
    <input type="submit" value="Upload" />
    </form>
</body>
</html>