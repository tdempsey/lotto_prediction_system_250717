<?php

	ini_set('implicit_flush', 1);

	// add tables population for combos, pairs
	// add test for missing draws
	// recalculate draw includes
	// add db class
	// error checking - all modules
	// fix pair count
	#CREATE TABLE recipes_new LIKE production.recipes;
	#INSERT INTO recipes_new SELECT * FROM production.recipes;
	#$random_row = mysqli_fetch_row(mysqli_query("select * from YOUR_TABLE order by rand() limit 1"));
	// Game ----------------------------- Game
	$game = 1; // Georgia Fantasy 5
	#$game = 2; // Mega Millions
	//$game = 3; // Georgia 5
	#$game = 4; // Jumbo
	#$game = 5; // Florida Mega Money
	#$game = 6; // Florida Lotto
	#$game = 7; // Powerball
	// Game ----------------------------- Game

	$hml = 0;
	#$hml = 1;		#= high
	#$hml = 2;		#= medium
	#$hml = 3;		#= low
	#$hml = 4;		#= min
	#$hml = 5;		#= max
	#$hml = 110;	

	$sumeo_sum = $_GET["sum"];
	$sumeo_even = $_GET["even"];
	$sumeo_odd = $_GET["odd"];

	$debug = 1;

	if ($debug)
	{
		ini_set('display_errors', '1');
		ini_set('display_startup_errors', '1');
		error_reporting(E_ALL);
	}
	
	require ("includes/games_switch.incl");

	require ("includes/mysqli.php");
	#require ("includes/db.class");
	require ('includes/db.class.php');

	require ("includes/even_odd.php");
	if ($game == 10 OR $game == 20)
	{
		require ("includes_aon/build_rank_table_aon.php");
		require ("includes_aon/combin.incl");
	} else {
		require ("includes/build_rank_table.php");
	}
	require ("includes/calculate_draw.php"); 
	require ("includes/calculate_rank.php");
	require ("includes/first_draw_unix.php");
	require ("includes/last_draw_unix.php");
	require ("includes/next_draw.php");
	require_once ("$game_includes/calc_devsq.php");
	require ("includes/calculate_50_50.php");
	require ("includes/calculate_drange4.php");
	require ("includes/calculate_drange5.php");
	require ("includes/calculate_drange6.php");
	require ("includes/calculate_drange7.php");
	require ("includes/calculate_drange8.php");
	require ("includes/calculate_drange9.php");
	require ("includes/calculate_drange10.php");
	require ("includes/display_sumeo.php");
	#require ("includes_ga_f5/split_draws_2.php");
	#require ("includes_ga_f5/split_draws_3.php");
	require ("includes_ga_f5/split_draws_2.php");
	require ("includes_ga_f5/split_draws_3.php");
	require ("includes_ga_f5/split_draws_4.php");

	require ("includes/display.php");
	
	require ("includes/dateDiffInDays.php");
	echo "dateDiffInDays.php<br>";
	require ("includes/unix.incl");
	echo "unix.incl<br>";

	date_default_timezone_set('America/New_York');

	require_once ("includes/hml_switch.incl");	
	
	$debug = 0;

	// ----------------------------------------------------------------------------------
	function print_column_test($col,$row,$hml_sum)
	{
		global $debug, $draw_table_name, $balls, $balls_drawn, $draw_prefix, $game, $eo50_sum, $eo50_even, $eo50_odd, $eo50_d2_1, $eo50_d2_2; 

		require ("includes/mysqli.php"); 
		require ("includes/unix.incl"); 

		$query4 = "DROP TABLE IF EXISTS $draw_prefix";
		$query4 .= "column_$col";
		$query4 .= "_$hml_sum";
		$query4 .= "_temp";

		#echo "$query4<p>";

		$mysqli_result4 = mysqli_query($mysqli_link, $query4) or die (mysqli_error($mysqli_link));

		$query4 = "CREATE TABLE $draw_prefix";
		$query4 .= "column_$col";
		$query4 .= "_$hml_sum";
		$query4 .= "_temp (";
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
		$query4 .= "percent_w1 float(4,1) unsigned NOT NULL default '0', ";
		$query4 .= "percent_w2 float(4,1) unsigned NOT NULL default '0', ";
		$query4 .= "percent_m1 float(4,1) unsigned NOT NULL default '0', ";
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

		$range_low = $hml_sum;
		$range_high = $hml_sum + 9;

		$query = "SELECT * FROM $draw_table_name ";
		$query .= "WHERE sum >= $range_low  ";
		$query .= "AND   sum <= $range_high  ";
		$query .= "AND   id < $row[id]  ";
		$query .= "ORDER BY id DESC ";

		#print "$query<p>";

		#$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

		$num_rows_all = mysqli_num_rows($mysqli_result);

		$draw = 1;

		$temp_array = array_fill (0,17,0);
		$column_count_array = array_fill (0,$balls+1,$temp_array);

		while($row = mysqli_fetch_array($mysqli_result))
		{
			$temp_draw = array (0);

			for ($v = 1; $v <= $balls_drawn; $v++)
			{
				$w = $v + 2;
				array_push($temp_draw, $row[$w]);
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
			# prev/last
			$prev_date = '1962-08-17';
			$last_date = '1962-08-17';

			$query_date = "SELECT * FROM $draw_table_name ";
			$query_date .= "WHERE sum >= $range_low  ";
			$query_date .= "AND   sum <= $range_high  ";
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

			# sigma
			$sigma = 0;

			$sigma = ($column_count_array[$x][1]*0.50) +
				 (($column_count_array[$x][2] - $column_count_array[$x][1])*0.30) +
				 (($column_count_array[$x][3] - $column_count_array[$x][2])*0.20);

			$sum_temp_w1 = number_format(($column_count_array[$x][1]/28)*100,2);

			$sum_temp_w2 = number_format(($column_count_array[$x][2]/56)*100,2);

			$sum_temp_m1 = number_format(($column_count_array[$x][3]/120)*100,2);

			$sum_temp_y1 = number_format(($column_count_array[$x][6]/(365*4))*100,1);

			$weighted_average = (
				($column_count_array[$x][1]/7*100*0.10) + #week1
				($column_count_array[$x][3]/30*100*0.10) + #month1
				($column_count_array[$x][5]/(365/2)*100*0.15) + #month6
				($column_count_array[$x][6]/365*100*0.15) + #year1
				($column_count_array[$x][10]/(365*5)*100*0.25) + #year5
				($column_count_array[$x][13]/(365*8)*100*0.25)); #year8

			$sum_temp = number_format($weighted_average,1);

			#print("x = $x<br>");

			#print_r ("$column_count_array");
			#die ("$column_count_array");

			$query9 = "INSERT INTO $draw_prefix";
			$query9 .= "column_$col";
			$query9 .= "_$hml_sum";
			$query9 .= "_temp ";
			$query9 .= "VALUES ('$x', ";
			for ($d = 0; $d <= 15; $d++) 
			{
				$query9 .= "'{$column_count_array[$x][$d]}', ";
			}
			#$query5 .= "'$num_rows', ";
			$query9 .= "'{$column_count_array[$x][16]}', ";
			$query9 .= "'$sum_temp_w1', ";
			$query9 .= "'$sum_temp_w2', ";
			$query9 .= "'$sum_temp_m1', ";
			$query9 .= "'$sum_temp_y1', ";
			$query9 .= "'$sum_temp_y5', ";
			$query9 .= "'$weighted_average', ";
			$query9 .= "'$row_last[last_date]',"; 
			$query9 .= "'$row_prev[last_date]')";

			#print "$query9<p>";
			#die ("column_count_array");
		
			$mysqli_result9 = mysqli_query($mysqli_link, $query9) or die (mysqli_error($mysqli_link));
		}
	}

	// ----------------------------------------------------------------------------------
	function print_column_test2($col,$row,$hml_sum)
	{
		global $debug, $draw_table_name, $balls, $balls_drawn, $draw_prefix, $game, $hml, $range_low, $range_high, $game, $eo50_sum, $eo50_even, $eo50_odd, $eo50_d2_1, $eo50_d2_2; 

		require ("includes/mysqli.php"); 
		require ("includes/unix.incl"); 

		$query4 = "DROP TABLE IF EXISTS $draw_prefix";
		$query4 .= "column_$col";
		$query4 .= "_$hml_sum";

		#echo "$query4<p>";

		$mysqli_result4 = mysqli_query($mysqli_link, $query4) or die (mysqli_error($mysqli_link));

		$query4 = "CREATE TABLE $draw_prefix";
		$query4 .= "column_$col";
		$query4 .= "_$hml_sum (";
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

		$range_low = $hml_sum;
		$range_high = $hml_sum + 9;
	
		$mysqli_result4 = mysqli_query($mysqli_link, $query4) or die (mysqli_error($mysqli_link));

		$query6 = "SELECT * FROM $draw_table_name ";
		if ($hml_sum)
		{
			$query6 .= "WHERE sum >= $range_low  ";
			$query6 .= "AND   sum <= $range_high  ";
			$query6 .= "AND   id < $row[id]  ";
		} else {
			$query6 .= "WHERE id < $row[id]  ";
		}
		$query6 .= "ORDER BY id DESC ";

		print "$query6<p>";

		#$mysqli_result6 = mysqli_query($mysqli_link, $query6) or die (mysqli_error($mysqli_link));

		$num_rows_all = mysqli_num_rows($mysqli_result6);

		$draw = 1;

		$temp_array = array_fill (0,17,0);
		$column_count_array = array_fill (0,$balls+1,$temp_array);

		while($row6 = mysqli_fetch_array($mysqli_result6))
		{
			#print_r($row6);
			#echo "<p>";
			$temp_draw = array (0);

			if ($game == 10 OR $game == 20)
			{
				for ($v = 1; $v <= $balls_drawn; $v++)
				{
					$w = $v + 2;
					array_push($temp_draw, $row6[$w]);
				}
			} else {
				for ($v = 1; $v <= $balls_drawn; $v++)
				{
					array_push($temp_draw, $row6[$v]);
				}
			}

			sort ($temp_draw);

			$x = $temp_draw[$col];

			$draw_date_array = date_parse("$row6[date]");
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

			$weighted_average = (
				($column_count_array[$x][1]/7*100*0.10) + #week1
				($column_count_array[$x][3]/30*100*0.10) + #month1
				($column_count_array[$x][5]/(365/2)*100*0.15) + #month6
				($column_count_array[$x][6]/365*100*0.15) + #year1
				($column_count_array[$x][10]/(365*5)*100*0.25) + #year5
				($column_count_array[$x][13]/(365*8)*100*0.25)); #year8

			$sum_temp = number_format($weighted_average,1);

			$query5 = "INSERT INTO $draw_prefix";
			$query5 .= "column_";
			$query5 .= "$col";
			$query5 .= "_$hml_sum ";
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

			print "$query5<p>";
			#die ("column_count_array");
		
			$mysqli_result = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));
		#}
	}
	
	function lot_combo ($combo_count)
	{ 
		global $debug, $game, $game_name, $balls, $balls_drawn, $mega_balls, $draw_table_name, $draw_prefix, $eo50_sum, $eo50_even, $eo50_odd, $eo50_d2_1, $eo50_d2_2;
	
		require ("includes/mysqli.php");
		require ("includes/unix.incl");

		$table_temp = $draw_prefix . "_" . $combo_count . $balls_drawn;

		$query = "DROP TABLE IF EXISTS $table_temp";

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

		$query = "CREATE TABLE $table_temp";
		$query .= "(";
		$query .= "id int(10) unsigned NOT NULL auto_increment, ";

		for ($r = 1; $r <= $combo_count; $r++)
		{
			$query .= "d";
			$query .= "$r tinyint(3) unsigned NOT NULL default '0', ";
		}
		
		$query .= "day1 tinyint(3) unsigned NOT NULL default '0', ";
		$query .= "week1 tinyint(3) unsigned NOT NULL default '0', ";
		$query .= "week2 tinyint(3) unsigned NOT NULL default '0', ";
		$query .= "month1 tinyint(3) unsigned NOT NULL default '0', ";
		$query .= "month3 tinyint(3) unsigned NOT NULL default '0', ";
		$query .= "month6 tinyint(3) unsigned NOT NULL default '0', ";
		$query .= "year1 int(5) unsigned NOT NULL default '0', ";
		$query .= "year2 int(5) unsigned NOT NULL default '0', ";
		$query .= "year3 int(5) unsigned NOT NULL default '0', ";
		$query .= "year4 int(5) unsigned NOT NULL default '0', ";
		$query .= "year5 int(5) unsigned NOT NULL default '0', ";
		$query .= "year6 int(5) unsigned NOT NULL default '0', ";
		$query .= "year7 int(5) unsigned NOT NULL default '0', ";
		$query .= "year8 int(5) unsigned NOT NULL default '0', ";
		$query .= "year9 int(5) unsigned NOT NULL default '0', ";
		$query .= "year10 int(5) unsigned NOT NULL default '0', ";
		$query .= "count int(5) unsigned NOT NULL default '0', ";
		$query .= "percent_30 float (4,1) unsigned NOT NULL default '0.0', ";
		$query .= "percent_365 float (4,1) unsigned NOT NULL default '0.0', ";
		$query .= "percent_5000 float (4,1) unsigned NOT NULL default '0.0', ";
		$query .= "PRIMARY KEY  (id), ";
		$query .= ")  ";

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));
	
		$query = "SELECT * FROM $draw_table_name ";
		$query .= "ORDER BY date ASC ";
		//$query .= "LIMIT 1 ";
	
		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));
	
		//start table
		print("<TABLE BORDER=\"1\">\n");
	
		//create header row
		print("<TR><B>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">&nbsp;</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Date</TD>\n");
		
		for($index = 1; $index <= $balls_drawn; $index++)
		{
			print("<TD BGCOLOR=\"#CCCCCC\">Pick $index</TD>\n");
		}
		
		print("<TD BGCOLOR=\"#CCCCCC\">Sum</TD>\n");
		print("</B></TR>\n");
	
		$dcount = 1;
	
		while($row = mysqli_fetch_array($mysqli_result))
		{
			for ($x = 1; $x <= $balls_drawn; $x++)
			{
				array_push($draw, $row[$x]);
			}
	
			print("<TR>\n");
	
			print("<TD><B>$dcount</B></TD>\n");
			print("<TD>$row[date]</TD>\n");
			
			for($index = 1; $index <= $balls_drawn; $index++)
			{
				print("<TD BGCOLOR=\"#CCCCCC\">Pick $row[$index]</TD>\n");
			}
			
			print("<TD>$row[sum]</TD>\n");
	
			print("</TR>\n");
			
			$dc = array_fill (0, ($combo_count), 0);
			//$combo_count = 0;
			
			for  ($x = 1; $x < $balls_drawn; $x++)
			{
				for ($y = ($x+1); $y <= $balls_drawn; $y++)
				{
					for ($z = 1; $z <= $combo_count; $z++)
					{
						$dc[$x] = $row[$z];
						
						for ($b = $z + 1; $b <= $balls_drawn; $b++) // draw index
						{
							$dc[$z] = $row[$b];
							$combo_count++;
						}
					}
				}
			}
				
				$query3 = "Insert INTO $table_temp ";
				$query3 .= "VALUES ( '0', ";
				$query3 .= "         '$row[0]', ";
				
				for($z = 1; $z <= $combo_count; $z++)
				{
					$query3 .= "         $dc[$z], ";
				}
				
				$query3 .= "	     ) ";
	
				//print "$query3<p>";
				
				$mysqli_result3 = mysqli_query($mysqli_link, $query3) or die (mysqli_error($mysqli_link));

				$dcount++;
			}
		
		print("</TABLE>\n");
	}

	function lot_grid ($limit)
	{
		global $debug, $game, $game_name, $balls, $balls_drawn, $mega_balls, $draw_table_name, $draw_prefix, $grid_26_flag, $grid_all_flag, $eo50_sum, $eo50_even, $eo50_odd, $eo50_d2_1, $eo50_d2_2;
		
		require ("includes/mysqli.php");

		print "<a name=\"grid$limit\"><H2>Pair Grid - $limit</H2></a>";

		print "<h4><a href=\"#unsorted$limit\">Unsorted $limit</a> | <a href=\"#sorted$limit\">Sorted $limit</a> | <a href=\"#pairs$limit\">Pairs $limit</a> | <a href=\"#rank$limit\">Rank $limit</a> | <a href=\"#grid$limit\">Grid $limit</a></h4>";

		$table_temp = $draw_prefix . "temp_2_" . $limit;


		//start table
		print("<TABLE BORDER=\"1\">\n");

		//create header row
		print("<TR><B>\n");
		print("<TD width=20>&nbsp;</TD>\n");
		for ($x = 1; $x <= $balls; $x++)
		{
			print("<TD align=center width=20><b>$x</b></TD>\n");
		}
		print("<TD width=20>&nbsp;</TD>\n");
		print("</TR></B>\n");

		if ($limit == 26)
		{
			$grid_flag = $grid_26_flag;
		} else {
			$grid_flag = $grid_all_flag;
		}

		// process rows
		for ($x = 1; $x <= $balls; $x++)
		{
			print("<TR>\n");
			print("<TD align=center><b>$x</b></TD>\n");
			for ($y = 1; $y <= $balls; $y++)
			{
				if ($x != $y)
				{
					$query5 = "SELECT * FROM $table_temp ";
					$query5 .= "WHERE num1 = $x AND num2 = $y "; 
					$query5 .= "ORDER BY last_date DESC ";

					//print("$query5<br>");
					
					$mysqli_result5 = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));

					$num_rows = mysqli_num_rows($mysqli_result5);

					if ($game == 2 || $game == 7)
					{
						if ($num_rows == 3 || $num_rows == 4 || $num_rows > 10)
						{
							print("<TD bgcolor=\"#99ff00\" align=center><b>$num_rows</b></TD>\n");
						} elseif ($num_rows > 15) {
							print("<TD bgcolor=\"#99ffff\" align=center><b>$num_rows</b></TD>\n");
						} else {
							print("<TD align=center>$num_rows</TD>\n");
						}
					} else {
						if ($num_rows == 3 || $num_rows == 4 || $num_rows > 35)
						{
							print("<TD bgcolor=\"#99ff00\" align=center><b>$num_rows</b></TD>\n");
						} elseif ($num_rows > $grid_flag) {
							print("<TD bgcolor=\"#99ffff\" align=center><b>$num_rows</b></TD>\n");
						} else {
							print("<TD align=center>$num_rows</TD>\n");
						}

					}
				} else {
					print("<TD>&nbsp;</TD>\n");
				}
			}
			    print("<TD align=center><b>$x</b></TD>\n");
				print("</TR>\n");
		}

		//create footer row
		print("<TR><B>\n");
		print("<TD>&nbsp;</TD>\n");
		for ($x = 1; $x <= $balls; $x++)
		{
			print("<TD align=center><b>$x</b></TD>\n");
		}
		print("<TD>&nbsp;</TD>\n");
		print("</TR></B>\n");

		//end table
		print("</TABLE>\n");
	}

	function lot_grid_2 ($limit,$all)
	{
		global $debug, $game, $game_name, $balls, $balls_drawn, $mega_balls, $draw_table_name, $draw_prefix, $grid_26_flag, $grid_all_flag, $eo50_sum, $eo50_even, $eo50_odd, $eo50_d2_1, $eo50_d2_2;
		
		require ("includes/mysqli.php");

		print "<a name=\"grid5000\"><H2>Pair Grid - $limit/$all</H2></a>";

		print "<h4><a href=\"#unsorted$limit\">Unsorted $limit</a> | <a href=\"#sorted$limit\">Sorted $limit</a> | <a href=\"#pairs$limit\">Pairs $limit</a> | <a href=\"#rank$limit\">Rank $limit</a> | <a href=\"#grid$limit\">Grid $limit</a></h4>";

		$table_temp = $draw_prefix . "temp_2_" . $limit;

		//start table
		print("<TABLE BORDER=\"1\">\n");

		//create header row
		print("<TR><B>\n");
		print("<TD width=20>&nbsp;</TD>\n");
		for ($x = 1; $x <= $balls; $x++)
		{
			print("<TD align=center><b>$x</b></TD>\n");
		}
		print("<TD width=20>&nbsp;</TD>\n");
		print("</TR></B>\n");

		$grid_flag = $grid_all_flag;

		// process rows
		for ($x = 1; $x <= $balls; $x++)
		{
			print("<TR>\n");
			print("<TD align=center><b>$x</b></TD>\n");
			for ($y = 1; $y <= $balls; $y++)
			{
				if ($x != $y)
				{
					$query5 = "SELECT * FROM $table_temp ";
					$query5 .= "WHERE num1 = $x AND num2 = $y "; 
					$query5 .= "ORDER BY last_date DESC ";
					
					$mysqli_result5 = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));

					$num_rows_limit = mysqli_num_rows($mysqli_result5);

					$query6 = "SELECT * FROM $draw_prefix";
					$query6 .= "temp_2_"; 
					$query6 .= "$all "; 
					$query6 .= "WHERE num1 = $x AND num2 = $y "; 
					$query6 .= "ORDER BY last_date DESC ";
					
					$mysqli_result6 = mysqli_query($mysqli_link, $query6) or die (mysqli_error($mysqli_link));

					$num_rows_all = mysqli_num_rows($mysqli_result6);

					if ($game == 1 || $game == 4)
					{
						if ($num_rows_all > 45)
						{
							print("<TD bgcolor=\"#ff0000\" align=center><b>$num_rows_limit/$num_rows_all</b></TD>\n");
						} elseif ($num_rows_limit == 3 || $num_rows_limit == 4 || $num_rows_all > 45) {
							print("<TD bgcolor=\"#66cc00\" align=center><b>$num_rows_limit/$num_rows_all</b></TD>\n");
						} elseif ($num_rows_limit == 3 || $num_rows_limit == 4) {
							print("<TD bgcolor=\"#99ff00\" align=center><b>$num_rows_limit/$num_rows_all</b></TD>\n");
						} elseif ($num_rows_all >= 15) {
							print("<TD bgcolor=\"#ffffff\" align=center><b>$num_rows_limit/$num_rows_all</b></TD>\n");
						} else {
							print("<TD align=center><b>$num_rows_limit/$num_rows_all</b></TD>\n");
						}
					} else {
						if ($num_rows_all > 20)
						{
							print("<TD bgcolor=\"#ffffff\" align=center><b>$num_rows_limit/$num_rows_all</b></TD>\n");
						} elseif ($num_rows_limit == 3 || $num_rows_limit == 4 || $num_rows_all >= 15) {
							print("<TD bgcolor=\"#66cc00\" align=center><b>$num_rows_limit/$num_rows_all</b></TD>\n");
						} elseif ($num_rows > $grid_flag) {
							print("<TD bgcolor=\"#99ffff\" align=center><b>$num_rows</b></TD>\n");
						} else {
							#print("<TD bgcolor=\"#f0f0f0\" align=center><b>$num_rows_limit/$num_rows_all</b></TD>\n");
							print("<TD align=center><b>$num_rows_limit/$num_rows_all</b></TD>\n");
						}
					}
				} else {
					print("<TD>&nbsp;</TD>\n");
				}
			}
			    print("<TD align=center><b>$x</b></TD>\n");
				print("</TR>\n");
		}

		//create footer row
		print("<TR><B>\n");
		print("<TD>&nbsp;</TD>\n");
		for ($x = 1; $x <= $balls; $x++)
		{
			print("<TD align=center><b>$x</b></TD>\n");
		}
		print("<TD>&nbsp;</TD>\n");
		print("</TR></B>\n");

		//end table
		print("</TABLE>\n");
	}

	function lot_grid_wa ($limit)
	{
		global $debug, $game, $game_name, $balls, $balls_drawn, $mega_balls, $draw_table_name, $draw_prefix, $grid_26_flag, $grid_all_flag, $eo50_sum, $eo50_even, $eo50_odd, $eo50_d2_1, $eo50_d2_2;
		
		require ("includes/mysqli.php");

		print "<a name=\"grid$limit\"><H2>Pair Grid - Weighted Averages - $limit</H2></a>";

		print "<h4><a href=\"#unsorted$limit\">Unsorted $limit</a> | <a href=\"#sorted$limit\">Sorted $limit</a> | <a href=\"#pairs$limit\">Pairs $limit</a> | <a href=\"#rank$limit\">Rank $limit</a> | <a href=\"#grid$limit\">Grid $limit</a></h4>";

		$table_temp = $draw_prefix . "temp_pairs_" . $limit;


		//start table
		print("<TABLE BORDER=\"1\">\n");

		//create header row
		print("<TR><B>\n");
		print("<TD width=20>&nbsp;</TD>\n");
		for ($x = 1; $x <= $balls; $x++)
		{
			print("<TD align=center width=20><b>$x</b></TD>\n");
		}
		print("<TD width=20>&nbsp;</TD>\n");
		print("</TR></B>\n");

		if ($limit == 26)
		{
			$grid_flag = $grid_26_flag;
		} else {
			$grid_flag = $grid_all_flag;
		}

		// process rows
		for ($x = 1; $x <= $balls; $x++)
		{
			print("<TR>\n");
			print("<TD align=center><b>$x</b></TD>\n");
			for ($y = 1; $y <= $balls; $y++)
			{
				if ($x != $y)
				{
					$query5 = "SELECT * FROM $table_temp ";
					$query5 .= "WHERE num1 = $x AND num2 = $y "; 
					$query5 .= "ORDER BY last_date DESC ";

					//print("$query5<br>");
					
					$mysqli_result5 = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));

					$row = mysqli_fetch_array($mysqli_result5);

					//$num_rows = mysqli_num_rows($mysqli_result5);

					#if ($row[percent_wa] > 2.9)
					if ($row[percent_365] >= 1.0)
					{
						print("<TD bgcolor=\"#99ff00\" align=center><b>$row[percent_365]</b></TD>\n");
					#} elseif ($row[percent_wa] > 1.4) {
					#} elseif ($row[percent_wa] >= 0.5) {
					} elseif ($row[percent_365] >= 0.8) {
						print("<TD bgcolor=\"#99ffff\" align=center><b>$row[percent_365]</b></TD>\n");
					} else {
						print("<TD align=center>$row[percent_365]</TD>\n");
					}
				} else {
					print("<TD>&nbsp;</TD>\n");
				}
			}
			    print("<TD align=center><b>$x</b></TD>\n");
				print("</TR>\n");
		}

		//create footer row
		print("<TR><B>\n");
		print("<TD>&nbsp;</TD>\n");
		for ($x = 1; $x <= $balls; $x++)
		{
			print("<TD align=center><b>$x</b></TD>\n");
		}
		print("<TD>&nbsp;</TD>\n");
		print("</TR></B>\n");

		//end table
		print("</TABLE>\n");
	}

	// ----------------------------------------------------------------------------------
	function test_columns($cola,$colb) 
	{
		global $debug, $game, $draw_prefix, $draw_table_name, $balls, $balls_drawn, $eo50_sum, $eo50_even, $eo50_odd, $eo50_d2_1, $eo50_d2_2;

		require ("includes/mysqli.php");
		require ("includes/unix.incl");

		$table_temp = $draw_prefix . 'temp_col_' . $cola . '_' . $colb;

		$query = "DROP TABLE IF EXISTS $table_temp";

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

		$query = "CREATE TABLE $table_temp";
		$query .= "(id int(10) unsigned NOT NULL auto_increment, ";
		$query .= "num1 tinyint(3) unsigned NOT NULL default '0', ";
		$query .= "num2 tinyint(3) unsigned NOT NULL default '0', ";
		$query .= "day1 tinyint(3) unsigned NOT NULL default '0', ";
		$query .= "week1 tinyint(3) unsigned NOT NULL default '0', ";
		$query .= "week2 tinyint(3) unsigned NOT NULL default '0', ";
		$query .= "month1 tinyint(3) unsigned NOT NULL default '0', ";
		$query .= "month3 tinyint(3) unsigned NOT NULL default '0', ";
		$query .= "month6 tinyint(3) unsigned NOT NULL default '0', ";
		$query .= "year1 tinyint(3) unsigned NOT NULL default '0', ";
		$query .= "year2 tinyint(3) unsigned NOT NULL default '0', ";
		$query .= "year3 tinyint(3) unsigned NOT NULL default '0', ";
		$query .= "year4 tinyint(3) unsigned NOT NULL default '0', ";
		$query .= "year5 tinyint(3) unsigned NOT NULL default '0', ";
		$query .= "year6 tinyint(3) unsigned NOT NULL default '0', ";
		$query .= "year7 tinyint(3) unsigned NOT NULL default '0', ";
		$query .= "year8 tinyint(3) unsigned NOT NULL default '0', ";
		$query .= "year9 tinyint(3) unsigned NOT NULL default '0', ";
		$query .= "year10 tinyint(3) unsigned NOT NULL default '0', ";
		$query .= "count int(5) unsigned NOT NULL default '0', ";
		$query .= "prev_draw date NOT NULL default '1962-08-17', ";
		$query .= "last_draw date NOT NULL default '1962-08-17', ";
		$query .= "percent_365 float (4,1) unsigned NOT NULL default '0', ";
		$query .= "percent_5000 float (4,1) unsigned NOT NULL default '0', ";
		$query .= "percent_wa float (4,1) unsigned NOT NULL default '0', ";
		$query .= "PRIMARY KEY  (id), ";
		$query .= "KEY num1 (num1), ";
		$query .= "KEY num2 (num2) ";
		$query .= ")  ";

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

		$query_all = "SELECT * FROM $draw_table_name ";
		$query_all .= "ORDER BY date DESC ";

		$mysqli_result_all = mysqli_query($query_all, $mysqli_link) or die (mysqli_error($mysqli_link));

		$num_rows_all = mysqli_num_rows($mysqli_result_all);

		$today  = mktime (0,0,0,date("m"),date("d"),date("Y"));

		for ($x = 1; $x < $balls; $x++)
		{
			for ($y = ($x+1); $y <= $balls; $y++)
			{
				// day1
				$query5 = "SELECT * FROM $draw_table_name ";
				$query5 .= "WHERE b$cola = $x AND b$colb = $y "; 
				$query5 .= "AND date = '$last_draw_unix'  ";
				
				$mysqli_result5 = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));

				$pair_day1 = mysqli_num_rows($mysqli_result5);

				// week1
				$temp_date = gmstrftime("%Y-%m-%d", ($today-(86400*7)));

				$query5 = "SELECT * FROM $draw_table_name ";
				$query5 .= "WHERE b$cola = $x AND b$colb = $y "; 
				$query5 .= "AND date >= '$temp_date'  ";
				
				$mysqli_result5 = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));

				$pair_week1 = mysqli_num_rows($mysqli_result5);

				// week2
				$temp_date = gmstrftime("%Y-%m-%d", ($today-(86400*14)));

				$query5 = "SELECT * FROM $draw_table_name ";
				$query5 .= "WHERE b$cola = $x AND b$colb = $y "; 
				$query5 .= "AND date >= '$temp_date'  ";
				
				$mysqli_result5 = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));
				$pair_week2 = mysqli_num_rows($mysqli_result5);

				// month1
				$temp_date = gmstrftime("%Y-%m-%d", ($today-(86400*30)));

				$query5 = "SELECT * FROM $draw_table_name ";
				$query5 .= "WHERE b$cola = $x AND b$colb = $y "; 
				$query5 .= "AND date >= '$temp_date'  ";
				
				$mysqli_result5 = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));
				$pair_month1 = mysqli_num_rows($mysqli_result5);

				// month3
				$temp_date = gmstrftime("%Y-%m-%d", ($today-(86400*90)));

				$query5 = "SELECT * FROM $draw_table_name ";
				$query5 .= "WHERE b$cola = $x AND b$colb = $y "; 
				$query5 .= "AND date >= '$temp_date'  ";
				
				$mysqli_result5 = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));
				$pair_month3 = mysqli_num_rows($mysqli_result5);

				// month6
				$temp_date = gmstrftime("%Y-%m-%d", ($today-(86400*180)));

				$query5 = "SELECT * FROM $draw_table_name ";
				$query5 .= "WHERE b$cola = $x AND b$colb = $y "; 
				$query5 .= "AND date >= '$temp_date'  ";
				
				$mysqli_result5 = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));
				$pair_month6 = mysqli_num_rows($mysqli_result5);

				// year1
				$temp_date = gmstrftime("%Y-%m-%d", ($today-(86400*365)));

				$query5 = "SELECT * FROM $draw_table_name ";
				$query5 .= "WHERE b$cola = $x AND b$colb = $y "; 
				$query5 .= "AND date >= '$temp_date'  ";
				
				$mysqli_result5 = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));
				$pair_year1 = mysqli_num_rows($mysqli_result5);

				// year2
				$temp_date = gmstrftime("%Y-%m-%d", ($today-(86400*(365*2))));

				$query5 = "SELECT * FROM $draw_table_name ";
				$query5 .= "WHERE b$cola = $x AND b$colb = $y "; 
				$query5 .= "AND date >= '$temp_date'  ";
				
				$mysqli_result5 = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));
				$pair_year2 = mysqli_num_rows($mysqli_result5);

				// year3
				$temp_date = gmstrftime("%Y-%m-%d", ($today-(86400*(365*3))));

				$query5 = "SELECT * FROM $draw_table_name ";
				$query5 .= "WHERE b$cola = $x AND b$colb = $y "; 
				$query5 .= "AND date >= '$temp_date'  ";
				
				$mysqli_result5 = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));
				$pair_year3 = mysqli_num_rows($mysqli_result5);

				// year4
				$temp_date = gmstrftime("%Y-%m-%d", ($today-(86400*(365*4))));

				$query5 = "SELECT * FROM $draw_table_name ";
				$query5 .= "WHERE b$cola = $x AND b$colb = $y "; 
				$query5 .= "AND date >= '$temp_date'  ";
				
				$mysqli_result5 = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));
				$pair_year4 = mysqli_num_rows($mysqli_result5);

				// year5
				$temp_date = gmstrftime("%Y-%m-%d", ($today-(86400*(365*5))));

				$query5 = "SELECT * FROM $draw_table_name ";
				$query5 .= "WHERE b$cola = $x AND b$colb = $y "; 
				$query5 .= "AND date >= '$temp_date'  ";
				
				$mysqli_result5 = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));
				$pair_year5 = mysqli_num_rows($mysqli_result5);

				// year6
				$temp_date = gmstrftime("%Y-%m-%d", ($today-(86400*(365*6))));

				$query5 = "SELECT * FROM $draw_table_name ";
				$query5 .= "WHERE b$cola = $x AND b$colb = $y "; 
				$query5 .= "AND date >= '$temp_date'  ";
				
				$mysqli_result5 = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));
				$pair_year6 = mysqli_num_rows($mysqli_result5);

				// year7
				$temp_date = gmstrftime("%Y-%m-%d", ($today-(86400*(365*7))));

				$query5 = "SELECT * FROM $draw_table_name ";
				$query5 .= "WHERE b$cola = $x AND b$colb = $y "; 
				$query5 .= "AND date >= '$temp_date'  ";
				
				$mysqli_result5 = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));
				$pair_year7 = mysqli_num_rows($mysqli_result5);

				// year8
				$temp_date = gmstrftime("%Y-%m-%d", ($today-(86400*(365*8))));

				$query5 = "SELECT * FROM $draw_table_name ";
				$query5 .= "WHERE b$cola = $x AND b$colb = $y "; 
				$query5 .= "AND date >= '$temp_date'  ";
				
				$mysqli_result5 = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));
				$pair_year8 = mysqli_num_rows($mysqli_result5);

				# year9
				$temp_date = gmstrftime("%Y-%m-%d", ($today-(86400*(365*9))));

				$query5 = "SELECT * FROM $draw_table_name ";
				$query5 .= "WHERE b$cola = $x AND b$colb = $y "; 
				$query5 .= "AND date >= '$temp_date'  ";
				
				$mysqli_result5 = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));
				$pair_year9 = mysqli_num_rows($mysqli_result5);

				# year10
				$temp_date = gmstrftime("%Y-%m-%d", ($today-(86400*(365*10))));

				$query5 = "SELECT * FROM $draw_table_name ";
				$query5 .= "WHERE b$cola = $x AND b$colb = $y "; 
				$query5 .= "AND date >= '$temp_date'  ";
				
				$mysqli_result5 = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));
				$pair_year10 = mysqli_num_rows($mysqli_result5);

				$pair_temp_year1 = number_format(($pair_year1/365)*100,1);

				$pair_temp_year8 = number_format(($pair_year8/$num_rows_all)*100,1);

				$row2 = mysqli_fetch_array($mysqli_result5);

				$last_draw = $row2[date];

				$row3 = mysqli_fetch_array($mysqli_result5);

				$prev_draw = $row3[date];

				$weighted_average = (
					($pair_week1/10*100*0.01) +
					($pair_month1/30*100*0.05) +
					($pair_month3/90*100*0.10) +
					($pair_year1/365*100*0.20) +
					($pair_year3/(365*3)*100*0.20) +
					($pair_year5/(365*5)*100*0.20) +
					($pair_year8/$num_rows_all*100*0.24));

				$pair_temp_wa = number_format($weighted_average,1);

				$query_insert = "INSERT INTO $draw_prefix";
				$query_insert .= "temp_col_";
				$query_insert .= "$cola";
				$query_insert .= "_$colb ";
				$query_insert .= "VALUES ('0', ";
				$query_insert .= "'$x', ";
				$query_insert .= "'$y', ";
				$query_insert .= "'$pair_day1', "; 
				$query_insert .= "'$pair_week1', "; 
				$query_insert .= "'$pair_week2', "; 
				$query_insert .= "'$pair_month1', "; 
				$query_insert .= "'$pair_month3', "; 
				$query_insert .= "'$pair_month6', ";
				$query_insert .= "'$pair_year1', "; 
				$query_insert .= "'$pair_year2', ";
				$query_insert .= "'$pair_year3', ";
				$query_insert .= "'$pair_year4', ";
				$query_insert .= "'$pair_year5', ";
				$query_insert .= "'$pair_year6', ";
				$query_insert .= "'$pair_year7', ";
				$query_insert .= "'$pair_year8', ";
				$query_insert .= "'$pair_year9', ";
				$query_insert .= "'$pair_year10', ";
				$query_insert .= "'$pair_year10', ";
				$query_insert .= "'$prev_draw', "; 
				$query_insert .= "'$last_draw', "; 
				$query_insert .= "'$pair_temp_year1', "; 
				$query_insert .= "'$pair_temp_year8', "; 
				$query_insert .= "'$pair_temp_wa')"; 
			
				$mysqli_result_insert = mysqli_query($query_insert, $mysqli_link) or die (mysqli_error($mysqli_link)); 
			}
		}

		# copy current table into dated table
		$curr_date = date("ymd");

		$table_temp = $draw_prefix . 'temp_col_' . $cola . '_' . $colb;
		$table_temp_date = $table_temp . "_" . $curr_date;

		$query = "DROP TABLE IF EXISTS $table_temp_date";

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

		$query_copy .= "CREATE TABLE $table_temp_date SELECT * FROM $table_temp";

		$mysqli_result = mysqli_query($mysqli_link, $query_copy) or die (mysqli_error($mysqli_link));

		print "<h3>Table <font color=\"#ff0000\">$table_temp</font> Updated!</h3>";
		print "<h3>Table <font color=\"#ff0000\">$table_temp_date</font> Updated!</h3>";
	}

	function lot_grid_wa_y1 ($limit)
	{
		global $debug, $game, $game_name, $balls, $balls_drawn, $mega_balls, $draw_table_name, $draw_prefix, $grid_26_flag, $grid_all_flag, $eo50_sum, $eo50_even, $eo50_odd, $eo50_d2_1, $eo50_d2_2;
		
		require ("includes/mysqli.php");

		print "<a name=\"grid$limit\"><H2>Pair Grid - Weighted Averages - Year 1 - $limit</H2></a>";

		print "<h4><a href=\"#unsorted$limit\">Unsorted $limit</a> | <a href=\"#sorted$limit\">Sorted $limit</a> | <a href=\"#pairs$limit\">Pairs $limit</a> | <a href=\"#rank$limit\">Rank $limit</a> | <a href=\"#grid$limit\">Grid $limit</a></h4>";

		$table_temp = $draw_prefix . "temp_pairs_" . $limit;


		//start table
		print("<TABLE BORDER=\"1\">\n");

		//create header row
		print("<TR><B>\n");
		print("<TD width=20>&nbsp;</TD>\n");
		for ($x = 1; $x <= $balls; $x++)
		{
			print("<TD align=center width=20><b>$x</b></TD>\n");
		}
		print("<TD width=20>&nbsp;</TD>\n");
		print("</TR></B>\n");

		if ($limit == 26)
		{
			$grid_flag = $grid_26_flag;
		} else {
			$grid_flag = $grid_all_flag;
		}

		// process rows
		for ($x = 1; $x <= $balls; $x++)
		{
			print("<TR>\n");
			print("<TD align=center><b>$x</b></TD>\n");
			for ($y = 1; $y <= $balls; $y++)
			{
				if ($x != $y)
				{
					$query5 = "SELECT * FROM $table_temp ";
					$query5 .= "WHERE num1 = $x AND num2 = $y "; 
					$query5 .= "ORDER BY last_date DESC ";

					//print("$query5<br>");
					
					$mysqli_result5 = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));

					$row = mysqli_fetch_array($mysqli_result5);

					//$num_rows = mysqli_num_rows($mysqli_result5);

					#if ($row[percent_wa] > 2.9)
					if ($row[percent_wa] >= 1.0)
					{
						print("<TD bgcolor=\"#99ff00\" align=center><b>$row[percent_wa]</b></TD>\n");
					#} elseif ($row[percent_wa] > 1.4) {
					#} elseif ($row[percent_wa] >= 0.5) {
					} elseif ($row[percent_wa] >= 0.8) {
						print("<TD bgcolor=\"#99ffff\" align=center><b>$row[percent_wa]</b></TD>\n");
					} else {
						print("<TD align=center>$row[percent_wa]</TD>\n");
					}
				} else {
					print("<TD>&nbsp;</TD>\n");
				}
			}
			    print("<TD align=center><b>$x</b></TD>\n");
				print("</TR>\n");
		}

		//create footer row
		print("<TR><B>\n");
		print("<TD>&nbsp;</TD>\n");
		for ($x = 1; $x <= $balls; $x++)
		{
			print("<TD align=center><b>$x</b></TD>\n");
		}
		print("<TD>&nbsp;</TD>\n");
		print("</TR></B>\n");

		//end table
		print("</TABLE>\n");
	}

	// ----------------------------------------------------------------------------------
	function update_counts($count,$draw_date) 
	{
		require ("includes/mysqli.php"); 

		global $debug, $game, $draw_prefix, $draw_table_name, $balls, $balls_drawn, $eo50_sum, $eo50_even, $eo50_odd, $eo50_d2_1, $eo50_d2_2;

		$table_temp = $draw_prefix . $count . "_" . $balls;

		$query = "SELECT * FROM $draw_table_name ";
		$query .= "WHERE date > '$draw_date' ";
		$query .= "ORDER BY date ASC "; 

		#print "$query<p>";

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

		$temp_include = "combin_" . $count . "_" . $balls_drawn . ".incl";

		### check for hml count and update
		while($row = mysqli_fetch_array($mysqli_result))
		{
			$query5 = "SELECT * FROM $table_temp ";
			$query5 .= "WHERE date = '$row[date]' ";
			$query5 .= "AND hml = 0 "; # = all

			echo "$query5<br>";

			#echo "$query5 --- checking ---<br>";

			$mysqli_result5 = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));

			if (!$num_rows = mysqli_num_rows($mysqli_result5))
			{
				#echo "--- updating ---<br>";
				require ("includes/$temp_include");
			}
		}


		#print "<h3>Table <font color=\"#ff0000\">$table_temp</font> Updated!</h3>";
	}

	// ----------------------------------------------------------------------------------
	function update_counts_hml($count,$draw_date) 
	{
		require ("includes/mysqli.php"); 

		global $debug, $game, $draw_prefix, $draw_table_name, $balls, $balls_drawn, $eo50_sum, $eo50_even, $eo50_odd, $eo50_d2_1, $eo50_d2_2;

		$table_temp = $draw_prefix . $count . "_" . $balls;

		$query = "SELECT * FROM $draw_table_name ";
		$query .= "WHERE date > '$draw_date' ";
		$query .= "ORDER BY date ASC "; 

		#print "$query<p>";

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

		$temp_include = "combin_" . $count . "_" . $balls_drawn . "_hml" . ".incl";

		### check for hml count and update
		while($row = mysqli_fetch_array($mysqli_result))
		{
			$hml = (intval($row[sum]/10)) * 10; # = decade

			$query5 = "SELECT * FROM $table_temp ";
			$query5 .= "WHERE date = '$row[date]' ";
			$query5 .= "AND hml = $hml ";

			echo "$query5<br>";

			#echo "$query5 --- checking ---<br>";

			$mysqli_result5 = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));

			if (!$num_rows = mysqli_num_rows($mysqli_result5))
			{
				#echo "--- updating ---<br>";
				require ("includes/$temp_include");
			}
		}

		print "<h3>Table <font color=\"#ff0000\">$table_temp hml=$hml</font> Updated!</h3>";
	}

	// ----------------------------------------------------------------------------------
	function update_counts_hml_sum($count,$draw_date) 
	{
		require ("includes/mysqli.php"); 

		global $debug, $game, $draw_prefix, $draw_table_name, $balls, $balls_drawn, $eo50_sum, $eo50_even, $eo50_odd, $eo50_d2_1, $eo50_d2_2;

		$table_temp = $draw_prefix . $count . "_" . $balls;

		$query = "SELECT * FROM $draw_table_name ";
		$query .= "WHERE date > '$draw_date' ";
		$query .= "ORDER BY date ASC "; 

		#print "$query<p>";

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

		$temp_include = "combin_" . $count . "_" . $balls_drawn . "_hml" . ".incl";

		### check for hml count and update
		while($row = mysqli_fetch_array($mysqli_result))
		{
			$hml = $row[sum] + 500; # = sum

			$query5 = "SELECT * FROM $table_temp ";
			$query5 .= "WHERE date = '$row[date]' ";
			$query5 .= "AND hml = $hml ";

			#echo "$query5 --- checking ---<br>";

			$mysqli_result5 = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));

			if (!$num_rows = mysqli_num_rows($mysqli_result5))
			{
				#echo "--- updating ---<br>";
				require ("includes/$temp_include");
			}
		}

		print "<h3>Table <font color=\"#ff0000\">$table_temp hml=$hml</font> Updated!</h3>";
	}

	// ----------------------------------------------------------------------------------
	function update_counts_hml_sum_1510($count,$draw_date) 
	{
		require ("includes/mysqli.php"); 

		global $debug, $game, $draw_prefix, $draw_table_name, $balls, $balls_drawn, $eo50_sum, $eo50_even, $eo50_odd, $eo50_d2_1, $eo50_d2_2;

		$table_temp = $draw_prefix . $count . "_" . $balls;

		$query = "SELECT * FROM $draw_table_name ";
		$query .= "WHERE date > '$draw_date' ";
		$query .= "WHERE date >= '2015-10-1' ";
		$query .= "ORDER BY date ASC "; 

		#print "$query<p>";

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

		$temp_include = "combin_" . $count . "_" . $balls_drawn . "_hml" . ".incl";

		### check for hml count and update
		while($row = mysqli_fetch_array($mysqli_result))
		{
			$hml = $row[sum] + 20000; # >= 2015-10-01

			$query5 = "SELECT * FROM $table_temp ";
			$query5 .= "WHERE date = '$row[date]' ";
			$query5 .= "AND hml = $hml ";

			#echo "$query5 --- checking ---<br>";

			$mysqli_result5 = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));

			if (!$num_rows = mysqli_num_rows($mysqli_result5))
			{
				#echo "--- updating ---<br>";
				require ("includes/$temp_include");
			}
		}

		#print "<h3>Table <font color=\"#ff0000\">$table_temp</font> Updated!</h3>";
	}

	// ----------------------------------------------------------------------------------
	function update_counts_eo4($count,$draw_date) 
	{
		require ("includes/mysqli.php"); 

		global $debug, $game, $draw_prefix, $draw_table_name, $balls, $balls_drawn, $eo50_sum, $eo50_even, $eo50_odd, $eo50_d2_1, $eo50_d2_2;

		$table_temp = $draw_prefix . $count . "_" . $balls;

		$query = "SELECT * FROM $draw_table_name ";
		$query .= "WHERE date > '$draw_date' ";
		$query .= "ORDER BY date ASC "; 

		#print "$query<p>";

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

		$temp_include = "combin_" . $count . "_" . $balls_drawn . "_hml" . ".incl";

		### check for hml count and update
		while($row = mysqli_fetch_array($mysqli_result))
		{
			$hml = $row[sum] + 54000;

			$query5 = "SELECT * FROM $table_temp ";
			$query5 .= "WHERE date = '$row[date]' ";
			$query5 .= "AND hml = $hml ";

			#echo "$query5 --- checking ---<br>";

			$mysqli_result5 = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));

			if (!$num_rows = mysqli_num_rows($mysqli_result5))
			{
				#echo "--- updating ---<br>";
				require ("includes/$temp_include");
			}
		}

		#print "<h3>Table <font color=\"#ff0000\">$table_temp</font> Updated!</h3>";
	}

	function update_column_combin($count,$column) 
	{
		require ("includes/mysqli.php"); 

		global $debug, $game, $draw_prefix, $draw_table_name, $balls, $balls_drawn, $eo50_sum, $eo50_even, $eo50_odd, $eo50_d2_1, $eo50_d2_2;

		$table_temp = $draw_prefix . "column" . $column . "_combin" . $count;

		$query = "TRUNCATE TABLE $table_temp ";

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

		$query = "SELECT * FROM $draw_table_name ";
		$query .= "ORDER BY date ASC ";

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

		// get each draw table row
		while($row = mysqli_fetch_array($mysqli_result))
		{
			$y = $column;

			$query9 = "Insert INTO $table_temp ";
			$query9 .= "VALUES ( '0', ";
			$query9 .= "     '$row[0]', ";
			for ($x = 1; $x < $count; $x++)
			{
				$query9 .= " '$row[$y]', ";
				$y++;
			}
			$query9 .= "'$row[$y]', ";
			$query9 .= "'0') ";
			
			$mysqli_result9 = mysqli_query($mysqli_link, $query9) or die (mysqli_error($mysqli_link));
		}

		$query = "SELECT * FROM $table_temp ";
		//$query .= "ORDER BY date ASC ";

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

		// get each row
		while($row = mysqli_fetch_array($mysqli_result))
		{
			if (!$row[count])
			{
				$y = 2;
				$query7 = "SELECT * FROM $table_temp ";
				$query7 .= "WHERE ";
				for ($x = 1; $x < $count; $x++)
				{
					$query7 .= " d$x = $row[$y] AND ";
					$y++;
				}
				$query7 .= " d$x = $row[$y] ";

				//print "$query7<br>";

				$mysqli_result7 = mysqli_query($mysqli_link, $query7) or die (mysqli_error($mysqli_link));

				$num_rows = mysqli_num_rows($mysqli_result7);

				$y = 2;
				$query9 = "UPDATE $table_temp ";
				$query9 .= "SET count = $num_rows ";
				$query9 .= "WHERE ";
				for ($x = 1; $x < $count; $x++)
				{
					$query9 .= " d$x = $row[$y] AND ";
					$y++;
				}
				$query9 .= " d$x = $row[$y] ";

				//print "$query9<br>";
				
				$mysqli_result9 = mysqli_query($mysqli_link, $query9) or die (mysqli_error($mysqli_link));
			}
		}

		#print "<h3>Table <font color=\"#ff0000\">$table_temp</font> Updated!</h3>";
	}

	// ----------------------------------------------------------------------------------
	function build_aon_draws_neg() 
	{
		require ("includes/mysqli.php"); 

		global $debug, $game, $draw_prefix, $draw_table_name, $balls, $balls_drawn, $eo50_sum, $eo50_even, $eo50_odd, $eo50_d2_1, $eo50_d2_2;

		$table_temp = $draw_prefix . "draws_neg";

		$query = "SELECT * FROM $draw_table_name ";
		$query .= "ORDER BY id ASC "; 

		print "$query<p>";

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

		while($row = mysqli_fetch_array($mysqli_result))
		{
			$draw_num_pos = array ();
			$draw_num = array ();

			for ($t = 1; $t <= 12; $t++)
			{
				array_push($draw_num_pos, $draw_num[d.$t]);
			}
			
			for ($r = 1; $r <= 24; $r++)
			{
				if (array_search($draw_num_pos[$y], $r) !== FALSE)
				{
					array_push($draw_num, $r);
					echo "draw_array_neg added $r<br>";
				}
			}
		}

		$query = "INSERT INTO $table_temp ";
		$query .= "VALUES ('0', ";
		$query .= "'$draw_num[1]', ";
		$query .= "'$draw_num[2]', ";
		$query .= "'$draw_num[3]', ";
		$query .= "'$draw_num[4]', ";
		$query .= "'$draw_num[5]', ";
		$query .= "'$draw_num[6]', ";
		$query .= "'$draw_num[7]', ";
		$query .= "'$draw_num[8]', ";
		$query .= "'$draw_num[9]', ";
		$query .= "'$draw_num[10]', ";
		$query .= "'$draw_num[11]', ";
		$query .= "'$draw_num[12]', ";
		$query .= "'0', ";
		$query .= "'0', ";
		$query .= "'0', ";
		$query .= "'0', ";
		$query .= "'0', ";

		for($z = 0; $z < 3; $z++)
		{
			$query .= "0, ";
		}

		for($z = 0; $z < 4; $z++)
		{
			$query .= "0, ";
		}

		$query .= "'0', ";
		$query .= "'0', ";
		$query .= "'0', ";

		$query .= "'0', ";
		$query .= "'0', ";
		$query .= "'0', ";
		$query .= "'0', ";
		$query .= "'0', ";
		$query .= "'0', ";
		$query .= "'0', ";
		$query .= "'0', ";
		$query .= "'0', ";
		$query .= "'0', ";
		$query .= "'0', ";
		$query .= "'0', ";
		$query .= "'0', ";
		$query .= "'0', ";
		$query .= "'0', ";
		$query .= "'0', ";
		$query .= "'0', ";
		$query .= "'0', ";
		$query .= "'0', ";
		$query .= "'0', ";
		$query .= "'0', ";

		$query .= "'0', ";
		$query .= "'0', ";
		$query .= "'0', ";
		$query .= "'0', ";

		$query .= "'0', ";
		$query .= "'0', ";
		$query .= "'0', ";
		$query .= "'0', ";
		$query .= "'0', ";

		$query .= "'0', ";
		$query .= "'0', ";
		$query .= "'0', ";
		$query .= "'0', ";
		$query .= "'0', ";
		$query .= "'0', ";
		$query .= "'0', ";
		$query .= "'0', ";
		$query .= "'0', ";
		$query .= "'0', ";

		$query .= "'0', ";

		$query .= "'$avg', ";
		#$query .= "'$median', ";
		$query .= "'0', ";
		$query .= "'$harmean', ";
		$query .= "'$geomean', ";
		$query .= "'$quart1', ";
		$query .= "'$quart2', ";
		$query .= "'$quart3', ";
		$query .= "'$stdev', ";
		$query .= "'$variance', ";
		$query .= "'$avedev', ";
		$query .= "'$kurtosis', ";
		$query .= "'$skew', ";
		#$query .= "'$devsq', ";
		$query .= "'0', ";
		$query .= "'$wheel_generated_rows', ";
		$query .= "'$wa_sum', ";

		$query .= "'$draw_last', ";
		$query .= "'$draw_count', ";
		
		$query .= "'$curr_date')";

		#print "$query<br>";
		
		$mysqli_result2 = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));
	}


	//start HTML page
	print("<HTML>\n");
	print("<HEAD>\n");
	print("<TITLE>$game_name Display - $game_name</TITLE>\n");
	print("</HEAD>\n");
	
	print("<BODY>\n");

	print "<h3><a href=\"#26\">Limit 26</a> | <a href=\"#5000\">Limit 5000</a></h3>";

	################################################################################################

	$curr_date = date('Y-m-d');

	$date = strtotime($curr_date);
    $date = strtotime("-1 day", $date);
    $last_updated = date('Y-m-d', $date);

	echo "$last_updated<br>";

	$date = explode('-', $last_updated);

	$lastupdated = $date[0];
	$lastupdated .= $date[1];
	$lastupdated .= $date[2];

	echo "$lastupdated<br>";

	$drop_tables = 1;
	
	if ($drop_tables)
	{
		$profile_table = "combo_5_42_updated_";
		$profile_table .= "$lastupdated";

		$query = "DROP TABLE IF EXISTS $profile_table ";

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

		$query =  "CREATE TABLE IF NOT EXISTS $profile_table LIKE combo_5_42" ;

		print("$query<p>");

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));
	}

	$query = "INSERT INTO $profile_table ";
	$query .= "(SELECT * FROM combo_5_42  ";
	$query .= "WHERE last_updated = '$last_updated') ";

	print "$query<br>";
	
	$mysqli_result2 = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

	################################################################################################

	#split_draws_4 (10000);

	lot_display_sumeo ($sumeo_sum,$sumeo_even,$sumeo_odd);

	die();

	lot_display (10);

	lot_display (14);

	lot_display (26);

	lot_display (28);
	
	lot_display (30);

	lot_display (1560); #190704
	
	lot_display (10000);

	#split_draws_4 (10000);

	#split_draws_3 (10000);

	#split_draws_2 (10000);
	
	#lot_display_4 (5);
	
	for ($x = 2; $x < $balls_drawn; $x++)
	{
		if ($game == 7)
		{
			$table_temp = $draw_prefix . $x. "_75";
		} else {
			$table_temp = $draw_prefix . $x. "_" . $balls;
		}

		$query = "SELECT * FROM $table_temp ";
		$query .= "WHERE hml <> 0 ";
		$query .= "ORDER BY date DESC ";

		print "$query<p>";

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

		$row_date = mysqli_fetch_array($mysqli_result);

		$row_date[date] = "1962-08-16";

		#echo "update_counts $row_date[date]<br>";
		update_counts($x,'2019-06-10');

		#update_counts_hml($x,$row_date[date]);
		#echo "update_counts 2019-05-01<br>";
		update_counts_hml($x,'2019-06-10');

		#update_counts_hml_sum($x,$row_date[date]);
		#echo "update_counts2019-05-01<br>";
		update_counts_hml_sum($x,'2019-06-10');
	}

	print "<a href=\"lot_analyze.php\" target=\"_blank\">Open lot_analyze.php</a>"; 
	
	print("</BODY>\n");
?>
