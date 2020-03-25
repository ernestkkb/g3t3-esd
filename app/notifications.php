<!DOCTYPE html>
<!-- Validations -->
<?php
require './fb-init.php';
?>

<!-- Up until here. If validation is unsuccessful, redirected to logout page, session destroyed and redirected to login page !-->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script>
    $(document).ready(function(){
        $("submit").click(function(){
            $.post("demo_test_post.asp",
            {
            name: "Donald Duck",
            city: "Duckburg"
            },
            function(data,status){
            alert("Data: " + data + "\nStatus: " + status);
            });
        });
    });
</script>
<!-- Starting of the HTML BODY -->
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="./css/homepage.css">
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
                    <li><a href="./logout.php">Logout</a> <!-- Logout and destroy the session -->
                </ul>
            </div>
        </div>
    </nav>

    <section class="home"></section> <!--Don't Delete. This is for the background picture !-->


    <div style="height: 1000px">
        <!-- just to make scrolling effect possible -->
        <h2 class="myH2">Your planned schedule</h2>

        <div style="text-align:center">
        Email Address<input type = "text" id = "email">
        <button id = "sendMailButton">Submit</button>
        </div>                      
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

        async function postData(serviceURL, requestBody, emailAddress) {
            var requestParam = {
                headers: { "content-type": "charset=UTF-8; application/json;" },
                mode: 'no-cors', // other options: no-cors, etc.
                method: 'POST',
                body: JSON.stringify(requestBody)
            }
            try {
                alert("A copy of your itinerary has been sent to your email address at " + emailAddress);
                const response = await fetch(serviceURL, requestParam);
                data = await response.json();
                console.log(data);
            } catch (error) {
                console.error(error);
            }
        }
        $("#sendMailButton").click(function(){
            var emailAddress = $('#email').val(); // e.g., 9781449474453
            var serviceURL = "http://localhost:5004/notification/email";
            var requestBody = {
                email: emailAddress
            };
            postData(serviceURL, requestBody,emailAddress);
        });

    </script>




</body>
</html>