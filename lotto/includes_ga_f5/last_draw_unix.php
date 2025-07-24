<?php
function findLastDrawDateUnix($game) 
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
		case 8:
		case 9:
		case 10:
		case 20:
			$last_draw = $today - 86400;
			break;
		case 2:
		case 5:
			switch ($current_date["wday"])
			{
				case "0": //Sun
				case "3": //Wed
					$last_draw = $today + (86400*2);
					break;
				case "1": //Mon
				case "4": //Thur
					$last_draw = $today + 86400;
					break;
				case "2": // Tues
				case "5": //Fri
					$last_draw = $today;
					break;
				case "6": //Sat
					$last_draw = $today + (86400*3);
					break;
			}
			break;
		case 3:
		case 6:
			switch ($current_date["wday"])
			{
				case "0": //Sunday
					$last_draw = $today - 86400;
					break;
				case "1": //Monday
					$last_draw = $today - (86400*2);
					break;
				case "2": // Tuesday
					$last_draw = $today - (86400*3);
					break;
				case "3": //Wednesday
					$last_draw = $today - (86400*4);
					break;
				case "4": //Thursday
					$last_draw = $today - 86400;
					break;
				case "5": // Friday
					$last_draw = $today - (86400*2);
					break;
				case "6": //Saturday
					$last_draw = $today - (86400*3);
					break;
			}
			break;
		case 7:
			switch ($current_date["wday"])
			{
				case "0": //Sunday
					$last_draw = $today - 86400;
					break;
				case "1": //Monday
					$last_draw = $today - (86400*2);
					break;
				case "2": // Tuesday
					$last_draw = $today - (86400*3);
					break;
				case "3": //Wednesday
					$last_draw = $today - (86400*4);
					break;
				case "4": //Thursday
					$last_draw = $today - 86400;
					break;
				case "5": // Friday
					$last_draw = $today - (86400*2);
					break;
				case "6": //Saturday
					$last_draw = $today - (86400*3);
					break;
			}
			break;
		case 30:
			switch ($current_date["wday"])
			{
				case "0": //Sunday
					$last_draw = $today - (86400*2);
					break;
				case "1": //Monday
					$last_draw = $today - (86400*3);
					break;
				case "2": // Tuesday
					$last_draw = $today - (86400*4);
					break;
				case "3": //Wednesday
					$last_draw = $today - (86400*5);
					break;
				case "4": //Thursday
					$last_draw = $today - (86400*6);
					break;
				case "5": // Friday
					$last_draw = $today - (86400*7);
					break;
				case "6": //Saturday
					$last_draw = $today - (86400*1);
					break;
			}
			break;
		default:
			exit ('<h1><font color="#ff0000">No game selected in function last_draw_unix.php</font></h1>');
	}

	return ($last_draw);
}
?>