<?php
	$game = 6; // Florida Lotto
	
	require ("includes/games_switch.incl");

	// include to connect to database
	require ("includes/mysqli.php");
	require ("includes/count_2_seq.php");
	require ("includes/count_3_seq.php");
	require ("includes_fl/look_up_rank_fl.php");
	require ("$game_includes/combin.incl");

	//start HTML page
	print("<HTML>\n");
	print("<HEAD>\n");
	print("<TITLE>Draw Analyze</TITLE>\n");
	print("</HEAD>\n");
	
	print("<BODY bgcolor=\"#FFFFFF\" text=\"#000000\">\n");

	print "<center><h1>Draw Analyze</h1></center>";

	$limit = 10;

	$pair_low = 1000;
	$pair_high = 0;

	$query = "SELECT * FROM $draw_table_name ";
	$query .= "ORDER BY date DESC ";
	$query .= "LIMIT $limit ";

	#print("$query<p>");

	$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

	while($row = mysqli_fetch_array($mysqli_result))
	{
		$pair_sum = pair_sum_count_6 ($row);
		print "<b>Date</b> = $row[date]<br>";
		print "<b>pair_sum_6</b> = $pair_sum<p>";

		if ($pair_sum > $pair_high)
		{
			$pair_high = $pair_sum;
		}

		if ($pair_sum < $pair_low)
		{
			$pair_low = $pair_sum;
		}
	}

	print "<b>pair_low = $pair_low</b><br>";
	print "<b>pair_high = $pair_high</b><p>";

	function pair_sum_count_6 ($draw_num)
	{ 
		global $debug;
	
		require ("includes/mysqli.php");

		$pair_sum = 0;
					
		// pair count 
		for ($c = 1; $c <= 15; $c++)
		{
			switch ($c) { 
				case 1: 
				   $d1 = $draw_num[1];
				   $d2 = $draw_num[2];
				   break; 
				case 2: 
				   $d1 = $draw_num[1];
				   $d2 = $draw_num[3];
				   break; 
				case 3: 
				   $d1 = $draw_num[1];
				   $d2 = $draw_num[4];
				   break; 
				case 4: 
				   $d1 = $draw_num[1];
				   $d2 = $draw_num[5];
				   break;
				case 5: 
				   $d1 = $draw_num[1];
				   $d2 = $draw_num[6];
				   break;
				case 6: 
				   $d1 = $draw_num[2];
				   $d2 = $draw_num[3];
				   break; 
				case 7: 
				   $d1 = $draw_num[2];
				   $d2 = $draw_num[4];
				   break; 
				case 8: 
				   $d1 = $draw_num[2];
				   $d2 = $draw_num[5];
				   break;
				case 9: 
				   $d1 = $draw_num[2];
				   $d2 = $draw_num[6];
				   break;
				case 10: 
				   $d1 = $draw_num[3];
				   $d2 = $draw_num[4];
				   break;
				case 11: 
				   $d1 = $draw_num[3];
				   $d2 = $draw_num[5];
				   break;
				case 12: 
				   $d1 = $draw_num[3];
				   $d2 = $draw_num[6];
				   break;
				case 13: 
				   $d1 = $draw_num[4];
				   $d2 = $draw_num[5];
				   break;
				case 14: 
				   $d1 = $draw_num[4];
				   $d2 = $draw_num[6];
				   break;
				case 15: 
				   $d1 = $draw_num[5];
				   $d2 = $draw_num[6];
				   break;
			} 

			$query8 = "SELECT num1, num2, count FROM fl_temp_2_5000 ";
			$query8 .= "WHERE num1 = $d1 ";
			$query8 .= "  AND num2 = $d2 ";
			$query8 .= "  AND last_date < '$draw_num[0]' ";

			#print "$query8<p>";

			$mysqli_result8 = mysqli_query($query8, $mysqli_link) or die (mysqli_error($mysqli_link));

			$row8 = mysqli_fetch_array($mysqli_result8);

			$num_rows = mysqli_num_rows($mysqli_result8);

			#print "num_rows = $num_rows<p>";
			
			$pair_sum+= $num_rows;
		} 

		return $pair_sum;
	}

	print("</body>");
	print("</html>");

?>