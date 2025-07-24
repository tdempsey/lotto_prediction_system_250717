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
			
			//$dc = array_fill (0, ($combo_count + 1), 0);
			//print "<p>x = $x<br>";
			//print "y = $y<br>";
			//print "c = $c<br>";
			//print "index = $index<br>";
			//print "dc[index] = {$dc[$index-1]}<br>";
			//print "row[c] = $row[$c]<br>";
			print "combo_count = $combo_count<p>";
			//print_r ($dc);
			print "<b>$row[1] - $row[2] - $row[3] - $row[4] - $row[5]</b><br>";
			//print_r ($row);
			print "<p>";
			//$combo_count = 0;
			$z = 0;

			for  ($x = 1; $x < $balls_drawn; $x++) // first index
			{
				print "<b>------------ start x = $x</b><br>";
				for ($y = $x+1; $y <= $balls_drawn; $y++) // second index
				{
					print "<font color=\"#ff0000\">------------ start y = $y</font><br>";
					for ($c = $y; $c <= $balls_drawn; $c++) // incr index
					{
						for ($d = $c; $d <= $balls_drawn; $d++) // incr index
						{
							$dc = array ();
							$dc[0] = $row[$x];
							fwrite($handle, "$dc[0], ");
							for ($index = $c; $index < ($balls_drawn-$combo_count+1); $index++) // incr index
							{
								$dc[$index] = $row[$index];
							}
							
							for ($e = $index; $e <= $balls_drawn; $e++) // incr index
							{
								$dc[$index] = $row[$e];
								fwrite($handle, "$dc[$index], ");
								print "<b>";
								print_r ($dc);
								print "</b><br>";
								for ($m = 0; $m < $combo_count; $m++)
								{
									fwrite($handle, "$dc[$m], ");
								}
								fwrite($handle, "\n");
								$dcount++;
							}
						}
					}
					print "------------ end c = $c<br>";
				}
				print "<font color=\"#ff0000\">------------ end y = $y</font><br>";
			}
			print "<b>------------ end x = $x</b><br>";
		}

			print "<h2>dcount = $dcount</h2>";
			fclose($handle);
	
			/*
			for ($c = 1; $c <= 15; $c++)
			{
				switch ($c) { 
				   case 1: 
					   $d1 = $row[1];
					   $d2 = $row[2];
					   break; 
				   case 2: 
					   $d1 = $row[1];
					   $d2 = $row[3];
					   break; 
				   case 3: 
					   $d1 = $row[1];
					   $d2 = $row[4];
					   break; 
				   case 4: 
					   $d1 = $row[1];
					   $d2 = $row[5];
					   break;
				   case 5: 
					   $d1 = $row[1];
					   $d2 = $row[6];
					   break;
				   case 6: 
					   $d1 = $row[2];
					   $d2 = $row[3];
					   break;
				   case 7: 
					   $d1 = $row[2];
					   $d2 = $row[4];
					   break; 
				   case 8: 
					   $d1 = $row[2];
					   $d2 = $row[5];
					   break; 
				   case 9: 
					   $d1 = $row[2];
					   $d2 = $row[6];
					   break; 
				   case 10: 
					   $d1 = $row[3];
					   $d2 = $row[4];
					   break;
				   case 11: 
					   $d1 = $row[3];
					   $d2 = $row[5];
					   break;
				   case 12: 
					   $d1 = $row[3];
					   $d2 = $row[6];
					   break;
				   case 13: 
					   $d1 = $row[4];
					   $d2 = $row[5];
					   break;
				   case 14: 
					   $d1 = $row[4];
					   $d2 = $row[6];
					   break;
				   case 15: 
					   $d1 = $row[5];
					   $d2 = $row[6];
					   break;
				} 
				*/
	
?>
