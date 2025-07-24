<?php
	function update_combo_sumeo_5 ($sum,$even,$odd)
	{
		$executionStartTime = microtime(true);

		require ("includes/mysqli.php");
		
		global $draw_prefix,$hml,$balls_drawn,$balls;
		
		require ("includes/combo_switch_2.incl");

		$curr_date = date("Y-m-d");

		$table_temp = 'temp_5a_' . $balls . '_' . $sum . '_' . $even . '_' . $odd;

		echo "$table_temp<p>";

	 	### build lastxdraws
	
		for ($x = 1; $x <= 50; $x++)
		{
			${'last_'.$x.'_draws'} = array_fill (0,51,0);
		}

		for ($x = 1; $x <= 50; $x++)
		{
			${'last_'.$x.'_draws'} = LastDraws($curr_date,$x);
		}	

		$query = "SELECT * FROM ga_f5_temp2a_30  ";
		$query .= "ORDER BY num ASC ";

		print "$query<br>";

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

		while($row = mysqli_fetch_array($mysqli_result))
		{
			$v = $row[0];
			$rank_count[$v] = $row[1];
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
		echo "<h3>Update SumEO = $sum,$even,$odd</h3>";

		#################################################################################

		$table_temp = 'temp_5a_' . $balls . '_' . $sum . '_' . $even . '_' . $odd;

		$query_ct = "SELECT * FROM $table_temp ";
		#$query_ct .= "WHERE b1 <= '9' ";
		$query_ct .= "WHERE  last_updated < '$curr_date' ";
		$query_ct .= "ORDER BY id ASC ";

		echo "<p>$query_ct</p>";

		$mysqli_result_ct = mysqli_query($mysqli_link, $query_ct) or die (mysqli_error($mysqli_link));

		$num_rows_ct = mysqli_num_rows($mysqli_result_ct);

		echo "combo rows to update - $num_rows_ct<br>";

		while($row = mysqli_fetch_array($mysqli_result_ct))
		{
			$reject = 0;

			$c = 1;

			#echo "<strong>$row[b1], $row[b2], $row[b3], $row[b4], $row[b5]</strong><br>";
			
			$dup_count = array_fill (0,26,0);

			//count repeating numbers
			for ($x = 1 ; $x <= 50; $x++)
			{
				for ($y = 1 ; $y <= 5; $y++)
				{	
					$temp_b = "b" . $y;
					#echo "row[$temp_b] = $row[$temp_b]<br>";

					#echo "last_.$x._draws<br>";
					#print_r (${last_.$x._draws});
					#echo "<p>";

					if (array_search($row[$temp_b], ${'last_'.$x.'_draws'}) !== FALSE)
					{
						$dup_count[$x]++;
						#echo "dup - $row[$temp_b] found - dup_count[$x] = $dup_count[$x]<br>";
					}
				}
			}
			
			$query_rank = "SELECT * FROM ga_f5_temp2a_30  ";
			$query_rank .= "ORDER BY num ASC ";

			#print "$query_rank<br>";

			$mysqli_result_rank = mysqli_query($mysqli_link, $query_rank) or die (mysqli_error($mysqli_link));

			while($row_rank = mysqli_fetch_array($mysqli_result_rank))
			{
				$v = $row_rank[0];
				$rank_count[$v] = $row_rank[1];
			}

			$draw_rank_count = array_fill (0, 8	, 0);

			for($y = 1; $y <= $balls_drawn; $y++)
			{
				if ($rank_count[$row[$y]] >= 7)
				{
					$draw_rank_count[7]++;
					$temp_rank = 7;
				} else {
					$temp_rank = $rank_count[$row[$y]];
					$draw_rank_count[$temp_rank]++;
				}
				#echo "rank -- $row[$y] = $temp_rank<br>";
			}

			$total_combin = array_fill (0,7,0);

			$total_combin = test_combin_sumeo($row);

			require ("includes_ga_f5/sigma_sumeo.incl");

			$row[0] = date("Y-m-d");

			require ("includes/combo_count_date.incl");

			#die();

			$query9 = "UPDATE $table_temp ";
			$query9 .= "SET rank0 = '$draw_rank_count[0]', ";
			$query9 .= "    rank1 = '$draw_rank_count[1]', ";
			$query9 .= "    rank2 = '$draw_rank_count[2]', ";
			$query9 .= "    rank3 = '$draw_rank_count[3]', ";
			$query9 .= "    rank4 = '$draw_rank_count[4]', ";
			$query9 .= "    rank5 = '$draw_rank_count[5]', ";
			$query9 .= "    rank6 = '$draw_rank_count[6]', ";
			$query9 .= "    rank7 = '$draw_rank_count[7]', ";
			$query9 .= "    comb2 = $total_combin[2], ";
			$query9 .= "    comb3 = $total_combin[3], ";
			$query9 .= "    comb4 = $total_combin[4], ";
			$query9 .= "    comb5 = $total_combin[5], ";
			#$query9 .= "    nums_total_2 = $temp_nums_count_2, ";
			#$query9 .= "    combo_total_2 = $temp_combo_count_2, ";
			#$query9 .= "    nums_total_3 = $temp_nums_count_3, ";
			#$query9 .= "    combo_total_3 = $temp_combo_count_3, ";
			#$query9 .= "    nums_total_4 = $temp_nums_count_4, ";
			#$query9 .= "    combo_total_4 = $temp_combo_count_4,	 ";

			#$query9 .= "    wheel_percent_wa = $row_eo50[wa], ";
			$query9 .= "    y1_sum = $sigma_sumeo, ";
			$query9 .= "    last_updated = '$curr_date' ";
			$query9 .= "WHERE id = '$row[id]' ";

			#echo "$query9<br>";
			
			$mysqli_result9 = mysqli_query($mysqli_link, $query9) or die (mysqli_error($mysqli_link));

			$query_dup = "SELECT * FROM `combo_5_42_dup_table` ";
			$query_dup .= "WHERE  id = '$row[id]' ";
			$query_dup .= "ORDER BY id ASC ";

			echo "<p>$query_dup</p>";

			$mysqli_result_dup = mysqli_query($mysqli_link, $query_dup) or die (mysqli_error($mysqli_link));

			$num_rows_dup = mysqli_num_rows($mysqli_result_ct);

			if ($num_rows_dup)
			{
				$query9 = "INSERT INTO `combo_5_42_dup_table` (`id`, `dup1`, `dup2`, `dup3`, `dup4`, `dup5`, `dup6`, `dup7`, `dup8`, `dup9`, `dup10`, `dup11`, `dup12`, `dup13`, `dup14`, `dup15`, `dup16`, `dup17`, `dup18`, `dup19`, `dup20`, `dup21`, `dup22`, `dup23`, `dup24`, `dup25`, `dup26`, `dup27`, `dup28`, `dup29`, `dup30`, `dup31`, `dup32`, `dup33`, `dup34`, `dup35`, `dup36`, `dup37`, `dup38`, `dup39`, `dup40`, `dup41`, `dup42`, `dup43`, `dup44`, `dup45`, `dup46`, `dup47`, `dup48`, `dup49`, `dup50`, `last_updated`) VALUES ('$row[id]', '$dup_count[1]', '$dup_count[2]', '$dup_count[3]', '$dup_count[4]', '$dup_count[5]', '$dup_count[6]', '$dup_count[7]', '$dup_count[8]', '$dup_count[9]', '$dup_count[10]', '$dup_count[11]', '$dup_count[12]', '$dup_count[13]', '$dup_count[14]', '$dup_count[15]', '$dup_count[16]', '$dup_count[17]', '$dup_count[18]', '$dup_count[19]', '$dup_count[20]', '$dup_count[21]', '$dup_count[22]', '$dup_count[23]', '$dup_count[24]', '$dup_count[25]', '$dup_count[26]', '$dup_count[27]', '$dup_count[28]', '$dup_count[29]', '$dup_count[30]', '$dup_count[31]', '$dup_count[32]', '$dup_count[33]', '$dup_count[34]', '$dup_count[35]', '$dup_count[36]', '$dup_count[37]', '$dup_count[38]', '$dup_count[39]', '$dup_count[40]', '$dup_count[41]', '$dup_count[42]', '$dup_count[43]', '$dup_count[44]', '$dup_count[45]', '$dup_count[46]', '$dup_count[47]', '$dup_count[48]', '$dup_count[49]', '$dup_count[50]', '$curr_date'); ";
			} else {
				$query9 = "UPDATE $table_temp ";
				$query9 .= "SET dup1 = '$dup_count[1]', ";

				for ($g = 2; $g <= 50; $g++)
				{
					$query9 .= "dup";
					$query9 .= "$g = '$dup_count[$g]', ";
				}

				$query9 .= "    last_updated = '$curr_date' ";
			}

			#echo "$query9<br>";
			
			$mysqli_result9 = mysqli_query($mysqli_link, $query9) or die (mysqli_error($mysqli_link));
		}

		$executionEndTime = microtime(true);
 
		//The result will be in seconds and milliseconds.
		$seconds = $executionEndTime - $executionStartTime;
		 
		//Print it out
		echo "<h3>This script took $seconds to execute.</h3>";
	}
?>