<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
</head>

<body>
    <p id="display">Display POI</p>
    <form method="POST" action=>
        <table id="POITable" class='table table-striped' border='1'>
            <thead class='thead-dark'>
                <tr>
                    <th>POI S.N.</th>
                    <th>Name</th>
                    <th>Address</th>
                    <th>Photos</th>
                    <th>Rating</th>
                    <th>Day Chosen</th>
                </tr>
            </thead>
        </table>
    </form> 

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
    
        // anonymous async function 
        // - using await requires the function that calls it to be async
        var city = '<?php echo$_GET['city'];?>';
        var days = '<?php echo$_GET['days'];?>';
        var serviceURL = "http://127.0.0.1:5008/city"+"/"+city;
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
                console.log(data);
                var rows = "";
                rowcounts = data.length;
                counter = 1;
                for (const poi of data){
                    if (counter == 1){
                        row = 
                            "<tr><td>" + counter + "</td>" + 
                            "<td>" + poi[0] + "</td>" +
                            "<td>" + poi[1] + "</td>" +
                            "<td>" + "<img src =" + "'" +  poi[2] + "'>" + "</td>" +
                            "<td>" + poi[3] + "</td>" + 
                            "<td rowspan=" + '"' + rowcounts + '"' + ">" + 
                                "POI S.N.:<input type ='text' name = 'poino'/><br>" + 
                                "DAY CHOSEN:<input type ='text' name = 'day'/>" + 
                                "<input type='submit' name='add' value='Add'><br>" + 
                                "<input type='submit' name='confirm' value='Confirm'></td></tr>";
                        $('#POITable').append(row);
                        counter += 1;
                    }
                    else{
                        eachRow =
                            "<td>" + counter + "</td>" +
                            "<td>" + poi[0] + "</td>" +
                            "<td>" + poi[1] + "</td>" +
                            "<td>" + "<img src =" + "'" +  poi[2] + "'>" + "</td>" +
                            "<td>" + poi[3] + "</td>";
                        rows += "<tr>" + eachRow + "</tr>";
                        counter += 1;
                    }
                }
                // add all the rows to the table
                $('#POITable').append(rows);
                
            

            } catch (error) {
                console.error(error);
            }
        }

</script>

<script>
    var add_to_db = function() {
        var serviceURL = "http://127.0.0.1:5002/addPOI/"+ poino + "/" + day;
        var day = 
        
    }
</script>




</body>

</html>