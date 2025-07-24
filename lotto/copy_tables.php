<?php
	#CREATE TABLE recipes_new LIKE production.recipes;
	#INSERT INTO recipes_new SELECT * FROM production.recipes;
	#$random_row = mysqli_fetch_row(mysqli_query("select * from YOUR_TABLE order by rand() limit 1"))

	// Game ----------------------------- Game
	//$game = 1; // Georgia Fantasy 5
	$game = 2; // Georgia Mega Millions
	//$game = 3; // Georgia Lotto South
	#$game = 4; // Florida Fantasy 5
	//$game = 5; // Florida Mega Money
	#$game = 6; // Florida Lotto
	#$game = 7; // Powerball
	// Game ----------------------------- Game
	
	require ("includes/games_switch.incl");

	require ("includes/mysqli.php");
	#require ("includes/db.class");

	for ($a = 1; $a <= 25; $a++){
		$querya = "DROP TABLE IF EXISTS combo_5_56_";
		if ($a < 10)
		{
			$querya .= "0$a";
		} else {
			$querya .= "$a";
		}
		
		echo "$querya<BR>";

		$mysqli_resulta = mysqli_query($querya, $mysqli_link) or die (mysqli_error($mysqli_link));
	}

	for ($a = 1; $a <= 25; $a++){
		$querya = "CREATE TABLE combo_5_56_";
		if ($a < 10)
		{
			$querya .= "0$a";
		} else {
			$querya .= "$a";
		}
		$querya .= " LIKE combo_5_56 ";

		echo "$querya<BR>";

		#die();

		$mysqli_resultb = mysqli_query($querya, $mysqli_link) or die (mysqli_error($mysqli_link));
	}

	for ($a = 1; $a <= 25; $a++){
		$querya = "INSERT INTO combo_5_56_"; 
		if ($a < 10)
		{
			$querya .= "0$a ";
		} else {
			$querya .= "$a ";
		}
		$querya .= " SELECT * FROM combo_5_56 WHERE b1 = $a ";

		echo "$querya<BR>";

		$mysqli_resultc = mysqli_query($querya, $mysqli_link) or die (mysqli_error($mysqli_link));
	}
?>