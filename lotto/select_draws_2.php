<?php

	// Game ----------------------------- Game
	//$game = 1; // Georgia Fantasy 5
	#$game = 2; // Mega Millions
	//$game = 3; // Georgia Lotto South
	#$game = 4; // Florida Fantasy 5
	//$game = 5; // Florida Mega Money
	$game = 6; // Florida Lotto
	#$game = 7; // Powerball
	// Game ----------------------------- Game
	
	require ("includes/games_switch.incl");

	require ("includes/mysqli.php");
	
	$debug = 0;

	$query = "SELECT  * FROM filter_b_6_53_02  ";
	#$query .= "WHERE (b2 = 19 OR b3 = 19 OR b4 = 19) ";
	#$query .= "AND (b3 = 20 OR b4 = 20 OR b5 = 20 OR b6 = 20) ";
	#$query .= "AND (b4 = 52 OR b5 = 52 or b6 = 52) ";
	#$query .= "AND (b4 = 38 OR b5 = 38 or b6 = 38) ";
	#$query .= "WHERE (b2 = 11 OR b3 = 11 OR b4 = 11 OR b5 = 11 or b6 =11) ";
	#$query .= "AND (b2 = 50 OR b3 = 50 OR b4 = 50 OR b5 = 50 OR b6 = 50) ";
	$query .= "WHERE (b2 = 6) ";
	#$query .= "AND   (b3 >= 15 AND b3 <= 29) "; # <-------------------------------------------------
	$query .= "AND   (b3 = 16) ";
	$query .= "AND   (b4 >= 22 AND b4 <= 39) "; # <-------------------------------------------------
	#$query .= "AND   (b4 = 41) ";
	$query .= "AND   (b5 >= 28 AND b5 <= 46) "; # <-------------------------------------------------
	#$query .= "AND   (b5 = 35) ";
	$query .= "AND   (b6 = 51) ";
	#$query .= "AND   (b6 >= 38 AND b6 <= 52) "; # <-------------------------------------------------
	#$query .= "AND (b3 = 32 OR b4 = 32) ";
	#$query .= "AND (b2 = 53 OR b3 = 53 OR b4 = 53 OR b5 = 53 OR b6 = 53) ";
	#$query .= "AND (b2 = 51 OR b3 = 51 or b4 = 51 OR b5 = 51 or b6 = 51) ";
	#$query .= "OR (b4 =  OR b4 = ) ";
	#$query .= "OR (b5 =  OR b4 = ) ";
	#$query .= "AND b6 = 50 ";

	print "$query<p>";

	$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

	$num_rows = mysqli_num_rows($mysqli_result);

	$x = 1;

	mt_srand((float) microtime() * 10000000);

	$y = mt_rand(0,$num_rows);

	print "rand = $y<p>";

	while($row = mysqli_fetch_array($mysqli_result))
	{
		print "$x - $row[b1],$row[b2],$row[b3],$row[b4],$row[b5],$row[b6]<br>";
		$x++;
	}

?>