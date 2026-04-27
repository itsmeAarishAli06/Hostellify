<?php
    // Database Credentials (Change these ONLY here when hosting)
    define('DB_SERVER', 'localhost'); // Usually localhost on free hosts
    define('DB_USERNAME', 'root');    // Your hosting DB user
    define('DB_PASSWORD', '');        // Your hosting DB password
    define('DB_NAME', 'hostel');      // Your hosting DB name

    // Create connection
    $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
?>
