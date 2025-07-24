<?php
function calculate_drange3($draw,&$d1,&$d2,&$d3,$max_draw) 
{  
	$d1 = 0;
	$d2 = 0;
	$d3 = 0;
	#$d4 = 0;
	$range1 = intval($max_draw*0.33);
	$range2 = intval($max_draw*0.66);
	#$range3 = intval($max_draw*0.75);

	// error checking ----------------------------------------------------------------------------------------------
	if (count($draw) == 0)
	{
		exit("<h2>Error - function calculate_drange3.php - <font color=\"#FF0000\">draw undefined</font></h2>");
	}

	if (array_sum($draw) == 0)
	{
		exit("<h2>Error - function calculate_drange3.php - <font color=\"#FF0000\">draw array_sum = 0</font></h2>");
	}

	if (is_null($max_draw) || $max_draw == 0)
	{
		exit("<h2>Error - function calculate_drange3.php - <font color=\"#FF0000\">max_draw undefined - max_draw = $max_draw</font></h2>");
	}

	// error checking ----------------------------------------------------------------------------------------------
	
	reset ($draw); 
	
	foreach ($draw as $val) 
	{ 
		if ($val > $range2) {
			$d3++;
		} elseif ($val > $range1) {
			$d2++;
		} else {
			$d1++;
		}
	} 

	if ($d1 == 0 && $d2 == 0 && $d3 == 0)
	{
		exit("<font color=\"#FF0000\"><h2>Error - function calculate_drange3.php - d1/d2/d3 = 0</h2></font>");
	}

	if ($d1+$d2+$d3 <> 5)
	{
		exit("<font color=\"#FF0000\"><h2>Error - function calculate_drange3.php - $d1/$d2/$d3/$d4 <> 5</h2></font>");
	}
	
	return true;
}
?>