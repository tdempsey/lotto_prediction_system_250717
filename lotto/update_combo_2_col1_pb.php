<?php

	// add tables population for combos, pairs
	// add test for missing draws
	// recalcu;ate draw includes
	// add db class
	// error checking - all modules
	// fix pair count

	// Game ----------------------------- Game
	$game = 1; // Georgia Fantasy 5
	//$game = 2; // Georgia Mega Millions
	//$game = 3; // Georgia Lotto South
	#$game = 4; // Florida Fantasy 5
	//$game = 5; // Florida Mega Money
	//$game = 6; // Florida Lotto
	$game = 7; // Powerball
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
	require ("includes_pb/last_draws_pb.php");
	#require ("includes_pb/last_draws_pb.php");
	require ("$game_includes/combin.incl");
	require ("includes/mysqli.php");
	
	$debug = 0;

	function lot_update_combin_4 ($d,$update_date)
	{ 
		global $debug, $game, $game_name, $balls, $balls_drawn, $mega_balls, $draw_table_name, $draw_prefix, $game_includes;
	
		require ("includes/mysqli.php");

		require ("$game_includes/config.incl");
		
		print("<H2>Lotto Combo 4 Update - $game_name</H2>\n");
	
		$dcount = 0;

		$curr_date = date("Y-m-d");
		#$curr_date = '2013-3-21';

		$query_d = "SELECT * FROM pb_4_59 ";
		$query_d .= "WHERE nums_count <=  '2' ";
		$query_d .= "AND   date >= '$update_date' ";
		$query_d .= "ORDER BY date DESC ";

		print "$query_d<p>";

		$mysqli_result_d = mysqli_query($query_d, $mysqli_link) or die (mysqli_error($mysqli_link));

		$num_rows_d = mysqli_num_rows($mysqli_result_d);

		echo "num_rows_d = $num_rows_d<p>";
	
		// get each row
		while($row = mysqli_fetch_array($mysqli_result_d))
		{	
			$query_combo = "SELECT * FROM combo_5_59";
			$query_combo .= "_$d ";
			$query_combo .= "WHERE (b1 =  $row[d1] ";
			$query_combo .= "    or b2 =  $row[d1] ";
			$query_combo .= "    or b3 =  $row[d1] ";
			$query_combo .= "    or b4 =  $row[d1] ";
			$query_combo .= "    or b5 =  $row[d1]) ";
			$query_combo .= "AND   (b1 =  $row[d2] ";
			$query_combo .= "    or b2 =  $row[d2] ";
			$query_combo .= "    or b3 =  $row[d2] ";
			$query_combo .= "    or b4 =  $row[d2] ";
			$query_combo .= "    or b5 =  $row[d2]) ";
			$query_combo .= "AND   (b1 =  $row[d3] ";
			$query_combo .= "    or b2 =  $row[d3] ";
			$query_combo .= "    or b3 =  $row[d3] ";
			$query_combo .= "    or b4 =  $row[d3] ";
			$query_combo .= "    or b5 =  $row[d3]) ";
			$query_combo .= "AND   (b1 =  $row[d4] ";
			$query_combo .= "    or b2 =  $row[d4] ";
			$query_combo .= "    or b3 =  $row[d4] ";
			$query_combo .= "    or b4 =  $row[d4] ";
			$query_combo .= "    or b5 =  $row[d4]) ";
			$query_combo .= "AND   b1 <= '20' ";
			$query_combo .= "AND   last_updated <= '$row[date]' ";
			$query_combo .= "ORDER BY id ASC ";

			print "$query_combo<p>";

			$mysqli_result_combo = mysqli_query($query_combo, $mysqli_link) or die (mysqli_error($mysqli_link));

			$num_rows_combo = mysqli_num_rows($mysqli_result_combo);

			echo "num_rows_combo = $num_rows_combo<p>";

			while($row_combo = mysqli_fetch_array($mysqli_result_combo))
			{
				$total_combin = array();
				$draw_array = array(0);

				#print_r ($row);

				for ($v = 1; $v <= $balls_drawn; $v++)
				{
					array_push($draw_array, $row_combo[$v]);
				}

				$total_combin = test_combin($draw_array);

				$query7 = "UPDATE combo_5_59";
				$query7 .= "_$d ";
				$query7 .= "SET   comb2 = $total_combin[2], ";
				$query7 .= "      comb3 = $total_combin[3], ";
				$query7 .= "      comb4 = $total_combin[4], ";
				#$query7 .= "      comb5 = $total_combin[5], ";
				$query7 .= "      last_updated = '$row[date]' ";
				$query7 .= "WHERE b1 = $row_combo[b1] ";
				$query7 .= "AND   b2 = $row_combo[b2] ";
				$query7 .= "AND   b3 = $row_combo[b3] ";
				$query7 .= "AND   b4 = $row_combo[b4] ";
				$query7 .= "AND   b5 = $row_combo[b5] ";

				print "$query7<p>";

				#die();
				
				$mysqli_result7 = mysqli_query($query7, $mysqli_link) or die (mysqli_error($mysqli_link));
			}
		}
	}

	function lot_update_combin_3 ($d,$update_date)
	{ 
		global $debug, $game, $game_name, $balls, $balls_drawn, $mega_balls, $draw_table_name, $draw_prefix, $game_includes;
	
		require ("includes/mysqli.php");

		require ("$game_includes/config.incl");
		
		print("<H2>Lotto Combo 3 Update - $game_name</H2>\n");
	
		$dcount = 0;

		$curr_date = date("Y-m-d");
		#$curr_date = '2012-07-10';

		$query_d = "SELECT * FROM pb_3_59 ";
		$query_d .= "WHERE nums_count <=  '2' ";
		$query_d .= "AND   date >= '$update_date' ";
		$query_d .= "ORDER BY date DESC ";

		print "$query_d<p>";

		$mysqli_result_d = mysqli_query($query_d, $mysqli_link) or die (mysqli_error($mysqli_link));

		$num_rows_d = mysqli_num_rows($mysqli_result_d);

		echo "num_rows_d = $num_rows_d<p>";
	
		// get each row
		while($row = mysqli_fetch_array($mysqli_result_d))
		{	
			$query_combo = "SELECT * FROM combo_5_59";
			$query_combo .= "_$d ";
			$query_combo .= "WHERE (b1 =  $row[d1] ";
			$query_combo .= "    or b2 =  $row[d1] ";
			$query_combo .= "    or b3 =  $row[d1] ";
			$query_combo .= "    or b4 =  $row[d1] ";
			$query_combo .= "    or b5 =  $row[d1]) ";
			$query_combo .= "AND   (b1 =  $row[d2] ";
			$query_combo .= "    or b2 =  $row[d2] ";
			$query_combo .= "    or b3 =  $row[d2] ";
			$query_combo .= "    or b4 =  $row[d2] ";
			$query_combo .= "    or b5 =  $row[d2]) ";
			$query_combo .= "AND   (b1 =  $row[d3] ";
			$query_combo .= "    or b2 =  $row[d3] ";
			$query_combo .= "    or b3 =  $row[d3] ";
			$query_combo .= "    or b4 =  $row[d3] ";
			$query_combo .= "    or b5 =  $row[d3]) ";
			$query_combo .= "AND   b1 <= '20' ";
			$query_combo .= "AND   last_updated <= '$row[date]' ";
			$query_combo .= "ORDER BY id ASC ";

			print "$query_combo<p>";

			$mysqli_result_combo = mysqli_query($query_combo, $mysqli_link) or die (mysqli_error($mysqli_link));

			$num_rows_combo = mysqli_num_rows($mysqli_result_combo);

			echo "num_rows_combo = $num_rows_combo<p>";

			while($row_combo = mysqli_fetch_array($mysqli_result_combo))
			{
				$total_combin = array();
				$draw_array = array(0);

				#print_r ($row);

				for ($v = 1; $v <= $balls_drawn; $v++)
				{
					array_push($draw_array, $row_combo[$v]);
				}

				$total_combin = test_combin($draw_array);

				$query7 = "UPDATE combo_5_59";
				$query7 .= "_$d ";
				$query7 .= "SET   comb2 = '10', ";
				$query7 .= "      comb3 = $total_combin[3], ";
				$query7 .= "      comb4 = $total_combin[4], ";
				#$query7 .= "      comb5 = $total_combin[5], ";
				$query7 .= "      last_updated = '$row[date]' ";
				$query7 .= "WHERE b1 = $row_combo[b1] ";
				$query7 .= "AND   b2 = $row_combo[b2] ";
				$query7 .= "AND   b3 = $row_combo[b3] ";
				$query7 .= "AND   b4 = $row_combo[b4] ";
				$query7 .= "AND   b5 = $row_combo[b5] ";

				print "$query7<p>";

				#die();
				
				$mysqli_result7 = mysqli_query($query7, $mysqli_link) or die (mysqli_error($mysqli_link));
			}
		}
	}

	function lot_update_combin_5 ($d,$update_date)
	{ 
		global $debug, $game, $game_name, $balls, $balls_drawn, $mega_balls, $draw_table_name, $draw_prefix, $game_includes;
	
		require ("includes/mysqli.php");

		require ("$game_includes/config.incl");
		
		print("<H2>Lotto Combo 5 Update - $game_name</H2>\n");
	
		$dcount = 0;

		$curr_date = date("Y-m-d");
		#$curr_date = '2012-07-10';

		$query_d = "SELECT * FROM pb_draws ";
		$query_d .= "WHERE date >= '$update_date' ";
		$query_d .= "ORDER BY date DESC ";

		print "$query_d<p>";

		$mysqli_result_d = mysqli_query($query_d, $mysqli_link) or die (mysqli_error($mysqli_link));

		$num_rows_d = mysqli_num_rows($mysqli_result_d);

		echo "num_rows_d = $num_rows_d<p>";
	
		// get each row
		while($row = mysqli_fetch_array($mysqli_result_d))
		{	
			$query_combo = "SELECT * FROM pb_draws ";
			$query_combo .= "WHERE b1 = $row[b1] ";
			$query_combo .= "AND   b2 = $row[b2] ";
			$query_combo .= "AND   b3 = $row[b3] ";
			$query_combo .= "AND   b4 = $row[b4] ";
			$query_combo .= "AND   b5 = $row[b5] ";

			print "$query_combo<p>";

			$mysqli_result_combo = mysqli_query($query_combo, $mysqli_link) or die (mysqli_error($mysqli_link));

			$num_rows_combo = mysqli_num_rows($mysqli_result_combo);

			echo "num_rows_combo = $num_rows_combo<p>";

			$row_combo = mysqli_fetch_array($mysqli_result_combo);

			$query7 = "UPDATE combo_5_59";
			$query7 .= "_$row_combo[b1] ";
			$query7 .= "SET   comb5 = '$num_rows_combo', ";
			$query7 .= "      last_updated = '$row[date]' ";
			$query7 .= "WHERE b1 = $row_combo[b1] ";
			$query7 .= "AND   b2 = $row_combo[b2] ";
			$query7 .= "AND   b3 = $row_combo[b3] ";
			$query7 .= "AND   b4 = $row_combo[b4] ";
			$query7 .= "AND   b5 = $row_combo[b5] ";

			print "$query7<p>";

			#die();
			
			$mysqli_result7 = mysqli_query($query7, $mysqli_link) or die (mysqli_error($mysqli_link));
		}
	}

	function lot_update_combin_2 ($d)
	{ 
		global $debug, $game, $game_name, $balls, $balls_drawn, $mega_balls, $draw_table_name, $draw_prefix, $game_includes;
	
		require ("includes/mysqli.php");

		require ("$game_includes/config.incl");
		
		print("<H2>Lotto Combo 2 Update - $game_name</H2>\n");
	
		$dcount = 0;

		$curr_date = date("Y-m-d");
		#$curr_date = '2012-07-10';

		$query_d = "SELECT * FROM combo_5_59";
		$query_d .= "_$d ";
		$query_d .= "WHERE comb2 = 0 ";
		#$query_d .= "ORDER BY date DESC ";

		print "$query_d<p>";

		$mysqli_result_d = mysqli_query($query_d, $mysqli_link) or die (mysqli_error($mysqli_link));

		$num_rows_d = mysqli_num_rows($mysqli_result_d);

		echo "num_rows_d = $num_rows_d<p>";
	
		// get each row
		while($row = mysqli_fetch_array($mysqli_result_d))
		{	
			$total_combin = array();
			$draw_array = array(0);

			#print_r ($row);

			for ($v = 1; $v <= $balls_drawn; $v++)
			{
				array_push($draw_array, $row[$v]);
			}

			$total_combin = test_combin($draw_array);

			$query7 = "UPDATE combo_5_59";
			$query7 .= "_$d ";
			$query7 .= "SET   comb5 = '$total_combin[5]', ";
			$query7 .= "      comb4 = '$total_combin[4]', ";
			$query7 .= "      comb3 = '$total_combin[3]', ";
			$query7 .= "      comb2 = '$total_combin[2]', ";
			$query7 .= "      last_updated = '$curr_date' ";
			$query7 .= "WHERE id = $row[id] ";

			print "$query7<p>";

			#die();
			
			$mysqli_result7 = mysqli_query($query7, $mysqli_link) or die (mysqli_error($mysqli_link));
		}
	}

	//start HTML page
	print("<HTML>\n");
	print("<HEAD>\n");
	print("<TITLE>Lotto Update Comb 2 - $game_name - Combo</TITLE>\n");
	print("</HEAD>\n");
	
	print("<BODY>\n");

	$col1 = $_GET["col1"];

	$update_date = "2012-01-01";

	lot_update_combin_2($col1);

	lot_update_combin_3 ($col1,$update_date); # Georgia Fantasy 5

	lot_update_combin_4 ($col1,$update_date); # Georgia Fantasy 5

	lot_update_combin_5 ($col1,$update_date);

	print("</BODY>\n");
?>
