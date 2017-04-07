<?php
class datapoints {

	var $long;
	var $lat;
	var $grade;

	function datapoints($longitude,$latitude,$roadGrade){
		$this->long=$longitude;
		$this->lat=$latitude;
		$this->grade=$roadGrade;
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

}
?>
