<?php
	function SetTestRanges($numbers_in_draw)
	{
		global$draw_range,$rank_count_range,$range501_low,$range502_low,$range501_high,$range502_high,
				$even_high,$odd_high,$even_low,$odd_low,$six_nums,$five_nums,$four_nums,$three_nums,
				$two_nums,$one_nums,$rank_count_range,$draw_table_name;

		$test_range_draws = 5;	
		
		require ("includes/mysqli.php");

		$range_count = array_fill (0, 6, 0);
		$range_count_average = array_fill (0, 6, 0);

		$query2 = "SELECT * FROM $draw_table_name ";
		$query2 .= "ORDER BY date DESC ";
		$query2 .= "LIMIT $test_range_draws ";
		
		$mysqli_result2 = mysqli_query($mysqli_link, $query2) or die (mysqli_error($mysqli_link));

		while($row = mysqli_fetch_row($mysqli_result2))
		{	
			$range = array_fill (0, 6, 0);
			$range501 = 0;
			$range502 = 0;
			$even = 0;
			$odd = 0;

			for ($index = 1; $index <= $balls_drawn; $index++)
			{
				$val = $row[$index];
				//$sum += $row[$index];
				
				if ($val < 10) {
					$range[0]++;
				} 
				elseif ($val > 9 && $val < 20) {
					$range[1]++;
				}
				elseif ($val > 19 && $val < 30) {
					$range[2]++;
				}
				elseif ($val > 29 && $val < 40) {
					$range[3]++;
				}
				elseif ($val > 39 && $val < 50) {
					$range[4]++;
				}
				else {
					$range[5]++;
				}

				if ($val < 26.5)
				{
					$range501++;	
				}
				else
				{
					$range502++;
				}

				if (is_int($val/2)){
					$even++;
				}
				else {
					$odd++;
				}

				$nums[$index-1] = $val;
			}

			if ($range[0] > 0)
			{
				$range_count[0]++;
			}

			if ($range[1] > 0)
			{
				$range_count[1]++;
			}

			if ($range[2] > 0)
			{
				$range_count[2]++;
			}

			if ($range[3] > 0)
			{
				$range_count[3]++;
			}

			if ($range[4] > 0)
			{
				$range_count[4]++;
			}

			if ($range[5] > 0)
			{
				$range_count[5]++;
			}

			sort($nums);

			for ($x = 0; $x <= 5; $x++)
			{
				$range_average[$x] += $range[$x];
			}

			$range501_average += $range501;
			$range502_average += $range502;
			$even_average += $even;
			$odd_average += $odd;
		}

		for ($x = 0; $x <= 5; $x++)
		{
			$range_average[$x] = $range_average[$x]/($numbers_in_draw * $test_range_draws);
		}
		
		$range501_average = $range501_average/($numbers_in_draw * $test_range_draws);
		$range502_average = $range502_average/($numbers_in_draw * $test_range_draws);
		$even_average = $even_average/($numbers_in_draw * $test_range_draws);
		$odd_average = $odd_average/($numbers_in_draw * $test_range_draws);

		for ($x = 0; $x <= 4; $x++)
		{
			$range_count[$x] == $test_range_draws ? $draw_range[$x][0] = 1 : $draw_range[$x][0] = 0;
			$range_average[$x] > 0.15 ? $draw_range[$x][1] = 2 : $draw_range[$x][1] = 1;
		}

		$range_count[5] == $test_range_draws ? $draw_range[5][0] = 1 : $draw_range[5][0] = 0;
		$range_average[5] > 0.08 ? $draw_range[5][1] = 1 : $draw_range[5][1] = 0;
			
		$even_average < 0.40 ? $even_low = 1 : $even_low = 2;
		$even_average > 0.55 ? $even_high = 5 : $even_high = 4;
		$odd_average < 0.40 ? $odd_low = 1 : $odd_low = 2;
		$odd_average > 0.55 ? $odd_high = 5 : $odd_high = 4;

		$range501_average < 0.58 ? $range501_low = 1 : $range501_low = 2;
		$range502_average < 0.58 ? $range502_low = 1 : $range502_low = 2;
		$range501_average > 0.58 ? $range501_high = 4 : $range501_high = 3;
		$range502_average > 0.58 ? $range502_high = 4 : $range502_high = 3;
		/*

		$rank_count_range[0][0] = 0;
		$rank_count_range[1][0] = 0;
		$rank_count_range[2][0] = 0;
		$rank_count_range[3][0] = 0;
		$rank_count_range[4][0] = 0;
		$rank_count_range[5][0] = 0;
		$rank_count_range[6][0] = 0;
		//count($zero_nums) < 5 ? $rank_count_range[0][0] = 0 : $rank_count_range[0][0] = 1; 
		//count($one_nums) < 5 ? $rank_count_range[1][0] = 0 : $rank_count_range[1][0] = 1; 
		//count($two_nums) < 5 ? $rank_count_range[2][0] = 0 : $rank_count_range[2][0] = 1; 
		//count($three_nums) < 5 ? $rank_count_range[3][0] = 0 : $rank_count_range[3][0] = 1;
		//count($four_nums) < 5 ? $rank_count_range[4][0] = 0 : $rank_count_range[4][0] = 1;
		//count($five_nums) < 5 ? $rank_count_range[5][0] = 0 : $rank_count_range[5][0] = 1;
		//count($six_nums) < 5 ? $rank_count_range[6][0] = 0 : $rank_count_range[6][0] = 1;

		//$rank_count_range[0][1] = 0;
		//$rank_count_range[1][1] = 1;
		//$rank_count_range[2][1] = 2;
		//$rank_count_range[3][1] = 2;
		//$rank_count_range[4][1] = 2;
		//$rank_count_range[5][1] = 2;
		//$rank_count_range[6][1] = 2;
		
		//count($one_nums) > 9 ? $rank_count_range[1][1] = 2 : $rank_count_range[1][1] = 1;
		//count($two_nums) > 9 ? $rank_count_range[2][1] = 2 : $rank_count_range[2][1] = 1; 
		//count($three_nums) > 9 ? $rank_count_range[3][1] = 2 : $rank_count_range[3][1] = 1;
		//count($four_nums) > 9 ? $rank_count_range[4][1] = 2 : $rank_count_range[4][1] = 1;
		//count($five_nums) > 9 ? $rank_count_range[5][1] = 2 : $rank_count_range[5][1] = 1;
		//count($six_nums) > 9 ? $rank_count_range[6][1] = 2 : $rank_count_range[6][1] = 1;

		if (count($six_nums) > 10)
		{
			$rank_count_range[6][1] = 3;
		} 
		elseif (count($six_nums) > 3)
		{
			$rank_count_range[6][1] = 2;
		} 
		else
		{
			$rank_count_range[6][1] = 1;
		} 

		if (count($five_nums) > 10)
		{
			$rank_count_range[5][1] = 3;
		} 
		elseif (count($five_nums) > 3)
		{
			$rank_count_range[5][1] = 2;
		} 
		else
		{
			$rank_count_range[5][1] = 1;
		} 

		if (count($four_nums) > 10)
		{
			$rank_count_range[4][1] = 3;
		} 
		elseif (count($four_nums) > 3)
		{
			$rank_count_range[4][1] = 2;
		} 
		else
		{
			$rank_count_range[4][1] = 1;
		} 

		if (count($three_nums) > 10)
		{
			$rank_count_range[3][1] = 3;
		} 
		elseif (count($three_nums) > 3)
		{
			$rank_count_range[3][1] = 2;
		} 
		else
		{
			$rank_count_range[3][1] = 1;
		} 

		if (count($two_nums) > 10)
		{
			$rank_count_range[2][1] = 3;
		} 
		elseif (count($two_nums) > 3)
		{
			$rank_count_range[2][1] = 2;
		} 
		else
		{
			$rank_count_range[2][1] = 1;
		} 

		if (count($one_nums) > 10)
		{
			$rank_count_range[1][1] = 3;
		} 
		elseif (count($one_nums) > 3)
		{
			$rank_count_range[1][1] = 2;
		} 
		else
		{
			$rank_count_range[1][1] = 1;
		} 

		if (count($zero_nums) > 10)
		{
			$rank_count_range[0][1] = 3;
		} 
		elseif (count($zero_nums) > 3)
		{
			$rank_count_range[0][1] = 2;
		} 
		else
		{
			$rank_count_range[0][1] = 1;
		} 
		*/

		return 0;
	}
?>