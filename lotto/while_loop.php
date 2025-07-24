<?php
// File: mysql_read_while.php

function readUntilConditionMet($host, $user, $password, $database, $conditionCallback) {
    // Connect to the MySQL database
    $conn = new mysqli($host, $user, $password, $database);

    // Check for connection errors
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // SQL query to fetch data from the table
    $query = "SELECT * FROM your_table_name"; // Replace with your table name
    $result = $conn->query($query);

    // Check if query was successful
    if (!$result) {
        die("Query failed: " . $conn->error);
    }

    // Loop through rows using while
    while ($row = $result->fetch_assoc()) {
        // Process the current row
        print_r($row); // Replace with your processing logic

        // Check if the condition is met
        if ($conditionCallback($row)) {
            echo "Condition met. Stopping loop.\n";
            break;
        }
    }

    // Clean up
    $result->free();
    $conn->close();
}

// Example usage
readUntilConditionMet(
    "localhost",          // MySQL host
    "root",               // MySQL user
    "yourpassword",       // MySQL password
    "yourdatabase",       // MySQL database
    function($row) {      // Condition callback
        // Example condition: Stop if 'specific_column' equals 100
        return $row['specific_column'] == 100; // Replace with your logic
    }
);
?>
