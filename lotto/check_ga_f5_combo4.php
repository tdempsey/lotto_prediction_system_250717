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

	//start HTML page
	print("<HTML>\n");
	print("<HEAD>\n");
	print("<TITLE>Check Georgia Fantasy 5 Combo</TITLE>\n");
	print("</HEAD>\n");
	
	print("<body bgcolor=\"#FFFFFF\" text=\"#000000\">\n");

	$query2 = "SELECT DISTINCT date, d1, d2, d3, d4, combo, combo_count, hml FROM ga_f5_4_39 ";
	$query2 .= "WHERE combo_count > 1 ";
	$query2 .= "ORDER BY date ASC ";

	echo "$query2<p>";

	$mysqli_result2 = mysqli_query($mysqli_link, $query2) or die (mysqli_error($mysqli_link));

	#$num_rows = mysqli_num_rows($mysqli_result2);

	while($row2 = mysqli_fetch_array($mysqli_result2))
	{
		$query = "SELECT * FROM ga_f5_4_39 ";
		$query .= "WHERE date = '$row2[date]' ";
		$query .= "AND d1 = $row2[d1] "; 
		$query .= "AND d2 = $row2[d2] "; 
		$query .= "AND d3 = $row2[d3] ";
		$query .= "AND d4 = $row2[d4] ";
		$query .= "AND combo = $row2[combo] ";
		$query .= "AND hml = $row2[hml] ";
		$query .= "ORDER BY id ASC ";

		echo "$query<p>";

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

		$num_rows = mysqli_num_rows($mysqli_result);

		echo "num_rows = $num_rows<p>";

		$row = mysqli_fetch_array($mysqli_result);

		if ($num_rows > 1)
		{
			echo "dup = $row[date],$row[d1],$row[d2],$row[d3],$row[d4],$row[hml]<p>";

			while($row = mysqli_fetch_array($mysqli_result))
			{
				$query5 = "DELETE FROM ga_f5_4_39 ";
				$query5 .= "WHERE id = $row[id] ";

				print "$query5<p>";

				$mysqli_result5 = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));
			}

			$query6 = "SELECT * FROM ga_f5_4_39 ";
			$query6 .= "WHERE d1 = $row2[d1] "; 
			$query6 .= "AND d2 = $row2[d2] "; 
			$query6 .= "AND d3 = $row2[d3] "; 
			$query6 .= "AND d4 = $row2[d4] "; 
			$query6 .= "AND hml = $row2[hml] "; 

			print "$query6<p>";

			$mysqli_result6 = mysqli_query($query6, $mysqli_link) or die (mysqli_error($mysqli_link));

			$num_rows = mysqli_num_rows($mysqli_result6);

			$query_update = "UPDATE ga_f5_4_39 ";
			$query_update .= "SET nums_count = $num_rows ";
			$query_update .= "WHERE d1 = $row2[d1] ";
			$query_update .= "AND d2 = $row2[d2] ";
			$query_update .= "AND d3 = $row2[d3] ";
			$query_update .= "AND d4 = $row2[d4] ";
			$query_update .= "AND hml = $row2[hml] "; 

			print "$query_update<p>";

			$mysqli_result_update = mysqli_query ($mysqli_link, $query_update) or die (mysqli_error($mysqli_link));

			$query6 = "SELECT * FROM ga_f5_4_39 ";
			$query6 .= "WHERE d1 = $row2[d1] "; 
			$query6 .= "AND   d2 = $row2[d2] "; 
			$query6 .= "AND   d3 = $row2[d3] "; 
			$query6 .= "AND   d4 = $row2[d4] "; 
			$query6 .= "AND   combo = $row2[combo] ";
			$query6 .= "AND   hml = $row2[hml] "; 

			print "$query6<p>";

			$mysqli_result6 = mysqli_query($query6, $mysqli_link) or die (mysqli_error($mysqli_link));

			$num_rows = mysqli_num_rows($mysqli_result6);

			echo "num_rows = $num_rows<p>";
			
			$query_update = "UPDATE ga_f5_4_39 ";
			$query_update .= "SET combo_count = $num_rows ";
			$query_update .= "WHERE d1 = $row2[d1] ";
			$query_update .= "AND d2 = $row2[d2] ";
			$query_update .= "AND d3 = $row2[d3] ";
			$query_update .= "AND d4 = $row2[d4] ";
			$query_update .= "AND combo = $row2[combo] ";
			$query_update .= "AND hml = $row2[hml] ";

			echo "$query_update<p>";

			$mysqli_result_update = mysqli_query ($mysqli_link, $query_update) or die (mysqli_error($mysqli_link));

			#die();
		}
	}

	print("</body>");
	print("</html>");

?>