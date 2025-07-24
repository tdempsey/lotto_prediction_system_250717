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

	$num_rank_array = array_fill(0, 54, 0);

	for ($r = 1; $r <= 9; $r++)
	{
	}

		$query = "SELECT * FROM combo_6_53_";
		$query .= "$x ";

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

	$max_num = 53;

	$count = 0;
	$print_flag = 0;

	print("<p align=\"center\"><b><font face=\"Arial, Helvetica, sans-serif\">Build Combo 6/$max_num</font></b></p>");

	while($draw_num[1] != $max_num - 4 && $draw_num[1] <= 9)
	{
		$even = 0;
		$odd = 0;
		$d501 = 0;
		$d502 = 0;
		$d0 = 0;
		$d1 = 0;
		$d2 = 0;
		$d3 = 0;
		$d4 = 0;
		$d5 = 0;
		$mod_total = 0;
		$dup_pass = 1;

		$total_combin = array_fill (0,7,0);
		$num_count = array_fill (0,7,0);
		$rank_count = array_fill (0,7,0);
		$mod = array_fill (0,7,0);
	
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

				$dup_count = array_fill (0, 10, 0);

				for ($x = 1 ; $x <= 5; $x++)
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
						if ($dup_count[$x] > $dup_count_limit[$x] || $dup_count[$x] < $dup_count_minim[$x]) 
						{
							$dup_pass = 0;
						}
				}

				

				if (#$dup_pass &&
					#$rank_count[0] <= 1 &&
					#$rank_count[1] <= 1 &&
					#$rank_count[2] <= 2 &&
					#$rank_count[3] <= 2 &&
					#$rank_count[4] <= 2 &&
					#$rank_count[5] <= 1 &&
					#$rank_count[6] <= 1 &&
					
					#$total_combin[3] >= 7 &&
					#$total_combin[4] <= 1 &&

					##$total_combin[5] == 0 &&
					##$total_combin[6] == 0 
					)
				{
					#die ("insert");
					$combo_table = "combo_6_53";
					if ($draw_num[1] < 10)
					{
						$combo_table .= "_0$draw_num[1]";
					} else {
						$combo_table .= "_$draw_num[1]";
					}
					
					$query = "INSERT INTO $combo_table ";
					$query .= "VALUES ('$draw_num[1]', ";
					$query .= "'$draw_num[2]', ";
					$query .= "'$draw_num[3]', ";
					$query .= "'$draw_num[4]', ";
					$query .= "'$draw_num[5]', ";
					$query .= "'$draw_num[6]', ";
					$query .= "'$sum', ";
					$query .= "'$even', ";
					$query .= "'$odd', ";
					$query .= "'$d501', ";
					$query .= "'$d502', ";
					$query .= "'$num_count[0]', ";
					$query .= "'$num_count[1]', ";
					$query .= "'$num_count[2]', ";
					$query .= "'$num_count[3]', ";
					$query .= "'$num_count[4]', ";
					$query .= "'$num_count[5]', ";

					$query .= "'$rank_count[0]', ";
					$query .= "'$rank_count[1]', ";
					$query .= "'$rank_count[2]', ";
					$query .= "'$rank_count[3]', ";
					$query .= "'$rank_count[4]', ";
					$query .= "'$rank_count[5]', ";
					$query .= "'$rank_count[6]', ";

					$query .= "'$mod_total', ";
					$query .= "'$seq2', ";
					$query .= "'$seq3', ";
					$query .= "'$total_combin[2]', ";
					$query .= "'$total_combin[3]', ";
					$query .= "'$total_combin[4]', ";
					$query .= "'$total_combin[5]', ";
					$query .= "'$total_combin[6]', ";

					$query .= "'$dup_count[1]', ";
					$query .= "'$dup_count[2]', ";
					$query .= "'$dup_count[3]', ";
					$query .= "'$dup_count[4]', ";
					$query .= "'$dup_count[5]', ";

					$query .= "'$pair_sum', ";
					$query .= "'0.0', ";
					$query .= "'0.0', ";
					$query .= "'0.0', ";
					$query .= "'0.0', ";
					$query .= "'0.0', ";
					$query .= "'0.0', ";
					$query .= "'0.0', ";
					$query .= "'0.0', ";
					$query .= "'0.0', ";
					$query .= "'0.0', ";
					$query .= "'0.0', ";
					$query .= "'0.0', ";
					$query .= "'0.0', ";
					$query .= "'$curr_date')";
					
					$mysqli_result2 = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

					$count++;
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