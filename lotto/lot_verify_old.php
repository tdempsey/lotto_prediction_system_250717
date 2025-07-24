<?php

	// add tables population for combos, pairs
	// add test for missing draws
	// recalcu;ate draw includes
	// add db class
	// error checking - all modules
	// fix pair count

	// Game ----------------------------- Game
	//$game = 1; // Georgia Fantasy 5
	//$game = 2; // Georgia Mega Millions
	//$game = 3; // Georgia Lotto South
	//$game = 4; // Florida Fantasy 5
	//$game = 5; // Florida Mega Money
	$game = 6; // Florida Lotto
	//$game = 7; // Powerball
	// Game ----------------------------- Game
	
	require ("includes/games_switch.incl");

	require ("includes/mysqli.php");
	require ("includes/db.class");
	require ("includes/even_odd.php");
	require ("includes/calculate_50_50.php");
	require ("includes/build_rank_table.php");
	require ("includes/calculate_draw.php"); 
	require ("includes/calculate_rank.php");
	require ("includes/next_draw.php");
	
	$debug = 0;

	function lot_display ($limit)
	{ 
		global $debug, $game, $game_name, $balls, $balls_drawn, $mega_balls, $draw_table, $draw_prefix;
	
		require ("includes/mysqli.php");
		
		print("<a name=\"$limit\"><H2>Lotto Display - $game_name - Limit $limit</H2></a>\n");

		print "<h4><a href=\"#unsorted$limit\">Unsorted $limit</a> | <a href=\"#sorted$limit\">Sorted $limit</a> | <a href=\"#pairs$limit\">Pairs $limit</a> | <a href=\"#grid$limit\">Grid $limit</a></h4>";

		// initalize variables [include]
		require ("includes/init_display.incl");
	
		//start table
		print("<TABLE BORDER=\"1\">\n");
	
		//create header row
		print("<TR><B>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Date</TD>\n");
		for($index = 1; $index <= $balls_drawn; $index++)
		{
			print("<TD BGCOLOR=\"#CCCCCC\"><center>Pick $index</center></TD>\n");
		}
		
		if ($mega_balls)
		{
			print("<TD BGCOLOR=\"#CCCCCC\">MB</TD>\n");
		}

		print("<TD BGCOLOR=\"#CCCCCC\">Sum<br>Rep</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">SumX<br>Rank</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Table</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Wheel</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Wheel %</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Wheel<br>Count</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">EO50</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">EO50<br>Table</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Width</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Seq2</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Seq3</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">DC1</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">DC2</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">DC3</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">X_num</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">X_1</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">X_2</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">X_3</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Mod</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Due</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Soon</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Hot</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Warm</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Cold</TD>\n");
		for($index = 2; $index <= $balls_drawn; $index++)
		{
			print("<TD BGCOLOR=\"#CCCCCC\"><center>Combin<br>$index</center></TD>\n");
		}

		print("</B></TR>\n");
	
		$dcount = 1;

		$table_temp = $draw_prefix . "temp_2_" . $limit;

		//print("<h3>$table_temp</h3>");

		// Table structure for table 'mm_temp_2'
		$query3 = "DROP TABLE IF EXISTS $table_temp ";
	
		//print("$query3\n");
	
		$mysqli_result3 = mysqli_query($mysqli_link, $query3) or die (mysqli_error($mysqli_link));
	
		#
		# Table 
		#
	
		$query4 = "CREATE TABLE $table_temp ( ";
	  	$query4 .= "id int(10) unsigned NOT NULL auto_increment, ";
		$query4 .= "num1 tinyint(3) unsigned NOT NULL default '0', ";
	  	$query4 .= "num2 tinyint(3) unsigned NOT NULL default '0', ";
	  	$query4 .= "count int(5) unsigned NOT NULL default '0', ";
		$query4 .= "num2_total int(5) unsigned NOT NULL default '0', ";
	  	$query4 .= "last_date date NOT NULL default '1962-08-17', ";
	  	$query4 .= "PRIMARY KEY  (id), ";
	  	$query4 .= "KEY num1 (num1), ";
	  	$query4 .= "KEY num2 (num2) ";
		$query4 .= ") TYPE=MyISAM ";
	
		//print("<P>$query4<p>");
	
		$mysqli_result4 = mysqli_query($mysqli_link, $query4) or die (mysqli_error($mysqli_link));

		// get everything from draw table
		$query5 = "SELECT * FROM $draw_table ";
		$query5 .= "ORDER BY date DESC ";
		$query5 .= "LIMIT $limit "; 

		//print("<P>$query5<p>");
	
		$mysqli_result5 = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));
	
		// get each row
		while($row = mysqli_fetch_array($mysqli_result5))
		{
			// build draw array [0..x]
			$draw = array ();
	
			for ($x = 1; $x <= $balls_drawn; $x++)
			{
				array_push($draw, $row[$x]);
			}
	
			if ($mega_balls)
			{
				$MB = $row[mb];
			}
	
			$draw_sum  = $row[sum];
			$draw_even  = $row[even];
			$draw_odd  = $row[odd];
			
			if ($draw_sum == 0)
			{
				// update sum
				require ("includes/update_sum.incl");
			}
	
			if ($draw_even == 0 && $draw_odd == 0)
			{
				// update even/odd
				require ("includes/update_even_odd.incl");
			}
			
			print("<TR>\n");
	
			print("<TD>$row[date]</TD>\n");
	
			for($index = 1; $index <= $balls_drawn; $index++)
			{
				print("<TD>$row[$index]</TD>\n");
			}
			
			if ($mega_balls)
			{
				print("<TD>$row[mb]</TD>\n");
			}
	
			print("<TD>$draw_sum</TD>\n");

			$z = intval($draw_sum/10);

			$table_sum_temp = $draw_prefix . "temp_sum";

			$query6 = "SELECT * FROM $table_sum_temp ";
			$query6 .= "WHERE num = $z ";
		
			$mysqli_result6 = mysqli_query($mysqli_link, $query6) or die (mysqli_error($mysqli_link));

			$row_sum = mysqli_fetch_array($mysqli_result6);

			print("<TD><center>$row_sum[count]</center></TD>\n");
	
			print("</TR>\n");
			
			for ($x = 1; $x <= $balls_drawn; $x++)
			{
				$y = $row[$x];
				$num_array[$y]++;
				if ($row[date] > $num_date[$y])
				{
					$num_date[$y] = $row[date];
				}
			}
			
			if ($mega_balls)
			{
				$mb_array[$MB]++;
				$mb_date[$MB] = $row[date];
			}
			//print("<h3>Start draw</h3>");

			for ($x = 1; $x < $balls_drawn; $x++)
			{
				for ($y = ($x+1); $y <= $balls_drawn; $y++)
				{
					$query2 = "INSERT INTO $table_temp ";
					$query2 .= "VALUES ('0', ";
					$query2 .= "'$row[$x]', ";
					$query2 .= "'$row[$y]', ";
					$query2 .= "'1', ";
					$query2 .= "'0', ";
					$query2 .= "'$row[date]')"; 
				
					//print("$query2<br>");
					$mysqli_result = mysqli_query($mysqli_link, $query2) or die (mysqli_error($mysqli_link));
				}
			}
			
			//print("<h3>balls_drawn = $balls_drawn</h3>");
	
			for ($x = $balls_drawn; $x >= 2; $x--)
			{
				for ($y = ($x-1); $y >= 1; $y--)
				{
					$query2 = "INSERT INTO $table_temp ";
					$query2 .= "VALUES ('0', ";
					$query2 .= "'$row[$x]', ";
					$query2 .= "'$row[$y]', ";
					$query2 .= "'1', ";
					$query2 .= "'0', ";
					$query2 .= "'$row[date]')"; 
				
					//print("$query2<br>");
					$mysqli_result = mysqli_query($mysqli_link, $query2) or die (mysqli_error($mysqli_link));
				}
			}
			
			$dcount++;
		}
	
		//end table
		print("</TABLE>\n");
	
		///////////////////////////////////////////////////////////////////////////////////////////////
		/*
		print "<h4><a href=\"#unsorted$limit\">Unsorted $limit</a> | <a href=\"#sorted$limit\">Sorted $limit</a> | <a href=\"#pairs$limit\">Pairs $limit</a> | <a href=\"#grid$limit\">Grid $limit</a><a name=\"unsorted$limit\">&nbsp;</a></h4>";

		// process and print unsorted table
		require ("includes/unsorted_table.incl");

		print "<h4><a href=\"#unsorted$limit\">Unsorted $limit</a> | <a href=\"#sorted$limit\">Sorted $limit</a> | <a href=\"#pairs$limit\">Pairs $limit</a> | <a href=\"#grid$limit\">Grid $limit</a><a name=\"sorted$limit\">&nbsp;</a></h4>";

		// process and print sorted table
		require ("includes/sorted_table.incl");

		// process and print megaball table
		require ("includes/megaball_table.incl");
	
		///////////////////////////////////////////////////////////////////////////////

		$table_temp = $draw_prefix . "temp_2_" . $limit;

		print "<h4><a href=\"#unsorted$limit\">Unsorted $limit</a> | <a href=\"#sorted$limit\">Sorted $limit</a> | <a href=\"#pairs$limit\">Pairs $limit</a> | <a href=\"#grid$limit\">Grid $limit</a><a name=\"pairs$limit\">&nbsp;</a></h4>";
	
		//start table
		print("<P>");
		print("<TABLE BORDER=\"1\">\n");

		//create header row
		print("<TR><B>\n");
	
		print("<TD BGCOLOR=\"#CCCCCC\">Num1</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Num2</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Total</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Last Match</TD>\n");
		print("</B></TR>\n");

		for ($x = 1; $x < $balls; $x++)
		{
			for ($y = ($x+1); $y <= $balls; $y++)
			{
				$query5 = "SELECT * FROM $table_temp ";
				$query5 .= "WHERE num1 = $x AND num2 = $y "; 
				$query5 .= "ORDER BY last_date DESC ";

				//print("$query5<br>");
				
				$mysqli_result5 = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));

				$num_rows = mysqli_num_rows($mysqli_result5);

				$row = mysqli_fetch_array($mysqli_result5);

				if ($num_rows)
				{
					print("<TR>\n");
					print("<TD>$x</TD>\n");
					print("<TD>$y</TD>\n");
					print("<TD>$num_rows</TD>\n");
					print("<TD>$row[last_date]</TD>\n");
					print("</TR>\n");
				}
			}
		}
	
		//end table
		print("</TABLE>\n");
		*/
	}

	function lot_columns ($limit)
	{ 
		global $debug, $game, $game_name, $balls, $balls_drawn, $mega_balls, $draw_table, $draw_prefix;
	
		require ("includes/mysqli.php");
		
		print("<a name=\"$limit\"><H2>Lotto Display - $game_name - Limit $limit</H2></a>\n");

		print "<h4><a href=\"#unsorted$limit\">Unsorted $limit</a> | <a href=\"#sorted$limit\">Sorted $limit</a> | <a href=\"#pairs$limit\">Pairs $limit</a> | <a href=\"#grid$limit\">Grid $limit</a></h4>";

		// initalize variables [include]
		require ("includes/init_display.incl");
	
		//start table
		print("<TABLE BORDER=\"1\">\n");
	
		//create header row
		print("<TR><B>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Date</TD>\n");
		for($index = 1; $index <= $balls_drawn; $index++)
		{
			print("<TD BGCOLOR=\"#CCCCCC\"><center>Pick $index</center></TD>\n");
		}
		
		if ($mega_balls)
		{
			print("<TD BGCOLOR=\"#CCCCCC\">MB</TD>\n");
		}

		print("<TD BGCOLOR=\"#CCCCCC\">Sum<br>Rep</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Sum<br>Rank</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Table</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Wheel</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Wheel %</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Wheel<br>Count</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">EO50</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">EO50<br>Table</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Width</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Seq2</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Seq3</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">DC1</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">DC2</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">DC3</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">X_num</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">X_1</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">X_2</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">X_3</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Mod</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Due</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Soon</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Hot</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Warm</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Cold</TD>\n");
		for($index = 2; $index <= $balls_drawn; $index++)
		{
			print("<TD BGCOLOR=\"#CCCCCC\"><center>Combin<br>$index</center></TD>\n");
		}

		print("</B></TR>\n");

	}

	function lot_combin ($limit)
	{ 
		global $debug, $game, $game_name, $balls, $balls_drawn, $mega_balls, $draw_table, $draw_prefix;
	
		require ("includes/mysqli.php");
		
		print("<a name=\"$limit\"><H2>Lotto Display - $game_name - Limit $limit</H2></a>\n");

		print "<h4><a href=\"#unsorted$limit\">Unsorted $limit</a> | <a href=\"#sorted$limit\">Sorted $limit</a> | <a href=\"#pairs$limit\">Pairs $limit</a> | <a href=\"#grid$limit\">Grid $limit</a></h4>";

		// initalize variables [include]
		require ("includes/init_display.incl");
	
		//start table
		print("<TABLE BORDER=\"1\">\n");
	
		//create header row
		print("<TR><B>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Date</TD>\n");
		for($index = 1; $index <= $balls_drawn; $index++)
		{
			print("<TD BGCOLOR=\"#CCCCCC\"><center>Pick $index</center></TD>\n");
		}
		
		if ($mega_balls)
		{
			print("<TD BGCOLOR=\"#CCCCCC\">MB</TD>\n");
		}

		print("<TD BGCOLOR=\"#CCCCCC\">Sum<br>Rep</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Sum<br>Rank</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Table</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Wheel</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Wheel %</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Wheel<br>Count</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">EO50</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">EO50<br>Table</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Width</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Seq2</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Seq3</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">DC1</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">DC2</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">DC3</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">X_num</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">X_1</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">X_2</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">X_3</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Mod</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Due</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Soon</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Hot</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Warm</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Cold</TD>\n");
		for($index = 2; $index <= $balls_drawn; $index++)
		{
			print("<TD BGCOLOR=\"#CCCCCC\"><center>Combin<br>$index</center></TD>\n");
		}

		print("</B></TR>\n");

	}
	
	function lot_combo ($combo_count)
	{ 
		global $debug, $game, $game_name, $balls, $balls_drawn, $mega_balls, $draw_table, $draw_prefix;
	
		require ("includes/mysqli.php");
		
		$table_temp = $draw_prefix . "_" . $combo_count . $balls_drawn;
		
		$query = "TRUNCATE TABLE $table_temp  ";

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));
	
		$query = "SELECT * FROM $draw_table ";
		$query .= "ORDER BY date ASC ";
		//$query .= "LIMIT 1 ";
	
		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));
	
		//start table
		print("<TABLE BORDER=\"1\">\n");
	
		//create header row
		print("<TR><B>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">&nbsp;</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Date</TD>\n");
		
		for($index = 1; $index <= $balls_drawn; $index++)
		{
			print("<TD BGCOLOR=\"#CCCCCC\">Pick $index</TD>\n");
		}
		
		print("<TD BGCOLOR=\"#CCCCCC\">Sum</TD>\n");
		print("</B></TR>\n");
	
		$dcount = 1;
	
		// get each row
		while($row = mysqli_fetch_array($mysqli_result))
		{
			//get columns
			for ($x = 1; $x <= $balls_drawn; $x++)
			{
				array_push($draw, $row[$x]);
			}
			
			$SUM  = $row[sum];
			$EVEN  = $row[even];
			$ODD  = $row[odd];
	
			print("<TR>\n");
	
			print("<TD><B>$dcount</B></TD>\n");
			print("<TD>$row[0]</TD>\n");
			
			for($index = 1; $index <= $balls_drawn; $index++)
			{
				print("<TD BGCOLOR=\"#CCCCCC\">Pick $row[$index]</TD>\n");
			}
			
			print("<TD>$SUM</TD>\n");
	
			print("</TR>\n");
			
			$dc = array_fill (0, ($combo_count), 0);
			//$combo_count = 0;
			
			for  ($x = 1; $x < $balls_drawn; $x++)
			{
				for ($y = ($x+1); $y <= $balls_drawn; $y++)
				{
					for ($z = 1; $z <= $combo_count; $z++)
					{
						$dc[$x] = $row[$z];
						
						for ($b = $z + 1; $b <= $balls_drawn; $b++) // draw index
						{
							$dc[$z] = $row[$b];
							$combo_count++;
						}
					}
				}
			}
				
				$query3 = "Insert INTO $table_temp ";
				$query3 .= "VALUES ( '0', ";
				$query3 .= "         '$row[0]', ";
				
				for($z = 1; $z <= $combo_count; $z++)
				{
					$query3 .= "         $dc[$z], ";
				}
				
				$query3 .= "	     ) ";
	
				//print "$query3<p>";
				
				$mysqli_result3 = mysqli_query($mysqli_link, $query3) or die (mysqli_error($mysqli_link));

				$dcount++;
			}
		
		print("</TABLE>\n");
	}

	function lot_grid ($limit)
	{
		// pair grid - need for all numbers and abbreviated

		global $debug, $game, $game_name, $balls, $balls_drawn, $mega_balls, $draw_table, $draw_prefix;
		
		require ("includes/mysqli.php");

		print "<a name=\"grid$limit\"><H2>Pair Grid - $limit</H2></a>";

		print "<h4><a href=\"#unsorted$limit\">Unsorted $limit</a> | <a href=\"#sorted$limit\">Sorted $limit</a> | <a href=\"#pairs$limit\">Pairs $limit</a> | <a href=\"#grid$limit\">Grid $limit</a></h4>";

		$table_temp = $draw_prefix . "temp_2_" . $limit;

		//print("<h3>$table_temp</h3>");

		//print "<H2>$table_temp</H2>";

		//start table
		print("<TABLE BORDER=\"1\">\n");

		//create header row
		print("<TR><B>\n");
		print("<TD>&nbsp;</TD>\n");
		for ($x = 1; $x <= $balls; $x++)
		{
			print("<TD><b>$x</b></TD>\n");
		}
		print("<TD>&nbsp;</TD>\n");
		print("</TR></B>\n");

		// process rows
		for ($x = 1; $x <= $balls; $x++)
		{
			print("<TR>\n");
			print("<TD><b>$x</b></TD>\n");
			for ($y = 1; $y <= $balls; $y++)
			{
				if ($x != $y)
				{
					$query5 = "SELECT * FROM $table_temp ";
					$query5 .= "WHERE num1 = $x AND num2 = $y "; 
					$query5 .= "ORDER BY last_date DESC ";

					//print("$query5<br>");
					
					$mysqli_result5 = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));

					$num_rows = mysqli_num_rows($mysqli_result5);

					if ($num_rows > 9)
					{
						print("<TD><font color=\"#0000ff\"><b>$num_rows</b></font></TD>\n");
					} else {
						print("<TD>$num_rows</TD>\n");
					}
				} else {
					print("<TD>&nbsp;</TD>\n");
				}
			}
			    print("<TD><b>$x</b></TD>\n");
				print("</TR>\n");
		}

		//create footer row
		print("<TR><B>\n");
		print("<TD>&nbsp;</TD>\n");
		for ($x = 1; $x <= $balls; $x++)
		{
			print("<TD><b>$x</b></TD>\n");
		}
		print("<TD>&nbsp;</TD>\n");
		print("</TR></B>\n");

		//end table
		print("</TABLE>\n");
	}

	//start HTML page
	print("<HTML>\n");
	print("<HEAD>\n");
	print("<TITLE>Lotto Verify - $game_name</TITLE>\n");
	print("</HEAD>\n");
	
	print("<BODY>\n");

	print "<h3><a href=\"#26\">Limit 26</a> | <a href=\"#5000\">Limit 5000</a></h3>";
		
	lot_display (10);

	print "<h4>Draw Table</h4>";

	print "<ol>";
	print "<li>In table: </li>";
	print "<li>Even/Odd, D501/D502</li>";
	print "</ol>";

	print "<h4>Draw</h4>";

	print "<ol>";
	print "<li>In table: </li>";
	print "<li>Even/Odd, D501/D502</li>";
	print "</ol>";

	print "<h4>Range</h4>";

	print "<ol>";
	print "<li>In table: </li>";
	print "<li>Even/Odd, D501/D502</li>";
	print "</ol>";

	print "<h4>Rank</h4>";

	print "<ol>";
	print "<li>In table: </li>";
	print "<li>Even/Odd, D501/D502</li>";
	print "</ol>";

	//lot_grid (10);

	print("</BODY>\n");
?>
