<?php

include_once("adb.php");

class coordinates extends{

  //Constructor
  function coordinates(){
  }

  //Splits text line
  function splitLine($dataLine){
    $textArray = explode(",",$datapoints);
    addCoordinate($textArray[0],$textArray[1],$textArray[2]);
  }

  //Inserts data into database
  function addCoordinate($grade, $longitude,$latitude){
    $strQuery="insert into DataPoints set
						GRADE='$grade',
						LONGITUDE='$longitude',
						LATITUDE=$latitude";
		return $this->query($strQuery);
  }


//Reads entire textfile
  function read($dataFile){
    $tempFile = fopen($dataFile, "r");
    if ($tempFile) {
      while (($line = fgets($tempFile)) !== false) {
        // process the line read.
        splitLine($line);
      }
      fclose($handle);

      } else {
        // error opening the file.
      }
  }

}

?>
