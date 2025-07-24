<?php

	// Game ----------------------------- Game
	$game = 1; // Georgia Fantasy 5
	#$game = 2; // Mega Millions
	//$game = 3; // Georgia 5
	#$game = 4; // Jumbo
	//$game = 5; // Florida Mega Money
	#$game = 6; // Florida Lotto
	#$game = 7; // Powerball
	#$game = 10; // All or Nothing
	#$game = 20; // All or Nothing Negative
	// Game ----------------------------- Game

	$hml = 0;
	#$hml = 1;		#= high
	#$hml = 2;		#= medium
	#$hml = 3;		#= low
	#$hml = 4;		#= min
	#$hml = 5;		#= max
	#$hml = 100;

	# add EO50 limit

	$col1_select = 0;
	
	require ("includes/games_switch.incl");
	require ("includes/even_odd.php");
	require ("includes/last_draws.php");
	require ("includes/calculate_rank.php");
	require ("includes/look_up_rank.php"); 
	if ($game == 10 OR $game == 20)
	{
		require ("includes_aon/build_rank_table_aon.php");
	} else {
		require ("includes/build_rank_table.php");
	}
	require ("includes/test_column_lookup.php");
	require ("includes/calculate_rank_mb.php");
	require ("includes/next_draw.php");
	require ("includes/number_due.php");
	require ("includes/first_draw_unix.php");
	require ("includes/last_draw_unix.php"); 
	require ("includes/count_2_seq.php");
	require ("includes/count_3_seq.php");
	require ("includes/mysqli.php"); 
	require ("$game_includes/combin.incl");

	require ("includes/limit_functions.incl");

	date_default_timezone_set('America/New_York');

	echo "<b>col1_select = $col1_select in lot_display.php</b><p>";

	require_once ("includes/hml_switch.incl");	

	$debug = 0;
	
	// ----------------------------------------------------------------------------------
	function print_table($limit)
	{
		global $draw_table_name, $balls, $balls_drawn, $game_includes, $draw_prefix, $game_includes, $hml, $range_low, $range_high, $col1_select; 

		require ("includes/mysqli.php"); 

		require ("$game_includes/config.incl");

		$query = "SELECT * FROM $draw_table_name ";
		if ($hml)
		{
			$query .= "WHERE sum >= $range_low  ";
			$query .= "AND   sum <= $range_high  ";
			if ($col1_select)
			{
				$query .= "AND d1 = $col1_select  ";
			}
		} elseif ($col1_select) {
				$query .= "WHERE d1 = $col1_select  ";
			}
		if ($game == 10 OR $game == 20)
		{
			$query .= "ORDER BY id DESC ";
		} else {
			$query .= "ORDER BY date DESC ";
		}
		$query .= "LIMIT $limit ";

		#print("$query<p>");

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

		//start table
		print("<h3>Last $limit draws</h3>\n");
		print("<TABLE BORDER=\"1\">\n");

		//create header row
		print("<TR><B>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Date</center></TD>\n");

		if ($game == 10 OR $game == 20)
		{
			print("<TD BGCOLOR=\"#CCCCCC\">game</TD>\n");
		}

		for ($x = 1; $x <= $balls_drawn; $x++)
		{
			print("<TD BGCOLOR=\"#CCCCCC\"><center>&nbsp;P$x&nbsp;</center></TD>\n");
		}
		
		if ($mega_balls)
		{
			print("<TD BGCOLOR=\"#CCCCCC\">Mega</TD>\n");
		}

		print("<TD BGCOLOR=\"#CCCCCC\"><center>Sum</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><CENTER>Even<br>/Odd</CENTER></TD>\n");

		for ($x = 0; $x <= intval($balls/10); $x++)
		{
			print("<TD BGCOLOR=\"#CCCCCC\"><CENTER>$x.x</CENTER></TD>\n");
		}

		$half = intval($balls/2);

		print("<TD BGCOLOR=\"#CCCCCC\"><CENTER>Range<br>$half/$balls</CENTER></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><CENTER>EO50</CENTER></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><CENTER>Mid</CENTER></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><CENTER>r0</CENTER></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><CENTER>r1</CENTER></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><CENTER>r2</CENTER></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><CENTER>r3</CENTER></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><CENTER>r4</CENTER></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><CENTER>r5</CENTER></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><CENTER>r6</CENTER></TD>\n");
		if ($game == 10 OR $game == 20)
		{
			print("<TD BGCOLOR=\"#CCCCCC\"><CENTER>r7</CENTER></TD>\n");
			print("<TD BGCOLOR=\"#CCCCCC\"><CENTER>r8</CENTER></TD>\n");
			print("<TD BGCOLOR=\"#CCCCCC\"><CENTER>r9</CENTER></TD>\n");
			print("<TD BGCOLOR=\"#CCCCCC\"><CENTER>r10</CENTER></TD>\n");
			print("<TD BGCOLOR=\"#CCCCCC\"><CENTER>r11</CENTER></TD>\n");
			print("<TD BGCOLOR=\"#CCCCCC\"><CENTER>r12</CENTER></TD>\n");
			print("<TD BGCOLOR=\"#CCCCCC\"><CENTER>r13</CENTER></TD>\n");
			print("<TD BGCOLOR=\"#CCCCCC\"><CENTER>r14</CENTER></TD>\n");
			print("<TD BGCOLOR=\"#CCCCCC\"><CENTER>r15</CENTER></TD>\n");
			print("<TD BGCOLOR=\"#CCCCCC\"><CENTER>r16</CENTER></TD>\n");
			print("<TD BGCOLOR=\"#CCCCCC\"><CENTER>r17</CENTER></TD>\n");
			print("<TD BGCOLOR=\"#CCCCCC\"><CENTER>r18</CENTER></TD>\n");
			print("<TD BGCOLOR=\"#CCCCCC\"><CENTER>r19</CENTER></TD>\n");
			print("<TD BGCOLOR=\"#CCCCCC\"><CENTER>r20</CENTER></TD>\n");
		}

		if ($mega_balls)
		{
			print("<TD BGCOLOR=\"#CCCCCC\">rMB</TD>\n");
		}

		print("<TD BGCOLOR=\"#CCCCCC\"><font size=\"-1\">nums2</font></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><font size=\"-1\">combo2</font></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><font size=\"-1\">nums3</font></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><font size=\"-1\">combo3</font></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><font size=\"-1\">nums4</font></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><font size=\"-1\">combo4</font></TD>\n");

		

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
		$num_range_d2_1_sum = 0;
		$num_range_d2_2_sum = 0;
		$mod2_count_sum = 0;
		$mod3_count_sum = 0;
		$draw_rank_sum = array_fill (0, 7, 0);

		$draw_columns_count = intval($balls/10) + 1;

		$col_sum = array_fill (0, 7, $draw_rank_sum);

		/* change - calculate values */

		while($row = mysqli_fetch_array($mysqli_result))
		{
			/*
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
			*/
				require ("includes/d0.incl");
			#}

			$num_range = array_fill (0,8,0);

			$num_range_d2_1 = 0;
			$num_range_d2_2 = 0;
			
			// build draw array [0..x]
			$draw_array = array ();
	
			if ($game == 10 OR $game == 20)
			{
				for ($x = 3; $x <= 14; $x++)

				{
					array_push($draw_array, $row[$x]);
				}
			} else {
				for ($x = 1; $x <= $balls_drawn; $x++)
				{
					array_push($draw_array, $row[$x]);
				}
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
			for($index = 0; $index < $balls_drawn; $index++)
			{	
				if ($game == 10 OR $game == 20)
				{
					$number = $row[$index+3];
				} else {
					$number = $row[$index+1];
				}

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
				elseif ($number > 49 && $number < 60) {
					$num_range[5]++;
					$y = $number - 50;
					$mod[$y]++;
				}
				elseif ($number > 59 && $number < 70) {
					$num_range[6]++;
					$y = $number - 60;
					$mod[$y]++;
				}
				else {
					$num_range[7]++;
					$y = $number - 70;
					$mod[$y]++;
				}

				$temp = intval($balls/2);

				if ($number <= $temp) { 
					$num_range_d2_1++;
				}
				else {
					$num_range_d2_2++;
				}
			}

			for ($x = 0; $x < $draw_columns_count; $x++)
			{
				$num_range_sum[$x] += $num_range[$x];
			}

			$num_range_d2_1_sum += $num_range_d2_1;
			$num_range_d2_2_sum += $num_range_d2_2;

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

			// build rank table - last 26

			$rank_count = array (0);

			if ($game == 10 OR $game == 20)
			{
				$rank_count = BuildRankTable($row[date]);

				$rank_table_count = array_fill (0, 21, 0);

				for($z = 1; $z <= $balls; $z++)
				{
					$rank_table_count[$rank_count[$z]]++;
				}
			} else {
				$rank_count = BuildRankTable($row[date]); // array 0..balls with total draws for last 2

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
			}

			//
			
			print("<TR>\n");

			print("<TD nowrap><font size=\"-1\">$row[date]</font></TD>\n");

			if ($game == 10 OR $game == 20)
			{
				print("<TD><center>$row[day_draw]<br>\n");

				for ($y = 3; $y <= 14; $y++)
				{
					print("<TD><center>$row[$y]<br>\n");

					$rank_ball = $rank_count[$row[$y]];
					
					print("<font size=\"-1\" color=\"#ff0000\"><b>$rank_ball</b></font></center></TD>\n");
				}
			} else {
				for ($y = 1; $y <= $balls_drawn; $y++)
				{
					print("<TD><center>$row[$y]<br>\n");

					$rank_ball = $rank_count[$row[$y]];
					
					print("<font size=\"-1\" color=\"#ff0000\"><b>$rank_ball</b></font></center></TD>\n");
				}
			}
	
			if ($mega_balls)
			{
				print("<TD><center>$row[mb]</center></TD>\n");
			}

			if ($row[sum] < $sum_low || $row[sum] > $sum_high)
			{
				print("<TD align=center><font color=\"#ff0000\"><b>$row[sum]</b></font></TD>\n");
			} else {
				print("<TD align=center>$row[sum]</TD>\n");
			}

			if ($even <= 0 || $even >= $balls_drawn) {
				print("<TD BGCOLOR=\"#99CCFF\"><strong><CENTER>$even/$odd</CENTER></strong></TD>\n");
			} else {
				print("<TD BGCOLOR=\"#99CCFF\"><CENTER>$even/$odd</CENTER></TD>\n");
			}

			for ($x = 0; $x <= intval($balls/10); $x++)
			{
				if ($num_range[$x] > 2) {
					print("<TD><CENTER><font color=\"#ff0000\"><B>$num_range[$x]</B></font></CENTER></TD>\n");
				} else {
					print("<TD><CENTER>$num_range[$x]</CENTER></TD>\n");
				}
			}

			if ($num_range_d2_1 == 0 || $num_range_d2_1 >= $balls_drawn) {
				print("<TD BGCOLOR=\"#99CCFF\"><CENTER><B>$num_range_d2_1/$num_range_d2_2</B></CENTER></TD>\n");
			} else {
				print("<TD BGCOLOR=\"#99CCFF\"><CENTER>$num_range_d2_1/$num_range_d2_2</CENTER></TD>\n");
			}

			$query3 = "SELECT * FROM $draw_table_name ";
			$query3 .= "WHERE sum = $row[sum] ";
			$query3 .= "AND   even = $row[even] ";
			$query3 .= "AND   odd = $row[odd] ";
			$query3 .= "AND   d2_1 = $row[d2_1] ";
			$query3 .= "AND   d2_2 = $row[d2_2] ";
			$query3 .= "AND date < $row[date] ";

			#echo "$query3<br>";
		
			$mysqli_result3 = mysqli_query($mysqli_link, $query3) or die (mysqli_error($mysqli_link));

			$num_rows_all = mysqli_num_rows($mysqli_result3); 

			$row3 = mysqli_fetch_array($mysqli_result3);

			if ($num_rows_all > 2) {
				print("<TD><CENTER><font color=\"#ff0000\"><B>$num_rows_all</B></font></CENTER></TD>\n");
			} else {
				print("<TD><CENTER>$num_rows_all</CENTER></TD>\n");
			}

			$mid_point = ($row[1] + $row[$balls_drawn])/2;
			$mid_count = 0;

			for ($x = 1; $x <= $balls_drawn; $x++)
			{
				if ($row[$x] <= $mid_point) 
				{
					$mid_count++;
				}
			}

			if ($mid_count == $num_range_d2_1) {
				print("<TD><CENTER>$mid_count<br><font size=\"-1\">($mid_point)</font></CENTER></TD>\n");
			} else {
				print("<TD><CENTER><b>$mid_count<br><font size=\"-1\">($mid_point)</font></b></CENTER></TD>\n");
			}

			if ($game == 10 OR $game == 20)
			{
				$draw_rank_count = array_fill (0, 21, 0);

				for($y = 1; $y <= $balls_drawn; $y++)
				{
					if ($rank_count[$row[$y]] >= 20)
					{
						$draw_rank_count[20]++;
					} else {
						$draw_rank_count[$rank_count[$row[$y]]]++;
					}
				}
			} else {
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
	
			############################################################################################
			############################################################################################

			if ($game == 1)
			{
				$query_nc = "SELECT * FROM ga_f5_nums_combos_table ";
				$query_nc .= "WHERE date = '$row[date]' ";

				#echo "$query_nc<br>";
			
				#$mysqli_result_nc = mysqli_query($query_nc, $mysqli_link) or die (mysqli_error($mysqli_link));

				#$num_rows_all_nc = mysqli_num_rows($mysqli_result_nc); 

				#$row_nc = mysqli_fetch_array($mysqli_result_nc);

				if ($num_rows_all_nc)
				{
					$row_nc = mysqli_fetch_array($mysqli_result_nc);

					$temp_nums_count_2 = $row_nc[nums2];
					$temp_combo_count_2 = $row_nc[combs2];
					$temp_nums_count_3 = $row_nc[nums3];
					$temp_combo_count_3 = $row_nc[combs3];
					$temp_nums_count_4 = $row_nc[nums4];
					$temp_combo_count_4 = $row_nc[combs4];
				} else {
					require ("includes/combo_count_date.incl");	

					$query_nc = "INSERT INTO ga_f5_nums_combos_table ";
					$query_nc .= "VALUES ('$row[date]', ";
					$query_nc .= "'$temp_nums_count_2',";
					$query_nc .= "'$temp_combo_count_2',";
					$query_nc .= "'$temp_nums_count_3',";
					$query_nc .= "'$temp_combo_count_3',";
					$query_nc .= "'$temp_nums_count_4',";
					$query_nc .= "'$temp_combo_count_4')"; 

					#echo "$query_nc<p>";
				
					#$mysqli_result_nc2 = mysqli_query($query_nc, $mysqli_link) or die (mysqli_error($mysqli_link));
				}
			}

			print("<TD><CENTER>$temp_nums_count_2</CENTER></TD>\n");
			print("<TD><CENTER>$temp_combo_count_2</CENTER></TD>\n");

			print("<TD><CENTER>$temp_nums_count_3</CENTER></TD>\n");
			print("<TD><CENTER>$temp_combo_count_3</CENTER></TD>\n");

			print("<TD><CENTER>$temp_nums_count_4</CENTER></TD>\n");
			print("<TD><CENTER>$temp_combo_count_4</CENTER></TD>\n");

			print("</TR>\n");

			$average_count++;
		}
		
		print("<TR>\n");
		print("<TD>&nbsp;</TD>\n");

		for ($x = 1; $x <= $balls_drawn; $x++)
		{
			print("<TD>&nbsp;</TD>\n");
		}
		
		$num_temp = number_format($sum_sum/$average_count,2);
		print("<TD>$num_temp</TD>\n");
		$number_count = $average_count*$balls_drawn;
		//print "even_sum = $even_sum<br>";
		//print "odd_sum = $odd_sum<br>";
		//print "number_count = $number_count<br>";
		$num_temp = number_format($even_sum/$number_count,2)*100;
		$num_temp2 = number_format($odd_sum/$number_count,2)*100;
		print("<TD BGCOLOR=\"#99CCFFa\"><CENTER>$num_temp/$num_temp2%</CENTER></TD>\n");

		for ($x = 0; $x < $draw_columns_count; $x++)
		{
			$num_temp = number_format($num_range_sum[$x]/$number_count,2)*100;
			print("<TD><CENTER>$num_temp%</CENTER></TD>\n");
		}
		/*
		$num_temp = number_format($num_range_sum[0]/$number_count,2)*100;
		print("<TD><CENTER>$num_temp%</CENTER></TD>\n");
		$num_temp = number_format($num_range_sum[1]/$number_count,2)*100;
		print("<TD><CENTER>$num_temp%</CENTER></TD>\n");
		$num_temp = number_format($num_range_sum[2]/$number_count,2)*100;
		print("<TD><CENTER>$num_temp%</CENTER></TD>\n");
		$num_temp = number_format($num_range_sum[3]/$number_count,2)*100;
		print("<TD><CENTER>$num_temp%</CENTER></TD>\n");
		*/
		$num_temp1 = number_format($num_range_d2_1_sum/$number_count,2)*100;
		$num_temp2 = number_format($num_range_d2_2_sum/$number_count,2)*100;
		print("<TD BGCOLOR=\"#99CCFFa\"><CENTER>$num_temp1/$num_temp2%</CENTER></TD>\n");
		print("<TD>&nbsp;</TD>\n");
		$num_temp = number_format($draw_rank_sum[0]/$number_count,2)*100;
		print("<TD>$num_temp%</TD>\n");
		$num_temp = number_format($draw_rank_sum[1]/$number_count,2)*100;
		print("<TD>$num_temp%</TD>\n");
		$num_temp = number_format($draw_rank_sum[2]/$number_count,2)*100;
		print("<TD>$num_temp%</TD>\n");
		$num_temp = number_format($draw_rank_sum[3]/$number_count,2)*100;
		print("<TD>$num_temp%</TD>\n");
		$num_temp = number_format($draw_rank_sum[4]/$number_count,2)*100;
		print("<TD>$num_temp%</TD>\n");
		$num_temp = number_format($draw_rank_sum[5]/$number_count,2)*100;
		print("<TD>$num_temp%</TD>\n");
		$num_temp = number_format($draw_rank_sum[6]/$number_count,2)*100;
		print("<TD>$num_temp%</TD>\n");

		print("<TD>&nbsp;</TD>\n");
		print("<TD>&nbsp;</TD>\n");

		for($x = 1; $x < $balls_drawn; $x++)
		{
			for($y = $x+1; $y <= $balls_drawn; $y++)
			{
				if ($col_sum[$x][$y])
				{
					print("<TD><center><font color=\"#ff0000\">{$col_sum[$x][$y]}</font></center></TD>\n");
				} else {
					print("<TD><center>{$col_sum[$x][$y]}</center></TD>\n");
				}
			}
		}

		print("<TD>&nbsp;</TD>\n");
		print("<TD>&nbsp;</TD>\n");
		print("<TD>&nbsp;</TD>\n");
		print("<TD>&nbsp;</TD>\n");
		print("<TD>&nbsp;</TD>\n");

		print("</TR>\n");
		print("</TABLE>\n");
	}

	// ----------------------------------------------------------------------------------
	function print_sum_grid()
	{
		global $draw_table_name, $balls, $balls_drawn, $draw_prefix, $col1_select, $hml, $range_low, $range_high; 

		require ("includes/mysqli.php");
	
		require ("includes/calculate_sum_count.incl");

		require ("includes/print_sum_table.incl");
		
		print("</TABLE>\n");

		print "<h3>Table <font color=\"#ff0000\">{$draw_prefix}sum</font> Updated!</h3>";

	}

	// ----------------------------------------------------------------------------------
	function print_sum_grid_old()
	{
		global $draw_table_name, $balls, $balls_drawn, $draw_prefix; 

		require ("includes/mysqli.php");

		$query4 = "DROP TABLE IF EXISTS $draw_prefix";
		$query4 .= "sum ";

		$mysqli_result4 = mysqli_query($mysqli_link, $query4) or die (mysqli_error($mysqli_link));

		$query4 = "CREATE TABLE $draw_prefix";
		$query4 .= "sum (";
		$query4 .= "`numx` tinyint(3) unsigned NOT NULL,";
		$query4 .= "`last5` tinyint(3) unsigned NOT NULL,";
		$query4 .= "`last10` tinyint(3) unsigned NOT NULL,";
		$query4 .= "`last30` tinyint(3) unsigned NOT NULL,";
		$query4 .= "`last50` tinyint(3) unsigned NOT NULL,";
		$query4 .= "`last100` tinyint(3) unsigned NOT NULL,";
		$query4 .= "`last250` tinyint(3) unsigned NOT NULL,";
		$query4 .= "`last365` tinyint(3) unsigned NOT NULL,";
		$query4 .= "`last500` tinyint(3) unsigned NOT NULL,";
		$query4 .= "`last1000` tinyint(3) unsigned NOT NULL,";
		$query4 .= "`last5000` tinyint(3) unsigned NOT NULL,";
		$query4 .= "`percent_30` float(4,1) unsigned NOT NULL,";
		$query4 .= "`percent_365` float(4,1) unsigned NOT NULL,";
		$query4 .= "`percent_5000` float(4,1) unsigned NOT NULL,";
		$query4 .= "`percent_wa` float(4,1) unsigned NOT NULL,";
		$query4 .= "PRIMARY KEY  (`numx`),";
		$query4 .= "UNIQUE KEY `numx_2` (`numx`),";
		$query4 .= "KEY `numx` (`numx`)";
		$query4 .= ") ENGINE=InnoDB DEFAULT CHARSET=latin1;";
	
		$mysqli_result4 = mysqli_query($mysqli_link, $query4) or die (mysqli_error($mysqli_link));

		$sum_tot_5 =	array_fill (0, 29, 0);
		$sum_tot_10 =	array_fill (0, 29, 0);
		$sum_tot_30 =	array_fill (0, 29, 0);
		$sum_tot_50 =	array_fill (0, 29, 0);
		$sum_tot_100 =	array_fill (0, 29, 0);
		$sum_tot_250 =	array_fill (0, 29, 0);
		$sum_tot_365 =	array_fill (0, 29, 0);
		$sum_tot_500 =	array_fill (0, 29, 0);
		$sum_tot_1000 =	array_fill (0, 29, 0);
		$sum_tot_5000 =	array_fill (0, 29, 0);
	
		$query = "SELECT sum FROM $draw_table_name ";
		$query .= "ORDER BY date DESC ";
	
		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

		$num_rows_all = mysqli_num_rows($mysqli_result);

		//start table
		print("<h3>Sum Rank</h3>\n");
		print("<TABLE BORDER=\"1\">\n");
	
		//create header row
		print("<TR>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">SumX</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">&nbsp;5&nbsp;</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">&nbsp;10&nbsp;</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">&nbsp;<b>30</b>&nbsp;</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">&nbsp;50&nbsp;</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">100</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">250</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><b>365</b></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">500</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">1000</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">$num_rows_all</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\" align=center>30</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\" align=center>365</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\" align=center>$num_rows_all</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\" align=center>wa</TD>\n");
		print("</TR>\n");

		$draw = 1;
	
		while($row = mysqli_fetch_array($mysqli_result))
		{
			$x = intval($row[0]/10);
			if ($draw <= 5)
			{
				$sum_tot_5[$x]++;
				$sum_tot_10[$x]++;
				$sum_tot_30[$x]++;
				$sum_tot_50[$x]++;
				$sum_tot_100[$x]++;
				$sum_tot_250[$x]++;
				$sum_tot_365[$x]++;
				$sum_tot_500[$x]++;
				$sum_tot_1000[$x]++;
				$sum_tot_5000[$x]++;
			} elseif ($draw <= 10) {
				$sum_tot_10[$x]++;
				$sum_tot_30[$x]++;
				$sum_tot_50[$x]++;
				$sum_tot_100[$x]++;
				$sum_tot_250[$x]++;
				$sum_tot_365[$x]++;
				$sum_tot_500[$x]++;
				$sum_tot_1000[$x]++;
				$sum_tot_5000[$x]++;
			} elseif ($draw <= 30) {
				$sum_tot_30[$x]++;
				$sum_tot_50[$x]++;
				$sum_tot_100[$x]++;
				$sum_tot_250[$x]++;
				$sum_tot_365[$x]++;
				$sum_tot_500[$x]++;
				$sum_tot_1000[$x]++;
				$sum_tot_5000[$x]++;
			} elseif ($draw <= 50) {
				$sum_tot_50[$x]++;
				$sum_tot_100[$x]++;
				$sum_tot_250[$x]++;
				$sum_tot_365[$x]++;
				$sum_tot_500[$x]++;
				$sum_tot_1000[$x]++;
				$sum_tot_5000[$x]++;
			} elseif ($draw <= 100) {
				$sum_tot_100[$x]++;
				$sum_tot_250[$x]++;
				$sum_tot_365[$x]++;
				$sum_tot_500[$x]++;
				$sum_tot_1000[$x]++;
				$sum_tot_5000[$x]++;
			} elseif ($draw <= 250) {
				$sum_tot_250[$x]++;
				$sum_tot_365[$x]++;
				$sum_tot_500[$x]++;
				$sum_tot_1000[$x]++;
				$sum_tot_5000[$x]++;
			} elseif ($draw <= 365) {
				$sum_tot_365[$x]++;
				$sum_tot_500[$x]++;
				$sum_tot_1000[$x]++;
				$sum_tot_5000[$x]++;
			} elseif ($draw <= 500) {
				$sum_tot_500[$x]++;
				$sum_tot_1000[$x]++;
				$sum_tot_5000[$x]++;
			} elseif ($draw <= 1000) {
				$sum_tot_1000[$x]++;
				$sum_tot_5000[$x]++;
			} elseif ($draw <= 5000) {
				$sum_tot_5000[$x]++;
			}
			$draw++;
		}

		for ($x = 1; $x <= 28; $x++)
		{
			print("<TR>\n");
			print("<TD><CENTER><b>$x</b></CENTER></TD>\n");
			if ($sum_tot_5[$x] > 1) {
				print("<TD BGCOLOR=\"#CCFFFF\" align=center><b>$sum_tot_5[$x]</b></TD>\n");
			} else {
				print("<TD><CENTER>$sum_tot_5[$x]</CENTER></TD>\n");
			}
			if ($sum_tot_10[$x] > 1) {
				print("<TD BGCOLOR=\"#CCFFFF\"><CENTER><b>$sum_tot_10[$x]</b></CENTER></TD>\n");
			} else {
				print("<TD><CENTER>$sum_tot_10[$x]</CENTER></TD>\n");
			}
			if ($sum_tot_30[$x] > 2) {
				print("<TD BGCOLOR=\"#CCFFFF\"><CENTER><b>$sum_tot_30[$x]</b></CENTER></TD>\n");
			} else {
				print("<TD><CENTER>$sum_tot_30[$x]</CENTER></TD>\n");
			}
			if ($sum_tot_50[$x] > 3) {
				print("<TD BGCOLOR=\"#CCFFFF\"><CENTER><b>$sum_tot_50[$x]</b></CENTER></TD>\n");
			} else {
				print("<TD><CENTER>$sum_tot_50[$x]</CENTER></TD>\n");
			}
			if ($sum_tot_100[$x] > 3) {
				print("<TD BGCOLOR=\"#CCFFFF\"><CENTER><b>$sum_tot_100[$x]</b></CENTER></TD>\n");
			} else {
				print("<TD><CENTER>$sum_tot_100[$x]</CENTER></TD>\n");
			}
			if ($sum_tot_250[$x] > 3) {
				print("<TD BGCOLOR=\"#CCFFFF\"><CENTER><b>$sum_tot_250[$x]</b></CENTER></TD>\n");
			} else {
				print("<TD><CENTER>$sum_tot_250[$x]</CENTER></TD>\n");
			}
			if ($sum_tot_365[$x] > 3) {
				print("<TD BGCOLOR=\"#CCFFFF\"><CENTER><b>$sum_tot_365[$x]</b></CENTER></TD>\n");
			} else {
				print("<TD><CENTER>$sum_tot_365[$x]</CENTER></TD>\n");
			}
			if ($sum_tot_500[$x] > 4) {
				print("<TD BGCOLOR=\"#CCFFFF\"><CENTER><b>$sum_tot_500[$x]</b></CENTER></TD>\n");
			} else {
				print("<TD><CENTER>$sum_tot_500[$x]</CENTER></TD>\n");
			}
			if ($sum_tot_1000[$x] > 5) {
				print("<TD BGCOLOR=\"#CCFFFF\"><CENTER><b>$sum_tot_1000[$x]</b></CENTER></TD>\n");
			} else {
				print("<TD><CENTER>$sum_tot_1000[$x]</CENTER></TD>\n");
			}
			if ($sum_tot_5000[$x] > 10) {
				print("<TD BGCOLOR=\"#CCFFFF\"><CENTER><b>$sum_tot_5000[$x]</b></CENTER></TD>\n");
			} else {
				print("<TD><CENTER>$sum_tot_5000[$x]</CENTER></TD>\n");
			}

			$sum_temp_30 = number_format(($sum_tot_30[$x]/30)*100,1);
			if ($sum_temp_30 >= 10.0)
			{
				print("<TD align=center><font size=\"-1\"><b>$sum_temp_30 %</b></font></TD>\n");
			} else {
				print("<TD align=center><font size=\"-1\">$sum_temp_30 %</font></TD>\n");
			}

			$sum_temp_365 = number_format(($sum_tot_365[$x]/365)*100,1);
			if ($sum_temp_365 >= 10.0)
			{
				print("<TD align=center><font size=\"-1\"><b>$sum_temp_365 %</b></font></TD>\n");
			} else {
				print("<TD align=center><font size=\"-1\">$sum_temp_365 %</font></TD>\n");
			}

			$sum_temp_5000 = number_format(($sum_tot_5000[$x]/$num_rows_all)*100,1);
			if ($sum_temp_5000 >= 10.0)
			{
				print("<TD align=center><font size=\"-1\"><b>$sum_temp_5000 %</b></font></TD>\n");
			} else {
				print("<TD align=center><font size=\"-1\">$sum_temp_5000 %</font></TD>\n");
			}

			$weighted_average = (
				($sum_tot_10[$x]/10*100*0.05) +
				($sum_tot_30[$x]/30*100*0.10) +
				($sum_tot_100[$x]/100*100*0.15) +
				($sum_tot_365[$x]/365*100*0.20) +
				($sum_tot_500[$x]/500*100*0.10) +
				($sum_tot_1000[$x]/1000*100*0.20) +
				($sum_tot_5000[$x]/$num_rows_all*100*0.20));

			$sum_temp_wa = number_format($weighted_average,1);
			if ($sum_temp_wa >= 10.0)
			{
				print("<TD align=center><font size=\"-1\"><b>$sum_temp_wa %</b></font></TD>\n");
			} else {
				print("<TD align=center><font size=\"-1\">$sum_temp_wa %</font></TD>\n");
			}

			print("</TR>\n");

			$query5 = "INSERT INTO $draw_prefix";
			$query5 .= "sum ";
			$query5 .= "VALUES ('$x', ";
			$query5 .= "'$sum_tot_5[$x]',";
			$query5 .= "'$sum_tot_10[$x]',";
			$query5 .= "'$sum_tot_30[$x]',";
			$query5 .= "'$sum_tot_50[$x]',";
			$query5 .= "'$sum_tot_100[$x]',";
			$query5 .= "'$sum_tot_250[$x]',";
			$query5 .= "'$sum_tot_365[$x]',";
			$query5 .= "'$sum_tot_500[$x]',";
			$query5 .= "'$sum_tot_1000[$x]',";
			$query5 .= "'$sum_tot_5000[$x]',";
			$query5 .= "'$sum_temp_30',";
			$query5 .= "'$sum_temp_365',";
			$query5 .= "'$sum_temp_5000',";
			$query5 .= "'$weighted_average')"; 

			echo "$query5<p>";
		
			$mysqli_result = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));
		}

		print("</TABLE>\n");

		print "<h3>Table <font color=\"#ff0000\">{$draw_prefix}sum</font> Updated!</h3>";

	}

	// ------------------------------------------------------------------------
	//start HTML page
	print("<HTML>\n");
	print("<HEAD>\n");
	print("<TITLE>$game_name Analyze</TITLE>\n");
	print("</HEAD>\n");
	
	print("<BODY>\n");
	print("<H1>$game_name Analyze</H1>\n");
	
	$curr_date = date("Ymd");
	$next_draw_Ymd = findNextDrawDate($game);

	
	#print_table(26);
	print_table(31);

	print_table(14);

	print_table(7);

	print_table(4);

	print_table(3);

	print_table(2);

	print_sum_grid();

	require ("includes/unix.incl");

	$d1 = $row[d.$p];
	$d2 = $row[d.$q];
	$temp1 = 'd' . $p;
	$temp2 = 'd' . $q;
	$draw1 = 1;
	
	/*
	### Combo Sorted double/pair 

	for ($p = 1; $p <= 6; $p++)
	{
		require ("$game_includes/calculate_combo_count_dd.incl");
		require ("$game_includes/sorted_combo_table_large_dd.incl");
	}

	### Combo Sorted double/pair 

	for ($hml_temp = 110; $hml_temp <= 180; $hml_temp += 10)
	{
		for ($p = 1; $p <= 1; $p++)
		{
			require ("$game_includes/calculate_combo_count_dd.incl");
			require ("$game_includes/sorted_combo_table_large_dd.incl");
		}
	}

	### Combo Sorted double/pair 

	for ($hml_temp = 110; $hml_temp <= 180; $hml_temp += 10)
	{
		for ($p = 2; $p <= 2; $p++)
		{
			require ("$game_includes/calculate_combo_count_dd.incl");
			require ("$game_includes/sorted_combo_table_large_dd.incl");
		}
	}

	### Combo Sorted double/pair 

	for ($hml_temp = 110; $hml_temp <= 180; $hml_temp += 10)
	{
		for ($p = 3; $p <= 3; $p++)
		{
			require ("$game_includes/calculate_combo_count_dd.incl");
			require ("$game_includes/sorted_combo_table_large_dd.incl");
		}
	}
	*/

	$query = "SELECT * FROM $draw_table_name ";
	if ($hml)
	{
		$query .= "WHERE sum >= $range_low  ";
		$query .= "AND   sum <= $range_high  ";
	}
	$query .= "ORDER BY date DESC ";
	$query .= "LIMIT 5 ";

	$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

	print("$query<p>");

	while($row = mysqli_fetch_array($mysqli_result))
	{
		$draw_array = array ();

		$hml = intval($row[sum]/10)*10;

		#echo "hml = $hml<p>";
	
		for ($x = 1; $x <= $balls_drawn; $x++)
		{
			array_push($draw_array, $row[$x]);
		}

		$query_temp = "SELECT * FROM $draw_prefix";
		$query_temp .= "filter_limits ";
		$query_temp .= "WHERE date = '$row[date]' ";
		$query_temp .= "AND   hml  = '$hml' ";

		print("$query_temp<p>");

		$mysqli_result_temp = mysqli_query($mysqli_link, $query_temp) or die (mysqli_error($mysqli_link));

		$row_temp = mysqli_fetch_array($mysqli_result_temp);

		$num_rows = mysqli_num_rows($mysqli_result_temp);

		if (!$num_rows && $row[b1] <= '15')
		{
			$next_draw_date = $row[date];

			require ("includes/hml_switch.incl");

			echo "limit not found - processing $row[date]<p>";

			lot_filter_limit_sum ($next_draw_date);
			lot_filter_limit_balls ($next_draw_date);
			lot_filter_limit_even ($next_draw_date);
			lot_filter_limit_odd ($next_draw_date);
			lot_filter_limit_d2_1 ($next_draw_date);
			lot_filter_limit_d2_2 ($next_draw_date);
			#lot_filter_limit_mod ($next_draw_date);
			#lot_filter_limit_modx ($next_draw_date);
			lot_filter_limit_draw ($next_draw_date);
			lot_filter_limit_rank ($next_draw_date);
			lot_filter_limit_dup ($next_draw_date); 
			lot_filter_limit_combo ($next_draw_date);
			lot_filter_limit_average ($next_draw_date);
			lot_filter_limit_median ($next_draw_date);
			lot_filter_limit_harmean ($next_draw_date);
			#lot_filter_limit_geomean ($next_draw_date);
			lot_filter_limit_quart1 ($next_draw_date);
			lot_filter_limit_quart2 ($next_draw_date);
			lot_filter_limit_quart3 ($next_draw_date);
			lot_filter_limit_stdev ($next_draw_date);
			lot_filter_limit_variance ($next_draw_date);
			lot_filter_limit_avedev ($next_draw_date);
			lot_filter_limit_kurt ($next_draw_date);
			lot_filter_limit_skew ($next_draw_date); 
			lot_filter_limit_devsq ($next_draw_date); 
		}

		print("<h3>Filters - $row[date]</h3><p>Draw: $row[b1]-$row[b2]-$row[b3]-$row[b4]-$row[b5]</p>\n");

		$combo_count = test_combin_count_2_hml($row,$date_switch=1,$hml);

		echo "Comb2 - ";

		for ($y = 1; $y < 10; $y++)
		{
			echo "$combo_count[$y],";
		}
		
		echo "$combo_count[$y] = ";

		$temp2 = array_sum($combo_count);
		echo "$temp2<br>";

		if ($temp2 > $top_sum)
		{
			$top_id = $row[id];
			$top_sum = $temp2;
			$temp_wa = $row[wa];
		}

		$combo_count = test_combin_count_3_hml($row,$date_switch=1,$hml);

		echo "Comb3 - ";

		for ($y = 1; $y < 10; $y++)
		{
			echo "$combo_count[$y],";
		}
		
		echo "$combo_count[$y] = ";

		$temp2 = array_sum($combo_count);
		echo "$temp2<br>";

		$combo_count = test_combin_count_4_hml($row,$date_switch=1,$hml);

		echo "Comb4 - ";

		for ($y = 1; $y < 5; $y++)
		{
			echo "$combo_count[$y],";
		}
		
		echo "$combo_count[$y] = ";

		$temp2 = array_sum($combo_count);
		echo "$temp2<p>";

		if ($row[b1] > '20')
		{
			echo("<h3><font color=\"#ff0000\">------ No data for $row[b1] ------</font></h3>");
		}
		
		//start table
		print("<TABLE BORDER=\"1\">\n");

		//create header row
		print("<TR>\n");
		print("<TD BGCOLOR=\"#CCCCCC\" width=\"100\"><center>Filter #</center></TD>\n");
		print("<TD width=\"50\"><center>Min</center></TD>\n");
		print("<TD width=\"50\"><center>Max</center></TD>\n");
		print("<TD width=\"50\"><center>Actual</center></TD>\n");
		print("<TD width=\"50\"><center>Pass</center></TD>\n");
		print("<TD width=\"50\"><center>Fail</center></TD>\n");
		print("</TR>\n");

		### 01 - Sum

		$query_temp = "SELECT * FROM $draw_prefix";
		$query_temp .= "filter_limits ";
		$query_temp .= "WHERE date = '$row[date]' ";
		$query_temp .= "AND limit_type = 'sm' ";
		$query_temp .= "AND hml = $hml ";
		$query_temp .= "AND col1_select = '$row[b1]' ";
		$query_temp .= "ORDER BY id DESC ";

		#echo "$query_temp<br>";

		$mysqli_result_temp = mysqli_query($mysqli_link, $query_temp) or die (mysqli_error($mysqli_link));

		$row_temp = mysqli_fetch_array($mysqli_result_temp);

		print("<TR>\n");
		print("<TD width=\"100\"><center>01 - Sum</center></TD>\n");
		print("<TD width=\"50\"><center>$row_temp[low]</center></TD>\n");
		print("<TD width=\"50\"><center>$row_temp[high]</center></TD>\n");
		print("<TD width=\"50\"><center>$row[sum]</center></TD>\n");
		if ($row[sum] >= $row_temp[low] && $row[sum] <= $row_temp[high])
		{
			print("<TD width=\"50\" BGCOLOR=\"#00dd00\"><center><font color=\"#ffffff\"><b>Pass</b></font></center></TD>\n");
			print("<TD width=\"50\"><center>---</center></TD>\n");
		} else {
			print("<TD width=\"50\"><center>---</center></TD>\n");
			print("<TD width=\"50\" BGCOLOR=\"#dd0000\"><center><font color=\"#ffffff\"><b>Fail</b></font></center></TD>\n");
		}
		print("</TR>\n");

		### 02a - Even

		$query_temp = "SELECT * FROM $draw_prefix";
		$query_temp .= "filter_limits ";
		$query_temp .= "WHERE date = '$row[date]' ";
		$query_temp .= "AND limit_type = 'ev' ";
		$query_temp .= "AND hml = $hml ";
		$query_temp .= "AND col1_select = '$row[b1]' ";
		$query_temp .= "ORDER BY id DESC ";

		#echo "$query_temp<br>";

		$mysqli_result_temp = mysqli_query($mysqli_link, $query_temp) or die (mysqli_error($mysqli_link));

		$row_temp = mysqli_fetch_array($mysqli_result_temp);

		print("<TR>\n");
		print("<TD width=\"100\"><center>02 - Even</center></TD>\n");
		print("<TD width=\"50\"><center>$row_temp[low]</center></TD>\n");
		print("<TD width=\"50\"><center>$row_temp[high]</center></TD>\n");
		print("<TD width=\"50\"><center>$row[even]</center></TD>\n");
		if ($row[even] >= $row_temp[low] && $row[even] <= $row_temp[high])
		{
			print("<TD width=\"50\" BGCOLOR=\"#00dd00\"><center><font color=\"#ffffff\"><b>Pass</b></font></center></TD>\n");
			print("<TD width=\"50\"><center>---</center></TD>\n");
		} else {
			print("<TD width=\"50\"><center>---</center></TD>\n");
			print("<TD width=\"50\" BGCOLOR=\"#dd0000\"><center><font color=\"#ffffff\"><b>Fail</b></font></center></TD>\n");
		}
		print("</TR>\n");

		### 03a - Odd

		$query_temp = "SELECT * FROM $draw_prefix";
		$query_temp .= "filter_limits ";
		$query_temp .= "WHERE date = '$row[date]' ";
		$query_temp .= "AND limit_type = 'od' ";
		$query_temp .= "AND hml = $hml ";
		$query_temp .= "AND col1_select = '$row[b1]' ";
		$query_temp .= "ORDER BY id DESC ";

		#echo "$query_temp<br>";

		$mysqli_result_temp = mysqli_query($mysqli_link, $query_temp) or die (mysqli_error($mysqli_link));

		$row_temp = mysqli_fetch_array($mysqli_result_temp);

		print("<TR>\n");
		print("<TD width=\"100\"><center>03 - Odd</center></TD>\n");
		print("<TD width=\"50\"><center>$row_temp[low]</center></TD>\n");
		print("<TD width=\"50\"><center>$row_temp[high]</center></TD>\n");
		print("<TD width=\"50\"><center>$row[odd]</center></TD>\n");
		if ($row[odd] >= $row_temp[low] && $row[odd] <= $row_temp[high])
		{
			print("<TD width=\"50\" BGCOLOR=\"#00dd00\"><center><font color=\"#ffffff\"><b>Pass</b></font></center></TD>\n");
			print("<TD width=\"50\"><center>---</center></TD>\n");
		} else {
			print("<TD width=\"50\"><center>---</center></TD>\n");
			print("<TD width=\"50\" BGCOLOR=\"#dd0000\"><center><font color=\"#ffffff\"><b>Fail</b></font></center></TD>\n");
		}
		print("</TR>\n");

		### 04a - d2_1

		$query_temp = "SELECT * FROM $draw_prefix";
		$query_temp .= "filter_limits ";
		$query_temp .= "WHERE date = '$row[date]' ";
		$query_temp .= "AND limit_type = 'd1' ";
		$query_temp .= "AND hml = $hml ";
		$query_temp .= "AND col1_select = '$row[b1]' ";
		$query_temp .= "ORDER BY id DESC ";

		#echo "$query_temp<br>";

		$mysqli_result_temp = mysqli_query($mysqli_link, $query_temp) or die (mysqli_error($mysqli_link));

		$row_temp = mysqli_fetch_array($mysqli_result_temp);

		print("<TR>\n");
		print("<TD width=\"100\"><center>04 - d2_1</center></TD>\n");
		print("<TD width=\"50\"><center>$row_temp[low]</center></TD>\n");
		print("<TD width=\"50\"><center>$row_temp[high]</center></TD>\n");
		print("<TD width=\"50\"><center>$row[d2_1]</center></TD>\n");
		if ($row[d2_1] >= $row_temp[low] && $row[d2_1] <= $row_temp[high])
		{
			print("<TD width=\"50\" BGCOLOR=\"#00dd00\"><center><font color=\"#ffffff\"><b>Pass</b></font></center></TD>\n");
			print("<TD width=\"50\"><center>---</center></TD>\n");
		} else {
			print("<TD width=\"50\"><center>---</center></TD>\n");
			print("<TD width=\"50\" BGCOLOR=\"#dd0000\"><center><font color=\"#ffffff\"><b>Fail</b></font></center></TD>\n");
		}
		print("</TR>\n");

		### 05a - d2_2

		$query_temp = "SELECT * FROM $draw_prefix";
		$query_temp .= "filter_limits ";
		$query_temp .= "WHERE date = '$row[date]' ";
		$query_temp .= "AND limit_type = 'd2' ";
		$query_temp .= "AND hml = $hml ";
		$query_temp .= "AND col1_select = '$row[b1]' ";
		$query_temp .= "ORDER BY id DESC ";

		#echo "$query_temp<br>";

		$mysqli_result_temp = mysqli_query($mysqli_link, $query_temp) or die (mysqli_error($mysqli_link));

		$row_temp = mysqli_fetch_array($mysqli_result_temp);

		print("<TR>\n");
		print("<TD width=\"100\"><center>05 - d2_2</center></TD>\n");
		print("<TD width=\"50\"><center>$row_temp[low]</center></TD>\n");
		print("<TD width=\"50\"><center>$row_temp[high]</center></TD>\n");
		print("<TD width=\"50\"><center>$row[d2_2]</center></TD>\n");
		if ($row[d2_2] >= $row_temp[low] && $row[d2_2] <= $row_temp[high])
		{
			print("<TD width=\"50\" BGCOLOR=\"#00dd00\"><center><font color=\"#ffffff\"><b>Pass</b></font></center></TD>\n");
			print("<TD width=\"50\"><center>---</center></TD>\n");
		} else {
			print("<TD width=\"50\"><center>---</center></TD>\n");
			print("<TD width=\"50\" BGCOLOR=\"#dd0000\"><center><font color=\"#ffffff\"><b>Fail</b></font></center></TD>\n");
		}
		print("</TR>\n");

		### 06_1a - Ball 01

		for ($z = 1; $z <= 5; $z++)
		{
			$query_temp = "SELECT * FROM $draw_prefix";
			$query_temp .= "filter_limits ";
			$query_temp .= "WHERE date = '$row[date]' ";
			$query_temp .= "AND limit_type = 'ba' ";
			$query_temp .= "AND hml = $hml ";
			$query_temp .= "AND col1_select = '$row[b1]' ";
			$query_temp .= "AND col_pos = '$z' ";
			$query_temp .= "ORDER BY id DESC ";

			#echo "$query_temp<br>";

			$mysqli_result_temp = mysqli_query($mysqli_link, $query_temp) or die (mysqli_error($mysqli_link));

			$row_temp = mysqli_fetch_array($mysqli_result_temp);

			print("<TR>\n");
			print("<TD width=\"100\"><center>06_$z - Ball $z</center></TD>\n");
			print("<TD width=\"50\"><center>$row_temp[low]</center></TD>\n");
			print("<TD width=\"50\"><center>$row_temp[high]</center></TD>\n");
			print("<TD width=\"50\"><center>$row[$z]</center></TD>\n");
			if ($row[$z] >= $row_temp[low] && $row[$z] <= $row_temp[high])
			{
				print("<TD width=\"50\" BGCOLOR=\"#00dd00\"><center><font color=\"#ffffff\"><b>Pass</b></font></center></TD>\n");
				print("<TD width=\"50\"><center>---</center></TD>\n");
			} else {
				print("<TD width=\"50\"><center>---</center></TD>\n");
				print("<TD width=\"50\" BGCOLOR=\"#dd0000\"><center><font color=\"#ffffff\"><b>Fail</b></font></center></TD>\n");
			}
			print("</TR>\n");
		}
		
		### 07 - Seq2
		
		$seq2 = Count2Seq($draw_array);

		print("<TR>\n");
		print("<TD width=\"100\"><center>07 - Seq2</center></TD>\n");
		print("<TD width=\"50\"><center>0</center></TD>\n");
		print("<TD width=\"50\"><center>1</center></TD>\n");
		print("<TD width=\"50\"><center>$seq2</center></TD>\n");
		if ($seq2 >= 0 && $seq2 <= 1)
		{
			print("<TD width=\"50\" BGCOLOR=\"#00dd00\"><center><font color=\"#ffffff\"><b>Pass</b></font></center></TD>\n");
			print("<TD width=\"50\"><center>---</center></TD>\n");
		} else {
			print("<TD width=\"50\"><center>---</center></TD>\n");
			print("<TD width=\"50\" BGCOLOR=\"#dd0000\"><center><font color=\"#ffffff\"><b>Fail</b></font></center></TD>\n");
		}
		print("</TR>\n");

		### 08 - Seq3

		$draw = array ($row[b1],$row[b2],$row[b3],$row[b4],$row[b5]);

		$seq3 = Count3Seq($draw_array);

		print("<TR>\n");
		print("<TD width=\"100\"><center>08 - Seq3</center></TD>\n");
		print("<TD width=\"50\"><center>0</center></TD>\n");
		print("<TD width=\"50\"><center>0</center></TD>\n");
		print("<TD width=\"50\"><center>$seq3</center></TD>\n");
		if ($seq3 >= 0 && $seq3 <= 0)
		{
			print("<TD width=\"50\" BGCOLOR=\"#00dd00\"><center><font color=\"#ffffff\"><b>Pass</b></font></center></TD>\n");
			print("<TD width=\"50\"><center>---</center></TD>\n");
		} else {
			print("<TD width=\"50\"><center>---</center></TD>\n");
			print("<TD width=\"50\" BGCOLOR=\"#dd0000\"><center><font color=\"#ffffff\"><b>Fail</b></font></center></TD>\n");
		}
		print("</TR>\n");

		##################################################################

		$mod = array_fill (0, 10, 0);

		$mod2_count = 0;
		$mod3_count = 0;

		// build ranges and mod
		for($index = 0; $index < $balls_drawn; $index++)
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
		}

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

		### 09 - Mod

		print("<TR>\n");
		print("<TD width=\"100\"><center>09 - Mod</center></TD>\n");
		print("<TD width=\"50\"><center>0</center></TD>\n");
		print("<TD width=\"50\"><center>1</center></TD>\n");
		print("<TD width=\"50\"><center>$mod2_count</center></TD>\n");
		if ($mod2_count >= 0 && $mod2_count <= 1)
		{
			print("<TD width=\"50\" BGCOLOR=\"#00dd00\"><center><font color=\"#ffffff\"><b>Pass</b></font></center></TD>\n");
			print("<TD width=\"50\"><center>---</center></TD>\n");
		} else {
			print("<TD width=\"50\"><center>---</center></TD>\n");
			print("<TD width=\"50\" BGCOLOR=\"#dd0000\"><center><font color=\"#ffffff\"><b>Fail</b></font></center></TD>\n");
		}
		print("</TR>\n");

		### 10 - Modx

		print("<TR>\n");
		print("<TD width=\"100\"><center>10 - Modx</center></TD>\n");
		print("<TD width=\"50\"><center>0</center></TD>\n");
		print("<TD width=\"50\"><center>0</center></TD>\n");
		print("<TD width=\"50\"><center>$mod3_count</center></TD>\n");
		if ($mod3_count == 0)
		{
			print("<TD width=\"50\" BGCOLOR=\"#00dd00\"><center><font color=\"#ffffff\"><b>Pass</b></font></center></TD>\n");
			print("<TD width=\"50\"><center>---</center></TD>\n");
		} else {
			print("<TD width=\"50\"><center>---</center></TD>\n");
			print("<TD width=\"50\" BGCOLOR=\"#dd0000\"><center><font color=\"#ffffff\"><b>Fail</b></font></center></TD>\n");
		}
		print("</TR>\n");

		### 11a - Draw 01

		for ($z = 0; $z <= 3; $z++)
		{
			$query_temp = "SELECT * FROM $draw_prefix";
			$query_temp .= "filter_limits ";
			$query_temp .= "WHERE date = '$row[date]' ";
			$query_temp .= "AND limit_type = 'dr' ";
			$query_temp .= "AND hml = $hml ";
			$query_temp .= "AND col1_select = '$row[b1]' ";
			$query_temp .= "AND col_pos = '$z' ";
			$query_temp .= "ORDER BY id DESC ";

			#echo "$query_temp<br>";

			$mysqli_result_temp = mysqli_query($mysqli_link, $query_temp) or die (mysqli_error($mysqli_link));

			$row_temp = mysqli_fetch_array($mysqli_result_temp);

			$y = $z + 11;

			print("<TR>\n");
			print("<TD width=\"100\"><center>11_$z - Draw $z</center></TD>\n");
			print("<TD width=\"50\"><center>$row_temp[low]</center></TD>\n");
			print("<TD width=\"50\"><center>$row_temp[high]</center></TD>\n");
			print("<TD width=\"50\"><center>$row[$y]</center></TD>\n");
			if ($row[$y] >= $row_temp[low] && $row[$y] <= $row_temp[high])
			{
				print("<TD width=\"50\" BGCOLOR=\"#00dd00\"><center><font color=\"#ffffff\"><b>Pass</b></font></center></TD>\n");
				print("<TD width=\"50\"><center>---</center></TD>\n");
			} else {
				print("<TD width=\"50\"><center>---</center></TD>\n");
				print("<TD width=\"50\" BGCOLOR=\"#dd0000\"><center><font color=\"#ffffff\"><b>Fail</b></font></center></TD>\n");
			}
			print("</TR>\n");
		}

		### 12a - Rank 01

		for ($z = 0; $z <= 6; $z++)
		{
			$query_temp = "SELECT * FROM $draw_prefix";
			$query_temp .= "filter_limits ";
			$query_temp .= "WHERE date = '$row[date]' ";
			$query_temp .= "AND limit_type = 'rn' ";
			$query_temp .= "AND hml = $hml ";
			$query_temp .= "AND col1_select = '$row[b1]' ";
			$query_temp .= "AND col_pos = '$z' ";
			$query_temp .= "ORDER BY id DESC ";

			#echo "$query_temp<br>";

			$mysqli_result_temp = mysqli_query($mysqli_link, $query_temp) or die (mysqli_error($mysqli_link));

			$row_temp = mysqli_fetch_array($mysqli_result_temp);

			$y = $z + 15;

			print("<TR>\n");
			print("<TD width=\"100\"><center>12_$z - Rank $z</center></TD>\n");
			print("<TD width=\"50\"><center>$row_temp[low]</center></TD>\n");
			print("<TD width=\"50\"><center>$row_temp[high]</center></TD>\n");
			print("<TD width=\"50\"><center>$row[$y]</center></TD>\n");
			if ($row[$y] >= $row_temp[low] && $row[$y] <= $row_temp[high])
			{
				print("<TD width=\"50\" BGCOLOR=\"#00dd00\"><center><font color=\"#ffffff\"><b>Pass</b></font></center></TD>\n");
				print("<TD width=\"50\"><center>---</center></TD>\n");
			} else {
				print("<TD width=\"50\"><center>---</center></TD>\n");
				print("<TD width=\"50\" BGCOLOR=\"#dd0000\"><center><font color=\"#ffffff\"><b>Fail</b></font></center></TD>\n");
			}
			print("</TR>\n");
		}

		### 13a - Dup 01

		for ($z = 1; $z <= 10; $z++)
		{
			$query_temp = "SELECT * FROM $draw_prefix";
			$query_temp .= "filter_limits ";
			$query_temp .= "WHERE date = '$row[date]' ";
			$query_temp .= "AND limit_type = 'dp' ";
			$query_temp .= "AND hml = $hml ";
			$query_temp .= "AND col1_select = '$row[b1]' ";
			$query_temp .= "AND col_pos = '$z' ";
			$query_temp .= "ORDER BY id DESC ";

			#echo "$query_temp<br>";

			$mysqli_result_temp = mysqli_query($mysqli_link, $query_temp) or die (mysqli_error($mysqli_link));

			$row_temp = mysqli_fetch_array($mysqli_result_temp);

			$query_dup = "SELECT * FROM $draw_prefix";
			$query_dup .= "dup_table ";
			$query_dup .= "WHERE date = '$row[date]' ";

			$mysqli_result_dup = mysqli_query($query_dup, $mysqli_link) or die (mysqli_error($mysqli_link));

			$row_dup = mysqli_fetch_array($mysqli_result_dup);

			print("<TR>\n");
			print("<TD width=\"100\"><center>13_$z - Dup $z</center></TD>\n");
			print("<TD width=\"50\"><center>$row_temp[low]</center></TD>\n");
			print("<TD width=\"50\"><center>$row_temp[high]</center></TD>\n");
			print("<TD width=\"50\"><center>$row_dup[$z]</center></TD>\n");
			if ($row_dup[$z] >= $row_temp[low] && $row_dup[$z] <= $row_temp[high])
			{
				print("<TD width=\"50\" BGCOLOR=\"#00dd00\"><center><font color=\"#ffffff\"><b>Pass</b></font></center></TD>\n");
				print("<TD width=\"50\"><center>---</center></TD>\n");
			} else {
				print("<TD width=\"50\"><center>---</center></TD>\n");
				print("<TD width=\"50\" BGCOLOR=\"#dd0000\"><center><font color=\"#ffffff\"><b>Fail</b></font></center></TD>\n");
			}
			print("</TR>\n");
		}
		/*
		### 14a - Combo 01

		for ($z = 2; $z <= 5; $z++)
		{
			$query_temp = "SELECT * FROM $draw_prefix";
			$query_temp .= "filter_limits ";
			$query_temp .= "WHERE date = '$row[date]' ";
			$query_temp .= "AND limit_type = 'cb' ";
			$query_temp .= "AND col1_select = '$row[b1]' ";
			$query_temp .= "AND col_pos = '$z' ";
			$query_temp .= "ORDER BY id DESC ";

			#echo "$query_temp<br>";

			$mysqli_result_temp = mysqli_query($mysqli_link, $query_temp) or die (mysqli_error($mysqli_link));

			$row_temp = mysqli_fetch_array($mysqli_result_temp);

			$query_combo = "SELECT * FROM $draw_prefix";
			$query_combo .= "combo_table ";
			$query_combo .= "WHERE date = '$row[date]' ";

			$mysqli_result_combo = mysqli_query($query_combo, $mysqli_link) or die (mysqli_error($mysqli_link));

			$row_combo = mysqli_fetch_array($mysqli_result_combo);

			$y = $z - 1;

			print("<TR>\n");
			print("<TD width=\"100\"><center>13_$z - Cb $z</center></TD>\n");
			print("<TD width=\"50\"><center>$row_temp[low]</center></TD>\n");
			print("<TD width=\"50\"><center>$row_temp[high]</center></TD>\n");
			print("<TD width=\"50\"><center>$row_combo[$y]</center></TD>\n");
			if ($row_combo[$y] >= $row_temp[low] && $row_combo[$y] <= $row_temp[high])
			{
				print("<TD width=\"50\" BGCOLOR=\"#00dd00\"><center><font color=\"#ffffff\"><b>Pass</b></font></center></TD>\n");
				print("<TD width=\"50\"><center>---</center></TD>\n");
			} else {
				print("<TD width=\"50\"><center>---</center></TD>\n");
				print("<TD width=\"50\" BGCOLOR=\"#dd0000\"><center><font color=\"#ffffff\"><b>Fail</b></font></center></TD>\n");
			}
			print("</TR>\n");
		}
		*/

		### 14 - Avg

		$query_temp = "SELECT * FROM $draw_prefix";
		$query_temp .= "filter_limits ";
		$query_temp .= "WHERE date = '$row[date]' ";
		$query_temp .= "AND limit_type = 'av' ";
		$query_temp .= "AND hml = $hml ";
		$query_temp .= "AND col1_select = '$row[b1]' ";
		$query_temp .= "ORDER BY id DESC ";

		#echo "$query_temp<br>";

		$mysqli_result_temp = mysqli_query($mysqli_link, $query_temp) or die (mysqli_error($mysqli_link));

		$row_temp = mysqli_fetch_array($mysqli_result_temp);

		print("<TR>\n");
		print("<TD width=\"100\"><center>14 - Average</center></TD>\n");
		print("<TD width=\"50\"><center>$row_temp[low_f]</center></TD>\n");
		print("<TD width=\"50\"><center>$row_temp[high_f]</center></TD>\n");
		print("<TD width=\"50\"><center>$row[draw_average]</center></TD>\n");
		if ($row[draw_average] >= $row_temp[low_f] && $row[draw_average] <= $row_temp[high_f])
		{
			print("<TD width=\"50\" BGCOLOR=\"#00dd00\"><center><font color=\"#ffffff\"><b>Pass</b></font></center></TD>\n");
			print("<TD width=\"50\"><center>---</center></TD>\n");
		} else {
			print("<TD width=\"50\"><center>---</center></TD>\n");
			print("<TD width=\"50\" BGCOLOR=\"#dd0000\"><center><font color=\"#ffffff\"><b>Fail</b></font></center></TD>\n");
		}
		print("</TR>\n");

		### 15 - Median

		$query_temp = "SELECT * FROM $draw_prefix";
		$query_temp .= "filter_limits ";
		$query_temp .= "WHERE date = '$row[date]' ";
		$query_temp .= "AND limit_type = 'md' ";
		$query_temp .= "AND hml = $hml ";
		$query_temp .= "AND col1_select = '$row[b1]' ";
		$query_temp .= "ORDER BY id DESC ";

		#echo "$query_temp<br>";

		$mysqli_result_temp = mysqli_query($mysqli_link, $query_temp) or die (mysqli_error($mysqli_link));

		$row_temp = mysqli_fetch_array($mysqli_result_temp);

		print("<TR>\n");
		print("<TD width=\"100\"><center>15 - Median</center></TD>\n");
		print("<TD width=\"50\"><center>$row_temp[low_f]</center></TD>\n");
		print("<TD width=\"50\"><center>$row_temp[high_f]</center></TD>\n");
		print("<TD width=\"50\"><center>$row[median]</center></TD>\n");
		if ($row[median] >= $row_temp[low_f] && $row[median] <= $row_temp[high_f])
		{
			print("<TD width=\"50\" BGCOLOR=\"#00dd00\"><center><font color=\"#ffffff\"><b>Pass</b></font></center></TD>\n");
			print("<TD width=\"50\"><center>---</center></TD>\n");
		} else {
			print("<TD width=\"50\"><center>---</center></TD>\n");
			print("<TD width=\"50\" BGCOLOR=\"#dd0000\"><center><font color=\"#ffffff\"><b>Fail</b></font></center></TD>\n");
		}
		print("</TR>\n");

		### 16 - Harmean

		$query_temp = "SELECT * FROM $draw_prefix";
		$query_temp .= "filter_limits ";
		$query_temp .= "WHERE date = '$row[date]' ";
		$query_temp .= "AND limit_type = 'hm' ";
		$query_temp .= "AND hml = $hml ";
		$query_temp .= "AND col1_select = '$row[b1]' ";
		$query_temp .= "ORDER BY id DESC ";

		#echo "$query_temp<br>";

		$mysqli_result_temp = mysqli_query($mysqli_link, $query_temp) or die (mysqli_error($mysqli_link));

		$row_temp = mysqli_fetch_array($mysqli_result_temp);

		print("<TR>\n");
		print("<TD width=\"100\"><center>16 - Harmean</center></TD>\n");
		print("<TD width=\"50\"><center>$row_temp[low_f]</center></TD>\n");
		print("<TD width=\"50\"><center>$row_temp[high_f]</center></TD>\n");
		print("<TD width=\"50\"><center>$row[harmean]</center></TD>\n");
		if ($row[harmean] >= $row_temp[low_f] && $row[harmean] <= $row_temp[high_f])
		{
			print("<TD width=\"50\" BGCOLOR=\"#00dd00\"><center><font color=\"#ffffff\"><b>Pass</b></font></center></TD>\n");
			print("<TD width=\"50\"><center>---</center></TD>\n");
		} else {
			print("<TD width=\"50\"><center>---</center></TD>\n");
			print("<TD width=\"50\" BGCOLOR=\"#dd0000\"><center><font color=\"#ffffff\"><b>Fail</b></font></center></TD>\n");
		}
		print("</TR>\n");
		
		/*
		### 17 - Geomean

		$query_temp = "SELECT * FROM $draw_prefix";
		$query_temp .= "filter_limits ";
		$query_temp .= "WHERE date = '$row[date]' ";
		$query_temp .= "AND limit_type = 'gm' ";
		$query_temp .= "AND col1_select = '$row[b1]' ";
		$query_temp .= "ORDER BY id DESC ";

		#echo "$query_temp<br>";

		$mysqli_result_temp = mysqli_query($mysqli_link, $query_temp) or die (mysqli_error($mysqli_link));

		$row_temp = mysqli_fetch_array($mysqli_result_temp);

		print("<TR>\n");
		print("<TD width=\"100\"><center>17 - Geomean</center></TD>\n");
		print("<TD width=\"50\"><center>$row_temp[low_f]</center></TD>\n");
		print("<TD width=\"50\"><center>$row_temp[high_f]</center></TD>\n");
		print("<TD width=\"50\"><center>$row[geomean]</center></TD>\n");
		if ($row[geomean] >= $row_temp[low_f] && $row[geomean] <= $row_temp[high_f])
		{
			print("<TD width=\"50\" BGCOLOR=\"#00dd00\"><center><font color=\"#ffffff\"><b>Pass</b></font></center></TD>\n");
			print("<TD width=\"50\"><center>---</center></TD>\n");
		} else {
			print("<TD width=\"50\"><center>---</center></TD>\n");
			print("<TD width=\"50\" BGCOLOR=\"#dd0000\"><center><font color=\"#ffffff\"><b>Fail</b></font></center></TD>\n");
		}
		print("</TR>\n");

		*/

		### 18 - Quart1

		$query_temp = "SELECT * FROM $draw_prefix";
		$query_temp .= "filter_limits ";
		$query_temp .= "WHERE date = '$row[date]' ";
		$query_temp .= "AND limit_type = 'q1' ";
		$query_temp .= "AND col1_select = '$row[b1]' ";
		$query_temp .= "ORDER BY id DESC ";

		#echo "$query_temp<br>";

		$mysqli_result_temp = mysqli_query($mysqli_link, $query_temp) or die (mysqli_error($mysqli_link));

		$row_temp = mysqli_fetch_array($mysqli_result_temp);

		print("<TR>\n");
		print("<TD width=\"100\"><center>18 - Quart1</center></TD>\n");
		print("<TD width=\"50\"><center>$row_temp[low_f]</center></TD>\n");
		print("<TD width=\"50\"><center>$row_temp[high_f]</center></TD>\n");
		print("<TD width=\"50\"><center>$row[quart1]</center></TD>\n");
		if ($row[quart1] >= $row_temp[low_f] && $row[quart1] <= $row_temp[high_f])
		{
			print("<TD width=\"50\" BGCOLOR=\"#00dd00\"><center><font color=\"#ffffff\"><b>Pass</b></font></center></TD>\n");
			print("<TD width=\"50\"><center>---</center></TD>\n");
		} else {
			print("<TD width=\"50\"><center>---</center></TD>\n");
			print("<TD width=\"50\" BGCOLOR=\"#dd0000\"><center><font color=\"#ffffff\"><b>Fail</b></font></center></TD>\n");
		}
		print("</TR>\n");

		### 19 - Quart2

		$query_temp = "SELECT * FROM $draw_prefix";
		$query_temp .= "filter_limits ";
		$query_temp .= "WHERE date = '$row[date]' ";
		$query_temp .= "AND limit_type = 'q2' ";
		$query_temp .= "AND hml = $hml ";
		$query_temp .= "AND col1_select = '$row[b1]' ";
		$query_temp .= "ORDER BY id DESC ";

		#echo "$query_temp<br>";

		$mysqli_result_temp = mysqli_query($mysqli_link, $query_temp) or die (mysqli_error($mysqli_link));

		$row_temp = mysqli_fetch_array($mysqli_result_temp);

		print("<TR>\n");
		print("<TD width=\"100\"><center>19 - Quart2</center></TD>\n");
		print("<TD width=\"50\"><center>$row_temp[low_f]</center></TD>\n");
		print("<TD width=\"50\"><center>$row_temp[high_f]</center></TD>\n");
		print("<TD width=\"50\"><center>$row[quart2]</center></TD>\n");
		if ($row[quart2] >= $row_temp[low_f] && $row[quart2] <= $row_temp[high_f])
		{
			print("<TD width=\"50\" BGCOLOR=\"#00dd00\"><center><font color=\"#ffffff\"><b>Pass</b></font></center></TD>\n");
			print("<TD width=\"50\"><center>---</center></TD>\n");
		} else {
			print("<TD width=\"50\"><center>---</center></TD>\n");
			print("<TD width=\"50\" BGCOLOR=\"#dd0000\"><center><font color=\"#ffffff\"><b>Fail</b></font></center></TD>\n");
		}
		print("</TR>\n");

		### 20 - Quart3

		$query_temp = "SELECT * FROM $draw_prefix";
		$query_temp .= "filter_limits ";
		$query_temp .= "WHERE date = '$row[date]' ";
		$query_temp .= "AND limit_type = 'q3' ";
		$query_temp .= "AND hml = $hml ";
		$query_temp .= "AND col1_select = '$row[b1]' ";
		$query_temp .= "ORDER BY id DESC ";

		#echo "$query_temp<br>";

		$mysqli_result_temp = mysqli_query($mysqli_link, $query_temp) or die (mysqli_error($mysqli_link));

		$row_temp = mysqli_fetch_array($mysqli_result_temp);

		print("<TR>\n");
		print("<TD width=\"100\"><center>20 - Quart3</center></TD>\n");
		print("<TD width=\"50\"><center>$row_temp[low_f]</center></TD>\n");
		print("<TD width=\"50\"><center>$row_temp[high_f]</center></TD>\n");
		print("<TD width=\"50\"><center>$row[quart3]</center></TD>\n");
		if ($row[quart3] >= $row_temp[low_f] && $row[quart3] <= $row_temp[high_f])
		{
			print("<TD width=\"50\" BGCOLOR=\"#00dd00\"><center><font color=\"#ffffff\"><b>Pass</b></font></center></TD>\n");
			print("<TD width=\"50\"><center>---</center></TD>\n");
		} else {
			print("<TD width=\"50\"><center>---</center></TD>\n");
			print("<TD width=\"50\" BGCOLOR=\"#dd0000\"><center><font color=\"#ffffff\"><b>Fail</b></font></center></TD>\n");
		}
		print("</TR>\n");

		### 21 - Stdev

		$query_temp = "SELECT * FROM $draw_prefix";
		$query_temp .= "filter_limits ";
		$query_temp .= "WHERE date = '$row[date]' ";
		$query_temp .= "AND limit_type = 'sd' ";
		$query_temp .= "AND hml = $hml ";
		$query_temp .= "AND col1_select = '$row[b1]' ";
		$query_temp .= "ORDER BY id DESC ";

		#echo "$query_temp<br>";

		$mysqli_result_temp = mysqli_query($mysqli_link, $query_temp) or die (mysqli_error($mysqli_link));

		$row_temp = mysqli_fetch_array($mysqli_result_temp);

		print("<TR>\n");
		print("<TD width=\"100\"><center>21 - Stdev</center></TD>\n");
		print("<TD width=\"50\"><center>$row_temp[low_f]</center></TD>\n");
		print("<TD width=\"50\"><center>$row_temp[high_f]</center></TD>\n");
		print("<TD width=\"50\"><center>$row[stdev]</center></TD>\n");
		if ($row[stdev] >= $row_temp[low_f] && $row[stdev] <= $row_temp[high_f])
		{
			print("<TD width=\"50\" BGCOLOR=\"#00dd00\"><center><font color=\"#ffffff\"><b>Pass</b></font></center></TD>\n");
			print("<TD width=\"50\"><center>---</center></TD>\n");
		} else {
			print("<TD width=\"50\"><center>---</center></TD>\n");
			print("<TD width=\"50\" BGCOLOR=\"#dd0000\"><center><font color=\"#ffffff\"><b>Fail</b></font></center></TD>\n");
		}
		print("</TR>\n");

		### 22 - Variance

		$query_temp = "SELECT * FROM $draw_prefix";
		$query_temp .= "filter_limits ";
		$query_temp .= "WHERE date = '$row[date]' ";
		$query_temp .= "AND limit_type = 'vr' ";
		$query_temp .= "AND hml = $hml ";
		$query_temp .= "AND col1_select = '$row[b1]' ";
		$query_temp .= "ORDER BY id DESC ";

		#echo "$query_temp<br>";

		$mysqli_result_temp = mysqli_query($mysqli_link, $query_temp) or die (mysqli_error($mysqli_link));

		$row_temp = mysqli_fetch_array($mysqli_result_temp);

		print("<TR>\n");
		print("<TD width=\"100\"><center>22 - Variance</center></TD>\n");
		print("<TD width=\"50\"><center>$row_temp[low_f]</center></TD>\n");
		print("<TD width=\"50\"><center>$row_temp[high_f]</center></TD>\n");
		print("<TD width=\"50\"><center>$row[variance]</center></TD>\n");
		if ($row[variance] >= $row_temp[low_f] && $row[variance] <= $row_temp[high_f])
		{
			print("<TD width=\"50\" BGCOLOR=\"#00dd00\"><center><font color=\"#ffffff\"><b>Pass</b></font></center></TD>\n");
			print("<TD width=\"50\"><center>---</center></TD>\n");
		} else {
			print("<TD width=\"50\"><center>---</center></TD>\n");
			print("<TD width=\"50\" BGCOLOR=\"#dd0000\"><center><font color=\"#ffffff\"><b>Fail</b></font></center></TD>\n");
		}
		print("</TR>\n");

		### 23 - Avedev

		$query_temp = "SELECT * FROM $draw_prefix";
		$query_temp .= "filter_limits ";
		$query_temp .= "WHERE date = '$row[date]' ";
		$query_temp .= "AND limit_type = 'ad' ";
		$query_temp .= "AND hml = $hml ";
		$query_temp .= "AND col1_select = '$row[b1]' ";
		$query_temp .= "ORDER BY id DESC ";

		#echo "$query_temp<br>";

		$mysqli_result_temp = mysqli_query($mysqli_link, $query_temp) or die (mysqli_error($mysqli_link));

		$row_temp = mysqli_fetch_array($mysqli_result_temp);

		print("<TR>\n");
		print("<TD width=\"100\"><center>23 - Avedev</center></TD>\n");
		print("<TD width=\"50\"><center>$row_temp[low_f]</center></TD>\n");
		print("<TD width=\"50\"><center>$row_temp[high_f]</center></TD>\n");
		print("<TD width=\"50\"><center>$row[avedev]</center></TD>\n");
		if ($row[avedev] >= $row_temp[low_f] && $row[avedev] <= $row_temp[high_f])
		{
			print("<TD width=\"50\" BGCOLOR=\"#00dd00\"><center><font color=\"#ffffff\"><b>Pass</b></font></center></TD>\n");
			print("<TD width=\"50\"><center>---</center></TD>\n");
		} else {
			print("<TD width=\"50\"><center>---</center></TD>\n");
			print("<TD width=\"50\" BGCOLOR=\"#dd0000\"><center><font color=\"#ffffff\"><b>Fail</b></font></center></TD>\n");
		}
		print("</TR>\n");

		### 24 - Kurt

		$query_temp = "SELECT * FROM $draw_prefix";
		$query_temp .= "filter_limits ";
		$query_temp .= "WHERE date = '$row[date]' ";
		$query_temp .= "AND limit_type = 'kt' ";
		$query_temp .= "AND hml = $hml ";
		$query_temp .= "AND col1_select = '$row[b1]' ";
		$query_temp .= "ORDER BY id DESC ";

		#echo "$query_temp<br>";

		$mysqli_result_temp = mysqli_query($mysqli_link, $query_temp) or die (mysqli_error($mysqli_link));

		$row_temp = mysqli_fetch_array($mysqli_result_temp);

		print("<TR>\n");
		print("<TD width=\"100\"><center>24 - Kurt</center></TD>\n");
		print("<TD width=\"50\"><center>$row_temp[low_f]</center></TD>\n");
		print("<TD width=\"50\"><center>$row_temp[high_f]</center></TD>\n");
		print("<TD width=\"50\"><center>$row[kurt]</center></TD>\n");
		if ($row[kurt] >= $row_temp[low_f] && $row[kurt] <= $row_temp[high_f])
		{
			print("<TD width=\"50\" BGCOLOR=\"#00dd00\"><center><font color=\"#ffffff\"><b>Pass</b></font></center></TD>\n");
			print("<TD width=\"50\"><center>---</center></TD>\n");
		} else {
			print("<TD width=\"50\"><center>---</center></TD>\n");
			print("<TD width=\"50\" BGCOLOR=\"#dd0000\"><center><font color=\"#ffffff\"><b>Fail</b></font></center></TD>\n");
		}
		print("</TR>\n");

		### 25 - Skew

		$query_temp = "SELECT * FROM $draw_prefix";
		$query_temp .= "filter_limits ";
		$query_temp .= "WHERE date = '$row[date]' ";
		$query_temp .= "AND limit_type = 'sk' ";
		$query_temp .= "AND hml = $hml ";
		$query_temp .= "AND col1_select = '$row[b1]' ";
		$query_temp .= "ORDER BY id DESC ";

		#echo "$query_temp<br>";

		$mysqli_result_temp = mysqli_query($mysqli_link, $query_temp) or die (mysqli_error($mysqli_link));

		$row_temp = mysqli_fetch_array($mysqli_result_temp);

		print("<TR>\n");
		print("<TD width=\"100\"><center>25 - Skew</center></TD>\n");
		print("<TD width=\"50\"><center>$row_temp[low_f]</center></TD>\n");
		print("<TD width=\"50\"><center>$row_temp[high_f]</center></TD>\n");
		print("<TD width=\"50\"><center>$row[skew]</center></TD>\n");
		if ($row[skew] >= $row_temp[low_f] && $row[skew] <= $row_temp[high_f])
		{
			print("<TD width=\"50\" BGCOLOR=\"#00dd00\"><center><font color=\"#ffffff\"><b>Pass</b></font></center></TD>\n");
			print("<TD width=\"50\"><center>---</center></TD>\n");
		} else {
			print("<TD width=\"50\"><center>---</center></TD>\n");
			print("<TD width=\"50\" BGCOLOR=\"#dd0000\"><center><font color=\"#ffffff\"><b>Fail</b></font></center></TD>\n");
		}
		print("</TR>\n");

		### 26 - Devsq

		$query_temp = "SELECT * FROM $draw_prefix";
		$query_temp .= "filter_limits ";
		$query_temp .= "WHERE date = '$row[date]' ";
		$query_temp .= "AND limit_type = 'ds' ";
		$query_temp .= "AND hml = $hml ";
		$query_temp .= "AND col1_select = '$row[b1]' ";
		$query_temp .= "ORDER BY id DESC ";

		#echo "$query_temp<br>";

		$mysqli_result_temp = mysqli_query($mysqli_link, $query_temp) or die (mysqli_error($mysqli_link));

		$row_temp = mysqli_fetch_array($mysqli_result_temp);

		print("<TR>\n");
		print("<TD width=\"100\"><center>26 - Devsq</center></TD>\n");
		print("<TD width=\"50\"><center>$row_temp[low_f]</center></TD>\n");
		print("<TD width=\"50\"><center>$row_temp[high_f]</center></TD>\n");
		print("<TD width=\"50\"><center>$row[devsq]</center></TD>\n");
		if ($row[devsq] >= $row_temp[low_f] && $row[devsq] <= $row_temp[high_f])
		{
			print("<TD width=\"50\" BGCOLOR=\"#00dd00\"><center><font color=\"#ffffff\"><b>Pass</b></font></center></TD>\n");
			print("<TD width=\"50\"><center>---</center></TD>\n");
		} else {
			print("<TD width=\"50\"><center>---</center></TD>\n");
			print("<TD width=\"50\" BGCOLOR=\"#dd0000\"><center><font color=\"#ffffff\"><b>Fail</b></font></center></TD>\n");
		}
		print("</TR>\n");

		### 27 - Comb2

		$query_temp = "SELECT * FROM $draw_prefix";
		$query_temp .= "filter_limits ";
		$query_temp .= "WHERE date = '$row[date]' ";
		$query_temp .= "AND limit_type = 'cb' ";
		$query_temp .= "AND hml = $hml ";
		$query_temp .= "AND col1_select = '$row[b1]' ";
		$query_temp .= "AND col_pos = 2 ";
		$query_temp .= "ORDER BY id DESC ";


		$mysqli_result_temp = mysqli_query($mysqli_link, $query_temp) or die (mysqli_error($mysqli_link));

		$row_temp = mysqli_fetch_array($mysqli_result_temp);

		$query_temp2 = "SELECT * FROM $draw_prefix";
		$query_temp2 .= "combo_table ";
		$query_temp2 .= "WHERE date = '$row[date]' ";

		$mysqli_result_temp2 = mysqli_query($query_temp2, $mysqli_link) or die (mysqli_error($mysqli_link));

		$row_temp2 = mysqli_fetch_array($mysqli_result_temp2);

		print("<TR>\n");
		print("<TD width=\"100\"><center>27 - Comb2</center></TD>\n");
		print("<TD width=\"50\"><center>$row_temp[low]</center></TD>\n");
		print("<TD width=\"50\"><center>$row_temp[high]</center></TD>\n");
		print("<TD width=\"50\"><center>$row_temp2[c2]</center></TD>\n");
		if ($row_temp2[c2] >= $row_temp[low] && $row_temp2[c2] <= $row_temp[high])
		{
			print("<TD width=\"50\" BGCOLOR=\"#00dd00\"><center><font color=\"#ffffff\"><b>Pass</b></font></center></TD>\n");
			print("<TD width=\"50\"><center>---</center></TD>\n");
		} else {
			print("<TD width=\"50\"><center>---</center></TD>\n");
			print("<TD width=\"50\" BGCOLOR=\"#dd0000\"><center><font color=\"#ffffff\"><b>Fail</b></font></center></TD>\n");
		}
		print("</TR>\n");

		### 28 - Comb3

		$query_temp = "SELECT * FROM $draw_prefix";
		$query_temp .= "filter_limits ";
		$query_temp .= "WHERE date = '$row[date]' ";
		$query_temp .= "AND limit_type = 'cb' ";
		$query_temp .= "AND hml = $hml ";
		$query_temp .= "AND col1_select = '$row[b1]' ";
		$query_temp .= "AND col_pos = 3 ";
		$query_temp .= "ORDER BY id DESC ";


		$mysqli_result_temp = mysqli_query($mysqli_link, $query_temp) or die (mysqli_error($mysqli_link));

		$row_temp = mysqli_fetch_array($mysqli_result_temp);

		$query_temp2 = "SELECT * FROM $draw_prefix";
		$query_temp2 .= "combo_table ";
		$query_temp2 .= "WHERE date = '$row[date]' ";

		$mysqli_result_temp2 = mysqli_query($query_temp2, $mysqli_link) or die (mysqli_error($mysqli_link));

		$row_temp2 = mysqli_fetch_array($mysqli_result_temp2);

		print("<TR>\n");
		print("<TD width=\"100\"><center>28 - Comb3</center></TD>\n");
		print("<TD width=\"50\"><center>$row_temp[low]</center></TD>\n");
		print("<TD width=\"50\"><center>$row_temp[high]</center></TD>\n");
		print("<TD width=\"50\"><center>$row_temp2[c3]</center></TD>\n");
		if ($row_temp2[c3] >= $row_temp[low] && $row_temp2[c3] <= $row_temp[high])
		{
			print("<TD width=\"50\" BGCOLOR=\"#00dd00\"><center><font color=\"#ffffff\"><b>Pass</b></font></center></TD>\n");
			print("<TD width=\"50\"><center>---</center></TD>\n");
		} else {
			print("<TD width=\"50\"><center>---</center></TD>\n");
			print("<TD width=\"50\" BGCOLOR=\"#dd0000\"><center><font color=\"#ffffff\"><b>Fail</b></font></center></TD>\n");
		}
		print("</TR>\n");


		### 29 - Comb4

		$query_temp = "SELECT * FROM $draw_prefix";
		$query_temp .= "filter_limits ";
		$query_temp .= "WHERE date = '$row[date]' ";
		$query_temp .= "AND limit_type = 'cb' ";
		$query_temp .= "AND hml = $hml ";
		$query_temp .= "AND col1_select = '$row[b1]' ";
		$query_temp .= "AND col_pos = 4 ";
		$query_temp .= "ORDER BY id DESC ";


		$mysqli_result_temp = mysqli_query($mysqli_link, $query_temp) or die (mysqli_error($mysqli_link));

		$row_temp = mysqli_fetch_array($mysqli_result_temp);

		$query_temp2 = "SELECT * FROM $draw_prefix";
		$query_temp2 .= "combo_table ";
		$query_temp2 .= "WHERE date = '$row[date]' ";

		$mysqli_result_temp2 = mysqli_query($query_temp2, $mysqli_link) or die (mysqli_error($mysqli_link));

		$row_temp2 = mysqli_fetch_array($mysqli_result_temp2);

		print("<TR>\n");
		print("<TD width=\"100\"><center>29 - Comb4</center></TD>\n");
		print("<TD width=\"50\"><center>$row_temp[low]</center></TD>\n");
		print("<TD width=\"50\"><center>$row_temp[high]</center></TD>\n");
		print("<TD width=\"50\"><center>$row_temp2[c4]</center></TD>\n");
		if ($row_temp2[c4] >= $row_temp[low] && $row_temp2[c4] <= $row_temp[high])
		{
			print("<TD width=\"50\" BGCOLOR=\"#00dd00\"><center><font color=\"#ffffff\"><b>Pass</b></font></center></TD>\n");
			print("<TD width=\"50\"><center>---</center></TD>\n");
		} else {
			print("<TD width=\"50\"><center>---</center></TD>\n");
			print("<TD width=\"50\" BGCOLOR=\"#dd0000\"><center><font color=\"#ffffff\"><b>Fail</b></font></center></TD>\n");
		}
		print("</TR>\n");

		### 30 - Filter A

		$query_temp = "SELECT * FROM $draw_prefix";
		$query_temp .= "filter_a_";
		if ($row[b1] < 10)
		{
			$query_temp .= "0$row[b1] ";
		} else {
			$query_temp .= "$row[b1] ";
		}
		$query_temp .= "WHERE b1 = '$row[b1]' ";
		$query_temp .= "AND b2 = '$row[b2]' ";
		$query_temp .= "AND b3 = '$row[b3]' ";
		$query_temp .= "AND b4 = '$row[b4]' ";
		$query_temp .= "AND b5 = '$row[b5]' ";

		#echo "$query_temp<br>";

		$mysqli_result_temp = mysqli_query($mysqli_link, $query_temp) or die (mysqli_error($mysqli_link));

		$num_rows_all = mysqli_num_rows($mysqli_result_temp);

		print("<TR>\n");
		print("<TD width=\"100\"><center>30 - Filer A</center></TD>\n");
		print("<TD width=\"50\"><center>1</center></TD>\n");
		print("<TD width=\"50\"><center>1</center></TD>\n");
		print("<TD width=\"50\"><center>$num_rows_all</center></TD>\n");
		if ($num_rows_all)
		{
			print("<TD width=\"50\" BGCOLOR=\"#00dd00\"><center><font color=\"#ffffff\"><b>Pass</b></font></center></TD>\n");
			print("<TD width=\"50\"><center>---</center></TD>\n");
		} else {
			print("<TD width=\"50\"><center>---</center></TD>\n");
			print("<TD width=\"50\" BGCOLOR=\"#dd0000\"><center><font color=\"#ffffff\"><b>Fail</b></font></center></TD>\n");
		}
		print("</TR>\n");

		### 31 - Filter B

		$query_temp = "SELECT * FROM $draw_prefix";
		$query_temp .= "filter_b_";
		if ($row[b1] < 10)
		{
			$query_temp .= "0$row[b1] ";
		} else {
			$query_temp .= "$row[b1] ";
		}
		$query_temp .= "WHERE b1 = '$row[b1]' ";
		$query_temp .= "AND b2 = '$row[b2]' ";
		$query_temp .= "AND b3 = '$row[b3]' ";
		$query_temp .= "AND b4 = '$row[b4]' ";
		$query_temp .= "AND b5 = '$row[b5]' ";

		#echo "$query_temp<br>";

		$mysqli_result_temp = mysqli_query($mysqli_link, $query_temp) or die (mysqli_error($mysqli_link));

		$num_rows_all = mysqli_num_rows($mysqli_result_temp);

		print("<TR>\n");
		print("<TD width=\"100\"><center>31 - Filer B</center></TD>\n");
		print("<TD width=\"50\"><center>1</center></TD>\n");
		print("<TD width=\"50\"><center>1</center></TD>\n");
		print("<TD width=\"50\"><center>$num_rows_all</center></TD>\n");
		if ($num_rows_all)
		{
			print("<TD width=\"50\" BGCOLOR=\"#00dd00\"><center><font color=\"#ffffff\"><b>Pass</b></font></center></TD>\n");
			print("<TD width=\"50\"><center>---</center></TD>\n");
		} else {
			print("<TD width=\"50\"><center>---</center></TD>\n");
			print("<TD width=\"50\" BGCOLOR=\"#dd0000\"><center><font color=\"#ffffff\"><b>Fail</b></font></center></TD>\n");
		}
		print("</TR>\n");

		### 32 - Comb2-1

		$combo_count = test_combin_count_2_hml($row,$date_switch=1,$hml);

		$query_temp = "SELECT DISTINCT * FROM $draw_prefix";
		$query_temp .= "2_";
		$query_temp .= "$balls ";
		$query_temp .= "WHERE d1 = $row[b1] ";
		$query_temp .= "AND combo = 1 ";
		$query_temp .= "AND hml = $hml ";
		$query_temp .= "ORDER BY combo_count DESC ";

		#echo "$query_temp<br>";

		$mysqli_result_temp = mysqli_query($mysqli_link, $query_temp) or die (mysqli_error($mysqli_link));

		$row_temp2 = mysqli_fetch_array($mysqli_result_temp);

		$temp2 = intval($row_temp2[combo_count]*0.33)+1;

		print("<TR>\n");
		print("<TD width=\"100\"><center>32 - Comb2-1</center></TD>\n");
		print("<TD width=\"50\"><center>$temp2</center></TD>\n");
		print("<TD width=\"50\"><center>$row_temp2[combo_count]</center></TD>\n");
		print("<TD width=\"50\"><center>$combo_count[1]</center></TD>\n");
		if ($combo_count[1] >= $temp2)
		{
			print("<TD width=\"50\" BGCOLOR=\"#00dd00\"><center><font color=\"#ffffff\"><b>Pass</b></font></center></TD>\n");
			print("<TD width=\"50\"><center>---</center></TD>\n");
		} else {
			print("<TD width=\"50\"><center>---</center></TD>\n");
			print("<TD width=\"50\" BGCOLOR=\"#dd0000\"><center><font color=\"#ffffff\"><b>Fail</b></font></center></TD>\n");
		}
		print("</TR>\n");

		### 33 - Comb2-2

		#$combo_count = test_combin_count_2_hml($row,$date_switch=1,$hml);

		$query_temp = "SELECT DISTINCT * FROM $draw_prefix";
		$query_temp .= "2_";
		$query_temp .= "$balls ";
		$query_temp .= "WHERE d1 = $row[b1] ";
		$query_temp .= "AND combo = 2 ";
		$query_temp .= "AND hml = $hml ";
		$query_temp .= "ORDER BY combo_count DESC ";

		#echo "$query_temp<br>";

		$mysqli_result_temp = mysqli_query($mysqli_link, $query_temp) or die (mysqli_error($mysqli_link));

		$row_temp2 = mysqli_fetch_array($mysqli_result_temp);

		$temp2 = intval($row_temp2[combo_count]*0.33)+1;

		print("<TR>\n");
		print("<TD width=\"100\"><center>33 - Comb2-2</center></TD>\n");
		print("<TD width=\"50\"><center>$temp2</center></TD>\n");
		print("<TD width=\"50\"><center>$row_temp2[combo_count]</center></TD>\n");
		print("<TD width=\"50\"><center>$combo_count[2]</center></TD>\n");
		if ($combo_count[2] >= $temp2)
		{
			print("<TD width=\"50\" BGCOLOR=\"#00dd00\"><center><font color=\"#ffffff\"><b>Pass</b></font></center></TD>\n");
			print("<TD width=\"50\"><center>---</center></TD>\n");
		} else {
			print("<TD width=\"50\"><center>---</center></TD>\n");
			print("<TD width=\"50\" BGCOLOR=\"#dd0000\"><center><font color=\"#ffffff\"><b>Fail</b></font></center></TD>\n");
		}
		print("</TR>\n");

		### 34 - Comb2-3

		#$combo_count = test_combin_count_2($row,$date_switch=1);

		$query_temp = "SELECT DISTINCT * FROM $draw_prefix";
		$query_temp .= "2_";
		$query_temp .= "$balls ";
		$query_temp .= "WHERE d1 = $row[b1] ";
		$query_temp .= "AND combo = 3 ";
		$query_temp .= "AND hml = $hml ";
		$query_temp .= "ORDER BY combo_count DESC ";

		#echo "$query_temp<br>";

		$mysqli_result_temp = mysqli_query($mysqli_link, $query_temp) or die (mysqli_error($mysqli_link));

		$row_temp2 = mysqli_fetch_array($mysqli_result_temp);

		$temp2 = intval($row_temp2[combo_count]*0.33)+1;

		print("<TR>\n");
		print("<TD width=\"100\"><center>34 - Comb2-3</center></TD>\n");
		print("<TD width=\"50\"><center>$temp2</center></TD>\n");
		print("<TD width=\"50\"><center>$row_temp2[combo_count]</center></TD>\n");
		print("<TD width=\"50\"><center>$combo_count[3]</center></TD>\n");
		if ($combo_count[3] >= $temp2)
		{
			print("<TD width=\"50\" BGCOLOR=\"#00dd00\"><center><font color=\"#ffffff\"><b>Pass</b></font></center></TD>\n");
			print("<TD width=\"50\"><center>---</center></TD>\n");
		} else {
			print("<TD width=\"50\"><center>---</center></TD>\n");
			print("<TD width=\"50\" BGCOLOR=\"#dd0000\"><center><font color=\"#ffffff\"><b>Fail</b></font></center></TD>\n");
		}

		### 35 - Comb2-4

		#$combo_count = test_combin_count_2($row,$date_switch=1);

		$query_temp = "SELECT DISTINCT * FROM $draw_prefix";
		$query_temp .= "2_";
		$query_temp .= "$balls ";
		$query_temp .= "WHERE d1 = $row[b1] ";
		$query_temp .= "AND combo = 4 ";
		$query_temp .= "AND hml = $hml ";
		$query_temp .= "ORDER BY combo_count DESC ";

		#echo "$query_temp<br>";

		$mysqli_result_temp = mysqli_query($mysqli_link, $query_temp) or die (mysqli_error($mysqli_link));

		$row_temp2 = mysqli_fetch_array($mysqli_result_temp);

		$temp2 = intval($row_temp2[combo_count]*0.33)+1;

		print("<TR>\n");
		print("<TD width=\"100\"><center>35 - Comb2-4</center></TD>\n");
		print("<TD width=\"50\"><center>$temp2</center></TD>\n");
		print("<TD width=\"50\"><center>$row_temp2[combo_count]</center></TD>\n");
		print("<TD width=\"50\"><center>$combo_count[4]</center></TD>\n");
		if ($combo_count[4] >= $temp2)
		{
			print("<TD width=\"50\" BGCOLOR=\"#00dd00\"><center><font color=\"#ffffff\"><b>Pass</b></font></center></TD>\n");
			print("<TD width=\"50\"><center>---</center></TD>\n");
		} else {
			print("<TD width=\"50\"><center>---</center></TD>\n");
			print("<TD width=\"50\" BGCOLOR=\"#dd0000\"><center><font color=\"#ffffff\"><b>Fail</b></font></center></TD>\n");
		}

		### 36 - Comb2-5

		#$combo_count = test_combin_count_2($row,$date_switch=1);

		$query_temp = "SELECT DISTINCT * FROM $draw_prefix";
		$query_temp .= "2_";
		$query_temp .= "$balls ";
		$query_temp .= "WHERE d1 = $row[b2] ";
		$query_temp .= "AND combo = 5 ";
		$query_temp .= "AND hml = $hml ";
		$query_temp .= "ORDER BY combo_count DESC ";

		#echo "$query_temp<br>";

		$mysqli_result_temp = mysqli_query($mysqli_link, $query_temp) or die (mysqli_error($mysqli_link));

		$row_temp2 = mysqli_fetch_array($mysqli_result_temp);

		$temp2 = intval($row_temp2[combo_count]*0.33)+1;

		print("<TR>\n");
		print("<TD width=\"100\"><center>36 - Comb2-5</center></TD>\n");
		print("<TD width=\"50\"><center>$temp2</center></TD>\n");
		print("<TD width=\"50\"><center>$row_temp2[combo_count]</center></TD>\n");
		print("<TD width=\"50\"><center>$combo_count[5]</center></TD>\n");
		if ($combo_count[5] >= $temp2)
		{
			print("<TD width=\"50\" BGCOLOR=\"#00dd00\"><center><font color=\"#ffffff\"><b>Pass</b></font></center></TD>\n");
			print("<TD width=\"50\"><center>---</center></TD>\n");
		} else {
			print("<TD width=\"50\"><center>---</center></TD>\n");
			print("<TD width=\"50\" BGCOLOR=\"#dd0000\"><center><font color=\"#ffffff\"><b>Fail</b></font></center></TD>\n");
		}

		### 37 - Comb2-6

		$combo_count = test_combin_count_2_hml($row,$date_switch=1,$hml);

		$query_temp = "SELECT DISTINCT * FROM $draw_prefix";
		$query_temp .= "2_";
		$query_temp .= "$balls ";
		$query_temp .= "WHERE d1 = $row[b2] ";
		$query_temp .= "AND combo = 6 ";
		$query_temp .= "AND hml = $hml ";
		$query_temp .= "ORDER BY combo_count DESC ";

		#echo "$query_temp<br>";

		$mysqli_result_temp = mysqli_query($mysqli_link, $query_temp) or die (mysqli_error($mysqli_link));

		$row_temp2 = mysqli_fetch_array($mysqli_result_temp);

		$temp2 = intval($row_temp2[combo_count]*0.33)+1;

		print("<TR>\n");
		print("<TD width=\"100\"><center>37 - Comb2-6</center></TD>\n");
		print("<TD width=\"50\"><center>$temp2</center></TD>\n");
		print("<TD width=\"50\"><center>$row_temp2[combo_count]</center></TD>\n");
		print("<TD width=\"50\"><center>$combo_count[6]</center></TD>\n");
		if ($combo_count[6] >= $temp2)
		{
			print("<TD width=\"50\" BGCOLOR=\"#00dd00\"><center><font color=\"#ffffff\"><b>Pass</b></font></center></TD>\n");
			print("<TD width=\"50\"><center>---</center></TD>\n");
		} else {
			print("<TD width=\"50\"><center>---</center></TD>\n");
			print("<TD width=\"50\" BGCOLOR=\"#dd0000\"><center><font color=\"#ffffff\"><b>Fail</b></font></center></TD>\n");
		}
		print("</TR>\n");

		### 38 - Comb2-7

		#$combo_count = test_combin_count_2_hml($row,$date_switch=1,$hml);

		$query_temp = "SELECT DISTINCT * FROM $draw_prefix";
		$query_temp .= "2_";
		$query_temp .= "$balls ";
		$query_temp .= "WHERE d1 = $row[b2] ";
		$query_temp .= "AND combo = 7 ";
		$query_temp .= "AND hml = $hml ";
		$query_temp .= "ORDER BY combo_count DESC ";

		#echo "$query_temp<br>";

		$mysqli_result_temp = mysqli_query($mysqli_link, $query_temp) or die (mysqli_error($mysqli_link));

		$row_temp2 = mysqli_fetch_array($mysqli_result_temp);

		$temp2 = intval($row_temp2[combo_count]*0.33)+1;

		print("<TR>\n");
		print("<TD width=\"100\"><center>38 - Comb2-7</center></TD>\n");
		print("<TD width=\"50\"><center>$temp2</center></TD>\n");
		print("<TD width=\"50\"><center>$row_temp2[combo_count]</center></TD>\n");
		print("<TD width=\"50\"><center>$combo_count[7]</center></TD>\n");
		if ($combo_count[7] >= $temp2)
		{
			print("<TD width=\"50\" BGCOLOR=\"#00dd00\"><center><font color=\"#ffffff\"><b>Pass</b></font></center></TD>\n");
			print("<TD width=\"50\"><center>---</center></TD>\n");
		} else {
			print("<TD width=\"50\"><center>---</center></TD>\n");
			print("<TD width=\"50\" BGCOLOR=\"#dd0000\"><center><font color=\"#ffffff\"><b>Fail</b></font></center></TD>\n");
		}
		print("</TR>\n");

		### 39 - Comb2-8

		#$combo_count = test_combin_count_2($row,$date_switch=1);

		$query_temp = "SELECT DISTINCT * FROM $draw_prefix";
		$query_temp .= "2_";
		$query_temp .= "$balls ";
		$query_temp .= "WHERE d1 = $row[b3] ";
		$query_temp .= "AND combo = 8 ";
		$query_temp .= "AND hml = $hml ";
		$query_temp .= "ORDER BY combo_count DESC ";

		#echo "$query_temp<br>";

		$mysqli_result_temp = mysqli_query($mysqli_link, $query_temp) or die (mysqli_error($mysqli_link));

		$row_temp2 = mysqli_fetch_array($mysqli_result_temp);

		$temp2 = intval($row_temp2[combo_count]*0.33)+1;

		print("<TR>\n");
		print("<TD width=\"100\"><center>39 - Comb2-8</center></TD>\n");
		print("<TD width=\"50\"><center>$temp2</center></TD>\n");
		print("<TD width=\"50\"><center>$row_temp2[combo_count]</center></TD>\n");
		print("<TD width=\"50\"><center>$combo_count[8]</center></TD>\n");
		if ($combo_count[8] >= $temp2)
		{
			print("<TD width=\"50\" BGCOLOR=\"#00dd00\"><center><font color=\"#ffffff\"><b>Pass</b></font></center></TD>\n");
			print("<TD width=\"50\"><center>---</center></TD>\n");
		} else {
			print("<TD width=\"50\"><center>---</center></TD>\n");
			print("<TD width=\"50\" BGCOLOR=\"#dd0000\"><center><font color=\"#ffffff\"><b>Fail</b></font></center></TD>\n");
		}

		### 40 - Comb2-9

		#$combo_count = test_combin_count_2($row,$date_switch=1);

		$query_temp = "SELECT DISTINCT * FROM $draw_prefix";
		$query_temp .= "2_";
		$query_temp .= "$balls ";
		$query_temp .= "WHERE d1 = $row[b3] ";
		$query_temp .= "AND combo = 9 ";
		$query_temp .= "AND hml = $hml ";
		$query_temp .= "ORDER BY combo_count DESC ";

		#echo "$query_temp<br>";

		$mysqli_result_temp = mysqli_query($mysqli_link, $query_temp) or die (mysqli_error($mysqli_link));

		$row_temp2 = mysqli_fetch_array($mysqli_result_temp);

		$temp2 = intval($row_temp2[combo_count]*0.33)+1;

		print("<TR>\n");
		print("<TD width=\"100\"><center>40 - Comb2-9</center></TD>\n");
		print("<TD width=\"50\"><center>$temp2</center></TD>\n");
		print("<TD width=\"50\"><center>$row_temp2[combo_count]</center></TD>\n");
		print("<TD width=\"50\"><center>$combo_count[9]</center></TD>\n");
		if ($combo_count[9] >= $temp2)
		{
			print("<TD width=\"50\" BGCOLOR=\"#00dd00\"><center><font color=\"#ffffff\"><b>Pass</b></font></center></TD>\n");
			print("<TD width=\"50\"><center>---</center></TD>\n");
		} else {
			print("<TD width=\"50\"><center>---</center></TD>\n");
			print("<TD width=\"50\" BGCOLOR=\"#dd0000\"><center><font color=\"#ffffff\"><b>Fail</b></font></center></TD>\n");
		}

		### 40 - Comb2-10

		#$combo_count = test_combin_count_2($row,$date_switch=1);

		$query_temp = "SELECT DISTINCT * FROM $draw_prefix";
		$query_temp .= "2_";
		$query_temp .= "$balls ";
		$query_temp .= "WHERE d1 = $row[b4] ";
		$query_temp .= "AND combo = 10 ";
		$query_temp .= "AND hml = $hml ";
		$query_temp .= "ORDER BY combo_count DESC ";

		#echo "$query_temp<br>";

		$mysqli_result_temp = mysqli_query($mysqli_link, $query_temp) or die (mysqli_error($mysqli_link));

		$row_temp2 = mysqli_fetch_array($mysqli_result_temp);

		$temp2 = intval($row_temp2[combo_count]*0.33)+1;

		print("<TR>\n");
		print("<TD width=\"100\"><center>40 - Comb2-10</center></TD>\n");
		print("<TD width=\"50\"><center>$temp2</center></TD>\n");
		print("<TD width=\"50\"><center>$row_temp2[combo_count]</center></TD>\n");
		print("<TD width=\"50\"><center>$combo_count[10]</center></TD>\n");
		if ($combo_count[10] >= $temp2)
		{
			print("<TD width=\"50\" BGCOLOR=\"#00dd00\"><center><font color=\"#ffffff\"><b>Pass</b></font></center></TD>\n");
			print("<TD width=\"50\"><center>---</center></TD>\n");
		} else {
			print("<TD width=\"50\"><center>---</center></TD>\n");
			print("<TD width=\"50\" BGCOLOR=\"#dd0000\"><center><font color=\"#ffffff\"><b>Fail</b></font></center></TD>\n");
		}

		## 41 - Comb3-1

		$combo_count = test_combin_count_3_hml($row,$date_switch=1,$hml);

		$query_temp = "SELECT DISTINCT * FROM $draw_prefix";
		$query_temp .= "3_";
		$query_temp .= "$balls ";
		$query_temp .= "WHERE d1 = $row[b1] ";
		$query_temp .= "AND   d2 = $row[b2] ";
		$query_temp .= "AND   d3 = $row[b3] ";
		$query_temp .= "AND combo = 1 ";
		$query_temp .= "AND hml = $hml ";
		$query_temp .= "AND date < '$row[date]' ";
		$query_temp .= "ORDER BY combo_count DESC ";

		#echo "$query_temp<br>";

		$mysqli_result_temp = mysqli_query($mysqli_link, $query_temp) or die (mysqli_error($mysqli_link));

		$row_temp2 = mysqli_fetch_array($mysqli_result_temp);

		$num_rows = mysqli_num_rows($mysqli_result_temp);

		print("<TR>\n");
		print("<TD width=\"100\"><center>41 - Comb3-1</center></TD>\n");
		print("<TD width=\"50\"><center>0</center></TD>\n");
		print("<TD width=\"50\"><center>0</center></TD>\n");
		print("<TD width=\"50\"><center>$combo_count[1]</center></TD>\n");
		if (!$combo_count[1])
		{
			print("<TD width=\"50\" BGCOLOR=\"#00dd00\"><center><font color=\"#ffffff\"><b>Pass</b></font></center></TD>\n");
			print("<TD width=\"50\"><center>---</center></TD>\n");
		} else {
			print("<TD width=\"50\"><center>---</center></TD>\n");
			print("<TD width=\"50\" BGCOLOR=\"#dd0000\"><center><font color=\"#ffffff\"><b>$num_rows</b></font></center></TD>\n");
		}

		## 42 - Comb3-2

		#$combo_count = test_combin_count_3_hml($row,$date_switch=1,$hml);

		$query_temp = "SELECT DISTINCT * FROM $draw_prefix";
		$query_temp .= "3_";
		$query_temp .= "$balls ";
		$query_temp .= "WHERE d1 = $row[b1] ";
		$query_temp .= "AND   d2 = $row[b2] ";
		$query_temp .= "AND   d3 = $row[b4] ";
		$query_temp .= "AND combo = 2 ";
		$query_temp .= "AND hml = $hml ";
		$query_temp .= "AND date < '$row[date]' ";
		$query_temp .= "ORDER BY combo_count DESC ";

		#echo "$query_temp<br>";

		$mysqli_result_temp = mysqli_query($mysqli_link, $query_temp) or die (mysqli_error($mysqli_link));

		$num_rows = mysqli_num_rows($mysqli_result_temp);

		$row_temp2 = mysqli_fetch_array($mysqli_result_temp);

		print("<TR>\n");
		print("<TD width=\"100\"><center>42 - Comb3-2</center></TD>\n");
		print("<TD width=\"50\"><center>0</center></TD>\n");
		print("<TD width=\"50\"><center>0</center></TD>\n");
		print("<TD width=\"50\"><center>$combo_count[2]</center></TD>\n");
		if (!$combo_count[2])
		{
			print("<TD width=\"50\" BGCOLOR=\"#00dd00\"><center><font color=\"#ffffff\"><b>Pass</b></font></center></TD>\n");
			print("<TD width=\"50\"><center>---</center></TD>\n");
		} else {
			print("<TD width=\"50\"><center>---</center></TD>\n");
			print("<TD width=\"50\" BGCOLOR=\"#dd0000\"><center><font color=\"#ffffff\"><b>$num_rows</b></font></center></TD>\n");
		}

		print("</TR>\n");

		print("</TABLE>\n");

		print("<b><p>Sum</p></b>");

		print("<b><p>Wheel</p></b>");
		
		print("<b><p>Even/Odd<p></b>");

		print("<b><p>d2_1/d2_2</p></b>");

		print("<b><p>Draw</p></b>");

		print("<b><p>Rank</p></b>");

		print("<b><p>Dup</p></b>");

		print("<b><p>Combo</p></b>");

		print("<b><p>------------------------------------------------------------------------------------------------------</p></b>");

		

	}

	print "<p><a href=\"lot_test.php\" target=\"_blank\">Open lot_test.php</a></p>"; 

	//close page
	print("</BODY>\n");
	print("</HTML>\n");

?>
