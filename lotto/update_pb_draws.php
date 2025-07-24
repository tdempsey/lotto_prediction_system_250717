<?php
	$game = 7; // Powerball

	// include to connect to database
	require ("includes/mysqli.php");
	require ("includes/build_rank_table.php"); 
	require ("includes/calculate_rank.php");
	require ("includes/last_draws.php");
	require ("includes/last_draw_unix.php");
	require ("includes_pb/combin.incl");

	require ("includes/games_switch.incl");

	//start HTML page
	print("<HTML>\n");
	print("<HEAD>\n");
	print("<TITLE>Update filter_a 5/39 </TITLE>\n");
	print("</HEAD>\n");
	
	print("<BODY bgcolor=\"#FFFFFF\" text=\"#000000\">\n");

	print("<p align=\"center\"><b><font face=\"Arial, Helvetica, sans-serif\">Update filter_a 5/39</font></b></p>");

	$curr_date = date('Y-m-d');

	$query = "SELECT * FROM pb_draws ";
	$query .= "ORDER BY date ASC ";

	echo "$query<p>";

	$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

	while($row = mysqli_fetch_array($mysqli_result))
	{
		$draw_array = array(0,$row[b1],$row[b2],$row[b3],$row[b4],$row[b5]);

		sort ($draw_array);

		$query9 = "UPDATE pb_draws ";
		$query9 .= "SET ";
		for ($d = 1; $d <= 4; $d++)
		{
			$query9 .= "    b$d = $draw_array[$d], ";
		}
		$query9 .= "    b$d = $draw_array[$d] ";
		$query9 .= "WHERE date = '$row[date]' ";

		echo "$query9<p>";

		$mysqli_result9 = mysqli_query($mysqli_link, $query9) or die (mysqli_error($mysqli_link));

		#die();
	}

	print("</body>");
	print("</html>");

?>