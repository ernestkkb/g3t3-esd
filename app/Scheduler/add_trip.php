<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width">

    <title>Add Trip</title>

    <link rel="stylesheet" href="">
    <!--[if lt IE 9]>
      <script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <!-- Bootstrap libraries -->
    <meta name="viewport" 
        content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet"
    href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css"
    integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" 
    crossorigin="anonymous">

    <!-- Latest compiled and minified JavaScript -->
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script 
    src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    
    <script
    src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js"
    integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut"
    crossorigin="anonymous"></script>
    
    <script 
    src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js"
    integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k"
    crossorigin="anonymous"></script>
</head>
<body>
    <div id="main-container" class="container">
        <h1 class="display-4">Add a Trip</h1>
        <form id="AddTripForm" method = 'POST'>
            Trip Name<br>
            <input name="tripName" type="text" id="tripname" value =<?php $tripname?> style = "width:100%"/><br>
            Start Date<br>
            <input name="startdate" type="text" id="startdate" value = <?php $startdate?> style = "width:100%"/><br>
            End Date<br>
            <input name="enddate" type="text" id="enddate" value = <?php $enddate?>  style = "width:100%"/><br>
            City<br>
            <input name="city" type="text" id="city" value = <?php $city?>  style = "width:100%"/></br>
            <br>
            <input class="btn btn-primary" type="submit" value="Find places of Interest">
        </form>
        <br><br>
    
    </div>
    <script>
        // Helper function to display error message
        function showError(message) {
            // Hide the table and button in the event of error
            $('#tripsTable').hide();
            $('#addTripBtn').hide();
    
            // Display an error under the main container
            $('#main-container')
                .append("<label>"+message+"</label>");
        }
    
        // anonymous async function 
        // - using await requires the function that calls it to be async
        $("#AddTripForm").submit(async() => {     
            event.preventDefault();      
            // Change serviceURL to your own
            var tripName = $('#tripName').val();
            var startDate = $("startDate").val();
            var endDate = $("endDate").val();
            var placesOfInterest = $("placesOfInterest").val()
            var serviceURL = "http://127.0.0.1:5002/scheduler"+"/"+tripName;
    
            try {   
                const response =
                 await fetch(
                    serviceURL, { 
                    method: 'POST', 
                    headers:{"Content-Type": "application/json"},
                    body: JSON.stringify({tripName:tripName, startDate:startDate, endDate:endDate, placesOfInterest:placesOfInterest})}
                );

                const data = await response.json();
                // var books = data.trips; //the arr is in data.books of the JSON data
            
                // array or array.length are false
                if (!data) {
                    showError('Add trip failed.')
                } else {
                    window.location = 'http://localhost/Scheduler/route_trip.php';
                }
            } catch (error) {
                // Errors when calling the service; such as network error, 
                // service offline, etc
                showError
              ('There is a problem retrieving trips data, please try again later.<br />'+error);
               
            } // error
        });
    </script>
</body>
</html>

</html>