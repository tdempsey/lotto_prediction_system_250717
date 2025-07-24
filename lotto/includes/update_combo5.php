<?php
function update_combo5($date,$draw,$max_draw) 
{  
	$d5 = array_fill(0,6,0);
	$range = array_fill(0,10,0);

	$range[1] = intval($max_draw*0.20);
	$range[2] = intval($max_draw*0.40);
	$range[3] = intval($max_draw*0.60);
	$range[4] = intval($max_draw*0.80);

	// error checking ----------------------------------------------------------------------------------------------
	if (count($draw) == 0)
	{
		exit("<h2>Error - function update_combo5.php - <font color=\"#FF0000\">draw undefined</font></h2>");
	}

	if (array_sum($draw) == 0)
	{
		exit("<h2>Error - function update_combo5.php - <font color=\"#FF0000\">draw array_sum = 0</font></h2>");
	}

	if (is_null($max_draw) || $max_draw == 0)
	{
		exit("<h2>Error - function update_combo5.php - <font color=\"#FF0000\">max_draw undefined - max_draw = $max_draw</font></h2>");
	}

	// error checking ----------------------------------------------------------------------------------------------
	
	reset ($draw); 
	
	foreach ($draw as $val) 
	{ 
		if ($d5 > $range[4]) {
			$d5[5]++;
		} elseif ($d5 > $range[3]) {
			$d5[4]++;
		} elseif ($d5 > $range[2]) {
			$d5[3]++;
		} elseif ($d5 > $range[1]) {
			$d5[2]++;
		} else {
			$d5[1]++;
		}
	} 

	if (array_sum($d5)
	{
		exit("<font color=\"#FF0000\"><h2>Error - function update_combo5.php - d5 = 0</h2></font>");
	}

	$query = "INSERT INTO ga_f5_draw_range5";
	$query .= "'0', ";
	$query .= "'$date', ";
	$query .= "'$d5[1]', ";
	$query .= "'$d5[2]', ";
	$query .= "'$d5[3]', ";
	$query .= "'$d5[4]', ";
	$query .= "'$d5[5]', ";

	$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));
	
	return true;
}
?>