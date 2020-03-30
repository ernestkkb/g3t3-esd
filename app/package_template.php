<?php  
    require "fb-init.php";
    if(isset($_SESSION['user'])){
        $user = $_SESSION['user'];
    }
    else{
        header("Location: login.php");
    }
?>
<html>

<head>
  <meta charset="UTF-8">
  <title>Pricing Table</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" >
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" >
<link rel="stylesheet" href="temp.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
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
    
<section>
  <div class="container-fluid">
    <div class="container">
      <div class="row">
        <div class="col-sm-4">
          <div class="card text-center">
            <div class="title">
              <i class="fa fa-paper-plane" aria-hidden="true"></i>
              <h2>Japan</h2>
            </div>
            <div class="price">
              <h4><sup>$</sup>321</h4>
            </div>
            <div class="option">
              <ul>
              <li> <i class="fa fa-check" aria-hidden="true"></i> Japan, Osaka </li>
              <li> <i class="fa fa-check" aria-hidden="true"></i> Japan, Tokyo </li>
              <li> <i class="fa fa-check" aria-hidden="true"></i> Japan, Okinawa </li>
              <li> <i class="fa fa-check" aria-hidden="true"></i> Japan, Hokkaido </li>
              <!-- <li> <i class="fa fa-times" aria-hidden="true"></i> Japan, Hokkaido </li> -->
              </ul>
            </div>
            <a href="#">Order Now </a>
          </div>
        </div>
        <!-- END Col one -->
        <div class="col-sm-4">
          <div class="card text-center">
            <div class="title">
              <i class="fa fa-plane" aria-hidden="true"></i>
              <h2>Singapore</h2>
            </div>
            <div class="price">
              <h4><sup>$</sup>350</h4>
            </div>
            <div class="option">
              <ul>
              <li> <i class="fa fa-check" aria-hidden="true"></i> Merlion Park </li>
              <li> <i class="fa fa-check" aria-hidden="true"></i> Gardens by the Bay </li>
              <li> <i class="fa fa-check" aria-hidden="true"></i> Cloud Forest </li>
              <li> <i class="fa fa-check" aria-hidden="true"></i> Singapore Zoo </li>
              <!-- <li> <i class="fa fa-times" aria-hidden="true"></i> Singapore Zoo </li> -->
              </ul>
            </div>
            <a href="#">Order Now </a>
          </div>
        </div>
        <!-- END Col two -->
        <div class="col-sm-4">
          <div class="card text-center">
            <div class="title">
              <i class="fa fa-rocket" aria-hidden="true"></i>
              <h2>Premium</h2>
            </div>
            <div class="price">
              <h4><sup>$</sup>100</h4>
            </div>
            <div class="option">
              <ul>
              <li> <i class="fa fa-check" aria-hidden="true"></i> Unlimited GB Space </li>
              <li> <i class="fa fa-check" aria-hidden="true"></i> 30 Domain Names </li>
              <li> <i class="fa fa-check" aria-hidden="true"></i> Unlimited Email Address </li>
              <li> <i class="fa fa-check" aria-hidden="true"></i> Live Support </li>
              </ul>
            </div>
            <a href="#">Order Now </a>
          </div>
        </div>
        <!-- END Col three -->
      </div>
    </div>
  </div>
</section>
</body>
</html>
