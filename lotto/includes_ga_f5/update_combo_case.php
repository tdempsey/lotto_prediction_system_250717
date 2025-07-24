<?php
	function update_combo_case($combo,$sum,$even,$odd,$d4_1,$d4_2,$d4_3,$d4_4)
	{
		require ("includes/mysqli.php");
		#require ("includes_ga_f5/pair_draw_filter.incl");


		global $draw_prefix,$hml,$balls_drawn,$balls;
		
		require ("includes/combo_switch_2.incl");

		$curr_date = date("Y-m-d");
		$curr_date = date("1962-08-17");

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

		$query = "SELECT * FROM ga_f5_temp2a_26  ";
		$query .= "ORDER BY num ASC ";

		#print "$query<br>";

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

		while($row = mysqli_fetch_array($mysqli_result))
		{
			$v = $row[num];
			$rank_count[$v] = $row[count];
		}

		echo "rank table - ";
		print_r ($rank_count);
		echo "<br>";

		$rank_table_count = array_fill (0, 7, 0);

		for($z = 1; $z <= $balls; $z++)
		{
			if ($rank_count[$z] >= 6)
			{
				$rank_table_count[6]++;
			} else {
				$rank_table_count[$rank_count[$z]]++;
			}
		}

		print_r ($rank_table_count);
		echo "<br>";

		echo "<h3>Update Case - hml = $hml</h3>";

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

		$query_ct = "SELECT DISTINCT sum, b1, b3, d4_1, d4_2, d4_3, d4_4 FROM temp_cases_";
		if ($hml)
		{
			$query_ct .= "$hml ";
		}
		$query_ct .= "WHERE sum = $sum ";
		#$query_ct .= "AND   even = $even ";
		#$query_ct .= "AND   odd = $odd ";
		$query_ct .= "AND   d4_1 = $d4_1 ";
		$query_ct .= "AND   d4_2 = $d4_2 ";
		$query_ct .= "AND   d4_3 = $d4_3 ";
		$query_ct .= "AND   d4_4 = $d4_4 ";
		#$query_ct .= " ORDER BY percent_y1 DESC, percent_y5 DESC ";
		#$query_ct .= "LIMIT 7 ";

		print "$query_ct<p>";

		$mysqli_result_ct = mysqli_query($query_ct, $mysqli_link) or die (mysqli_error($mysqli_link));

		while($row_ct = mysqli_fetch_array($mysqli_result_ct))
		{
				/*
				array_push($num5_temp,$row_ct[num]);	
		}

		echo "<strong>num5_temp = </strong>";
		print_r ($num5_temp);
		echo "<br>";

		##################################################################################################

		foreach ($num3_temp as $q)  
		{
			foreach ($num1_temp as $s)  
			{
				if ($s < $q AND ($s <= 30))  
				{*/
					#echo "<p><b>num1_temp = $s</b><br>";
					#echo "<b>num3_temp = $q</b><br>";
				
					$query_dup = "SELECT * FROM combo_5_42 ";
					$query_dup .= "WHERE sum = $sum ";
					#$query_dup .= "AND   even = $even ";
					#$query_dup .= "AND   odd = $odd ";
					$query_dup .= "AND   d4_1 = $d4_1 ";
					$query_dup .= "AND   d4_2 = $d4_2 ";
					$query_dup .= "AND   d4_3 = $d4_3 ";
					$query_dup .= "AND   d4_4 = $d4_4 ";
					$query_dup .= "AND seq2 <= 1 ";
					$query_dup .= "AND mod_tot <= 1 ";

					#$query_dup .= "AND   b1 = $row_ct[b1] ";
					#$query_dup .= "AND   b3 = $row_ct[b3] ";	

					#$query_dup .= "AND   last_updated < '$curr_date' ";
					$query_dup .= "ORDER BY b1 ASC, b3 ASC ";

					echo "<p>$query_dup</p>";

					$mysqli_result_dup = mysqli_query($query_dup, $mysqli_link) or die (mysqli_error($mysqli_link));

					$num_rows_dup = mysqli_num_rows($mysqli_result_dup);

					echo "dup rows to update - $num_rows_dup<br>";

					while($row = mysqli_fetch_array($mysqli_result_dup))
					{
						$reject = 0;

						$c = 1;

						echo "<strong>$row[b1], $row[b2], $row[b3], $row[b4], $row[b5]</strong><br>";
						
						$dup_count = array_fill (0,7,0);

						//count repeating numbers
						for ($x = 1 ; $x <= 25; $x++)
						{
							for ($y = 1 ; $y <= 5; $y++)
							{	
								$temp_b = "b" . $y;

								if (array_search($row[$temp_b], ${last_.$x._draws}) !== FALSE)
								{
									$dup_count[$x]++;
									#echo "dup - $row[$temp_b] found - dup_count[$x] = $dup_count[$x]<br>";
								}
							}
						}
						
						$query_rank = "SELECT * FROM ga_f5_temp2a_26  ";
						$query_rank .= "ORDER BY num ASC ";

						#print "$query_rank<br>";

						$mysqli_result_rank = mysqli_query($query_rank, $mysqli_link) or die (mysqli_error($mysqli_link));

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
							#echo "rank -- $row[$y] = $temp_rank<br>";
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

						$mysqli_result_eo50 = mysqli_query($query_eo50, $mysqli_link) or die (mysqli_error($mysqli_link));

						$row_eo50 = mysqli_fetch_array($mysqli_result_eo50);

						#echo "begin m6_sum<br>";
						require ("includes_ga_f5/y1_sum.incl");
						#echo "end m6_sum<br>";

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

						#$query9 .= "    wheel_percent_wa = $row_eo50[wa], ";
						$query9 .= "    y1_sum = $y1_sum, ";
						$query9 .= "    last_updated = '$curr_date' ";
						$query9 .= "WHERE id = '$row[id]' ";

						#echo "$query9<br>";
						
						$mysqli_result9 = mysqli_query($mysqli_link, $query9) or die (mysqli_error($mysqli_link));

						#die();
					}
				#}
			#}
		}
	}
?>