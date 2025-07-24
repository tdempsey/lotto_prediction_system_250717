<?php
	function calc_devsq ($draw,$average)
	{
		$average = array_sum($draw)/5;
		$devsq = 0.0;
		for ($x = 0; $x < 5; $x++)
		{
			$temp = $draw[$x]-$average;
			$devsq += $temp*$temp;
		}

		#echo "devsq = $devsq<p>";

		return $devsq;
	}
?>