<?php
function SaveDrawArray()	
{
	global $sorted_nums,$saved_nums_array,$debug;

	for ($x = 0; $x < 6; $x++)
	{
		array_push($saved_nums_array, $sorted_nums[$x]); 
	}

	return 0;
}	
?>
