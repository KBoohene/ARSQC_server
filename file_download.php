<?php

/*if (is_uploaded_file($_FILES['fileToUpload']['tmp_name'])) {
   echo "File ". $_FILES['fileToUpload']['name'] ." uploaded successfully.\n";

} else {
   echo "file failed to upload";
}*/

/*$fileDir ="coordinates/";
$uploadfile = $fileDir . basename($_FILES['fileToUpload']['name']);
if (move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $uploadfile)) {
    echo "Successful";
} else {
    echo "Possible file upload failed";
}
*/
include_once("coordinates.php");
$obj = new coordinates();

$obj->read("features.txt");

?>
