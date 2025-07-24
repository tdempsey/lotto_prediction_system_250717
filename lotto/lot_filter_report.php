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
	require ("includes_ga_f5/last_draws_ga_f5.php");
	require ("includes_ga_f5/combin.incl");
	require ("includes/mysqli.php");
	
	$debug = 0;

	function lot_filter_summary ($limit)
	{ 
		global $debug, $game, $game_name, $balls, $balls_drawn, $mega_balls, $draw_table_name, $draw_prefix, $combo_table;
	
		require ("includes/mysqli.php");

		echo "<h3>combo_$balls_draw_$balls</h3>";

		for ($x = 1; $x <= $limit; $x++)
		{
			$query5 = "SELECT * FROM combo";
			$query5 .= "_$balls_drawn";
			$query5 .= "_$balls";
			if ($x < 10)
			{
				#$query5 .= "_0$x ";
			} else {
				#$query5 .= "_$x ";
			}
			$query5 .= " WHERE b1 = $x ";

			#echo "$query5<br>";
		
			$mysqli_result5 = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));

			$num_rows_all = mysqli_num_rows($mysqli_result5);
			
			echo "$x = $num_rows_all<br>";
		}

	}

	//start HTML page
	print("<HTML>\n");
	print("<HEAD>\n");
	print("<TITLE>Georgia Fantasy 5 Filter A - $game_name</TITLE>\n");
	print("</HEAD>\n");
	
	print("<BODY>\n");

	$next_draw_date = findNextDrawDateDash($game);

	echo "Next draw = $next_draw_date<p>";


	for ($x = 4; $x <= 4; $x++)
	{
		lot_filter_summary (10);
	}

	print("</BODY>\n");
?>
