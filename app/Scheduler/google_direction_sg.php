<!DOCTYPE html>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<?php
  $tripID = "";
  if(isset($_GET['tripId'])){
    $tripID = $_GET['tripId'];
    $facebookID = $_GET['facebookID'];
    $day = $_GET['day'];
  }
  echo " 
  <input type='hidden' id = 'tripID'>$tripID</input>
  ";
?> 

<html>
  <head>
    <script> 
    $(async() => {           
        // Change serviceURL to your own
        var serviceURL = "http://127.0.0.1:5000/scheduler/";
 
        try {
            const response =
             await fetch(
               serviceURL, { method: 'GET' }
            );
            const data = await response.json();
            var trip = data.trip; //the arr is in data.books of the JSON data
 
            // array or array.length are falsy
            if (!books || !books.length) {
                showError('Books list empty or undefined.')
            } else {
                // for loop to setup all table rows with obtained book data
                var rows = "";
                for (const book of books) {
                    eachRow =
                        "<td>" + book.title + "</td>" +
                        "<td>" + book.isbn13 + "</td>" +
                        "<td>" + book.price + "</td>" +
                        "<td>" + book.availability + "</td>";
                    rows += "<tbody><tr>" + eachRow + "</tr></tbody>";
                }
                // add all the rows to the table
                $('#booksTable').append(rows);
            }
        } catch (error) {
            // Errors when calling the service; such as network error, 
            // service offline, etc
            showError
            ('There is a problem retrieving books data, please try again later.<br />'+error);
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