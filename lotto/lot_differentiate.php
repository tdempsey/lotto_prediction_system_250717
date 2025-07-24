<?php

	// Game ----------------------------- Game
	//$game = 1; // Georgia Fantasy 5
	#$game = 2; // Mega Millions
	//$game = 3; // Georgia Lotto South
	#$game = 4; // Florida Fantasy 5
	//$game = 5; // Florida Mega Money
	$game = 6; // Florida Lotto
	#$game = 7; // Powerball
	// Game ----------------------------- Game
	
	require ("includes/games_switch.incl");
	require ("includes/even_odd.php");
	require ("includes/last_draws.php");
	require ("includes/calculate_rank.php");
	require ("includes/look_up_rank.php"); 
	require ("includes/build_rank_table.php");
	require ("includes/test_column_lookup.php");
	require ("includes/calculate_rank_mb.php");
	require ("includes/next_draw.php");
	require ("includes/number_due.php");
	require ("includes/count_2_seq.php");
	require ("includes/count_3_seq.php");
	require ("includes/mysqli.php"); 

	require ("includes_fl/combin_date.incl");
	
	// ----------------------------------------------------------------------------------
	function print_table($limit)
	{
		global $draw_table_name, $balls, $balls_drawn, $game_includes, $draw_prefix, $game_includes, $average_count, $number_count,
				$comp_avg,$total_failed; 

		require ("includes/mysqli.php"); 

		require ("$game_includes/config.incl");

		$query = "SELECT * FROM $draw_table_name ";
		$query .= "ORDER BY date DESC ";
		$query .= "LIMIT $limit ";

		//print("$query<p>");

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

		//start table
		print("<h3>Last $limit draws</h3>\n");
		print("<TABLE BORDER=\"1\">\n");

		//create header row
		print("<TR><B>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Date</center></TD>\n");

		for ($x = 1; $x <= $balls_drawn; $x++)
		{
			print("<TD BGCOLOR=\"#CCCCCC\"><center>&nbsp;P$x&nbsp;</center></TD>\n");
		}
		
		if ($mega_balls)
		{
			print("<TD BGCOLOR=\"#CCCCCC\">Mega</TD>\n");
		}

		print("<TD BGCOLOR=\"#CCCCCC\"><center>Sum</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><font size=\"-1\"><CENTER>Even<br>/Odd</CENTER></font></TD>\n");

		for ($x = 0; $x <= intval($balls/10); $x++)
		{
			print("<TD BGCOLOR=\"#CCCCCC\"><CENTER>$x.x</CENTER></TD>\n");
		}

		$half = intval($balls/2);

		print("<TD BGCOLOR=\"#CCCCCC\"><CENTER>Range<br>$half/$balls</CENTER></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><CENTER>r0</CENTER></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><CENTER>r1</CENTER></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><CENTER>r2</CENTER></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><CENTER>r3</CENTER></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><CENTER>r4</CENTER></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><CENTER>r5</CENTER></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><CENTER>r6</CENTER></TD>\n");

		if ($mega_balls)
		{
			print("<TD BGCOLOR=\"#CCCCCC\">rMB</TD>\n");
		}

		print("<TD BGCOLOR=\"#CCCCCC\"><font size=\"-1\">Seq2</font></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><font size=\"-1\">Seq3</font></TD>\n");
		
		print("<TD BGCOLOR=\"#CCCCCC\"><font size=\"-1\">Mod2</font></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><font size=\"-1\">Mod3</font></TD>\n");

		print("<TD BGCOLOR=\"#CCCCCC\" align=\"center\"><font size=\"-1\">Comp<br>2</font></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\" align=\"center\"><font size=\"-1\">Comp<br>3</font></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\" align=\"center\"><font size=\"-1\">Comp<br>4</font></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\" align=\"center\"><font size=\"-1\">Comp<br>5</font></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\" align=\"center\"><font size=\"-1\">Comp<br>6</font></TD>\n");

		print("</B></TR>\n");

		$average_count = 0;
		$sum_sum = 0;
		$even_sum = 0;
		$odd_sum = 0;
		$num_range0_sum = 0;
		$num_range1_sum = 0;
		$num_range2_sum = 0;
		$num_range3_sum = 0;
		$num_range4_sum = 0;
		$num_range_d501_sum = 0;
		$num_range_d502_sum = 0;
		$mod2_count_sum = 0;
		$mod3_count_sum = 0;
		$draw_rank_sum = array_fill (0,7,0);
		$draw = array_fill (0,$balls_drawn-1,0);
		$comp_avg = array_fill (0,7,0);
		$comp_avg_tot = array_fill (0,7,0);
		$col_avg = array_fill (0,7,0);
		$seq2_avg = 0;
		$seq3_avg = 0;
		$mod2_avg = 0;
		$mod3_avg = 0;
		$total_failed = array_fill (0,7,0);
		$total_failed_tot = array_fill (0,7,0);

		$draw_columns_count = intval($balls/10) + 1;

		$col_sum = array_fill (0, 7, $draw_rank_sum);

		/* change - calculate values */

		while($row = mysqli_fetch_array($mysqli_result))
		{

			$y = 1;
			for ($x = 0; $x < $balls_drawn; $x++)
			{
				$draw[$x] = $row[b.$y];
				$y++;
			}

			sort($draw);

			if ($row[sum] >= 0 && $row[sum] <= 9) {
				require ("$game_includes/d80.incl");
			} elseif ($row[sum] >= 10 && $row[sum] <= 19) {
				require ("$game_includes/d0.incl");
			} elseif ($row[sum] >= 20 && $row[sum] <= 29) {
				require ("$game_includes/d0.incl");
			} elseif ($row[sum] >= 30 && $row[sum] <= 39) {
				require ("$game_includes/d0.incl");
			} elseif ($row[sum] >= 40 && $row[sum] <= 49) {
				require ("$game_includes/d0.incl");
			} elseif ($row[sum] >= 50 && $row[sum] <= 59) {
				require ("$game_includes/d0.incl");
			} elseif ($row[sum] >= 60 && $row[sum] <= 69) {
				require ("$game_includes/d0.incl");
			} elseif ($row[sum] >= 70 && $row[sum] <= 79) {
				require ("$game_includes/d0.incl");
			} elseif ($row[sum] >= 80 && $row[sum] <= 89) {
				require ("$game_includes/d80.incl");
			} elseif ($row[sum] >= 90 && $row[sum] <= 99) {
				require ("$game_includes/d90.incl");
			} elseif ($row[sum] >= 100 && $row[sum] <= 109) {
				require ("$game_includes/d100.incl");
			} elseif ($row[sum] >= 110 && $row[sum] <= 119) {
				require ("$game_includes/d110.incl");
			} elseif ($row[sum] >= 120 && $row[sum] <= 129) {
				require ("$game_includes/d120.incl");
			} elseif ($row[sum] >= 130 && $row[sum] <= 139) {
				require ("$game_includes/d130.incl");
			} elseif ($row[sum] >= 140 && $row[sum] <= 149) {
				require ("$game_includes/d140.incl");
			} elseif ($row[sum] >= 150 && $row[sum] <= 159) {
				require ("$game_includes/d150.incl");
			} elseif ($row[sum] >= 160 && $row[sum] <= 169) {
				require ("$game_includes/d160.incl");
			} elseif ($row[sum] >= 170 && $row[sum] <= 179) {
				require ("$game_includes/d170.incl");
			} elseif ($row[sum] >= 180 && $row[sum] <= 189) {
				require ("$game_includes/d180.incl");
			} elseif ($row[sum] >= 190 && $row[sum] <= 199) {
				require ("$game_includes/d190.incl");
			} elseif ($row[sum] >= 200 && $row[sum] <= 209) {
				require ("$game_includes/d200.incl");
			} elseif ($row[sum] >= 210 && $row[sum] <= 219) {
				require ("$game_includes/d210.incl");
			} elseif ($row[sum] >= 220 && $row[sum] <= 229) {
				require ("$game_includes/d220.incl");
			} elseif ($row[sum] >= 230 && $row[sum] <= 239) {
				require ("$game_includes/d220.incl");
			} else {
				require ("includes/d0.incl");
			}

			$num_range = array_fill (0,6,0);

			$num_range_d501 = 0;
			$num_range_d502 = 0;
			
			// build draw array [0..x]
			$draw_array = array ();
	
			for ($x = 1; $x <= $balls_drawn; $x++)
			{
				array_push($draw_array, $row[$x]);
				$col_avg[$x] += $row[$x];
			}
	
			if ($mega_balls)
			{
				$MB = $row[mb];
			}

			$mod = array_fill (0, 10, 0);

			$mod2_count = 0;
			$mod3_count = 0;

			$sum = array_sum($draw_array);
			$sum_sum += $sum;

			// build ranges, $skip_pointer logic
			for($index = 0; $index < $balls_drawn-2; $index++)
			{	
				$number = $row[$index+1];

				if ($number < 10) {
					$num_range[0]++;
					$mod[$number]++;
				}
				elseif ($number > 9 && $number < 20) {
					$num_range[1]++;
					$y = $number - 10;
					$mod[$y]++;
				}
				elseif ($number > 19 && $number < 30) {
					$num_range[2]++;
					$y = $number - 20;
					$mod[$y]++;
				}
				elseif ($number > 29 && $number < 40) {
					$num_range[3]++;
					$y = $number - 30;
					$mod[$y]++;
				}
				elseif ($number > 39 && $number < 50) {
					$num_range[4]++;
					$y = $number - 40;
					$mod[$y]++;
				}
				else {
					$num_range[5]++;
					$y = $number - 50;
					$mod[$y]++;
				}

				$temp = intval($balls/2);

				if ($number <= $temp) { 
					$num_range_d501++;
				}
				else {
					$num_range_d502++;
				}
			}

			for ($x = 0; $x < $draw_columns_count; $x++)
			{
				$num_range_sum[$x] += $num_range[$x];
			}

			#$num_range_sum[0] += $num_range[0];
			#$num_range_sum[1] += $num_range[1];
			#$num_range_sum[2] += $num_range[2];
			#$num_range_sum[3] += $num_range[3];
			#$num_range_sum[4] += $num_range[4];
			#$num_range_sum[5] += $num_range[5];
			$num_range_d501_sum += $num_range_d501;
			$num_range_d502_sum += $num_range_d502;

			$even = 0;
			$odd = 0;

			even_odd($draw_array, $even, $odd); 

			$even_sum += $even;
			$odd_sum += $odd;

			for ($x = 0; $x <= 9; $x++)
			{
				if ($mod[$x] > 1)
				{
					$mod2_count++;
				}
				if ($mod[$x] > 2)
				{
					$mod3_count++; 
				}
			}

			$mod2_count_sum += $mod2_count;
			$mod3_count_sum += $mod3_count;
			
			print("<TR>\n");

			print("<TD nowrap><font size=\"-1\">$row[date]</font></TD>\n");

			for ($y = 1; $y <= $balls_drawn; $y++)
			{
				print("<TD><center>$row[$y]</center></TD>\n");
			}
	
			if ($mega_balls)
			{
				print("<TD><center>$row[mb]</center></TD>\n");
			}

			if ($row[sum] < $sum_low || $row[sum] > $sum_high)
			{
				print("<TD align=center BGCOLOR=\"#ff0000\"><font color=\"#ffffff\"><b>$row[sum]</b></font></TD>\n");
			} else {
				print("<TD align=center>$row[sum]</TD>\n");
			}

			if ($even <= 1 || $even >= $balls_drawn-1) {
				print("<TD BGCOLOR=\"#ff0000\"><strong><CENTER><font color=\"#ffffff\">$even/$odd</font></CENTER></strong></TD>\n");
			} else {
				print("<TD BGCOLOR=\"#99CCFF\"><CENTER>$even/$odd</CENTER></TD>\n");
			}

			for ($x = 0; $x <= intval($balls/10)-1; $x++)
			{
				if ($num_range[$x] > 3) {
					print("<TD BGCOLOR=\"#ff0000\"><CENTER><font color=\"#ffffff\"><B>$num_range[$x]</B></font></CENTER></TD>\n");
				} else {
					print("<TD><CENTER>$num_range[$x]</CENTER></TD>\n");
				}
			}

			if ($num_range[intval($balls/10)] > 1) {
					print("<TD BGCOLOR=\"#ff0000\"><CENTER><font color=\"#ffffff\"><B>$num_range[$x]</B></font></CENTER></TD>\n");
				} else {
					print("<TD><CENTER>$num_range[$x]</CENTER></TD>\n");
			}
			
			if ($num_range_d501 == 1 || $num_range_d501 >= $balls_drawn-1) {
				print("<TD BGCOLOR=\"#ff0000\"><CENTER><B><font color=\"#ffffff\">$num_range_d501/$num_range_d502</font></B></CENTER></TD>\n");
			} else {
				print("<TD BGCOLOR=\"#99CCFF\"><CENTER>$num_range_d501/$num_range_d502</CENTER></TD>\n");
			}

			// build rank table - last 26

			$rank_count = array (0);

			$rank_count = BuildRankTable($row[date]); // array 0..balls with total draws for last 26

			$rank_table_count = array_fill (0, 7, 0);

			for($z = 1; $z <= $balls; $z++)
			{
				if ($rank_count[$z] >= 6)
				{
					$rank_table_count[6]++;
				} else {
					$rank_table_count[$rank_count[$z]]++;
				}
			}

			$draw_rank_count = array_fill (0, 7, 0);

			for($y = 0; $y <= $balls_drawn-2; $y++)
			{
				if ($rank_count[$draw_array[$y]] >= 6)
				{
					$draw_rank_count[6]++;
				} else {
					$draw_rank_count[$draw_array[$row[$y]]]++;
				}
			}

			for($x = 0; $x <= 6; $x++)
			{
				$draw_rank_sum[$x] += $draw_rank_count[$x];
			}

			// add loop below

			if ($draw_rank_count[0] > 0) {
				print("<TD><CENTER><font color=\"#ff0000\"><B>$draw_rank_count[0]/$rank_table_count[0]</B></font></CENTER></TD>\n");
			} else {
				print("<TD><CENTER>$draw_rank_count[0]/$rank_table_count[0]</CENTER></TD>\n");
			}
			if ($draw_rank_count[1] > 1) {
				print("<TD><CENTER><font color=\"#ff0000\"><B>$draw_rank_count[1]/$rank_table_count[1]</B></font></CENTER></TD>\n");
			} else {
				print("<TD><CENTER>$draw_rank_count[1]/$rank_table_count[1]</CENTER></TD>\n");
			}
			if ($draw_rank_count[2] > 2) {
				print("<TD><CENTER><font color=\"#ff0000\"><B>$draw_rank_count[2]/$rank_table_count[2]</B></font></CENTER></TD>\n");
			} else {
				print("<TD><CENTER>$draw_rank_count[2]/$rank_table_count[2]</CENTER></TD>\n");
			}
			if ($draw_rank_count[3] > 2) {
				print("<TD><CENTER><font color=\"#ff0000\"><B>$draw_rank_count[3]/$rank_table_count[3]</B></font></CENTER></TD>\n");
			} else {
				print("<TD><CENTER>$draw_rank_count[3]/$rank_table_count[3]</CENTER></TD>\n");
			}
			if ($draw_rank_count[4] > 2) {
				print("<TD><CENTER><font color=\"#ff0000\"><B>$draw_rank_count[4]/$rank_table_count[4]</B></font></CENTER></TD>\n");
			} else {
				print("<TD><CENTER>$draw_rank_count[4]/$rank_table_count[4]</CENTER></TD>\n");
			}
			if ($draw_rank_count[5] > 2) {
				print("<TD><CENTER><font color=\"#ff0000\"><B>$draw_rank_count[5]/$rank_table_count[5]</B></font></CENTER></TD>\n");
			} else {
				print("<TD><CENTER>$draw_rank_count[5]/$rank_table_count[5]</CENTER></TD>\n");
			}
			if ($draw_rank_count[6] > 1) {
				print("<TD><CENTER><font color=\"#ff0000\"><B>$draw_rank_count[6]/$rank_table_count[6]</B></font></CENTER></TD>\n");
			} else {
				print("<TD><CENTER>$draw_rank_count[6]/$rank_table_count[6]</CENTER></TD>\n");
			}

			$seq2 = Count2Seq($draw_array);

			if ($seq2 > 1) {
				print("<TD BGCOLOR=\"#ff0000\"><CENTER><B><FONT COLOR=\"#ffffff\">$seq2</FONT></B></CENTER></TD>\n");
				$seq2_avg += $seq2;
			} else {
				print("<TD BGCOLOR=\"#99CCFF\"><CENTER>$seq2</CENTER></TD>\n");
			}

			$seq3 = Count3Seq($draw_array);

			if ($seq3 > 0) {
				print("<TD BGCOLOR=\"#ff0000\"><CENTER><B><FONT COLOR=\"#ffffff\">$seq3</FONT></B></CENTER></TD>\n");
				$seq3_tot++;
				$seq3_avg += $seq3;
			} else {
				print("<TD><CENTER>$seq3</CENTER></TD>\n");
			}

			//$rank_mb = calculate_rank_mb($row[date], $row[mb]); // check fields

			//print("calculate_rank_mb complete<p>");

			if ($mod2_count > 1) {
				print("<TD BGCOLOR=\"#ff0000\"><CENTER><B><font color=\"#ffffff\">$mod2_count</font></B></CENTER></TD>\n");
			} else {
				print("<TD BGCOLOR=\"#99CCFF\"><CENTER>$mod2_count</CENTER></TD>\n");
			}

			if ($mod3_count > 0) {
				print("<TD><CENTER><B>$mod3_count</B></CENTER></TD>\n");
			} else {
				print("<TD><CENTER>$mod3_count</CENTER></TD>\n");
			}

			$mod2_avg += $mod2_count;
			$mod3_avg += $mod3_count;

			test_combin_date($row[date],$comp_avg,$total_failed);

			for ($z = 0; $z <= 6; $z++)
			{
				$comp_avg_tot[$z] += $comp_avg[$z];
			}

			for ($z = 0; $z <= 6; $z++)
			{
				$total_failed_tot[$z] += $total_failed[$z];
			}
			
			print("</TR>\n");

			$average_count++;
		}

		print_table_footer ($average_count,$balls_drawn,$sum_sum,$even_sum,$odd_sum,$num_range_sum,$draw_columns_count,
							$num_range_sum,$num_range_d501_sum,$num_range_d502_sum,$draw_rank_sum,$limit,$comp_avg_tot,
							$col_avg,$seq2_avg,$seq3_avg,$mod2_avg,$mod3_avg,$total_failed_tot);
	}

	function print_draw_five($draw_array,$draw_code)
	{
		global $draw_table_name, $balls, $balls_drawn, $game_includes, $draw_prefix, $game_includes, $average_count, $number_count,
				$comp_avg,$total_failed; 

		require ("includes/mysqli.php"); 

		require ("$game_includes/config.incl");

		$average_count = 0;
		$sum_sum = 0;
		$even_sum = 0;
		$odd_sum = 0;
		$num_range0_sum = 0;
		$num_range1_sum = 0;
		$num_range2_sum = 0;
		$num_range3_sum = 0;
		$num_range4_sum = 0;
		$num_range_d501_sum = 0;
		$num_range_d502_sum = 0;
		$mod2_count_sum = 0;
		$mod3_count_sum = 0;
		$draw_rank_sum = array_fill (0,7,0);
		$draw = array_fill (0,$balls_drawn-1,0);
		$comp_avg = array_fill (0,7,0);
		$comp_avg_tot = array_fill (0,7,0);
		$col_avg = array_fill (0,7,0);
		$seq2_avg = 0;
		$seq3_avg = 0;
		$mod2_avg = 0;
		$mod3_avg = 0;
		$total_failed = array_fill (0,7,0);
		$total_failed_tot = array_fill (0,7,0);

		$draw_columns_count = intval($balls/10) + 1;

		$col_sum = array_fill (0, 7, $draw_rank_sum);

		/* change - calculate values */

		while($row = mysqli_fetch_array($mysqli_result))
		{
			#$y = 1;
			#for ($x = 0; $x < $balls_drawn; $x++)
			#{
			#	$draw[$x] = $row[b.$y];
			#	$y++;
			#}

			sort($draw_array);

			$num_range = array_fill (0,6,0);

			$num_range_d501 = 0;
			$num_range_d502 = 0;
			
			// build draw array [0..x]
			#$draw_array = array ();
	
			#for ($x = 1; $x <= $balls_drawn; $x++)
			#{
			#	array_push($draw_array, $row[$x]);
			#	$col_avg[$x] += $row[$x];
			#}
	
			if ($mega_balls)
			{
				$MB = $row[mb];
			}

			$mod = array_fill (0, 10, 0);

			$mod2_count = 0;
			$mod3_count = 0;

			$sum = array_sum($draw_array);
			$sum_sum += $sum; # ????

			// build ranges, $skip_pointer logic
			for($index = 0; $index < 5; $index++)
			{	
				$number = $draw_array[$index]; # ???

				if ($number < 10) {
					$num_range[0]++;
					$mod[$number]++;
				}
				elseif ($number > 9 && $number < 20) {
					$num_range[1]++;
					$y = $number - 10;
					$mod[$y]++;
				}
				elseif ($number > 19 && $number < 30) {
					$num_range[2]++;
					$y = $number - 20;
					$mod[$y]++;	
				}
				elseif ($number > 29 && $number < 40) {
					$num_range[3]++;
					$y = $number - 30;
					$mod[$y]++;
				}
				elseif ($number > 39 && $number < 50) {
					$num_range[4]++;
					$y = $number - 40;
					$mod[$y]++;
				}
				else {
					$num_range[5]++;
					$y = $number - 50;
					$mod[$y]++;
				}

				$temp = intval($balls/2);

				if ($number <= $temp) { 
					$num_range_d501++;
				}
				else {
					$num_range_d502++;
				}
			}

			for ($x = 0; $x < $draw_columns_count; $x++)
			{
				$num_range_sum[$x] += $num_range[$x];
			}

			$num_range_d501_sum += $num_range_d501;
			$num_range_d502_sum += $num_range_d502;

			$even = 0;
			$odd = 0;

			even_odd($draw_array, $even, $odd); 

			$even_sum += $even;
			$odd_sum += $odd;

			for ($x = 0; $x <= 9; $x++)
			{
				if ($mod[$x] > 1)
				{
					$mod2_count++;
				}
				if ($mod[$x] > 2)
				{
					$mod3_count++; 
				}
			}

			$mod2_count_sum += $mod2_count;
			$mod3_count_sum += $mod3_count;
			
			print("<TR>\n");

			print("<TD nowrap><font size=\"-1\">$draw_code</font></TD>\n");

			for ($y = 1; $y <= 5; $y++)
			{
				print("<TD><center>$row[$y]</center></TD>\n");
			}

			print("<TD><center>---</center></TD>\n");
	
			if ($mega_balls)
			{
				print("<TD><center>$row[mb]</center></TD>\n");
			}

			#if ($row[sum] < $sum_low || $row[sum] > $sum_high)
			#{
			#	print("<TD align=center BGCOLOR=\"#ff0000\"><font color=\"#ffffff\"><b>$row[sum]</b></font></TD>\n");
			#} else {
				print("<TD align=center>$row[sum]</TD>\n");
			#}

			if ($even <= 1 || $even >= $balls_drawn-1) {
				print("<TD BGCOLOR=\"#ff0000\"><strong><CENTER><font color=\"#ffffff\">$even/$odd</font></CENTER></strong></TD>\n");
			} else {
				print("<TD BGCOLOR=\"#99CCFF\"><CENTER>$even/$odd</CENTER></TD>\n");
			}

			for ($x = 0; $x <= intval($balls/10)-1; $x++)
			{
				if ($num_range[$x] > 3) {
					print("<TD BGCOLOR=\"#ff0000\"><CENTER><font color=\"#ffffff\"><B>$num_range[$x]</B></font></CENTER></TD>\n");
				} else {
					print("<TD><CENTER>$num_range[$x]</CENTER></TD>\n");
				}
			}

			if ($num_range[intval($balls/10)] > 1) {
					print("<TD BGCOLOR=\"#ff0000\"><CENTER><font color=\"#ffffff\"><B>$num_range[$x]</B></font></CENTER></TD>\n");
				} else {
					print("<TD><CENTER>$num_range[$x]</CENTER></TD>\n");
			}
			
			if ($num_range_d501 == 1 || $num_range_d501 >= $balls_drawn-1) {
				print("<TD BGCOLOR=\"#ff0000\"><CENTER><B><font color=\"#ffffff\">$num_range_d501/$num_range_d502</font></B></CENTER></TD>\n");
			} else {
				print("<TD BGCOLOR=\"#99CCFF\"><CENTER>$num_range_d501/$num_range_d502</CENTER></TD>\n");
			}

			// build rank table - last 26

			$rank_count = array (0);

			$rank_count = BuildRankTable($row[date]); // array 0..balls with total draws for last 26

			$rank_table_count = array_fill (0, 7, 0);

			for($z = 1; $z <= $balls; $z++)
			{
				if ($rank_count[$z] >= 6)
				{
					$rank_table_count[6]++;
				} else {
					$rank_table_count[$rank_count[$z]]++;
				}
			}

			$draw_rank_count = array_fill (0, 7, 0);

			for($y = 0; $y < 5; $y++)
			{
				if ($rank_count[$draw_array[$y]] >= 6)
				{
					$draw_rank_count[6]++;
				} else {
					$draw_rank_count[$rank_count[$draw_array[$y]]]++;
				}
			}

			for($x = 0; $x <= 6; $x++)
			{
				$draw_rank_sum[$x] += $draw_rank_count[$x];
			}

			// add loop below

			if ($draw_rank_count[0] > 0) {
				print("<TD><CENTER><font color=\"#ff0000\"><B>$draw_rank_count[0]/$rank_table_count[0]</B></font></CENTER></TD>\n");
			} else {
				print("<TD><CENTER>$draw_rank_count[0]/$rank_table_count[0]</CENTER></TD>\n");
			}
			if ($draw_rank_count[1] > 1) {
				print("<TD><CENTER><font color=\"#ff0000\"><B>$draw_rank_count[1]/$rank_table_count[1]</B></font></CENTER></TD>\n");
			} else {
				print("<TD><CENTER>$draw_rank_count[1]/$rank_table_count[1]</CENTER></TD>\n");
			}
			if ($draw_rank_count[2] > 2) {
				print("<TD><CENTER><font color=\"#ff0000\"><B>$draw_rank_count[2]/$rank_table_count[2]</B></font></CENTER></TD>\n");
			} else {
				print("<TD><CENTER>$draw_rank_count[2]/$rank_table_count[2]</CENTER></TD>\n");
			}
			if ($draw_rank_count[3] > 2) {
				print("<TD><CENTER><font color=\"#ff0000\"><B>$draw_rank_count[3]/$rank_table_count[3]</B></font></CENTER></TD>\n");
			} else {
				print("<TD><CENTER>$draw_rank_count[3]/$rank_table_count[3]</CENTER></TD>\n");
			}
			if ($draw_rank_count[4] > 2) {
				print("<TD><CENTER><font color=\"#ff0000\"><B>$draw_rank_count[4]/$rank_table_count[4]</B></font></CENTER></TD>\n");
			} else {
				print("<TD><CENTER>$draw_rank_count[4]/$rank_table_count[4]</CENTER></TD>\n");
			}
			if ($draw_rank_count[5] > 2) {
				print("<TD><CENTER><font color=\"#ff0000\"><B>$draw_rank_count[5]/$rank_table_count[5]</B></font></CENTER></TD>\n");
			} else {
				print("<TD><CENTER>$draw_rank_count[5]/$rank_table_count[5]</CENTER></TD>\n");
			}
			if ($draw_rank_count[6] > 1) {
				print("<TD><CENTER><font color=\"#ff0000\"><B>$draw_rank_count[6]/$rank_table_count[6]</B></font></CENTER></TD>\n");
			} else {
				print("<TD><CENTER>$draw_rank_count[6]/$rank_table_count[6]</CENTER></TD>\n");
			}

			$seq2 = Count2Seq($draw_array);

			if ($seq2 > 1) {
				print("<TD BGCOLOR=\"#ff0000\"><CENTER><B><FONT COLOR=\"#ffffff\">$seq2</FONT></B></CENTER></TD>\n");
				$seq2_avg += $seq2;
			} else {
				print("<TD BGCOLOR=\"#99CCFF\"><CENTER>$seq2</CENTER></TD>\n");
			}

			$seq3 = Count3Seq($draw_array);

			if ($seq3 > 0) {
				print("<TD BGCOLOR=\"#ff0000\"><CENTER><B><FONT COLOR=\"#ffffff\">$seq3</FONT></B></CENTER></TD>\n");
				$seq3_tot++;
				$seq3_avg += $seq3;
			} else {
				print("<TD><CENTER>$seq3</CENTER></TD>\n");
			}

			//$rank_mb = calculate_rank_mb($row[date], $row[mb]); // check fields

			//print("calculate_rank_mb complete<p>");

			if ($mod2_count > 1) {
				print("<TD BGCOLOR=\"#ff0000\"><CENTER><B><font color=\"#ffffff\">$mod2_count</font></B></CENTER></TD>\n");
			} else {
				print("<TD BGCOLOR=\"#99CCFF\"><CENTER>$mod2_count</CENTER></TD>\n");
			}

			if ($mod3_count > 0) {
				print("<TD><CENTER><B>$mod3_count</B></CENTER></TD>\n");
			} else {
				print("<TD><CENTER>$mod3_count</CENTER></TD>\n");
			}

			$mod2_avg += $mod2_count;
			$mod3_avg += $mod3_count;

			test_combin_date("$row[date]",$comp_avg,$total_failed);

			for ($z = 0; $z <= 6; $z++)
			{
				$comp_avg_tot[$z] += $comp_avg[$z];
			}

			for ($z = 0; $z <= 6; $z++)
			{
				$total_failed_tot[$z] += $total_failed[$z];
			}
			
			print("</TR>\n");

			$average_count++;
		}

		print_table_footer ($average_count,$balls_drawn,$sum_sum,$even_sum,$odd_sum,$num_range_sum,$draw_columns_count,
							$num_range_sum,$num_range_d501_sum,$num_range_d502_sum,$draw_rank_sum,$limit,$comp_avg_tot,
							$col_avg,$seq2_avg,$seq3_avg,$mod2_avg,$mod3_avg,$total_failed_tot);
		
		print("</TABLE>\n");
	}

	function print_draw_four($draw_array,$draw_code)
	{
		global $draw_table_name, $balls, $balls_drawn, $game_includes, $draw_prefix, $game_includes, $average_count, $number_count,
				$comp_avg,$total_failed; 

		require ("includes/mysqli.php"); 

		require ("$game_includes/config.incl");

		$average_count = 0;
		$sum_sum = 0;
		$even_sum = 0;
		$odd_sum = 0;
		$num_range0_sum = 0;
		$num_range1_sum = 0;
		$num_range2_sum = 0;
		$num_range3_sum = 0;
		$num_range4_sum = 0;
		$num_range_d501_sum = 0;
		$num_range_d502_sum = 0;
		$mod2_count_sum = 0;
		$mod3_count_sum = 0;
		$draw_rank_sum = array_fill (0,7,0);
		$draw = array_fill (0,$balls_drawn-1,0);
		$comp_avg = array_fill (0,7,0);
		$comp_avg_tot = array_fill (0,7,0);
		$col_avg = array_fill (0,7,0);
		$seq2_avg = 0;
		$seq3_avg = 0;
		$mod2_avg = 0;
		$mod3_avg = 0;
		$total_failed = array_fill (0,7,0);
		$total_failed_tot = array_fill (0,7,0);

		$draw_columns_count = intval($balls/10) + 1;

		$col_sum = array_fill (0, 7, $draw_rank_sum);

		/* change - calculate values */

		while($row = mysqli_fetch_array($mysqli_result))
		{

			$y = 1;
			for ($x = 0; $x < $balls_drawn; $x++)
			{
				$draw[$x] = $row[b.$y];
				$y++;
			}

			sort($draw);

			if ($row[sum] >= 0 && $row[sum] <= 9) {
				require ("$game_includes/d80.incl");
			} elseif ($row[sum] >= 10 && $row[sum] <= 19) {
				require ("$game_includes/d0.incl");
			} elseif ($row[sum] >= 20 && $row[sum] <= 29) {
				require ("$game_includes/d0.incl");
			} elseif ($row[sum] >= 30 && $row[sum] <= 39) {
				require ("$game_includes/d0.incl");
			} elseif ($row[sum] >= 40 && $row[sum] <= 49) {
				require ("$game_includes/d0.incl");
			} elseif ($row[sum] >= 50 && $row[sum] <= 59) {
				require ("$game_includes/d0.incl");
			} elseif ($row[sum] >= 60 && $row[sum] <= 69) {
				require ("$game_includes/d0.incl");
			} elseif ($row[sum] >= 70 && $row[sum] <= 79) {
				require ("$game_includes/d0.incl");
			} elseif ($row[sum] >= 80 && $row[sum] <= 89) {
				require ("$game_includes/d80.incl");
			} elseif ($row[sum] >= 90 && $row[sum] <= 99) {
				require ("$game_includes/d90.incl");
			} elseif ($row[sum] >= 100 && $row[sum] <= 109) {
				require ("$game_includes/d100.incl");
			} elseif ($row[sum] >= 110 && $row[sum] <= 119) {
				require ("$game_includes/d110.incl");
			} elseif ($row[sum] >= 120 && $row[sum] <= 129) {
				require ("$game_includes/d120.incl");
			} elseif ($row[sum] >= 130 && $row[sum] <= 139) {
				require ("$game_includes/d130.incl");
			} elseif ($row[sum] >= 140 && $row[sum] <= 149) {
				require ("$game_includes/d140.incl");
			} elseif ($row[sum] >= 150 && $row[sum] <= 159) {
				require ("$game_includes/d150.incl");
			} elseif ($row[sum] >= 160 && $row[sum] <= 169) {
				require ("$game_includes/d160.incl");
			} elseif ($row[sum] >= 170 && $row[sum] <= 179) {
				require ("$game_includes/d170.incl");
			} elseif ($row[sum] >= 180 && $row[sum] <= 189) {
				require ("$game_includes/d180.incl");
			} elseif ($row[sum] >= 190 && $row[sum] <= 199) {
				require ("$game_includes/d190.incl");
			} elseif ($row[sum] >= 200 && $row[sum] <= 209) {
				require ("$game_includes/d200.incl");
			} elseif ($row[sum] >= 210 && $row[sum] <= 219) {
				require ("$game_includes/d210.incl");
			} elseif ($row[sum] >= 220 && $row[sum] <= 229) {
				require ("$game_includes/d220.incl");
			} elseif ($row[sum] >= 230 && $row[sum] <= 239) {
				require ("$game_includes/d220.incl");
			} else {
				require ("includes/d0.incl");
			}

			$num_range = array_fill (0,6,0);

			$num_range_d501 = 0;
			$num_range_d502 = 0;
			
			// build draw array [0..x]
			#$draw_array = array ();
	
			#for ($x = 1; $x <= $balls_drawn; $x++)
			#{
			#	array_push($draw_array, $row[$x]);
			#	$col_avg[$x] += $row[$x];
			#}
	
			if ($mega_balls)
			{
				$MB = $row[mb];
			}

			$mod = array_fill (0, 10, 0);

			$mod2_count = 0;
			$mod3_count = 0;

			$sum = array_sum($draw_array);
			$sum_sum += $sum; # ????

			// build ranges, $skip_pointer logic
			for($index = 0; $index < $balls_drawn-1; $index++)
			{	
				$number = $draw_array[$index+1];

				if ($number < 10) {
					$num_range[0]++;
					$mod[$number]++;
				}
				elseif ($number > 9 && $number < 20) {
					$num_range[1]++;
					$y = $number - 10;
					$mod[$y]++;
				}
				elseif ($number > 19 && $number < 30) {
					$num_range[2]++;
					$y = $number - 20;
					$mod[$y]++;
				}
				elseif ($number > 29 && $number < 40) {
					$num_range[3]++;
					$y = $number - 30;
					$mod[$y]++;
				}
				elseif ($number > 39 && $number < 50) {
					$num_range[4]++;
					$y = $number - 40;
					$mod[$y]++;
				}
				else {
					$num_range[5]++;
					$y = $number - 50;
					$mod[$y]++;
				}

				$temp = intval($balls/2);

				if ($number <= $temp) { 
					$num_range_d501++;
				}
				else {
					$num_range_d502++;
				}
			}

			for ($x = 0; $x < $draw_columns_count; $x++)
			{
				$num_range_sum[$x] += $num_range[$x];
			}

			$num_range_d501_sum += $num_range_d501;
			$num_range_d502_sum += $num_range_d502;

			$even = 0;
			$odd = 0;

			even_odd($draw_array, $even, $odd); 

			$even_sum += $even;
			$odd_sum += $odd;

			for ($x = 0; $x <= 9; $x++)
			{
				if ($mod[$x] > 1)
				{
					$mod2_count++;
				}
				if ($mod[$x] > 2)
				{
					$mod3_count++; 
				}
			}

			$mod2_count_sum += $mod2_count;
			$mod3_count_sum += $mod3_count;
			
			print("<TR>\n");

			print("<TD nowrap><font size=\"-1\">$draw_code</font></TD>\n");

			for ($y = 1; $y <= $balls_drawn-1; $y++)
			{
				print("<TD><center>$row[$y]</center></TD>\n");
			}

			print("<TD><center>---</center></TD>\n");
	
			if ($mega_balls)
			{
				print("<TD><center>$row[mb]</center></TD>\n");
			}

			#if ($row[sum] < $sum_low || $row[sum] > $sum_high)
			#{
			#	print("<TD align=center BGCOLOR=\"#ff0000\"><font color=\"#ffffff\"><b>$row[sum]</b></font></TD>\n");
			#} else {
				print("<TD align=center>$row[sum]</TD>\n");
			#}

			if ($even <= 1 || $even >= $balls_drawn-1) {
				print("<TD BGCOLOR=\"#ff0000\"><strong><CENTER><font color=\"#ffffff\">$even/$odd</font></CENTER></strong></TD>\n");
			} else {
				print("<TD BGCOLOR=\"#99CCFF\"><CENTER>$even/$odd</CENTER></TD>\n");
			}

			for ($x = 0; $x <= intval($balls/10)-1; $x++)
			{
				if ($num_range[$x] > 3) {
					print("<TD BGCOLOR=\"#ff0000\"><CENTER><font color=\"#ffffff\"><B>$num_range[$x]</B></font></CENTER></TD>\n");
				} else {
					print("<TD><CENTER>$num_range[$x]</CENTER></TD>\n");
				}
			}

			if ($num_range[intval($balls/10)] > 1) {
					print("<TD BGCOLOR=\"#ff0000\"><CENTER><font color=\"#ffffff\"><B>$num_range[$x]</B></font></CENTER></TD>\n");
				} else {
					print("<TD><CENTER>$num_range[$x]</CENTER></TD>\n");
			}
			
			if ($num_range_d501 == 1 || $num_range_d501 >= $balls_drawn-1) {
				print("<TD BGCOLOR=\"#ff0000\"><CENTER><B><font color=\"#ffffff\">$num_range_d501/$num_range_d502</font></B></CENTER></TD>\n");
			} else {
				print("<TD BGCOLOR=\"#99CCFF\"><CENTER>$num_range_d501/$num_range_d502</CENTER></TD>\n");
			}

			// build rank table - last 26

			$rank_count = array (0);

			$rank_count = BuildRankTable($row[date]); // array 0..balls with total draws for last 26

			$rank_table_count = array_fill (0, 7, 0);

			for($z = 1; $z <= $balls; $z++)
			{
				if ($rank_count[$z] >= 6)
				{
					$rank_table_count[6]++;
				} else {
					$rank_table_count[$rank_count[$z]]++;
				}
			}

			$draw_rank_count = array_fill (0, 7, 0);

			for($y = 1; $y <= $balls_drawn; $y++)
			{
				if ($rank_count[$row[$y]] >= 6)
				{
					$draw_rank_count[6]++;
				} else {
					$draw_rank_count[$rank_count[$row[$y]]]++;
				}
			}

			for($x = 0; $x <= 6; $x++)
			{
				$draw_rank_sum[$x] += $draw_rank_count[$x];
			}

			// add loop below

			if ($draw_rank_count[0] > 0) {
				print("<TD><CENTER><font color=\"#ff0000\"><B>$draw_rank_count[0]/$rank_table_count[0]</B></font></CENTER></TD>\n");
			} else {
				print("<TD><CENTER>$draw_rank_count[0]/$rank_table_count[0]</CENTER></TD>\n");
			}
			if ($draw_rank_count[1] > 1) {
				print("<TD><CENTER><font color=\"#ff0000\"><B>$draw_rank_count[1]/$rank_table_count[1]</B></font></CENTER></TD>\n");
			} else {
				print("<TD><CENTER>$draw_rank_count[1]/$rank_table_count[1]</CENTER></TD>\n");
			}
			if ($draw_rank_count[2] > 2) {
				print("<TD><CENTER><font color=\"#ff0000\"><B>$draw_rank_count[2]/$rank_table_count[2]</B></font></CENTER></TD>\n");
			} else {
				print("<TD><CENTER>$draw_rank_count[2]/$rank_table_count[2]</CENTER></TD>\n");
			}
			if ($draw_rank_count[3] > 2) {
				print("<TD><CENTER><font color=\"#ff0000\"><B>$draw_rank_count[3]/$rank_table_count[3]</B></font></CENTER></TD>\n");
			} else {
				print("<TD><CENTER>$draw_rank_count[3]/$rank_table_count[3]</CENTER></TD>\n");
			}
			if ($draw_rank_count[4] > 2) {
				print("<TD><CENTER><font color=\"#ff0000\"><B>$draw_rank_count[4]/$rank_table_count[4]</B></font></CENTER></TD>\n");
			} else {
				print("<TD><CENTER>$draw_rank_count[4]/$rank_table_count[4]</CENTER></TD>\n");
			}
			if ($draw_rank_count[5] > 2) {
				print("<TD><CENTER><font color=\"#ff0000\"><B>$draw_rank_count[5]/$rank_table_count[5]</B></font></CENTER></TD>\n");
			} else {
				print("<TD><CENTER>$draw_rank_count[5]/$rank_table_count[5]</CENTER></TD>\n");
			}
			if ($draw_rank_count[6] > 1) {
				print("<TD><CENTER><font color=\"#ff0000\"><B>$draw_rank_count[6]/$rank_table_count[6]</B></font></CENTER></TD>\n");
			} else {
				print("<TD><CENTER>$draw_rank_count[6]/$rank_table_count[6]</CENTER></TD>\n");
			}

			$seq2 = Count2Seq($draw);

			if ($seq2 > 1) {
				print("<TD BGCOLOR=\"#ff0000\"><CENTER><B><FONT COLOR=\"#ffffff\">$seq2</FONT></B></CENTER></TD>\n");
				$seq2_avg += $seq2;
			} else {
				print("<TD BGCOLOR=\"#99CCFF\"><CENTER>$seq2</CENTER></TD>\n");
			}

			$seq3 = Count3Seq($draw);

			if ($seq3 > 0) {
				print("<TD BGCOLOR=\"#ff0000\"><CENTER><B><FONT COLOR=\"#ffffff\">$seq3</FONT></B></CENTER></TD>\n");
				$seq3_tot++;
				$seq3_avg += $seq3;
			} else {
				print("<TD><CENTER>$seq3</CENTER></TD>\n");
			}

			//$rank_mb = calculate_rank_mb($row[date], $row[mb]); // check fields

			//print("calculate_rank_mb complete<p>");

			if ($mod2_count > 1) {
				print("<TD BGCOLOR=\"#ff0000\"><CENTER><B><font color=\"#ffffff\">$mod2_count</font></B></CENTER></TD>\n");
			} else {
				print("<TD BGCOLOR=\"#99CCFF\"><CENTER>$mod2_count</CENTER></TD>\n");
			}

			if ($mod3_count > 0) {
				print("<TD><CENTER><B>$mod3_count</B></CENTER></TD>\n");
			} else {
				print("<TD><CENTER>$mod3_count</CENTER></TD>\n");
			}

			$mod2_avg += $mod2_count;
			$mod3_avg += $mod3_count;

			test_combin_date($row[date],$comp_avg,$total_failed);

			for ($z = 0; $z <= 6; $z++)
			{
				$comp_avg_tot[$z] += $comp_avg[$z];
			}

			for ($z = 0; $z <= 6; $z++)
			{
				$total_failed_tot[$z] += $total_failed[$z];
			}
			
			print("</TR>\n");

			$average_count++;
		}

		print_table_footer ($average_count,$balls_drawn,$sum_sum,$even_sum,$odd_sum,$num_range_sum,$draw_columns_count,
							$num_range_sum,$num_range_d501_sum,$num_range_d502_sum,$draw_rank_sum,$limit,$comp_avg_tot,
							$col_avg,$seq2_avg,$seq3_avg,$mod2_avg,$mod3_avg,$total_failed_tot);
		
		print("</TABLE>\n");
	}

	function print_table_footer($average_count,$balls_drawn,$sum_sum,$even_sum,$odd_sum,$num_range_sum,$draw_columns_count,
								$num_range_sum,$num_range_d501_sum,$num_range_d502_sum,$draw_rank_sum,$limit,$comp_avg_tot,
								$col_avg,$seq2_avg,$seq3_avg,$mod2_avg,$mod3_avg,$total_failed_tot)
	{
		
		print("<TR>\n");	
		print("<TD align=\"center\"><font size=\"-1\">Avg $limit</font></TD>\n");

		$num_temp = number_format($col_avg[1]/$limit,1);
		print("<TD align=\"center\">$num_temp</TD>\n");
		$num_temp = number_format($col_avg[2]/$limit,1);
		print("<TD align=\"center\">$num_temp</TD>\n");
		$num_temp = number_format($col_avg[3]/$limit,1);
		print("<TD align=\"center\">$num_temp</TD>\n");
		$num_temp = number_format($col_avg[4]/$limit,1);
		print("<TD align=\"center\">$num_temp</TD>\n");
		$num_temp = number_format($col_avg[5]/$limit,1);
		print("<TD align=\"center\">$num_temp</TD>\n");
		$num_temp = number_format($col_avg[6]/$limit,1);
		print("<TD align=\"center\">$num_temp</TD>\n");
			
		$num_temp = number_format($sum_sum/$average_count,2);
		print("<TD>$num_temp</TD>\n");
		$number_count = $average_count*$balls_drawn;

		$num_temp = number_format($even_sum/$number_count,2)*100;
		$num_temp2 = number_format($odd_sum/$number_count,2)*100;
		print("<TD BGCOLOR=\"#99CCFFa\"><CENTER>$num_temp/$num_temp2%</CENTER></TD>\n");

		for ($x = 0; $x < $draw_columns_count; $x++)
		{
			#$num_temp = number_format($num_range_sum[$x]/$number_count,2)*100;
			$num_temp = number_format($num_range_sum[$x]/$limit,1);
			print("<TD><CENTER>$num_temp</CENTER></TD>\n");
		}

		$num_temp1 = number_format($num_range_d501_sum/$number_count,2)*100;
		$num_temp2 = number_format($num_range_d502_sum/$number_count,2)*100;
		print("<TD BGCOLOR=\"#99CCFFa\"><CENTER>$num_temp1/$num_temp2%</CENTER></TD>\n");
		#print("<TD>&nbsp;</TD>\n");
		#$num_temp = number_format($draw_rank_sum[0]/$number_count,2)*100;
		$num_temp = number_format($draw_rank_sum[0]/$limit,1);
		print("<TD align=\"center\">$num_temp</TD>\n");
		#$num_temp = number_format($draw_rank_sum[1]/$number_count,2)*100;
		$num_temp = number_format($draw_rank_sum[1]/$limit,1);
		print("<TD align=\"center\">$num_temp</TD>\n");
		#$num_temp = number_format($draw_rank_sum[2]/$number_count,2)*100;
		$num_temp = number_format($draw_rank_sum[2]/$limit,1);
		print("<TD align=\"center\">$num_temp</TD>\n");
		#$num_temp = number_format($draw_rank_sum[3]/$number_count,2)*100;
		$num_temp = number_format($draw_rank_sum[3]/$limit,1);
		print("<TD align=\"center\">$num_temp</TD>\n");
		#$num_temp = number_format($draw_rank_sum[4]/$number_count,2)*100;
		$num_temp = number_format($draw_rank_sum[4]/$limit,1);
		print("<TD align=\"center\">$num_temp</TD>\n");
		#$num_temp = number_format($draw_rank_sum[5]/$number_count,2)*100;
		$num_temp = number_format($draw_rank_sum[5]/$limit,1);
		print("<TD align=\"center\">$num_temp</TD>\n");
		#$num_temp = number_format($draw_rank_sum[6]/$number_count,2)*100;
		$num_temp = number_format($draw_rank_sum[6]/$limit,1);
		print("<TD align=\"center\">$num_temp</TD>\n");

		$num_temp = number_format($seq2_avg/$limit,1);
		print("<TD BGCOLOR=\"#99CCFFa\" align=\"center\">$num_temp</TD>\n");
		$num_temp = number_format($seq3_avg/$limit,1);
		print("<TD align=\"center\">$num_temp</TD>\n");

		$num_temp = number_format($mod2_avg/$limit,1);
		print("<TD BGCOLOR=\"#99CCFFa\" align=\"center\">$num_temp</TD>\n");
		$num_temp = number_format($mod3_avg/$limit,1);
		print("<TD align=\"center\">$num_temp</TD>\n");

		$num_temp = number_format($comp_avg_tot[2]/$limit,0);
		print("<TD align=\"center\">$num_temp</TD>\n");
		$num_temp = number_format($comp_avg_tot[3]/$limit,1);
		print("<TD align=\"center\">$num_temp</TD>\n");
		$num_temp = number_format($comp_avg_tot[4]/$limit,1);
		print("<TD align=\"center\">$num_temp</TD>\n");
		$num_temp = number_format($comp_avg_tot[5]/$limit,1);
		print("<TD align=\"center\">$num_temp</TD>\n");
		$num_temp = number_format($comp_avg_tot[6]/$limit,0);
		print("<TD align=\"center\">$num_temp</TD>\n");

		print("</TR>\n");

		#print_r ($total_failed_tot);

		print("<TR>\n");
		print("<TD BGCOLOR=\"#e0e0e0\" colspan=\"27\" align= \"right\"><font size=\"-1\">setting in combin_date.incl&nbsp;</font></TD>\n");
		$num_temp = number_format($total_failed_tot[2]/$limit,2)*100;
		print("<TD align=\"center\">$num_temp%</TD>\n");
		$num_temp = number_format($total_failed_tot[3]/$limit,2)*100;
		print("<TD align=\"center\">$num_temp%</TD>\n");
		$num_temp = number_format($total_failed_tot[4]/$limit,2)*100;
		print("<TD align=\"center\">$num_temp%</TD>\n");
		$num_temp = number_format($total_failed_tot[5]/$limit,2)*100;
		print("<TD align=\"center\">$num_temp%</TD>\n");
		$num_temp = number_format($total_failed_tot[6]/$limit,2)*100;
		print("<TD align=\"center\">$num_temp%</TD>\n");

		print("</TR>\n");
	}

	function print_sum_table($limit)
	{
		global $draw_table_name, $balls, $balls_drawn; 

		require ("includes/mysqli.php");

		for ($x = 0; $x < 16; $x++)
		{
			$sum_range[$x] = 0;
		}
	
		// get everything from catalog table
		$query = "SELECT sum FROM $draw_table_name ";
		$query .= "ORDER BY date DESC ";
		$query .= "LIMIT $limit ";
	
		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

		//start table
		print("<h3>Sum Rank - last $limit draws</h3>\n");
		print("<TABLE BORDER=\"1\">\n");
	
		//create header row
		print("<TR>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Drawx</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Count</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><CENTER>% draws</CENTER></TD>\n");
		print("</TR>\n");
	
		// get each row
		while($row = mysqli_fetch_row($mysqli_result))
		{
			$y = intval($row[0]/10);
			$sum_range[$y]++;
		}

		for ($x = 1; $x < 14; $x++)
		{
			print("<TR>\n");
			print("<TD>$x</TD>\n");
			if ($sum_range[$x] > 2)
			{
				print("<TD><FONT COLOR=\"red\"><B>$sum_range[$x]</B></FONT></TD>\n");
			}
			else
			{
				print("<TD>$sum_range[$x]</TD>\n");
			}
			$num_temp = number_format($sum_range[$x]/$limit,2)*100;
			print("<TD><CENTER>$num_temp%</CENTER></TD>\n");
			print("</TR>\n");
		}

		print("</TABLE>\n");

	}

	//start HTML page
	print("<HTML>\n");
	print("<HEAD>\n");
	print("<TITLE>$game_name Differentiate</TITLE>\n");
	print("</HEAD>\n");
	
	print("<BODY>\n");
	print("<H1>$game_name Differentiate</H1>\n");
	
	print_table(10);

	//close page
	print("</BODY>\n");
	print("</HTML>\n");

?>
