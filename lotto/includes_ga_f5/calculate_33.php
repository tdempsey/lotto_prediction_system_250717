<?php
function calculate_33($draw,&$d3_1,&$d3_2,&$d3_3,$max_draw) 
{  
	$d3_1 = 0;
	$d3_2 = 0;
	$d3_3 = 0;
	$third = intval($max_draw/3);
	$two_third = $third * 2;

	// error checking ----------------------------------------------------------------------------------------------
	if (count($draw) == 0)
	{
		exit("<h2>Error - function calculate_33.php - <font color=\"#FF0000\">draw undefined</font></h2>");
	}

	if (array_sum($draw) == 0)
	{
		exit("<h2>Error - function calculate_33.php - <font color=\"#FF0000\">draw array_sum = 0</font></h2>");
	}

	if (is_null($max_draw) || $max_draw == 0)
	{
		exit("<h2>Error - function calculate_33.php - <font color=\"#FF0000\">max_draw undefined - max_draw = $max_draw</font></h2>");
	}

	// error checking ----------------------------------------------------------------------------------------------
	
	reset ($draw); 
	
	foreach ($draw as $val) 
	{ 
		if ($val > $two_third) {
			$d3_3++;
		} elseif ($val > $third) {
			$d3_2++;
		} else {
			$d3_1++;
		}
	} 

	if ($d3_1 == 0 && $d3_2 == 0 && $d3_3 == 0)
	{
		exit("<font color=\"#FF0000\"><h2>Error - function calculate_33.php - d3_1/d3_2/d3_3 = 0</h2></font>");
	}
	
	return true;
}
?>