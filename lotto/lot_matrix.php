<?php

	// Game ----------------------------- Game
	$game = 1; // Georgia Fantasy 5
	#$game = 2; // Mega Millions
	//$game = 3; // Georgia Lotto South
	#$game = 4; // Florida Fantasy 5
	//$game = 5; // Florida Mega Money
	#$game = 6; // Florida Lotto
	#$game = 7; // Powerball
	// Game ----------------------------- Game

	#do {
    #echo $i;
	#} while ($i > 0);

	#do {
	#	if ($i < 5) 
	#	{
	#		echo "i is not big enough";
	#		break;
	#	}
	#} while (0);
	
	require ("includes/games_switch.incl");

	//require ("includes/mysqli.php");
	require ("includes/db.class");
	require ("includes/even_odd.php");
	require ("includes/last_draws.php");
	require ("includes/calculate_50_50.php");
	require ("includes/build_rank_table.php");
	require ("includes/calculate_draw.php"); 
	require ("includes/calculate_rank.php");
	require ("includes/first_draw_unix.php");
	require ("includes/last_draw_unix.php"); 
	require ("includes/next_draw.php");
	require ("$game_includes/combin.incl");
	
	$debug = 0;

	function lot_matrix ($col1,$dup1,$dup2,$dup3,$limit,$delete_temp)
	{
		global $debug, $game, $game_name, $balls, $balls_drawn, $mega_balls, $draw_table_name, $draw_prefix, $game_includes, $hml, $seq2_limit, $seq3_limit,$combin2,$combin3,$combin4,$combin5;
	
		require ("includes/mysqli.php");
		require ("$game_includes/config.incl");

		$curr_date = Date('Y-m-d');
		$sum_fail = 0;
		$sum_total = 0;
		$sum_array = array();

		$temp = array_fill(0,6,0);
		$matrix = array_fill(0,6,$temp);

		#5 012345
		#4 012345
		#3 012345
		#2 012345
		#1 012345
		#0 012345

		$row4 = array (4,3,1,2,5);
		$row3 = array (3,5,2,4,1);
		$row2 = array (2,1,3,5,4);
		$row1 = array (5,2,4,1,3);
		$row0 = array (1,4,5,3,2);

		/*
		for ($x = 0; $x < $balls_drawn; $x++)
		{
			$row0[$x] = $x + 1;
			$row1[$x] = $x + 1;
			$row2[$x] = $x + 1;
			$row3[$x] = $x + 1;
			$row4[$x] = $x + 1;
			$row5[$x] = $x + 1;
		}
		*/
		
		print("<h2>Lotto Matrix - $game_name - Limit $limit - Col1 = $col1</h2>\n");

		# --01-- delete temp tables -------------------------------------------------------------------------
		if ($delete_temp)
		{
			$sql = "TRUNCATE TABLE `ga_f5_2_39_save`";
			print "$sql<br>";
			$mysqli_result2 = mysqli_query($sql, $mysqli_link) or die (mysqli_error($mysqli_link));

			$sql = "TRUNCATE TABLE `ga_f5_3_39_save`";
			print "$sql<br>";
			$mysqli_result3 = mysqli_query($sql, $mysqli_link) or die (mysqli_error($mysqli_link));

			$sql = "TRUNCATE TABLE `ga_f5_4_39_save`";
			print "$sql<br>";
			$mysqli_result4 = mysqli_query($sql, $mysqli_link) or die (mysqli_error($mysqli_link));

			$sql = "CREATE TABLE IF NOT EXISTS `ga_f5_draws_matrix_$curr_date` (
					  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
					  `date` date NOT NULL default '1962-08-17',
					  `b1` tinyint(2) unsigned NOT NULL DEFAULT '0',
					  `b2` tinyint(2) unsigned NOT NULL DEFAULT '0',
					  `b3` tinyint(2) unsigned NOT NULL DEFAULT '0',
					  `b4` tinyint(2) unsigned NOT NULL DEFAULT '0',
					  `b5` tinyint(2) unsigned NOT NULL DEFAULT '0',
					  `sum` smallint(3) unsigned NOT NULL DEFAULT '0',
					  `even` tinyint(1) unsigned NOT NULL DEFAULT '0',
					  `odd` tinyint(1) unsigned NOT NULL DEFAULT '0',
					  `d501` tinyint(1) unsigned NOT NULL DEFAULT '0',
					  `d502` tinyint(1) unsigned NOT NULL DEFAULT '0',
					  `draw0` tinyint(1) unsigned NOT NULL DEFAULT '0',
					  `draw1` tinyint(1) unsigned NOT NULL DEFAULT '0',
					  `draw2` tinyint(1) unsigned NOT NULL DEFAULT '0',
					  `draw3` tinyint(1) unsigned NOT NULL DEFAULT '0',
					  `rank0` tinyint(1) unsigned NOT NULL DEFAULT '0',
					  `rank1` tinyint(1) unsigned NOT NULL DEFAULT '0',
					  `rank2` tinyint(1) unsigned NOT NULL DEFAULT '0',
					  `rank3` tinyint(1) unsigned NOT NULL DEFAULT '0',
					  `rank4` tinyint(1) unsigned NOT NULL DEFAULT '0',
					  `rank5` tinyint(1) unsigned NOT NULL DEFAULT '0',
					  `rank6` tinyint(1) unsigned NOT NULL DEFAULT '0',
					  `pair_sum` mediumint(8) unsigned NOT NULL DEFAULT '0',
					  `average` float(4,2) unsigned NOT NULL DEFAULT '0.00',
					  `median` float(4,2) unsigned NOT NULL DEFAULT '0.00',
					  `harmean` float(4,2) unsigned NOT NULL DEFAULT '0.00',
					  `geomean` float(4,2) unsigned NOT NULL DEFAULT '0.00',
					  `quart1` float(4,2) unsigned NOT NULL DEFAULT '0.00',
					  `quart2` float(4,2) unsigned NOT NULL DEFAULT '0.00',
					  `quart3` float(4,2) unsigned NOT NULL DEFAULT '0.00',
					  `stdev` float(4,2) unsigned NOT NULL DEFAULT '0.00',
					  `variance` float(6,2) unsigned NOT NULL DEFAULT '0.00',
					  `avedev` float(4,2) unsigned NOT NULL DEFAULT '0.00',
					  `kurt` float(4,2) NOT NULL DEFAULT '0.00',
					  `skew` float(4,2) NOT NULL DEFAULT '0.00',
					  `devsq` float(6,2) unsigned NOT NULL DEFAULT '0.00',
					  PRIMARY KEY (`id`),
					  KEY `date` (`id`)
					) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
					";
				print "$sql<br>";
			
			$mysqli_result5 = mysqli_query($sql, $mysqli_link) or die (mysqli_error($mysqli_link));
		}
		
		for ($m = 1; $m <= $limit; $m++)
		{
			echo "<hr>";
			echo "<b>Seed 2 - col1 - check mod/seq</b><p>";

		# --02-- seed col1 - 0,0/ ---------------------------------------------------------------------------
		#      - all same num or pull 5/6 from table [1..10] - exclude previous on multi

			if ($col1 == 0)
			{
				$query = "SELECT * FROM $draw_prefix";
				$query .= "column_1 ";
				if ($hml == 0) {
					$query .= " ";
				} elseif ($hml == 1) {
					$query .= "_1 ";
				} elseif ($hml == 2) {
					$query .= "_2 ";
				} elseif ($hml == 3) {
					$query .= "_3 ";
				} else {
					die ("hml incorrect for 0,4 - hml = $hml");
				}
				$query .= "WHERE num <= 11 "; # 10
				$query .= "ORDER BY percent_y1 DESC ";
				$query .= "LIMIT 8 "; # 6

				echo "$query<p>";
			
				$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

				$col1_table = array();
				$y = 0;

				while($row = mysqli_fetch_array($mysqli_result))
				{
					$col1_table[$y++] = $row[num];
				}

				echo "col1_table = ";
				print_r ($col1_table);
				echo "<br>";

				for ($a = 0; $a <= 4; $a++)
				{
					for ($b = 0; $b <= 4; $b++)
					{
						if (${row.$a}[$b] == 1)
						{
							do {
								$temp_count = count($col1_table)-1;
								$rand_id = mt_rand(0,($temp_count));
								$passed = DrawSeed1($seed_col1=$col1_table[$rand_id],$dup1);
								if ($passed)
								{
									$matrix[$a][$b] = $col1_table[$rand_id];
								} else {
									echo "--02-- $col1_table[$rand_id] failed - dup1 = $dup1 <br>";
								}
								unset ($col1_table[$rand_id]);
								$col1_table = array_values($col1_table);
							} while (!$passed);
						}
					}
				}
			} else {
				for ($a = 0; $a <= 4; $a++)
				{
					for ($b = 0; $b <= 4; $b++)
					{
						if (${row.$a}[$b] == 1)
						{
							$matrix[$a][$b] = $col1; #add multi-col1 here
							echo "matrix[$a][$b] = {$matrix[$a][$b]}<br>";
							break;
						}
					}
				}
			}
			print_matrix ($matrix,$balls_drawn);

			# --03-- seed comb1 -----------------------------------------------------------------------------
			# add looke-ahead function

			echo "<hr>";
			echo "<b>Seed 3 - comb1</b><p>";

			$col2_save = array();

			if ($col1 < 39)
			{
				# ---- 0,4
				$mrow = 0;
				$mcolumn = 4;

				$query = "SELECT * FROM $draw_prefix";
				$query .= "temp_combo2_p1";
				if ($hml == 0) {
					$query .= " ";
				} elseif ($hml == 1) {
					$query .= "_1 ";
				} elseif ($hml == 2) {
					$query .= "_2 ";
				} elseif ($hml == 3) {
					$query .= "_3 ";
				} else {
					die ("hml incorrect for 0,4 - hml = $hml");
				}
				$query .= "WHERE num1 = '{$matrix[0][0]}' "; 
				$query .= "AND num2 > {$matrix[3][4]} ";

				print("<p>$query</p>");
			
				$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

				$seed3_table = array_fill(0, $balls+1, 0.0);

				while($row = mysqli_fetch_array($mysqli_result))
				{
					$y = $row[num2];
					$seed3_table[$y] += $row[percent_y10];
				}

				$query = "SELECT * FROM $draw_prefix";
				$query .= "temp_combo2_p1";
				if ($hml == 0) {
					$query .= " ";
				} elseif ($hml == 1) {
					$query .= "_1 ";
				} elseif ($hml == 2) {
					$query .= "_2 ";
				} elseif ($hml == 3) {
					$query .= "_3 ";
				} else {
					die ("hml incorrect for 0,4 - hml = $hml");
				}
				$query .= "WHERE num1 = '{$matrix[3][4]}' "; 
				$query .= "AND num2 > {$matrix[0][0]} ";

				print("<p>$query</p>");
			
				$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

				while($row = mysqli_fetch_array($mysqli_result))
				{
					$y = $row[num2];
					$seed3_table[$y] += $row[percent_y10];
				}

				echo "seed3_table = ";
				print_r ($seed3_table);
				echo "<p>";

				# pick top 

				$max_array = array_fill (0, 2, 0);

				for ($z = 0; $z < 2; $z++)
				{
					$maxs = array_keys($seed3_table, max($seed3_table));
					$max_array[$z] = $maxs[0];
					echo "maxs = $max_array[$z]<br>";
					$seed3_table[$maxs[0]] = 0.0;
				}

				do {
					shuffle ($max_array);
					$temp_count = count($max_array)-1;
					$rand_id = mt_rand(0,($temp_count));
					$passed = DrawCombo1($matrix,$seed_comb2=$max_array[$rand_id],$dup1,$dup2,$dup3,$mrow,$mcolumn);
					if ($passed)
					{
						$matrix[0][4] = $max_array[$rand_id];
						array_push ($col2_save, $max_array[$rand_id]);
					} else {
						echo "--0,4-- $max_array[$rand_id] failed - dup1 = $dup1 or rank? <br>";
					}
					unset ($max_array[$rand_id]);
					$max_array = array_values($max_array);
					if (count($max_array) == 0 && !$passed)
					{
						for ($z = 0; $z < 2; $z++)
						{
							$maxs = array_keys($seed3_table, max($seed3_table));
							$max_array[$z] = $maxs[0];
							echo "maxs = $max_array[$z]<br>";
							$seed3_table[$maxs[0]] = 0.0;
						}
					}
				} while (!$passed);

				# ---- 0,4 end

				# ---- 1,1
				$mrow = 1;
				$mcolumn = 1;

				$query = "SELECT * FROM $draw_prefix";
				$query .= "temp_combo2_p1";
				if ($hml == 0) {
					$query .= " ";
				} elseif ($hml == 1) {
					$query .= "_1 ";
				} elseif ($hml == 2) {
					$query .= "_2 ";
				} elseif ($hml == 3) {
					$query .= "_3 ";
				} else {
					die ("hml incorrect for 0,4 - hml = $hml");
				}
				$query .= "WHERE num1 = '{$matrix[2][1]}' "; 
				$query .= "AND num2 > {$matrix[1][3]} ";

				print("<p>$query</p>");
			
				$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

				$seed3_table = array_fill(0, $balls+1, 0.0);

				while($row = mysqli_fetch_array($mysqli_result))
				{
					$y = $row[num2];
					$seed3_table[$y] += $row[percent_y10];
				}

				$query = "SELECT * FROM $draw_prefix";
				$query .= "temp_combo2_p1";
				if ($hml == 0) {
					$query .= " ";
				} elseif ($hml == 1) {
					$query .= "_1 ";
				} elseif ($hml == 2) {
					$query .= "_2 ";
				} elseif ($hml == 3) {
					$query .= "_3 ";
				} 
				$query .= "WHERE num1 = '{$matrix[1][3]}' "; 
				$query .= "AND num2 > {$matrix[2][1]} ";

				print("<p>$query</p>");
			
				$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

				while($row = mysqli_fetch_array($mysqli_result))
				{
					$y = $row[num2];
					$seed3_table[$y] += $row[percent_y10];
				}

				echo "seed3_table = ";
				print_r ($seed3_table);
				echo "<p>";

				# pick top 

				$max_array = array_fill (0, 2, 0);

				for ($z = 0; $z < 2; $z++)
				{
					$maxs = array_keys($seed3_table, max($seed3_table));
					$max_array[$z] = $maxs[0];
					echo "maxs = $max_array[$z]<br>";
					$seed3_table[$maxs[0]] = 0.0;
				}

				do {
					shuffle ($max_array);
					$temp_count = count($max_array)-1;
					$rand_id = mt_rand(0,($temp_count));
					$passed = DrawCombo1($matrix,$seed_comb2=$max_array[$rand_id],$dup1,$dup2,$dup3,$mrow,$mcolumn);
					if (in_array($max_array[$rand_id], $col2_save) && $col1 > 0)
					{
						$passed = 0;
						echo "col2_save dup<br>";
					}
					if ($passed)
					{
						$matrix[1][1] = $max_array[$rand_id];
						array_push ($col2_save, $max_array[$rand_id]);
					} else {
						echo "--1,1-- $max_array[$rand_id] failed - dup1 = $dup1 <br>";
					}
					unset ($max_array[$rand_id]);
					$max_array = array_values($max_array);
					if (count($max_array) == 0 && !$passed)
					{
						for ($z = 0; $z < 2; $z++)
						{
							$maxs = array_keys($seed3_table, max($seed3_table));
							$max_array[$z] = $maxs[0];
							echo "maxs = $max_array[$z]<br>";
							$seed3_table[$maxs[0]] = 0.0;
						}
					}
				} while (!$passed);

				# ---- 1,1 end

				# ---- 2,0
				$mrow = 2;
				$mcolumn = 0;

				$query = "SELECT * FROM $draw_prefix";
				$query .= "temp_combo2_p1";
				if ($hml == 0) {
					$query .= " ";
				} elseif ($hml == 1) {
					$query .= "_1 ";
				} elseif ($hml == 2) {
					$query .= "_2 ";
				} elseif ($hml == 3) {
					$query .= "_3 ";
				} 
				$query .= "WHERE num1 = '{$matrix[0][0]}' "; 
				$query .= "AND num2 > {$matrix[2][1]} ";

				print("<p>$query</p>");
			
				$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

				$seed3_table = array_fill(0, $balls+1, 0.0);

				while($row = mysqli_fetch_array($mysqli_result))
				{
					$y = $row[num2];
					$seed3_table[$y] += $row[percent_y10];
				}

				$query = "SELECT * FROM $draw_prefix";
				$query .= "temp_combo2_p1";
				if ($hml == 0) {
					$query .= " ";
				} elseif ($hml == 1) {
					$query .= "_1 ";
				} elseif ($hml == 2) {
					$query .= "_2 ";
				} elseif ($hml == 3) {
					$query .= "_3 ";
				} 
				$query .= "WHERE num1 = '{$matrix[2][1]}' "; 
				$query .= "AND num2 > {$matrix[0][0]} ";

				print("<p>$query</p>");
			
				$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

				while($row = mysqli_fetch_array($mysqli_result))
				{
					$y = $row[num2];
					$seed3_table[$y] += $row[percent_y10];
				}

				echo "seed3_table = ";
				print_r ($seed3_table);
				echo "<p>";

				# pick top 

				$max_array = array_fill (0, 2, 0);

				for ($z = 0; $z < 2; $z++)
				{
					$maxs = array_keys($seed3_table, max($seed3_table));
					$max_array[$z] = $maxs[0];
					echo "maxs = $max_array[$z]<br>";
					$seed3_table[$maxs[0]] = 0.0;
				}

				do {
					shuffle ($max_array);
					$temp_count = count($max_array)-1;
					$rand_id = mt_rand(0,($temp_count));
					$passed = DrawCombo1($matrix,$seed_comb2=$max_array[$rand_id],$dup1,$dup2,$dup3,$mrow,$mcolumn);
					if (in_array($max_array[$rand_id], $col2_save) && $col1 > 0)
					{
						$passed = 0;
						echo "col2_save dup<br>";
					}
					if ($passed)
					{
						$matrix[2][0] = $max_array[$rand_id];
						array_push ($col2_save, $max_array[$rand_id]);
					} else {
						echo "--2,0-- $max_array[$rand_id] failed - dup1 = $dup1 <br>";
					}
					unset ($max_array[$rand_id]);
					$max_array = array_values($max_array);
					if (count($max_array) == 0 && !$passed)
					{
						for ($z = 0; $z < 2; $z++)
						{
							$maxs = array_keys($seed3_table, max($seed3_table));
							$max_array[$z] = $maxs[0];
							echo "maxs = $max_array[$z]<br>";
							$seed3_table[$maxs[0]] = 0.0;
						}
					}
				} while (!$passed);

				# ---- 2,0 end

				# ---- 3,2
				$mrow = 3;
				$mcolumn = 2;

				$query = "SELECT * FROM $draw_prefix";
				$query .= "temp_combo2_p1";
				if ($hml == 0) {
					$query .= " ";
				} elseif ($hml == 1) {
					$query .= "_1 ";
				} elseif ($hml == 2) {
					$query .= "_2 ";
				} elseif ($hml == 3) {
					$query .= "_3 ";
				} 
				$query .= "WHERE num1 = '{$matrix[3][4]}' "; 
				$query .= "AND num2 > {$matrix[4][2]} ";

				print("<p>$query</p>");
			
				$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

				$seed3_table = array_fill(0, $balls+1, 0.0);

				while($row = mysqli_fetch_array($mysqli_result))
				{
					$y = $row[num2];
					$seed3_table[$y] += $row[percent_y10];
				}

				$query = "SELECT * FROM $draw_prefix";
				$query .= "temp_combo2_p1";
				if ($hml == 0) {
					$query .= " ";
				} elseif ($hml == 1) {
					$query .= "_1 ";
				} elseif ($hml == 2) {
					$query .= "_2 ";
				} elseif ($hml == 3) {
					$query .= "_3 ";
				} 
				$query .= "WHERE num1 = '{$matrix[4][2]}' "; 
				$query .= "AND num2 > {$matrix[3][4]} ";

				print("<p>$query</p>");
			
				$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

				while($row = mysqli_fetch_array($mysqli_result))
				{
					$y = $row[num2];
					$seed3_table[$y] += $row[percent_y10];
				}

				echo "seed3_table = ";
				print_r ($seed3_table);
				echo "<p>";

				# pick top 

				$max_array = array_fill (0, 2, 0);

				for ($z = 0; $z < 2; $z++)
				{
					$maxs = array_keys($seed3_table, max($seed3_table));
					$max_array[$z] = $maxs[0];
					echo "maxs = $max_array[$z]<br>";
					$seed3_table[$maxs[0]] = 0.0;
				}

				do {
					shuffle ($max_array);
					$temp_count = count($max_array)-1;
					$rand_id = mt_rand(0,($temp_count));
					$passed = DrawCombo1($matrix,$seed_comb2=$max_array[$rand_id],$dup1,$dup2,$dup3,$mrow,$mcolumn);
					if (in_array($max_array[$rand_id], $col2_save) && $col1 > 0)
					{
						$passed = 0;
						echo "col2_save dup<br>";
					}
					if ($passed)
					{
						$matrix[3][2] = $max_array[$rand_id];
						array_push ($col2_save, $max_array[$rand_id]);
					} else {
						echo "--3,2-- $max_array[$rand_id] failed - dup1 = $dup1 <br>";
					}
					unset ($max_array[$rand_id]);
					if (count($max_array) == 0 && !$passed)
					{
						for ($z = 0; $z < 2; $z++)
						{
							$maxs = array_keys($seed3_table, max($seed3_table));
							$max_array[$z] = $maxs[0];
							echo "maxs = $max_array[$z]<br>";
							$seed3_table[$maxs[0]] = 0.0;
						}
					}
					$max_array = array_values($max_array);
				} while (!$passed);

				# ---- 3,2 end

				# ---- 4,3
				$mrow = 4;
				$mcolumn = 3;

				$query = "SELECT * FROM $draw_prefix";
				$query .= "temp_combo2_p1";
				if ($hml == 0) {
					$query .= " ";
				} elseif ($hml == 1) {
					$query .= "_1 ";
				} elseif ($hml == 2) {
					$query .= "_2 ";
				} elseif ($hml == 3) {
					$query .= "_3 ";
				} 
				$query .= "WHERE num1 = '{$matrix[4][3]}' "; 
				$query .= "AND num2 > {$matrix[1][3]} ";

				print("<p>$query</p>");
			
				$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

				$seed3_table = array_fill(0, $balls+1, 0.0);

				while($row = mysqli_fetch_array($mysqli_result))
				{
					$y = $row[num2];
					$seed3_table[$y] += $row[percent_y10];
				}

				$query = "SELECT * FROM $draw_prefix";
				$query .= "temp_combo2_p1";
				if ($hml == 0) {
					$query .= " ";
				} elseif ($hml == 1) {
					$query .= "_1 ";
				} elseif ($hml == 2) {
					$query .= "_2 ";
				} elseif ($hml == 3) {
					$query .= "_3 ";
				} 
				$query .= "WHERE num1 = '{$matrix[1][3]}' "; 
				$query .= "AND num2 > {$matrix[4][2]} ";

				print("<p>$query</p>");
			
				$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

				while($row = mysqli_fetch_array($mysqli_result))
				{
					$y = $row[num2];
					$seed3_table[$y] += $row[percent_y10];
				}

				echo "seed3_table = ";
				print_r ($seed3_table);
				echo "<p>";

				# pick top 

				$max_array = array_fill (0, 2, 0);

				for ($z = 0; $z < 2; $z++)
				{
					$maxs = array_keys($seed3_table, max($seed3_table));
					$max_array[$z] = $maxs[0];
					echo "maxs = $max_array[$z]<br>";
					$seed3_table[$maxs[0]] = 0.0;
				}

				do {
					shuffle ($max_array);
					$temp_count = count($max_array)-1;
					$rand_id = mt_rand(0,($temp_count));
					$passed = DrawCombo1($matrix,$seed_comb2=$max_array[$rand_id],$dup1,$dup2,$dup3,$mrow,$mcolumn);
					if (in_array($max_array[$rand_id], $col2_save) && $col1 > 0)
					{
						$passed = 0;
						echo "col2_save dup<br>";
					}
					if ($passed)
					{
						$matrix[4][3] = $max_array[$rand_id];
						array_push ($col2_save, $max_array[$rand_id]);
					} else {
						echo "--4,3-- $max_array[$rand_id] failed - dup1 = $dup1 <br>";
					}
					unset ($max_array[$rand_id]);
					$max_array = array_values($max_array);
					if (count($max_array) == 0 && !$passed)
					{
						for ($z = 0; $z < 2; $z++)
						{
							$maxs = array_keys($seed3_table, max($seed3_table));
							$max_array[$z] = $maxs[0];
							echo "maxs = $max_array[$z]<br>";
							$seed3_table[$maxs[0]] = 0.0;
						}
					}
				} while (!$passed);

				# ---- 4,3 end

				echo "col2_save = ";
				print_r ($col2_save);
				echo "<p>";
			} else {
				# ---- 
				$query = "SELECT * FROM $draw_prefix";
				$query .= "temp_combo2_p1";
				if ($hml == 0) {
					$query .= " ";
				} elseif ($hml == 1) {
					$query .= "_1 ";
				} elseif ($hml == 2) {
					$query .= "_2 ";
				} elseif ($hml == 3) {
					$query .= "_3 ";
				} 
				$query .= "WHERE num1 = '$col1' "; 

				print("<p>$query</p>");
			
				$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));
			}

			print_matrix ($matrix,$balls_drawn);

			# --04-- seed comb4 --------------------------------------------------------------------------------------
			# note add comb7 - error !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!

			echo "<hr>";
			echo "<b>Seed 4 - comb4/add comb7</b><p>";

			if ($col1 < 39)
			{
				# ---- 0,2
				$mrow = 0;
				$mcolumn = 2;
				$query = "SELECT * FROM $draw_prefix";
				$query .= "temp_combo2_p4";
				if ($hml == 0) {
					$query .= " ";
				} elseif ($hml == 1) {
					$query .= "_1 ";
				} elseif ($hml == 2) {
					$query .= "_2 ";
				} elseif ($hml == 3) {
					$query .= "_3 ";
				} 
				$query .= "WHERE num1 = '{$matrix[0][0]}' "; 
				$query .= "AND num2 > {$matrix[0][4]} ";

				print("<p>$query</p>");
			
				$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

				$seed4_table = array_fill(0, $balls+1, 0.0);

				while($row = mysqli_fetch_array($mysqli_result))
				{
					$y = $row[num2];
					$seed4_table[$y] += $row[percent_y10];
				}

				$query = "SELECT * FROM $draw_prefix";
				$query .= "temp_combo2_p4";
				if ($hml == 0) {
					$query .= " ";
				} elseif ($hml == 1) {
					$query .= "_1 ";
				} elseif ($hml == 2) {
					$query .= "_2 ";
				} elseif ($hml == 3) {
					$query .= "_3 ";
				} 
				$query .= "WHERE num1 = '{$matrix[4][2]}' "; 
				$query .= "AND num2 > {$matrix[3][2]} ";

				print("<p>$query</p>");
			
				$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

				while($row = mysqli_fetch_array($mysqli_result))
				{
					$y = $row[num2];
					$seed4_table[$y] += $row[percent_y10];
				}

				echo "seed4_table = ";
				print_r ($seed4_table);
				echo "<p>";

				# pick top 

				$max_array = array_fill (0, 2, 0);

				for ($z = 0; $z < 2; $z++)
				{
					$maxs = array_keys($seed4_table, max($seed4_table));
					$max_array[$z] = $maxs[0];
					echo "maxs = $max_array[$z]<br>";
					$seed4_table[$maxs[0]] = 0.0;
				}

				echo "(a)max_array = ";
				print_r ($max_array);
				echo "<p>";

				do {
					shuffle ($max_array);
					$temp_count = count($max_array)-1;
					$rand_id = mt_rand(0,($temp_count));
					$passed = DrawCombo1($matrix,$seed_comb2=$max_array[$rand_id],$dup1,$dup2,$dup3,$mrow,$mcolumn);
					if ($passed)
					{
						$matrix[0][2] = $max_array[$rand_id];
					} else {
						echo "--0,2-- $max_array[$rand_id] failed - dup1 = $dup1 <br>";
					}
					unset ($max_array[$rand_id]);
					$max_array = array_values($max_array);
					if (count($max_array) == 0 && !$passed)
					{
						for ($z = 0; $z < 2; $z++)
						{
							$maxs = array_keys($seed4_table, max($seed4_table));
							$max_array[$z] = $maxs[0];
							echo "maxs = $max_array[$z]<br>";
							$seed4_table[$maxs[0]] = 0.0;
						}
					}
					echo "(b)max_array = ";
					print_r ($max_array);
					echo "<p>";
				} while (!$passed);

				# ---- 0,2 end

				# ---- 1,0
				$mrow = 1;
				$mcolumn = 0;
				$query = "SELECT * FROM $draw_prefix";
				$query .= "temp_combo2_p4";
				if ($hml == 0) {
					$query .= " ";
				} elseif ($hml == 1) {
					$query .= "_1 ";
				} elseif ($hml == 2) {
					$query .= "_2 ";
				} elseif ($hml == 3) {
					$query .= "_3 ";
				} 
				$query .= "WHERE num1 = '{$matrix[1][3]}' "; 
				$query .= "AND num2 > {$matrix[1][1]} ";
				$query .= "AND num2 > {$matrix[2][0]} ";

				print("<p>$query</p>");
			
				$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

				$seed4_table = array_fill(0, $balls+1, 0.0);

				while($row = mysqli_fetch_array($mysqli_result))
				{
					$y = $row[num2];
					$seed4_table[$y] += $row[percent_y10];
				}

				$query = "SELECT * FROM $draw_prefix";
				$query .= "temp_combo2_p4";
				if ($hml == 0) {
					$query .= " ";
				} elseif ($hml == 1) {
					$query .= "_1 ";
				} elseif ($hml == 2) {
					$query .= "_2 ";
				} elseif ($hml == 3) {
					$query .= "_3 ";
				} 
				$query .= "WHERE num1 = '{$matrix[0][0]}' "; 
				$query .= "AND num2 > {$matrix[0][2]} ";
				$query .= "AND num2 > {$matrix[1][1]} ";

				print("<p>$query</p>");
			
				$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

				while($row = mysqli_fetch_array($mysqli_result))
				{
					$y = $row[num2];
					$seed4_table[$y] += $row[percent_y10];
				}

				echo "seed4_table = ";
				print_r ($seed4_table);
				echo "<p>";

				# pick top 

				$max_array = array_fill (0, 2, 0);

				for ($z = 0; $z < 2; $z++)
				{
					$maxs = array_keys($seed4_table, max($seed4_table));
					$max_array[$z] = $maxs[0];
					echo "maxs = $max_array[$z]<br>";
					$seed4_table[$maxs[0]] = 0.0;
				}

				echo "(a)max_array = ";
				print_r ($max_array);
				echo "<p>";

				do {
					shuffle ($max_array);
					$temp_count = count($max_array)-1;
					$rand_id = mt_rand(0,($temp_count));
					$passed = DrawCombo1($matrix,$seed_comb2=$max_array[$rand_id],$dup1,$dup2,$dup3,$mrow,$mcolumn);
					if ($passed)
					{
						$matrix[1][0] = $max_array[$rand_id];
					} else {
						echo "--1,0-- $max_array[$rand_id] failed - dup1 = $dup1 <br>";
					}
					unset ($max_array[$rand_id]);
					$max_array = array_values($max_array);
					if (count($max_array) == 0 && !$passed)
					{
						for ($z = 0; $z < 2; $z++)
						{
							$maxs = array_keys($seed4_table, max($seed4_table));
							$max_array[$z] = $maxs[0];
							echo "maxs = $max_array[$z]<br>";
							$seed4_table[$maxs[0]] = 0.0;
						}
					}
					echo "(b)max_array = ";
					print_r ($max_array);
					echo "<p>";
				} while (!$passed);

				# ---- 1,0 end

				# ---- 2,3
				$mrow = 2;
				$mcolumn = 3;
				$query = "SELECT * FROM $draw_prefix";
				$query .= "temp_combo2_p4";
				if ($hml == 0) {
					$query .= " ";
				} elseif ($hml == 1) {
					$query .= "_1 ";
				} elseif ($hml == 2) {
					$query .= "_2 ";
				} elseif ($hml == 3) {
					$query .= "_3 ";
				} 
				$query .= "WHERE num1 = '{$matrix[2][1]}' "; 
				$query .= "AND num2 > {$matrix[2][0]} ";

				print("<p>$query</p>");
			
				$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

				$seed4_table = array_fill(0, $balls+1, 0.0);

				while($row = mysqli_fetch_array($mysqli_result))
				{
					$y = $row[num2];
					$seed4_table[$y] += $row[percent_y10];
				}

				$query = "SELECT * FROM $draw_prefix";
				$query .= "temp_combo2_p4";
				if ($hml == 0) {
					$query .= " ";
				} elseif ($hml == 1) {
					$query .= "_1 ";
				} elseif ($hml == 2) {
					$query .= "_2 ";
				} elseif ($hml == 3) {
					$query .= "_3 ";
				} 
				$query .= "WHERE num1 = '{$matrix[1][3]}' "; 
				$query .= "AND num2 > {$matrix[4][3]} ";

				print("<p>$query</p>");
			
				$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

				while($row = mysqli_fetch_array($mysqli_result))
				{
					$y = $row[num2];
					$seed4_table[$y] += $row[percent_y10];
				}

				echo "seed4_table = ";
				print_r ($seed4_table);
				echo "<p>";

				# pick top 

				$max_array = array_fill (0, 2, 0);

				for ($z = 0; $z < 2; $z++)
				{
					$maxs = array_keys($seed4_table, max($seed4_table));
					$max_array[$z] = $maxs[0];
					echo "maxs = $max_array[$z]<br>";
					$seed4_table[$maxs[0]] = 0.0;
				}

				do {
					shuffle ($max_array);
					$temp_count = count($max_array)-1;
					$rand_id = mt_rand(0,($temp_count));
					$passed = DrawCombo1($matrix,$seed_comb2=$max_array[$rand_id],$dup1,$dup2,$dup3,$mrow,$mcolumn);
					if ($passed)
					{
						$matrix[2][3] = $max_array[$rand_id];
					} else {
						echo "--2,3-- $max_array[$rand_id] failed - dup1 = $dup1 <br>";
					}
					unset ($max_array[$rand_id]);
					$max_array = array_values($max_array);
					if (count($max_array) == 0 && !$passed)
					{
						for ($z = 0; $z < 2; $z++)
						{
							$maxs = array_keys($seed4_table, max($seed4_table));
							$max_array[$z] = $maxs[0];
							echo "maxs = $max_array[$z]<br>";
							$seed4_table[$maxs[0]] = 0.0;
						}
					}
				} while (!$passed);

				# ---- 2,3 end

				# ---- 3,1
				$mrow = 3;
				$mcolumn = 1;
				$query = "SELECT * FROM $draw_prefix";
				$query .= "temp_combo2_p4";
				if ($hml == 0) {
					$query .= " ";
				} elseif ($hml == 1) {
					$query .= "_1 ";
				} elseif ($hml == 2) {
					$query .= "_2 ";
				} elseif ($hml == 3) {
					$query .= "_3 ";
				} 
				$query .= "WHERE num1 = '{$matrix[3][4]}' "; 
				$query .= "AND num2 > {$matrix[3][2]} ";

				print("<p>$query</p>");
			
				$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

				$seed4_table = array_fill(0, $balls+1, 0.0);

				while($row = mysqli_fetch_array($mysqli_result))
				{
					$y = $row[num2];
					$seed4_table[$y] += $row[percent_y10];
				}

				$query = "SELECT * FROM $draw_prefix";
				$query .= "temp_combo2_p4";
				if ($hml == 0) {
					$query .= " ";
				} elseif ($hml == 1) {
					$query .= "_1 ";
				} elseif ($hml == 2) {
					$query .= "_2 ";
				} elseif ($hml == 3) {
					$query .= "_3 ";
				} 
				$query .= "WHERE num1 = '{$matrix[2][1]}' "; 
				$query .= "AND num2 > {$matrix[1][1]} ";

				print("<p>$query</p>");
			
				$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

				while($row = mysqli_fetch_array($mysqli_result))
				{
					$y = $row[num2];
					$seed4_table[$y] += $row[percent_y10];
				}

				echo "seed4_table = ";
				print_r ($seed4_table);
				echo "<p>";

				# pick top 

				$max_array = array_fill (0, 2, 0);

				for ($z = 0; $z < 2; $z++)
				{
					$maxs = array_keys($seed4_table, max($seed4_table));
					$max_array[$z] = $maxs[0];
					echo "maxs = $max_array[$z]<br>";
					$seed4_table[$maxs[0]] = 0.0;
				}

				do {
					shuffle ($max_array);
					$temp_count = count($max_array)-1;
					$rand_id = mt_rand(0,($temp_count));
					$passed = DrawCombo1($matrix,$seed_comb2=$max_array[$rand_id],$dup1,$dup2,$dup3,$mrow,$mcolumn);
					if ($passed)
					{
						$matrix[3][1] = $max_array[$rand_id];
					} else {
						echo "--3,1-- $max_array[$rand_id] failed - dup1 = $dup1 <br>";
					}
					unset ($max_array[$rand_id]);
					$max_array = array_values($max_array);
					if (count($max_array) == 0 && !$passed)
					{
						for ($z = 0; $z < 2; $z++)
						{
							$maxs = array_keys($seed4_table, max($seed4_table));
							$max_array[$z] = $maxs[0];
							echo "maxs = $max_array[$z]<br>";
							$seed4_table[$maxs[0]] = 0.0;
						}
					}
				} while (!$passed);

				# ---- 3,1 end

				# ---- 4,4
				$mrow = 4;
				$mcolumn = 4;
				$query = "SELECT * FROM $draw_prefix";
				$query .= "temp_combo2_p4";
				if ($hml == 0) {
					$query .= " ";
				} elseif ($hml == 1) {
					$query .= "_1 ";
				} elseif ($hml == 2) {
					$query .= "_2 ";
				} elseif ($hml == 3) {
					$query .= "_3 ";
				} 
				$query .= "WHERE num1 = '{$matrix[4][2]}' "; 
				$query .= "AND num2 > {$matrix[4][3]} ";

				print("<p>$query</p>");
			
				$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

				$seed4_table = array_fill(0, $balls+1, 0.0);

				while($row = mysqli_fetch_array($mysqli_result))
				{
					$y = $row[num2];
					$seed4_table[$y] += $row[percent_y10];
				}

				$query = "SELECT * FROM $draw_prefix";
				$query .= "temp_combo2_p4";
				if ($hml == 0) {
					$query .= " ";
				} elseif ($hml == 1) {
					$query .= "_1 ";
				} elseif ($hml == 2) {
					$query .= "_2 ";
				} elseif ($hml == 3) {
					$query .= "_3 ";
				} 
				$query .= "WHERE num1 = '{$matrix[3][4]}' "; 
				$query .= "AND num2 > {$matrix[0][4]} ";

				print("<p>$query</p>");
			
				$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

				while($row = mysqli_fetch_array($mysqli_result))
				{
					$y = $row[num2];
					$seed4_table[$y] += $row[percent_y10];
				}

				echo "seed4_table = ";
				print_r ($seed4_table);
				echo "<p>";

				# pick top 

				$max_array = array_fill (0, 2, 0);

				for ($z = 0; $z < 2; $z++)
				{
					$maxs = array_keys($seed4_table, max($seed4_table));
					$max_array[$z] = $maxs[0];
					echo "maxs = $max_array[$z]<br>";
					$seed4_table[$maxs[0]] = 0.0;
				}

				do {
					shuffle ($max_array);
					$temp_count = count($max_array)-1;
					$rand_id = mt_rand(0,($temp_count));
					$passed = DrawCombo1($matrix,$seed_comb2=$max_array[$rand_id],$dup1,$dup2,$dup3,$mrow,$mcolumn);
					if ($passed)
					{
						$matrix[4][4] = $max_array[$rand_id];
					} else {
						echo "--4,4-- $max_array[$rand_id] failed - dup1 = $dup1 <br>";
					}
					unset ($max_array[$rand_id]);
					$max_array = array_values($max_array);
					if (count($max_array) == 0 && !$passed)
					{
						for ($z = 0; $z < 2; $z++)
						{
							$maxs = array_keys($seed4_table, max($seed4_table));
							$max_array[$z] = $maxs[0];
							echo "maxs = $max_array[$z]<br>";
							$seed4_table[$maxs[0]] = 0.0;
						}
					}
				} while (!$passed);

				# ---- 4,4 end
			} else {
				# ---- 
				$query = "SELECT * FROM $draw_prefix";
				$query .= "temp_combo2_p4";
				if ($hml == 0) {
					$query .= " ";
				} elseif ($hml == 1) {
					$query .= "_1 ";
				} elseif ($hml == 2) {
					$query .= "_2 ";
				} elseif ($hml == 3) {
					$query .= "_3 ";
				} 
				$query .= "WHERE num1 = '$col1' "; 

				print("<p>$query</p>");
			
				$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));
			}

			print_matrix ($matrix,$balls_drawn);

			# --05-- seed comb2 --------------------------------------------------------------------------------------
			# note add comb?

			echo "<hr>";
			echo "<b>Seed 5 - comb2/add comb?</b><p>";

			if ($col1 < 39)
			{
				# ---- 3,0
				$mrow = 3;
				$mcolumn = 0;
				$query = "SELECT * FROM $draw_prefix";
				$query .= "temp_combo2_p2";
				if ($hml == 0) {
					$query .= " ";
				} elseif ($hml == 1) {
					$query .= "_1 ";
				} elseif ($hml == 2) {
					$query .= "_2 ";
				} elseif ($hml == 3) {
					$query .= "_3 ";
				} 

				$query .= "WHERE num1 = '{$matrix[3][4]}' "; #row 
				$query .= "AND (num2 > {$matrix[3][2]} ";
				$query .= "AND num2 < {$matrix[3][1]}) ";
				#$query .= "AND (num2 > {$matrix[2][0]} ";
				#$query .= "AND num2 < {$matrix[1][0]}) ";

				print("<p>$query</p>");
			
				$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

				$seed5_table = array_fill(0, $balls+1, 0.0);

				while($row = mysqli_fetch_array($mysqli_result))
				{
					$y = $row[num2];
					$seed5_table[$y] += $row[percent_y10];
				}

				$query = "SELECT * FROM $draw_prefix";
				$query .= "temp_combo2_p2";
				if ($hml == 0) {
					$query .= " ";
				} elseif ($hml == 1) {
					$query .= "_1 ";
				} elseif ($hml == 2) {
					$query .= "_2 ";
				} elseif ($hml == 3) {
					$query .= "_3 ";
				} 
				$query .= "WHERE num1 = '{$matrix[3][4]}' "; #col
				$query .= "AND (num2 > {$matrix[2][0]} ";
				$query .= "AND num2 < {$matrix[1][0]}) ";
				#$query .= "AND (num2 > {$matrix[3][2]} ";
				#$query .= "AND num2 < {$matrix[3][1]}) ";

				print("<p>$query</p>");
			
				$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

				while($row = mysqli_fetch_array($mysqli_result))
				{
					$y = $row[num2];
					$seed5_table[$y] += $row[percent_y10];
				}

				$query = "SELECT * FROM $draw_prefix";
				$query .= "temp_combo2_p2";
				if ($hml == 0) {
					$query .= " ";
				} elseif ($hml == 1) {
					$query .= "_1 ";
				} elseif ($hml == 2) {
					$query .= "_2 ";
				} elseif ($hml == 3) {
					$query .= "_3 ";
				} 
				$query .= "WHERE num1 = '{$matrix[0][0]}' "; #col
				#$query .= "AND (num2 > {$matrix[2][0]} ";
				#$query .= "AND num2 < {$matrix[1][0]}) ";
				$query .= "AND (num2 > {$matrix[3][2]} ";
				$query .= "AND num2 < {$matrix[3][1]}) ";

				print("<p>$query</p>");
			
				$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

				while($row = mysqli_fetch_array($mysqli_result))
				{
					$y = $row[num2];
					$seed5_table[$y] += $row[percent_y10];
				}

				$query = "SELECT * FROM $draw_prefix";
				$query .= "temp_combo2_p2";
				if ($hml == 0) {
					$query .= " ";
				} elseif ($hml == 1) {
					$query .= "_1 ";
				} elseif ($hml == 2) {
					$query .= "_2 ";
				} elseif ($hml == 3) {
					$query .= "_3 ";
				} 
				$query .= "WHERE num1 = '{$matrix[0][0]}' "; #col
				$query .= "AND (num2 > {$matrix[2][0]} ";
				$query .= "AND num2 < {$matrix[1][0]}) ";
				#$query .= "AND (num2 > {$matrix[3][2]} ";
				#$query .= "AND num2 < {$matrix[3][1]}) ";

				print("<p>$query</p>");
			
				$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

				while($row = mysqli_fetch_array($mysqli_result))
				{
					$y = $row[num2];
					$seed5_table[$y] += $row[percent_y10];
				}

				echo "seed5_table = ";
				print_r ($seed5_table);
				echo "<p>";

				# pick top 

				$max_array = array_fill (0, 2, 0);

				for ($z = 0; $z < 2; $z++)
				{
					$maxs = array_keys($seed5_table, max($seed5_table));
					$max_array[$z] = $maxs[0];
					echo "maxs = $max_array[$z]<br>";
					$seed5_table[$maxs[0]] = 0.0;
				}

				do {
					shuffle ($max_array);
					$temp_count = count($max_array)-1;
					$rand_id = mt_rand(0,($temp_count));
					$passed = DrawCombo1($matrix,$seed_comb2=$max_array[$rand_id],$dup1,$dup2,$dup3,$mrow,$mcolumn);
					if ($passed)
					{
						$matrix[3][0] = $max_array[$rand_id];
					} else {
						echo "--3,0-- $max_array[$rand_id] failed - dup1 = $dup1 <br>";
					}
					unset ($max_array[$rand_id]);
					$max_array = array_values($max_array);
					if (count($max_array) == 0 && !$passed)
					{
						for ($z = 0; $z < 2; $z++)
						{
							$maxs = array_keys($seed5_table, max($seed5_table));
							$max_array[$z] = $maxs[0];
							echo "maxs = $max_array[$z]<br>";
							$seed5_table[$maxs[0]] = 0.0;
						}
					}
				} while (!$passed);

				# ---- 3,0 end

				# ---- 4,1
				$mrow = 4;
				$mcolumn = 1;

				$query = "SELECT * FROM $draw_prefix";
				$query .= "temp_combo2_p2";
				if ($hml == 0) {
					$query .= " ";
				} elseif ($hml == 1) {
					$query .= "_1 ";
				} elseif ($hml == 2) {
					$query .= "_2 ";
				} elseif ($hml == 3) {
					$query .= "_3 ";
				} 
				$query .= "WHERE num1 = '{$matrix[4][2]}' "; #row
				$query .= "AND (num2 > {$matrix[4][3]} ";
				$query .= "AND num2 < {$matrix[4][4]}) ";
				#$query .= "AND (num2 > {$matrix[1][1]} ";
				#$query .= "AND num2 < {$matrix[3][1]}) ";

				print("<p>$query</p>");
			
				$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

				$seed5_table = array_fill(0, $balls+1, 0.0);

				while($row = mysqli_fetch_array($mysqli_result))
				{
					$y = $row[num2];
					$seed5_table[$y] += $row[percent_y10];
				}

				$query = "SELECT * FROM $draw_prefix";
				$query .= "temp_combo2_p2";
				if ($hml == 0) {
					$query .= " ";
				} elseif ($hml == 1) {
					$query .= "_1 ";
				} elseif ($hml == 2) {
					$query .= "_2 ";
				} elseif ($hml == 3) {
					$query .= "_3 ";
				} 
				$query .= "WHERE num1 = '{$matrix[4][2]}' "; #row
				#$query .= "AND (num2 > {$matrix[4][3]} ";
				#$query .= "AND num2 < {$matrix[4][4]}) ";
				$query .= "AND (num2 > {$matrix[1][1]} ";
				$query .= "AND num2 < {$matrix[3][1]}) ";

				print("<p>$query</p>");
			
				$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

				while($row = mysqli_fetch_array($mysqli_result))
				{
					$y = $row[num2];
					$seed5_table[$y] += $row[percent_y10];
				}

				$query = "SELECT * FROM $draw_prefix";
				$query .= "temp_combo2_p2";
				if ($hml == 0) {
					$query .= " ";
				} elseif ($hml == 1) {
					$query .= "_1 ";
				} elseif ($hml == 2) {
					$query .= "_2 ";
				} elseif ($hml == 3) {
					$query .= "_3 ";
				} 
				$query .= "WHERE num1 = '{$matrix[2][1]}' "; #col
				$query .= "AND (num2 > {$matrix[1][1]} ";
				$query .= "AND num2 < {$matrix[3][1]}) ";
				#$query .= "AND (num2 > {$matrix[4][3]} ";
				#$query .= "AND num2 < {$matrix[4][4]}) ";

				print("<p>$query</p>");
			
				$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

				while($row = mysqli_fetch_array($mysqli_result))
				{
					$y = $row[num2];
					$seed5_table[$y] += $row[percent_y10];
				}

				$query = "SELECT * FROM $draw_prefix";
				$query .= "temp_combo2_p2";
				if ($hml == 0) {
					$query .= " ";
				} elseif ($hml == 1) {
					$query .= "_1 ";
				} elseif ($hml == 2) {
					$query .= "_2 ";
				} elseif ($hml == 3) {
					$query .= "_3 ";
				} 
				$query .= "WHERE num1 = '{$matrix[2][1]}' "; #col
				#$query .= "AND (num2 > {$matrix[1][1]} ";
				#$query .= "AND num2 < {$matrix[3][1]}) ";
				$query .= "AND (num2 > {$matrix[4][3]} ";
				$query .= "AND num2 < {$matrix[4][4]}) ";

				print("<p>$query</p>");
			
				$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

				while($row = mysqli_fetch_array($mysqli_result))
				{
					$y = $row[num2];
					$seed5_table[$y] += $row[percent_y10];
				}

				echo "seed5_table = ";
				print_r ($seed5_table);
				echo "<p>";

				# pick top 

				$max_array = array_fill (0, 2, 0);

				for ($z = 0; $z < 2; $z++)
				{
					$maxs = array_keys($seed5_table, max($seed5_table));
					$max_array[$z] = $maxs[0];
					echo "maxs = $max_array[$z]<br>";
					$seed5_table[$maxs[0]] = 0.0;
				}

				do {
					shuffle ($max_array);
					$temp_count = count($max_array)-1;
					$rand_id = mt_rand(0,($temp_count));
					$passed = DrawCombo1($matrix,$seed_comb2=$max_array[$rand_id],$dup1,$dup2,$dup3,$mrow,$mcolumn);
					if ($passed)
					{
						$matrix[4][1] = $max_array[$rand_id];
					} else {
						echo "--4,1-- $max_array[$rand_id] failed - dup1 = $dup1 <br>";
					}
					unset ($max_array[$rand_id]);
					$max_array = array_values($max_array);
					if (count($max_array) == 0 && !$passed)
					{
						for ($z = 0; $z < 2; $z++)
						{
							$maxs = array_keys($seed5_table, max($seed5_table));
							$max_array[$z] = $maxs[0];
							echo "maxs = $max_array[$z]<br>";
							$seed5_table[$maxs[0]] = 0.0;
						}
					}
				} while (!$passed);

				# ---- 4,1 end

				# ---- 2,2
				$mrow = 2;
				$mcolumn = 2;

				$seed5_table = array_fill(0, $balls+1, 0.0);

				$query = "SELECT * FROM $draw_prefix";
				$query .= "temp_combo2_p2";
				if ($hml == 0) {
					$query .= " ";
				} elseif ($hml == 1) {
					$query .= "_1 ";
				} elseif ($hml == 2) {
					$query .= "_2 ";
				} elseif ($hml == 3) {
					$query .= "_3 ";
				} 
				$query .= "WHERE num1 = '{$matrix[2][1]}' "; #row
				$query .= "AND (num2 > {$matrix[2][0]} ";
				$query .= "AND num2 < {$matrix[2][3]}) ";
				#$query .= "AND (num2 > {$matrix[3][2]} ";
				#$query .= "AND num2 < {$matrix[0][2]}) ";

				print("<p>$query</p>");
			
				$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

				while($row = mysqli_fetch_array($mysqli_result))
				{
					$y = $row[num2];
					$seed5_table[$y] += $row[percent_y10];
				}

				$query = "SELECT * FROM $draw_prefix";
				$query .= "temp_combo2_p2";
				if ($hml == 0) {
					$query .= " ";
				} elseif ($hml == 1) {
					$query .= "_1 ";
				} elseif ($hml == 2) {
					$query .= "_2 ";
				} elseif ($hml == 3) {
					$query .= "_3 ";
				} 
				$query .= "WHERE num1 = '{$matrix[2][1]}' "; #row
				#$query .= "AND (num2 > {$matrix[2][0]} ";
				#$query .= "AND num2 < {$matrix[2][3]}) ";
				$query .= "AND (num2 > {$matrix[3][2]} ";
				$query .= "AND num2 < {$matrix[0][2]}) ";

				print("<p>$query</p>");
			
				$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

				while($row = mysqli_fetch_array($mysqli_result))
				{
					$y = $row[num2];
					$seed5_table[$y] += $row[percent_y10];
				}

				$query = "SELECT * FROM $draw_prefix";
				$query .= "temp_combo2_p2";
				if ($hml == 0) {
					$query .= " ";
				} elseif ($hml == 1) {
					$query .= "_1 ";
				} elseif ($hml == 2) {
					$query .= "_2 ";
				} elseif ($hml == 3) {
					$query .= "_3 ";
				} 

				$query .= "WHERE num1 = '{$matrix[4][2]}' "; #col
				$query .= "AND (num2 > {$matrix[2][0]} ";
				$query .= "AND num2 < {$matrix[2][3]}) ";
				#$query .= "AND (num2 > {$matrix[3][2]} ";
				#$query .= "AND num2 < {$matrix[0][2]}) ";

				print("<p>$query</p>");
			
				$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

				while($row = mysqli_fetch_array($mysqli_result))
				{
					$y = $row[num2];
					$seed5_table[$y] += $row[percent_y10];
				}

				$query = "SELECT * FROM $draw_prefix";
				$query .= "temp_combo2_p2";
				if ($hml == 0) {
					$query .= " ";
				} elseif ($hml == 1) {
					$query .= "_1 ";
				} elseif ($hml == 2) {
					$query .= "_2 ";
				} elseif ($hml == 3) {
					$query .= "_3 ";
				} 

				$query .= "WHERE num1 = '{$matrix[4][2]}' "; #col
				#$query .= "AND (num2 > {$matrix[2][0]} ";
				#$query .= "AND num2 < {$matrix[2][3]}) ";
				$query .= "AND (num2 > {$matrix[3][2]} ";
				$query .= "AND num2 < {$matrix[0][2]}) ";

				print("<p>$query</p>");
			
				$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

				while($row = mysqli_fetch_array($mysqli_result))
				{
					$y = $row[num2];
					$seed5_table[$y] += $row[percent_y10];
				}

				echo "seed5_table = ";
				print_r ($seed5_table);
				echo "<p>";

				# pick top 

				$max_array = array_fill (0, 2, 0);

				for ($z = 0; $z < 2; $z++)
				{
					$maxs = array_keys($seed5_table, max($seed5_table));
					$max_array[$z] = $maxs[0];
					echo "maxs = $max_array[$z]<br>";
					$seed5_table[$maxs[0]] = 0.0;
				}

				do {
					shuffle ($max_array);
					$temp_count = count($max_array)-1;
					$rand_id = mt_rand(0,($temp_count));
					$passed = DrawCombo1($matrix,$seed_comb2=$max_array[$rand_id],$dup1,$dup2,$dup3,$mrow,$mcolumn);
					if ($passed)
					{
						$matrix[2][2] = $max_array[$rand_id];
					} else {
						echo "--2,2-- $max_array[$rand_id] failed - dup1 = $dup1 <br>";
					}
					unset ($max_array[$rand_id]);
					$max_array = array_values($max_array);
					if (count($max_array) == 0 && !$passed)
					{
						for ($z = 0; $z < 2; $z++)
						{
							$maxs = array_keys($seed5_table, max($seed5_table));
							$max_array[$z] = $maxs[0];
							echo "maxs = $max_array[$z]<br>";
							$seed5_table[$maxs[0]] = 0.0;
						}
					}
				} while (!$passed);

				# ---- 2,2 end

				# ---- 0,3
				$mrow = 0;
				$mcolumn = 3;

				$seed5_table = array_fill(0, $balls+1, 0.0);

				$query = "SELECT * FROM $draw_prefix";
				$query .= "temp_combo2_p2";
				if ($hml == 0) {
					$query .= " ";
				} elseif ($hml == 1) {
					$query .= "_1 ";
				} elseif ($hml == 2) {
					$query .= "_2 ";
				} elseif ($hml == 3) {
					$query .= "_3 ";
				} 
				$query .= "WHERE num1 = '{$matrix[0][0]}' "; #row
				$query .= "AND (num2 > {$matrix[4][3]} ";
				$query .= "AND num2 < {$matrix[2][3]}) ";
				#$query .= "AND (num2 > {$matrix[0][4]} ";
				#$query .= "AND num2 < {$matrix[0][2]}) ";

				print("<p>$query</p>");
			
				$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

				while($row = mysqli_fetch_array($mysqli_result))
				{
					$y = $row[num2];
					$seed5_table[$y] += $row[percent_y10];
				}

				$query = "SELECT * FROM $draw_prefix";
				$query .= "temp_combo2_p2";
				if ($hml == 0) {
					$query .= " ";
				} elseif ($hml == 1) {
					$query .= "_1 ";
				} elseif ($hml == 2) {
					$query .= "_2 ";
				} elseif ($hml == 3) {
					$query .= "_3 ";
				} 
				$query .= "WHERE num1 = '{$matrix[0][0]}' "; #row
				#$query .= "AND (num2 > {$matrix[4][3]} ";
				#$query .= "AND num2 < {$matrix[2][3]}) ";
				$query .= "AND (num2 > {$matrix[0][4]} ";
				$query .= "AND num2 < {$matrix[0][2]}) ";

				print("<p>$query</p>");
			
				$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

				while($row = mysqli_fetch_array($mysqli_result))
				{
					$y = $row[num2];
					$seed5_table[$y] += $row[percent_y10];
				}

				$query = "SELECT * FROM $draw_prefix";
				$query .= "temp_combo2_p2";
				if ($hml == 0) {
					$query .= " ";
				} elseif ($hml == 1) {
					$query .= "_1 ";
				} elseif ($hml == 2) {
					$query .= "_2 ";
				} elseif ($hml == 3) {
					$query .= "_3 ";
				} 
				$query .= "WHERE num1 = '{$matrix[1][3]}' "; #col
				$query .= "AND (num2 > {$matrix[4][3]} ";
				$query .= "AND num2 < {$matrix[2][3]}) ";
				#$query .= "AND (num2 > {$matrix[0][4]} ";
				#$query .= "AND num2 < {$matrix[0][2]}) ";

				print("<p>$query</p>");
			
				$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

				while($row = mysqli_fetch_array($mysqli_result))
				{
					$y = $row[num2];
					$seed5_table[$y] += $row[percent_y10];
				}

				$query = "SELECT * FROM $draw_prefix";
				$query .= "temp_combo2_p2";
				if ($hml == 0) {
					$query .= " ";
				} elseif ($hml == 1) {
					$query .= "_1 ";
				} elseif ($hml == 2) {
					$query .= "_2 ";
				} elseif ($hml == 3) {
					$query .= "_3 ";
				} 
				$query .= "WHERE num1 = '{$matrix[1][3]}' "; #col
				#$query .= "AND (num2 > {$matrix[4][3]} ";
				#$query .= "AND num2 < {$matrix[2][3]}) ";
				$query .= "AND (num2 > {$matrix[0][4]} ";
				$query .= "AND num2 < {$matrix[0][2]}) ";

				print("<p>$query</p>");
			
				$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

				while($row = mysqli_fetch_array($mysqli_result))
				{
					$y = $row[num2];
					$seed5_table[$y] += $row[percent_y10];
				}

				echo "seed5_table = ";
				print_r ($seed5_table);
				echo "<p>";

				# pick top 

				$max_array = array_fill (0, 2, 0);

				for ($z = 0; $z < 2; $z++)
				{
					$maxs = array_keys($seed5_table, max($seed5_table));
					$max_array[$z] = $maxs[0];
					echo "maxs = $max_array[$z]<br>";
					$seed5_table[$maxs[0]] = 0.0;
				}

				do {
					shuffle ($max_array);
					$temp_count = count($max_array)-1;
					$rand_id = mt_rand(0,($temp_count));
					$passed = DrawCombo1($matrix,$seed_comb2=$max_array[$rand_id],$dup1,$dup2,$dup3,$mrow,$mcolumn);
					if ($passed)
					{
						$matrix[0][3] = $max_array[$rand_id];
					} else {
						echo "--0,3-- $max_array[$rand_id] failed - dup1 = $dup1 <br>";
					}
					unset ($max_array[$rand_id]);
					$max_array = array_values($max_array);
					if (count($max_array) == 0 && !$passed)
					{
						for ($z = 0; $z < 2; $z++)
						{
							$maxs = array_keys($seed5_table, max($seed5_table));
							$max_array[$z] = $maxs[0];
							echo "maxs = $max_array[$z]<br>";
							$seed5_table[$maxs[0]] = 0.0;
						}
					}
				} while (!$passed);

				# ---- 0,3 end

				# ---- 1,4
				$mrow = 1;
				$mcolumn = 4;

				$seed5_table = array_fill(0, $balls+1, 0.0);

				$query = "SELECT * FROM $draw_prefix";
				$query .= "temp_combo2_p2";
				if ($hml == 0) {
					$query .= " ";
				} elseif ($hml == 1) {
					$query .= "_1 ";
				} elseif ($hml == 2) {
					$query .= "_2 ";
				} elseif ($hml == 3) {
					$query .= "_3 ";
				} 
				$query .= "WHERE num1 = '{$matrix[1][3]}' "; #row
				$query .= "AND (num2 > {$matrix[0][4]} ";
				$query .= "AND num2 < {$matrix[4][4]}) ";
				#$query .= "AND (num2 > {$matrix[1][1]} ";
				#$query .= "AND num2 < {$matrix[1][0]}) ";

				print("<p>$query</p>");
			
				$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

				while($row = mysqli_fetch_array($mysqli_result))
				{
					$y = $row[num2];
					$seed5_table[$y] += $row[percent_y10];
				}

				$query = "SELECT * FROM $draw_prefix";
				$query .= "temp_combo2_p2";
				if ($hml == 0) {
					$query .= " ";
				} elseif ($hml == 1) {
					$query .= "_1 ";
				} elseif ($hml == 2) {
					$query .= "_2 ";
				} elseif ($hml == 3) {
					$query .= "_3 ";
				} 
				$query .= "WHERE num1 = '{$matrix[1][3]}' "; #row
				#$query .= "AND (num2 > {$matrix[0][4]} ";
				#$query .= "AND num2 < {$matrix[4][4]}) ";
				$query .= "AND (num2 > {$matrix[1][1]} ";
				$query .= "AND num2 < {$matrix[1][0]}) ";

				print("<p>$query</p>");
			
				$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

				while($row = mysqli_fetch_array($mysqli_result))
				{
					$y = $row[num2];
					$seed5_table[$y] += $row[percent_y10];
				}

				$query = "SELECT * FROM $draw_prefix";
				$query .= "temp_combo2_p2";
				if ($hml == 0) {
					$query .= " ";
				} elseif ($hml == 1) {
					$query .= "_1 ";
				} elseif ($hml == 2) {
					$query .= "_2 ";
				} elseif ($hml == 3) {
					$query .= "_3 ";
				} 
				$query .= "WHERE num1 = '{$matrix[3][4]}' "; #col
				$query .= "AND (num2 > {$matrix[0][4]} ";
				$query .= "AND num2 < {$matrix[4][4]}) ";
				#$query .= "AND (num2 > {$matrix[1][1]} ";
				#$query .= "AND num2 < {$matrix[1][0]}) ";

				print("<p>$query</p>");
			
				$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

				while($row = mysqli_fetch_array($mysqli_result))
				{
					$y = $row[num2];
					$seed5_table[$y] += $row[percent_y10];
				}

				$query = "SELECT * FROM $draw_prefix";
				$query .= "temp_combo2_p2";
				if ($hml == 0) {
					$query .= " ";
				} elseif ($hml == 1) {
					$query .= "_1 ";
				} elseif ($hml == 2) {
					$query .= "_2 ";
				} elseif ($hml == 3) {
					$query .= "_3 ";
				} 
				$query .= "WHERE num1 = '{$matrix[3][4]}' "; #col
				#$query .= "AND (num2 > {$matrix[0][4]} ";
				#$query .= "AND num2 < {$matrix[4][4]}) ";
				$query .= "AND (num2 > {$matrix[1][1]} ";
				$query .= "AND num2 < {$matrix[1][0]}) ";

				print("<p>$query</p>");
			
				$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

				while($row = mysqli_fetch_array($mysqli_result))
				{
					$y = $row[num2];
					$seed5_table[$y] += $row[percent_y10];
				}

				echo "seed5_table = ";
				print_r ($seed5_table);
				echo "<p>";

				# pick top 

				$max_array = array_fill (0, 2, 0);

				for ($z = 0; $z < 2; $z++)
				{
					$maxs = array_keys($seed5_table, max($seed5_table));
					$max_array[$z] = $maxs[0];
					echo "maxs = $max_array[$z]<br>";
					$seed5_table[$maxs[0]] = 0.0;
				}

				do {
					shuffle ($max_array);
					$temp_count = count($max_array)-1;
					$rand_id = mt_rand(0,($temp_count));
					$passed = DrawCombo1($matrix,$seed_comb2=$max_array[$rand_id],$dup1,$dup2,$dup3,$mrow,$mcolumn);
					if ($passed)
					{
						$matrix[1][4] = $max_array[$rand_id];
					} else {
						echo "--1,4-- $max_array[$rand_id] failed - dup1 = $dup1 <br>";
					}
					unset ($max_array[$rand_id]);
					$max_array = array_values($max_array);
					if (count($max_array) == 0 && !$passed)
					{
						for ($z = 0; $z < 2; $z++)
						{
							$maxs = array_keys($seed5_table, max($seed5_table));
							$max_array[$z] = $maxs[0];
							echo "maxs = $max_array[$z]<br>";
							$seed5_table[$maxs[0]] = 0.0;
						}
					}
				} while (!$passed);

				# ---- 1,4 end
			} else {
				# ---- 
				$query = "SELECT * FROM $draw_prefix";
				$query .= "temp_combo2_p2";
				if ($hml == 0) {
					$query .= " ";
				} elseif ($hml == 1) {
					$query .= "_1 ";
				} elseif ($hml == 2) {
					$query .= "_2 ";
				} elseif ($hml == 3) {
					$query .= "_3 ";
				} 
				$query .= "WHERE num1 = '$col1' "; 

				print("<p>$query</p>");
			
				$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));
			}

			print_matrix ($matrix,$balls_drawn);

			# --06-- seed comb3 --------------------------------------------------------------------------------------
			# note add comb?

			echo "<hr>";
			echo "<b>Seed 6 - comb3/add comb?</b><p>";

			if ($col1 < 39)
			{
				# ---- 4,0
				$mrow = 4;
				$mcolumn = 0;
				$query = "SELECT * FROM $draw_prefix";
				$query .= "temp_combo2_p3";
				if ($hml == 0) {
					$query .= " ";
				} elseif ($hml == 1) {
					$query .= "_1 ";
				} elseif ($hml == 2) {
					$query .= "_2 ";
				} elseif ($hml == 3) {
					$query .= "_3 ";
				} 
				$query .= "WHERE num1 = '{$matrix[4][2]}' "; #row 
				$query .= "AND (num2 > {$matrix[4][1]} ";
				$query .= "AND num2 < {$matrix[4][4]}) ";
				$query .= "AND (num2 > {$matrix[3][0]} ";
				$query .= "AND num2 < {$matrix[1][0]}) ";	

				print("<p>$query</p>");
			
				$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

				$seed6_table = array_fill(0, $balls+1, 0.0);

				while($row = mysqli_fetch_array($mysqli_result))
				{
					$y = $row[num2];
					$seed6_table[$y] += $row[percent_y10];
				}

				$query = "SELECT * FROM $draw_prefix";
				$query .= "temp_combo2_p3";
				if ($hml == 0) {
					$query .= " ";
				} elseif ($hml == 1) {
					$query .= "_1 ";
				} elseif ($hml == 2) {
					$query .= "_2 ";
				} elseif ($hml == 3) {
					$query .= "_3 ";
				} 
				$query .= "WHERE num1 = '{$matrix[0][0]}' "; #col
				$query .= "AND (num2 > {$matrix[4][1]} ";
				$query .= "AND num2 < {$matrix[4][4]}) ";
				$query .= "AND (num2 > {$matrix[3][0]} ";
				$query .= "AND num2 < {$matrix[1][0]}) ";

				print("<p>$query</p>");
			
				$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

				while($row = mysqli_fetch_array($mysqli_result))
				{
					$y = $row[num2];
					$seed6_table[$y] += $row[percent_y10];
				}

				echo "seed6_table = ";
				print_r ($seed6_table);
				echo "<p>";

				# pick top 

				$max_array = array_fill (0, 2, 0);

				for ($z = 0; $z < 2; $z++)
				{
					$maxs = array_keys($seed6_table, max($seed6_table));
					$max_array[$z] = $maxs[0];
					echo "maxs = $max_array[$z]<br>";
					$seed6_table[$maxs[0]] = 0.0;
				}

				do {
					shuffle ($max_array);
					$temp_count = count($max_array)-1;
					$rand_id = mt_rand(0,($temp_count));
					$passed = DrawCombo1($matrix,$seed_comb2=$max_array[$rand_id],$dup1,$dup2,$dup3,$mrow,$mcolumn);
					if ($passed)
					{
						$matrix[4][0] = $max_array[$rand_id];
					} else {
						echo "--4,0-- $max_array[$rand_id] failed - dup1 = $dup1 <br>";
					}
					unset ($max_array[$rand_id]);
					$max_array = array_values($max_array);
					if (count($max_array) == 0 && !$passed)
					{
						for ($z = 0; $z < 2; $z++)
						{
							$maxs = array_keys($seed6_table, max($seed6_table));
							$max_array[$z] = $maxs[0];
							echo "maxs = $max_array[$z]<br>";
							$seed6_table[$maxs[0]] = 0.0;
						}
					}
				} while (!$passed);

				# ---- 4,0 end

				# ---- 0,1
				$mrow = 0;
				$mcolumn = 1;

				$query = "SELECT * FROM $draw_prefix";
				$query .= "temp_combo2_p3";
				if ($hml == 0) {
					$query .= " ";
				} elseif ($hml == 1) {
					$query .= "_1 ";
				} elseif ($hml == 2) {
					$query .= "_2 ";
				} elseif ($hml == 3) {
					$query .= "_3 ";
				} 
				$query .= "WHERE num1 = '{$matrix[0][0]}' "; #row
				$query .= "AND (num2 > {$matrix[0][3]} ";
				$query .= "AND num2 < {$matrix[0][2]}) ";
				$query .= "AND (num2 > {$matrix[4][1]} ";
				$query .= "AND num2 < {$matrix[3][1]}) ";

				print("<p>$query</p>");
			
				$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

				$seed6_table = array_fill(0, $balls+1, 0.0);

				while($row = mysqli_fetch_array($mysqli_result))
				{
					$y = $row[num2];
					$seed6_table[$y] += $row[percent_y10];
				}

				$query = "SELECT * FROM $draw_prefix";
				$query .= "temp_combo2_p3";
				if ($hml == 0) {
					$query .= " ";
				} elseif ($hml == 1) {
					$query .= "_1 ";
				} elseif ($hml == 2) {
					$query .= "_2 ";
				} elseif ($hml == 3) {
					$query .= "_3 ";
				} 
				$query .= "WHERE num1 = '{$matrix[2][1]}' "; #col
				$query .= "AND (num2 > {$matrix[0][3]} ";
				$query .= "AND num2 < {$matrix[0][2]}) ";
				$query .= "AND (num2 > {$matrix[4][1]} ";
				$query .= "AND num2 < {$matrix[3][1]}) ";


				print("<p>$query</p>");
			
				$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

				while($row = mysqli_fetch_array($mysqli_result))
				{
					$y = $row[num2];
					$seed6_table[$y] += $row[percent_y10];
				}

				echo "seed6_table = ";
				print_r ($seed6_table);
				echo "<p>";

				# pick top 

				$max_array = array_fill (0, 2, 0);

				for ($z = 0; $z < 2; $z++)
				{
					$maxs = array_keys($seed6_table, max($seed6_table));
					$max_array[$z] = $maxs[0];
					echo "maxs = $max_array[$z]<br>";
					$seed6_table[$maxs[0]] = 0.0;
				}

				do {
					shuffle ($max_array);
					$temp_count = count($max_array)-1;
					$rand_id = mt_rand(0,($temp_count));
					$passed = DrawCombo1($matrix,$seed_comb2=$max_array[$rand_id],$dup1,$dup2,$dup3,$mrow,$mcolumn);
					if ($passed)
					{
						$matrix[0][1] = $max_array[$rand_id];
					} else {
						echo "--0,1-- $max_array[$rand_id] failed - dup1 = $dup1 <br>";
					}
					unset ($max_array[$rand_id]);
					$max_array = array_values($max_array);
					if (count($max_array) == 0 && !$passed)
					{
						for ($z = 0; $z < 2; $z++)
						{
							$maxs = array_keys($seed6_table, max($seed6_table));
							$max_array[$z] = $maxs[0];
							echo "maxs = $max_array[$z]<br>";
							$seed6_table[$maxs[0]] = 0.0;
						}
					}
				} while (!$passed);

				# ---- 0,1 end

				# ---- 1,2
				$mrow = 1;
				$mcolumn = 2;

				$query = "SELECT * FROM $draw_prefix";
				$query .= "temp_combo2_p3";
				if ($hml == 0) {
					$query .= " ";
				} elseif ($hml == 1) {
					$query .= "_1 ";
				} elseif ($hml == 2) {
					$query .= "_2 ";
				} elseif ($hml == 3) {
					$query .= "_3 ";
				} 

				$query .= "WHERE num1 = '{$matrix[1][3]}' "; #row
				$query .= "AND (num2 > {$matrix[1][4]} ";
				$query .= "AND num2 < {$matrix[1][0]}) ";
				$query .= "AND (num2 > {$matrix[2][2]} ";
				$query .= "AND num2 < {$matrix[0][2]}) ";

				print("<p>$query</p>");
			
				$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

				$seed6_table = array_fill(0, $balls+1, 0.0);

				while($row = mysqli_fetch_array($mysqli_result))
				{
					$y = $row[num2];
					$seed6_table[$y] += $row[percent_y10];
				}

				$query = "SELECT * FROM $draw_prefix";
				$query .= "temp_combo2_p3";
				if ($hml == 0) {
					$query .= " ";
				} elseif ($hml == 1) {
					$query .= "_1 ";
				} elseif ($hml == 2) {
					$query .= "_2 ";
				} elseif ($hml == 3) {
					$query .= "_3 ";
				} 
				$query .= "WHERE num1 = '{$matrix[4][2]}' "; #col
				$query .= "AND (num2 > {$matrix[2][2]} ";
				$query .= "AND num2 < {$matrix[0][2]}) ";
				$query .= "AND (num2 > {$matrix[1][4]} ";
				$query .= "AND num2 < {$matrix[1][0]}) ";

				print("<p>$query</p>");
			
				$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

				while($row = mysqli_fetch_array($mysqli_result))
				{
					$y = $row[num2];
					$seed6_table[$y] += $row[percent_y10];
				}

				echo "seed6_table = ";
				print_r ($seed6_table);
				echo "<p>";

				# pick top 

				$max_array = array_fill (0, 2, 0);

				for ($z = 0; $z < 2; $z++)
				{
					$maxs = array_keys($seed6_table, max($seed6_table));
					$max_array[$z] = $maxs[0];
					echo "maxs = $max_array[$z]<br>";
					$seed6_table[$maxs[0]] = 0.0;
				}

				do {
					shuffle ($max_array);
					$temp_count = count($max_array)-1;
					$rand_id = mt_rand(0,($temp_count));
					$passed = DrawCombo1($matrix,$seed_comb2=$max_array[$rand_id],$dup1,$dup2,$dup3,$mrow,$mcolumn);
					if ($passed)
					{
						$matrix[1][2] = $max_array[$rand_id];
					} else {
						echo "--1,2-- $max_array[$rand_id] failed - dup1 = $dup1 <br>";
					}
					unset ($max_array[$rand_id]);
					$max_array = array_values($max_array);
					if (count($max_array) == 0 && !$passed)
					{
						for ($z = 0; $z < 2; $z++)
						{
							$maxs = array_keys($seed6_table, max($seed6_table));
							$max_array[$z] = $maxs[0];
							echo "maxs = $max_array[$z]<br>";
							$seed6_table[$maxs[0]] = 0.0;
						}
					}
				} while (!$passed);

				# ---- 1,2 end

				# ---- 3,3
				$mrow = 3;
				$mcolumn = 3;

				$query = "SELECT * FROM $draw_prefix";
				$query .= "temp_combo2_p3";
				if ($hml == 0) {
					$query .= " ";
				} elseif ($hml == 1) {
					$query .= "_1 ";
				} elseif ($hml == 2) {
					$query .= "_2 ";
				} elseif ($hml == 3) {
					$query .= "_3 ";
				} 
				$query .= "WHERE num1 = '{$matrix[3][4]}' "; #row
				$query .= "AND (num2 > {$matrix[3][0]} ";
				$query .= "AND num2 < {$matrix[3][1]}) ";
				$query .= "AND (num2 > {$matrix[0][3]} ";
				$query .= "AND num2 < {$matrix[2][3]}) ";

				print("<p>$query</p>");
			
				$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

				$seed6_table = array_fill(0, $balls+1, 0.0);

				while($row = mysqli_fetch_array($mysqli_result))
				{
					$y = $row[num2];
					$seed6_table[$y] += $row[percent_y10];
				}

				$query = "SELECT * FROM $draw_prefix";
				$query .= "temp_combo2_p3";
				if ($hml == 0) {
					$query .= " ";
				} elseif ($hml == 1) {
					$query .= "_1 ";
				} elseif ($hml == 2) {
					$query .= "_2 ";
				} elseif ($hml == 3) {
					$query .= "_3 ";
				} 
				$query .= "WHERE num1 = '{$matrix[1][3]}' "; #col
				$query .= "AND (num2 > {$matrix[3][0]} ";
				$query .= "AND num2 < {$matrix[3][1]}) ";
				$query .= "AND (num2 > {$matrix[0][3]} ";
				$query .= "AND num2 < {$matrix[2][3]}) ";

				print("<p>$query</p>");
			
				$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

				while($row = mysqli_fetch_array($mysqli_result))
				{
					$y = $row[num2];
					$seed6_table[$y] += $row[percent_y10];
				}

				echo "seed6_table = ";
				print_r ($seed6_table);
				echo "<p>";

				# pick top 

				$max_array = array_fill (0, 2, 0);

				for ($z = 0; $z < 2; $z++)
				{
					$maxs = array_keys($seed6_table, max($seed6_table));
					$max_array[$z] = $maxs[0];
					echo "maxs = $max_array[$z]<br>";
					$seed6_table[$maxs[0]] = 0.0;
				}

				do {
					shuffle ($max_array);
					$temp_count = count($max_array)-1;
					$rand_id = mt_rand(0,($temp_count));
					$passed = DrawCombo1($matrix,$seed_comb2=$max_array[$rand_id],$dup1,$dup2,$dup3,$mrow,$mcolumn);
					if ($passed)
					{
						$matrix[3][3] = $max_array[$rand_id];
					} else {
						echo "--3,3-- $max_array[$rand_id] failed - dup1 = $dup1 <br>";
					}
					unset ($max_array[$rand_id]);
					$max_array = array_values($max_array);
					if (count($max_array) == 0 && !$passed)
					{
						for ($z = 0; $z < 2; $z++)
						{
							$maxs = array_keys($seed6_table, max($seed6_table));
							$max_array[$z] = $maxs[0];
							echo "maxs = $max_array[$z]<br>";
							$seed6_table[$maxs[0]] = 0.0;
						}
					}
				} while (!$passed);

				# ---- 3,3 end

				# ---- 2,4
				$mrow = 2;
				$mcolumn = 4;

				$query = "SELECT * FROM $draw_prefix";
				$query .= "temp_combo2_p3";
				if ($hml == 0) {
					$query .= " ";
				} elseif ($hml == 1) {
					$query .= "_1 ";
				} elseif ($hml == 2) {
					$query .= "_2 ";
				} elseif ($hml == 3) {
					$query .= "_3 ";
				} 

				$query .= "WHERE num1 = '{$matrix[2][1]}' "; #row
				$query .= "AND (num2 > {$matrix[2][2]} ";
				$query .= "AND num2 < {$matrix[2][3]}) ";
				$query .= "AND (num2 > {$matrix[1][4]} ";
				$query .= "AND num2 < {$matrix[4][4]}) ";

				print("<p>$query</p>");
			
				$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

				$seed6_table = array_fill(0, $balls+1, 0.0);

				while($row = mysqli_fetch_array($mysqli_result))
				{
					$y = $row[num2];
					$seed6_table[$y] += $row[percent_y10];
				}

				$query = "SELECT * FROM $draw_prefix";
				$query .= "temp_combo2_p3";
				if ($hml == 0) {
					$query .= " ";
				} elseif ($hml == 1) {
					$query .= "_1 ";
				} elseif ($hml == 2) {
					$query .= "_2 ";
				} elseif ($hml == 3) {
					$query .= "_3 ";
				} 

				$query .= "WHERE num1 = '{$matrix[3][4]}' "; #col
				$query .= "AND (num2 > {$matrix[2][2]} ";
				$query .= "AND num2 < {$matrix[2][3]}) ";
				$query .= "AND (num2 > {$matrix[1][4]} ";
				$query .= "AND num2 < {$matrix[4][4]}) ";

				print("<p>$query</p>");
			
				$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

				while($row = mysqli_fetch_array($mysqli_result))
				{
					$y = $row[num2];
					$seed6_table[$y] += $row[percent_y10];
				}

				echo "seed6_table = ";
				print_r ($seed6_table);
				echo "<p>";

				# pick top 

				$max_array = array_fill (0, 2, 0);

				for ($z = 0; $z < 2; $z++)
				{
					$maxs = array_keys($seed6_table, max($seed6_table));
					$max_array[$z] = $maxs[0];
					echo "maxs = $max_array[$z]<br>";
					$seed6_table[$maxs[0]] = 0.0;
				}

				do {
					shuffle ($max_array);
					$temp_count = count($max_array)-1;
					$rand_id = mt_rand(0,($temp_count));
					$passed = DrawCombo1($matrix,$seed_comb2=$max_array[$rand_id],$dup1,$dup2,$dup3,$mrow,$mcolumn);
					if ($passed)
					{
						$matrix[2][4] = $max_array[$rand_id];
					} else {
						echo "--2,4-- $max_array[$rand_id] failed - dup1 = $dup1 <br>";
					}
					unset ($max_array[$rand_id]);
					$max_array = array_values($max_array);
					if (count($max_array) == 0 && !$passed)
					{
						for ($z = 0; $z < 2; $z++)
						{
							$maxs = array_keys($seed6_table, max($seed6_table));
							$max_array[$z] = $maxs[0];
							echo "maxs = $max_array[$z]<br>";
							$seed6_table[$maxs[0]] = 0.0;
						}
					}
				} while (!$passed);

				# ---- 2,4 end
			} else {
				# ---- 
				$query = "SELECT * FROM $draw_prefix";
				$query .= "temp_combo2_p3";
				if ($hml == 0) {
					$query .= " ";
				} elseif ($hml == 1) {
					$query .= "_1 ";
				} elseif ($hml == 2) {
					$query .= "_2 ";
				} elseif ($hml == 3) {
					$query .= "_3 ";
				} 
				$query .= "WHERE num1 = '$col1' "; 

				print("<p>$query</p>");
			
				$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));
			}

			print_matrix ($matrix,$balls_drawn);
		}
	}	

	function print_matrix ($matrix,$balls_drawn)
	{
		# start table
		print("<table border=\"1\">\n");
	
		# create header row
		print("<tr>\n");
		print("<td>&nbsp;</td>\n");
		
		for ($index = 0; $index < $balls_drawn; $index++)
		{
			print("<td align=\"center\"><b>$index</b></td>\n");
		}

		//print rows
		for ($x = $balls_drawn-1; $x >= 0; $x--)
		{
			print("<tr>\n");
			print("<td><b>$x</b></td>\n");
			
			for ($y = 0; $y < $balls_drawn; $y++)
			{
				print("<td align=\"center\">{$matrix[$x][$y]}</td>\n");
			}

			print("</tr>\n");
		}

		//create footer row 1
		print("<tr>\n");
		print("<td>&nbsp;</td>\n");
		for ($index = 0; $index < $balls_drawn; $index++)
		{
			print("<td align=\"center\"><b>$index</b></td>\n");
		}

		print("</tr>\n");
		
		//end table
		print("</TABLE>\n");

	}

	function Count2Seq($draw)
	{
		$seq2 = 0;

		echo "Count2Seq draw = ";
		print_r($draw);
		echo "<br>";

		sort($draw);
		
		for ($x = 0 ; $x <= count($draw)-2; $x++)
		{
			$num1 = $draw[$x];
			$num2 = $draw[$x+1]-1;
			if ($num1 == $num2 && $num1 != 0 && $num2 != 0)
			{
				$seq2++;
			}
		}

		return $seq2;
	}

	function Count3Seq($draw)
	{
		$seq3 = 0;

		sort($draw);
		
		for ($count = 0 ; $count <= count($draw)-3; $count++)
		{
			$num1 = $draw[$count];
			$num2 = $draw[$count+1]-1;
			$num3 = $draw[$count+2]-2;
			if ($num1 == $num2 && $num2 == $num3 && $num1 != 0 && $num2 != 0  && $num3 != 0)
			{
				$seq3++;
			}
		}

		return $seq3;
	}

	function TestRank($matrix,$mrow,$mcolumn)
	{
		global $rank_count,$rank_limit;

		# rank row
		#echo "------------------------------ TestRank ------------------------------<br>";	
		#echo "------------------------------ mrow = $mrow ------------------------------<br>";	
		#echo "------------------------------ mcolumn = $mcolumn ------------------------------<br>";	
		#echo "rank_limit = ";
		#print_r ($rank_limit);
		#echo "<br>";

		$rank_table_count = array_fill (0, 7, 0);

		for ($b = 0; $b <= 4; $b++)
		{
			#echo "TestRank(a) - b = $b<br>";
			#echo "TestRank(a) - matrix[$mrow][$b] = {$matrix[$mrow][$b]}<br>";
			if ($matrix[$mrow][$b] != 0)
			{
				#echo "TestRank(a) - b = $b; matrix[$mrow][$b] = {$matrix[$mrow][$b]}<br>";
				if ($rank_count[$matrix[$mrow][$b]] >= 6)
				{
					$rank_table_count[6]++;
					#echo "<b>TestRank(a) - 6++ - {$matrix[$mrow][$b]}</b><br>";
				} else {
					$rank_table_count[$rank_count[$matrix[$mrow][$b]]]++;
					#echo "<b>TestRank(a) - rank_count[{$matrix[$mrow][$b]}] = {$rank_count[$matrix[$mrow][$b]]} - matrix[$mrow][$b] = {$matrix[$mrow][$b]}</b><br>";
				}
			}
		}

		#echo "TestRank(a) - rank_table_count(a) = ";
		#print_r ($rank_table_count);
		#echo "<br>";

		for ($x = 0; $x <= 6; $x++)
		{
			if ($rank_table_count[$x] > $rank_limit[$x])
			{
				echo "TestRank(a) rejected - rank_table_count[$x] > rank_limit[$x], $rank_table_count[$x] > $rank_limit[$x]<br>";
				return 0;
			}
		}

		# rank column

		$rank_table_count = array_fill (0, 7, 0);

		for ($b = 0; $b <= 4; $b++)
		{
			#echo "TestRank(b) - b = $b<br>";
			#echo "TestRank(b) - b = $b; matrix[$b][$mcolumn] = {$matrix[$b][$mcolumn]}<br>";
			if ($matrix[$b][$mcolumn] != 0)
			{
				if ($rank_count[$matrix[$b][$mcolumn]] >= 6)
				{
					$rank_table_count[6]++;
					#echo "<b>TestRank(b) - 6++ - {$matrix[$b][$mcolumn]}</b><br>";
				} else {
					$rank_table_count[$rank_count[$matrix[$b][$mcolumn]]]++;
					#echo "<b>TestRank(b) - rank_count[{$matrix[$b][$mcolumn]}] = {$rank_count[$matrix[$b][$mcolumn]]} - matrix[$b][$mcolumn]} = {$matrix[$b][$mcolumn]}</b><br>";
				}
			}
		}

		#echo "TestRank(b) - rank_table_count(b) = ";
		#print_r ($rank_table_count);
		#echo "<br>";

		for ($x = 0; $x <= 6; $x++)
		{
			#echo "TestRank(c) - x = $x<br>";
			if ($rank_table_count[$x] > $rank_limit[$x])
			{
				echo "TestRank(b) rejected -  rank_table_count[$x] > rank_limit[$x], $rank_table_count[$x] > $rank_limit[$x]<br>";
				return 0;
			}
		}

		return 1;
	}

	function DrawSeed1($seed_col1,$dup1)
	{
		global $debug, $game, $game_name, $balls, $balls_drawn, $mega_balls, $draw_table_name, $draw_prefix, $game_includes, $rank_count, $rank_limit;
	
		require ("includes/mysqli.php");

		echo "<b>----------- $seed_col1 -----------------</b><br>";

		$count = 0;

		$query = "SELECT * FROM $draw_table_name ";
		$query .= "ORDER BY DATE DESC ";
		$query .= "LIMIT 1 ";
	
		$mysqli_result3 = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

		$row1 = mysqli_fetch_array($mysqli_result3);

		# dup

		for ($x = 1 ; $x <= $balls_drawn; $x++)
		{
			if ($seed_col1 == $row1[$x])
			{
				$count++;
			}
		}

		if ($count > $dup1)
		{
			echo "DrawSeed1 - count = $count, dup1 = $dup1<p>";
			return 0;
		} 

		# rank

		$rank_seed = $rank_count[$seed_col1];

		if ($rank_seed > 6)

		{
			$rank_seed = 6;
		}

		#echo "<b>$seed_col1</b> -- rank_seed = $rank_seed<br>";

		#echo "rank_limit = $rank_limit[$rank_seed]<br>";

		if ($rank_limit[$rank_seed] == 0)
		{
			echo "DrawSeed1 - rank failed - rank_limit[$rank_seed] = $rank_limit[$rank_seed]<p>";
			return 0;
		} 

		return 1;
	}
	
	function DrawCombo1($matrix,$seed_comb2,$dup1,$dup2,$dup3,$mrow,$mcolumn)
	{
		global $debug, $game, $game_name, $balls, $balls_drawn, $mega_balls, $draw_table_name, $draw_prefix, $game_includes, $seq2_limit, $seq3_limit,
			$mod2_limit, $mod3_limit, $combin2, $combin3, $combin4, $combin5;
	
		require ("includes/mysqli.php");

		$passed = 1;
		$count1 = 0;
		$count2 = 0;

		echo "<b>----------- $seed_comb2 -----------------</b><br>";

		if ($seed_comb2 == 0)
		{
			die ("<b>seed_comb2 = $seed_comb2 - FAILED</b>");
		}

		$matrix[$mrow][$mcolumn] = $seed_comb2;

		echo "<b>-------------------------------</b><br>";
		echo "<b>mrow = $mrow</b><br>";
		echo "<b>mcolumn = $mcolumn</b><br>";
		echo "<b>-------------------------------</b><br>";

		for ($x = 1; $x <= 3; $x++)
		{
			${last_.$x._draws} = LastDraws(date("Y-m-d"),$x);
			echo "last_.$x._draws = ";
			print_r (${last_.$x._draws});
			echo "<br>";
		}

		$query = "SELECT * FROM $draw_table_name ";
		$query .= "ORDER BY DATE DESC ";
		$query .= "LIMIT 2 ";
	
		$mysqli_result3 = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

		# dup1 seed_comb2

		$row1 = mysqli_fetch_array($mysqli_result3);

		/*
		for ($x = 1 ; $x <= $balls_drawn; $x++)
		{
			if ($seed_comb2 == $row1[$x])
			{
				$count1++;
				#echo "seed_comb2 count1 = $count1, dup1 = $dup1<br>";
			}
		}

		if ($count1 != $dup1)
		{
			echo "rejected seed_comb2 = $seed_comb2 - count1 != dup1, count1 = $count1 ; dup1 = $dup1<br>";
			return 0;
		} 
		*/

		# --------------------------------------- row ----------------------------------------------------------------------

		# dup row

		$count1 = 0;
		$draw1 = array ();

		for ($b = 0; $b <= 4; $b++)
		{
			array_push ($draw1, $matrix[$mrow][$b]);
		}

		echo "draw1 = ";
		print_r ($draw1);
		echo "<br>";	

		//count repeating numbers 1
		for ($y = 0 ; $y < $balls_drawn; $y++)
		{	
			if (array_search($draw1[$y], $last_1_draws) !== FALSE)
			{
				$count1++;
				echo "(row) last_1_draws - count1 = $count1<br>";
			}
		}

		if ($count1 != $dup1)
		{
			echo "(row) rejected - count1 != dup1, count1 = $count1 ; dup1 = $dup1<br>";
			return 0;
		}

		$count2 = 0;

		# count repeating numbers 2
		for ($y = 0 ; $y < $balls_drawn; $y++)
		{	
			if (array_search($draw1[$y], $last_2_draws) !== FALSE)
			{
				$count2++;
				echo "(row) number $draw1[$y] - last_2_draws - count2 = $count2<br>";
			}
		}

		if ($count2 != $dup2)
		{
			echo "(row) rejected - count2 != dup2, count2 = $count2 ; dup2 = $dup2<br>";
			return 0;
		}

		$seq2_count = Count2Seq($draw1);	
		echo "(row) (a)seq2_limit = $seq2_limit<br>";

		$seq3_count = Count3Seq($draw1);	
		echo "(row) (a)seq3_limit = $seq3_limit<br>";

		if ($seq2_count > $seq2_limit && $seq3_count > $seq3_limit)
		{
			echo "(row) rejected - (a)seq2_count > seq2_limit, seq2_count = $seq2_count ; seq2_limit = $seq2_limit<br>";
			echo "(row) rejected - (a)seq3_count > seq3_limit, seq3_count = $seq3_count ; seq3_limit = $seq3_limit<br>";
			return 0;
		}

		# unique
		sort ($draw1);

		for ($v = 0; $v <= 3; $v++)
		{
			if ($draw1[$v] == $draw1[$v+1] && $draw1[$v] != 0)
			{
				echo "(row) $draw1[$v] - reject unique<br>";
				return 0;
			}
		}

		# even/odd
		$even = 0;
		$odd = 0;
		$d_count = 0;
		$num_range_d501 = 0;
		$num_range_d502 = 0;
		$temp_half = intval($balls/2);
		
		foreach ($draw1 as $val) 
		{ 
			if(!is_int($val/2)) 
			{ 
				$odd++; 
			} 
			else 
			{ 
				$even++; 
			}

			if ($val <= $temp_half) { 
				$num_range_d501++;
			}
			else {
				$num_range_d502++;
			}

			if ($val)
			{
				$d_count++;
			}
		}

		if ($d_count == $balls_drawn)
		{
			if ($even == 0 || $even == 5 || $odd == 0 || $odd == 5)
			{
				echo "(row) rejected - even/odd, even = $even :: odd = $odd<br>";
				return 0;
			} elseif ($num_range_d501 == 0 || $num_range_d501 == 5 || $num_range_d502 == 0 || $num_range_d502 == 5) {
				echo "(row) rejected - d501/d502, d501 = $num_range_d501 :: d502 = $num_range_d502<br>";
				return 0;
			}
		}

		# mod2/3 - row
		$mod = array_fill (0, 10, 0);

		$mod2_count = 0;
		$mod3_count = 0;

		# build mod, draw count and d501/d502
		for($m = 0; $m < $balls_drawn; $m++)
		{	
			$number = $draw1[$m];

			if ($number < 10 && $number != 0) {
				$num_range[0]++;
				$mod[$number]++;
				echo "mod[0]++ - $number<br>";
			}
			elseif ($number > 9 && $number < 20  && $number != 0) {
				$num_range[1]++;
				$y = $number - 10;
				$mod[$y]++;
				echo "mod[1]++ - $number<br>";
			}
			elseif ($number > 19 && $number < 30 && $number != 0) {
				$num_range[2]++;
				$y = $number - 20;
				$mod[$y]++;
				echo "mod[2]++ - $number<br>";
			}
			elseif ($number > 29 && $number < 40 && $number != 0) {
				$num_range[3]++;
				$y = $number - 30;
				$mod[$y]++;
				echo "mod[3]++ - $number<br>";
			}
			elseif ($number > 39 && $number < 50 && $number != 0) {
				$num_range[4]++;
				$y = $number - 40;
				$mod[$y]++;
				echo "mod[4]++ - $number<br>";
			}
			elseif ($number != 0) {
				$num_range[5]++;
				$y = $number - 50;
				$mod[$y]++;
				echo "mod[5]++ - $number<br>";
			}

			$temp = intval($balls/2);

			if ($number <= $temp) { 
				$num_range_d501++;
			}
			else {
				$num_range_d502++;
			}
		}

		for ($x = 0; $x <= 9; $x++)
		{
			if ($mod[$x] > 1)
			{
				$mod2_count++;
			}
			if ($mod[$x] > 2)
			{
				$mod3_count++; 
			}
		}

		echo "(row) mod = ";
		print_r ($mod);
		echo "<br>";

		echo "(row) mod2_count = $mod2_count<br>";
		echo "(row) mod2_limit = $mod2_limit<br>";

		if ($mod2_count > $mod2_limit)
		{
			echo "(row) mod2 failed<br>";
			return 0;
		} elseif ($mod3_count > $mod3_limit) {
			echo "(row) mod3 failed<br>";
			return 0;
		}

		# combin
		if ($d_count == 3)
		{
			$total_combin = array_fill (0,7,0);

			$draw_0 = array(0,$draw1[0],$draw1[1],$draw1[2],$draw1[3],$draw1[4]);
			
			$total_combin = test_combin($draw_0);

			echo "(row 3) total_combin = ";
			print_r ($total_combin);
			echo "<br>";

			if ($total_combin[3] != 1) {
				echo "(row 3) total_combin[3] failed = $total_combin[3]<br>";
				return 0;
			} elseif ($total_combin[2] != 3) {
				echo "(row 3) total_combin[2] failed = $total_combin[2]<br>";
				return 0;
			} 
		}

		if ($d_count == $balls_drawn)
		{
			$total_combin = array_fill (0,7,0);

			$draw_0 = array(0,$draw1[0],$draw1[1],$draw1[2],$draw1[3],$draw1[4]);
			
			$total_combin = test_combin($draw_0);

			echo "(row 5) total_combin = ";
			print_r ($total_combin);
			echo "<br>";

			if ($total_combin[5] > 0) {
				echo "(row 5) total_combin[5] failed = $total_combin[5]<br>";
				return 0;
			} elseif ($total_combin[4] < 1 || $total_combin[4] > $combin4) {
				echo "(row 5) total_combin[4] failed > $total_combin[4]<br>";
				return 0;
			} elseif ($total_combin[3] != $combin3) {
				echo "(row 5) total_combin[3] failed = $total_combin[3]<br>";
				return 0;
			} elseif ($total_combin[2] != $combin2) {
				echo "(row 5) total_combin[2] failed = $total_combin[2]<br>";
				return 0;
			} 
		}

		#--------------------------------------- column ----------------------------------------------------------------------

		# dup column

		$count1 = 0;
		$draw2 = array ();

		for ($b = 0; $b <= 4; $b++)
		{
			array_push ($draw2, $matrix[$b][$mcolumn]);
		}

		echo "draw2 = ";
		print_r ($draw2);
		echo "<br>";	

		//count repeating numbers 1
		for ($y = 0 ; $y < $balls_drawn; $y++)
		{	
			if (array_search($draw2[$y], $last_1_draws) !== FALSE)
			{
				$count1++;
				echo "(column) last_1_draws - count1 = $count1<br>";
			}
		}

		if ($count1 != $dup1)
		{
			echo "(column) rejected - count1 != dup1, count1 = $count1 ; dup1 = $dup1<br>";
			return 0;
		}

		$count2 = 0;

		//count repeating numbers 2
		for ($y = 0 ; $y < $balls_drawn; $y++)
		{	
			if (array_search($draw2[$y], $last_2_draws) !== FALSE)
			{
				$count2++;
				echo "(column) ($draw2[$y]) last_2_draws - count2 = $count2<br>";
			}
		}

		if ($count2 != $dup2)
		{
			echo "(column) rejected - count2 != dup2, count2 = $count2 ; dup2 = $dup2<br>";
			return 0;
		}

		$seq2_count = Count2Seq($draw2);	
		echo "(column) (b)seq2_limit = $seq2_limit<br>";

		$seq3_count = Count3Seq($draw2);	
		echo "(column) (b)seq3_limit = $seq3_limit<br>";

		if ($seq2_count > $seq2_limit && $seq3_count > $seq3_limit)
		{
			echo "(column) rejected - (b)seq2_count > seq2_limit, seq2_count = $seq2_count ; seq2_limit = $seq2_limit<br>";
			echo "(column) rejected - (b)seq3_count > seq3_limit, seq3_count = $seq3_count ; seq3_limit = $seq3_limit<br>";
			return 0;
		}

		# rank
		$rank_flag = TestRank($matrix,$mrow,$mcolumn);

		if (!$rank_flag)
		{
			echo "rank failed<br>";
			return 0;
		}

		# unique
		sort ($draw2);

		for ($v = 0; $v <= 3; $v++)
		{
			if ($draw2[$v] == $draw2[$v+1] && $draw2[$v] != 0)
			{
				echo "(column) $draw2[$v] - reject non-unique numbers in draw<br>";
				return 0;
			}
		}

		# even/odd
		$even = 0;
		$odd = 0;
		$d_count = 0;
		$num_range_d501 = 0;
		$num_range_d502 = 0;
		$temp_half = intval($balls/2);
		
		foreach ($draw2 as $val) 
		{ 
			if(!is_int($val/2)) 
			{ 
				$odd++; 
			} 
			else 
			{ 
				$even++; 
			}

			if ($val <= $temp_half) { 
				$num_range_d501++;
			}
			else {
				$num_range_d502++;
			}

			if ($val)
			{
				$d_count++;
			}
		}

		if ($d_count == $balls_drawn)
		{
			if ($even == 0 || $even == 5 || $odd == 0 || $odd == 5)
			{
				echo "(column) rejected - even/odd, even = $even :: odd = $odd<br>";
				return 0;
			} elseif ($num_range_d501 == 0 || $num_range_d501 == 5 || $num_range_d502 == 0 || $num_range_d502 == 5) {
				echo "(column) rejected - d501/d502, d501 = $num_range_d501 :: d502 = $num_range_d502<br>";
				return 0;
			}
		}
		
		# mod2/3 - column
		$mod = array_fill (0, 10, 0);

		$mod2_count = 0;
		$mod3_count = 0;

		// build mod, draw count and d501/d502
		for($m = 0; $m < $balls_drawn; $m++)
		{	
			$number = $draw2[$m];

			if ($number < 10 && $number != 0) {
				$num_range[0]++;
				$mod[$number]++;
				echo "mod[0]++ - $number<br>";
			}
			elseif ($number > 9 && $number < 20  && $number != 0) {
				$num_range[1]++;
				$y = $number - 10;
				$mod[$y]++;
				echo "mod[1]++ - $number<br>";
			}
			elseif ($number > 19 && $number < 30 && $number != 0) {
				$num_range[2]++;
				$y = $number - 20;
				$mod[$y]++;
				echo "mod[2]++ - $number<br>";
			}
			elseif ($number > 29 && $number < 40 && $number != 0) {
				$num_range[3]++;
				$y = $number - 30;
				$mod[$y]++;
				echo "mod[3]++ - $number<br>";
			}
			elseif ($number > 39 && $number < 50 && $number != 0) {
				$num_range[4]++;
				$y = $number - 40;
				$mod[$y]++;
			}
			elseif ($number != 0) {
				$num_range[5]++;
				$y = $number - 50;
				$mod[$y]++;
			}

			$temp = intval($balls/2);

			if ($number <= $temp) { 
				$num_range_d501++;
			}
			else {
				$num_range_d502++;
			}
		}

		for ($x = 0; $x <= 9; $x++)
		{
			if ($mod[$x] > 1)
			{
				$mod2_count++;
			}
			if ($mod[$x] > 2)
			{
				$mod3_count++; 
			}
		}

		echo "(column) mod = ";
		print_r ($mod);
		echo "<br>";

		echo "(column) mod2_count = $mod2_count<br>";
		echo "(column) mod2_limit = $mod2_limit<br>";

		if ($mod2_count > $mod2_limit)
		{
			echo "(column) mod2 failed<br>";
			return 0;
		} elseif ($mod3_count > $mod3_limit) {
			echo "(column) mod3 failed<br>";
			return 0;
		}

		# combin
		if ($d_count == 3)
		{
			$total_combin = array_fill (0,7,0);

			$draw_0 = array(0,$draw2[0],$draw2[1],$draw2[2],$draw2[3],$draw2[4]);
			
			$total_combin = test_combin($draw_0);

			echo "(column 3) total_combin = ";
			print_r ($total_combin);
			echo "<br>";

			if ($total_combin[3] != 1) {
				echo "(column 3) total_combin[3] failed = $total_combin[3]<br>";
				return 0;
			} elseif ($total_combin[2] != 3) {
				echo "(column 3) total_combin[2] failed = $total_combin[2]<br>";
				return 0;
			} 
		}

		if ($d_count == $balls_drawn)
		{
			$total_combin = array_fill (0,7,0);

			$draw_0 = array(0,$draw2[0],$draw2[1],$draw2[2],$draw2[3],$draw2[4]);
			
			$total_combin = test_combin($draw_0);

			echo "(column 5) total_combin = ";
			print_r ($total_combin);
			echo "<br>";

			if ($total_combin[5] > 0) {
				echo "(column 5) total_combin[5] failed = $total_combin[5]<br>";
				return 0;
			} elseif ($total_combin[4] < 1 || $total_combin[4] > $combin4) {
				echo "(column 5) total_combin[4] failed > $total_combin[4]<br>";
				return 0;
			} elseif ($total_combin[3] != $combin3) {
				echo "(column 5) total_combin[3] failed = $total_combin[3]<br>";
				return 0;
			} elseif ($total_combin[2] != $combin2) {
				echo "(column 5) total_combin[2] failed = $total_combin[2]<br>";
				return 0;
			} 
		}
		
		# sumeo50

		
		return 1;
	}

	function Fill($draw)
	{
		global $debug, $game, $game_name, $balls, $balls_drawn, $mega_balls, $draw_table_name, $draw_prefix, $game_includes;
	
		require ("includes/mysqli.php");

	}

	function DrawTest($draw)
	{
		global $debug, $game, $game_name, $balls, $balls_drawn, $mega_balls, $draw_table_name, $draw_prefix, $game_includes;
	
		require ("includes/mysqli.php");

		$passed = 1;

		#--------------- combin ----------------------
		/*

		$total_combin = array_fill (0,7,0);

		$draw_0 = array(0,$draw[0],$draw[1],$draw[2],$draw[3],$draw[4]);
		
		$total_combin = test_combin($draw_0);

		if ($test_combin[5] > 0) {
			return 0;
		} elseif ($test_combin[4] != $combin4) {
			return 0;
		} elseif ($test_combin[3] != $combin3) {
			return 0;
		} elseif ($test_combin[2] != $combin2) {
			return 0;
		} 
		*/
		
		# sum

		# sumeo50 - set sum (use limit?)

		# mod

		# modx

		# draw (test)

		# stats?

		# combo - set table
		#--------------- combin ----------------------
		/*

		$total_combin = array_fill (0,7,0);

		$draw_0 = array(0,$draw[0],$draw[1],$draw[2],$draw[3],$draw[4]);
		
		$total_combin = test_combin($draw_0);

		if ($test_combin[5] > 0) {
			return 0;
		} elseif ($test_combin[4] != $combin4) {
			return 0;
		} elseif ($test_combin[3] != $combin3) {
			return 0;
		} elseif ($test_combin[2] != $combin2) {
			return 0;
		} 
		*/

		return $passed;
	}
	
	//start HTML page
	print("<HTML>\n");
	print("<HEAD>\n");
	print("<TITLE>$game_name Matrix - $game_name</TITLE>\n");
	print("</HEAD>\n");
	
	print("<BODY>\n");

	// build rank table - last 26

	$rank_count = array (0);

	$rank_count = BuildRankTable(date("Y-m-d")); // array 0..balls with total draws for last 26

	print_r ($rank_count);

	echo "<p>";
	print_r ($rank_limit);

	$hml = 0;
	#$hml = 1;		#= high
	#$hml = 2;		#= medium
	#$hml = 3;		#= low

	echo "<p>hml = $hml<br>";

	/* add rank function for hml
	switch ($hml)
	{
	case '0':
		$rank_limit = array (1,2,2,2,2,1,1);
		#                    0,1,2,3,4,5,6
		break;
	case '1':
		$rank_limit = array (0,1,2,3,1,1,2);
		#                    0,1,2,3,4,5,6
		break;
	case '2':
		$rank_limit = array (1,2,2,2,2,1,1);
		#                    0,1,2,3,4,5,6
		break;
	case '3':
		$rank_limit = array (1,2,2,2,2,1,1);
		#                    0,1,2,3,4,5,6
		break;
	default:
		die ("hml error - matrix.php")
	}
	*/

	$rank_limit = array (0,1,2,3,2,1,1);
	#                    0,1,2,3,4,5,6

	$seq2_limit = 1;
	$seq3_limit = 0;
	$mod2_limit = 1;
	$mod3_limit = 0;

	$combin2 = 10;
	$combin3 = 10;
	$combin4 = 2; # 2 ---------------------
	$combin5 = 0;

	lot_matrix ($col1=0,$dup1=0,$dup2=0,$dup3=0,$limit=1,$delete_temp=0);
	
	print("</BODY>\n");
?>
