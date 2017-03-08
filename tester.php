<?php

function splitLine($dataLine){
  $textArray = explode(",",$dataLine);
  return $textArray;
}

$dataFile = "features.txt";
$tempFile = fopen($dataFile, "r");

if ($tempFile) {
  while(($line = fgets($tempFile)) !== false){

    $currentPoint=ftell($tempFile);
    $textArray = splitLine($line);

    $nxtLine =fgets($tempFile);
    $nxtArray=splitLine($nxtLine);
    echo " Longitude: ";
    echo $textArray[1];
    echo " Latitude: ";
    echo $textArray[2];
    echo " Grade: ";
    echo $textArray[0];


    if($nxtLine != false){
      echo " nxtLongitude: ";
      echo $nxtArray[1];
      echo " nxtLatitude: ";
      echo $nxtArray[2];
    }

    fseek($tempFile,$currentPoint,SEEK_SET);
    echo " Switch line: ";
    echo $currentPoint;
    echo "<br />";

  }
  fclose($tempFile);

} else {
  // error opening the file.
}



?>
