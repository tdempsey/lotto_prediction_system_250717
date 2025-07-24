<?php
function calculate_drange10($draw,&$d1,&$d2,&$d3,&$d4,&$d5,&$d6,&$d7,&$d8,&$d9,&$d10,$max_draw) 
{  
	$d1 = 0;
	$d2 = 0;
	$d3 = 0;
	$d4 = 0;
	$d5 = 0;
	$d6 = 0;
	$d7 = 0;
	$d8 = 0;
	$d9 = 0;
	$d10 = 0;
	$root10 = (100/10)*0.01;
	$range1 = intval($max_draw*$root10);
	$range2 = intval($max_draw*($root10*2));
	$range3 = intval($max_draw*($root10*3));
	$range4 = intval($max_draw*($root10*4));
	$range5 = intval($max_draw*($root10*5));
	$range6 = intval($max_draw*($root10*6));
	$range7 = intval($max_draw*($root10*7));
	$range8 = intval($max_draw*($root10*8));
	$range9 = intval($max_draw*($root10*9));

	// error checking ----------------------------------------------------------------------------------------------
	if (count($draw) == 0)
	{
		exit("<h2>Error - function calculate_drange10.php - <font color=\"#FF0000\">draw undefined</font></h2>");
	}

	if (array_sum($draw) == 0)
	{
		exit("<h2>Error - function calculate_drange10.php - <font color=\"#FF0000\">draw array_sum = 0</font></h2>");
	}

	if (is_null($max_draw) || $max_draw == 0)
	{
		exit("<h2>Error - function calculate_drange10.php - <font color=\"#FF0000\">max_draw undefined - max_draw = $max_draw</font></h2>");
	}

	// error checking ----------------------------------------------------------------------------------------------
	
	reset ($draw); 
	
	foreach ($draw as $val) 
	{ 
		if ($val > $range9) {
			$d10++;
		} elseif ($val > $range8) {
			$d9++;
		} elseif ($val > $range7) {
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

	if ($d1 == 0 && $d2 == 0 && $d3 == 0 && $d4 == 0 && $d5 == 0 && $d6 == 0 && $d7 == 0 && $d8 == 0 && $d9 == 0 && $d10 == 0)
	{
		exit("<font color=\"#FF0000\"><h2>Error - function calculate_drange10.php - d1/d2/d3/d4/d5/d6/d7/d8/d9/d10 = 0</h2></font>");
	}

	if ($d1+$d2+$d3+$d4+$d5+$d6+$d7+$d8+$d9+$d10 <> 5)
	{
		exit("<font color=\"#FF0000\"><h2>Error - function calculate_drange10.php - d1/d2/d3/d4/d5/d6/d7/d8/d9/d10 <> 5</h2></font>");
	}
	
	return true;
}
?>