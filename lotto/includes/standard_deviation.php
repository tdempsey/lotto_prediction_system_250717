<?php
function standard_deviation($array) 
{  
	// error checking ----------------------------------------------------------------------------------------------
	if (count($array) == 0)
	{
		exit("<h2>Error - function standard_deviation.php - <font color=\"#FF0000\">\$draw undefined</font></h2>");
	}

	if (array_sum($array) == 0)
	{
		exit("<h2>Error - function standard_deviation.php - <font color=\"#FF0000\">\$draw array_sum = 0</font></h2>");
	}
	// error checking ----------------------------------------------------------------------------------------------
	
	
	//get sum of array values 
	//	while(list($key,$val) = each($array)) { 
	//	$total += $val; 
	//}
	
	$total = array_sum($array);
     
	//reset($array); 
	$mean = $total/count($array); 
     
	while(list($key,$val) = each($array)) { 
		$sum += pow(($val-$mean),2); 
	} 
	$var = sqrt($sum/(count($array)-1)); 

	if ($var == 0)
	{
		exit("<font color=\"#FF0000\"><h2>Error - function standard_deviation.php - \$var = 0</h2></font>");
	}
     
	return $var; 
}
?>