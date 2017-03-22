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
	function addCoordinate($grade, $longitude,$latitude,$RouteID,$status,$NxtLong=false,$NxtLat=false){
		if($NxtLong==false){
			$strQuery="INSERT into DataPoints SET
						GRADE='$grade',
						LONGITUDE='$longitude',
						LATITUDE='$latitude',
						ROUTEID='$RouteID',
						POSITION='$status'";
		}
		else{
			$strQuery="INSERT into DataPoints SET
						GRADE='$grade',
						LONGITUDE='$longitude',
						LATITUDE='$latitude',
						NXTLONGITUDE='$NxtLong',
						NXTLATITUDE='$NxtLat',
						ROUTEID='$RouteID',
						POSITION='$status'";
		}

		return $this->query($strQuery);
  }


  /*
  * @params road quality file
  * @return nothing
  * Reads the lines of a text file
  */
  function read($dataFile){
    $tempFile = fopen($dataFile, "r");
		$RouteID=sha1(basename($dataFile));
		$RouteID = substr($RouteID, 0, 5);


    if ($tempFile) {
      while(($line = fgets($tempFile)) !== false){

        $currentPoint=ftell($tempFile);
        $textArray = $this->splitLine($line);

        $nxtLine =fgets($tempFile);
        $nxtArray=$this->splitLine($nxtLine);


        if($nxtLine != false){
					$this->addCoordinate($textArray[0], $textArray[1],$textArray[2],$RouteID,intVal($textArray[5]),$nxtArray[1],$nxtArray[2]);
        }
        else{
          $this->addCoordinate($textArray[0],$textArray[1],$textArray[2],$RouteID,2);
        }

        fseek($tempFile,$currentPoint,SEEK_SET);
        //echo " Switch line: ";
        //echo $currentPoint;
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
