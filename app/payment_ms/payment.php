<?php  
    require "../fb-init.php";
    if(isset($_SESSION['user'])){
        $user = $_SESSION['user'];
    }
    else{
        header("Location: login.php");
    }
?>

<?php # require "fb-init.php";?>

<?php # if (isset($_SESSION['access_token'])): ?>
    
<?php # else: ?>
    <!-- <a href="<?php # echo $login_url;?>">Login With Facebook</a> -->
<?php # endif; ?>

<?php
// if (isset($_SESSION['access_token'])) {

//     try {
//       $fb->setDefaultAccesstoken($_SESSION['access_token']);

//       $response = $fb->get('/me?fields=id,name,picture,last_name', $_SESSION['access_token']);
//       $user = $response->getGraphUser();

//     } catch (Exception $e) {
//       echo $e->getTraceAsString();
//       header("Location: ./logout.php");
//     }
//   }
?>


<!-- Up until here. If validation is unsuccessful, redirected to logout page, session destroyed and redirected to login page !-->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

<!-- Starting of the HTML BODY -->
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="../css/homepage.css">
    <link rel="stylesheet" href = "../css/main.css">

</head>
<body>

<nav class="nav">
        <div class="container">
            <div class="logo">
                <a href="#">Welcome back, <?php echo $user[1]; ?> </a>
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


    <div style="height: 1000px">
        <!-- just to make scrolling effect possible -->
        <h2 class="myH2">Your payment history</h2>
    
        <table id="paymentTable" class='table100 ver2' style="margin-left:auto;margin-right:auto;" border=1>
            <tr class = 'table100 ver2'>
                <th>Trip ID</th>
                <th>Price</th>
                <th>Payment Status</th>
            </tr>
        </thead>
    </table>

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
        
        var facebookID = '<?php echo $user[0]?>';
        // var serviceURL = "http://127.0.0.1:5003/paymentHistory/"+facebookID;
        var serviceURL = "http://127.0.0.1:5003/payment";
        var data = getData(serviceURL);
        async function getData(serviceURL) {
            let requestParam = {
                headers: { "content-type": "charset=UTF-8" },
                mode: 'cors', // allow cross-origin resource sharing
                method: 'GET',
            }
            try {   
                const response = await fetch(serviceURL, requestParam);
                items = await response.json();
                data = items.payment_process;
    
                // var books = data.books; //the arr is in data.books of the JSON data
                console.log(data)
                // array or array.length are falsy
                if (!data) {
                    showError('Payment History list empty or undefined.')
                } else {
                    $("#paymentTable td").remove(); 

                    for (const poi of data){
                        var rows = "";
                            eachRow =
                                "<td>" + poi.tripID + "</td>" +
                                "<td>" + poi.price + "</td>" +
                                "<td>" + poi.paymentStatus + "</td>";
                            rows += "<tbody><tr>" + eachRow + "</tr></tbody>";
                    
                    
                    // add all the rows to the table
                    $('#paymentTable').append(rows);
                    }
                    //#paymentTable tbody: select unique table & a particular body (‘tbody’)
                    ///$(‘tbody’).append(rows) //only works if there is only one table
                    //The <tbody> tag is used to group the body content in an HTML table. 
                    // The <tbody> element is used in conjunction with the <thead> and <tfoot> 
                    // elements to specify each part of a table (body, header, footer). 
                    // Browsers can use these elements to enable scrolling of the table body independently of the header and footer.
                }

            } catch (error) {
                console.error(error);               
            } // error
        };
    </script>
    

</body>
</html>