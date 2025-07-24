<?php
	// ----------------------------------------------------------------------------------
	function print_table4($combin,$limit)
	{
		global $draw_table_name, $balls, $balls_drawn, $game_includes, $draw_prefix, $game_includes, $hml, $range_low, $range_high, $col1_select; 

		require ("includes/mysqli.php"); 

		require ("$game_includes/config.incl");

		$query = "SELECT * FROM ga_f5_4_42 ";
		$query .= "WHERE combo = $combin ";
		$query .= "AND hml = '0' ";
		$query .= "ORDER BY date DESC ";
		$query .= "LIMIT $limit ";

		#print("$query<p>");

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

		//start table
		print("<h3>Combin $combin - Last $limit draws</h3>\n");
		print("<TABLE BORDER=\"1\">\n");

		//create header row
		print("<TR><B>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Date</center></TD>\n");

		for ($x = 1; $x <= 4; $x++)
		{
			print("<TD BGCOLOR=\"#CCCCCC\"><center>&nbsp;draw$x&nbsp;</center></TD>\n");
		}

		print("<TD BGCOLOR=\"#CCCCCC\"><center>Sum</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><CENTER>Even<br>/Odd</CENTER></TD>\n");

		for ($x = 0; $x <= intval($balls/10); $x++)
		{
			print("<TD BGCOLOR=\"#CCCCCC\"><CENTER>$x.x</CENTER></TD>\n");
		}

		$half = intval($balls/2);

		print("<TD BGCOLOR=\"#CCCCCC\"><CENTER>Range<br>$half/$balls</CENTER></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><CENTER>eo2</CENTER></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><CENTER>Mid</CENTER></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><CENTER>r0</CENTER></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><CENTER>r1</CENTER></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><CENTER>r2</CENTER></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><CENTER>r3</CENTER></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><CENTER>r4</CENTER></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><CENTER>r5</CENTER></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><CENTER>r6</CENTER></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><CENTER>r7</CENTER></TD>\n");

		#print("<TD BGCOLOR=\"#CCCCCC\"><font size=\"0\">nums2</font></TD>\n");
		#print("<TD BGCOLOR=\"#CCCCCC\"><font size=\"0\">combo2</font></TD>\n");
		#print("<TD BGCOLOR=\"#CCCCCC\"><font size=\"0\">nums3</font></TD>\n");
		#print("<TD BGCOLOR=\"#CCCCCC\"><font size=\"0\">combo3</font></TD>\n");
		#print("<TD BGCOLOR=\"#CCCCCC\"><font size=\"0\">nums4</font></TD>\n");
		#rint("<TD BGCOLOR=\"#CCCCCC\"><font size=\"0\">combo4</font></TD>\n");

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
			require ("includes/d0.incl");

			$num_range = array_fill (0,8,0);
			$num_range_sum = array_fill (0,8,0);

			$num_range_d2_1 = 0;
			$num_range_d2_2 = 0;
			
			// build draw array [0..x]
			$draw_array = array ();
	
			for ($x = 2; $x <= 5; $x++)
			{
				array_push($draw_array, $row[$x]);
			}

			#print_r ($draw_array);
			#echo "</br>";

			$mod = array_fill (0, 10, 0);

			$mod2_count = 0;
			$mod3_count = 0;

			$sum = array_sum($draw_array);
			$sum_sum += $sum;

			// build ranges, $skip_pointer logic
			for($index = 3; $index <= 6; $index++)
			{	
				$number = $row[$index];

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

			if ($row['even'] == 0 && $row['odd'] == 0)
			{
				require ("includes/update_even_odd4.incl");

				$row[9] = $even;

				$row[10] = $odd;
			}
			
			#echo "even = $even</br>";
			#echo "odd = $odd</br>";

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

			#echo "### build rank table ###<br>";	

			$rank_count = array_fill (0,8,0);

			$rank_count = BuildRankTable($row[1]); ## 1911

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

			#######################################################################################

			print("<TR>\n");

			print("<TD nowrap><font size=\"-1\">$row[1]</font></TD>\n");

			for ($y = 2; $y <= 5; $y++)
			{
				print("<TD><center>$row[$y]<br>\n");

				$rank_ball = $rank_count[$row[$y]];
				
				print("<font size=\"-1\" color=\"#ff0000\"><b>$rank_ball</b></font></center></TD>\n");
			}

			if ($row[6] < $sum_low || $row[6] > $sum_high)
			{
				print("<TD align=center><font color=\"#ff0000\"><b>$row[6]</b></font></TD>\n");
			} else {
				print("<TD align=center>$row[6]</TD>\n");
			}

			if ($even <= 0 || $even >= 4) {
				print("<TD BGCOLOR=\"#99CCFF\"><strong><CENTER>$row[7]/$row[8]</CENTER></strong></TD>\n");
			} else {
				print("<TD BGCOLOR=\"#99CCFF\"><CENTER>$row[7]/$row[8]</CENTER></TD>\n");
			}

			for ($x = 0; $x <= intval($balls/10); $x++)
			{
				if ($num_range[$x] > 2) {
					print("<TD><CENTER><font color=\"#ff0000\"><B>$num_range[$x]</B></font></CENTER></TD>\n");
				} else {
					print("<TD><CENTER>$num_range[$x]</CENTER></TD>\n");
				}
			}

			if ($num_range_d2_1 == 0 || $num_range_d2_1 >= 4) {
				print("<TD BGCOLOR=\"#99CCFF\"><CENTER><B>$num_range_d2_1/$num_range_d2_2</B></CENTER></TD>\n");
			} else {
				print("<TD BGCOLOR=\"#99CCFF\"><CENTER>$num_range_d2_1/$num_range_d2_2</CENTER></TD>\n");
			}

			$query3 = "SELECT * FROM ga_f5_4_42 ";
			$query3 .= "WHERE sum = $row[6] ";
			$query3 .= "AND   even = $row[7] ";
			$query3 .= "AND   odd = $row[8] ";
			$query3 .= "AND   combo = $combin ";
			$query3 .= "AND date < $row[1] ";

			#echo "$query3<br>";
		
			$mysqli_result3 = mysqli_query($mysqli_link, $query3) or die (mysqli_error($mysqli_link));

			$num_rows_all = mysqli_num_rows($mysqli_result3); 

			$row3 = mysqli_fetch_array($mysqli_result3);

			if ($num_rows_all > 2) {
				print("<TD><CENTER><font color=\"#ff0000\"><B>$num_rows_all</B></font></CENTER></TD>\n");
			} else {
				print("<TD><CENTER>$num_rows_all</CENTER></TD>\n");
			}

			$mid_point = ($row3[1] + $row3[4])/2;
			$mid_count = 0;

			for ($x = 3; $x <= 6; $x++)
			{
				if ($row3[$x] <= $mid_point) 
				{
					$mid_count++;
				}
			}

			if ($mid_count == $num_range_d2_1) {
				print("<TD><CENTER>$mid_count<br><font size=\"-1\">($mid_point)</font></CENTER></TD>\n");
			} else {
				print("<TD><CENTER><b>$mid_count<br><font size=\"-1\">($mid_point)</font></b></CENTER></TD>\n");
			}

			$draw_rank_count = array_fill (0, 7, 0); #180907

			for($y = 1; $y <= 4; $y++)
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
			
			if ($draw_rank_count[7] > 1) {
				print("<TD><CENTER><font color=\"#ff0000\"><B>$draw_rank_count[7]/$rank_table_count[7]</B></font></CENTER></TD>\n");
			} else {
				print("<TD><CENTER>$draw_rank_count[7]/$rank_table_count[7]</CENTER></TD>\n");
			}

			print("</TR>\n"); #201107

			$average_count++; #201107
	
			############################################################################################
			############################################################################################

			#if ($game == 1)
			if (0)
			{
				$query_nc = "SELECT * FROM ga_f5_nums_combos_table ";
				$query_nc .= "WHERE date = '$row[1]' ";

				#echo "$query_nc<br>";
			
				#$mysqli_result_nc = mysqli_query($query_nc, $mysqli_link) or die (mysqli_error($mysqli_link));

				#$num_rows_all_nc = mysqli_num_rows($mysqli_result_nc); 

				#$row_nc = mysqli_fetch_array($mysqli_result_nc);

				/*
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
					$query_nc .= "VALUES ('$row[1]', ";
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

			#print("</TR>\n");

			#$average_count++;
			}
		}
		
		/*
		for($x = 1; $x < 4; $x++) #190310
		{
			for($y = $x+1; $y <= 4; $y++)
			{
				if ($col_sum[$x][$y])
				{
					print("<TD><center><font color=\"#ff0000\">{$col_sum[$x][$y]}</font></center></TD>\n");
				} else {
					print("<TD><center>{$col_sum[$x][$y]}</center></TD>\n");
				}
			}
		}
		
		print("</TR>\n");

		print("<TR>\n");
		print("<TD>&nbsp;</TD>\n");

		for ($x = 1; $x <= 4; $x++)
		{
			print("<TD>&nbsp;</TD>\n");
		}
		
		$num_temp = number_format($sum_sum/$average_count,2);
		print("<TD>$num_temp</TD>\n");
		$number_count = $average_count*4;
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
		
		$num_temp = number_format($num_range_sum[0]/$number_count,2)*100;
		print("<TD><CENTER>$num_temp%</CENTER></TD>\n");
		$num_temp = number_format($num_range_sum[1]/$number_count,2)*100;
		print("<TD><CENTER>$num_temp%</CENTER></TD>\n");
		$num_temp = number_format($num_range_sum[2]/$number_count,2)*100;
		print("<TD><CENTER>$num_temp%</CENTER></TD>\n");
		$num_temp = number_format($num_range_sum[3]/$number_count,2)*100;
		print("<TD><CENTER>$num_temp%</CENTER></TD>\n");
		
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
		print("<TD>&nbsp;</TD>\n");
		print("<TD>&nbsp;</TD>\n");
		print("<TD>&nbsp;</TD>\n");
		print("<TD>&nbsp;</TD>\n");
		print("<TD>&nbsp;</TD>\n");
		#print("</TABLE>\n");
		*/
		}

		print("</TABLE>\n");
	}
?>