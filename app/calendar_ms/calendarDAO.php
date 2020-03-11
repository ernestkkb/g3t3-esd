
<?php

require_once 'common.php';

class calendarDAO {

// Retrieve a row from table grade where email == $email
// If no matching row is found, return null
public function retrieveAll() {

    // Step 1 - Connect to Database
    $connMgr = new ConnectionManager();
    $pdo = $connMgr->getConnection();
    // Step 2 - Prepare SQL
    $sql = "SELECT 
                * 
            FROM 
                events";
    $stmt = $pdo->prepare($sql);

    // Step 3 - Execute SQL
    
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);

    // STEP 4
    $events = []; // Indexed Array of Post objects
    while( $row = $stmt->fetch() ) {
        $events[] =
            new Event(
                $row['id'],
                $row['title'],
                $row['start_event'],
                $row['end_event']);
    }

    // STEP 5
    $stmt = null;
    $conn = null;

    // STEP 6
    return $events;
    }
}

?>