<?php

include_once("coordinates.php");
$obj = new coordinates();

$fileDir ="coordinates/";

$uploadfile = $fileDir . basename($_FILES['fileToUpload']['name']);
if (move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $uploadfile)) {
    echo "Successful";
    $obj->read($uploadfile);

    //Deletes file from directory
    //$unlink($uploadfile);
} else {
    echo "Possible file upload failed";
}

?>
