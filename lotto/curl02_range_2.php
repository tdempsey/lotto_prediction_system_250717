<?php

set_time_limit(0);

$game = 1; // Georgia Fantasy 5

$debug = 0;

if ($debug)
{
	ini_set('display_errors', '1');
	ini_set('display_startup_errors', '1');
	error_reporting(E_ALL);
}

require_once ("includes/games_switch.incl");
require_once ("includes/mysqli.php"); 

echo '<h3>Begin...</h3>';

$query1 = "SELECT * FROM `ga_f5_sum_count_sum`  ";
$query1 .= "ORDER BY `ga_f5_sum_count_sum`.`month3` DESC, `month6` DESC, `year1` DESC ";
$query1 .= "LIMIT 25 ";

echo "$query1<br>";

$mysqli_result1 = mysqli_query($mysqli_link, $query1) or die (mysqli_error($mysqli_link));

while($row = mysqli_fetch_array($mysqli_result1))
{
	$url = 'http://localhost:81/lotto/lot_sumeo_get_range_no_tables.php?sum=';
	$url .= $row[1];
	$url .= '&even=';
	$url .= $row[2];
	$url .= '&odd=';
	$url .= $row[3];
	$url .= '&range=2';

	echo "$url<br>";

	#die();

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Get response as string
	curl_setopt($ch, CURLOPT_HEADER, 0); // Exclude headers

	$response = curl_exec($ch);

	if (curl_errno($ch)) {
		echo 'Error: ' . curl_error($ch);
	} else {
		echo 'Response: ' . $response;
	}

	curl_close($ch);
}

?>
