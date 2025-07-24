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
	require ("includes/build_rank_table.php");
	require ("includes/calculate_draw.php"); 
	require ("includes/calculate_rank.php");
	require ("includes/next_draw.php");
	require ("includes/count_2_seq.php");
	require ("includes/count_3_seq.php");
	require ("includes_ga_f5/last_draws_ga_f5.php");
	require ("includes_ga_f5/combin.incl");
	require ("includes/mysqli.php");
	
	$debug = 0;

	function lot_update_drange2_wa ($id, $draw)
	{ 
		global $debug, $game, $game_name, $balls, $balls_drawn, $mega_balls, $draw_table_name, $draw_prefix, $game_includes;
	
		require ("includes/mysqli.php");

		$query4 = "SELECT * FROM $draw_prefix";
		$query4 .= "draws_draw2 ";
		$query4 .= "WHERE id = '$id' ";

		#print("$query4<br>");
		
		$mysqli_result4 = mysqli_query($mysqli_link, $query4) or die (mysqli_error($mysqli_link));

		$num_rows4 = mysqli_num_rows($mysqli_result4);

		if (!$num_rows4)
		{
			#calculate_drange2($draw,$d1,$d2,$balls);

			$d1 = 0;
			$d2 = 0; 
			#$range1 = intval($max_draw*0.50);

			$range1 = intval(42*0.5);

			reset ($draw); 
		
			foreach ($draw as $val) 
			{ 
				#echo "val = $val<br>";
				if ($val > $range1) {
					$d2++;
				} else {
					$d1++;
				}
				#echo "d1 = $d1<br>";
				#echo "d2 = $d2<br>";
			} 

			$query4 = "INSERT INTO $draw_prefix";
			$query4 .= "draws_draw2 ";
			$query4 .= "VALUES('0', ";
			$query4 .= "'$row[id]', ";
			$query4 .= "'$d1', ";
			$query4 .= "'$d2') ";

			#print("$query4<br>");

			$mysqli_result4 = mysqli_query($mysqli_link, $query4) or die (mysqli_error($mysqli_link)); 

			print("<FONT COLOR=RED><H4>*** drange2 $row[0] updated ***</H4></FONT>\n");
		}
	}

	//start HTML page
	print("<HTML>\n");
	print("<HEAD>\n");
	print("<TITLE>Lotto Update Drange2 - $game_name - Rank Limit</TITLE>\n");
	print("</HEAD>\n");
	
	print("<BODY>\n");

	### get count draw table
	$query5 = "SELECT * FROM $draw_table_name ";
	$query5 .= "WHERE date >= '2015-10-01' ";

	#echo "$query5<br>";

	$mysqli_result5 = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));

	while ($row5 = mysqli_fetch_array($mysqli_result5))
	{
		lot_update_drange2_wa ($row5[id]);
	}

	print("<h2>Completed!</h2>");

	print("</BODY>\n");
?>
