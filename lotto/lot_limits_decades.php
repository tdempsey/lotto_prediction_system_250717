<?php

	// Game ----------------------------- Game
	$game = 1; // Georgia Fantasy 5
	#$game = 2; // Mega Millions
	//$game = 3; // Georgia Lotto South
	#$game = 4; // Florida Fantasy 5
	//$game = 5; // Florida Mega Money
	#$game = 6; // Florida Lotto
	#$game = 7; // Powerball
	// Game ----------------------------- Game
	
	require ("includes/games_switch.incl");

	//require ("includes/mysqli.php");
	require ("includes/db.class");
	require ("includes/even_odd.php");
	require ("includes/calculate_50_50.php");
	require ("includes/build_rank_table.php");
	require ("includes/calculate_draw.php"); 
	require ("includes/calculate_rank.php");
	require ("includes/first_draw_unix.php");
	require ("includes/last_draw_unix.php"); 
	require ("includes/next_draw.php");
	require ("$game_includes/combin.incl");

	date_default_timezone_set('America/New_York');
	
	$debug = 0;

	require ("includes/limit_functions.incl");

	//start HTML page
	print("<HTML>\n");
	print("<HEAD>\n");
	print("<TITLE>$game_name Limits - $game_name</TITLE>\n");
	print("</HEAD>\n");
	
	print("<BODY>\n");

	echo "<h1>$game_name Limits</h1>";

	$next_draw_date = findNextDrawDateDash($game);
	#$next_draw_date = '2013-10-09';

	echo "Next draw = $next_draw_date<p>";

	for ($hml = 50; $hml <= 200; $hml = $hml + 10)
	{
		require ("includes/hml_switch.incl");

		lot_filter_limit_sum ($next_draw_date);
		lot_filter_limit_balls ($next_draw_date);
		lot_filter_limit_even ($next_draw_date);
		lot_filter_limit_odd ($next_draw_date);
		lot_filter_limit_d501 ($next_draw_date);
		lot_filter_limit_d502 ($next_draw_date);
		#lot_filter_limit_mod ($next_draw_date);
		#lot_filter_limit_modx ($next_draw_date);
		lot_filter_limit_draw ($next_draw_date);
		lot_filter_limit_rank ($next_draw_date);
		lot_filter_limit_dup ($next_draw_date); 
		lot_filter_limit_combo ($next_draw_date);
		#lot_filter_limit_combo_count ($next_draw_date,2);
		#lot_filter_limit_combo_count ($next_draw_date,3);
		#lot_filter_limit_combo_count ($next_draw_date,4);
		lot_filter_limit_average ($next_draw_date);
		lot_filter_limit_median ($next_draw_date);
		lot_filter_limit_harmean ($next_draw_date);
		#lot_filter_limit_geomean ($next_draw_date);
		lot_filter_limit_quart1 ($next_draw_date);
		lot_filter_limit_quart2 ($next_draw_date);
		lot_filter_limit_quart3 ($next_draw_date);
		lot_filter_limit_stdev ($next_draw_date);
		lot_filter_limit_variance ($next_draw_date);
		lot_filter_limit_avedev ($next_draw_date);
		lot_filter_limit_kurt ($next_draw_date);
		lot_filter_limit_skew ($next_draw_date); 
		lot_filter_limit_devsq ($next_draw_date); 
	}
	
	print("</BODY>\n");
?>
