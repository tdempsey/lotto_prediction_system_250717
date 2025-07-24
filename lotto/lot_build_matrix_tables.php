<?php

	// Game ----------------------------- Game
	//$game = 1; // Georgia Fantasy 5
	#$game = 2; // Mega Millions
	//$game = 3; // Georgia Lotto South
	$game = 4; // Florida Fantasy 5
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
	require ("includes/first_draw_unix.php");
	require ("includes/last_draw_unix.php"); 
	require ("includes/next_draw.php");
	
	$debug = 0;

	function lot_matrix_tables ($balls_drawn,$limit)
	{ 
		global $debug, $game, $game_name, $balls, $balls_drawn, $mega_balls, $draw_table_name, $draw_prefix, $game_includes;
	
		require ("includes/mysqli.php");
		require ("$game_includes/config.incl");

		#no
		if ($balls_drawn = 4)
		{

		} elseif ($balls_drawn = 5) {

		} elseif ($balls_drawn = 6) {

		}

		$temp = array_fill(0,6,0);
		$matrix_table = array_fill(0,6,$temp);

		#5 012345
		#4 012345
		#3 012345
		#2 012345
		#1 012345
		#0 012345

		$row0 = array ();
		$row1 = array ();
		$row2 = array ();
		$row3 = array ();
		$row4 = array ();
		$row5 = array ();

		for ($x = 0; $x < $balls_drawn; $x++)
		{
			$row0[$x] = $x + 1;
			$row1[$x] = $x + 1;
			$row2[$x] = $x + 1;
			$row3[$x] = $x + 1;
			$row4[$x] = $x + 1;
			$row5[$x] = $x + 1;
		}

		# row0
		shuffle $row1;

		$shuffled = 0;

		# row1
		do {
			$shuffled = 1;
			shuffle $row2;

			for ($x = 0; $x < $balls_drawn; $x++)
			{
				if ($row0[$x] == $row2[$x])
				{
					$shuffled = 0;
					break;
				}
			} 
		} while (!$shuffled);

		# row2

		# row3
		if ($balls_drawn <= 4)
		{

		}

		# row4
		if ($balls_drawn <= 5)
		{

		}
		
		# row5
		if ($balls_drawn <= 6)
		{

		}
	}
	
	//start HTML page
	print("<HTML>\n");
	print("<HEAD>\n");
	print("<TITLE>$game_name Build Matrix Tables - $game_name</TITLE>\n");
	print("</HEAD>\n");
	
	print("<BODY>\n");

	#parameter = numbers of tables
	lot_matrix_tables (5,100);
	
	print("</BODY>\n");
?>
