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
	require ("includes/db.class");
	require ("includes/even_odd.php");
	require ("includes/calculate_50_50.php");
	require_once ("includes/build_rank_table.php");
	require ("includes/calculate_draw.php"); 
	require ("includes/calculate_rank.php");
	require ("includes/next_draw.php");
	require ("includes/count_2_seq.php");
	require ("includes/count_3_seq.php");
	require ("includes_ga_f5/last_draws_ga_f5.php");
	require ("includes_ga_f5/combin.incl");
	require ("includes/mysqli.php");
	
	$debug = 0;

	function lot_update_combin ($col1)
	{ 
		global $debug, $game, $game_name, $balls, $balls_drawn, $mega_balls, $draw_table_name, $draw_prefix, $game_includes;
	
		require ("includes/mysqli.php");

		require ("$game_includes/config.incl");
		
		print("<H2>Lotto Filter D Combin Update - $game_name</H2>\n");
	
		$dcount = 0;

		$curr_date = date("Y-m-d");
		#$curr_date = '2012-07-10';

		$table_temp = $draw_prefix . "filter_a_";
		if ($col1 < 10)
		{
			$table_temp .= "0$col1";
		} else {
			$table_temp .= "$col1";
		}

		$query_d = "SELECT * FROM $table_temp ";
		#$query_d .= "WHERE b1 =  $col1 ";
		#$query_d .= "WHERE   last_updated < '$curr_date' ";
		$query_d .= "ORDER BY id ASC ";

		print "$query_d<p>";

		$mysqli_result_d = mysqli_query($query_d, $mysqli_link) or die (mysqli_error($mysqli_link));

		$num_rows_d = mysqli_num_rows($mysqli_result_d);

		echo "<h2>$num_rows_d left to update</h2>";
	
		// get each row
		while($row = mysqli_fetch_array($mysqli_result_d))
		{	
			$query_combo = "SELECT * FROM combo_5_39 ";
			$query_combo .= "WHERE id = $row[id] ";

			#print "$query_combo<p>";

			$mysqli_result_combo = mysqli_query($query_combo, $mysqli_link) or die (mysqli_error($mysqli_link));

			$row_combo = mysqli_fetch_array($mysqli_result_combo);

			if ($row_combo[last_updated] < '2012-07-10')
			{
				$total_combin = array();
				$draw_array = array(0);

				#print_r ($row);

				for ($v = 1; $v <= $balls_drawn; $v++)
				{
					array_push($draw_array, $row[$v]);
				}

				$total_combin = test_combin($draw_array);

				$query7 = "UPDATE combo_5_39 ";
				$query7 .= "SET   comb2 = '10', ";
				$query7 .= "      comb3 = $total_combin[3], ";
				$query7 .= "      comb4 = $total_combin[4], ";
				$query7 .= "      comb5 = $total_combin[5], ";
				$query7 .= "      last_updated = '$curr_date' ";
				$query7 .= "WHERE id = $row[id] ";

				print "$query7<p>";
				
				$mysqli_result7 = mysqli_query($query7, $mysqli_link) or die (mysqli_error($mysqli_link));
			}
		}
	}

	//start HTML page
	print("<HTML>\n");
	print("<HEAD>\n");
	print("<TITLE>Lotto Update Comb - $game_name - Rank Limit</TITLE>\n");
	print("</HEAD>\n");
	
	print("<BODY>\n");

	#$x = 1;

	$col1 = $_GET["col1"];

	echo "col1 = $col1<br>";

	#for ($x = 1 ; $x <= 20; $x++)
	#{
		lot_update_combin ($col1); # Georgia Fantasy 5
	#}

	print("<h2>Completed!</h2>");

	print("</BODY>\n");
?>
