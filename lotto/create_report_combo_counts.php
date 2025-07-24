<?php

	// add tables population for combos, pairs
	// add test for missing draws
	// recalcu;ate draw includes
	// add db class
	// error checking - all modules
	// fix pair count
	
	# filters - sum,even,odd,d501,d502,mod,modx,seq2,seq3

	// Game ----------------------------- Game
	$game = 1; // Georgia Fantasy 5
	#$game = 2; // Georgia Mega Millions
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
	require ("includes/next_draw.php");
	require ("includes/count_2_seq.php");
	require ("includes/count_3_seq.php");
	#require ("includes_fl_f5/last_draws_fl_f5.php");
	#require ("includes_fl_f5/combin.incl");
	require ("includes_ga_f5/last_draws_ga_f5.php"); #fix
	require ("$game_includes/combin.incl");
	require ("includes/mysqli.php");
	
	$debug = 0;

	// ----------------------------------------------------------------------------------
	function update_counts($col1, $count) 
	{
		require ("includes/mysqli.php"); 

		global $debug, $game, $draw_prefix, $draw_table_name, $balls, $balls_drawn;

		$table_temp = 'zcombo_' . $count . "_" . $balls . "_" . 'counts' . '_' . $col1;

		$query = "DROP TABLE IF EXISTS $table_temp ";

		echo "$query<p>";

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

		$query =  "CREATE TABLE $table_temp LIKE report_combo_counts_model ";

		echo "$query<p>";

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));
	/*
		$query = "SELECT * FROM $table_temp ";
		$query .= "ORDER BY id DESC ";

		print "$query<p>";

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

		$row_id = mysqli_fetch_array($mysqli_result);

	*/

		$temp_include = "combin_" . $count . "_" . $balls_drawn . ".incl";

		$query = "SELECT * FROM combo_5_39 ";
		$query .= "WHERE b1 = $col1 ";

		print "$query<p>";

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

		while($row = mysqli_fetch_array($mysqli_result))
		{
			require ("includes/$temp_include"); 
		}
		#die();
	}

	//start HTML page
	print("<HTML>\n");
	print("<HEAD>\n");
	print("<TITLE>Create Report Combo Counts - $game_name</TITLE>\n");
	print("</HEAD>\n");
	
	print("<BODY>\n");

	for ($w = 1; $w <= 25; $w++)
	{
		for ($x = 2; $x < $balls_drawn; $x++)
		{
			update_counts($w,$x);
			#die();
		}
	}

	print("</BODY>\n");
?>
