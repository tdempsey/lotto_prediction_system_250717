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

	// Game ----------------------------- Game
	$game = 1; // Georgia Fantasy 5
	//$game = 2; // Georgia Mega Millions
	//$game = 3; // Georgia Lotto South
	#$game = 4; // Florida Fantasy 5
	//$game = 5; // Florida Mega Money
	//$game = 6; // Florida Lotto
	#$game = 7; // Powerball
	// Game ----------------------------- Game
	
	require ("includes/games_switch.incl");

	//require ("includes/mysqli.php");
	require ("includes/mysqli.php");
	
	$debug = 0;

	if ($debug)
	{
		ini_set('display_errors', '1');
		ini_set('display_startup_errors', '1');
		error_reporting(E_ALL);
	}

	print("<h2>lot_update2.php updating</h2>");

	exec("php lot_update2.php parameters 2>dev/null >&- <&- >/dev/null &");

	print("<h2>lot_update3.php updating</h2>");

	exec("php lot_update3.php parameters 2>dev/null >&- <&- >/dev/null &");

	print("<h2>lot_update4.php updating</h2>");

	exec("php lot_update4.php parameters 2>dev/null >&- <&- >/dev/null &");

	print("<h2>lot_drange_update2.php updating</h2>");

	exec("php lot_drange_update2.php parameters 2>dev/null >&- <&- >/dev/null &");

	print("<h2>lot_drange_update3.php updating</h2>");

	exec("php lot_drange_update3.php parameters 2>dev/null >&- <&- >/dev/null &");

	print("<h2>lot_drange_update4.php updating</h2>");

	exec("php lot_drange_update4.php parameters 2>dev/null >&- <&- >/dev/null &");

	print("<h2>lot_drange_update5.php updating</h2>");

	exec("php lot_drange_update5.php parameters 2>dev/null >&- <&- >/dev/null &");

	print("<h2>lot_drange_update6.php updating</h2>");

	exec("php lot_drange_update6.php parameters 2>dev/null >&- <&- >/dev/null &");

	print("<h2>lot_drange_update7.php updating</h2>");

	exec("php lot_drange_update7.php parameters 2>dev/null >&- <&- >/dev/null &");

	print("<h2>lot_drange_update8.php updating</h2>");

	exec("php lot_drange_update8.php parameters 2>dev/null >&- <&- >/dev/null &");

	print("<h2>lot_drange_update9.php updating</h2>");

	exec("php lot_drange_update9.php parameters 2>dev/null >&- <&- >/dev/null &");

	print("<h2>updating</h2>");

	print("</BODY>\n");
?>
