
<?php
require "../fb-init.php";
if(isset($_SESSION['user'])){
    $user = $_SESSION['user'];

}
else{
    header("Location: ./login.php");
}
$tripID = $_GET['tripID'];
if ($tripID == 'OkjrD6SzQw'){
    $tripName = "United States, New York";
}
elseif ($tripID == 'cPG0LAtqIk'){
    $tripName = "Australia, Melbourne";
}
else{
    $tripName = "Canada, Vancouver";
}
?>

<html>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

<script src="js/scripts.js"></script>
<head>
    
    <link rel="stylesheet" href="../css/homepage.css">
    <link rel="stylesheet" href = "../css/main.css">

</head>
<body>
<!-----------------------------------------------------------[Start] NAVBAR ------------------------------------------------------------------------>
<nav class="nav">
        <div class="container">
            <div class="logo">
                <a href="#">Welcome back, <?php echo $user[1]; ?> </a>
            </div>
            <div id="mainListDiv" class="main_list">
                <ul class="navlinks">
                    <li><a href="../homepage.php">Home</a></li>
                    <li><a href="../notifications.php">Email</a></li>
                    <li><a href="../payment_ms/payment.php">Payment</a></li>
                    <li><a href="../summary.php">Summary</a>
                    <li><a href="./logout.php">Logout</a> <!-- Logout and destroy the session -->
                    
                </ul>
            </div>
        </div>
    </nav>
    <section class="home"></section> <!--Don't Delete. This is for the background picture !-->

    <script>
        $(window).scroll(function() {
            if ($(document).scrollTop() > 50) {
                $('.nav').addClass('affix');
                console.log("OK");
            } else {
                $('.nav').removeClass('affix');
            }
        });
    </script>
<!-----------------------------------------------------------[END] NAVBAR ------------------------------------------------------------------------>


<div style="height: 1000px">
        <!-- just to make scrolling effect possible -->
        
        <table id="summaryTable" class='table100 ver2' style="margin-left:auto;margin-right:auto;" border=1>
            <tr><th colspan = 2> <?php echo $tripName?>  </th></tr>
            <tr class = 'table100 ver2'>
                <th>Day</th>
                <th>Places of Interest</th>
            </tr>
        </thead>
    </table>
    <input type="submit" style="width: 300px; margin: 0 auto;" id="toConfirm" />

    </div>
    <div style="text-align:center">
        <form id="package" method="POST" action="../summary.php">
        <br>
        </br>
        </form>
    </div>


<script> 
    var tripName = '<?php echo $tripName ?>';
    var tripID = '<?php echo $tripID ?>';
    var serviceURL = "http://127.0.0.1:5002/retrieveAllTripID"+"/"+tripID;
    var data = getData(serviceURL);

    async function getData(serviceURL) {
        let requestParam = {
            headers: { "content-type": "charset=UTF-8" },
            mode: 'cors', // allow cross-origin resource sharing
            method: 'GET',
        }
        try {
            const response = await fetch(serviceURL, requestParam);
            data = await response.json();
            var rows = "";
            var dictionaryOfData = {};
            //console.log(data)
            details = data['details']
            //console.log(details)
            for (const poi of details){
                var tripDetails = poi.placeOfInterest.name;
                var day = poi.day;
                //console.log(tripDetails)
                if(!dictionaryOfData[day]){
                    dictionaryOfData[day] = [tripDetails];
                }
                else{
                    dictionaryOfData[day].push(tripDetails);
                }
            }
            for (const day in dictionaryOfData){
                eachRow = "";
                var value = dictionaryOfData[day];
                console.log(value.length);
                eachRow+="<tr><td align = 'center' rowspan='"+value.length + "'>" + String(day) + "</td>"; 
                for(const place in value){
                    eachRow += "<td align = 'center'>" + String(value[place]) + "</td> </tr>";
                }
                eachRow+="</tr>";
                console.log(eachRow)
                $('#summaryTable').append(eachRow);

            }
            // lastRow = "<tr> <td align = 'center' colspan = 2> <button id='toConfirm'>Confirm</button></td></tr>";

            // $('#summaryTable').append(lastRow);



        } catch (error) {
            console.error(error);
        }
    }
    var toConfirmURL = "http://127.0.0.1:5002/addTrip/"+tripID + "/" + '<?php echo $user[0]?>';
    var toPaymentDB = "http://127.0.0.1:5003/payment/"+tripID + "/" + '<?php echo $user[0]?>' + '/20/unpaid';
    $("#toConfirm").click(function(){
        var lol = getDataAnother(toConfirmURL);
        var lol1 = getDataAnother(toPaymentDB);
        alert("You've added your trip to your schedule! Click ok to go back to homepage");
        window.location.replace("../summary.php");

    })

    async function getDataAnother(serviceURL) {
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
</body>

</html>
