<?php
function findPreviousDrawDate($game,$current_draw_date) 
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

	//$today = mktime (0,0,0,date("m"),date("d"),date("Y"));
	//$current_date = getdate(time());
	//list($year, $month, $day) = split('[/.-]', $current_draw_date);
	$date_temp = date_parse("$current_draw_date");
	//print_r ($date_temp);
	//print "<p>date $current_draw_date in date_parse format:\n";
	//print_r ($date_temp);
	//print "<p>";
	$date_temp_unix = mktime (0,0,0,$date_temp[month],$date_temp[day],$date_temp[year]);
	$date_explode = getdate($date_temp_unix);
	//print "date_temp_unix = $date_temp_unix<br>";
	$previous_draw = 0;

	switch ($game)
	{
		case 1:
		case 4:
			$previous_draw = $date_temp_unix - 86400;
			break;
		case 2:
		case 5:
			switch ($date_temp["wday"])
			{
				case "0": //Sun
				case "3": //Wed
					$previous_draw = $today + (86400*2);
					break;
				case "1": //Mon
				case "4": //Thur
					$previous_draw = $today + 86400;
					break;
				case "2": // Tues
				case "5": //Fri
					$previous_draw = $today;
					break;
				case "6": //Sat
					$previous_draw = $today + (86400*3);
					break;
			}
			break;
		case 3:
		case 6:
			//print "case 6 - Florida Lotto - day {$date_explode["wday"]}<br>";
			switch ($date_explode["wday"])
			{
				case "0": //Sun
				case "1": //Mon
				case "2": //Tues
				case "4": //Thur
				case "5": //Fri
					exit ('<h1><font color="#ff0000">Invalid date $current_draw_date in previous_draw.php</font></h1>');
					break;
				case "3": //Wed
					$previous_draw = $date_temp_unix - (86400*4);
					//print "case 3 - $previous_draw<br>";
					break;
				case "6": //Sat
					$previous_draw = $date_temp_unix - (86400*3);
					//print "case 6 - $previous_draw<br>";
					break;
			}
			break;
		case 7:
			//print "case 6 - Florida Lotto - day {$date_explode["wday"]}<br>";
			switch ($date_explode["wday"])
			{
				case "0": //Sun
				case "1": //Mon
				case "2": //Tues
				case "4": //Thur
				case "5": //Fri
					exit ('<h1><font color="#ff0000">Invalid date $current_draw_date in previous_draw.php</font></h1>');
					break;
				case "3": //Wed
					$previous_draw = $date_temp_unix - (86400*4);
					//print "case 3 - $previous_draw<br>";
					break;
				case "6": //Sat
					$previous_draw = $date_temp_unix - (86400*3);
					//print "case 6 - $previous_draw<br>";
					break;
			}
			break;
		default:
			exit ('<h1><font color="#ff0000">No game selected in function previous_draw.php</font></h1>');
	}

	//print "previous_draw = $previous_draw<br>";

	return gmstrftime("%Y-%m-%d", $previous_draw);
}
?>