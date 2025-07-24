<?php
	// include to connect to database
	require ("includes/mysqli.php");
	require ("includes/count_2_seq.php");
	require ("includes/count_3_seq.php");
	#require ("includes_fl/build_rank_table_fl.php");
	#require ("includes_fl/calculate_rank_fl.php");
	require ("includes_fl/last_draws_fl.php");
	require ("includes_fl/combin.incl");

	//start HTML page
	print("<HTML>\n");
	print("<HEAD>\n");
	print("<TITLE>Build Combo 6/53</TITLE>\n");
	print("</HEAD>\n");
	
	print("<BODY bgcolor=\"#FFFFFF\" text=\"#000000\">\n");

	$curr_date = date('Y-m-d');

	$print_flag = 0;

	### build rank table

	$num_rank_array = array_fill(0, 54, 0);

	$query = "SELECT * FROM fl_draws ";
	$query .= "WHERE date < '$curr_date' ";
	$query .= "ORDER BY date DESC ";
	$query .= "LIMIT 26 ";

	$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));
	
	while($row = mysqli_fetch_array($mysqli_result))
	{
		$num_rank_array[$row[b1]]++;
		$num_rank_array[$row[b2]]++;
		$num_rank_array[$row[b3]]++;
		$num_rank_array[$row[b4]]++;
		$num_rank_array[$row[b5]]++;
		$num_rank_array[$row[b6]]++;
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

	for ($r = 1; $r <= 21; $r++)
	#for ($r = 10; $r <= 10; $r++)
	{
		$query = "SELECT * FROM combo_6_53_0";
		$query .= "$r ";
		#$query .= "WHERE last_updated < '$curr_date' ";
		$query .= "WHERE last_updated < '2009-08-23' ";

		print "$query<p>";

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

		while($row = mysqli_fetch_array($mysqli_result))
		{
			$total_combin = array_fill (0,7,0);
			$rank_count = array_fill (0,7,0);
			$dup_count = array_fill (0,11,0);

			$draw_array = array ($row[b1],$row[b2],$row[b3],$row[b4],$row[b5],$row[b6]);
			$draw_num = array (0,$row[b1],$row[b2],$row[b3],$row[b4],$row[b5],$row[b6]);

			for ($x = 1; $x <= 6; $x++) 
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

			$pair_sum = pair_sum_count_6 ($draw_num);

			for ($x = 1 ; $x <= 10; $x++)
			{
				for ($z = 1; $z <= 6; $z++)	
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

			$query2 =  "UPDATE combo_6_53_0";
			$query2 .=  "$r ";
			$query2 .= "SET ";
			$query2 .= "rank0 = $rank_count[0], ";
			$query2 .= "rank1 = $rank_count[1], ";
			$query2 .= "rank2 = $rank_count[2], ";
			$query2 .= "rank3 = $rank_count[3], ";
			$query2 .= "rank4 = $rank_count[4], ";
			$query2 .= "rank5 = $rank_count[5], ";
			$query2 .= "rank6 = $rank_count[6], ";
			$query2 .= "comb3 = $total_combin[3], ";
			$query2 .= "comb4 = $total_combin[4], ";
			$query2 .= "comb5 = $total_combin[5], ";
			$query2 .= "comb6 = $total_combin[6], ";
			$query2 .= "dup1 = $dup_count[1], ";
			$query2 .= "dup2 = $dup_count[2], ";
			$query2 .= "dup3 = $dup_count[3], ";
			$query2 .= "dup4 = $dup_count[4], ";
			$query2 .= "dup5 = $dup_count[5], ";
			$query2 .= "dup6 = $dup_count[6], ";
			$query2 .= "dup7 = $dup_count[7], ";
			$query2 .= "dup8 = $dup_count[8], ";
			$query2 .= "dup9 = $dup_count[9], ";
			$query2 .= "dup10 = $dup_count[10], ";
			$query2 .= "last_updated = '$curr_date' ";
			$query2 .= "WHERE b1=$row[b1] and b2=$row[b2] and b3=$row[b3] and b4=$row[b4] and b5=$row[b5] and b6=$row[b6]";

			#print "$query2";
			#die("insert");

			$mysqli_result2 = mysqli_query($mysqli_link, $query2) or die (mysqli_error($mysqli_link));

			$print_flag++;
		}

		if ($print_flag == 50000)
		{
			print "$draw_num[1],$draw_num[2],$draw_num[3],$draw_num[4],$draw_num[5],$draw_num[6]<br>";
			$print_flag = 0;
		}

		$print_flag++;

		set_time_limit(0);
	}

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

			$query8 = "SELECT DISTINCT num1, num2, count FROM fl_temp_2_5000 ";
			$query8 .= "WHERE num1 = $d1 ";
			$query8 .= "  AND num2 = $d2 ";

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