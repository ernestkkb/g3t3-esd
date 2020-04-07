
<?php
    require_once "../fb-init.php";
    if(isset($_SESSION['user'])){
        $user = $_SESSION['user'];
    }
    else{
        header("Location: login.php");
    }

    if(isset($_GET['tripName'])&& isset($_GET['tripID'])){
        $tripName=$_GET['tripName'];
        $tripID=$_GET['tripID'];
        $checkout="true";
        $_SESSION['tripID'] =$tripID;
    }else{
        $checkout="false";
    }
?>
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

<!-----------------------------------------------------------[START] NAVBAR ------------------------------------------------------------------------>
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
                            echo "User";
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

<!-----------------------------------------------------------[END] NAVBAR ------------------------------------------------------------------------>
<!-----------------------------------------------------------[START] PAYPAL ------------------------------------------------------------------------>
<link rel="stylesheet" href="../css/homepage.css">
<link rel="stylesheet" href = "../css/main.css">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
<style type="text/css">

        .bs-example{
            margin: 20px;
        }
    </style>
<!-- Starting of the HTML BODY -->
<!DOCTYPE html>

<html>
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../css/homepage.css">
    <link rel="stylesheet" href = "../css/main.css">

</head>
<body>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <div style="height: 1000px">
    <h2 class="myH2">Checkout</h2>
    <div class="bs-example">
    <script>
                $(document).ready(function(){
                    var checkout1 = '<?php echo $checkout; ?>';
                    if(checkout1==="false"){
                        $("#paypal").hide();

                    }
                });

    </script>
            <table id='paypal' class='table100 ver2'>
                <thead>
                    <tr class="table table-dark" align="center">
                        <th scope="#"><font color="white">#</font></th>
                        <th scope="col"><font color="white">Name of Trip</font></th>
                        <th scope="col"><font color="white">Price</font></th>
                        <th scope="col"><font color="white">Quantity</font></th>
                        <th scope="col"><font color="white">Checkout</font></th>
                    </tr>
                </thead>
                <tbody>  
                    <tr align="center">
                        <th scope="row">1</th>
                        <td><?php echo $tripName ?></td>
                        <td><?php echo "$20" ?></td>
                        <td><?php echo "1" ?></td>
                        <td>

                <div id="paypal-button"></div>
                <script src='https://www.paypalobjects.com/api/checkout.js'></script>
                            <script>
                                
                                var CREATE_PAYMENT_URL  = 'https://g3t3-payment.herokuapp.com/makepayment';
                                var EXECUTE_PAYMENT_URL = 'https://g3t3-payment.herokuapp.com/execute';

                                var tripName= "<?php echo $tripName ?>";
                                var tripID= "<?php echo $tripID ?>";

                                var triplist=[{
                                'name': tripName,
                                'sku':  tripID,
                                'price': '20',
                                'currency': "SGD",
                                'quantity': 1}];

                                    paypal.Button.render({


                                    env: 'sandbox', // Or 'sandbox'

                                    commit: true, // Show a 'Pay Now' button

                                    payment: function() {
                                        return paypal.request({ method: 'post', url: CREATE_PAYMENT_URL, json: triplist}).then(function(data) {
                                            return data.paymentID;
                                    })
                                },
                                    onAuthorize: function(data) {
                                            var dummy={paymentID: data.paymentID, payerID: data.payerID};
                                            return paypal.request({method: 'post', url: EXECUTE_PAYMENT_URL, json: dummy
                                            }).then(function(res) {
                                                if(res.success==true){
                                                    alert("Payment Success! Thank you, we hope you will have a great holiday ahead!");
                                                    window.location.href = "../notifications.php";
                                                }
                                                // The payment is complete!
                                                // You can now show a confirmation message to the customer       
                                            });
                                        }
                                    }, '#paypal-button');
                            </script>
                        </td>
                    </tr>
            </tbody>
        </table>
    </div>
    <!-----------------------------------------------------------[END] PAYPAL ------------------------------------------------------------------------>
    <!-----------------------------------------------------------[START] Payment Transaction History ------------------------------------------------------------------------>
    <div style="height: 1000px">
        <!-- just to make scrolling effect possible -->
        <h2 class="myH2">Your payment history</h2>
    
        <table id="paymentTable" class='table100 ver2'   style="text-align: center;" border=1>
            <tr class="table table-dark" align="center">
                <th><font color="white">Trip ID</font></th>
                <th><font color="white">Price</font></th>
                <th><font color="white">Payment Status</font></th>
            </tr>
        </thead>
    </table>

    </div>

<!-- Jquery needed -->
    <script src="js/scripts.js"></script>

<!-- Function used to shrink nav bar removing paddings and adding black background -->
    <script>

        
        var facebookID = '<?php echo $user[0]?>';
        var serviceURL = "https://g3t3-payment.herokuapp.com/paymentHistory/" + facebookID;
        // var serviceURL = "http://127.0.0.1:5003/payment";
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
                data = items.paymentHistory;
    
                // var books = data.books; //the arr is in data.books of the JSON data
                // console.log(data)
                // array or array.length are falsy
                if (!data) {
                    console.log('Payment History list empty or undefined.')
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
<!-----------------------------------------------------------[END] Payment Transaction History ------------------------------------------------------------------------>
</body>
</html>