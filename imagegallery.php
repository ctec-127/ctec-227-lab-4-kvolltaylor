<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Image Gallery</title>

    <!-- CSS -->
    <style>

    img {
        margin-bottom: -5px;
    }

    h1.title {
        text-align: center;
        padding: 15px 0 15px 0;
        font-size: 3em;
    }

    footer {
        text-align: center;
        margin: auto;
        padding-bottom: 15px;
    }

    #wrapper {
        margin: auto;
        max-width: 1200px;
    }

    #uploadBar {
        margin: auto;
        padding: 10px 0 10px 0;
        text-align: center;
        border-radius: 5px;
        border: 1px solid #000000;
        background-color: #eeeeee;

    }

    #chooseFile {
        border-radius: 5px;
        border: 1px solid #cccccc;
        background-color: #ffffff;
        width: 60%;
        margin-right: 5%;
    }

    #uploadFile {
        border-radius: 5px;
        border: 1px solid #cccccc;
        background-color: #11aa03;
        padding: 5px 10px 5px 10px;
        color: #ffffff;
    }

    .galleryDisplay {
        border: 1px solid #000000;
        border-radius: 10px;
        padding: 25px;
        margin: 25px 0 25px 0;
        background-color: #eeeeee;
        display: block;

    }

    .imageDisplay {
        border: 1px solid #000000;
        border-radius: 5px;
        padding: 25px;
        margin: 35px;
        background-color: #ffffff;
        display: inline-block;
        box-shadow: 5px 10px 18px #888888;
    }

    .imageFileWrapper {
        border: 1px solid #cccccc;
        border-radius: 5px;
        padding: 0;
        margin: 0;
    }

    .filenameDisplay {
        text-align: center;
        padding-bottom: 5px;
        background-color: #eeeeee; 
        border-radius: 5px 5px 0 0;   
        }

    .imageOptions {
        border-radius: 0 0 5px 5px;
        width: 250px;
        display: block;
        margin-top: 0px;
    }

    .del {
        color: #ffffff;
        background-color: #ff0000;
        text-align: center;
        display: inline-block;
        width: 30%;
        padding: 10px 0 10px 0;
        border-radius: 0 0 0 5px;
    }

    .full {
        color: #ffffff;
        background-color: #eaebff;
        text-align: center;
        display: inline-block;
        width: 70%;
        padding: 10px 0 10px 0;
        border-radius: 0 0 5px 0;
     }

     .success {
         background-color: #d9ffd6;
         text-align: center;
         max-width: 1178px;
         padding: 10px;
         border: 1px solid #11aa03;
         border-radius: 5px;
     }

     .doh {
         background-color: #fffddb;
         text-align: center;
         max-width: 1178px;
         padding: 10px;
         border: 1px solid #f7b009;
         border-radius: 5px;
     }

     .deleted {
         background-color: #fccfcf;
         text-align: center;
         max-width: 1178px;
         padding: 10px;
         border: 1px solid #ff0000;
         border-radius: 5px;
     }
    a:link.buttonLinkText, a:visited.buttonLinkText {
        color: #ffffff;
    }

    a:hover.buttonLinkText, a:active.buttonLinkText {
        color: #ffffb5;
     }

    a:link, a:visited, a:hover, a:active {
        text-decoration: none;
    }
    
    </style>

</head>
<body>

    <!-- PHP LOGIC FOR HANDLING UPLOADING IMAGE FILES -->
    <?php
    
        // array to tell user what went wrong in plain english
        $upload_errors = array(
                                UPLOAD_ERR_OK 				=> "No errors.",
                                UPLOAD_ERR_INI_SIZE  		=> "Larger than upload_max_filesize.",
                                UPLOAD_ERR_FORM_SIZE 		=> "Larger than form MAX_FILE_SIZE.",
                                UPLOAD_ERR_PARTIAL 			=> "Partial upload.",
                                UPLOAD_ERR_NO_FILE 			=> "No file.",
                                UPLOAD_ERR_NO_TMP_DIR 		=> "No temporary directory.",
                                UPLOAD_ERR_CANT_WRITE 		=> "Can't write to disk.",
                                UPLOAD_ERR_EXTENSION 		=> "File upload stopped by extension.");
                                
        // for uploading images
        $upload_dir = 'images';

        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $tmp_file = $_FILES['file_upload']['tmp_name'];
            $target_file = $_FILES['file_upload']['name'];
            // $upload_dir = 'images';
            
            if(move_uploaded_file($tmp_file, $upload_dir . "/" . $target_file)){
                $message = "<div class='success'>File uploaded successfully</div>";
            } else {
                $error = $_FILES['file_upload']['error'];
                $message = "<div class='doh'>WARNING: $upload_errors[$error]</div>";
            } // end if/else to upload and move from temp to specified directory
        } // end if for POST

        // LOGIC FOR DELETING FILES
        if($_SERVER['REQUEST_METHOD'] == 'GET'){
            if(isset($_GET['del'])){
                $image_file = $_GET['del'];
                unlink("images/$image_file");
                $message = "<div class='deleted'>File deleted successfully</div>";
            } // end if GET isset
        } // end if for GET

                                    
    
    ?>

    <div id="wrapper">

        <h1 class="title" id="title">Images Gallery</h1>

        <!-- BEGIN UPLOAD FORM -->
        <section id="uploadBar">
            <form method="post" enctype="multipart/form-data">
                <input type="hidden" name="MAX_FILE_SIZE" value="100000000">
                <!-- using a value of "image/*" wildcard forces acceptance of 
                    only image files, but of all types of image files -->
                <input type="file" name="file_upload" accept="image/*" id="chooseFile">
                <input type="submit" name="submit" value="Upload" id="uploadFile">
            </form>
        </section>
        <!-- END UPLOAD FORM -->

        <!-- Message display to give user feedback that upload worked or not-->
        <?php if(!empty($message)) {echo "<p>{$message}</p>";} ?>

        <!-- BEGIN DISPLAY OF IMAGES -->
        <section class="galleryDisplay">
            <div>
                <?php

                    if (is_dir($upload_dir)) {
                        $dir_array = scandir($upload_dir);
                        foreach ($dir_array as $image_file){
                            if(strpos($image_file,'.') > 0){
                                echo "<div class='imageDisplay'>";
                                echo "<div class='imageFileWrapper'>";
                                echo "<div class='filenameDisplay'><b>Filename:</b><br> {$image_file}<br/></div>";
                                echo "<img src='images/$image_file' alt='$image_file' width='250'><br>";
                                echo "<div class='imageOptions'>";
                                echo "<div class='del'>";
                                echo "<a href='?del=$image_file' title='Delete $image_file' class='buttonLinkText'>Delete</a>";
                                echo "</div>";
                                echo "<div class='full'>";
                                echo "<a href='images/$image_file' title='View Full Size $image_file'>View Image Full Size</a>";
                                echo "</div>";
                                echo "</div>";
                                echo "</div>";
                                echo "</div>";
                            } // end if
                        } // end for each
                    } // end if
                
                ?>
            </div>
        </section>
    <!-- END DISPLAY OF IMAGES -->
        <footer>
        <a href="#title">Back to Top of Page</a>
        </footer>
    </div> <!-- end wrapper div -->

</body>
</html>