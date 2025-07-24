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

	function lot_update_rank ($col1)
	{ 
		global $debug, $game, $game_name, $balls, $balls_drawn, $mega_balls, $draw_table_name, $draw_prefix, $game_includes;
	
		require ("includes/mysqli.php");

		require ("$game_includes/config.incl");
		
		print("<H2>Lotto Filter D Rank Update - $game_name</H2>\n");
	
		$dcount = 0;

		$curr_date = date("Y-m-d");
		#$curr_date = '2012-07-10';

		$rank_count = array (0);

		$rank_count = BuildRankTable($curr_date); // array 0..balls with total draws for last 26

		$rank_table_count = array_fill (0, 7, 0);

		for($z = 1; $z <= $balls; $z++)
		{
			if ($rank_count[$z] >= 6)
			{
				$rank_table_count[6]++;
			} else {
				$rank_table_count[$rank_count[$z]]++;
			}
		}

		print_r ($rank_table_count);

		$table_temp = $draw_prefix . "filter_d_";
		if ($col1 < 10)
		{
			$table_temp .= "0$col1";
		} else {
			$table_temp .= "$col1";
		}

		$table_temp = "combo_5_39";

		$query_d = "SELECT * FROM $table_temp ";
		$query_d .= "WHERE b1 <= 20 ";
		$query_d .= "ORDER BY id ASC ";

		#print "$query_d<p>";

		$mysqli_result_d = mysqli_query($query_d, $mysqli_link) or die (mysqli_error($mysqli_link));

		$num_rows_d = \($mysqli_result_d);

		echo "<h2>$num_rows_d left to update</h2>";
	
		// get each row
		while($row = mysqli_fetch_array($mysqli_result_d))
		{	
			if ($row_combo[rank_updated] < '$curr_date')
			{
				$draw_rank_count = array_fill (0, 7, 0);

				for($y = 1; $y <= $balls_drawn; $y++)
				{
					if ($rank_count[$row[$y]] >= 6)
					{
						$draw_rank_count[6]++;
					} else {
						$draw_rank_count[$rank_count[$row[$y]]]++;
					}
				}

				$query7 = "UPDATE $table_temp ";
				$query7 .= "SET   rank1 = '$draw_rank_count[1]', ";
				$query7 .= "      rank2 = '$draw_rank_count[2]', ";
				$query7 .= "      rank3 = '$draw_rank_count[3]', ";
				$query7 .= "      rank4 = '$draw_rank_count[4]', ";
				$query7 .= "      rank5 = '$draw_rank_count[5]', ";
				$query7 .= "      rank6 = '$draw_rank_count[6]', ";
				#$query7 .= "      rank_updated = '$curr_date' ";
				$query7 .= "      last_updated = '$curr_date' ";
				$query7 .= "WHERE id = $row[id] ";

				#print "$query7<p>";

				$mysqli_result7 = mysqli_query($query7, $mysqli_link) or die (mysqli_error($mysqli_link));

				#die();
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
		lot_update_rank ($_GET["col1"]); # Georgia Fantasy 5
	#}

	print("<h2>Completed!</h2>");

	print("</BODY>\n");
?>
