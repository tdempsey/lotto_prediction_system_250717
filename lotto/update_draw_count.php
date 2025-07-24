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
	require ("includes/mysqli.php");
	
	$debug = 0;

	function calculate_draw_count($draw) 
	{  
		global $debug, $game;

		$draw_count = array_fill (0, 8, 0);
		
		for ($index = 0; $index <= 4; $index++) ### 210107
		{
			$val = $draw[$index];

			if ($val < 10) {
				$draw_count[0]++;
			} 
			elseif ($val > 9 && $val < 20) {
				$draw_count[1]++;
			}
			elseif ($val > 19 && $val < 30) {
				$draw_count[2]++;
			}
			elseif ($val > 29 && $val < 40) {
				$draw_count[3]++;
			}
			elseif ($val > 39 && $val < 50) {
				$draw_count[4]++;
			}
			elseif ($val > 49 && $val < 60) {
				$draw_count[5]++;
			}
			elseif ($val > 59 && $val < 70) {
				$draw_count[6]++;
			}
			else {
				$draw_count[7]++;
			}
		} 

		//print_r ($draw_count);
		  
		return $draw_count;
	}

	//start HTML page
	print("<HTML>\n");
	print("<HEAD>\n");
	print("<TITLE>Lotto Update Draw Count - $game_name - Rank Limit</TITLE>\n");
	print("</HEAD>\n");
	
	print("<BODY>\n");

	### get count draw table
	$query5 = "SELECT * FROM $draw_table_name ";
	$query5 .= "ORDER BY date ASC ";

	echo "$query5<br>";

	$mysqli_result5 = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));

	while ($row5 = mysqli_fetch_array($mysqli_result5))
	{
		### get count draw table
		$query5 = "SELECT * FROM ga_f5_draw_count ";
		$query5 .= "WHERE draw_date = $row5[0] ";

		echo "$query5<br>";

		$mysqli_result5 = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));

		$num_rows = mysqli_num_rows($mysqli_result5); 

		if (!$num_roews)
		{
			$draw = array ();
			$draw_count = array ();

			for ($x = 1; $x <= $balls_drawn; $x++)
			{
				array_push($draw, $row5[$x]);
			}

			calculate_draw_count ($draw); 

			$query9 = "INSERT INTO ga_f5_draw_count ";
			$query9 .= "VALUES ('0', ";
			$query9 .= "'$row5[0]', ";
			$query9 .= "'$draw_count[0]', ";
			$query9 .= "'$draw_count[1]', ";
			$query9 .= "'$draw_count[2]', ";
			$query9 .= "'$draw_count[3]', ";
			$query9 .= "'$draw_count[4]') ";
			
			#echo("$query9d\n");

			$mysqli_result9 = mysqli_query($mysqli_link, $query9) or die (mysqli_error($mysqli_link));
		}
	}

	print("<h2>Completed!</h2>");

	print("</BODY>\n");
?>
