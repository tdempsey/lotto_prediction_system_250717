<?php
	$game = 7; // Powerball

	// include to connect to database
	require ("includes/mysqli.php");
	require_once ("includes/build_rank_table.php"); 
	require ("includes/calculate_rank.php");
	require ("includes/last_draws.php");
	require ("includes/last_draw_unix.php");
	require ("includes_pb/combin.incl");

	require ("includes/games_switch.incl");

	//start HTML page
	print("<HTML>\n");
	print("<HEAD>\n");
	print("<TITLE>Update filter_b 5/59 </TITLE>\n");
	print("</HEAD>\n");
	
	print("<BODY bgcolor=\"#FFFFFF\" text=\"#000000\">\n");

	print("<p align=\"center\"><b><font face=\"Arial, Helvetica, sans-serif\">Update filter_b 5/59</font></b></p>");

	$curr_date = date('Y-m-d');

	$count = array_fill (0, 11, 0);
	$display_count = 0;

	$draw_rank_sum = array_fill (0, 7, 0);

	#----------------------------------------------------------------------------------

	$rank_count = array (0);

	$rank_count = BuildRankTable($curr_date); // array 0..balls with total draws for last 26

	for ($x = 1; $x <= 10; $x++)
	{
		${last_.$x._draws} = LastDraws($curr_date,$x);
	}

	for ($a = 1; $a <= 20; $a++)
	{ 
		$query = "SELECT * FROM pb_filter_b_";
		if ($a < 10)
		{
			$query .= "0$a ";
		} else {
			$query .= "$a ";
		}
		$query .= "WHERE comb5 = 0 ";
		$query .= "AND   comb4 = 0 ";
		$query .= "AND   comb3 <= 3 ";
		$query .= "AND   comb2 >= 7 ";
		$query .= "AND   comb2 <= 10 ";
		$query .= "AND   last_updated < '$curr_date' ";

		echo "$query<p>";

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

		$temp1 = mysqli_num_rows($mysqli_result);

		echo "rows $a = $temp1<p>";

		while($row = mysqli_fetch_array($mysqli_result))
		{
			$query_wheel = "SELECT * FROM pb_wheel_sum_table ";
			$query_wheel .= "WHERE sum = $row[sum] ";
			$query_wheel .= "AND even = $row[even] ";
			$query_wheel .= "AND odd = $row[odd] ";
			$query_wheel .= "AND d501 = $row[d501] ";
			$query_wheel .= "AND d502 = $row[d502] ";

			#echo "$query_wheel<p>";
		
			$mysqli_result_wheel = mysqli_query($query_wheel, $mysqli_link) or die (mysqli_error($mysqli_link));

			$row_wheel = mysqli_fetch_array($mysqli_result_wheel);

			$row_wheel_exist = mysqli_num_rows($mysqli_result_wheel);

			$temp1 = 0;
			$temp5 = 0;
			$temp10 = 0;

			if ($row_wheel_exist)
			{
				$temp1 = $row_wheel[percent_1];
				$temp5 = $row_wheel[percent_5];
				$temp10 = $row_wheel[percent_10];
				$tempwa = $row_wheel[wa];
			}

			# update dup

			$last_dup = array_fill (0, 51, 0);

			//count repeating numbers
			for ($x = 1 ; $x <= 10; $x++)
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
					#echo "$row[$y]= {$rank_count[$row[$y]]}<br>";
				} else {
					$draw_rank_count[$rank_count[$row[$y]]]++;
					#echo "$row[$y]= {$rank_count[$row[$y]]}<br>";
				}
			}
			
			$query9 = "UPDATE pb_filter_b_";
			if ($row[b1] < 10)
			{
				$query9 .= "0$row[b1] ";
			} else {
				$query9 .= "$row[b1] ";
			}
			$query9 .= "SET ";
			for ($d = 1; $d <= 10; $d++)
			{
				$query9 .= "    dup$d = $last_dup[$d], ";
			}
			for ($d = 0; $d <= 6; $d++)
			{
				$query9 .= "    rank$d = $draw_rank_count[$d], ";
			}	
			$query9 .= "    wheel_percent_wa = $tempwa, ";
			$query9 .= "    last_updated = CURDATE() ";
			$query9 .= "WHERE id = $row[id] ";

			echo "$query9<p>";

			$mysqli_result9 = mysqli_query($mysqli_link, $query9) or die (mysqli_error($mysqli_link));

			#die();

			$count[$row[b1]]++;

			if ($display_count == 1000)
			{
				$display_count = 0;
				echo "<p>$query9</p>";
			} else {
				$display_count++;
			}
		}
	}

	for ($d = 1; $d <= 20; $d++)
	{
		print("<h2>Count $d = $count[$d]</h2>");
	}

	print("</body>");
	print("</html>");

?>