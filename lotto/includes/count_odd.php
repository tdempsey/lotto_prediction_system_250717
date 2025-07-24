<?php
function count_odd($array) 
{ 
	$even = 0;
	$odd = 0;

	while(list($key,$val) = each($array)) 
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
	
	return $odd;
} 
?>