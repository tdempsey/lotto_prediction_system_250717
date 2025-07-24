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

	set_time_limit(0);
	
	require ("includes/games_switch.incl");

	//require ("includes/mysqli.php");
	require ("includes/mysqli.php");
	
	$debug = 0;

	function lot_update_drange8 ($date, $draw)
	{ 
		global $debug;
	
		require ("includes/mysqli.php");

		$query4 = "SELECT * FROM ga_f5_draws_draw8 ";
		$query4 .= "WHERE draw_date = '$date' ";

		#print("$query4<br>");
		
		$mysqli_result4 = mysqli_query($mysqli_link, $query4) or die (mysqli_error($mysqli_link));

		$num_rows4 = mysqli_num_rows($mysqli_result4);

		if (!$num_rows4)
		{
			$d1 = 0;
			$d2 = 0; 
			$d3 = 0;
			$d4 = 0; 
			$d5 = 0;
			$d6 = 0; 
			$d7 = 0;
			$d8 = 0; 
			$d9 = 0;
			$d10 = 0; 

			$range1 = intval(42/8);
			$range2 = intval((42/8)*2);
			$range3 = intval((42/8)*3);
			$range4 = intval((42/8)*4);
			$range5 = intval((42/8)*5);
			$range6 = intval((42/8)*6);
			$range7 = intval((42/8)*7);

			reset ($draw); 
		
			foreach ($draw as $val) 
			{ 
				if ($val > $range7) {
					$d8++;
				} elseif ($val > $range6) {
					$d7++;
				} elseif ($val > $range5) {
					$d6++;
				} elseif ($val > $range4) {
					$d5++;
				} elseif ($val > $range3) {
					$d4++;
				} elseif ($val > $range2) {
					$d3++;
				} elseif ($val > $range1) {
					$d2++;
				} else {
					$d1++;
				}
			} 

			$query4 = "INSERT INTO ga_f5_draws_draw8 ";
			$query4 .= "VALUES('0', ";
			$query4 .= "'$date', ";
			$query4 .= "'$d1', ";
			$query4 .= "'$d2', ";
			$query4 .= "'$d3', ";
			$query4 .= "'$d4', ";
			$query4 .= "'$d5', ";
			$query4 .= "'$d6', ";
			$query4 .= "'$d7', ";
			#$query4 .= "'$d8', ";
			#$query4 .= "'$d9', ";
			$query4 .= "'$d8') ";

			#print("$query4<br>");

			$mysqli_result4 = mysqli_query($mysqli_link, $query4) or die (mysqli_error($mysqli_link)); 
		}
	}

	//start HTML page
	print("<HTML>\n");
	print("<HEAD>\n");
	print("<TITLE>Lotto Update Drange8 - $game_name - Rank Limit</TITLE>\n");
	print("</HEAD>\n");
	
	print("<BODY>\n");

	### get count draw table
	$query5 = "SELECT * FROM $draw_table_name ";
	$query5 .= "WHERE date >= '2015-10-01' ";
	$query5 .= "ORDER BY date DESC ";
	#$query5 .= "LIMIT 25 ";

	echo "$query5<br>";

	$mysqli_result5 = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));

	while ($row5 = mysqli_fetch_array($mysqli_result5))
	{
		$draw = array ();

		for ($x = 1; $x <= $balls_drawn; $x++)
		{
			array_push($draw, $row5[$x]);
		}

		#print_r ($row5);

		lot_update_drange8 ($row5[0], $draw); ### 201223
	}

	print("<h2>Completed!</h2>");

	print("</BODY>\n");
?>
