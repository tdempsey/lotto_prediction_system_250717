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

	require ("includes/build_rank_table.php");
	require ("includes/last_draws.php");

	require ("includes_ga_f5/wheel16_split_draws_4.php");

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
	
	$debug = 0;

	$curr_date_dash = date("Y-m-d");

	require ("includes_ga_f5/wheel16_combin.incl");

	//start HTML page
	print("<HTML>\n");
	print("<HEAD>\n");
	print("<TITLE>Lotto Update drange2 - $game_name - Rank Limit</TITLE>\n");
	print("</HEAD>\n");
	
	print("<BODY>\n");

	### build whell16_array exclude last two draws - 001 ##########################################

	#$wheel16_array = array(0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16); ############################

	$wheel16_array_01 = array(0,0,4,9,14,15,17,21,24,25,29,32,34,35,37,40,41); #######################

	$wheel16_array_02 = array(0,0,6,7,9,13,16,19,21,24,29,32,35,36,38,40,42); #######################

	$wheel16_array_03 = array(0,0,9,11,12,17,19,21,24,27,29,33,35,37,39,41,42); #######################
	
	$wheel16_array_04 = array(0,0,8,9,10,13,16,17,19,21,25,27,36,35,36,37,42); #######################

	$query4 = "INSERT INTO `ga_f5_wheel16_array` (`id`, `date`, `b1`, `b2`, `b3`, `b4`, `b5`, `b6`, `b7`, `b8`, `b9`, `b10`, `b11`, `b12`, `b13`, `b14`, `b15`, `b16`) VALUES ('0', '$curr_date_dash', ";

	for ($k = 1; $k <= 15; $k++)
	{
		$query4 .= "$wheel16_array_01[$k], ";
	}

	$query4 .= "$wheel16_array_01[16]) ";

	print("$query4<br>");

	$mysqli_result4 = mysqli_query($mysqli_link, $query4) or die (mysqli_error($mysqli_link));
		
	###############################################################################################
	
	$query3 = "DROP TABLE IF EXISTS ga_f5_wheel16_draws ";
	
	print "$query3<p>";

	#$mysqli_result3 = mysqli_query($mysqli_link, $query3) or die (mysqli_error($mysqli_link));

	#for ($d = 1; $d <= 50; $d++)
	#{
	#	$query4 .= "`dup.$d` tinyint(1) unsigned NOT NULL, ";
	#}

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
				  `s1510` tinyint UNSIGNED NOT NULL,
				  `s61510` tinyint UNSIGNED NOT NULL,
				  `last_updated` date NOT NULL
				) ENGINE=MyISAM DEFAULT CHARSET=latin1;";

	print "$query4<p>";

	#$mysqli_result4 = mysqli_query($mysqli_link, $query4) or die (mysqli_error($mysqli_link));

	$query4b = "ALTER TABLE `ga_f5_wheel16_draws`
				  ADD PRIMARY KEY (`id`),
				  ADD UNIQUE KEY `id` (`id`); ";
	
	print "$query4b<p>";

	#$mysqli_result4b = mysqli_query($mysqli_link, $query4b) or die (mysqli_error($mysqli_link));

	$query4c = "ALTER TABLE `ga_f5_wheel16_draws`
					MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT; ";
	
	print "$query4c<p>";

	#$mysqli_result4c = mysqli_query($mysqli_link, $query4c) or die (mysqli_error($mysqli_link));
	
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

	for ($m = 9; $m <= 11; $m++)
	{
		### get count draw table ######################################################################
		$query5 = "SELECT DISTINCT d2,d3,d4,d5 FROM wheels16 "; ### 210202 removed d5
		$query5 .= "ORDER BY d2 ASC, d3 ASC, d4 ASC, d5 ASC ";  ### 210202 removed d5

		echo "$query5<br>";

		$mysqli_result5 = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));

		while ($row5 = mysqli_fetch_array($mysqli_result5))
		{
			$draw = array_fill (0,5,0);

			$even = 0;
			$odd = 0;

			#$temp1 = $row5[d1];
			#$draw[0] = $wheel16_array_01[$temp1];
			$temp1 = $m; ### 210202 m=[1..7]
			$draw[0] = $m;
			$temp2 = $row5[0];
			$draw[1] = $wheel16_array_01[$temp2];
			$temp3 = $row5[1];
			$draw[2] = $wheel16_array_01[$temp3];
			$temp4 = $row5[2];
			$draw[3] = $wheel16_array_01[$temp4];
			$temp5 = $row5[3];
			$draw[4] = $wheel16_array_01[$temp5];

			if ($draw[1] == $m || $draw[2] == $m || $draw[3] == $m || $draw[4] == $m) {
				break;
			}

			#print_r ($draw);
			#echo "<br>";

			$sum = array_sum($draw);

			foreach ($draw as $val) 
			{ 
				if(!is_int($val/2)) 
				{ 
					$odd++; 
					#echo "odd++<br>";
				} else { 
					$even++; 
					#echo "even++<br>";
				}
			}

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
				
				/*
				$query10 = "SELECT * FROM combo_5_42 a ";
				$query10 .= "JOIN combo_5_42_drange4 b ";
				$query10 .= "ON  a.id = b.id ";
				$query10 .= "WHERE a.b1 = '$draw[0]' "; 
				$query10 .= "AND   a.b2 = '$draw[1]' ";
				$query10 .= "AND   a.b3 = '$draw[2]' ";
				$query10 .= "AND   a.b4 = '$draw[3]' ";
				$query10 .= "AND   a.b5 = '$draw[4]' ";
				
				echo "$query10<p>";

				$mysqli_result10 = mysqli_query($mysqli_link, $query10) or die (mysqli_error($mysqli_link));

				$row10 = mysqli_fetch_array($mysqli_result10);

				$d4[1] = $row10[d4_1];
				$d4[2] = $row10[d4_2];
				$d4[3] = $row10[d4_3];
				$d4[4] = $row10[d4_4];
				*/

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

				$seq2 = 0;
				$seq3 = 0;
				$mod_total = 0;
				$dup_pass = 1;
				$mod_x = 0;

				sort($draw);
				
				for ($x = 0 ; $x <= count($draw)-2; $x++)
				{
					$num1 = $draw[$x];
					$num2 = $draw[$x+1];
					if ($num1 == ($num2-1))
					{
						$seq2++;
					}
				}

				for ($count = 0 ; $count <= count($draw)-3; $count++)
				{
					$num1 = $draw[$count];
					$num2 = $draw[$count+1];
					$num3 = $draw[$count+2];
					if ($num1 == ($num2-1) && $num2 == ($num3-1))
					{
						$seq3++;
					}
				}

				for ($x = 0; $x <= 4; $x++) 
				{ 
					if ($draw[$x] > 0 && $draw[$x] < 10) {
						$y = $draw[$x];
						$mod[$y]++;
					} elseif ($draw[$x] > 9 && $draw[$x] < 20) {
						$y = $draw[$x] - 10;
						$mod[$y]++;
					} elseif ($draw[$x] > 19 && $draw[$x] < 30) {
						$y = $draw[$x] - 20;
						$mod[$y]++;
					} elseif ($draw[$x] > 29 && $draw[$x] < 40) {
						$y = $draw[$x] - 30;
						$mod[$y]++;
					} else {
						$y = $draw[$x] - 40;
						$mod[$y]++;
					}
				}

				$mod_total = 0;

				for ($x = 0; $x <= 9; $x++)
				{
					if ($mod[$x] > 1)
					{
						$mod_total += $mod[$x] - 1;
					}

					if ($mod[$x] > 2)
					{
						#$mod_x++;
					}
				}

				$mod_total = 0;
				$dup_pass = 1;
				$mod_x = 0;

				#print_r ($d4);
				#echo "<br>";

				###########################################################################################

				echo "$draw[0] - $draw[1] - $draw[2] - $draw[3] - $draw[4] <br>";

				for ($d = 1; $d <= 50; $d++)
				{
					$query4 .= "`dup.$d`, ";
				}

				for ($d = 1; $d <= 50; $d++)
				{
					$query4 .= "`$last_dup[$d]`, ";
				}

				$query4 = "INSERT INTO `ga_f5_wheel16_draws` 
				(`id`, `d1`, `d2`, `d3`, `d4`, `d5`, `sum`, `even`, `odd`,`seq2`, `seq3`, `mod`, `mod_x`, `rank0`, `rank1`, `rank2`, `rank3`, `rank4`, `rank5`, `rank6`, `rank7`, `dup1`, `dup2`, `dup3`, `dup4`, `comb2`, `comb3`, `comb4`, `comb5`, `d4_1`, `d4_2`, `d4_3`, `d4_4`, `pair_sum`, `draw_average`, `median`, `harmean`, `geomean`, `quart1`, `quart2`, `quart3`, `stdev`, `variance`, `avedev`, `kurt`, `skew`, `devsq`, `nums_total_2`, `combo_total_2`, `nums_total_3`, `combo_total_3`, `nums_total_4`, `combo_total_4`, `s1510`, `s61510`, `last_updated`) 
				VALUES 
				('0', '$draw[0]', '$draw[1]', '$draw[2]', '$draw[3]', '$draw[4]', 
				'$sum', '$even', '$odd', '$seq2', '$seq3', '0', '0', 
				'$rank_count[0]', '$rank_count[1]', '$rank_count[2]', '$rank_count[3]', 
				'$rank_count[4]', '$rank_count[5]', '$rank_count[6]', '$rank_count[7]', 
				'$last_dup[1]', '$last_dup[2]', '$last_dup[3]', '$last_dup[4]', 
				'$total_combin[2]', '$total_combin[3]', '$total_combin[4]', '$total_combin[5]', 
				'$d4[1]', '$d4[2]', '$d4[3]', '$d4[4]', 
				'0', '0', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '$curr_date_dash')";

				print("$query4<br>");

				$mysqli_result4 = mysqli_query($mysqli_link, $query4) or die (mysqli_error($mysqli_link));

				#die();

				#require ("includes_ga_f5/print_wheels16_draws.incl");
		}
	}

	print("<h2>Completed!</h2>");

	wheel16_split_draws_4 ($dateDiff);

	print("</BODY>\n");
?>




