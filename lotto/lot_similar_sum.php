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

	require ("includes/mysqli.php");
	require ("includes/unix.incl");
	require ("includes/print_column_test_sumeo.php"); 

	
	$$debug = 1;

	if ($debug)
	{
		ini_set('display_errors', '1');
		ini_set('display_startup_errors', '1');
		error_reporting(E_ALL);
	}
	
	//start HTML page
	print("<HTML>\n");
	print("<HEAD>\n");
	print("<TITLE>$game_name Similar Sums</TITLE>\n");
	print("</HEAD>\n");

	print("<BODY>\n");
	print("<H1>$game_name Statistics</H1>\n");
	
	$curr_date = date("Ymd");
	$curr_date_dash = date("Y-m-d");

	for ($col = 1; $col <= 5; $col++)
	{
		print_column_test_sumeo($col, 88, 2, 3); 
		print_column_test_sumeo($col, 90, 2, 3);
		print_column_test_sumeo($col, 92, 2, 3);
	}

	// **************************************************************************

?>