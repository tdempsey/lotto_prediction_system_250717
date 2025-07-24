<?php
// Georgia Fantasy 5 Lottery Data Scraper
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

/**
 * IMPORTANT NOTE ABOUT WEB SCRAPING:
 * 
 * Direct scraping of the Georgia Lottery website may not be reliable because:
 * 1. The website likely uses JavaScript to load the lottery data dynamically
 * 2. The website may have anti-scraping measures in place
 * 3. The structure of the website could change frequently
 * 
 * A more reliable approach is to:
 * - Check if the Georgia Lottery offers an official API
 * - Use a proper web scraping tool like Puppeteer (PHP+Node.js) or Selenium
 * - Set up a scheduled task with proper browser automation
 * 
 * For now, this script demonstrates an alternative approach by:
 * 1. Downloading data from the Georgia Lottery's data feed if available
 * 2. Providing a fallback to manual data entry if the feed doesn't work
 */

// Records tracking
$records_added = 0;
$records_skipped = 0;
$records_processed = 0;

// Method 1: Try to access a potential data feed URL
// Note: This URL might not exist or might be different
$dataUrl = 'https://www.galottery.com/api/draw-games/fantasy-5/draws';
echo "Attempting to fetch data from potential API: $dataUrl<br>";

$options = [
    'http' => [
        'method' => 'GET',
        'header' => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36\r\n" .
                    "Accept: application/json\r\n"
    ]
];

$context = stream_context_create($options);
$jsonData = @file_get_contents($dataUrl, false, $context);

// Process data if successfully retrieved
if ($jsonData !== false) {
    echo "Successfully retrieved data from API.<br>";
    
    // Parse the JSON data
    $drawData = json_decode($jsonData, true);
    
    if (json_last_error() === JSON_ERROR_NONE && is_array($drawData)) {
        // Process the JSON data - structure will depend on the actual API
        processAPIData($drawData, $mysqli_link, $draw_table_name);
    } else {
        echo "Error parsing JSON data: " . json_last_error_msg() . "<br>";
    }
} else {
    echo "Could not fetch data from API. Switching to manual data entry mode.<br>";
    
    // Method 2: Manual data entry interface
    displayManualEntryForm($mysqli_link, $draw_table_name);
}

// Summary
echo "<h3>Import Summary</h3>";
echo "Total records processed: $records_processed<br>";
echo "Records added: $records_added<br>";
echo "Records skipped (already exist): $records_skipped<br>";

// Close the database connection
mysqli_close($mysqli_link);
echo "Database connection closed.";

/**
 * Process data from the API
 */
function processAPIData($data, $mysqli_link, $draw_table_name) {
    global $records_processed, $records_added, $records_skipped, $debug;
    
    // This function needs to be adapted based on the actual API response structure
    // For now, we'll assume a structure like: [{"drawDate": "MM/DD/YYYY", "numbers": [1,2,3,4,5]}, ...]
    
    if (isset($data['draws']) && is_array($data['draws'])) {
        $draws = $data['draws'];
    } else if (is_array($data)) {
        $draws = $data; // Assume the data itself is the array of draws
    } else {
        echo "Unknown API data structure. Cannot process.<br>";
        return;
    }
    
    echo "Found " . count($draws) . " draws to process.<br>";
    
    foreach ($draws as $draw) {
        $records_processed++;
        
        // Extract date - adapt this based on actual API format
        if (isset($draw['drawDate'])) {
            $dateText = $draw['drawDate'];
        } else if (isset($draw['date'])) {
            $dateText = $draw['date'];
        } else {
            echo "Draw #$records_processed: No date found - skipping<br>";
            continue;
        }
        
        // Format date to YYYY-MM-DD
        $date = date_create_from_format('m/d/Y', $dateText);
        if (!$date) {
            // Try alternate format
            $date = date_create_from_format('Y-m-d', $dateText);
        }
        
        if (!$date) {
            echo "Draw #$records_processed: Invalid date format: $dateText - skipping<br>";
            continue;
        }
        
        $formatted_date = $date->format('Y-m-d');
        
        // Extract numbers - adapt this based on actual API format
        if (isset($draw['numbers']) && is_array($draw['numbers'])) {
            $numbers = $draw['numbers'];
        } else if (isset($draw['b1']) && isset($draw['b5'])) {
            $numbers = [$draw['b1'], $draw['b2'], $draw['b3'], $draw['b4'], $draw['b5']];
        } else {
            echo "Draw #$records_processed: No numbers found - skipping<br>";
            continue;
        }
        
        if (count($numbers) !== 5) {
            echo "Draw #$records_processed: Expected 5 numbers, found " . count($numbers) . " - skipping<br>";
            continue;
        }
        
        insertDrawIntoDatabase($mysqli_link, $draw_table_name, $formatted_date, $numbers);
    }
}

/**
 * Display a form for manual data entry
 */
function displayManualEntryForm($mysqli_link, $draw_table_name) {
    echo <<<HTML
    <h2>Manual Fantasy 5 Draw Entry</h2>
    <p>The automatic data feed could not be accessed. Please enter the draw data manually:</p>
    
    <form method="POST" action="">
        <div>
            <label for="draw_date">Draw Date (MM/DD/YYYY):</label>
            <input type="text" id="draw_date" name="draw_date" required pattern="^\d{2}/\d{2}/\d{4}$" placeholder="MM/DD/YYYY">
        </div>
        <div>
            <label for="ball1">Ball 1 (1-42):</label>
            <input type="number" id="ball1" name="ball1" min="1" max="39" required>
        </div>
        <div>
            <label for="ball2">Ball 2 (1-42):</label>
            <input type="number" id="ball2" name="ball2" min="1" max="39" required>
        </div>
        <div>
            <label for="ball3">Ball 3 (1-42):</label>
            <input type="number" id="ball3" name="ball3" min="1" max="39" required>
        </div>
        <div>
            <label for="ball4">Ball 4 (1-42):</label>
            <input type="number" id="ball4" name="ball4" min="1" max="39" required>
        </div>
        <div>
            <label for="ball5">Ball 5 (1-42):</label>
            <input type="number" id="ball5" name="ball5" min="1" max="39" required>
        </div>
        <div>
            <input type="submit" name="submit_draw" value="Add Draw">
        </div>
    </form>
HTML;

    // Process form submission
    if (isset($_POST['submit_draw'])) {
        $draw_date = $_POST['draw_date'];
        $numbers = [
            (int)$_POST['ball1'],
            (int)$_POST['ball2'],
            (int)$_POST['ball3'],
            (int)$_POST['ball4'],
            (int)$_POST['ball5']
        ];
        
        // Convert MM/DD/YYYY to YYYY-MM-DD
        $date_parts = explode('/', $draw_date);
        if (count($date_parts) === 3) {
            $formatted_date = $date_parts[2] . '-' . $date_parts[0] . '-' . $date_parts[1];
            insertDrawIntoDatabase($mysqli_link, $draw_table_name, $formatted_date, $numbers);
        } else {
            echo "Invalid date format. Please use MM/DD/YYYY format.<br>";
        }
    }
}

/**
 * Insert a draw into the database
 */
function insertDrawIntoDatabase($mysqli_link, $draw_table_name, $formatted_date, $numbers) {
    global $records_added, $records_skipped, $debug;
    
    echo "Processing: $formatted_date - " . implode('-', $numbers) . "<br>";
    
    // Check if this draw already exists in the database
    $query = "SELECT * FROM $draw_table_name WHERE date = '$formatted_date'";
    $result = mysqli_query($mysqli_link, $query);
    
    if (!$result) {
        echo "Query error: " . mysqli_error($mysqli_link) . "<br>";
        return;
    }
    
    if (mysqli_num_rows($result) > 0) {
        echo "Draw for $formatted_date already exists in database - skipping<br>";
        $records_skipped++;
        return;
    }
    
    // Verify numbers are valid
    $valid_numbers = true;
    foreach ($numbers as $number) {
        if (!is_numeric($number) || $number < 1 || $number > 39) {
            $valid_numbers = false;
            break;
        }
    }
    
    if (!$valid_numbers) {
        echo "Invalid numbers for date $formatted_date - skipping<br>";
        return;
    }
    
    // Prepare insert query
    $query_insert = "INSERT INTO `$draw_table_name` 
        (`date`, `b1`, `b2`, `b3`, `b4`, `b5`) VALUES 
        ('$formatted_date', {$numbers[0]}, {$numbers[1]}, {$numbers[2]}, {$numbers[3]}, {$numbers[4]})";
        
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
}
?>