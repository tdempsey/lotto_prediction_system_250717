<?php
function update_calculate_range5($draw,$draw_date,$max_draw) 
{  
	require ("includes/mysqli.php");

	$r5 = array_fill(0,10,0);

	$range_array = array_fill(0,10,0);

	$range_array[0] = intval($max_draw*0.20);
	$range_array[1] = intval($max_draw*0.40);
	$range_array[2] = intval($max_draw*0.60);
	$range_array[3] = intval($max_draw*0.80);

	// error checking ----------------------------------------------------------------------------------------------
	if (count($draw) == 0)
	{
		exit("<h2>Error - function update_calculate_range5.php - <font color=\"#FF0000\">draw undefined</font></h2>");
	}

	if (array_sum($draw) == 0)
	{
		exit("<h2>Error - function update_calculate_range5.php - <font color=\"#FF0000\">draw array_sum = 0</font></h2>");
	}

	if (is_null($max_draw) || $max_draw == 0)
	{
		exit("<h2>Error - function update_calculate_range5.php - <font color=\"#FF0000\">max_draw undefined - max_draw = $max_draw</font></h2>");
	}

	// error checking ----------------------------------------------------------------------------------------------

	$query = "SELECT * FROM ga_f5_draw_range5 ";
	$query .= "WHERE date = '$draw_date'";

	#echo "$query";

	$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

	$num_rows = mysqli_num_rows($mysqli_result);
	
	if (!$num_rows)
	{
		reset ($draw); 
	
		foreach ($draw as $val) 
		{ 
			if ($val > $range_array[3]) {
				$r5[4]++;
			} elseif ($val > $range_array[2]) {
				$r5[3]++;
			} elseif ($val > $range_array[1]) {
				$r5[2]++;
			} elseif ($val > $range_array[0]) {
				$r5[1]++;
			} else {
				$r5[0]++;
			}
		} 

		if ($r5[0] == 0 && $r5[1] == 0 && $r5[2] == 0 && $r5[3] == 0 && $r5[4] == 0)
		{
			exit("<font color=\"#FF0000\"><h2>Error - function update_calculate_range5.php - r5_1/r5_2/r5_3/r5_4 = 0</h2></font>");
		}
	
		$query = "INSERT INTO ga_f5_draw_range5 ";
		$query .= "VALUES ('0', ";
		$query .= "'$draw_date', ";
		$query .= "'$r5[0]', ";
		$query .= "'$r5[1]', ";
		$query .= "'$r5[2]', ";
		$query .= "'$r5[3]', ";
		$query .= "'$r5[4]') ";

		#echo "$query<br>";

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

		echo "update_calculate_range5 - $draw_date updated<br>";
	}

	
	return true;
}
?>