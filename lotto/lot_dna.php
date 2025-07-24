<?php
	// Game ----------------------------- Game
	$game = 1; // Georgia Fantasy 5
	#$game = 2; // Mega Millions
	//$game = 3; // Georgia Lotto South
	#$game = 4; // Florida Fantasy 5
	//$game = 5; // Florida Mega Money
	#$game = 6; // Florida Lotto
	#$game = 7; // Powerball
	// Game ----------------------------- Game
	
	require ("includes/games_switch.incl");

	require ("includes/mysqli.php");
	require ("includes/table_exist.php");
	require ("includes/last_draws.php");
	require ("includes/last_draw_unix.php");
	require ("includes/test_draw_table.php");
	require ("includes/test_filter_a_table.php");
	require ("includes/test_filter_b_table.php");
	require ("includes/test_filter_c_table.php");
	require ("includes/test_wheel_table.php");
	require ("includes/calculate_draw.php");
	require ("includes/table_draw_count.php");
	require ("includes/x10.php");
	require ("includes/count_2_seq.php");
	require ("includes/count_3_seq.php");
	require ("includes/count_mod.php");
	require ("includes/draw_count_total.php");
	require ("includes/calculate_draw_pair_count.incl");
	require ("includes/first_draw_unix.php");
	require ("includes/limit_functions.incl");
	
	$debug = 0;

	date_default_timezone_set('America/New_York');

	#$hml = 0;
	#$hml = 1;		#= high
	#$hml = 2;		#= medium
	#$hml = 3;		#= low
	#$hml = 4;		#= min
	#$hml = 5;		#= max
	#$hml = 130;		

	#require ("includes/hml_switch.incl");	

	// ----------------------------------------------------------------------------------
	function lot_sum ($limit)
	{ 
		global $debug, $game, $game_name, $balls, $balls_drawn, $mega_balls, $draw_table_name, $draw_prefix, $game_includes, $hml, $range_low, $range_high;
	
		require ("includes/mysqli.php");

		require ("$game_includes/config.incl");

		require ("includes/hml_switch.incl");

		$sum_a_count = 0;
		$sum_d_count = 0;
		$sum_s_count = 0;
		$sum_s_fixed = 0;
		
		print("<a name=\"$limit\"><H2>Sum - $game_name - Limit = $limit</H2></a>\n");

		print "<h4><a href=\"#unsorted$limit\">Unsorted $limit</a> | <a href=\"#sorted$limit\">Sorted $limit</a> | <a href=\"#pairs$limit\">Pairs $limit</a> | <a href=\"#rank$limit\">Rank $limit</a> | <a href=\"#grid$limit\">Grid $limit</a></h4>";
		
		// initalize variables [include]
		require ("includes/init_display.incl");
		
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
		
		if ($mega_balls)
		{
			print("<TD BGCOLOR=\"#CCCCCC\">PB</TD>\n");
		}

		print("<TD BGCOLOR=\"#CCCCCC\">Sum/0</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Sum/d</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Sum/x</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Sum/<br>86-114</TD>\n");
		print("</B></TR>\n");
	
		$dcount = 1;
		$num_array = array_fill (0,60,0);
		
		$num_array_count = array_fill (0,60,$num_array);
		$pb_array_count = array_fill (0,60,$num_array);
		$prev_date = array_fill (0,60,'1962-08-17');

		$draw_count_temp_array = array_fill (0,16,0);
		$draw_count_array = array_fill (0,60,$draw_count_temp_array);
			
		// get from draw table
		$query5 = "SELECT * FROM $draw_table_name ";
		if ($hml)
		{
			$query5 .= "WHERE sum >= $range_low  ";
			$query5 .= "AND   sum <= $range_high  ";
		}
		$query5 .= "ORDER BY date DESC ";
		$query5 .= "LIMIT $limit "; 

		##echo "$query5<br>";
	
		$mysqli_result5 = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));

		$z = 1;
		$pb_array = array_fill (0,50,0);
		$pb_date = array_fill (0,50,'1962-08-17');

		#initialize date variables
		require ("includes/unix.incl");
	
		// get each row
		while($row = mysqli_fetch_array($mysqli_result5))
		{
			#echo "row[sum] = $row[sum]<p>";

			// build draw array [0..x]
			$draw = array ();
	
			for ($x = 1; $x <= $balls_drawn; $x++)
			{
				array_push($draw, $row[$x]);
			}

			sort($draw);
	
			if ($mega_balls)
			{
				$pb = $row[pb];
			}

			$modulus10 = $dcount % 100;

			if ($modulus10 == 1 || $limit < 100) 
			{
				print("<TR>\n");
		
				print("<TD align=center><b>$dcount</b></TD>\n");
				print("<TD>$row[date]</TD>\n");
		
				for($index = 0; $index < $balls_drawn; $index++)
				{
					print("<TD align=center>$draw[$index]</TD>\n");
				}
				
				if ($mega_balls)
				{
					print("<TD align=center><b>$row[pb]</b></TD>\n");
				}

				# -------------------------------------------------------------------------------------------------------
				# ----------  Sum Limit All -----------------
				# -------------------------------------------------------------------------------------------------------
				$query_sum = "SELECT * FROM $draw_prefix";
				$query_sum .= "filter_limits ";
				$query_sum .= "WHERE col1 = '$row[b1]' ";
				$query_sum .= "AND limit_type = 'sm' ";
				$query_sum .= "AND hml = '0' ";
				$query_sum .= "AND date = '$row[date]' ";
				$query_sum .= "ORDER BY id DESC ";

				##echo "$query_sum<br>";
			
				$mysqli_result_sum = mysqli_query($query_sum, $mysqli_link) or die (mysqli_error($mysqli_link));
			
				$row_sum = mysqli_fetch_array($mysqli_result_sum);
		
				$num_rows_sum = mysqli_num_rows($mysqli_result_sum);
				
				if ($num_rows_sum)
				{
					$sum_low = $row_sum[low];
					$sum_high = $row_sum[high];
				} else {
					#echo "$row[date] -- num_rows_sum = $num_rows_sum<p>";

					$hml = 0;

					require ("includes/hml_switch.incl");

					lot_filter_limit_sum ($row[date]);

					$query_sum = "SELECT * FROM $draw_prefix";
					$query_sum .= "filter_limits ";
					$query_sum .= "WHERE col1 = '$row[b1]' ";
					$query_sum .= "AND limit_type = 'sm' ";
					$query_sum .= "AND hml = '0' ";
					$query_sum .= "AND date = '$row[date]' ";
					$query_sum .= "ORDER BY id DESC ";
				
					$mysqli_result_sum = mysqli_query($query_sum, $mysqli_link) or die (mysqli_error($mysqli_link));
				
					$row_sum = mysqli_fetch_array($mysqli_result_sum);

					$sum_low = $row_sum[low];
					$sum_high = $row_sum[high];
				}

				if ($debug)
				{
					print "<h5>(all/0) sum_low = $row_sum[low] / sum_mid = $row_sum[mid] / sum_high = $row_sum[high] - row_sum = $row[sum]</h5>";
				}

				# -------------------------------------------------------------------------------------------------------

				# -------------------------------------------------------------------------------------------------------
				# ----------  Sum Limit Decade -----------------
				# -------------------------------------------------------------------------------------------------------
				$temp_decade = (intval($row[sum]/10))*10;
				
				$query_sum = "SELECT * FROM $draw_prefix";
				$query_sum .= "filter_limits ";
				$query_sum .= "WHERE col1 = '$row[b1]' ";
				$query_sum .= "AND limit_type = 'sm' ";
				$query_sum .= "AND hml = '$temp_decade' ";
				$query_sum .= "AND date = '$row[date]' ";
				$query_sum .= "ORDER BY id DESC ";

				##echo "$query_sum<p>";
			
				$mysqli_result_sum = mysqli_query($query_sum, $mysqli_link) or die (mysqli_error($mysqli_link));
			
				$row_sum = mysqli_fetch_array($mysqli_result_sum);
		
				$num_rows_sum = mysqli_num_rows($mysqli_result_sum);
				
				if ($num_rows_sum)
				{
					$sum_low_d = $row_sum[low];
					$sum_high_d = $row_sum[high];
				} else {
					#echo "$row[date] -- num_rows_sum = $num_rows_sum<p>";

					$hml = $temp_decade;

					require ("includes/hml_switch.incl");

					lot_filter_limit_sum ($row[date]);

					$query_sum = "SELECT * FROM $draw_prefix";
					$query_sum .= "filter_limits ";
					$query_sum .= "WHERE col1 = '$row[b1]' ";
					$query_sum .= "AND limit_type = 'sm' ";
					$query_sum .= "AND hml = '$temp_decade' ";
					$query_sum .= "AND date = '$row[date]' ";
					$query_sum .= "ORDER BY id DESC ";
				
					$mysqli_result_sum = mysqli_query($query_sum, $mysqli_link) or die (mysqli_error($mysqli_link));
				
					$row_sum = mysqli_fetch_array($mysqli_result_sum);

					$sum_low_d = $row_sum[low];
					$sum_high_d = $row_sum[high];
				}

				if ($debug)
				{
					print "<h5>(decade) sum_low_d = $row_sum[low] / sum_mid_d = $row_sum[mid] / sum_high_d = $row_sum[high] - row_sum = $row[sum]</h5>";
				}

				# -------------------------------------------------------------------------------------------------------

				# -------------------------------------------------------------------------------------------------------
				# ----------  Sum Limit Sum -----------------
				# -------------------------------------------------------------------------------------------------------	
				$hml = $row[sum] + 500;
				
				$query_sum = "SELECT * FROM $draw_prefix";
				$query_sum .= "filter_limits ";
				$query_sum .= "WHERE col1 = '$row[b1]' ";
				$query_sum .= "AND limit_type = 'sm' ";
				$query_sum .= "AND hml = '$hml' ";
				$query_sum .= "AND date = '$row[date]' ";
				$query_sum .= "ORDER BY id DESC ";

				##echo "$query_sum<p>";
			
				$mysqli_result_sum = mysqli_query($query_sum, $mysqli_link) or die (mysqli_error($mysqli_link));
			
				$row_sum = mysqli_fetch_array($mysqli_result_sum);
		
				$num_rows_sum = mysqli_num_rows($mysqli_result_sum);
				
				if ($num_rows_sum)
				{
					$sum_low_s = $row_sum[low];
					$sum_high_s = $row_sum[high];
				} else {
					#echo "$row[date] -- num_rows_sum = $num_rows_sum<p>";

					require ("includes/hml_switch.incl");

					lot_filter_limit_sum ($row[date]);

					$query_sum = "SELECT * FROM $draw_prefix";
					$query_sum .= "filter_limits ";
					$query_sum .= "WHERE col1 = '$row[b1]' ";
					$query_sum .= "AND limit_type = 'sm' ";
					$query_sum .= "AND hml = '$hml' ";
					$query_sum .= "AND date = '$row[date]' ";
					$query_sum .= "ORDER BY id DESC ";

					##echo "$query_sum<p>";
				
					$mysqli_result_sum = mysqli_query($query_sum, $mysqli_link) or die (mysqli_error($mysqli_link));
				
					$row_sum = mysqli_fetch_array($mysqli_result_sum);

					$sum_low_s = $row_sum[low];
					$sum_high_s = $row_sum[high];
				}

				if ($debug)
				{
					print "<h5>(sum) sum_low_s = $row_sum[low] / sum_mid_s = $row_sum[mid] / sum_high_s = $row_sum[high] - row_sum = $row[sum]</h5>";
				}

				# -------------------------------------------------------------------------------------------------------
				
				if ($row[sum] < $sum_low || $row[sum] > $sum_high) #------------------------
				{
					print("<TD bgcolor=\"#FF0000\" align=center><font color=\"#ffffff\"><b>$row[sum]</b></font></TD>\n");
					$sum_a_count++;
				} else {
					print("<TD align=center>$row[sum]</TD>\n");
				}			
				if ($row[sum] < $sum_low_d || $row[sum] > $sum_high_d) #------------------------
				{
					print("<TD bgcolor=\"#FF0000\" align=center><font color=\"#ffffff\"><b>$row[sum]</b></font></TD>\n");
					$sum_d_count++;
				} else {
					print("<TD align=center>$row[sum]</TD>\n");
				}		
				if ($row[sum] < $sum_low_s || $row[sum] > $sum_high_s) #------------------------
				{
					print("<TD bgcolor=\"#FF0000\" align=center><font color=\"#ffffff\"><b>$row[sum]</b></font></TD>\n");
					$sum_s_count++;
				} else {
					print("<TD align=center>$row[sum]</TD>\n");
				}	
				if ($row[sum] < 86 || $row[sum] > 114) #------------------------
				{
					print("<TD bgcolor=\"#FF0000\" align=center><font color=\"#ffffff\"><b>$row[sum]</b></font></TD>\n");
					$sum_s_fixed++;
				} else {
					print("<TD align=center>$row[sum]</TD>\n");
				}		
				print("</TR>\n");
			}
			
			$dcount++;
			$z++;
		}

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

		print("<TD BGCOLOR=\"#CCCCCC\">Sum/0</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Sum/d</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Sum/x</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Sum/<br>86-114</TD>\n");
		print("</B></TR>\n");
	
		//end table
		print("</TABLE>\n");

		$temp_result = ($sum_a_count/$limit)*100;

		echo "<h3><font color=\"#ff0000\">Sum/0 Failed - $temp_result %</font></h3>";

		$temp_result = ($sum_d_count/$limit)*100;

		echo "<h3><font color=\"#ff0000\">Sum/d Failed - $temp_result %</font></h3>";

		$temp_result = ($sum_s_count/$limit)*100;

		echo "<h3><font color=\"#ff0000\">Sum/s Failed - $temp_result %</font></h3>";

		$temp_result = ($sum_s_fixed/$limit)*100;

		echo "<h3><font color=\"#ff0000\">Sum/fixed Failed - $temp_result %</font></h3>";
	}

	// ----------------------------------------------------------------------------------
	function lot_balls ($limit)
	{ 
		global $debug, $game, $game_name, $balls, $balls_drawn, $mega_balls, $draw_table_name, $draw_prefix, $game_includes, $hml, $range_low, $range_high;
	
		require ("includes/mysqli.php");

		require ("$game_includes/config.incl");

		require ("includes/hml_switch.incl");

		$sum_0_count_row = 0;
		$sum_d_count_row = 0;
		$sum_x_count_row = 0;
		$sum_0_count = 0;
		$sum_d_count = 0;
		$sum_x_count = 0;
		
		print("<a name=\"$limit\"><H2>Balls - $game_name - Limit = $limit</H2></a>\n");

		print "<h4><a href=\"#unsorted$limit\">Unsorted $limit</a> | <a href=\"#sorted$limit\">Sorted $limit</a> | <a href=\"#pairs$limit\">Pairs $limit</a> | <a href=\"#rank$limit\">Rank $limit</a> | <a href=\"#grid$limit\">Grid $limit</a></h4>";
		
		// initalize variables [include]
		require ("includes/init_display.incl");
		
		//start table
		print("<TABLE BORDER=\"1\">\n");
	
		//create header row
		print("<TR><B>\n");
		print("<TD BGCOLOR=\"#CCCCCC\" width=20>&nbsp;</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Date</center></TD>\n");
		for($index = 1; $index <= $balls_drawn; $index++)
		{
			print("<TD BGCOLOR=\"#AAAAAA\">b$index.a</TD>\n");
		}
		print("<TD BGCOLOR=\"#CCCCCC\" width=20>&nbsp;</TD>\n");
		for($index = 1; $index <= $balls_drawn; $index++)
		{
			print("<TD BGCOLOR=\"#EEEEEE\">b$index.d</TD>\n");
		}
		print("<TD BGCOLOR=\"#CCCCCC\" width=20>&nbsp;</TD>\n");
		for($index = 1; $index <= $balls_drawn; $index++)
		{
			print("<TD BGCOLOR=\"#AAAAAA\">b$index.s</TD>\n");
		}
		
		if ($mega_balls)
		{
			print("<TD BGCOLOR=\"#CCCCCC\">PB</TD>\n");
		}
		print("<TD BGCOLOR=\"#CCCCCC\" width=20>&nbsp;</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Sum</TD>\n");

		print("</B></TR>\n");
	
		$dcount = 1;
		$num_array = array_fill (0,60,0);
		
		$num_array_count = array_fill (0,60,$num_array);
		$pb_array_count = array_fill (0,60,$num_array);
		$prev_date = array_fill (0,60,'1962-08-17');

		$draw_count_temp_array = array_fill (0,16,0);
		$draw_count_array = array_fill (0,60,$draw_count_temp_array);
			
		// get from draw table
		$query5 = "SELECT * FROM $draw_table_name ";
		if ($hml)
		{
			$query5 .= "WHERE sum >= $range_low  ";
			$query5 .= "AND   sum <= $range_high  ";
		}
		$query5 .= "ORDER BY date DESC ";
		$query5 .= "LIMIT $limit "; 

		##echo "$query5<br>";
	
		$mysqli_result5 = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));

		$z = 1;
		$pb_array = array_fill (0,50,0);
		$pb_date = array_fill (0,50,'1962-08-17');

		#initialize date variables
		require ("includes/unix.incl");
	
		// get each row
		while($row = mysqli_fetch_array($mysqli_result5))
		{
			#echo "row[sum] = $row[sum]<p>";

			// build draw array [0..x]
			$draw = array ();
	
			for ($x = 1; $x <= $balls_drawn; $x++)
			{
				array_push($draw, $row[$x]);
			}

			sort($draw);
	
			if ($mega_balls)
			{
				$pb = $row[pb];
			}

			$modulus10 = $dcount % 100;

			if ($modulus10 == 1 || $limit < 100) 
			{
				print("<TR>\n");
		
				print("<TD align=center><b>$dcount</b></TD>\n");
				print("<TD>$row[date]</TD>\n");
		
				
				
				if ($mega_balls)
				{
					print("<TD align=center><b>$row[pb]</b></TD>\n");
				}
				
				#-----------------------------------------------------------------------------------------

				for($index = 0; $index < $balls_drawn; $index++)
				{
					require ("includes/balls_all.incl");
					if ($draw[$index] < $balls_low || $draw[$index] > $balls_high) #------------------------
					{
						print("<TD bgcolor=\"#FF0000\" align=center><font color=\"#ffffff\"><b>$draw[$index]</b></font></TD>\n");
						$sum_a_count++;
					} else {
						print("<TD align=center>$draw[$index]</TD>\n");
					}	
				}

				if ($sum_a_count)
				{
					$sum_a_count_row++;
				}

				print("<TD width=20>&nbsp;</TD>\n");

				for($index = 0; $index < $balls_drawn; $index++)
				{
					require ("includes/balls_decade.incl");
					if ($draw[$index] < $balls_low || $draw[$index] > $balls_high) #------------------------
					{
						print("<TD bgcolor=\"#FF0000\" align=center><font color=\"#ffffff\"><b>$draw[$index]</b></font></TD>\n");
						$sum_d_count++;
					} else {
						print("<TD align=center>$draw[$index]</TD>\n");
					}	
				}

				if ($sum_d_count)
				{
					$sum_d_count_row++;
				}
				
				print("<TD width=20>&nbsp;</TD>\n");

				for($index = 0; $index < $balls_drawn; $index++)
				{
					require ("includes/balls_sum.incl");
					if ($draw[$index] < $balls_low || $draw[$index] > $balls_high) #------------------------
					{
						print("<TD bgcolor=\"#FF0000\" align=center><font color=\"#ffffff\"><b>$draw[$index]</b></font></TD>\n");
						$sum_s_count++;
						$sum_s_count_row++;
					} else {
						print("<TD align=center>$draw[$index]</TD>\n");
					}	
				}

				if ($sum_s_count)
				{
					$sum_s_count_row++;
				}

				print("<TD BGCOLOR=\"#CCCCCC\" width=20>&nbsp;</TD>\n");
				print("<TD align=center>$row[sum]</TD>\n");
				print("</TR>\n");
			}
			
			$dcount++;
			$z++;
		}

		//create footer row
		print("<TR><B>\n");
		print("<TD BGCOLOR=\"#CCCCCC\" width=20>&nbsp;</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Date</center></TD>\n");
		for($index = 1; $index <= $balls_drawn; $index++)
		{
			print("<TD BGCOLOR=\"#AAAAAA\">b$index.a</TD>\n");
		}
		print("<TD BGCOLOR=\"#CCCCCC\" width=20>&nbsp;</TD>\n");
		for($index = 1; $index <= $balls_drawn; $index++)
		{
			print("<TD BGCOLOR=\"#EEEEEE\">b$index.d</TD>\n");
		}
		print("<TD BGCOLOR=\"#CCCCCC\" width=20>&nbsp;</TD>\n");
		for($index = 1; $index <= $balls_drawn; $index++)
		{
			print("<TD BGCOLOR=\"#AAAAAA\">b$index.s</TD>\n");
		}
		
		if ($mega_balls)
		{
			print("<TD BGCOLOR=\"#CCCCCC\">PB</TD>\n");
		}
		print("<TD BGCOLOR=\"#CCCCCC\" width=20>&nbsp;</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Sum</TD>\n");
		
		if ($mega_balls)
		{
			print("<TD BGCOLOR=\"#CCCCCC\">PB</TD>\n");
		}

		print("</B></TR>\n");
	
		//end table
		print("</TABLE>\n");

		echo "Nums -----------------<p>";
		
		$temp_result = ($sum_a_count/($limit*5))*100;

		echo "<h3><font color=\"#ff0000\">Sum/a Failed - $temp_result %</font></h3>";

		$temp_result = ($sum_d_count/($limit*5))*100;

		echo "<h3><font color=\"#ff0000\">Sum/d Failed - $temp_result %</font></h3>";

		$temp_result = ($sum_s_count/($limit*5))*100;

		echo "<h3><font color=\"#ff0000\">Sum/s Failed - $temp_result %</font></h3>";

		echo "Draws -----------------<p>";
		
		$temp_result = ($sum_a_count_row/$limit)*100;

		echo "<h3><font color=\"#ff0000\">Sum/a Failed - $temp_result %</font></h3>";

		$temp_result = ($sum_d_count_row/$limit)*100;

		echo "<h3><font color=\"#ff0000\">Sum/d Failed - $temp_result %</font></h3>";

		$temp_result = ($sum_s_count_row/$limit)*100;

		echo "<h3><font color=\"#ff0000\">Sum/s Failed - $temp_result %</font></h3>";

		// process and print megaball table
		require ("includes/megaball_table.incl");
	}

	// ----------------------------------------------------------------------------------
	function lot_combo ($limit,$count,$combo)
	{ 
		global $debug, $game, $game_name, $balls, $balls_drawn, $mega_balls, $draw_table_name, $draw_prefix, $game_includes, $hml, $range_low, $range_high;
	
		require ("includes/mysqli.php");

		require ("$game_includes/config.incl");

		require ("includes/hml_switch.incl");

		$sum_0_count_row = 0;
		$sum_d_count_row = 0;
		$sum_x_count_row = 0;
		$sum_0_count = 0;
		$sum_d_count = 0;
		$sum_x_count = 0;
		
		print("<a name=\"$limit\"><H2>Combo - $game_name - Limit = $limit - Count = $count - Combo = $combo</H2></a>\n");
		
		// initalize variables [include]
		require ("includes/init_display.incl");
		
		//start table
		print("<TABLE BORDER=\"1\">\n");
	
		//create header row
		print("<TR><B>\n");
		print("<TD BGCOLOR=\"#CCCCCC\" width=20>&nbsp;</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Date</center></TD>\n");
		for($index = 1; $index <= $balls_drawn; $index++)
		{
			print("<TD BGCOLOR=\"#AAAAAA\">b$index.a</TD>\n");
		}
		print("<TD BGCOLOR=\"#CCCCCC\" width=20>&nbsp;</TD>\n");
		for($index = 1; $index <= $balls_drawn; $index++)
		{
			print("<TD BGCOLOR=\"#EEEEEE\">b$index.d</TD>\n");
		}
		print("<TD BGCOLOR=\"#CCCCCC\" width=20>&nbsp;</TD>\n");
		for($index = 1; $index <= $balls_drawn; $index++)
		{
			print("<TD BGCOLOR=\"#AAAAAA\">b$index.s</TD>\n");
		}
		
		if ($mega_balls)
		{
			print("<TD BGCOLOR=\"#CCCCCC\">PB</TD>\n");
		}
		print("<TD BGCOLOR=\"#CCCCCC\" width=20>&nbsp;</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Sum</TD>\n");

		print("</B></TR>\n");
	
		$dcount = 1;
		$num_array = array_fill (0,60,0);
		
		$num_array_count = array_fill (0,60,$num_array);
		$pb_array_count = array_fill (0,60,$num_array);
		$prev_date = array_fill (0,60,'1962-08-17');

		$draw_count_temp_array = array_fill (0,16,0);
		$draw_count_array = array_fill (0,60,$draw_count_temp_array);
			
		// get from draw table
		$query5 = "SELECT * FROM $draw_table_name ";
		if ($hml)
		{
			$query5 .= "WHERE sum >= $range_low  ";
			$query5 .= "AND   sum <= $range_high  ";
		}
		$query5 .= "ORDER BY date DESC ";
		$query5 .= "LIMIT $limit "; 

		echo "$query5<br>";
	
		$mysqli_result5 = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));

		$z = 1;
		$pb_array = array_fill (0,50,0);
		$pb_date = array_fill (0,50,'1962-08-17');

		#initialize date variables
		require ("includes/unix.incl");
	
		// get each row
		while($row = mysqli_fetch_array($mysqli_result5))
		{
			#echo "row[sum] = $row[sum]<p>";

			// build draw array [0..x]
			$draw = array ();
	
			for ($x = 1; $x <= $balls_drawn; $x++)
			{
				array_push($draw, $row[$x]);
			}

			sort($draw);
	
			if ($mega_balls)
			{
				$pb = $row[pb];
			}

			$modulus10 = $dcount % 100;

			if ($modulus10 == 1 || $limit < 100) 
			{
				print("<TR>\n");
		
				print("<TD align=center><b>$dcount</b></TD>\n");
				print("<TD>$row[date]</TD>\n");
				
				if ($mega_balls)
				{
					print("<TD align=center><b>$row[pb]</b></TD>\n");
				}
				
				#-----------------------------------------------------------------------------------------

				for($index = 0; $index < $balls_drawn; $index++)
				{
					require ("includes/combo_all.incl");
					if ($draw[$index] < $balls_low || $draw[$index] > $balls_high) #------------------------
					{
						print("<TD bgcolor=\"#FF0000\" align=center><font color=\"#ffffff\"><b>$draw[$index]</b></font></TD>\n");
						$sum_a_count++;
					} else {
						print("<TD align=center>$draw[$index]</TD>\n");
					}	
				}

				if ($sum_a_count)
				{
					$sum_a_count_row++;
				}

				print("<TD width=20>&nbsp;</TD>\n");

				for($index = 0; $index < $balls_drawn; $index++)
				{
					#require ("includes/combo_decade.incl");
					if ($draw[$index] < $balls_low || $draw[$index] > $balls_high) #------------------------
					{
						print("<TD bgcolor=\"#FF0000\" align=center><font color=\"#ffffff\"><b>$draw[$index]</b></font></TD>\n");
						$sum_d_count++;
					} else {
						print("<TD align=center>$draw[$index]</TD>\n");
					}	
				}

				if ($sum_d_count)
				{
					$sum_d_count_row++;
				}
				
				print("<TD width=20>&nbsp;</TD>\n");

				for($index = 0; $index < $balls_drawn; $index++)
				{
					#require ("includes/combo_sum.incl");
					if ($draw[$index] < $balls_low || $draw[$index] > $balls_high) #------------------------
					{
						print("<TD bgcolor=\"#FF0000\" align=center><font color=\"#ffffff\"><b>$draw[$index]</b></font></TD>\n");
						$sum_s_count++;
						$sum_s_count_row++;
					} else {
						print("<TD align=center>$draw[$index]</TD>\n");
					}	
				}

				if ($sum_s_count)
				{
					$sum_s_count_row++;
				}

				print("<TD BGCOLOR=\"#CCCCCC\" width=20>&nbsp;</TD>\n");
				print("<TD align=center>$row[sum]</TD>\n");
				print("</TR>\n");
			}
			
			$dcount++;
			$z++;
		}

		//create footer row
		print("<TR><B>\n");
		print("<TD BGCOLOR=\"#CCCCCC\" width=20>&nbsp;</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Date</center></TD>\n");
		for($index = 1; $index <= $balls_drawn; $index++)
		{
			print("<TD BGCOLOR=\"#AAAAAA\">b$index.a</TD>\n");
		}
		print("<TD BGCOLOR=\"#CCCCCC\" width=20>&nbsp;</TD>\n");
		for($index = 1; $index <= $balls_drawn; $index++)
		{
			print("<TD BGCOLOR=\"#EEEEEE\">b$index.d</TD>\n");
		}
		print("<TD BGCOLOR=\"#CCCCCC\" width=20>&nbsp;</TD>\n");
		for($index = 1; $index <= $balls_drawn; $index++)
		{
			print("<TD BGCOLOR=\"#AAAAAA\">b$index.s</TD>\n");
		}
		
		if ($mega_balls)
		{
			print("<TD BGCOLOR=\"#CCCCCC\">PB</TD>\n");
		}
		print("<TD BGCOLOR=\"#CCCCCC\" width=20>&nbsp;</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Sum</TD>\n");
		
		if ($mega_balls)
		{
			print("<TD BGCOLOR=\"#CCCCCC\">PB</TD>\n");
		}

		print("</B></TR>\n");
	
		//end table
		print("</TABLE>\n");

		echo "Nums -----------------<p>";
		
		$temp_result = ($sum_a_count/($limit*5))*100;

		echo "<h3><font color=\"#ff0000\">Sum/a Failed - $temp_result %</font></h3>";

		$temp_result = ($sum_d_count/($limit*5))*100;

		echo "<h3><font color=\"#ff0000\">Sum/d Failed - $temp_result %</font></h3>";

		$temp_result = ($sum_s_count/($limit*5))*100;

		echo "<h3><font color=\"#ff0000\">Sum/s Failed - $temp_result %</font></h3>";

		echo "Draws -----------------<p>";
		
		$temp_result = ($sum_a_count_row/$limit)*100;

		echo "<h3><font color=\"#ff0000\">Sum/a Failed - $temp_result %</font></h3>";

		$temp_result = ($sum_d_count_row/$limit)*100;

		echo "<h3><font color=\"#ff0000\">Sum/d Failed - $temp_result %</font></h3>";

		$temp_result = ($sum_s_count_row/$limit)*100;

		echo "<h3><font color=\"#ff0000\">Sum/s Failed - $temp_result %</font></h3>";

		// process and print megaball table
		require ("includes/megaball_table.incl");
	}

	// ----------------------------------------------------------------------------------
	function lot_display ($limit)
	{ 
		global $debug, $game, $game_name, $balls, $balls_drawn, $mega_balls, $draw_table_name, $draw_prefix, $game_includes, $hml, $range_low, $range_high;
	
		require ("includes/mysqli.php");

		require ("$game_includes/config.incl");

		require ("includes/hml_switch.incl");
		
		print("<a name=\"$limit\"><H2>Lotto Display - $game_name - Limit $limit</H2></a>\n");

		print "<h4><a href=\"#unsorted$limit\">Unsorted $limit</a> | <a href=\"#sorted$limit\">Sorted $limit</a> | <a href=\"#pairs$limit\">Pairs $limit</a> | <a href=\"#rank$limit\">Rank $limit</a> | <a href=\"#grid$limit\">Grid $limit</a></h4>";

		# -------------------------------------------------------------------------------------------------------
		# ----------  Sum Limit -----------------
		# -------------------------------------------------------------------------------------------------------
		$query_sum = "SELECT * FROM $draw_prefix";
		$query_sum .= "filter_limits ";
		$query_sum .= "WHERE col1 = '0' ";
		$query_sum .= "AND limit_type = 'sm' ";
		$query_sum .= "ORDER BY id DESC ";
		
		##echo "$query_sum<p>";
	
		$mysqli_result_sum = mysqli_query($query_sum, $mysqli_link) or die (mysqli_error($mysqli_link));
	
		$row_sum = mysqli_fetch_array($mysqli_result_sum);
		# -------------------------------------------------------------------------------------------------------

		print "<h5>(all/0) sum_low = $row_sum[low] / sum_mid = $row_sum[mid] / sum_high = $row_sum[high]</h5>";
		
		// initalize variables [include]
		require ("includes/init_display.incl");
		
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
		
		if ($mega_balls)
		{
			print("<TD BGCOLOR=\"#CCCCCC\">PB</TD>\n");
		}

		print("<TD BGCOLOR=\"#CCCCCC\">Sum</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Average</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Median</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Harmean</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Geomean</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Quart1</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Quart2</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Quart3</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Stdev</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Variance</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">AveDev</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Kurt</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Skew</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">DevSq</TD>\n");
		print("</B></TR>\n");
	
		$dcount = 1;
		$num_array = array_fill (0,60,0);
		
		$num_array_count = array_fill (0,60,$num_array);
		$pb_array_count = array_fill (0,60,$num_array);
		$prev_date = array_fill (0,60,'1962-08-17');

		$draw_count_temp_array = array_fill (0,16,0);
		$draw_count_array = array_fill (0,60,$draw_count_temp_array);

		#require ("includes/pair_table.incl"); #pair	

		// get from draw table
		$query5 = "SELECT * FROM $draw_table_name ";
		$query5 .= "WHERE sum = 0 ";
		$query5 .= "ORDER BY date DESC ";
		$query5 .= "LIMIT $limit "; 
	
		$mysqli_result5 = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));
	
		// get each row
		while($row = mysqli_fetch_array($mysqli_result5))
		{	
			if ($row[sum] == 0)
			{
				$draw_sum  = $row[sum];
				$draw_even  = $row[even];
				$draw_odd  = $row[odd];

				// build draw array [0..x]
				$draw = array ();
		
				for ($x = 1; $x <= $balls_drawn; $x++)
				{
					array_push($draw, $row[$x]);
				}

				sort($draw);

				require ("includes/update_sum.incl");
				require ("includes/update_even_odd.incl");
				require ("$game_includes/update_stats.incl");
			} 
		}

		// get from draw table
		$query5 = "SELECT * FROM $draw_table_name ";
		$query5 .= "WHERE devsq = 99.99 ";
		$query5 .= "ORDER BY date DESC ";
		$query5 .= "LIMIT $limit "; 
	
		$mysqli_result5 = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));
	
		// get each row
		while($row = mysqli_fetch_array($mysqli_result5))
		{		
			// build draw array [x..x]
			$draw = array ();
	
			for ($x = 1; $x <= $balls_drawn; $x++)
			{
				array_push($draw, $row[$x]);
			}

			sort($draw);

			if ($row[devsq] = 99.99) 
			{
				$draw_average = ($row[b1]+$row[b2]+$row[b3]+$row[b4]+$row[b5])/5;
				$devsq = calc_devsq ($draw,$draw_average);

				$query7 = "UPDATE $draw_table_name ";
				$query7 .= "SET   devsq = $devsq, ";
				$query7 .= "      draw_average = $draw_average ";
				$query7 .= "WHERE date = '$row[date]' ";

				#print "$query7<p>";
				
				$mysqli_result7 = mysqli_query($query7, $mysqli_link) or die (mysqli_error($mysqli_link));
			}
		}
			
		// get from draw table
		$query5 = "SELECT * FROM $draw_table_name ";
		if ($hml)
		{
			$query5 .= "WHERE sum >= $range_low  ";
			$query5 .= "AND   sum <= $range_high  ";
		}
		$query5 .= "ORDER BY date DESC ";
		$query5 .= "LIMIT $limit "; 

		##echo "$query5<br>";

		print("<h2>Decades</h2>");
	
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
	
			for ($x = 1; $x <= $balls_drawn; $x++)
			{
				array_push($draw, $row[$x]);
			}

			sort($draw);

			$draw_sum  = $row[sum];
			$draw_even  = $row[even];
			$draw_odd  = $row[odd];
	
			if ($mega_balls)
			{
				$pb = $row[pb];
			}

			$modulus10 = $dcount % 100;

			if ($modulus10 == 1 || $limit < 100) 
			{
				print("<TR>\n");
		
				print("<TD align=center><b>$dcount</b></TD>\n");
				print("<TD>$row[date]</TD>\n");
		
				for($index = 0; $index < $balls_drawn; $index++)
				{
					print("<TD align=center>$draw[$index]</TD>\n");
				}
				
				if ($mega_balls)
				{
					print("<TD align=center><b>$row[pb]</b></TD>\n");
				}

				# -------------------------------------------------------------------------------------------------------
				# ----------  Sum Limit -----------------
				# -------------------------------------------------------------------------------------------------------
				$query_sum = "SELECT * FROM $draw_prefix";
				$query_sum .= "filter_limits ";
				$query_sum .= "WHERE col1 = '$row[b1]' ";
				$query_sum .= "AND limit_type = 'sm' ";
				$query_sum .= "AND date = '$row[date]' ";
				$query_sum .= "ORDER BY id DESC ";

				##echo "$query_sum<p>";
			
				$mysqli_result_sum = mysqli_query($query_sum, $mysqli_link) or die (mysqli_error($mysqli_link));
			
				$row_sum = mysqli_fetch_array($mysqli_result_sum);
				# -------------------------------------------------------------------------------------------------------
		
				$num_rows_sum = mysqli_num_rows($mysqli_result_sum);
				
				if ($num_rows_sum)
				{
					$sum_low = $row_sum[low];
					$sum_high = $row_sum[high];
				} else {
					$sum_low = 80;
					$sum_high = 120;
				}
				
				if ($draw_sum < $sum_low || $draw_sum > $sum_high) #------------------------
				{
					print("<TD bgcolor=\"#FF0000\" align=center><font color=\"#ffffff\"><b>$draw_sum</b></font></TD>\n");
				} else {
					print("<TD align=center>$draw_sum</TD>\n");
				}

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
			
				print("</TR>\n");
			}

			##############################################################
			# calculate draw count
			##############################################################
			require ("includes/calculate_draw_count.incl"); #ball count
			
			$dcount++;
			$z++;
		}

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
		print("<TD BGCOLOR=\"#CCCCCC\">Average</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Median</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Harmean</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Geomean</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Quart1</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Quart2</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Quart3</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Stdev</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Variance</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">AveDev</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Kurt</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Skew</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">DevSq</TD>\n");
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
		require ("includes/draw_count_table.incl");
		##############################################################################

		// process and print unsorted table
		if ($limit > 105)
		{
			require ("includes/unsorted_table_large.incl");
		} elseif ($limit == 100) {
			require ("includes/unsorted_table_100.incl");
		} else {
			require ("includes/unsorted_table.incl");
		}

		print "<h4><a href=\"#unsorted$limit\">Unsorted $limit</a> | <a href=\"#sorted$limit\">Sorted $limit</a> | <a href=\"#pairs$limit\">Pairs $limit</a> | <a href=\"#rank$limit\">Rank $limit</a> | <a href=\"#grid$limit\">Grid $limit</a><a name=\"sorted$limit\">&nbsp;</a></h4>";

		##############################################################
		# process and print sorted table
		##############################################################
		if ($limit > 105 )
		{
			require ("includes/sorted_table_large.incl");
		} elseif ($limit == 100) {
			require ("includes/sorted_table_100.incl");
		} else {
			require ("includes/sorted_table.incl");
		}

		// process and print megaball table
		require ("includes/megaball_table.incl");
	}

	// ----------------------------------------------------------------------------------
	function lot_even_odd ($limit)
	{ 
		global $debug, $game, $game_name, $balls, $balls_drawn, $mega_balls, $draw_table_name, $draw_prefix, $game_includes, $hml, $range_low, $range_high;
	
		require ("includes/mysqli.php");

		require ("$game_includes/config.incl");

		require ("includes/hml_switch.incl");

		$even_a_count = 0;
		$even_d_count = 0;
		$even_s_count = 0;
		$odd_a_count = 0;
		$odd_d_count = 0;
		$odd_s_count = 0;
		
		print("<a name=\"$limit\"><H2>Even/Odd - $game_name - Limit = $limit</H2></a>\n");

		print "<h4><a href=\"#unsorted$limit\">Unsorted $limit</a> | <a href=\"#sorted$limit\">Sorted $limit</a> | <a href=\"#pairs$limit\">Pairs $limit</a> | <a href=\"#rank$limit\">Rank $limit</a> | <a href=\"#grid$limit\">Grid $limit</a></h4>";
		
		// initalize variables [include]
		require ("includes/init_display.incl");
		
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
		
		if ($mega_balls)
		{
			print("<TD BGCOLOR=\"#CCCCCC\">PB</TD>\n");
		}

		print("<TD BGCOLOR=\"#CCCCCC\">Even/0</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Odd/0</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Even/d</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Odd/d</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Even/x</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Odd/x</TD>\n");
		print("</B></TR>\n");
	
		$dcount = 1;
		$num_array = array_fill (0,60,0);
		
		$num_array_count = array_fill (0,60,$num_array);
		$pb_array_count = array_fill (0,60,$num_array);
		$prev_date = array_fill (0,60,'1962-08-17');

		$draw_count_temp_array = array_fill (0,16,0);
		$draw_count_array = array_fill (0,60,$draw_count_temp_array);
			
		// get from draw table
		$query5 = "SELECT * FROM $draw_table_name ";
		if ($hml)
		{
			$query5 .= "WHERE sum >= $range_low  ";
			$query5 .= "AND   sum <= $range_high  ";
		}
		$query5 .= "ORDER BY date DESC ";
		$query5 .= "LIMIT $limit "; 

		##echo "$query5<br>";
	
		$mysqli_result5 = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));

		$z = 1;
		$pb_array = array_fill (0,50,0);
		$pb_date = array_fill (0,50,'1962-08-17');

		#initialize date variables
		require ("includes/unix.incl");
	
		// get each row
		while($row = mysqli_fetch_array($mysqli_result5))
		{
			#echo "row[sum] = $row[sum]<p>";

			// build draw array [0..x]
			$draw = array ();
	
			for ($x = 1; $x <= $balls_drawn; $x++)
			{
				array_push($draw, $row[$x]);
			}

			sort($draw);
	
			if ($mega_balls)
			{
				$pb = $row[pb];
			}

			$modulus10 = $dcount % 100;

			if ($modulus10 == 1 || $limit < 100) 
			{
				print("<TR>\n");
		
				print("<TD align=center><b>$dcount</b></TD>\n");
				print("<TD>$row[date]</TD>\n");
		
				for($index = 0; $index < $balls_drawn; $index++)
				{
					print("<TD align=center>$draw[$index]</TD>\n");
				}
				
				if ($mega_balls)
				{
					print("<TD align=center><b>$row[pb]</b></TD>\n");
				}

				# -------------------------------------------------------------------------------------------------------
				# ----------  Even/Odd Limit All -----------------
				# -------------------------------------------------------------------------------------------------------
				$query_even = "SELECT * FROM $draw_prefix";
				$query_even .= "filter_limits ";
				$query_even .= "WHERE col1 = '$row[b1]' ";
				$query_even .= "AND limit_type = 'ev' ";
				$query_even .= "AND hml = '0' ";
				$query_even .= "AND date = '$row[date]' ";
				$query_even .= "ORDER BY id DESC ";

				#echo "$query_even<p>";
			
				$mysqli_result_even = mysqli_query($query_even, $mysqli_link) or die (mysqli_error($mysqli_link));
			
				$row_even = mysqli_fetch_array($mysqli_result_even);
		
				$num_rows_even = mysqli_num_rows($mysqli_result_even);

				echo "num_rows_even = $num_rows_even<p>";
				
				if ($num_rows_even)
				{
					$even_low = $row_even[low];
					$even_high = $row_even[high];
				} else {
					#echo "$row[date] -- num_rows_sum = $num_rows_sum<p>";

					$hml = 0;

					require ("includes/hml_switch.incl");

					lot_filter_limit_even ($row[date]);

					$query_even = "SELECT * FROM $draw_prefix";
					$query_even .= "filter_limits ";
					$query_even .= "WHERE col1 = '$row[b1]' ";
					$query_even .= "AND limit_type = 'ev' ";
					$query_even .= "AND hml = '0' ";
					$query_even .= "AND date = '$row[date]' ";
					$query_even .= "ORDER BY id DESC ";
				
					$mysqli_result_even = mysqli_query($query_even, $mysqli_link) or die (mysqli_error($mysqli_link));
				
					$row_even = mysqli_fetch_array($mysqli_result_even);

					$even_low = $row_even[low];
					$even_high = $row_even[high];
				}

				#-------------------------------------------------------------------------------------------------------

				$query_odd = "SELECT * FROM $draw_prefix";
				$query_odd .= "filter_limits ";
				$query_odd .= "WHERE col1 = '$row[b1]' ";
				$query_odd .= "AND limit_type = 'od' ";
				$query_odd .= "AND hml = '0' ";
				$query_odd .= "AND date = '$row[date]' ";
				$query_odd .= "ORDER BY id DESC ";

				#echo "$query_odd<p>";
			
				$mysqli_result_odd = mysqli_query($query_odd, $mysqli_link) or die (mysqli_error($mysqli_link));
			
				$row_odd = mysqli_fetch_array($mysqli_result_odd);
		
				$num_rows_odd = mysqli_num_rows($mysqli_result_odd);

				echo "num_rows_odd = $num_rows_odd<p>";
				
				if ($num_rows_odd)
				{
					$odd_low = $row_odd[low];
					$odd_high = $row_odd[high];
				} else {
					#echo "$row[date] -- num_rows_sum = $num_rows_sum<p>";

					$hml = 0;

					require ("includes/hml_switch.incl");

					lot_filter_limit_odd ($row[date]);

					$query_odd = "SELECT * FROM $draw_prefix";
					$query_odd .= "filter_limits ";
					$query_odd .= "WHERE col1 = '$row[b1]' ";
					$query_odd .= "AND limit_type = 'od' ";
					$query_odd .= "AND hml = '0' ";
					$query_odd .= "AND date = '$row[date]' ";
					$query_odd .= "ORDER BY id DESC ";
				
					$mysqli_result_odd = mysqli_query($query_odd, $mysqli_link) or die (mysqli_error($mysqli_link));
				
					$row_odd = mysqli_fetch_array($mysqli_result_odd);

					$odd_low = $row_odd[low];
					$odd_high = $row_odd[high];
				}

				if ($debug)
				{
					print "<h5>(all/0) even_low = $row_sum[low] / sum_mid = $row_sum[mid] / sum_high = $row_sum[high] - row_sum = $row[even]</h5>";
				}

				# -------------------------------------------------------------------------------------------------------

				# -------------------------------------------------------------------------------------------------------
				# ----------  Even/Odd Limit Decade -----------------
				# -------------------------------------------------------------------------------------------------------
				$temp_decade = (intval($row[sum]/10))*10;
				
				$query_even = "SELECT * FROM $draw_prefix";
				$query_even .= "filter_limits ";
				$query_even .= "WHERE col1 = '$row[b1]' ";
				$query_even .= "AND limit_type = 'ev' ";
				$query_even .= "AND hml = '$temp_decade' ";
				$query_even .= "AND date = '$row[date]' ";
				$query_even .= "ORDER BY id DESC ";

				#echo "$query_even<p>";
			
				$mysqli_result_even = mysqli_query($query_even, $mysqli_link) or die (mysqli_error($mysqli_link));
			
				$row_even = mysqli_fetch_array($mysqli_result_even);
		
				$num_rows_even = mysqli_num_rows($mysqli_result_even);

				echo "num_rows_even = $num_rows_even<p>";
				
				if ($num_rows_even)
				{
					$even_low_d = $row_even[low];
					$even_high_d = $row_even[high];
				} else {
					#echo "$row[date] -- num_rows_sum = $num_rows_sum<p>";

					$hml = $temp_decade;

					require ("includes/hml_switch.incl");

					lot_filter_limit_even ($row[date]);

					$query_even = "SELECT * FROM $draw_prefix";
					$query_even .= "filter_limits ";
					$query_even .= "WHERE col1 = '$row[b1]' ";
					$query_even .= "AND limit_type = 'ev' ";
					$query_even .= "AND hml = '$temp_decade' ";
					$query_even .= "AND date = '$row[date]' ";
					$query_even .= "ORDER BY id DESC ";

					#echo "$query_even<p>";
				
					$mysqli_result_even = mysqli_query($query_even, $mysqli_link) or die (mysqli_error($mysqli_link));
				
					$row_even = mysqli_fetch_array($mysqli_result_sum);

					$even_low_d = $row_even[low];
					$even_high_d = $row_even[high];
				}

				if ($debug)
				{
					print "<h5>(decade) sum_low_d = $row_sum[low] / sum_mid_d = $row_sum[mid] / sum_high_d = $row_sum[high] - row_sum = $row[sum]</h5>";
				}

				# -------------------------------------------------------------------------------------------------------

				# -------------------------------------------------------------------------------------------------------
				# ----------  Even/Odd Limit Sum -----------------
				# -------------------------------------------------------------------------------------------------------	
				$hml = $row[sum] + 500;
				
				$query_even = "SELECT * FROM $draw_prefix";
				$query_even .= "filter_limits ";
				$query_even .= "WHERE col1 = '$row[b1]' ";
				$query_even .= "AND limit_type = 'ev' ";
				$query_even .= "AND hml = '$hml' ";
				$query_even .= "AND date = '$row[date]' ";
				$query_even .= "ORDER BY id DESC ";

				#echo "$query_even<p>";
			
				$mysqli_result_even = mysqli_query($query_even, $mysqli_link) or die (mysqli_error($mysqli_link));
			
				$row_even = mysqli_fetch_array($mysqli_result_even);
		
				$num_rows_even = mysqli_num_rows($mysqli_result_even);

				echo "num_rows_even = $num_rows_even<p>";
				
				if ($num_rows_even)
				{
					$even_low_s = $row_even[low];
					$even_high_s = $row_even[high];
				} else {
					#echo "$row[date] -- num_rows_sum = $num_rows_sum<p>";

					require ("includes/hml_switch.incl");

					lot_filter_limit_even ($row[date]);

					$query_even = "SELECT * FROM $draw_prefix";
					$query_even .= "filter_limits ";
					$query_even .= "WHERE col1 = '$row[b1]' ";
					$query_even .= "AND limit_type = 'ev' ";
					$query_even .= "AND hml = '$hml' ";
					$query_even .= "AND date = '$row[date]' ";
					$query_even .= "ORDER BY id DESC ";

					##echo "$query_even<p>";
				
					$mysqli_result_even = mysqli_query($query_even, $mysqli_link) or die (mysqli_error($mysqli_link));
				
					$row_even = mysqli_fetch_array($mysqli_result_even);

					$even_low_s = $row_even[low];
					$even_high_s = $row_even[high];
				}

				if ($debug)
				{
					print "<h5>(even) sum_low_s = $row_sum[low] / sum_mid_s = $row_sum[mid] / sum_high_s = $row_sum[high] - row_sum = $row[even]</h5>";
				}


				# -------------------------------------------------------------------------------------------------------
				
				if ($row[even] < $even_low || $row[even] > $even_high) #------------------------
				{
					print("<TD bgcolor=\"#FF0000\" align=center><font color=\"#ffffff\"><b>$row[even]</b></font></TD>\n");
					$even_a_count++;
				} else {
					print("<TD align=center>$row[even]</TD>\n");
				}	
				if ($row[odd] < $odd_low || $row[odd] > $odd_high) #------------------------
				{
					print("<TD bgcolor=\"#FF0000\" align=center><font color=\"#ffffff\"><b>$row[odd]</b></font></TD>\n");
					$odd_a_count++;
				} else {
					print("<TD align=center>$row[odd]</TD>\n");
				}	
				if ($row[even] < $even_low_d || $row[even] > $even_high_d) #------------------------
				{
					print("<TD bgcolor=\"#FF0000\" align=center><font color=\"#ffffff\"><b>$row[even]</b></font></TD>\n");
					$sum_d_count++;
				} else {
					print("<TD align=center>$row[even]</TD>\n");
				}		
				if ($row[even] < $even_low_s || $row[even] > $even_high_s) #------------------------
				{
					print("<TD bgcolor=\"#FF0000\" align=center><font color=\"#ffffff\"><b>$row[even]</b></font></TD>\n");
					$sum_s_count++;
				} else {
					print("<TD align=center>$row[even]</TD>\n");
				}		
				print("</TR>\n");
			}
			
			$dcount++;
			$z++;
		}

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

		print("<TD BGCOLOR=\"#CCCCCC\">Even/0</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Odd/0</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Even/d</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Odd/d</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Even/x</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Odd/x</TD>\n");
		print("</B></TR>\n");
	
		//end table
		print("</TABLE>\n");

		$temp_result = ($sum_a_count/$limit)*100;

		echo "<h3><font color=\"#ff0000\">Sum/0 Failed - $temp_result %</font></h3>";

		$temp_result = ($sum_d_count/$limit)*100;

		echo "<h3><font color=\"#ff0000\">Sum/d Failed - $temp_result %</font></h3>";

		$temp_result = ($sum_s_count/$limit)*100;

		echo "<h3><font color=\"#ff0000\">Sum/s Failed - $temp_result %</font></h3>";
	}

	// ----------------------------------------------------------------------------------
	function lot_d501_d502 ($limit)
	{ 
		global $debug, $game, $game_name, $balls, $balls_drawn, $mega_balls, $draw_table_name, $draw_prefix, $game_includes, $hml, $range_low, $range_high;
	
		require ("includes/mysqli.php");

		require ("$game_includes/config.incl");

		require ("includes/hml_switch.incl");

		$sum_a_count = 0;
		$sum_d_count = 0;
		$sum_s_count = 0;
		
		print("<a name=\"$limit\"><H2>D501/D502 - $game_name - Limit = $limit</H2></a>\n");

		print "<h4><a href=\"#unsorted$limit\">Unsorted $limit</a> | <a href=\"#sorted$limit\">Sorted $limit</a> | <a href=\"#pairs$limit\">Pairs $limit</a> | <a href=\"#rank$limit\">Rank $limit</a> | <a href=\"#grid$limit\">Grid $limit</a></h4>";
		
		// initalize variables [include]
		require ("includes/init_display.incl");
		
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
		
		if ($mega_balls)
		{
			print("<TD BGCOLOR=\"#CCCCCC\">PB</TD>\n");
		}

		print("<TD BGCOLOR=\"#CCCCCC\">D501/0</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">D502/0</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">D501/d</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">D502/d</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">D501/x</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">D502/x</TD>\n");
		print("</B></TR>\n");
	
		$dcount = 1;
		$num_array = array_fill (0,60,0);
		
		$num_array_count = array_fill (0,60,$num_array);
		$pb_array_count = array_fill (0,60,$num_array);
		$prev_date = array_fill (0,60,'1962-08-17');

		$draw_count_temp_array = array_fill (0,16,0);
		$draw_count_array = array_fill (0,60,$draw_count_temp_array);
			
		// get from draw table
		$query5 = "SELECT * FROM $draw_table_name ";
		if ($hml)
		{
			$query5 .= "WHERE sum >= $range_low  ";
			$query5 .= "AND   sum <= $range_high  ";
		}
		$query5 .= "ORDER BY date DESC ";
		$query5 .= "LIMIT $limit "; 

		##echo "$query5<br>";
	
		$mysqli_result5 = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));

		$z = 1;
		$pb_array = array_fill (0,50,0);
		$pb_date = array_fill (0,50,'1962-08-17');

		#initialize date variables
		require ("includes/unix.incl");
	
		// get each row
		while($row = mysqli_fetch_array($mysqli_result5))
		{
			#echo "row[sum] = $row[sum]<p>";

			// build draw array [0..x]
			$draw = array ();
	
			for ($x = 1; $x <= $balls_drawn; $x++)
			{
				array_push($draw, $row[$x]);
			}

			sort($draw);
	
			if ($mega_balls)
			{
				$pb = $row[pb];
			}

			$modulus10 = $dcount % 100;

			if ($modulus10 == 1 || $limit < 100) 
			{
				print("<TR>\n");
		
				print("<TD align=center><b>$dcount</b></TD>\n");
				print("<TD>$row[date]</TD>\n");
		
				for($index = 0; $index < $balls_drawn; $index++)
				{
					print("<TD align=center>$draw[$index]</TD>\n");
				}
				
				if ($mega_balls)
				{
					print("<TD align=center><b>$row[pb]</b></TD>\n");
				}

				# -------------------------------------------------------------------------------------------------------
				# ----------  Sum Limit All -----------------
				# -------------------------------------------------------------------------------------------------------
				$query_sum = "SELECT * FROM $draw_prefix";
				$query_sum .= "filter_limits ";
				$query_sum .= "WHERE col1 = '$row[b1]' ";
				$query_sum .= "AND limit_type = 'sm' ";
				$query_sum .= "AND hml = '0' ";
				$query_sum .= "AND date = '$row[date]' ";
				$query_sum .= "ORDER BY id DESC ";
			
				$mysqli_result_sum = mysqli_query($query_sum, $mysqli_link) or die (mysqli_error($mysqli_link));
			
				$row_sum = mysqli_fetch_array($mysqli_result_sum);
		
				$num_rows_sum = mysqli_num_rows($mysqli_result_sum);
				
				if ($num_rows_sum)
				{
					$sum_low = $row_sum[low];
					$sum_high = $row_sum[high];
				} else {
					#echo "$row[date] -- num_rows_sum = $num_rows_sum<p>";

					$hml = 0;

					require ("includes/hml_switch.incl");

					lot_filter_limit_sum ($row[date]);

					$query_sum = "SELECT * FROM $draw_prefix";
					$query_sum .= "filter_limits ";
					$query_sum .= "WHERE col1 = '$row[b1]' ";
					$query_sum .= "AND limit_type = 'sm' ";
					$query_sum .= "AND hml = '0' ";
					$query_sum .= "AND date = '$row[date]' ";
					$query_sum .= "ORDER BY id DESC ";
				
					$mysqli_result_sum = mysqli_query($query_sum, $mysqli_link) or die (mysqli_error($mysqli_link));
				
					$row_sum = mysqli_fetch_array($mysqli_result_sum);

					$sum_low = $row_sum[low];
					$sum_high = $row_sum[high];
				}

				if ($debug)
				{
					print "<h5>(all/0) sum_low = $row_sum[low] / sum_mid = $row_sum[mid] / sum_high = $row_sum[high] - row_sum = $row[sum]</h5>";
				}

				# -------------------------------------------------------------------------------------------------------

				# -------------------------------------------------------------------------------------------------------
				# ----------  Sum Limit Decade -----------------
				# -------------------------------------------------------------------------------------------------------
				$temp_decade = (intval($row[sum]/10))*10;
				
				$query_sum = "SELECT * FROM $draw_prefix";
				$query_sum .= "filter_limits ";
				$query_sum .= "WHERE col1 = '$row[b1]' ";
				$query_sum .= "AND limit_type = 'sm' ";
				$query_sum .= "AND hml = '$temp_decade' ";
				$query_sum .= "AND date = '$row[date]' ";
				$query_sum .= "ORDER BY id DESC ";

				##echo "$query_sum<p>";
			
				$mysqli_result_sum = mysqli_query($query_sum, $mysqli_link) or die (mysqli_error($mysqli_link));
			
				$row_sum = mysqli_fetch_array($mysqli_result_sum);
		
				$num_rows_sum = mysqli_num_rows($mysqli_result_sum);
				
				if ($num_rows_sum)
				{
					$sum_low_d = $row_sum[low];
					$sum_high_d = $row_sum[high];
				} else {
					#echo "$row[date] -- num_rows_sum = $num_rows_sum<p>";

					$hml = $temp_decade;

					require ("includes/hml_switch.incl");

					lot_filter_limit_sum ($row[date]);

					$query_sum = "SELECT * FROM $draw_prefix";
					$query_sum .= "filter_limits ";
					$query_sum .= "WHERE col1 = '$row[b1]' ";
					$query_sum .= "AND limit_type = 'sm' ";
					$query_sum .= "AND hml = '$temp_decade' ";
					$query_sum .= "AND date = '$row[date]' ";
					$query_sum .= "ORDER BY id DESC ";
				
					$mysqli_result_sum = mysqli_query($query_sum, $mysqli_link) or die (mysqli_error($mysqli_link));
				
					$row_sum = mysqli_fetch_array($mysqli_result_sum);

					$sum_low_d = $row_sum[low];
					$sum_high_d = $row_sum[high];
				}

				if ($debug)
				{
					print "<h5>(decade) sum_low_d = $row_sum[low] / sum_mid_d = $row_sum[mid] / sum_high_d = $row_sum[high] - row_sum = $row[sum]</h5>";
				}

				# -------------------------------------------------------------------------------------------------------

				# -------------------------------------------------------------------------------------------------------
				# ----------  Sum Limit Sum -----------------
				# -------------------------------------------------------------------------------------------------------	
				$hml = $row[sum] + 500;
				
				$query_sum = "SELECT * FROM $draw_prefix";
				$query_sum .= "filter_limits ";
				$query_sum .= "WHERE col1 = '$row[b1]' ";
				$query_sum .= "AND limit_type = 'sm' ";
				$query_sum .= "AND hml = '$hml' ";
				$query_sum .= "AND date = '$row[date]' ";
				$query_sum .= "ORDER BY id DESC ";

				##echo "$query_sum<p>";
			
				$mysqli_result_sum = mysqli_query($query_sum, $mysqli_link) or die (mysqli_error($mysqli_link));
			
				$row_sum = mysqli_fetch_array($mysqli_result_sum);
		
				$num_rows_sum = mysqli_num_rows($mysqli_result_sum);
				
				if ($num_rows_sum)
				{
					$sum_low_s = $row_sum[low];
					$sum_high_s = $row_sum[high];
				} else {
					#echo "$row[date] -- num_rows_sum = $num_rows_sum<p>";

					require ("includes/hml_switch.incl");

					lot_filter_limit_sum ($row[date]);

					$query_sum = "SELECT * FROM $draw_prefix";
					$query_sum .= "filter_limits ";
					$query_sum .= "WHERE col1 = '$row[b1]' ";
					$query_sum .= "AND limit_type = 'sm' ";
					$query_sum .= "AND hml = '$hml' ";
					$query_sum .= "AND date = '$row[date]' ";
					$query_sum .= "ORDER BY id DESC ";

					##echo "$query_sum<p>";
				
					$mysqli_result_sum = mysqli_query($query_sum, $mysqli_link) or die (mysqli_error($mysqli_link));
				
					$row_sum = mysqli_fetch_array($mysqli_result_sum);

					$sum_low_s = $row_sum[low];
					$sum_high_s = $row_sum[high];
				}

				if ($debug)
				{
					print "<h5>(sum) sum_low_s = $row_sum[low] / sum_mid_s = $row_sum[mid] / sum_high_s = $row_sum[high] - row_sum = $row[sum]</h5>";
				}

				# -------------------------------------------------------------------------------------------------------
				
				if ($row[sum] < $sum_low || $row[sum] > $sum_high) #------------------------
				{
					print("<TD bgcolor=\"#FF0000\" align=center><font color=\"#ffffff\"><b>$row[sum]</b></font></TD>\n");
					$sum_a_count++;
				} else {
					print("<TD align=center>$row[sum]</TD>\n");
				}			
				if ($row[sum] < $sum_low_d || $row[sum] > $sum_high_d) #------------------------
				{
					print("<TD bgcolor=\"#FF0000\" align=center><font color=\"#ffffff\"><b>$row[sum]</b></font></TD>\n");
					$sum_d_count++;
				} else {
					print("<TD align=center>$row[sum]</TD>\n");
				}		
				if ($row[sum] < $sum_low_s || $row[sum] > $sum_high_s) #------------------------
				{
					print("<TD bgcolor=\"#FF0000\" align=center><font color=\"#ffffff\"><b>$row[sum]</b></font></TD>\n");
					$sum_s_count++;
				} else {
					print("<TD align=center>$row[sum]</TD>\n");
				}		
				print("</TR>\n");
			}
			
			$dcount++;
			$z++;
		}

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

		print("<TD BGCOLOR=\"#CCCCCC\">Sum/0</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Sum/d</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Sum/x</TD>\n");
		print("</B></TR>\n");
	
		//end table
		print("</TABLE>\n");

		$temp_result = ($sum_a_count/$limit)*100;

		echo "<h3><font color=\"#ff0000\">Sum/0 Failed - $temp_result %</font></h3>";

		$temp_result = ($sum_d_count/$limit)*100;

		echo "<h3><font color=\"#ff0000\">Sum/d Failed - $temp_result %</font></h3>";

		$temp_result = ($sum_s_count/$limit)*100;

		echo "<h3><font color=\"#ff0000\">Sum/s Failed - $temp_result %</font></h3>";
	}

	// ----------------------------------------------------------------------------------
	function lot_summary ()
	{ 
		global $debug, $game, $game_name, $balls, $balls_drawn, $mega_balls, $draw_table_name, $draw_prefix, $game_includes, $hml, $range_low, $range_high;
	
		require ("includes/mysqli.php");

		require ("$game_includes/config.incl");

		require ("includes/hml_switch.incl");

		print "<p><b>Combo Count Summary</b></p>";

		print("<table border=\"1\">\n");

		print("<tr>\n");
			print("<TD WIDTH=20 BGCOLOR=\"#CCCCCC\" align=center align=center>Col1</TD>\n");
			print("<TD WIDTH=20 BGCOLOR=\"#CCCCCC\" align=center align=center>2</TD>\n");
			print("<TD WIDTH=20 BGCOLOR=\"#CCCCCC\" align=center align=center>3</TD>\n");
			print("<TD WIDTH=20 BGCOLOR=\"#CCCCCC\" align=center align=center>4</TD>\n");
			print("<TD WIDTH=20 BGCOLOR=\"#CCCCCC\" align=center align=center>5</TD>\n");
			print("<TD WIDTH=20 BGCOLOR=\"#CCCCCC\" align=center>Count</TD>\n");
		print("</tr>\n");

		for ($col1 = 1; $col1 <= 10; $col1++)
		{
			for ($k = 9; $k <= 10; $k++)
			{
				print("<tr>\n");
					$query_dup = "SELECT * FROM combo_5_39 ";
					$query_dup .= "WHERE comb2 = $k ";
					$query_dup .= "AND b1 = $col1 "; 

					$mysqli_result_dup = mysqli_query($query_dup, $mysqli_link) or die (mysqli_error($mysqli_link));

					$num_rows = mysqli_num_rows($mysqli_result_dup); 
					
					print("<TD align=center align=center>$col1</TD>\n");
					print("<TD align=center align=center>$k</TD>\n");
					print("<TD align=center align=center>&nbsp;</TD>\n");
					print("<TD align=center align=center>&nbsp;</TD>\n");
					print("<TD align=center align=center>&nbsp;</TD>\n");
					if ($num_rows > 8)
					{
						print("<TD align=center align=center BGCOLOR=\"#ff0000\"><b>$num_rows</b></TD>\n");
					} else {
						print("<TD align=center align=center>$num_rows</TD>\n");
					}
				print("</tr>\n");
			}

			for ($j = 10; $j <= 10; $j++)
			{
				for ($k = 0; $k <= 10; $k++)
				{
					print("<tr>\n");
						$query_dup = "SELECT * FROM combo_5_39 ";
						$query_dup .= "WHERE comb2 = $j ";
						$query_dup .= "AND   comb3 = $k ";
						$query_dup .= "AND   b1 = $col1 ";

						$mysqli_result_dup = mysqli_query($query_dup, $mysqli_link) or die (mysqli_error($mysqli_link));

						$num_rows = mysqli_num_rows($mysqli_result_dup); 
						
						print("<TD align=center align=center>$col1</TD>\n");
						print("<TD align=center align=center>$j</TD>\n");
						print("<TD align=center align=center>$k</TD>\n");
						print("<TD align=center align=center>&nbsp;</TD>\n");
						print("<TD align=center align=center>&nbsp;</TD>\n");
						if ($num_rows > 4)
						{
							print("<TD align=center align=center BGCOLOR=\"#ff0000\"><b>$num_rows</b></TD>\n");
						} else {
							print("<TD align=center align=center>$num_rows</TD>\n");
						}
					print("</tr>\n");
				}
			}

			for ($i = 10; $i <= 10; $i++)
			{
				for ($j = 3; $j <= 10; $j++)
				{
					for ($k = 0; $k <= 5; $k++)
					{
						print("<tr>\n");
							$query_dup = "SELECT * FROM combo_5_39 ";
							$query_dup .= "WHERE comb2 = $i ";
							$query_dup .= "AND   comb3 = $j ";
							$query_dup .= "AND   comb4 = $k ";
							$query_dup .= "AND   b1 = $col1 ";

							$mysqli_result_dup = mysqli_query($query_dup, $mysqli_link) or die (mysqli_error($mysqli_link));

							$num_rows = mysqli_num_rows($mysqli_result_dup); 
							
							print("<TD align=center align=center>$col1</TD>\n");
							print("<TD align=center align=center>$i</TD>\n");
							print("<TD align=center align=center>$j</TD>\n");
							print("<TD align=center align=center>$k</TD>\n");
							print("<TD align=center align=center>&nbsp;</TD>\n");
							if ($num_rows > 2)
							{
								print("<TD align=center align=center BGCOLOR=\"#ff0000\"><b>$num_rows</b></TD>\n");
							} else {
								print("<TD align=center align=center>$num_rows</TD>\n");
							}
						print("</tr>\n");
					}
				}
			}
		}
		
		print("</table>\n");

	}

	///////////////////////////////////////////////////////////////////////////////////////////////
	
	//start HTML page
	print("<HTML>\n");
	print("<HEAD>\n");
	print("<TITLE>$game_name DNA</TITLE>\n");
	print("</HEAD>\n");

	print("<BODY>\n");
	print("<h1>$game_name DNA</h1>\n");
	
	$curr_date = date("Ymd");
	$curr_date_dash = date("Y-m-d");
	/*
	$hml = 0;
	
	lot_display (26);

	$hml = 0;

	#lot_display (14);

	$hml = 0;

	#lot_display (7);

	$hml = 0;
	
	lot_sum (26);

	$hml = 0;

	lot_balls (26);
	*/
	$hml = 0;

	lot_combo (26,2,1);
	
	$hml = 0;

	#lot_even_odd (26);

	$hml = 0;

	#lot_d501_d502 (26);
	
	#lot_summary ();

	// **************************************************************************

	//close page
	print("</BODY>\n");
	print("</HTML>\n");

?>