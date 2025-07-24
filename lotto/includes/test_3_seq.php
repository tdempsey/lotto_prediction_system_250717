<?php
//test for three sequential numbers 
// - must be sorted or error (-1)
// - no dups
function Test3Seq($array)
{
	for ($count = 0 ; $count <= count($array)-3; $count++)
	{
		$num1 = $sorted_nums[$array];
		$num2 = $sorted_nums[$array+1]-1;
		$num3 = $sorted_nums[$array+2]-2;
		if ($num1 == $num2 && $num2 == $num3)
		{
			return 1;
		}
	}

	return 0;
{
?>