<?php

	// add tables population for combos, pairs
	// add test for missing draws
	// recalcu;ate draw includes
	// add db class
	// error checking - all modules
	// fix pair count

	// Game ----------------------------- Game
	$game = 1; // Georgia Fantasy 5
	//$game = 2; // Georgia Mega Millions
	//$game = 3; // Georgia Lotto South
	#$game = 4; // Florida Fantasy 5
	//$game = 5; // Florida Mega Money
	//$game = 6; // Florida Lotto
	#$game = 7; // Powerball
	// Game ----------------------------- Game

	#ini_set('display_errors', '1');
	#ini_set('display_startup_errors', '1');
	#error_reporting(E_ALL);

	require ("includes/calculate_draw.php"); 
	require ("includes/calculate_rank.php");
	require ("includes/first_draw_unix.php");
	require ("includes/last_draw_unix.php");
	require ("includes/next_draw.php");
	
	require ("includes/games_switch.incl");

	//require ("includes/mysqli.php");
	require ("includes/mysqli.php");

	require ("includes/dateDiffInDays.php");
	require ("includes/unix.incl");

	echo "4<br>";

	require ("includes_ga_f5/split_draws_2.php");
	require ("includes_ga_f5/split_draws_3.php");
	require ("includes_ga_f5/split_draws_4.php");
	
	$debug = 0;

	function lot_update_drange2_2 ($date, $draw, $combin)
	{ 
		global $debug, $game, $draw_table_name;
	
		require ("includes/mysqli.php");

		$query4 = "SELECT * FROM ga_f5_draws_draw2_2 ";
		$query4 .= "WHERE draw_date = '$date' ";
		$query4 .= "AND combin = $combin ";

		print("$query4<br>");
		
		$mysqli_result4 = mysqli_query($mysqli_link, $query4) or die (mysqli_error($mysqli_link));

		$num_rows4 = mysqli_num_rows($mysqli_result4);

		if (!$num_rows4)
		{
			$d1 = 0;
			$d2 = 0; 

			$range1 = intval(42/2);

			reset ($draw); 
		
			foreach ($draw as $val) 
			{ 
				if ($val > $range1) { # > 24
					$d2++;
				} else {
					$d1++;
				}
			} 

			$query4 = "INSERT INTO ga_f5_draws_draw2_2 ";
			$query4 .= "VALUES('0', ";
			$query4 .= "'$date', ";
			$query4 .= "'$combin', ";
			$query4 .= "'$d1', ";
			$query4 .= "'$d2') ";

			print("$query4<br>");

			$mysqli_result4 = mysqli_query($mysqli_link, $query4) or die (mysqli_error($mysqli_link)); 

			#print("<FONT COLOR=RED><H4>*** drange2 $row[0] updated ***</H4></FONT>\n");
		} elseif ($num_rows4 > 1) {
			$row4 = mysqli_fetch_array($mysqli_result4);

			$query_delete = "DELETE FROM `ga_f5_draws_draw2_2` WHERE id = $row4[id]";

			$mysqli_result_delete = mysqli_query($mysqli_link, $query_delete) or die (mysqli_error($mysqli_link)); 
		}
	} 

	function lot_update_drange2_3 ($date, $draw, $combin)
	{ 
		global $debug, $game, $draw_table_name;
	
		require ("includes/mysqli.php");

		$query4 = "SELECT * FROM ga_f5_draws_draw2_3 ";
		$query4 .= "WHERE draw_date = '$date' ";
		$query4 .= "AND combin = $combin ";

		#print("$query4<br>");
		
		$mysqli_result4 = mysqli_query($mysqli_link, $query4) or die (mysqli_error($mysqli_link));

		$num_rows4 = mysqli_num_rows($mysqli_result4);

		if (!$num_rows4)
		{
			$d1 = 0;
			$d2 = 0; 

			$range1 = intval(42/2);

			reset ($draw); 
		
			foreach ($draw as $val) 
			{ 
				if ($val > $range1) { # > 24
					$d2++;
				} else {
					$d1++;
				}
			} 

			$query4 = "INSERT INTO ga_f5_draws_draw2_3 ";
			$query4 .= "VALUES('0', ";
			$query4 .= "'$date', ";
			$query4 .= "'$combin', ";
			$query4 .= "'$d1', ";
			$query4 .= "'$d2') ";

			#print("$query4<br>");

			$mysqli_result4 = mysqli_query($mysqli_link, $query4) or die (mysqli_error($mysqli_link)); 

			#print("<FONT COLOR=RED><H4>*** drange2 $row[0] updated ***</H4></FONT>\n");
		} elseif ($num_rows4 > 1) {
			$row4 = mysqli_fetch_array($mysqli_result4);

			$query_delete = "DELETE FROM `ga_f5_draws_draw2_3` WHERE id = $row4[id]";

			$mysqli_result_delete = mysqli_query($mysqli_link, $query_delete) or die (mysqli_error($mysqli_link)); 
		}
	}

	function lot_update_drange2_4 ($date, $draw, $combin)
	{ 
		global $debug, $game, $draw_table_name;
	
		require ("includes/mysqli.php");

		$query4 = "SELECT * FROM ga_f5_draws_draw2_4 ";
		$query4 .= "WHERE draw_date = '$date' ";
		$query4 .= "AND combin = $combin ";

		#print("$query4<br>");
		
		$mysqli_result4 = mysqli_query($mysqli_link, $query4) or die (mysqli_error($mysqli_link));

		$num_rows4 = mysqli_num_rows($mysqli_result4);

		if (!$num_rows4)
		{
			$d1 = 0;
			$d2 = 0; 

			$range1 = intval(42/2);

			#echo "range1 = $range1<br>";

			reset ($draw); 

			#echo "draw = ";
			#print_r ($draw);
			#echo "<br>";
		
			foreach ($draw as $val) 
			{ 
				#echo "val = $val<br>";
				if ($val > $range1) { # > 24
					$d2++;
					#echo "d2 = $d2<br>";
				} else {
					$d1++;
					#echo "d1 = $d1<br>";
				}
			} 

			$query4 = "INSERT INTO ga_f5_draws_draw2_4 ";
			$query4 .= "VALUES('0', ";
			$query4 .= "'$date', ";
			$query4 .= "'$combin', ";
			$query4 .= "'$d1', ";
			$query4 .= "'$d2') ";

			#print("$query4<br>");

			$mysqli_result4 = mysqli_query($mysqli_link, $query4) or die (mysqli_error($mysqli_link)); 

			#print("<FONT COLOR=RED><H4>*** drange2 $row[0] updated ***</H4></FONT>\n");
		} elseif ($num_rows4 > 1) {
			$row4 = mysqli_fetch_array($mysqli_result4);

			$query_delete = "DELETE FROM `ga_f5_draws_draw2_4` WHERE id = $row4[id]";

			#print("$query_delete<br>");

			$mysqli_result_delete = mysqli_query($mysqli_link, $query_delete) or die (mysqli_error($mysqli_link)); 
		}
	}

	function lot_update_drange3_2 ($date, $draw, $combin)
	{ 
		global $debug, $game, $draw_table_name;
	
		require ("includes/mysqli.php");

		$query4 = "SELECT * FROM ga_f5_draws_draw3_2 ";
		$query4 .= "WHERE draw_date = '$date' ";
		$query4 .= "AND combin = $combin ";

		#print("$query4<br>");
		
		$mysqli_result4 = mysqli_query($mysqli_link, $query4) or die (mysqli_error($mysqli_link));

		$num_rows4 = mysqli_num_rows($mysqli_result4);

		if (!$num_rows4)
		{
			$d1 = 0;
			$d2 = 0;
			$d3 = 0;

			$range1 = intval(42/3);
			$range2 = intval((42/3)*2);

			reset ($draw); 
		
			foreach ($draw as $val) 
			{ 
				if ($val > $range2) { # > 28
					$d3++;
				} elseif ($val > $range1) { # > 14
					$d2++;
				} else {
					$d1++;
				}
			} 

			$query4 = "INSERT INTO ga_f5_draws_draw3_2 ";
			$query4 .= "VALUES('0', ";
			$query4 .= "'$date', ";
			$query4 .= "'$combin', ";
			$query4 .= "'$d1', ";
			$query4 .= "'$d2', ";
			$query4 .= "'$d3') ";

			#print("$query4<br>");

			$mysqli_result4 = mysqli_query($mysqli_link, $query4) or die (mysqli_error($mysqli_link)); 

			#print("<FONT COLOR=RED><H4>*** drange2 $row[0] updated ***</H4></FONT>\n");
		} elseif ($num_rows4 > 1) {
			$row4 = mysqli_fetch_array($mysqli_result4);

			$query_delete = "DELETE FROM `ga_f5_draws_draw3_2` WHERE id = $row4[id]";

			$mysqli_result_delete = mysqli_query($mysqli_link, $query_delete) or die (mysqli_error($mysqli_link)); 
		}
	}

	function lot_update_drange3_3 ($date, $draw, $combin)
	{ 
		global $debug, $game, $draw_table_name;
	
		require ("includes/mysqli.php");

		$query4 = "SELECT * FROM ga_f5_draws_draw3_3 ";
		$query4 .= "WHERE draw_date = '$date' ";
		$query4 .= "AND combin = $combin ";

		#print("$query4<br>");
		
		$mysqli_result4 = mysqli_query($mysqli_link, $query4) or die (mysqli_error($mysqli_link));

		$num_rows4 = mysqli_num_rows($mysqli_result4);

		if (!$num_rows4)
		{
			$d1 = 0;
			$d2 = 0;
			$d3 = 0;

			$range1 = intval(42/3);
			$range2 = intval((42/3)*2);

			reset ($draw); 
		
			foreach ($draw as $val) 
			{ 
				if ($val > $range2) { # > 28
					$d3++;
				} elseif ($val > $range1) { # > 14
					$d2++;
				} else {
					$d1++;
				}
			} 

			$query4 = "INSERT INTO ga_f5_draws_draw3_3 ";
			$query4 .= "VALUES('0', ";
			$query4 .= "'$date', ";
			$query4 .= "'$combin', ";
			$query4 .= "'$d1', ";
			$query4 .= "'$d2', ";
			$query4 .= "'$d3') ";

			#print("$query4<br>");

			$mysqli_result4 = mysqli_query($mysqli_link, $query4) or die (mysqli_error($mysqli_link)); 

			#print("<FONT COLOR=RED><H4>*** drange2 $row[0] updated ***</H4></FONT>\n");
		} elseif ($num_rows4 > 1) {
			$row4 = mysqli_fetch_array($mysqli_result4);

			$query_delete = "DELETE FROM `ga_f5_draws_draw3_3` WHERE id = $row4[id]";

			$mysqli_result_delete = mysqli_query($mysqli_link, $query_delete) or die (mysqli_error($mysqli_link)); 
		}
	}

	function lot_update_drange3_4 ($date, $draw, $combin)
	{ 
		global $debug, $game, $draw_table_name;
	
		require ("includes/mysqli.php");

		$query4 = "SELECT * FROM ga_f5_draws_draw3_4 ";
		$query4 .= "WHERE draw_date = '$date' ";
		$query4 .= "AND combin = $combin ";

		#print("$query4<br>");
		
		$mysqli_result4 = mysqli_query($mysqli_link, $query4) or die (mysqli_error($mysqli_link));

		$num_rows4 = mysqli_num_rows($mysqli_result4);

		if (!$num_rows4)
		{
			$d1 = 0;
			$d2 = 0;
			$d3 = 0;

			$range1 = intval(42/3);
			$range2 = intval((42/3)*2);

			reset ($draw); 
		
			foreach ($draw as $val) 
			{ 
				if ($val > $range2) { # > 28
					$d3++;
				} elseif ($val > $range1) { # > 14
					$d2++;
				} else {
					$d1++;
				}
			} 

			$query4 = "INSERT INTO ga_f5_draws_draw3_4 ";
			$query4 .= "VALUES('0', ";
			$query4 .= "'$date', ";
			$query4 .= "'$combin', ";
			$query4 .= "'$d1', ";
			$query4 .= "'$d2', ";
			$query4 .= "'$d3') ";

			#print("$query4<br>");

			$mysqli_result4 = mysqli_query($mysqli_link, $query4) or die (mysqli_error($mysqli_link)); 

			#print("<FONT COLOR=RED><H4>*** drange2 $row[0] updated ***</H4></FONT>\n");
		} elseif ($num_rows4 > 1) {
			$row4 = mysqli_fetch_array($mysqli_result4);

			$query_delete = "DELETE FROM `ga_f5_draws_draw3_4` WHERE id = $row4[id]";

			$mysqli_result_delete = mysqli_query($mysqli_link, $query_delete) or die (mysqli_error($mysqli_link)); 
		}
	}

	function lot_update_drange4_2 ($date, $draw, $combin)
	{ 
		global $debug, $game, $draw_table_name;
	
		require ("includes/mysqli.php");

		$query4 = "SELECT * FROM ga_f5_draws_draw4_2 ";
		$query4 .= "WHERE draw_date = '$date' ";
		$query4 .= "AND combin = $combin ";

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

			$range1 = intval(42/4);
			$range2 = intval((42/4)*2);
			$range3 = intval((42/4)*3);

			reset ($draw); 
		
			foreach ($draw as $val) 
			{ 
				if ($val > $range3) { # > 31
					$d4++;
				} elseif ($val > $range2) { # > 21
					$d3++;
				} elseif ($val > $range1) { # > 10
					$d2++;
				} else {
					$d1++;
				}
			} 

			$query4 = "INSERT INTO ga_f5_draws_draw4_2 ";
			$query4 .= "VALUES('0', ";
			$query4 .= "'$date', ";
			$query4 .= "'$combin', ";
			$query4 .= "'$d1', ";
			$query4 .= "'$d2', ";
			$query4 .= "'$d3', ";
			#$query4 .= "'$d4', ";
			#$query4 .= "'$d5', ";
			#$query4 .= "'$d6', ";
			#$query4 .= "'$d7', ";
			#$query4 .= "'$d8', ";
			#$query4 .= "'$d9', ";
			$query4 .= "'$d4') ";

			#print("$query4<br>");

			$mysqli_result4 = mysqli_query($mysqli_link, $query4) or die (mysqli_error($mysqli_link)); 
		} elseif ($num_rows4 > 1) {
			$row4 = mysqli_fetch_array($mysqli_result4);

			$query_delete = "DELETE FROM `ga_f5_draws_draw4_2` WHERE id = $row4[id]";

			$mysqli_result_delete = mysqli_query($mysqli_link, $query_delete) or die (mysqli_error($mysqli_link)); 
		}
	}

	function lot_update_drange4_3 ($date, $draw, $combin)
	{ 
		global $debug, $game, $draw_table_name;
	
		require ("includes/mysqli.php");

		$query4 = "SELECT * FROM ga_f5_draws_draw4_3 ";
		$query4 .= "WHERE draw_date = '$date' ";
		$query4 .= "AND combin = $combin ";

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

			$range1 = intval(42/4);
			$range2 = intval((42/4)*2);
			$range3 = intval((42/4)*3);

			reset ($draw); 
		
			foreach ($draw as $val) 
			{ 
				if ($val > $range3) { # > 31
					$d4++;
				} elseif ($val > $range2) { # > 21
					$d3++;
				} elseif ($val > $range1) { # > 10
					$d2++;
				} else {
					$d1++;
				}
			} 

			$query4 = "INSERT INTO ga_f5_draws_draw4_3 ";
			$query4 .= "VALUES('0', ";
			$query4 .= "'$date', ";
			$query4 .= "'$combin', ";
			$query4 .= "'$d1', ";
			$query4 .= "'$d2', ";
			$query4 .= "'$d3', ";
			$query4 .= "'$d4') ";

			#print("$query4<br>");

			$mysqli_result4 = mysqli_query($mysqli_link, $query4) or die (mysqli_error($mysqli_link)); 
		} elseif ($num_rows4 > 1) {
			$row4 = mysqli_fetch_array($mysqli_result4);

			$query_delete = "DELETE FROM `ga_f5_draws_draw4_3` WHERE id = $row4[id]";

			$mysqli_result_delete = mysqli_query($mysqli_link, $query_delete) or die (mysqli_error($mysqli_link)); 
		}
	}

	function lot_update_drange4_4 ($date, $draw, $combin)
	{ 
		global $debug, $game, $draw_table_name;
	
		require ("includes/mysqli.php");

		$query4 = "SELECT * FROM ga_f5_draws_draw4_4 ";
		$query4 .= "WHERE draw_date = '$date' ";
		$query4 .= "AND combin = $combin ";

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

			$range1 = intval(42/4);
			$range2 = intval((42/4)*2);
			$range3 = intval((42/4)*3);

			reset ($draw); 
		
			foreach ($draw as $val) 
			{ 
				if ($val > $range3) { # > 31
					$d4++;
				} elseif ($val > $range2) { # > 21
					$d3++;
				} elseif ($val > $range1) { # > 10
					$d2++;
				} else {
					$d1++;
				}
			} 

			$query4 = "INSERT INTO ga_f5_draws_draw4_4 ";
			$query4 .= "VALUES('0', ";
			$query4 .= "'$date', ";
			$query4 .= "'$combin', ";
			$query4 .= "'$d1', ";
			$query4 .= "'$d2', ";
			$query4 .= "'$d3', ";
			#$query4 .= "'$d4', ";
			#$query4 .= "'$d5', ";
			#$query4 .= "'$d6', ";
			#$query4 .= "'$d7', ";
			#$query4 .= "'$d8', ";
			#$query4 .= "'$d9', ";
			$query4 .= "'$d4') ";

			#print("$query4<br>");

			$mysqli_result4 = mysqli_query($mysqli_link, $query4) or die (mysqli_error($mysqli_link)); 
		} elseif ($num_rows4 > 1) {
			$row4 = mysqli_fetch_array($mysqli_result4);

			$query_delete = "DELETE FROM `ga_f5_draws_draw4_4` WHERE id = $row4[id]";

			$mysqli_result_delete = mysqli_query($mysqli_link, $query_delete) or die (mysqli_error($mysqli_link)); 
		}
	}

	function lot_update_drange5_2 ($date, $draw, $combin)
	{ 
		global $debug, $game, $draw_table_name;
	
		require ("includes/mysqli.php");

		$query4 = "SELECT * FROM ga_f5_draws_draw5_2 ";
		$query4 .= "WHERE draw_date = '$date' ";
		$query4 .= "AND combin = $combin ";

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

			$range1 = intval(42/5);
			$range2 = intval((42/5)*2);
			$range3 = intval((42/5)*3);
			$range4 = intval((42/5)*4);

			reset ($draw); 
		
			foreach ($draw as $val) 
			{ 
				if ($val > $range4) { # > 33
					$d5++;
				} elseif ($val > $range3) { # > 25
					$d4++;
				} elseif ($val > $range2) { # > 18
					$d3++;
				} elseif ($val > $range1) { # > 8
					$d2++;
				} else {
					$d1++;
				}
			} 

			$query4 = "INSERT INTO ga_f5_draws_draw5_2 ";
			$query4 .= "VALUES('0', ";
			$query4 .= "'$date', ";
			$query4 .= "'$combin', ";
			$query4 .= "'$d1', ";
			$query4 .= "'$d2', ";
			$query4 .= "'$d3', ";
			$query4 .= "'$d4', ";
			#$query4 .= "'$d5', ";
			#$query4 .= "'$d6', ";
			#$query4 .= "'$d7', ";
			#$query4 .= "'$d8', ";
			#$query4 .= "'$d9', ";
			$query4 .= "'$d5') ";

			#print("$query4<br>");

			$mysqli_result4 = mysqli_query($mysqli_link, $query4) or die (mysqli_error($mysqli_link)); 
		} elseif ($num_rows4 > 1) {
			$row4 = mysqli_fetch_array($mysqli_result4);

			$query_delete = "DELETE FROM `ga_f5_draws_draw5_2` WHERE id = $row4[id]";

			$mysqli_result_delete = mysqli_query($mysqli_link, $query_delete) or die (mysqli_error($mysqli_link)); 
		}
	}

	function lot_update_drange5_3 ($date, $draw, $combin)
	{ 
		global $debug, $game, $draw_table_name;
	
		require ("includes/mysqli.php");

		$query4 = "SELECT * FROM ga_f5_draws_draw5_3 ";
		$query4 .= "WHERE draw_date = '$date' ";
		$query4 .= "AND combin = $combin ";

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

			$range1 = intval(42/5);
			$range2 = intval((42/5)*2);
			$range3 = intval((42/5)*3);
			$range4 = intval((42/5)*4);

			reset ($draw); 
		
			foreach ($draw as $val) 
			{ 
				if ($val > $range4) { # > 33
					$d5++;
				} elseif ($val > $range3) { # > 25
					$d4++;
				} elseif ($val > $range2) { # > 18
					$d3++;
				} elseif ($val > $range1) { # > 8
					$d2++;
				} else {
					$d1++;
				}
			} 

			$query4 = "INSERT INTO ga_f5_draws_draw5_3 ";
			$query4 .= "VALUES('0', ";
			$query4 .= "'$date', ";
			$query4 .= "'$combin', ";
			$query4 .= "'$d1', ";
			$query4 .= "'$d2', ";
			$query4 .= "'$d3', ";
			$query4 .= "'$d4', ";
			#$query4 .= "'$d5', ";
			#$query4 .= "'$d6', ";
			#$query4 .= "'$d7', ";
			#$query4 .= "'$d8', ";
			#$query4 .= "'$d9', ";
			$query4 .= "'$d5') ";

			#print("$query4<br>");

			$mysqli_result4 = mysqli_query($mysqli_link, $query4) or die (mysqli_error($mysqli_link)); 
		} elseif ($num_rows4 > 1) {
			$row4 = mysqli_fetch_array($mysqli_result4);

			$query_delete = "DELETE FROM `ga_f5_draws_draw5_3` WHERE id = $row4[id]";

			$mysqli_result_delete = mysqli_query($mysqli_link, $query_delete) or die (mysqli_error($mysqli_link)); 
		}
	}

	function lot_update_drange5_4 ($date, $draw, $combin)
	{ 
		global $debug, $game, $draw_table_name;
	
		require ("includes/mysqli.php");

		$query4 = "SELECT * FROM ga_f5_draws_draw5_4 ";
		$query4 .= "WHERE draw_date = '$date' ";
		$query4 .= "AND combin = $combin ";

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

			$range1 = intval(42/5);
			$range2 = intval((42/5)*2);
			$range3 = intval((42/5)*3);
			$range4 = intval((42/5)*4);

			reset ($draw); 
		
			foreach ($draw as $val) 
			{ 
				if ($val > $range4) { # > 33
					$d5++;
				} elseif ($val > $range3) { # > 25
					$d4++;
				} elseif ($val > $range2) { # > 18
					$d3++;
				} elseif ($val > $range1) { # > 8
					$d2++;
				} else {
					$d1++;
				}
			} 

			$query4 = "INSERT INTO ga_f5_draws_draw5_4 ";
			$query4 .= "VALUES('0', ";
			$query4 .= "'$date', ";
			$query4 .= "'$combin', ";
			$query4 .= "'$d1', ";
			$query4 .= "'$d2', ";
			$query4 .= "'$d3', ";
			$query4 .= "'$d4', ";
			#$query4 .= "'$d5', ";
			#$query4 .= "'$d6', ";
			#$query4 .= "'$d7', ";
			#$query4 .= "'$d8', ";
			#$query4 .= "'$d9', ";
			$query4 .= "'$d5') ";

			#print("$query4<br>");

			$mysqli_result4 = mysqli_query($mysqli_link, $query4) or die (mysqli_error($mysqli_link)); 
		} elseif ($num_rows4 > 1) {
			$row4 = mysqli_fetch_array($mysqli_result4);

			$query_delete = "DELETE FROM `ga_f5_draws_draw4_4` WHERE id = $row4[id]";

			$mysqli_result_delete = mysqli_query($mysqli_link, $query_delete) or die (mysqli_error($mysqli_link)); 
		} elseif ($num_rows4 > 1) {
			$row4 = mysqli_fetch_array($mysqli_result4);

			$query_delete = "DELETE FROM `ga_f5_draws_draw5_4` WHERE id = $row4[id]";

			$mysqli_result_delete = mysqli_query($mysqli_link, $query_delete) or die (mysqli_error($mysqli_link)); 
		}
	}

	function lot_update_drange6_2 ($date, $draw, $combin)
	{ 
		global $debug, $game, $draw_table_name;
	
		require ("includes/mysqli.php");

		$query4 = "SELECT * FROM ga_f5_draws_draw6_2 ";
		$query4 .= "WHERE draw_date = '$date' ";
		$query4 .= "AND combin = $combin ";

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

			$range1 = intval(42/6);
			$range2 = intval((42/6)*2);
			$range3 = intval((42/6)*3);
			$range4 = intval((42/6)*4);
			$range5 = intval((42/6)*5);

			reset ($draw); 
		
			foreach ($draw as $val) 
			{ 
				if ($val > $range5) {
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

			$query4 = "INSERT INTO ga_f5_draws_draw6_2 ";
			$query4 .= "VALUES('0', ";
			$query4 .= "'$date', ";
			$query4 .= "'$combin', ";
			$query4 .= "'$d1', ";
			$query4 .= "'$d2', ";
			$query4 .= "'$d3', ";
			$query4 .= "'$d4', ";
			$query4 .= "'$d5', ";
			#$query4 .= "'$d6', ";
			#$query4 .= "'$d7', ";
			#$query4 .= "'$d8', ";
			#$query4 .= "'$d9', ";
			$query4 .= "'$d6') ";

			#print("$query4<br>");

			$mysqli_result4 = mysqli_query($mysqli_link, $query4) or die (mysqli_error($mysqli_link)); 
		} elseif ($num_rows4 > 1) {
			$row4 = mysqli_fetch_array($mysqli_result4);

			$query_delete = "DELETE FROM `ga_f5_draws_draw6_2` WHERE id = $row4[id]";

			$mysqli_result_delete = mysqli_query($mysqli_link, $query_delete) or die (mysqli_error($mysqli_link)); 
		}
	}

	function lot_update_drange6_3 ($date, $draw, $combin)
	{ 
		global $debug, $game, $draw_table_name;
	
		require ("includes/mysqli.php");

		$query4 = "SELECT * FROM ga_f5_draws_draw6_3 ";
		$query4 .= "WHERE draw_date = '$date' ";
		$query4 .= "AND combin = $combin ";

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

			$range1 = intval(42/6);
			$range2 = intval((42/6)*2);
			$range3 = intval((42/6)*3);
			$range4 = intval((42/6)*4);
			$range5 = intval((42/6)*5);

			reset ($draw); 
		
			foreach ($draw as $val) 
			{ 
				if ($val > $range5) {
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

			$query4 = "INSERT INTO ga_f5_draws_draw6_3 ";
			$query4 .= "VALUES('0', ";
			$query4 .= "'$date', ";
			$query4 .= "'$combin', ";
			$query4 .= "'$d1', ";
			$query4 .= "'$d2', ";
			$query4 .= "'$d3', ";
			$query4 .= "'$d4', ";
			$query4 .= "'$d5', ";
			#$query4 .= "'$d6', ";
			#$query4 .= "'$d7', ";
			#$query4 .= "'$d8', ";
			#$query4 .= "'$d9', ";
			$query4 .= "'$d6') ";

			#print("$query4<br>");

			$mysqli_result4 = mysqli_query($mysqli_link, $query4) or die (mysqli_error($mysqli_link)); 
		} elseif ($num_rows4 > 1) {
			$row4 = mysqli_fetch_array($mysqli_result4);

			$query_delete = "DELETE FROM `ga_f5_draws_draw6_3` WHERE id = $row4[id]";

			$mysqli_result_delete = mysqli_query($mysqli_link, $query_delete) or die (mysqli_error($mysqli_link)); 
		}
	}

	function lot_update_drange6_4 ($date, $draw, $combin)
	{ 
		global $debug, $game, $draw_table_name;
	
		require ("includes/mysqli.php");

		$query4 = "SELECT * FROM ga_f5_draws_draw6_4 ";
		$query4 .= "WHERE draw_date = '$date' ";
		$query4 .= "AND combin = $combin ";

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

			$range1 = intval(42/6);
			$range2 = intval((42/6)*2);
			$range3 = intval((42/6)*3);
			$range4 = intval((42/6)*4);
			$range5 = intval((42/6)*5);

			reset ($draw); 
		
			foreach ($draw as $val) 
			{ 
				if ($val > $range5) {
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

			$query4 = "INSERT INTO ga_f5_draws_draw6_4 ";
			$query4 .= "VALUES('0', ";
			$query4 .= "'$date', ";
			$query4 .= "'$combin', ";
			$query4 .= "'$d1', ";
			$query4 .= "'$d2', ";
			$query4 .= "'$d3', ";
			$query4 .= "'$d4', ";
			$query4 .= "'$d5', ";
			#$query4 .= "'$d6', ";
			#$query4 .= "'$d7', ";
			#$query4 .= "'$d8', ";
			#$query4 .= "'$d9', ";
			$query4 .= "'$d6') ";

			#print("$query4<br>");

			$mysqli_result4 = mysqli_query($mysqli_link, $query4) or die (mysqli_error($mysqli_link)); 
		} elseif ($num_rows4 > 1) {
			$row4 = mysqli_fetch_array($mysqli_result4);

			$query_delete = "DELETE FROM `ga_f5_draws_draw6_4` WHERE id = $row4[id]";

			$mysqli_result_delete = mysqli_query($mysqli_link, $query_delete) or die (mysqli_error($mysqli_link)); 
		}
	}

	function lot_update_drange7_2 ($date, $draw, $combin)
	{ 
		global $debug, $game, $draw_table_name;
	
		require ("includes/mysqli.php");

		$query4 = "SELECT * FROM ga_f5_draws_draw7_2 ";
		$query4 .= "WHERE draw_date = '$date' ";
		$query4 .= "AND combin = $combin ";

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

			$range1 = intval(42/7);
			$range2 = intval((42/7)*2);
			$range3 = intval((42/7)*3);
			$range4 = intval((42/7)*4);
			$range5 = intval((42/7)*5);
			$range6 = intval((42/7)*6);

			reset ($draw); 
		
			foreach ($draw as $val) 
			{ 
				if ($val > $range6) {
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

			$query4 = "INSERT INTO ga_f5_draws_draw7_2 ";
			$query4 .= "VALUES('0', ";
			$query4 .= "'$date', ";
			$query4 .= "'$combin', ";
			$query4 .= "'$d1', ";
			$query4 .= "'$d2', ";
			$query4 .= "'$d3', ";
			$query4 .= "'$d4', ";
			$query4 .= "'$d5', ";
			$query4 .= "'$d6', ";
			#$query4 .= "'$d7', ";
			#$query4 .= "'$d8', ";
			#$query4 .= "'$d9', ";
			$query4 .= "'$d7') ";

			#print("$query4<br>");

			$mysqli_result4 = mysqli_query($mysqli_link, $query4) or die (mysqli_error($mysqli_link)); 
		} elseif ($num_rows4 > 1) {
			$row4 = mysqli_fetch_array($mysqli_result4);

			$query_delete = "DELETE FROM `ga_f5_draws_draw7_2` WHERE id = $row4[id]";

			$mysqli_result_delete = mysqli_query($mysqli_link, $query_delete) or die (mysqli_error($mysqli_link)); 
		}
	}

	function lot_update_drange7_3 ($date, $draw, $combin)
	{ 
		global $debug, $game, $draw_table_name;
	
		require ("includes/mysqli.php");

		$query4 = "SELECT * FROM ga_f5_draws_draw7_3 ";
		$query4 .= "WHERE draw_date = '$date' ";
		$query4 .= "AND combin = $combin ";

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

			$range1 = intval(42/7);
			$range2 = intval((42/7)*2);
			$range3 = intval((42/7)*3);
			$range4 = intval((42/7)*4);
			$range5 = intval((42/7)*5);
			$range6 = intval((42/7)*6);

			reset ($draw); 
		
			foreach ($draw as $val) 
			{ 
				if ($val > $range6) {
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

			$query4 = "INSERT INTO ga_f5_draws_draw7_3 ";
			$query4 .= "VALUES('0', ";
			$query4 .= "'$date', ";
			$query4 .= "'$combin', ";
			$query4 .= "'$d1', ";
			$query4 .= "'$d2', ";
			$query4 .= "'$d3', ";
			$query4 .= "'$d4', ";
			$query4 .= "'$d5', ";
			$query4 .= "'$d6', ";
			#$query4 .= "'$d7', ";
			#$query4 .= "'$d8', ";
			#$query4 .= "'$d9', ";
			$query4 .= "'$d7') ";

			#print("$query4<br>");

			$mysqli_result4 = mysqli_query($mysqli_link, $query4) or die (mysqli_error($mysqli_link)); 
		} elseif ($num_rows4 > 1) {
			$row4 = mysqli_fetch_array($mysqli_result4);

			$query_delete = "DELETE FROM `ga_f5_draws_draw7_3` WHERE id = $row4[id]";

			$mysqli_result_delete = mysqli_query($mysqli_link, $query_delete) or die (mysqli_error($mysqli_link)); 
		}
	}

	function lot_update_drange7_4 ($date, $draw, $combin)
	{ 
		global $debug, $game, $draw_table_name;
	
		require ("includes/mysqli.php");

		$query4 = "SELECT * FROM ga_f5_draws_draw7_4 ";
		$query4 .= "WHERE draw_date = '$date' ";
		$query4 .= "AND combin = $combin ";

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

			$range1 = intval(42/7);
			$range2 = intval((42/7)*2);
			$range3 = intval((42/7)*3);
			$range4 = intval((42/7)*4);
			$range5 = intval((42/7)*5);
			$range6 = intval((42/7)*6);

			reset ($draw); 
		
			foreach ($draw as $val) 
			{ 
				if ($val > $range6) {
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

			$query4 = "INSERT INTO ga_f5_draws_draw7_4 ";
			$query4 .= "VALUES('0', ";
			$query4 .= "'$date', ";
			$query4 .= "'$combin', ";
			$query4 .= "'$d1', ";
			$query4 .= "'$d2', ";
			$query4 .= "'$d3', ";
			$query4 .= "'$d4', ";
			$query4 .= "'$d5', ";
			$query4 .= "'$d6', ";
			#$query4 .= "'$d7', ";
			#$query4 .= "'$d8', ";
			#$query4 .= "'$d9', ";
			$query4 .= "'$d7') ";

			#print("$query4<br>");

			$mysqli_result4 = mysqli_query($mysqli_link, $query4) or die (mysqli_error($mysqli_link)); 
		} elseif ($num_rows4 > 1) {
			$row4 = mysqli_fetch_array($mysqli_result4);

			$query_delete = "DELETE FROM `ga_f5_draws_draw7_4` WHERE id = $row4[id]";

			$mysqli_result_delete = mysqli_query($mysqli_link, $query_delete) or die (mysqli_error($mysqli_link)); 
		}
	}

	function lot_update_drange8_2 ($date, $draw, $combin)
	{ 
		global $debug;
	
		require ("includes/mysqli.php");

		$query4 = "SELECT * FROM ga_f5_draws_draw8_2 ";
		$query4 .= "WHERE draw_date = '$date' ";
		$query4 .= "AND combin = $combin ";

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

			$query4 = "INSERT INTO ga_f5_draws_draw8_2 ";
			$query4 .= "VALUES('0', ";
			$query4 .= "'$date', ";
			$query4 .= "'$combin', ";
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
		} elseif ($num_rows4 > 1) {
			$row4 = mysqli_fetch_array($mysqli_result4);

			$query_delete = "DELETE FROM `ga_f5_draws_draw8_2` WHERE id = $row4[id]";

			$mysqli_result_delete = mysqli_query($mysqli_link, $query_delete) or die (mysqli_error($mysqli_link)); 
		}
	}

	function lot_update_drange8_3 ($date, $draw, $combin)
	{ 
		global $debug;
	
		require ("includes/mysqli.php");

		$query4 = "SELECT * FROM ga_f5_draws_draw8_3 ";
		$query4 .= "WHERE draw_date = '$date' ";
		$query4 .= "AND combin = $combin ";

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

			$query4 = "INSERT INTO ga_f5_draws_draw8_3 ";
			$query4 .= "VALUES('0', ";
			$query4 .= "'$date', ";
			$query4 .= "'$combin', ";
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
		} elseif ($num_rows4 > 1) {
			$row4 = mysqli_fetch_array($mysqli_result4);

			$query_delete = "DELETE FROM `ga_f5_draws_draw8_3` WHERE id = $row4[id]";

			$mysqli_result_delete = mysqli_query($mysqli_link, $query_delete) or die (mysqli_error($mysqli_link)); 
		}
	}

	function lot_update_drange8_4 ($date, $draw, $combin)
	{ 
		global $debug;
	
		require ("includes/mysqli.php");

		$query4 = "SELECT * FROM ga_f5_draws_draw8_4 ";
		$query4 .= "WHERE draw_date = '$date' ";
		$query4 .= "AND combin = $combin ";

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

			$query4 = "INSERT INTO ga_f5_draws_draw8_4 ";
			$query4 .= "VALUES('0', ";
			$query4 .= "'$date', ";
			$query4 .= "'$combin', ";
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
		} elseif ($num_rows4 > 1) {
			$row4 = mysqli_fetch_array($mysqli_result4);

			$query_delete = "DELETE FROM `ga_f5_draws_draw8_4` WHERE id = $row4[id]";

			$mysqli_result_delete = mysqli_query($mysqli_link, $query_delete) or die (mysqli_error($mysqli_link)); 
		}
	}

	//start HTML page
	print("<HTML>\n");
	print("<HEAD>\n");
	print("<TITLE>Lotto Update drange combin all - $game_name</TITLE>\n");
	print("</HEAD>\n");
	
	print("<BODY>\n");

	split_draws_2 ($dateDiff);

	### get ga_f5_draws_2 table
	$query5 = "SELECT * FROM ga_f5_draws_2 ";
	$query5 .= "WHERE date >= '2015-10-01' ";
	$query5 .= "ORDER BY date DESC ";
	$query5 .= "LIMIT 10 ";

	echo "$query5<br>";

	$mysqli_result5 = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));

	while ($row5 = mysqli_fetch_array($mysqli_result5))
	{
		$draw = array ();

		for ($x = 1; $x <= $balls_drawn; $x++)
		{
			array_push($draw, $row5[$x]);
		}

		for ($c = 3; $c <= 4; $c++)
		{
			lot_update_drange2_2 ($row5[0], $draw, $combin=$c);
			lot_update_drange3_2 ($row5[0], $draw, $combin=$c);
			lot_update_drange4_2 ($row5[0], $draw, $combin=$c);
			lot_update_drange5_2 ($row5[0], $draw, $combin=$c);
			lot_update_drange6_2 ($row5[0], $draw, $combin=$c);
			lot_update_drange7_2 ($row5[0], $draw, $combin=$c);
			lot_update_drange8_2 ($row5[0], $draw, $combin=$c);
		}
	}

	split_draws_3 ($dateDiff);

	### get ga_f5_draws_2 table
	$query5 = "SELECT * FROM ga_f5_draws_3 ";
	$query5 .= "WHERE date >= '2015-10-01' ";
	$query5 .= "ORDER BY date DESC ";
	$query5 .= "LIMIT 10 ";

	echo "$query5<br>";

	$mysqli_result5 = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));

	while ($row5 = mysqli_fetch_array($mysqli_result5))
	{
		$draw = array ();

		for ($x = 3; $x <= 5; $x++)
		{
			array_push($draw, $row5[$x]);
		}

		for ($c = 1; $c <= 10; $c++)
		{
			lot_update_drange2_3 ($row5[0], $draw, $combin=$c);
			lot_update_drange3_3 ($row5[0], $draw, $combin=$c);
			lot_update_drange4_3 ($row5[0], $draw, $combin=$c);
			lot_update_drange5_3 ($row5[0], $draw, $combin=$c);
			lot_update_drange6_3 ($row5[0], $draw, $combin=$c);
			lot_update_drange7_3 ($row5[0], $draw, $combin=$c);
			lot_update_drange8_3 ($row5[0], $draw, $combin=$c);
		}
	}

	split_draws_4 ($dateDiff);

	### get ga_f5_draws_2 table
	$query5 = "SELECT * FROM ga_f5_draws_4 ";
	$query5 .= "WHERE date >= '2015-10-01' ";
	$query5 .= "ORDER BY date DESC ";
	$query5 .= "LIMIT 10 ";	###############################################

	echo "$query5<br>";

	$mysqli_result5 = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));

	while ($row5 = mysqli_fetch_array($mysqli_result5))
	{
		$draw = array ();

		for ($x = 3; $x <= 6; $x++)
		{
			array_push($draw, $row5[$x]);
		}

		for ($c = 1; $c <= 5; $c++)
		{
			lot_update_drange2_4 ($row5[0], $draw, $combin=$c);
			lot_update_drange3_4 ($row5[0], $draw, $combin=$c);
			lot_update_drange4_4 ($row5[0], $draw, $combin=$c);
			lot_update_drange5_4 ($row5[0], $draw, $combin=$c);
			lot_update_drange6_4 ($row5[0], $draw, $combin=$c);
			lot_update_drange7_4 ($row5[0], $draw, $combin=$c);
			lot_update_drange8_4 ($row5[0], $draw, $combin=$c);
		}
	}

	print("<h2>Completed!</h2>");

	print("</BODY>\n");
?>
