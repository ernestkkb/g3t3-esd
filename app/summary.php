<!-- Validations -->
<?php
require './fb-init.php';
?>

<?php # require "fb-init.php";?>

<?php # if (isset($_SESSION['access_token'])): ?>
    
<?php # else: ?>
    <!-- <a href="<?php # echo $login_url;?>">Login With Facebook</a> -->
<?php # endif; ?>

<?php
// if (isset($_SESSION['access_token'])) {

//     try {
//       $fb->setDefaultAccesstoken($_SESSION['access_token']);

//       $response = $fb->get('/me?fields=id,name,picture,last_name', $_SESSION['access_token']);
//       $user = $response->getGraphUser();

//     } catch (Exception $e) {
//       echo $e->getTraceAsString();
//       header("Location: ./logout.php");
//     }
//   }
?>

<!-- Up until here. If validation is unsuccessful, redirected to logout page, session destroyed and redirected to login page !-->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

<!-- Starting of the HTML BODY -->
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="./css/homepage.css">
    <link rel="stylesheet" href = "./css/main.css">

</head>
<body>


<nav class="nav">
        <div class="container">
            <div class="logo">
                <a href="#">Welcome back, <?php # echo $user->getField('last_name') ?> </a>
            </div>
            <div id="mainListDiv" class="main_list">
                <ul class="navlinks">
                    <li><a href="./notifications.php">Email</a></li>
                    <li><a href="./payment_ms/payment.php">Payment</a></li>
                    <li><a href="./search_ms/search.php">Start Planning</a></li>
                    <li><a href="./calendar_ms/calendar.php">Calendar</a></li>
                    <li><a href="summary.php">Summary</a>
                    <li><a href="./logout.php">Logout</a> <!-- Logout and destroy the session -->
                    
                </ul>
            </div>
        </div>
    </nav>

    <section class="home"></section> <!--Don't Delete. This is for the background picture !-->


    <div style="height: 1000px">
        <!-- just to make scrolling effect possible -->
        <h2 class="myH2">Your planned schedule</h2>
    
        <table id="summaryTable" class='table100 ver2' style="margin-left:auto;margin-right:auto;" border=1>
            <tr class = 'table100 ver2'>
                <th>Trip Name</th>
                <th>Pay</th>
                <th>Day</th>
                <th>Places Of Interest</th>
                <th>View Route</th>
            </tr>
        </thead>
    </table>

    </div>

<!-- Jquery needed -->
    <script src="js/scripts.js"></script>

<!-- Function used to shrink nav bar removing paddings and adding black background -->
    <script>
        $(window).scroll(function() {
            if ($(document).scrollTop() > 50) {
                $('.nav').addClass('affix');
                console.log("OK");
            } else {
                $('.nav').removeClass('affix');
            }
        });
        
        var facebookID = 1
        var serviceURL = "http://127.0.0.1:5002/retrieveAll/"+facebookID;
        var data = getData(serviceURL);
        var places_dict = {};

        async function getData(serviceURL) {
            let requestParam = {
                headers: { "content-type": "charset=UTF-8" },
                mode: 'cors', // allow cross-origin resource sharing
                method: 'GET',
            }
            try {
                const response = await fetch(serviceURL, requestParam);
                items = await response.json();
                var rows = "";
                data = items.details;
                //console.log(data);
                rowcounts = data.length;
                counter = 1;
                var dictionaryOfData = {};
                for (const poi of data){
                    //console.log(poi);
                    var tripDetails = poi.placeOfInterest;
                    var day = poi.day;
                    //console.log(tripDetails);
                    var tripName = poi.tripName;
                    if(!dictionaryOfData[tripName]){
                        dictionaryOfData[tripName] = [[tripDetails.name,day]];
                    }
                    else{
                        dictionaryOfData[tripName].push([tripDetails.name,day]);
                    }
                }
                //console.log(dictionaryOfData);
                for (const trip_name in dictionaryOfData){
                    each_trip_deets = dictionaryOfData[trip_name];
                    rowspan = each_trip_deets.length;
                    //console.log(each_trip_deets);
                    eachRow = "<tr><td rowspan = " + rowspan + ">" + trip_name + "</td>" + "<td rowspan = " + rowspan + ">" + "<form method='POST' id='paymentForm'><button type='submit' name='pay' id='pay'>Pay here</button></form>"  + "</td>";
                    names_by_day = {};
                    for (const event of each_trip_deets){
                        if (!names_by_day[event[1]]){
                            names_by_day[event[1]] = [event[0]];
                        }
                        else{
                            names_by_day[event[1]].push(event[0])
                        }
                    }
                    console.log(names_by_day);
                    //console.log(day_store);
                    for (const event of each_trip_deets){
                        //console.log(event);
                        
                        toadd = "<td>" + event[1] + "</td>" +
                        "<td>" + event[0] + "</td>" + 
                        "<td>" +"<form action='Scheduler/google_direction_sg.php' method='post'> <button type='submit' name='data' value="+btoa(names_by_day[event[1]])+">View Route</button> </form>" + "</td></tr>";
                        eachRow +=toadd;
                    }
                    
                    rows += eachRow;
                    
                    counter += 1;
                }
                
                // add all the rows to the table
                $('#summaryTable').append(rows);



            } catch (error) {
                console.error(error);
            }


        }

        // $('#pay').click(async() => {
        //     event.preventDefault();
        //     var tripName = 
        //     var tripID = 
        //     var data = {"tripName": , "tripID":};
        //     // data to send over to scheduler.py: the tripname, trip id
        //     var addpoiURL = "http://127.0.0.1:5002/makePayment";
        //     await fetch(
        //         addpoiURL, {
        //         method: 'POST',
        //         mode: 'cors',
        //         headers: { "Content-Type": "application/json"},
        //         body: JSON.stringify(data)
        //     });

            
        // });
    </script>

</body>
</html>