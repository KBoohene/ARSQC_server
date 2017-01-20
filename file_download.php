<?php
// if text data was posted
if($_POST){
print_r($_POST);
}


/*if (is_uploaded_file($_FILES['fileToUpload']['tmp_name'])) {
   echo "File ". $_FILES['fileToUpload']['name'] ." uploaded successfully.\n";

} else {
   echo "file failed to upload";
}*/

$fileDir ="coordinates/";
$uploadfile = $fileDir . basename($_FILES['fileToUpload']['name']);
if (move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $uploadfile)) {
    echo "Success";
} else {
    echo "Possible file upload failed";
}

?>
