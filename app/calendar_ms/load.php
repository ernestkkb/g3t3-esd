<?php

// Remember to run the calendarDB.sql script first to establish the DB


$connect = new PDO('mysql:host=localhost;dbname=calendarDB', 'root', '');

$data = array(); 

$query = "SELECT * FROM events ORDER BY id";

$statement = $connect->prepare($query);

$statement->execute();

$result = $statement->fetchAll();

foreach($result as $row)
{
 $data[] = array(
  'id'   => $row["id"],
  'title'   => $row["title"],
  'start'   => $row["start_event"],
  'end'   => $row["end_event"]
 );
}

echo json_encode($data); # convert data into string format and display onto calendar plug-in

?>
