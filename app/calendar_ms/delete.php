<?php

// Remember to run the calendarDB.sql script first to establish the DB

if(isset($_POST["id"]))
{
 $connect = new PDO('mysql:host=localhost;dbname=calendarDB', 'root', '');
 $query = "
 DELETE from events WHERE id=:id
 ";
 $statement = $connect->prepare($query);
 $statement->execute(
  array(
   ':id' => $_POST['id']
  )
 );
}

?>