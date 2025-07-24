<?php

	$game = 3; // Georgia F5

	set_time_limit(0);

	// include to connect to database
		
	require ("includes/count_2_seq.php");
	require ("includes/count_3_seq.php");
	#require ("includes_fl/build_rank_table_fl.php");
	#require ("includes_fl/calculate_rank_fl.php");
	require ("includes_ga_f5/last_draws_ga_f5.php");
	require ("includes_ga_f5/combin.incl");

	$debug = 1;

	if ($debug)
	{
		ini_set('display_errors', '1');
		ini_set('display_startup_errors', '1');
		error_reporting(E_ALL);
	}

	require ($_SERVER['DOCUMENT_ROOT'].'/lotto/includes/mysqli.php');

	$mysqli_link = mysqli_connect('localhost', 'root', 'wef5esuv', 'ga_f5_lotto');

	if (!$mysqli_link) {
		die('Connect Error (' . mysqli_connect_errno() . ') '
				. mysqli_connect_error());
	}

	echo 'Success... ' . mysqli_get_host_info($mysqli_link) . "\n";

	#mysqli_close($mysqli_link);

	echo "1<br>";

	require ("includes/mysqli.php");

	echo "2<br>";

	//start HTML page
	print("<HTML>\n");
	print("<HEAD>\n");
	print("<TITLE>Build Combo 5/42</TITLE>\n");
	print("</HEAD>\n");
	
	print("<BODY bgcolor=\"#FFFFFF\" text=\"#000000\">\n");

	$curr_date = date('Y-m-d');

	$drop_tables = 0;
	
	if ($drop_tables)
	{
		for ($k = 1; $k < 38; $k++)
		{
			if ($k < 10)
			{
				$col = '0' .  $k;
			} else {
				$col = $k;
			}

			$combo_table = "combo_6_42_";
			$combo_table .= "$col";

			$query = "DROP TABLE IF EXISTS $combo_table ";

			echo "$query<p>";

			$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

			$query =  "CREATE TABLE IF NOT EXISTS  $combo_table ( ";
			$query .= "  `id` int(10) unsigned NOT NULL auto_increment, ";
			$query .= "  `b1` tinyint(2) unsigned NOT NULL default '0', ";
			$query .= "  `b2` tinyint(2) unsigned NOT NULL default '0', ";
			$query .= "  `b3` tinyint(2) unsigned NOT NULL default '0', ";
			$query .= "  `b4` tinyint(2) unsigned NOT NULL default '0', ";
			$query .= "  `b5` tinyint(2) unsigned NOT NULL default '0', ";
			$query .= "  `b6` tinyint(2) unsigned NOT NULL default '0', ";
			$query .= "  `sum` int(5) unsigned NOT NULL default '0', ";
			$query .= "  `hml` int(3) unsigned NOT NULL default '0', ";
			$query .= "  `even` tinyint(1) unsigned NOT NULL default '0', ";
			$query .= "  `odd` tinyint(1) unsigned NOT NULL default '0', ";
			$query .= "  `d0` tinyint(1) unsigned NOT NULL default '0', ";
			$query .= "  `d1` tinyint(1) unsigned NOT NULL default '0', ";
			$query .= "  `d2` tinyint(1) unsigned NOT NULL default '0', ";
			$query .= "  `d3` tinyint(1) unsigned NOT NULL default '0', ";
			$query .= "  `d4` tinyint(1) unsigned NOT NULL default '0', ";
			$query .= "  `rank0` tinyint(1) unsigned NOT NULL default '0', ";
			$query .= "  `rank1` tinyint(1) unsigned NOT NULL default '0', ";
			$query .= "  `rank2` tinyint(1) unsigned NOT NULL default '0', ";
			$query .= "  `rank3` tinyint(1) unsigned NOT NULL default '0', ";
			$query .= "  `rank4` tinyint(1) unsigned NOT NULL default '0', ";
			$query .= "  `rank5` tinyint(1) unsigned NOT NULL default '0', ";
			$query .= "  `rank6` tinyint(1) unsigned NOT NULL default '0', ";
			$query .= "  `mod_tot` tinyint(1) unsigned NOT NULL default '0', ";
			$query .= "  `mod_x` tinyint(1) unsigned NOT NULL default '0', ";
			$query .= "  `seq2` tinyint(1) unsigned NOT NULL default '0', ";
			$query .= "  `seq3` tinyint(1) unsigned NOT NULL default '0', ";
			$query .= "  `comb2` tinyint(1) unsigned NOT NULL default '0', ";
			$query .= "  `comb3` tinyint(1) unsigned NOT NULL default '0', ";
			$query .= "  `comb4` tinyint(1) unsigned NOT NULL default '0', ";
			$query .= "  `comb5` tinyint(1) unsigned NOT NULL default '0', ";
			$query .= "  `comb6` tinyint(1) unsigned NOT NULL default '0', ";
			$query .= "  `dup1` tinyint(1) unsigned NOT NULL default '0', ";
			$query .= "  `dup2` tinyint(1) unsigned NOT NULL default '0', ";
			$query .= "  `dup3` tinyint(1) unsigned NOT NULL default '0', ";
			$query .= "  `dup4` tinyint(1) unsigned NOT NULL default '0', ";
			$query .= "  `dup5` tinyint(1) unsigned NOT NULL default '0', ";
			$query .= "  `dup6` tinyint(1) unsigned NOT NULL default '0', ";
			$query .= "  `dup7` tinyint(1) unsigned NOT NULL default '0', ";
			$query .= "  `dup8` tinyint(1) unsigned NOT NULL default '0', ";
			$query .= "  `dup9` tinyint(1) unsigned NOT NULL default '0', ";
			$query .= "  `dup10` tinyint(1) unsigned NOT NULL default '0', ";
			$query .= "  `pair_sum` mediumint(8) unsigned NOT NULL default '0', ";
			$query .= "  `avg` float(4,2) unsigned NOT NULL default '0.00', ";
			$query .= "  `median` float(4,2) unsigned NOT NULL default '0.00', ";
			$query .= "  `harmean` float(4,2) unsigned NOT NULL default '0.00', ";
			$query .= "  `geomean` float(4,2) unsigned NOT NULL default '0.00', ";
			$query .= "  `quart1` float(4,2) unsigned NOT NULL default '0.00', ";
			$query .= "  `quart2` float(4,2) unsigned NOT NULL default '0.00', ";
			$query .= "  `quart3` float(4,2) unsigned NOT NULL default '0.00', ";
			$query .= "  `stdev` float(4,2) unsigned NOT NULL default '0.00', ";
			$query .= "  `variance` float(6,2) unsigned NOT NULL default '0.00', ";
			$query .= "  `avedev` float(4,2) unsigned NOT NULL default '0.00', ";
			$query .= "  `kurt` float(4,2) NOT NULL default '0.00', ";
			$query .= "  `skew` float(4,2) NOT NULL default '0.00', ";
			$query .= "  `devsq` float(6,2) unsigned NOT NULL default '0.00', ";
			$query .= "  `wheel_cnt5000` mediumint(5) unsigned NOT NULL default '0', ";
			$query .= "  `wheel_percent_wa` float(4,2) unsigned NOT NULL default '0.00', ";
			$query .= "  `draw_last` date NOT NULL default '1962-08-17', ";
			$query .= "  `draw_count` tinyint(3) unsigned NOT NULL default '0', ";
			$query .= "  `last_updated` date NOT NULL default '1962-08-17', ";
			$query .= "  PRIMARY KEY  (`id`) ";
			$query .= ") ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 "; 

			echo "$query<p>";

			$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

			$query7 = "INSERT INTO $combo_table ";
			$query7 .= "SELECT * FROM combo_6_42 ";
			$query7 .= "WHERE b1 = $col ";

			echo "$query7<p>";

			$mysqli_result7 = mysqli_query($mysqli_link, $query7) or die (mysqli_error($mysqli_link));
		}
	}

	$max_num = 42;

	echo "<p>max_num = $max_num</p>";

	$count = 0;
	$print_flag = 0;
	$draw_num = array_fill (0, 10, 0);
	
	#$k = 12; ##########################################################################################################
	for ($k = 13; $k <= 13; $k++)
	{
		echo "<h2>k = $k</h2>";

		if ($k < 10)
			{
				$col = '0' .  $k;
			} else {
				$col = $k;
			}

		$combo_table = "combo_6_42_";
		$combo_table .= "$col";

		$query4 = "SELECT * FROM $combo_table ";
		$query4 .= "WHERE b1 = $k ";
		$query4 .= "ORDER BY id DESC ";
		#$query4 .= "LIMIT 1 ";

		echo $query4;

		$mysqli_result4 = mysqli_query($mysqli_link, $query4) or die (mysqli_error($mysqli_link));

		$num_rows = mysqli_num_rows($mysqli_result4);

		echo "<p>### num_rows = $num_rows</p>";

		if ($num_rows > 0)
		{
			$row4 = mysqli_fetch_array($mysqli_result4);

			if ($row4[2] < 38)
			{
				echo "<p>row4[2] < 38</p>";
				$draw_num[1] = $row4[1];
				$draw_num[2] = $row4[2];
				$draw_num[3] = $row4[3];
				$draw_num[4] = $row4[4];
				$draw_num[5] = $row4[5];
				$draw_num[6] = $row4[6];

				echo "<p>row4 = <br>";
				print_r ($row4);
			}

			echo "<br>draw_num<br>";
			print_r ($draw_num);
		} else {
				echo "num_rows = 0<br>";
				$draw_num[1] = $k;
				$draw_num[2] = $k+1;
				$draw_num[3] = $k+2;
				$draw_num[4] = $k+3;
				$draw_num[5] = $k+4;
				$draw_num[6] = $k+5;
		}

		#while ($draw_num[1] == $row4[1] AND $row4[2] < 38)
		while ($draw_num[2] < 38)
		{
			$num_rows = 0;

			### check for existing table row and skip
			$query6 = "SELECT * FROM $combo_table ";
			$query6 .= "WHERE b1 = '$draw_num[1]' ";
			$query6 .= "AND   b2 = '$draw_num[2]' ";
			$query6 .= "AND   b3 = '$draw_num[3]' ";
			$query6 .= "AND   b4 = '$draw_num[4]' ";
			$query6 .= "AND   b5 = '$draw_num[5]' ";
			$query6 .= "AND   b6 = '$draw_num[6]' ";

			#echo "$query6<br>";

			$mysqli_result6 = mysqli_query($mysqli_link, $query6) or die (mysqli_error($mysqli_link));

			$num_rows = mysqli_num_rows($mysqli_result6);

			$row6 = mysqli_fetch_array($mysqli_result6);

			#echo "<b>2 num_rows = $num_rows</b><br>";

			#die();
			
			if ($num_rows == 0 and $draw_num[1] <= 37) # add row to table
			{
				$even = 0;
				$odd = 0;
				$d501 = 0;
				$d502 = 0;
				$d3_array = array_fill (0,3,0);
				$d4_array = array_fill (0,4,0);
				$seq2 = 0;
				$seq3 = 0;
				$mod_total = 0;
				$dup_pass = 1;
				$mod_x = 0;

				$total_combin = array_fill (0,10,0);
				$num_count = array_fill (0,10,0);
				$rank_count = array_fill (0,10,0);
				$mod = array_fill (0,10,0);
				$dup_count = array_fill (0,11,0);

				$draw_array = array ($draw_num[1],$draw_num[2],$draw_num[3],$draw_num[4],$draw_num[5],$draw_num[6]);
				$draw_array_0 = array (0,$draw_num[1],$draw_num[2],$draw_num[3],$draw_num[4],$draw_num[5],$draw_num[6]);

				$sum =	$draw_num[1] + $draw_num[2] + $draw_num[3] + $draw_num[4] + $draw_num[5] + $draw_num[6] ;

				print "<h3>$draw_num[1] - $draw_num[2] - $draw_num[3] - $draw_num[4] - $draw_num[5] - $draw_num[6]</h3>";
				
				#print "sum = $sum<br>";

				$seq2 = Count2Seq($draw_array); ################################################################################
				$seq3 = Count3Seq($draw_array); ################################################################################

				#$total_combin = test_combin($draw_num);

				for ($index = 1; $index <= 6; $index++)
				{ 
					if ($draw_num[$index] > 21) {
						$d502++;
					} else {
						$d501++;
					}

					if ($draw_num[$index] > 28) {
						$d3_array[2]++;
					} elseif ($draw_num[$index] > 14) {
						$d3_array[1]++;
					} else {
						$d3_array[0]++;
					}

					if ($draw_num[$index] > 30) {
						$d4_array[3]++;
					} elseif ($draw_num[$index] > 20) {
						$d4_array[2]++;
					} elseif ($draw_num[$index] > 10) {
						$d4_array[1]++;
					} else {
						$d4_array[0]++;
					}
					
					if(!is_int($draw_num[$index]/2)) 
					{ 
						$odd++; 
					} 
					else 
					{ 
						$even++; 
					}
				} 

				// test modulus
				for ($x = 1; $x <= 6; $x++) 
				{ 
					if ($draw_num[$x] > 0 && $draw_num[$x] < 10) {
						$y = $draw_num[$x];
						$mod[$y]++;
						$num_count[0]++;
					}
					elseif ($draw_num[$x] > 9 && $draw_num[$x] < 20) {
						$y = $draw_num[$x] - 10;
						$mod[$y]++;
						$num_count[1]++;
					}
					elseif ($draw_num[$x] > 19 && $draw_num[$x] < 30) {
						$y = $draw_num[$x] - 20;
						$mod[$y]++;
						$num_count[2]++;
					}
					elseif ($draw_num[$x] > 29 && $draw_num[$x] < 40) {
						$y = $draw_num[$x] - 30;
						$mod[$y]++;
						$num_count[3]++;
					}
					else {
						$y = $draw_num[$x] - 40;
						$mod[$y]++;
						$num_count[4]++;
					}
				}

				$mod_x = 0;

				for ($x = 0; $x <= 9; $x++)
				{
					if ($mod[$x] > 1)
					{
						$mod_total += $mod[$x] - 1;
					}

					if ($mod[$x] > 2)
					{
						$mod_x++;;
					}
				}

				#print "<h3>mod_total = $mod_total</h3>";
			
				if (1)#$sum >= $sum_low && 
					#$sum <= $sum_high && 
					#$seq2 <= 1 && 
					#$seq3 <= 0 &&
					#$mod_total <= 100) # <---------------------------------
					#$num_count[0] <= 2 &&
					#$num_count[1] <= 2 &&
					#$num_count[2] <= 2 &&
					#$num_count[3] <= 2 &&
					#$num_count[4] <= 2 &&
					#$num_count[5] <= 1 &&
				
					#($even >= 2 && $even <= 4) &&
					#($odd  >= 2 && $odd <= 4)  &&
					#($d501 >= 2 && $d501 <= 4) &&
					#($d502 >= 2 && $d502 <= 4))
				{				
					/*
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

					$pair_sum = pair_sum_count_5 ($draw_num);

					$dup_count = array_fill (0, 10, 0);

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
					*/
					
					#if ($total_combin[2] >= 0 # == 15
					if (1 # == 15
						#$total_combin[3] >= 7 &&
						#$total_combin[4] <= 1 &&
						#$total_combin[5] == 0 &&
						#$total_combin[6] == 0 
						)
					{
					
						/*
						include_once 'C:\wamp\www\lotto\PEAR\Math\Stats.php';

						$s = new Math_Stats();
						$s->setData($draw_array);
						$stats = $s->calcBasic();
						*/
						$avg = $sum/5;
						$median = ($draw_num[3]+$draw_num[4])/2;
						$quart1 = ($draw_num[1]+$draw_num[2])/2;
						$quart2 = ($draw_num[1]+$draw_num[2]+$draw_num[3])/2;
						$quart3 = ($draw_num[1]+$draw_num[2]+$draw_num[3]+$draw_num[4])/2;
						#$stdev = $s->stDev();
						#$variance = $s->variance();
						#$avedev = $s->harmonicMean();
						$avedev = 0;
						#$kurtosis = $s->kurtosis();
						#$skew = $s->skewness();

						$draw_array = array ($draw_num[1],$draw_num[2],$draw_num[3],$draw_num[4],$draw_num[5],$draw_num[6]);

						#$average = $row[sum]/5;
						#$median = $row[b3];
						#$harmean = $s->harmonicMean();
						#$quart1 = ($row[b1]+$row[b2])/2;
						#$quart2 = ($row[b1]+$row[b2]+$row[b3])/2;
						#$quart3 = ($row[b1]+$row[b2]+$row[b3]+$row[b4])/2;
						#$stdev = $s->stDev();
						#$variance = $s->variance();
						#$avedev = $s->__calcAbsoluteDeviation();
						#$kurtosis = $s->kurtosis();
						#$skew = $s->skewness();
						#$geomean = $s->geometricMean();
						#$devsq = calc_devsq ($draw_array,$average);

						/*
						###########################################################################
						#		EO50
						###########################################################################

						$query2 =  "SELECT * FROM ga_f5_eo50 ";
						$query2 .= "WHERE even = $even ";
						$query2 .= "AND odd = $odd ";
						$query2 .= "AND d501 = $d501 ";
						$query2 .= "AND d502 = $d502 ";

						$mysqli_result2 = mysqli_query($mysqli_link, $query2) or die (mysqli_error($mysqli_link));

						$row2 = mysqli_fetch_array($mysqli_result2);

						$num_rows_2 = mysqli_num_rows($mysqli_result2); 

						$wheel_id = $row2[id];

						###########################################################################
						#		wheel_id
						###########################################################################

						$query3 =  "SELECT * FROM ga_f5_";
						$query3 .= "wheels_generated ";
						$query3 .= "WHERE eo50 = $wheel_id ";
						$query3 .= "AND sum = $sum ";

						$mysqli_result3 = mysqli_query($mysqli_link, $query3) or die (mysqli_error($mysqli_link));

						$row3 = mysqli_fetch_array($mysqli_result3);

						$wheel_generated_rows = mysqli_num_rows($mysqli_result3); 

						if ($wheel_generated_rows)
						{
							$wheel_generated_wa
							 = $row3[percent_wa];
						} else {
							$wheel_generated_wa = 0.0;
						}
						*/
						$wheel_generated_rows = 0;
						$wheel_generated_wa = 0.0;
						$pair_sum = 0;
						$draw_count = 0;

						$query7 = "SELECT * FROM $combo_table ";
						$query7 .= "WHERE b1 = '$draw_num[1]' ";
						$query7 .= "AND   b2 = '$draw_num[2]' ";
						$query7 .= "AND   b3 = '$draw_num[3]' ";
						$query7 .= "AND   b4 = '$draw_num[4]' ";
						$query7 .= "AND   b5 = '$draw_num[5]' ";
						$query7 .= "AND   b5 = '$draw_num[6]' ";
						$query7 .= "ORDER BY id ASC ";

						#print "$query7<br>";

						$mysqli_result7 = mysqli_query($mysqli_link, $query7) or die (mysqli_error($mysqli_link));

						$draw_count = mysqli_num_rows($mysqli_result7);

						#print "draw_count = $draw_count<br>";

						if ($draw_count > 1)
						{
							$row7 = mysqli_fetch_array($mysqli_result7);

							while($row7 = mysqli_fetch_array($mysqli_result7))
							{
								$query_delete = "DELETE FROM $combo_table ";
								$query_delete .= "WHERE id = $row7[id] ";

								#echo "$query_delete<br>";
							
								$mysqli_result_combin = mysqli_query($mysqli_link, $query_delete) or die (mysqli_error($mysqli_link));
							}
						} else {
							$draw_last = '1962-08-17';
						
							$hml = intval($sum/10)*10;

							$query = "INSERT INTO $combo_table ";
							$query .= "VALUES ('0', ";
							$query .= "'$draw_num[1]', ";
							$query .= "'$draw_num[2]', ";
							$query .= "'$draw_num[3]', ";
							$query .= "'$draw_num[4]', ";
							$query .= "'$draw_num[5]', ";
							$query .= "'$draw_num[6]', ";
							$query .= "'$sum', ";
							$query .= "'$hml', ";
							$query .= "'$even', ";
							$query .= "'$odd', ";
							$query .= "'$num_count[0]', ";
							$query .= "'$num_count[1]', ";
							$query .= "'$num_count[2]', ";
							$query .= "'$num_count[3]', ";
							$query .= "'$num_count[4]', ";
							$query .= "'$rank_count[0]', ";
							$query .= "'$rank_count[1]', ";
							$query .= "'$rank_count[2]', ";
							$query .= "'$rank_count[3]', ";
							$query .= "'$rank_count[4]', ";
							$query .= "'$rank_count[5]', ";
							$query .= "'$rank_count[6]', ";
							$query .= "'$mod_total', ";
							$query .= "'$mod_x', ";
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
							$query .= "'$dup_count[6]', ";
							$query .= "'$dup_count[7]', ";
							$query .= "'$dup_count[8]', ";
							$query .= "'$dup_count[9]', ";
							$query .= "'$dup_count[10]', ";

							for ($r = 1; $r <= 14; $r++)
							{
								$query .= "'0.0', ";
							}

							$query .= "'0', ";
							$query .= "'0.0', ";
							$query .= "'1962-08-17', ";
							
							$query .= "'0.0', ";

							$query .= "'1962-08-17') ";

							#print "$query<br>";
							
							$mysqli_result2 = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

							#die("insert die");

							$count++;
						}
					}
				}
			} 
		}

		/*
		elseif ($num_row > 1) {
			echo "dup found $draw_num[1] - $draw_num[2] - $draw_num[3] - $draw_num[4] - $draw_num[5]";

			$row6 = mysqli_fetch_array($mysqli_result6);

			$query9 = "DELETE From combo_6_42 ";
			$query9 .= "WHERE id = $row6[id] ";

			$mysqli_result9 = mysqli_query($mysqli_link, $query9) or die (mysqli_error($mysqli_link));

			echo "id $row6[id] deleted - $draw_num[1] - $draw_num[2] - $draw_num[3] - $draw_num[4] - $draw_num[5]";
			
		} 
		*/

		if ($draw_num[6] < $max_num) {
				$draw_num[6] = $draw_num[6] + 1;
		} elseif ($draw_num[5] < $max_num-1) {
				$draw_num[5] = $draw_num[5] + 1;
				$draw_num[6] = $draw_num[5] + 1;
		} elseif ($draw_num[4] < $max_num-2) {
				$draw_num[4] = $draw_num[4] + 1;
				$draw_num[5] = $draw_num[4] + 1;
				$draw_num[6] = $draw_num[5] + 1;
		} elseif ($draw_num[3] < $max_num-3) {
				$draw_num[3] = $draw_num[3] + 1;
				$draw_num[4] = $draw_num[3] + 1;
				$draw_num[5] = $draw_num[4] + 1;
				$draw_num[6] = $draw_num[5] + 1;
		} elseif ($draw_num[2] < $max_num-4) {
				$draw_num[2] = $draw_num[2] + 1;	
				$draw_num[3] = $draw_num[2] + 1;
				$draw_num[4] = $draw_num[3] + 1;
				$draw_num[5] = $draw_num[4] + 1;
				$draw_num[6] = $draw_num[5] + 1;
		} else {
				$draw_num[1] = $draw_num[1] + 1;
				$draw_num[2] = $draw_num[1] + 1;
				$draw_num[3] = $draw_num[2] + 1;
				$draw_num[4] = $draw_num[3] + 1;
				$draw_num[5] = $draw_num[4] + 1;
				$draw_num[6] = $draw_num[6] + 1;
		}
		
		if ($print_flag == 50000)
		{
			echo "$draw_num[1],$draw_num[2],$draw_num[3],$draw_num[4],$draw_num[5]<br>";
			$print_flag = 0;
		}

		$print_flag++;

		set_time_limit(0);
	} 

	function calc_devsq ($draw,$average)
	{
		$average = array_sum($draw)/5;
		$devsq = 0.0;
		for ($x = 0; $x < 5; $x++)
		{
			$temp = $draw[$x]-$average;
			$devsq += $temp*$temp;
		}

		#echo "devsq = $devsq<p>";

		return $devsq;
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
				   $d1 = $draw_num[3];
				   $d2 = $draw_num[5];
				   break;
			} 

			$query8 = "SELECT num1, num2, count FROM ga_f5_temp_2_5000 ";
			$query8 .= "WHERE num1 = $d1 ";
			$query8 .= "  AND num2 = $d2 ";
			#$query8 .= "  AND last_date < '$date' ";

			$mysqli_result8 = mysqli_query($mysqli_link, $query8) or die (mysqli_error($mysqli_link));

			$row8 = mysqli_fetch_array($mysqli_result8);

			$num_rows = mysqli_num_rows($mysqli_result8);
			
			$pair_sum+= $num_rows;
		} 

		return $pair_sum;
	}

	print("</table>");

	print("<h2>Count = $count</h2>");

	print("</body>");
	print("</html>");

?>