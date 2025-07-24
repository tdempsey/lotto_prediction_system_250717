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
			$mysqli_link = mysqli_connect("localhost", "root", "") or die ('Not connected : ' . mysqli_error());
			mysqli_select_db("georgia_fantasy_5", $mysqli_link) or die ('Can\'t use startgate: ' . mysqli_error()); 
			break;
		case 2:
			$mysqli_link = mysqli_connect("localhost", "root", "") or die ('Not connected : ' . mysqli_error());
			mysqli_select_db("megamillions", $mysqli_link) or die ('Can\'t use megamillions: ' . mysqli_error()); 
			break;
		case 3:
			$mysqli_link = mysqli_connect("localhost", "root", "") or die ('Not connected : ' . mysqli_error());
			mysqli_select_db("cash_4_life", $mysqli_link) or die ('Can\'t use startgate: ' . mysqli_error()); 
			break;
		case 4:
			$mysqli_link = mysqli_connect("localhost", "root", "") or die ('Not connected : ' . mysqli_error());
			mysqli_select_db("jumbo", $mysqli_link) or die ('Can\'t use georgia_jumbo_bucks: ' . mysqli_error()); 
			break;
		case 5:
			$mysqli_link = mysqli_connect("localhost", "root", "") or die ('Not connected : ' . mysqli_error());
			mysqli_select_db("fl_mega_money", $mysqli_link) or die ('Can\'t use startgate: ' . mysqli_error()); 
			break;
		case 6:
			$mysqli_link = mysqli_connect("localhost", "root", "") or die ('Not connected : ' . mysqli_error());
			mysqli_select_db("florida", $mysqli_link) or die ('Can\'t use startgate: ' . mysqli_error()); 
			break;
		case 7:
			$mysqli_link = mysqli_connect("localhost", "root", "") or die ('Not connected : ' . mysqli_error());
			mysqli_select_db("powerball", $mysqli_link) or die ('Can\'t use startgate: ' . mysqli_error()); 
			break;
		case 8:
			$mysqli_link = mysqli_connect("localhost", "root", "") or die ('Not connected : ' . mysqli_error());
			mysqli_select_db("georgia_cash3", $mysqli_link) or die ('Can\'t use startgate: ' . mysqli_error()); 
			break;
		case 9:
			$mysqli_link = mysqli_connect("localhost", "root", "") or die ('Not connected : ' . mysqli_error());
			mysqli_select_db("georgia_cash4", $mysqli_link) or die ('Can\'t use startgate: ' . mysqli_error()); 
			break;
		case 10:
			$mysqli_link = mysqli_connect("localhost", "root", "") or die ('Not connected : ' . mysqli_error());
			mysqli_select_db("cash_4_life", $mysqli_link) or die ('Can\'t use startgate: ' . mysqli_error()); 
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