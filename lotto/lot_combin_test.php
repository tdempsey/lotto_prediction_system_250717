<?php

	// Game ----------------------------- Game
	//$game = 1; // Georgia Fantasy 5
	//$game = 2; // Georgia Mega Millions
	//$game = 3; // Georgia Lotto South
	$game = 4; // Florida Fantasy 5
	//$game = 5; // Florida Mega Money
	//$game = 6; // Florida Lotto
	//$game = 7; // Powerball
	// Game ----------------------------- Game
	
	require ("includes/games_switch.incl");

	require ("includes/mysqli.php");
	require ("includes/table_exist.php");
	require ("includes_once/last_draws.php");
	require ("includes/calculate_draw.php");
	require ("includes/table_draw_count.php");
	require ("includes/x10.php");
	require ("includes/count_2_seq.php");
	require ("includes/count_mod.php");
	require ("includes/draw_count_total.php");
	require ("includes/factorial.func");
	require ("includes/combination.func");
	
	$debug = 0;

	require ("includes_fl_f5/combin.incl");

	require ("includes_fl_f5/column_combin.incl");
	
	
	///////////////////////////////////////////////////////////////////////////////////////////////
	
	//start HTML page
	print("<HTML>\n");
	print("<HEAD>\n");
	print("<TITLE>$game_name Combin Test</TITLE>\n");
	print("</HEAD>\n");

	print("<BODY>\n");
	print("<H1>$game_name Combin Test</H1>\n");

	test_combin(30); 

	test_column_combin(30);

	//test_combin(100); 

	// **************************************************************************

	//close page
	print("</BODY>\n");
	print("</HTML>\n");

?>
