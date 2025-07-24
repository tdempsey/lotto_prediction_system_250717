<?php

	ini_set('implicit_flush', 1);

	set_time_limit(0);

	$debug = 0;

	if ($debug)
	{
		ini_set('display_errors', '1');
		ini_set('display_startup_errors', '1');
		error_reporting(E_ALL);
	}

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
	#$game = 7; // Powerball12/16/2020
	// Game ----------------------------- Game

	$hml = 0;
	#$hml = 1;		#= high
	#$hml = 2;		#= medium
	#$hml = 3;		#= low
	#$hml = 4;		#= min
	#$hml = 5;		#= max
	#$hml = 110;	

	echo "start<br>";
	
	require ("includes/games_switch.incl");

	#echo "1<br>";

	require ("includes/mysqli.php");

	#echo "2<br>";

	require ("includes/even_odd.php");
	require ("includes/build_rank_table.php");
	require ("includes_ga_f5/calculate_draw2.php"); 
	require ("includes/calculate_rank2.php");
	require ("includes/first_draw_unix.php");
	require ("includes/last_draw_unix.php");
	require ("includes/next_draw.php");

	require ("includes/display2.php");
	require ("includes_ga_f5/split_draws_2.php");
	require ("includes/display2_summary.php");
	require ("includes/dateDiffInDays.php");
	require ("includes/unix.incl");

	#echo "4<br>";	 

	date_default_timezone_set('America/New_York');

	require_once ("includes/hml_switch.incl");	
	
	$debug = 0;

	require ("includes/display_include.incl");

	//start HTML page
	print("<HTML>\n");
	print("<HEAD>\n");
	print("<TITLE>$game_name Display 2 - $game_name</TITLE>\n");
	print("</HEAD>\n");
	
	print("<BODY>\n");

	print "<h3><a href=\"#26\">Limit 26</a> | <a href=\"#5000\">Limit 5000</a></h3>";

	#log_text("$game_name Display Start");

	$curr_date = date('Y-m-d');

	$date = strtotime($curr_date);
    $date = strtotime("-1 day", $date);
    $last_updated = date('Y-m-d', $date);

	#echo "$last_updated<br>";

	$date = explode('-', $last_updated);

	$lastupdated = $date[0];
	$lastupdated .= $date[1];
	$lastupdated .= $date[2];

	#echo "$lastupdated<br>";

	$drop_tables = 0;
	
	if ($drop_tables)
	{
		$profile_table = "combo_5_42_updated_";
		$profile_table .= "$lastupdated";

		$query = "DROP TABLE IF EXISTS $profile_table ";

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

		$query =  "CREATE TABLE IF NOT EXISTS $profile_table LIKE combo_5_42" ;

		print("$query<p>");

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

		$query = "INSERT INTO $profile_table ";
		$query .= "(SELECT * FROM combo_5_42  ";
		$query .= "WHERE last_updated = '$last_updated') ";

		#print "$query<br>";
		
		$mysqli_result2 = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));
	}

	################################################################################################

	#echo "calling ### 1 ###<br>";

	#########################################################################################################
		
		echo "### 2 ###<br>";
		
		split_draws_2 ($dateDiff);

		echo "### 3 ###<br>";

		lot_display2 ($dateDiff,$combin=1);
		
		lot_display2 ($dateDiff,$combin=2);

		lot_display2 ($dateDiff,$combin=3);

		lot_display2 ($dateDiff,$combin=4);

		lot_display2 ($dateDiff,$combin=5);

		lot_display2 ($dateDiff,$combin=6);
		
		lot_display2 ($dateDiff,$combin=7);

		lot_display2 ($dateDiff,$combin=8);

		lot_display2 ($dateDiff,$combin=9);

		lot_display2 ($dateDiff,$combin=10);
		
		lot_display2_summary ($dateDiff);

	die();

	print "<a href=\"lot_analyze.php\" target=\"_blank\">Open lot_analyze.php</a>"; 
	
	print("</BODY>\n");
?>
