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

	$combin = $_GET["combin"];
	
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
	require ("includes/display.php");
	#require ("includes_ga_f5/split_draws_2.php");
	require ("includes_ga_f5/limits_sumeo_5.php");
	require ("includes_ga_f5/print_table_sumeo.php");

	date_default_timezone_set('America/New_York');

	require_once ("includes/hml_switch.incl");	
	
	$debug = 0;

	//start HTML page
	print("<HTML>\n");
	print("<HEAD>\n");
	print("<TITLE>$game_name SumEO Limits - $game_name</TITLE>\n");
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

	$query3 = "SELECT * FROM $draw_prefix";
	$query3 = "sum_count_sum WHERE percent_wa >= 0.1 ORDER BY percent_wa DESC, percent_y4 DESC, percent_y1 DESC LIMIT 30";

	print "$query3<br>";

	$mysqli_result3 = mysqli_query($mysqli_link, $query3) or die (mysqli_error($mysqli_link));

	while($row3 = mysqli_fetch_array($mysqli_result3))
	{
		echo "<h2>*** SumEO = $row3[numx],$row3[even],$row3[odd]</h2>";
		
		limits_sumeo_5($row3[numx],$row3[even],$row3[odd]); #top 30 sumeo

		print_table_sumeo($row3[numx],$row3[even],$row3[odd]); #top 30 sumeo

		echo "<h2>*** SumEO_m2 = $row3[numx],$row3[even],$row3[odd]</h2>";

		limits_sumeo_5_m2($row3[numx],$row3[even],$row3[odd]); #top 30 sumeo

		print_table_sumeo($row3[numx],$row3[even],$row3[odd]); #top 30 sumeo

		#print_table_sumeo_m2($row3[numx],$row3[even],$row3[odd]); #top 30 sumeo

		#die();
	}

	die();
	
	print("</BODY>\n");

// ----------------------------------------------------------------------------------
	function print_limits_by_sumeo_less_date($draw_date,$sum,$even,$odd)
	{
		global $draw_table_name, $balls, $balls_drawn, $draw_prefix, $col1_select, $hml, $range_low, $range_high; 

		require ("includes/mysqli.php");
		
		require ("includes/calculate_limits_by_sumeo_less_date.incl");

		print "<h3>Table <font color=\"#ff0000\">{$draw_prefix}_combo_by_sum_less_date</font> Updated!</h3>";
	}
?>
