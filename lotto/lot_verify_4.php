<?php
	header('Expires: Sun, 01 Jan 2014 00:00:00 GMT');
	header('Cache-Control: no-store, no-cache, must-revalidate');
	header('Cache-Control: post-check=0, pre-check=0', FALSE);
	header('Pragma: no-cache');

	set_time_limit(0);
	
	$game = 1; // Georgia Fantasy 5

	$hml = 0;
	#$hml = 1;		#= high
	#$hml = 2;		#= medium
	#$hml = 3;		#= low
	#$hml = 4;		#= min
	#$hml = 5;		#= max
	#$hml = 110;	
	
	require ("includes/games_switch.incl");

	$debug = 1;

	if ($debug)
	{
		ini_set('display_errors', '1');
		ini_set('display_startup_errors', '1');
		error_reporting(E_ALL);
	}

	require ("includes/mysqli.php");
	#require ("includes/db.class");
	require ('includes/db.class.php');
	require ("includes/even_odd.php");
	require ("includes/build_rank_table.php");
	require ("includes/calculate_draw.php"); 
	require ("includes/calculate_rank.php");
	require ("includes/first_draw_unix.php");
	require ("includes/last_draw_unix.php");
	require ("includes/next_draw.php");
	require_once ("$game_includes/calc_devsq.php");
	require ("includes/calculate_50_50.php");
	require ("includes/display.php");
	require ("includes/display4.php");
	require ("includes/display4_summary.php");
	require ("includes/display4_all.php");

	date_default_timezone_set('America/New_York');

	require_once ("includes/hml_switch.incl");	

	require ("includes/dateDiffInDays.php");
	require ("includes/unix.incl");

	// ----------------------------------------------------------------------------------
	function print_limits_by_sumeo_date($date)
	{
		global $draw_table_name, $balls, $balls_drawn, $draw_prefix, $col1_select, $hml, $range_low, $range_high; 

		require ("includes/mysqli.php");
		
		require ("includes/calculate_limits_by_sumeo_date.incl");

		require ("includes/calculate_limits_by_sumeo_method2_date.incl");

		print "<h3>Table <font color=\"#ff0000\">{$draw_prefix}_combo_by_sum</font> Updated!</h3>";
	}
	
	$debug = 0;

	//start HTML page
	print("<HTML>\n");
	print("<HEAD>\n");
	print("<TITLE>Lotto Verify 4</TITLE>\n");
	print("</HEAD>\n");
	
	print("<BODY bgcolor=\"#FFFFFF\" text=\"#000000\">\n");

	print "<center><h1>Lotto Verify 4</h1></center>";

	$limit = 30;

	flush();
	ob_flush();

	#3require ("includes/verify_sumeo_c1.incl");

	#require ("includes/verify_sumeo_c1_m2.incl");

	#die();

	$num_array = array_fill (0,2000,0);
	$num_array_count = array_fill (0,2000,$num_array); ### 220312
	
	#echo "lot_display(1470)<br>";
	lot_display($limit); 

	#die(); 
	
	for ($combo = 1; $combo <= 5; $combo++)
	{
		require ("includes/verify_sum_4.incl");
	}

	#require ("includes/verify_col1_1_9_4.incl");

	require ("includes/verify_col1_4.incl");

	#require ("includes/verify_col2_4.incl");
	
	#require ("includes/verify_col3_4.incl");

	#require ("includes/verify_col4_4.incl");

	#require ("includes/verify_col5_4.incl");
	
	#require ("includes/verify_even_odd_4.incl");
	
	require ("includes/verify_seq2_4.incl");

	require ("includes/verify_seq3_4.incl");

	require ("includes/verify_mod_4.incl");

	require ("includes/verify_modx_4.incl");
	
	#require ("includes/verify_sumeo_c1_4.incl");

	#require ("includes/verify_sumeo_c1_m2_4.incl");

	require ("includes/verify_dup_4.incl");
	
	require ("includes/verify_sumeo_4.incl");

	#require ("includes/verify_combin_4.incl");	### 220416

	require ("includes/verify_horizontal_4.incl");

	print("</body>");
	print("</html>");	

	die();
	
	print("</TABLE>\n");

	print("</body>");
	print("</html>");
?>