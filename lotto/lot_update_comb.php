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

	function update_comb ()
	{ 
		global $debug, $game, $game_name, $balls, $balls_drawn, $mega_balls, $draw_table_name, $draw_prefix;
	
		require ("includes/mysqli.php");
		//require ("includes/db.class");

		$current_date = date("Y-m-d");
		
		print("<H2>Update SEQ - $game_name</H2>\n");
	
		$dcount = 0;

		//print("<h3>$table_temp</h3>");

		// get from draw table
		//$query5 = "SELECT * FROM combo";
		//$query5 .= "_$balls_drawn";
		//$query5 .= "_$balls ";
		//$query5 .= "WHERE b1 >= 4 ";

		$query_a = "SELECT * FROM fl_f5_filter_a ";
		$query_a .= "WHERE last_updated < '2008-1-18' ";

		print("<P>$querya<p>");
	
		$mysqli_result_a = mysqli_query($query_a, $mysqli_link) or die (mysqli_error($mysqli_link));
	
		// get each row
		while($row = mysqli_fetch_array($mysqli_result_a))
		{
			$comb2 = 0;
			$comb3 = 0;
			$comb4 = 0;
			$comb5 = 0;

			// count 2
			for ($c = 1; $c <= 10; $c++)
			{
				switch ($c) { 
				   case 1: 
					   $d1 = $row[b1];
					   $d2 = $row[b2];
					   break; 
				   case 2: 
					   $d1 = $row[b1];
					   $d2 = $row[b3];
					   break; 
				   case 3: 
					   $d1 = $row[b1];
					   $d2 = $row[b4];
					   break; 
				   case 4: 
					   $d1 = $row[b1];
					   $d2 = $row[b5];
					   break;
				   case 5: 
					   $d1 = $row[b2];
					   $d2 = $row[b3];
					   break;
				   case 6: 
					   $d1 = $row[b2];
					   $d2 = $row[b4];
					   break; 
				   case 7: 
					   $d1 = $row[b2];
					   $d2 = $row[b5];
					   break; 
				   case 8: 
					   $d1 = $row[b3];
					   $d2 = $row[b4];
					   break;
				   case 9: 
					   $d1 = $row[b3];
					   $d2 = $row[b5];
					   break;
				   case 10: 
					   $d1 = $row[b4];
					   $d2 = $row[b5];
					   break;
				} 

				$query2 = "SELECT DISTINCT * FROM fl_f5_2_36 ";
				$query2 .= "WHERE d1 = $d1 ";
				$query2 .= "  AND d2 = $d2 ";
				//$query2 .= "  AND date < '$row[date]' ";

				$mysqli_result2 = mysqli_query($mysqli_link, $query2) or die (mysqli_error($mysqli_link));
				
				if ($num_rows = mysqli_num_rows($mysqli_result2))
				{
					$comb2++;
				}
			}

			// count 3
			for ($c = 1; $c <= 10; $c++)
			{
				switch ($c) { 
				   case 1: 
					   $d1 = $row[b1];
					   $d2 = $row[b2];
					   $d3 = $row[b3];
					   break; 
				   case 2: 
					   $d1 = $row[b1];
					   $d2 = $row[b2];
					   $d3 = $row[b4];
					   break; 
				   case 3: 
					   $d1 = $row[b1];
					   $d2 = $row[b2];
					   $d3 = $row[b5];
					   break; 
				   case 4: 
					   $d1 = $row[b1];
					   $d2 = $row[b3];
					   $d3 = $row[b4];
					   break;
				   case 5: 
					   $d1 = $row[b1];
					   $d2 = $row[b3];
					   $d3 = $row[b5];
					   break;
				   case 6: 
					   $d1 = $row[b1];
					   $d2 = $row[b4];
					   $d3 = $row[b5];
					   break; 
				   case 7: 
					   $d1 = $row[b2];
					   $d2 = $row[b3];
					   $d3 = $row[b4];
					   break;
				   case 8: 
					   $d1 = $row[b2];
					   $d2 = $row[b3];
					   $d3 = $row[b5];
					   break;
				   case 9: 
					   $d1 = $row[b2];
					   $d2 = $row[b4];
					   $d3 = $row[b5];
					   break;
				   case 10: 
					   $d1 = $row[b3];
					   $d2 = $row[b4];
					   $d3 = $row[b5];
					   break;
				} 

				$query3 = "SELECT DISTINCT * FROM fl_f5_3_36 ";
				$query3 .= "WHERE d1 = $d1 ";
				$query3 .= "  AND d2 = $d2 ";
				$query3 .= "  AND d3 = $d3 ";
				//$query3 .= "  AND date < '$row[date]' ";

				$mysqli_result3 = mysqli_query($mysqli_link, $query3) or die (mysqli_error($mysqli_link));
				
				if ($num_rows = mysqli_num_rows($mysqli_result3))
				{
					$comb3++;
				}
			}

			// count 4
			for ($c = 1; $c <= 5; $c++)
			{
				switch ($c) { 
				   case 1: 
					   $d1 = $row[b1];
					   $d2 = $row[b2];
					   $d3 = $row[b3];
					   $d4 = $row[b4];
					   break; 
				   case 2: 
					   $d1 = $row[b1];
					   $d2 = $row[b2];
					   $d3 = $row[b3];
					   $d4 = $row[b5];
					   break; 
				   case 3: 
					   $d1 = $row[b1];
					   $d2 = $row[b2];
					   $d3 = $row[b4];
					   $d4 = $row[b5];
					   break;
				   case 4: 
					   $d1 = $row[b1];
					   $d2 = $row[b3];
					   $d3 = $row[b4];
					   $d4 = $row[b5];
					   break; 
				   case 5: 
					   $d1 = $row[b2];
					   $d2 = $row[b3];
					   $d3 = $row[b4];
					   $d4 = $row[b5];
					   break;
				} 

				$query4 = "SELECT DISTINCT date, d1, d2, d3, d4 FROM fl_f5_4_36 ";
				$query4 .= "WHERE d1 = $d1 ";
				$query4 .= "  AND d2 = $d2 ";
				$query4 .= "  AND d3 = $d3 ";
				$query4 .= "  AND d4 = $d4 ";
				//$query4 .= "  AND date < '$row[date]' ";

				$mysqli_result4 = mysqli_query($mysqli_link, $query4) or die (mysqli_error($mysqli_link));
				
				if ($num_rows = mysqli_num_rows($mysqli_result4))
				{
					$comb4++;
				}
			}

			// count 5
			$query5 = "SELECT * FROM fl_f5_draws ";
			$query5 .= "WHERE b1 = $row[b1] ";
			$query5 .= "  AND b2 = $row[b2] ";
			$query5 .= "  AND b3 = $row[b3] ";
			$query5 .= "  AND b4 = $row[b4] ";
			$query5 .= "  AND b5 = $row[b5] ";
			//$query5 .= "  AND date < '$row[bdate]' ";

			$mysqli_result5 = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));
			
			$comb5 = mysqli_num_rows($mysqli_result5);

			//$query6 = "UPDATE combo";
			//$query6 .= "_$balls_drawn";
			//$query6 .= "_$balls ";
			$query6 = "UPDATE fl_f5_filter_a ";
			$query6 .= "SET comb2 = $comb2, comb3 = $comb3, ";
			$query6 .= "    comb4 = $comb4, comb5 = $comb5, ";
			$query6 .= "    last_updated = '$current_date' ";
			$query6 .= "WHERE b1 =  $row[b1] AND ";
			$query6 .= "b2 =  $row[b2] AND ";
			$query6 .= "b3 =  $row[b3] AND ";
			$query6 .= "b4 =  $row[b4] AND ";
			$query6 .= "b5 =  $row[b5] ";
			print "<p>$query6<p>";
			$mysqli_result6 = mysqli_query($mysqli_link, $query6) or die (mysqli_error($mysqli_link));

			$dcount++;
		}
		print("<P>$dcount Updated<p>");
	}


	//start HTML page
	print("<HTML>\n");
	print("<HEAD>\n");
	print("<TITLE>Lotto Filter A - update combin - $game_name</TITLE>\n");
	print("</HEAD>\n");
	
	print("<BODY>\n");

	update_comb ();

	print("</BODY>\n");
?>
