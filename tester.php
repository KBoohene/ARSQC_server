<?php
$GPSvalues=array();
$count=0;
$exists=false;

$lat=0.1;
$lng=0.2;


$GPSvalues[]=array("lat" => 0.1, "lng" => 0.2);
$count++;

$GPSvalues[]=array("lat" => 0.3, "lng" => 0.4);
$count++;

$GPSvalues[]=array("lat" => 0.5, "lng" => 0.6);
$count++;

$GPSvalues[]=array("lat" => 0.7, "lng" => 0.8);
$count++;

for($i=0;$i<$count;$i++){
	if($GPSvalues[$i]['lat']==$lat){

		if($GPSvalues[$i]['lng']==$lng){
			$exists=true;
		}

	}
	//echo $GPSvalues[$i]['lat'];
}



if($exists==false){

	$GPSvalues[]=array("lat" => $lat, "lng" => $lng);
}

print_r($GPSvalues);
