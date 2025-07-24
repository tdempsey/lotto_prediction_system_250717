<?php
	$game = 6;
	$debug = 0;

	// Game ----------------------------- Game
	//$game = 1; // Georgia Fantasy 5
	//$game = 2; // Georgia Mega Millions
	//$game = 3; // Georgia Lotto South
	$game = 4; // Florida Fantasy 5
	//$game = 5; // Florida Mega Money
	//$game = 6; // Florida Lotto
	//$game = 7; // Powerball
	// Game ----------------------------- Game

	require ("includes/games_switch.incl");

	require ("includes/mysqli.php");

	// ----------------------------------------------------------------------------------
	function test_columns($cola,$colb) 
	{
		require ("includes/mysqli.php"); 

		global $debug, $game, $draw_prefix, $draw_table_name, $balls, $balls_drawn;

		print "<h3><a name=\"col$cola$colb\">&nbsp;</a>";
		for($x=1; $x < ($balls_drawn-1); $x++)
		{
			for($y = $x+1; $y <= $balls_drawn; $y++)
			{
				print "<a href=\"#col$x$y\">Col$x$y</a> - ";
			}
		}
		$x = $balls_drawn - 1;
		print "<a href=\"#col$x$balls_drawn\">Col$x$balls_drawn</a></h3>";

		for($index=1; $index < ($balls+1); $index++)
		{
			$num_array[$index]=0;
			$num_date[$index]="1962-08-17";
		}

		// **************************************************************************

		for($index1=1; $index1 <= ($balls*$balls); $index1++)
		{
				$zero_array = array(0,000-00-00);
				$pick_array[$index1] = $zero_array;	
				$num1 = intval($index1/$balls)+1;
				$num2 = $index1-(($num1-1)*$balls); 

				$col_total_array = array_fill (0, 9, 0);
				//$col_total_array[$index1] = $col_zero_array;
		}

		$query = "SELECT * FROM $draw_table_name ";
		$query .= "ORDER BY date DESC ";

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

		$dcount = 1;

		while($row = mysqli_fetch_array($mysqli_result))
		{
			$numa = $row[$cola];
			$numb = $row[$colb];

			$pick_array[($numa-1)*$balls+$numb][0]++;
			if ($row[date] > $pick_array[($numa-1)*$balls+$numb][1])
			{
				$pick_array[($numa-1)*$balls+$numb][1] = $row[date];
			}

			$dcount++;
		}

		$Query = "DROP TABLE IF EXISTS $draw_prefix";
		$Query .= "temp_col_";
		$Query .= "$cola";
		$Query .= "_$colb ";

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

		$Query = "CREATE TABLE $draw_prefix";
		$Query .= "temp_col_";
		$Query .= "$cola";
		$Query .= "_$colb (";
		$Query .= "id int(10) unsigned NOT NULL auto_increment, ";
		$Query .= "num1 tinyint(3) unsigned NOT NULL default '0', ";
		$Query .= "num2 tinyint(3) unsigned NOT NULL default '0', ";
		$Query .= "count_365 tinyint(4) unsigned NOT NULL default '0', ";
		$Query .= "count_all tinyint(4) unsigned NOT NULL default '0', ";
		$Query .= "last_date date NOT NULL default '1962-08-17', ";
		$Query .= "PRIMARY KEY  (id), ";
		$Query .= "KEY num1 (num1), ";
		$Query .= "KEY num2 (num2) ";
		$Query .= ") TYPE=MyISAM ";

		//print "$Query<p>";

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

		for($index=1; $index <= ($balls*$balls); $index++)
		{
			if ($index % $balls == 0) {
				$num1 = intval($index/$balls);
			} else {
				$num1 = intval($index/$balls)+1;
			}
			$num2 = $index-(($num1-1)*$balls); 
			$count_all = $pick_array[$index][0];
			$date = $pick_array[$index][1];
			
			// this can be moved below without the long calculation
			if ($num1 != $num2)
			{
				$Query = "INSERT INTO $draw_prefix";
				$Query .= "temp_col_";
				$Query .= "$cola";
				$Query .= "_$colb ";
				$Query .= "VALUES ('0', ";
				$Query .= "'$num1', ";
				$Query .= "'$num2', ";
				$Query .= "'$count_365', "; // add count_365
				$Query .= "'$count_all', ";
				$Query .= "'$date')"; 
			
				$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link)); 
			}
		} 

		//start table
		print("<TABLE BORDER=\"1\">\n");

		//create header row
		print("<TR>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Col$cola</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Col$colb</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">&nbsp;5&nbsp;</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">&nbsp;10&nbsp;</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">&nbsp;30&nbsp;</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">&nbsp;50&nbsp;</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">100</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">250</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><b>365</b></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">500</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">1000</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">5000</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Prev</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Last</center></TD>\n");
		print("</TR>\n");

		$cola_previous = 0;
		$colb_previous = 0;

		for($a = 1; $a < $balls; $a++)
		{
			$cola_total = 0;

			for($b = $a+1; $b <= $balls; $b++)
			{
				$colb_total = 0;

				$col_tot_5 =	0;
				$col_tot_10 =	0;
				$col_tot_30 =	0;
				$col_tot_50 =	0;
				$col_tot_100 =	0;
				$col_tot_300 =	0;
				$col_tot_365 =	0;
				$col_tot_500 =	0;
				$col_tot_1000 =	0;
				$col_tot_5000 =	0;

				$prev_date = "1962-08-17";
				$last_date = "1962-08-17";

				$query = "SELECT * FROM $draw_table_name ";
				//$query .= "WHERE b$cola = $a and b$colb = $b ";
				$query .= "ORDER BY DATE DESC ";
				//print "$query<br>";

				$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

				$num_rows = mysqli_num_rows($mysqli_result);

				//print "<b>cola = $cola, colb = $colb</b><br>";
				//print "<b>num_rows = $num_rows</b><br>";
				//print "a = $a, b = $b<br>";

				$dcount = 1;

				while($row = mysqli_fetch_array($mysqli_result))
				{	
					if ($row[$cola] == $a && $row[$colb] == $b)
					{
						//print "$row[date] - cola = $cola, colb = $colb<br>";
						if ($dcount <= 5)
						{
							$col_tot_5++;
							$col_tot_10++;
							$col_tot_30++;
							$col_tot_50++;
							$col_tot_100++;
							$col_tot_300++;
							$col_tot_365++;
							$col_tot_500++;
							$col_tot_1000++;
							$col_tot_5000++;
						} elseif ($dcount <= 10) {
							$col_tot_10++;
							$col_tot_30++;
							$col_tot_50++;
							$col_tot_100++;
							$col_tot_300++;
							$col_tot_365++;
							$col_tot_500++;
							$col_tot_1000++;
							$col_tot_5000++;
						} elseif ($dcount <= 30) {
							$col_tot_30++;
							$col_tot_50++;
							$col_tot_100++;
							$col_tot_300++;
							$col_tot_365++;
							$col_tot_500++;
							$col_tot_1000++;
							$col_tot_5000++;
						} elseif ($dcount <= 50) {
							$col_tot_50++;
							$col_tot_100++;
							$col_tot_300++;
							$col_tot_365++;
							$col_tot_500++;
							$col_tot_1000++;
							$col_tot_5000++;
						} elseif ($dcount <= 100) {
							$col_tot_100++;
							$col_tot_300++;
							$col_tot_365++;
							$col_tot_500++;
							$col_tot_1000++;
							$col_tot_5000++;
						} elseif ($dcount <= 250) {
							$col_tot_300++;
							$col_tot_365++;
							$col_tot_500++;
							$col_tot_1000++;
							$col_tot_5000++;
						} elseif ($dcount <= 365) {
							$col_tot_365++;
							$col_tot_500++;
							$col_tot_1000++;
							$col_tot_5000++;
						} elseif ($dcount <= 500) {
							$col_tot_500++;
							$col_tot_1000++;
							$col_tot_5000++;
						} elseif ($dcount <= 1000) {
							$col_tot_1000++;
							$col_tot_5000++;
						} elseif ($dcount <= 5000) {
							$col_tot_5000++;
						} // if dcount
						
						if ($row[date] > $last_date)
						{
							$last_date = $row[date];
						} elseif ($row[date] > $prev_date) {
							$prev_date = $row[date];
						}
					} // if cola colb

					$dcount++;
				} // while

				print("<TR>\n");
				print("<TD><CENTER><b>$a</b></CENTER></TD>\n");
				print("<TD><CENTER><b>$b</b></CENTER></TD>\n");
				if ($col_tot_5 > 1) {
					print("<TD BGCOLOR=\"#CCFFFF\"><CENTER><b>$col_tot_5</b></CENTER></TD>\n");
				} else {
					print("<TD><CENTER>$col_tot_5</CENTER></TD>\n");
				}
				if ($col_tot_10 > 1) {
					print("<TD BGCOLOR=\"#CCFFFF\"><CENTER><b>$col_tot_10</b></CENTER></TD>\n");
				} else {
					print("<TD><CENTER>$col_tot_10</CENTER></TD>\n");
				}
				if ($col_tot_30 > 1) {
					print("<TD BGCOLOR=\"#CCFFFF\"><CENTER><b>$col_tot_30</b></CENTER></TD>\n");
				} else {
					print("<TD><CENTER>$col_tot_30</CENTER></TD>\n");
				}
				if ($col_tot_50 > 1) {
					print("<TD BGCOLOR=\"#CCFFFF\"><CENTER><b>$col_tot_50</b></CENTER></TD>\n");
				} else {
					print("<TD><CENTER>$col_tot_50</CENTER></TD>\n");
				}
				if ($col_tot_100 > 1) {
					print("<TD BGCOLOR=\"#CCFFFF\"><CENTER><b>$col_tot_100</b></CENTER></TD>\n");
				} else {
					print("<TD><CENTER>$col_tot_100</CENTER></TD>\n");
				}
				if ($col_tot_300 > 1) {
					print("<TD BGCOLOR=\"#CCFFFF\"><CENTER><b>$col_tot_300</b></CENTER></TD>\n");
				} else {
					print("<TD><CENTER>$col_tot_300</CENTER></TD>\n");
				}
				if ($col_tot_365 > 1) {
					print("<TD BGCOLOR=\"#CCFFFF\"><CENTER><b>$col_tot_365</b></CENTER></TD>\n");
				} else {
					print("<TD><CENTER>$col_tot_365</CENTER></TD>\n");
				}
				if ($col_tot_500 > 1) {
					print("<TD BGCOLOR=\"#CCFFFF\"><CENTER><b>$col_tot_500</b></CENTER></TD>\n");
				} else {
					print("<TD><CENTER>$col_tot_500</CENTER></TD>\n");
				}
				if ($col_tot_1000 > 1) {
					print("<TD BGCOLOR=\"#CCFFFF\"><CENTER><b>$col_tot_1000</b></CENTER></TD>\n");
				} else {
					print("<TD><CENTER>$col_tot_1000</CENTER></TD>\n");
				}
				if ($col_tot_5000 > 1) {
					print("<TD BGCOLOR=\"#CCFFFF\"><CENTER><b>$col_tot_5000</b></CENTER></TD>\n");
				} else {
					print("<TD><CENTER>$col_tot_5000</CENTER></TD>\n");
				}
				if ($prev_date == "1962-08-17") {
					print("<TD align=center>---</TD>\n");
				} elseif ($prev_date < "2007-01-01") {
					print("<TD><font color=\"#ff0000\">$prev_date</font></TD>\n");
				} else {
					print("<TD><CENTER>$prev_date</CENTER></TD>\n");
				}
				if ($last_date == "1962-08-17") {
					print("<TD align=center>---</TD>\n");
				} elseif ($last_date < "2007-01-01") {
					print("<TD><font color=\"#ff0000\">$last_date</font></TD>\n");
				} else {
					print("<TD>$last_date</TD>\n");
				}
				print("</TR>\n");
				
				$query9 = "UPDATE $draw_prefix";
				$query9 .= "temp_col_";
				$query9 .= "$cola";
				$query9 .= "_$colb ";
				$query9 .= "SET count_365 = $col_tot_365 ";
				$query9 .= "WHERE num1 = $a AND num2 = $b ";

				$mysqli_result9 = mysqli_query($mysqli_link, $query9) or die (mysqli_error($mysqli_link));
			} // b
			print("<TR>\n");
			print("<TD BGCOLOR=\"#CCCCCC\">Col$cola</TD>\n");
			print("<TD BGCOLOR=\"#CCCCCC\">Col$colb</TD>\n");
			print("<TD BGCOLOR=\"#CCCCCC\">&nbsp;5&nbsp;</TD>\n");
			print("<TD BGCOLOR=\"#CCCCCC\">&nbsp;10&nbsp;</TD>\n");
			print("<TD BGCOLOR=\"#CCCCCC\">&nbsp;30&nbsp;</TD>\n");
			print("<TD BGCOLOR=\"#CCCCCC\">&nbsp;50&nbsp;</TD>\n");
			print("<TD BGCOLOR=\"#CCCCCC\">100</TD>\n");
			print("<TD BGCOLOR=\"#CCCCCC\">250</TD>\n");
			print("<TD BGCOLOR=\"#CCCCCC\"><b>365</b></TD>\n");
			print("<TD BGCOLOR=\"#CCCCCC\">500</TD>\n");
			print("<TD BGCOLOR=\"#CCCCCC\">1000</TD>\n");
			print("<TD BGCOLOR=\"#CCCCCC\">5000</TD>\n");
			print("<TD BGCOLOR=\"#CCCCCC\"><center>Prev</center></TD>\n");
			print("<TD BGCOLOR=\"#CCCCCC\"><center>Last</center></TD>\n");
			print("</TR>\n");
		} // a

		//end table
		print("</TABLE><p>\n");

		$table_temp = $draw_prefix . "temp_col_" . $cola . "_" . $colb;

		print "<h3>$table_temp Updated!</h3>";
	}

	// ----------------------------------------------------------------------------------
	function test_columns_old($cola,$colb) 
	{
		require ("includes/mysqli.php"); 

		global $debug, $game, $draw_prefix, $draw_table_name, $balls, $balls_drawn;

		print "<h3><a name=\"col$cola$colb\">&nbsp;</a>";
		for($x=1; $x < ($balls_drawn-1); $x++)
		{
			for($y = $x+1; $y <= $balls_drawn; $y++)
			{
				print "<a href=\"#col$x$y\">Col$x$y</a> - ";
			}
		}
		$x = $balls_drawn - 1;
		print "<a href=\"#col$x$balls_drawn\">Col$x$balls_drawn</a></h3>";

		for($index=1; $index < ($balls+1); $index++)
		{
			$num_array[$index]=0;
			$num_date[$index]="1962-08-17";
		}

		// **************************************************************************

		for($index1=1; $index1 <= ($balls*$balls); $index1++)
		{
				$zero_array = array(0,000-00-00);
				$pick_array[$index1] = $zero_array;	
				$num1 = intval($index1/$balls)+1;
				$num2 = $index1-(($num1-1)*$balls); 
		}

		$query = "SELECT * FROM $draw_table_name ";
		$query .= "ORDER BY date DESC ";
		//$query .= "LIMIT 26 ";

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

		$dcount = 1;

		while($row = mysqli_fetch_array($mysqli_result))
		{
			$numa = $row[$cola];
			$numb = $row[$colb];

			$pick_array[($numa-1)*$balls+$numb][0]++;
			if ($row[date] > $pick_array[($numa-1)*$balls+$numb][1])
			{
				$pick_array[($numa-1)*$balls+$numb][1] = $row[date];
			}

			$dcount++;
		}

		$Query = "DROP TABLE IF EXISTS $draw_prefix";
		$Query .= "temp_col_";
		$Query .= "$cola";
		$Query .= "_$colb ";

		//print "$Query<P>";

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

		$Query = "CREATE TABLE $draw_prefix";
		$Query .= "temp_col_";
		$Query .= "$cola";
		$Query .= "_$colb (";
		$Query .= "id int(10) unsigned NOT NULL auto_increment, ";
		$Query .= "num1 tinyint(3) unsigned NOT NULL default '0', ";
		$Query .= "num2 tinyint(3) unsigned NOT NULL default '0', ";
		$Query .= "count tinyint(4) unsigned NOT NULL default '0', ";
		$Query .= "last_date date NOT NULL default '1962-08-17', ";
		$Query .= "PRIMARY KEY  (id), ";
		$Query .= "KEY num1 (num1), ";
		$Query .= "KEY num2 (num2) ";
		$Query .= ") TYPE=MyISAM ";

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

		for($index=1; $index <= ($balls*$balls); $index++)
		{
			if ($index % $balls == 0) {
				$num1 = intval($index/$balls);
			} else {
				$num1 = intval($index/$balls)+1;
			}
			$num2 = $index-(($num1-1)*$balls); 
			$count = $pick_array[$index][0];
			$date = $pick_array[$index][1];
			

			if ($num1 != $num2)
			{
				$Query = "INSERT INTO $draw_prefix";
				$Query .= "temp_col_";
				$Query .= "$cola";
				$Query .= "_$colb ";
				$Query .= "VALUES ('0', ";
				$Query .= "'$num1', ";
				$Query .= "'$num2', ";
				$Query .= "'$count', ";
				$Query .= "'$date')"; 

				//print "$Query<P>";
			
				$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link)); 
			}
		} 

		//start table
		print("<TABLE BORDER=\"1\">\n");

		//create header row
		print("<TR><B>\n");

		print("<TD BGCOLOR=\"#CCCCCC\">Col$cola</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Col$colb</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Total</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Last Match</TD>\n");
		print("</B></TR>\n");

		// ---------------------------------------------------------------------
		$query = "SELECT * FROM $draw_prefix";
		$query .= "temp_col_";
		$query .= "$cola";
		$query .= "_$colb ";
		$query .= "WHERE count > 1 ";
		$query .= "ORDER BY num1,num2 ASC ";
		//print "$query<p>";
		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

		// get each row
		while($row = mysqli_fetch_array($mysqli_result))
		{
			print("<TR>\n");

			print("<TD>$row[num1]</TD>\n");
			print("<TD>$row[num2]</TD>\n");
			if ($row[1] == $row[2]) {
				print("<TD BGCOLOR=\"#CCCCCC\">&nbsp;</TD>\n");
			} else {
				print("<TD>$row[3]</TD>\n");
			}
			print("<TD>$row[4]</TD>\n");

			print("</TR>\n");

		}

		//end table
		print("</TABLE><p>\n");
	}


	function lot_grid ($cola,$colb)
	{
		// pair grid - need for all numbers and abbreviated

		global $debug, $game, $game_name, $balls, $balls_drawn, $mega_balls, $draw_table_name, $draw_prefix, $grid_col_flag;
		
		require ("includes/mysqli.php");

		print "<H2>Pair Grid - col$cola$colb - 365/ALL</H2>";

		$table_temp = $draw_prefix . "temp_col_" . $cola . "_" . $colb;

		//start table
		print("<TABLE BORDER=\"1\">\n");

		//create header row
		print("<TR><B>\n");
		print("<TD width=20>&nbsp;</TD>\n");
		for ($x = 1; $x <= $balls; $x++)
		{
			print("<TD width=25><b><center>$x</center></b></TD>\n");
		}
		print("<TD>&nbsp;</TD>\n");
		print("</TR></B>\n");

		// process rows
		for ($x = 1; $x <= $balls; $x++)
		{
			print("<TR>\n");
			print("<TD align=center><b>$x</b></TD>\n");
			for ($y = 1; $y <= $balls; $y++)
			{
				if ($x != $y)
				{
					$query5 = "SELECT * FROM $table_temp ";
					$query5 .= "WHERE num1 = $x AND num2 = $y "; 

					//print "$query5<p>";
					
					$mysqli_result5 = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));

					$row = mysqli_fetch_array($mysqli_result5);

					if ($row[count_all] > $grid_col_flag)
					{
						print("<TD align=center><font color=\"#ff0000\"><b>$row[count_365]/$row[count_all]</b></font></TD>\n");
					} else {
						print("<TD align=center>$row[count_365]/$row[count_all]</TD>\n");
					}
				} else {
					print("<TD>&nbsp;</TD>\n");
				}
			}
			    print("<TD align=center><b>$x</b></TD>\n");
				print("</TR>\n");
		}

		//create footer row
		print("<TR><B>\n");
		print("<TD>&nbsp;</TD>\n");
		for ($x = 1; $x <= $balls; $x++)
		{
			print("<TD align=center><b>$x</b></TD>\n");
		}
		print("<TD>&nbsp;</TD>\n");
		print("</TR></B>\n");

		//end table
		print("</TABLE>\n");
	}
	
	///////////////////////////////////////////////////////////////////////////////////////////////
	
	//start HTML page
	print("<HTML>\n");
	print("<HEAD>\n");
	print("<TITLE>$game_name Column Test</TITLE>\n");
	print("</HEAD>\n");

	print("<BODY>\n");
	print("<H1>$game_name Column Save Test</H1>\n");

	for ($x = 1; $x < $balls_drawn; $x++)
	{
		for ($y = $x+ 1; $y <= $balls_drawn; $y++)
		{
			test_columns($cola=$x,$colb=$y);
			lot_grid ($cola=$x,$colb=$y);
		}
	}
	
	//print("</TABLE>\n");

	// **************************************************************************

	//close page
	print("</BODY>\n");
	print("</HTML>\n");

?>
