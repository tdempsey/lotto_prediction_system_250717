<?php

set_time_limit(0);

date_default_timezone_set('America/New_York');

$game = 1; // Georgia Fantasy 5

$debug = 1;

if ($debug)
{
	ini_set('display_errors', '1');
	ini_set('display_startup_errors', '1');
	error_reporting(E_ALL);
}

require_once ("includes/games_switch.incl");
require_once ("includes/mysqli.php"); 

echo '<h3>Begin...</h3>';

$currdate = date('ymd');

$temp_table1 = 'temp_cover_1k_count_' .  $currdate;

$range = 2;	#<<<<<<


$sum = 60;	#<<<<<<
$sum_low = $sum - 2;	#<<<<<<
$sum_high = $sum + 2;	#<<<<<<
$even = 3;
$odd = 2;

#$query1 = "SELECT * FROM `ga_f5_sum_count_sum`  ";	### 240318
$query1 = "SELECT * FROM ga_f5_draws ";
$query1 .= "WHERE (sum  = $sum_low ";
$query1 .= "OR   sum = $sum ";
$query1 .= "OR   sum = $sum_high) ";
$query1 .= "AND   even = 3 ";
$query1 .= "AND   odd  = 2 ";
$query1 .= "ORDER BY date DESC ";
#$query1 .= "LIMIT 1 ";

echo "$query1<br>";

$mysqli_result1 = mysqli_query($mysqli_link, $query1) or die (mysqli_error($mysqli_link));

while($row = mysqli_fetch_array($mysqli_result1))
{
	#$url = 'http://localhost:81/lotto/lot_sumeo_get_no_tables.php?sum=';
	$url = 'http://localhost:81/lotto/update_split_sumeo_notables_range.php?sum=';
	$url .= $sum;
	$url .= '&even=';
	$url .= $even;
	$url .= '&odd=';
	$url .= $odd;

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
