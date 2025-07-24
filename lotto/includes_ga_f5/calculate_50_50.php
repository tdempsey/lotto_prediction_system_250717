<?php
function calculate_50_50($draw,&$d2_1,&$d2_2,$max_draw) 
{  
	$d2_1 = 0;
	$d2_2 = 0;
	$half = intval($max_draw/2);

	// error checking ----------------------------------------------------------------------------------------------
	if (count($draw) == 0)
	{
		exit("<h2>Error - function calculate_50_50.php - <font color=\"#FF0000\">draw undefined</font></h2>");
	}

	if (array_sum($draw) == 0)
	{
		exit("<h2>Error - function calculate_50_50.php - <font color=\"#FF0000\">draw array_sum = 0</font></h2>");
	}

	if (is_null($max_draw) || $max_draw == 0)
	{
		exit("<h2>Error - function calculate_50_50.php - <font color=\"#FF0000\">max_draw undefined - max_draw = $max_draw</font></h2>");
	}

	// error checking ----------------------------------------------------------------------------------------------
	
	reset ($draw); 
	
	foreach ($draw as $val) 
	{ 
		if ($val > $half) {
			$d2_2++;
		} else {
			$d2_1++;
		}
	} 

	if ($d2_1 == 0 && $d2_2 == 0)
	{
		exit("<font color=\"#FF0000\"><h2>Error - function calculate_50_50.php - d2_1/d2_2 = 0</h2></font>");
	}
	
	return true;
}
?>