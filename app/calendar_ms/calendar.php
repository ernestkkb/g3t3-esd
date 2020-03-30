<?php


require_once 'common.php';
require_once 'calendarDAO.php';
require_once 'Event.php';
require_once 'ConnectionManager.php';

$calendarDAO = new calendarDAO();

?>
<!DOCTYPE html>
<html>
    <head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <title>Jquery Fullcalandar Integration with PHP and Mysql</title>
        <link rel="stylesheet" href="../css/homepage.css">
  <!-- full calendar stylesheet URL -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.css" />
  
  <!-- bootstrap stylesheet URL -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-alpha.6/css/bootstrap.css" />

  <!-- jquery -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

  <!-- jquery-ui -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

  <!-- parse, validate, manipulate and display dates -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>

  <!-- js lib for full calendar plugin -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.min.js"></script>

  <script>
  $(document).ready(function() {
   var calendar = $('#calendar').fullCalendar({ // activate full calendar plugin under the calendar divison tag at the bottom
    editable:true, // allows drag and resize of elements in the calendar
    header:{
        // display the elements on the left, center and right respectively
     left:'prev,next today', 
     center:'title',
     right:'month,agendaWeek,agendaDay'
    },
    events: 'load.php', // write events option, load data from load.php in feed format to see particular day on this plug-in
    selectable:true, // allow users to highlight mutiple days or event time slot by clicking or dragging cursor
    selectHelper:true, // draw a placeholder while user is dragging any events
    


    select: function(start, end, allDay) // select argument w 3 arguments. We can add more here
    {
     var title = prompt("Enter Event Title"); // Upon selecting the calendar
     if(title)
     {
      var start = $.fullCalendar.formatDate(start, "Y-MM-DD HH:mm:ss");
      var end = $.fullCalendar.formatDate(end, "Y-MM-DD HH:mm:ss");
      $.ajax({ // asynchronus js and XML. Used to send and retrieve data from a server in the background
       url:"insert.php", // send the request to this page
       type:"POST", // communication via HTTP (May need to change to accomodate AMQP)
       data:{title:title, start:start, end:end},
       success:function() // called if request is called successfully
       {
        calendar.fullCalendar('refetchEvents'); // reload event data on the calendar
        alert("Added Successfully"); // pop up message on web page
       }
      })
     }
    },
    editable:true, // allow to edit event data


    // reloading the calendar after resizing.  Updating is the same as inserting
    eventResize:function(event)
    {
     var start = $.fullCalendar.formatDate(event.start, "Y-MM-DD HH:mm:ss");
     var end = $.fullCalendar.formatDate(event.end, "Y-MM-DD HH:mm:ss");
     var title = event.title;
     var id = event.id;
     $.ajax({
      url:"update.php",
      type:"POST",
      data:{title:title, start:start, end:end, id:id},
      success:function(){
       calendar.fullCalendar('refetchEvents');
       alert('Event Update');
      }
     })
    },

    // Drag and drop event from one date to another. Updating is the same as inserting
    eventDrop:function(event)
    {
     var start = $.fullCalendar.formatDate(event.start, "Y-MM-DD HH:mm:ss");
     var end = $.fullCalendar.formatDate(event.end, "Y-MM-DD HH:mm:ss");
     var title = event.title;
     var id = event.id;
     $.ajax({
      url:"update.php",
      type:"POST",
      data:{title:title, start:start, end:end, id:id},
      success:function()
      {
       calendar.fullCalendar('refetchEvents');
       alert("Event Updated");
      }
     });
    },

    eventClick:function(event)
    {
     if(confirm("Are you sure you want to remove it?"))
     {
      var id = event.id;
      $.ajax({
       url:"delete.php",
       type:"POST",
       data:{id:id},
       success:function()
       {
        calendar.fullCalendar('refetchEvents');
        alert("Event Removed");
       }
      })
     }
    },

   });
  });
   
  </script>
 </head>
 <body>
 <nav class="nav">
        <div class="container">
            <div class="logo">
                <a href="#">Welcome back, <?php # echo $user->getField('last_name') ?> </a>
            </div>
            <div id="mainListDiv" class="main_list">
                <ul class="navlinks">
                    <li><a href="../notifications.php">Email</a></li>
                    <li><a href="../payment_ms/payment.php">Payment</a></li>
                    <li><a href="../search_ms/search.php">Start Planning</a></li>
                    <li><a href="calendar.php">Calendar</a></li>
                    <li><a href="../summary.php">Summary</a>
                    <li><a href="../logout.php">Logout</a> <!-- Logout and destroy the session -->
                </ul>
            </div>
        </div>
    </nav>
    <section class="home"></section> <!--Don't Delete. This is for the background picture !-->
    
  <br />
  <h2 align="center"><a href="#">Jquery Fullcalandar Integration with PHP and Mysql</a></h2>
  <br />
  <div class="container">
   <div id="calendar"></div>
  </div>
  
  <?php


$data = $calendarDAO->retrieveAll();

// TODO: Send data over to the scheduler page <add_trip.html>
?>
 </body>
</html>