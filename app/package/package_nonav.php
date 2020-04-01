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
</head>
<body>
    
<section>
  <div class="container-fluid">
    <div class="container">
      <div class="row">
        <div class="col-sm-4">
          <div class="card text-center">
            <div class="title">
              <i class="fa fa-plane" aria-hidden="true"></i>
              <h2>United States <br> New York </h2>
            </div>
            <div class="price">
              <h4><sup>$</sup>320</h4>
            </div>
            <div class="option">
              <ul>
              <li> <i class="fa fa-check" aria-hidden="true"></i>5 days</li>
              <!-- <li> <i class="fa fa-times" aria-hidden="true"></i> Japan, Hokkaido </li> -->
              </ul>
            </div>
            <a href="package/package_view.php?tripID=OkjrD6SzQw">View</a>
          </div>
        </div>
        <!-- END Col one -->
        <div class="col-sm-4">
          <div class="card text-center">
            <div class="title">
              <i class="fa fa-plane" aria-hidden="true"></i>
              <h2>Australia <br> Melbourne </h2>
            </div>
            <div class="price">
              <h4><sup>$</sup>350</h4>
            </div>
            <div class="option">
              <ul>
              <li> <i class="fa fa-check" aria-hidden="true"></i> 5 days </li>
              <!-- <li> <i class="fa fa-times" aria-hidden="true"></i> Singapore Zoo </li> -->
              </ul>
            </div>
            <a href="package/package_view.php?tripID=cPG0LAtqIk">View</a>
          </div>
        </div>
        <!-- END Col two -->
        <div class="col-sm-4">
          <div class="card text-center">
            <div class="title">
              <i class="fa fa-plane" aria-hidden="true"></i>
              <h2>Canada<br>Vancouver</h2>
            </div>
            <div class="price">
              <h4><sup>$</sup>400</h4>
            </div>
            <div class="option">
              <ul>
              <li> <i class="fa fa-check" aria-hidden="true"></i>5 days</li>
              </ul>
            </div>
            <a href="package/package_view.php?tripID=6vLq62DGv0">View</a>
          </div>
        </div>
        <!-- END Col three -->
      </div>
    </div>
  </div>
</section>
</body>
</html>
