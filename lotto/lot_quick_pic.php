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

	
	$debug = 0;

	$table_temp = $draw_prefix . "quick_pick ";

	$query = "DROP TABLE IF EXISTS $table_temp";

	print "$query<p>";

	$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

	$query = "CREATE TABLE $table_temp LIKE ";
	$query .= "(";

	print "$query<p>";

	$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

	$query = "SELECT * FROM $draw_prefix";
	$query .= "wheel_draw_eoX ";

	print "$query<p>";

	$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

	while($row = mysqli_fetch_array($mysqli_result))
	{	
		$query2 = "SELECT * FROM aon_";
		$query2 .= "$combo_count";
		$query3 .= "_";
		$query3 .= "$balls ";
		$query2 .= "WHERE  ";
		$query2 .= "ORDER BY date DESC ";

		print "$query2<p>";

		$mysqli_result2 = mysqli_query($mysqli_link, $query2) or die (mysqli_error($mysqli_link));

		$row2 = mysqli_fetch_array($mysqli_result2);

		$last_draw = $row[date];

		$row2 = mysqli_fetch_array($mysqli_result2);

		$prev_draw = $row[date];

		$query3 = "Insert INTO $table_temp ";
		$query3 .= "VALUES ( '0', ";
		
		for($z = 0; $z < $combo_count; $z++)
		{
			$query3 .= "$row[d.$z], ";
		}

		$query3 .= "$sum, ";
		
		for($z = 1; $z <= 16; $z++)
		{
			$query3 .= "'0', ";
		}
		
		$query3 .= "$num_rows, "; #count

		$query3 .= "'0', ";
		$query3 .= "'0', ";
		$query3 .= "'0', ";
		$query3 .= "'0'";

		$query3 .= "	     ) ";

		print "$query3<p>";
		
		$mysqli_result3 = mysqli_query($mysqli_link, $query3) or die (mysqli_error($mysqli_link));
	}

	//start table
	print("<TABLE BORDER=\"1\">\n");

	//create header row
	print("<TR><B>\n");
	print("<TD BGCOLOR=\"#CCCCCC\" align=\"center\">Sum</TD>\n");
	print("<TD BGCOLOR=\"#CCCCCC\" align=\"center\">Total</TD>\n");
	
	for($index = 30; $index <= 245; $index++)
	{
		print("<TR>\n");
		print("<TD align=\"center\"><B>$index</B></TD>\n");
		if ($sum_array[$index] > 35)
		{
			print("<TD align=\"center\"><font color=\"#ff3300\"><b>$sum_array[$index]</b></font></TD>\n");
		} else {
			print("<TD align=\"center\">$sum_array[$index]</TD>\n");
		}
		print("</TR>\n");
	}
		
	print("</TABLE>\n");

	///////////////////////////////////////////////////////////////////////////////////////////////
	
	//start HTML page
	print("<HTML>\n");
	print("<HEAD>\n");
	print("<TITLE>$game_name Conbo Count Table</TITLE>\n");
	print("</HEAD>\n");

	print("<BODY>\n");
	print("<H1>$game_name Statistics</H1>\n");
	
	$curr_date = date("Ymd");
	$curr_date_dash = date("Y-m-d");

	combo_table(2);

	// **************************************************************************

	//close page
	print("</BODY>\n");
	print("</HTML>\n");

?>