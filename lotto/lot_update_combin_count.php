<?php

	// Game ----------------------------- Game
	//$game = 1; // Georgia Fantasy 5
	#$game = 2; // Mega Millions
	//$game = 3; // Georgia Lotto South
	$game = 4; // Florida Fantasy 5
	//$game = 5; // Florida Mega Money
	$game = 6; // Florida Lotto
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
	require ("includes/last_draw_unix.php"); 
	require ("includes/next_draw.php");
	
	$debug = 0;

// ----------------------------------------------------------------------------------
	function update_counts($count,$k) 
	{
		require ("includes/mysqli.php"); 

		global $debug, $game, $draw_prefix, $draw_table_name, $balls, $balls_drawn;

		$filter_table_b = "filter_b_6_53";
		if ($k < 10)
		{
			$filter_table_b .= "_0$k";
		} else {
			$filter_table_b .= "_$k";
		}

		$table_temp = $draw_prefix . $count . "_" . $balls . "_" . $filter_table_b;

		$query = "DROP TABLE IF EXISTS $table_temp";

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

		$query = "CREATE TABLE $table_temp";
		$query .= "(";
		#$query .= "id int(10) unsigned NOT NULL auto_increment, ";
		$query .= "date int(10) unsigned NOT NULL auto_increment, ";
		#$query .= "date date NOT NULL default '1962-08-17',";
		$query .= "b_int int(10) unsigned NOT NULL default '0',";

		for ($r = 1; $r < $count; $r++)
		{
			$query .= "d";
			$query .= "$r tinyint(3) unsigned NOT NULL default '0', ";
		}
		$query .= "d";
		$query .= "$r tinyint(3) unsigned NOT NULL default '0', ";
		$query .= "sum int(10) unsigned NOT NULL default '0', ";
		$query .= "PRIMARY KEY  (date) ";
		$query .= ") TYPE=MyISAM ";

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));
	
		$query = "SELECT * FROM $filter_table_b ";
		#$query .= "ORDER BY date ASC ";

		print "$query<p>";

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

		$temp_include = "combin_" . $count . "_" . $balls_drawn . ".incl";

		# process counts
		while($row = mysqli_fetch_array($mysqli_result))
		{
			#print "$temp_include<p>";
			require ("includes/$temp_include"); 
		}

		print "<h3>Table <font color=\"#ff0000\">$table_temp</font> Updated!</h3>";
	}

	for ($k = 1; $k <= 1; $k++) #---------------------------------------------------------------------------
	{
		for ($x = 5; $x < $balls_drawn; $x++)
		{
			#print "<b>update_counts($x,$k)</b><p>";
			update_counts($x,$k);
		}
	}
	
	print("</BODY>\n");
?>
