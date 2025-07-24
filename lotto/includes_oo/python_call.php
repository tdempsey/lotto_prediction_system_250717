<?php
// The command to execute the Python script
$command = 'python calculate_50_50.py';

// Execute the command and capture the output
exec($command, $output, $return_var);

// $output is an array containing each line of output from the script
foreach ($output as $line) {
    echo $line . "\n";
}

// Check if the command was executed successfully
if ($return_var === 0) {
    echo "Script executed successfully.\n";
} else {
    echo "Script execution failed.\n";
}
?>
