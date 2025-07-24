<?php
	// Game
	$game = 1; // Georgia Fantasy 5
	//$game = 2; // Georgia Mega Millions
	//$game = 3; // Georgia Lotto South
	//$game = 4; // Florida Fantasy 5
	//$game = 5; // Florida Mega Money
	//$game = 6; // Florida Lotto
	//$game = 7; // Powerball
	
	switch ($game):
		case 1:
			$game_name = "Georgia Fantasy 5";
			$draw_table = "ga_f5_draws";
			$draw_prefix = "ga_f5_";
			$balls = 39;
			$balls_drawn = 5;
			$mega_balls = 0; // 0 means no megaball
			break;
		default:
			exit ('No game selected in function lot_display.php');
	endswitch;

	require ("includes/mysqli.php");
	require ("includes/even_odd.php");
	require ("includes/calculate_50_50.php");
	require ("includes/build_rank_table.php");
	require ("includes/calculate_draw.php"); 
	require ("includes/calculate_rank.php");
	require ("includes/next_draw.php");
	
	$debug = 0;

	$combo_count = 2;

	$handle = fopen("c:\combo_test.txt", "w");

	$query = "SELECT * FROM $draw_table ";
	$query .= "ORDER BY date ASC ";
	$query .= "LIMIT 1 ";

	$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

	$dcount = 0;
	
	// get each row
	while($row = mysqli_fetch_array($mysqli_result))
	{
			print "combo_count = $combo_count<p>";
			print "<b>$row[1] - $row[2] - $row[3] - $row[4] - $row[5]</b><br>";
			print "<p>";
			$z = 0;

			for  ($x = 1; $x < $balls_drawn; $x++) // first index
			{
				print "<b>------------ start x = $x</b><br>";
				for ($y = $x+1; $y <= $balls_drawn; $y++) // second index
				{
					
					print "------------ end c = $c<br>";
				}
				print "<font color=\"#ff0000\">------------ end y = $y</font><br>";
			}
			print "<b>------------ end x = $x</b><br>";
		}

			print "<h2>dcount = $dcount</h2>";
			fclose($handle);
	
?>
