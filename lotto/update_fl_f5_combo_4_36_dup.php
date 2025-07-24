<?php
	$game = 4; // Florida Fantasy 5

	// include to connect to database
	require ("includes/mysqli.php");
	require ("includes/build_rank_table.php"); 
	require ("includes/calculate_rank.php");
	require ("includes/last_draws.php");
	require ("includes/last_draw_unix.php");
	require ("includes_fl_f5/combin.incl");

	require ("includes/games_switch.incl");

	//start HTML page
	print("<HTML>\n");
	print("<HEAD>\n");
	print("<TITLE>Update filter_a Dup 5/36 </TITLE>\n");
	print("</HEAD>\n");
	
	print("<BODY bgcolor=\"#FFFFFF\" text=\"#000000\">\n");

	print("<p align=\"center\"><b><font face=\"Arial, Helvetica, sans-serif\">Update filter_a Dup 5/36</font></b></p>");

	$curr_date = date('Y-m-d');

	$start_time = time(); 

	$count = array_fill (0, 11, 0);
	$display_count = 0;

	$last_dup = array_fill (0, 51, 0);

	$query9 = "UPDATE combo_5_36 ";
	$query9 .= "SET ";
	for ($d = 1; $d <= 9; $d++)
	{
		$query9 .= "    dup$d = $last_dup[$d], ";
	}
	$query9 .= "    dup$d = $last_dup[$d] ";
	
	echo "<h3>$query9</h3>";
	
	$mysqli_result9 = mysqli_query($mysqli_link, $query9) or die (mysqli_error($mysqli_link));

	for ($x = 1; $x <= 10; $x++)
	{
		${last_.$x._draws} = LastDraws($curr_date,$x);
	}

	$query = "SELECT * FROM combo_5_36 ";

	echo "$query<p>";

	$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

	while($row = mysqli_fetch_array($mysqli_result))
	{
		# update dup

		$last_dup = array_fill (0, 11, 0);

		for ($x = 1 ; $x <= 10; $x++)
		{
			for ($y = 1 ; $y <= $balls_drawn; $y++)
			{	
				if (array_search($row[$y], ${last_.$x._draws}) !== FALSE)
				{
					$last_dup[$x]++;
				}
			}
		}
		
		$query9 = "UPDATE combo_5_36 ";
		$query9 .= "SET ";
		for ($d = 1; $d <= 10; $d++)
		{
			$query9 .= "    dup$d = $last_dup[$d], ";
		}
		$query9 .= "    last_updated = '$curr_date' ";
		$query9 .= "WHERE b1 = $row[b1] ";
		$query9 .= "AND   b2 = $row[b2] ";
		$query9 .= "AND   b3 = $row[b3] ";
		$query9 .= "AND   b4 = $row[b4] ";
		$query9 .= "AND   b5 = $row[b5] ";

		$count[$f]++;

		if ($display_count == 1000)
		{
			$display_count = 0;
			echo "<p>$query9</p>";
		} else {
			$display_count++;
		}
		
		$mysqli_result9 = mysqli_query($mysqli_link, $query9) or die (mysqli_error($mysqli_link));
	}

	for ($d = 1; $d <= 10; $d++)
	{
		print("<h2>Count $d = $count[$d]</h2>");
	}

	$end_time = time();

	$elapsed_time = ($end_time - $start_time)/60;

	echo "Elapsed time = $elapsed_time minutes";

	print("</body>");
	print("</html>");

?>