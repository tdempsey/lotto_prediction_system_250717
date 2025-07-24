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

	require ("includes/mysqli.php");
	
	$debug = 0;

	$print_tables = 0;

	$limit = 5000;
	
	//start HTML page
	print("<HTML>\n");
	print("<HEAD>\n");
	print("<TITLE>$game_name Pair/Trip/Quad/Five Report - $game_name</TITLE>\n");
	print("</HEAD>\n");
	
	print("<BODY>\n");

	$dcount = 1;
	$num_array = array_fill (0,60,0);
	$num_array_2 = array_fill (0,60,$num_array);
	$num_array_count = array_fill (0,60,$num_arra2);
	$prev_date_temp = array_fill (0,60,'1962-08-17');
	$prev_date = array_fill (0,60,$prev_date_temp);

	$draw_count_temp_array1 = array_fill (0,16,0);
	$draw_count_temp_array2 = array_fill (0,16,$draw_count_temp_array1);
	$draw_count_array = array_fill (0,60,$draw_count_temp_array2);

	$combo2_count = array_fill (0,10,0);
	
	#initialize date variables
	require ("includes/unix.incl");

	$table_temp = $draw_prefix . 'temp_2_5000';

	#echo "table_temp = $table_temp<br>";
	
	##############################################################
	# create pair table and populate
	##############################################################
	echo "------ create_pair_table.incl -----<br>";
	require ("includes/create_pair_table.incl");
	
	##############################################################
	# calculate pair count and update temp_pairs_5000
	##############################################################
	echo "------ calculate_draw_pair_count.incl -----<br>";
	require ("includes/calculate_draw_pair_count.incl"); #pair count
	
	##############################################################
	# print pair table
	##############################################################
	echo "------ print_pair_table.incl -----<br>";
	
	if ($print_tables)
	{
		require ("includes/print_pair_table.incl");
	}
	
	##############################################################
	# create trip table and populate
	##############################################################
	echo "------ create_trip_table.incl -----<br>";
	require ("includes/create_trip_table.incl");

	##############################################################
	# print trip table
	##############################################################
	echo "------ print_trip_table.incl -----<br>";
	
	if ($print_tables)
	{
		require ("includes/print_trip_table.incl");
	}

	$table_temp = $draw_prefix . 'temp_4_5000';

	##############################################################
	# create quad table and populate
	##############################################################
	echo "------ create_quad_table.incl -----<br>";
	require ("includes/create_quad_table.incl");

	##############################################################
	# print quad table
	##############################################################
	echo "------ print_quad_table.incl -----<br>";
	#
	
	if ($print_tables)
	{
		require ("includes/print_quad_table.incl");
	}
	
	print("</BODY>\n");
?>