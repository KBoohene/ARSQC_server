<?php
var $GPSvalues=array();


function pointExists($datapoint){
	for($i=0;i<$this->$GPSvalues.length;$i++){
		if($datapoint->getLng()==$this->$GPSvalues[$i]->getLng()){
			$validPoint=true;
			break;
		}
		else{
			$validPoint=false;
		}
	}

	return $validPoint;
}


?>
