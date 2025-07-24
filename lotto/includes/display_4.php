<?php
	function lot_display_4 ($limit)
	{ 
		global $debug, $game, $game_name, $balls, $balls_drawn, $mega_balls, $draw_table_name, $draw_prefix, $game_includes, $hml, $range_low, $range_high, $eo50_sum, $eo50_even, $eo50_odd, $eo50_d2_1, $eo50_d2_2;
	
		require ("includes/mysqli.php");
		
		print("<a name=\"$limit\"><H2>Lotto Display Comb4 - $game_name - Limit $limit</H2></a>\n");

		print "<h4><a href=\"#unsorted$limit\">Unsorted $limit</a> | <a href=\"#sorted$limit\">Sorted $limit</a> | <a href=\"#pairs$limit\">Pairs $limit</a> | <a href=\"#rank$limit\">Rank $limit</a> | <a href=\"#grid$limit\">Grid $limit</a></h4>";

		// initalize variables [include]
		require ("includes/init_display.incl");
		
		//start table
		print("<TABLE BORDER=\"1\">\n");
	
		//create header row
		print("<TR><B>\n");
		print("<TD BGCOLOR=\"#CCCCCC\" width=20>&nbsp;</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Date</center></TD>\n");
		
		for($index = 1; $index <= ($balls_drawn-1); $index++)
		{
			print("<TD BGCOLOR=\"#CCCCCC\">b$index</TD>\n");
		}
		
		if ($mega_balls)
		{
			print("<TD BGCOLOR=\"#CCCCCC\">PB</TD>\n");
		}

		print("<TD BGCOLOR=\"#CCCCCC\">Sum</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Average</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\" align=center>W2</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Harmean</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Geomean</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Quart1</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Quart2</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Quart3</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Stdev</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">W1</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">AveDev</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Kurt</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Skew</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\" align=center>Y1</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\" align=center>WA</TD>\n");
		print("</B></TR>\n");
	
		$dcount = 1;
		$num_array = array_fill (0,60,0);
		
		$num_array_count = array_fill (0,60,$num_array);
		$pb_array_count = array_fill (0,60,$num_array);
		$prev_date = array_fill (0,60,'1962-08-17');

		$draw_count_temp_array = array_fill (0,17,0);
		$draw_count_array = array_fill (0,80,$draw_count_temp_array);

		#require ("includes/pair_table.incl"); #pair	

		$limit_temp = $limit * 5;

		/*
		// get from draw table
		$query5 = "SELECT * FROM $draw_prefix";
		$query5 .= "4_$balls";
		#$query5 .= "WHERE sum = 0 ";
		$query5 .= "ORDER BY date DESC ";
		$query5 .= "LIMIT $limit_temp "; 

		#echo "$query5<br>";
	
		$mysqli_result5a = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));
	
		// get each row
		while($row = mysqli_fetch_array($mysqli_result5a))
		{	
			$draw = array ();

			for ($x = 1; $x <= ($balls_drawn-1); $x++)
			{
				array_push($draw, $row[$x]);
			}

			#echo "balls = $balls<br>";

			#require ("includes/update_calculate_drange4.incl");
			#require ("includes/update_calculate_drange5.incl");
			#require ("includes/update_calculate_drange6.incl");
			#require ("includes/update_calculate_drange7.incl");
			#require ("includes/update_calculate_drange8.incl");
			#require ("includes/update_calculate_drange9.incl");
			#require ("includes/update_calculate_drange10.incl");

			
			if ($row[sum] == 0)
			{
				$draw_sum  = $row[sum];
				$draw_even  = $row[even];
				$draw_odd  = $row[odd];

				// build draw array [0..x]
				$draw = array ();
		
				if ($game == 10 OR $game == 20)
				{
					for ($x = 3; $x <= 14; $x++)
					{
						array_push($draw, $row[$x]);
					}
				} else {
					for ($x = 1; $x <= $balls_drawn; $x++)
					{
						array_push($draw, $row[$x]);
					}
				}

				sort($draw);

				require ("includes/update_sum.incl");

				if ($row[even] == 0)
				{
					require ("includes/update_even_odd.incl");
					#require ("includes/update_calculate_25_33.incl"); #180723
					#require ("$game_includes/update_stats.incl");
				} 
			}
			
			if ($row[nums_total_2] == 0 && $game == 1)
			{
				require ("includes/combo_count_date.incl");
				require ("includes/update_combo_nums.incl");
			} 
		}
		*/

		// get from draw table
		$query5 = "SELECT * FROM $draw_prefix";
		$query5 .= "4_$balls ";
		if ($hml)
		{
			$query5 .= "WHERE sum >= $range_low  ";
			$query5 .= "AND   sum <= $range_high  ";
		}
		#$query5 .= "WHERE sum = 0 ";
		$query5 .= "ORDER BY date DESC ";
		$query5 .= "LIMIT $limit_temp "; 

		#echo "$query5<br>";

		print("<P>lot_display - $query5<p>");
	
		$mysqli_result5 = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));

		$z = 1;
		$pb_array = array_fill (0,50,0);
		$pb_date = array_fill (0,50,'1962-08-17');

		#initialize date variables
		require ("includes/unix.incl");
	
		// get each row
		while($row = mysqli_fetch_array($mysqli_result5))
		{
			// build draw array [0..x]
			$draw = array ();
	
			for ($x = 1; $x <= ($balls_drawn-1_; $x++)
			{
				array_push($draw, $row[$x]);
			}

			sort($draw);

			#$draw_sum  = $row[sum];
			#$draw_even  = $row[even];
			#$draw_odd  = $row[odd];
	
			if ($mega_balls)
			{
				$pb = $row[pb];
			}

			$modulus10 = $dcount % 100;

			if ($modulus10 == 1 || $limit < 100) 
			{
				print("<TR>\n");
		
				print("<TD align=center><b>$dcount</b></TD>\n");
				print("<TD>$row[0]</TD>\n");
				
				for($index = 1; $index <= $balls_drawn; $index++)
				{
					print("<TD align=center>$row[$index]</TD>\n");
				}
				
				if ($mega_balls)
				{
					print("<TD align=center><b>$row[pb]</b></TD>\n");
				}
				
				if ($num_rows_sum)
				{
					$sum_low = $row_sum[low];
					$sum_high = $row_sum[high];
				} else {
					$sum_low = 80;
					$sum_high = 120;
				}
				
				if ($draw_sum < $sum_low || $draw_sum > $sum_high)
				{
					print("<TD bgcolor=\"#FF0000\" align=center><font color=\"#ffffff\"><b>$draw_sum</b></font></TD>\n");
				} else {
					print("<TD align=center>$draw_sum</TD>\n");
				}

				$wa_sum_w1 = 0;
				$wa_sum_w2 = 0;
				$wa_sum_y1 = 0;

				#echo "row[devsq] = $row[devsq]<br>";

				print("<TD align=center>$row[draw_average]</TD>\n");
				print("<TD align=center>$row[median]</TD>\n");
				print("<TD align=center>$row[harmean]</TD>\n");
				print("<TD align=center>$row[geomean]</TD>\n");
				print("<TD align=center>$row[quart1]</TD>\n");
				print("<TD align=center>$row[quart2]</TD>\n");
				print("<TD align=center>$row[quart3]</TD>\n");
				print("<TD align=center>$row[stdev]</TD>\n");
				print("<TD align=center>$row[variance]</TD>\n");
				print("<TD align=center>$row[avedev]</TD>\n");
				print("<TD align=center>$row[kurt]</TD>\n");
				print("<TD align=center>$row[skew]</TD>\n");
				print("<TD align=center>$row[devsq]</TD>\n");

				print("<TD align=center>$wa_sum</TD>\n");
			
				print("</TR>\n");
			}

			##############################################################
			# calculate draw count
			##############################################################
			require ("includes/calculate_draw_count_4.incl"); #ball count
			
			$dcount++;
			$z++;
		}

		//create footer row
		print("<TR><B>\n");
		print("<TD BGCOLOR=\"#CCCCCC\" width=20>&nbsp;</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Date</center></TD>\n");
		if ($game == 10 OR $game == 20)
		{
			print("<TD BGCOLOR=\"#CCCCCC\">game</TD>\n");
		}
		for($index = 1; $index <= $balls_drawn; $index++)
		{
			print("<TD BGCOLOR=\"#CCCCCC\">b$index</TD>\n");
		}
		
		if ($mega_balls)
		{
			print("<TD BGCOLOR=\"#CCCCCC\">PB</TD>\n");
		}

		print("<TD BGCOLOR=\"#CCCCCC\">Sum</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Average</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\" align=center>W2</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Harmean</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Geomean</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Quart1</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Quart2</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Quart3</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Stdev</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\" align=center>W1</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">AveDev</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Kurt</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Skew</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\" align=center>Y1</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">WA</TD>\n");
		print("</B></TR>\n");
	
		//end table
		print("</TABLE>\n");

		##############################################################################
		# create pair table
		#
		#require ("includes/create_pair_table.incl"); #pair
		##############################################################################
		
		print "<h3>Table <font color=\"#ff0000\">$table_temp</font> Updated!</h3>";

		print "<h4><a href=\"#unsorted$limit\">Unsorted $limit</a> | <a href=\"#sorted$limit\">Sorted $limit</a> | <a href=\"#pairs$limit\">Pairs $limit</a> |  <a href=\"#rank$limit\">Rank $limit</a> | <a href=\"#grid$limit\">Grid $limit</a><a name=\"unsorted$limit\">&nbsp;</a></h4>";

		##############################################################################
		# create draw count table
		#
		require ("includes/draw_count_table_4.incl");
		##############################################################################

		// process and print unsorted table
		if ($limit > 105)
		{
			require ("includes/unsorted_table_large_4.incl");
			#require ("includes/unsorted_table_1510.incl");
		} elseif ($limit == 100) {
			require ("includes/unsorted_table_100_4.incl");
		} else {
			require ("includes/unsorted_table_4.incl");
		}

		print "<h4><a href=\"#unsorted$limit\">Unsorted $limit</a> | <a href=\"#sorted$limit\">Sorted $limit</a> | <a href=\"#pairs$limit\">Pairs $limit</a> | <a href=\"#rank$limit\">Rank $limit</a> | <a href=\"#grid$limit\">Grid $limit</a><a name=\"sorted$limit\">&nbsp;</a></h4>";

		##############################################################
		# process and print sorted table
		##############################################################
		if ($limit > 105 )
		{
			require ("includes/sorted_table_large_4.incl");
		} elseif ($limit == 100) {
			require ("includes/sorted_table_100_4.incl");
		} else {
			require ("includes/sorted_table_4.incl");
		}

		// process and print megaball table
		require ("includes/megaball_table.incl");
	
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
			
		*/

		$table_temp = $draw_prefix . "temp_2_4_" . $limit;
		$table_temp_pairs = $draw_prefix . "temp_pairs_4_" . $limit;

		print "<h4><a href=\"#unsorted$limit\">Unsorted $limit</a> | <a href=\"#sorted$limit\">Sorted $limit</a> | <a href=\"#pairs$limit\">Pairs $limit</a> | <a href=\"#rank$limit\">Rank $limit</a> | <a href=\"#grid$limit\">Grid $limit</a><a name=\"pairs$limit\">&nbsp;</a></h4>";
	
	}
?>