<?php
	function update_draw_case($dc_combo_table)
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

		$query = "SELECT * FROM ga_f5_temp_26  ";
		$query .= "ORDER BY num ASC ";

		print "$query<br>";

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

		$query_ct = "SELECT * FROM ga_f5_column_1";
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

		$query_ct = "SELECT * FROM ga_f5_column_2";
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

		$query_ct = "SELECT * FROM ga_f5_column_3";
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

		$query_ct = "SELECT * FROM ga_f5_column_4";
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

		$query_ct = "SELECT * FROM ga_f5_column_5";
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
				
					$query_dc =  "SELECT * FROM $dc_combo_table ";
					$query_dc .= "WHERE last_updated < '$curr_date' ";

					echo "<p>$query_dc</p>";

					$mysqli_result_dc = mysqli_query($query_dc, $mysqli_link) or die (mysqli_error($mysqli_link));

					$num_rows_dc = mysqli_num_rows($mysqli_result_dc);

					echo "dc rows to update - $num_rows_dc<br>";

					while($row_dc = mysqli_fetch_array($mysqli_result_dc))
					{
						$reject = 0;

						$c = 1;

						#echo "<strong>$row_dc[b1], $row_dc[b2], $row_dc[b3], $row_dc[b4], $row_dc[b5]</strong><br>";
						
						$dup_count = array_fill (0,7,0);

						//count repeating numbers
						for ($x = 1 ; $x <= 25; $x++)
						{
							for ($y = 1 ; $y <= 5; $y++)
							{	
								$temp_b = "b" . $y;

								if (array_search($row_dc[$temp_b], ${last_.$x._draws}) !== FALSE)
								{
									$dup_count[$x]++;
									#echo "dup - $row_dc[$temp_b] found - dup_count[$x] = $dup_count[$x]<br>";
								}
							}
						}
						
						$query_rank = "SELECT * FROM ga_f5_temp_26  ";
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
							if ($rank_count[$row_dc[$y]] >= 6)
							{
								$draw_rank_count[6]++;
								$temp_rank = 6;
							} else {
								$temp_rank = $rank_count[$row_dc[$y]];
								$draw_rank_count[$temp_rank]++;
							}
							#echo "rank -- $row_dc[$y] = $temp_rank<br>";
						}

						$total_combin = array_fill (0,7,0);

						$total_combin = test_combin($row_dc);

						$query_eo50 = "SELECT * FROM ga_f5_wheel_sum_table_25  ";
						$query_eo50 .= "WHERE sum = $row_dc[sum] ";
						#$query_eo50 .= "AND   even = $row_dc[even] ";
						#$query_eo50 .= "AND   odd = $row_dc[odd] ";
						$query_eo50 .= "AND   d4_1 = $row_dc[d4_1] ";
						$query_eo50 .= "AND   d4_2 = $row_dc[d4_2] ";
						$query_eo50 .= "AND   d4_3 = $row_dc[d4_3] ";
						$query_eo50 .= "AND   d4_4 = $row_dc[d4_4] ";

						#print "$query_eo50<br>";

						$mysqli_result_eo50 = mysqli_query($query_eo50, $mysqli_link) or die (mysqli_error($mysqli_link));

						$row_eo50 = mysqli_fetch_array($mysqli_result_eo50);

						#echo "begin m6_sum<br>";
						require ("includes_ga_f5/y1_sum_hml.incl");
						#echo "end m6_sum<br>";

						$query9 = "UPDATE $dc_combo_table ";
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
						$query9 .= "WHERE id = '$row_dc[id]' ";

						#echo "$query9<br>";
						
						$mysqli_result9 = mysqli_query($mysqli_link, $query9) or die (mysqli_error($mysqli_link));

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
						$query9 .= "WHERE id = '$row_dc[id]' ";

						#echo "$query9<br>";
						
						$mysqli_result9 = mysqli_query($mysqli_link, $query9) or die (mysqli_error($mysqli_link));

						#die();
					}
				#}
			#}
		#}
	}
?>