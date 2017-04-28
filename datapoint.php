<?php
/**
* @author:Kwabena Boohene
* @date:01/04/2017
* Object that holds all attributes of datapoints
*/
class datapoints {

	var $long;
	var $lat;
	var $grade;
	var $pathPoint;

	/*
  * @params $longitude, $latitude, $roadGrade, $path
  * @return True or False after insert into database
  *  Constructor of the class
  */
	function datapoints($longitude,$latitude,$roadGrade,$path){
		$this->long=$longitude;
		$this->lat=$latitude;
		$this->grade=$roadGrade;
		$this->pathPoint=$path;
	}

	/*
  * @params nothing
  * @return latitude of data point
  * Gets the latitude of the data point
  */
	function getLat(){
		return $this->lat;
	}

	/*
  * @params nothing
  * @return longitude of data point
  * Gets the longitude of the data point
  */
	function getLng(){
		return $this->long;
	}

	/*
  * @params nothing
  * @return the grade of the data point
  * Gets the grade of the data point
  */
	function getGrade(){
		return $this->grade;
	}

	/*
  * @params nothing
  * @return the path of the data point
  * Obtains the path of the data point
  */
	function getPath(){
		return $this->pathPoint;
	}
}
?>
