<html>
  <head>
    <title>
      Quadtree Test
    </title>
    <script src="geo-tree/z-curve.js" type="text/javascript"></script>
    <script src="geo-tree/red-black.js" type="text/javascript"></script>
    <script src="geo-tree/geo-tree.js" type="text/javascript"></script>
  </head>
  <body>
   <?php
    include_once('coordinates.php');
    /*
    0 takes GPS longitude
    1 takes GPS latitude
    2 takes Road Grade
    3 takes Next Longitude
    4 takes Next Latitude
    */
      $GPSdata = array(array());
      $obj = new coordinates();
      $obj->fetchAllData();
      $count=0;
      while($coordData=$obj->fetch()){
        $GPSdata[$count][0]=$coordData['grade'];
        $GPSdata[$count][1]=$coordData['Longitude'];
        $GPSdata[$count][2]=$coordData['Latitude'];
        $GPSdata[$count][3]=$coordData['nxtLongitude'];
        $GPSdata[$count][4]=$coordData['nxtLatitude'];
        $GPSdata[$count][5]=$coordData['routeId'];
        $count++;
      }

   // print_r($GPSdata);

    ?>
    <script>
      var set = new GeoTree();
      var binaryTreeGPS=[], GradeData=[], tempArray=[];
      var GPSpoints = <?php echo json_encode($GPSdata); ?>;

      for (var i=0;i<GPSpoints.length;i++){
        GradeData={NxtLng:parseFloat(GPSpoints[i][3]),NxtLat:parseFloat(GPSpoints[i][4]),grade:GPSpoints[i][0]};
        binaryTreeGPS={lat: parseFloat(GPSpoints[i][2]),lng: parseFloat(GPSpoints[i][1]),data: GradeData};
        console.log(binaryTreeGPS);
        set.insert(binaryTreeGPS);
      }


     /* set.insert([
        {lat: 52.50754, lng: 13.42614, data: 'Berlin, Germany'},
        {lat: 51.500728, lng: -0.124626, data: 'London'},
        {lat: -33.9593169, lng: 18.6741289, data: 'Cape Town'},
        {lat: 50.05967, lng: 14.46562, data: 'Prague, Czech Republic'}
      ]);*/

      set.dump();

      /*window.onbeforeunload = updateDB;
      function updateDB(){

         return null;
      }*/

      /*var output = set.find({lat: -30, lng: -0.5}, {lat: 55, lng: 20});
      console.log(output);*/
    </script>

  </body>
</html>
