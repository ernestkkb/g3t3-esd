<?php

# THIS FILE IS REFERENCE FROM WAD. WILL NEED TO MAKE ADJUSTMENTS BEFORE USE

class ConnectionManager {

    public function getConnection() {
        $servername = 'localhost';
        $username = 'root';
        $password = '';
        $dbname = 'session_test';
        
        // Create connection
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);     
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // if fail, exception will be thrown

        // Return connection object
        return $conn;
    }

}