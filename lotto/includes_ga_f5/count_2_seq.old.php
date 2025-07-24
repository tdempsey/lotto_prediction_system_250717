<?php
//count 2 sequential numbers 
// - must be sorted or error (-1)
// - no dups
function Count2Seq($draw)
{
	$pair = 0;

	for ($count = 0 ; $count <= 4; $count++)
	{
		$num1 = $draw[$count];
		$num2 = $draw[$count+1]-1;
		if ($num1 == $num2)
		{
			$pair++;
		}
	}

	return $pair;
}
?>