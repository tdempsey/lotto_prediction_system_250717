<?php
//count modulus of draw 
function CountMod($draw)
{
	for ($x = 0; $x <= 9; $x++)
	{
		$mod[$x] = 0;
	}
	
	for ($x = 0; $x <= count($draw)-1; $x++)
	{
		if ($draw[$x] < 10) {
			$y = $draw[$x];
			$mod[$y]++;
		} elseif ($draw[$x] > 9 && $draw[$x] < 20) {
			$y = $draw[$x] - 10;
			$mod[$y]++;
		} elseif ($draw[$x] > 19 && $draw[$x] < 30) {
			$y = $draw[$x] - 20;
			$mod[$y]++;
		} elseif ($draw[$x] > 29 && $draw[$x] < 40) {
			$y = $draw[$x] - 30;
			$mod[$y]++;
		} elseif ($draw[$x] > 39 && $draw[$x] < 50) {
			$y = $draw[$x] - 40;
			$mod[$y]++;
		} else {
			$y = $draw[$x] - 50;
			$mod[$y]++;
		}
	}

	$mod_total = 0;

	for ($x = 0; $x <= 9; $x++)
	{
		if ($mod[$x] > 1)
		{
			$mod_total++;	
		}
	}

	return $mod_total;

}
?>