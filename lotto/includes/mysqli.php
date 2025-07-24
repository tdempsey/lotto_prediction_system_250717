<?php
	
	global $game;

	#$game = 6;

	//print "<h2>mysqli.php >> game = $game</h2>";
	
	// Game
	//$game = 1; // Georgia Fantasy 5
	//$game = 2; // Georgia Mega Millions
	//$game = 3; // Georgia Lotto South
	//$game = 4; // Florida Fantasy 5
	//$game = 5; // Florida Mega Money
	//$game = 6; // Florida Lotto
	//$game = 7; // Powerball

	#print "game = $game<br>";
	
	switch ($game):
		case 1:
			$mysqli_link = new mysqli("localhost", "root", "", "ga_f5_lotto");
			/* check connection */
			if ($mysqli_link->connect_errno) {
				printf("Connect failed: %s\n", $mysqli_link->connect_error);
				exit();
			}
			break;
		case 2:
			/* open connection */
			$mysqli_link = new mysqli("localhost", "root", "wef5esuv", "megamillions");
			/* check connection */
			if ($mysqli_link->connect_errno) {
				printf("Connect failed: %s\n", $mysqli->connect_error);
				exit();
			}
			break;
		case 3:
			$mysqli_link = mysqli_connect("localhost", "root", "") or die ('Not connected : ' . mysqli_error());
			mysqli_select_db("georgia_georgia_5", $mysqli_link) or die ('Can\'t use startgate: ' . mysqli_error()); 
			break;
		case 4:
			$mysqli_link = mysqli_connect("localhost", "root", "") or die ('Not connected : ' . mysqli_error());
			mysqli_select_db("florida_fantasy_5", $mysqli_link) or die ('Can\'t use florida_fantasy_5: ' . mysqli_error()); 
			break;
		case 5:
			$mysqli_link = mysqli_connect("localhost", "root", "") or die ('Not connected : ' . mysqli_error());
			mysqli_select_db("fl_mega_money", $mysqli_link) or die ('Can\'t use startgate: ' . mysqli_error()); 
			break;
		case 6:
			$mysqli_link = new mysqli("localhost", "root", "wef5esuv", "fl_lotto");
			/* check connection */
			if ($mysqli_link->connect_errno) {
				printf("Connect failed: %s\n", $mysqli->connect_error);
				exit();
			}
			break;
		case 7:
			/* open connection */
			$mysqli_link = new mysqli("localhost", "root", "wef5esuv", "powerball");
			/* check connection */
			if ($mysqli_link->connect_errno) {
				printf("Connect failed: %s\n", $mysqli->connect_error);
				exit();
			}
			break;
		case 8:
			$mysqli_link = mysqli_connect("localhost", "root", "") or die ('Not connected : ' . mysqli_error());
			mysqli_select_db("florida_cash3", $mysqli_link) or die ('Can\'t use startgate: ' . mysqli_error()); 
			break;
		case 9:
			$mysqli_link = mysqli_connect("localhost", "root", "") or die ('Not connected : ' . mysqli_error());
			mysqli_select_db("florida_cash4", $mysqli_link) or die ('Can\'t use startgate: ' . mysqli_error()); 
			break;
		case 10:
			$mysqli_link = mysqli_connect("localhost", "root", "") or die ('Not connected : ' . mysqli_error());
			mysqli_select_db("allornothing", $mysqli_link) or die ('Can\'t use startgate: ' . mysqli_error()); 
			break;
		case 20:
			$mysqli_link = mysqli_connect("localhost", "root", "") or die ('Not connected : ' . mysqli_error());
			mysqli_select_db("allornothing", $mysqli_link) or die ('Can\'t use startgate: ' . mysqli_error()); 
			break;
		case 30:
			$mysqli_link = mysqli_connect("localhost", "root", "") or die ('Not connected : ' . mysqli_error());
			mysqli_select_db("monopoly", $mysqli_link) or die ('Can\'t use startgate: ' . mysqli_error()); 
			break;
		default:
			$mysqli_link = mysqli_connect("localhost", "root", "") or die ('Not connected : ' . mysqli_error());
			mysqli_select_db("florida", $mysqli_link) or die ('Can\'t use startgate: ' . mysqli_error()); 
	endswitch;
?>