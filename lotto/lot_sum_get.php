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

	$debug = 1;

	if ($debug)
	{
		ini_set('display_errors', '1');
		ini_set('display_startup_errors', '1');
		error_reporting(E_ALL);
	}

	set_time_limit(0);

	#$combin = $_GET["combin"];

	$sum_sum = $_GET["sum"];
	#$sum_even = $_GET["even"];
	#$sum_odd = $_GET["odd"];
	
	require ("includes/games_switch.incl");

	require ("includes/mysqli.php");
	#require ("includes/db.class");
	require ('includes/db.class.php');

	require ("includes/even_odd.php");
	require ("includes/build_rank_table.php");
	require ("includes/calculate_draw.php"); 
	require ("includes/calculate_rank.php");
	require ("includes/first_draw_unix.php");
	require ("includes/last_draw_unix.php");
	require ("includes/last_draws.php");
	require ("includes/next_draw.php");
	#require ("includes_ga_f5/combin.incl");
	require_once ("$game_includes/calc_devsq.php");
	require ("includes/calculate_50_50.php");
	require ("includes/calculate_drange4.php");
	require ("includes/calculate_drange5.php");
	require ("includes/calculate_drange6.php");
	require ("includes/calculate_drange7.php");
	require ("includes/calculate_drange8.php");
	require ("includes/calculate_drange9.php");
	require ("includes/calculate_drange10.php");
	require ("includes/display_sum.php");
	#require ("includes_ga_f5/split_draws_2.php");
	require ("includes_ga_f5/split_sum_2.php");
	require ("includes_ga_f5/split_sum_3.php");
	require ("includes_ga_f5/split_sum_4.php");
	require ("includes_ga_f5/split_sum_5.php");
	require ("includes_ga_f5/update_combo_sum_5.php");
	require ("includes/print_column_test_sum.php"); #220530
	#require ("includes_ga_f5/update_combo_sum_4.php");
	#require ("includes_ga_f5/update_combo_sum_3.php");
	#require ("includes_ga_f5/update_combo_sum_2.php");
	require ("includes_ga_f5/combin_sum.incl");

	require_once ("includes/dateDiffInDays.php");
	require_once ("includes/unix.incl");
	require_once ("includes/print_sum_drange.incl");	###220413

	date_default_timezone_set('America/New_York');

	require_once ("includes/hml_switch.incl");	

	// ----------------------------------------------------------------------------------
	function print_draw_summary($sum)
	{
		global $draw_table_name, $balls, $balls_drawn, $draw_prefix, $col1_select, $hml, $range_low, $range_high, $game; 

		require ("includes/unix.incl");

		require ("includes/mysqli.php");
	
		require ("includes/calculate_draw_summary_sum.incl");

		require ("includes/print_draw_summary_sum.incl");

		echo "add range & summary<br>";

		#print "<h3>Table <font color=\"#ff0000\">{$draw_prefix}sum</font> Updated!</h3>";
	}

	// ----------------------------------------------------------------------------------
	function print_sum_grid_sum4_sum($combin,$sum_sum)
	{
		global $draw_table_name, $balls, $balls_drawn, $draw_prefix, $col1_select, $hml, $range_low, $range_high; 

		require ("includes/mysqli.php");
	
		require ("includes/calculate_sum_count_sum4_sum.incl");

		require ("includes/print_sum_table_sum4_sum.incl");

		#require ("includes/print_sum_table_sum_top25_4_sum.incl");

		#print "<h3>Table <font color=\"#ff0000\">{$draw_prefix}sum $combin</font> Updated!</h3>";
	}

	// ----------------------------------------------------------------------------------
	function print_sum_grid_sum3_sum($combin,$sum_sum)
	{
		global $draw_table_name, $balls, $balls_drawn, $draw_prefix, $col1_select, $hml, $range_low, $range_high; 

		require ("includes/mysqli.php");
	
		require ("includes/calculate_sum_count_sum3_sum.incl");

		require ("includes/print_sum_table_sum3_sum.incl");

		#require ("includes/print_sum_table_sum_top25_4_sum.incl");

		#print "<h3>Table <font color=\"#ff0000\">{$draw_prefix}sum $combin</font> Updated!</h3>";
	}

	// ----------------------------------------------------------------------------------
	function print_sum_grid_sum2_sum($combin,$sum_sum)
	{
		global $draw_table_name, $balls, $balls_drawn, $draw_prefix, $col1_select, $hml, $range_low, $range_high; 

		require ("includes/mysqli.php");
	
		require ("includes/calculate_sum_count_sum2_sum.incl");

		require ("includes/print_sum_table_sum2_sum.incl");

		#require ("includes/print_sum_table_sum_top25_4_sum.incl");

		#print "<h3>Table <font color=\"#ff0000\">{$draw_prefix}sum $combin</font> Updated!</h3>";
	}

	//start HTML page
	print("<HTML>\n");
	print("<HEAD>\n");
	print("<TITLE>$game_name Sum Split - $game_name</TITLE>\n");
	print("</HEAD>\n");
	
	print("<BODY>\n");

	print "<h3><a href=\"#26\">Limit 26</a> | <a href=\"#5000\">Limit 5000</a></h3>";

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

	$sum = $sum_sum;

	$sum = 5; ### <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<

		echo "<h2>*** SumEO $sum - sum $sum</h2>";

		echo "fix lot_display_sum<p>";

		#lot_display_sum($sum_sum);

		#test_combin_print_all_sum(100,$sum_sum);

		print_draw_summary($sum);

		/*
		require ("includes/print_sum_table_sum.incl");

		require ("includes/print_sum_table_drange2_sum.incl");

		print_sum_drange2_sum($sum_sum,$drange=2)

		require ("includes/print_sum_table_drange3_sum.incl");

		require ("includes/print_sum_table_drange4_sum.incl");

		require ("includes/print_sum_table_drange5_sum.incl");

		require ("includes/print_sum_table_drange6_sum.incl");

		require ("includes/print_sum_table_drange7_sum.incl");

		require ("includes/print_sum_table_drange8_sum.incl");
		*/
		#die();

		for ($col = 1; $col <= 5; $col++)
		{
			print_column_test_sum($col, $sum_sum); #200915
		}

		split_sum_5 ($sum);

		split_sum_4 ($sum);

		split_sum_3 ($sum_sum);

		split_sum_2 ($sum_sum);

		for ($s = 1; $s <= 5; $s++)
		{
			print_sum_grid_sum4_sum($combin=$s,$sum_sum);
		}

		for ($s = 1; $s <= 10; $s++)
		{
			print_sum_grid_sum3_sum($combin=$s,$sum_sum);
		}

		for ($s = 1; $s <= 10; $s++)
		{
			print_sum_grid_sum2_sum($combin=$s,$sum_sum);
		}

	#print "<a href=\"lot_analyze.php\" target=\"_blank\">Open lot_analyze.php</a>"; 
	
	print("</BODY>\n");
?>
