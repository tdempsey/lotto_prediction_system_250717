<?php
	$game = 4; // Florida Fantasy 5

	// include to connect to database
	require ("includes/mysqli.php");
	require ("includes/count_2_seq.php");
	require ("includes/count_3_seq.php");
	#require ("includes_fl/build_rank_table_fl.php");
	#require ("includes_fl/calculate_rank_fl.php");
	require ("includes_fl_f5/last_draws_fl_f5.php");
	require ("includes_fl_f5/combin.incl");

	//start HTML page
	print("<HTML>\n");
	print("<HEAD>\n");
	print("<TITLE>Build Combo 5/36</TITLE>\n");
	print("</HEAD>\n");
	
	print("<BODY bgcolor=\"#FFFFFF\" text=\"#000000\">\n");

	$curr_date = date('Y-m-d');
	
	$count = 0;
	$print_flag = 0;

	print("<p align=\"center\"><b><font face=\"Arial, Helvetica, sans-serif\">Build Combo 5/36</font></b></p>");

	$query5 = "SELECT * FROM combo_5_36 ";
	$query5 .= "WHERE b1 <= 10 ";

	print "$query5<p>";

	$mysqli_result5 = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));
	
	while($row = mysqli_fetch_array($mysqli_result5))
	{			
			$query7 = "SELECT id FROM fl_f5_eo50 ";
			$query7 .= "WHERE	odd		= $row[odd] ";
			$query7 .= "AND		even	= $row[even] ";
			$query7 .= "AND		d501	= $row[d501] ";
			$query7 .= "AND		d502	= $row[d502] ";
			
			$mysqli_result7 = mysqli_query($query7, $mysqli_link) or die (mysqli_error($mysqli_link));

			$row7 = mysqli_fetch_array($mysqli_result7);

			$eo50 = $row7[id];

			$wheel_cnt5000 = 0;
			$wheel_percent_wa = 0.0;

			$query7 = "SELECT * FROM fl_f5_wheels_generated_";
			$query7 .= "$row[b1] ";
			$query7 .= "WHERE eo50 = $eo50 AND ";
			$query7 .= "sum = $row[sum] ";
		
			$mysqli_result7 = mysqli_query($query7, $mysqli_link) or die (mysqli_error($mysqli_link));

			$num_rows = mysqli_num_rows($mysqli_result7);

			#print "<br>query7 = $query7<br>";
			#print "num_rows = $num_rows<br>";

			if ($num_rows)
			{
				$row_wheels = mysqli_fetch_array($mysqli_result7);

				$wheel_cnt5000 = $row_wheels[cnt5000];
				$wheel_percent_wa = $row_wheels[percent_wa];
			}

			#print_r ("$draw_count");
		
			$query9 = "UPDATE combo_5_36 ";
			$query9 .= "SET wheel_cnt5000 = $wheel_cnt5000, ";
			$query9 .= "    wheel_percent_wa = $wheel_percent_wa ";
			$query9 .= "WHERE b1 = $row[b1] ";
			$query9 .= "AND   b2 = $row[b2] ";
			$query9 .= "AND   b3 = $row[b3] ";
			$query9 .= "AND   b4 = $row[b4] ";
			$query9 .= "AND   b5 = $row[b5] ";

			#print "$query9<p>";

			$mysqli_result9 = mysqli_query($mysqli_link, $query9) or die (mysqli_error($mysqli_link));

			set_time_limit(0);
	}

	
	print("</table>");

	print("<h2>Count = $count</h2>");

	print("</body>");
	print("</html>");

?>