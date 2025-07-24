<?php

	// Game ----------------------------- Game
	$game = 1; // Georgia Fantasy 5
	#$game = 2; // Mega Millions
	//$game = 3; // Georgia 5
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
	require ("includes/test_filter_d_table.php");
	require ("includes/test_wheel_table.php");
	require ("includes/calculate_draw.php");
	require ("includes/table_draw_count.php");
	require ("includes/x10.php");
	require ("includes/count_2_seq.php");
	require ("includes/count_3_seq.php");
	require ("includes/count_mod.php");
	require ("includes/draw_count_total.php");
	
	$debug = 0;

	$combo_pos = $_GET["combo_pos"];

	echo "combo_pos = $combo_pos<br>";

	if ($combo_pos == NULL)
	{
		die ("combo_pos undefined");
	}

	# print pair table
	require ("includes/unix.incl");

	$query4 = "DROP TABLE IF EXISTS $draw_prefix";
	$query4 .= "temp_combo3";
	$query4 .= "_p";
	$query4 .= "$combo_pos ";

	$mysqli_result4 = mysqli_query($mysqli_link, $query4) or die (mysqli_error($mysqli_link));

	$query4 = "CREATE TABLE $draw_prefix";
	$query4 .= "temp_combo3";
	$query4 .= "_p";
	$query4 .= "$combo_pos (";
	$query4 .= " id int(10) unsigned NOT NULL auto_increment, ";
	$query4 .= "`num1` tinyint(3) unsigned NOT NULL,";
	$query4 .= "`num2` tinyint(3) unsigned NOT NULL,";
	$query4 .= "`num3` tinyint(3) unsigned NOT NULL,";
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
	$query4 .= "percent_y1 float(4,2) unsigned NOT NULL default '0', ";
	$query4 .= "percent_y5 float(4,2) unsigned NOT NULL default '0', ";
	$query4 .= "percent_y10 float(4,2) unsigned NOT NULL default '0', ";
	$query4 .= "previous_date date NOT NULL default '1962-08-17', ";
	$query4 .= "last_date date NOT NULL default '1962-08-17', ";
	$query4 .= "PRIMARY KEY  (`id`)";
	$query4 .= ") ";

	print "$query4<p>";

	$mysqli_result4 = mysqli_query($mysqli_link, $query4) or die (mysqli_error($mysqli_link));

	print("<H2>Combo3 - position $combo_pos - $game_name - Limit $limit</H2>\n");

	//start table
	print("<P>");
	print("<TABLE BORDER=\"1\">\n");

	//create header row
	print("<TR>\n");
	print("<TD bgcolor=\"#CCCCCC\">Num1</TD>\n");
	print("<TD bgcolor=\"#CCCCCC\">Num2</TD>\n");
	print("<TD bgcolor=\"#CCCCCC\">Num3</TD>\n");
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

	$query_all = "SELECT * FROM $draw_prefix";
	$query_all .= "3_$balls ";
	$query_all .= "ORDER BY date DESC ";

	#echo "$query_all<br>";

	$mysqli_result_all = mysqli_query($query_all, $mysqli_link) or die (mysqli_error($mysqli_link));

	$num_rows_all = mysqli_num_rows($mysqli_result_all);

	for ($x = 1; $x <= $balls; $x++)
	{
		for ($y = $x + 1; $y <= $balls; $y++) ### $y = 1
		{
			for ($z = $y + 1; $z <= $balls; $z++) ### $y = 1
			{
				if ($x <> $y && $y <> $Z)
				{
					$sigma = 0;
					$draw_count_array = array_fill (0,17,0);
					
					print("<TR>\n");
					print("<TD align=center>$x</TD>\n");
					print("<TD align=center>$y</TD>\n");
					print("<TD align=center>$z</TD>\n");

					// 1
					$temp_date = gmstrftime("%Y-%m-%d", ($last_draw_unix));

					$table_temp = $draw_prefix . "3_" . $balls;

					$query5 = "SELECT d1,d2,combo,combo_count FROM $table_temp ";
					$query5 .= "WHERE d1 = $x AND d2 = $y AND d3 = $z and combo = $combo_pos "; 
					$query5 .= "AND date >= '$temp_date'  ";

					#echo "$query5<br>";
					
					$mysqli_result5 = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));

					$num_rows = mysqli_num_rows($mysqli_result5);

					$draw_count_array[0] += $num_rows;

					#$row = mysqli_fetch_array($mysqli_result5);
						
					if ($num_rows > 10)
					{
						print("<TD bgcolor=\"#FF0033\" align=center>$num_rows</TD>\n");
					} elseif ($num_rows > 5) {
						print("<TD bgcolor=\"#CCFFFF\" align=center>$num_rows</TD>\n");
					} elseif ($num_rows > 1) {
						print("<TD bgcolor=\"#CCFF66\" align=center>$num_rows</TD>\n");
					} elseif ($num_rows == 1) {
						print("<TD bgcolor=\"#F1F1F1\" align=center>$num_rows</TD>\n");
					} else {
						print("<TD align=center>$num_rows</TD>\n");
					}

					// 7
					$temp_date = gmstrftime("%Y-%m-%d", ($last_draw_unix-(86400*7)));

					$query5 = "SELECT d1,d2,combo,combo_count FROM $table_temp ";
					$query5 .= "WHERE d1 = $x AND d2 = $y AND d3 = $z and combo = $combo_pos "; 
					$query5 .= "AND date >= '$temp_date'  ";

					#echo "$query5<br>";
					
					$mysqli_result5 = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));

					$num_rows = mysqli_num_rows($mysqli_result5);
					#$sigma += $num_rows;
					$draw_count_array[1] += $num_rows;

					#$row = mysqli_fetch_array($mysqli_result5);
						
					if ($num_rows > 10)
					{
						print("<TD bgcolor=\"#FF0033\" align=center>$num_rows</TD>\n");
					} elseif ($num_rows > 5) {
						print("<TD bgcolor=\"#CCFFFF\" align=center>$num_rows</TD>\n");
					} elseif ($num_rows > 1) {
						print("<TD bgcolor=\"#CCFF66\" align=center>$num_rows</TD>\n");
					} elseif ($num_rows == 1) {
						print("<TD bgcolor=\"#F1F1F1\" align=center>$num_rows</TD>\n");
					} else {
						print("<TD align=center>$num_rows</TD>\n");
					}

					// 14
					$temp_date = gmstrftime("%Y-%m-%d", ($last_draw_unix-(86400*14)));

					$query5 = "SELECT d1,d2,combo,combo_count FROM $table_temp ";
					$query5 .= "WHERE d1 = $x AND d2 = $y AND d3 = $z and combo = $combo_pos ";
					$query5 .= "AND date >= '$temp_date'  ";

					#echo "$query5<br>";
					
					$mysqli_result5 = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));

					$num_rows = mysqli_num_rows($mysqli_result5);
					#$sigma += $num_rows;
					$draw_count_array[2] += $num_rows;

					#$row = mysqli_fetch_array($mysqli_result5);
						
					if ($num_rows > 10)
					{
						print("<TD bgcolor=\"#FF0033\" align=center>$num_rows</TD>\n");
					} elseif ($num_rows > 5) {
						print("<TD bgcolor=\"#CCFFFF\" align=center>$num_rows</TD>\n");
					} elseif ($num_rows > 1) {
						print("<TD bgcolor=\"#CCFF66\" align=center>$num_rows</TD>\n");
					} elseif ($num_rows == 1) {
						print("<TD bgcolor=\"#F1F1F1\" align=center>$num_rows</TD>\n");
					} else {
						print("<TD align=center>$num_rows</TD>\n");
					}

					// 30
					$temp_date = gmstrftime("%Y-%m-%d", ($last_draw_unix-(86400*30)));

					$query5 = "SELECT d1,d2,combo,combo_count FROM $table_temp ";
					$query5 .= "WHERE d1 = $x AND d2 = $y AND d3 = $z and combo = $combo_pos ";
					$query5 .= "AND date >= '$temp_date'  ";

					#echo "$query5<br>";
					
					$mysqli_result5 = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));

					$num_rows = mysqli_num_rows($mysqli_result5);
					#$sigma += $num_rows;
					$draw_count_array[3] += $num_rows;
					$pair_30 = $num_rows;

					#$row = mysqli_fetch_array($mysqli_result5);
						
					if ($num_rows > 10)
					{
						print("<TD bgcolor=\"#FF0033\" align=center>$num_rows</TD>\n");
					} elseif ($num_rows > 5) {
						print("<TD bgcolor=\"#CCFFFF\" align=center>$num_rows</TD>\n");
					} elseif ($num_rows > 1) {
						print("<TD bgcolor=\"#CCFF66\" align=center>$num_rows</TD>\n");
					} elseif ($num_rows == 1) {
						print("<TD bgcolor=\"#F1F1F1\" align=center>$num_rows</TD>\n");
					} else {
						print("<TD align=center>$num_rows</TD>\n");
					}

					// 90
					$temp_date = gmstrftime("%Y-%m-%d", ($last_draw_unix-(86400*90)));

					$query5 = "SELECT d1,d2,combo,combo_count FROM $table_temp ";
					$query5 .= "WHERE d1 = $x AND d2 = $y AND d3 = $z and combo = $combo_pos ";
					$query5 .= "AND date >= '$temp_date'  ";

					#echo "$query5<br>";
					
					$mysqli_result5 = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));

					$num_rows = mysqli_num_rows($mysqli_result5);
					#$sigma += $num_rows;
					$draw_count_array[4] += $num_rows;
					$pair_50 = $num_rows;

					#$row = mysqli_fetch_array($mysqli_result5);
						
					if ($num_rows > 10)
					{
						print("<TD bgcolor=\"#FF0033\" align=center>$num_rows</TD>\n");
					} elseif ($num_rows > 5) {
						print("<TD bgcolor=\"#CCFFFF\" align=center>$num_rows</TD>\n");
					} elseif ($num_rows > 1) {
						print("<TD bgcolor=\"#CCFF66\" align=center>$num_rows</TD>\n");
					} elseif ($num_rows == 1) {
						print("<TD bgcolor=\"#F1F1F1\" align=center>$num_rows</TD>\n");
					} else {
						print("<TD align=center>$num_rows</TD>\n");
					}

					// 180
					$temp_date = gmstrftime("%Y-%m-%d", ($last_draw_unix-(86400*180)));

					$query5 = "SELECT d1,d2,combo,combo_count FROM $table_temp ";
					$query5 .= "WHERE d1 = $x AND d2 = $y AND d3 = $z and combo = $combo_pos ";
					$query5 .= "AND date >= '$temp_date'  ";

					#echo "$query5<br>";
					
					$mysqli_result5 = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));

					$num_rows = mysqli_num_rows($mysqli_result5);
					#$sigma += $num_rows;
					$draw_count_array[5] += $num_rows;
					$pair_180 = $num_rows;

					#$row = mysqli_fetch_array($mysqli_result5);
						
					if ($num_rows > 10)
					{
						print("<TD bgcolor=\"#FF0033\" align=center>$num_rows</TD>\n");
					} elseif ($num_rows > 5) {
						print("<TD bgcolor=\"#CCFFFF\" align=center>$num_rows</TD>\n");
					} elseif ($num_rows > 1) {
						print("<TD bgcolor=\"#CCFF66\" align=center>$num_rows</TD>\n");
					} elseif ($num_rows == 1) {
						print("<TD bgcolor=\"#F1F1F1\" align=center>$num_rows</TD>\n");
					} else {
						print("<TD align=center>$num_rows</TD>\n");
					}

					// year1
					$temp_date = gmstrftime("%Y-%m-%d", ($last_draw_unix-(86400*365)));

					$query5 = "SELECT d1,d2,combo,combo_count FROM $table_temp ";
					$query5 .= "WHERE d1 = $x AND d2 = $y AND d3 = $z and combo = $combo_pos "; 
					$query5 .= "AND date >= '$temp_date'  ";

					#echo "$query5<br>";
					
					$mysqli_result5 = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));

					$num_rows = mysqli_num_rows($mysqli_result5);
					#$sigma += $num_rows;
					$draw_count_array[6] += $num_rows;
					$pair_365 = $num_rows;

					#$row = mysqli_fetch_array($mysqli_result5);
						
					if ($num_rows > 10)
					{
						print("<TD bgcolor=\"#FF0033\" align=center>$num_rows</TD>\n");
					} elseif ($num_rows > 5) {
						print("<TD bgcolor=\"#CCFFFF\" align=center>$num_rows</TD>\n");
					} elseif ($num_rows > 1) {
						print("<TD bgcolor=\"#CCFF66\" align=center>$num_rows</TD>\n");
					} elseif ($num_rows == 1) {
						print("<TD bgcolor=\"#F1F1F1\" align=center>$num_rows</TD>\n");
					} else {
						print("<TD align=center>$num_rows</TD>\n");
					}

					// year2
					$temp_date = gmstrftime("%Y-%m-%d", ($last_draw_unix-(86400*(365*2))));

					$query5 = "SELECT d1,d2,combo,combo_count FROM $table_temp ";
					$query5 .= "WHERE d1 = $x AND d2 = $y AND d3 = $z and combo = $combo_pos ";
					$query5 .= "AND date >= '$temp_date'  ";

					#echo "$query5<br>";
					
					$mysqli_result5 = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));

					$num_rows = mysqli_num_rows($mysqli_result5);
					#$sigma += $num_rows;
					$draw_count_array[7] += $num_rows;

					#$row = mysqli_fetch_array($mysqli_result5);
						
					if ($num_rows > 10)
					{
						print("<TD bgcolor=\"#FF0033\" align=center>$num_rows</TD>\n");
					} elseif ($num_rows > 5) {
						print("<TD bgcolor=\"#CCFFFF\" align=center>$num_rows</TD>\n");
					} elseif ($num_rows > 1) {
						print("<TD bgcolor=\"#CCFF66\" align=center>$num_rows</TD>\n");
					} elseif ($num_rows == 1) {
						print("<TD bgcolor=\"#F1F1F1\" align=center>$num_rows</TD>\n");
					} else {
						print("<TD align=center>$num_rows</TD>\n");
					}

					// year3
					$temp_date = gmstrftime("%Y-%m-%d", ($last_draw_unix-(86400*(365*3))));

					$query5 = "SELECT d1,d2,combo,combo_count FROM $table_temp ";
					$query5 .= "WHERE d1 = $x AND d2 = $y AND d3 = $z and combo = $combo_pos ";
					$query5 .= "AND date >= '$temp_date'  ";

					#echo "$query5<br>";
					
					$mysqli_result5 = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));

					$num_rows = mysqli_num_rows($mysqli_result5);
					#$sigma += $num_rows;
					$draw_count_array[8] += $num_rows;

					#$row = mysqli_fetch_array($mysqli_result5);
						
					if ($num_rows > 10)
					{
						print("<TD bgcolor=\"#FF0033\" align=center>$num_rows</TD>\n");
					} elseif ($num_rows > 5) {
						print("<TD bgcolor=\"#CCFFFF\" align=center>$num_rows</TD>\n");
					} elseif ($num_rows > 1) {
						print("<TD bgcolor=\"#CCFF66\" align=center>$num_rows</TD>\n");
					} elseif ($num_rows == 1) {
						print("<TD bgcolor=\"#F1F1F1\" align=center>$num_rows</TD>\n");
					} else {
						print("<TD align=center>$num_rows</TD>\n");
					}

					// year4
					$temp_date = gmstrftime("%Y-%m-%d", ($last_draw_unix-(86400*(365*4))));

					$query5 = "SELECT d1,d2,combo,combo_count FROM $table_temp ";
					$query5 .= "WHERE d1 = $x AND d2 = $y AND d3 = $z and combo = $combo_pos ";
					$query5 .= "AND date >= '$temp_date'  ";

					#echo "$query5<br>";
					
					$mysqli_result5 = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));

					$num_rows = mysqli_num_rows($mysqli_result5);
					#$sigma += $num_rows;
					$draw_count_array[9] += $num_rows;

					#$row = mysqli_fetch_array($mysqli_result5);
						
					if ($num_rows > 10)
					{
						print("<TD bgcolor=\"#FF0033\" align=center>$num_rows</TD>\n");
					} elseif ($num_rows > 5) {
						print("<TD bgcolor=\"#CCFFFF\" align=center>$num_rows</TD>\n");
					} elseif ($num_rows > 1) {
						print("<TD bgcolor=\"#CCFF66\" align=center>$num_rows</TD>\n");
					} elseif ($num_rows == 1) {
						print("<TD bgcolor=\"#F1F1F1\" align=center>$num_rows</TD>\n");
					} else {
						print("<TD align=center>$num_rows</TD>\n");
					}

					// year5
					$temp_date = gmstrftime("%Y-%m-%d", ($last_draw_unix-(86400*(365*5))));

					$query5 = "SELECT d1,d2,combo,combo_count FROM $table_temp ";
					$query5 .= "WHERE d1 = $x AND d2 = $y AND d3 = $z and combo = $combo_pos ";
					$query5 .= "AND date >= '$temp_date'  ";

					#echo "$query5<br>";
					
					$mysqli_result5 = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));

					$num_rows = mysqli_num_rows($mysqli_result5);
					#$sigma += $num_rows;
					$draw_count_array[10] += $num_rows;

					#$row = mysqli_fetch_array($mysqli_result5);
						
					if ($num_rows > 10)
					{
						print("<TD bgcolor=\"#FF0033\" align=center>$num_rows</TD>\n");
					} elseif ($num_rows > 5) {
						print("<TD bgcolor=\"#CCFFFF\" align=center>$num_rows</TD>\n");
					} elseif ($num_rows > 1) {
						print("<TD bgcolor=\"#CCFF66\" align=center>$num_rows</TD>\n");
					} elseif ($num_rows == 1) {
						print("<TD bgcolor=\"#F1F1F1\" align=center>$num_rows</TD>\n");
					} else {
						print("<TD align=center>$num_rows</TD>\n");
					}

					// year6
					$temp_date = gmstrftime("%Y-%m-%d", ($last_draw_unix-(86400*(365*6))));

					$query5 = "SELECT d1,d2,combo,combo_count FROM $table_temp ";
					$query5 .= "WHERE d1 = $x AND d2 = $y AND d3 = $z and combo = $combo_pos "; 
					$query5 .= "AND date >= '$temp_date'  ";

					#echo "$query5<br>";
					
					$mysqli_result5 = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));

					$num_rows = mysqli_num_rows($mysqli_result5);
					#$sigma += $num_rows;
					$draw_count_array[11] += $num_rows;

					#$row = mysqli_fetch_array($mysqli_result5);
						
					if ($num_rows > 10)
					{
						print("<TD bgcolor=\"#FF0033\" align=center>$num_rows</TD>\n");
					} elseif ($num_rows > 5) {
						print("<TD bgcolor=\"#CCFFFF\" align=center>$num_rows</TD>\n");
					} elseif ($num_rows > 1) {
						print("<TD bgcolor=\"#CCFF66\" align=center>$num_rows</TD>\n");
					} elseif ($num_rows == 1) {
						print("<TD bgcolor=\"#F1F1F1\" align=center>$num_rows</TD>\n");
					} else {
						print("<TD align=center>$num_rows</TD>\n");
					}

					// year7
					$temp_date = gmstrftime("%Y-%m-%d", ($last_draw_unix-(86400*(365*7))));

					$query5 = "SELECT d1,d2,combo,combo_count FROM $table_temp ";
					$query5 .= "WHERE d1 = $x AND d2 = $y AND d3 = $z and combo = $combo_pos ";
					$query5 .= "AND date >= '$temp_date'  ";

					#echo "$query5<br>";
					
					$mysqli_result5 = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));

					$num_rows = mysqli_num_rows($mysqli_result5);
					#$sigma += $num_rows;
					$draw_count_array[12] += $num_rows;

					#$row = mysqli_fetch_array($mysqli_result5);
						
					if ($num_rows > 10)
					{
						print("<TD bgcolor=\"#FF0033\" align=center>$num_rows</TD>\n");
					} elseif ($num_rows > 5) {
						print("<TD bgcolor=\"#CCFFFF\" align=center>$num_rows</TD>\n");
					} elseif ($num_rows > 1) {
						print("<TD bgcolor=\"#CCFF66\" align=center>$num_rows</TD>\n");
					} elseif ($num_rows == 1) {
						print("<TD bgcolor=\"#F1F1F1\" align=center>$num_rows</TD>\n");
					} else {
						print("<TD align=center>$num_rows</TD>\n");
					}

					// year8
					if ($year7 > $first_draw_unix) 
					{
						$temp_date = gmstrftime("%Y-%m-%d", ($last_draw_unix-(86400*(365*8))));

						$query5 = "SELECT d1,d2,combo,combo_count FROM $table_temp ";
						$query5 .= "WHERE d1 = $x AND d2 = $y AND d3 = $z and combo = $combo_pos ";
						$query5 .= "AND date >= '$temp_date'  ";

						#echo "$query5<br>";
						
						$mysqli_result5 = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));

						$num_rows = mysqli_num_rows($mysqli_result5);
						#$sigma += $num_rows;
						$draw_count_array[13] += $num_rows;
					} else {
						$num_rows = 0;
					}
						
					if ($num_rows > 10)
					{
						print("<TD bgcolor=\"#FF0033\" align=center>$num_rows</TD>\n");
					} elseif ($num_rows > 5) {
						print("<TD bgcolor=\"#CCFFFF\" align=center>$num_rows</TD>\n");
					} elseif ($num_rows > 1) {
						print("<TD bgcolor=\"#CCFF66\" align=center>$num_rows</TD>\n");
					} elseif ($num_rows == 1) {
						print("<TD bgcolor=\"#F1F1F1\" align=center>$num_rows</TD>\n");
					} else {
						print("<TD align=center>$num_rows</TD>\n");
					}

					// year9
					if ($year8 > $first_draw_unix) 
					{
						$temp_date = gmstrftime("%Y-%m-%d", ($last_draw_unix-(86400*(365*9))));

						$query5 = "SELECT d1,d2,combo,combo_count FROM $table_temp ";
						$query5 .= "WHERE d1 = $x AND d2 = $y AND d3 = $z and combo = $combo_pos ";
						$query5 .= "AND date >= '$temp_date'  ";

						#echo "$query5<br>";
						
						$mysqli_result5 = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));

						$num_rows = mysqli_num_rows($mysqli_result5);
						#$sigma += $num_rows;
						$draw_count_array[14] += $num_rows;
					} else {
						$num_rows = 0;
					}
						
					if ($num_rows > 10)
					{
						print("<TD bgcolor=\"#FF0033\" align=center>$num_rows</TD>\n");
					} elseif ($num_rows > 5) {
						print("<TD bgcolor=\"#CCFFFF\" align=center>$num_rows</TD>\n");
					} elseif ($num_rows > 1) {
						print("<TD bgcolor=\"#CCFF66\" align=center>$num_rows</TD>\n");
					} elseif ($num_rows == 1) {
						print("<TD bgcolor=\"#F1F1F1\" align=center>$num_rows</TD>\n");
					} else {
						print("<TD align=center>$num_rows</TD>\n");
					}
					
					// year10
					if ($year9 > $first_draw_unix) 
					{
						$temp_date = gmstrftime("%Y-%m-%d", ($last_draw_unix-(86400*(365*10))));

						$query5 = "SELECT d1,d2,combo,combo_count FROM $table_temp ";
						$query5 .= "WHERE d1 = $x AND d2 = $y AND d3 = $z and combo = $combo_pos ";
						$query5 .= "AND date >= '$temp_date'  ";

						#echo "$query5<br>";
						
						$mysqli_result5 = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));

						$num_rows = mysqli_num_rows($mysqli_result5);
						#$sigma += $num_rows;
						$draw_count_array[15] += $num_rows;
					} else {
						$num_rows = 0;
					}

					#$row = mysqli_fetch_array($mysqli_result5);
						
					if ($num_rows > 10)
					{
						print("<TD bgcolor=\"#FF0033\" align=center>$num_rows</TD>\n");
					} elseif ($num_rows > 5) {
						print("<TD bgcolor=\"#CCFFFF\" align=center>$num_rows</TD>\n");
					} elseif ($num_rows > 1) {
						print("<TD bgcolor=\"#CCFF66\" align=center>$num_rows</TD>\n");
					} elseif ($num_rows == 1) {
						print("<TD bgcolor=\"#F1F1F1\" align=center>$num_rows</TD>\n");
					} else {
						print("<TD align=center>$num_rows</TD>\n");
					}

					// 5000
					$query5 = "SELECT * FROM $table_temp ";
					$query5 .= "WHERE d1 = $x AND d2 = $y AND d3 = $z and combo = $combo_pos "; 

					#echo "$query5<br>";
					
					$mysqli_result5 = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));

					$num_rows = mysqli_num_rows($mysqli_result5);
					$draw_count_array[16] += $num_rows;
					$pair_5000 = $num_rows;

					#$row = mysqli_fetch_array($mysqli_result5);
						
					if ($num_rows > 10)
					{
						print("<TD bgcolor=\"#FF0033\" align=center>$num_rows</TD>\n");
					} elseif ($num_rows > 5) {
						print("<TD bgcolor=\"#CCFFFF\" align=center>$num_rows</TD>\n");
					} elseif ($num_rows > 1) {
						print("<TD bgcolor=\"#CCFF66\" align=center>$num_rows</TD>\n");
					} elseif ($num_rows == 1) {
						print("<TD bgcolor=\"#F1F1F1\" align=center>$num_rows</TD>\n");
					} else {
						print("<TD align=center>$num_rows</TD>\n");
					}

					#print("<TD align=center>&nbsp;</TD>\n"); 
					#print("<TD align=center>&nbsp;</TD>\n");

					$query_last = "SELECT * FROM $table_temp ";
					$query_last .= "WHERE d1 = $x AND d2 = $y AND d3 = $z and combo = $combo_pos ";  
					$query_last .= "ORDER BY date DESC "; 

					#echo "$query_last<br>";
					
					$mysqli_result_last = mysqli_query($query_last, $mysqli_link) or die (mysqli_error($mysqli_link));

					$row_last = mysqli_fetch_array($mysqli_result_last);

					$row_prev = mysqli_fetch_array($mysqli_result_last);

					$today  = mktime (0,0,0,date("m"),date("d"),date("Y"));

					$temp_date = gmstrftime("%Y-%m-%d", ($today-(86400*180)));
					
					if ($row_prev[last_date] == '1962-08-17' || $row_prev[last_date] < $temp_date)
					{
						print("<TD><font color=\"#ff0033\">$row_prev[last_date]</font></TD>\n");
					} else {
						print("<TD>$row_prev[last_date]</TD>\n");
					}

					if ($row_last[last_date] == '1962-08-17' || $row_last[last_date] < $temp_date)
					{
						print("<TD><font color=\"#ff0033\">$row_last[last_date]</font></TD>\n");
					} else {
						print("<TD>$row_last[last_date]</TD>\n");
					}

					$sigma = array_sum($draw_count_array);

					if ($sigma < 70)
					{
						print("<TD align=center>$sigma</TD>\n");
					} else {
						print("<TD bgcolor=\"#FF0033\" align=center>$sigma</TD>\n");
					}

					$col_temp_y1 = number_format(($draw_count_array[6]/365)*100,2);
					if ($col_temp_y1 >= 2.0)
					{
						print("<TD align=center><font size=\"-1\"><b>$col_temp_y1 %</b></font></TD>\n");
					} else {
						print("<TD align=center><font size=\"-1\">$col_temp_y1 %</font></TD>\n");
					}

					$col_temp_y5 = number_format(($draw_count_array[10]/(365*5))*100,2);
					if ($col_temp_y5 >= 2.0)
					{
						print("<TD align=center><font size=\"-1\"><b>$col_temp_y5 %</b></font></TD>\n");
					} else {
						print("<TD align=center><font size=\"-1\">$col_temp_y5 %</font></TD>\n");
					}

					$col_temp_y10 = number_format(($draw_count_array[15]/(365*10))*100,2);
					if ($col_temp_y10 >= 2.0)
					{
						print("<TD align=center><font size=\"-1\"><b>$col_temp_y10 %</b></font></TD>\n");
					} else {
						print("<TD align=center><font size=\"-1\">$col_temp_y10 %</font></TD>\n");
					}

					$previous_draw = '1962-08-17';
					$last_draw = '1962-08-17';

					$query_combo = "INSERT INTO $draw_prefix";
					$query_combo .= "temp_combo3";
					$query_combo .= "_p";
					$query_combo .= "$combo_pos ";
					$query_combo .= "VALUES ('0', ";
					$query_combo .= "'$x', ";
					$query_combo .= "'$y', ";
					$query_combo .= "'$z', ";
					for ($d = 0; $d <= 16; $d++) 
					{
						$query_combo .= "'{$draw_count_array[$d]}', ";
					}
					$query_combo .= "'$col_temp_y1', ";
					$query_combo .= "'$col_temp_y5', ";
					$query_combo .= "'$col_temp_y10', ";
					$query_combo .= "'$previous_draw', ";
					$query_combo .= "'$last_draw')";

					#echo "$query_combo<br>";

					$mysqli_result_combo = mysqli_query($query_combo, $mysqli_link) or die (mysqli_error($mysqli_link));
				}
			}
			print("<TR>\n");
			print("<TD bgcolor=\"#CCCCCC\">Num1</TD>\n");
			print("<TD bgcolor=\"#CCCCCC\">Num2</TD>\n");
			print("<TD bgcolor=\"#CCCCCC\">Num3</TD>\n");
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
	}

	//end table
	print("</TABLE>\n");

	// **************************************************************************

	//close page
	print("</BODY>\n");
	print("</HTML>\n");

?>
