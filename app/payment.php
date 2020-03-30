<?php  
    require "fb-init.php";
    if(isset($_SESSION['user'])){
        $user = $_SESSION['user'];
    }
    else{
        header("Location: login.php");
    }
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
                <a href="#">Welcome back, <?php echo $user[1]; ?> </a>
            </div>
            <div id="mainListDiv" class="main_list">
                <ul class="navlinks">
                    <li><a href="homepage.php">Home</a></li>
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
        <h2 class="myH2">Your payment history</h2>
    
        <table id="paymentTable" class='table100 ver2' style="margin-left:auto;margin-right:auto;" border=1>
            <tr class = 'table100 ver2'>
                <th>Trip ID</th>
                <th>Price</th>
                <th>Payment Status</th>
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
        
        var facebookID = $user;
        var serviceURL = "http://127.0.0.1:5003/paymentHistory/"+facebookID;
        var data = getData(serviceURL);

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

                //console.log(dictionaryOfData);
                count = 1;
                for (const trip_name in dictionaryOfData){
                    each_trip_deets = dictionaryOfData[trip_name];
                    rowspan = each_trip_deets.length;
                    //console.log(each_trip_deets);
                    eachRow = "<tr><td rowspan = " + rowspan + ">" + trip_name + "</td>" + "<td rowspan = " + rowspan + ">" + "<button id='paypal-button"+count+"'> </button>" + "</td>";
                    names_by_day = {};
                    for (const event of each_trip_deets){
                        if (!names_by_day[event[1]]){
                            names_by_day[event[1]] = [event[0]];
                        }
                        else{
                            names_by_day[event[1]].push(event[0])
                        }
                    }
                    //console.log(names_by_day);
                    //console.log(day_store);
                    for (const day_thingy in names_by_day){
                        //console.log(event);
                        names_of_place = names_by_day[day_thingy];
                        console.log(names_of_place);
                        length = names_of_place.length;

                        eachRow += "<td rowspan =" +length + ">" + "<form action='Scheduler/google_direction_sg.php' method='post'> <button type='submit' name='data' value="+btoa(names_of_place)+">View Route</button> </form>" + "</td>" +
                        "<td rowspan =" +length +">" + day_thingy + "</td>";
                        for (places of names_of_place){
                            //console.log(places);
                            eachRow += "<td>" + places + "</td></tr>";
                            
                        }
                        
                    }
                    
                    rows += eachRow;
                    
                    counter += 1;
                    count+=1;
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