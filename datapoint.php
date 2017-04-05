<?php
class datapoints {

	var $lng, $lat, $grade;

	function datapoints($longitude,$latitude,$roadGrade){
		$lng=$longitude;
		$lat=$latitude;
		$grade=$roadGrade;
	}

	function getLat(){
		return $lat;
	}

	function getLng(){
		return $lng;
	}

	function getGrade(){
		return $grade;
	}

}
?>
