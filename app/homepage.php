<!-- Validations -->
<?php
require './fb-init.php';
?>

<?php require "fb-init.php";?>

<?php if (isset($_SESSION['access_token'])): ?>
    
<?php else: ?>
    <a href="<?php echo $login_url;?>">Login With Facebook</a>
<?php endif; ?>

<?php
if (isset($_SESSION['access_token'])) {

    try {
      $fb->setDefaultAccesstoken($_SESSION['access_token']);

      $response = $fb->get('/me?fields=id,name,picture,last_name', $_SESSION['access_token']);
      $user = $response->getGraphUser();

    } catch (Exception $e) {
      echo $e->getTraceAsString();
      header("Location: ./logout.php");
    }
  }
?>


<!-- Up until here. If validation is unsuccessful, redirected to logout page, session destroyed and redirected to login page !-->

<!-- Refer to Lab 6 -- Creating a Bookstore Interface !-->


<script>
        // anonymous async function - using await requires the function that calls it to be async
        $(async() => {           
            // Change serviceURL to your own
            var serviceURL = "http://127.0.0.1:5000/locations";
            console.log(serviceURL);
            try {
                const response =
                await fetch(
                    serviceURL, { method: 'GET' }
                );
                const data = await response.json();
                var locations = data.locations; //the arr is in data.books of the JSON data
                // array or array.length are falsy
                console.log(locations);
                if (!locations || !locations.length) {
                    console.log('Locations list empty or undefined.');
                } else {
                    // for loop to setup all table rows with obtained book data
                    var locations_arr = [];

                    for (const location of locations) {
                        locations_arr.push([location[0], location[1]])
                    }

                    console.log(locations_arr);
                }
            } catch (error) {
                console.trace();
            } // error
        });
    </script>

<!-- Starting of the HTML BODY -->
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="./css/homepage.css">


    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script> -->
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" /> -->
    <!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script> -->
    <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"> -->
</head>

<body>

<nav class="nav">
        <div class="container">
            <div class="logo">
                <a href="#">Welcome back, <?php echo $user->getField('last_name') ?> </a>
            </div>
            <div id="mainListDiv" class="main_list">
                <ul class="navlinks">
                    <li><a href="#">About</a></li>
                    <li><a href="./payment_ms/payment.php">Payment</a></li>
                    <li><a href="./search_ms/search.php">Start Planning</a></li>
                    <li><a href="./calendar_ms/calendar.php">Calendar</a></li>
                    <li><a href="./logout.php">Logout</a> <!-- Logout and destroy the session -->
                </ul>
            </div>
        </div>
    </nav>

    <section class="home">
    </section>
    <div style="height: 1000px">
        <!-- just to make scrolling effect possible -->
			<h2 class="myH2">Your planned schedule</h2>


            <div style="text-align:center">
            
                <form id="search_bar" method="POST" action="/retrieve_data">
                    <font size="+2"> Country: </font> 
                    <select style="width:150px" name="country">
                        <option value="test"></option>
                    </select>
                    <font size="+2"> City: </font>
                    <select style="width:150px" name="city">
                        <option value="test">Test</option>
                    </select>
                    <input type="submit" name="submit">
                </form>

                <?php


            

                ?>
            </div>

                      
    </div>



  
<!-- Jquery needed -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
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
    </script>

</body>
</html>