<?php

	$game = 1; // Georgia F5
	$hml = 0;
	$draw_table_name = "ga_f5_draws";

	set_time_limit(0);

	$k = 1;

	$debug = 1;

	if ($debug)
	{
		ini_set('display_errors', '1');
		ini_set('display_startup_errors', '1');
		error_reporting(E_ALL);
	} else {
		error_reporting(0);
	}

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
	require ("includes/display_sumeo.php");
	#require ("includes_ga_f5/split_draws_2.php");
	require ("includes_ga_f5/split_draws_2.php");
	require ("includes_ga_f5/split_draws_3.php");
	require ("includes_ga_f5/split_draws_4.php");
	#require ("includes_ga_f5/split_sumeo_5.php");
	require ("includes_ga_f5/update_combo_sumeo_5.php");
	require ("includes/print_column_test_sumeo.php"); #200915
	#require ("includes_ga_f5/update_combo_sumeo_4.php");
	#require ("includes_ga_f5/update_combo_sumeo_3.php");
	#require ("includes_ga_f5/update_combo_sumeo_2.php");
	require ("includes_ga_f5/combin_sumeo.incl");

	require_once ("includes/dateDiffInDays.php");
	require_once ("includes/unix.incl");
	require_once ("includes/print_sumeo_drange.incl");	###220413

	date_default_timezone_set('America/New_York');

	require_once ("includes/hml_switch.incl");	

	//start HTML page
	print("<HTML>\n");
	print("<HEAD>\n");
	print("<TITLE>Lotto Cover 1K - 5/42</TITLE>\n");
	print("</HEAD>\n");
	
	print("<BODY bgcolor=\"#FFFFFF\" text=\"#000000\">\n");

	$curr_date = date('Y-m-d');
	$currdate = date('ymd');	

	$sumeo = 5;

	// ----------------------------------------------------------------------------------
	function print_sum_grid_sum4_sumeo($combin,$sumeo_sum,$sumeo_even,$sumeo_odd)
	{
		global $draw_table_name, $balls, $balls_drawn, $draw_prefix, $col1_select, $hml, $range_low, $range_high; 

		require ("includes/mysqli.php");
	
		require ("includes/calculate_sum_count_sum4_sumeo.incl");

		require ("includes/print_sum_table_sum4_sumeo.incl");

		#require ("includes/print_sum_table_sum_top25_4_sumeo.incl");

		#print "<h3>Table <font color=\"#ff0000\">{$draw_prefix}sum $combin</font> Updated!</h3>";
	}

	#split_sumeo_5 ($sum,$even,$odd);

	split_draws_4 (0);

	split_draws_3 (0);

	split_draws_2 (0);
?>