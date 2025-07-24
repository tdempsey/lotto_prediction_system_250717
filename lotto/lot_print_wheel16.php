<?php

	// add tables population for combos, pairs
	// add test for missing draws
	// recalcu;ate draw includes
	// add db class
	// error checking - all modules
	// fix pair count

	//////////////////////////////////////////
	//////////// uncomment combin.incl ga f5
	//////////////////////////////////////////

	$game = 1; // Georgia Fantasy 5

	require ("includes/build_rank_table.php");
	require ("includes/last_draws.php");

	$debug = 0;

	if ($debug)
	{
		ini_set('display_errors', '1');
		ini_set('display_startup_errors', '1');
		error_reporting(E_ALL);
	}
	
	require ("includes/games_switch.incl");

	require ("includes/mysqli.php");

	$curr_date_dash = date("Y-m-d");

	require ("includes_ga_f5/print_wheels16_draws.incl");

	print("<h2>Completed!</h2>");

	print("</BODY>\n");
?>
