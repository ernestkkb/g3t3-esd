<!DOCTYPE html>
<!-- Validations -->
<?php  
    require "fb-init.php";
    if(isset($_SESSION['user'])){
        $user = $_SESSION['user'];
        if(isset($_SESSION['tripID'])){
            $tripID = $_SESSION['tripID'];
        }
    }
    else{
        header("Location: https://g3t3-ui.herokuapp.com/app/login.php");
    }
?>

<!-- Up until here. If validation is unsuccessful, redirected to logout page, session destroyed and redirected to login page !-->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
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
                <a href="#">Welcome back, <?php echo $user[1]; ?> </a>
            </div>
            <div id="mainListDiv" class="main_list">
                <ul class="navlinks">
                    <li><a href="https://g3t3-ui.herokuapp.com/app/homepage.php">Home</a></li>
                    <li><a href="https://g3t3-ui.herokuapp.com/app/notifications.php">Email</a></li>
                    <li><a href="https://g3t3-ui.herokuapp.com/app/payment_ms/payment.php">Payment</a></li>
                    <li><a href="https://g3t3-ui.herokuapp.com/app/summary.php">Summary</a>
                    <li><a href="https://g3t3-ui.herokuapp.com/app/logout.php">Logout</a> <!-- Logout and destroy the session -->
                </ul>
            </div>
        </div>
    </nav>

    <section class="home"></section> <!--Don't Delete. This is for the background picture !-->


    <div style="height: 1000px">
        <!-- just to make scrolling effect possible -->
        <h2 class="myH2">Your planned schedule</h2>

        <div style="text-align:center">
        Email Address <input type = "text" id = "email">
        <button id = "sendMailButton" class="btn btn-primary">Submit</button>
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

        

    </script>
     <script>
        $(window).scroll(function() {
            if ($(document).scrollTop() > 50) {
                $('.nav').addClass('affix');
                console.log("OK");
            } else {
                $('.nav').removeClass('affix');
            }
        });

        var facebookID = '<?php echo $user[0]?>';
        var tripID = '<?php echo $tripID?>';
        var serviceStartURL = "https://g3t3-notification.herokuapp.com/retrieveByTripID/"+tripID+"/"+facebookID;
        // var data = getData1(serviceURL);
        var places_dict = {};
        async function getData1(serviceURL) {
            let requestParam = {
                headers: { "content-type": "charset=UTF-8" },
                mode: 'cors', // allow cross-origin resource sharing
                method: 'GET',
            }
            try {
                const response = await fetch(serviceURL, requestParam);
                items = await response.json();
                var rows = "";
                data = items;
                console.log(items);
                console.log("F");
                console.log(data);
                return data;

        }catch (error) {
                console.error(error);
            }
    }
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
                window.location.href = "summary.php";
                return true;
            } catch (error) {
                console.error(error);
            }
        }
        $("#sendMailButton").click(function(){
            var emailAddress = $('#email').val(); // e.g., 9781449474453
            var serviceURL = "https://g3t3-notification.herokuapp.com/notification/email/"+emailAddress;
            console.log(serviceURL);

            getData1(serviceStartURL).then(function(value){
                postData(serviceURL,value,emailAddress);
            });
        });
    </script>



</body>
</html>