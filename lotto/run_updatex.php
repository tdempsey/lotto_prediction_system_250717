<?php

// Define the list of PHP programs to execute.
$programs = array(
    '/path/to/program1.php',
    '/path/to/program2.php',
    '/path/to/program3.php',
);

// Iterate over the list of PHP programs and execute each program.
foreach ($programs as $program) {
    pcntl_exec($program);
}

// Wait for all of the child processes to finish.
while (($pid = pcntl_waitpid(-1, $status)) != -1) {
    // Do something with the child process exit status, if desired.
}

// All of the child processes have finished executing.
echo 'All of the PHP programs have finished executing.';
?>