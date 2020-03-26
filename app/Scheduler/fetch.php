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
                <th>Day Chosen</th>
            </tr>
        </thead>
    </table>

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
        //         console.log(data);
                var rows = "";
                rowcounts = data.length;
                counter = 1;
                var places_dict = {};
                for (const poi of data){
                    places_dict[counter] = poi;
                    if (counter == 1){
                        row = 
                            "<tr><td>" + counter + "</td>" + 
                            "<td>" + poi[0] + "</td>" +
                            "<td>" + poi[1] + "</td>" +
                            "<td>" + "<img src =" + "'" +  poi[2] + "'>" + "</td>" +
                            "<td>" + poi[3] + "</td>" + 
                            "<td rowspan=" + '"' + rowcounts + '"' + ">" + 
                                "<form method = 'POST' id = 'POIform'>POI S.N.:<input type ='text' name='poino' id = 'poino'/><br>" + 
                                "DAY CHOSEN:<input type ='text' name='day'/>" + 
                                "<button name='add' value='Add' id='add'>Add</button><br></form>" + 
                                "<input type='submit' name='confirm' value='Confirm' id='confirm'></td></tr>";
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

                // var test = "<input type='text' id='test'>"
                // var test2 = "<button name='add' value='Add' id='test2'>Add</button><br>"
                // $('#POITable').append(test);
                // $('#POITable').append(test2);

                // add all the rows to the table
                $('#POITable').append(rows);

                //console.log(places_dict);


            } catch (error) {
                console.error(error);
            }
        }
    
        $('#add').click(function() {
                alert("LOL");
                console.log("HELLO");
                console.log($("#poino").val());
                var $inputs = $('#POIform : input');
                console.log($inputs);
                var values = {};
                $inputs.each(function() {
                    values[this.name] = $(this).val();
                })
        });

        // var addpoiURL = "http://127.0.0.1:5008/addPOI"+"/"+day;
        // var data = addPoi(addpoiURL);

        // function addPoi(addpoiURL){
        //     // 1. manipulate the poino received to get name, address
        //     // 2. send to scheduler.py the name, address, day 
        // }


    // // $('#add').click(console.log('hello'));
    // $('#add').click(async (event) => {
    //     event.preventDefault();
    //     var day = $('#day').val();
    //     var serviceURL = "http://127.0.0.1:5002/addPOI/" + day;
        
    //     var poino = $('#poino').val();
    //     var places = places_dict[poino];
    //     var name = places[0];
    //     var address = places[1];

    //     console.log(places);
    //     try {
    //         const response = 
    //             await fetch(
    //                 serviceURL, {
    //                 method: 'POST',
    //                 headers: {'Content-Type': "application/json"},
    //                 body: JSON.stringify({ name: name, address: address})
    //             })
    //         const data = await response.json();
    //         if (response.ok) {
    //             console.log("Success");
    //         } else {
    //             console.log("Fail");
    //         }
    //     } catch (error) {
    //         console.log(error);
    //     }
    // }); 



</script>




</body>

</html>