<?php

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

	require ("includes/mysqli.php");
	require ("includes_fl_f5/test_column_lookup.php"); // <<<<<<<<<<<<<<<<
	//require ("includes/calculate_50_50.php");
	//require ("includes/build_rank_table_fl.php");
	//require ("includes/calculate_draw_fl.php"); 
	//require ("includes/calculate_rank_fl.php");
	//require ("includes/next_draw_fl.php");
	require ("includes/count_2_seq.php");
	require ("includes/count_3_seq.php");

	$debug = 0;

	//start HTML page
	print("<HTML>\n");
	print("<HEAD>\n");
	print("<TITLE>Florida Fantasy 5 Picks</TITLE>\n");
	print("</HEAD>\n");
	
	print("<BODY>\n");
	print("<H2>Florida Fantasy 5 - Picks</H2>\n");
	
	$draw_count = array_fill (0,4,0);
	$rank_count = array_fill (0,7,0);

	/////////////////// Database Query ///////////////////

	$query = "SELECT * FROM combo_5_36 ";
	$query .= "WHERE sum = 71 ";
	//$query .= "WHERE b1 = 15 ";
	$query .= "AND b1 = 1 ";
	$query .= "AND b5 = 28 ";

	/*
	$query .= "AND even = 2  ";
	$query .= "AND odd = 3 ";
	$query .= "AND d501 = 2 ";
	$query .= "AND d502 = 3 ";
	*/

	$query .= "AND even > 0 ";
	$query .= "AND even < 5 ";
	$query .= "AND odd > 0 ";
	$query .= "AND odd < 5 ";

	$query .= "AND d501 >= 0 ";
	$query .= "AND d501 <= 5 ";
	$query .= "AND d502 >= 0 ";
	$query .= "AND d502 <= 5 ";
	
	$query .= "ORDER BY sum,b1,b2,b3,b4,b5 ASC ";
	//$query .= "LIMIT 1000 ";

	//print "$query";

	$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

	//start table
	print("<TABLE BORDER=\"1\">\n");

	//create header row
	print("<TR><B>\n");
	print("<TD BGCOLOR=\"#CCCCCC\">&nbsp;</TD>\n");
	print("<TD BGCOLOR=\"#CCCCCC\">Pick 1</TD>\n");
	print("<TD BGCOLOR=\"#CCCCCC\">Pick 2</TD>\n");
	print("<TD BGCOLOR=\"#CCCCCC\">Pick 3</TD>\n");
	print("<TD BGCOLOR=\"#CCCCCC\">Pick 4</TD>\n");
	print("<TD BGCOLOR=\"#CCCCCC\">Pick 5</TD>\n");
	print("<TD BGCOLOR=\"#CCCCCC\">Sum</TD>\n");
	print("<TD BGCOLOR=\"#CCCCCC\">D0</TD>\n");
	print("<TD BGCOLOR=\"#CCCCCC\">D1</TD>\n");
	print("<TD BGCOLOR=\"#CCCCCC\">D2</TD>\n");
	print("<TD BGCOLOR=\"#CCCCCC\">D3</TD>\n");
	print("<TD BGCOLOR=\"#CCCCCC\">R0</TD>\n");
	print("<TD BGCOLOR=\"#CCCCCC\">R1</TD>\n");
	print("<TD BGCOLOR=\"#CCCCCC\">R2</TD>\n");
	print("<TD BGCOLOR=\"#CCCCCC\">R3</TD>\n");
	print("<TD BGCOLOR=\"#CCCCCC\">R4</TD>\n");
	print("<TD BGCOLOR=\"#CCCCCC\">R5</TD>\n");
	print("<TD BGCOLOR=\"#CCCCCC\">R6</TD>\n");
	print("</B></TR>\n");

	$dcount = 1;

	// get each row
	while($row = mysqli_fetch_array($mysqli_result))
	{
		$draw = array ($row[b1],$row[b2],$row[b3],$row[b4],$row[b5]);

		$pass = 1;

		/////////////////// SEQ2/3 ///////////////////
		$seq2 = Count2Seq($draw);
		$seq3 = Count3Seq($draw);

		if ($seq2 > 1 && $seq3 > 0) 
		{
			//print "seq2 = $seq2<br>";
			$pass = 0;
		} 
		/*
		/////////////////// Column /////////////////// (change to specific columns)
		for($x = 1; $x < 5; $x++)
		{
			for($y = $x+1; $y <= 5; $y++)
			{
				$temp = test_column_lookup($cola=$x,$colb=$y,$row[b.$x],$row[b.$y])-1;
				if ($temp < 2) 
				{
					$pass = 0;
				}
			}
		}
		
		/////////////////// Combination ///////////////////
		if ($pass) 
		{ 
			// test combin 5 
			$combin_found = combin5testFLF5($draw);
			if ($combin_found)
			{
				$pass = 0;
			}
			
			// saved combin 5 test 
			$combin_found = combin5saveFL($draw);
			if ($combin_found)
			{
				$pass = 0;
			}
			
			// test combin 4 
			$combin_found = combin4testFL($draw);
			if ($combin_found > $combin4max)
			{
				$pass = 0;
			}
			
			// saved combin 4 test 
			$combin_found = combin4saveFL($draw);
			//if ($combin_found > 2) /////////////////////////////////// 
			if ($combin_found)
			{
				$pass = 0;
			}
			
			// test combin 3 
			$combin_found = combin3testFL($draw);
			if ($combin_found > $combin3max || $combin_found < $combin3min )
			{
				$pass = 0;
			}
			
			// saved combin 3 test 
			$combin_found = combin3saveFL($draw);
			if ($combin_found > $combin3min) 
			{
				$pass = 0;
			}
			
			// test combin 2 
			$combin_found = combin2testFL($draw);
			if ($combin_found < $combin2min)
			{
				$pass = 0;
			}
			
			// saved combin 2 test 
			$combin_found = combin2saveFL($draw);
			if ($combin_found)
			{
				$pass = 0;
			}
		}
		*/
		if ($pass) 
		{
			print("<TR>\n");
			print("<TD><B>$dcount</B></TD>\n");
			print("<TD>$row[b1]</TD>\n");
			print("<TD>$row[b2]</TD>\n");
			print("<TD>$row[b3]</TD>\n");
			print("<TD>$row[b4]</TD>\n");
			print("<TD>$row[b5]</TD>\n");
			print("<TD>$row[sum]</TD>\n");
			print("</TR>\n");
			$dcount++;
			//generate_pick_table($sum,$row[id],$even,$odd,$d501,$d502,$num_rows,$percent,$num_rows_26,$num_rows_all);
		}
	}

	//end table
	print("</TABLE>\n");
	
	//close page
	print("</BODY>\n");
	print("</HTML>\n");

	// ----------------------------------------------------------------------------------
	function generate_pick_table($sum,$eo50,$even,$odd,$d501,$d502,$total_sum,$percent,$num_rows_26,
								  $num_rows_all)
	{
		global $debug;

		require ("includes/mysqli.php");
	
		$query9 = "INSERT INTO fl_f5_picks_a ";
		$query9 .= "VALUES (0, $sum, $eo50, $total_sum, $percent, $num_rows_26, $num_rows_all, '2007-2-15') ";
	
		$mysqli_result9 = mysqli_query($mysqli_link, $query9) or die (mysqli_error($mysqli_link));
	}

?>
