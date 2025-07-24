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
	require ("includes/db.class");
	require ("includes/even_odd.php");
	require ("includes/calculate_division.php");
	require ("includes/build_rank_table.php");
	require ("includes/calculate_draw.php"); 
	require ("includes/calculate_rank.php");
	require ("includes/first_draw_unix.php");
	require ("includes/last_draw_unix.php");
	require ("includes/next_draw.php");
	
	$debug = 0;

	$max_draw = 36;

	$d2_array = array_fill (0,2,0);
	$d3_array = array_fill (0,3,0);
	$d4_array = array_fill (0,4,0);
	$d6_array = array_fill (0,6,0);
	$d12_array = array_fill (0,12,0);
	$d18_array = array_fill (0,18,0);


	//start HTML page
	print("<HTML>\n");
	print("<HEAD>\n");
	print("<TITLE>$game_name Filter Money - $game_name</TITLE>\n");
	print("</HEAD>\n");
	
	print("<BODY>\n");

	// get from draw table
	$query5 = "SHOW TABLES FROM $db_name ";
	$query5 .= "LIKE 'ga_f5_filter_a%' ";

	echo "$query5<p>";

	$mysqli_result5 = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));

	// get each row
	while($row = mysqli_fetch_array($mysqli_result5))
	{	
		print "<p>$row[0]</p>";

	}
	
	print("</BODY>\n");
?>
