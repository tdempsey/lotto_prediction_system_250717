<?php
function even_odd($draw,&$even,&$odd) 
{ 
	global $even, $odd;
	
	$even = 0;
	$odd = 0;

	// error checking ---------------------------------------------------------------------------------------
	if (count($draw) == 0)
	{
		exit("<h2>Error - function even_odd.php - <font color=\"#FF0000\">\$draw undefined</font></h2>");
	}

	if (array_sum($draw) == 0)
	{
		exit("<h2>Error - function even_odd.php - <font color=\"#FF0000\">\$draw array_sum = 0</font></h2>");
	}
	// error checking ---------------------------------------------------------------------------------------

	foreach ($draw as $val) 
	{ 
		if(!is_int($val/2)) 
		{ 
			$odd++; 
		} 
		else 
        { 
			$even++; 
        }
	}

	if ($even == 0 && $odd == 0)
	{
		exit("<font color=\"#FF0000\"><h2>Error - function even_odd.php - \$even/\$odd = 0</h2></font>");
	}

	#echo "<p>1--- even = $even</p>";
	#echo "2--- odd = $odd</br>";

	return true;
} 
?>