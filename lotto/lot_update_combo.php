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

	/*
		split by combo_x (less x for draw6)
	*/

	
	require ("includes/games_switch.incl");

	require ("includes/mysqli.php");
	require ("includes/table_exist.php");
	require ("includes/last_draws.php");
	require ("includes/last_draw_unix.php");
	require ("includes/test_draw_table.php");
	require ("includes/test_filter_a_table.php");
	require ("includes/test_filter_b_table.php");
	require ("includes/test_filter_c_table.php");
	require ("includes/test_wheel_table.php");
	require ("includes/calculate_draw.php");
	require ("includes/table_draw_count.php");
	require ("includes/x10.php");
	require ("includes/count_2_seq.php");
	require ("includes/count_3_seq.php");
	require ("includes/count_mod.php");
	require ("includes/draw_count_total.php");
	
	$debug = 0;

	// ----------------------------------------------------------------------------------
	function combo_table ($combo_count)
	{ 
		global $debug, $game, $game_name, $balls, $balls_drawn, $mega_balls, $draw_table_name, $draw_prefix;
	
		require ("includes/mysqli.php");
		require ("includes/unix.incl");

		$sum_array = array_fill (0,251,0);

		$table_temp = $draw_prefix . $combo_count . "_" . $balls . "_stats";

		$query = "DROP TABLE IF EXISTS $table_temp";

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

		$query = "CREATE TABLE $table_temp";
		$query .= "(";
		$query .= "id int(10) unsigned NOT NULL auto_increment, ";

		for ($r = 1; $r <= $combo_count; $r++)
		{
			$query .= "d";
			$query .= "$r tinyint(3) unsigned NOT NULL default '0', ";
		}
		
		$query .= "sum tinyint(3) unsigned NOT NULL default '0', ";
		$query .= "day1 tinyint(3) unsigned NOT NULL default '0', ";
		$query .= "week1 tinyint(3) unsigned NOT NULL default '0', ";
		$query .= "week2 tinyint(3) unsigned NOT NULL default '0', ";
		$query .= "month1 tinyint(3) unsigned NOT NULL default '0', ";
		$query .= "month3 tinyint(3) unsigned NOT NULL default '0', ";
		$query .= "month6 tinyint(3) unsigned NOT NULL default '0', ";
		$query .= "year1 int(5) unsigned NOT NULL default '0', ";
		$query .= "year2 int(5) unsigned NOT NULL default '0', ";
		$query .= "year3 int(5) unsigned NOT NULL default '0', ";
		$query .= "year4 int(5) unsigned NOT NULL default '0', ";
		$query .= "year5 int(5) unsigned NOT NULL default '0', ";
		$query .= "year6 int(5) unsigned NOT NULL default '0', ";
		$query .= "year7 int(5) unsigned NOT NULL default '0', ";
		$query .= "year8 int(5) unsigned NOT NULL default '0', ";
		$query .= "year9 int(5) unsigned NOT NULL default '0', ";
		$query .= "year10 int(5) unsigned NOT NULL default '0', ";
		$query .= "count int(5) unsigned NOT NULL default '0', ";
		$query .= "percent_30 float (4,1) unsigned NOT NULL default '0.0', ";
		$query .= "percent_365 float (4,1) unsigned NOT NULL default '0.0', ";
		$query .= "percent_5000 float (4,1) unsigned NOT NULL default '0.0', ";
		$query .= "sigma int(5) unsigned NOT NULL default '0', ";
		$query .= "PRIMARY KEY  (id) ";
		$query .= ") TYPE=MyISAM ";

		print "$query<p>";

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));
	
		$query = "SELECT DISTINCT d1,d2,d3,d4,d5 FROM $draw_prefix";
		$query .= "$combo_count";
		$query .= "_";
		$query .= "$balls ";
		#$query .= "ORDER BY date ASC ";
		#$query .= "LIMIT 1 ";

		#print "$query<p>";
	
		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

		while($row = mysqli_fetch_array($mysqli_result))
		{
			$sum = $row[d1] + $row[d2] + $row[d3] + $row[d4] + $row[d5];

			$sum_array[$sum]++;
			
			$query2 = "SELECT * FROM $draw_prefix";
			$query2 .= "$combo_count";
			$query2 .= "_";
			$query2 .= "$balls ";
			$query2 .= "WHERE d1 = $row[d1] ";
			$query2 .= "AND d2 = $row[d2] ";
			$query2 .= "AND d3 = $row[d3] ";
			$query2 .= "AND d4 = $row[d4] ";
			$query2 .= "AND d5 = $row[d5] ";

			#print "$query2<p>";

			$mysqli_result2 = mysqli_query($mysqli_link, $query2) or die (mysqli_error($mysqli_link));

			$num_rows = mysqli_num_rows($mysqli_result2); 

			#add year calc

			$query3 = "Insert INTO $table_temp ";
			$query3 .= "VALUES ( '0', ";
			#$query3 .= "         '$row[0]', ";
			
			for($z = 0; $z < $combo_count; $z++)
			{
				$query3 .= "$row[$z], ";
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

			#print "$query3<p>";
			
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
	}

	///////////////////////////////////////////////////////////////////////////////////////////////
	
	//start HTML page
	print("<HTML>\n");
	print("<HEAD>\n");
	print("<TITLE>$game_name Update Combo</TITLE>\n");
	print("</HEAD>\n");

	print("<BODY>\n");
	print("<H1>$game_name Update Combo</H1>\n");
	
	$curr_date = date("Ymd");
	$curr_date_dash = date("Y-m-d");

	combo_table(5);
	
	#print_sum_combo_table(5000);

	// **************************************************************************

	//close page
	print("</BODY>\n");
	print("</HTML>\n");

?>