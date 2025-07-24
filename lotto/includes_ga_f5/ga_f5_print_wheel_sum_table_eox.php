<?php
	function ga_f5_print_wheel_sum_table_eox($limit,$x)
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
	
		
		rebuild include
	
		$query_sum = "SELECT * FROM $draw_prefix";
		$query_sum .= "wheel_sum_table_eo4 "; fix
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
		$query4 .= "wheel_sum_table_eo";
		$query4 .= "$x ";

		#print "$query4<p>";

		$mysqli_result4 = mysqli_query($mysqli_link, $query4) or die (mysqli_error($mysqli_link));

		$query4 = "CREATE TABLE $draw_prefix";
		$query4 .= "wheel_sum_table_eo";
		$query4 .= "$x (";
		$query4 .= " id int(10) unsigned NOT NULL auto_increment, ";
		$query4 .= "`sum` int(3) unsigned NOT NULL,";
		$query4 .= "`sum_count` int(6) unsigned NOT NULL,";
		$query4 .= "`eo50` tinyint(3) unsigned NOT NULL,";
		$query4 .= "`even` tinyint(3) unsigned NOT NULL,";
		$query4 .= "`odd` tinyint(3) unsigned NOT NULL,";
		
		for ($d = 1; $d <= $x; $d++)
		{
		$query4 .= "d.$x_";
		$query4 .= "$d"; 
		$query4 .= " tinyint(3) unsigned NOT NULL,"; 
		}
		
		$query4 .= "day1 tinyint(3) unsigned NOT NULL default '0', ";
		$query4 .= "week1 tinyint(3) unsigned NOT NULL default '0', ";
		$query4 .= "week2 tinyint(3) unsigned NOT NULL default '0', ";
		$query4 .= "month1 tinyint(3) unsigned NOT NULL default '0', ";
		$query4 .= "month3 tinyint(3) unsigned NOT NULL default '0', ";
		$query4 .= "month6 tinyint(3) unsigned NOT NULL default '0', ";
		$query4 .= "year1 tinyint(3) unsigned NOT NULL default '0', ";
		$query4 .= "D1510 tinyint(3) unsigned NOT NULL default '0', ";
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
	
		/*
		$query_eo50 = "SELECT * FROM $draw_prefix";
		$query_eo50 .= "eo50 ";
		$query_eo50 .= "ORDER BY id ASC ";

		#print "$query_eo50<p>";
		*/

		fix $query_eo50 = "SELECT DISTINCT even, odd, d4_1, d4_2, d4_3, d4_4 FROM combo_";
		$query_eo50 .= "$balls_drawn";
		$query_eo50 .= "_$balls ";
		$query_eo50 .= "WHERE even > 0 ";
		$query_eo50 .= "AND   even < 5 ";
		fix $query_eo50 .= "ORDER BY d4_1 ASC, d4_2 ASC, d4_3 ASC, d4_4 ASC ";

		fix print "$query_eox<p>";

		#exit();
	
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
		fixprint("<TD BGCOLOR=\"#CCCCCC\" align=\"center\">D401</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\" align=\"center\">D402</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\" align=\"center\">D403</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\" align=\"center\">D404</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\" align=\"center\">Rows</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Last</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Week1</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Week2</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Month1</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Month3</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Month6</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Year1</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>D1510</center></TD>\n");
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
		print("<TD BGCOLOR=\"#CCCCCC\"><center>wa</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>wa</center></TD>\n");
		print("</TR>\n");

		$z = 0;
		$b_switch = 0;
		$row_count_break = 0;

		$low = 80;
		$high = 220;
		
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
						fix print "<TD BGCOLOR=\"#CCCCCC\" align=\"center\">D401</TD>\n";
						print "<TD BGCOLOR=\"#CCCCCC\" align=\"center\">D402</TD>\n";
						print "<TD BGCOLOR=\"#CCCCCC\" align=\"center\">D403</TD>\n";
						print "<TD BGCOLOR=\"#CCCCCC\" align=\"center\">D404</TD>\n";
						print "<TD BGCOLOR=\"#CCCCCC\" align=\"center\">Rows</TD>\n";
						print "<TD BGCOLOR=\"#CCCCCC\"><center>Last</center></TD>\n";
						print "<TD BGCOLOR=\"#CCCCCC\"><center>Week1</center></TD>\n";
						print "<TD BGCOLOR=\"#CCCCCC\"><center>Week2</center></TD>\n";
						print "<TD BGCOLOR=\"#CCCCCC\"><center>Month1</center></TD>\n";
						print "<TD BGCOLOR=\"#CCCCCC\"><center>Month3</center></TD>\n";
						print "<TD BGCOLOR=\"#CCCCCC\"><center>Month6</center></TD>\n";
						print "<TD BGCOLOR=\"#CCCCCC\"><center>Year1</center></TD>\n";
						print "<TD BGCOLOR=\"#CCCCCC\"><center>D1510</center></TD>\n";
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
						print "<TD BGCOLOR=\"#CCCCCC\"><center>wa</center></TD>\n";
						print("<TD BGCOLOR=\"#CCCCCC\"><center>wa</center></TD>\n");
						print "</TR>\n";
					}
					
					$sum_count_array = array_fill (0,17,0);

					# select draws based on sum,even,odd,d2_1,d2_2
					$query_draw = "SELECT * FROM $draw_table_name ";
					$query_draw .= "WHERE sum = $w ";
					$query_draw .= "AND even = $row_eo50[even] ";
					$query_draw .= "AND odd = $row_eo50[odd] ";
					fix $query_draw .= "AND d4_1 = $row_eo50[d4_1] ";
					$query_draw .= "AND d4_2 = $row_eo50[d4_2] ";
					$query_draw .= "AND d4_3 = $row_eo50[d4_3] ";
					$query_draw .= "AND d4_4 = $row_eo50[d4_4] ";
					$query_draw .= "ORDER BY date DESC ";
					$query_draw .= "LIMIT $limit ";

					#echo "$query_draw<p>";

					#exit();
				
					$mysqli_result_draw = mysqli_query($query_draw, $mysqli_link) or die (mysqli_error($mysqli_link));

					$num_rows_all_draw = mysqli_num_rows($mysqli_result_draw);

					# loop draws
					while($row_draw = mysqli_fetch_array($mysqli_result_draw))
					{
						$sum_table[$row[0]]++;
						
						$draw_date_array = explode("-","$row_draw[0]"); ### 210104
						$draw_date_unix = mktime (0,0,0,$draw_date_array[1],$draw_date_array[2],$draw_date_array[0]); ### 210104

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
						} elseif ($draw_date_unix > $d1510_unix) {
							for ($d = 7; $d <= 16; $d++) {$sum_count_array[$d]++;}
						} elseif ($draw_date_unix > $year2) {
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
							$query_rows .= "AND d4_1 = $row_eo50[d4_1] ";
							$query_rows .= "AND d4_2 = $row_eo50[d4_2] ";
							$query_rows .= "AND d4_3 = $row_eo50[d4_3] ";
							$query_rows .= "AND d4_4 = $row_eo50[d4_4] ";
						} fix

						#echo "$query_rows<p>";

						$mysqli_result_rows = mysqli_query($query_rows, $mysqli_link) or die (mysqli_error($mysqli_link));

						$row_count = mysqli_fetch_array($mysqli_result_rows);

						$num_rows = $row_count[0];
					} else {
						$num_rows = 0;
						
						for ($x = 1; $x <= 16; $x++)
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
							$query_rows .= "AND	d4_1	= $row_eo50[d4_1] ";
							$query_rows .= "AND	d4_2	= $row_eo50[d4_2] ";
							$query_rows .= "AND	d4_3	= $row_eo50[d4_3] ";
							fix$query_rows .= "AND	d4_4	= $row_eo50[d4_4] ";

							#print("$query_rows<p>");

							$mysqli_result_rows = mysqli_query($query_rows, $mysqli_link) or die (mysqli_error($mysqli_link));

							$row_count = mysqli_fetch_array($mysqli_result_rows);

							$num_rows += $row_count[0];
						}
					}
					
					if ($num_rows) #151014
					{
						print("<TR>\n");
						print("<TD align=center><center>$w</center></TD>\n");
						print("<TD align=center><center>$row_eo50[id]</center></TD>\n");
						print("<TD align=center><center>$row_eo50[even]</center></TD>\n");
						print("<TD align=center><center>$row_eo50[odd]</center></TD>\n");
						fixprint("<TD align=center><center>$row_eo50[d4_1]</center></TD>\n");
						print("<TD align=center><center>$row_eo50[d4_2]</center></TD>\n");
						print("<TD align=center><center>$row_eo50[d4_3]</center></TD>\n");
						print("<TD align=center><center>$row_eo50[d4_4]</center></TD>\n");

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
						$query_date .= "AND d4_1 = $row_eo50[d4_1] ";
						$query_date .= "AND d4_2 = $row_eo50[d4_2] ";
						$query_date .= "AND d4_3 = $row_eo50[d4_3] ";
						$query_date .= "AND d4_4 = $row_eo50[d4_4] ";
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

						$row_count_break++;
					} #151014

					$query4 = "Insert INTO $draw_prefix";
					$query4 .= "wheel_sum_table_eo4 ";
					$query4 .= "VALUES ( '0', ";
					$query4 .= "'$w', ";
					$query4 .= "'$row_count[0]', ";
					$query4 .= "'$row_wheel[eo50]', ";
					$query4 .= "'$row_eo50[even]', ";
					$query4 .= "'$row_eo50[odd]', ";
					$query4 .= "'$row_eo50[d4_1]', ";
					$query4 .= "'$row_eo50[d4_2]', ";
					$query4 .= "'$row_eo50[d4_3]', ";
					$query4 .= "'$row_eo50[d4_4]', ";
					for ($d = 0; $d <= 17; $d++) 
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

					if ($row_count_break == 23)
					{
						print "<TR>\n";
						print "<TD BGCOLOR=\"#CCCCCC\" align=\"center\">Sum</TD>\n";
						print "<TD BGCOLOR=\"#CCCCCC\" align=\"center\">EO50</TD>\n";
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
						print "<TD BGCOLOR=\"#CCCCCC\"><center>D1510</center></TD>\n";
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
						print "<TD BGCOLOR=\"#CCCCCC\"><center>wa</center></TD>\n";
						print("<TD BGCOLOR=\"#CCCCCC\"><center>wa</center></TD>\n");
						print "</TR>\n";

						$row_count_break = 0;
					}
			}

			print "<TR>\n";
			print "<TD BGCOLOR=\"#CCCCCC\" align=\"center\">Sum</TD>\n";
			print "<TD BGCOLOR=\"#CCCCCC\" align=\"center\">EO50</TD>\n";
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
			print "<TD BGCOLOR=\"#CCCCCC\"><center>D1510</center></TD>\n";
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
			print "<TD BGCOLOR=\"#CCCCCC\"><center>wa</center></TD>\n";
			print("<TD BGCOLOR=\"#CCCCCC\"><center>wa</center></TD>\n");
			print "</TR>\n";
		}

		print("</TABLE>\n");

		print "<h3>Table <font color=\"#ff0000\">{$draw_prefix}wheel_sum_table_eo4</font> Updated!</h3>";

		# copy current table into dated table
		$curr_date = date("ymd");

		$table_temp_date = $draw_prefix . 'wheel_sum_table_e04_' . $curr_date;

		$query = "DROP TABLE IF EXISTS $table_temp_date";

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

		$query_copy .= "CREATE TABLE $table_temp_date SELECT * FROM $draw_prefix";
		$query_copy .= "wheel_sum_table_eo4 ";

		$mysqli_result = mysqli_query($mysqli_link, $query_copy) or die (mysqli_error($mysqli_link));

		print "<h3>Table <font color=\"#ff0000\">$table_temp_date</font> Updated!</h3>";
	}

?>