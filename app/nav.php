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
                    <li><a href="https://g3t3-ui.herokuapp.com/app/search_ms/search.php">Start Planning</a></li>
                    <li><a href="https://g3t3-ui.herokuapp.com/app/calendar_ms/calendar.php">Calendar</a></li>
                    <li><a href="https://g3t3-ui.herokuapp.com/app/summary.php">Summary</a>
                    <li><a href="https://g3t3-ui.herokuapp.com/app/logout.php">Logout</a> <!-- Logout and destroy the session -->
                </ul>
            </div>
        </div>
    </nav>

    <section class="home"></section> <!--Don't Delete. This is for the background picture !-->

