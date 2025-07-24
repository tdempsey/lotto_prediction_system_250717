<?php

	// Game ----------------------------- Game
	//$game = 1; // Georgia Fantasy 5
	#$game = 2; // Mega Millions
	//$game = 3; // Georgia Lotto South
	$game = 4; // Florida Fantasy 5
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

	# cross hatch
	
	require ("includes/games_switch.incl");

	//require ("includes/mysqli.php");
	require ("includes/db.class");
	require ("includes/even_odd.php");
	require ("includes/calculate_50_50.php");
	require ("includes/build_rank_table.php");
	require ("includes/calculate_draw.php"); 
	require ("includes/calculate_rank.php");
	require ("includes/first_draw_unix.php");
	require ("includes/last_draw_unix.php"); 
	require ("includes/next_draw.php");
	#require ("includes_ga_f5/calc_matrix_filters.incl");
	
	$debug = 0;

	function lot_matrix ($col1,$limit,$delete_temp)
	{ 
		global $debug, $game, $game_name, $balls, $balls_drawn, $mega_balls, $draw_table_name, $draw_prefix, $game_includes;
	
		require ("includes/mysqli.php");
		require ("$game_includes/config.incl");

		$curr_date = Date('Y-m-d');
		$sum_fail = 0;
		$sum_total = 0;
		$sum_array = array();

		$temp = array_fill(0,6,0);
		$matrix = array_fill(0,6,$temp);
		$matrix2 = array_fill(0,6,$temp);

		#5 012345
		#4 012345
		#3 012345
		#2 012345
		#1 012345
		#0 012345

		$row0 = array (1,4,5,3,2);
		$row1 = array (5,2,4,1,3);
		$row2 = array (2,1,3,5,4);
		$row3 = array (3,5,2,4,1);
		$row4 = array (4,3,1,2,5);

		$row2_0 = array (1,4,5,3,2);
		$row2_1 = array (5,2,4,1,3);
		$row2_2 = array (2,1,3,5,4);
		$row2_3 = array (3,5,2,4,1);
		$row2_4 = array (4,3,1,2,5);

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
		
		print("<h2>Lotto Matrix 3 Cross Hatch - $game_name - Limit $limit - Col1 = $col1</h2>\n");

		# --01-- delete temp tables
		if ($delete_temp)
		{
			$sql = "TRUNCATE TABLE `ga_f5_2_53_save`";
			$mysqli_result2 = mysqli_query($sql, $mysqli_link) or die (mysqli_error($mysqli_link));

			$sql = "TRUNCATE TABLE `ga_f5_3_53_save`";
			$mysqli_result3 = mysqli_query($sql, $mysqli_link) or die (mysqli_error($mysqli_link));

			$sql = "TRUNCATE TABLE `ga_f5_4_53_save`";
			$mysqli_result4 = mysqli_query($sql, $mysqli_link) or die (mysqli_error($mysqli_link));

			$sql = "CREATE TABLE IF NOT EXISTS `ga_f5_draws_matrix_cross_hatch_$curr_date` (
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
					`";
			
			$mysqli_result5 = mysqli_query($sql, $mysqli_link) or die (mysqli_error($mysqli_link));
		}
		
		for ($m = 1; $m <= $limit; $m++)
		{
			echo "<hr>";
			echo "<b>Seed 2 - col1 - check mod/seq</b><p>";

			# --02-- seed col1 - 0,0/
			#      - all same num or pull 5/6 from table [1..10] - exclude previous on multi

			if ($col1 == 0)
			{
				$query = "SELECT * FROM $draw_prefix";
				$query .= "column_1 ";
				#$query .= "WHERE num <= 10 "; # 11
				$query .= "ORDER BY percent_wa DESC ";
				$query .= "LIMIT 5 "; # 6

				echo "$query<p>";
			
				$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

				$col1_table = array();
				$y = 0;

				while($row = mysqli_fetch_array($mysqli_result))
				{
					$col1_table[$y++] = $row[num];
				}

				shuffle ($col1_table);

				echo "col1_table = ";
				print_r ($col1_table);
				echo "<p>";

				/*
				echo "<b>OVERRIDE</b><br> ";
				$col1_table = array (2,5,1,6,4);
				shuffle ($col1_table);
				echo "col1_table = ";
				print_r ($col1_table);
				echo "<p>";
				*/

				for ($a = 0; $a <= 4; $a++)
				{
					for ($b = 0; $b <= 4; $b++)
					{
						if (${row.$a}[$b] == 1)
						{
							$temp_count = count($col1_table)-1;
							$rand_id = mt_rand(0,($temp_count));
							echo "rand_id = $rand_id<br>";
							$matrix[$a][$b] = $col1_table[$rand_id];
							unset ($col1_table[$rand_id]);
							$col1_table = array_values($col1_table);
							break;
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
			
			# --03-- row0 --------------------------------------------------------------------------------------

			echo "<hr>";
			echo "<b>Seed 3a - row0</b><p>";
			
			# calculate matrix filters
			#calc_matrix_filters ($matrix[0][0]);
			$col1 = $matrix[0][0];
			require ("includes_ga_f5/calc_matrix_filters.incl");

			$query = "SELECT * FROM $draw_prefix";
			$query .= "filter_b_";
			if ($matrix[0][0] > 9)
			{
				$query .= "{$matrix[0][0]} ";
			} else {
				$query .= "0{$matrix[0][0]} ";
			}
			$query .= "WHERE b2 > {$matrix[0][0]} ";
			$query .= "AND b2 > {$matrix[3][4]} ";
			$query .= "AND b3 > {$matrix[1][3]} ";
			$query .= "AND b3 > {$matrix[3][4]} ";
			$query .= "AND b4 > {$matrix[2][1]} ";
			$query .= "AND b5 > {$matrix[4][2]} ";
			$query .= "AND rank0 <= 1 ";
			$query .= "AND rank1 <= 1 ";
			$query .= "AND rank2 <= 2 ";
			$query .= "AND rank3 <= 2 ";
			$query .= "AND rank4 <= 2 ";
			$query .= "AND rank5 <= 1 ";
			$query .= "AND rank6 <= 1 ";

			for ($c = 2; $c <= 5; $c++)
			{
				$temp_low = ${"row_limit_ba_" . $c}[low];
				$temp_high = ${"row_limit_ba_" . $c}[high];
				$query .= "AND b$c >= $temp_low ";
				$query .= "AND b$c <= $temp_high ";
			}

			for ($c = 0; $c <= 3; $c++)
			{
				$temp_low = ${"row_limit_dr_" . $c}[low];
				$temp_high = ${"row_limit_dr_" . $c}[high];
				$query .= "AND d$c >= $temp_low ";
				$query .= "AND d$c <= $temp_high ";
			}

			$query .= "AND ((dup1 = '0' AND dup2 = '1' AND dup3 = '1') ";
			$query .= "OR (dup1 = '1' AND dup2 = '1' AND dup3 = '1') ";
			$query .= "OR (dup1 = '1' AND dup2 = '1' AND dup3 = '2') ";
			#$query .= "OR (dup1 = '1' AND dup2 = '2' AND dup3 = '2') ";
			$query .= "OR (dup1 = '1' AND dup2 = '2' AND dup3 = '3')) ";
			$query .= "AND passed_wheel = '1' ";
			$query .= "AND comb2 = '10' ";
			$query .= "AND comb3 = '10' ";
			$query .= "AND comb4 <= '1' ";
			$query .= "AND comb5 = '0' ";
			$query .= "ORDER BY RAND() ";

			print("<p>$query</p>");
		
			$mysqli_result_r0 = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

			$num_rows_r0 = mysqli_num_rows($mysqli_result_r0);

			print("<p>num_rows_r0 = $num_rows_r0</p>");

			if ($num_rows_r0)
			{
				$pass_pairs = 0;

				do 
				{
					$row0 = mysqli_fetch_array($mysqli_result_r0);

					$pass_pairs = test_pairs($row0);

					if ($pass_pairs)
					{
						$pass_single_pair = test_single_pair ($matrix[4][2],$row0[b5]);

						if ($pass_single_pair)
						{
							$pass_pairs = 1;

							$matrix[0][4] = $row0[b2];
							$matrix[0][3] = $row0[b3];
							$matrix[0][1] = $row0[b4];
							$matrix[0][2] = $row0[b5];
						} else {
							echo "failed pair - {$matrix[4][2]},{$row0[b5]} [b5]<p>";
							$pass_pairs = 0;
						}
					} else {
						echo "failed - $row0[b1] - $row0[b2] - $row0[b3] - $row0[b4]- $row0[b5]<p>";
					}
				} while (!$pass_pairs);
				
			} else {
				echo ("No rows - seed 3a");
				require ("includes_ga_f5/matrix_3a.incl");
			}

			echo "$row0[b1] - $row0[b2] - $row0[b3] - $row0[b4]- $row0[b5] / dup $row0[dup1],$row0[dup2],$row0[dup3] <p>";

			print_matrix ($matrix,$balls_drawn);

			# ---- end row0 ----

			# --03-- col2 --------------------------------------------------------------------------------------

			echo "<hr>";
			echo "<b>Seed 3b - col2</b><p>";

			$col1 = $matrix[4][2];
			require ("includes_ga_f5/calc_matrix_filters.incl");

			$query = "SELECT * FROM $draw_prefix";
			$query .= "filter_b_";
			if ($matrix[4][2] > 9)
			{
				$query .= "{$matrix[4][2]} ";
			} else {
				$query .= "0{$matrix[4][2]} ";
			}
			$query .= "WHERE b2 > {$matrix[3][4]} ";
			$query .= "AND b3 > {$matrix[2][1]} ";
			$query .= "AND b4 > {$matrix[1][3]} ";
			$query .= "AND b5 = {$matrix[0][2]} ";
			$query .= "AND b2 >= $row_limit_ba_2[low] ";
			$query .= "AND b2 <= $row_limit_ba_2[high] ";
			$query .= "AND b3 >= $row_limit_ba_3[low] ";
			$query .= "AND b3 <= $row_limit_ba_3[high] ";
			$query .= "AND b4 >= $row_limit_ba_4[low] ";
			$query .= "AND b4 <= $row_limit_ba_4[high] ";
			$query .= "AND rank0 <= 1 ";
				$query .= "AND rank1 <= 1 ";
				$query .= "AND rank2 <= 2 ";
				$query .= "AND rank3 <= 2 ";
				$query .= "AND rank4 <= 2 ";
				$query .= "AND rank5 <= 1 ";
				$query .= "AND rank6 <= 1 ";

			for ($c = 2; $c <= 5; $c++)
			{
				$temp_low = ${"row_limit_ba_" . $c}[low];
				$temp_high = ${"row_limit_ba_" . $c}[high];
				$query .= "AND b$c >= $temp_low ";
				$query .= "AND b$c <= $temp_high ";
			}

			for ($c = 0; $c <= 3; $c++)
			{
				$temp_low = ${"row_limit_dr_" . $c}[low];
				$temp_high = ${"row_limit_dr_" . $c}[high];
				$query .= "AND d$c >= $temp_low ";
				$query .= "AND d$c <= $temp_high ";
			}
			$query .= "AND ((dup1 = '0' AND dup2 = '1' AND dup3 = '1') ";
			$query .= "OR (dup1 = '1' AND dup2 = '1' AND dup3 = '1') ";
			$query .= "OR (dup1 = '1' AND dup2 = '1' AND dup3 = '2') ";
			#$query .= "OR (dup1 = '1' AND dup2 = '2' AND dup3 = '2') ";
			$query .= "OR (dup1 = '1' AND dup2 = '2' AND dup3 = '3')) ";
			$query .= "AND passed_wheel = '1' ";
			$query .= "AND comb2 = '10' ";
			$query .= "AND comb3 = '10' ";
			$query .= "AND comb4 <= '1' ";
			$query .= "AND comb5 = '0' ";
			$query .= "ORDER BY RAND() ";

			print("<p>$query</p>");
		
			$mysqli_result_r0 = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

			$num_rows_r0 = mysqli_num_rows($mysqli_result_r0);

			print("<p>num_rows_r0 = $num_rows_r0</p>");

			if ($num_rows_r0)
			{
				$pass_pairs = 0;

				do 
				{
					if ($row0 = mysqli_fetch_array($mysqli_result_r0))
					{
						$pass_pairs = test_pairs($row0);

						if ($pass_pairs)
						{
							$pass_single_pair_2 = test_single_pair ($matrix[3][4],$row0[b2]);
							$pass_single_pair_3 = test_single_pair ($matrix[2][1],$row0[b3]);
							$pass_single_pair_4 = test_single_pair ($matrix[1][3],$row0[b4]);

							if ($pass_single_pair_2 && $pass_single_pair_3 && $pass_single_pair_4)
							{
								$matrix[3][2] = $row0[b2];
								$matrix[2][2] = $row0[b3];
								$matrix[1][2] = $row0[b4];
								$pass_pairs = 1;
							} else {
								echo "failed pair [b5]<p>";
								$pass_pairs = 0;
							}
						} else {
							echo "failed - $row0[b1] - $row0[b2] - $row0[b3] - $row0[b4]- $row0[b5]<p>";
						}
					} else {
						echo ("<b>No more rows - c2</b>");
						require ("includes_ga_f5/matrix_3a.incl");
					}
				} while (!$pass_pairs);
				
			} else {
				echo ("No rows - seed 3a - c2");
				require ("includes_ga_f5/matrix_3a.incl");
			}

			print_matrix ($matrix,$balls_drawn);

			# ---- end col2 ----

			# --04-- row2 --------------------------------------------------------------------------------------

			echo "<hr>";
			echo "<b>Seed 4 - row2</b><p>";

			$col1 = $matrix[2][1];
			require ("includes_ga_f5/calc_matrix_filters.incl");

			$query = "SELECT * FROM $draw_prefix";
			$query .= "filter_b_";
			if ($matrix[2][1] > 9)
			{
				$query .= "{$matrix[2][1]} ";
			} else {
				$query .= "0{$matrix[2][1]} ";
			}
			$query .= "WHERE b2 = {$matrix[0][4]} ";
			$query .= "AND b3 > {$matrix[0][4]} ";
			$query .= "AND b4 = {$matrix[2][4]} ";
			$query .= "AND b5 > {$matrix[1][3]} ";
			$query .= "AND b3 >= $row_limit_ba_3[low] ";
			$query .= "AND b3 <= $row_limit_ba_3[high] ";
			$query .= "AND b5 >= $row_limit_ba_5[low] ";
			$query .= "AND b5 <= $row_limit_ba_5[high] ";
			$query .= "AND rank0 <= 1 ";
			$query .= "AND rank1 <= 1 ";
			$query .= "AND rank2 <= 2 ";
			$query .= "AND rank3 <= 2 ";
			$query .= "AND rank4 <= 2 ";
			$query .= "AND rank5 <= 1 ";
			$query .= "AND rank6 <= 1 ";
			$query .= "AND ((dup1 = '0' AND dup2 = '1' AND dup3 = '1') ";
			$query .= "OR (dup1 = '1' AND dup2 = '1' AND dup3 = '1') ";
			$query .= "OR (dup1 = '1' AND dup2 = '1' AND dup3 = '2') ";
			#$query .= "OR (dup1 = '1' AND dup2 = '2' AND dup3 = '2') ";
			$query .= "OR (dup1 = '1' AND dup2 = '2' AND dup3 = '3')) ";
			$query .= "AND passed_wheel = '1' ";
			$query .= "AND comb2 = '10' ";
			$query .= "AND comb3 = '10' ";
			$query .= "AND comb4 <= '1' ";
			$query .= "AND comb5 = '0' ";
			$query .= "ORDER BY RAND() ";

			print("<p>$query</p>");
		
			$mysqli_result_r0 = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

			$num_rows_r0 = mysqli_num_rows($mysqli_result_r0);

			print("<p>num_rows_r2 = $num_rows_r0</p>");

			if ($num_rows_r0)
			{
				$pass_pairs = 0;

				do 
				{
					if ($row0 = mysqli_fetch_array($mysqli_result_r0))
					{
						$pass_pairs = test_pairs($row0);

						if ($pass_pairs)
						{
							$pass_single_pair_2 = test_single_pair ($matrix[0][0],$row0[b2]);
							$pass_single_pair_4a = test_single_pair ($matrix[0][4],$row0[b4]);
							$pass_single_pair_4b = test_single_pair ($matrix[3][4],$row0[b4]);
							$pass_single_pair_5 = test_single_pair ($matrix[0][3],$row0[b5]);

							if ($pass_single_pair_2 && $pass_single_pair_4a && $pass_single_pair_4b && $pass_single_pair_5)
							{
								$matrix[2][0] = $row0[b2];
								$matrix[2][4] = $row0[b4];
								$matrix[2][3] = $row0[b5];
								$pass_pairs = 1;
							} else {
								echo "failed pair [b5]<p>";
								$pass_pairs = 0;
							}
						} else {
							echo "failed - $row0[b1] - $row0[b2] - $row0[b3] - $row0[b4]- $row0[b5]<p>";
						}

						if ($pass_pairs)
						{
							$pass_pairs = 1;
						} else {
							echo "failed - $row0[b1] - $row0[b2] - $row0[b3] - $row0[b4]- $row0[b5]<p>";
							#require ("includes_ga_f5/matrix_4a.incl");
						}
					} else {
						echo ("<b>No more rows - r2</b>");
						require ("includes_ga_f5/matrix_4a.incl");
					}
				} while (!$pass_pairs);
				
			} else {
				echo ("No rows - r2");
				require ("includes_ga_f5/matrix_4a.incl");
			}

			print_matrix ($matrix,$balls_drawn);

			# ---- end row2 ----

			# --05-- col4 --------------------------------------------------------------------------------------

			echo "<hr>";
			echo "<b>Seed 5 - col4</b><p>";

			$col1 = $matrix[3][4];
			require ("includes_ga_f5/calc_matrix_filters.incl");

			$query = "SELECT * FROM $draw_prefix";
			$query .= "filter_b_";
			if ($matrix[3][4] > 9)
			{
				$query .= "{$matrix[3][4]} ";
			} else {
				$query .= "0{$matrix[3][4]} ";
			}
			$query .= "WHERE b3 > {$matrix[0][4]} ";
			$query .= "AND b3 > {$matrix[1][3]} ";
			$query .= "AND b3 < {$matrix[1][2]} ";
			$query .= "AND b4 = {$matrix[2][4]} ";
			$query .= "AND b5 > {$matrix[1][2]} ";
			$query .= "AND b3 >= $row_limit_ba_3[low] ";
			$query .= "AND b3 <= $row_limit_ba_3[high] ";
			$query .= "AND b5 >= $row_limit_ba_5[low] ";
			$query .= "AND b5 <= $row_limit_ba_5[high] ";
			$query .= "AND rank0 <= 1 ";
			$query .= "AND rank1 <= 1 ";
			$query .= "AND rank2 <= 2 ";
			$query .= "AND rank3 <= 2 ";
			$query .= "AND rank4 <= 2 ";
			$query .= "AND rank5 <= 1 ";
			$query .= "AND rank6 <= 1 ";
			for ($c = 0; $c <= 3; $c++)
			{
				$temp_low = ${"row_limit_dr_" . $c}[low];
				$temp_high = ${"row_limit_dr_" . $c}[high];
				$query .= "AND d$c >= $temp_low ";
				$query .= "AND d$c <= $temp_high ";
			}
			$query .= "AND ((dup1 = '0' AND dup2 = '1' AND dup3 = '1') ";
			$query .= "OR (dup1 = '1' AND dup2 = '1' AND dup3 = '1') ";
			$query .= "OR (dup1 = '1' AND dup2 = '1' AND dup3 = '2') ";
			#$query .= "OR (dup1 = '1' AND dup2 = '2' AND dup3 = '2') ";
			$query .= "OR (dup1 = '1' AND dup2 = '2' AND dup3 = '3')) ";
			$query .= "AND passed_wheel = '1' ";
			$query .= "AND comb2 = '10' ";
			$query .= "AND comb3 = '10' ";
			$query .= "AND comb4 <= '1' ";
			$query .= "AND comb5 = '0' ";
			$query .= "ORDER BY RAND() ";

			print("<p>$query</p>");
		
			$mysqli_result_r0 = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

			$num_rows_r0 = mysqli_num_rows($mysqli_result_r0);

			print("<p>num_rows_c4 = $num_rows_r0</p>");

			if ($num_rows_r0)
			{
				$pass_pairs = 0;

				do 
				{
					if ($row0 = mysqli_fetch_array($mysqli_result_r0))
					{
						$pass_pairs = test_pairs($row0);

						if ($pass_pairs)
						{
							$matrix[1][4] = $row0[b3];
							$matrix[4][4] = $row0[b5];
							$pass_pairs = 1;
						} else {
							echo "failed - $row0[b1] - $row0[b2] - $row0[b3] - $row0[b4]- $row0[b5]<p>";
						}
					} else {
						#die ("<b>No more rows - r0</b>");
						$query = "SELECT * FROM $draw_prefix";
						$query .= "filter_b_";
						if ($matrix[3][4] > 9)
						{
							$query .= "{$matrix[3][4]} ";
						} else {
							$query .= "0{$matrix[3][4]} ";
						}
						$query .= "WHERE b2 = {$matrix[0][4]} ";
						$query .= "AND b3 > {$matrix[1][3]} ";
						$query .= "AND b4 = {$matrix[2][4]} ";
						$query .= "AND b5 > {$matrix[4][2]} ";
						$query .= "AND b3 >= $row_limit_ba_3[low] ";
						$query .= "AND b3 <= $row_limit_ba_3[high] ";
						$query .= "AND b5 >= $row_limit_ba_5[low] ";
						$query .= "AND rank0 <= 1 ";
						$query .= "AND rank1 <= 1 ";
						$query .= "AND rank2 <= 2 ";
						$query .= "AND rank3 <= 2 ";
						$query .= "AND rank4 <= 2 ";
						$query .= "AND rank5 <= 1 ";
						$query .= "AND rank6 <= 1 ";
						$query .= "AND b5 <= '36' "; # ----------------------------------
						$query .= "AND ((dup1 = '0' AND dup2 = '1' AND dup3 = '1') ";
						$query .= "OR (dup1 = '1' AND dup2 = '1' AND dup3 = '1') ";
						$query .= "OR (dup1 = '1' AND dup2 = '1' AND dup3 = '2') ";
						#$query .= "OR (dup1 = '1' AND dup2 = '2' AND dup3 = '2') ";
						$query .= "OR (dup1 = '1' AND dup2 = '2' AND dup3 = '3')) ";
						$query .= "AND passed_wheel = '1' ";
						$query .= "AND comb2 = '10' ";
						$query .= "AND comb3 = '10' ";
						$query .= "AND comb4 <= '1' ";
						$query .= "AND comb5 = '0' ";
						$query .= "ORDER BY RAND() ";

						print("<p>$query</p>");
					
						$mysqli_result_r0 = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

						$num_rows_r0 = mysqli_num_rows($mysqli_result_r0);

						print("<p>num_rows_r0 = $num_rows_r0</p>");

						if ($num_rows_r0)
						{
							$pass_pairs = 0;

							do 
							{
								if ($row0 = mysqli_fetch_array($mysqli_result_r0))
								{
									$pass_pairs = test_pairs($row0);

									if ($pass_pairs)
									{
										$matrix[1][4] = $row0[b3];
										$matrix[4][4] = $row0[b5];

										$pass_pairs = 1;
									} else {
										echo "failed - $row0[b1] - $row0[b2] - $row0[b3] - $row0[b4]- $row0[b5]<p>";
									}
								} else {
									echo ("<b>No more rows - c4 (36)</b>");
									require ("includes_ga_f5/matrix_5a.incl");
								}
							} while (!$pass_pairs);
							
						} else {
							die ("No rows - c4 (36)");
						}
					}
				} while (!$pass_pairs);
				
			} else {
				echo ("No rows - c4");
				require ("includes_ga_f5/matrix_5a.incl");
			}

			print_matrix ($matrix,$balls_drawn);

			# ---- end col4 ----

			# --06-- col0 --------------------------------------------------------------------------------------

			echo "<hr>";
			echo "<b>Seed 6 - col0</b><p>";

			$col1 = $matrix[0][0];
			require ("includes_ga_f5/calc_matrix_filters.incl");

			$query = "SELECT * FROM $draw_prefix";
			$query .= "filter_b_";
			if ($matrix[0][0] > 9)
			{
				$query .= "{$matrix[0][0]} ";
			} else {
				$query .= "0{$matrix[0][0]} ";
			}
			$query .= "WHERE b2 = {$matrix[2][0]} ";
			$query .= "AND b3 > {$matrix[3][2]} ";
			$query .= "AND b4 > {$matrix[4][2]} ";
			$query .= "AND b4 < {$matrix[4][4]} ";
			$query .= "AND b4 > {$matrix[2][2]} ";
			$query .= "AND b5 > {$matrix[1][2]} ";
			$query .= "AND rank0 <= 1 ";
			$query .= "AND rank1 <= 1 ";
			$query .= "AND rank2 <= 2 ";
			$query .= "AND rank3 <= 2 ";
			$query .= "AND rank4 <= 2 ";
			$query .= "AND rank5 <= 1 ";
			$query .= "AND rank6 <= 1 ";
			$query .= "AND b3 >= $row_limit_ba_3[low] ";
			$query .= "AND b3 <= $row_limit_ba_3[high] ";
			$query .= "AND b4 >= $row_limit_ba_4[low] ";
			$query .= "AND b4 <= $row_limit_ba_4[high] ";
			$query .= "AND b5 >= $row_limit_ba_5[low] ";
			$query .= "AND b5 <= $row_limit_ba_5[high] ";
			$query .= "AND ((dup1 = '0' AND dup2 = '1' AND dup3 = '1') ";
			$query .= "OR (dup1 = '1' AND dup2 = '1' AND dup3 = '1') ";
			$query .= "OR (dup1 = '1' AND dup2 = '1' AND dup3 = '2') ";
			#$query .= "OR (dup1 = '1' AND dup2 = '2' AND dup3 = '2') ";
			$query .= "OR (dup1 = '1' AND dup2 = '2' AND dup3 = '3')) ";
			$query .= "AND passed_wheel = '1' ";
			$query .= "AND comb2 = '10' ";
			$query .= "AND comb3 = '10' ";
			$query .= "AND comb4 <= '1' ";
			$query .= "AND comb5 = '0' ";
			$query .= "ORDER BY RAND() ";

			print("<p>$query</p>");
		
			$mysqli_result_r0 = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

			$num_rows_r0 = mysqli_num_rows($mysqli_result_r0);

			print("<p>num_rows_c0 = $num_rows_r0</p>");

			if ($num_rows_r0)
			{
				$pass_pairs = 0;

				do 
				{
					if ($row0 = mysqli_fetch_array($mysqli_result_r0))
					{
						$pass_pairs = test_pairs($row0);

						if ($pass_pairs)
						{
							$matrix[3][0] = $row0[b3];
							$matrix[4][0] = $row0[b4];
							$matrix[1][0] = $row0[b5];
							$pass_pairs = 1;
						} else {
							echo "failed - $row0[b1] - $row0[b2] - $row0[b3] - $row0[b4]- $row0[b5]<p>";
						}
					} else {
						echo ("<b>No more rows - c0</b>");
						require ("includes_ga_f5/matrix_6a.incl");
					}
				} while (!$pass_pairs);
				
			} else {
				die ("No rows - c0");
			}

			print_matrix ($matrix,$balls_drawn);

			# ---- end col0 ----

			# --07a-- row4 --------------------------------------------------------------------------------------

			echo "<hr>";
			echo "<b>Seed 7 - row4</b><p>";

			$col1 = $matrix[4][2];
			require ("includes_ga_f5/calc_matrix_filters.incl");

			$query = "SELECT * FROM $draw_prefix";
			$query .= "filter_b_";
			if ($matrix[4][2] > 9)
			{
				$query .= "{$matrix[4][2]} ";
			} else {
				$query .= "0{$matrix[4][2]} ";
			}
			$query .= "WHERE b2 > {$matrix[1][3]} ";
			$query .= "AND b2 < {$matrix[0][3]} ";
			$query .= "AND b3 < {$matrix[0][1]} ";
			$query .= "AND b4 = {$matrix[4][0]} ";
			$query .= "AND b5 = {$matrix[4][4]} ";
			$query .= "AND b2 >= $row_limit_ba_2[low] ";
			$query .= "AND b2 <= $row_limit_ba_2[high] ";
			$query .= "AND b3 >= $row_limit_ba_3[low] ";
			$query .= "AND b3 <= $row_limit_ba_3[high] ";
			$query .= "AND rank0 <= 1 ";
			$query .= "AND rank1 <= 1 ";
			$query .= "AND rank2 <= 2 ";
			$query .= "AND rank3 <= 2 ";
			$query .= "AND rank4 <= 2 ";
			$query .= "AND rank5 <= 1 ";
			$query .= "AND rank6 <= 1 ";
			$query .= "AND ((dup1 = '0' AND dup2 = '1' AND dup3 = '1') ";
			$query .= "OR (dup1 = '1' AND dup2 = '1' AND dup3 = '1') ";
			$query .= "OR (dup1 = '1' AND dup2 = '1' AND dup3 = '2') ";
			#$query .= "OR (dup1 = '1' AND dup2 = '2' AND dup3 = '2') ";
			$query .= "OR (dup1 = '1' AND dup2 = '2' AND dup3 = '3')) ";
			$query .= "AND passed_wheel = '1' ";
			$query .= "AND comb2 = '10' ";
			$query .= "AND comb3 = '10' ";
			$query .= "AND comb4 <= '1' ";
			$query .= "AND comb5 = '0' ";
			$query .= "ORDER BY RAND() ";

			print("<p>$query</p>");
		
			$mysqli_result_r0 = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

			$num_rows_r0 = mysqli_num_rows($mysqli_result_r0);

			print("<p>num_rows_r4 = $num_rows_r0</p>");

			if ($num_rows_r0)
			{
				$pass_pairs = 0;

				do 
				{
					if ($row0 = mysqli_fetch_array($mysqli_result_r0))
					{
						$pass_pairs = test_pairs($row0);

						if ($pass_pairs)
						{
							$matrix[4][3] = $row0[b2];
							$matrix[4][1] = $row0[b3];
							$pass_pairs = 1;
						} else {
							echo "failed - $row0[b1] - $row0[b2] - $row0[b3] - $row0[b4]- $row0[b5]<p>";
						}
					} else {
						echo ("<b>No more rows - r4</b>");
						require ("includes_ga_f5/matrix_7a.incl");
					}
				} while (!$pass_pairs);
				
			} else {
				echo ("No rows - r0");
				require ("includes_ga_f5/matrix_7a.incl");
			}

			print_matrix ($matrix,$balls_drawn);

			# --08-- row3 --------------------------------------------------------------------------------------

			echo "<hr>";
			echo "<b>Seed 8 - row3</b><p>";

			$col1 = $matrix[3][4];
			require ("includes_ga_f5/calc_matrix_filters.incl");

			$query = "SELECT * FROM $draw_prefix";
			$query .= "filter_b_";
			if ($matrix[3][4] > 9)
			{
				$query .= "{$matrix[3][4]} ";
			} else {
				$query .= "0{$matrix[3][4]} ";
			}
			$query .= "WHERE b2 = {$matrix[3][2]} ";
			$query .= "AND b3 = {$matrix[3][0]} ";
			$query .= "AND b4 > {$matrix[0][3]} ";
			$query .= "AND b4 < {$matrix[2][3]} ";
			$query .= "AND b5 > {$matrix[0][1]} ";
			$query .= "AND b4 >= $row_limit_ba_4[low] ";
			$query .= "AND b4 <= $row_limit_ba_4[high] ";
			$query .= "AND b5 >= $row_limit_ba_5[low] ";
			$query .= "AND b5 <= $row_limit_ba_5[high] ";
			$query .= "AND rank0 <= 1 ";
			$query .= "AND rank1 <= 1 ";
			$query .= "AND rank2 <= 2 ";
			$query .= "AND rank3 <= 2 ";
			$query .= "AND rank4 <= 2 ";
			$query .= "AND rank5 <= 1 ";
			$query .= "AND rank6 <= 1 ";
			$query .= "AND ((dup1 = '0' AND dup2 = '1' AND dup3 = '1') ";
			$query .= "OR (dup1 = '1' AND dup2 = '1' AND dup3 = '1') ";
			$query .= "OR (dup1 = '1' AND dup2 = '1' AND dup3 = '2') ";
			#$query .= "OR (dup1 = '1' AND dup2 = '2' AND dup3 = '2') ";
			$query .= "OR (dup1 = '1' AND dup2 = '2' AND dup3 = '3')) ";
			$query .= "AND passed_wheel = '1' ";
			$query .= "AND comb2 = '10' ";
			$query .= "AND comb3 = '10' ";
			$query .= "AND comb4 <= '1' ";
			$query .= "AND comb5 = '0' ";
			$query .= "ORDER BY RAND() ";

			print("<p>$query</p>");
		
			$mysqli_result_r0 = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

			$num_rows_r0 = mysqli_num_rows($mysqli_result_r0);

			print("<p>num_rows_r3 = $num_rows_r0</p>");

			if ($num_rows_r0)
			{
				$pass_pairs = 0;

				do 
				{
					if ($row0 = mysqli_fetch_array($mysqli_result_r0))
					{
						$pass_pairs = test_pairs($row0);

						if ($pass_pairs)
						{
							$matrix[3][3] = $row0[b4];
							$matrix[3][1] = $row0[b5];
							$pass_pairs = 1;
						} else {
							echo "failed - $row0[b1] - $row0[b2] - $row0[b3] - $row0[b4]- $row0[b5]<p>";
						}
					} else {
						echo ("<b>No more rows - r3</b>");
						require ("includes_ga_f5/matrix_8a.incl");
					}
				} while (!$pass_pairs);
				
			} else {
				echo ("No rows - r3");
				require ("includes_ga_f5/matrix_8a.incl");
			}

			print_matrix ($matrix,$balls_drawn);

			# --09-- col1 --------------------------------------------------------------------------------------

			echo "<hr>";
			echo "<b>Seed 9 - col1</b><p>";

			$col1 = $matrix[2][1];
			require ("includes_ga_f5/calc_matrix_filters.incl");

			$query = "SELECT * FROM $draw_prefix";
			$query .= "filter_b_";
			if ($matrix[2][1] > 9)
			{
				$query .= "{$matrix[2][1]} ";
			} else {
				$query .= "0{$matrix[2][1]} ";
			}
			$query .= "WHERE b2 > {$matrix[1][3]} ";
			$query .= "AND b2 < {$matrix[1][4]} ";
			$query .= "AND b3 = {$matrix[4][1]} ";
			$query .= "AND b4 = {$matrix[0][1]} ";
			$query .= "AND b5 = {$matrix[3][1]} ";
			$query .= "AND rank0 <= 1 ";
				$query .= "AND rank1 <= 1 ";
				$query .= "AND rank2 <= 2 ";
				$query .= "AND rank3 <= 2 ";
				$query .= "AND rank4 <= 2 ";
				$query .= "AND rank5 <= 1 ";
				$query .= "AND rank6 <= 1 ";
			$query .= "AND b2 >= $row_limit_ba_2[low] ";
			$query .= "AND b2 <= $row_limit_ba_2[high] ";
			$query .= "AND passed_wheel = '1' ";
			$query .= "AND ((dup1 = '0' AND dup2 = '1' AND dup3 = '1') ";
			$query .= "OR (dup1 = '1' AND dup2 = '1' AND dup3 = '1') ";
			$query .= "OR (dup1 = '1' AND dup2 = '1' AND dup3 = '2') ";
			#$query .= "OR (dup1 = '1' AND dup2 = '2' AND dup3 = '2') ";
			$query .= "OR (dup1 = '1' AND dup2 = '2' AND dup3 = '3')) ";
			$query .= "AND comb2 = '10' ";
			$query .= "AND comb3 = '10' ";
			$query .= "AND comb4 <= '1' ";
			$query .= "AND comb5 = '0' ";
			$query .= "ORDER BY RAND() ";

			print("<p>$query</p>");
		
			$mysqli_result_r0 = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

			$num_rows_r0 = mysqli_num_rows($mysqli_result_r0);

			print("<p>num_rows_r0 = $num_rows_r0</p>");

			if ($num_rows_r0)
			{
				$pass_pairs = 0;

				do 
				{
					if ($row0 = mysqli_fetch_array($mysqli_result_r0))
					{
						$pass_pairs = test_pairs($row0);

						if ($pass_pairs)
						{
							$matrix[1][1] = $row0[b2];

							$pass_pairs = 1;
						} else {
							echo "failed - $row0[b1] - $row0[b2] - $row0[b3] - $row0[b4]- $row0[b5]<p>";
						}
					} else {
						#die ("<b>No more rows - r0</b>");
						break;
					}
				} while (!$pass_pairs);
			}  
			
			if ($matrix[1][1] == 0)
			{
				$query = "SELECT * FROM $draw_prefix";
				$query .= "filter_b_";
				if ($matrix[2][1] > 9)
				{
					$query .= "{$matrix[2][1]} ";
				} else {
					$query .= "0{$matrix[2][1]} ";
				}
				$query .= "WHERE b2 > {$matrix[1][3]} ";
				$query .= "AND b2 < {$matrix[1][4]} ";
				$query .= "AND b3 = {$matrix[4][1]} ";
				$query .= "AND b4 = {$matrix[0][1]} ";
				$query .= "AND b5 = {$matrix[3][1]} ";
				$query .= "AND rank0 <= 1 ";
				$query .= "AND rank1 <= 1 ";
				$query .= "AND rank2 <= 2 ";
				$query .= "AND rank3 <= 2 ";
				$query .= "AND rank4 <= 2 ";
				$query .= "AND rank5 <= 1 ";
				$query .= "AND rank6 <= 1 ";
				$query .= "AND ((dup1 = '0' AND dup2 = '1' AND dup3 = '1') ";
				$query .= "OR (dup1 = '1' AND dup2 = '1' AND dup3 = '1') ";
				$query .= "OR (dup1 = '1' AND dup2 = '1' AND dup3 = '2') ";
				#$query .= "OR (dup1 = '1' AND dup2 = '2' AND dup3 = '2') ";
				$query .= "OR (dup1 = '1' AND dup2 = '2' AND dup3 = '3')) ";
				$query .= "AND passed_wheel = '1' ";
				$query .= "AND comb2 = '10' ";
				$query .= "AND comb3 = '10' ";
				$query .= "AND comb4 <= '1' ";
				$query .= "AND comb5 = '0' ";
				$query .= "ORDER BY RAND() ";

				print("<p>$query</p>");
			
				$mysqli_result_r0 = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

				$num_rows_r0 = mysqli_num_rows($mysqli_result_r0);

				print("<p>num_rows_r0 = $num_rows_r0</p>");

				if ($num_rows_r0)
				{
					$pass_pairs = 0;

					do 
					{
						if ($row0 = mysqli_fetch_array($mysqli_result_r0))
						{
							$pass_pairs = test_pairs($row0);

							if ($pass_pairs)
							{
								$matrix[1][1] = $row0[b2];
								$pass_pairs = 1;
							} else {
								echo "failed - $row0[b1] - $row0[b2] - $row0[b3] - $row0[b4]- $row0[b5]<p>";
							}
						} else {
							die ("<b>No more rows - r0 (none)</b>");
						}
					} while (!$pass_pairs);
					
				} else {
					die ("No rows - r0 (none)");
				}
			}

			print_matrix ($matrix,$balls_drawn);

			# ---- end col1 ----

			die();
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

		sort($draw);
		
		for ($x = 0 ; $x <= count($draw)-2; $x++)
		{
			$num1 = $draw[$x];
			$num2 = $draw[$x+1]-1;
			if ($num1 == $num2 && $num1 > 0 && $num2 > 0)
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
			if ($num1 == $num2 && $num2 == $num3 && $num1 > 0 && $num2 > 0  && $num3 > 0)
			{
				$seq3++;
			}
		}

		return $seq3;
	}

	function test_single_pair ($d1,$d2)
	{
		require ("includes/mysqli.php");

		global $debug, $game, $game_name, $balls, $balls_drawn, $mega_balls, $draw_table_name, $draw_prefix, $game_includes;

		$query2 = "SELECT * FROM $draw_prefix";
		$query2 .= "temp_pairs_5000 ";
		$query2 .= "WHERE num1 = $d1 ";
		$query2 .= "AND num2 = $d2 ";

		#print("<p>$query2</p>");
	
		$mysqli_result7 = mysqli_query($mysqli_link, $query2) or die (mysqli_error($mysqli_link));

		$row7 = mysqli_fetch_array($mysqli_result7);

		if ($row7[percent_wa] < 1.0)
		{
			return '0';
		}

		return '1';
	}

	function test_pairs ($row)
	{
		require ("includes/mysqli.php");

		global $debug, $game, $game_name, $balls, $balls_drawn, $mega_balls, $draw_table_name, $draw_prefix, $game_includes;

		for ($c = 1; $c <= 10; $c++)
		{
			switch ($c) 
			{ 
			   case 1: 
				   $d1 = $row[1];
				   $d2 = $row[2];
				   break; 
			   case 2: 
				   $d1 = $row[1];
				   $d2 = $row[3];
				   break; 
			   case 3: 
				   $d1 = $row[1];
				   $d2 = $row[4];
				   break; 
			   case 4: 
				   $d1 = $row[1];
				   $d2 = $row[5];
				   break;
			   case 5: 
				   $d1 = $row[2];
				   $d2 = $row[3];
				   break;
			   case 6: 
				   $d1 = $row[2];
				   $d2 = $row[4];
				   break; 
			   case 7: 
				   $d1 = $row[2];
				   $d2 = $row[5];
				   break; 
			   case 8: 
				   $d1 = $row[3];
				   $d2 = $row[4];
				   break;
			   case 9: 
				   $d1 = $row[3];
				   $d2 = $row[5];
				   break;
			   case 10: 
				   $d1 = $row[4];
				   $d2 = $row[5];
				   break;
			} 

			$query2 = "SELECT * FROM $draw_prefix";
			$query2 .= "temp_pairs_5000 ";
			$query2 .= "WHERE num1 = $d1 ";
			$query2 .= "AND num2 = $d2 ";

			#print("<p>$query2</p>");
		
			$mysqli_result2 = mysqli_query($mysqli_link, $query2) or die (mysqli_error($mysqli_link));

			$row2 = mysqli_fetch_array($mysqli_result2);

			#print("<p>row2[percent_wa] = $row2[percent_wa]</p>");

			if ($row2[percent_wa] < 1.0)
			{
				return '0';
			}
		}

		return '1';
	}
	
	//start HTML page
	print("<HTML>\n");
	print("<HEAD>\n");
	print("<TITLE>$game_name Matrix 3 - $game_name</TITLE>\n");
	print("</HEAD>\n");
	
	print("<BODY>\n");

	lot_matrix ($col1=0,$limit=1,$delete_temp=0);
	
	print("</BODY>\n");
?>
