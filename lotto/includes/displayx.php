<?php
	function lot_displayx ($limit, $combin)
	{ 
		global $debug, $game, $game_name, $balls, $balls_drawn, $mega_balls, $draw_table_name, $draw_prefix, $game_includes, $hml, $range_low, $range_high, $eo50_sum, $eo50_even, $eo50_odd, $eo50_d2_1, $eo50_d2_2, $combin;
	
		require ("includes/mysqli.php");
		#require ('includes/db.class.php');

		require ("$game_includes/config.incl");

		if (1)
		{
			print("<a name=\"$limit\"><H2>Lotto Display4/$combin - $game_name - Combin $combin - Limit $limit</H2></a>\n");

			require ("includes_ga_f5/display4nav.incl");

			// initalize variables [include]
			require ("includes/init_display.incl");
			
			//start table
			print("<TABLE BORDER=\"1\">\n");
		
			//create header row
			print("<TR><B>\n");
			print("<TD BGCOLOR=\"#CCCCCC\" width=20>&nbsp;</TD>\n");
			print("<TD BGCOLOR=\"#CCCCCC\"><center>Date</center></TD>\n");
			
			for($index = 1; $index <= $balls_drawn-$combin; $index++)
			{
				print("<TD BGCOLOR=\"#CCCCCC\">b$index</TD>\n");
			}
			
			if ($mega_balls)
			{
				print("<TD BGCOLOR=\"#CCCCCC\">PB</TD>\n");
			}

			print("<TD BGCOLOR=\"#CCCCCC\">Sum</TD>\n");
			print("<TD BGCOLOR=\"#CCCCCC\">Average</TD>\n");
			print("<TD BGCOLOR=\"#CCCCCC\" align=center>w1</TD>\n");
			print("<TD BGCOLOR=\"#CCCCCC\" align=center>w2</TD>\n");
			print("<TD BGCOLOR=\"#CCCCCC\" align=center>wa</TD>\n");
			print("</B></TR>\n");
		
			$dcount = 1;
			$num_array = array_fill (0,60,0);
			
			$num_array_count = array_fill (0,60,$num_array);
			$pb_array_count = array_fill (0,60,$num_array);
			$prev_date = array_fill (0,60,'1962-08-17');

			$draw_count_temp_array = array_fill (0,17,0);
			$draw_count_array = array_fill (0,80,$draw_count_temp_array);

			#require ("includes/pair_table.incl"); #pair	

			print "<a name=\"unsorted";
			print "$combin\"><h2> Combin = $combin </h2></a>";

			// get from draw table
			$query5 = "SELECT * FROM ga_f5_draws_";
			$query5 .= "$combin ";
			$query5 .= "WHERE combin = $combin ";
			$query5 .= "ORDER BY date DESC ";
			$query5 .= "LIMIT $limit "; 

			#echo "$query5<br>";
		
			$mysqli_result5a = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));
		
			// get each row
			while($row = mysqli_fetch_array($mysqli_result5a))
			{	
				$draw = array ();

				for ($x = 1; $x <= ($balls_drawn-$combin); $x++)
				{
					array_push($draw, $row[$x]);
				}

				### 1911 update needed

				#require ("includes/update_calculate_drange4.incl");
				#require ("includes/update_calculate_drange5.incl");
				#require ("includes/update_calculate_drange6.incl");
				#require ("includes/update_calculate_drange7.incl");
				#require ("includes/update_calculate_drange8.incl");
				#require ("includes/update_calculate_drange9.incl");
				#require ("includes/update_calculate_drange10.incl");

				#echo "--- row[even] = $row[even]<br> ---<br>";
				
				if ($row[even] == 0)
				{
					// build draw array [0..x]
					$draw = array ();
			
					for ($x = 3; $x <= (7-$combin); $x++)
					{
						array_push($draw, $row[$x]);
					}

					sort($draw);

					#print_r ($draw);
					#echo "<br>";

					require ("includes_ga_f5/update_even_oddx.incl");
					#require ("includes/update_calculate_25_33.incl"); #180723
					#require ("$game_includes/update_stats.incl");
				} 

				#echo "--- row[sum] = $row[sum]<br> ---<br>";

				if ($row[sum] == 0)
				{
					echo "### update sum###<br>";
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
						for ($x = 1; $x <= ($balls_drawn-$combin); $x++)
						{
							array_push($draw, $row[$x]);
						}
					}

					sort($draw);

					require ("includes/update_sum.incl");
				}
				
				if ($row[nums_total_2] == 0 && $game == 1)
				{
					### 1911 update needed
					#require ("includes/combo_count_date.incl");
					#require ("includes/update_combo_nums.incl");
				} 
			}
				
			// get from draw table
			$query5 = "SELECT * FROM ga_f5_draws_4 ";
			$query5 .= "WHERE combin = $combin ";
			$query5 .= "ORDER BY date DESC ";
			$query5 .= "LIMIT $limit "; 

			#echo "$query5<br>";

			#print("<P>lot_display4 - $query5<p>");
		
			$mysqli_result5 = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));

			$z = 1;
			$pb_array = array_fill (0,50,0);
			$pb_date = array_fill (0,50,'1962-08-17');

			#initialize date variables
			require ("includes/unix.incl");

			$count = 0;
		
			// get each row
			while($row = mysqli_fetch_array($mysqli_result5))
			{
				// build draw array [0..x]
				$draw = array ();
		
				for ($x = 3; $x <= 6; $x++)
				{
					array_push($draw, $row[$x]);
				}

				sort($draw);

				#print_r ($draw);
				#echo "<br>";

				$draw_sum  = $row[sum];
				$draw_even  = $row[even];
				$draw_odd  = $row[odd];

				$modulus10 = $dcount % 100;

				if ($limit <= 100 || $count == 0) 
				{
					print("<TR>\n");
			
					print("<TD align=center><b>$dcount</b></TD>\n");
					print("<TD>$row[0]</TD>\n");
			
					for($index = 3; $index <= 6; $index++)
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
						$sum_low = 50;
						$sum_high = 100;
					}
					
					if ($draw_sum < $sum_low || $draw_sum > $sum_high)
					{
						print("<TD bgcolor=\"#FF0000\" align=center><font color=\"#ffffff\"><b>$draw_sum</b></font></TD>\n");
					} else {
						print("<TD align=center>$draw_sum</TD>\n");
					}

					$average = (array_sum($draw)/4);

					print("<TD align=center>$average</TD>\n");

					$wa_sum_w1 = 0;
					$wa_sum_w2 = 0;
					$wa_sum_y1 = 0;
				} 

				##############################################################
				# calculate draw count
				##############################################################
				#echo "### calculate_draw_count4.incl ###<br>";
				require ("includes/calculate_draw_count4.incl"); #ball count
				
				$count++;
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
			for($index = 1; $index <= $balls_drawn-1; $index++)
			{
				print("<TD BGCOLOR=\"#CCCCCC\">b$index</TD>\n");
			}
			
			if ($mega_balls)
			{
				print("<TD BGCOLOR=\"#CCCCCC\">PB</TD>\n");
			}

			print("<TD BGCOLOR=\"#CCCCCC\">Sum</TD>\n");
			print("<TD BGCOLOR=\"#CCCCCC\">Average</TD>\n");
			print("<TD BGCOLOR=\"#CCCCCC\" align=center>w1</TD>\n");
			print("<TD BGCOLOR=\"#CCCCCC\" align=center>w2</TD>\n");
			print("<TD BGCOLOR=\"#CCCCCC\" align=center>wa</TD>\n");
			print("</B></TR>\n");
		
			//end table
			print("</TABLE>\n");

			##############################################################################
			# create pair table
			#
			#require ("includes/create_pair_table.incl"); #pair
			##############################################################################
			
			print "<h3>Table <font color=\"#ff0000\">$table_temp</font> Updated!</h3>";

			require ("includes_ga_f5/display4nav.incl");

			##############################################################################
			# create draw count table
			#
			require ("includes_ga_f5/draw_count_table_4.incl"); #fix 1911
			##############################################################################
			
			// process and print unsorted table
			
			if ($limit > 105)
			{
				require ("includes_ga_f5/unsorted_table_large.incl");
				#require ("includes/unsorted_table_1510.incl");
			} elseif ($limit == 100) {
				require ("includes_ga_f5/unsorted_table_100.incl");
			} else {
				require ("includes_ga_f5/unsorted_table.incl");
			}

			require ("includes_ga_f5/display4nav.incl");

			##############################################################
			# process and print sorted table
			##############################################################
			if ($limit > 105 )
			{
				require ("includes_ga_f5/sorted_table_large.incl");
			} elseif ($limit == 100) {
				require ("includes_ga_f5/sorted_table_100.incl");
			} else {
				require ("includes_ga_f5/sorted_table.incl");
			}

			// process and print megaball table
			#require ("includes/megaball_table.incl");
			
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

			#$table_temp = $draw_prefix . "temp_2_" . $limit;
			#$table_temp_pairs = $draw_prefix . "temp_pairs_" . $limit;

			require ("includes_ga_f5/display4nav.incl");

			#die();
		}
	
	}
?>