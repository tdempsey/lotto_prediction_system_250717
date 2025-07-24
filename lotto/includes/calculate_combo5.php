<?php
function calculate_combo4($draw,&$d4_1,&$d4_2,&$d4_3,&$d4_4,$max_draw) 
{  
	$d4_1 = 0;
	$d4_2 = 0;
	$d4_3 = 0;
	$d4_4 = 0;
	$fourth = intval($max_draw*0.25);
	$two_fourth = intval($max_draw*0.50);
	$three_fourth = intval($max_draw*0.75);

	// error checking ----------------------------------------------------------------------------------------------
	if (count($draw) == 0)
	{
		exit("<h2>Error - function calculate_25.php - <font color=\"#FF0000\">draw undefined</font></h2>");
	}

	if (array_sum($draw) == 0)
	{
		exit("<h2>Error - function calculate_25.php - <font color=\"#FF0000\">draw array_sum = 0</font></h2>");
	}

	if (is_null($max_draw) || $max_draw == 0)
	{
		exit("<h2>Error - function calculate_25.php - <font color=\"#FF0000\">max_draw undefined - max_draw = $max_draw</font></h2>");
	}

	// error checking ----------------------------------------------------------------------------------------------
	
	reset ($draw); 
	
	foreach ($draw as $val) 
	{ 
		if ($val > $three_fourth) {
			$d4_4++;
		} elseif ($val > $two_fourth) {
			$d4_3++;
		} elseif ($val > $fourth) {
			$d4_2++;
		} else {
			$d4_1++;
		}
	} 

	if ($d4_1 == 0 && $d4_2 == 0 && $d4_3 == 0 && $d4_4 == 0)
	{
		exit("<font color=\"#FF0000\"><h2>Error - function calculate_25.php - d4_1/d4_2/d4_3/d4_4 = 0</h2></font>");
	}
	
	return true;
}
?>