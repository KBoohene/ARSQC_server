<?php
/*
* @author: Kwabena Boohene
* @date:03/03/2017
* Contains functions to move classified data to sql database
*/

include_once("adb.php");

class coordinates extends adb{
	var $counter=0;
	var $exists=0;
	var $GPSvalues=array();


  //Constructor
  function coordinates(){

  }

	//Checks if GPS point has already been added
	function checkPoints($lng,$lat){

		/*echo "GPS coordinates are: ";
		print_r($this->GPSvalues);
		echo "<br /> GPS points: ";
		echo $lat;
		echo ",";
		echo $lng;
		echo "<br />";*/

		for($i=0;$i<$this->counter;$i++){

			if($this->GPSvalues[$i]['lat']==$lat){
				if($this->GPSvalues[$i]['lng']==$lng){
					$this->exists=1;
				}
				else{
					$this->exists=0;
				}
			}
			else{
				$this->exists=0;
			}
		}


		/*echo "exists: "+$this->exists;
		echo "<br />";*/



		if($this->exists==0){
			$this->GPSvalues[]=array("lat" => $lat, "lng" => $lng);

			$this->counter++;
			return false;
		}
		else{
			return true;
		}


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

		$firstOccur=0;

		$RouteID=sha1(basename($dataFile));
		$RouteID = substr($RouteID, 0, 5);

		$this->checkLast();
		$check=$this->fetch();


		if($check['position']==2){
			$checkFile = fopen($dataFile,"r");
			$checkLine = fgets($checkFile);
			$fLine = $this->splitLine($checkLine);

			$Exist=$this->checkPoints($fLine[1],$fLine[2]);
			if($Exist==false){
				$this->updateNxt($fLine[2],$fLine[1],$check['pointId']);
				$firstOccur=0;
			}
			else{
				$firstOccur=1;
			}
			fclose($checkFile);
		}


		$tempFile = fopen($dataFile, "r");

    if ($tempFile) {
      while(($line = fgets($tempFile)) !== false){

        $currentPoint=ftell($tempFile);
        $textArray = $this->splitLine($line);

        $nxtLine =fgets($tempFile);
        $nxtArray=$this->splitLine($nxtLine);

        if($nxtLine != false){
					//$this->addCoordinate($textArray[0], //$textArray[1],$textArray[2],$RouteID,intVal($textArray[5]),$nxtArray[1],$nxtArray[2]);

					if($nxtArray[1]!=$textArray[1]){
						if($firstOccur==1){
							$this->updateNxt($nxtArray[2],$nxtArray[1],$check['pointId']);
							$firstOccur++;
						}

						$Exist=$this->checkPoints($textArray[1],$textArray[2]);

						if($Exist==false){
							$this->addCoordinate($textArray[0], $textArray[1],$textArray[2],$RouteID,2,$nxtArray[1],$nxtArray[2]);
						}
					}

        }
        else{
					$Exist=$this->checkPoints($textArray[1],$textArray[2]);
					if($Exist==false){
						$this->addCoordinate($textArray[0],$textArray[1],$textArray[2],$RouteID,2);
					}
						//$this->addCoordinate($textArray[0],$textArray[1],$textArray[2],$RouteID,intVal($textArray[5]));

				}
        fseek($tempFile,$currentPoint,SEEK_SET);

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

	//Checks the last entry into the database
	function checkLast(){
		$strQuery="SELECT pointId, position FROM datapoints ORDER BY position DESC LIMIT 1";
		return $this->query($strQuery);
	}

	//Updates null entries in the database
	function updateNxt($nxtLat,$nxtLng,$pointId){
		$strQuery="UPDATE datapoints SET nxtLatitude = '$nxtLat', nxtLongitude = '$nxtLng' WHERE pointId='$pointId'";
		return $this->query($strQuery);
	}




}

?>
