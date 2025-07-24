<?php

	// Game ----------------------------- Game
	$game = 1; // Georgia Fantasy 5
	// Game ----------------------------- Game

	set_time_limit(0);
	
	require ("includes/games_switch.incl");

	require ("includes/mysqli.php");
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

	function lot_update_combo_5_42()
	{ 
		global $debug, $game, $game_name, $balls, $balls_drawn, $mega_balls, $draw_table_name, $draw_prefix, $game_includes;
	
		require ("includes/mysqli.php");

		require ("$game_includes/config.incl");
		
		print("<H2>Lotto Filter D Combin Update - $game_name</H2>\n");
	
		$dcount = 0;

		$currdate = date('ymd');
		$curr_date = date("Y-m-d");
		
		$temp_table1 = 'temp_cover_1k_count_' .  $currdate;

		#$query_d = "SELECT DISTINCT sum,even,odd,k_count FROM $temp_table1 ";
		$query_d = "SELECT * FROM ga_f5_sum_count_sum ";
		#$query_d .= "WHERE last_updated < '$curr_date' ";
		$query_d .= "ORDER BY `percent_wa` DESC  ";

		echo "<p>$query_d</p>";

		$mysqli_result_d = mysqli_query($mysqli_link, $query_d) or die (mysqli_error($mysqli_link));

		$num_rows_d = mysqli_num_rows($mysqli_result_d);

		echo "<h2>$num_rows_d left to update from $temp_table1</h2>";

		$count = 0;
		
		// get each row
		while($row_d = mysqli_fetch_array($mysqli_result_d))
		{	
			$query8 = "SELECT * FROM combo_5_42 ";
			$query8 .= "WHERE sum  = $row_d[numx] ";
			$query8 .= "AND   even = $row_d[even] ";
			$query8 .= "AND   odd  = $row_d[odd] ";
			$query8 .= "AND   seq2 <= 1 AND seq3 = 0 AND mod_tot <= 1 AND mod_x = 0 ";
			#$query8 .= "AND   last_updated < '$curr_date' ";	### <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
			$query8 .= "AND   last_updated < '2024-01-01' ";	### <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
			$query8 .= "ORDER BY id ASC ";

			$mysqli_result8 = mysqli_query($mysqli_link, $query8) or die (mysqli_error($mysqli_link));

			$num_rows8 = mysqli_num_rows($mysqli_result8);

			echo "<h2>$num_rows8 to update in combo_5_42</h2>";

			// get each row
			while($row8 = mysqli_fetch_array($mysqli_result8))
			{
				$total_combin = array();
				$draw_array = array(0);

				for ($v = 1; $v <= $balls_drawn; $v++)
				{
					array_push($draw_array, $row8[$v]);
				}

				$total_combin = test_combin($draw_array);

				$query7 = "UPDATE combo_5_42 ";
				$query7 .= "SET   comb2 = $total_combin[2], ";
				$query7 .= "      comb3 = $total_combin[3], ";
				$query7 .= "      comb4 = $total_combin[4], ";
				$query7 .= "      comb5 = $total_combin[5], ";
				$query7 .= "      last_updated = '$curr_date' ";
				$query7 .= "WHERE id = $row8[id] ";
				
				$mysqli_result7 = mysqli_query($mysqli_link, $query7) or die (mysqli_error($mysqli_link));

				if ($count > 500)
				{
					print "$query7<p>";
					$count = 0;
				} else {
					$count++;
				}
			}
		}
	}

	//start HTML page
	print("<HTML>\n");
	print("<HEAD>\n");
	print("<TITLE>Lotto Update Combo 5/42 - $game_name</TITLE>\n");
	print("</HEAD>\n");
	
	print("<BODY>\n");

	lot_update_combo_5_42 (); # Georgia Fantasy 5	###240329

	print("<h2>Completed!</h2>");

	print("</BODY>\n");
?>
