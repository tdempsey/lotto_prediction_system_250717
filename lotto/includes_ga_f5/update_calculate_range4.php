<?php
function update_calculate_range4($draw,$draw_date,$max_draw) 
{  
	require ("includes/mysqli.php");

	$r4 = array_fill(0,10,0);

	$range_array = array_fill(0,10,0);

	$range_array[0] = intval($max_draw*0.25);
	$range_array[1] = intval($max_draw*0.50);
	$range_array[2] = intval($max_draw*0.75);

	// error checking ----------------------------------------------------------------------------------------------
	if (count($draw) == 0)
	{
		exit("<h2>Error - function update_calculate_range4.php - <font color=\"#FF0000\">draw undefined</font></h2>");
	}

	if (array_sum($draw) == 0)
	{
		exit("<h2>Error - function update_calculate_range4.php - <font color=\"#FF0000\">draw array_sum = 0</font></h2>");
	}

	if (is_null($max_draw) || $max_draw == 0)
	{
		exit("<h2>Error - function update_calculate_range4.php - <font color=\"#FF0000\">max_draw undefined - max_draw = $max_draw</font></h2>");
	}

	// error checking ----------------------------------------------------------------------------------------------

	$query = "SELECT * FROM ga_f5_draw_range4 ";
	$query .= "WHERE date = '$draw_date'";

	#echo "$query";

	$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

	$num_rows = mysqli_num_rows($mysqli_result);
	
	if (!$num_rows)
	{
		reset ($draw); 
	
		foreach ($draw as $val) 
		{ 
			if ($val > $range_array[2]) {
				$r4[3]++;
			} elseif ($val > $range_array[1]) {
				$r4[2]++;
			} elseif ($val > $range_array[0]) {
				$r4[1]++;
			} else {
				$r4[0]++;
			}
		} 

		if ($r4[0] == 0 && $r4[1] == 0 && $r4[2] == 0 && $r4[3] == 0)
		{
			exit("<font color=\"#FF0000\"><h2>Error - function update_calculate_range4.php - r4_1/r4_2/r4_3/r4_4 = 0</h2></font>");
		}
	
		$query = "INSERT INTO ga_f5_draw_range4 ";
		$query .= "VALUES ('0', ";
		$query .= "'$draw_date', ";
		$query .= "'$r4[0]', ";
		$query .= "'$r4[1]', ";
		$query .= "'$r4[2]', ";
		$query .= "'$r4[3]') ";

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

		echo "update_calculate_range4 - $draw_date updated<br>";
	}

	
	return true;
}
?>