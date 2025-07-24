<?php
	// Game ----------------------------- Game
	//$game = 1; // Georgia Fantasy 5
	$game = 2; // Mega Millions
	//$game = 3; // Georgia Lotto South
	#$game = 4; // Florida Fantasy 5
	//$game = 5; // Florida Mega Money
	#$game = 6; // Florida Lotto
	#$game = 7; // Powerball
	// Game ----------------------------- Game
	
	require ("includes/games_switch.incl");

	require ("includes/mysqli.php");
	require ("includes/db.class");
	require ("includes/even_odd.php");
	require ("includes/calculate_division.php");
	require ("includes/build_rank_table.php");
	require ("includes/calculate_draw.php"); 
	require ("includes/calculate_rank.php");
	require ("includes/first_draw_unix.php");
	require ("includes/last_draw_unix.php");
	require ("includes/next_draw.php");
	
	$debug = 0;

	$max_draw = 36;

	$d2_array = array_fill (0,2,0);
	$d3_array = array_fill (0,3,0);
	$d4_array = array_fill (0,4,0);
	$d6_array = array_fill (0,6,0);
	$d12_array = array_fill (0,12,0);
	$d18_array = array_fill (0,18,0);


	//start HTML page
	print("<HTML>\n");
	print("<HEAD>\n");
	print("<TITLE>$game_name Division - $game_name</TITLE>\n");
	print("</HEAD>\n");
	
	print("<BODY>\n");

	// get from draw table
	$query5 = "SELECT * FROM $draw_table_name ";
	$query5 .= "ORDER BY date DESC ";
	#$query5 .= "LIMIT 1 ";

	$mysqli_result5 = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));

	// get each row
	while($row = mysqli_fetch_array($mysqli_result5))
	{	
		$query6 = "SELECT * FROM $draw_prefix";
		$query6 .= "division ";
		$query6 .= "WHERE date = '$row[date]' ";

		$mysqli_result6 = mysqli_query($query6, $mysqli_link) or die (mysqli_error($mysqli_link));

		if (!$num_rows = mysqli_num_rows($mysqli_result6))
		{
			$draw = array ($row[b1],$row[b2],$row[b3],$row[b4],$row[b5]);
			calculate_div_2($draw,&$d2_array,$max_draw);
			calculate_div_3($draw,&$d3_array,$max_draw);
			calculate_div_4($draw,&$d4_array,$max_draw);
			calculate_div_6($draw,&$d6_array,$max_draw);
			calculate_div_12($draw,&$d12_array,$max_draw);
			calculate_div_18($draw,&$d18_array,$max_draw);

			$query3 = "Insert INTO $draw_prefix";
			$query3 .= "division ";
			$query3 .= "VALUES ( '$row[date]', ";
			
			for($z = 0; $z < 2; $z++)
			{
				$query3 .= "$d2_array[$z], ";
			}

			for($z = 0; $z < 3; $z++)
			{
				$query3 .= "$d3_array[$z], ";
			}

			for($z = 0; $z < 4; $z++)
			{
				$query3 .= "$d4_array[$z], ";
			}

			for($z = 0; $z < 6; $z++)
			{
				$query3 .= "$d6_array[$z], ";
			}

			for($z = 0; $z < 12; $z++)
			{
				$query3 .= "$d12_array[$z], ";
			}

			for($z = 0; $z < 17; $z++)
			{
				$query3 .= "$d18_array[$z], ";
			}
			
			$query3 .= "$d18_array[17]) ";

			print "$query3<p>";
			
			$mysqli_result3 = mysqli_query($mysqli_link, $query3) or die (mysqli_error($mysqli_link));
		} 

	}
	
	print("</BODY>\n");
?>
