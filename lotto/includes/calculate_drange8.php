<?php
function calculate_drange8($draw,&$d1,&$d2,&$d3,&$d4,&$d5,&$d6,&$d7,&$d8,$max_draw) 
{  
	$d1 = 0;
	$d2 = 0;
	$d3 = 0;
	$d4 = 0;
	$d5 = 0;
	$d6 = 0;
	$d7 = 0;
	$d8 = 0;
	$root8 = (100/8)*0.01;
	$range1 = intval($max_draw*$root8);
	$range2 = intval($max_draw*($root8*2));
	$range3 = intval($max_draw*($root8*3));
	$range4 = intval($max_draw*($root8*4));
	$range5 = intval($max_draw*($root8*5));
	$range6 = intval($max_draw*($root8*6));
	$range7 = intval($max_draw*($root8*7));

	// error checking ----------------------------------------------------------------------------------------------
	if (count($draw) == 0)
	{
		exit("<h2>Error - function calculate_drange8.php - <font color=\"#FF0000\">draw undefined</font></h2>");
	}

	if (array_sum($draw) == 0)
	{
		exit("<h2>Error - function calculate_drange8.php - <font color=\"#FF0000\">draw array_sum = 0</font></h2>");
	}

	if (is_null($max_draw) || $max_draw == 0)
	{
		exit("<h2>Error - function calculate_drange8.php - <font color=\"#FF0000\">max_draw undefined - max_draw = $max_draw</font></h2>");
	}

	// error checking ----------------------------------------------------------------------------------------------
	
	reset ($draw); 
	
	foreach ($draw as $val) 
	{ 
		if ($val > $range7) {
			$d8++;
		} elseif ($val > $range6) {
			$d7++;
		} elseif ($val > $range5) {
			$d6++;
		} elseif ($val > $range4) {
			$d5++;
		} elseif ($val > $range3) {
			$d4++;
		} elseif ($val > $range2) {
			$d3++;
		} elseif ($val > $range1) {
			$d2++;
		} else {
			$d1++;
		}
	} 

	if ($d1 == 0 && $d2 == 0 && $d3 == 0 && $d4 == 0 && $d5 == 0 && $d6 == 0 && $d7 == 0 && $d8 == 0)
	{
		exit("<font color=\"#FF0000\"><h2>Error - function calculate_drange8.php - d1/d2/d3/d4/d5/d6/d7/d8 = 0</h2></font>");
	}

	if ($d1+$d2+$d3+$d4+$d5+$d6+$d7+$d8 <> 5)
	{
		exit("<font color=\"#FF0000\"><h2>Error - function calculate_drange8.php - d1/d2/d3/d4/d5/d6/d7/d8 <> 5</h2></font>");
	}
	
	return true;
}
?>