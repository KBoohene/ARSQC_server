<?php
class datapoints {

	var $long;
	var $lat;
	var $grade;
	var $pathPoint;

	function datapoints($longitude,$latitude,$roadGrade,$path){
		$this->long=$longitude;
		$this->lat=$latitude;
		$this->grade=$roadGrade;
		$this->pathPoint=$path;
	}

	function getLat(){
		return $this->lat;
	}

	function getLng(){
		return $this->long;
	}

	function getGrade(){
		return $this->grade;
	}

	function getPath(){
		return $this->pathPoint;
	}
}
?>
