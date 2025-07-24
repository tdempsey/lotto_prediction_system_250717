<?php

	$game = 1; // Florida F5

	require ("includes/games_switch.incl");

	// include to connect to database
	require ("includes/mysqli.php");
	require ("includes/count_2_seq_4.php");
	require ("includes/count_3_seq_4.php");
	#require ("includes_fl/build_rank_table_fl.php");
	#require ("includes_fl/calculate_rank_fl.php");
	require ("includes/first_draw_unix.php");
	require ("includes/last_draw_unix.php");
	require ("includes/dateDiffInDays.php");

	//start HTML page
	print("<HTML>\n");
	print("<HEAD>\n");
	print("<TITLE>Check Georgia Fantasy 5</TITLE>\n");
	print("</HEAD>\n");
	
	print("<body bgcolor=\"#FFFFFF\" text=\"#000000\">\n");

	require ("includes/unix.incl");
	#require ("includes/dateDiffInDays.php");


	$first_draw = mktime (0,0,0,date(1),date(1),date(2003));

	print "first_draw = $first_draw<p>";

	$check_date = date ('Y-m-d', $last_draw_unix);

	print "check_date = $check_date<p>";

	while ($last_draw_unix >= $first_draw) 
	{
		$check_date = date ('Y-m-d', $last_draw_unix);

		#$query = "SELECT date FROM ga_f5_draws ";
		$query2 = "SELECT date FROM ga_f5_2_42 ";
		$query2 .= "WHERE date = '$check_date' ";

		print "$query";
	
		$mysqli_result2 = mysqli_query($mysqli_link, $query2) or die (mysqli_error($mysqli_link));

		$num_rows_all = mysqli_num_rows($mysqli_result2);

		if (!$num_rows_all)
		{
			print "<b>draw  - $check_date missing</b><br>";

			$row2 = mysqli_fetch_array($mysqli_result2);

			$query = "SELECT * FROM $draw_table_name ";
			$query .= "WHERE date = '$check_date' ";

			print "$query<p>";

			#die();

			$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

			$row = mysqli_fetch_array($mysqli_result);

			$table_temp = $draw_prefix . "2_" . $balls;
			require ("includes/combin_2_5.incl");
			$table_temp = $draw_prefix . "3_" . $balls;
			require ("includes/combin_3_5.incl");
			$table_temp = $draw_prefix . "4_" . $balls;
			require ("includes/combin_4_5.incl");
		}

		
		$last_draw_unix -= 86400;	
	}

	$check_date = date ('Y-m-d', $last_draw_unix);

	print "<b><br>$check_date last</b><br>";

	print("</body>");
	print("</html>");

?>