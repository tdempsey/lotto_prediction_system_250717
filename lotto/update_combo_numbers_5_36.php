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
	
	### build rank table

	$num_rank_array = array_fill(0, 54, 0);

	$query = "SELECT * FROM fl_f5_draws ";
	$query .= "WHERE date < '$curr_date' ";
	$query .= "ORDER BY date DESC ";
	$query .= "LIMIT 26 ";

	print "$query<p>";

	$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

	while($row = mysqli_fetch_array($mysqli_result))
	{
		$num_rank_array[$row[b1]]++;
		$num_rank_array[$row[b2]]++;
		$num_rank_array[$row[b3]]++;
		$num_rank_array[$row[b4]]++;
		$num_rank_array[$row[b5]]++;
	}

	### end build rank table

	### build lastxdraws
	
	for ($x = 1; $x <= 10; $x++)
	{
		${last_.$x._draws} = array_fill (0,51,0);
	}

	for ($x = 1; $x <= 10; $x++)
	{
		${last_.$x._draws} = LastxDraws($curr_date,$x);
	}

	### end lastxdraws

	$count = 0;
	$print_flag = 0;

	print("<p align=\"center\"><b><font face=\"Arial, Helvetica, sans-serif\">Build Combo 5/36</font></b></p>");
	
	$query7 = "SELECT * FROM fl_f5_draws ";
	$query7 .= "WHERE date < '$curr_date' ";
	$query7 .= "ORDER BY date DESC ";
	$query7 .= "LIMIT 1 ";

	print "$query7<p>";

	$mysqli_result7 = mysqli_query($query7, $mysqli_link) or die (mysqli_error($mysqli_link));

	$row5 = mysqli_fetch_array($mysqli_result7);

	for ($x = 1; $x <= 5; $x++)
	{
		$ldraw[$x] = $row[$x];
	}

	$query5 = "SELECT * FROM combo_5_36 ";
	#$query5 .= "WHERE (b1 = 1 OR b1 = 2 OR b1 = 5 OR b1 = 10) ";
	#$query5 .= "WHERE b1 = 2 ";
	$query5 .= "WHERE last_updated < '$curr_date' ";
	#$query5 .= "AND last_updated < '2009-7-3' ";

	print "$query5<p>";

	$mysqli_result5 = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));
	
	while($row = mysqli_fetch_array($mysqli_result5))
	{
		/*
		if ($row[b1] = $ldraw[1] or ($row[b2] = $ldraw[1] or ($row[b3] = $ldraw[1] or ($row[b4] = $ldraw[1] or ($row[b5] = $ldraw[1] or 
			$row[b1] = $ldraw[2] or ($row[b2] = $ldraw[2] or ($row[b3] = $ldraw[2] or ($row[b4] = $ldraw[2] or ($row[b5] = $ldraw[2] or 
			$row[b1] = $ldraw[3] or ($row[b2] = $ldraw[3] or ($row[b3] = $ldraw[3] or ($row[b4] = $ldraw[3] or ($row[b5] = $ldraw[3] or 
			$row[b1] = $ldraw[4] or ($row[b2] = $ldraw[4] or ($row[b3] = $ldraw[4] or ($row[b4] = $ldraw[4] or ($row[b5] = $ldraw[4] or 
			$row[b1] = $ldraw[5] or ($row[b2] = $ldraw[5] or ($row[b3] = $ldraw[5] or ($row[b4] = $ldraw[5] or ($row[b5] = $ldraw[5])
		{
		*/
			$pair_sum = 0;

			$total_combin = array_fill (0,7,0);
			$rank_count = array_fill (0,7,0);

			$draw_num = array (0,$row[b1],$row[b2],$row[b3],$row[b4],$row[b5]);
									
			for ($x = 1; $x <= 5; $x++) 
			{
				switch ($num_rank_array[$draw_num[$x]])
				{
						case "0":
							$rank_count[0]++;
							break;
						case "1":
							$rank_count[1]++;
							break;
						case "2":
							$rank_count[2]++;
							break;
						case "3":
							$rank_count[3]++;
							break;
						case "4":
							$rank_count[4]++;
							break;
						case "5":
							$rank_count[5]++;
							break;
						default:
							$rank_count[6]++;	
				}	
			}
			
			$total_combin = test_combin($draw_num);
			$pair_sum = pair_sum_count_5 ($draw_num);

			$dup_count = array_fill (0, 11, 0);

			for ($x = 1 ; $x <= 10; $x++)
			{
				for ($z = 1; $z <= 5; $z++)
				{	
					for ($y = 0; $y < count(${last_.$x._draws}); $y++)
					{
						if ($draw_num[$z] == ${last_.$x._draws}[$y])
						{
							$dup_count[$x]++;
						}
					}
				}
			}

			$query7 = "SELECT id FROM fl_f5_eo50 ";
			$query7 .= "WHERE	odd		= $row[odd] ";
			$query7 .= "AND		even	= $row[even] ";
			$query7 .= "AND		d501	= $row[d501] ";
			$query7 .= "AND		d502	= $row[d502] ";
			
			$mysqli_result7 = mysqli_query($query7, $mysqli_link) or die (mysqli_error($mysqli_link));

			$row7 = mysqli_fetch_array($mysqli_result7);

			$eo50 = $row7[id];

			$wheel_cnt365 = 0;
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
			$query9 .= "SET rank0 = $rank_count[0], ";
			$query9 .= "    rank1 = $rank_count[1], ";
			$query9 .= "    rank2 = $rank_count[2], ";
			$query9 .= "    rank3 = $rank_count[3], ";
			$query9 .= "    rank4 = $rank_count[4], ";
			$query9 .= "    rank5 = $rank_count[5], ";
			$query9 .= "    rank6 = $rank_count[6], ";
			$query9 .= "    dup1 = $dup_count[1], ";
			$query9 .= "    dup2 = $dup_count[2], ";
			$query9 .= "    dup3 = $dup_count[3], ";
			$query9 .= "    dup4 = $dup_count[4], ";
			$query9 .= "    dup5 = $dup_count[5], ";
			$query9 .= "    dup6 = $dup_count[6], ";
			$query9 .= "    dup7 = $dup_count[7], ";
			$query9 .= "    dup8 = $dup_count[8], ";
			$query9 .= "    dup9 = $dup_count[9], ";
			$query9 .= "    dup10 = $dup_count[10], ";
			$query9 .= "    comb2 = $total_combin[2], ";
			$query9 .= "    comb3 = $total_combin[3], ";
			$query9 .= "    comb4 = $total_combin[4], ";
			$query9 .= "    comb5 = $total_combin[5], ";
			$query9 .= "    wheel_cnt5000 = $wheel_cnt5000, ";
			$query9 .= "    wheel_percent_wa = $wheel_percent_wa, ";
			$query9 .= "    pair_sum = $pair_sum, ";
			$query9 .= "    last_updated = '$curr_date' ";
			$query9 .= "WHERE b1 = $row[b1] ";
			$query9 .= "AND   b2 = $row[b2] ";
			$query9 .= "AND   b3 = $row[b3] ";
			$query9 .= "AND   b4 = $row[b4] ";
			$query9 .= "AND   b5 = $row[b5] ";

			#print "$query9<p>";

			$mysqli_result9 = mysqli_query($mysqli_link, $query9) or die (mysqli_error($mysqli_link));

			set_time_limit(0);
		#}
	}

	function pair_sum_count_5 ($draw_num)
	{ 
		global $debug;
	
		require ("includes/mysqli.php");

		$pair_sum = 0;
					
		// pair count 
		for ($c = 1; $c <= 10; $c++)
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
				   $d1 = $draw_num[2];
				   $d2 = $draw_num[3];
				   break;
				case 6: 
				   $d1 = $draw_num[2];
				   $d2 = $draw_num[4];
				   break; 
				case 7: 
				   $d1 = $draw_num[2];
				   $d2 = $draw_num[5];
				   break; 
				case 8: 
				   $d1 = $draw_num[3];
				   $d2 = $draw_num[4];
				   break;
				case 9: 
				   $d1 = $draw_num[3];
				   $d2 = $draw_num[5];
				   break;
				case 10: 
				   $d1 = $draw_num[4];
				   $d2 = $draw_num[5];
				   break;
			} 

			$query8 = "SELECT DISTINCT num1, num2, count FROM fl_f5_temp_2_5000 ";
			$query8 .= "WHERE num1 = $d1 ";
			$query8 .= "  AND num2 = $d2 ";

			#print "$query8<p>";

			$mysqli_result8 = mysqli_query($query8, $mysqli_link) or die (mysqli_error($mysqli_link));

			$row8 = mysqli_fetch_array($mysqli_result8);
			
			$pair_sum+= $row8[count];
		} 

		return $pair_sum;
	}



	print("</table>");

	print("<h2>Count = $count</h2>");

	print("</body>");
	print("</html>");

?>