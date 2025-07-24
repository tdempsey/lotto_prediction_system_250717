<?php

	// Game ----------------------------- Game
	$game = 1; // Georgia Fantasy 5
	#$game = 2; // Mega Millions
	//$game = 3; // Georgia 5
	#$game = 4; // Jumbo
	#$game = 5; // Florida Fantasy 5
	#$game = 6; // Florida Lotto
	#$game = 7; // Powerball
	// Game ----------------------------- Game

	$hml = 0;
	#$hml = 1;		#= high
	#$hml = 2;		#= medium
	#$hml = 3;		#= low
	#$hml = 4;		#= min
	#$hml = 5;		#= max
	#$hml = 100;

	set_time_limit(0);

	#error_reporting(0);

	$debug = 1;

	if ($debug)
	{
		ini_set('display_errors', '1');
		ini_set('display_startup_errors', '1');
		error_reporting(E_ALL);
	}

	$col1_select = 0;
	
	require_once ("includes/games_switch.incl");
	require_once ("includes/even_odd.php");
	require_once ("includes/last_draws.php");
	require_once ("includes/calculate_rank.php");
	require_once ("includes/look_up_rank.php"); 
	require_once ("includes/build_rank_table.php");
	require_once ("includes/test_column_lookup.php");
	#require_once ("includes/calculate_rank_mb.php");
	require_once ("includes/next_draw.php");
	require_once ("includes/number_due.php");
	require_once ("includes/first_draw_unix.php");
	require_once ("includes/last_draw_unix.php"); 
	require_once ("includes/count_2_seq.php");
	require_once ("includes/count_3_seq.php");
	require_once ("includes/mysqli.php"); 
	require_once ("$game_includes/combin.incl");
	require_once ("includes/dateDiffInDays.php");
	require_once ("includes/print_sumeo_drange.incl");	###220413
	
	#require_once ("includes/log_text.incl");

	require_once ("includes/limit_functions.incl");

	date_default_timezone_set('America/New_York');

	#echo "<b>col1_select = $col1_select in lot_display.php</b><p>";

	require_once ("includes/hml_switch.incl");	

	$debug = 0;
	
	require ("includes/print_table_analyze.incl");	### 240723 ###

	// ----------------------------------------------------------------------------------
	function print_draw_summary()
	{
		global $draw_table_name, $balls, $balls_drawn, $draw_prefix, $col1_select, $hml, $range_low, $range_high; 

		require ("includes/mysqli.php");

		require ("includes/unix.incl");
	
		require ("includes/calculate_draw_summary.incl");

		require ("includes/print_draw_summary.incl");

		print "<h3>Table <font color=\"#ff0000\">{$draw_prefix}sum</font> Updated!</h3>";
	}

	// ----------------------------------------------------------------------------------
	function print_sum_grid()
	{
		global $draw_table_name, $balls, $balls_drawn, $draw_prefix, $col1_select, $hml, $range_low, $range_high; 

		require ("includes/mysqli.php");
	
		require ("includes/calculate_sum_count.incl");

		require ("includes/print_sum_table.incl");

		#require ("includes/print_sum_table_even.incl");

		#require ("includes/print_sum_table_odd.incl");

		print "<h3>Table <font color=\"#ff0000\">{$draw_prefix}sum</font> Updated!</h3>";
	}
	/*
	// ----------------------------------------------------------------------------------
	function print_sum_grid_1()
	{
		global $draw_table_name, $balls, $balls_drawn, $draw_prefix, $col1_select, $hml, $range_low, $range_high; 

		require ("includes/mysqli.php");
	
		require ("includes/calculate_sum_count_1.incl");

		require ("includes/print_sum_table_1.incl");

		print "<h3>Table <font color=\"#ff0000\">{$draw_prefix}sum_1</font> Updated!</h3>";
	}

	// ----------------------------------------------------------------------------------
	function print_sum_grid_2()
	{
		global $draw_table_name, $balls, $balls_drawn, $draw_prefix, $col1_select, $hml, $range_low, $range_high; 

		require ("includes/mysqli.php");
	
		require ("includes/calculate_sum_count_2.incl");

		require ("includes/print_sum_table_2.incl");

		print "<h3>Table <font color=\"#ff0000\">{$draw_prefix}sum_2</font> Updated!</h3>";
	}

	// ----------------------------------------------------------------------------------
	function print_sum_grid_3()
	{
		global $draw_table_name, $balls, $balls_drawn, $draw_prefix, $col1_select, $hml, $range_low, $range_high; 

		require ("includes/mysqli.php");
	
		require ("includes/calculate_sum_count_3.incl");

		require ("includes/print_sum_table_3.incl");

		print "<h3>Table <font color=\"#ff0000\">{$draw_prefix}sum_3</font> Updated!</h3>";
	}

	// ----------------------------------------------------------------------------------
	function print_sum_grid_5()
	{
		global $draw_table_name, $balls, $balls_drawn, $draw_prefix, $col1_select, $hml, $range_low, $range_high; 

		require ("includes/mysqli.php");
	
		require ("includes/calculate_sum_count_5.incl"); ### zero separate

		require ("includes/print_sum_table_5.incl");

		print "<h3>Table <font color=\"#ff0000\">{$draw_prefix}sum_5</font> Updated!</h3>";
	}

	// ----------------------------------------------------------------------------------
	function print_sum_grid_sumeo()
	{
		global $draw_table_name, $balls, $balls_drawn, $draw_prefix, $col1_select, $hml, $range_low, $range_high; 

		require ("includes/mysqli.php");
	
		require ("includes/calculate_sum_count_5.incl"); ### zero separate

		require ("includes/print_sum_table_5.incl");

		print "<h3>Table <font color=\"#ff0000\">{$draw_prefix}sum_5</font> Updated!</h3>";
	}
	*/
	// ----------------------------------------------------------------------------------
	function print_sum_grid_sum()
	{
		global $draw_table_name, $balls, $balls_drawn, $draw_prefix, $col1_select, $hml, $range_low, $range_high; 

		require ("includes/mysqli.php");
	
		echo "calculate_sum_count_sum.incl<br>";
		
		require ("includes/calculate_sum_count_sum.incl");
		
		echo "print_sum_table_sum.incl<br>";

		require ("includes/print_sum_table_sum.incl");
		
		echo "print_sum_table_sum_top25.incl<br>";

		require ("includes/print_sum_table_sum_top25.incl");

		print "<h3>Table <font color=\"#ff0000\">{$draw_prefix}sum</font> Updated!</h3>";
		
		echo " print_sum_table_sum_23_32.incl<br>";

		require ("includes/print_sum_table_sum_23_32.incl");
		
		echo "print_sum_table_sum_top25_23_32.incl<br>";

		require ("includes/print_sum_table_sum_top25_23_32.incl");
	}

	// ----------------------------------------------------------------------------------
	function print_sum_drange2()
	{
		global $draw_table_name, $balls, $balls_drawn, $draw_prefix, $col1_select, $hml, $range_low, $range_high; 

		require ("includes/mysqli.php");
	
		require ("includes/calculate_sum_drange2.incl");

		require ("includes/print_sum_table_drange2.incl");

		require ("includes/print_sum_table_drange2_top25.incl");

		print "<h3>Table <font color=\"#ff0000\">{$draw_prefix}sum</font> Updated!</h3>";
	}

	// ----------------------------------------------------------------------------------
	function print_sum_drange3()
	{
		global $draw_table_name, $balls, $balls_drawn, $draw_prefix, $col1_select, $hml, $range_low, $range_high; 

		require ("includes/mysqli.php");
	
		require ("includes/calculate_sum_drange3.incl");

		require ("includes/print_sum_table_drange3.incl");

		require ("includes/print_sum_table_drange3_top25.incl");

		print "<h3>Table <font color=\"#ff0000\">{$draw_prefix}sum</font> Updated!</h3>";
	}

	// ----------------------------------------------------------------------------------
	function print_sum_grid_colx()
	{
		global $draw_table_name, $balls, $balls_drawn, $draw_prefix, $col1_select, $hml, $range_low, $range_high; 

		require ("includes/mysqli.php");
	
		require ("includes/calculate_sum_count_colx.incl");

		require ("includes/print_sum_table_colx.incl");

		print "<h3>Table <font color=\"#ff0000\">{$draw_prefix}sum</font> Updated!</h3>";
	}

	// ----------------------------------------------------------------------------------
	function print_sum_grid_sum_colx() ### not used
	{
		global $draw_table_name, $balls, $balls_drawn, $draw_prefix, $col1_select, $hml, $range_low, $range_high; 

		require ("includes/mysqli.php");
	
		require ("includes/calculate_sum_count_sum_colx.incl");

		require ("includes/print_sum_table_sum_colx.incl");

		require ("includes/print_sum_table_sum_top25_colx.incl");

		print "<h3>Table <font color=\"#ff0000\">{$draw_prefix}sum</font> Updated!</h3>";
	}

	// ----------------------------------------------------------------------------------
	function print_combo_by_sum()
	{
		global $draw_table_name, $balls, $balls_drawn, $draw_prefix, $col1_select, $hml, $range_low, $range_high; 

		require ("includes/mysqli.php");

		$query4 = "DROP TABLE IF EXISTS $draw_prefix";
		$query4 .= "combo_by_sum ";
		
		#print "$query4<p>";

		$mysqli_result4 = mysqli_query($mysqli_link, $query4) or die (mysqli_error($mysqli_link));

		$query4 = "CREATE TABLE $draw_prefix";
		$query4 .= "combo_by_sum (";
		$query4 .= " id int(10) unsigned NOT NULL auto_increment, ";
		$query4 .= "sum tinyint(3) unsigned NOT NULL default '0',";
		$query4 .= "combo tinyint(3) unsigned NOT NULL default '0',";
		$query4 .= "d1 tinyint(3) unsigned NOT NULL default '0',";
		$query4 .= "d2 tinyint(3) unsigned NOT NULL default '0',";
		$query4 .= "day1 tinyint(3) unsigned NOT NULL default '0', ";
		$query4 .= "week1 tinyint(3) unsigned NOT NULL default '0', ";
		$query4 .= "week2 tinyint(3) unsigned NOT NULL default '0', ";
		$query4 .= "month1 tinyint(3) unsigned NOT NULL default '0', ";
		$query4 .= "month3 tinyint(3) unsigned NOT NULL default '0', ";
		$query4 .= "month6 tinyint(3) unsigned NOT NULL default '0', ";
		$query4 .= "year1 int(5) unsigned NOT NULL default '0', ";
		$query4 .= "year2 int(5) unsigned NOT NULL default '0', ";
		$query4 .= "year3 int(5) unsigned NOT NULL default '0', ";
		$query4 .= "year4 int(5) unsigned NOT NULL default '0', ";
		$query4 .= "year5 int(5) unsigned NOT NULL default '0', ";
		$query4 .= "year6 int(5) unsigned NOT NULL default '0', ";
		$query4 .= "year7 int(5) unsigned NOT NULL default '0', ";
		$query4 .= "year8 int(5) unsigned NOT NULL default '0', ";
		$query4 .= "year9 int(5) unsigned NOT NULL default '0', ";
		$query4 .= "year10 int(5) unsigned NOT NULL default '0', ";
		$query4 .= "count int(5) unsigned NOT NULL,";
		$query4 .= "percent_y1 float(5,3) unsigned NOT NULL,";
		$query4 .= "percent_y8 float(5,3) unsigned NOT NULL,";
		$query4 .= "percent_wa float(5,3) unsigned NOT NULL,";
		$query4 .= "PRIMARY KEY  (`id`),";
		$query4 .= "UNIQUE KEY `id_2` (`id`),";
		$query4 .= "KEY `id` (`id`)";
		$query4 .= ") ENGINE=InnoDB DEFAULT CHARSET=latin1;";

		#print "$query4<p>";

		$mysqli_result4 = mysqli_query($mysqli_link, $query4) or die (mysqli_error($mysqli_link));
	
		for ($c = 1; $c <= 10; $c++)
		{
			require ("includes/calculate_combo2_by_sum.incl");
		}

		#require ("includes/print_combo_by_sum.incl");

		print "<h3>Table <font color=\"#ff0000\">{$draw_prefix}_combo_by_sum</font> Updated!</h3>";
	}

	// ----------------------------------------------------------------------------------
	function print_limits_by_sumeo()
	{
		global $draw_table_name, $balls, $balls_drawn, $draw_prefix, $col1_select, $hml, $range_low, $range_high; 

		require ("includes/mysqli.php");
		
		require ("includes/calculate_limits_by_sumeo.incl");

		require ("includes/calculate_limits_by_sumeo_method2.incl");

		print "<h3>Table <font color=\"#ff0000\">{$draw_prefix}_combo_by_sum</font> Updated!</h3>";
	}
	
	// ----------------------------------------------------------------------------------------------------------------
	// calculate the weighted average of the Number Draws
	/*
	<?php
		// Given values array based on 'x' as 1 and '-' as 0
		$values = [1, 0, 0, 0, 0, 1, 0, 1, 0, 1, 0, 0, 1, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 1, 1, 1, 0, 1, 1];

		// Assign weights descending from 30 to 1
		$weights = range(30, 1);

		// Calculate weighted sum
		$weightedSum = 0;
		$totalWeight = array_sum($weights);

		foreach ($values as $position => $value) {
			// The weight for each position is from the descending weights array
			$weight = $weights[$position];
			$weightedSum += $value * $weight;
		}

		// Calculate weighted average
		$weightedAverage = $weightedSum / $totalWeight;

		// Display the result
		echo "The weighted average is: " . round($weightedAverage, 4);
	?>
*/

	// ------------------------------------------------------------------------
	//start HTML page
	#log_text("$game_name Analyle Start");
	print("<HTML>\n");
	print("<HEAD>\n");
	print("<TITLE>$game_name Analyze</TITLE>\n");
	print("</HEAD>\n");
	
	print("<BODY>\n");
	print("<H1>$game_name Analyze</H1>\n");
	
	$curr_date = date("Ymd");
	$next_draw_Ymd = findNextDrawDate($game);

	flush();
	ob_flush();

	#print_sum_grid();

	#print_sum_grid_sum();

	#die();
	
	#print_sumeo_drange2();

	/*
	print("<a href="http://localhost/lotto/lot_analyze.php#analyze01">Draw Count Table</a></br>\n");
	print("<a href="http://localhost/lotto/lot_analyze.php#analyze02">Sum Rank</a></br>\n");
	print("<a href="http://localhost/lotto/lot_analyze.php#analyze03">Sum Table Sum - Combin 5</a></br>\n");
	print("<a href="http://localhost/lotto/lot_analyze.php#analyze04">Sum Table Sum - Top 100 - Combin 5</a></br>\n");
	print("<a href="http://localhost/lotto/lot_analyze.php#analyze05">Sumeo Table Drange Summary - Combin 5</a></br>\n");
	print("<a href="http://localhost/lotto/lot_analyze.php#analyze06">Sumeo Table Drange Summary - Combin 5 - Top 100</a></br>\n");
	*/
	
	echo "entering print_table_analyze(31)<br>";
	print_table_analyze(31);

	echo "entering print_draw_summary<br>";
	print_draw_summary();

	#die();

	#print_limits_by_sumeo(); #201005 fix

	#print_table_analyze(14);

	#print_table_analyze(7);

	echo "entering print_sum_grid<br>";
	print_sum_grid();

	echo "entering print_sum_grid_sum<br>";
	print_sum_grid_sum();

	#print_sum_grid_1();

	#print_sum_grid_2();

	#print_sum_grid_3();

	#print_sum_grid_5();

	#print_limits_by_sumeo(); #201005 fix

	#die();
	/*
	print_sumeo_drange2();

	print_sumeo_drange3();

	print_sumeo_drange4();

	print_sumeo_drange5();

	print_sumeo_drange6();

	print_sumeo_drange7();

	print_sumeo_drange8();

	#print_sumeo_drange9();

	#print_sumeo_drange10();
	*/
	print_sumeo_drange_summary();

	#print_sumeo_drange_summary_limited();

	die();

	#print_sumeo_drange3();

	#print_sumeo_drange4();

	#print_sumeo_drange5();

	print_sumeo_drange6();

	print_sumeo_drange7();

	#print_sumeo_drange8();

	print_sumeo_drange_summary();

	#die();
	
	flush();
	ob_flush();
	
	print_table_analyze(31);

	print_table_analyze(14);

	print_table_analyze(7);

	print_sum_grid();

	print_sum_grid_sum();

	print_limits_by_sumeo();

	print_sum_drange2();
	
	print_sum_drange3();

	print_sum_drange4();

	#print_sum_drange5();

	#print_sum_drange6();

	die();

	print_sum_drange7();

	print_sum_drange8();

	print_sum_drange9();

	print_sum_drange10();

	#echo "<h2>Add combo limit by eox</h2>";

	#print_combo_by_sum(); #190613
	
	print_limits_by_sumeo();
	
	print "<p><a href=\"lot_test.php\" target=\"_blank\">Open lot_test.php</a></p>"; 

	//close page
	print("</BODY>\n");
	print("</HTML>\n");

?>
