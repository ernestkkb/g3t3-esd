<?php
if (isset($_POST['country']) && isset($_POST['city'])) {
    $country = $_POST['country'];
    $city = $_POST['city'];

}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width">

    <title>Add Trip</title>

    <link rel="stylesheet" href="">
    <!--[if lt IE 9]>
      <script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <!-- Bootstrap libraries -->
    <meta name="viewport" 
        content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet"
    href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css"
    integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" 
    crossorigin="anonymous">

    <!-- Latest compiled and minified JavaScript -->
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script 
    src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    
    <script
    src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js"
    integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut"
    crossorigin="anonymous"></script>
    
    <script 
    src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js"
    integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k"
    crossorigin="anonymous"></script>
</head>
<body>
    <form method="POST" action="fetch.php">
            Trip Name<br>
            <input name="tripName" type="text" id="tripname" value ="" style = "width:100%"/><br>
            <!-- Start Date<br>
            <input name="startdate" type="text" id="startdate" value = <?php $startdate?> style = "width:100%"/><br>
            End Date<br>
            <input name="enddate" type="text" id="enddate" value = <?php $enddate?>  style = "width:100%"/><br> -->
            Number of days<br>
            <input name="days" type="text" id="days" style = "width:100%"/></br>
            City<br>
            <input name="city" type="text" id="city" value=<?php echo $_POST['city']; ?> style = "width:100%"/></br>
            <br>
            <input type="submit" name="submit" value="Find Places of Interest"/>
    </form>
    
</body>
</html>

</html>