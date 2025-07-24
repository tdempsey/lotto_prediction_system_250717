<?php
// Georgia Fantasy 5 Lottery Data Import Script
// Configuration
$game = 1; // Georgia Fantasy 5
$debug = 1; // Set to 0 for production

// Error reporting setup
if ($debug) {
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);
}

// Include required files
require($_SERVER['DOCUMENT_ROOT'].'/lotto/includes/games_switch.incl');

// Database connection
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'ga_f5_lotto';

$mysqli_link = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
if (!$mysqli_link) {
    die('Connect Error (' . mysqli_connect_errno() . ') ' . mysqli_connect_error());
}
echo 'Database connection successful... ' . mysqli_get_host_info($mysqli_link) . "<br>\n";

// File processing
$filename = 'C:\wamp64\www\lotto\gaf5_170615.txt';
echo "Processing file: $filename<br>";

if (!file_exists($filename)) {
    die("Error: Input file not found");
}

$contents = file_get_contents($filename);
if ($contents === false) {
    die("Error: Unable to read input file");
}

// Extract dates and numbers using regex
preg_match_all("(\d{2}\/\d{2}\/\d{4})", $contents, $matches_date);
preg_match_all("(\d{2} \d{2} \d{2} \d{2} \d{2})", $contents, $matches_nums);

$size_date = count($matches_date[0]);
echo "Found $size_date drawing records<br>";

// Prepare SQL statement for bulk insertion
$values_to_insert = [];
$records_added = 0;
$records_skipped = 0;

// Process each drawing
for ($x = 0; $x < $size_date; $x++) {
    // Format date (MM/DD/YYYY to YYYY-MM-DD)
    $date_parts = explode("/", $matches_date[0][$x]);
    $formatted_date = "{$date_parts[2]}-{$date_parts[0]}-{$date_parts[1]}";
    
    // Get lottery numbers
    $draws = explode(" ", $matches_nums[0][$x]);
    
    // Display current processing item
    echo "Processing: $formatted_date - {$draws[0]}-{$draws[1]}-{$draws[2]}-{$draws[3]}-{$draws[4]}<br>";
    
    // Check if this draw already exists in the database
    $query = "SELECT * FROM $draw_table_name WHERE date = '$formatted_date'";
    $result = mysqli_query($mysqli_link, $query);
    
    if (!$result) {
        echo "Query error: " . mysqli_error($mysqli_link) . "<br>";
        continue;
    }
    
    if (mysqli_num_rows($result) > 0) {
        echo "Draw for $formatted_date already exists in database - skipping<br>";
        $records_skipped++;
        continue;
    }
    
    // Prepare insert query - with proper validation
    if (count($draws) == 5 && 
        is_numeric($draws[0]) && is_numeric($draws[1]) && 
        is_numeric($draws[2]) && is_numeric($draws[3]) && 
        is_numeric($draws[4])) {
        
        $b1 = (int)$draws[0];
        $b2 = (int)$draws[1];
        $b3 = (int)$draws[2];
        $b4 = (int)$draws[3];
        $b5 = (int)$draws[4];
        
        $query_insert = "INSERT INTO `$draw_table_name` 
            (`date`, `b1`, `b2`, `b3`, `b4`, `b5`) VALUES 
            ('$formatted_date', $b1, $b2, $b3, $b4, $b5)";
            
        if ($debug) {
            echo "SQL: $query_insert<br>";
        }
        
        $result_insert = mysqli_query($mysqli_link, $query_insert);
        
        if ($result_insert) {
            echo "Successfully added draw for $formatted_date<br>";
            $records_added++;
        } else {
            echo "Error adding draw: " . mysqli_error($mysqli_link) . "<br>";
        }
    } else {
        echo "Invalid drawing data format for date $formatted_date - skipping<br>";
    }
}

// Summary
echo "<h3>Import Summary</h3>";
echo "Total records processed: $size_date<br>";
echo "Records added: $records_added<br>";
echo "Records skipped (already exist): $records_skipped<br>";

// Close the database connection
mysqli_close($mysqli_link);
echo "Database connection closed.";
?>