<?php
/*
* @author: Kwabena Boohene
* @date:03/03/2017
* Contains functions to move classified data to sql database
*/

include_once("adb.php");
include_once("datapoint.php");

class coordinates extends adb{

	//Array containing existent grades
	var $GradeList=array();

	//Counter object
	var $place=-1;

	//Array containing GPS values
	var $GPSvalues=array();

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

	function readFile($dataFile){

		$RouteID=sha1(basename($dataFile));
		$RouteID = substr($RouteID, 0, 5);

		$tempFile = fopen($dataFile, "r");
		if ($tempFile) {
			while(($line = fgets($tempFile)) !== false){

				//Gets current GPS points
				$currentPoint=ftell($tempFile);
				$textArray = $this->splitLine($line);

				$nxtLine =fgets($tempFile);
				$nxtArray=$this->splitLine($nxtLine);

				if($nxtLine != false){

					if($textArray[5]==1.0){
						$point = new datapoints($textArray[1],$textArray[2],$textArray[0],$textArray[5]);
						$this->shortenPath($point);
						array_push($this->GPSvalues,$point);
					}

					//Makes sure duplicate points arent added
					if($nxtArray[1]!=$textArray[1]){

						$startPoint = new datapoints($textArray[1],$textArray[2],$textArray[0],$textArray[5]);

						//Check if the grade point has already been stored
						$verify = $this->pointExists($startPoint);

						if($verify!=true){
							//Checks grade point
							$veriGrade=$this->shortenPath($startPoint);}
						else{$veriGrade=false;}

							if($veriGrade==true){
								array_push($this->GPSvalues,$startPoint);
							}
					}

				}
				else{

					$point = new datapoints($textArray[1],$textArray[2],$textArray[0],$textArray[5]);

					//Check if point has already been stored
					$verify = $this->pointExists($point);

					if($verify!=true){
						//Checks grade point
						$veriGrade=$this->shortenPath($point);}
					else{$veriGrade=false;}

					if($veriGrade==true){
						array_push($this->GPSvalues,$point);
					}

					if($textArray[5]==3.0){
						array_push($this->GPSvalues,$point);
						$this->insertData($RouteID);
					}

				}
				fseek($tempFile,$currentPoint,SEEK_SET);

			}
			fclose($tempFile);
		} else {
			// error opening the file.
		}

	}


	/*
  * @params data point
  * @return boolean value
	* Prevents sequential GPS points of the same grade
	* being added to the database
	*/
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

	/*
  * @params data point
  * @return boolean value
	* Check if point has been stored already
	*/
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

	/*
  * @params unique id for route
  * @return nothing
	* Adds data to database
	*/

	function insertData($RouteID){
		for($i=0;$i<sizeof($this->GPSvalues);$i++){

			if($i+1<sizeof($this->GPSvalues)){
				$this->addCoordinate($this->GPSvalues[$i]->getGrade(), $this->GPSvalues[$i]->getLng(),
														 $this->GPSvalues[$i]->getLat(),$RouteID,$this->GPSvalues[$i]->getPath(),
														 $this->GPSvalues[$i+1]->getLng(),$this->GPSvalues[$i+1]->getLat());
			}
			else{
				$this->addCoordinate($this->GPSvalues[$i]->getGrade(), $this->GPSvalues[$i]->getLng(),
														 $this->GPSvalues[$i]->getLat(),$RouteID,$this->GPSvalues[$i]->getPath());
			}

		}
		unset($this->GPSvalues);
		$this->GPSvalues=array();
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
