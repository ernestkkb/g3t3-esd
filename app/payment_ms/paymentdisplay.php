<?php
    if(isset($_GET['tripName'])&& isset($_GET['tripID'])){
        $tripName=$_GET['tripName'];
        $tripID=$_GET['tripID'];
    }

?>
<html lang="en">
    <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Check Out</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <style type="text/css">
        .bs-example{
            margin: 20px;
        }
    </style>
    </head>
    <body>
        <div class="bs-example">
            <table class="table table-dark">
                <thead>
                    <tr>
                        <th scope="#">#</th>
                        <th scope="col">Name of Trip</th>
                        <th scope="col">Price</th>
                        <th scope="col">Quantity</th>
                        <th scope="col">Checkout</th>
                    </tr>
                </thead>
                <tbody>  
                    <tr>
                        <th scope="row">1</th>
                        <td><?php echo $tripName ?></td>
                        <td><?php echo "$20" ?></td>
                        <td><?php echo "1" ?></td>
                        <td>

                <div id="paypal-button"></div>
                <script src='https://www.paypalobjects.com/api/checkout.js'></script>
                            <script>
                                
                                var CREATE_PAYMENT_URL  = 'http://127.0.0.1:5003/makepayment';
                                var EXECUTE_PAYMENT_URL = 'http://127.0.0.1:5003/execute';

                                var tripName= "<?php echo $tripName ?>";
                                var tripID= "<?php echo $tripID ?>";

                                var triplist=[{
                                'name': tripName,
                                'sku':  tripID,
                                'price': '20',
                                'currency': "USD",
                                'quantity': 1}];
                            
                                paypal.Button.render({

                                    env: 'sandbox', // Or 'sandbox'

                                    commit: true, // Show a 'Pay Now' button

                                    payment: function() {
                                        return paypal.request({ method: 'post', url: CREATE_PAYMENT_URL,json: triplist }).then(function(data) {
                                            console.log("Hello");
                                            return data.paymentID;
                                    })
                                },
                                    onAuthorize: function(data) {
                                            return paypal.request({method: 'post', url:EXECUTE_PAYMENT_URL, json: {
                                                paymentID: data.paymentID,
                                                payerID:   data.payerID
                                            }}).then(function(res) {
                                                console.log(res.success)
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
    </body>
</html>