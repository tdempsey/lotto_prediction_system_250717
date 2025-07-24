<?php
function update_calculate_range7($draw,$draw_date,$max_draw) 
{  
	require ("includes/mysqli.php");

	$r7 = array_fill(0,10,0);

	$range_array = array_fill(0,10,0);

	$range_array[0] = 6;
	$range_array[1] = 12;
	$range_array[2] = 18;
	$range_array[3] = 24;
	$range_array[4] = 30;
	$range_array[5] = 36;

	// error checking ----------------------------------------------------------------------------------------------
	if (count($draw) == 0)
	{
		exit("<h2>Error - function update_calculate_range7.php - <font color=\"#FF0000\">draw undefined</font></h2>");
	}

	if (array_sum($draw) == 0)
	{
		exit("<h2>Error - function update_calculate_range7.php - <font color=\"#FF0000\">draw array_sum = 0</font></h2>");
	}

	if (is_null($max_draw) || $max_draw == 0)
	{
		exit("<h2>Error - function update_calculate_range7.php - <font color=\"#FF0000\">max_draw undefined - max_draw = $max_draw</font></h2>");
	}

	// error checking ----------------------------------------------------------------------------------------------

	$query = "SELECT * FROM ga_f5_draw_range7 ";
	$query .= "WHERE date = '$draw_date'";

	#echo "$query";

	$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

	$num_rows = mysqli_num_rows($mysqli_result);
	
	if (!$num_rows)
	{
		reset ($draw); 
	
		foreach ($draw as $val) 
		{ 
			if ($val > $range_array[5]) {
				$r7[6]++;
			} elseif ($val > $range_array[4]) {
				$r7[5]++;
			} elseif ($val > $range_array[3]) {
				$r7[4]++;
			} elseif ($val > $range_array[2]) {
				$r7[3]++;
			} elseif ($val > $range_array[1]) {
				$r7[2]++;
			} elseif ($val > $range_array[0]) {
				$r7[1]++;
			} else {
				$r7[0]++;
			}
		} 

		if ($r7[0] == 0 && $r7[1] == 0 && $r7[2] == 0 && $r7[3] == 0 && $r7[4] == 0 && $r7[5] == 0)
		{
			exit("<font color=\"#FF0000\"><h2>Error - function update_calculate_range7.php - r7_1/r7_2/r7_3/r7_4 = 0</h2></font>");
		}
	
		$query = "INSERT INTO ga_f5_draw_range7 ";
		$query .= "VALUES ('0', ";
		$query .= "'$draw_date', ";
		$query .= "'$r7[0]', ";
		$query .= "'$r7[1]', ";
		$query .= "'$r7[2]', ";
		$query .= "'$r7[3]', ";
		$query .= "'$r7[4]', ";
		$query .= "'$r7[5]', ";
		$query .= "'$r7[6]') ";

		#echo "$query<br>";

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

		echo "update_calculate_range7 - $draw_date updated<br>";
	}

	
	return true;
}
?>