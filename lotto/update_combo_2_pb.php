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

	function lot_update_combin_4 ($update_date)
	{ 
		global $debug, $game, $game_name, $balls, $balls_drawn, $mega_balls, $draw_table_name, $draw_prefix, $game_includes;
	
		require ("includes/mysqli.php");

		require ("$game_includes/config.incl");
		
		print("<H2>Lotto Combo 4 Update - $game_name</H2>\n");
	
		$dcount = 0;

		$curr_date = date("Y-m-d");
		#$curr_date = '2012-07-10';

		for ($r = 1; $r <= 20; $r++)
		{
			$query_d = "SELECT * FROM pb_4_59 ";
			#$query_d .= "WHERE nums_count <=  '2' ";
			$query_d .= "WHERE date >= '$update_date' ";
			$query_d .= "ORDER BY date DESC ";

			print "$query_d<p>";

			$mysqli_result_d = mysqli_query($query_d, $mysqli_link) or die (mysqli_error($mysqli_link));

			$num_rows_d = mysqli_num_rows($mysqli_result_d);

			echo "num_rows_d = $num_rows_d<p>";
		
			// get each row
			while($row = mysqli_fetch_array($mysqli_result_d))
			{	
				$query_combo = "SELECT * FROM combo_5_59_";
				$query_combo .= "$r ";
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
				#$query_combo .= "AND   b1 <= '20' ";
				$query_combo .= "AND   comb5 = '0' ";
				$query_combo .= "AND   last_updated < '$row[date]' ";
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

					$total_combin = test_combin_count($draw_array);

					$query7 = "UPDATE combo_5_59_";
					$query7 .= "$r ";
					$query7 .= "SET   comb2 = $total_combin[2], ";
					$query7 .= "      comb3 = $total_combin[3], ";
					$query7 .= "      comb4 = $total_combin[4], ";
					#$query7 .= "      comb5 = $total_combin[5], ";
					$query7 .= "      last_updated = '$row[date]' ";
					$query7 .= "WHERE id = $row_combo[id] ";
					#$query7 .= "AND   b2 = $row_combo[b2] ";
					#$query7 .= "AND   b3 = $row_combo[b3] ";
					#$query7 .= "AND   b4 = $row_combo[b4] ";
					#$query7 .= "AND   b5 = $row_combo[b5] ";

					print "$query7<p>";

					#die();
					
					$mysqli_result7 = mysqli_query($query7, $mysqli_link) or die (mysqli_error($mysqli_link));
				}
			}
		}
		
	}

	function lot_update_combin_all ($update_date)
	{ 
		global $debug, $game, $game_name, $balls, $balls_drawn, $mega_balls, $draw_table_name, $draw_prefix, $game_includes;
	
		require ("includes/mysqli.php");

		require ("$game_includes/config.incl");
		
		print("<H2>Lotto Combo Count Update - $game_name</H2>\n");
	
		$dcount = 0;

		$curr_date = date("Y-m-d");
		$curr_date = '2013-07-27';

		$query_d = "SELECT * FROM combo_5_59 ";
		$query_d .= "WHERE last_updated < '2013-07-27' ";
		#$query_d .= "AND b1 = 11 ";
		$query_d .= "AND b1 <= 20 ";
		
		print "$query_d<p>";

		$mysqli_result_d = mysqli_query($query_d, $mysqli_link) or die (mysqli_error($mysqli_link));

		$num_rows_d = mysqli_num_rows($mysqli_result_d);

		echo "num_rows_d = $num_rows_d<p>";
	
		// get each row
		while($row = mysqli_fetch_array($mysqli_result_d))
		{	
			$total_combin = array();
			$draw_array = array(0);

			for ($v = 1; $v <= $balls_drawn; $v++)
			{
				array_push($draw_array, $row[$v]);
			}

			$total_combin = test_combin_count($draw_array);

			$query7 = "UPDATE combo_5_59 ";
			$query7 .= "SET   comb2 = $total_combin[2], ";
			$query7 .= "      comb3 = $total_combin[3], ";
			$query7 .= "      comb4 = $total_combin[4], ";
			#$query7 .= "      comb5 = $total_combin[5], ";
			$query7 .= "      last_updated = '$curr_date' ";
			$query7 .= "WHERE b1 = $row[b1] ";
			$query7 .= "AND   b2 = $row[b2] ";
			$query7 .= "AND   b3 = $row[b3] ";
			$query7 .= "AND   b4 = $row[b4] ";
			$query7 .= "AND   b5 = $row[b5] ";

			print "$query7<p>";
			
			$mysqli_result7 = mysqli_query($query7, $mysqli_link) or die (mysqli_error($mysqli_link));
		}
	}

	function lot_fix_combin_all_3 ($update_date)
	{ 
		global $debug, $game, $game_name, $balls, $balls_drawn, $mega_balls, $draw_table_name, $draw_prefix, $game_includes;
	
		require ("includes/mysqli.php");

		require ("$game_includes/config.incl");
		
		print("<H2>Lotto Combo 5 Update - $game_name</H2>\n");
	
		$dcount = 0;

		$curr_date = date("Y-m-d");
		#$curr_date = '2013-07-08';

		$query_d = "SELECT * FROM combo_5_59_";
		$query_d .= "$r ";
		
		print "$query_d<p>";

		$mysqli_result_d = mysqli_query($query_d, $mysqli_link) or die (mysqli_error($mysqli_link));

		$num_rows_d = mysqli_num_rows($mysqli_result_d);

		echo "num_rows_d = $num_rows_d<p>";
	
		// get each row
		while($row = mysqli_fetch_array($mysqli_result_d))
		{	
			$total_combin = array();
			$draw_array = array(0);

			for ($v = 1; $v <= $balls_drawn; $v++)
			{
				array_push($draw_array, $row[$v]);
			}

			$total_combin = test_combin_count($draw_array);

			$query7 = "UPDATE combo_5_59 ";
			$query7 .= "SET   comb2 = '10', ";
			$query7 .= "      comb3 = $total_combin[3], ";
			$query7 .= "      comb4 = $total_combin[4], ";
			#$query7 .= "      comb5 = $total_combin[5], ";
			$query7 .= "      last_updated = '$curr_date' ";
			$query7 .= "WHERE b1 = $row[b1] ";
			$query7 .= "AND   b2 = $row[b2] ";
			$query7 .= "AND   b3 = $row[b3] ";
			$query7 .= "AND   b4 = $row[b4] ";
			$query7 .= "AND   b5 = $row[b5] ";

			print "$query7<p>";
			
			$mysqli_result7 = mysqli_query($query7, $mysqli_link) or die (mysqli_error($mysqli_link));
		}
	}

	function lot_update_combin_3 ($update_date)
	{ 
		global $debug, $game, $game_name, $balls, $balls_drawn, $mega_balls, $draw_table_name, $draw_prefix, $game_includes;
	
		require ("includes/mysqli.php");

		require ("$game_includes/config.incl");
		
		print("<H2>Lotto Combo 3 Update - $game_name</H2>\n");
	
		$dcount = 0;

		$curr_date = date("Y-m-d");
		#$curr_date = '2012-07-10';

		for ($r = 1; $r <= 20; $r++)
		{
				$query_d = "SELECT * FROM pb_3_59 ";
				#$query_d .= "WHERE nums_count <=  '2' ";
				$query_d .= "WHERE date >= '$update_date' ";
				$query_d .= "ORDER BY date DESC ";

				print "$query_d<p>";

				$mysqli_result_d = mysqli_query($query_d, $mysqli_link) or die (mysqli_error($mysqli_link));

				$num_rows_d = mysqli_num_rows($mysqli_result_d);

				echo "num_rows_d = $num_rows_d<p>";

				// get each row
				while($row = mysqli_fetch_array($mysqli_result_d))
				{	
					$query_combo = "SELECT * FROM combo_5_59_";
					$query_combo .= "$r ";
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
					#$query_combo .= "AND   b1 <= '20' ";
					$query_combo .= "AND   comb5 = '0' ";
					$query_combo .= "AND   last_updated < '$row[date]' ";
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

						$total_combin = test_combin_count($draw_array);

						$query7 = "UPDATE combo_5_59_";
						$query7 .= "$r ";
						$query7 .= "SET   comb2 = $total_combin[2], ";
						$query7 .= "      comb3 = $total_combin[3], ";
						$query7 .= "      comb4 = $total_combin[4], ";
						#$query7 .= "      comb5 = $total_combin[5], ";
						$query7 .= "      last_updated = '$row[date]' ";
						$query7 .= "WHERE id = $row_combo[id] ";
						#$query7 .= "AND   b2 = $row_combo[b2] ";
						#$query7 .= "AND   b3 = $row_combo[b3] ";
						#$query7 .= "AND   b4 = $row_combo[b4] ";
						#$query7 .= "AND   b5 = $row_combo[b5] ";

						print "$query7<p>";

						#die();
						
						$mysqli_result7 = mysqli_query($query7, $mysqli_link) or die (mysqli_error($mysqli_link));
					}
				}

		}
	}

	function lot_update_combin_2 ($update_date)
	{ 
		global $debug, $game, $game_name, $balls, $balls_drawn, $mega_balls, $draw_table_name, $draw_prefix, $game_includes;
	
		require ("includes/mysqli.php");

		require ("$game_includes/config.incl");
		
		print("<H2>Lotto Combo 2 Update - $game_name</H2>\n");
	
		$dcount = 0;

		$curr_date = date("Y-m-d");
		#$curr_date = '2012-07-10';

		for ($r = 1; $r <= 20; $r++)
		{
			$query_d = "SELECT * FROM pb_2_59 ";
			#$query_d .= "WHERE nums_count <=  '2' ";
			$query_d .= "WHERE date >= '$update_date' ";
			$query_d .= "ORDER BY date DESC ";

			print "$query_d<p>";

			$mysqli_result_d = mysqli_query($query_d, $mysqli_link) or die (mysqli_error($mysqli_link));

			$num_rows_d = mysqli_num_rows($mysqli_result_d);

			echo "num_rows_d = $num_rows_d<p>";
		
			// get each row
			while($row = mysqli_fetch_array($mysqli_result_d))
			{	
				$query_combo = "SELECT * FROM combo_5_59_";
				$query_combo .= "$r ";
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
				#$query_combo .= "AND   b1 <= '20' ";
				$query_combo .= "AND   comb5 = '0' ";
				$query_combo .= "AND   last_updated < '$row[date]' ";
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

					$total_combin = test_combin_count($draw_array);

					$query7 = "UPDATE combo_5_59_";
					$query7 .= "$r ";
					$query7 .= "SET   comb2 = $total_combin[2], ";
					$query7 .= "      comb3 = $total_combin[3], ";
					$query7 .= "      comb4 = $total_combin[4], ";
					#$query7 .= "      comb5 = $total_combin[5], ";
					$query7 .= "      last_updated = '$row[date]' ";
					$query7 .= "WHERE id = $row_combo[id] ";
					#$query7 .= "AND   b2 = $row_combo[b2] ";
					#$query7 .= "AND   b3 = $row_combo[b3] ";
					#$query7 .= "AND   b4 = $row_combo[b4] ";
					#$query7 .= "AND   b5 = $row_combo[b5] ";

					print "$query7<p>";

					#die();
					
					$mysqli_result7 = mysqli_query($query7, $mysqli_link) or die (mysqli_error($mysqli_link));
				}
			}
		}
		
	}

	function lot_update_combin_5 ($update_date)
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

			if ($row_combo[b1] <= 20)
			{
				$query7 = "UPDATE combo_5_59_";
				$query7 .= "$row_combo[b1] ";
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
	}

	function lot_fix_2_59 ()
	{ 
		global $debug, $game, $game_name, $balls, $balls_drawn, $mega_balls, $draw_table_name, $draw_prefix, $game_includes;
	
		require ("includes/mysqli.php");

		$table_temp = $draw_prefix . "2_" . $balls;
		
		print("<H2>Lotto Combo 5 Update 2_59 - $game_name</H2>\n");
	
		$dcount = 0;

		$curr_date = date("Y-m-d");

		$query = "SELECT DISTINCT d1, d2 FROM $table_temp ";

		print "$query<p>";

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

		$num_rows_update = mysqli_num_rows($mysqli_result);

		echo "num_rows  = $num_rows_update<p>";

		while($row = mysqli_fetch_array($mysqli_result))
		{
			$query2 = "SELECT * FROM $table_temp ";
			$query2 .= "WHERE d1 = '$row[d1]' ";
			$query2 .= "AND   d2 = '$row[d2]' ";

			print "$query2<p>";

			$mysqli_result2 = mysqli_query($mysqli_link, $query2) or die (mysqli_error($mysqli_link));

			$num_rows2 = mysqli_num_rows($mysqli_result2);

			$query_update = "UPDATE $table_temp ";
			$query_update .= "SET nums_count = $num_rows2 ";
			$query_update .= "WHERE d1 = $row[d1] ";
			$query_update .= "AND d2 = $row[d2] ";

			print "$query_update<br>";

			$mysqli_result_update = mysqli_query ($mysqli_link, $query_update) or die (mysqli_error($mysqli_link));

			for ($x = 1; $x <= 10; $x++)
			{
				$query2 = "SELECT d1, d2, combo FROM $table_temp ";
				$query2 .= "WHERE d1 = $row[d1] "; 
				$query2 .= "AND d2 = $row[d2] "; 
				$query2 .= "AND combo = $x ";

				$mysqli_result2 = mysqli_query($mysqli_link, $query2) or die (mysqli_error($mysqli_link));

				$num_rows2 = mysqli_num_rows($mysqli_result2);

				$query_update = "UPDATE $table_temp ";
				$query_update .= "SET combo_count = $num_rows2 ";
				$query_update .= "WHERE d1 = $row[d1] ";
				$query_update .= "AND d2 = $row[d2] ";
				$query_update .= "AND combo = $x ";

				echo "$query_update<br>";

				$mysqli_result_update = mysqli_query ($mysqli_link, $query_update) or die (mysqli_error($mysqli_link));
			}
		}
	}

	function lot_fix_3_59 ()
	{ 
		global $debug, $game, $game_name, $balls, $balls_drawn, $mega_balls, $draw_table_name, $draw_prefix, $game_includes;
	
		require ("includes/mysqli.php");

		$table_temp = $draw_prefix . "3_" . $balls;
		
		print("<H2>Lotto Combo 5 Update 3_59 - $game_name</H2>\n");
	
		$dcount = 0;

		$curr_date = date("Y-m-d");

		$query = "SELECT DISTINCT d1, d2, d3 FROM $table_temp ";

		print "$query<p>";

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

		$num_rows_update = mysqli_num_rows($mysqli_result);

		echo "num_rows  = $num_rows_update<p>";

		// get each row
		while($row = mysqli_fetch_array($mysqli_result_d))
		{	
			$query_combo = "SELECT * FROM combo_5_59 ";
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
			$query_combo .= "AND   comb5 = '0' ";
			$query_combo .= "AND   last_updated < '$row[date]' ";
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

				$total_combin = test_combin_count($draw_array);

				$query7 = "UPDATE combo_5_59 ";
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

	function lot_fix_4_59 ()
	{ 
		global $debug, $game, $game_name, $balls, $balls_drawn, $mega_balls, $draw_table_name, $draw_prefix, $game_includes;
	
		require ("includes/mysqli.php");

		$table_temp = $draw_prefix . "4_" . $balls;
		
		print("<H2>Lotto Combo 5 Update 4_59 - $game_name</H2>\n");
	
		$dcount = 0;

		$curr_date = date("Y-m-d");

		$query = "SELECT DISTINCT d1, d2, d3, d4 FROM $table_temp ";

		print "$query<p>";

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

		$num_rows_update = mysqli_num_rows($mysqli_result);

		echo "num_rows  = $num_rows_update<p>";

		while($row = mysqli_fetch_array($mysqli_result))
		{
			$query2 = "SELECT * FROM $table_temp ";
			$query2 .= "WHERE d1 = '$row[d1]' ";
			$query2 .= "AND   d2 = '$row[d2]' ";
			$query2 .= "AND   d3 = '$row[d3]' ";
			$query2 .= "AND   d4 = '$row[d4]' ";

			print "$query2<p>";

			$mysqli_result2 = mysqli_query($mysqli_link, $query2) or die (mysqli_error($mysqli_link));

			$num_rows2 = mysqli_num_rows($mysqli_result2);

			$query_update = "UPDATE $table_temp ";
			$query_update .= "SET nums_count = $num_rows2 ";
			$query_update .= "WHERE d1 = $row[d1] ";
			$query_update .= "AND d2 = $row[d2] ";
			$query_update .= "AND d3 = $row[d3] ";
			$query_update .= "AND d4 = $row[d4] ";

			print "$query_update<br>";

			$mysqli_result_update = mysqli_query ($mysqli_link, $query_update) or die (mysqli_error($mysqli_link));

			for ($x = 1; $x <= 5; $x++)
			{
				$query2 = "SELECT d1, d2, d3, d4, combo FROM $table_temp ";
				$query2 .= "WHERE d1 = $row[d1] "; 
				$query2 .= "AND d2 = $row[d2] "; 
				$query2 .= "AND d3 = '$row[d3]' ";
				$query2 .= "AND d4 = '$row[d4]' ";
				$query2 .= "AND combo = $x ";

				$mysqli_result2 = mysqli_query($mysqli_link, $query2) or die (mysqli_error($mysqli_link));

				$num_rows2 = mysqli_num_rows($mysqli_result2);

				$query_update = "UPDATE $table_temp ";
				$query_update .= "SET combo_count = $num_rows2 ";
				$query_update .= "WHERE d1 = $row[d1] ";
				$query_update .= "AND d2 = $row[d2] ";
				$query_update .= "AND d3 = $row[d3] ";
				$query_update .= "AND d4 = $row[d4] ";
				$query_update .= "AND combo = $x ";

				echo "$query_update<br>";

				$mysqli_result_update = mysqli_query ($mysqli_link, $query_update) or die (mysqli_error($mysqli_link));
			}
		}
	}

	function lot_rebuild_3_59 ()
	{ 
		global $debug, $game, $game_name, $balls, $balls_drawn, $mega_balls, $draw_table_name, $draw_prefix, $game_includes;
	
		require ("includes/mysqli.php");

		$table_temp = $draw_prefix . "3_" . $balls;
		
		print("<H2>Lotto Combo 5 Rebuild 3_59 - $game_name</H2>\n");
	
		$dcount = 0;

		$curr_date = date("Y-m-d");

		$table_temp = $draw_prefix . "3_" . $balls;

		$query = "SELECT * From $draw_table_name ";
		$query .= "WHERE date > '2008-07-23' ";
		$query .= "ORDER BY date ASC ";
		
		print "$query<p>";

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

		$num_rows_update = mysqli_num_rows($mysqli_result);

		echo "num_rows  = $num_rows_update<p>";

		// get each row
		while($row = mysqli_fetch_array($mysqli_result))
		{	
			$temp_array = array_fill(0,4,0);

			for ($s = 1; $s <= $balls_drawn; $s++)
			{
				$temp_array[$s] = $row[$s];
			}
			
			sort ($temp_array);

			for ($s = 1; $s <= $balls_drawn; $s++)
			{
				$row[$s] = $temp_array[$s];
			}

			for ($c = 1; $c <= 10; $c++)
			{
				switch ($c) 
				{ 
				   case 1: 
					   $d1 = $row[1];
					   $d2 = $row[2];
					   $d3 = $row[3];
					   break; 
				   case 2: 
					   $d1 = $row[1];
					   $d2 = $row[2];
					   $d3 = $row[4];
					   break; 
				   case 3: 
					   $d1 = $row[1];
					   $d2 = $row[2];
					   $d3 = $row[5];
					   break; 
				   case 4: 
					   $d1 = $row[1];
					   $d2 = $row[3];
					   $d3 = $row[4];
					   break;
				   case 5: 
					   $d1 = $row[1];
					   $d2 = $row[3];
					   $d3 = $row[5];
					   break; 
				   case 6: 
					   $d1 = $row[1];
					   $d2 = $row[4];
					   $d3 = $row[5];
					   break;
				   case 7: 
					   $d1 = $row[2];
					   $d2 = $row[3];
					   $d3 = $row[4];
					   break;
				   case 8: 
					   $d1 = $row[2];
					   $d2 = $row[3];
					   $d3 = $row[5];
					   break;
				   case 9: 
					   $d1 = $row[2];
					   $d2 = $row[4];
					   $d3 = $row[5];
					   break;
				   case 10: 
					   $d1 = $row[3];
					   $d2 = $row[4];
					   $d3 = $row[5];
					   break;
				} 

				$sum = $d1 + $d2 + $d3;

				$draw_sum = $row[1] + $row[2] + $row[3] + $row[4] + $row[5]; 

				$query2 = "Insert INTO $table_temp ";
				$query2 .= "VALUES ( '0', ";
				$query2 .= "         '$row[0]', "; ########## 0
				$query2 .= "         $d1, ";
				$query2 .= "	     $d2, ";
				$query2 .= "	     $d3, ";
				$query2 .= "	     $sum, ";
				$query2 .= "	     $draw_sum, ";
				$query2 .= "	     $c, "; #combo position
				$query2 .= "	     '1', ";
				$query2 .= "	     '1') "; 

				print "$query2<p>";
			
				$mysqli_result2 = mysqli_query($mysqli_link, $query2) or die (mysqli_error($mysqli_link));

				$query2 = "SELECT d1, d2, d3 FROM $table_temp ";
				$query2 .= "WHERE d1 = $d1 "; 
				$query2 .= "AND d2 = $d2 "; 
				$query2 .= "AND d3 = $d3 ";

				print "$query2<p>";


				$mysqli_result2 = mysqli_query($mysqli_link, $query2) or die (mysqli_error($mysqli_link));

				$num_rows = mysqli_num_rows($mysqli_result2);

				$query_update = "UPDATE $table_temp ";
				$query_update .= "SET nums_count = $num_rows ";
				$query_update .= "WHERE d1 = $d1 "; 
				$query_update .= "AND d2 = $d2 ";
				$query_update .= "AND d3 = $d3 ";

				print "$query_update<p>";

				$mysqli_result_update = mysqli_query ($mysqli_link, $query_update) or die (mysqli_error($mysqli_link));

				$query2 = "SELECT d1, d2, d3, combo FROM $table_temp ";
				$query2 .= "WHERE d1 = $d1 "; 
				$query2 .= "AND d2 = $d2 "; 
				$query2 .= "AND d3 = $d3 "; 
				$query2 .= "AND combo = $c ";

				print "$query2<p>";

				$mysqli_result2 = mysqli_query($mysqli_link, $query2) or die (mysqli_error($mysqli_link));

				$num_rows = mysqli_num_rows($mysqli_result2);

				echo "num_rows = $num_rows<p>";
				
				$query_update = "UPDATE $table_temp ";
				$query_update .= "SET combo_count = $num_rows ";
				$query_update .= "WHERE d1 = $d1 ";
				$query_update .= "AND d2 = $d2 ";
				$query_update .= "AND d3 = $d3 ";
				$query_update .= "AND combo = $c ";

				echo "$query_update<p>";

				$mysqli_result_update = mysqli_query ($mysqli_link, $query_update) or die (mysqli_error($mysqli_link));

				echo "------------------------------------<br>";
			}
		}
	}

	//start HTML page
	print("<HTML>\n");
	print("<HEAD>\n");
	print("<TITLE>Lotto Update Comb 2 - $game_name - Combo</TITLE>\n");
	print("</HEAD>\n");
	
	print("<BODY>\n");

	$update_date = "2013-08-01";

	#lot_update_combin_all ($update_date);

	#die();
	
	lot_update_combin_5 ($update_date);

	lot_update_combin_4 ($update_date); # Georgia Fantasy 5

	lot_update_combin_3 ($update_date); # Georgia Fantasy 5
	
	lot_update_combin_2($update_date);

	#lot_update_combin_all ($update_date);

	#lot_fix_3_59 ();

	#lot_fix_4_59 ();

	#lot_rebuild_3_59 ();

	print("</BODY>\n");
?>
