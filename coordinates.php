<?php
/*
* @author: Kwabena Boohene
* @date:03/03/2017
* Contains functions to move classified data to sql database
*/

include_once("adb.php");
include_once("datapoint.php");

class coordinates extends adb{

	var $GradeList=array();
	var $place=-1;
	var $GPSvalues=array();


  //Constructor
  function coordinates(){

  }

	//Checks if GPS point has already been added
	//already to the database
	/*function checkPoints($lng,$lat){

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

		if($this->exists==0){
			$this->GPSvalues[]=array("lat" => $lat, "lng" => $lng);

			$this->counter++;
			return false;
		}
		else{
			return true;
		}
	}*/


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

		/*function read($dataFile){

		$firstOccur=0;

		$RouteID=sha1(basename($dataFile));
		$RouteID = substr($RouteID, 0, 5);

		$this->checkLast();
		$check=$this->fetch();


		//Upates the last stored point in the database
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

				//Gets current GPS points
        $currentPoint=ftell($tempFile);
        $textArray = $this->splitLine($line);


				//Bets the following GPS points
        $nxtLine =fgets($tempFile);
        $nxtArray=$this->splitLine($nxtLine);

        if($nxtLine != false){
					//$this->addCoordinate($textArray[0], //$textArray[1],$textArray[2],$RouteID,intVal($textArray[5]),$nxtArray[1],$nxtArray[2]);

					if($nxtArray[1]!=$textArray[1]){
						//Update the next of the first point in the database
						if($firstOccur==1){
							$this->updateNxt($nxtArray[2],$nxtArray[1],$check['pointId']);
							$firstOccur++;
						}

						//Checks if the GPS point already exists
						$Exist=$this->checkPoints($textArray[1],$textArray[2]);
						//Checks grade point
						$veriGrade=$this->shortenPath($textArray[0]);
						if($Exist==false){
							if($veriGrade==true){$this->addCoordinate($textArray[0], $textArray[1],$textArray[2],$RouteID,2,$nxtArray[1],$nxtArray[2]);}
						}
					}

        }
        else{

					//Checks if the GPS point exists
					$Exist=$this->checkPoints($textArray[1],$textArray[2]);
					//Checks grade point
					$veriGrade=$this->shortenPath($textArray[0]);
					if($Exist==false){
						if($veriGrade==true){$this->addCoordinate($textArray[0],$textArray[1],$textArray[2],$RouteID,2);}

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

	*/



	//Reads file and does appropriate checks
	function readFile($dataFile){

		$RouteID=sha1(basename($dataFile));
		$RouteID = substr($RouteID, 0, 5);

		$this->checkLast();
		$check=$this->fetch();


		/*if($check['position']==2){
			$checkFile = fopen($dataFile,"r");
			$checkLine = fgets($checkFile);

			$fLine = $this->splitLine($checkLine);
			echo"<br/>";

			print_r($fLine);

			echo"<br/>";
			$newPoint = new datapoints($fLine[1],$fLine[2],$fline[0]);
			$Exist=$this->pointExists($newPoint);

			if($Exist==false){
				$this->updateNxt($fLine[2],$fLine[1],$check['pointId']);
				array_push($this->GPSvalues,$newPoint);
			}
			else{
			}
			fclose($checkFile);
		}*/


		$tempFile = fopen($dataFile, "r");
		if ($tempFile) {
			while(($line = fgets($tempFile)) !== false){

				//Gets current GPS points
				$currentPoint=ftell($tempFile);
				$textArray = $this->splitLine($line);

				$nxtLine =fgets($tempFile);
				$nxtArray=$this->splitLine($nxtLine);

				if($nxtLine != false){

					//Makes sure duplicate points arent added
					if($nxtArray[1]!=$textArray[1]){

						$startPoint = new datapoints($textArray[1],$textArray[2],$textArray[0]);

						//Check if point has already been stored
						$verify = $this->pointExists($startPoint);

						if($verify!=true){
							//Checks grade point
							$veriGrade=$this->shortenPath($startPoint);}
						else{$veriGrade=false;}

							if($veriGrade==true){
								array_push($this->GPSvalues,$startPoint);

								//$this->counter++;
							}

					}

				}
				else{

					$point = new datapoints($textArray[1],$textArray[2],$textArray[0]);

					//Check if point has already been stored
					$verify = $this->pointExists($point);

					if($verify!=true){
						//Checks grade point
						$veriGrade=$this->shortenPath($point);}
					else{$veriGrade=false;}

					if($veriGrade==true){
						array_push($this->GPSvalues,$point);

					}
				}
				fseek($tempFile,$currentPoint,SEEK_SET);

			}
			fclose($tempFile);
		} else {
			// error opening the file.
		}


		//Add data to database
		print_r($this->GPSvalues);

	}


	//Prevents sequential GPS points of the same grade
	//being added to the database
	function shortenPath($point){

		if($this->place==-1){
			array_push($this->GradeList,$point->getGrade());
			$this->place++;
			return true;
		}
		else{
			if($point->getGrade()!=$this->GradeList[$this->place]){
				array_push($this->GradeList,$point->getGrade());
				$this->place++;
				return true;
			}
			else{
				return false;
			}

		}
	}

	//Check if point has been stored alread
	function pointExists($datapoint){
	   $validPoint=false;


		for($i=0;$i<sizeof($this->GPSvalues);$i++){
			if($datapoint->getLng()==$this->GPSvalues[$i]->getLng()){
				$validPoint=true;
				break;
			}
			else{
				$validPoint=false;
			}
		}

		if(sizeof($this->GPSvalues)==0)
			$validPoint=false;

		return $validPoint;
	}


	//Adds data to database
	function insertData($RouteID){
		for($i=0;$i<sizeof($this->GPSvalues);$i++){

			if($i+1<sizeof($this->GPSvalues)){
				$this->addCoordinate($this->GPSvalues[$i]->getGrade(), $this->GPSvalues[$i]->getLng(),
														 $this->GPSvalues[$i]->getLat(),$RouteID,2,$this->GPSvalues[$i+1]->getLng(),
														 $this->GPSvalues[$i+1]->getLat());
			}
			else{
				$this->addCoordinate($this->GPSvalues[$i]->getGrade(), $this->GPSvalues[$i]->getLng(),
														 $this->GPSvalues[$i]->getLat(),$RouteID,2);
			}

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
