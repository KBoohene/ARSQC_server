<?php
/**
* @author:Kwabena Boohene
* @date:18/01/2017
* Moves classified road data into sql database
*/

include_once("coordinates.php");
$obj = new coordinates();

//Directory of stored coordinates on the server
$fileDir ="coordinates/";
$obj->read("coordinates/d90cea8ead6d5ed9_samsung_GT-I9500_22 Mar 2017 00_20_36.txt")
/*
$uploadfile = $fileDir . basename($_FILES['fileToUpload']['name']);

//Moves file from temp folder into final directory
if (move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $uploadfile)) {
    echo "Successful";

    //Read file data into database
    $obj->read($uploadfile);

    //Deletes file from directory
    //$unlink($uploadfile);
} else {
    echo "Possible file upload failed";
}
*/
?>
