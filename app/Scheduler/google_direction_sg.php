<!DOCTYPE html>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<html>
  <head>
    <script> 
     $(async() => {      
        //grab data from URL
        //http://localhost/g3t3-esd/app/Scheduler/temp.php?tripID=21&facebookID=91009100&day=2
        const queryString = window.location.search;  
        const urlParams = new URLSearchParams(queryString);
        const tripID = urlParams.get('tripID');
        const facebookID = urlParams.get('facebookID');
        const day = urlParams.get('day');

        var serviceURL = "http://127.0.0.1:5002/scheduler/" + tripID + '/' + facebookID + '/' + day;
        // var serviceURL = "http://127.0.0.1:5002/scheduler/1/9/1";
        try {
            const response = await fetch( serviceURL, { method: 'GET' , mode: 'cors'} );
            const data = await response.json();
            var trips = data.trips; //the arr is in data.books of the JSON data
            console.log(trips);
            
        } catch (error) {
            console.log(error);
            } // error
        });
    
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
    var datas = ["Downtown, Singapore", "2 Cox Terrace, Singapore 179622", "8 Sentosa Gateway, Singapore 098269"];
    $(document).ready(function(){
      for (let i = 0; i < datas.length; i++) {
      var one_data = datas[i];
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
    var data2 = ["Siloso Rd, Singapore", "2 Cox Terrace, Singapore 179622", "8 Sentosa Gateway, Singapore 098269"];
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
    var datas = ["Downtown, Singapore", "2 Cox Terrace, Singapore 179622", "8 Sentosa Gateway, Singapore 098269"];
    $(document).ready(function(){
      for (let i = 0; i < datas.length; i++) {
      var one_data = datas[i];
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
            window.alert('Directions request failed due to ' + status);
          }
        });
      }
    </script>
    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCVh6H9I5mG7Y3nXkLzjRwKogIhhBhjVkw&callback=initMap">
    </script>
  </body>
</html>