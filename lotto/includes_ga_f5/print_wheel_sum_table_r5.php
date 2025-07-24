<?php
function print_wheel_sum_table_r5($limit)
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
	$query_sum .= "wheel_sum_table_eo4 ";
	$query_sum .= "WHERE last_date = '$row_draw[date]' ";

	#print "$query_sum<p>";

	#$mysqli_result_sum = mysqli_query($query_sum, $mysqli_link) or die (mysqli_error($mysqli_link));

	#$num_rows_sum = mysqli_num_rows($mysqli_result_sum);

	#die(); #-------------------------------------------------------------------------------------------

	if ($num_rows_sum)
	{
		print "num_rows_sum = $num_rows_sum<p>";
		#die();
	}

	$query4 = "DROP TABLE IF EXISTS $draw_prefix";
	$query4 .= "wheel_sum_table_r5 ";

	#print "$query4<p>";

	$mysqli_result4 = mysqli_query($mysqli_link, $query4) or die (mysqli_error($mysqli_link));

	$query4 = "CREATE TABLE $draw_prefix";
	$query4 .= "wheel_sum_table_r5 (";
	$query4 .= " id int(10) unsigned NOT NULL auto_increment, ";
	$query4 .= "`sum` int(3) unsigned NOT NULL,";
	$query4 .= "`sum_count` int(6) unsigned NOT NULL,";
	#$query4 .= "`eo50` tinyint(3) unsigned NOT NULL,";
	$query4 .= "`even` tinyint(3) unsigned NOT NULL,";
	$query4 .= "`odd` tinyint(3) unsigned NOT NULL,";
	$query4 .= "`r5_1` tinyint(3) unsigned NOT NULL,";
	$query4 .= "`r5_2` tinyint(3) unsigned NOT NULL,";
	$query4 .= "`r5_3` tinyint(3) unsigned NOT NULL,";
	$query4 .= "`r5_4` tinyint(3) unsigned NOT NULL,";
	$query4 .= "`r5_5` tinyint(3) unsigned NOT NULL,";
	$query4 .= "day1 tinyint(3) unsigned NOT NULL default '0', ";
	$query4 .= "week1 tinyint(3) unsigned NOT NULL default '0', ";
	$query4 .= "week2 tinyint(3) unsigned NOT NULL default '0', ";
	$query4 .= "month1 tinyint(3) unsigned NOT NULL default '0', ";
	$query4 .= "month3 tinyint(3) unsigned NOT NULL default '0', ";
	$query4 .= "month6 tinyint(3) unsigned NOT NULL default '0', ";
	$query4 .= "year1 tinyint(3) unsigned NOT NULL default '0', ";
	$query4 .= "year2 tinyint(3) unsigned NOT NULL default '0', ";
	$query4 .= "d1510 tinyint(3) unsigned NOT NULL default '0', ";
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
	$query4 .= "percent_1510 float(4,2) unsigned NOT NULL default '0', ";
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

	$query_eo50 = "SELECT DISTINCT a.even, a.odd, b.d1, b.d2, b.d3, b.d4, b.d5 FROM ";
	$query_eo50 .= "$draw_table_name a ";
	$query_eo50 .= "JOIN ga_f5_draw_range5 ";
	$query_eo50 .= "b ON ";
	$query_eo50 .= "a.date = b.date ";
	$query_eo50 .= "ORDER BY a.even, a.odd, b.d1 ASC, b.d2 ASC, b.d3 ASC, b.d4 ASC, b.d5 ASC ";

	#print "$query_eo50<p>";

	#die();

	$mysqli_result_eo50 = mysqli_query($query_eo50, $mysqli_link) or die (mysqli_error($mysqli_link));

	//start table
	print("<h3>Wheel Sum Table - $limit</h3>\n");
	print("<TABLE BORDER=\"1\">\n");

	//create header row
	print("<TR>\n");
	print("<TD BGCOLOR=\"#CCCCCC\" align=\"center\">Sum</TD>\n");
	#print("<TD BGCOLOR=\"#CCCCCC\" align=\"center\">EO50</TD>\n");
	print("<TD BGCOLOR=\"#CCCCCC\" align=\"center\">Even</TD>\n");
	print("<TD BGCOLOR=\"#CCCCCC\" align=\"center\">Odd</TD>\n");
	print("<TD BGCOLOR=\"#CCCCCC\" align=\"center\">r5_1</TD>\n");
	print("<TD BGCOLOR=\"#CCCCCC\" align=\"center\">r5_2</TD>\n");
	print("<TD BGCOLOR=\"#CCCCCC\" align=\"center\">r5_3</TD>\n");
	print("<TD BGCOLOR=\"#CCCCCC\" align=\"center\">r5_4</TD>\n");
	print("<TD BGCOLOR=\"#CCCCCC\" align=\"center\">r5_5</TD>\n");
	print("<TD BGCOLOR=\"#CCCCCC\" align=\"center\">Rows</TD>\n");
	print("<TD BGCOLOR=\"#CCCCCC\"><center>Last</center></TD>\n");
	print("<TD BGCOLOR=\"#CCCCCC\"><center>Week1</center></TD>\n");
	print("<TD BGCOLOR=\"#CCCCCC\"><center>Week2</center></TD>\n");
	print("<TD BGCOLOR=\"#CCCCCC\"><center>Month1</center></TD>\n");
	print("<TD BGCOLOR=\"#CCCCCC\"><center>Month3</center></TD>\n");
	print("<TD BGCOLOR=\"#CCCCCC\"><center>Month6</center></TD>\n");
	print("<TD BGCOLOR=\"#CCCCCC\"><center>Year1</center></TD>\n");
	print("<TD BGCOLOR=\"#CCCCCC\"><center>Year2</center></TD>\n");
	print("<TD BGCOLOR=\"#CCCCCC\"><center>D1510</center></TD>\n");
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
	#print("<TD BGCOLOR=\"#CCCCCC\"><center>Sigma</center></TD>\n");
	print("<TD BGCOLOR=\"#CCCCCC\"><center>wa1</center></TD>\n");
	print("<TD BGCOLOR=\"#CCCCCC\"><center>wa1510</center></TD>\n");
	print("<TD BGCOLOR=\"#CCCCCC\"><center>wa10</center></TD>\n");
	print("<TD BGCOLOR=\"#CCCCCC\"><center>wa</center></TD>\n");
	print("</TR>\n");

	$z = 0;
	$b_switch = 0;

	# loop eo50
	if ($game == 2 || $game == 4 || $game == 7)
	{
		$low = 80;
		$high = 300;
	} elseif ($game == 10 || $game == 20) {
		$low = 120;
		$high = 179;
	} else {
		$low = 80;
		$high = 159;

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
					print "<TD BGCOLOR=\"#CCCCCC\" align=\"center\">Even</TD>\n";
					print "<TD BGCOLOR=\"#CCCCCC\" align=\"center\">Odd</TD>\n";
					print "<TD BGCOLOR=\"#CCCCCC\" align=\"center\">r5_1</TD>\n";
					print "<TD BGCOLOR=\"#CCCCCC\" align=\"center\">r5_2</TD>\n";
					print "<TD BGCOLOR=\"#CCCCCC\" align=\"center\">r5_3</TD>\n";
					print "<TD BGCOLOR=\"#CCCCCC\" align=\"center\">r5_4</TD>\n";
					print("<TD BGCOLOR=\"#CCCCCC\" align=\"center\">r5_5</TD>\n");
					print "<TD BGCOLOR=\"#CCCCCC\" align=\"center\">Rows</TD>\n";
					print "<TD BGCOLOR=\"#CCCCCC\"><center>Last</center></TD>\n";
					print "<TD BGCOLOR=\"#CCCCCC\"><center>Week1</center></TD>\n";
					print "<TD BGCOLOR=\"#CCCCCC\"><center>Week2</center></TD>\n";
					print "<TD BGCOLOR=\"#CCCCCC\"><center>Month1</center></TD>\n";
					print "<TD BGCOLOR=\"#CCCCCC\"><center>Month3</center></TD>\n";
					print "<TD BGCOLOR=\"#CCCCCC\"><center>Month6</center></TD>\n";
					print "<TD BGCOLOR=\"#CCCCCC\"><center>Year1</center></TD>\n";
					print "<TD BGCOLOR=\"#CCCCCC\"><center>Year2</center></TD>\n";
					print("<TD BGCOLOR=\"#CCCCCC\"><center>D1510</center></TD>\n");
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
					#print "<TD BGCOLOR=\"#CCCCCC\"><center>Sigma</center></TD>\n";
					print "<TD BGCOLOR=\"#CCCCCC\"><center>wa1</center></TD>\n";
					print "<TD BGCOLOR=\"#CCCCCC\"><center>wa1510</center></TD>\n";
					print "<TD BGCOLOR=\"#CCCCCC\"><center>wa10</center></TD>\n";
					print("<TD BGCOLOR=\"#CCCCCC\"><center>wa</center></TD>\n");
					print "</TR>\n";
				}
				
				$sum_count_array = array_fill (0,20,0);

				# select draws based on sum,even,odd,d2_1,d2_2
				$query_draw = "SELECT a.date, a.sum, a.even, a.odd, b.d1, b.d2, b.d3, b.d4, b.d5 FROM ";
				$query_draw .= "$draw_table_name a ";
				$query_draw .= "JOIN ga_f5_draw_range5 ";
				$query_draw .= "b ON ";
				$query_draw .= "a.date = b.date ";
				$query_draw .= "WHERE sum = $w ";
				$query_draw .= "AND even = $row_eo50[even] ";
				$query_draw .= "AND odd = $row_eo50[odd] ";
				$query_draw .= "AND d1 = $row_eo50[d1] ";
				$query_draw .= "AND d2 = $row_eo50[d2] ";
				$query_draw .= "AND d3 = $row_eo50[d3] ";
				$query_draw .= "AND d4 = $row_eo50[d4] ";
				$query_draw .= "AND d5 = $row_eo50[d5] ";
				$query_draw .= "ORDER BY a.date,a.even, a.odd, b.d1 ASC, b.d2 ASC, b.d3 ASC, b.d4 ASC, b.d5 ASC ";

				#echo "$query_draw<p>";

				#exit();
			
				$mysqli_result_draw = mysqli_query($query_draw, $mysqli_link) or die (mysqli_error($mysqli_link));

				$num_rows_all_draw = mysqli_num_rows($mysqli_result_draw);

				#echo "num rows = $num_rows_all_draw<p>";

				# loop draws
				while($row_draw = mysqli_fetch_array($mysqli_result_draw))
				{
					$sum_table[$row[0]]++;
					
					$$draw_date_array = explode("-","$row_draw[0]"); ### 210104
					$draw_date_unix = mktime (0,0,0,$draw_date_array[1],$draw_date_array[2],$draw_date_array[0]); ### 210104
					#echo "draw_date_unix = $draw_date_unix<br>";
					#echo "year10 = $year10<br>";

					#$x = $row_date[sum];
					
					if ($draw_date_unix == $day1)
					{ 
						for ($d = 0; $d <= 16; $d++) {$sum_count_array[$d]++;}
					} elseif ($draw_date_unix > $week1) {
						for ($d = 1; $d <= 16; $d++) {$sum_count_array[$d]++;}
					} elseif ($draw_date_unix > $week2) {
						for ($d = 2; $d <= 16; $d++) {$sum_count_array[$d]++;}
					} elseif ($draw_date_unix > $month1) {
						for ($d = 3; $d <= 16; $d++) {$sum_count_array[$d]++;}
					} elseif ($draw_date_unix > $month3) {
						for ($d = 4; $d <= 16; $d++) {$sum_count_array[$d]++;}
					} elseif ($draw_date_unix > $month6) {
						for ($d = 5; $d <= 16; $d++) {$sum_count_array[$d]++;}
					} elseif ($draw_date_unix > $year1) {
						for ($d = 6; $d <= 16; $d++) {$sum_count_array[$d]++;}
					} elseif ($draw_date_unix > $year2) {
						for ($d = 7; $d <= 16; $d++) {$sum_count_array[$d]++;}
					} elseif ($draw_date_unix > $d1510_unix) {
						for ($d = 8; $d <= 16; $d++) {$sum_count_array[$d]++;}
					} elseif ($draw_date_unix > $year3) {
						for ($d = 9; $d <= 16; $d++) {$sum_count_array[$d]++;}
					} elseif ($draw_date_unix > $year4) {
						for ($d = 10; $d <= 16; $d++) {$sum_count_array[$d]++;}
					} elseif ($draw_date_unix > $year5) {
						for ($d = 11; $d <= 16; $d++) {$sum_count_array[$d]++;}
					} elseif ($draw_date_unix > $year6) {
						for ($d = 12; $d <= 16; $d++) {$sum_count_array[$d]++;}
					} elseif ($draw_date_unix > $year7) {
						for ($d = 13; $d <= 16; $d++) {$sum_count_array[$d]++;}
					} elseif ($draw_date_unix > $year8) {
						for ($d = 14; $d <= 16; $d++) {$sum_count_array[$d]++;}
					} elseif ($draw_date_unix > $year9) {
						for ($d = 15; $d <= 16; $d++) {$sum_count_array[$d]++;}
					} elseif ($draw_date_unix > $year10) {
						for ($d = 16; $d <= 16; $d++) {$sum_count_array[$d]++;}
					}
			
					$sum_count_array[17]++;

					#add 1 year to clear
					if ($first_draw_unix > $year7) {
						#for ($d = 14; $d <= 16; $d++) {$sum_count_array[$d]=0;}
					} elseif ($first_draw_unix > $year8) {
						#for ($d = 15; $d <= 16; $d++) {$sum_count_array[$d]=0;}
					} elseif ($first_draw_unix > $year9) {
						#for ($d = 16; $d <= 16; $d++) {$sum_count_array[$d]=0;}
					} elseif ($first_draw_unix > $year10) {
						#for ($d = 17; $d <= 17; $d++) {$sum_count_array[$d]=0;}
					}
					
					$z++;
				}

				#$x = $w;

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
						$query_rows .= "AND r5_1 = $row_eo50[d1] ";
						$query_rows .= "AND r5_2 = $row_eo50[d2] ";
						$query_rows .= "AND r5_3 = $row_eo50[d3] ";
						$query_rows .= "AND r5_4 = $row_eo50[d4] ";
						$query_rows .= "AND r5_5 = $row_eo50[d5] ";
					} else {
						$query_rows .= "AND d3_1 = $row_eo50[d3_1] ";
						$query_rows .= "AND d3_2 = $row_eo50[d3_2] ";
						$query_rows .= "AND d3_3 = $row_eo50[d2_3] ";
					}

					#echo "$query_rows<p>"; #171122

					#$mysqli_result_rows = mysqli_query($query_rows, $mysqli_link) or die (mysqli_error($mysqli_link));

					#$row_count = mysqli_fetch_array($mysqli_result_rows);

					#$num_rows = $row_count[0];
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
						$query_rows .= "AND	r5_1	= $row_eo50[d1] ";
						$query_rows .= "AND	r5_2	= $row_eo50[d2] ";
						$query_rows .= "AND	r5_3	= $row_eo50[d3] ";
						$query_rows .= "AND	r5_4	= $row_eo50[d4] ";
						$query_rows .= "AND	r5_5	= $row_eo50[d5] ";

						#print("$query_rows<p>");

						if ($game != 10 AND $game != 20)
						{
							$mysqli_result_rows = mysqli_query($query_rows, $mysqli_link) or die (mysqli_error($mysqli_link));

							$row_count = mysqli_fetch_array($mysqli_result_rows);
						}

						$num_rows += $row_count[0];
					}
				}
				
				#if ($num_rows && $sum_count_array[16] > 0) #151014
				#if ($num_rows) #151014
				#echo "num_rows_all_draw = $num_rows_all_draw<br>";
				#echo "sum_count_array[16] = $sum_count_array[16]<br>";
				#echo "sum_count_array[17] = $sum_count_array[17]<br>";
				if ($num_rows_all_draw && $sum_count_array[11] > 1 && $sum_count_array[16] > 1)
				{
					print("<TR>\n");
					print("<TD align=center><center>$w</center></TD>\n");
					#print("<TD align=center><center>$row_eo50[id]</center></TD>\n");
					print("<TD align=center><center>$row_eo50[even]</center></TD>\n");
					print("<TD align=center><center>$row_eo50[odd]</center></TD>\n");
					print("<TD align=center><center>$row_eo50[d1]</center></TD>\n");
					print("<TD align=center><center>$row_eo50[d2]</center></TD>\n");
					print("<TD align=center><center>$row_eo50[d3]</center></TD>\n");
					print("<TD align=center><center>$row_eo50[d4]</center></TD>\n");
					print("<TD align=center><center>$row_eo50[d5]</center></TD>\n");

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
					$query_date .= "AND even = $row_eo50[even] ";
					$query_date .= "AND odd = $row_eo50[odd] ";
					$query_date .= "AND r5_1 = $row_eo50[d1] ";
					$query_date .= "AND r5_2 = $row_eo50[d2] ";
					$query_date .= "AND r5_3 = $row_eo50[d3] ";
					$query_date .= "AND r5_4 = $row_eo50[d4] ";
					$query_date .= "AND r5_5 = $row_eo50[d5] ";
					$query_date .= "ORDER BY date DESC ";
					
					#echo "$query_date<br>"; #171122
				
					#$mysqli_result_date = mysqli_query($query_date, $mysqli_link) or die (mysqli_error($mysqli_link)); #171122

					#$row_date = mysqli_fetch_array($mysqli_result_date); #171122

					#$row_date_prev = mysqli_fetch_array($mysqli_result_date); #171122

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

					#print("<TD align=center>$sigma</TD>\n");

					$sum_temp_y1 = number_format(($sum_count_array[6]/365)*100,1);

					if ($sum_temp_y1 >= 0.5)
					{
						print("<TD align=center nowrap><font size=\"-1\"><b>$sum_temp_y1 %</b></font></TD>\n");
					} else {
						print("<TD align=center nowrap><font size=\"-1\">$sum_temp_y1 %</font></TD>\n");
					}

					$sum_temp_d1510 = number_format(($sum_count_array[8]/(365*2))*100,1);

					if ($sum_temp_d1510 >= 0.5)
					{
						print("<TD align=center nowrap><font size=\"-1\"><b>$sum_temp_d1510 %</b></font></TD>\n");
					} else {
						print("<TD align=center nowrap><font size=\"-1\">$sum_temp_d1510 %</font></TD>\n");
					}

					$sum_temp_y10 = number_format(($sum_count_array[16]/(365*10))*100,1);

					if ($sum_temp_y10 >= 0.5)
					{
						print("<TD align=center nowrap><font size=\"-1\"><b>$sum_temp_y10 %</b></font></TD>\n");
					} else {
						print("<TD align=center nowrap><font size=\"-1\">$sum_temp_y10 %</font></TD>\n");
					}

					$weighted_average = (
						($sum_temp_y1  * 0.30) + 
						($sum_temp_d1510  * 0.50) + 
						($sum_temp_y10 * 0.20));

					if ($weighted_average >= 0.5)
					{
						print("<TD align=center nowrap><font size=\"-1\" align=center><b>$weighted_average %</b></font></TD>\n");
					} else {
						print("<TD align=center nowrap><font size=\"-1\" align=center>$weighted_average %</font></TD>\n");
					}

					print("</TR>\n");
				} #151014

				$query4 = "Insert INTO $draw_prefix";
				$query4 .= "wheel_sum_table_r5 ";
				$query4 .= "VALUES ( '0', ";
				$query4 .= "'$w', ";
				$query4 .= "'$row_count[0]', ";
				#$query4 .= "'$row_wheel[eo50]', ";
				$query4 .= "'$row_eo50[even]', ";
				$query4 .= "'$row_eo50[odd]', ";
				$query4 .= "'$row_eo50[d1]', ";
				$query4 .= "'$row_eo50[d2]', ";
				$query4 .= "'$row_eo50[d3]', ";
				$query4 .= "'$row_eo50[d4]', ";
				$query4 .= "'$row_eo50[d5]', ";
				for ($d = 0; $d <= 17; $d++) 
				{
					$query4 .= "'$sum_count_array[$d]', ";
				}
				$query4 .= "'$sigma', ";
				$query4 .= "'$sum_temp_y1', ";
				$query4 .= "'$sum_temp_d1510', ";
				$query4 .= "'$sum_temp_y10', ";
				$query4 .= "'$weighted_average', ";
				
				$query4 .= "'$row_date_prev[date]', ";
				$query4 .= "'$row_date[date]')"; 

				#print "$query4<p>";
				
				$mysqli_result4 = mysqli_query($mysqli_link, $query4) or die (mysqli_error($mysqli_link));
		}

		print "<TR>\n";
		print "<TD BGCOLOR=\"#CCCCCC\" align=\"center\">Sum</TD>\n";
		#print "<TD BGCOLOR=\"#CCCCCC\" align=\"center\">EO50</TD>\n";
		print "<TD BGCOLOR=\"#CCCCCC\" align=\"center\">Even</TD>\n";
		print "<TD BGCOLOR=\"#CCCCCC\" align=\"center\">Odd</TD>\n";
		print "<TD BGCOLOR=\"#CCCCCC\" align=\"center\">r5_1</TD>\n";
		print "<TD BGCOLOR=\"#CCCCCC\" align=\"center\">r5_2</TD>\n";
		print "<TD BGCOLOR=\"#CCCCCC\" align=\"center\">r5_3</TD>\n";
		print "<TD BGCOLOR=\"#CCCCCC\" align=\"center\">r5_4</TD>\n";
		print("<TD BGCOLOR=\"#CCCCCC\" align=\"center\">r5_5</TD>\n");
		print "<TD BGCOLOR=\"#CCCCCC\" align=\"center\">Rows</TD>\n";
		print "<TD BGCOLOR=\"#CCCCCC\"><center>Last</center></TD>\n";
		print "<TD BGCOLOR=\"#CCCCCC\"><center>Week1</center></TD>\n";
		print "<TD BGCOLOR=\"#CCCCCC\"><center>Week2</center></TD>\n";
		print "<TD BGCOLOR=\"#CCCCCC\"><center>Month1</center></TD>\n";
		print "<TD BGCOLOR=\"#CCCCCC\"><center>Month3</center></TD>\n";
		print "<TD BGCOLOR=\"#CCCCCC\"><center>Month6</center></TD>\n";
		print "<TD BGCOLOR=\"#CCCCCC\"><center>Year1</center></TD>\n";
		print "<TD BGCOLOR=\"#CCCCCC\"><center>Year2</center></TD>\n";
		print("<TD BGCOLOR=\"#CCCCCC\"><center>D1510</center></TD>\n");
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
		#print "<TD BGCOLOR=\"#CCCCCC\"><center>Sigma</center></TD>\n";
		print "<TD BGCOLOR=\"#CCCCCC\"><center>wa1</center></TD>\n";
		print "<TD BGCOLOR=\"#CCCCCC\"><center>wa1510</center></TD>\n";
		print "<TD BGCOLOR=\"#CCCCCC\"><center>wa10</center></TD>\n";
		print("<TD BGCOLOR=\"#CCCCCC\"><center>wa</center></TD>\n");
		print "</TR>\n";
	}

	print("</TABLE>\n");

	print "<h3>Table <font color=\"#ff0000\">{$draw_prefix}wheel_sum_table_r5</font> Updated!</h3>";

	# copy current table into dated table
	$curr_date = date("ymd");

	$table_temp_date = $draw_prefix . 'wheel_sum_table_r5_' . $curr_date;

	$query = "DROP TABLE IF EXISTS $table_temp_date";

	$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

	$query_copy .= "CREATE TABLE $table_temp_date SELECT * FROM $draw_prefix";
	$query_copy .= "wheel_sum_table_r5 ";

	$mysqli_result = mysqli_query($mysqli_link, $query_copy) or die (mysqli_error($mysqli_link));

	print "<h3>Table <font color=\"#ff0000\">$table_temp_date</font> Updated!</h3>";
}
?>