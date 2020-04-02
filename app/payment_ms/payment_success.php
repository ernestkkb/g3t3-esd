
<!DOCTYPE html>
    <html>
    <head>
        <meta charset="utf-8">
        <title>Payment Success</title>
        <link rel="stylesheet" href="../css/homepage.css">
        <link rel="stylesheet" href = "../css/main.css">    
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
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
                        <li><a href="../homepage.php">Home</a></li>
                        <li><a href="../notifications.php">Email</a></li>
                        <li><a href="./payment.php">Payment</a></li>
                        <li><a href="../summary.php">Summary</a>
                        <li><a href="../logout.php">Logout</a> <!-- Logout and destroy the session -->
                    </ul>
                </div>
            </div>
    </nav>
    <section class="home"></section> 
    <!-- Don't Delete. This is for the background picture ! -->
    <!-- <table class='table table-dark'>
        <thead>
            <tr>
                <th scope="col">Payment Success! Thank you, we hope you will have a great holiday ahead!</th>
            </tr>
        </thead>
    </table> -->
    <h1 font-size= 80 font-family= Perpetua align=center>Payment Success! Thank you, we hope you will have a great holiday ahead!</h1>
</body>