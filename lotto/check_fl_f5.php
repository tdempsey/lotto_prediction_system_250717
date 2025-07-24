<?php

	$game = 4; // Florida F5

	// include to connect to database
	require ("includes/mysqli.php");
	require ("includes/count_2_seq_4.php");
	require ("includes/count_3_seq_4.php");
	#require ("includes_fl/build_rank_table_fl.php");
	#require ("includes_fl/calculate_rank_fl.php");
	require ("includes/last_draw_unix.php");

	//start HTML page
	print("<HTML>\n");
	print("<HEAD>\n");
	print("<TITLE>Check Fantasy 5</TITLE>\n");
	print("</HEAD>\n");
	
	print("<body bgcolor=\"#FFFFFF\" text=\"#000000\">\n");

	require ("includes/unix.incl");

	$first_draw = mktime (0,0,0,date(1),date(1),date(2003));

	print "first_draw = $first_draw<p>";

	$check_date = date ('Y-m-d', $last_draw_unix);

	print "check_date = $check_date<p>";

	while ($last_draw_unix >= $first_draw) 
	{
		$check_date = date ('Y-m-d', $last_draw_unix);

		$query = "SELECT date FROM fl_f5_draws ";
		$query .= "WHERE date = '$check_date' ";

		#print "$query";
	
		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

		$num_rows_all = mysqli_num_rows($mysqli_result);

		if (!$num_rows_all)
		{
			print "<b>$check_date missing</b><br>";
		}
		
		$last_draw_unix -= 86400;	
	}

	print "<b><br>$check_date last</b><br>";

	print("</body>");
	print("</html>");

?>