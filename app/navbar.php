<html>
<head>
<link rel="stylesheet" href="css/homepage.css">
<link rel="stylesheet" href = "css/main.css">    
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

</head>
<body>
<nav class="nav">
        <div class="container">
            <div class="logo">
                <a href="#">Welcome back, <?php if(isset($user['last_name'])){
                    echo $user['last_name'];
                }
                elseif(isset($user[1])){
                    echo $user[1];
                    }
                else{
                        echo "LOL";
                        }?> </a>
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

<section class="home"></section> 
<!-- Don't Delete. This is for the background picture ! -->

