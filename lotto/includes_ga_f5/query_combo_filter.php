<?php
function query_combo_table7($sum,$even,$odd,$d2_1,$d2_2)
   {
   		global $debug,$combo,$draw_range,$exclude_numbers_col_one,$width_limit,$filter_range,
			   $query_limit,$balls,$balls_drawn,$seq2_limit,$seq3_limit,$test_switch;	

		// error checking ----------------------------------------------------------------------------------------------
		if (is_null($sum) || is_null($even) || is_null($odd) || is_null($d2_1) || 
			is_null($d2_2) || is_null($query_limit) || is_null($seq2_limit) || is_null($seq3_limit) ||
			is_null($balls) || is_null($balls_drawn))
		{
			exit("<h2>Error - function query_combo_filter.php - <font color=\"#FF0000\">parameter undefined - even = $even, odd = $odd, d2_1 = $d2_1, d2_2 = $d2_2, query_limit = $query_limit, seq2_limit = $seq2_limit, seq3_limit = $seq3_limit, balls = $balls, balls_drawn = $balls_drawn</font></h2>");
		}
		// error checking ----------------------------------------------------------------------------------------------

		array_splice($combo, 0);

		require ("includes/mysqli.php");

		log_info("--- query_combo_table7($sum,$even,$odd,$d2_1,$d2_2)\n");
		
		for ($y = 1; $y <= $query_limit; $y++) // query_limit = col limit
		{
			if (array_search($y, $exclude_numbers_col_one) === FALSE)
			{

				$query_combo = "SELECT DISTINCT b1, b2, b3, b4, b5 FROM fl_f5_filter_a "; // fix
				$query_combo .= "WHERE	sum =	$sum ";
				$query_combo .= "AND	even = $even ";
				$query_combo .= "AND	odd =	$odd ";
				$query_combo .= "AND	d2_1 = $d2_1 ";
				$query_combo .= "AND	d2_2 = $d2_2 ";
				

				//if ($filter_range && $test_switch[1])
				// add table read - fl_f5_range_analysis
				if ($filter_range)
				{
					$query_combo .= "AND	d0 >=	{$draw_range[0][0]} ";
					$query_combo .= "AND	d0 <=	{$draw_range[0][1]} ";
					$query_combo .= "AND	d1 >=	{$draw_range[1][0]} ";
					$query_combo .= "AND	d1 <=	{$draw_range[1][1]} ";
					$query_combo .= "AND	d2 >=	{$draw_range[2][0]} ";
					$query_combo .= "AND	d2 <=	{$draw_range[2][1]} ";
					$query_combo .= "AND	d3 >=	{$draw_range[3][0]} ";
					$query_combo .= "AND	d3 <=	{$draw_range[3][1]} ";
					$query_combo .= "AND	d4 >=	{$draw_range[4][0]} ";
					$query_combo .= "AND	d4 <=	{$draw_range[4][1]} ";
					$query_combo .= "AND	d5 >=	{$draw_range[5][0]} ";
					$query_combo .= "AND	d5 <=	{$draw_range[5][1]} ";
				}

				// add table read - fl_f5_range_analysis (or width)
				if ($test_switch[16])
				{
					$query_combo .= "AND	width >= $width_limit ";
				}

				if ($test_switch[18])
				{
					$query_combo .= "AND	seq2 <= $seq2_limit ";
				}

				if ($test_switch[19])
				{
					$query_combo .= "AND	seq3 <= $seq3_limit ";
				}

				//print "query_combo = $query_combo<p>";

				$mysqli_result_combo = mysqli_query($query_combo, $mysqli_link) or die (mysqli_error($mysqli_link));

				$num_rows = mysqli_num_rows($mysqli_result_combo);

				log_info("combo result for $y - $sum - $num_rows\n");

				$x = 0;

				////////////////////////////////////////////////////////////////////////////
				// 
				//    Filters
				//
				////////////////////////////////////////////////////////////////////////////

				while($row_combo = mysqli_fetch_row($mysqli_result_combo))
				{
					$pass = 1;

					/*
					if ($test_switch[18])
					{
						$seq2 = Count2Seq($row_combo);
						if ($seq2 > $seq2_limit) 
						{
							$pass = 0;
						}
					} else {
						log_info("test_switch[18] off - seq_2\n");
					}

					if ($test_switch[19] && $pass)
					{
						$seq3 = Count3Seq($row_combo);
						if ($seq3 > $seq3_limit) 
						{
							$pass = 0;
						}
					} else {
						if (!$test_switch[19])
						{
							log_info("test_switch[19] off - seq_3\n");
						}
					}

					if ($test_switch[20] && $pass)
					{
						$temp12 = test_column_lookup($cola=1,$colb=2,$row_combo[0],$row_combo[1]);
						if (!$temp12) 
						{
							$pass = 0;
						}
					} else {
						//log_info("test_switch[20] off - col12\n");
					}

					if ($test_switch[20] && $pass)
					{
						$temp23 = test_column_lookup($cola=2,$colb=3,$row_combo[1],$row_combo[2]);
						if (!$temp23) 
						{
							$pass = 0;
						}
					} else {
						//log_info("test_switch[20] off - col12\n");
					}

					if ($test_switch[20] && $pass)
					{
						$temp16 = test_column_lookup($cola=1,$colb=6,$row_combo[0],$row_combo[5]);
						if (!$temp16) 
						{
							$pass = 0;
						}
					} else {
						//log_info("test_switch[20] off - col12\n");
					}
					*/
					
					if ($pass)
					{
						$combo[$x] = $row_combo;
						$x++;
					} 
				}
			}
		}

		$temp = count($combo);

		log_info("********************\n");
		log_info("combo = $temp\n");
		log_info("********************\n");


		if ($x == 0)
		{
			return 0;
		}
		else
		{
			return count($combo);
		}

   }
?>