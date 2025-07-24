<?php
	header('Expires: Sun, 01 Jan 2014 00:00:00 GMT');
	header('Cache-Control: no-store, no-cache, must-revalidate');
	header('Cache-Control: post-check=0, pre-check=0', FALSE);
	header('Pragma: no-cache');
	
	$game = 7; // Powerball

	$hml = 0;
	#$hml = 1;		#= high
	#$hml = 2;		#= medium
	#$hml = 3;		#= low
	#$hml = 4;		#= min
	#$hml = 5;		#= max
	#$hml = 110;	
	
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
	require ("includes_mm/display.php");
	require ("includes/display4.php");
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
	print("<TITLE>Lotto Verify</TITLE>\n");
	print("</HEAD>\n");
	
	print("<BODY bgcolor=\"#FFFFFF\" text=\"#000000\">\n");

	print "<center><h1>Lotto Verify</h1></center>";

	$limit = 30;

	flush();
	ob_flush();

	#3require ("includes/verify_sumeo_c1.incl");

	#require ("includes/verify_sumeo_c1_m2.incl");

	#die();
	
	#echo "lot_display(1470)<br>";
	lot_display($limit);
	
	require ("includes/verify_sum.incl");

	require ("includes/verify_col1_1_9.incl");
	/*
	require ("includes/verify_col1.incl");

	require ("includes/verify_col2.incl");

	require ("includes/verify_col3.incl");

	require ("includes/verify_col4.incl");

	require ("includes/verify_col5.incl");
	*/
	require ("includes/verify_even_odd.incl");

	require ("includes/verify_seq2.incl");

	require ("includes/verify_seq3.incl");

	require ("includes/verify_mod.incl");

	require ("includes/verify_modx.incl");
	
	#require ("includes/verify_sumeo_c1.incl");

	#require ("includes/verify_sumeo_c1_m2.incl");

	require ("includes/verify_dup.incl");

	print("</body>");
	print("</html>");	

	die();
	
	print("</TABLE>\n");

	print("</body>");
	print("</html>");
?>