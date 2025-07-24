<?php
	function lot_display_sumeo ($sumeo_sum,$sumeo_even,$sumeo_odd)
	{ 
		global $debug, $game, $game_name, $balls, $balls_drawn, $mega_balls, $draw_table_name, $draw_prefix, $game_includes, $hml, $range_low, $range_high, $eo50_sum, $eo50_even, $eo50_odd, $eo50_d2_1, $eo50_d2_2;
	
		require ("includes/mysqli.php");
		#require ('includes/db.class.php');

		require ("$game_includes/config.incl");
		
		print("<H2>Lotto Display - $game_name - Sumeo $sumeo_sum/$sumeo_even/$sumeo_odd</H2>\n");

		#print "<h4><a href=\"#unsorted$limit\">Unsorted $limit</a> | <a href=\"#sorted$limit\">Sorted $limit</a> | <a href=\"#pairs$limit\">Pairs $limit</a> | <a href=\"#rank$limit\">Rank $limit</a> | <a href=\"#grid$limit\">Grid $limit</a></h4>";

		// initalize variables [include]
		require ("includes/init_display.incl");
		#echo "check 1<br>"; #201108
		//start table
		print("<TABLE BORDER=\"1\">\n");
	
		//create header row
		print("<TR><B>\n");
		print("<TD BGCOLOR=\"#CCCCCC\" width=20>&nbsp;</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Date</center></TD>\n");
		
		for($index = 1; $index <= $balls_drawn; $index++)
		{
			print("<TD BGCOLOR=\"#CCCCCC\">b$index</TD>\n");
		}

		print("<TD BGCOLOR=\"#CCCCCC\">Sum</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Even</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Odd</TD>\n");
		
		print("<TD BGCOLOR=\"#CCCCCC\">Average</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Median</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Harmean</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Geomean</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Quart1</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Quart2</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Quart3</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Stdev</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Var</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">AveDev</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Kurt</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Skew</TD>\n");
		print("</B></TR>\n");
	
		$dcount = 1;
		$num_array = array_fill (0,2000,0);
		$row5 = array_fill (0,60,0);
		$row5a = array_fill (0,60,0);
		
		$num_array_count = array_fill (0,2000,$num_array);
		$pb_array_count = array_fill (0,60,$num_array);
		$prev_date = array_fill (0,60,'1962-08-17');

		$draw_count_temp_array = array_fill (0,17,0);
		$draw_count_array = array_fill (0,80,$draw_count_temp_array);

		#require ("includes/pair_table.incl"); #pair	

		// get from draw table
		$query5a = "SELECT * FROM $draw_table_name ";
		$query5a .= "WHERE sum = $sumeo_sum ";
		$query5a .= "AND even = $sumeo_even ";
		$query5a .= "AND odd = $sumeo_odd ";
		$query5a .= "ORDER BY date DESC ";
	
		echo "$query5a<br>"; #220402
	
		$mysqli_result5a = mysqli_query($mysqli_link, $query5a) or die (mysqli_error($mysqli_link));

		$num_rows_all = mysqli_num_rows($mysqli_result5a); 

		echo "num_rows_all 5a = $num_rows_all<br>"; #201108
	
		// get each row
		while($row5a = mysqli_fetch_array($mysqli_result5a))
		{	
			$draw = array ();

			for ($x = 1; $x <= $balls_drawn; $x++)
			{
				array_push($draw, $row5a[$x]);
			}

			#print_r($draw); #201108
			#echo "<br>";
			
			if ($game == 0) 
			{
				#echo "enter<br>"; #201108
				require ("includes/update_calculate_drange2.incl");
				require ("includes/update_calculate_drange3.incl");
				require ("includes/update_calculate_drange4.incl");
				require ("includes/update_calculate_drange5.incl");
				require ("includes/update_calculate_drange6.incl");
				require ("includes/update_calculate_drange7.incl");
				require ("includes/update_calculate_drange8.incl");
				#require ("includes/update_calculate_drange9.incl");
				#require ("includes/update_calculate_drange10.incl");
				#echo "exit<br>"; #201108
			}
			
			if ($row5a[nums_total_2] == 0 && $game == 1)
			{
				#require ("includes/combo_count_date.incl");
				#require ("includes/update_combo_nums.incl");
			} 
		}
			
		// get from draw table
		$query5 = "SELECT * FROM $draw_table_name ";
		$query5 .= "WHERE date >= '2015-10-01' ";
		$query5 .= "ORDER BY date DESC ";
		$query5 .= "LIMIT $limit "; 

		#echo "$query5<br>"; #200821

		#print("<P>display - $query5<p>");
	
		$mysqli_result5 = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));

		$num_rows_all = mysqli_num_rows($mysqli_result5); 

		#echo "num_rows_all 5 = $num_rows_all<br>"; #201108

		$z = 1;

		#initialize date variables
		require ("includes/unix.incl");

		#echo "start 5<br>";
	
		// get each row
		while($row5 = mysqli_fetch_array($mysqli_result5))
		{
			#echo "start 6<br>";

			// build draw array [0..x]
			$draw = array ();
	
			for ($x = 1; $x <= $balls_drawn; $x++)
			{
				array_push($draw, $row5[$x]);
			}

			sort($draw);

			#print_r($draw);
			#echo "<br>";

			$draw_sum  = $row5[6];
			$draw_even = $row5[7];
			$draw_odd  = $row5[8];

			$modulus10 = $dcount % 100;

			if ($modulus10 == 1 || $limit < 100) 
			{
				print("<TR>\n");
		
				print("<TD align=center><b>$dcount</b></TD>\n");
				print("<TD>$row5[0]</TD>\n");
		
				if ($game == 10 OR $game == 20)
				{
					print("<TD align=center>$row5[day_draw]</TD>\n");
					for($index = 3; $index <= 14; $index++)
					{
						print("<TD align=center>$row5[$index]</TD>\n");
					}
				} else {
					for($index = 1; $index <= $balls_drawn; $index++)
					{
						print("<TD align=center>$row5[$index]</TD>\n");
					}
				}
				
				$sum_low = 80;
				$sum_high = 119;
				
				if ($draw_sum < $sum_low || $draw_sum > $sum_high)
				{
					print("<TD bgcolor=\"#FF0000\" align=center><font color=\"#ffffff\"><b>$draw_sum</b></font></TD>\n");
				} else {
					print("<TD align=center>$draw_sum</TD>\n");
				}

				if ($draw_even < 1 || $draw_odd < 1)
				{
					print("<TD bgcolor=\"#FF0000\" align=center><font color=\"#ffffff\"><b>$draw_even</b></font></TD>\n");
					print("<TD bgcolor=\"#FF0000\" align=center><font color=\"#ffffff\"><b>$draw_odd</b></font></TD>\n");
				} else {
					print("<TD align=center>$draw_even</TD>\n");
					print("<TD align=center>$draw_odd</TD>\n");
				}

				$average = (array_sum($draw)/5);
									
				print("<TD align=center>$average</TD>\n");

				### print median (the middle number)
				print("<TD align=center>$draw[2]</TD>\n");

				$wa_sum_w1 = 0;
				$wa_sum_w2 = 0;
				$wa_sum_y1 = 0;

				#echo "row[devsq] = $row5[devsq]<br>";

				if ($row5[24] == 0.00 OR $row5[35] == 0.00)
				{
					$hml_sum = intval($row5[6]/10)*10;

					for ($col = 1; $col <= 12; $col++)
					{
						#print_column_test($col,$row5,$hml_sum);

						$temp = $col + 2;

						$query_col = "SELECT * FROM $draw_prefix";
						$query_col .= "column_$col";						
						$query_col .= "_$hml_sum";
						$query_col .= "_temp ";
						$query_col .= "WHERE num = $row5[$temp] ";

						#echo "$query_col<br>";
					
						#$mysqli_result_col = mysqli_query($query_col, $mysqli_link) or die (mysqli_error($mysqli_link));

						#$row5_col = mysqli_fetch_array($mysqli_result_col);

						#$wa_sum_w1 += $row5_col[percent_w1];
						#$wa_sum_w2 += $row5_col[percent_w2];
						#$wa_sum_y1 += $row5_col[percent_y1];

						#echo "row_col[percent_y1] = $row5_col[percent_y1],wa_sum_y1 = $wa_sum_y1<br>"; 
					}

					#$query7 = "UPDATE $draw_table_name ";
					#$query7 .= "SET  median = '$wa_sum_w2', ";
					#$query7 .= "     variance = '$wa_sum_w1', ";
					#$query7 .= "     devsq = '$wa_sum_y1' ";
					#$query7 .= "WHERE id = $row5[id] ";

					#echo "$query7<br>";

					#$mysqli_result7 = mysqli_query($mysqli_link, $query7) or die (mysqli_error($mysqli_link));
				} else {
					$wa_sum = $row5[35];
				}

				print("<TD align=center>$row5[25]</TD>\n");
				print("<TD align=center>$row5[26]</TD>\n");
				print("<TD align=center>$row5[27]</TD>\n");
				print("<TD align=center>$row5[28]</TD>\n");
				print("<TD align=center>$row5[29]</TD>\n");
				print("<TD align=center>$row5[30]</TD>\n");
				print("<TD align=center>$row5[31]</TD>\n");
				print("<TD align=center>$row5[32]</TD>\n");
				print("<TD align=center>$row5[33]</TD>\n");
				print("</TR>\n");
			}

			##############################################################
			# calculate draw count
			##############################################################
			#echo "calculate_draw_count.incl<br>";
			#require ("includes/calculate_draw_count.incl"); #ball count
			
			$dcount++;
			$z++;
		}

		#echo "draw_count_array<br>"; #201108
		#print_r ($draw_count_array);
		#echo "<br>";

		//create footer row
		print("<TR><B>\n");
		print("<TD BGCOLOR=\"#CCCCCC\" width=20>&nbsp;</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Date</center></TD>\n");

		for($index = 1; $index <= $balls_drawn; $index++)
		{
			print("<TD BGCOLOR=\"#CCCCCC\">b$index</TD>\n");
		}
		
		if ($mega_balls)
		{
			print("<TD BGCOLOR=\"#CCCCCC\">PB</TD>\n");
		}

		print("<TD BGCOLOR=\"#CCCCCC\">Sum</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Even</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Odd</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Average</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Median</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Harmean</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Geomean</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Quart1</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Quart2</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Quart3</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Stdev</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Var</TD>\n");
		#print("<TD BGCOLOR=\"#CCCCCC\" align=center>W1</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">AveDev</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Kurt</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Skew</TD>\n");
		print("</B></TR>\n");
	
		//end table
		print("</TABLE>\n");
		
		#print "<h3>Table <font color=\"#ff0000\">$table_temp</font> Updated!</h3>";

		#print "<h4><a href=\"#unsorted$limit\">Unsorted $limit</a> | <a href=\"#sorted$limit\">Sorted $limit</a> | <a href=\"#pairs$limit\">Pairs $limit</a> |  <a href=\"#rank$limit\">Rank $limit</a> | <a href=\"#grid$limit\">Grid $limit</a><a name=\"unsorted$limit\">&nbsp;</a></h4>";

		##############################################################################
		# create draw count table
		#
		#require ("includes/draw_count_table.incl");
		##############################################################################

		// process and print unsorted table
		echo "unsorted_table.incl<br>";
		require ("includes/unsorted_table.incl");

		#print "<h4><a href=\"#unsorted$limit\">Unsorted $limit</a> | <a href=\"#sorted$limit\">Sorted $limit</a> | <a href=\"#pairs$limit\">Pairs $limit</a> | <a href=\"#rank$limit\">Rank $limit</a> | <a href=\"#grid$limit\">Grid $limit</a><a name=\"sorted$limit\">&nbsp;</a></h4>";

		##############################################################
		# process and print sorted table
		##############################################################
		echo "sorted_table.incl<br>";
		require ("includes/sorted_table.incl");

		// process and print megaball table
		#require ("includes/megaball_table.incl"); #200112
	
		///////////////////////////////////////////////////////////////////////////////
	
		//$table_temp = $draw_prefix . "temp_2_" . $limit;
	
		/* fix me!
		for($index=1; $index <= ($balls*$balls); $index++)
		{
			if ($index % $balls == 0) {
				$num1 = intval($index/$balls);
			} else {
				$num1 = intval($index/$balls)+1;
			}
			$num2 = $index-(($num1-1)*$balls); 
			$count = $pick_array[$index][0];
			$date = $pick_array[$index][1];
			
		

		$table_temp = $draw_prefix . "temp_2_" . $limit;
		$table_temp_pairs = $draw_prefix . "temp_pairs_" . $limit;

		print "<h4><a href=\"#unsorted$limit\">Unsorted $limit</a> | <a href=\"#sorted$limit\">Sorted $limit</a> | <a href=\"#pairs$limit\">Pairs $limit</a> | <a href=\"#rank$limit\">Rank $limit</a> | <a href=\"#grid$limit\">Grid $limit</a><a name=\"pairs$limit\">&nbsp;</a></h4>";

		*/
	
	}
?>