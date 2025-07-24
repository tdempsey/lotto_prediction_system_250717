<?php
function calculate_50_50($draw,&$d501,&$d502,$num_in_draw) 
{  
	$d501 = 0;
	$d502 = 0;
	$half = intval($num_in_draw/2);
	
	while(list($key,$val) = each($draw)) 
	{ 
		if ($val > $half) {
			$d502++;
		} else {
			$d501++;
		}
	} 
	
	return 0;
}
?>