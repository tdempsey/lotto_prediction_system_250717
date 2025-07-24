<?php

	// Game ----------------------------- Game
	$game = 1; // Georgia Fantasy 5
	#$game = 2; // Mega Millions
	//$game = 3; // Georgia 5
	#$game = 4; // Jumbo
	#$game = 5; // Florida Fantasy 5
	#$game = 6; // Florida Lotto
	#$game = 7; // Powerball
	// Game ----------------------------- Game

	$hml = 0;
	#$hml = 1;		#= high
	#$hml = 2;		#= medium
	#$hml = 3;		#= low
	#$hml = 4;		#= min
	#$hml = 5;		#= max
	#$hml = 100;

	set_time_limit(0);

	$debug = 1;

	if ($debug)
	{
		ini_set('display_errors', '1');
		ini_set('display_startup_errors', '1');
		error_reporting(E_ALL);
	}

	$col1_select = 0;
	
	require_once ("includes/games_switch.incl");
	require_once ("includes/even_odd.php");
	require_once ("includes/last_draws.php");
	require_once ("includes/calculate_rank.php");
	require_once ("includes/look_up_rank.php"); 
	require_once ("includes/build_rank_table.php");
	require_once ("includes/test_column_lookup.php");
	require_once ("includes/calculate_draw.php"); 
	require_once ("includes/calculate_rank_mb.php");
	require_once ("includes/next_draw.php");
	require_once ("includes/number_due.php");
	require_once ("includes/first_draw_unix.php");
	require_once ("includes/last_draw_unix.php"); 
	require_once ("includes/count_2_seq.php");
	require_once ("includes/count_3_seq.php");
	require_once ("includes/mysqli.php"); 
	require_once ("$game_includes/combin.incl");
	require_once ("includes/dateDiffInDays.php");
	require ("includes_ga_f5/split_sumeo_2.php");
	require ("includes_ga_f5/split_sumeo_3.php");
	require ("includes_ga_f5/split_sumeo_4.php");
	require ("includes_ga_f5/split_sumeo_5.php");
	require_once ("includes/print_column_test_sumeo.php");
	#require ("includes/print_sumeo_table_drange2_sumeo.incl");
	#require_once ("includes/log_text.incl");

	require_once ("includes/limit_functions.incl");

	date_default_timezone_set('America/New_York');

	#echo "<b>col1_select = $col1_select in lot_display.php</b><p>";

	require_once ("includes/hml_switch.incl");	

	$debug = 0;
	
	// ----------------------------------------------------------------------------------
	function print_table($limit)
	{

		global $draw_table_name, $balls, $balls_drawn, $game_includes, $draw_prefix, $game_includes, $hml, $range_low, $range_high, $col1_select; 

		require ("includes/mysqli.php"); 

		require ("$game_includes/config.incl");

		$query = "SELECT * FROM $draw_table_name ";
		$query .= "ORDER BY date DESC ";
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
		
		#if ($mega_balls) 200721
		#{
		#	print("<TD BGCOLOR=\"#CCCCCC\">Mega</TD>\n");
		#}

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
		print("<TD BGCOLOR=\"#CCCCCC\"><CENTER>r7</CENTER></TD>\n");

		#if ($mega_balls) 200721
		#{
		#	print("<TD BGCOLOR=\"#CCCCCC\">MB</TD>\n");
		#}

		#print("<TD BGCOLOR=\"#CCCCCC\"><font size=\"0\">nums2</font></TD>\n");
		#print("<TD BGCOLOR=\"#CCCCCC\"><font size=\"0\">combo2</font></TD>\n");
		#print("<TD BGCOLOR=\"#CCCCCC\"><font size=\"0\">nums3</font></TD>\n");
		#print("<TD BGCOLOR=\"#CCCCCC\"><font size=\"0\">combo3</font></TD>\n");
		#print("<TD BGCOLOR=\"#CCCCCC\"><font size=\"0\">nums4</font></TD>\n");
		#print("<TD BGCOLOR=\"#CCCCCC\"><font size=\"0\">combo4</font></TD>\n");

		

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

		$row = array ();

		while($row = mysqli_fetch_array($mysqli_result))
		{
			require ("includes/d0.incl");

			$num_range = array_fill (0,20,0);
			$num_range_sum[$x] = array_fill (0,20,0);

			$num_range_d2_1 = 0;
			$num_range_d2_2 = 0;
			
			// build draw array [0..x]
			$draw_array = array ();
	
			for ($x = 1; $x <= $balls_drawn; $x++)
			{
				array_push($draw_array, $row[$x]);
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
				###
			} else {
				$rank_count = BuildRankTable($row[0]); // array 0..balls with total draws for last 2 ### 210814

				$rank_table_count = array_fill (0, 8, 0);

				for($z = 1; $z <= $balls; $z++)
				{
					if ($rank_count[$z] >= 7)
					{
						$rank_table_count[7]++;
					} else {
						$rank_table_count[$rank_count[$z]]++;
					}
				}
			}

			//
			
			print("<TR>\n");

			print("<TD nowrap><font size=\"-1\">$row[0]</font></TD>\n");

			if ($game == 10 OR $game == 20)
			{
				###
			} else {
				for ($y = 1; $y <= $balls_drawn; $y++)
				{
					print("<TD><center>$row[$y]<br>\n");

					$rank_ball = $rank_count[$row[$y]];
					
					print("<font size=\"-1\" color=\"#ff0000\"><b>$rank_ball</b></font></center></TD>\n");
				}
			}

			if ($row[6] < $sum_low || $row[6] > $sum_high)
			{
				print("<TD align=center><font color=\"#ff0000\"><b>$row[6]</b></font></TD>\n");
			} else {
				print("<TD align=center>$row[6]</TD>\n");
			}

			if ($even <= 0 || $even >= $balls_drawn) {
				print("<TD BGCOLOR=\"#99CCFF\"><strong><CENTER>$row[even]/$row[odd]</CENTER></strong></TD>\n");
			} else {
				print("<TD BGCOLOR=\"#99CCFF\"><CENTER>$row[even]/$row[odd]</CENTER></TD>\n");
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
			/*
			$query3 = "SELECT * FROM $draw_table_name ";
			$query3 .= "WHERE sum = $row[0] ";
			$query3 .= "AND   even = $row[even] ";
			$query3 .= "AND   odd = $row[odd] ";
			$query3 .= "AND   d2_1 = $row[d2_1] ";
			$query3 .= "AND   d2_2 = $row[d2_2] ";
			$query3 .= "AND date < $row[date] ";
			*/
			#echo "$query3<br>";
		
			#$mysqli_result3 = mysqli_query($mysqli_link, $query3) or die (mysqli_error($mysqli_link)); #200909

			#$num_rows_all = mysqli_num_rows($mysqli_result3); #200909

			#$row3 = mysqli_fetch_array($mysqli_result3); #200909

			print("<TD><CENTER>---</CENTER></TD>\n");

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

			$draw_rank_count = array_fill (0, 8, 0); #180907
			$draw_rank_sum = array_fill (0, 8, 0); #200721
			for($y = 1; $y <= $balls_drawn; $y++)
			{
				if ($rank_count[$row[$y]] >= 7) #200321
				{
					$draw_rank_count[7]++; #200321	
				} else {
					$draw_rank_count[$rank_count[$row[$y]]]++;
				}
			}

			for($x = 0; $x <= 7; $x++) #200321
			{
				$draw_rank_sum[$x] += $draw_rank_count[$x];
			}

			// add loop below

			if ($draw_rank_count[0] > 2) {
				print("<TD><CENTER><font color=\"#ff0000\"><B>$draw_rank_count[0]/$rank_table_count[0]</B></font></CENTER></TD>\n");
			} else {
				print("<TD><CENTER>$draw_rank_count[0]/$rank_table_count[0]</CENTER></TD>\n");
			}
			if ($draw_rank_count[1] > 2) {
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
			if ($draw_rank_count[6] > 2) {
				print("<TD><CENTER><font color=\"#ff0000\"><B>$draw_rank_count[6]/$rank_table_count[6]</B></font></CENTER></TD>\n");
			} else {
				print("<TD><CENTER>$draw_rank_count[6]/$rank_table_count[6]</CENTER></TD>\n");
			}
			if ($draw_rank_count[7] > 2) { 
				print("<TD><CENTER><font color=\"#ff0000\"><B>$draw_rank_count[7]/$rank_table_count[6]</B></font></CENTER></TD>\n");
			} else {
				print("<TD><CENTER>$draw_rank_count[7]/$rank_table_count[7]</CENTER></TD>\n");
			} #200321
	
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

			/*#
			print("<TD><CENTER>$temp_nums_count_2</CENTER></TD>\n");
			print("<TD><CENTER>$temp_combo_count_2</CENTER></TD>\n");

			print("<TD><CENTER>$temp_nums_count_3</CENTER></TD>\n");
			#print("<TD><CENTER>$temp_combo_count_3</CENTER></TD>\n");

			if ($temp_combo_count_3)
			{
				print("<TD align=center><font size=\"-1\"><b>$temp_combo_count_3</b></font></TD>\n");
			} else {
				print("<TD align=center>$temp_combo_count_3</TD>\n");
			}

			if ($temp_nums_count_4)
			{
				print("<TD align=center><font color=\"#ff0000\"><b>$temp_nums_count_4</b></font></TD>\n");
			} else {
				print("<TD align=center>$temp_nums_count_4</TD>\n");
			}

			if ($temp_combo_count_4)
			{
				print("<TD align=center><font color=\"#ff0000\"><b>$temp_combo_count_4</b></font></TD>\n");
			} else {
				print("<TD align=center>$temp_combo_count_4</TD>\n");
			}

			#print("<TD><CENTER>$temp_nums_count_4</CENTER></TD>\n");
			#print("<TD><CENTER>$temp_combo_count_4</CENTER></TD>\n");
			*/

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
		
		/*
		for($x = 1; $x < $balls_drawn; $x++) #190310
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
		*/
		print("</TR>\n");
		print("</TABLE>\n");
	}

	// ----------------------------------------------------------------------------------
	function print_draw_summary_sumeo($sum,$even,$odd)
	{
		global $draw_table_name, $balls, $balls_drawn, $draw_prefix, $col1_select, $hml, $range_low, $range_high; 

		require ("includes/mysqli.php");

		require ("includes/unix.incl");
	
		require ("includes/calculate_draw_summary_sumeo.incl");

		require ("includes/print_draw_summary_sumeo.incl");

		#print "<h3>Table <font color=\"#ff0000\">{$draw_prefix}sum</font> Updated!</h3>";
	}

	// ----------------------------------------------------------------------------------
	function print_sum_grid()
	{
		global $draw_table_name, $balls, $balls_drawn, $draw_prefix, $col1_select, $hml, $range_low, $range_high; 

		require ("includes/mysqli.php");
	
		require ("includes/calculate_sum_count.incl");

		require ("includes/print_sum_table.incl");

		#print "<h3>Table <font color=\"#ff0000\">{$draw_prefix}sum</font> Updated!</h3>";
	}

	// ----------------------------------------------------------------------------------
	function print_sum_grid_sum()
	{
		global $draw_table_name, $balls, $balls_drawn, $draw_prefix, $col1_select, $hml, $range_low, $range_high; 

		require ("includes/mysqli.php");
	
		require ("includes/calculate_sum_count_sum.incl");

		require ("includes/print_sum_table_sum.incl");

		require ("includes/print_sum_table_sum_top25.incl");

		#print "<h3>Table <font color=\"#ff0000\">{$draw_prefix}sum</font> Updated!</h3>";
	}

	// ----------------------------------------------------------------------------------
	function print_sumeo_drange_summary()
	{
		global $draw_table_name, $balls, $balls_drawn, $draw_prefix, $col1_select, $hml, $range_low, $range_high; 

		require ("includes/mysqli.php");
	
		require ("includes/calculate_sumeo_drange_summary.incl");

		require ("includes/print_sumeo_table_drange_summary.incl");

		require ("includes/print_sumeo_table_drange_summary_top25.incl");

		#print "<h3>Table <font color=\"#ff0000\">{$draw_prefix}sum</font> Updated!</h3>";
	}

	// ----------------------------------------------------------------------------------
	function print_sum_grid_colx()
	{
		global $draw_table_name, $balls, $balls_drawn, $draw_prefix, $col1_select, $hml, $range_low, $range_high; 

		require ("includes/mysqli.php");
	
		require ("includes/calculate_sum_count_colx.incl");

		require ("includes/print_sum_table_colx.incl");

		#print "<h3>Table <font color=\"#ff0000\">{$draw_prefix}sum</font> Updated!</h3>";
	}

	// ----------------------------------------------------------------------------------
	function print_sum_grid_sum_colx()
	{
		global $draw_table_name, $balls, $balls_drawn, $draw_prefix, $col1_select, $hml, $range_low, $range_high; 

		require ("includes/mysqli.php");
	
		require ("includes/calculate_sum_count_sum_colx.incl");

		require ("includes/print_sum_table_sum_colx.incl");

		require ("includes/print_sum_table_sum_top25_colx.incl");

		#print "<h3>Table <font color=\"#ff0000\">{$draw_prefix}sum</font> Updated!</h3>";
	}

	// ----------------------------------------------------------------------------------
	function print_combo_by_sum()
	{
		global $draw_table_name, $balls, $balls_drawn, $draw_prefix, $col1_select, $hml, $range_low, $range_high; 

		require ("includes/mysqli.php");

		$query4 = "DROP TABLE IF EXISTS $draw_prefix";
		$query4 .= "combo_by_sum ";
		
		#print "$query4<p>";

		$mysqli_result4 = mysqli_query($mysqli_link, $query4) or die (mysqli_error($mysqli_link));

		$query4 = "CREATE TABLE $draw_prefix";
		$query4 .= "combo_by_sum (";
		$query4 .= " id int(10) unsigned NOT NULL auto_increment, ";
		$query4 .= "sum tinyint(3) unsigned NOT NULL default '0',";
		$query4 .= "combo tinyint(3) unsigned NOT NULL default '0',";
		$query4 .= "d1 tinyint(3) unsigned NOT NULL default '0',";
		$query4 .= "d2 tinyint(3) unsigned NOT NULL default '0',";
		$query4 .= "day1 tinyint(3) unsigned NOT NULL default '0', ";
		$query4 .= "week1 tinyint(3) unsigned NOT NULL default '0', ";
		$query4 .= "week2 tinyint(3) unsigned NOT NULL default '0', ";
		$query4 .= "month1 tinyint(3) unsigned NOT NULL default '0', ";
		$query4 .= "month3 tinyint(3) unsigned NOT NULL default '0', ";
		$query4 .= "month6 tinyint(3) unsigned NOT NULL default '0', ";
		$query4 .= "year1 int(5) unsigned NOT NULL default '0', ";
		$query4 .= "year2 int(5) unsigned NOT NULL default '0', ";
		$query4 .= "year3 int(5) unsigned NOT NULL default '0', ";
		$query4 .= "year4 int(5) unsigned NOT NULL default '0', ";
		$query4 .= "year5 int(5) unsigned NOT NULL default '0', ";
		$query4 .= "year6 int(5) unsigned NOT NULL default '0', ";
		$query4 .= "year7 int(5) unsigned NOT NULL default '0', ";
		$query4 .= "year8 int(5) unsigned NOT NULL default '0', ";
		$query4 .= "year9 int(5) unsigned NOT NULL default '0', ";
		$query4 .= "year10 int(5) unsigned NOT NULL default '0', ";
		$query4 .= "count int(5) unsigned NOT NULL,";
		$query4 .= "percent_y1 float(4,2) unsigned NOT NULL,";
		$query4 .= "percent_y8 float(4,2) unsigned NOT NULL,";
		$query4 .= "percent_wa float(4,2) unsigned NOT NULL,";
		$query4 .= "PRIMARY KEY  (`id`),";
		$query4 .= "UNIQUE KEY `id_2` (`id`),";
		$query4 .= "KEY `id` (`id`)";
		$query4 .= ") ENGINE=InnoDB DEFAULT CHARSET=latin1;";

		#print "$query4<p>";

		$mysqli_result4 = mysqli_query($mysqli_link, $query4) or die (mysqli_error($mysqli_link));
	
		for ($c = 1; $c <= 10; $c++)
		{
			require ("includes/calculate_combo2_by_sum.incl");
		}

		#require ("includes/print_combo_by_sum.incl");

		#print "<h3>Table <font color=\"#ff0000\">{$draw_prefix}_combo_by_sum</font> Updated!</h3>";
	}

	// ----------------------------------------------------------------------------------
	function print_limits_by_sumeo()
	{
		global $draw_table_name, $balls, $balls_drawn, $draw_prefix, $col1_select, $hml, $range_low, $range_high; 

		require ("includes/mysqli.php");
		
		require ("includes/calculate_limits_by_sumeo.incl");

		require ("includes/calculate_limits_by_sumeo_method2.incl");

		#print "<h3>Table <font color=\"#ff0000\">{$draw_prefix}_combo_by_sum</font> Updated!</h3>";
	}

	// ------------------------------------------------------------------------
	//start HTML page
	#log_text("$game_name Analyle Start");
	print("<HTML>\n");
	print("<HEAD>\n");
	print("<TITLE>$game_name Sumeo Prospects</TITLE>\n");
	print("</HEAD>\n");
	
	print("<BODY>\n");
	print("<H1>$game_name Sumeo Prospects</H1>\n");
	
	$curr_date = date("Ymd");
	$next_draw_Ymd = findNextDrawDate($game);

	flush();
	ob_flush();

	#print_sum_grid();

	#print_sum_grid_sum();

	#die();
	
	#print_sumeo_drange2();

	#print_table(31);

	#print_draw_summary();

	#print_limits_by_sumeo();

	#print_limits_by_sumeo(); #201005 fix

	#print_table(14);

	#print_table(7);

	print_sum_grid();

	print_sum_grid_sum();

	#print_limits_by_sumeo(); #201005 fix

	### prospect
	# Draw Count Table by Sumeo
	# Each Sumeo
	# Print limits and test??? Or without limits and test percent_wa
	# Drange 10-2 by Sumeo
	# Add to prospects, if not present
	# Update percent_wa tables...

	#print_sumeo_drange_summary();

	#test_combin_print_all_sumeo(100,$sumeo_sum,$sumeo_even,$sumeo_odd);

	#require ("includes/print_sum_table_sumeo.incl"); ### 220410

	$query_sms = "SELECT * FROM $draw_prefix";
	$query_sms .= "sum_count_sum "; 
	$query_sms .= "ORDER BY percent_wa DESC, percent_y4 DESC, percent_y1 DESC ";
	$query_sms .= "LIMIT 5 ";

	echo "$query_sms<br>";
	
	$mysqli_result_sms = mysqli_query($mysqli_link, $query_sms) or die (mysqli_error($mysqli_link));
	
	// get each row
	while($row_sms = mysqli_fetch_array($mysqli_result_sms))
	{
		$sum = $row_sms[1];
		$even = $row_sms[2];
		$odd = $row_sms[3];
		$sumeo_sum = $row_sms[1];
		$sumeo_even = $row_sms[2];
		$sumeo_odd = $row_sms[3];

		print "<h2>***** Sumeo $sum/$even/$odd *****</h2>";
		
		print_draw_summary_sumeo($sum,$even,$odd);
		
		#print_sumeo_drange_summary_sum_even_odd($sum,$even,$odd);

		require ("includes/calculate_sumeo_drange2_sumeo.incl");

		require ("includes/print_sumeo_table_drange2_sumeo.incl");

		require ("includes/calculate_sumeo_drange3_sumeo.incl");

		require ("includes/print_sumeo_table_drange3_sumeo.incl");

		require ("includes/calculate_sumeo_drange4_sumeo.incl");

		require ("includes/print_sumeo_table_drange4_sumeo.incl");

		require ("includes/calculate_sumeo_drange5_sumeo.incl");

		require ("includes/print_sumeo_table_drange5_sumeo.incl");

		require ("includes/calculate_sumeo_drange6_sumeo.incl");

		require ("includes/print_sumeo_table_drange6_sumeo.incl");

		require ("includes/calculate_sumeo_drange7_sumeo.incl");

		require ("includes/print_sumeo_table_drange7_sumeo.incl");
		/*
		require ("includes/calculate_sumeo_drange8_sumeo.incl");

		require ("includes/print_sumeo_table_drange8_sumeo.incl");

		require ("includes/calculate_sumeo_drange9_sumeo.incl");

		require ("includes/print_sumeo_table_drange9_sumeo.incl");

		require ("includes/calculate_sumeo_drange10_sumeo.incl");

		require ("includes/print_sumeo_table_drange10_sumeo.incl");

		die();
		
		require ("includes/calculate_sumeo_drange3.incl");
		
		require ("includes/print_sumeo_table_drange3_sumeo.incl");

		require ("includes/calculate_sumeo_drange4.incl");

		require ("includes/print_sumeo_table_drange4_sumeo.incl");

		require ("includes/calculate_sumeo_drange5.incl");

		require ("includes/print_sumeo_table_drange5_sumeo.incl");

		require ("includes/calculate_sumeo_drange6.incl");

		require ("includes/print_sumeo_table_drange6_sumeo.incl");

		require ("includes/calculate_sumeo_drange7.incl");

		require ("includes/print_sumeo_table_drange7_sumeo.incl");

		require ("includes/calculate_sumeo_drange8.incl");

		require ("includes/print_sumeo_table_drange8_sumeo.incl");
		
		for ($col = 1; $col <= 5; $col++)
		{
			print_column_test_sumeo($col, $sumeo_sum, $sumeo_even, $sumeo_odd); #200915
		}

		split_sumeo_5 ($sum,$even,$odd);

		split_sumeo_4 ($sum,$even,$odd);

		split_sumeo_3 ($sumeo_sum,$sumeo_even,$sumeo_odd);

		split_sumeo_2 ($sumeo_sum,$sumeo_even,$sumeo_odd);

		*/
	}
	/*
	for ($sd = 8; $sd > 2; $sd--)
	{
		$query = "SELECT * FROM $draw_prefix";
		$query .= "sumeo_drange"; 
		$query .= "$sd "; 
		$query .= "ORDER BY percent_wa DESC, percent_y5 DESC, percent_y1 DESC, year5 DESC, year3 DESC, year2 DESC, year1 DESC ";

		echo "$query<br>";

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

		$dcount = 0;

		// get each row
		while($row = mysqli_fetch_array($mysqli_result))
		{
			
			require ("includes/print_sumeo_table_drange2_sumeo.incl");

			require ("includes/print_sumeo_table_drange3_sumeo.incl");

			require ("includes/print_sumeo_table_drange4_sumeo.incl");

			require ("includes/print_sumeo_table_drange5_sumeo.incl");

			require ("includes/print_sumeo_table_drange6_sumeo.incl");

			require ("includes/print_sumeo_table_drange7_sumeo.incl");

			require ("includes/print_sumeo_table_drange8_sumeo.incl");

			die();
		}
	}
	*/
	die();

	print_sumeo_drange_summary();

	#die(); 

	//close page
	print("</BODY>\n");
	print("</HTML>\n");

?>
