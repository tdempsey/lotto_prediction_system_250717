<?php
	function calculate_draw_count($draw) 
	{  
		global $debug, $game;

		$draw_count = array_fill (0, 8, 0);
		
		for ($index = 0; $index <= 4; $index++) ### 210107
		{
			$val = $draw[$index];

			if ($val < 10) {
				$draw_count[0]++;
			} 
			elseif ($val > 9 && $val < 20) {
				$draw_count[1]++;
			}
			elseif ($val > 19 && $val < 30) {
				$draw_count[2]++;
			}
			elseif ($val > 29 && $val < 40) {
				$draw_count[3]++;
			}
			elseif ($val > 39 && $val < 50) {
				$draw_count[4]++;
			}
			elseif ($val > 49 && $val < 60) {
				$draw_count[5]++;
			}
			elseif ($val > 59 && $val < 70) {
				$draw_count[6]++;
			}
			else {
				$draw_count[7]++;
			}
		} 
		  
		return $draw_count; 
	}

	function calculate_draw_count4($draw) 
	{  
		global $debug, $game;

		$draw_count = array_fill (0, 8, 0);
		
		for ($index = 0; $index <= 3; $index++) 
		{
			$val = $draw[$index];

			if ($val < 10) {
				$draw_count[0]++;
			} 
			elseif ($val > 9 && $val < 20) {
				$draw_count[1]++;
			}
			elseif ($val > 19 && $val < 30) {
				$draw_count[2]++;
			}
			elseif ($val > 29 && $val < 40) {
				$draw_count[3]++;
			}
			elseif ($val > 39 && $val < 50) {
				$draw_count[4]++;
			}
			elseif ($val > 49 && $val < 60) {
				$draw_count[5]++;
			}
			elseif ($val > 59 && $val < 70) {
				$draw_count[6]++;
			}
			else {
				$draw_count[7]++;
			}
		} 
		  
		return $draw_count; 
	}

	function calculate_draw_count3($draw) 
	{  
		global $debug, $game;

		$draw_count = array_fill (0, 8, 0);
		
		for ($index = 0; $index <= 2; $index++) 
		{
			$val = $draw[$index];

			if ($val < 10) {
				$draw_count[0]++;
			} 
			elseif ($val > 9 && $val < 20) {
				$draw_count[1]++;
			}
			elseif ($val > 19 && $val < 30) {
				$draw_count[2]++;
			}
			elseif ($val > 29 && $val < 40) {
				$draw_count[3]++;
			}
			elseif ($val > 39 && $val < 50) {
				$draw_count[4]++;
			}
			elseif ($val > 49 && $val < 60) {
				$draw_count[5]++;
			}
			elseif ($val > 59 && $val < 70) {
				$draw_count[6]++;
			}
			else {
				$draw_count[7]++;
			}
		} 
		  
		return $draw_count; 
	}

	function calculate_draw_count2($draw) 
	{  
		global $debug, $game;

		$draw_count = array_fill (0, 8, 0);
		
		for ($index = 0; $index <= 1; $index++) 
		{
			$val = $draw[$index];

			if ($val < 10) {
				$draw_count[0]++;
			} 
			elseif ($val > 9 && $val < 20) {
				$draw_count[1]++;
			}
			elseif ($val > 19 && $val < 30) {
				$draw_count[2]++;
			}
			elseif ($val > 29 && $val < 40) {
				$draw_count[3]++;
			}
			elseif ($val > 39 && $val < 50) {
				$draw_count[4]++;
			}
			elseif ($val > 49 && $val < 60) {
				$draw_count[5]++;
			}
			elseif ($val > 59 && $val < 70) {
				$draw_count[6]++;
			}
			else {
				$draw_count[7]++;
			}
		} 
		  
		return $draw_count; 
	}
?>