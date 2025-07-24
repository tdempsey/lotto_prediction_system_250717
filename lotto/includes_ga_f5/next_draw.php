<?php
function findNextDrawDate($game) 
{
	global $debug;

	// error checking - in case statement ----------------------------------------------------------------------------------------------

	// Game
	//$game = 1; // Georgia Fantasy 5
	//$game = 2; // Georgia Mega Millions
	//$game = 3; // Georgia Lotto South
	//$game = 4; // Florida Fantasy 5
	//$game = 5; // Florida Mega Money
	//$game = 6; // Florida Lotto
	//$game = 7; // Powerball

	$today = mktime (0,0,0,date("m"),date("d"),date("Y"));
	$current_date = getdate(time());

	switch ($game)
	{
		case 1:
		case 4:
		case 10:
		case 20:
			$next_draw = $today;
			break;
		case 2:
			switch ($current_date["wday"])
			{
				case "0": //Sun
				case "3": //Wed
					$next_draw = $today + (86400*2);
					break;
				case "1": //Mon
				case "4": //Thur
					$next_draw = $today + 86400;
					break;
				case "2": // Tues
				case "5": //Fri
					$next_draw = $today;
					break;
				case "6": //Sat
					$next_draw = $today + (86400*3);
					break;
			}
			break;
		case 5:
			switch ($current_date["wday"])
			{
				case "0": //Sun
				case "3": //Wed
					$next_draw = $today + (86400*2);
					break;
				case "1": //Mon
				case "4": //Thur
					$next_draw = $today + 86400;
					break;
				case "2": // Tues
				case "5": //Fri
					$next_draw = $today;
					break;
				case "6": //Sat
					$next_draw = $today + (86400*3);
					break;
			}
			break;
		case 3:
		case 6:
			switch ($current_date["wday"])
			{
				case "0": //Sunday
					$next_draw = $today + (86400*3);
					break;
				case "1": //Monday
					$next_draw = $today + (86400*2);
					break;
				case "2": // Tuesday
					$next_draw = $today + 86400;
					break;
				case "3": //Wednesday
					$next_draw = $today;
					break;
				case "4": //Thursday
					$next_draw = $today + (86400*2);
					break;
				case "5": // Friday
					$next_draw = $today + 86400;
					break;
				case "6": //Saturday
					$next_draw = $today;
					break;
			}
			break;
		case 7:
			switch ($current_date["wday"])
			{
				case "0": //Sun
					$next_draw = $today + (86400*3);
					break;
				case "1": //Mon
				case "4": //Thur
					$next_draw = $today + (86400*2);
					break;
				case "2": //Tues
				case "5": //Fri
					$next_draw = $today + 86400;
					break;
				case "3": // Wed
				case "6": //Sat
					$next_draw = $today;
					break;
			}
			break;
		default:
			exit ('<h1><font color="#ff0000">No game selected in function next_draw.php</font></h1>');
	}

	return gmstrftime("%Y%m%d", $next_draw);
}

function findNextDrawDateDash($game) 
{
	global $debug;

	// error checking - in case statement ----------------------------------------------------------------------------------------------

	// Game
	//$game = 1; // Georgia Fantasy 5
	//$game = 2; // Georgia Mega Millions
	//$game = 3; // Georgia Lotto South
	//$game = 4; // Florida Fantasy 5
	//$game = 5; // Florida Mega Money
	//$game = 6; // Florida Lotto
	//$game = 7; // Powerball

	$today = mktime (0,0,0,date("m"),date("d"),date("Y"));
	$current_date = getdate(time());

	switch ($game)
	{
		case 1:
		case 4:
		case 10:
			$next_draw = $today;
			break;
		case 2:
			switch ($current_date["wday"])
			{
				case "0": //Sun
				case "3": //Wed
					$next_draw = $today + (86400*2);
					break;
				case "1": //Mon
				case "4": //Thur
					$next_draw = $today + 86400;
					break;
				case "2": // Tues
				case "5": //Fri
					$next_draw = $today;
					break;
				case "6": //Sat
					$next_draw = $today + (86400*3);
					break;
			}
			break;
		case 5:
			switch ($current_date["wday"])
			{
				case "0": //Sun
				case "3": //Wed
					$next_draw = $today + (86400*2);
					break;
				case "1": //Mon
				case "4": //Thur
					$next_draw = $today + 86400;
					break;
				case "2": // Tues
				case "5": //Fri
					$next_draw = $today;
					break;
				case "6": //Sat
					$next_draw = $today + (86400*3);
					break;
			}
			break;
		case 3:
		case 6:
			switch ($current_date["wday"])
			{
				case "0": //Sunday
					$next_draw = $today + (86400*3);
					break;
				case "1": //Monday
					$next_draw = $today + (86400*2);
					break;
				case "2": // Tuesday
					$next_draw = $today + 86400;
					break;
				case "3": //Wednesday
					$next_draw = $today;
					break;
				case "4": //Thursday
					$next_draw = $today + (86400*2);
					break;
				case "5": // Friday
					$next_draw = $today + 86400;
					break;
				case "6": //Saturday
					$next_draw = $today;
					break;
			}
			break;
		case 7:
			switch ($current_date["wday"])
			{
				case "1": //Mon
				case "4": //Thur
					$next_draw = $today + (86400*2);
					break;
				case "2": //Tues
				case "5": //Fri
					$next_draw = $today + 86400;
					break;
				case "3": // Wed
				case "6": //Sat
					$next_draw = $today;
					break;
				case "0": //Sun
					$next_draw = $today + (86400*3);
					break;
			}
			break;
		default:
			exit ('<h1><font color="#ff0000">No game selected in function next_draw.php</font></h1>');
	}

	return gmstrftime("%Y-%m-%d", $next_draw);
}
?>