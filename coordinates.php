<?php
/*
* @author: Kwabena Boohene
* @date:03/03/2017
* Contains functions to move classified data to sql database
*/

include_once("adb.php");

class coordinates extends adb{

  //Constructor
  function coordinates(){
  }

  /*
  * @params $dataline- Line in a text file
  * Splits lines in a text file into array format
  * @return True or False after insert into database
  */
  function splitLine($dataLine){
    $textArray = explode(",",$dataLine);
    return $textArray;
  }


  /*
  * @params $grade, $longitude, $latitude
  * @return query result, True or False
  * Inserts data into database
  */
  function addCoordinate($grade, $longitude,$latitude,$NxtLong,$NxtLat,$RouteID){
    $strQuery="insert into DataPoints set
						GRADE='$grade',
						LONGITUDE='$longitude',
						LATITUDE='$latitude',
                        NXTLONGITUDE='$NxtLong',
                        NXTLATITUDE='$NxtLat',
                        ROUTEID='$RouteID'";
		return $this->query($strQuery);
  }


  /*
  * @params road quality file
  * @return nothing
  * Reads the lines of a text file
  */
  function read($dataFile){
    $tempFile = fopen($dataFile, "r");
    if ($tempFile) {
      while(($line = fgets($tempFile)) !== false){

        $currentPoint=ftell($tempFile);
        $textArray = $this->splitLine($line);

        $nxtLine =fgets($tempFile);
        $nxtArray=$this->splitLine($nxtLine);
        /*echo " Longitude: ";
        echo $textArray[1];
        echo " Latitude: ";
        echo $textArray[2];
        echo " Grade: ";
        echo $textArray[0];*/


        if($nxtLine != false){
          /*echo " nxtLongitude: ";
          echo $nxtArray[1];
          echo " nxtLatitude: ";
          echo $nxtArray[2];*/
          $this->addCoordinate($textArray[0], $textArray[1],$textArray[2],$nxtArray[1],$nxtArray[2],$RouteID);
        }
        else{
          $this->addCoordinate($textArray[0], $textArray[1],$textArray[2],null,null,$RouteID);
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
  }

  /*
  * @params nothing
  * @return all the gps data stored in the database
  */
  function fetchAllData(){
    $strQuery="Select grade, Longitude, Latitude, nxtLongitude, nxtLatitude, routeId, position from datapoints";
    return $this->query($strQuery);
  }
}

?>
