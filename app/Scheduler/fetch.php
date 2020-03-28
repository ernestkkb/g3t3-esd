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
            <button name='add' value='Add' id='add'>Add</button>
            <br>
    </form> 
    
    <input type='submit' name='confirm' value='Confirm' id='confirm'>
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

        var city = 'Singapore';
        var serviceURL = "http://127.0.0.1:5008/city"+"/"+city;
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

                // var test = "<input type='text' id='test'>"
                // var test2 = "<button name='add' value='Add' id='test2'>Add</button><br>"
                // $('#POITable').append(test);
                // $('#POITable').append(test2);

                //console.log(places_dict);


            } catch (error) {
                console.error(error);
            }
        }
    
        $(document).ready(function() {
            $('#add').click(function() {
                event.preventDefault();
                var day = $('#day').val();
                var poino = $('#poino').val();
                var details = places_dict[poino];
                var name_poi = details[0];
                //console.log(name_poi);
                var address_poi = details[1];
                //console.log(address_poi);
                // data to send over to scheduler: name, address, day
                var addpoiURL = "http://127.0.0.1:5008/addPOI"+"/"+ day; 
            });
        });







</script>




</body>

</html>