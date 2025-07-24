<?php
function update_calculate_range6($draw,$draw_date,$max_draw) 
{  
	require ("includes/mysqli.php");

	$r6 = array_fill(0,10,0);

	$range_array = array_fill(0,10,0);

	$range_array[0] = 7;
	$range_array[1] = 14;
	$range_array[2] = 21;
	$range_array[3] = 28;
	$range_array[4] = 35;

	// error checking ----------------------------------------------------------------------------------------------
	if (count($draw) == 0)
	{
		exit("<h2>Error - function update_calculate_range6.php - <font color=\"#FF0000\">draw undefined</font></h2>");
	}

	if (array_sum($draw) == 0)
	{
		exit("<h2>Error - function update_calculate_range6.php - <font color=\"#FF0000\">draw array_sum = 0</font></h2>");
	}

	if (is_null($max_draw) || $max_draw == 0)
	{
		exit("<h2>Error - function update_calculate_range6.php - <font color=\"#FF0000\">max_draw undefined - max_draw = $max_draw</font></h2>");
	}

	// error checking ----------------------------------------------------------------------------------------------

	$query = "SELECT * FROM ga_f5_draw_range6 ";
	$query .= "WHERE date = '$draw_date'";

	#echo "$query";

	$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

	$num_rows = mysqli_num_rows($mysqli_result);
	
	if (!$num_rows)
	{
		reset ($draw); 
	
		foreach ($draw as $val) 
		{ 
			if ($val > $range_array[4]) {
				$r6[5]++;
			} elseif ($val > $range_array[3]) {
				$r6[4]++;
			} elseif ($val > $range_array[2]) {
				$r6[3]++;
			} elseif ($val > $range_array[1]) {
				$r6[2]++;
			} elseif ($val > $range_array[0]) {
				$r6[1]++;
			} else {
				$r6[0]++;
			}
		} 

		if ($r6[0] == 0 && $r6[1] == 0 && $r6[2] == 0 && $r6[3] == 0 && $r6[4] == 0 && $r6[5] == 0)
		{
			exit("<font color=\"#FF0000\"><h2>Error - function update_calculate_range6.php - r6_1/r6_2/r6_3/r6_4 = 0</h2></font>");
		}
	
		$query = "INSERT INTO ga_f5_draw_range6 ";
		$query .= "VALUES ('0', ";
		$query .= "'$draw_date', ";
		$query .= "'$r6[0]', ";
		$query .= "'$r6[1]', ";
		$query .= "'$r6[2]', ";
		$query .= "'$r6[3]', ";
		$query .= "'$r6[4]', ";
		$query .= "'$r6[5]') ";

		#echo "$query<br>";

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

		echo "update_calculate_range6 - $draw_date updated<br>";
	}

	
	return true;
}
?>