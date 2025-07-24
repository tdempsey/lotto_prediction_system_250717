<?php
	function update_combo_sumeo_5 ($sum,$even,$odd)
	{
		require ("includes/mysqli.php");
		
		global $draw_prefix,$hml,$balls_drawn,$balls;
		
		require ("includes/combo_switch_2.incl");

		$curr_date = date("Y-m-d");

		$table_temp = 'combo_5_' . $balls . '_' . $sum . '_' . $even . '_' . $odd;

	 	### build lastxdraws
	
		for ($x = 1; $x <= 50; $x++)
		{
			${last_.$x._draws} = array_fill (0,51,0);
		}

		for ($x = 1; $x <= 25; $x++)
		{
			${last_.$x._draws} = LastDraws($curr_date,$x);
		}	

		$query = "SELECT * FROM ga_f5_temp2a_30  ";
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
		echo "<h3>Update SumEO = $sum,$even,$odd</h3>";

		#################################################################################

		$table_temp = 'combo_5_' . $balls . '_' . $sum . '_' . $even . '_' . $odd;

		$query_ct = "SELECT * FROM $table_temp ";
		$query_ct .= "ORDER BY id ASC ";

		echo "<p>$query_ct</p>";

		$mysqli_result_ct = mysqli_query($query_ct, $mysqli_link) or die (mysqli_error($mysqli_link));

		$num_rows_ct = mysqli_num_rows($mysqli_result_ct);

		echo "combo rows to update - $num_rows_ct<br>";

		while($row = mysqli_fetch_array($mysqli_result_ct))
		{
			$reject = 0;

			$c = 1;

			echo "<strong>$row[b1], $row[b2], $row[b3], $row[b4], $row[b5]</strong><br>";
			
			$dup_count = array_fill (0,26,0);

			//count repeating numbers
			for ($x = 1 ; $x <= 25; $x++)
			{
				for ($y = 1 ; $y <= 5; $y++)
				{	
					$temp_b = "b" . $y;
					#echo "row[$temp_b] = $row[$temp_b]<br>";

					#echo "last_.$x._draws<br>";
					#print_r (${last_.$x._draws});
					#echo "<p>";

					if (array_search($row[$temp_b], ${last_.$x._draws}) !== FALSE)
					{
						$dup_count[$x]++;
						#echo "dup - $row[$temp_b] found - dup_count[$x] = $dup_count[$x]<br>";
					}
				}
			}
			
			$query_rank = "SELECT * FROM ga_f5_temp2a_30  ";
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
				echo "rank -- $row[$y] = $temp_rank<br>";
			}

			$total_combin = array_fill (0,7,0);

			$total_combin = test_combin($row);

			require ("includes_ga_f5/sigma_sum.incl");

			$row[0] = date("Y-m-d");

			require ("includes/combo_count_date.incl");

			#die();

			$query9 = "UPDATE $table_temp ";
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
			$query9 .= "    nums_total_2 = $temp_nums_count_2, ";
			$query9 .= "    combo_total_2 = $temp_combo_count_2, ";
			$query9 .= "    nums_total_3 = $temp_nums_count_3, ";
			$query9 .= "    combo_total_3 = $temp_combo_count_3, ";
			$query9 .= "    nums_total_4 = $temp_nums_count_4, ";
			$query9 .= "    combo_total_4 = $temp_combo_count_4,	 ";

			#$query9 .= "    wheel_percent_wa = $row_eo50[wa], ";
			$query9 .= "    y1_sum = $sigma_sum, ";
			$query9 .= "    last_updated = '$curr_date' ";
			$query9 .= "WHERE id = '$row[id]' ";

			#echo "$query9<br>";

			die();
			
			$mysqli_result9 = mysqli_query($mysqli_link, $query9) or die (mysqli_error($mysqli_link));
		}
	}
?>