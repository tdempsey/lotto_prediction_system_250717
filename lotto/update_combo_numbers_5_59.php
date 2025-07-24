<?php
	$game = 7; // Powerball

	// include to connect to database
	require ("includes/mysqli.php");
	require ("includes/count_2_seq.php");
	require ("includes/count_3_seq.php");
	#require ("includes_fl/build_rank_table_fl.php");
	#require ("includes_fl/calculate_rank_fl.php");
	require ("includes/calculate_draw.php");
	require ("includes_pb/last_draws_pb.php");
	require ("includes_pb/combin.incl");

	//start HTML page
	print("<HTML>\n");
	print("<HEAD>\n");
	print("<TITLE>Build Combo 5/59</TITLE>\n");
	print("</HEAD>\n");
	
	print("<BODY bgcolor=\"#FFFFFF\" text=\"#000000\">\n");

	$curr_date = date('Y-m-d');

	for ($f = 3; $f <= 10; $f++)
	{
		$query5 = "SELECT * FROM combo_5_59_0$f ";

		print "$query5<p>";

		$mysqli_result5 = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));
		
		while($row = mysqli_fetch_array($mysqli_result5))
		{
				$draw_count = array_fill (0, 7, 0);

				$draw = array($row[b1],$row[b2],$row[b3],$row[b4],$row[b5]);

				$draw_count = calculate_draw_count($draw);
			
				$query9 = "UPDATE combo_5_59_0$f ";
				$query9 .= "SET d0 = $draw_count[0], ";
				$query9 .= "    d1 = $draw_count[1], ";
				$query9 .= "    d2 = $draw_count[2], ";
				$query9 .= "    d3 = $draw_count[3], ";
				$query9 .= "    d4 = $draw_count[4], ";
				$query9 .= "    d5 = $draw_count[5] ";
				$query9 .= "WHERE id = $row[id] ";

				#print "$query9<p>";

				$mysqli_result9 = mysqli_query($mysqli_link, $query9) or die (mysqli_error($mysqli_link));

				set_time_limit(0);
			#}
		}
	}

	print("</table>");

	print("<h2>Count = $count</h2>");

	print("</body>");
	print("</html>");

?>