<?php

	// Game ----------------------------- Game
	$game = 1; // Georgia Fantasy 5
	#$game = 2; // Mega Millions
	//$game = 3; // Georgia 5
	#$game = 4; // Jumbo
	//$game = 5; // Florida Mega Money
	#$game = 6; // Florida Lotto
	#$game = 7; // Powerball
	// Game ----------------------------- Game

	$debug = 0;

	if ($debug)
	{
		ini_set('display_errors', '1');
		ini_set('display_startup_errors', '1');
		error_reporting(E_ALL);
	}

	set_time_limit(0);

	$hml = 0;
	#$hml = 1;		#= high
	#$hml = 2;		#= medium
	#$hml = 3;		#= low
	#$hml = 4;		#= min
	#$hml = 5;		#= max
	#$hml = 100;

	# add EO50 limit

	$col1_select = 0;
	
	require ("includes/games_switch.incl");
	require ("includes/even_odd.php");
	require ("includes/last_draws.php");
	require ("includes/calculate_rank.php");
	require ("includes/look_up_rank.php"); 
	require ("includes/build_rank_table.php");
	require ("includes/test_column_lookup.php");
	require ("includes/calculate_rank_mb.php");
	require ("includes/next_draw.php");
	require ("includes/number_due.php");
	require ("includes/first_draw_unix.php");
	require ("includes/last_draw_unix.php"); 
	require ("includes/count_2_seq.php");
	require ("includes/count_3_seq.php");
	require ("includes/mysqli.php"); 
	require ("$game_includes/combin.incl");
	require ("includes/dateDiffInDays.php");
	require ("$game_includes/print_table_analyze2.php");
	require ("includes/analyze2_summary.php");
	#require ("includes/dateDiffInDays.php");

	require ("includes/limit_functions.incl");

	date_default_timezone_set('America/New_York');

	echo "<b>col1_select = $col1_select in lot_display.php</b><p>";

	require_once ("includes/hml_switch.incl");	

	$debug = 0;

	// ----------------------------------------------------------------------------------
	function print_sum_grid2($combin)
	{
		global $draw_table_name, $balls, $balls_drawn, $draw_prefix, $col1_select, $hml, $range_low, $range_high; 

		#echo "enter print_sum_grid3<br>";	

		require ("includes/mysqli.php");
	
		require ("includes/calculate_sum_count2.incl");

		require ("includes/print_sum_table2.incl");

		print "<h3>Table <font color=\"#ff0000\">{$draw_prefix}sum $combin</font> Updated!</h3>";

		#echo "leaving print_sum_grid3<br>";
	}

	// ----------------------------------------------------------------------------------
	function print_sum_grid_sum2($combin)
	{
		global $draw_table_name, $balls, $balls_drawn, $draw_prefix, $col1_select, $hml, $range_low, $range_high; 

		require ("includes/mysqli.php");
	
		require ("includes/calculate_sum_count_sum2.incl");

		require ("includes/print_sum_table_sum2.incl");

		require ("includes/print_sum_table_sum_top25_2.incl");

		print "<h3>Table <font color=\"#ff0000\">{$draw_prefix}sum $combin</font> Updated!</h3>";
	}

	// ----------------------------------------------------------------------------------
	function print_sum_table_sum2_summary()
	{
		global $draw_table_name, $balls, $balls_drawn, $draw_prefix, $col1_select, $hml, $range_low, $range_high; 

		require ("includes/mysqli.php");
	
		require ("includes/calculate_sum_table_sum2_summary.incl");

		#require ("includes/print_sum_table_sum2.incl");

		require ("includes/print_sum_table_sum2_summary.incl");

		print "<h3>Table <font color=\"#ff0000\">{$draw_prefix}sum $combin</font> Updated!</h3>";
	}

	// ----------------------------------------------------------------------------------
	function print_combo_by_sum2()
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
		$query4 .= "d3 tinyint(3) unsigned NOT NULL default '0',";
		$query4 .= "d4 tinyint(3) unsigned NOT NULL default '0',";
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
		$query4 .= "percent_y1 float(4,1) unsigned NOT NULL,";
		$query4 .= "percent_y8 float(4,1) unsigned NOT NULL,";
		$query4 .= "percent_wa float(4,1) unsigned NOT NULL,";
		$query4 .= "PRIMARY KEY  (`id`),";
		$query4 .= "UNIQUE KEY `id_2` (`id`),";
		$query4 .= "KEY `id` (`id`)";
		$query4 .= ") ENGINE=InnoDB DEFAULT CHARSET=latin1;";

		#print "$query4<p>";

		$mysqli_result4 = mysqli_query($mysqli_link, $query4) or die (mysqli_error($mysqli_link));
	
		for ($c = 1; $c <= 10; $c++)
		{
			require ("includes/calculate_combo3_by_sum.incl");
		}

		#require ("includes/print_combo_by_sum.incl");

		print "<h3>Table <font color=\"#ff0000\">{$draw_prefix}_combo3_by_sum</font> Updated!</h3>";
	}

	// ----------------------------------------------------------------------------------
	function print_limits_by_sumeo_2($combin)
	{
		global $draw_table_name, $balls, $balls_drawn, $draw_prefix, $col1_select, $hml, $range_low, $range_high; 

		require ("includes/mysqli.php");
		
		require ("includes/calculate_limits_by_sumeo_2.incl");

		print "<h3>Table <font color=\"#ff0000\">{$draw_prefix}_combo_by_sum</font> Updated!</h3>";
	}

	// ----------------------------------------------------------------------------------
	function print_sumeo_drange2_2($combin)
	{
		global $draw_table_name, $balls, $balls_drawn, $draw_prefix, $col1_select, $hml, $range_low, $range_high; 

		#echo "enter print_sumeo_drange2_3<br>";	

		require ("includes/mysqli.php");
	
		require ("includes/calculate_sumeo_drange2_2.incl");

		require ("includes/print_sumeo_table_drange2_2.incl");

		require ("includes/print_sumeo_table_drange2_top25_2.incl");

		print "<h3>Table <font color=\"#ff0000\">{$draw_prefix}sumeo</font> Updated!</h3>";

		#echo "leave print_sumeo_drange2_3<br>";	
	}

	// ----------------------------------------------------------------------------------
	function print_sumeo_drange3_2($combin)
	{
		global $draw_table_name, $balls, $balls_drawn, $draw_prefix, $col1_select, $hml, $range_low, $range_high; 

		require ("includes/mysqli.php");
	
		require ("includes/calculate_sumeo_drange3_2.incl");

		require ("includes/print_sumeo_table_drange3_2.incl");

		require ("includes/print_sumeo_table_drange3_top25_2.incl");

		print "<h3>Table <font color=\"#ff0000\">{$draw_prefix}sumeo</font> Updated!</h3>";
	}

	// ----------------------------------------------------------------------------------
	function print_sumeo_drange4_2($combin)
	{
		global $draw_table_name, $balls, $balls_drawn, $draw_prefix, $col1_select, $hml, $range_low, $range_high; 

		require ("includes/mysqli.php");
	
		require ("includes/calculate_sumeo_drange4_2.incl");

		require ("includes/print_sumeo_table_drange4_2.incl");

		require ("includes/print_sumeo_table_drange4_top25_2.incl");

		print "<h3>Table <font color=\"#ff0000\">{$draw_prefix}sumeo</font> Updated!</h3>";
	}

	// ----------------------------------------------------------------------------------
	function print_sumeo_drange5_2($combin)
	{
		global $draw_table_name, $balls, $balls_drawn, $draw_prefix, $col1_select, $hml, $range_low, $range_high; 

		require ("includes/mysqli.php");
	
		require ("includes/calculate_sumeo_drange5_2.incl");

		require ("includes/print_sumeo_table_drange5_2.incl");

		require ("includes/print_sumeo_table_drange5_top25_2.incl");

		print "<h3>Table <font color=\"#ff0000\">{$draw_prefix}sum</font> Updated!</h3>";
	}

	// ----------------------------------------------------------------------------------
	function print_sumeo_drange6_2($combin)
	{
		global $draw_table_name, $balls, $balls_drawn, $draw_prefix, $col1_select, $hml, $range_low, $range_high; 

		require ("includes/mysqli.php");
	
		require ("includes/calculate_sumeo_drange6_2.incl");

		require ("includes/print_sumeo_table_drange6_2.incl");

		require ("includes/print_sumeo_table_drange6_top25_2.incl");

		print "<h3>Table <font color=\"#ff0000\">{$draw_prefix}sum</font> Updated!</h3>";
	}

	function print_sumeo_drange7_2($combin)
	{
		global $draw_table_name, $balls, $balls_drawn, $draw_prefix, $col1_select, $hml, $range_low, $range_high; 

		require ("includes/mysqli.php");
	
		require ("includes/calculate_sumeo_drange7_2.incl");

		require ("includes/print_sumeo_table_drange7_2.incl");

		require ("includes/print_sumeo_table_drange7_top25_2.incl");

		print "<h3>Table <font color=\"#ff0000\">{$draw_prefix}sum</font> Updated!</h3>";
	}

	// ----------------------------------------------------------------------------------
	function print_sumeo_drange8_2($combin)
	{
		global $draw_table_name, $balls, $balls_drawn, $draw_prefix, $col1_select, $hml, $range_low, $range_high; 

		require ("includes/mysqli.php");
	
		require ("includes/calculate_sumeo_drange8_2.incl");

		require ("includes/print_sumeo_table_drange8_2.incl");

		require ("includes/print_sumeo_table_drange8_top25_2.incl");

		print "<h3>Table <font color=\"#ff0000\">{$draw_prefix}sum</font> Updated!</h3>";
	}

	// ------------------------------------------------------------------------
	//start HTML page
	print("<HTML>\n");
	print("<HEAD>\n");
	print("<TITLE>$game_name analyze2</TITLE>\n");
	print("</HEAD>\n");
	
	print("<BODY>\n");
	print("<H1>$game_name analyze2</H1>\n");
	
	$curr_date = date("Ymd");
	$next_draw_Ymd = findNextDrawDate($game);
	
	print_table2($combin=1,$limit=30);

	print_sum_grid2($combin=1);

	print_sum_grid_sum2($combin=1);

	print_sumeo_drange2_2($combin=1);

	print_table2($combin=2,$limit=30);

	print_sum_grid2($combin=2);

	print_sum_grid_sum2($combin=2);

	print_table2($combin=3,$limit=30);

	print_sum_grid2($combin=3);

	print_sum_grid_sum2($combin=3);

	print_table2($combin=4,$limit=30);

	print_sum_grid2($combin=4);

	print_sum_grid_sum2($combin=4);

	print_table2($combin=5,$limit=30);

	print_sum_grid2($combin=5);

	print_sum_grid_sum2($combin=5);
	
	#echo "print_sum_grid_sum2<br>";

	print_table2($combin=6,$limit=30);

	print_sum_grid2($combin=6);

	print_sum_grid_sum2($combin=6);
	
	#echo "print_sum_grid_sum2<br>";

	print_table2($combin=7,$limit=30);

	print_sum_grid2($combin=7);

	print_sum_grid_sum2($combin=7);
	
	#echo "print_sum_grid_sum2<br>";

	print_table2($combin=8,$limit=30);

	print_sum_grid2($combin=8);

	print_sum_grid_sum2($combin=8);
	
	#echo "print_sum_grid_sum2<br>";

	print_table2($combin=9,$limit=30);

	print_sum_grid2($combin=9);

	print_sum_grid_sum2($combin=9);
	
	#echo "print_sum_grid_sum2<br>";

	print_table2($combin=10,$limit=30);

	print_sum_grid2($combin=10);

	print_sum_grid_sum2($combin=10);
	
	#echo "print_sum_grid_sum2<br>";

	lot_analyze2_summary ($dateDiff);

	echo "<h2>Add combo limit by eox</h2>";

	#print_combo_by_sum(); #190613

	#print_limits_by_sumeo_2($combin=1);

	print "<p><a href=\"lot_test.php\" target=\"_blank\">Open lot_test.php</a></p>"; 

	//close page
	print("</BODY>\n");
	print("</HTML>\n");

?>
