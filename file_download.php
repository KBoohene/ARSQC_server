<?php
/**
* @author:Kwabena Boohene
* @date:18/01/2017
* Moves classified road data into sql database
*/

include_once("coordinates.php");
$obj = new coordinates();

//Directory of stored coordinates on the server

//$uploadfile = $fileDir . basename($_FILES['fileToUpload']['name']);
$files =  glob("coordinates/*.txt");



foreach($files as $file) {
	$obj->readFile($file);
}
//$obj->insertData("route");

/*//Moves file from temp folder into final directory
if (move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $uploadfile)) {
    echo "Successful";

    //Read file data into database
    $obj->read($uploadfile);

    //Deletes file from directory
    //$unlink($uploadfile);
} else {
    echo "Possible file upload failed";
}*/
?>
