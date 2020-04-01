<html>
<head>
<link rel="stylesheet" href="css/homepage.css">
<link rel="stylesheet" href = "css/main.css">    
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
                    <li><a href="homepage.php">Home</a></li>
                    <li><a href="./notifications.php">Email</a></li>
                    <li><a href="./payment_ms/payment.php">Payment</a></li>
                    <li><a href="summary.php">Summary</a>
                    <li><a href="./logout.php">Logout</a> <!-- Logout and destroy the session -->
                </ul>
            </div>
        </div>
</nav>

<section class="home"></section> 
<!-- Don't Delete. This is for the background picture ! -->

