<?php
//test to count two sequential numbers 
// - must be sorted or error (-1)
// - no dups
function Test2Seq($array)
{
	$pair = 0;

	for ($count = 0 ; $count <= count($array)-2; $count++)
	{
		$num1 = $array[$count];
		$num2 = $array[$count+1];
		if ($num1 == $num2-1)
		{
			$pair++;
		}
	}

	return $pair;
{
?>