<?php

echo '<!DOCTYPE html>';
echo '<html lang="en">';
echo '<head>';
echo '    <meta charset="UTF-8">';
echo '    <title>DataTables Example</title>';
echo '    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.css">';
echo '    <script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>';
echo '    <script type="text/javascript" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.js"></script>';
echo '    <style>';
echo '        #table-container {';
echo '            width: 400px;';
echo '            margin-left: 0;';
echo '            margin-right: auto;';
echo '        }';
echo '        table.dataTable tbody tr:nth-child(odd) {';
echo '            background-color: #007BFF;'; // Light blue color for odd rows
echo '        }';
echo '        table.dataTable tbody tr:nth-child(even) {';
echo '            background-color: #ffffff;'; // White color for even rows
echo '        }';
echo '        table.dataTable tbody td {';
echo '            padding: 8px;';
echo '        }';
echo '    </style>';
echo '</head>';
echo '<body>';

echo '<div id="table-container">';
echo '    <table id="example" class="display" style="width:100%">';
echo '        <thead>';
echo '            <tr>';
echo '                <th>Sum</th>';
echo '                <th>Count</th>';
echo '            </tr>';
echo '        </thead>';
echo '        <tbody>';

$sum = 0;
for ($count = 1; $count <= 10; $count++) {
    $sum += 10;
    echo "<tr><td>" . $sum . "</td><td>" . $count . "</td></tr>";
}

echo '        </tbody>';
echo '    </table>';
echo '</div>';

echo '<script>';
echo '$(document).ready(function() {';
echo '    $("#example").DataTable();';
echo '} );';
echo '</script>';

echo '</body>';
echo '</html>';

?>
