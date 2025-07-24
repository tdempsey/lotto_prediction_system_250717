<?php
	$game = 6; // Florida Lotto

	// include to connect to database
	require ("includes/mysqli.php");
	require ("includes/count_2_seq.php");
	require ("includes/count_3_seq.php");
	require ("includes_fl/last_draws_fl.php");
	require ("includes_fl/look_up_rank_fl.php");
	require ("includes_fl/combin.incl");
	require ("includes_fl/config.incl");

	//start HTML page
	print("<HTML>\n");
	print("<HEAD>\n");
	print("<TITLE>Lotto Pair Sigma Draw Update</TITLE>\n");
	print("</HEAD>\n");
	
	print("<BODY bgcolor=\"#FFFFFF\" text=\"#000000\">\n");

	$curr_date = Date('Y-m-d');

	$col1 = 4;

	$query5 = "SELECT * FROM filter_a_6_53_0"; 
	$query5 .= "$col1 ";
	#$query5 .= "WHERE pair_sigma = 0 ";
	
	$mysqli_result5 = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));

	#$num_rows = mysqli_num_rows($mysqli_result5);

	while ($row = mysqli_fetch_array($mysqli_result5))
	{
		$num_rows = 0;
		$num_rows_total = 0;

		for ($c = 1; $c <= 15; $c++)
		{
			switch ($c) 
			{ 
				case 1: 
				   $d1 = $row[b1];
				   $d2 = $row[b2];
				   break; 
				case 2: 
				   $d1 = $row[b1];
				   $d2 = $row[b3];
				   break; 
				case 3: 
				   $d1 = $row[b1];
				   $d2 = $row[b4];
				   break; 
				case 4: 
				   $d1 = $row[b1];
				   $d2 = $row[b5];
				   break;
				case 5: 
				   $d1 = $row[b1];
				   $d2 = $row[b6];
				   break;
				case 6: 
				   $d1 = $row[b2];
				   $d2 = $row[b3];
				   break; 
				case 7: 
				   $d1 = $row[b2];
				   $d2 = $row[b4];
				   break; 
				case 8: 
				   $d1 = $row[b2];
				   $d2 = $row[b5];
				   break;
				case 9: 
				   $d1 = $row[b2];
				   $d2 = $row[b6];
				   break;
				case 10: 
				   $d1 = $row[b3];
				   $d2 = $row[b4];
				   break;
				case 11: 
				   $d1 = $row[b3];
				   $d2 = $row[b5];
				   break;
				case 12: 
				   $d1 = $row[b3];
				   $d2 = $row[b6];
				   break;
				case 13: 
				   $d1 = $row[b4];
				   $d2 = $row[b5];
				   break;
				case 14: 
				   $d1 = $row[b4];
				   $d2 = $row[b6];
				   break;
				case 15: 
				   $d1 = $row[b5];
				   $d2 = $row[b6];
				   break;
			} 

			$query2 = "SELECT * FROM fl_2_53 ";
			$query2 .= "WHERE d1 = $d1 "; 
			$query2 .= "AND d2 = $d2 "; 
			#$query2 .= "AND date < '$row[date]' "; 

			print "$query2<p>";
			
			$mysqli_result2 = mysqli_query($mysqli_link, $query2) or die (mysqli_error($mysqli_link));

			$num_rows = mysqli_num_rows($mysqli_result2);

			#print "num_rows = $num_rows<p>";

			$num_rows_total += $num_rows;
		}	

		$query_update = "UPDATE filter_a_6_53_0";
		$query_update .= "$col1 ";
		$query_update .= "SET pair_sigma = $num_rows_total, ";
		$query_update .= "last_updated = '$curr_date' ";
		$query_update .= "WHERE b1 = '$row[b1]' ";
		$query_update .= "AND   b2 = '$row[b2]' ";
		$query_update .= "AND   b3 = '$row[b3]' ";
		$query_update .= "AND   b4 = '$row[b4]' ";
		$query_update .= "AND   b5 = '$row[b5]' ";
		$query_update .= "AND   b6 = '$row[b6]' ";
	

		print "<b>$query_update</b><p>";

		$mysqli_result_update = mysqli_query ($mysqli_link, $query_update) or die (mysqli_error($mysqli_link));
	}

	print("</body>");
	print("</html>");

?>