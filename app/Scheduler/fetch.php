<?php  
    require "../fb-init.php";
    if(isset($_SESSION['user'])){
        $user = $_SESSION['user'];
    
    }
    else{
        header("Location: ./login.php");
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
    <script
    src="https://code.jquery.com/jquery-3.4.1.js"
    integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU="
    crossorigin="anonymous"></script>
    <script src="push.js"></script>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
</head>

<body>
    <p id="display">Display POI</p>
    <p>Trip Name: <?php echo $_POST['tripName'] ?></p>
    <br><br>
    <table id="POITable" class='table table-striped' border='1'>
        <thead class='thead-dark'>
            <tr>
                <th>POI S.N.</th>
                <th>Name</th>
                <th>Address</th>
                <th>Photos</th>
                <th>Rating</th>
            </tr>
        </thead>
    </table>
    <form method='GET' id='POIform'>
            POI S.N.:<input type ='text' name='poino' id = 'poino'/>
            <br>
            DAY CHOSEN:<input type ='text' name='day' id = 'day'/>
            <br>
            <button name='add' value='Add' id='add' class="btn btn-primary">Add POI</button>
            <br>
    </form> 
    <br>
    <button name='confirm' value='Confirm' id='confirm' class="btn btn-primary">Add Trip</button>
<script>
        // Helper function to display error message
        function showError(message) {
            // Hide the table and button in the event of error
            $('#POITable').hide();
            $('#addTripBtn').hide();
    
            // Display an error under the main container
            $('#main-container')
                .append("<label>"+message+"</label>");
        }
        function makeid(length) {
        var result           = '';
        var characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        var charactersLength = characters.length;
        for ( var i = 0; i < length; i++ ) {
            result += characters.charAt(Math.floor(Math.random() * charactersLength));
        }
        return result;
        }
        // anonymous async function 
        // - using await requires the function that calls it to be async

        var city = '<?php echo $_POST['city'] ?>';
        var tripName = '<?php echo $_POST['tripName'] ?>';
        var serviceURL = "http://127.0.0.1:5008/city"+"/"+city;
        var tripID = makeid(10);
        var data = getData(serviceURL);
        var places_dict = {};
        
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
                console.log(data)
                rowcounts = data.length;
                counter = 1;
                for (const poi of data){
                    places_dict[counter] = poi;
                    eachRow =
                        "<td>" + counter + "</td>" +
                        "<td>" + poi[0] + "</td>" +
                        "<td>" + poi[1] + "</td>" +
                        "<td>" + "<img src =" + "'" +  poi[2] + "'>" + "</td>" +
                        "<td>" + poi[3] + "</td>";
                    rows += "<tr>" + eachRow + "</tr>";
                    counter += 1;
                }
                
                // add all the rows to the table
                $('#POITable').append(rows);


            } catch (error) {
                console.error(error);
            }
        }
    
        $('#add').click(async() => {
            var facebookID = '<?php echo $user[0];?>';
            console.log(facebookID);
            event.preventDefault();
            var day = $('#day').val();
            var poino = $('#poino').val();
            var details = places_dict[poino];
            var id = makeid(10);
            var poi_dict = {};
            var name_poi = details[0];
            //console.log(name_poi);
            var address_poi = details[1];
            poi_dict['name'] = name_poi;
            poi_dict['address'] = address_poi;
            var data = {"tripID":tripID, "tripName": tripName,"facebookID":facebookID, "placeOfInterest":poi_dict, "paymentStatus":"paid", "day":day, "id":id};
            // data to send over to scheduler: name, address, day
            var addpoiURL = "http://127.0.0.1:5002/addPOI";
            await fetch(
                addpoiURL, {
                method: 'POST',
                mode: 'cors',
                headers: { "Content-Type": "application/json"},
                body: JSON.stringify(data)
            });
        });

        $("#add").click(function(){
            Push.create("POI Added!",{
            body: "You have added your places of interest for the day.",
            icon: 'Logo_small.png',
            timeout: 2000,
            onClick: function () {
                window.focus();
                this.close();
            }
            });
        });

        $('#confirm').click(async() => {
            event.preventDefault();
            var facebookID = '<?php echo $user[0];?>';
            var price = '20';
            var paymentStatus = "unpaid";
            var URLtoAdd = "http://127.0.0.1:5003/payment/"+tripID+"/"+facebookID+"/"+price+"/"+paymentStatus;
            addTripToDB(URLtoAdd);
            alert(URLtoAdd);
            window.location.href = "../summary.php";
        
        });
        async function addTripToDB(serviceURL) {
            let requestParam = {
                headers: { "content-type": "charset=UTF-8" },
                mode: 'cors', // allow cross-origin resource sharing
                method: 'GET',
            }
            try {
                const response = await fetch(serviceURL, requestParam);
                data = await response.json();
                alert(serviceURL);
                console.log(data);
        }catch (error) {
                console.error(error);
            }
        }

</script>
</body>

</html>