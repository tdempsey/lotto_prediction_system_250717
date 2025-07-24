<?php

	// add tables population for combos, pairs
	// add test for missing draws
	// recalcu;ate draw includes
	// add db class
	// error checking - all modules
	// fix pair count

	// Game ----------------------------- Game
	//$game = 1; // Georgia Fantasy 5
	//$game = 2; // Georgia Mega Millions
	//$game = 3; // Georgia Lotto South
	$game = 4; // Florida Fantasy 5
	//$game = 5; // Florida Mega Money
	//$game = 6; // Florida Lotto
	//$game = 7; // Powerball
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
	
	$debug = 0;

	function update_seq ()
	{ 
		global $debug, $game, $game_name, $balls, $balls_drawn, $mega_balls, $draw_table_name, $draw_prefix;
	
		require ("includes/mysqli.php");
		//require ("includes/db.class");
		
		print("<H2>Update SEQ - $game_name</H2>\n");
	
		$dcount = 0;

		//print("<h3>$table_temp</h3>");

		// get from draw table
		$query5 = "SELECT * FROM combo";
		$query5 .= "_$balls_drawn";
		$query5 .= "_$balls ";
		$query5 .= "WHERE b1 >= 4 ";

		print("<P>$query5<p>");
	
		$mysqli_result5 = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));
	
		// get each row
		while($row = mysqli_fetch_array($mysqli_result5))
		{
			// build draw array [0..x]
			$draw = array ();
	
			for ($x = 0; $x < $balls_drawn; $x++)
			{
				array_push($draw, $row[$x]);
			}

			$seq2 = Count2Seq($draw);
			$seq3 = Count3Seq($draw);

			$query6 = "UPDATE combo";
			$query6 .= "_$balls_drawn";
			$query6 .= "_$balls ";
			$query6 .= "SET seq2 = $seq2, seq3 = $seq3 ";
			$query6 .= "WHERE b1 =  $row[b1] AND ";
			$query6 .= "b2 =  $row[b2] AND ";
			$query6 .= "b3 =  $row[b3] AND ";
			$query6 .= "b4 =  $row[b4] AND ";
			$query6 .= "b5 =  $row[b5] ";
			//print "<p>$query6<p>";
			$mysqli_result6 = mysqli_query($query6, $mysqli_link) or die (mysqli_error($mysqli_link));

			$dcount++;
		}
		print("<P>$dcount Updated<p>");
	}


	//start HTML page
	print("<HTML>\n");
	print("<HEAD>\n");
	print("<TITLE>Lotto Filter A - $game_name</TITLE>\n");
	print("</HEAD>\n");
	
	print("<BODY>\n");

	update_seq ();

	print("</BODY>\n");
?>
