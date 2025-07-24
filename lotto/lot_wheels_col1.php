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
	require ("includes/first_draw_unix.php");
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
	
	$debug = 0;

	// ----------------------------------------------------------------------------------
	// ----------------------------------------------------------------------------------
	function print_wheel_sum_table_col1($limit,$col1)
	{
		global $debug, $draw_table_name, $draw_prefix, $balls, $balls_drawn, $game; 

		require ("includes/mysqli.php");
		require ("includes/unix.incl");

		$query_draw = "SELECT * FROM $draw_table_name ";
		$query_draw .= "ORDER BY date DESC ";
		$query_draw .= "LIMIT 1 ";

		echo "$query_draw<p>";
	
		$mysqli_result_draw = mysqli_query($query_draw, $mysqli_link) or die (mysqli_error($mysqli_link));

		$row_draw = mysqli_fetch_array($mysqli_result_draw);

		$query_sum = "SELECT * FROM $draw_prefix";
		$query_sum .= "wheel_sum_table_";
		$query_sum .= "$col1 ";
		$query_sum .= "WHERE last_date = '$row_draw[date]' ";

		print "$query_sum<p>";
	
		#$mysqli_result_sum = mysqli_query($query_sum, $mysqli_link) or die (mysqli_error($mysqli_link));

		$num_rows_sum = mysqli_num_rows($mysqli_result_sum);

		if ($num_rows_sum)
		{
			print "num_rows_sum = $num_rows_sum<p>";
			die();
		}

		$query4 = "DROP TABLE IF EXISTS $draw_prefix";
		$query4 .= "wheel_sum_table_";
		$query4 .= "$col1 ";

		print "$query4<p>";

		$mysqli_result4 = mysqli_query($mysqli_link, $query4) or die (mysqli_error($mysqli_link));

		$query4 = "CREATE TABLE $draw_prefix";
		$query4 .= "wheel_sum_table_";
		$query4 .= "$col1 (";
		$query4 .= " id int(10) unsigned NOT NULL auto_increment, ";
		$query4 .= "`sum` tinyint(3) unsigned NOT NULL,";
		$query4 .= "`sum_count` int(6) unsigned NOT NULL,";
		$query4 .= "`eo50` tinyint(3) unsigned NOT NULL,";
		$query4 .= "`even` tinyint(3) unsigned NOT NULL,";
		$query4 .= "`odd` tinyint(3) unsigned NOT NULL,";
		$query4 .= "`d501` tinyint(3) unsigned NOT NULL,";
		$query4 .= "`d502` tinyint(3) unsigned NOT NULL,";
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

		print "$query4<p>";
	
		$mysqli_result4 = mysqli_query($mysqli_link, $query4) or die (mysqli_error($mysqli_link));

		$sum_table_date = array_fill (0,265,"1962-08-17");
		$sum_table_date_prev = array_fill (0,265,"1962-08-17");
		$sum_table = array_fill (0,265,0);
	
		$query_eo50 = "SELECT * FROM $draw_prefix";
		$query_eo50 .= "eo50 ";
		$query_eo50 .= "ORDER BY id ASC ";
	
		$mysqli_result_eo50 = mysqli_query($query_eo50, $mysqli_link) or die (mysqli_error($mysqli_link));

		//start table
		print("<h3>Wheel Sum Table - $limit - col=$col1</h3>\n");
		print("<TABLE BORDER=\"1\">\n");
	
		//create header row
		print("<TR>\n");
		print("<TD BGCOLOR=\"#CCCCCC\" align=\"center\">Sum</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\" align=\"center\">EO50</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\" align=\"center\">Even</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\" align=\"center\">Odd</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\" align=\"center\">D501</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\" align=\"center\">D502</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\" align=\"center\">Rows</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Last</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Week1</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Week2</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Month1</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Month3</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Month6</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Year1</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Year2</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Year3</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Year4</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Year5</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Year6</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Year7</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Year8</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Year9</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Year10</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Total</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Prev</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Last</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Sigma</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>wa1</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>wa5</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>wa10</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>wa</center></TD>\n");
		print("</TR>\n");

		$z = 0;
		$b_switch = 0;
	
		# loop eo50
		if ($game == 7)
		{
			$low = 80;
			$high = 239;
		} else {
			$low = 50;
			$high = 169;

		}
		for ($w = $low; $w <= $high; $w++)
		{
			mysqli_data_seek($mysqli_result_eo50,0);

			while($row_eo50 = mysqli_fetch_array($mysqli_result_eo50))
			{
					if ($row_eo50[id] == 25)
					{
						print "<TR>\n";
						print "<TD BGCOLOR=\"#CCCCCC\" align=\"center\">Sum</TD>\n";
						print "<TD BGCOLOR=\"#CCCCCC\" align=\"center\">EO50</TD>\n";
						print "<TD BGCOLOR=\"#CCCCCC\" align=\"center\">Even</TD>\n";
						print "<TD BGCOLOR=\"#CCCCCC\" align=\"center\">Odd</TD>\n";
						print "<TD BGCOLOR=\"#CCCCCC\" align=\"center\">D501</TD>\n";
						print "<TD BGCOLOR=\"#CCCCCC\" align=\"center\">D502</TD>\n";
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
						print "<TD BGCOLOR=\"#CCCCCC\"><center>wa10</center></TD>\n";
						print("<TD BGCOLOR=\"#CCCCCC\"><center>wa</center></TD>\n");
						print "</TR>\n";
					}
					
					$sum_count_array = array_fill (0,17,0);

					# select draws based on sum,even,odd,d501,d502
					$query_draw = "SELECT * FROM $draw_table_name ";
					$query_draw .= "WHERE sum = $w ";
					$query_draw .= "AND b1 = $col1 ";
					$query_draw .= "AND even = $row_eo50[even] ";
					$query_draw .= "AND odd = $row_eo50[odd] ";
					$query_draw .= "AND d501 = $row_eo50[d501] ";
					$query_draw .= "AND d502 = $row_eo50[d502] ";
					$query_draw .= "ORDER BY date DESC ";
					$query_draw .= "LIMIT $limit ";

					#echo "$query_draw<p>";
				
					$mysqli_result_draw = mysqli_query($query_draw, $mysqli_link) or die (mysqli_error($mysqli_link));

					$num_rows_all_draw = mysqli_num_rows($mysqli_result_draw);

					# loop draws
					while($row_draw = mysqli_fetch_array($mysqli_result_draw))
					{
						$sum_table[$row[0]]++;
						
						$draw_date_array = date_parse("$row_draw[date]");
						$draw_date_unix = mktime (0,0,0,$draw_date_array[month],$draw_date_array[day],$draw_date_array[year]);

						#$x = $row_date[sum];
						
						if ($draw_date_unix == $day1)
						{ 
							for ($d = 0; $d <= 15; $d++) {$sum_count_array[$d]++;}
						} elseif ($draw_date_unix > $week1) {
							for ($d = 1; $d <= 15; $d++) {$sum_count_array[$d]++;}
						} elseif ($draw_date_unix > $week2) {
							for ($d = 2; $d <= 15; $d++) {$sum_count_array[$d]++;}
						} elseif ($draw_date_unix > $month1) {
							for ($d = 3; $d <= 15; $d++) {$sum_count_array[$d]++;}
						} elseif ($draw_date_unix > $month3) {
							for ($d = 4; $d <= 15; $d++) {$sum_count_array[$d]++;}
						} elseif ($draw_date_unix > $month6) {
							for ($d = 5; $d <= 15; $d++) {$sum_count_array[$d]++;}
						} elseif ($draw_date_unix > $year1) {
							for ($d = 6; $d <= 15; $d++) {$sum_count_array[$d]++;}
						} elseif ($draw_date_unix > $year2) {
							for ($d = 7; $d <= 15; $d++) {$sum_count_array[$d]++;}
						} elseif ($draw_date_unix > $year3) {
							for ($d = 8; $d <= 15; $d++) {$sum_count_array[$d]++;}
						} elseif ($draw_date_unix > $year4) {
							for ($d = 9; $d <= 15; $d++) {$sum_count_array[$d]++;}
						} elseif ($draw_date_unix > $year5) {
							for ($d = 10; $d <= 15; $d++) {$sum_count_array[$d]++;}
						} elseif ($draw_date_unix > $year6) {
							for ($d = 11; $d <= 15; $d++) {$sum_count_array[$d]++;}
						} elseif ($draw_date_unix > $year7) {
							for ($d = 12; $d <= 15; $d++) {$sum_count_array[$d]++;}
						} elseif ($draw_date_unix > $year8) {
							for ($d = 13; $d <= 15; $d++) {$sum_count_array[$d]++;}
						} elseif ($draw_date_unix > $year9) {
							for ($d = 14; $d <= 15; $d++) {$sum_count_array[$d]++;}
						} elseif ($draw_date_unix > $year10) {
							for ($d = 15; $d <= 15; $d++) {$sum_count_array[$d]++;}
						}
				
						$sum_count_array[16]++;

						#add 1 year to clear
						if ($first_draw_unix > $year7) {
							for ($d = 13; $d <= 15; $d++) {$sum_count_array[$d]=0;}
						} elseif ($first_draw_unix > $year8) {
							for ($d = 14; $d <= 15; $d++) {$sum_count_array[$d]=0;}
						} elseif ($first_draw_unix > $year9) {
							for ($d = 15; $d <= 15; $d++) {$sum_count_array[$d]=0;}
						} elseif ($first_draw_unix > $year10) {
							for ($d = 16; $d <= 16; $d++) {$sum_count_array[$d]=0;}
						}
						
						$z++;
					}

					#$x = $w;

					print("<TR>\n");
					print("<TD align=center><center>$w</center></TD>\n");
					print("<TD align=center><center>$row_eo50[id]</center></TD>\n");
					print("<TD align=center><center>$row_eo50[even]</center></TD>\n");
					print("<TD align=center><center>$row_eo50[odd]</center></TD>\n");
					print("<TD align=center><center>$row_eo50[d501]</center></TD>\n");
					print("<TD align=center><center>$row_eo50[d502]</center></TD>\n");

					if ($game == 1 || $game == 5)
					{
						$num_rows = 0;

						$query_rows = "SELECT count(*) FROM combo_";
						$query_rows .= "$balls_drawn";
						if ($game == 7)
						{
							$query_rows .= "_$balls ";
						} else {
							$query_rows .= "_$balls ";
						}
						$query_rows .= "WHERE sum = $w ";
						$query_rows .= "AND b1 = $col1 ";
						$query_rows .= "AND even = $row_eo50[even] ";
						$query_rows .= "AND odd = $row_eo50[odd] ";
						if ($game == 1)
						{
							$query_rows .= "AND d2_1 = $row_eo50[d501] ";
							$query_rows .= "AND d2_2 = $row_eo50[d502] ";
						} else {
							$query_rows .= "AND d501 = $row_eo50[d501] ";
							$query_rows .= "AND d502 = $row_eo50[d502] ";
						}

						echo "$query_rows<p>";

						$mysqli_result_rows = mysqli_query($query_rows, $mysqli_link) or die (mysqli_error($mysqli_link));

						$row_count = mysqli_fetch_array($mysqli_result_rows);

						$num_rows = $row_count[0];
					} else {
						$num_rows = 0;
						
						for ($x = 1; $x <= 15; $x++)
						{
							$query_rows = "SELECT count(*) FROM combo_"; #001
							$query_rows .= "$balls_drawn";
							$query_rows .= "_";
							$query_rows .= "$balls";
							if ($x < 1)
							{
								$query_rows .= "_0";
							} else {
								$query_rows .= "_";
							}
							$query_rows .= "$x ";
							$query_rows .= "WHERE sum	= $w ";
							$query_rows .= "AND b1 = $col1 ";
							$query_rows .= "AND	even	= $row_eo50[even] ";
							$query_rows .= "AND	odd		= $row_eo50[odd] ";
							$query_rows .= "AND	d501	= $row_eo50[d501] ";
							$query_rows .= "AND	d502	= $row_eo50[d502] ";

							#print("$query_rows<p>");

							$mysqli_result_rows = mysqli_query($query_rows, $mysqli_link) or die (mysqli_error($mysqli_link));

							$row_count = mysqli_fetch_array($mysqli_result_rows);

							$num_rows += $row_count[0];
						}
					}

					print("<TD align=center><center>$num_rows</center></TD>\n");
					for ($d = 0; $d <= 15; $d++) 
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

					if ($sum_count_array[16] > 10)
					{
						print("<TD bgcolor=\"#FF0000\" align=center><font color=\"#ffffff\"><b>{$sum_count_array[16]}</b></font></TD>\n");
					} else {
						print("<TD align=center>{$sum_count_array[16]}</TD>\n");
					}

					# get dates
					$query_date = "SELECT * FROM $draw_table_name ";
					$query_date .= "WHERE sum = $w ";
					$query_date .= "AND b1 = $col1 ";
					$query_date .= "AND even = $row_eo50[even] ";
					$query_date .= "AND odd = $row_eo50[odd] ";
					$query_date .= "AND d501 = $row_eo50[d501] ";
					$query_date .= "AND d502 = $row_eo50[d502] ";
					$query_date .= "ORDER BY date DESC ";
					$query_date .= "LIMIT $limit ";
				
					$mysqli_result_date = mysqli_query($query_date, $mysqli_link) or die (mysqli_error($mysqli_link));

					$row_date = mysqli_fetch_array($mysqli_result_date);

					$row_date_prev = mysqli_fetch_array($mysqli_result_date);

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

					for ($d = 0; $d <= 15; $d++) 
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

					$sum_temp_y5 = number_format(($sum_count_array[10]/(365*5))*100,1);

					if ($sum_temp_y5 >= 0.5)
					{
						print("<TD align=right align=center nowrap><font size=\"-1\"><b>$sum_temp_y5 %</b></font></TD>\n");
					} else {
						print("<TD align=right align=center nowrap><font size=\"-1\">$sum_temp_y5 %</font></TD>\n");
					}

					$sum_temp_y10 = number_format(($sum_count_array[15]/(365*10))*100,1);

					if ($sum_temp_y10 >= 0.5)
					{
						print("<TD align=right align=center nowrap><font size=\"-1\"><b>$sum_temp_y10 %</b></font></TD>\n");
					} else {
						print("<TD align=right align=center nowrap><font size=\"-1\">$sum_temp_y10 %</font></TD>\n");
					}

					$weighted_average = (
						($sum_temp_y1  * 0.20) + 
						($sum_temp_y5  * 0.30) + 
						($sum_temp_y10 * 0.50));

					if ($weighted_average >= 0.5)
					{
						print("<TD align=right align=center nowrap><font size=\"-1\" align=center><b>$weighted_average %</b></font></TD>\n");
					} else {
						print("<TD align=right align=center nowrap><font size=\"-1\" align=center>$weighted_average %</font></TD>\n");
					}

					print("</TR>\n");

					$query4 = "Insert INTO $draw_prefix";
					$query4 .= "wheel_sum_table_";
					$query4 .= "$col1 ";
					$query4 .= "VALUES ( '0', ";
					$query4 .= "'$w', ";
					$query4 .= "'$row_count[0]', ";
					$query4 .= "'$row_wheel[eo50]', ";
					$query4 .= "'$row_eo50[even]', ";
					$query4 .= "'$row_eo50[odd]', ";
					$query4 .= "'$row_eo50[d501]', ";
					$query4 .= "'$row_eo50[d502]', ";
					for ($d = 0; $d <= 16; $d++) 
					{
						$query4 .= "'$sum_count_array[$d]', ";
					}
					$query4 .= "'$sigma', ";
					$query4 .= "'$sum_temp_y1', ";
					$query4 .= "'$sum_temp_y5', ";
					$query4 .= "'$sum_temp_y10', ";
					$query4 .= "'$weighted_average', ";
					
					$query4 .= "'$row_date_prev[date]', ";
					$query4 .= "'$row_date[date]')"; 

					#print "$query4<p>";
					
					$mysqli_result4 = mysqli_query($mysqli_link, $query4) or die (mysqli_error($mysqli_link));
			}

			print "<TR>\n";
			print "<TD BGCOLOR=\"#CCCCCC\" align=\"center\">Sum</TD>\n";
			print "<TD BGCOLOR=\"#CCCCCC\" align=\"center\">EO50</TD>\n";
			print "<TD BGCOLOR=\"#CCCCCC\" align=\"center\">Even</TD>\n";
			print "<TD BGCOLOR=\"#CCCCCC\" align=\"center\">Odd</TD>\n";
			print "<TD BGCOLOR=\"#CCCCCC\" align=\"center\">D501</TD>\n";
			print "<TD BGCOLOR=\"#CCCCCC\" align=\"center\">D502</TD>\n";
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
			print "<TD BGCOLOR=\"#CCCCCC\"><center>wa10</center></TD>\n";
			print("<TD BGCOLOR=\"#CCCCCC\"><center>wa</center></TD>\n");
			print "</TR>\n";
		}

		print("</TABLE>\n");

		print "<h3>Table <font color=\"#ff0000\">{$draw_prefix}wheel_sum_table</font> Updated!</h3>";

		# copy current table into dated table
		$curr_date = date("ymd");

		$table_temp_date = $draw_prefix . 'wheel_sum_table_' . $col1 . '_' .$curr_date;

		$query = "DROP TABLE IF EXISTS $table_temp_date";

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

		$query_copy .= "CREATE TABLE $table_temp_date SELECT * FROM $draw_prefix";
		$query_copy .= "wheel_sum_table_";
		$query_copy .= "$col1 ";

		$mysqli_result = mysqli_query($mysqli_link, $query_copy) or die (mysqli_error($mysqli_link));

		print "<h3>Table <font color=\"#ff0000\">$table_temp_date</font> Updated!</h3>";
	}

	// ----------------------------------------------------------------------------------
	function print_wheel_report_col($sum,$col1)
	{
		global $debug, $draw_table_name, $balls, $balls_drawn, $draw_prefix, $game; 

		require ("includes/games_switch.incl");

		require ("includes/mysqli.php");

		$today  = mktime (0,0,0,date("m"),date("d"),date("Y"));
		$prev_draw = '1962-08-17';
		$last_draw = '1962-08-17';

		$query_all = "SELECT * FROM $draw_table_name ";
		$query_all .= "WHERE b1 = $col1 ";

		print("$query_all<p>");

		$mysqli_result_all = mysqli_query($query_all, $mysqli_link) or die (mysqli_error($mysqli_link));

		$num_rows_all = mysqli_num_rows($mysqli_result_all);

		#print("num_rows_all = $num_rows_all<p>");

		$curr_date = date("Y-m-d");

		//start table
		print("<h3>Wheel Report - $sum - col1 $col1</h3>\n");
		print("<TABLE BORDER=\"1\">\n");
	
		//create header row
		print("<TR>\n");
		print("<TD BGCOLOR=\"#CCCCCC\" align=\"center\">Sum</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\" align=\"center\">Even</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\" align=\"center\">Odd</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\" align=\"center\">D501</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\" align=\"center\">D502</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\" align=\"center\">Last</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\" align=\"center\">Week1</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\" align=\"center\">Week2</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\" align=\"center\">Month1</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\" align=\"center\">Month3</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\" align=\"center\">Month6</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\" align=\"center\">Year1</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\" align=\"center\">Year2</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\" align=\"center\">Year3</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\" align=\"center\">Year4</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\" align=\"center\">Year5</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\" align=\"center\">Year6</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\" align=\"center\">Year7</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\" align=\"center\">Year8</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\" align=\"center\">Year9</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\" align=\"center\">Year10</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\" align=\"center\">$num_rows_all</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\" align=center>30</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\" align=center>365</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\" align=center>$num_rows_all</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\" align=center>wa</TD>\n");
		print("</TR>\n");
	
		$query1 = "SELECT * FROM $draw_prefix";
		$query1 .= "eo50 ";

		print("$query1<p>");
	
		$mysqli_result_1 = mysqli_query($mysqli_link, $query1) or die (mysqli_error($mysqli_link));

		$num_rows_1 = mysqli_num_rows($mysqli_result_1);

		#print("num_rows_1 = $num_rows_1<p>");

		while($row6 = mysqli_fetch_array($mysqli_result_1))
		{
			#print "game = $game<br>";
			if ($game == 4 || $game == 5)
			{
				$query2 = "SELECT * FROM_"; #001
				$query2 .= "$balls_drawn";
				$query2 .= "_";
				$query2 .= "$balls ";
				$query2 .= "WHERE sum	= $sum ";
				$query2 .= "AND	even	= $row6[even] ";
				$query2 .= "AND	odd		= $row6[odd] ";
				$query2 .= "AND	d501	= $row6[d501] ";
				$query2 .= "AND	d502	= $row6[d502] ";

				print("$query2<p>");

				$mysqli_result7 = mysqli_query($mysqli_link, $query2) or die (mysqli_error($mysqli_link));

				$num_rows = mysqli_num_rows($mysqli_result7);
			} else {
				$num_rows = 0;
				
				for ($x = 1; $x <= 9; $x++)
				{
					$query2 = "SELECT * FROM combo_"; #001
					$query2 .= "$balls_drawn";
					$query2 .= "_";
					$query2 .= "$balls";
					$query2 .= "_0";
					$query2 .= "$x ";
					$query2 .= "WHERE sum	= $sum ";
					$query2 .= "AND	even	= $row6[even] ";
					$query2 .= "AND	odd		= $row6[odd] ";
					$query2 .= "AND	d501	= $row6[d501] ";
					$query2 .= "AND	d502	= $row6[d502] ";

					#print("$query2<p>");

					$mysqli_result7 = mysqli_query($mysqli_link, $query2) or die (mysqli_error($mysqli_link));

					$num_rows += mysqli_num_rows($mysqli_result7);
				}
			}

			print("<b>num_rows = $num_rows</b><p>");

			#die ("die num rows");

			if ($num_rows)
			{
				print("<TR>\n");
				print("<TD align=center>$sum</TD>\n");
				print("<TD align=center>$row6[even]</TD>\n"); 
				print("<TD align=center>$row6[odd]</TD>\n");
				print("<TD align=center>$row6[d501]</TD>\n");
				print("<TD align=center>$row6[d502]</TD>\n");
				#print("<TD align=center>$num_rows</TD>\n");
				
				while($row = mysqli_fetch_array($mysqli_result7))
				{
					#$wheel_tot_temp_array = array_fill (0,16,0);
					#$wheel_tot_array = array_fill (0,30,$wheel_tot_temp_array);
					$wheel_tot_array = array_fill (0,19,0);

					###################################################################

					$wheel_tot_array = calculate_wheel_count ($col1,$sum,$row);

					###################################################################

					#print("num_rows_5000 = $num_rows_all<p>");

					for ($y = 0; $y <= 16; $y++)
					{
						print("<TD align=center>$wheel_tot_array[$y]</TD>\n");
					}
					
					if ($num_rows_5000)
					{
						$row_date = mysqli_fetch_array($mysqli_result4);
						$last_draw = $row_date[date];
					}

					if ($num_rows_5000 > 1)
					{
						$row_date = mysqli_fetch_array($mysqli_result4);
						$prev_draw = $row_date[date];
					}

					$num_rows_temp_30 = number_format(($wheel_tot_array[3]/30)*100,1);

					$num_rows_temp_365 = number_format(($wheel_tot_array[6]/365)*100,1);

					$num_rows_temp_5000 = number_format(($wheel_tot_array[15]/$num_rows_all)*100,1);
					
					$weighted_average = (
						($wheel_tot_array[0]/10*100*0.05) +
						($wheel_tot_array[1]/30*100*0.05) +
						($wheel_tot_array[2]/100*100*0.05) +
						($wheel_tot_array[3]/365*100*0.05) +
						($wheel_tot_array[4]/500*100*0.05) +
						($wheel_tot_array[5]/1000*100*0.05) +
						($wheel_tot_array[6]/1000*100*0.05) +
						($wheel_tot_array[7]/1000*100*0.05) +
						($wheel_tot_array[8]/1000*100*0.05) +
						($wheel_tot_array[9]/1000*100*0.05) +
						($wheel_tot_array[10]/1000*100*0.05) +
						($wheel_tot_array[11]/1000*100*0.05) +
						($wheel_tot_array[12]/1000*100*0.05) +
						($wheel_tot_array[13]/1000*100*0.05) +
						($wheel_tot_array[14]/1000*100*0.05) +
						($wheel_tot_array[15]/$num_rows_all*100*0.05));

					$num_rows_temp_wa = number_format($weighted_average,1);

					if ($wheel_tot_array[0])
					{
						$weighted_average_prev = (
						($wheel_tot_array[0]-1/10*100*0.05) +
						($wheel_tot_array[1]-1/30*100*0.05) +
						($wheel_tot_array[2]-1/100*100*0.05) +
						($wheel_tot_array[3]-1/365*100*0.05) +
						($wheel_tot_array[4]-1/500*100*0.05) +
						($wheel_tot_array[5]-1/1000*100*0.05) +
						($wheel_tot_array[5]-1/1000*100*0.05) +
						($wheel_tot_array[6]-1/1000*100*0.05) +
						($wheel_tot_array[7]-1/1000*100*0.05) +
						($wheel_tot_array[8]-1/1000*100*0.05) +
						($wheel_tot_array[9]-1/1000*100*0.05) +
						($wheel_tot_array[10]-1/1000*100*0.05) +
						($wheel_tot_array[11]-1/1000*100*0.05) +
						($wheel_tot_array[12]-1/1000*100*0.05) +
						($wheel_tot_array[13]-1/1000*100*0.05) +
						($wheel_tot_array[14]-1/1000*100*0.05) +
						($wheel_tot_array[15]-1/$num_rows_all*100*0.05));
					} else {
						$weighted_average_prev = $num_rows_temp_wa;
					}

					$prev_temp_wa = number_format($weighted_average_prev,1);

					#print("<TD align=center>$num_rows_temp</TD>\n");
					print("<TD align=center>$num_rows_temp_30</TD>\n");
					print("<TD align=center>$num_rows_temp_365</TD>\n");
					print("<TD align=center>$num_rows_temp_5000</TD>\n");
					print("<TD align=center>$prev_temp_wa</TD>\n");
					print("<TD align=center>$num_rows_temp_wa</TD>\n");
					print("</TR>\n");
					
					if ($num_rows > 0.0)
					{
						$query9 = "INSERT INTO $draw_prefix";
						$query9 .= "wheels_generated_$col1 "; 
						$query9 .= "VALUES ( $sum,
											 $row6[id],";
										for ($y = 0; $y <= 17; $y++)
										{
											$query9 .= "$wheel_tot_array[$y], ";
										}
						$query9 .= 		"'$num_rows_temp_wa',
										'$prev_temp_wa',
										'$prev_draw',
										'$last_draw', 
										'$curr_date') ";

						print "$query9<br>";
					
						$mysqli_result9 = mysqli_query($mysqli_link, $query9) or die (mysqli_error($mysqli_link));
					}
					#die ("die bottom");
				}
			}
		}

		print("</TABLE>\n");

		print "<h3>Table <font color=\"#ff0000\">$draw_prefix wheels_generated</font> Updated!</h3>";
	}


	for ($x = 1; $x <= 1; $x++)
	{
		print_wheel_sum_table_col1(5000,$x);
	}

	// **************************************************************************

	//close page
	print("</BODY>\n");
	print("</HTML>\n");

?>
