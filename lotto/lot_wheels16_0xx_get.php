<?php

	// add tables population for combos, pairs
	// add test for missing draws
	// recalcu;ate draw includes
	// add db class
	// error checking - all modules
	// fix pair count

	//////////////////////////////////////////
	//////////// uncomment combin.incl ga f5
	//////////////////////////////////////////

	set_time_limit(0);

	$game = 1; // Georgia Fantasy 5

	$col1 = $_GET["col1"];
	$drop = $_GET["drop"];

	require ("includes/build_rank_table.php");
	require ("includes/last_draws.php");
	require ("includes/first_draw_unix.php");
	require ("includes/last_draw_unix.php");
	require ("includes/dateDiffInDays.php");

	require ("includes_ga_f5/wheel16_split_draws_4.php");
	#require ("includes/print_column_test_sumeo.php");

	$debug = 0;

	if ($debug)
	{
		ini_set('display_errors', '1');
		ini_set('display_startup_errors', '1');
		error_reporting(E_ALL);
	}
	
	require ("includes/games_switch.incl");

	//require ("includes/mysqli.php");
	require ("includes/mysqli.php");

	$curr_date_dash = date("Y-m-d");

	require ("includes_ga_f5/wheel16_combin.incl");

	//start HTML page
	print("<HTML>\n");
	print("<HEAD>\n");
	print("<TITLE>Lotto Update - $game_name</TITLE>\n");
	print("</HEAD>\n");
	
	print("<BODY>\n");

	if ($drop)
	{
		$query3 = "DROP TABLE IF EXISTS ga_f5_wheel16_draws ";
	
		print "$query3<p>";

		$mysqli_result3 = mysqli_query($mysqli_link, $query3) or die (mysqli_error($mysqli_link));

		$query4 = "CREATE TABLE `ga_f5_wheel16_draws` (
					  `id` int UNSIGNED NOT NULL,
					  `d1` tinyint UNSIGNED NOT NULL DEFAULT '0',
					  `d2` tinyint UNSIGNED NOT NULL DEFAULT '0',
					  `d3` tinyint UNSIGNED NOT NULL DEFAULT '0',
					  `d4` tinyint UNSIGNED NOT NULL DEFAULT '0',
					  `d5` tinyint UNSIGNED NOT NULL DEFAULT '0',
					  `sum` smallint UNSIGNED NOT NULL DEFAULT '0',
					  `even` tinyint UNSIGNED NOT NULL DEFAULT '0',
					  `odd` tinyint UNSIGNED NOT NULL DEFAULT '0',
					  `rank0` tinyint UNSIGNED NOT NULL DEFAULT '0',
					  `rank1` tinyint UNSIGNED NOT NULL DEFAULT '0',
					  `rank2` tinyint UNSIGNED NOT NULL DEFAULT '0',
					  `rank3` tinyint UNSIGNED NOT NULL DEFAULT '0',
					  `rank4` tinyint UNSIGNED NOT NULL DEFAULT '0',
					  `rank5` tinyint UNSIGNED NOT NULL DEFAULT '0',
					  `rank6` tinyint UNSIGNED NOT NULL DEFAULT '0',
					  `rank7` tinyint UNSIGNED NOT NULL DEFAULT '0',
					  `seq2` tinyint(1) unsigned NOT NULL,
					  `seq3` tinyint(1) unsigned NOT NULL,
					  `mod` tinyint(1) unsigned NOT NULL,
					  `mod_x` tinyint(1) unsigned NOT NULL,
					  
		
					  
						`dup1` tinyint(1) unsigned NOT NULL,
						`dup2` tinyint(1) unsigned NOT NULL,
						`dup3` tinyint(1) unsigned NOT NULL,
						`dup4` tinyint(1) unsigned NOT NULL,

						`comb2` tinyint(1) unsigned NOT NULL,
					  `comb3` tinyint(1) unsigned NOT NULL,
					  `comb4` tinyint(1) unsigned NOT NULL,
					  `comb5` tinyint(1) unsigned NOT NULL,
					  `d4_1` tinyint(1) unsigned NOT NULL,
					  `d4_2` tinyint(1) unsigned NOT NULL,
					  `d4_3` tinyint(1) unsigned NOT NULL,
					  `d4_4` tinyint(1) unsigned NOT NULL,
					  `pair_sum` tinyint UNSIGNED NOT NULL DEFAULT '0',
					  `draw_average` float(4,2) UNSIGNED NOT NULL DEFAULT '0.00',
					  `median` float(4,2) UNSIGNED NOT NULL DEFAULT '0.00',
					  `harmean` float(4,2) UNSIGNED NOT NULL DEFAULT '0.00',
					  `geomean` float(4,2) UNSIGNED NOT NULL DEFAULT '0.00',
					  `quart1` float(4,2) UNSIGNED NOT NULL DEFAULT '0.00',
					  `quart2` float(4,2) UNSIGNED NOT NULL DEFAULT '0.00',
					  `quart3` float(4,2) UNSIGNED NOT NULL DEFAULT '0.00',
					  `stdev` float(4,2) UNSIGNED NOT NULL DEFAULT '0.00',
					  `variance` float(6,2) UNSIGNED NOT NULL,
					  `avedev` float(4,2) UNSIGNED NOT NULL,
					  `kurt` float(4,2) NOT NULL,
					  `skew` float(4,2) NOT NULL,
					  `devsq` float(7,2) UNSIGNED NOT NULL,
					  `nums_total_2` smallint UNSIGNED NOT NULL,
					  `combo_total_2` tinyint UNSIGNED NOT NULL,
					  `nums_total_3` smallint UNSIGNED NOT NULL,
					  `combo_total_3` tinyint UNSIGNED NOT NULL,
					  `nums_total_4` tinyint UNSIGNED NOT NULL,
					  `combo_total_4` tinyint UNSIGNED NOT NULL,
					  `s1510` float(4,2) NOT NULL,
					  `s61510` float(4,2) NOT NULL,
					  `last_updated` date NOT NULL
					) ENGINE=MyISAM DEFAULT CHARSET=latin1;";

		print "$query4<p>";

		$mysqli_result4 = mysqli_query($mysqli_link, $query4) or die (mysqli_error($mysqli_link));

		$query4b = "ALTER TABLE `ga_f5_wheel16_draws`
					  ADD PRIMARY KEY (`id`),
					  ADD UNIQUE KEY `id` (`id`); ";
		
		print "$query4b<p>";

		$mysqli_result4b = mysqli_query($mysqli_link, $query4b) or die (mysqli_error($mysqli_link));

		$query4c = "ALTER TABLE `ga_f5_wheel16_draws`
						MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT; ";
		
		print "$query4c<p>";

		$mysqli_result4c = mysqli_query($mysqli_link, $query4c) or die (mysqli_error($mysqli_link));
	}
	
	### build rank table ##########################################################################

	$rank_table = BuildRankTable($curr_date_dash);

	### build lastxdraws ##########################################################################
	
	for ($x = 1; $x <= 50; $x++)
	{
		$temp = 'last_' . $x . '_draws';
		${$temp} = array_fill (0,51,0);
	}

	for ($x = 1; $x <= 50; $x++)
	{
		$temp = 'last_' . $x . '_draws';
		${$temp} = LastDraws($curr_date_dash,$x);
	}	

	for ($m = $col1; $m <= $col1; $m++)
	{
		$query_sumeo = "SELECT * FROM ga_f5_sum_count_sum ";
		$query_sumeo .= "WHERE numx >= 83 AND numx <= 128 ";
		#$query_sumeo .= "ORDER BY percent_wa DESC, percent_y4 DESC, percent_y1 DESC, count DESC LIMIT 25 ";
		$query_sumeo .= "ORDER BY month3 DESC, month6 DESC, year1 DESC, year2 DESC LIMIT 25 ";
		
		echo "$query_sumeo<p>";

		$mysqli_result_sumeo = mysqli_query($mysqli_link, $query_sumeo) or die (mysqli_error($mysqli_link));

		// get each row
		while($row_sumeo = mysqli_fetch_array($mysqli_result_sumeo))
		{
			#for ($col = 1; $col <= 5; $col++)
			#{
			#	print_column_test_sumeo($col, $row_sumeo[1], $row_sumeo[2], $row_sumeo[3]); #210528
			#}

			$query17 = "SELECT * FROM `ga_f5_limits_by_sumeo` 
			WHERE `sum` = $row_sumeo[numx] AND `even` = $row_sumeo[even] 
			AND `odd` = $row_sumeo[odd] ";

			echo "$query17<p>";

			$mysqli_result17 = mysqli_query($mysqli_link, $query17) or die (mysqli_error($mysqli_link));

			$low_array = array_fill(0,6,0);
			$high_array = array_fill(0,6,0);

			while($row17 = mysqli_fetch_array($mysqli_result17))
			{
				$col = $row17[2];
				$low_array[$col] = $row17[5];
				$high_array[$col] = $row17[6];
			}

			$query5 = "SELECT * FROM combo_5_42 "; 
			$query5 .= "WHERE b1 = $m ";  
			$query5 .= "AND   b2 >= '$low_array[2]' ";
			$query5 .= "AND   b2 <= '$high_array[2]' ";
			$query5 .= "AND   b3 >= '$low_array[3]' ";
			$query5 .= "AND   b3 <= '$high_array[3]' ";
			$query5 .= "AND   b4 >= '$low_array[4]' ";
			$query5 .= "AND   b4 <= '$high_array[4]' ";
			$query5 .= "AND   b5 >= '32' ";
			$query5 .= "AND   b5 <= '$high_array[5]' ";
			$query5 .= "AND   seq2 <= '1' ";
			$query5 .= "AND   seq3 = '0' ";
			$query5 .= "AND   mod_tot <= '1' ";
			$query5 .= "AND   mod_x = '0' ";
			$query5 .= "AND   sum = '$row_sumeo[numx]' ";
			$query5 .= "AND   even = '$row_sumeo[even]' ";
			$query5 .= "AND   odd = '$row_sumeo[odd]' ";
			$query5 .= "ORDER BY b2 ASC, b3 ASC, b4 ASC, b5 ASC ";  

			echo "$query5<br>";

			$mysqli_result5 = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));

			while ($row = mysqli_fetch_array($mysqli_result5))
			{
				$draw = array_fill (0,5,0);

				$draw[0] = $row[1];
				$draw[1] = $row[2];
				$draw[2] = $row[3];
				$draw[3] = $row[4];
				$draw[4] = $row[5];


				###########################################################################################

					$rank_count = array_fill (0, 8, 0);
					
					for ($index = 0; $index <= 4; $index++) ### 210107
					{
						$val = $draw[$index];

						$count = $rank_table[$val]; 

						#echo "rank count = $count<br>";
					
						switch ($count)
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
								case "6":
									$rank_count[6]++;
									break;
								default:
									$rank_count[7]++;	
						}	

						#print_r ($rank_count);
						#echo "<br>";
					}

					###########################################################################################

					$last_dup = array_fill (0, 51, 0);

					//count repeating numbers
					for ($x = 1 ; $x <= 50; $x++)
					{
						for ($y = 0 ; $y <= 50; $y++)
						{	
							$temp = 'last_' . $x . '_draws';
							if (array_search($draw[$y], ${$temp}) !== FALSE)
							{
								$last_dup[$x]++;
							}
						}
					}

					###########################################################################################
					
					$rank_table_limits = array_fill (0,8,0);
					
					$query1 = "SELECT * FROM ga_f5_rank_table ";
					$query1 .= "WHERE date = '$curr_date_dash' "; 
					$query1 .= "AND   draw_limit = '30' ";
					
					echo "$query1<p>";

					$mysqli_result1 = mysqli_query($mysqli_link, $query1) or die (mysqli_error($mysqli_link));

					$row1 = mysqli_fetch_array($mysqli_result1);

					for ($a = 0; $a <=7; $a++)
					{
						if ($row1[$a] = 0)
						{
							$rank_table_limits[$a] = 0;
						} elseif ($row1[$a] >= 1 AND $row1[$a] <= 3) {
							$rank_table_limits[$a] = 1;
						} elseif ($row1[$a] >= 6 AND $row1[$a] <= 8) {
							$rank_table_limits[$a] = 2;
						} else {
							$rank_table_limits[$a] = 3;
						}
							
					}
					
					###########################################################################################

					$draw_0 = array_fill (0,6,0);

					for ($t = 0; $t <= 4; $t++)
					{
						$u = $t+1;
						$draw_0[$u] = $draw[$t];
					}
					
					$total2 = 0;
					$total3 = 0;
					$total4 = 0;
					$total5 = 0;

					$total_combin = array_fill (0,7,0);

					echo "draw_0<br>";
					print_r ($draw_0);
					echo "<br>";

					$total_combin = test_combin($draw_0);

					###########################################################################################

					$d4 = array_fill (0,6,0);

					for ($index = 0; $index <= 4; $index++) ### 210107
					{
						if ($draw[$index] <= 12) {
							$d4[1]++;
						} elseif ($draw[$index] <= 24) {
							$d4[2]++;
						} elseif ($draw[$index] <= 36) {
							$d4[3]++;
						} else {
							$d4[4]++;
						}
					}

					sort($draw);

					###########################################################################################

					echo "$draw[0] - $draw[1] - $draw[2] - $draw[3] - $draw[4]<br>";

					$sum = $row_sumeo[1];
					$even = $row_sumeo[2];
					$odd = $row_sumeo[3];

					require ("includes_ga_f5/sigma_column.incl");

					$query4 = "INSERT INTO `ga_f5_wheel16_draws` 
					(`id`, `d1`, `d2`, `d3`, `d4`, `d5`, `sum`, `even`, `odd`,`seq2`, `seq3`, `mod`, `mod_x`, `rank0`, `rank1`, `rank2`, `rank3`, `rank4`, `rank5`, `rank6`, `rank7`, `dup1`, `dup2`, `dup3`, `dup4`, `comb2`, `comb3`, `comb4`, `comb5`, `d4_1`, `d4_2`, `d4_3`, `d4_4`, `pair_sum`, `draw_average`, `median`, `harmean`, `geomean`, `quart1`, `quart2`, `quart3`, `stdev`, `variance`, `avedev`, `kurt`, `skew`, `devsq`, `nums_total_2`, `combo_total_2`, `nums_total_3`, `combo_total_3`, `nums_total_4`, `combo_total_4`, `s1510`, `s61510`, `last_updated`) 
					VALUES 
					('0', '$draw[0]', '$draw[1]', '$draw[2]', '$draw[3]', '$draw[4]', 
					'$row[sum]', '$row[even]', '$row[odd]', '$row[seq2]', '$row[seq3]', '$row[mod_tot]', '$row[mod_x]', 
					'$rank_count[0]', '$rank_count[1]', '$rank_count[2]', '$rank_count[3]', 
					'$rank_count[4]', '$rank_count[5]', '$rank_count[6]', '$rank_count[7]', 
					'$last_dup[1]', '$last_dup[2]', '$last_dup[3]', '$last_dup[4]', 
					'$total_combin[2]', '$total_combin[3]', '$total_combin[4]', '$total_combin[5]', 
					'$d4[1]', '$d4[2]', '$d4[3]', '$d4[4]', 
					'0', '0', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '$sigma_sumeo', '$curr_date_dash')";

					print("$query4<br>");

					$mysqli_result4 = mysqli_query($mysqli_link, $query4) or die (mysqli_error($mysqli_link));

			}
		}
	}

	print("<h2>Completed!</h2>");

	wheel16_split_draws_4 ($dateDiff);

	print("</BODY>\n");
?>




