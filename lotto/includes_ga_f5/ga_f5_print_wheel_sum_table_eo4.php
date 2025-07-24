<?php
	function ga_f5_print_wheel_sum_table_eo4($limit)
	{
		global $debug, $draw_table_name, $draw_prefix, $balls, $balls_drawn, $game; 

		require ("includes/mysqli.php");
		require ("includes/unix.incl");

		$query4 = "DROP TABLE IF EXISTS $draw_prefix";
		$query4 .= "wheel_sum_table_eo4 ";

		#print "$query4<p>";

		$mysqli_result4 = mysqli_query($mysqli_link, $query4) or die (mysqli_error($mysqli_link));

		$query4 = "CREATE TABLE $draw_prefix";
		$query4 .= "wheel_sum_table_eo4 (";
		$query4 .= " id int(10) unsigned NOT NULL auto_increment, ";
		$query4 .= "`sum` int(3) unsigned NOT NULL,";
		$query4 .= "`sum_count` int(6) unsigned NOT NULL default '0',";
		$query4 .= "`eo40` tinyint(3) unsigned NOT NULL default '0',";
		$query4 .= "`even` tinyint(3) unsigned NOT NULL default '0',";
		$query4 .= "`odd` tinyint(3) unsigned NOT NULL default '0',";
		$query4 .= "`d1` tinyint(3) unsigned NOT NULL,";
		$query4 .= "`d2` tinyint(3) unsigned NOT NULL,";
		$query4 .= "`d3` tinyint(3) unsigned NOT NULL,";
		$query4 .= "`d4` tinyint(3) unsigned NOT NULL,";
		$query4 .= "day1 tinyint(3) unsigned NOT NULL default '0', ";
		$query4 .= "week1 tinyint(3) unsigned NOT NULL default '0', ";
		$query4 .= "week2 tinyint(3) unsigned NOT NULL default '0', ";
		$query4 .= "month1 tinyint(3) unsigned NOT NULL default '0', ";
		$query4 .= "month3 tinyint(3) unsigned NOT NULL default '0', ";
		$query4 .= "month6 tinyint(3) unsigned NOT NULL default '0', ";
		$query4 .= "year1 tinyint(3) unsigned NOT NULL default '0', ";
		$query4 .= "year2 tinyint(3) unsigned NOT NULL default '0', ";
		$query4 .= "year3 tinyint(3) unsigned NOT NULL default '0', ";
		
		$query4 .= "year4 tinyint(3) unsigned NOT NULL default '0', ";
		$query4 .= "d1510 tinyint(3) unsigned NOT NULL default '0', ";
		$query4 .= "year5 tinyint(3) unsigned NOT NULL default '0', ";
		$query4 .= "year6 tinyint(3) unsigned NOT NULL default '0', ";
		$query4 .= "year7 tinyint(3) unsigned NOT NULL default '0', ";
		$query4 .= "year8 tinyint(3) unsigned NOT NULL default '0', ";
		$query4 .= "year9 tinyint(3) unsigned NOT NULL default '0', ";
		$query4 .= "year10 tinyint(3) unsigned NOT NULL default '0', ";
		$query4 .= "count int(5) unsigned NOT NULL default '0', ";
		$query4 .= "sigma int(5) unsigned NOT NULL default '0', ";
		$query4 .= "percent_1 float(4,2) unsigned NOT NULL default '0', ";
		$query4 .= "percent_5 float(4,2) unsigned NOT NULL default '0', ";
		$query4 .= "percent_10 float(4,2) unsigned NOT NULL default '0', ";
		$query4 .= "wa float(4,2) unsigned NOT NULL default '0', ";
		$query4 .= "previous_date date NOT NULL default '1962-08-17', ";
		$query4 .= "last_date date NOT NULL default '1962-08-17', ";
		$query4 .= "PRIMARY KEY  (`id`)";
		$query4 .= ") ";

		#print "$query4<p>";
	
		$mysqli_result4 = mysqli_query($mysqli_link, $query4) or die (mysqli_error($mysqli_link));

		$sum_table_date = array_fill (0,265,"1962-08-17");
		$sum_table_date_prev = array_fill (0,265,"1962-08-17");
		$sum_table = array_fill (0,265,0);

		//start table
		print("<h3>Wheel Sum Table - $limit</h3>\n");
		print("<TABLE BORDER=\"1\">\n");

		$z = 0;
		$b_switch = 0;
		$row_count_break = 0;
	
		$low = 60; #180828	
		$high = 149;

		for ($w = $low; $w <= $high; $w++)
		{
			$query_eo40 = "SELECT DISTINCT a.even, a.odd, b.d1, b.d2, b.d3, b.d4 FROM $draw_table_name a ";
			$query_eo40 .= "JOIN $draw_prefix";
			$query_eo40 .= "draws_draw4 b "; #change when building
			$query_eo40 .= "ON a.date = b.date ";
			$query_eo40 .= "WHERE a.even > 0 ";
			$query_eo40 .= "AND   a.even < 5 ";
			$query_eo40 .= "ORDER BY b.d1 ASC, b.d2 ASC, b.d3 ASC, b.d4 ASC ";

			#print "<h3>$query_eo40</h3><p>";
		
			$mysqli_result_eo40 = mysqli_query($mysqli_link, $query_eo40) or die (mysqli_error($mysqli_link));

			print "<TR>\n";
			print "<TD BGCOLOR=\"#CCCCCC\" align=\"center\">Sum</TD>\n";
			print "<TD BGCOLOR=\"#CCCCCC\" align=\"center\">eo40</TD>\n";
			print "<TD BGCOLOR=\"#CCCCCC\" align=\"center\">Even</TD>\n";
			print "<TD BGCOLOR=\"#CCCCCC\" align=\"center\">Odd</TD>\n";
			print "<TD BGCOLOR=\"#CCCCCC\" align=\"center\">d4_1</TD>\n";
			print "<TD BGCOLOR=\"#CCCCCC\" align=\"center\">d4_2</TD>\n";
			print "<TD BGCOLOR=\"#CCCCCC\" align=\"center\">d4_3</TD>\n";
			print "<TD BGCOLOR=\"#CCCCCC\" align=\"center\">d4_4</TD>\n";
			print "<TD BGCOLOR=\"#CCCCCC\" align=\"center\">Rows</TD>\n";
			print "<TD BGCOLOR=\"#CCCCCC\"><center>Last</center></TD>\n";
			print "<TD BGCOLOR=\"#CCCCCC\"><center>Week1</center></TD>\n";
			print "<TD BGCOLOR=\"#CCCCCC\"><center>Week2</center></TD>\n";
			print "<TD BGCOLOR=\"#CCCCCC\"><center>Month1</center></TD>\n";
			print "<TD BGCOLOR=\"#CCCCCC\"><center>Month3</center></TD>\n";
			print "<TD BGCOLOR=\"#CCCCCC\"><center>Month6</center></TD>\n";
			print "<TD BGCOLOR=\"#CCCCCC\"><center>Year1</center></TD>\n";
			
			print "<TD BGCOLOR=\"#CCCCCC\"><center>Year2</center></TD>\n";
			print "<TD BGCOLOR=\"#CCCCCC\"><center>Year3</center></TD>\n";
			
			print "<TD BGCOLOR=\"#CCCCCC\"><center>Year4</center></TD>\n";
			print "<TD BGCOLOR=\"#CCCCCC\"><center>D1510</center></TD>\n"; 
			print "<TD BGCOLOR=\"#CCCCCC\"><center>Year5</center></TD>\n";
			print "<TD BGCOLOR=\"#CCCCCC\"><center>Year6</center></TD>\n";
			print "<TD BGCOLOR=\"#CCCCCC\"><center>Year7</center></TD>\n";
			print "<TD BGCOLOR=\"#CCCCCC\"><center>Year8</center></TD>\n";
			print "<TD BGCOLOR=\"#CCCCCC\"><center>Year9</center></TD>\n";
			print "<TD BGCOLOR=\"#CCCCCC\"><center>Year10</center></TD>\n";
			print "<TD BGCOLOR=\"#CCCCCC\"><center>Total</center></TD>\n";
			print "<TD BGCOLOR=\"#CCCCCC\"><center>Prev</center></TD>\n";
			print "<TD BGCOLOR=\"#CCCCCC\"><center>Last</center></TD>\n";
			print "<TD BGCOLOR=\"#CCCCCC\"><center>Sigma</center></TD>\n";
			print "<TD BGCOLOR=\"#CCCCCC\"><center>wa1</center></TD>\n";
			print "<TD BGCOLOR=\"#CCCCCC\"><center>wa5</center></TD>\n";
			print "<TD BGCOLOR=\"#CCCCCC\"><center>wa</center></TD>\n";
			print("<TD BGCOLOR=\"#CCCCCC\"><center>wa</center></TD>\n");
			print "</TR>\n";

			while($row_eo40 = mysqli_fetch_array($mysqli_result_eo40))
			{					
				$sum_count_array = array_fill (0,17,0);

				$query_draw = "SELECT * FROM $draw_table_name a ";
				$query_draw .= "JOIN $draw_prefix";
				$query_draw .= "draws_draw4 b ";
				$query_draw .= "ON a.date = b.date ";
				$query_draw .= "AND a.even = $row_eo40[even] ";
				$query_draw .= "AND a.odd  = $row_eo40[odd] ";
				$query_draw .= "AND b.d1 = $row_eo40[d1] ";
				$query_draw .= "AND b.d2 = $row_eo40[d2] ";
				$query_draw .= "AND b.d3 = $row_eo40[d3] ";
				$query_draw .= "AND b.d4 = $row_eo40[d4] ";
				$query_draw .= "WHERE a.sum = $w ";
$query_draw .= "AND a.date >= '2015-10-01' ";
				$query_draw .= "ORDER BY a.date DESC ";
				$query_draw .= "LIMIT $limit ";

				#echo "$query_draw<p>";
			
				$mysqli_result_draw = mysqli_query($mysqli_link, $query_draw) or die (mysqli_error($mysqli_link));

				$num_rows_all_draw = mysqli_num_rows($mysqli_result_draw);

				# loop draws
				while($row_draw = mysqli_fetch_array($mysqli_result_draw))
				{
					$sum_table[$row[0]]++;
					
					$draw_date_array = explode("-","$row_draw[0]"); ### 210104
						$draw_date_unix = mktime (0,0,0,$draw_date_array[1],$draw_date_array[2],$draw_date_array[0]); ### 210104

					#$x = $row_date[sum];
					
					# calculate history
			if ($draw_date_unix == $day1_unix)
			{ 
				for ($d = 0; $d <= 16; $d++) {$draw_count_array[$y][$d]++;}
			} elseif ($draw_date_unix > $week1_unix) {
				for ($d = 1; $d <= 16; $d++) {$draw_count_array[$y][$d]++;}
			} elseif ($draw_date_unix > $week2_unix) {
				for ($d = 2; $d <= 16; $d++) {$draw_count_array[$y][$d]++;}
			} elseif ($draw_date_unix > $month1_unix) {
				for ($d = 3; $d <= 16; $d++) {$draw_count_array[$y][$d]++;}
			} elseif ($draw_date_unix > $month3_unix) {
				for ($d = 4; $d <= 16; $d++) {$draw_count_array[$y][$d]++;}
			} elseif ($draw_date_unix > $month6_unix) {
				for ($d = 5; $d <= 16; $d++) {$draw_count_array[$y][$d]++;}
			} elseif ($draw_date_unix > $year1_unix) {
				for ($d = 6; $d <= 16; $d++) {$draw_count_array[$y][$d]++;}
			} elseif ($draw_date_unix > $year2_unix) {
				for ($d = 7; $d <= 16; $d++) {$draw_count_array[$y][$d]++;}
			} elseif ($draw_date_unix > $d1510_unix) {
				for ($d = 8; $d <= 16; $d++) {$draw_count_array[$y][$d]++;}
			} elseif ($draw_date_unix > $year3_unix) {
				for ($d = 9; $d <= 16; $d++) {$draw_count_array[$y][$d]++;}
			} elseif ($draw_date_unix > $year4_unix) {
				for ($d = 10; $d <= 16; $d++) {$draw_count_array[$y][$d]++;}
			} elseif ($draw_date_unix > $year5_unix) {
				for ($d = 11; $d <= 16; $d++) {$draw_count_array[$y][$d]++;}
			} elseif ($draw_date_unix > $year6_unix) {
				for ($d = 12; $d <= 16; $d++) {$draw_count_array[$y][$d]++;}
			} elseif ($draw_date_unix > $year7_unix) {
				for ($d = 13; $d <= 16; $d++) {$draw_count_array[$y][$d]++;}
			} elseif ($draw_date_unix > $year8_unix) {
				for ($d = 14; $d <= 16; $d++) {$draw_count_array[$y][$d]++;}
			} elseif ($draw_date_unix > $year9_unix) {
				for ($d = 15; $d <= 16; $d++) {$draw_count_array[$y][$d]++;}
			} elseif ($draw_date_unix > $year10_unix) {
				for ($d = 16; $d <= 16; $d++) {$draw_count_array[$y][$d]++;}
			}

			#add 1 year to clear
			if ($first_draw_unix > $year7_unix) {
				for ($d = 14; $d <= 16; $d++) {$draw_count_array[$y][$d]=0;}
			} elseif ($first_draw_unix > $year8_unix) {
				for ($d = 15; $d <= 16; $d++) {$draw_count_array[$y][$d]=0;}
			} elseif ($first_draw_unix > $year9_unix) {
				for ($d = 16; $d <= 16; $d++) {$draw_count_array[$y][$d]=0;}
			} elseif ($first_draw_unix > $year1_unix) {
				for ($d = 17; $d <= 17; $d++) {$draw_count_array[$y][$d]=0;}
			}
			
					$sum_count_array[17]++;
					/*
					#add 1 year to clear
					if ($first_draw_unix > $year7) {
						for ($d = 13; $d <= 16; $d++) {$sum_count_array[$d]=0;}
					} elseif ($first_draw_unix > $year8) {
						for ($d = 14; $d <= 16; $d++) {$sum_count_array[$d]=0;}
					} elseif ($first_draw_unix > $year9) {
						for ($d = 15; $d <= 16; $d++) {$sum_count_array[$d]=0;}
					} elseif ($first_draw_unix > $year10) {
						for ($d = 17; $d <= 17; $d++) {$sum_count_array[$d]=0;}
					}
					*/ #170615
					$z++;
				}

				$num_rows = 0;

				$query_rows = "SELECT count(*) FROM combo_5_42_dr6 ";
				$query_rows .= "WHERE sum = $w ";;
				$query_rows .= "AND d1 = $row_draw[d1] ";
				$query_rows .= "AND d2 = $row_draw[d2] ";
				$query_rows .= "AND d3 = $row_draw[d3] ";
				$query_rows .= "AND d4 = $row_draw[d4] ";

				#echo "$query_rows<p>";

				#$mysqli_result_rows = mysqli_query($mysqli_link, $query_rows) or die (mysqli_error($mysqli_link));

				#$row_count = mysqli_fetch_array($mysqli_result_rows);

				$num_rows = 0;

				if ($sum_count_array[16] > 1) #180903
				{
					print("<TR>\n");
					print("<TD align=center><center>$w</center></TD>\n");
					print("<TD align=center><center>$row_eo40[id]</center></TD>\n");
					print("<TD align=center><center>$row_eo40[even]</center></TD>\n");
					print("<TD align=center><center>$row_eo40[odd]</center></TD>\n");
					print("<TD align=center><center>$row_eo40[d1]</center></TD>\n");
					print("<TD align=center><center>$row_eo40[d2]</center></TD>\n");
					print("<TD align=center><center>$row_eo40[d3]</center></TD>\n");
					print("<TD align=center><center>$row_eo40[d4]</center></TD>\n");

					print("<TD align=center><center>$num_rows</center></TD>\n");
					for ($d = 0; $d <= 16; $d++) 
					{
						if ($sum_count_array[$d] > 10)
						{
							print("<TD bgcolor=\"#FF0033\" align=center>{$sum_count_array[$d]}</TD>\n");
						} elseif ($sum_count_array[$d] > 7) {
							print("<TD bgcolor=\"#CCFFFF\" align=center>{$sum_count_array[$d]}</TD>\n");
						} elseif ($sum_count_array[$d] > 1) {
							print("<TD bgcolor=\"#CCFF66\" align=center>{$sum_count_array[$d]}</TD>\n");
						} elseif ($sum_count_array[$d] == 1) {
							print("<TD bgcolor=\"#F1F1F1\" align=center>1</TD>\n");
						} else {
							print("<TD align=center>-</TD>\n");
						}

						$sub_sum[$d] += $sum_count_array[$d];
					}

					if ($sum_count_array[17] > 10)
					{
						print("<TD bgcolor=\"#FF0000\" align=center><font color=\"#ffffff\"><b>{$sum_count_array[17]}</b></font></TD>\n");
					} else {
						print("<TD align=center>{$sum_count_array[17]}</TD>\n");
					}

					# get dates
					$query_date = "SELECT * FROM $draw_table_name ";
					$query_date .= "WHERE sum = $w ";
					$query_date .= "AND even = $row_eo40[even] ";
					$query_date .= "AND odd = $row_eo40[odd] ";
					$query_date .= "AND d4_1 = $row_eo40[d1] ";
					$query_date .= "AND d4_2 = $row_eo40[d2] ";
					$query_date .= "AND d4_3 = $row_eo40[d3] ";
					$query_date .= "AND d4_4 = $row_eo40[d4] ";
					$query_date .= "date <= '2015-10-01' ";
					$query_date .= "ORDER BY date DESC ";
					$query_date .= "LIMIT $limit ";
				
					#$mysqli_result_date = mysqli_query($query_date, $mysqli_link) or die (mysqli_error($mysqli_link));

					#$row_date = mysqli_fetch_array($mysqli_result_date);

					#$row_date_prev = mysqli_fetch_array($mysqli_result_date);

					if ($row_date_prev[date] == "1962-08-17") {
						print("<TD align=center nowrap><center>-</center></TD>\n");
					} elseif ($row_date_prev[date] < "2006-01-01") {
						print("<TD align=center nowrap><FONT COLOR=\"#ff0000\">$row_date_prev[date]</FONT></TD>\n");
					} elseif ($row_date_prev[date] < "2007-01-01") {
						print("<TD align=center nowrap><FONT COLOR=\"#ff6600\">$row_date_prev[date]</FONT></TD>\n");
					} else {
						print("<TD align=center nowrap><FONT COLOR=\"#000000\">$row_date_prev[date]</FONT></TD>\n");
					}

					if ($row_date[date] == "1962-08-17") {
						print("<TD align=center nowrap><center>-</center></TD>\n");
					} elseif ($row_date[date] < "2006-01-01") {
						print("<TD nowrap><FONT COLOR=\"#ff0000\" align=center>$row_date[date]</FONT></TD>\n");
					} elseif ($row_date[date] < "2007-01-01") {
						print("<TD nowrap><FONT COLOR=\"#ff6600\" align=center>$row_date[date]</FONT></TD>\n");
					} else {
						print("<TD nowrap><FONT COLOR=\"#000000\" align=center>$row_date[date]</FONT></TD>\n");
					}

					$sigma = 0;

					for ($d = 0; $d <= 16; $d++) 
					{
						$sigma += $sum_count_array[$d];
					} 

					print("<TD align=center>$sigma</TD>\n");

					$sum_temp_y1 = number_format(($sum_count_array[6]/365)*100,1);

					if ($sum_temp_y1 >= 0.5)
					{
						print("<TD align=right align=center nowrap><font size=\"-1\"><b>$sum_temp_y1 %</b></font></TD>\n");
					} else {
						print("<TD align=right align=center nowrap><font size=\"-1\">$sum_temp_y1 %</font></TD>\n");
					}

					$sum_temp_y5 = number_format(($sum_count_array[11]/(365*5))*100,1);

					if ($sum_temp_y5 >= 0.5)
					{
						print("<TD align=right align=center nowrap><font size=\"-1\"><b>$sum_temp_y5 %</b></font></TD>\n");
					} else {
						print("<TD align=right align=center nowrap><font size=\"-1\">$sum_temp_y5 %</font></TD>\n");
					}

					$sum_temp_y10 = number_format(($sum_count_array[16]/(365*10))*100,1);

					if ($sum_temp_y10 >= 0.5)
					{
						print("<TD align=right align=center nowrap><font size=\"-1\"><b>$sum_temp_y10 %</b></font></TD>\n");
					} else {
						print("<TD align=right align=center nowrap><font size=\"-1\">$sum_temp_y10 %</font></TD>\n");
					}

					$sum_temp_d1510 = number_format(($sum_count_array[7]/1075)*100,1);

					$weighted_average = (
						($sum_temp_y1    * 0.25) + 
						($sum_temp_d1510 * 0.35) +
						($sum_temp_y5    * 0.40)); 

					if ($weighted_average >= 0.5)
					{
						print("<TD align=right align=center nowrap><font size=\"-1\" align=center><b>$weighted_average %</b></font></TD>\n");
					} else {
						print("<TD align=right align=center nowrap><font size=\"-1\" align=center>$weighted_average %</font></TD>\n");
					}

					print("</TR>\n");

					$row_count_break++;

					$query4 = "Insert INTO $draw_prefix";
					$query4 .= "wheel_sum_table_eo4 ";
					$query4 .= "VALUES ( '0', ";
					$query4 .= "'$w', ";
					$query4 .= "'0', "; #180903
					#$query4 .= "'0', ";
					$query4 .= "'0', ";
					$query4 .= "'$row_eo40[even]', ";
					$query4 .= "'$row_eo40[odd]', ";
					$query4 .= "'$row_eo40[d1]', ";
					$query4 .= "'$row_eo40[d2]', ";
					$query4 .= "'$row_eo40[d3]', ";
					$query4 .= "'$row_eo40[d4]', ";
					for ($d = 0; $d <= 17; $d++) 
					{
						$query4 .= "'$sum_count_array[$d]', ";
					}
					$query4 .= "'$sigma', ";
					$query4 .= "'$sum_temp_y1', ";
					$query4 .= "'$sum_temp_y5', ";
					$query4 .= "'$sum_temp_y10', ";
					$query4 .= "'$weighted_average', ";
					
					$query4 .= "'1962-08-17', ";
					$query4 .= "'1962-08-17')"; 

					#print "$query4<p>";
					
					$mysqli_result4 = mysqli_query($mysqli_link, $query4) or die (mysqli_error($mysqli_link));

					if ($row_count_break == 23)
					{
						print "<TR>\n";
						print "<TD BGCOLOR=\"#CCCCCC\" align=\"center\">Sum</TD>\n";
						print "<TD BGCOLOR=\"#CCCCCC\" align=\"center\">eo40</TD>\n";
						print "<TD BGCOLOR=\"#CCCCCC\" align=\"center\">Even</TD>\n";
						print "<TD BGCOLOR=\"#CCCCCC\" align=\"center\">Odd</TD>\n";
						print "<TD BGCOLOR=\"#CCCCCC\" align=\"center\">d4_1</TD>\n";
						print "<TD BGCOLOR=\"#CCCCCC\" align=\"center\">d4_2</TD>\n";
						print "<TD BGCOLOR=\"#CCCCCC\" align=\"center\">d4_3</TD>\n";
						print "<TD BGCOLOR=\"#CCCCCC\" align=\"center\">d4_4</TD>\n";
						print "<TD BGCOLOR=\"#CCCCCC\" align=\"center\">Rows</TD>\n";
						print "<TD BGCOLOR=\"#CCCCCC\"><center>Last</center></TD>\n";
						print "<TD BGCOLOR=\"#CCCCCC\"><center>Week1</center></TD>\n";
						print "<TD BGCOLOR=\"#CCCCCC\"><center>Week2</center></TD>\n";
						print "<TD BGCOLOR=\"#CCCCCC\"><center>Month1</center></TD>\n";
						print "<TD BGCOLOR=\"#CCCCCC\"><center>Month3</center></TD>\n";
						print "<TD BGCOLOR=\"#CCCCCC\"><center>Month6</center></TD>\n";
						print "<TD BGCOLOR=\"#CCCCCC\"><center>Year1</center></TD>\n";
						
						print "<TD BGCOLOR=\"#CCCCCC\"><center>Year2</center></TD>\n";
						print "<TD BGCOLOR=\"#CCCCCC\"><center>Year3</center></TD>\n";
						
						print "<TD BGCOLOR=\"#CCCCCC\"><center>Year4</center></TD>\n";
						print "<TD BGCOLOR=\"#CCCCCC\"><center>D1510</center></TD>\n"; 
						print "<TD BGCOLOR=\"#CCCCCC\"><center>Year5</center></TD>\n";
						print "<TD BGCOLOR=\"#CCCCCC\"><center>Year6</center></TD>\n";
						print "<TD BGCOLOR=\"#CCCCCC\"><center>Year7</center></TD>\n";
						print "<TD BGCOLOR=\"#CCCCCC\"><center>Year8</center></TD>\n";
						print "<TD BGCOLOR=\"#CCCCCC\"><center>Year9</center></TD>\n";
						print "<TD BGCOLOR=\"#CCCCCC\"><center>Year10</center></TD>\n";
						print "<TD BGCOLOR=\"#CCCCCC\"><center>Total</center></TD>\n";
						print "<TD BGCOLOR=\"#CCCCCC\"><center>Prev</center></TD>\n";
						print "<TD BGCOLOR=\"#CCCCCC\"><center>Last</center></TD>\n";
						print "<TD BGCOLOR=\"#CCCCCC\"><center>Sigma</center></TD>\n";
						print "<TD BGCOLOR=\"#CCCCCC\"><center>wa1</center></TD>\n";
						print "<TD BGCOLOR=\"#CCCCCC\"><center>wa5</center></TD>\n";
						print "<TD BGCOLOR=\"#CCCCCC\"><center>wa</center></TD>\n";
						print("<TD BGCOLOR=\"#CCCCCC\"><center>wa</center></TD>\n");
						print "</TR>\n";

						$row_count_break = 0;
					}
				}
			}
		}

		print "<TR>\n";
		print "<TD BGCOLOR=\"#CCCCCC\" align=\"center\">Sum</TD>\n";
		print "<TD BGCOLOR=\"#CCCCCC\" align=\"center\">eo40</TD>\n";
		print "<TD BGCOLOR=\"#CCCCCC\" align=\"center\">Even</TD>\n";
		print "<TD BGCOLOR=\"#CCCCCC\" align=\"center\">Odd</TD>\n";
		print "<TD BGCOLOR=\"#CCCCCC\" align=\"center\">d4_1</TD>\n";
		print "<TD BGCOLOR=\"#CCCCCC\" align=\"center\">d4_2</TD>\n";
		print "<TD BGCOLOR=\"#CCCCCC\" align=\"center\">d4_3</TD>\n";
		print "<TD BGCOLOR=\"#CCCCCC\" align=\"center\">d4_4</TD>\n";
		print "<TD BGCOLOR=\"#CCCCCC\" align=\"center\">Rows</TD>\n";
		print "<TD BGCOLOR=\"#CCCCCC\"><center>Last</center></TD>\n";
		print "<TD BGCOLOR=\"#CCCCCC\"><center>Week1</center></TD>\n";
		print "<TD BGCOLOR=\"#CCCCCC\"><center>Week2</center></TD>\n";
		print "<TD BGCOLOR=\"#CCCCCC\"><center>Month1</center></TD>\n";
		print "<TD BGCOLOR=\"#CCCCCC\"><center>Month3</center></TD>\n";
		print "<TD BGCOLOR=\"#CCCCCC\"><center>Month6</center></TD>\n";
		print "<TD BGCOLOR=\"#CCCCCC\"><center>Year1</center></TD>\n";
		print "<TD BGCOLOR=\"#CCCCCC\"><center>Year2</center></TD>\n";
		print "<TD BGCOLOR=\"#CCCCCC\"><center>Year3</center></TD>\n";
		print "<TD BGCOLOR=\"#CCCCCC\"><center>D1510</center></TD>\n"; 
		print "<TD BGCOLOR=\"#CCCCCC\"><center>Year4</center></TD>\n";
		print "<TD BGCOLOR=\"#CCCCCC\"><center>Year5</center></TD>\n";
		print "<TD BGCOLOR=\"#CCCCCC\"><center>Year6</center></TD>\n";
		print "<TD BGCOLOR=\"#CCCCCC\"><center>Year7</center></TD>\n";
		print "<TD BGCOLOR=\"#CCCCCC\"><center>Year8</center></TD>\n";
		print "<TD BGCOLOR=\"#CCCCCC\"><center>Year9</center></TD>\n";
		print "<TD BGCOLOR=\"#CCCCCC\"><center>Year10</center></TD>\n";
		print "<TD BGCOLOR=\"#CCCCCC\"><center>Total</center></TD>\n";
		print "<TD BGCOLOR=\"#CCCCCC\"><center>Prev</center></TD>\n";
		print "<TD BGCOLOR=\"#CCCCCC\"><center>Last</center></TD>\n";
		print "<TD BGCOLOR=\"#CCCCCC\"><center>Sigma</center></TD>\n";
		print "<TD BGCOLOR=\"#CCCCCC\"><center>wa1</center></TD>\n";
		print "<TD BGCOLOR=\"#CCCCCC\"><center>wa5</center></TD>\n";
		print "<TD BGCOLOR=\"#CCCCCC\"><center>wa</center></TD>\n";
		print("<TD BGCOLOR=\"#CCCCCC\"><center>wa</center></TD>\n");
		print "</TR>\n";
		

		print("</TABLE>\n");

		print "<h3>Table <font color=\"#ff0000\">{$draw_prefix}wheel_sum_table_eo4</font> Updated!</h3>";

		# copy current table into dated table
		$curr_date = date("ymd");

		$table_temp_date = $draw_prefix . 'wheel_sum_table_eo4_' . $curr_date;

		$query = "DROP TABLE IF EXISTS $table_temp_date";

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

		$query_copy .= "CREATE TABLE $table_temp_date SELECT * FROM $draw_prefix";
		$query_copy .= "wheel_sum_table_eo4 ";

		$mysqli_result = mysqli_query($mysqli_link, $query_copy) or die (mysqli_error($mysqli_link));

		print "<h3>Table <font color=\"#ff0000\">$table_temp_date</font> Updated!</h3>";
	}

?>