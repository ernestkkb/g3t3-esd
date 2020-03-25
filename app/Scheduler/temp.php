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
    </head>
</html>

