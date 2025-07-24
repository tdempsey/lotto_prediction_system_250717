<?php

	// Game ----------------------------- Game
	$game = 1; // Georgia Fantasy 5
	#$game = 2; // Mega Millions
	//$game = 3; // Georgia 5
	#$game = 4; // Florida Fantasy 5
	//$game = 5; // Florida Mega Money
	#$game = 6; // Florida Lotto
	#$game = 7; // Powerball
	$game = 10; // All or Nothing
	// Game ----------------------------- Game

	$hml = 0;
	#$hml = 1;		#= high
	#$hml = 2;		#= medium
	#$hml = 3;		#= low
	#$hml = 4;		#= min
	#$hml = 5;		#= max
	#$hml = 150;	
	
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
	require ("includes/test_filter_d_table.php");
	require ("includes/test_wheel_table.php");
	require ("includes/calculate_draw.php");
	require ("includes/table_draw_count.php");
	require ("includes/x10.php");
	require ("includes/count_2_seq.php");
	require ("includes/count_3_seq.php");
	require ("includes/count_mod.php");
	require ("includes/draw_count_total.php");

	date_default_timezone_set('America/New_York');	

	require_once ("includes/hml_switch.incl");
	
	$debug = 0;

	// ----------------------------------------------------------------------------------
	function print_sum_table($limit)
	{
		global $debug, $draw_table_name, $draw_prefix, $balls, $balls_drawn, $game, $hml, $range_low, $range_high; 

		require ("includes/mysqli.php");
		require ("includes/unix.incl");

		$query4 = "DROP TABLE IF EXISTS $draw_prefix";
		$query4 .= "sum_table ";

		$mysqli_result4 = mysqli_query($mysqli_link, $query4) or die (mysqli_error($mysqli_link));

		$query4 = "CREATE TABLE $draw_prefix";
		$query4 .= "sum_table (";
		$query4 .= "`sum` int(3) unsigned NOT NULL,";
		#$query4 .= "`eo50` tinyint(3) unsigned NOT NULL,";
		#$query4 .= "`even` tinyint(3) unsigned NOT NULL,";
		#$query4 .= "`odd` tinyint(3) unsigned NOT NULL,";
		#$query4 .= "`d501` tinyint(3) unsigned NOT NULL,";
		#$query4 .= "`d502` tinyint(3) unsigned NOT NULL,";
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
		$query4 .= "percent_30 float(4,1) unsigned NOT NULL default '0', ";
		$query4 .= "percent_365 float(4,1) unsigned NOT NULL default '0', ";
		$query4 .= "percent_5000 float(4,1) unsigned NOT NULL default '0', ";
		#$query4 .= "percent_wa float(4,1) unsigned NOT NULL default '0', ";
		$query4 .= "previous_date date NOT NULL default '1962-08-17', ";
		$query4 .= "last_date date NOT NULL default '1962-08-17', ";
		$query4 .= "PRIMARY KEY  (`sum`)";
		$query4 .= ") ENGINE=InnoDB DEFAULT CHARSET=latin1;";
	
		$mysqli_result4 = mysqli_query($mysqli_link, $query4) or die (mysqli_error($mysqli_link));

		$sum_table_date = array_fill (0,265,'1962-08-17');
		$sum_table_date_prev = array_fill (0,265,'1962-08-17');
		$sum_table = array_fill (0,265,0);
	
		$query = "SELECT * FROM $draw_table_name ";
		$query .= "ORDER BY date DESC ";
		$query .= "LIMIT $limit ";
	
		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

		$num_rows_all = mysqli_num_rows($mysqli_result); 

		//start table
		print("<h3>Sum Table - $limit</h3>\n");
		print("<TABLE BORDER=\"1\">\n");
	
		//create header row
		print("<TR>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Sum</TD>\n");
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
		print("</TR>\n");

		$z = 0;

		$temp_array = array_fill (0,17,0);
		$sum_count_array = array_fill (0,265,$temp_array);
	
		while($row = mysqli_fetch_array($mysqli_result))
		{
			$sum_table[$row[0]]++;

			if ($row[date] > $sum_table_date[$row[sum]]) {
				$sum_table_date[$row[sum]] = $row[date];
			} elseif ($sum_table_date_prev[$row[sum]] == '1962-08-17') {
				$sum_table_date_prev[$row[sum]] = $row[date];
			}
			
			$draw_date_array = date_parse("$row[date]");
			$draw_date_unix = mktime (0,0,0,$draw_date_array[month],$draw_date_array[day],$draw_date_array[year]);

			$x = $row[sum];
			
			if ($draw_date_unix == $day1)
			{ 
				for ($d = 0; $d <= 15; $d++) {$sum_count_array[$x][$d]++;}
			} elseif ($draw_date_unix > $week1) {
				for ($d = 1; $d <= 15; $d++) {$sum_count_array[$x][$d]++;}
			} elseif ($draw_date_unix > $week2) {
				for ($d = 2; $d <= 15; $d++) {$sum_count_array[$x][$d]++;}
			} elseif ($draw_date_unix > $month1) {
				for ($d = 3; $d <= 15; $d++) {$sum_count_array[$x][$d]++;}
			} elseif ($draw_date_unix > $month3) {
				for ($d = 4; $d <= 15; $d++) {$sum_count_array[$x][$d]++;}
			} elseif ($draw_date_unix > $month6) {
				for ($d = 5; $d <= 15; $d++) {$sum_count_array[$x][$d]++;}
			} elseif ($draw_date_unix > $year1) {
				for ($d = 6; $d <= 15; $d++) {$sum_count_array[$x][$d]++;}
			} elseif ($draw_date_unix > $year2) {
				for ($d = 7; $d <= 15; $d++) {$sum_count_array[$x][$d]++;}
			} elseif ($draw_date_unix > $year3) {
				for ($d = 8; $d <= 15; $d++) {$sum_count_array[$x][$d]++;}
			} elseif ($draw_date_unix > $year4) {
				for ($d = 9; $d <= 15; $d++) {$sum_count_array[$x][$d]++;}
			} elseif ($draw_date_unix > $year5) {
				for ($d = 10; $d <= 15; $d++) {$sum_count_array[$x][$d]++;}
			} elseif ($draw_date_unix > $year6) {
				for ($d = 11; $d <= 15; $d++) {$sum_count_array[$x][$d]++;}
			} elseif ($draw_date_unix > $year7) {
				for ($d = 12; $d <= 15; $d++) {$sum_count_array[$x][$d]++;}
			} elseif ($draw_date_unix > $year8) {
				for ($d = 13; $d <= 15; $d++) {$sum_count_array[$x][$d]++;}
			} elseif ($draw_date_unix > $year9) {
				for ($d = 14; $d <= 15; $d++) {$sum_count_array[$x][$d]++;}
			} elseif ($draw_date_unix > $year10) {
				for ($d = 15; $d <= 15; $d++) {$sum_count_array[$x][$d]++;}
			}
	
			$sum_count_array[$x][16]++;

			#add 1 year to clear
			if ($first_draw_unix > $year7) {
				for ($d = 13; $d <= 15; $d++) {$sum_count_array[$x][$d]=0;}
			} elseif ($first_draw_unix > $year8) {
				for ($d = 14; $d <= 15; $d++) {$sum_count_array[$x][$d]=0;}
			} elseif ($first_draw_unix > $year9) {
				for ($d = 15; $d <= 15; $d++) {$sum_count_array[$x][$d]=0;}
			} elseif ($first_draw_unix > $year10) {
				for ($d = 16; $d <= 16; $d++) {$sum_count_array[$x][$d]=0;}
			}
			
			$z++;
		}

		$sub_sum = array_fill (0,17,0);
		$sub_sum_tot = 0;

		for ($x = 40; $x < 300; $x++)
		{
			print("<TR>\n");
			print("<TD align=center><center>$x</center></TD>\n");
			for ($d = 0; $d <= 15; $d++) 
			{
				if ($sum_count_array[$x][$d] > 10)
				{
					print("<TD bgcolor=\"#FF0033\" align=center>{$sum_count_array[$x][$d]}</TD>\n");
				} elseif ($sum_count_array[$x][$d] > 7) {
					print("<TD bgcolor=\"#CCFFFF\" align=center>{$sum_count_array[$x][$d]}</TD>\n");
				} elseif ($sum_count_array[$x][$d] > 1) {
					print("<TD bgcolor=\"#CCFF66\" align=center>{$sum_count_array[$x][$d]}</TD>\n");
				} elseif ($sum_count_array[$x][$d] == 1) {
					print("<TD bgcolor=\"#F1F1F1\" align=center>1</TD>\n");
				} else {
					print("<TD align=center>-</TD>\n");
				}

				$sub_sum[$d] += $sum_count_array[$x][$d];
			}

			if ($sum_count_array[$x][16] > 10)
			{
				print("<TD bgcolor=\"#FF0000\" align=center><font color=\"#ffffff\"><b>{$sum_count_array[$x][16]}</b></font></TD>\n");
			} else {
				print("<TD align=center>{$sum_count_array[$x][16]}</TD>\n");
			}

			$sub_sum[16] += $sum_count_array[$x][16];
		
			if ($sum_table_date_prev[$x] == "1962-08-17") {
				print("<TD align=center><center>-</center></TD>\n");
			} elseif ($sum_table_date_prev[$x] < "2006-01-01") {
				print("<TD align=center><FONT COLOR=\"#ff0000\">$sum_table_date_prev[$x]</FONT></TD>\n");
			} elseif ($sum_table_date_prev[$x] < "2007-01-01") {
				print("<TD align=center><FONT COLOR=\"#ff6600\">$sum_table_date_prev[$x]</FONT></TD>\n");
			} else {
				print("<TD align=center><FONT COLOR=\"#000000\">$sum_table_date_prev[$x]</FONT></TD>\n");
			}

			if ($sum_table_date[$x] == "1962-08-17") {
				print("<TD align=center><center>-</center></TD>\n");
			} elseif ($sum_table_date[$x] < "2006-01-01") {
				print("<TD><FONT COLOR=\"#ff0000\" align=center>$sum_table_date[$x]</FONT></TD>\n");
			} elseif ($sum_table_date[$x] < "2007-01-01") {
				print("<TD><FONT COLOR=\"#ff6600\" align=center>$sum_table_date[$x]</FONT></TD>\n");
			} else {
				print("<TD><FONT COLOR=\"#000000\" align=center>$sum_table_date[$x]</FONT></TD>\n");
			}

			$sigma = 0;

			for ($d = 0; $d <= 15; $d++) 
			{
				$sigma += $sum_count_array[$x][$d];
			} 

			print("<TD align=center>$sigma</TD>\n");

			$sum_temp_y1 = number_format(($sum_count_array[$x][6]/365)*100,1);

			if ($sum_temp_y1 >= 1.0)
			{
				print("<TD align=right align=center><font size=\"-1\"><b>$sum_temp_y1 %</b></font></TD>\n");
			} else {
				print("<TD align=right align=center><font size=\"-1\">$sum_temp_y1 %</font></TD>\n");
			}

			$sum_temp_y5 = number_format(($sum_count_array[$x][10]/(365*5))*100,1);

			if ($sum_temp_y5 >= 1.0)
			{
				print("<TD align=right align=center><font size=\"-1\"><b>$sum_temp_y5 %</b></font></TD>\n");
			} else {
				print("<TD align=right align=center><font size=\"-1\">$sum_temp_y5 %</font></TD>\n");
			}

			$weighted_average = (
				($sum_count_array[$x][1]/7*100*0.10) + #week1
				($sum_count_array[$x][3]/30*100*0.10) + #month1
				($sum_count_array[$x][5]/(365/2)*100*0.15) + #month6
				($sum_count_array[$x][6]/365*100*0.15) + #year1
				($sum_count_array[$x][10]/(365*5)*100*0.25) + #year5
				($sum_count_array[$x][13]/(365*8)*100*0.25)); #year8

			$sum_temp_wa = number_format($weighted_average,1);

			if ($sum_temp_wa >= 0.5)
			{
				print("<TD align=right align=center><font size=\"-1\" align=center><b>$sum_temp_wa %</b></font></TD>\n");
			} else {
				print("<TD align=right align=center><font size=\"-1\" align=center>$sum_temp_wa %</font></TD>\n");
			}

			print("</TR>\n");

			if ($x == 49 || $x == 59 || $x == 69 || $x == 79 || $x == 89 ||
				$x == 99 || $x == 109 || $x == 119 || $x == 129 || $x == 139 ||
				$x == 149 || $x == 159 || $x == 169 || $x == 179 || $x == 189 ||
				$x == 199 || $x == 209 || $x == 219 || $x == 229)
			{
				print("<TR>\n");
				print("<TD><center>&nbsp;</center></TD>\n");
				for ($d = 0; $d <= 16; $d++) 
				{
					print("<TD><center>$sub_sum[$d]</center></TD>\n");
				}
				print("<TD><center>&nbsp;</center></TD>\n");
				print("<TD><center>&nbsp;</center></TD>\n");
				print("<TD><center>&nbsp;</center></TD>\n");
				print("<TD><center>&nbsp;</center></TD>\n");
				print("<TD><center>&nbsp;</center></TD>\n");
				print("<TD><center>&nbsp;</center></TD>\n");
				print("</TR>\n");
				
				print("<TR>\n");
				print("<TD BGCOLOR=\"#eeeeee\">Sum</TD>\n");
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
				print("</TR>\n");

				$sub_sum = array_fill (0,9, 0);
				$sub_sum_tot = 0;
			}

			$query4 = "Insert INTO $draw_prefix";
			$query4 .= "sum_table ";
			#$query4 .= "VALUES ( '0', ";
			$query4 .= " VALUE ($x, ";
			#$query4 .= "'$num_rows', ";
			#$query4 .= "'$row[even]', ";
			#$query4 .= "'$row[odd]', ";
			#$query4 .= "'$row[d501]', ";
			#$query4 .= "'$row[d502]', ";
			for ($d = 0; $d <= 15; $d++) 
			{
				$query4 .= "'{$sum_count_array[$x][$d]}', ";
			}
			$query4 .= "'{$sum_count_array[$x][16]}', ";
			$query4 .= "'$sigma', ";
			$query4 .= "'$sum_temp_y1', ";
			$query4 .= "'$sum_temp_y5', ";
			$query4 .= "'$weighted_average', ";
			$query4 .= "'$sum_table_date_prev[$x]',"; 
			$query4 .= "'$sum_table_date[$x]')";

			#print "$query4<p>";
			
			$mysqli_result4 = mysqli_query($mysqli_link, $query4) or die (mysqli_error($mysqli_link));
		}

		print("</TABLE>\n");

		print "<h3>Table <font color=\"#ff0000\">{$draw_prefix}sum_table</font> Updated!</h3>";
	}

	// ----------------------------------------------------------------------------------
	function print_wheel_sum_table_save($limit)
	{
		global $debug, $draw_table_name, $draw_prefix, $balls, $balls_drawn, $game; 

		require ("includes/mysqli.php");
		require ("includes/unix.incl");

		$sum_table_date = array_fill (0,265,"1962-08-17");
		$sum_table_date_prev = array_fill (0,265,"1962-08-17");

		$query_draw = "SELECT * FROM $draw_table_name ";
		$query_draw .= "ORDER BY date DESC ";

		#echo "$query_draw<p>";
	
		$mysqli_result_draw = mysqli_query($query_draw, $mysqli_link) or die (mysqli_error($mysqli_link));

		$num_rows_all_draw = mysqli_num_rows($mysqli_result_draw);

		# loop draws and update
		while($row_draw = mysqli_fetch_array($mysqli_result_draw))
		{
			$query_sum = "SELECT * FROM $draw_prefix";
			$query_sum .= "wheel_sum_table ";
			$query_sum .= "WHERE sum = $row_draw[sum] ";
			$query_sum .= "AND   even = $row_draw[even] ";
			$query_sum .= "AND   odd  = $row_draw[odd] ";
			$query_sum .= "AND   d501 = $row_draw[d501] ";
			$query_sum .= "AND   d502 = $row_draw[d502] ";

			#echo "$query_sum<p>";
		
			$mysqli_result_sum = mysqli_query($query_sum, $mysqli_link) or die (mysqli_error($mysqli_link));

			$row_sum = mysqli_fetch_array($mysqli_result_sum);

			$row_count_sum = mysqli_fetch_array($mysqli_result_sum);

			if ($row_sum[last_date] < $row_draw[date])
			{
				$sum_count_array = array_fill (0,17,0);

				# select draws based on sumeo50
				$query_draw2 = "SELECT * FROM $draw_table_name ";
				$query_draw2 .= "WHERE sum = $row_draw[sum] ";
				$query_draw2 .= "AND even = $row_draw[even] ";
				$query_draw2 .= "AND odd = $row_draw[odd] ";
				$query_draw2 .= "AND d501 = $row_draw[d501] ";
				$query_draw2 .= "AND d502 = $row_draw[d502] ";
				$query_draw2 .= "ORDER BY date DESC ";
				$query_draw2 .= "LIMIT $limit ";

				#echo "$query_draw2<p>";
			
				$mysqli_result_draw2 = mysqli_query($query_draw2, $mysqli_link) or die (mysqli_error($mysqli_link));

				$num_rows_all_draw2 = mysqli_num_rows($mysqli_result_draw2);

				# process draws
				while($row_draw2 = mysqli_fetch_array($mysqli_result_draw2))
				{					
					$draw_date_array = date_parse("$row_draw2[date]");
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
				
				mysqli_data_seek($mysqli_result_draw2,0);

				$row_date = mysqli_fetch_array($mysqli_result_draw2);

				$row_date_prev = mysqli_fetch_array($mysqli_result_draw2);
				
				$sigma = 0;

				for ($d = 0; $d <= 15; $d++) 
				{
					$sigma += $sum_count_array[$d];
				} 

				$weighted_average = (
					($sum_count_array[1]/7*100*0.10) + #week1
					($sum_count_array[3]/30*100*0.10) + #month1
					($sum_count_array[5]/(365/2)*100*0.15) + #month6
					($sum_count_array[6]/365*100*0.15) + #year1
					($sum_count_array[10]/(365*5)*100*0.25) + #year5
					($sum_count_array[13]/(365*8)*100*0.25)); #year8

				$sum_temp_wa = number_format($weighted_average,1);

				$query4 = "UPDATE $draw_prefix";
				$query4 .= "wheel_sum_table ";
				$query4 .= "SET  day1 = '$sum_count_array[0]', ";
				$query4 .= "  week1 = '$sum_count_array[1]', ";
				$query4 .= "  week2 = '$sum_count_array[2]', ";
				$query4 .= "  month1 = '$sum_count_array[3]', ";
				$query4 .= "  month3 = '$sum_count_array[4]', ";
				$query4 .= "  month6 = '$sum_count_array[5]', ";
				$query4 .= "  year1 = '$sum_count_array[6]', ";
				$query4 .= "  year2 = '$sum_count_array[7]', ";
				$query4 .= "  year3 = '$sum_count_array[8]', ";
				$query4 .= "  year4 = '$sum_count_array[9]', ";
				$query4 .= "  year5 = '$sum_count_array[10]', ";
				$query4 .= "  year6 = '$sum_count_array[11]', ";
				$query4 .= "  year7 = '$sum_count_array[12]', ";
				$query4 .= "  year8 = '$sum_count_array[13]', ";
				$query4 .= "  year9 = '$sum_count_array[14]', ";
				$query4 .= "  year10 = '$sum_count_array[15]', ";
				$query4 .= "  count = '$sum_count_array[16]', ";
				$query4 .= "  previous_date = '$row_date_prev[date]', ";
				$query4 .= "  last_date = '$row_date[date]' ";
				$query4 .= "WHERE id = '$row_sum[id]' ";

				print "$query4<p>";

				die();
				
				$mysqli_result4 = mysqli_query($mysqli_link, $query4) or die (mysqli_error($mysqli_link));
			}
		}

		die();

		//start table
		print("<h3>Wheel Sum Table - $limit</h3>\n");
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
		print("</TR>\n");

		$z = 0;
		$b_switch = 0;

		$query_eo50 = "SELECT * FROM $draw_prefix";
		$query_eo50 .= "wheel_sum_table ";
		$query_eo50 .= "ORDER BY id ASC ";
	
		$mysqli_result_eo50 = mysqli_query($query_eo50, $mysqli_link) or die (mysqli_error($mysqli_link));
	
		# loop eo50
		while($row_eo50 = mysqli_fetch_array($mysqli_result_eo50))
		{
			print "<TR>\n";
			print "<TD BGCOLOR=\"#CCCCCC\" align=\"center\">$row_eo50[sum]</TD>\n";
			print "<TD BGCOLOR=\"#CCCCCC\" align=\"center\">$row_eo50[eo50]</TD>\n";
			print "<TD BGCOLOR=\"#CCCCCC\" align=\"center\">$row_eo50[even]</TD>\n";
			print "<TD BGCOLOR=\"#CCCCCC\" align=\"center\">$row_eo50[odd]</TD>\n";
			print "<TD BGCOLOR=\"#CCCCCC\" align=\"center\">$row_eo50[d501]</TD>\n";
			print "<TD BGCOLOR=\"#CCCCCC\" align=\"center\">$row_eo50[d502]</TD>\n";
			print "<TD BGCOLOR=\"#CCCCCC\" align=\"center\">$row_eo50[sum_count]</TD>\n";

			/*
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
			*/

			print "<TD BGCOLOR=\"#CCCCCC\"><center>$row_eo50[day1]</center></TD>\n";
			print "<TD BGCOLOR=\"#CCCCCC\"><center>$row_eo50[week1]</center></TD>\n";
			print "<TD BGCOLOR=\"#CCCCCC\"><center>$row_eo50[week2]</center></TD>\n";
			print "<TD BGCOLOR=\"#CCCCCC\"><center>$row_eo50[month1]</center></TD>\n";
			print "<TD BGCOLOR=\"#CCCCCC\"><center>$row_eo50[month3]</center></TD>\n";
			print "<TD BGCOLOR=\"#CCCCCC\"><center>$row_eo50[month6]</center></TD>\n";
			print "<TD BGCOLOR=\"#CCCCCC\"><center>$row_eo50[year1]</center></TD>\n";
			print "<TD BGCOLOR=\"#CCCCCC\"><center>$row_eo50[year2]</center></TD>\n";
			print "<TD BGCOLOR=\"#CCCCCC\"><center>$row_eo50[year3]</center></TD>\n";
			print "<TD BGCOLOR=\"#CCCCCC\"><center>$row_eo50[year4]</center></TD>\n";
			print "<TD BGCOLOR=\"#CCCCCC\"><center>$row_eo50[year5]</center></TD>\n";
			print "<TD BGCOLOR=\"#CCCCCC\"><center>$row_eo50[year6]</center></TD>\n";
			print "<TD BGCOLOR=\"#CCCCCC\"><center>$row_eo50[year7]</center></TD>\n";
			print "<TD BGCOLOR=\"#CCCCCC\"><center>$row_eo50[year8]</center></TD>\n";
			print "<TD BGCOLOR=\"#CCCCCC\"><center>$row_eo50[year9]</center></TD>\n";
			print "<TD BGCOLOR=\"#CCCCCC\"><center>$row_eo50[year10]</center></TD>\n";
			print "<TD BGCOLOR=\"#CCCCCC\"><center>$row_eo50[count]</center></TD>\n";

			/*

			$sum_temp_y1 = number_format(($sum_count_array[6]/365)*100,1);

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
			*/

			print "<TD BGCOLOR=\"#CCCCCC\"><center>$row_eo50[previous_date]</center></TD>\n";
			print "<TD BGCOLOR=\"#CCCCCC\"><center>$row_eo50[last_date]</center></TD>\n";
			print "<TD BGCOLOR=\"#CCCCCC\"><center>$row_eo50[sigma]</center></TD>\n";

			/*
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

			if ($sum_temp_wa >= 0.4)
			{
				print("<TD align=right align=center nowrap><font size=\"-1\" align=center><b>$sum_temp_wa %</b></font></TD>\n");
			} else {
				print("<TD align=right align=center nowrap><font size=\"-1\" align=center>$sum_temp_wa %</font></TD>\n");
			}
			*/

			print "<TD BGCOLOR=\"#CCCCCC\"><center>$row_eo50[percent_30]</center></TD>\n";
			print "<TD BGCOLOR=\"#CCCCCC\"><center>$row_eo50[percent_365]</center></TD>\n";
			print "<TD BGCOLOR=\"#CCCCCC\"><center>$row_eo50[percent_5000]</center></TD>\n";
			print "</TR>\n";
		}

		print("</TABLE>\n");

		print "<h3>Table <font color=\"#ff0000\">{$draw_prefix}wheel_sum_table</font> Updated!</h3>";
	}

	// ----------------------------------------------------------------------------------
	function print_wheel_sum_table($limit)
	{
		global $debug, $draw_table_name, $draw_prefix, $balls, $balls_drawn, $game; 

		require ("includes/mysqli.php");
		require ("includes/unix.incl");

		$query_draw = "SELECT * FROM $draw_table_name ";
		$query_draw .= "ORDER BY date DESC ";
		$query_draw .= "LIMIT 1 ";

		#echo "$query_draw<p>";
	
		$mysqli_result_draw = mysqli_query($query_draw, $mysqli_link) or die (mysqli_error($mysqli_link));

		$row_draw = mysqli_fetch_array($mysqli_result_draw);

		$query_sum = "SELECT * FROM $draw_prefix";
		$query_sum .= "wheel_sum_table ";
		$query_sum .= "WHERE last_date = '$row_draw[date]' ";

		print "$query_sum<p>";
	
		$mysqli_result_sum = mysqli_query($query_sum, $mysqli_link) or die (mysqli_error($mysqli_link));

		$num_rows_sum = mysqli_num_rows($mysqli_result_sum);

		#die(); #-------------------------------------------------------------------------------------------

		if ($num_rows_sum)
		{
			print "num_rows_sum = $num_rows_sum<p>";
			die();
		}

		$query4 = "DROP TABLE IF EXISTS $draw_prefix";
		$query4 .= "wheel_sum_table ";

		print "$query4<p>";

		$mysqli_result4 = mysqli_query($mysqli_link, $query4) or die (mysqli_error($mysqli_link));

		$query4 = "CREATE TABLE $draw_prefix";
		$query4 .= "wheel_sum_table (";
		$query4 .= " id int(10) unsigned NOT NULL auto_increment, ";
		$query4 .= "`sum` int(3) unsigned NOT NULL,";
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
		print("<h3>Wheel Sum Table - $limit</h3>\n");
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
		if ($game == 2 || $game == 7)
		{
			$low = 80;
			$high = 300;
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

					if ($game == 1 || $game == 4 || $game == 5)
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
						$query_rows .= "AND even = $row_eo50[even] ";
						$query_rows .= "AND odd = $row_eo50[odd] ";
						if ($game == 1)
						{
							$query_rows .= "AND d2_1 = $row_eo50[d501] ";
							$query_rows .= "AND d2_2 = $row_eo50[d502] ";
						} else {
							$query_rows .= "AND d2_1 = $row_eo50[d501] ";
							$query_rows .= "AND d2_2 = $row_eo50[d502] ";
						}

						#echo "$query_rows<p>";

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
							if ($x < 10)
							{
								$query_rows .= "_0";
							} else {
								$query_rows .= "_";
							}
							$query_rows .= "$x ";
							$query_rows .= "WHERE sum	= $w ";
							$query_rows .= "AND	even	= $row_eo50[even] ";
							$query_rows .= "AND	odd		= $row_eo50[odd] ";
							$query_rows .= "AND	d501	= $row_eo50[d501] ";
							$query_rows .= "AND	d502	= $row_eo50[d502] ";

							#print("$query_rows<p>");

							if ($game != 10)
							{
								$mysqli_result_rows = mysqli_query($query_rows, $mysqli_link) or die (mysqli_error($mysqli_link));

								$row_count = mysqli_fetch_array($mysqli_result_rows);
							}

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
					$query4 .= "wheel_sum_table ";
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

		$table_temp_date = $draw_prefix . 'wheel_sum_table_' . $curr_date;

		$query = "DROP TABLE IF EXISTS $table_temp_date";

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

		$query_copy .= "CREATE TABLE $table_temp_date SELECT * FROM $draw_prefix";
		$query_copy .= "wheel_sum_table ";

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
					$query2 .= "AND	d2_1	= $row6[d2_1] ";
					$query2 .= "AND	d2_2	= $row6[d2_2] ";

					print("$query2<p>");

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

						#print "$query9<br>";
					
						$mysqli_result9 = mysqli_query($mysqli_link, $query9) or die (mysqli_error($mysqli_link));
					}
					#die ("die bottom");
				}
			}
		}

		print("</TABLE>\n");

		print "<h3>Table <font color=\"#ff0000\">$draw_prefix wheels_generated</font> Updated!</h3>";
	}

	// ----------------------------------------------------------------------------------
	function calculate_wheel_count ($col1,$sum,$row)
	{
		global $debug, $draw_table_name, $balls, $balls_drawn, $draw_prefix,$prev_draw,$last_draw; 

		require ("includes/mysqli.php");
		require ("includes/unix.incl");

		$wheel_tot_array = array_fill (0,18,0);

		// 1
		$day1_Ymd = gmdate ('Y-m-d', $day1);
		$query3 = "SELECT * FROM $draw_table_name ";
		$query3 .= "WHERE sum	= $sum ";
		$query3 .= "AND	b1	= $col1 ";
		$query3 .= "AND	even	= $row[even] ";
		$query3 .= "AND	odd		= $row[odd] ";
		$query3 .= "AND	d501	= $row[d501] ";
		$query3 .= "AND	d502	= $row[d502] ";
		$query3 .= "AND	date	>= '$day1_Ymd' ";
		$query3 .= "LIMIT 1 ";

		#print "$query3<br>";

		$mysqli_result3 = mysqli_query($mysqli_link, $query3) or die (mysqli_error($mysqli_link));

		$wheel_tot_array[0] = mysqli_num_rows($mysqli_result3);

		// 7

		$week1_Ymd = gmdate ('Y-m-d', $week1);
		$query3 = "SELECT * FROM $draw_table_name ";
		$query3 .= "WHERE sum	= $sum ";
		$query3 .= "AND	b1	= $col1 ";
		$query3 .= "AND	even	= $row[even] ";
		$query3 .= "AND	odd		= $row[odd] ";
		$query3 .= "AND	d501	= $row[d501] ";
		$query3 .= "AND	d502	= $row[d502] ";
		$query3 .= "AND	date	>= '$week1_Ymd' ";
		$query3 .= "LIMIT 7 ";

		#print "$query3<p>";

		$mysqli_result3 = mysqli_query($mysqli_link, $query3) or die (mysqli_error($mysqli_link));

		$wheel_tot_array[1] = mysqli_num_rows($mysqli_result3);
		
		// 14
		$week2_Ymd = gmdate ('Y-m-d', $week2);
		$query3 = "SELECT * FROM $draw_table_name ";
		$query3 .= "WHERE sum	= $sum ";
		$query3 .= "AND	b1	= $col1 ";
		$query3 .= "AND	even	= $row[even] ";
		$query3 .= "AND	odd		= $row[odd] ";
		$query3 .= "AND	d501	= $row[d501] ";
		$query3 .= "AND	d502	= $row[d502] ";
		$query3 .= "AND	date	>= '$week2_Ymd' ";
		$query3 .= "LIMIT 14 ";

		#print "$query3<br>";

		$mysqli_result3 = mysqli_query($mysqli_link, $query3) or die (mysqli_error($mysqli_link));

		$wheel_tot_array[2] = mysqli_num_rows($mysqli_result3);

		// 30
		$month1_Ymd = gmdate ('Y-m-d', $month1);
		$query3 = "SELECT * FROM $draw_table_name ";
		$query3 .= "WHERE sum	= $sum ";
		$query3 .= "AND	b1	= $col1 ";
		$query3 .= "AND	even	= $row[even] ";
		$query3 .= "AND	odd		= $row[odd] ";
		$query3 .= "AND	d501	= $row[d501] ";
		$query3 .= "AND	d502	= $row[d502] ";
		$query3 .= "AND	date	>= '$month1_Ymd' ";

		#print "$query3<br>";

		$mysqli_result3 = mysqli_query($mysqli_link, $query3) or die (mysqli_error($mysqli_link));

		$wheel_tot_array[3] = mysqli_num_rows($mysqli_result3);

		// 90
		$month3_Ymd = gmdate ('Y-m-d', $month3);
		$query3 = "SELECT * FROM $draw_table_name ";
		$query3 .= "WHERE sum	= $sum ";
		$query3 .= "AND	b1	= $col1 ";
		$query3 .= "AND	even	= $row[even] ";
		$query3 .= "AND	odd		= $row[odd] ";
		$query3 .= "AND	d501	= $row[d501] ";
		$query3 .= "AND	d502	= $row[d502] ";
		$query3 .= "AND	date	>= '$month3_Ymd' ";
		
		#print "$query3<br>";

		$mysqli_result3 = mysqli_query($mysqli_link, $query3) or die (mysqli_error($mysqli_link));

		$wheel_tot_array[4] = mysqli_num_rows($mysqli_result3);

		// 180
		$month6_Ymd = gmdate ('Y-m-d', $month6);
		$query3 = "SELECT * FROM $draw_table_name ";
		$query3 .= "WHERE sum	= $sum ";
		$query3 .= "AND	b1	= $col1 ";
		$query3 .= "AND	even	= $row[even] ";
		$query3 .= "AND	odd		= $row[odd] ";
		$query3 .= "AND	d501	= $row[d501] ";
		$query3 .= "AND	d502	= $row[d502] ";
		$query3 .= "AND	date	>= '$month6_Ymd' ";
		#print "$query3<br>";

		$mysqli_result3 = mysqli_query($mysqli_link, $query3) or die (mysqli_error($mysqli_link));

		$wheel_tot_array[5] = mysqli_num_rows($mysqli_result3);

		// year1-10
		$k = 6;
		for ($y = 1; $y <= 10; $y++)
		{
			$year_Ymd = gmdate ('Y-m-d', ${'year'.$y});
			#print "<b>year_Ymd = $year_Ymd</b><br>";

			$query3 = "SELECT * FROM $draw_table_name ";
			$query3 .= "WHERE sum	= $sum ";
			$query3 .= "AND	b1	= $col1 ";
			$query3 .= "AND	even	= $row[even] ";
			$query3 .= "AND	odd		= $row[odd] ";
			$query3 .= "AND	d501	= $row[d501] ";
			$query3 .= "AND	d502	= $row[d502] ";
			$query3 .= "AND	date	> '$year_Ymd";
			$query3 .= "$y' ";
			#print "$query3<br>";
	
			$mysqli_result3 = mysqli_query($mysqli_link, $query3) or die (mysqli_error($mysqli_link));

			$wheel_tot_array[$k] = mysqli_num_rows($mysqli_result3);
			$k++;
		}

		// 5000 - all
		$prev_draw = '1962-08-17';
		$last_draw = '1962-08-17';

		$query4 = "SELECT * FROM $draw_table_name ";
		$query4 .= "WHERE sum	= $sum ";
		$query4 .= "AND	b1	= $col1 ";
		$query4 .= "AND	even	= $row[even] ";
		$query4 .= "AND	odd		= $row[odd] ";
		$query4 .= "AND	d501	= $row[d501] ";
		$query4 .= "AND	d502	= $row[d502] ";
		$query4 .= "ORDER BY date DESC ";

		print "$query4<br>";

		$mysqli_result4 = mysqli_query($mysqli_link, $query4) or die (mysqli_error($mysqli_link));

		$wheel_tot_array[$k] = mysqli_num_rows($mysqli_result4);

		return $wheel_tot_array;

	}

	// ----------------------------------------------------------------------------------
	function print_wheel_report_summary()
	{
		global $debug, $draw_table_name, $balls, $balls_drawn, $draw_prefix; 

		require ("includes/mysqli.php");

		$rank = 1;

		//start table
		print("<h3>Wheel Report Summary</h3>\n");
		print("<TABLE BORDER=\"1\">\n");
	
		//create header row
		print("<TR>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Rank</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Sum</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Even</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Odd</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">D501</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">D501</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">EO50</TD>\n");
		#print("<TD BGCOLOR=\"#CCCCCC\">Total</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><b>Percent</b></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">30</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">365</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">1000</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">ALL</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Prev Drawn</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Last Drawn</TD>\n");
		print("</TR>\n");

		$query1 = "SELECT * FROM $draw_prefix";
		$query1 .= "wheels_generated ";
		$query1 .= "WHERE sum >= 70 and sum <= 119 ";
		$query1 .= "AND percent_wa >= 0.10 ";
		$query1 .= "ORDER BY percent_wa DESC ";

		//print("$query1<p>");

		$mysqli_result1 = mysqli_query($mysqli_link, $query1) or die (mysqli_error($mysqli_link));

		while($row = mysqli_fetch_array($mysqli_result1))
		{
			$query2 =  "SELECT * FROM $draw_prefix";
			$query2 .= "eo50 ";
			$query2 .= "WHERE id = $row[eo50]";

			//print("$query2<p>");
	
			$mysqli_result2 = mysqli_query($mysqli_link, $query2) or die (mysqli_error($mysqli_link));

			$row2 = mysqli_fetch_array($mysqli_result2);

			if ($row[total_draw] >= 0)
			{
				print("<TR>\n");
				print("<TD align=center><b>$rank</b></TD>\n");
				print("<TD align=center>$row[sum]</TD>\n");
				print("<TD align=center>$row2[even]</TD>\n"); 
				print("<TD align=center>$row2[odd]</TD>\n");
				print("<TD align=center>$row2[d501]</TD>\n");
				print("<TD align=center>$row2[d502]</TD>\n");
				print("<TD align=center>$row[eo50]</TD>\n");
				#$num_temp = number_format($row[total],0);
				#print("<TD align=center>$num_temp</TD>\n");
				print("<TD align=center><b>$row[percent_wa]</b></TD>\n");
				print("<TD align=center>$row[cnt30]</TD>\n");
				print("<TD align=center>$row[cnt365]</TD>\n");
				print("<TD align=center>$row[cnt1000]</TD>\n");
				print("<TD align=center>$row[cnt5000]</TD>\n");
				if ($row[prev_draw] < "2007-01-01")
				{
					print("<TD align=center><font color=\"#ff0000\">$row[prev_draw]</font></TD>\n");
				} elseif ($row[prev_draw] < "2007-04-01") {
					print("<TD align=center><font color=\"#ff6600\">$row[prev_draw]</font></TD>\n");
				} else {
					print("<TD align=center>$row[prev_draw]</TD>\n");
				}
				if ($row[last_draw] < "2007-01-01")
				{
					print("<TD align=center><font color=\"#ff0000\">$row[last_draw]</font></TD>\n");
				} elseif ($row[last_draw] < "2007-04-01") {
					print("<TD align=center><font color=\"#ff6600\">$row[last_draw]</font></TD>\n");
				} else {
					print("<TD align=center>$row[last_draw]</TD>\n");
				}

				$rank++;
			}
		}

		print("</TABLE>\n");
	}

	// ----------------------------------------------------------------------------------
	function test_nums($limit)
	{
		global $debug, $draw_table_name, $balls, $balls_drawn, $game_includes, $draw_prefix, $game_includes, $hml, $range_low, $range_high; 

		require ("includes/mysqli.php"); 

		require ("$game_includes/config.incl");

		$draw_count_columns = intval($balls/10); 
		$draw_count_total = array_fill (0, $draw_count_columns+1, 0);

		$query = "SELECT * FROM $draw_table_name ";
		if ($hml)
		{
			$query .= "WHERE sum >= $range_low  ";
			$query .= "AND   sum <= $range_high  ";
		}
		$query .= "ORDER BY date DESC ";
		$query .= "LIMIT $limit ";

		#echo "$query<p>";

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

		//start table
		print("<h3>Last $limit draws</h3>\n");
		print("<TABLE BORDER=\"1\">\n");

		//create header row
		print("<TR>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Date</TD>\n");
		for ($x = 1; $x <= $balls_drawn; $x++)
		{
			print("<TD BGCOLOR=\"#CCCCCC\" nowrap>Pick $x</TD>\n");
		}
		
		print("<TD BGCOLOR=\"#CCAACC\" align=center>Sum</TD>\n");
		print("<TD BGCOLOR=\"#CCAACC\" align=center>Width</TD>\n");

		for ($x = 0; $x <= $draw_count_columns; $x++)
		{
			print("<TD BGCOLOR=\"#CCCCCC\" align=center>$x.x</TD>\n");
		}

		print("<TD BGCOLOR=\"#CCAACC\">DC1</TD>\n");
		print("<TD BGCOLOR=\"#CCAACC\">DC2</TD>\n");
		print("<TD BGCOLOR=\"#CCAACC\">DC3</TD>\n");
		print("<TD BGCOLOR=\"#CCAACC\">DC4</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">X10_2</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">X10_3</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Seq2</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Seq3</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Mod</TD>\n");
		print("<TD BGCOLOR=\"#CCAACC\">Hot</TD>\n");
		print("<TD BGCOLOR=\"#CCAACC\">Warm</TD>\n");
		print("<TD BGCOLOR=\"#CCAACC\">Cold</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\" align=center colspan=2>Wheel</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Table</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\" nowrap><font size=\"-1\">Filter A</font></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\" nowrap><font size=\"-1\">Filter B</font></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\" nowrap><font size=\"-1\">Filter C</font></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><font size=\"-1\">Filter D</font></TD>\n");

		print("</B></TR>\n");

		$seq2_tot = 0;
		$seq3_tot = 0;
		$mod_tot = 0;
		$width_sum = 0;
		$hot_sum = 0;
		$warm_sum = 0;
		$cold_sum = 0;
		$sum_tot = 0;
		$width_tot = 0;
		$x10_2_tot = 0;
		$x10_3_tot = 0;
		$wheel_tot = 0;
		$table_draw_tot = 0;
		$filter_a_tot = 0;
		$filter_b_tot = 0;
		$filter_c_tot = 0;
		$one_count_tot = 0;
		$two_count_tot = 0;
		$two_one_count_tot = 0;
		$draw_count_tot = array_fill (0, 5, 0);
		$triplet_tot = 0;
		$draw = array_fill (0,$balls_drawn-1,0);

		// get each row
		while($row = mysqli_fetch_array($mysqli_result))
		{
			$y = 1;

			if ($game == 10)
			{
				for ($x = 0; $x < 12; $x++)
				{
					$draw[$x] = $row[d.$y];
					$y++;
				}
			} else {
				for ($x = 0; $x < $balls_drawn; $x++)
				{
					$draw[$x] = $row[b.$y];
					$y++;
				}
			}

			sort($draw);

			//print_r($draw);
			$draw_count = array_fill (0, 6, 0);
			$hot_nums = array();
			$warm_nums = array();
			$cold_nums = array();
			$one_count = 0;
			$two_count = 0;
			$combo_table = "N";
			$filter_a_table = "N";
			$filter_b_table = "N";
			$filter_c_table = "N";
			$filter_d_table = "N";

			/*
			if ($row[sum] >= 40 && $row[sum] <= 49)
			{
				require ("$game_includes/d40.incl");
			}
			elseif ($row[sum] >= 50 && $row[sum] <= 59)
			{
				require ("$game_includes/d50.incl");
			}
			elseif ($row[sum] >= 60 && $row[sum] <= 69)
			{
				require ("$game_includes/d60.incl");
			}
			elseif ($row[sum] >= 70 && $row[sum] <= 79)
			{
				require ("$game_includes/d70.incl");
			}
			elseif ($row[sum] >= 80 && $row[sum] <= 89)
			{
				require ("$game_includes/d80.incl");
			}
			elseif ($row[sum] >= 90 && $row[sum] <= 99)
			{
				require ("$game_includes/d90.incl");
			}
			elseif ($row[sum] >= 100 && $row[sum] <= 109)
			{
				require ("$game_includes/d100.incl");
			}
			elseif ($row[sum] >= 110 && $row[sum] <= 119)
			{
				require ("$game_includes/d110.incl");
			}
			elseif ($row[sum] >= 120 && $row[sum] <= 129)
			{
				require ("$game_includes/d120.incl");
			}
			elseif ($row[sum] >= 130 && $row[sum] <= 139)
			{
				require ("$game_includes/d130.incl");
			}
			elseif ($row[sum] >= 140 && $row[sum] <= 149)
			{
				require ("$game_includes/d140.incl");
			}
			elseif ($row[sum] >= 150 && $row[sum] <= 159)
			{
				require ("$game_includes/d150.incl");
			}
			elseif ($row[sum] >= 160 && $row[sum] <= 169)
			{
				require ("$game_includes/d160.incl");
			}
			elseif ($row[sum] >= 170 && $row[sum] <= 179)
			{
				require ("$game_includes/d170.incl");
			}
			elseif ($row[sum] >= 180 && $row[sum] <= 189)
			{
				require ("$game_includes/d170.incl");
			}
			elseif ($row[sum] >= 190 && $row[sum] <= 199)
			{
				require ("$game_includes/d170.incl");
			}
			elseif ($row[sum] >= 200 && $row[sum] <= 209)
			{
				require ("$game_includes/d170.incl");
			}
			else
			{
			*/
				require ("$game_includes/d0.incl");
			#}
			
			/*
			$query_test = "SELECT * FROM $draw_prefix";
			$query_test .= "test_table  ";
			$query_test .= "WHERE date = '$row[date]' ";

			$mysqli_result_test = mysqli_query($query_test, $mysqli_link) or die (mysqli_error($mysqli_link));

			$row_exist = mysqli_num_rows($mysqli_result_test);

			if ($row_exist)
			{
				$row_test = mysqli_fetch_array($mysqli_result_test);

				$width = $row_test[width];
				$seq2 = $row_test[seq2];
				$seq3 = $row_test[seq3];
				$dc1 = $row_test[dc1];
				$dc2 = $row_test[dc2];
				$dc3 = $row_test[dc3];
				$dt = $row_test[dt];
				$x10_1 = $row_test[x10_1];
				$x10_1_tot = $row_test[x10_1_tot];
				$x10_2 = $row_test[x10_2];
				$x10_2_tot = $row_test[x10_2_tot];
				$x10_3 = $row_test[x10_3];
				$x10_3_tot = $row_test[x10_3_tot];
				$mod = $row_test[mod];
				$due = $row_test[due];
				$due_tot = $row_test[due_tot];
				$soon = $row_test[soon];
				$soon_tot = $row_test[soon_tot];
				$hot = $row_test[hot];
				$hot_tot = $row_test[hot_tot];
				$warm = $row_test[warm];
				$warm_tot = $row_test[warm_tot];
				$cold = $row_test[cold];
				$cold_tot = $row_test[cold_tot];
				$combo_table = $row_test[combo_table];
				$wheel = $row_test[wheel];
				$el0 = $row_test[el0];
			} 
			else
			{
				test_table_update($row,$draw_table);
			}
			*/
			
			print("<TR>\n");

			print("<TD nowrap><CENTER>$row[date]</CENTER></TD>\n");
			
			
			for ($x = 0; $x < $balls_drawn; $x++)
			{
				print("<TD><CENTER>$draw[$x]</CENTER></TD>\n");
			}
			

			#print("<TD><CENTER>$draw[$x]</CENTER></TD>\n");

			if ($row[sum] < $sum_low || $row[sum] > $sum_high)
			{
				print("<TD align=center><font color=\"#ff0000\"><b>$row[sum]</b></font></TD>\n");
			} else {
				print("<TD align=center>$row[sum]</TD>\n");
			}

			$sum_tot += $row[sum];

			$width = $row[b5] - $row[b1];

			$width_tot += $width;

			if ($width < $width_limit) {
				print("<TD align=center><B><FONT COLOR=\"#ff0000\">$width</FONT></B></TD>\n");
			} else {
				print("<TD align=center>$width</TD>\n");
			}

			$draw_count = calculate_draw_count($draw); 

			for ($x = 0; $x <= $draw_count_columns; $x++)
			{
				if ($draw_count[$x] < $draw_range[$x][0] || $draw_count[$x] > $draw_range[$x][1]) {
					print("<TD align=center><B><FONT COLOR=\"#ff0000\">$draw_count[$x]</FONT></B></TD>\n");
				} else {
					print("<TD><CENTER>$draw_count[$x]</CENTER></TD>\n");
				}
				$draw_count_tot[$x] += $draw_count[$x];
			}

			$draw_count_total = draw_count_total($draw_count);

			//print "draw_count<br>";

			//print_r ($draw_count);

			for ($x = 1; $x <= 4; $x++)
			{
				if ($draw_count_total[1] == 0) {
					print("<TD BGCOLOR=\"#ff0000\"><CENTER><B><FONT COLOR=\"#ffffff\">$draw_count_total[$x]</FONT></B></CENTER></TD>\n");
				} elseif ($draw_count_total[3] == 2) {
					print("<TD BGCOLOR=\"#ff0000\"><CENTER><B><FONT COLOR=\"#ffffff\">$draw_count_total[$x]</FONT></B></CENTER></TD>\n");
				} elseif ($draw_count_total[4] == 1) {
					print("<TD BGCOLOR=\"#ff0000\"><CENTER><B><FONT COLOR=\"#ffffff\">$draw_count_total[$x]</FONT></B></CENTER></TD>\n");
				} elseif ($draw_count_total[$x] > 3) {
					print("<TD BGCOLOR=\"#ff0000\"><CENTER><B><FONT COLOR=\"#ffffff\">$draw_count_total[$x]</FONT></B></CENTER></TD>\n");
				} else {
					print("<TD><CENTER>$draw_count_total[$x]</CENTER></TD>\n");
				}
			}

			$x10_array = Last10Count($row[date]);

			// x10_2 
			$x10_count = 0;

			for ($x = 1 ; $x <= 4; $x++)
			{
				if ($x10_array[$row[$x]] >= 2)
				{
					$x10_count++;
				}
			}
			if ($x10_count > 3) {
				print("<TD BGCOLOR=\"#ff0000\"><CENTER><B><FONT COLOR=\"#ffffff\">$x10_count</FONT></B></CENTER></TD>\n");
				$x10_2_tot++;
			} else {
				print("<TD><CENTER>$x10_count</CENTER></TD>\n");
			}

			// x10_3 
			$x10_count = 0;

			for ($x = 1 ; $x <= 4; $x++)
			{
				if ($x10_array[$row[$x]] >= 3)
				{
					$x10_count++;
				}
			}

			if ($x10_count > 1) {
				print("<TD BGCOLOR=\"#ff0000\"><CENTER><B><FONT COLOR=\"#ffffff\">$x10_count</FONT></B></CENTER></TD>\n");
				$x10_3_tot++;
			} else {
				print("<TD><CENTER>$x10_count</CENTER></TD>\n");
			}

			$seq2 = Count2Seq($draw);

			if ($seq2 > 1) {
				print("<TD BGCOLOR=\"#ff0000\"><CENTER><B><FONT COLOR=\"#ffffff\">$seq2</FONT></B></CENTER></TD>\n");
				$seq2_tot++;
			} else {
				print("<TD><CENTER>$seq2</CENTER></TD>\n");
			}

			$seq3 = Count3Seq($draw);

			if ($seq3 > 0) {
				print("<TD BGCOLOR=\"#ff0000\"><CENTER><B><FONT COLOR=\"#ffffff\">$seq3</FONT></B></CENTER></TD>\n");
				$seq3_tot++;
			} else {
				print("<TD><CENTER>$seq3</CENTER></TD>\n");
			}

			$mod = CountMod($draw);
			if ($mod > 1) {
				print("<TD BGCOLOR=\"#ff0000\"><CENTER><B><FONT COLOR=\"#ffffff\">$mod</FONT></B></CENTER></TD>\n");
				$mod_tot++;
			} else {
				print("<TD><CENTER>$mod</CENTER></TD>\n");
			}

			$date_nodash = str_replace("-","", $row[date]);

			$num_rows = 0;

			$temp_file = $draw_prefix . "hot_" . $date_nodash;

			//print "temp_file = $temp_file<p>";

			//$hot_count = TableDrawCount($temp_file, $draw, $num_rows);

			//print "hot_count = $hot_count for fl_mm_hot_$date_nodash<br>";

			if ($hot_count != -1)
			{
				if ($hot_count < 0 || $hot_count > 2) {
					print("<TD BGCOLOR=\"#ff0000\"><CENTER><B><FONT COLOR=\"#ffffff\">$hot_count</FONT></B></CENTER></TD>\n");
				} else {
					print("<TD><CENTER>$hot_count</CENTER></TD>\n");
				}
				$hot_sum += $hot_count;
			}
			else
			{
				print("<TD><CENTER>-</CENTER></TD>\n");
			}

			$temp_file = $draw_prefix . "warm_" . $date_nodash;

			//$warm_count = TableDrawCount($temp_file, $draw, $num_rows);

			if ($warm_count != -1)
			{
				if ($warm_count < 0 || $warm_count > 2) {
					print("<TD BGCOLOR=\"#ff0000\"><CENTER><B><FONT COLOR=\"#ffffff\">$warm_count</FONT></B></CENTER></TD>\n");
				} else {
					print("<TD><CENTER>$warm_count</CENTER></TD>\n");
				}
				$warm_sum += $warm_count;
			}
			else
			{
				print("<TD><CENTER>-</CENTER></TD>\n");
			}

			$temp_file = $draw_prefix . "warm_" . $date_nodash;

			//$cold_count = TableDrawCount($temp_file, $draw, $num_rows);

			if ($cold_count != -1)
			{
				if ($cold_count > 2) {
					print("<TD BGCOLOR=\"#ff0000\"><CENTER><B><FONT COLOR=\"#ffffff\">$cold_count</FONT></B></CENTER></TD>\n");
				} else {
					print("<TD><CENTER>$cold_count</CENTER></TD>\n");
				}
				$cold_sum += $cold_count;
			}
			else
			{
				print("<TD><CENTER>-</CENTER></TD>\n");
			}

			$table_wheel_percent = 0.0; # tdd
			
			#TestWheelTable($row[even],$row[odd],$row[d501],$row[d502],$row[sum],$table_wheel_total,$prev_wheel_percent);
			$table_wheel_total--;

			if ($table_wheel_percent == 0.0) {
				print("<TD BGCOLOR=\"#ff0000\"><CENTER><B><FONT COLOR=\"#ffffff\">N</FONT></B></CENTER></TD>\n");
				print("<TD BGCOLOR=\"#ff0000\"><CENTER><B><FONT COLOR=\"#ffffff\">-</FONT></B></CENTER></TD>\n");
				$wheel_tot++;
			} elseif ($table_wheel_percent < 0.10) {
				print("<TD><CENTER><FONT COLOR=\"#ff0000\">$prev_wheel_percent</FONT></CENTER></TD>\n");
				print("<TD><CENTER><FONT COLOR=\"#ff0000\">$table_wheel_total</FONT></CENTER></TD>\n");
				$wheel_tot++;
			} else {
				print("<TD><CENTER>$prev_wheel_percent</CENTER></TD>\n");
				print("<TD><CENTER>$table_wheel_total</CENTER></TD>\n");
			}
			
			#$combo_table_count = TestDrawTable($draw); #fix 001
			$combo_table_count = 0; #fix 001

			if ($combo_table_count > 0) {
				$combo_table = 'Y';
			}

			if ($combo_table == 'N') {
				print("<TD BGCOLOR=\"#ff0000\"><CENTER><B><FONT COLOR=\"#ffffff\">$combo_table</FONT></B></CENTER></TD>\n");
				$table_draw_tot++;
			} else {
				print("<TD><CENTER>$combo_table</CENTER></TD>\n");
			}

			#$filter_a_table_count = TestFilterATable($draw); #fix 001

			if ($filter_a_table_count > 0) {
				$filter_a_table = 'Y';
			}

			if ($filter_a_table == 'N') {
				print("<TD BGCOLOR=\"#ff0000\"><CENTER><B><FONT COLOR=\"#ffffff\">$filter_a_table</FONT></B></CENTER></TD>\n");
				$filter_a_tot++;
			} else {
				print("<TD><CENTER>$filter_a_table</CENTER></TD>\n");
			}

			#$filter_b_table_count = TestFilterBTable($draw); #fix 001

			if ($filter_b_table_count > 0) {
				$filter_b_table = 'Y';
			}

			if ($filter_b_table == 'N') {
				print("<TD BGCOLOR=\"#ff0000\"><CENTER><B><FONT COLOR=\"#ffffff\">$filter_b_table</FONT></B></CENTER></TD>\n");
				$filter_b_tot++;
			} else {
				print("<TD><CENTER>$filter_b_table</CENTER></TD>\n");
			}

			#$filter_c_table_count = TestFilterCTable($draw); #fix 001

			if ($filter_c_table_count > 0) {
				$filter_c_table = 'Y';
			}

			if ($filter_c_table == 'N') {
				print("<TD BGCOLOR=\"#ff0000\"><CENTER><B><FONT COLOR=\"#ffffff\">$filter_c_table</FONT></B></CENTER></TD>\n");
				$filter_c_tot++;
			} else {
				print("<TD><CENTER>$filter_c_table</CENTER></TD>\n");
			}

			#$filter_d_table_count = TestFilterDTable($draw); #fix 001

			if ($filter_d_table_count > 0) {
				$filter_d_table = 'Y';
			}

			if ($filter_d_table == 'N') {
				print("<TD BGCOLOR=\"#ff0000\"><CENTER><B><FONT COLOR=\"#ffffff\">$filter_d_table</FONT></B></CENTER></TD>\n");
				$filter_d_tot++;
			} else {
				print("<TD><CENTER>$filter_d_table</CENTER></TD>\n");
			}

			print("</TR>\n");
		}

		print("<TR>\n");
		print("<TD>&nbsp;</TD>\n");

		for ($x = 1; $x <= $balls_drawn; $x++)
		{
			print("<TD>&nbsp;</TD>\n");
		}

		//print("<h2>sum_tot = $sum_tot</h2>");
		
		$num_temp = number_format($sum_tot/$limit,2);
		print("<TD><CENTER>$num_temp</CENTER></TD>\n");

		$num_temp = number_format($width_tot/$limit,2);
		print("<TD><CENTER>$num_temp</CENTER></TD>\n");

		for ($x = 0; $x <= $draw_count_columns; $x++)
		{	
			$num_temp = number_format($draw_count_tot[$x]/($limit*$balls_drawn),2)*100;
			print("<TD><CENTER>$num_temp%</CENTER></TD>\n");
		}

		print("<TD>&nbsp;</TD>\n");
		print("<TD>&nbsp;</TD>\n");
		print("<TD>&nbsp;</TD>\n");
		print("<TD>&nbsp;</TD>\n");

		$num_temp = number_format($x10_2_tot/$limit,2)*100;
		print("<TD><CENTER><font color=\"#ff0000\">$num_temp%</font></CENTER></TD>\n");
		$num_temp = number_format($x10_3_tot/$limit,2)*100;
		print("<TD><CENTER><font color=\"#ff0000\">$num_temp%</font></CENTER></TD>\n");

		$num_temp = number_format($seq2_tot/$limit,2)*100;
		print("<TD><CENTER><font color=\"#ff0000\">$num_temp%</font></CENTER></TD>\n");
		$num_temp = number_format($seq3_tot/$limit,2)*100;
		print("<TD><CENTER><font color=\"#ff0000\">$num_temp%</font></CENTER></TD>\n");
		$num_temp = number_format($mod_tot/$limit,2)*100;
		print("<TD><CENTER><font color=\"#ff0000\">$num_temp%</font></CENTER></TD>\n");

		$num_temp = number_format($hot_sum/($limit*6),2)*100;
		print("<TD><CENTER>$num_temp%</CENTER></TD>\n");
		$num_temp = number_format($warm_sum/($limit*6),2)*100;
		print("<TD><CENTER>$num_temp%</CENTER></TD>\n");
		$num_temp = number_format($cold_sum/($limit*6),2)*100;
		print("<TD><CENTER>$num_temp%</CENTER></TD>\n");

		$num_temp = number_format($wheel_tot/$limit,2)*100;
		print("<TD colspan=2><CENTER><font color=\"#ff0000\">$num_temp%</font></CENTER></TD>\n");

		$num_temp = number_format($table_draw_tot/$limit,2)*100;
		print("<TD><CENTER><font color=\"#ff0000\">$num_temp%</font></CENTER></TD>\n");

		$num_temp = number_format($filter_a_tot/$limit,2)*100;
		print("<TD><CENTER><font color=\"#ff0000\">$num_temp%</font></CENTER></TD>\n");
		$num_temp = number_format($filter_b_tot/$limit,2)*100;
		print("<TD><CENTER><font color=\"#ff0000\">$num_temp%</font></CENTER></TD>\n");
		$num_temp = number_format($filter_c_tot/$limit,2)*100;
		print("<TD><CENTER><font color=\"#ff0000\">$num_temp%</font></CENTER></TD>\n");
		$num_temp = number_format($triplet_tot/$limit,2)*100;
		print("<TD><CENTER><font color=\"#ff0000\">$num_temp%</font></CENTER></TD>\n");

		print("</TR>\n");
		print("</TABLE>\n");
		print("<h5>Filter A: sum >= 47, sum <= 124, col1 >= 1, col1 <= 12, seq2 <= 1, seq3 = 0, mod <= 2</h5>");
	}

	// ----------------------------------------------------------------------------------
	function test_table_update($row,$draw_table)
	{
		global $debug,$width,$seq2,$seq3,$dc1,$dc2,$dc3,$dt,$x10_1,$x10_1_tot,$x10_2,$x10_2_tot,$x10_3,	$x10_3_tot,$mod,$due,$due_tot,$soon,$soon_tot,$hot,$hot_tot,$warm,$warm_tot,$cold,$cold_tot,
		$combo_table,$wheel,$el0;

		require ("includes/mysqli.php"); 

		$dc1 = 0;
		$dc2 = 0;
		$dc3 = 0;
		$x10_1 = 0;
		$x10_1_tot = 0;
		$x10_2 = 0;
		$x10_2_tot = 0;
		$x10_3 = 0;
		$x10_3_tot = 0;
		$mod = 0;
		$due = 0;
		$due_tot = 0;
		$soon = 0;
		$soon_tot = 0;
		$hot = 0;
		$hot_tot = 0;
		$warm = 0;
		$warm_tot = 0;
		$cold = 0;
		$cold_tot = 0;
		$dt = 'N';
		$combo_table = 'N';
		$wheel = 'N';
		$el0 = 0;
		$eliminate_count = 0;
		$eliminate_total = 0;
		$eliminate_sum = 0;
		$table_draw_tot = 0;
		$wheel_tot = 0;

		$draw = array ($row[b1],$row[b2],$row[b3],$row[b4],$row[b5],$row[b6]);

		$due_nums = array();
		$soon_nums = array();
		$hot_nums = array();
		$warm_nums = array();
		$cold_nums = array();
		$draw_count = array();

		$eliminate_count = 0;
		$eliminate_total = 0;

		$width = $row[b6] - $row[b1];

		$seq2 = Count2Seq($draw);

		$seq3 = Count3Seq($draw);

		$draw_count = calculate_draw_count($draw);

		draw_1_2_3($draw_count,$dc1,$dc2,$dc3);

		$a = $draw_count[0];
		$b = $draw_count[1];

		print "draw_table[$a][$b] = {$draw_table[$a][$b]}<br>";

		if ($draw_table[$a][$b] == 1) {
			$dt = 'Y';
		}

		$x10_array = Last10Count($row[date]);

		// x10_1
		$x10_count = 0;

		for ($x = 1 ; $x <= 6; $x++)
		{
			if ($x10_array[$row[$x]] >= 1)
			{
				$x10_1++;
			}
		}

		for ($x = 1 ; $x <= 53; $x++)
		{
			if ($x10_array[$x] >= 1)
			{
				$x10_1_tot++;
			}
		}

		// x10_2
		$x10_count = 0;

		for ($x = 1 ; $x <= 6; $x++)
		{
			if ($x10_array[$row[$x]] >= 2)
			{
				$x10_2++;
			}
		}

		for ($x = 1 ; $x <= 53; $x++)
		{
			if ($x10_array[$x] >= 2)
			{
				$x10_2_tot++;
			}
		}

		// x10_3
		$x10_count = 0;

		for ($x = 1 ; $x <= 6; $x++)
		{
			if ($x10_array[$row[$x]] >= 3)
			{
				$x10_3++;
			}
		}

		for ($x = 1 ; $x <= 53; $x++)
		{
			if ($x10_array[$x] >= 3)
			{
				$x10_3_tot++;
			}
		}

		$mod = CountMod($draw);

		$date_nodash = str_replace("-","", $row[date]);

		$due = TableDrawCount("fl_due_$date_nodash", $draw, $due_tot);

		$soon = TableDrawCount("fl_soon_$date_nodash", $draw, $soon_tot);

		$hot = TableDrawCount("fl_hot_$date_nodash", $draw, $hot_tot);

		$warm = TableDrawCount("fl_warm_$date_nodash", $draw, $warm_tot);

		$cold = TableDrawCount("fl_cold_$date_nodash", $draw, $cold_tot);

		$combo_table_count = TestDrawTable($draw);

		if ($combo_table_count > 0) {
			$combo_table = 'Y';
		}

		$table_wheel_percent = TestWheelTable($row[even],$row[odd],$row[d501],$row[d502],$row[sum]);

		if ($table_wheel_percent > 0.0) {
			$wheel = $table_wheel_percent;
		}

		for ($z = 0; $z < 5; $z++)
		{
			$eliminate_count = TableEliminateCount("fl_pair_eliminate_$date_nodash", $draw[$z], $draw[$z+1]);

			if ($eliminate_count == -1)
			{
				$eliminate_total = -1;
				break;
			}
			else
			{
				$eliminate_total += $eliminate_count;
			}
		}

		$el0 += $eliminate_total;
		
		$query_test = "INSERT INTO $draw_prefix";
		$query_test .= "test_table ";
		$query_test .= "VALUES ('$row[date]', ";
		$query_test .= "'$width', ";
		$query_test .= "'$seq2', ";
		$query_test .= "'$seq3', ";
		$query_test .= "'$dc1', ";
		$query_test .= "'$dc2', ";
		$query_test .= "'$dc3', ";
		$query_test .= "'$dt', ";
		$query_test .= "'$x10_1', ";
		$query_test .= "'$x10_1_tot', ";
		$query_test .= "'$x10_2', ";
		$query_test .= "'$x10_2_tot', ";
		$query_test .= "'$x10_3', ";
		$query_test .= "'$x10_3_tot', ";
		$query_test .= "'$mod', ";
		$query_test .= "'$due', ";
		$query_test .= "'$due_tot', ";
		$query_test .= "'$soon', ";
		$query_test .= "'$soon_tot', ";
		$query_test .= "'$hot', ";
		$query_test .= "'$hot_tot', ";
		$query_test .= "'$warm', ";
		$query_test .= "'$warm_tot', ";
		$query_test .= "'$cold', ";
		$query_test .= "'$cold_tot', ";
		$query_test .= "'$combo_table', ";
		$query_test .= "'$wheel', ";
		$query_test .= "'$el0')";

		$mysqli_result_test = mysqli_query($query_test, $mysqli_link) or die (mysqli_error($mysqli_link));
	}

	// ----------------------------------------------------------------------------------
	function print_dup_table($limit,$min_max)
	{
		global $debug,$min_test,$max_test,$date_test,$game,$draw_prefix,$game_includes, $hml, $range_low, $range_high;

		require ("includes/mysqli.php"); 

		require ("$game_includes/config.incl");

		$last_dup_tot = array_fill (0, 51, 0);

		$query = "SELECT * FROM $draw_prefix";
		$query .= "draws ";
		if ($hml)
		{
			$query .= "WHERE sum >= $range_low  ";
			$query .= "AND   sum <= $range_high  ";
		}
		if ($game == 10)
		{
			$query .= "ORDER BY id DESC ";
		} else {
			$query .= "ORDER BY date DESC ";
		}
		$query .= "LIMIT $limit ";

		print "$query<p>";

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

		if ($min_max == 0)
		{
			print("<h3>Dup Report Limit - Last $limit draws</h3>\n");
			$min_test = array_fill (0, $limit + 1, 0);
		} else {
			print("<h3>Dup Report Minimum - Last $limit draws</h3>\n");
			$max_test = array_fill (0, $limit + 1, 0);
			$date_test = array_fill (0, $limit + 1, 0);
		}

		print("<TABLE BORDER=\"1\">\n");

		//header
		print("<TR><B>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Date</TD>\n");

		for ($x = 1; $x <= 50; $x++)
		{
			print("<TD BGCOLOR=\"#CCCCCC\">Dup$x</TD>\n");
		}

		print("</B></TR>\n");

		//header
		print("<TR><B>\n");
		print("<TD>&nbsp;</TD>\n");

		for ($x = 1; $x <= 50; $x++)
		{
			if ($min_max == 0)
			{
				print("<TD><center><b>$dup_count_limit[$x]</b></center></TD>\n");
			} else {
				print("<TD><center><b>$dup_count_minim[$x]</b></center></TD>\n");
			}
		}

		print("</B></TR>\n");

		$last_dup_tot = array_fill (0, 51, 0);

		$z = 1;

		// get each row
		while($row = mysqli_fetch_array($mysqli_result))
		{
			$last_dup = array_fill (0, 51, 0);

			$query_dup = "SELECT * FROM $draw_prefix";
			if ($hml)
			{
				$query_dup .= "dup_table_";
				$query_dup .= "$hml ";
			} else {
				$query_dup .= "dup_table ";
			}
			if ($game == 10)
			{
				$query_dup .= "WHERE id = $row[id] ";
			} else {
				$query_dup .= "WHERE date = '$row[date]' ";
			}

			#echo "$query_dup<br>";

			$mysqli_result_dup = mysqli_query($query_dup, $mysqli_link) or die (mysqli_error($mysqli_link));

			if ($row_exist = mysqli_num_rows($mysqli_result_dup))
			{
				$row_dup = mysqli_fetch_array($mysqli_result_dup);

				for ($x = 1 ; $x <= 50; $x++)
				{
					$last_dup[$x] = $row_dup[$x];
				}
			} 
			else
			{
				$last_dup = dup_table_update($row);
			}

			// body
			print("<TR>\n");

			print("<TD NOWRAP><CENTER>$row[date]</CENTER></TD>\n");

			if ($game == 10)
			{
				if ($min_max == 0)
				{
					for ($x = 2 ; $x <= 51; $x++)
					{
						if ($last_dup[$x] == $dup_count_limit[$x]) {
							print("<TD><CENTER><B><font color=\"#FF0000\">$last_dup[$x]</font></B></CENTER></TD>\n");
						} elseif ($last_dup[$x] > $dup_count_limit[$x]) {
							print("<TD BGCOLOR=\"#FF0000\"><CENTER><B>$last_dup[$x]</B></CENTER></TD>\n");
							$last_dup_tot[$x]++;
							$min_test[$z]++;
						} else {
							print("<TD><CENTER>$last_dup[$x]</CENTER></TD>\n");
						}
					}
				} else {
					for ($x = 2 ; $x <= 51; $x++)
					{
						if ($last_dup[$x] == $dup_count_minim[$x]) {
							print("<TD><CENTER><B><font color=\"#FF0000\">$last_dup[$x]</font></B></CENTER></TD>\n");
						} elseif ($last_dup[$x] < $dup_count_minim[$x]) {
							print("<TD BGCOLOR=\"#FF9900\"><CENTER><B>$last_dup[$x]</B></CENTER></TD>\n");
							$last_dup_tot[$x]++;
							$max_test[$z]++;
						} else {
							print("<TD><CENTER>$last_dup[$x]</CENTER></TD>\n");
						}
					}
					$date_test[$z] = $row[date];
				}
			} else {
				if ($min_max == 0)
				{
					for ($x = 1 ; $x <= 50; $x++)
					{
						if ($last_dup[$x] == $dup_count_limit[$x]) {
							print("<TD><CENTER><B><font color=\"#FF0000\">$last_dup[$x]</font></B></CENTER></TD>\n");
						} elseif ($last_dup[$x] > $dup_count_limit[$x]) {
							print("<TD BGCOLOR=\"#FF0000\"><CENTER><B>$last_dup[$x]</B></CENTER></TD>\n");
							$last_dup_tot[$x]++;
							$min_test[$z]++;
						} else {
							print("<TD><CENTER>$last_dup[$x]</CENTER></TD>\n");
						}
					}
				} else {
					for ($x = 1 ; $x <= 50; $x++)
					{
						if ($last_dup[$x] == $dup_count_minim[$x]) {
							print("<TD><CENTER><B><font color=\"#FF0000\">$last_dup[$x]</font></B></CENTER></TD>\n");
						} elseif ($last_dup[$x] < $dup_count_minim[$x]) {
							print("<TD BGCOLOR=\"#FF9900\"><CENTER><B>$last_dup[$x]</B></CENTER></TD>\n");
							$last_dup_tot[$x]++;
							$max_test[$z]++;
						} else {
							print("<TD><CENTER>$last_dup[$x]</CENTER></TD>\n");
						}
					}
					$date_test[$z] = $row[date];
				}
			}

			print("</TR>\n");
			$z++;
			$last_date = $row[date];
		}

		// footer
		print("<TR>\n");
		print("<TD>&nbsp;</TD>\n");

		for ($x = 1 ; $x <= 50; $x++)
		{
			$num_temp = number_format($last_dup_tot[$x]/$limit,2)*100;
			print("<TD><CENTER>$num_temp%</CENTER></TD>\n");
		}
		
		print("</TR>\n");
		print("</TABLE>\n");

		if ($min_max)
		{
			print "<p><b>Summary</b></p>";

			print("<table border=\"1\">\n");

			print("<tr>\n");
				print("<TD WIDTH=20 BGCOLOR=\"#CCCCCC\" align=center align=center>1</TD>\n");
				print("<TD WIDTH=20 BGCOLOR=\"#CCCCCC\" align=center align=center>2</TD>\n");
				print("<TD WIDTH=20 BGCOLOR=\"#CCCCCC\" align=center align=center>3</TD>\n");
				print("<TD WIDTH=20 BGCOLOR=\"#CCCCCC\" align=center align=center>4</TD>\n");
				print("<TD WIDTH=20 BGCOLOR=\"#CCCCCC\" align=center>Count</TD>\n");
			print("</tr>\n");

			for ($k = 0; $k <= 3; $k++)
			{
				print("<tr>\n");
					$query_dup = "SELECT * FROM $draw_prefix";
					$query_dup .= "dup_table a ";
					$query_dup .= "JOIN $draw_prefix";
					$query_dup .= "draws b ON ";
					$query_dup .= "a.date = b.date ";
					$query_dup .= "WHERE a.dup1 = $k ";
					$query_dup .= "AND   a.date >= '$last_date' ";
					if ($hml)
					{
						$query_dup .= "AND   b.sum >= $range_low  ";
						$query_dup .= "AND   b.sum <= $range_high  ";
					}

					#echo "$query_dup<br>";

					$mysqli_result_dup = mysqli_query($query_dup, $mysqli_link) or die (mysqli_error($mysqli_link));

					$num_rows = mysqli_num_rows($mysqli_result_dup); 
					
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

			for ($j = 0; $j <= 2; $j++)
			{
				for ($k = $j; $k <= 3; $k++)
				{
					print("<tr>\n");
						$query_dup = "SELECT * FROM $draw_prefix";
						$query_dup .= "dup_table a ";
						$query_dup .= "JOIN $draw_prefix";
						$query_dup .= "draws b ON ";
						$query_dup .= "a.date = b.date ";
						$query_dup .= "WHERE a.dup1 = $j ";
						$query_dup .= "AND   a.dup2 = $k ";
						$query_dup .= "AND   a.date >= '$last_date' ";
						if ($hml)
						{
							$query_dup .= "AND   b.sum >= $range_low  ";
							$query_dup .= "AND   b.sum <= $range_high  ";
						}

						#echo "$query_dup<br>";	

						$mysqli_result_dup = mysqli_query($query_dup, $mysqli_link) or die (mysqli_error($mysqli_link));

						$num_rows = mysqli_num_rows($mysqli_result_dup); 
						
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

			for ($i = 0; $i <= 2; $i++)
			{
				for ($j = $i; $j <= 3; $j++)
				{
					for ($k = $j; $k <= 4; $k++)
					{
						print("<tr>\n");
							$query_dup = "SELECT * FROM $draw_prefix";
							$query_dup .= "dup_table a ";
							$query_dup .= "JOIN $draw_prefix";
							$query_dup .= "draws b ON ";
							$query_dup .= "a.date = b.date ";
							$query_dup .= "WHERE a.dup1 = $i ";
							$query_dup .= "AND   a.dup2 = $j ";
							$query_dup .= "AND   a.dup3 = $k ";
							$query_dup .= "AND   a.date >= '$last_date' ";
							if ($hml)
							{
								$query_dup .= "AND   b.sum >= $range_low  ";
								$query_dup .= "AND   b.sum <= $range_high  ";
							}

							$mysqli_result_dup = mysqli_query($query_dup, $mysqli_link) or die (mysqli_error($mysqli_link));

							$num_rows = mysqli_num_rows($mysqli_result_dup); 
							
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

			for ($i = 0; $i <= 2; $i++)
			{
				for ($j = $i; $j <= 2; $j++)
				{
					for ($k = $j; $k <= 3; $k++)
					{
						for ($l = $k; $l <= 4; $l++)
						{
							print("<tr>\n");
								$query_dup = "SELECT * FROM $draw_prefix";
								$query_dup .= "dup_table a ";
								$query_dup .= "JOIN $draw_prefix";
								$query_dup .= "draws b ON ";
								$query_dup .= "a.date = b.date ";
								$query_dup .= "WHERE a.dup1 = $i ";
								$query_dup .= "AND   a.dup2 = $j ";
								$query_dup .= "AND   a.dup3 = $k ";
								$query_dup .= "AND   a.dup4 = $l ";
								$query_dup .= "AND   a.date >= '$last_date' ";
								if ($hml)
								{
									$query_dup .= "AND   b.sum >= $range_low  ";
									$query_dup .= "AND   b.sum <= $range_high  ";
								}

								$mysqli_result_dup = mysqli_query($query_dup, $mysqli_link) or die (mysqli_error($mysqli_link));

								$num_rows = mysqli_num_rows($mysqli_result_dup); 
								
								print("<TD align=center align=center>$i</TD>\n");
								print("<TD align=center align=center>$j</TD>\n");
								print("<TD align=center align=center>$k</TD>\n");
								print("<TD align=center align=center>$l</TD>\n");
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
	}

	// ----------------------------------------------------------------------------------
	function update_dup_table($limit,$min_max)
	{
		global $debug,$min_test,$max_test,$date_test,$game,$draw_prefix,$game_includes,$hml;

		require ("includes/mysqli.php"); 

		require ("$game_includes/config.incl");

		$last_dup_tot = array_fill (0, 51, 0);

		$query = "SELECT * FROM $draw_prefix";
		$query .= "draws ";
		$query .= "ORDER BY date DESC ";
		$query .= "LIMIT $limit ";

		#echo "$query<br>";

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

		// get each row
		while($row = mysqli_fetch_array($mysqli_result))
		{
			$last_dup = array_fill (0, 51, 0);

			$query_dup = "SELECT * FROM $draw_prefix";
			if ($hml)
			{
				$query_dup .= "dup_table_";
				$query_dup .= "$hml ";
			} else {
				$query_dup .= "dup_table ";
			}
			$query_dup .= "WHERE date = '$row[date]' ";

			#echo "$query_dup<br>";

			$mysqli_result_dup = mysqli_query($query_dup, $mysqli_link) or die (mysqli_error($mysqli_link));

			if ($row_exist = mysqli_num_rows($mysqli_result_dup))
			{
				$row_dup = mysqli_fetch_array($mysqli_result_dup);

				for ($x = 1 ; $x <= 50; $x++)
				{
					$last_dup[$x] = $row_dup[$x];
				}
			} 
			else
			{
				$last_dup = dup_table_update($row);
			}

			// body
			print("<TR>\n");

			print("<TD NOWRAP><CENTER>$row[date]</CENTER></TD>\n");
			
			if ($min_max == 0)
			{
				for ($x = 1 ; $x <= 50; $x++)
				{
					if ($last_dup[$x] == $dup_count_limit[$x]) {
						print("<TD><CENTER><B><font color=\"#FF0000\">$last_dup[$x]</font></B></CENTER></TD>\n");
					} elseif ($last_dup[$x] > $dup_count_limit[$x]) {
						print("<TD BGCOLOR=\"#FF0000\"><CENTER><B>$last_dup[$x]</B></CENTER></TD>\n");
						$last_dup_tot[$x]++;
						$min_test[$z]++;
					} else {
						print("<TD><CENTER>$last_dup[$x]</CENTER></TD>\n");
					}
				}
			} else {
				for ($x = 1 ; $x <= 50; $x++)
				{
					if ($last_dup[$x] == $dup_count_minim[$x]) {
						print("<TD><CENTER><B><font color=\"#FF0000\">$last_dup[$x]</font></B></CENTER></TD>\n");
					} elseif ($last_dup[$x] < $dup_count_minim[$x]) {
						print("<TD BGCOLOR=\"#FF9900\"><CENTER><B>$last_dup[$x]</B></CENTER></TD>\n");
						$last_dup_tot[$x]++;
						$max_test[$z]++;
					} else {
						print("<TD><CENTER>$last_dup[$x]</CENTER></TD>\n");
					}
				}
				$date_test[$z] = $row[date];
			}

			print("</TR>\n");
			$z++;
			$last_date = $row[date];
		}

		// footer
		print("<TR>\n");
		print("<TD>&nbsp;</TD>\n");

		for ($x = 1 ; $x <= 50; $x++)
		{
			$num_temp = number_format($last_dup_tot[$x]/$limit,2)*100;
			print("<TD><CENTER>$num_temp%</CENTER></TD>\n");
		}
		
		print("</TR>\n");
		print("</TABLE>\n");

		if ($min_max)
		{
			print "<p><b>Summary</b></p>";

			print("<table border=\"1\">\n");

			print("<tr>\n");
				print("<TD WIDTH=20 BGCOLOR=\"#CCCCCC\" align=center align=center>0</TD>\n");
				print("<TD WIDTH=20 BGCOLOR=\"#CCCCCC\" align=center align=center>1</TD>\n");
				print("<TD WIDTH=20 BGCOLOR=\"#CCCCCC\" align=center align=center>2</TD>\n");
				print("<TD WIDTH=20 BGCOLOR=\"#CCCCCC\" align=center>Count</TD>\n");
			print("</tr>\n");

			for ($k = 0; $k <= 2; $k++)
			{
				print("<tr>\n");
					$query_dup = "SELECT * FROM $draw_prefix";
					$query_dup .= "dup_table ";
					$query_dup .= "WHERE dup1 = $k ";
					$query_dup .= "AND date >= '$last_date' ";

					$mysqli_result_dup = mysqli_query($query_dup, $mysqli_link) or die (mysqli_error($mysqli_link));

					$num_rows = mysqli_num_rows($mysqli_result_dup); 
					
					print("<TD align=center align=center>$k</TD>\n");
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

			for ($j = 0; $j <= 2; $j++)
			{
				for ($k = $j; $k <= 2; $k++)
				{
					print("<tr>\n");
						$query_dup = "SELECT * FROM $draw_prefix";
						$query_dup .= "dup_table ";
						$query_dup .= "WHERE dup1 = $j ";
						$query_dup .= "AND   dup2 = $k ";
						$query_dup .= "AND date >= '$last_date' ";

						$mysqli_result_dup = mysqli_query($query_dup, $mysqli_link) or die (mysqli_error($mysqli_link));

						$num_rows = mysqli_num_rows($mysqli_result_dup); 
						
						print("<TD align=center align=center>$j</TD>\n");
						print("<TD align=center align=center>$k</TD>\n");
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

			for ($i = 0; $i <= 2; $i++)
			{
				for ($j = $i; $j <= 2; $j++)
				{
					for ($k = $j; $k <= 3; $k++)
					{
						print("<tr>\n");
							$query_dup = "SELECT * FROM $draw_prefix";
							$query_dup .= "dup_table ";
							$query_dup .= "WHERE dup1 = $i ";
							$query_dup .= "AND   dup2 = $j ";
							$query_dup .= "AND   dup3 = $k ";
							$query_dup .= "AND date >= '$last_date' ";

							$mysqli_result_dup = mysqli_query($query_dup, $mysqli_link) or die (mysqli_error($mysqli_link));

							$num_rows = mysqli_num_rows($mysqli_result_dup); 
							
							print("<TD align=center align=center>$i</TD>\n");
							print("<TD align=center align=center>$j</TD>\n");
							print("<TD align=center align=center>$k</TD>\n");
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
			
			print("</table>\n");
		}
	}

	// ----------------------------------------------------------------------------------
	function dup_table_update($row)
	{
		global $debug,$min_test,$max_test,$date_test,$draw_prefix,$balls_drawn,$hml,$range_low,$range_high;

		require ("includes/mysqli.php"); 

		$last_dup = array_fill (0, 51, 0);

		if ($game == 10)
		{
			for ($x = 1; $x <= 50; $x++)
			{
				${last_.$x._draws} = LastDraws($row[id],$x);
			}
		} else {
			for ($x = 1; $x <= 50; $x++)
			{
				${last_.$x._draws} = LastDraws($row[date],$x);
			}
		}

		//count repeating numbers
		for ($x = 1 ; $x <= 50; $x++)
		{
			for ($y = 1 ; $y <= $balls_drawn; $y++)
			{	
				if (array_search($row[d.$y], ${last_.$x._draws}) !== FALSE)
				{
					$last_dup[$x]++;
				}
			}
		}

		$query_dup = "INSERT INTO $draw_prefix";
		if ($hml)
		{
			$query_dup .= "dup_table_";
			$query_dup .= "$hml ";
		} else {
			$query_dup .= "dup_table ";
		}
		$query_dup .= "VALUES ('$row[date]', ";
		if ($game == 10)
		{
			$query_dup .= "'$row[id]', ";
		}
		$query_dup .= "'$last_dup[1]', ";
		$query_dup .= "'$last_dup[2]', ";
		$query_dup .= "'$last_dup[3]', ";
		$query_dup .= "'$last_dup[4]', ";
		$query_dup .= "'$last_dup[5]', ";
		$query_dup .= "'$last_dup[6]', ";
		$query_dup .= "'$last_dup[7]', ";
		$query_dup .= "'$last_dup[8]', ";
		$query_dup .= "'$last_dup[9]', ";
		$query_dup .= "'$last_dup[10]', ";
		$query_dup .= "'$last_dup[11]', ";
		$query_dup .= "'$last_dup[12]', ";
		$query_dup .= "'$last_dup[13]', ";
		$query_dup .= "'$last_dup[14]', ";
		$query_dup .= "'$last_dup[15]', ";
		$query_dup .= "'$last_dup[16]', ";
		$query_dup .= "'$last_dup[17]', ";
		$query_dup .= "'$last_dup[18]', ";
		$query_dup .= "'$last_dup[19]', ";
		$query_dup .= "'$last_dup[20]', ";
		$query_dup .= "'$last_dup[21]', ";
		$query_dup .= "'$last_dup[22]', ";
		$query_dup .= "'$last_dup[23]', ";
		$query_dup .= "'$last_dup[24]', ";
		$query_dup .= "'$last_dup[25]', ";
		$query_dup .= "'$last_dup[26]', ";
		$query_dup .= "'$last_dup[27]', ";
		$query_dup .= "'$last_dup[28]', ";
		$query_dup .= "'$last_dup[29]', ";
		$query_dup .= "'$last_dup[30]', ";
		$query_dup .= "'$last_dup[31]', ";
		$query_dup .= "'$last_dup[32]', ";
		$query_dup .= "'$last_dup[33]', ";
		$query_dup .= "'$last_dup[34]', ";
		$query_dup .= "'$last_dup[35]', ";
		$query_dup .= "'$last_dup[36]', ";
		$query_dup .= "'$last_dup[37]', ";
		$query_dup .= "'$last_dup[38]', ";
		$query_dup .= "'$last_dup[39]', ";
		$query_dup .= "'$last_dup[40]', ";
		$query_dup .= "'$last_dup[41]', ";
		$query_dup .= "'$last_dup[42]', ";
		$query_dup .= "'$last_dup[43]', ";
		$query_dup .= "'$last_dup[44]', ";
		$query_dup .= "'$last_dup[45]', ";
		$query_dup .= "'$last_dup[46]', ";
		$query_dup .= "'$last_dup[47]', ";
		$query_dup .= "'$last_dup[48]', ";
		$query_dup .= "'$last_dup[49]', ";
		$query_dup .= "'$last_dup[50]')";

		#echo "$query_dup<br>";

		$mysqli_result_dup = mysqli_query($query_dup, $mysqli_link) or die (mysqli_error($mysqli_link));

		return ($last_dup);
	}

	// ----------------------------------------------------------------------------------
	function combo_table_update($date,$total2,$total3,$total4,$total5)
	{
		global $debug,$min_test,$max_test,$date_test,$draw_prefix,$balls_drawn;

		require ("includes/mysqli.php"); 

		$query_combo = "INSERT INTO $draw_prefix";
		$query_combo .= "combo_table ";
		$query_combo .= "VALUES ('$date', ";
		$query_combo .= "'$total2', ";
		$query_combo .= "'$total3', ";
		$query_combo .= "'$total4', ";
		$query_combo .= "'$total5')";

		#echo "$query_combo<br>";

		$mysqli_result_combo = mysqli_query($query_combo, $mysqli_link) or die (mysqli_error($mysqli_link));
	}

	// ----------------------------------------------------------------------------------
	function combo_count_table_update($date,$day_draw,$total2,$total3,$total4,$total5)
	{
		global $debug,$min_test,$max_test,$date_test,$draw_prefix,$balls_drawn;

		require ("includes/mysqli.php"); 

		$query_combo = "INSERT INTO $draw_prefix";
		$query_combo .= "combo_count_table ";
		$query_combo .= "VALUES ('$date', ";
		if ($game == 10)
		{
			$query_combo .= "'$day_draw', ";
		}
		$query_combo .= "'$total2', ";
		$query_combo .= "'$total3', ";
		$query_combo .= "'$total4', ";
		$query_combo .= "'$total5')";

		#echo "$query_combo<br>";

		$mysqli_result_combo = mysqli_query($query_combo, $mysqli_link) or die (mysqli_error($mysqli_link));
	}

	// ----------------------------------------------------------------------------------
	function print_dup_table_date($limit,$min_max,$date)
	{
		global $debug,$min_test,$max_test,$date_test,$game,$draw_prefix,$game_includes, $hml, $range_low, $range_high;

		require ("includes/mysqli.php"); 

		require ("$game_includes/config.incl");

		$last_dup_tot = array_fill (0, 51, 0);

		$query = "SELECT * FROM $draw_prefix";
		$query .= "draws ";
		$query .= "ORDER BY date DESC ";
		$query .= "LIMIT $limit ";

		//print "$query<p>";

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

		if ($min_max == 0)
		{
			print("<h3>Dup Report Limit - Last $limit draws - filter date cutoff $date</h3>\n");
			$min_test = array_fill (0, $limit + 1, 0);
		} else {
			print("<h3>Dup Report Minimum - Last $limit draws - filter date cutoff $date</h3>\n");
			$max_test = array_fill (0, $limit + 1, 0);
			$date_test = array_fill (0, $limit + 1, 0);
		}

		print("<TABLE BORDER=\"1\">\n");

		//header
		print("<TR><B>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Date</TD>\n");

		for ($x = 1; $x <= 50; $x++)
		{
			print("<TD BGCOLOR=\"#CCCCCC\">Dup$x</TD>\n");
		}

		print("</B></TR>\n");

		//header 2
		print("<TR><B>\n");
		print("<TD>&nbsp;</TD>\n");

		for ($x = 1; $x <= 50; $x++)
		{
			if ($min_max == 0)
			{
				print("<TD><center><b>$dup_count_limit[$x]</b></center></TD>\n");
			} else {
				print("<TD><center><b>$dup_count_minim[$x]</b></center></TD>\n");
			}
		}

		print("</B></TR>\n");

		$last_dup_tot = array_fill (0, 51, 0);

		$z = 1;

		// get each row
		while($row = mysqli_fetch_array($mysqli_result))
		{
			$last_dup = array_fill (0, 51, 0);

			$query_dup = "SELECT * FROM $draw_prefix";
			$query_dup .= "draws ";
			$query_dup .= "WHERE date = '$row[date]' ";

			//print "$query_dup<p>";

			$mysqli_result_dup = mysqli_query($query_dup, $mysqli_link) or die (mysqli_error($mysqli_link));

			$row_exist = mysqli_num_rows($mysqli_result_dup);

			$last_dup = dup_table_update_fixed_date($date,$row);

			// body
			print("<TR>\n");

			print("<TD NOWRAP><CENTER>$row[date]</CENTER></TD>\n");
			
			if ($min_max == 0)
			{
				for ($x = 1 ; $x <= 50; $x++)
				{
					if ($last_dup[$x] == $dup_count_limit[$x]) {
						print("<TD><CENTER><B><font color=\"#FF0000\">$last_dup[$x]</font></B></CENTER></TD>\n");
					} elseif ($last_dup[$x] > $dup_count_limit[$x]) {
						print("<TD BGCOLOR=\"#FF0000\"><CENTER><B>$last_dup[$x]</B></CENTER></TD>\n");
						$last_dup_tot[$x]++;
						$min_test[$z]++;
					} else {
						print("<TD><CENTER>$last_dup[$x]</CENTER></TD>\n");
					}
				}
			} else {
				for ($x = 1 ; $x <= 50; $x++)
				{
					if ($last_dup[$x] == $dup_count_minim[$x]) {
						print("<TD><CENTER><B><font color=\"#FF0000\">$last_dup[$x]</font></B></CENTER></TD>\n");
					} elseif ($last_dup[$x] < $dup_count_minim[$x]) {
						print("<TD BGCOLOR=\"#FF9900\"><CENTER><B>$last_dup[$x]</B></CENTER></TD>\n");
						$last_dup_tot[$x]++;
						$max_test[$z]++;
					} else {
						print("<TD><CENTER>$last_dup[$x]</CENTER></TD>\n");
					}
				}
				$date_test[$z] = $row[date];
			}

			print("</TR>\n");
			$z++;
		}

		// footer
		print("<TR>\n");
		print("<TD>&nbsp;</TD>\n");

		for ($x = 1 ; $x <= 50; $x++)
		{
			$num_temp = number_format($last_dup_tot[$x]/$limit,2)*100;
			print("<TD><CENTER>$num_temp%</CENTER></TD>\n");
		}
		
		print("</TR>\n");
		print("</TABLE>\n");
	}

	// ----------------------------------------------------------------------------------
	function dup_table_update_fixed_date($date,$row)
	{
		global $debug,$min_test,$max_test,$date_test,$draw_prefix,$balls_drawn;

		require ("includes/mysqli.php"); 

		$last_dup = array_fill (0, 51, 0);

		for ($x = 1; $x <= 50; $x++)
		{
			${last_.$x._draws} = LastDraws($date,$x); // key point - does not move
		}

		//count repeating numbers
		for ($x = 1 ; $x <= 50; $x++)
		{
			for ($y = 1 ; $y <= $balls_drawn; $y++)
			{	
				if (array_search($row[$y], ${last_.$x._draws}) !== FALSE)
				{
					$last_dup[$x]++;
				}
			}
		}

		return ($last_dup);
	}

	// ----------------------------------------------------------------------------------
	function print_column_test($col)
	{
		global $debug, $draw_table_name, $balls, $balls_drawn, $draw_prefix, $game, $hml, $range_low, $range_high; 

		require ("includes/mysqli.php"); 
		require ("includes/unix.incl"); 

		$query4 = "DROP TABLE IF EXISTS $draw_prefix";
		$query4 .= "column_$col";
		if ($hml)
		{
			$query4 .= "_$hml";
		}

		#echo "$query4<p>";

		$mysqli_result4 = mysqli_query($mysqli_link, $query4) or die (mysqli_error($mysqli_link));

		$query4 = "CREATE TABLE $draw_prefix";
		$query4 .= "column_$col";
		if ($hml)
		{
			$query4 .= "_$hml (";
		} else {
			$query4 .= " (";
		}
		$query4 .= "`num` tinyint(3) unsigned NOT NULL,";
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
		$query4 .= "year5 int(5) unsigned NOT NULL default '0', ";
		$query4 .= "year6 int(5) unsigned NOT NULL default '0', ";
		$query4 .= "year7 int(5) unsigned NOT NULL default '0', ";
		$query4 .= "year8 int(5) unsigned NOT NULL default '0', ";
		$query4 .= "year9 int(5) unsigned NOT NULL default '0', ";
		$query4 .= "year10 int(5) unsigned NOT NULL default '0', ";
		$query4 .= "count int(5) unsigned NOT NULL default '0', ";
		$query4 .= "percent_y1 float(4,1) unsigned NOT NULL default '0', ";
		$query4 .= "percent_y5 float(4,1) unsigned NOT NULL default '0', ";
		$query4 .= "percent_wa float(4,1) unsigned NOT NULL default '0', ";
		$query4 .= "previous_date date NOT NULL default '1962-08-17', ";
		$query4 .= "last_date date NOT NULL default '1962-08-17', ";
		$query4 .= "PRIMARY KEY  (`num`),";
		$query4 .= "UNIQUE KEY `num_2` (`num`),";
		$query4 .= "KEY `num` (`num`)";
		$query4 .= ") ENGINE=InnoDB DEFAULT CHARSET=latin1;";

		#print "$query4<p>";
	
		$mysqli_result4 = mysqli_query($mysqli_link, $query4) or die (mysqli_error($mysqli_link));

		$query = "SELECT * FROM $draw_table_name ";
		if ($hml)
		{
			$query .= "WHERE sum >= $range_low  ";
			$query .= "AND   sum <= $range_high  ";
		}
		$query .= "ORDER BY date DESC ";

		#print "$query<p>";

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

		$num_rows_all = mysqli_num_rows($mysqli_result);

		//start table
		print("<h3>Column $col Test</h3>\n");
		print("<TABLE BORDER=\"1\">\n");

		//create header row
		print("<TR>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Num</TD>\n");
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
		print("</TR>\n");

		$draw = 1;

		$temp_array = array_fill (0,17,0);
		$column_count_array = array_fill (0,$balls+1,$temp_array);

		while($row = mysqli_fetch_array($mysqli_result))
		{
			$temp_draw = array (0);

			if ($game == 10)
			{
				for ($v = 1; $v <= $balls_drawn; $v++)
				{
					$w = $v + 2;
					array_push($temp_draw, $row[$w]);
				}
			} else {
				for ($v = 1; $v <= $balls_drawn; $v++)
				{
					array_push($temp_draw, $row[$v]);
				}
			}

			sort ($temp_draw);

			$x = $temp_draw[$col];

			$draw_date_array = date_parse("$row[date]");
			$draw_date_unix = mktime (0,0,0,$draw_date_array[month],$draw_date_array[day],$draw_date_array[year]);
			
			if ($draw_date_unix == $day1)
			{ 
				for ($d = 0; $d <= 15; $d++) {$column_count_array[$x][$d]++;}
			} elseif ($draw_date_unix > $week1) {
				for ($d = 1; $d <= 15; $d++) {$column_count_array[$x][$d]++;}
			} elseif ($draw_date_unix > $week2) {
				for ($d = 2; $d <= 15; $d++) {$column_count_array[$x][$d]++;}
			} elseif ($draw_date_unix > $month1) {
				for ($d = 3; $d <= 15; $d++) {$column_count_array[$x][$d]++;}
			} elseif ($draw_date_unix > $month3) {
				for ($d = 4; $d <= 15; $d++) {$column_count_array[$x][$d]++;}
			} elseif ($draw_date_unix > $month6) {
				for ($d = 5; $d <= 15; $d++) {$column_count_array[$x][$d]++;}
			} elseif ($draw_date_unix > $year1) {
				for ($d = 6; $d <= 15; $d++) {$column_count_array[$x][$d]++;}
			} elseif ($draw_date_unix > $year2) {
				for ($d = 7; $d <= 15; $d++) {$column_count_array[$x][$d]++;}
			} elseif ($draw_date_unix > $year3) {
				for ($d = 8; $d <= 15; $d++) {$column_count_array[$x][$d]++;}
			} elseif ($draw_date_unix > $year4) {
				for ($d = 9; $d <= 15; $d++) {$column_count_array[$x][$d]++;}
			} elseif ($draw_date_unix > $year5) {
				for ($d = 10; $d <= 15; $d++) {$column_count_array[$x][$d]++;}
			} elseif ($draw_date_unix > $year6) {
				for ($d = 11; $d <= 15; $d++) {$column_count_array[$x][$d]++;}
			} elseif ($draw_date_unix > $year7) {
				for ($d = 12; $d <= 15; $d++) {$column_count_array[$x][$d]++;}
			} elseif ($draw_date_unix > $year8) {
				for ($d = 13; $d <= 15; $d++) {$column_count_array[$x][$d]++;}
			} elseif ($draw_date_unix > $year9) {
				for ($d = 14; $d <= 15; $d++) {$column_count_array[$x][$d]++;}
			} elseif ($draw_date_unix > $year10) {
				for ($d = 15; $d <= 15; $d++) {$column_count_array[$x][$d]++;}
			}
	
			$column_count_array[$x][16]++;

			#add 1 year to clear
			if ($first_draw_unix > $year7) {
				for ($d = 13; $d <= 15; $d++) {$column_count_array[$x][$d]=0;}
			} elseif ($first_draw_unix > $year8) {
				for ($d = 14; $d <= 15; $d++) {$column_count_array[$x][$d]=0;}
			} elseif ($first_draw_unix > $year9) {
				for ($d = 15; $d <= 15; $d++) {$column_count_array[$x][$d]=0;}
			} elseif ($first_draw_unix > $year10) {
				for ($d = 16; $d <= 16; $d++) {$column_count_array[$x][$d]=0;}
			}

			$draw++;
		}

		
		for ($x = 1 ; $x <= $balls; $x++)
		{	
			if ($x == intval($balls/2+1))
			{
				print("<TR>\n");
				print("<TD BGCOLOR=\"#CCCCCC\">Num</TD>\n");
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
				print("<TD BGCOLOR=\"#CCCCCC\"><center>Year10</center>	</TD>\n");
				print("<TD BGCOLOR=\"#CCCCCC\" nowrap><center>Total</center></TD>\n");
				print("<TD BGCOLOR=\"#CCCCCC\" nowrap><center>Prev</center></TD>\n");
				print("<TD BGCOLOR=\"#CCCCCC\" nowrap><center>Last</center></TD>\n");
				print("<TD BGCOLOR=\"#CCCCCC\" nowrap><center>Sigma</center></TD>\n");
				print("<TD BGCOLOR=\"#CCCCCC\" nowrap><center>wa1</center></TD>\n");
				print("<TD BGCOLOR=\"#CCCCCC\" nowrap><center>wa5</center></TD>\n");
				print("<TD BGCOLOR=\"#CCCCCC\" nowrap><center>wa10</center></TD>\n");
				print("</TR>\n");
			}
			print("<TR>\n");
			print("<TD><CENTER><b>$x</b></CENTER></TD>\n");

			for ($d = 0; $d <= 15; $d++)
			{
				if ($column_count_array[$x][$d] > 79)
				{
					print("<TD bgcolor=\"#FF0033\" align=center>{$column_count_array[$x][$d]}</TD>\n");
				} elseif ($column_count_array[$x][$d] > 15) {
					print("<TD bgcolor=\"#CCFFFF\" align=center>{$column_count_array[$x][$d]}</TD>\n");
				} elseif ($column_count_array[$x][$d] > 1) {
					print("<TD bgcolor=\"#CCFF66\" align=center>{$column_count_array[$x][$d]}</TD>\n");
				} elseif ($column_count_array[$x][$d] == 1) {
					print("<TD bgcolor=\"#F1F1F1\" align=center>{$column_count_array[$x][$d]}</TD>\n");
				} else {
					print("<TD align=center>{$column_count_array[$x][$d]}</TD>\n");
				}
			} 

			if ($column_count_array[$x][16] > 79)
			{
				print("<TD bgcolor=\"#FF0000\" align=center><font color=\"#ffffff\"><b>{$column_count_array[$x][16]}</b></font></TD>\n");
			} else {
				print("<TD align=center>{$column_count_array[$x][16]}</TD>\n");
			}

			# prev/last
			$prev_date = '1962-08-17';
			$last_date = '1962-08-17';

			$query_date = "SELECT * FROM $draw_table_name ";
			if ($game != 10)
			{
				$query_date .= "WHERE b$col = $x "; #aon
				if ($hml)
				{
					$query_date .= "AND sum >= $range_low  ";
					$query_date .= "AND   sum <= $range_high  ";
				}
			} else {
			if ($hml)
				{
					$query_date .= "WHERE sum >= $range_low  ";
					$query_date .= "AND   sum <= $range_high  ";
				}
			}
			$query_date .= "ORDER BY date DESC ";

			#echo "$query_date<p>";

			$mysqli_result_date = mysqli_query($query_date, $mysqli_link) or die (mysqli_error($mysqli_link));

			if ($row_date = mysqli_fetch_array($mysqli_result_date))
			{
				$last_date = $row_date[date];
			}

			if ($row_date = mysqli_fetch_array($mysqli_result_date))
			{
				$prev_date = $row_date[date];
			}

			if ($prev_date == '1962-08-17')
			{
				print("<TD align=center>---</TD>\n");
			} elseif ((strtotime ("$prev_date") - $month6) < 0) {
				print("<TD nowrap><font color=\"#ff0000\">$prev_date</font></TD>\n");
			} else {
				print("<TD nowrap>$prev_date</TD>\n");
			}

			if ($last_date == '1962-08-17')
			{
				print("<TD align=center>---</TD>\n");
			} elseif ((strtotime ("$prev_date") - $month6) < 0) {
				print("<TD nowrap><font color=\"#ff0000\">$last_date</font></TD>\n");
			} else {
				print("<TD nowrap>$last_date</TD>\n");
			}

			# sigma
			$sigma_sum = 0;

			for ($d = 0; $d <= 15; $d++)
			{
				$sigma_sum += $column_count_array[$x][$d];
			}

			print("<TD align=center>$sigma_sum</TD>\n");

			$sum_temp_y1 = number_format(($column_count_array[$x][6]/365)*100,1);

			if ($sum_temp_y1 >= 1.0)
			{
				print("<TD align=center><font size=\"-1\"><b>$sum_temp_y1 %</b></font></TD>\n");
			} else {
				print("<TD align=center><font size=\"-1\">$sum_temp_y1 %</font></TD>\n");
			}

			$sum_temp_y5 = number_format(($column_count_array[$x][10]/(365*5))*100,1);

			if ($sum_temp_y5 >= 1.0)
			{
				print("<TD align=center><font size=\"-1\"><b>$sum_temp_y5 %</b></font></TD>\n");
			} else {
				print("<TD align=center><font size=\"-1\">$sum_temp_y5 %</font></TD>\n");
			}

			$weighted_average = (
				($column_count_array[$x][1]/7*100*0.10) + #week1
				($column_count_array[$x][3]/30*100*0.10) + #month1
				($column_count_array[$x][5]/(365/2)*100*0.15) + #month6
				($column_count_array[$x][6]/365*100*0.15) + #year1
				($column_count_array[$x][10]/(365*5)*100*0.25) + #year5
				($column_count_array[$x][13]/(365*8)*100*0.25)); #year8

			$sum_temp = number_format($weighted_average,1);
			if ($sum_temp >= 5.0){
				print("<TD align=center><font size=\"-1\"><b>$sum_temp %</b></font></TD>\n");
			} else {
				print("<TD align=center><font size=\"-1\">$sum_temp %</font></TD>\n");
			}

			print("</TR>\n");

			#print("x = $x<br>");

			#print_r ("$column_count_array");
			#die ("$column_count_array");

			$query5 = "INSERT INTO $draw_prefix";
			$query5 .= "column_$col";
			if ($hml)
			{
				$query5 .= "_$hml ";
			} else {
				$query5 .= " ";
			}
			$query5 .= "VALUES ('$x', ";
			for ($d = 0; $d <= 15; $d++) 
			{
				$query5 .= "'{$column_count_array[$x][$d]}', ";
			}
			#$query5 .= "'$num_rows', ";
			$query5 .= "'{$column_count_array[$x][16]}', ";
			$query5 .= "'$sum_temp_y1', ";
			$query5 .= "'$sum_temp_y5', ";
			$query5 .= "'$weighted_average', ";
			$query5 .= "'$row_last[last_date]',"; 
			$query5 .= "'$row_prev[last_date]')";

			#print "$query5<p>";
			#die ("column_count_array");
		
			$mysqli_result = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));
		}

		print("<TR>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Num</TD>\n");
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
		print("</TR>\n");

		print("</TABLE>\n");

		print "<h3>Table <font color=\"#ff0000\">{$draw_prefix}column_$col</font> Updated!</h3>";
	}

	// ----------------------------------------------------------------------------------
	function print_column_test_col_x_y($col1,$col2)
	{
		global $debug, $draw_table_name, $balls, $balls_drawn, $draw_prefix, $game, $hml, $range_low, $range_high; 

		require ("includes/mysqli.php"); 
		require ("includes/unix.incl"); 

		$query4 = "DROP TABLE IF EXISTS $draw_prefix";
		$query4 .= "column_$col1";
		$query4 .= "_";
		$query4 .= "$col2 ";

		$mysqli_result4 = mysqli_query($mysqli_link, $query4) or die (mysqli_error($mysqli_link));

		$query4 = "CREATE TABLE $draw_prefix";
		$query4 .= "column_$col1";
		$query4 .= "_";
		$query4 .= "$col2 (";
		$query4 .= "`num` tinyint(3) unsigned NOT NULL,";
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
		$query4 .= "percent_30 tinyint(3) unsigned NOT NULL default '0', ";
		$query4 .= "percent_365 tinyint(3) unsigned NOT NULL default '0', ";
		$query4 .= "percent_5000 tinyint(3) unsigned NOT NULL default '0', ";
		$query4 .= "percent_wa float(4,1) unsigned NOT NULL default '0', ";
		$query4 .= "previous_date date NOT NULL default '1962-08-17', ";
		$query4 .= "last_date date NOT NULL default '1962-08-17', ";
		$query4 .= "PRIMARY KEY  (`num`),";
		$query4 .= "UNIQUE KEY `num_2` (`num`),";
		$query4 .= "KEY `num` (`num`)";
		$query4 .= ") ENGINE=InnoDB DEFAULT CHARSET=latin1;";

		#print "$query4<p>";
	
		$mysqli_result4 = mysqli_query($mysqli_link, $query4) or die (mysqli_error($mysqli_link));

		$col_tot_5 =	array_fill (0, $balls+1, 0);
		$col_tot_10 =	array_fill (0, $balls+1, 0);
		$col_tot_30 =	array_fill (0, $balls+1, 0);
		$col_tot_50 =	array_fill (0, $balls+1, 0);
		$col_tot_100 =	array_fill (0, $balls+1, 0);
		$col_tot_365 =	array_fill (0, $balls+1, 0);
		$col_tot_500 =	array_fill (0, $balls+1, 0);
		$col_tot_1000 =	array_fill (0, $balls+1, 0);
		$col_tot_5000 =	array_fill (0, $balls+1, 0);

		//start table
		print("<h3>Column $col1/$col2 Test</h3>\n");
		print("<TABLE BORDER=\"1\">\n");

		//create header row
		print("<TR>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Num</TD>\n");
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
		print("</TR>\n");

		$temp_array = array_fill (0,17,0);
		$column_count_array = array_fill (0,$balls+1,$temp_array);

		for ($col = $col1; $col <= $col2; $col++)
		{
			$query = "SELECT * FROM $draw_table_name ";
			$query .= "ORDER BY date DESC ";

			//print "$query<p>";

			$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

			$num_rows_all = mysqli_num_rows($mysqli_result);

			$draw = 1;

			while($row = mysqli_fetch_array($mysqli_result))
			{
				$temp_draw = array (0);

				for ($v = 1; $v <= $balls_drawn; $v++)

				{
					array_push($temp_draw, $row[$v]);
				}

				sort ($temp_draw);

				$x = $temp_draw[$col];

				$draw_date_array = date_parse("$row[date]");
				$draw_date_unix = mktime (0,0,0,$draw_date_array[month],$draw_date_array[day],$draw_date_array[year]);
				
				if ($draw_date_unix == $day1)
				{ 
					for ($d = 0; $d <= 15; $d++) {$column_count_array[$x][$d]++;}
				} elseif ($draw_date_unix > $week1) {
					for ($d = 1; $d <= 15; $d++) {$column_count_array[$x][$d]++;}
				} elseif ($draw_date_unix > $week2) {
					for ($d = 2; $d <= 15; $d++) {$column_count_array[$x][$d]++;}
				} elseif ($draw_date_unix > $month1) {
					for ($d = 3; $d <= 15; $d++) {$column_count_array[$x][$d]++;}
				} elseif ($draw_date_unix > $month3) {
					for ($d = 4; $d <= 15; $d++) {$column_count_array[$x][$d]++;}
				} elseif ($draw_date_unix > $month6) {
					for ($d = 5; $d <= 15; $d++) {$column_count_array[$x][$d]++;}
				} elseif ($draw_date_unix > $year1) {
					for ($d = 6; $d <= 15; $d++) {$column_count_array[$x][$d]++;}
				} elseif ($draw_date_unix > $year2) {
					for ($d = 7; $d <= 15; $d++) {$column_count_array[$x][$d]++;}
				} elseif ($draw_date_unix > $year3) {
					for ($d = 8; $d <= 15; $d++) {$column_count_array[$x][$d]++;}
				} elseif ($draw_date_unix > $year4) {
					for ($d = 9; $d <= 15; $d++) {$column_count_array[$x][$d]++;}
				} elseif ($draw_date_unix > $year5) {
					for ($d = 10; $d <= 15; $d++) {$column_count_array[$x][$d]++;}
				} elseif ($draw_date_unix > $year6) {
					for ($d = 11; $d <= 15; $d++) {$column_count_array[$x][$d]++;}
				} elseif ($draw_date_unix > $year7) {
					for ($d = 12; $d <= 15; $d++) {$column_count_array[$x][$d]++;}
				} elseif ($draw_date_unix > $year8) {
					for ($d = 13; $d <= 15; $d++) {$column_count_array[$x][$d]++;}
				} elseif ($draw_date_unix > $year9) {
					for ($d = 14; $d <= 15; $d++) {$column_count_array[$x][$d]++;}
				} elseif ($draw_date_unix > $year10) {
					for ($d = 15; $d <= 15; $d++) {$column_count_array[$x][$d]++;}
				}

				$column_count_array[$x][16]++;

				#add 1 year to clear
				if ($first_draw_unix > $year7) {
					for ($d = 13; $d <= 15; $d++) {$column_count_array[$x][$d]=0;}
				} elseif ($first_draw_unix > $year8) {
					for ($d = 14; $d <= 15; $d++) {$column_count_array[$x][$d]=0;}
				} elseif ($first_draw_unix > $year9) {
					for ($d = 15; $d <= 15; $d++) {$column_count_array[$x][$d]=0;}
				} elseif ($first_draw_unix > $year10) {
					for ($d = 16; $d <= 16; $d++) {$column_count_array[$x][$d]=0;}
				}
				$draw++;
			}
		}

		for ($x = 1 ; $x <= $balls; $x++)
		{	if ($x == intval($balls/2+1))
			{
				print("<TR>\n");
				print("<TD BGCOLOR=\"#CCCCCC\">Num</TD>\n");
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
				print("</TR>\n");
			}
			print("<TR>\n");
			print("<TD><CENTER><b>$x</b></CENTER></TD>\n");

			for ($d = 0; $d <= 15; $d++)
			{
				if ($column_count_array[$x][$d] > 79)
				{
					print("<TD bgcolor=\"#FF0033\" align=center>{$column_count_array[$x][$d]}</TD>\n");
				} elseif ($column_count_array[$x][$d] > 15) {
					print("<TD bgcolor=\"#CCFFFF\" align=center>{$column_count_array[$x][$d]}</TD>\n");
				} elseif ($column_count_array[$x][$d] > 1) {
					print("<TD bgcolor=\"#CCFF66\" align=center>{$column_count_array[$x][$d]}</TD>\n");
				} elseif ($column_count_array[$x][$d] == 1) {
					print("<TD bgcolor=\"#F1F1F1\" align=center>{$column_count_array[$x][$d]}</TD>\n");
				} else {
					print("<TD align=center>{$column_count_array[$x][$d]}</TD>\n");
				}
			} 

			if ($column_count_array[$x][16] > 79)
			{
				print("<TD bgcolor=\"#FF0000\" align=center><font color=\"#ffffff\"><b>{$column_count_array[$x][16]}</b></font></TD>\n");
			} else {
				print("<TD align=center>{$column_count_array[$x][16]}</TD>\n");
			}

			# prev/last
			$prev_date = '1962-08-17';
			$last_date = '1962-08-17';

			$query_date = "SELECT * FROM $draw_table_name ";
			$query_date .= "WHERE b$col1 = $x ";
			$query_date .= "OR b$col2 = $x ";
			$query_date .= "ORDER BY date DESC ";

			$mysqli_result_date = mysqli_query($query_date, $mysqli_link) or die (mysqli_error($mysqli_link));

			if ($row_date = mysqli_fetch_array($mysqli_result_date))
			{
				$last_date = $row_date[date];
			}

			if ($row_date = mysqli_fetch_array($mysqli_result_date))
			{
				$prev_date = $row_date[date];
			}

			if ($prev_date == '1962-08-17')
			{
				print("<TD align=center>---</TD>\n");
			} elseif ((strtotime ("$prev_date") - $month6) < 0) {
				print("<TD nowrap><font color=\"#ff0000\">$prev_date</font></TD>\n");
			} else {
				print("<TD nowrap>$prev_date</TD>\n");
			}

			if ($last_date == '1962-08-17')
			{
				print("<TD align=center>---</TD>\n");
			} elseif ((strtotime ("$prev_date") - $month6) < 0) {
				print("<TD nowrap><font color=\"#ff0000\">$last_date</font></TD>\n");
			} else {
				print("<TD nowrap>$last_date</TD>\n");
			}

			# sigma
			$sigma_sum = 0;

			for ($d = 0; $d <= 15; $d++)
			{
				$sigma_sum += $column_count_array[$x][$d];
			}

			print("<TD align=center>$sigma_sum</TD>\n");

			$col_temp_5000 = number_format(($col_tot_5000[$x]/$num_rows_all)*100,1);
			if ($col_temp_5000 >= 5.0){
				print("<TD align=center><font size=\"-1\"><b>$col_temp_5000 %</b></font></TD>\n");
			} else {
				print("<TD align=center><font size=\"-1\">$col_temp_5000 %</font></TD>\n");
			}

			if ($game == 6)
			{
				$weighted_average = (
					($col_tot_10[$x]/10*100*0.05) +
					($col_tot_30[$x]/30*100*0.10) +
					($col_tot_50[$x]/50*100*0.10) +
					($col_tot_100[$x]/100*100*0.20) +
					#($col_tot_200[$x]/100*100*0.05) +
					($col_tot_365[$x]/365*100*0.25) +
					($col_tot_500[$x]/500*100*0.20) +
					#($col_tot_1000[$x]/1000*100*0.05) +
					($col_tot_1000[$x]/$num_rows_all*100*0.10));

			} else {
					$weighted_average = (
					($col_tot_10[$x]/10*100*0.05) +
					($col_tot_30[$x]/30*100*0.10) +
					#($col_tot_50[$x]/50*100*0.06) +
					($col_tot_100[$x]/100*100*0.15) +
					#($col_tot_200[$x]/200*100*0.15) + // extra
					($col_tot_365[$x]/365*100*0.20) +
					($col_tot_500[$x]/500*100*0.10) +
					($col_tot_1000[$x]/1000*100*0.20) +
					($col_tot_5000[$x]/$num_rows_all*100*0.20));

					if ($col_tot_10[$x] > 0)
					{
						$temp10 = $col_tot_10[$x] - 1;
					} else {
						$temp10 = 0;
					}

					if ($col_tot_30[$x] > 0)
					{
						$temp30 = $col_tot_30[$x] - 1;
					} else {
						$temp30 = 0;
					}

					if ($col_tot_50[$x] > 0)
					{
						$temp50 = $col_tot_50[$x] - 1;
					} else {
						$temp50 = 0;
					}

					if ($col_tot_100[$x] > 0)
					{
						$temp100 = $col_tot_100[$x] - 1;
					} else {
						$temp100 = 0;
					}

					if ($col_tot_200[$x] > 0)
					{
						$temp200 = $col_tot_200[$x] - 1;
					} else {
						$temp200 = 0;
					}

					if ($col_tot_365[$x] > 0)
					{
						$temp365 = $col_tot_365[$x] - 1;
					} else {
						$temp365 = 0;
					}

					if ($col_tot_500[$x] > 0)
					{
						$temp500 = $col_tot_500[$x] - 1;
					} else {
						$temp500 = 0;
					}

					if ($col_tot_1000[$x] > 0)
					{
						$temp1000 = $col_tot_1000[$x] - 1;
					} else {
						$temp1000 = 0;
					}

					if ($col_tot_5000[$x] > 0)
					{
						$temp5000 = $col_tot_5000[$x] - 1;
					} else {
						$temp5000 = 0;
					}

					#print "col_tot_5000[$x] = $col_tot_5000[$x]<br>";
					#print "temp5000 = $temp5000<br>";

					$weighted_average_prev = (
					($temp10/10*100*0.05) +
					($temp30/30*100*0.10) +
					#($temp50/50*100*0.06) +
					($temp100/100*100*0.15) +
					#($temp200/200*100*0.15) + // extra
					($temp365/365*100*0.20) +
					($temp500/500*100*0.10) +
					($temp1000/1000*100*0.20) +
					($temp5000/($num_rows_all-1)*100*0.20));

			}

			$sum_temp = number_format($weighted_average,1);
			if ($sum_temp >= 5.0){
				print("<TD align=center><font size=\"-1\"><b>$sum_temp %</b></font></TD>\n");
			} else {
				print("<TD align=center><font size=\"-1\">$sum_temp %</font></TD>\n");
			}

			$sum_temp_prev = number_format($weighted_average_prev,1);
			if ($sum_temp_prev >= 5.0){
				print("<TD align=center><font size=\"-1\"><b>$sum_temp_prev %</b></font></TD>\n");
			} else {
				print("<TD align=center><font size=\"-1\">$sum_temp_prev %</font></TD>\n");
			}

			print("</TR>\n");

			$query5 = "INSERT INTO $draw_prefix";
			$query5 .= "column_$col1";
			$query5 .= "_";
			$query5 .= "$col2 ";
			$query5 .= "VALUES ('$x', ";
			for ($d = 0; $d <= 15; $d++) 
			{
				$query5 .= "'{$column_count_array[$x][$d]}', ";
			}
			#$query5 .= "'$num_rows', ";
			$query5 .= "'{$column_count_array[$x][16]}', ";
			$query5 .= "'0', ";
			$query5 .= "'0', ";
			$query5 .= "'0', ";
			$query5 .= "'0', ";
			$query5 .= "'$row_last[last_date]',"; 
			$query5 .= "'$row_prev[last_date]')";

			#print "$query5<p>";
		
			$mysqli_result = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));
		}

		print("<TR>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Num</TD>\n");
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
		print("</TR>\n");

		print("</TABLE>\n");

		print "<h3>Table <font color=\"#ff0000\">{$draw_prefix}column _ $col1 _ $col2</font> Updated!</h3>";
	}
		
	// ----------------------------------------------------------------------------------
	function test_even_odd($limit)
	{
		global $debug, $draw_table_name, $balls, $balls_drawn, $game_includes; 

		require ("includes/mysqli.php");
	
		// get everything from catalog table
		$query = "SELECT * FROM $draw_table_name ";
		$query .= "ORDER BY date ASC ";
		$query .= "LIMIT $limit ";
	
		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));
	
		//start table
		print("<h3>Even/Odd - last $limit draws</h3>\n");
		print("<TABLE BORDER=\"1\">\n");
	
		//create header row
		print("<TR><B>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Date</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Pick 1</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Pick 2</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Pick 3</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Pick 4</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Pick 5</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Pick<br>6</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Sum</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Even<br>/Odd</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">D1</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">D10</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">D20</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">D30</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">D40</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">D50</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><B>Range<br>26/53</B></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><CENTER>R0</CENTER></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><CENTER>R1</CENTER></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><CENTER>R2</CENTER></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><CENTER>R3</CENTER></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><CENTER>R4</CENTER></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><CENTER>R5</CENTER></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><CENTER>R6</CENTER></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><CENTER>Mod</CENTER></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><CENTER>Dup1</CENTER></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><CENTER>Dup2</CENTER></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><CENTER>Dup3</CENTER></TD>\n");
		print("</B></TR>\n");
	
		$count = 1;
		$average_count = 0;
		$average_sum = 0;
		$sum_sum = 0;
		$stdev_sum = 0;
		$even_sum = 0;
		$odd_sum = 0;
		$num_range1_sum = 0;
		$num_range2_sum = 0;
		$num_range3_sum = 0;
		$num_range4_sum = 0;
		$num_range5_sum = 0;
		$num_range6_sum = 0;
		$num_range_1_26_sum = 0;
		$num_range_27_36_sum = 0;
		$rank0_sum = 0;
		$rank1_sum = 0;
		$rank2_sum = 0;
		$rank3_sum = 0;
		$rank4_sum = 0;
		$rank5_sum = 0;
		$rank6_sum = 0;
		$mod_count_sum = 0;
		$last1_sum = 0;
		$last2_sum = 0;
		$last3_sum = 0;
	
		// get each row
		while($row = mysqli_fetch_row($mysqli_result))
		{
			$last_draw = LastDraws($row[0],1);
			$last_2_draws = LastDraws($row[0],2);
			$last_3_draws = LastDraws($row[0],3);

			$num_range1 = 0;
			$num_range2 = 0;
			$num_range3 = 0;
			$num_range4 = 0;
			$num_range5 = 0;
			$num_range6 = 0;
			$num_range_1_26 = 0;
			$num_range_27_36 = 0;
			$last1 = 0;
			$last2 = 0;
			$last3 = 0;

			$draw = array ($row[1],$row[2],$row[3],$row[4],$row[5],$row[6]);

			for ($x = 0; $x <= 9; $x++)
			{
				$mod[$x] = 0;
			}

			$mod_count = 0;
	
			$SUM = $row[1] + $row[2] + $row[3] + $row[4] + $row[5] + $row[6];
			$sum_sum += $SUM;
			$average_sum += $SUM/6;
	
			// build build ranges, $skip_pointer logic
			for($index=1; $index <= 6; $index++)
			{
				//$stdev_array[$index] = $row[$index];
				
				$number = $row[$index];
	
				if ($number < 10)
				{
					$num_range1++;
					$mod[$number]++;
				}
				else if ($number > 9 && $number < 20)
				{
					$num_range2++;
					$y = $number - 10;
					$mod[$y]++;
				}
				else if ($number > 19 && $number < 30)
				{
					$num_range3++;
					$y = $number - 20;
					$mod[$y]++;
				}
				else if ($number > 29 && $number < 40)
				{
					$num_range4++;
					$y = $number - 30;
					$mod[$y]++;
				}
				else if ($number > 39 && $number < 50)
				{
					$num_range5++;
					$y = $number - 40;
					$mod[$y]++;
				}
				else
				{
					$num_range6++;
					$y = $number - 50;
					$mod[$y]++;
				}
	
				if ($number < 27) {
					$num_range_1_26++;
				} 
				else {
					$num_range_27_36++;
				}
			}

			//count duplicate draws
			for ($rp = 1; $rp <= 6; $rp++)
			{	
				for ($x = 0 ; $x < count($last_draw); $x++)
				{
					if ($row[$rp] == $last_draw[$x])
					{
						$last1++;
						if ($debug)
						{
							print "last_draw match - $row[$rp] = $last_draw[$x]<br>";
						}
					}
				}
					
				for ($x = 0 ; $x < count($last_2_draws); $x++)
				{
					if ($row[$rp] == $last_2_draws[$x])
					{
						$last2++;
					}
				}

				for ($x = 0 ; $x < count($last_3_draws); $x++)
				{
					if ($row[$rp] == $last_3_draws[$x])
					{
						$last3++;
					}
				}
			}
	
			$num_range1_sum += $num_range1;
			$num_range2_sum += $num_range2;
			$num_range3_sum += $num_range3;
			$num_range4_sum += $num_range4;
			$num_range5_sum += $num_range5;
			$num_range6_sum += $num_range6;
			$num_range_1_26_sum += $num_range_1_26;
			$num_range_27_36_sum += $num_range_27_36;
	
			$even = 0;
			$odd = 0;
			even_odd($draw, $even, $odd);
			$even_sum += $even;
			$odd_sum += $odd;

			for ($x = 0; $x <= 9; $x++)
			{
				if ($mod[$x] > 1)
				{
					$mod_count += ($mod[$x]-1);
				}
			}

			$mod_count_sum += $mod_count;
			$last1_sum += $last1;
			$last2_sum += $last2;
			$last3_sum += $last3;

			calculate_rank($draw,$row[0],$rank0,$rank1,$rank2,$rank3,$rank4,$rank5,$rank6);

			$rank0_sum += $rank0;
			$rank1_sum += $rank1;
			$rank2_sum += $rank2;
			$rank3_sum += $rank3;
			$rank4_sum += $rank4;
			$rank5_sum += $rank5;
			$rank6_sum += $rank6;
			
			print("<TR>\n");
	
			print("<TD>$row[0]</TD>\n");
			print("<TD>$row[1]</TD>\n");
			print("<TD>$row[2]</TD>\n");
			print("<TD>$row[3]</TD>\n");
			print("<TD>$row[4]</TD>\n");
			print("<TD>$row[5]</TD>\n");
			print("<TD>$row[6]</TD>\n");
			print("<TD>$SUM</TD>\n");
			if ($even < 2 || $odd < 2) {
				print("<TD BGCOLOR=\"#99CCFF\"><B><CENTER>$even/$odd</CENTER></B></TD>\n");
			} else {
				print("<TD BGCOLOR=\"#99CCFF\"><CENTER>$even/$odd</CENTER></TD>\n");
			}
			if ($num_range1 > 2) {
				print("<TD><B><CENTER>$num_range1</CENTER></B></TD>\n");
			} else {
				print("<TD><CENTER>$num_range1</CENTER></TD>\n");
			}
			if ($num_range2 > 2) {
				print("<TD><B><CENTER>$num_range2</CENTER></B></TD>\n");
			} else {
				print("<TD><CENTER>$num_range2</CENTER></TD>\n");
			}
			if ($num_range3 > 2) {
				print("<TD><B><CENTER>$num_range3</CENTER></B></TD>\n");
			} else {
				print("<TD><CENTER>$num_range3</CENTER></TD>\n");
			}
			if ($num_range4 > 2) {
				print("<TD><B><CENTER>$num_range4</CENTER></B></TD>\n");
			} else {
				print("<TD><CENTER>$num_range4</CENTER></TD>\n");
			}
			if ($num_range5 > 2) {
				print("<TD><B><CENTER>$num_range5</CENTER></B></TD>\n");
			} else {
				print("<TD><CENTER>$num_range5</CENTER></TD>\n");
			}
			if ($num_range6 > 1) {
				print("<TD><B><CENTER>$num_range6</CENTER></B></TD>\n");
			} else {
				print("<TD><CENTER>$num_range6</CENTER></TD>\n");
			}
			if ($num_range_1_26 < 2 || $num_range_27_36 < 2) {
				print("<TD BGCOLOR=\"#99CCFF\"><B><CENTER>$num_range_1_26/$num_range_27_36</CENTER></B></TD>\n");
			} else {
				print("<TD BGCOLOR=\"#99CCFF\"><CENTER>$num_range_1_26/$num_range_27_36</CENTER></TD>\n");
			}
			
			print("<TD><CENTER>$rank0</CENTER></TD>\n");
			print("<TD><CENTER>$rank1</CENTER></TD>\n");
			print("<TD><CENTER>$rank2</CENTER></TD>\n");
			print("<TD><CENTER>$rank3</CENTER></TD>\n");
			print("<TD><CENTER>$rank4</CENTER></TD>\n");
			print("<TD><CENTER>$rank5</CENTER></TD>\n");
			print("<TD><CENTER>$rank6</CENTER></TD>\n");
			if ($mod_count > 1) {
				print("<TD BGCOLOR=\"#99CCFF\"><CENTER><B>$mod_count</B></CENTER></TD>\n");
			} else {
				print("<TD BGCOLOR=\"#99CCFF\"><CENTER>$mod_count</CENTER></TD>\n");
			}
			if ($last1 > 1) {
				print("<TD><CENTER><B>$last1</B></CENTER></TD>\n");
			} else {
				print("<TD><CENTER>$last1</CENTER></TD>\n");
			}
			if ($last2 > 2) {
				print("<TD><CENTER><B>$last2</B></CENTER></TD>\n");
			} else {
				print("<TD><CENTER>$last2</CENTER></TD>\n");
			}
			if ($last3 > 3) {
				print("<TD><CENTER><B>$last3</B></CENTER></TD>\n");
			} else {
				print("<TD><CENTER>$last3</CENTER></TD>\n");
			}
			print("</TR>\n");

			$count++;
			$average_count++;
		}
	
		print("<TR>\n");
		print("<TD>&nbsp;</TD>\n");
		print("<TD>&nbsp;</TD>\n");
		print("<TD>&nbsp;</TD>\n");
		print("<TD>&nbsp;</TD>\n");
		print("<TD>&nbsp;</TD>\n");
		print("<TD>&nbsp;</TD>\n");
		print("<TD><b>Average</b></TD>\n");
		$num_temp = number_format($sum_sum/$average_count,2);
		print("<TD>$num_temp</TD>\n");
		$number_count = $average_count*5;
		$num_temp = number_format($even_sum/$number_count,2)*100;
		$num_temp2 = number_format($odd_sum/$number_count,2)*100;
		print("<TD BGCOLOR=\"#99CCFFa\">$num_temp/$num_temp2%</TD>\n");
		$num_temp = number_format($num_range1_sum/$number_count,2)*100;
		print("<TD>$num_temp%</TD>\n");
		$num_temp = number_format($num_range2_sum/$number_count,2)*100;
		print("<TD>$num_temp%</TD>\n");
		$num_temp = number_format($num_range3_sum/$number_count,2)*100;
		print("<TD>$num_temp%</TD>\n");
		$num_temp = number_format($num_range4_sum/$number_count,2)*100;
		print("<TD>$num_temp%</TD>\n");
		$num_temp = number_format($num_range5_sum/$number_count,2)*100;
		print("<TD>$num_temp%</TD>\n");
		$num_temp = number_format($num_range6_sum/$number_count,2)*100;
		print("<TD>$num_temp%</TD>\n");
		$num_temp1 = number_format($num_range_1_26_sum/$number_count,2)*100;
		$num_temp2 = number_format($num_range_27_36_sum/$number_count,2)*100;
		print("<TD BGCOLOR=\"#99CCFFa\">$num_temp1/$num_temp2%</TD>\n");
		$num_temp = number_format($rank0_sum/$number_count,2)*100;
		print("<TD>$num_temp%</TD>\n");
		$num_temp = number_format($rank1_sum/$number_count,2)*100;
		print("<TD>$num_temp%</TD>\n");
		$num_temp = number_format($rank2_sum/$number_count,2)*100;
		print("<TD>$num_temp%</TD>\n");
		$num_temp = number_format($rank3_sum/$number_count,2)*100;
		print("<TD>$num_temp%</TD>\n");
		$num_temp = number_format($rank4_sum/$number_count,2)*100;
		print("<TD>$num_temp%</TD>\n");
		$num_temp = number_format($rank5_sum/$number_count,2)*100;
		print("<TD>$num_temp%</TD>\n");
		$num_temp = number_format($rank6_sum/$number_count,2)*100;
		print("<TD>$num_temp%</TD>\n");
		$num_temp = number_format($mod_count_sum/$number_count,2)*100;
		print("<TD BGCOLOR=\"#99CCFFa\"><CENTER>$num_temp%</CENTER></TD>\n");
		$num_temp = number_format($last1_sum/$number_count,2)*100;
		print("<TD><CENTER>$num_temp%</CENTER></TD>\n");
		$num_temp = number_format($last2_sum/$number_count,2)*100;
		print("<TD><CENTER>$num_temp%</CENTER></TD>\n");
		$num_temp = number_format($last3_sum/$number_count,2)*100;
		print("<TD><CENTER>$num_temp%</CENTER></TD>\n");
		print("</TR>\n");
	
		//end table
		print("</TABLE><p>\n");
	}

	// function test_combin_print($limit)
	require ("$game_includes/combin.incl");
	
	///////////////////////////////////////////////////////////////////////////////////////////////
	
	//start HTML page
	print("<HTML>\n");
	print("<HEAD>\n");
	print("<TITLE>$game_name Tests</TITLE>\n");
	print("</HEAD>\n");

	print("<BODY>\n");
	print("<H1>$game_name Tests</H1>\n");
	
	$curr_date = date("Ymd");
	$curr_date_dash = date("Y-m-d");
	
	test_nums(30);

	test_nums(10);

	#print_dup_table(100,0);
	#print_dup_table(100,1);

	print_dup_table(30,0);
	print_dup_table(30,1);

	#update_dup_table(30,1);

	print_dup_table(10,0);
	print_dup_table(10,1);
	
	#print_dup_table(30,0);
	#print_dup_table(30,1);

	#print_dup_table_date(5,0,'2009-1-7'); 
	#print_dup_table_date(5,1,'2009-1-7');

	#print_dup_table_date(10,0,'2009-1-2'); 
	#print_dup_table_date(10,1,'2009-1-2');

	for ($x = 1; $x <= $balls_drawn; $x++)
	{
		print_column_test($x);
	}
	
	for ($x = 1; $x <= ($balls_drawn-1); $x++)
	{
		$y = $x + 1;
		#print_column_test_col_x_y($x,$y);
	}

	#print_wheel_report_summary();
	
	#$last_date = test_combin_print(100);

	# print pair table
	#require ("$game_includes/combo_summary_table.incl");

	#$last_date = test_combin_print(14);

	#$last_date = test_combin_print(1);

	# print pair table
	#require ("$game_includes/combo_summary_table.incl");
	/*
	$last_date = test_combin_count_print(100);

	# print pair table
	if ($hml)
	{
		require ("$game_includes/combo_count_summary_table_hml.incl");
	} else {
		require ("$game_includes/combo_count_summary_table.incl");
	}

	$last_date = test_combin_count_print(14);

	# print pair table
	if ($hml)
	{
		require ("$game_includes/combo_count_summary_table_hml.incl");
	} else {
		require ("$game_includes/combo_count_summary_table.incl");
	}
	
	# print pair table
	for ($combo_pos = 1; $combo_pos <= 10; $combo_pos++)
	{
		#require ("includes/combo2_table_large.incl");
	}

	# print trip table
	for ($combo_pos = 1; $combo_pos <= 10; $combo_pos++)
	{
		#require ("includes/combo3_table_large.incl");
	}

	#die ();
	
	# add combo by col1
	*/
	if (!$hml)
	{
		print_sum_table(5000);
		
		print_wheel_sum_table(5000);
	}

	// **************************************************************************

	print "<a href=\"lot_ptqf.php\" target=\"_blank\">Open lot_ptqf.php</a>"; 

	//close page
	print("</BODY>\n");
	print("</HTML>\n");

?>