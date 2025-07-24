<?php
function calculate_20($draw,&$d5_1,&$d5_2,&$d5_3,&$d5_4,&$d5_5,$max_draw) 
{  
	$d5_1 = 0;
	$d5_2 = 0;
	$d5_3 = 0;
	$d5_4 = 0;
	$d5_2 = 0;
	$fifth		 = intval($max_draw/5);
	$two_fifth	 = intval(($max_draw/5) * 2);
	$three_fifth = intval(($max_draw/5) * 3);
	$four_fifth  = intval(($max_draw/5) * 4);

	echo "<h2>fifth = $fifth,$two_fifth,$three_fifth,$four_fifth</h2>";

	// error checking ----------------------------------------------------------------------------------------------
	if (count($draw) == 0)
	{
		exit("<h2>Error - function calculate_20.php - <font color=\"#FF0000\">draw undefined</font></h2>");
	}

	if (array_sum($draw) == 0)
	{
		exit("<h2>Error - function calculate_20.php - <font color=\"#FF0000\">draw array_sum = 0</font></h2>");
	}

	if (is_null($max_draw) || $max_draw == 0)
	{
		exit("<h2>Error - function calculate_20.php - <font color=\"#FF0000\">max_draw undefined - max_draw = $max_draw</font></h2>");
	}

	// error checking ----------------------------------------------------------------------------------------------
	
	reset ($draw); 
	
	foreach ($draw as $val) 
	{ 
		if ($val > $four_fifth) {
			$d5_5++;
		} elseif ($val > $three_fifth) {
			$d5_3++;
		} elseif ($val > $two_fifth) {
			$d5_3++;		
		} elseif ($val > $fifth) {
			$d5_2++;
		} else {
			$d5_1++;
		}
	} 

	echo "d5_1 = $d5_1,d5_2 = $d5_2,d5_3 = $d5_3,d5_4 = $d5_4,d5_5 = $d5_5,<br>" 

	if ($d5_1 == 0 && $d5_2 == 0 && $d5_3 == 0 && $d5_4 == 0 && $d5_5 == 0)
	{
		exit("<font color=\"#FF0000\"><h2>Error - function calculate_20.php - d5_1/d5_2/d5_3/d5_4/d5_5 = 0</h2></font>");
	}
	
	return true;
}
?>