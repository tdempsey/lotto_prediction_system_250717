<?php

	// Game ----------------------------- Game
	$game = 1; // Georgia Fantasy 5
	#$game = 2; // Mega Millions
	//$game = 3; // Georgia 5
	#$game = 4; // Florida Fantasy 5
	//$game = 5; // Florida Mega Money
	#$game = 6; // Florida Lotto
	#$game = 7; // Powerball
	#$game = 10; // All or Nothing
	#$game = 20; // All or Nothing Negative
	// Game ----------------------------- Game

	$hml = 0;
	#$hml = 1;		#= high
	#$hml = 2;		#= medium
	#$hml = 3;		#= low
	#$hml = 4;		#= min
	#$hml = 5;		#= max
	$hml = 100;

	# add EO50 limit

	$col1_select = 0;
	
	require ("includes/games_switch.incl");
	require ("includes/even_odd.php");
	require ("includes/last_draws.php");
	require ("includes/calculate_rank.php");
	require ("includes/look_up_rank.php"); 
	if ($game == 10 OR $game == 20)
	{
		require ("includes_aon/build_rank_table_aon.php");
	} else {
		require ("includes/build_rank_table.php");
	}
	require ("includes/test_column_lookup.php");
	require ("includes/calculate_rank_mb.php");
	require ("includes/next_draw.php");
	require ("includes/number_due.php");
	require ("includes/first_draw_unix.php");
	require ("includes/last_draw_unix.php"); 
	require ("includes/count_2_seq.php");
	require ("includes/count_3_seq.php");
	require ("includes/mysqli.php"); 
	require ("$game_includes/combin.incl");

	require ("includes/limit_functions.incl");

	date_default_timezone_set('America/New_York');

	echo "<b>col1_select = $col1_select in lot_display.php</b><p>";

	require_once ("includes/hml_switch.incl");	

	$debug = 0;
	
	// ----------------------------------------------------------------------------------
	function print_table()
	{
		global $draw_table_name, $balls, $balls_drawn, $game_includes, $draw_prefix, $game_includes, $hml, $range_low, $range_high, $col1_select, $curr_date; 

		require ("includes/mysqli.php"); 

		require ("$game_includes/config.incl");

		$rank_limit = array_fill (0,7,0);

		for ($x = 0; $x <= 5; $x++)
		{
			$query = "SELECT count(*) FROM ga_f5_temp_26 ";
			$query .= "WHERE count = $x ";

			echo "$query<br>";

			$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

			$row_count = mysqli_fetch_array($mysqli_result);

			if ($row_count[0] <= 3)
			{
				$rank_limit[$x] = 1;
			#} elseif ($row_count[0] > 3) {
			#	$rank_limit[$x] = 3;
			} else {
				$rank_limit[$x] = intval($row_count[0]/3);
			}
		}

		$query = "SELECT count(*) FROM ga_f5_temp_26 ";
		$query .= "WHERE count >= 6 ";

		echo "$query<br>";

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

		$row_count = mysqli_fetch_array($mysqli_result);

		if ($row_count[0] <= 3)
		{
			$rank_limit[6] = 1;
		} else {
			$rank_limit[6] = intval($row_count[0]/3);
		}

		$hml9 = $hml + 9;

		### combo ###
		$sql7 = "SELECT * FROM `ga_f5_combo_count_5_39_$hml` WHERE count >= 5 ORDER BY `count` DESC LIMIT 4 ";

		echo "$sql7<br>";

		$mysqli_result7 = mysqli_query($sql7, $mysqli_link) or die (mysqli_error($mysqli_link));

		while($row7 = mysqli_fetch_array($mysqli_result7))
		{
			### draw ###
			$sql_draw = "SELECT * FROM `ga_f5_draw_count_5_39_$hml` WHERE `count` >= 6 ORDER BY `count` DESC  ";

			#echo "$sql_draw<br>";

			$mysqli_result_draw = mysqli_query($sql_draw, $mysqli_link) or die (mysqli_error($mysqli_link));

			while($row_draw = mysqli_fetch_array($mysqli_result_draw))
			{
				## dup ###
				$sql = "SELECT * FROM ga_f5_dup_5_39_$hml WHERE `dup1` <= 1 AND `dup2` <= 2 AND `dup3` <= 2 AND `count` > 0 ORDER BY `count` DESC LIMIT 5 ";

				#echo "$sql<br>";

				$mysqli_result_dup = mysqli_query($sql, $mysqli_link) or die (mysqli_error($mysqli_link));

				#echo "rows = $num_rows8<br>";

				while($row = mysqli_fetch_array($mysqli_result_dup))
				{	
					$query_wheel = "SELECT * FROM `ga_f5_wheel_sum_table` WHERE `sum` >= $hml AND `sum` <= $hml9 AND `wa` >= 0.2 ORDER BY `month6` DESC LIMIT 5 ";

					print "$query_wheel<br>";

					$mysqli_result_wheel = mysqli_query($query_wheel, $mysqli_link) or die (mysqli_error($mysqli_link));

					$num_rows_wheel = mysqli_num_rows($mysqli_result_wheel);

					echo "num_rows_wheel = $num_rows_wheel<br>";

					while($row_wheel = mysqli_fetch_array($mysqli_result_wheel))
					{
						echo "<strong>$row_wheel[sum],$row_wheel[even],$row_wheel[odd],$row_wheel[d501],$row_wheel[d502] - year1 count = $row_wheel[year1]</strong><br>";

						$query_comb12 = "SELECT * FROM combo_5_39 ";
						$query_comb12 .= "WHERE ";
						$query_comb12 .= "sum = $row_wheel[sum] ";
						$query_comb12 .= " AND even = $row_wheel[even] ";
						$query_comb12 .= " AND odd = $row_wheel[odd] ";
						$query_comb12 .= " AND d2_1 = $row_wheel[d501] ";
						$query_comb12 .= " AND d2_2 = $row_wheel[d502] ";
						$query_comb12 .= "AND d0 = $row_draw[draw0] ";
						$query_comb12 .= "AND d1 = $row_draw[draw1] ";
						$query_comb12 .= "AND d2 = $row_draw[draw2] ";
						$query_comb12 .= "AND d3 = $row_draw[draw3] ";
						$query_comb12 .= "AND   dup1 = $row[dup1] ";
						$query_comb12 .= "AND   dup2 = $row[dup2] ";
						$query_comb12 .= "AND   dup3 = $row[dup3] ";
						
						#for ($g = 4; $g <= 25; $g++)
						/*
						for ($g = 4; $g <= 10; $g++)
						{
							$query_comb12 .= "AND   dup";
							$query_comb12 .= "$g >= {$dup_range_all[$g][0]} ";
							$query_comb12 .= "AND   dup";
							$query_comb12 .= "$g <= {$dup_range_all[$g][1]} ";
						}
						*/
						$query_comb12 .= "AND   dup20 = 5 ";
						 
						$query_comb12 .= "AND comb2 = $row7[combo2] ";
						$query_comb12 .= "AND comb3 = $row7[combo3] ";
						$query_comb12 .= "AND comb4 = $row7[combo4] ";
						$query_comb12 .= "AND comb5 = $row7[combo5] ";
						$query_comb12 .= "AND seq2 <= 1 ";
						$query_comb12 .= "AND mod_tot <= 1 ";
						$query_comb12 .= "AND mod_x = 0 ";
						#$query_comb12 .= "AND   b1 > 1 ";
						#$query_comb12 .= "AND   b1 = $col1_select ";
						#$query_comb12 .= "AND   b3 = $col3_select ";
						$query_comb12 .= "AND   combo_total_3 <= 1 ";
						#$query_comb12 .= "AND   combo_total_3 <= 2 ";
						$query_comb12 .= "AND   combo_total_4 = 0 ";
						#$query_comb12 .= "AND   combo_total_4 <= 1 ";
						
						$query_comb12 .= "AND   rank0 <= $rank_limit[0] ";
						$query_comb12 .= "AND   rank1 <= $rank_limit[1] ";
						$query_comb12 .= "AND   rank2 <= $rank_limit[2] ";
						$query_comb12 .= "AND   rank3 <= $rank_limit[3] ";
						$query_comb12 .= "AND   rank4 <= $rank_limit[4] ";
						$query_comb12 .= "AND   rank5 <= $rank_limit[5] ";
						$query_comb12 .= "AND   rank6 <= $rank_limit[6] ";

						$query_comb12 .= "AND   last_updated = '$curr_date' ";
						
						$query_comb12 .= "ORDER BY m6_sum DESC ";

						echo "$query_comb12<p>";

						$mysqli_result_col12 = mysqli_query($query_comb12, $mysqli_link) or die (mysqli_error($mysqli_link));

						$num_rows_col12 = mysqli_num_rows($mysqli_result_col12);
					} 
				} #dup
			} #draw
		} #combo
		
	}


	

	// ------------------------------------------------------------------------
	//start HTML page
	print("<HTML>\n");
	print("<HEAD>\n");
	print("<TITLE>$game_name Deconstruct</TITLE>\n");
	print("</HEAD>\n");
	
	print("<BODY>\n");
	print("<H1>$game_name Analyze</H1>\n");
	
	$curr_date = date("Y-m-d");
	$next_draw_Ymd = findNextDrawDate($game);

	print_table(31);

	require ("includes/unix.incl");


	//close page
	print("</BODY>\n");
	print("</HTML>\n");

?>
