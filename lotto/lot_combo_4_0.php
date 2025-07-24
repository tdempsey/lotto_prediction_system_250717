<?php

	ini_set('implicit_flush', 1);

	set_time_limit(0);

	$debug = 0;

	if ($debug)
	{
		ini_set('display_errors', '1');
		ini_set('display_startup_errors', '1');
		error_reporting(E_ALL);
	}

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
	#$game = 7; // Powerball12/16/2020
	// Game ----------------------------- Game

	$hml = 0;
	#$hml = 1;		#= high
	#$hml = 2;		#= medium
	#$hml = 3;		#= low
	#$hml = 4;		#= min
	#$hml = 5;		#= max
	#$hml = 110;	

	echo "start<br>";
	
	require ("includes/games_switch.incl");

	require ("includes/mysqli.php");

	#require ("includes/even_odd.php");
	require ("includes/build_rank_table.php");
	require ("includes/calculate_draw.php"); 
	require ("includes/calculate_rank.php");
	require ("includes/first_draw_unix.php");
	require ("includes/last_draw_unix.php");
	require ("includes/next_draw.php");

	#echo "3<br>";
	/*
	require_once ("$game_includes/calc_devsq.php");
	require ("includes/calculate_50_50.php");
	require ("includes/calculate_drange4.php");
	require ("includes/calculate_drange5.php");
	require ("includes/calculate_drange6.php");
	require ("includes/calculate_drange7.php");
	require ("includes/calculate_drange8.php");
	require ("includes/calculate_drange9.php");
	require ("includes/calculate_drange10.php");
	require ("includes/display.php");
	#require ("includes_ga_f5/split_draws_2.php");
	#require ("includes_ga_f5/split_draws_3.php");
	#require ("includes_ga_f5/split_draws_4.php");
	require ("includes/dateDiffInDays.php");
	require ("includes/unix.incl");
	

	require ("includes/display4.php");
	#require ("includes_ga_f5/split_draws_2.php");
	#require ("includes_ga_f5/split_draws_3.php");
	require ("includes_ga_f5/split_draws_4.php");
	require ("includes/dateDiffInDays.php");
	require ("includes/unix.incl");
	#require ("includes/log_text.incl");
	*/

	require ("includes/display4.php");
	require ("includes/dateDiffInDays.php");
	require ("includes/unix.incl");
	require ("includes_ga_f5/split_draws_4.php");	 

	date_default_timezone_set('America/New_York');

	require_once ("includes/hml_switch.incl");	

	require ("includes/display_include.incl");
	require ("$game_includes/print_table_analyze4.php");

	// ----------------------------------------------------------------------------------
	function print_sum_grid4($combin)
	{
		global $draw_table_name, $balls, $balls_drawn, $draw_prefix, $col1_select, $hml, $range_low, $range_high; 

		echo "enter print_sum_grid4<br>";	

		require ("includes/mysqli.php");
	
		require ("includes/calculate_sum_count4.incl");

		require ("includes/print_sum_table4.incl");

		print "<h3>Table <font color=\"#ff0000\">{$draw_prefix}sum $combin</font> Updated!</h3>";

		echo "leaving print_sum_grid4<br>";
	}

	// ----------------------------------------------------------------------------------
	function print_sum_grid_sum4($combin)
	{
		global $draw_table_name, $balls, $balls_drawn, $draw_prefix, $col1_select, $hml, $range_low, $range_high; 

		require ("includes/mysqli.php");
	
		require ("includes/calculate_sum_count_sum4.incl");

		require ("includes/print_sum_table_sum4.incl");

		require ("includes/print_sum_table_sum_top25_4.incl");

		print "<h3>Table <font color=\"#ff0000\">{$draw_prefix}sum $combin</font> Updated!</h3>";
	}

	//start HTML page
	print("<HTML>\n");
	print("<HEAD>\n");
	print("<TITLE>$game_name Combo_4_1 - $game_name</TITLE>\n");
	print("</HEAD>\n");
	
	print("<BODY>\n");

	print "<h3><a href=\"#26\">Limit 26</a> | <a href=\"#5000\">Limit 5000</a></h3>";

	#log_text("$game_name Display Start");

	################################################################################################

	$curr_date = date('Y-m-d');

	$date = strtotime($curr_date);
    $date = strtotime("-1 day", $date);
    $last_updated = date('Y-m-d', $date);

	#echo "$last_updated<br>";

	$date = explode('-', $last_updated);

	$lastupdated = $date[0];
	$lastupdated .= $date[1];
	$lastupdated .= $date[2];

	#echo "$lastupdated<br>";

	$drop_tables = 0;
	
	if ($drop_tables)
	{
		$profile_table = "combo_5_42_updated_";
		$profile_table .= "$lastupdated";

		$query = "DROP TABLE IF EXISTS $profile_table ";

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

		$query =  "CREATE TABLE IF NOT EXISTS $profile_table LIKE combo_5_42" ;

		print("$query<p>");

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

		$query = "INSERT INTO $profile_table ";
		$query .= "(SELECT * FROM combo_5_42  ";
		$query .= "WHERE last_updated = '$last_updated') ";

		print "$query<br>";
		
		$mysqli_result2 = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));
	}

	################################################################################################

	echo "calling ### 1 ###<br>";

	echo "### 2 ###<br>";
	
	split_draws_4 ($dateDiff);

	echo "### 3 ###<br>";

	print_table4($combin=1,$limit=30);

	lot_display4 (2048,$combin=1); 

	print_sum_grid4($combin=1);

	print_sum_grid_sum4($combin=1);

	print_sumeo_drange2_4($combin=1);

	die();

	print "<a href=\"lot_analyze.php\" target=\"_blank\">Open lot_analyze.php</a>"; 
	
	print("</BODY>\n");
?>
