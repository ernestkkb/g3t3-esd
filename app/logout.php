<?php

session_destroy();
unset($_SESSION['access_token']);


?>

<script>
var serviceURLScheduler = "https://g3t3-scheduler.herokuapp.com/deleteAll";
var serviceURLPayment = "https://g3t3-payment.herokuapp.com/deleteAll";
getData(serviceURLPayment).then(function(value){
                getData(serviceURLScheduler).then(function(value){
                    window.location.href = "https://g3t3-ui.herokuapp.com/app/login.php";
                });
            });
            
async function getData(serviceURL) {
            let requestParam = {
                headers: {"content-type": "charset=UTF-8"},
                mode: 'cors',
                method: 'GET',
            }

            try {
                const response = await fetch(serviceURL, requestParam);
                data = await response.json();
                return data
            } catch (error) {
                console.error(error);
            }
        }

</script>
