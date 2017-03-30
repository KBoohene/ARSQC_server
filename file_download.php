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
//$obj->read("coordinates/d90cea8ead6d5ed9_samsung_GT-I9500_7 Mar 2017 02_44_57.txt");

//$files = scandir("coordinates/");
$files =  glob("coordinates/*.txt");



foreach($files as $file) {
	$obj->read($file);
}
//print_r($obj->GPSvalues);
//echo "done loading";

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
