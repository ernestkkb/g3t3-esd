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
    }
  }
?>
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
            <span class="navTrigger">
                <i></i>
                <i></i>
                <i></i>
            </span>
        </div>
    </nav>

    <section class="home">
    </section>
    <div style="height: 1000px">
        <!-- just to make scrolling effect possible -->
            <p class="myP" align="middle"> <img src="https://graph.facebook.com/<?php echo $user->getField('id') ?>/picture?type=large"></img>
			<h2 class="myH2">Your planned schedule</h2>
			<!-- <p class="myP">This is a responsive fixed navbar animated on scroll</p>
			<p class="myP">I took inspiration from  ABDO STEIF (<a href="https://codepen.io/abdosteif/pen/bRoyMb?editors=1100">https://codepen.io/abdosteif/pen/bRoyMb?editors=1100</a>)
			and Dicson <a href="https://codepen.io/dicson/pen/waKPgQ">(https://codepen.io/dicson/pen/waKPgQ)</a></p>
			<p class="myP">I HOPE YOU FIND THIS USEFULL</p>
			<p class="myP">Albi</p>
				<p class="myP"> -->
			</p>
            <form>
    <div class="form-row">
        <div class="col-7">
        <input type="text" class="form-control" placeholder="City">
        </div>
        <div class="col">
        <input type="text" class="form-control" placeholder="State">
        </div>
        <div class="col">
        <input type="text" class="form-control" placeholder="Zip">
        </div>
    </div>
    </form>

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