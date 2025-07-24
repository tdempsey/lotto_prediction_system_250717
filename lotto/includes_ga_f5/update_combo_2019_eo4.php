<?php
	function update_combo_2019_eo4($sum,$even,$odd,$d4_1,$d4_2,$d4_3,$d4_4)
	{
		require ("includes/mysqli.php");
		#require ("includes_ga_f5/pair_draw_filter.incl");


		global $draw_prefix,$hml,$balls_drawn,$balls;
		
		require ("includes/combo_switch_2.incl");

		$curr_date = date("Y-m-d");

		### build lastxdraws
	
		for ($x = 1; $x <= 50; $x++)
		{
			${last_.$x._draws} = array_fill (0,51,0);
		}

		for ($x = 1; $x <= 25; $x++)
		{
			${last_.$x._draws} = LastDraws($curr_date,$x);
			#echo "last_.$x._draws<br>";
			#print_r (${last_.$x._draws});
			#echo "<p>";
		}	

		### rank count limit
	
		require ("includes_ga_f5/build_rank_count_limit.incl");

		echo "<h3>Update Case - hml = $hml</h3>";
		/*
		#################################################################################

		$num1_temp = array();

		$query_ct = "SELECT * FROM ga_f5_column2_1";
		if ($hml)
		{
			$query_ct .= "_$hml ";
		}
		#$query_ct .= " WHERE num <= 15 ";
		$query_ct .= " ORDER BY percent_y1 DESC, percent_y5 DESC ";
		$query_ct .= "LIMIT 7 ";

		print "$query_ct<p>";

		$mysqli_result_ct = mysqli_query($query_ct, $mysqli_link) or die (mysqli_error($mysqli_link));

		while($row_ct = mysqli_fetch_array($mysqli_result_ct))
		{
				array_push($num1_temp,$row_ct[num]);	
		}

		echo "<strong>num1_temp = </strong>";
		print_r ($num1_temp);
		echo "<br>";

		#################################################################################

		$num2_temp = array();

		$query_ct = "SELECT * FROM ga_f5_column2_2";
		if ($hml)
		{
			$query_ct .= "_$hml ";
		}
		#$query_ct .= " WHERE num <= 15 ";
		$query_ct .= " ORDER BY percent_y1 DESC, percent_y5 DESC ";
		$query_ct .= "LIMIT 7 ";

		print "$query_ct<p>";

		$mysqli_result_ct = mysqli_query($query_ct, $mysqli_link) or die (mysqli_error($mysqli_link));

		while($row_ct = mysqli_fetch_array($mysqli_result_ct))
		{
				array_push($num2_temp,$row_ct[num]);	
		}

		echo "<strong>num2_temp = </strong>";
		print_r ($num2_temp);
		echo "<br>";

		##########################################################################################

		$num3_temp = array();

		$query_ct = "SELECT * FROM ga_f5_column2_3";
		if ($hml)
		{
			$query_ct .= "_$hml ";
		}
		#$query_ct .= " ORDER BY year3 DESC, year5 DESC, year10 DESC ";
		$query_ct .= " ORDER BY percent_y1 DESC, percent_y5 DESC ";
		$query_ct .= "LIMIT 7 ";

		print "$query_ct<p>";

		$mysqli_result_ct = mysqli_query($query_ct, $mysqli_link) or die (mysqli_error($mysqli_link));

		while($row_ct = mysqli_fetch_array($mysqli_result_ct))
		{
				array_push($num3_temp,$row_ct[num]);	
		}

		echo "<strong>num3_temp = </strong>";
		print_r ($num3_temp);
		echo "<br>";

		#################################################################################

		$num4_temp = array();

		$query_ct = "SELECT * FROM ga_f5_column2_4";
		if ($hml)
		{
			$query_ct .= "_$hml ";
		}
		#$query_ct .= " WHERE num <= 15 ";
		$query_ct .= " ORDER BY percent_y1 DESC, percent_y5 DESC ";
		$query_ct .= "LIMIT 7 ";

		print "$query_ct<p>";

		$mysqli_result_ct = mysqli_query($query_ct, $mysqli_link) or die (mysqli_error($mysqli_link));

		while($row_ct = mysqli_fetch_array($mysqli_result_ct))
		{
				array_push($num4_temp,$row_ct[num]);	
		}

		echo "<strong>num4_temp = </strong>";
		print_r ($num4_temp);
		echo "<br>";

		##########################################################################################

		$num5_temp = array();

		$query_ct = "SELECT * FROM ga_f5_column2_5";
		if ($hml)
		{
			$query_ct .= "_$hml ";
		}
		$query_ct .= " ORDER BY percent_y1 DESC, percent_y5 DESC ";
		$query_ct .= "LIMIT 7 ";

		print "$query_ct<p>";

		$mysqli_result_ct = mysqli_query($query_ct, $mysqli_link) or die (mysqli_error($mysqli_link));

		while($row_ct = mysqli_fetch_array($mysqli_result_ct))
		{
				array_push($num5_temp,$row_ct[num]);	
		}

		echo "<strong>num5_temp = </strong>";
		print_r ($num5_temp);
		echo "<br>";

		##################################################################################################

		$num5_temp = array();
		*/

		$low_array = array_fill(0,6,0);
		$high_array = array_fill(0,6,0);

		$index = 1;

		$query_limit = "SELECT * FROM ga_f5_limits_by_sumeo ";
		$query_limit .= "WHERE sum = $sum ";
		$query_limit .= "AND   even = $even ";
		$query_limit .= "AND   odd = $odd ";
		$query_limit .= "ORDER BY col ASC ";

		#print "$query_limit<br>";

		$mysqli_result_limit = mysqli_query($mysqli_link, $query_limit) or die (mysqli_error($mysqli_link));

		while($row_limit = mysqli_fetch_array($mysqli_result_limit))
		{
			$low_array[$row_limit[col]] = $row_limit[low];
			$high_array[$row_limit[col]] = $row_limit[high];
			$index++;
		}

		$query_ct = "SELECT * FROM combo_5_42 a ";
		$query_ct .= "JOIN combo_5_42_dr4 b ";
		$query_ct .= "ON a.id = b.id ";
		$query_ct .= "WHERE a.sum = $sum ";
		#$query_ct .= "AND   a.b1 <= $high_array[1] ";
		$query_ct .= "AND   a.b1 <= 7 ";
		
		$query_ct .= "AND   a.b2 >= $low_array[2] ";
		$query_ct .= "AND   a.b2 <= $high_array[2] ";
		$query_ct .= "AND   a.b3 >= $low_array[3] ";
		$query_ct .= "AND   a.b3 <= $high_array[3] ";
		$query_ct .= "AND   a.b4 >= $low_array[4] ";
		$query_ct .= "AND   a.b4 <= $high_array[4] ";
		$query_ct .= "AND   a.b5 >= $low_array[5] ";
		$query_ct .= "AND   a.b5 <= $high_array[5] ";
		
		$query_ct .= "AND   a.even = $even ";
		$query_ct .= "AND   a.odd = $odd ";
		$query_ct .= "AND   b.d1 = $d4_1 ";
		$query_ct .= "AND   b.d2 = $d4_2 ";
		$query_ct .= "AND   b.d3 = $d4_3 ";
		$query_ct .= "AND   b.d4 = $d4_4 ";
		$query_ct .= "AND seq2 <= 1 ";
		$query_ct .= "AND mod_tot <= 1 ";
		$query_ct .= "AND   last_updated < '$curr_date' "; 
		$query_ct .= "ORDER BY a.id ASC ";

		echo "<p>$query_ct</p>";

		#die();

		$mysqli_result_ct = mysqli_query($mysqli_link, $query_ct) or die (mysqli_error($mysqli_link));

		$num_rows_ct = mysqli_num_rows($mysqli_result_ct);

		echo "combo rows to update - $num_rows_ct<br>";

		while($row = mysqli_fetch_array($mysqli_result_ct))
		{
			$c = 1;

			echo "<strong>$row[b1], $row[b2], $row[b3], $row[b4], $row[b5]</strong><br>";
			
			$dup_count = array_fill (0,26,0);

			//count repeating numbers
			for ($x = 1 ; $x <= 25; $x++)
			{
				for ($y = 1 ; $y <= 5; $y++)
				{	
					$temp_b = "b" . $y;

					if (array_search($row[$temp_b], ${last_.$x._draws}) !== FALSE)
					{
						$dup_count[$x]++;
					}
				}
			}
			
			$query_rank = "SELECT * FROM ga_f5_temp2a_26  ";
			$query_rank .= "ORDER BY num ASC ";

			#print "$query_rank<br>";

			$mysqli_result_rank = mysqli_query($mysqli_link, $query_rank) or die (mysqli_error($mysqli_link));

			while($row_rank = mysqli_fetch_array($mysqli_result_rank))
			{
				$v = $row_rank[num];
				$rank_count[$v] = $row_rank[count];
			}

			$draw_rank_count = array_fill (0, 7, 0);

			for($y = 1; $y <= $balls_drawn; $y++)
			{
				if ($rank_count[$row[$y]] >= 6)
				{
					$draw_rank_count[6]++;
					$temp_rank = 6;
				} else {
					$temp_rank = $rank_count[$row[$y]];
					$draw_rank_count[$temp_rank]++;
				}
			}

			$total_combin = array_fill (0,7,0);

			$total_combin = test_combin($row);

			$query_eo50 = "SELECT * FROM ga_f5_wheel_sum_table_25  ";
			$query_eo50 .= "WHERE sum = $row[sum] ";
			#$query_eo50 .= "AND   even = $row[even] ";
			#$query_eo50 .= "AND   odd = $row[odd] ";
			$query_eo50 .= "AND   d4_1 = $row[d4_1] ";
			$query_eo50 .= "AND   d4_2 = $row[d4_2] ";
			$query_eo50 .= "AND   d4_3 = $row[d4_3] ";
			$query_eo50 .= "AND   d4_4 = $row[d4_4] ";

			#print "$query_eo50<br>";

			#$mysqli_result_eo50 = mysqli_query($query_eo50, $mysqli_link) or die (mysqli_error($mysqli_link));

			#$row_eo50 = mysqli_fetch_array($mysqli_result_eo50);

			#echo "begin m6_sum<br>";
			require ("includes_ga_f5/sigma_sum.incl");
			require ("includes_ga_f5/num_wa_sum.incl");
			#echo "end m6_sum<br>";

			$row[0] = date("Y-m-d");

			require ("includes/combo_count_date.incl");

			#die();

			$query9 = "UPDATE combo_5_42 ";
			$query9 .= "SET dup1 = '$dup_count[1]', ";

			for ($g = 2; $g <= 25; $g++)
			{
				$query9 .= "dup";
				$query9 .= "$g = '$dup_count[$g]', ";
			}

			$query9 .= "    rank0 = '$draw_rank_count[0]', ";
			$query9 .= "    rank1 = '$draw_rank_count[1]', ";
			$query9 .= "    rank2 = '$draw_rank_count[2]', ";
			$query9 .= "    rank3 = '$draw_rank_count[3]', ";
			$query9 .= "    rank4 = '$draw_rank_count[4]', ";
			$query9 .= "    rank5 = '$draw_rank_count[5]', ";
			$query9 .= "    rank6 = '$draw_rank_count[6]', ";
			$query9 .= "    comb2 = $total_combin[2], ";
			$query9 .= "    comb3 = $total_combin[3], ";
			$query9 .= "    comb4 = $total_combin[4], ";
			$query9 .= "    comb5 = $total_combin[5], ";
			$query9 .= "    median = '4.0', ";
			$query9 .= "    nums_total_2 = $temp_nums_count_2, ";
			$query9 .= "    combo_total_2 = $temp_combo_count_2, ";
			$query9 .= "    nums_total_3 = $temp_nums_count_3, ";
			$query9 .= "    combo_total_3 = $temp_combo_count_3, ";
			$query9 .= "    nums_total_4 = $temp_nums_count_4, ";
			$query9 .= "    combo_total_4 = $temp_combo_count_4,	 ";

			#$query9 .= "    wheel_percent_wa = $row_eo50[wa], ";
			$query9 .= "    avg = $num_wa_sum, ";
			$query9 .= "    y1_sum = $sigma_sum, ";
			$query9 .= "    last_updated = '$curr_date' ";
			$query9 .= "WHERE id = '$row[id]' ";

			#echo "$query9<br>";

			#die();
			
			$mysqli_result9 = mysqli_query($mysqli_link, $query9) or die (mysqli_error($mysqli_link));
		}
	}
?>