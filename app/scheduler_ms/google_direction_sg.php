<?php  
    require "../fb-init.php";
    if(isset($_SESSION['user'])){
        $user = $_SESSION['user'];
    }
    else{
        header("Location: ./login.php");
    }
?>
<!DOCTYPE html>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<html>
  <head>
    <script> 
     
    
    </script> 

    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <title>Waypoints in Directions</title>
    <style>
      #right-panel {
        font-family: 'Roboto','sans-serif';
        line-height: 30px;
        padding-left: 10px;
      }

      #right-panel select, #right-panel input {
        font-size: 15px;
      }

      #right-panel select {
        width: 100%;
      }

      #right-panel i {
        font-size: 12px;
      }
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
      #map {
        height: 100%;
        float: left;
        width: 70%;
        height: 100%;
      }
      #right-panel {
        margin: 20px;
        border-width: 2px;
        width: 20%;
        height: 400px;
        float: left;
        text-align: left;
        padding-top: 0;
      }
      #directions-panel {
        margin-top: 10px;
        background-color: #FFEE77;
        padding: 10px;
        overflow: scroll;
        height: 174px;
      }
    </style>
  </head>
  <body>
    <div id="map"></div>
    <div id="right-panel">
    <div>
    <b>Start:</b>
    <select id="start">
    </select>
    <script>
    var data1 = "<?php $things =$_POST['data']; $here =$things; echo $here;?>";
    data1 = data1.split("splitter");
    //var datas = ["Downtown, Singapore", "2 Cox Terrace, Singapore 179622", "8 Sentosa Gateway, Singapore 098269"];
    $(document).ready(function(){
      for (let i = 0; i < data1.length; i++) {
      var one_data = data1[i];
      // console.log(one_data);
      $('#start').append('<option value="' +  one_data  + '">' + one_data + '</option>');
    }
    })
    </script>
    
    <br>
    <b>Waypoints:</b> <br>
    <i>(Ctrl+Click or Cmd+Click for multiple selection)</i> <br>
    <select multiple id="waypoints">
    </select>

    <script>
    //var data2 = ["Siloso Rd, Singapore", "2 Cox Terrace, Singapore 179622", "8 Sentosa Gateway, Singapore 098269"];
    var data2 = "<?php $things =$_POST['data']; $here =$things; echo $here;?>";
    data2 = data2.split("splitter");
    $(document).ready(function(){
      for (let i = 0; i < data2.length; i++) {
      var one_data = data2[i];
      // console.log(one_data);
      $('#waypoints').append('<option value="' +  one_data  + '">' + one_data + '</option>');
    }
    })
    </script>

    <br>
    <b>End:</b>
    <select id="end">
    </select>
    <script>
    //var datas = ["Downtown, Singapore", "2 Cox Terrace, Singapore 179622", "8 Sentosa Gateway, Singapore 098269"];
    var data3 = "<?php $things =$_POST['data']; $here =$things; echo $here;?>";
    data3 = data3.split("splitter");
    console.log(data3);
    $(document).ready(function(){
      for (let i = 0; i < data3.length; i++) {
      var one_data = data3[i];
      // console.log(one_data);
      $('#end').append('<option value="' +  one_data  + '">' + one_data + '</option>');
    }
    })
    </script>
    <br>
      <input type="submit" id="submit">
    </div>
    <div id="directions-panel"></div>
    </div>
    <script>
      function initMap() {
        var directionsService = new google.maps.DirectionsService;
        var directionsRenderer = new google.maps.DirectionsRenderer;
        var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 6,
          center: {lat: 41.85, lng: -87.65}
        });
        directionsRenderer.setMap(map);

        document.getElementById('submit').addEventListener('click', function() {
          calculateAndDisplayRoute(directionsService, directionsRenderer);
        });
      }

      function calculateAndDisplayRoute(directionsService, directionsRenderer) {
        var waypts = [];
        var checkboxArray = document.getElementById('waypoints');
        for (var i = 0; i < checkboxArray.length; i++) {
          if (checkboxArray.options[i].selected) {
            waypts.push({
              location: checkboxArray[i].value,
              stopover: true
            });
          }
        }

        directionsService.route({
          origin: document.getElementById('start').value,
          destination: document.getElementById('end').value,
          waypoints: waypts,
          optimizeWaypoints: true,
          travelMode: 'DRIVING'
        }, function(response, status) {
          if (status === 'OK') {
            directionsRenderer.setDirections(response);
            var route = response.routes[0];
            var summaryPanel = document.getElementById('directions-panel');
            summaryPanel.innerHTML = '';
            // For each route, display summary information.
            for (var i = 0; i < route.legs.length; i++) {
              var routeSegment = i + 1;
              summaryPanel.innerHTML += '<b>Route Segment: ' + routeSegment +
                  '</b><br>';
              summaryPanel.innerHTML += route.legs[i].start_address + ' to ';
              summaryPanel.innerHTML += route.legs[i].end_address + '<br>';
              summaryPanel.innerHTML += route.legs[i].distance.text + '<br><br>';
            }
          } else {
            window.alert('Directions request failed as place is not reachable.');
          }
        });
      }
    </script>
    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCVh6H9I5mG7Y3nXkLzjRwKogIhhBhjVkw&callback=initMap">
    </script>
  </body>
</html>