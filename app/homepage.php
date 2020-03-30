<!-- Validations -->
<?php
require './fb-init.php';
?>

<?php  require "fb-init.php";?>

<?php  if (isset($_SESSION['access_token'])): ?>
    
<?php  else: ?>
    <a href="<?php # echo $login_url;?>">Login With Facebook</a>
<?php  endif; ?>

<?php
if (isset($_SESSION['access_token'])) {

    try {
      $fb->setDefaultAccesstoken($_SESSION['access_token']);


      $response = $fb->get('/me?fields=id,name,picture,last_name', $_SESSION['access_token']);
      $user = $response->getGraphUser();
      $_SESSION['user'] = [$user['id'], $user['last_name']];
      echo(($_SESSION['user'][0]));

    

    //   print_r( $_SESSION['user']);
      

    } catch (Exception $e) {
      echo $e->getTraceAsString();
      header("Location: ./logout.php");
    }
  }
?>

<!-- Up until here. If validation is unsuccessful, redirected to logout page, session destroyed and redirected to login page !-->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script>

        async function getData(serviceURL) {
            let requestParam = {
                headers: {"content-type": "charset=UTF-8"},
                mode: 'cors',
                method: 'GET',
            }

            try {
                const response = await fetch(serviceURL, requestParam);
                data = await response.json();
                return data
            } catch (error) {
                console.error(error);
            }
        }

        $(document).ready(function() {
            var serviceURL = "http://127.0.0.1:5000/locations";
            var locations = getData(serviceURL);

            locations.then((data) => { // Necessary for async programming
                for (let i = 0; i < data.length; i++) {
                    all_locations.shift(data);
                }
                // all_locations.shift(data);
                // console.log(data);
                // console.log(typeof(data)); // confirms that is object data type
                
                //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                // locations_array = data.locations;
                // var locations_dict = {};
                // locations_array.forEach(function(locations_array){
                //     if (!locations_array['country'] in locations_dict){
                //         locations_dict[locations_array['country']]=[locations_array['city']];
                //     }else{
                //         locations_dict[locations_array['country']].push(locations_array['city']);
                        
                //     }
                // console.log(locations_dict);
                /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                console.log(data.locations);
                locations_array = data.locations;
                var all_countries = [];
                locations_array.forEach(function(locations_array){
                    var country_options = locations_array['country'];
                    var city_option = locations_array['city'];

                    if (!all_countries.includes(country_options)) {
                        $('#country').append('<option value =' + country_options + '>'+ country_options+'</option>')
                        all_countries.push(country_options);   
                    }
                })

                // locations_array.forEach(function(locations_array){
                //     var country_options = locations_array['country'];
                //     var city_option = locations_array['city'];

                //     if (!all_countries.includes(country_options)) {
                //         $('#country').append('<option>' + country_options + '</option>')
                //         all_countries.push(country_options);
                //     }
                //     $('#city').append('<option>' + city_option + '</option>')
                // })
                
                $('#country').change(function(){
                        let selected_country = $('#country').val();
                        $('#city').empty();
                        locations_array.forEach(function(locations_array){
                            if (locations_array['country']==selected_country){
                                var here = String(locations_array['city']);
                                $('#city').append("<option value ='" + here+ "'>" + here + '</option>');
                            }
                        })
                })
            })
        })
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


    <div style="height: 50px">
        <!-- just to make scrolling effect possible -->
        <h2 class="myH2">Customise your schedule</h2>
        <div style="text-align:center">
        <h3 class="myH3"> Start by choosing your desired Country & City </h3>

        <div style="text-align:center">
        
            <form id="search_bar" method="POST" action="./Scheduler/add_trip.php">

                <font size="+2"> Country: </font> 
                <select style="width:150px" name="country" id="country">
                <option selected disabled='true' >Select Country</option>
                <!-- Values are filled from the script portion above !-->
                </select>
                <br>
                <font size="+2"> City: </font>
                <select style="width:150px" name="city" id="city">
                    <option selected disabled='true'>Select City</option>
                <!-- Values are filled from the script portion above !-->
                </select>
                <br>
                </br>
                <input type="submit" name="submit">
            </form>
        </div>         
        <br></br><br></br>
        <h2 class="myH2">Or pick a package below!</h2>
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
    </script>

</body>

<?php
    require 'package_nonav.php';
?>

</html>