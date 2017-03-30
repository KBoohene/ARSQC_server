<!doctype html>
<html>
  <!--
  - @author:Kwabena Boohene
  - @date:02/2017
  - Displays road quality data
  -->
  <header>
  	<title>SurfaceMap</title>
  </header>
  <head>

		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta http-equiv="x-ua-compatible" content="ie=edge">

		<!--Styling for web page-->
		<!-- Font Awesome -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.0/css/font-awesome.min.css">

		<!-- Bootstrap core CSS -->
		<link href="res/css/bootstrap.min.css" rel="stylesheet">

		<!-- Material Design Bootstrap -->
		<link href="res/css/mdb.min.css" rel="stylesheet">

		<!-- Your custom styles (optional) -->
		<link href="res/css/style.css" rel="stylesheet">

		<!-- JQuery -->
		<script type="text/javascript" src="res/js/jquery-2.2.3.min.js"></script>

		<!-- Bootstrap tooltips -->
		<script type="text/javascript" src="res/js/tether.min.js"></script>

		<!-- Bootstrap core JavaScript -->
		<script type="text/javascript" src="res/js/bootstrap.min.js"></script>



  <!--/Styling for web page-->

    <!--Scripts needed for geotree datastructure-->
    <script src="geo-tree/z-curve.js" type="text/javascript"></script>
    <script src="geo-tree/red-black.js" type="text/javascript"></script>
    <script src="geo-tree/geo-tree.js" type="text/javascript"></script>

    <!-- Google Maps JS API -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDVy-fqmHDhDqCo3N9MQFknESI5mbzOepU"></script>
    <!-- GMaps Library -->
    <script src="gmaps.js"></script>
    <script src="calLatLng.js"></script>
    <script src="calDistance.js"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
  </head>

  <body>
		<!-- MDB core JavaScript -->
		<script type="text/javascript" src="res/js/mdb.min.js"></script>
    <!--Google maps display-->
		<div class="row">
			<div class="col-md-9">
				<div id="map" style="width:100%;height:600px"></div>
			</div>
			<div class="col-md-3">
				<!--Takes in user search data-->
				<div class="md-form" id="draw-route" name="draw-route" action="" method="get" style="padding-top:40px;">

					<input type="text" id="to" name="to" required="required" placeholder="Destination address" size="10" />

					<button type="button" class="btn btn-primary" id="submit">Submit</button>

					<div>
						<a id="pos-link" href="#">Get my position</a>
					</div>

				</div>


				<div id="GPS" style="visibility:hidden">
					<h5>Your Location</h5>
					Latitude:<p id="lat">Lat</p>
					Longitude:<p id="long">Lng</p>
				</div>
			</div>

		</div>




    <!--This section loads data from the database into the geotree datastructure-->
    <?php
    include_once('coordinates.php');
    /*
    0 takes Road Grade
    1 takes GPS longitude
    2 takes GPS latitude
    3 takes Next Longitude
    4 takes Next Latitude
    5 takes the routeId
    6 takes the position
    */

    $GPSdata = array(array());
    $obj = new coordinates();
    $obj->fetchAllData();
    $count=0;

    //Stores data from database in a 2d array
    while($coordData=$obj->fetch()){
      $GPSdata[$count][0]=$coordData['grade'];
      $GPSdata[$count][1]=$coordData['Longitude'];
      $GPSdata[$count][2]=$coordData['Latitude'];
      $GPSdata[$count][3]=$coordData['nxtLongitude'];
      $GPSdata[$count][4]=$coordData['nxtLatitude'];
      $GPSdata[$count][5]=$coordData['routeId'];
      $GPSdata[$count][6]=$coordData['position'];
      $count++;
    }

    ?>
    <script>
      var set = new GeoTree();
      var binaryTreeGPS=[], GradeData=[], tempArray=[];

      //Stores php 2d array in javascript
      var GPSpoints = <?php echo json_encode($GPSdata); ?>;

      //Dumps data into geotree datastructure
      for (var i=0;i<GPSpoints.length;i++){
        GradeData={NxtLng:parseFloat(GPSpoints[i][3]),NxtLat:parseFloat(GPSpoints[i][4]),
                   grade:GPSpoints[i][0], position:parseInt(GPSpoints[i][6]), routeId:GPSpoints[i][5]};
        binaryTreeGPS={lat:parseFloat(GPSpoints[i][2]),lng:parseFloat(GPSpoints[i][1]),data: GradeData};
        //console.log(binaryTreeGPS);
        set.insert(binaryTreeGPS);
      }

    </script>

    <!--This section deals with all aspects of the map interface-->
    <script>
        /* Map Object */
        var mapObj = new GMaps({
          zoom:7,
          el: '#map',
          lat: 6.68848,
          lng: -1.62443
        });


      $(document).ready(function(){

        var sourceLng, sourcelat;
        var destiLng, destiLat;
        var markerCounter;
				var SourceMarker, destination;
				var polyline=null;

        //Finds the user's geolocation
        $("#pos-link").click(function(event) {

            GMaps.geolocate({
              success: function(position) {
                mapObj.setCenter(position.coords.latitude, position.coords.longitude);
                sourceLng=position.coords.longitude;
                sourcelat=position.coords.latitude;

								displayGPS();

                SourceMarker =mapObj.createMarker({
                   lat: position.coords.latitude,
                   lng: position.coords.longitude,
                   title: 'GPS Location',
								 	 label:"User"
                    });

                mapObj.addMarker(SourceMarker);
                mapObj.setZoom(12);

              },
              error: function(error) {
                alert('Geolocation failed: '+error.message);
              },
              not_supported: function() {
                alert("Your browser does not support geolocation");
              }
          });
        });

        //Submits users search request
        $("#submit").click(function() {
					var locationFind;
            GMaps.geocode({
              address: $('#to').val(),
              callback: function(results, status) {
                if (status == 'OK') {
                  var latlng = results[0].geometry.location;

                    destiLat = latlng.lat();
                    destiLng =latlng.lng();



                    locationFind=drawBoundary(latlng.lat(),latlng.lng());
                    //console.log(destiLat);

									if(locationFind==true){

										mapObj.removeMarkers();
										mapObj.setCenter(latlng.lat(), latlng.lng());
										destination= mapObj.createMarker({
											lat: latlng.lat(),
											lng: latlng.lng(),
											title: 'Destination',
											label:'Destination',
											icon: {path: google.maps.SymbolPath.BACKWARD_CLOSED_ARROW, scale: 3}

										});
										mapObj.addMarker(SourceMarker);
										mapObj.addMarker(destination);
										mapObj.setZoom(12);
									}
									else{
										if(polyline!=null){
											destination.setMap(null);
											polyline.setMap(null);
										}

									}

                }
              }
            });

          });

      /*Draws rectangular boundary around the user's
      * GPS location and the destination's GPS
      */
      function drawBoundary(destiLat,destiLng){
          var sourceVertical, sourceHorizontal=[];
          var destiVertical, destiHorizontal=[];
          var NW = new Array(2);
          var NE = new Array(2);
          var SW = new Array(2);
          var SE = new Array(2);
          var distance;
          var boundaries=[];
          mapObj.removePolygons();


          //Calculates the distance needed to draw the bounding box
          distance = distVincenty(sourcelat,sourceLng,destiLat,destiLng);
          distance= (distance*0.3);

          if((sourcelat>destiLat)&&(sourceLng<destiLng)){
            sourceHorizontal = destVincenty(sourcelat,sourceLng,270,distance);
            sourceVertical = destVincenty(sourceHorizontal[0],sourceHorizontal[1],0,distance);

            destiHorizontal = destVincenty(destiLat,destiLng,90,distance);
            destiVertical = destVincenty(destiHorizontal[0],destiHorizontal[1],180,distance);

            NW = sourceVertical;
            SE = destiVertical;
            NE[0] = sourceVertical[0];
            NE[1] = destiVertical[1];
            SW[0] = destiVertical[0];
            SW[1] = sourceVertical[1];


          }
          else if((sourcelat<destiLat)&&(sourceLng<destiLng)){
            sourceHorizontal = destVincenty(sourcelat,sourceLng,270,distance);
            sourceVertical = destVincenty(sourceHorizontal[0],sourceHorizontal[1],180,distance);

            destiHorizontal = destVincenty(destiLat,destiLng,90,distance);
            destiVertical = destVincenty(destiHorizontal[0],destiHorizontal[1],0,distance);

            SW = sourceVertical;
            NE = destiVertical;
            NW[0] = destiVertical[0];
            NW[1] = sourceVertical[1];
            SE[0] = sourceVertical[0];
            SE[1] = destiVertical[1];

          }
          else if((sourcelat<destiLat)&&(sourceLng>destiLng)){
            sourceHorizontal = destVincenty(sourcelat,sourceLng,90,distance);
            sourceVertical = destVincenty(sourceHorizontal[0],sourceHorizontal[1],180,distance);

            destiHorizontal = destVincenty(destiLat,destiLng,270,distance);
            destiVertical = destVincenty(destiHorizontal[0],destiHorizontal[1],0,distance);

            SE = sourceVertical;
            NW = destiVertical;
            NE[0] = destiVertical[0];
            NE[1] = sourceVertical[1];
            SW[0] = sourceVertical[0];
            SW[1] = destiVertical[1];
          }
          else if((sourcelat>destiLat)&&(sourceLng>destiLng)){
            sourceHorizontal = destVincenty(sourcelat,sourceLng,90,distance);
            sourceVertical = destVincenty(sourceHorizontal[0],sourceHorizontal[1],0,distance);

            destiHorizontal = destVincenty(destiLat,destiLng,270,distance);
            destiVertical = destVincenty(destiHorizontal[0],destiHorizontal[1],180,distance);

            SW = destiVertical;
            NE = sourceVertical;
            NW[0] = sourceVertical[0];
            NW[1] = destiVertical[1];
            SE[0] = destiVertical[0];
            SE[1] = sourceVertical[1];
          }

          var path = [[NW[0],NW[1]],
                      [NE[0],NE[1]],
                      [SE[0],SE[1]],
                      [SW[0],SW[1]]];



        /*This inserts the boundary points based on user input
        into Geotree datastructure*/
        var requiredPoints = [];
				requiredPoints =set.find({lat:NE[0],lng:NE[1]},{lat:SW[0],lng:SW[1]});

				//set.dump();

				//console.log(requiredPoints);

				if(requiredPoints.length!=0){

					//Arranges GPS points in the needed order to draw lines
					arrangePoints(requiredPoints);
					polygon = mapObj.drawPolygon({
						paths: path,
						strokeColor: '#BBD8E9',
						strokeOpacity: 1,
						strokeWeight: 1,
						fillColor: '#BBD8E9',
						fillOpacity: 0.6
					});

					var nwlatlng = new google.maps.LatLng(sourceVertical[0],sourceVertical[1]);
					var selatlng = new google.maps.LatLng(destiVertical[0],destiVertical[1]);
					boundaries.push(nwlatlng);
					boundaries.push(selatlng);

					//Fits map to GPS boundaries
					mapObj.fitLatLngBounds(boundaries);
					return true;
				}
				else{
					alert("No data collected yet about your destination");
					return false;
				}



        }

        /*
        * Arranges GPS points needed to draw lines on
        * mapping interface
        */
        function arrangePoints(requiredPoints){
          var arrayLength,  point, roadId;
          var output, startpoint=[], pathPoints=[];
          var count =1;

          arrayLength=requiredPoints.length;
					console.log(requiredPoints);

          //Get first start point
          startpoint=requiredPoints.find(getStartpoint);
          roadId=startpoint.data.routeId;
          pathPoints.push(startpoint);

          //Remove Start Point
          requiredPoints.splice(requiredPoints.indexOf(startpoint),1);
          nxtlat=startpoint.data.NxtLat;


          //Looping through the entire array
          while (count<arrayLength){
            output = requiredPoints.find(getNextData);
						console.log(output);

            if(output!=undefined){
              pathPoints.push(output);

            //Delete entry
            point=requiredPoints.indexOf(output);
            requiredPoints.splice(point,1);

            //Checks if array is empty
            if(requiredPoints.length!=0){

              if((isNaN(output.data.NxtLat))||(output.data.NxtLat==undefined)){

                  //This breaks the routes
                  pathPoints.push(null);

                  //Locate the new route start point
                  startpoint=requiredPoints.find(getStartpoint);

                  //Remove the new point from the array
                  requiredPoints.splice(requiredPoints.indexOf(startpoint),1);

                  //Add point to constructed array
                  pathPoints.push(startpoint);

                  //Alter the nextlatitude
                  nxtlat=startpoint.data.NxtLat;

              }else{
              nxtlat=output.data.NxtLat;
              }
						}
						}
            count++;

          }

          drawLines(pathPoints);

          //Returns the next GPS point
          function getNextData(point){
            return ((point.lat==nxtlat)&&(point.data.routeId));
          }

          //Returns start point
          function getStartpoint(point){
            return point.data.position==1;
          }
        }

      //Draws lines on the mapping interface
      function drawLines(pathPoints){
        var path =[];
        var count =0;
				//console.log(pathPoints);
        //Loops the points to plot
        while(count<pathPoints.length+1){

          if(pathPoints[count]!=null){
            //Adds points to temp array

            path.push([pathPoints[count].lat, pathPoints[count].lng]);

          }else{
           //Plot path in the array
           polyline= mapObj.drawPolyline({
              path: path,
              strokeColor: '#131540',
              strokeOpacity: 0.6,
              strokeWeight: 6
            });

            //Empty array after plotting
            emptyArray(path);
          }
          count++;

        }

      }

      //Empties an array
      function emptyArray(array1){
        for (var i = array1.length; i > 0; i--) {
          array1.pop();
        }
        return array1;
      }

				function displayGPS(){
					document.getElementById("GPS").style.visibility ="visible";
					document.getElementById("lat").innerHTML =sourcelat;
					document.getElementById("long").innerHTML =sourceLng;

				}

      });

    </script>




  </body>
</html>
