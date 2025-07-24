<?php
	$game = 1; // GA F5

	#$hml = 0;
	#$hml = 1;		#= high
	#$hml = 2;		#= medium
	#$hml = 3;		#= low
	#$hml = 4;		#= min
	#$hml = 5;		#= max
	#$hml = 90;	

	require_once ("includes/hml_switch.incl");

	// include to connect to database
	require ("includes/mysqli.php");
	require ("includes/build_rank_table.php"); 
	require ("includes_once/calculate_rank.php");
	require ("includes_once/last_draws.php");
	require ("includes/last_draw_unix.php");
	require ("includes_ga_f5/combin.incl");

	require ("includes/games_switch.incl");

	//start HTML page
	print("<HTML>\n");
	print("<HEAD>\n");
	print("<TITLE>Update filter_a 5/39 </TITLE>\n");
	print("</HEAD>\n");
	
	print("<BODY bgcolor=\"#FFFFFF\" text=\"#000000\">\n");

	print("<p align=\"center\"><b><font face=\"Arial, Helvetica, sans-serif\">Update filter_a 5/39</font></b></p>");

	$curr_date = date('Y-m-d');

	$count = array_fill (0, 11, 0);
	$display_count = 0;

	$draw_rank_sum = array_fill (0, 7, 0);

	#----------------------------------------------------------------------------------
	/*
	$rank_count = array (0);

	$rank_count = BuildRankTable($curr_date); // array 0..balls with total draws for last 26

	for ($x = 1; $x <= 4; $x++)
	{
		${last_.$x._draws} = LastDraws($curr_date,$x);
	}
	
	for ($a = 4; $a <= 9; $a++)
	{ 
		$query = "SELECT * FROM ga_f5_filter_a_";
		if ($a < 10)
		{
			$query .= "0$a";
		} else {
			$query .= "$a";
		}

		if ($hml)
		{
			$query .= "_";
			$query .= "$hml ";
		}

		$query .= "WHERE last_updated < '$curr_date' ";

		echo "$query<p>";

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));
		*/

		$query5 = "SELECT * FROM $draw_table_name "; 
		$query5 .= "ORDER BY date DESC  ";

		$mysqli_result5 = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));

		while($row = mysqli_fetch_array($mysqli_result5))
		{
			/*
			# update dup

			$last_dup = array_fill (0, 51, 0);

			//count repeating numbers
			for ($x = 1 ; $x <= 4; $x++)
			{
				for ($y = 0 ; $y < $balls_drawn; $y++)
				{	
					if (array_search($row[$y], ${last_.$x._draws}) !== FALSE)
					{
						$last_dup[$x]++;
					}
				}
			}

			# update rank

			$draw_rank_count = array_fill (0, 7, 0);

			for($y = 1; $y <= $balls_drawn; $y++)
			{
				if ($rank_count[$row[$y]] >= 6)
				{
					$draw_rank_count[6]++;
				} else {
					$draw_rank_count[$rank_count[$row[$y]]]++;
				}
			}

			# update combin

			$total_combin = array_fill (0,7,0);

			$total_combin = test_combin_count($row);
			*/
			
			#$query7 = "UPDATE ga_f5_filter_a_";
			/*
			if ($a < 10)
			{
				$query7 .= "0$a";
			} else {
				$query7 .= "$a";
			}

			if ($hml)
			{
				$query7 .= "_";
				$query7 .= "$hml ";
			}
			
			$query7 .= "SET   comb2 = $total_combin[2], ";
			$query7 .= "      comb3 = $total_combin[3], ";
			$query7 .= "      comb4 = $total_combin[4], ";
			$query7 .= "      dup1 = $last_dup[1], ";
			$query7 .= "      dup2 = $last_dup[2], ";
			$query7 .= "      dup3 = $last_dup[3], ";
			$query7 .= "      dup4 = $last_dup[4], ";
			$query7 .= "      rank0 = $draw_rank_count[0], ";
			$query7 .= "      rank1 = $draw_rank_count[1], ";
			$query7 .= "      rank2 = $draw_rank_count[2], ";
			$query7 .= "      rank3 = $draw_rank_count[3], ";
			$query7 .= "      rank4 = $draw_rank_count[4], ";
			$query7 .= "      rank5 = $draw_rank_count[5], ";
			$query7 .= "      rank6 = $draw_rank_count[6], ";
			$query7 .= "      draw_count = '$hml', ";
			$query7 .= "      last_updated = '$curr_date' ";
			$query7 .= "WHERE id = $row[id] ";

			$mysqli_result7 = mysqli_query($query7, $mysqli_link) or die (mysqli_error($mysqli_link));
			*/

			$query_insert = "INSERT $draw_prefix";
			$query_insert .= "rank_table  ";
			$query_insert .= "VALUES ('$row[0]', ";
			$query_insert .= "'$row[rank0]', ";
			$query_insert .= "'$row[rank1]', ";
			$query_insert .= "'$row[rank2]', ";
			$query_insert .= "'$row[rank3]', ";
			$query_insert .= "'$row[rank4]', ";
			$query_insert .= "'$row[rank5]', ";
			$query_insert .= "'$row[rank6]') ";
		
			$mysqli_result_insert = mysqli_query($query_insert, $mysqli_link) or die (mysqli_error($mysqli_link)); 

			#echo "$query7<p>";

			#die();

			$count[$f]++;
			$display_count++;

			if ($display_count == 1000)
			{
				$display_count = 0;
				echo "<p>$query7</p>";
			} else {
				$display_count++;
			}
		}
	#}

	for ($d = 1; $d <= 10; $d++)
	{
		print("<h2>Count $d = $count[$d]</h2>");
	}

	print("</body>");
	print("</html>");

?>