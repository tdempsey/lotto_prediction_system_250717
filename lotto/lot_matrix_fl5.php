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

		# --01-- delete temp tables
		if ($delete_temp)
		{
			$sql = "TRUNCATE TABLE `fl_f5_2_53_save`";
			$mysqli_result2 = mysqli_query($sql, $mysqli_link) or die (mysqli_error($mysqli_link));

			$sql = "TRUNCATE TABLE `fl_f5_3_53_save`";
			$mysqli_result3 = mysqli_query($sql, $mysqli_link) or die (mysqli_error($mysqli_link));

			$sql = "TRUNCATE TABLE `fl_f5_4_53_save`";
			$mysqli_result4 = mysqli_query($sql, $mysqli_link) or die (mysqli_error($mysqli_link));

			$sql = "CREATE TABLE IF NOT EXISTS `fl_f5_draws_matrix_$curr_date` (
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
				$query .= "temp_5000 ";
				$query .= "WHERE num <= 10 "; # 11
				$query .= "ORDER BY percent_wa DESC ";
				$query .= "LIMIT 11 "; # 6

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
				echo "<p>";

				for ($a = 0; $a <= 4; $a++)
				{
					for ($b = 0; $b <= 4; $b++)
					{
						if (${row.$a}[$b] == 1)
						{
							#mt_srand((float) microtime() * 10000000);
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
			
			# --03-- seed center --------------------------------------------------------------------------------------

			echo "<hr>";
			echo "<b>Seed 3 - center 2,2</b><p>";

			# ---- ba 3 ---- 2,2
			$query = "SELECT * FROM $draw_prefix";
			$query .= "filter_limits ";
			$query .= "WHERE col1 = '$col1' "; 
			$query .= "AND col_pos = '3' "; 
			$query .= "AND date = '$curr_date' ";
			$query .= "AND limit_type = 'ba' ";

			print("<p>$query</p>");
		
			$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

			if (!$num_rows_limit = mysqli_num_rows($mysqli_result))
			{
				die ("No limit found - ba $col1");
			}

			$row_limit_ba = mysqli_fetch_array($mysqli_result);
			# ---- ba 2 end ----

			$seed3_table = array_fill(0, $balls+1, 0.0);
			$y = 0;

			$query = "SELECT * FROM $draw_prefix";
			$query .= "temp_pairs_5000 ";
			$query .= "WHERE num1 = {$matrix[4][2]} ";
			$query .= "AND num2 > {$matrix[4][2]} ";
			$query .= "AND num2 >= $row_limit_ba[low] ";
			$query .= "AND num2 <= $row_limit_ba[high] ";
			$query .= "ORDER BY percent_wa DESC ";

			print("<p>$query</p>");
		
			$mysqli_result3 = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

			while($row3 = mysqli_fetch_array($mysqli_result3))
			{
				$y = $row3[num2];
				$seed3_table[$y] += $row3[percent_wa];
			}

			# filter dups

			$query = "SELECT * FROM $draw_prefix";
			$query .= "temp_pairs_5000 ";
			$query .= "WHERE num1 = {$matrix[2][1]} ";
			$query .= "AND num2 > {$matrix[2][1]} ";
			$query .= "AND num2 >= $row_limit_ba[low] ";
			$query .= "AND num2 <= $row_limit_ba[high] ";
			$query .= "ORDER BY percent_wa DESC ";

			print("<p>$query</p>");
		
			$mysqli_result3 = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

			$num_rows_3 = mysqli_num_rows($mysqli_result3);

			print("<p>num_rows_3 = $num_rows_3</p>");

			while($row3 = mysqli_fetch_array($mysqli_result3))
			{
				$y = $row3[num2];
				$seed3_table[$y] += $row3[percent_wa];
			}

			# filter dups

			$query = "SELECT * FROM $draw_prefix";
			$query .= "temp_pairs_5000 ";
			$query .= "WHERE num1 = {$matrix[1][3]} ";
			$query .= "AND num2 > {$matrix[1][3]} ";
			$query .= "AND num2 >= $row_limit_ba[low] ";
			$query .= "AND num2 <= $row_limit_ba[high] ";
			$query .= "ORDER BY percent_wa DESC ";

			print("<p>$query</p>");
		
			$mysqli_result3 = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

			$num_rows_3 = mysqli_num_rows($mysqli_result3);

			print("<p>num_rows_3 = $num_rows_3</p>");

			while($row3 = mysqli_fetch_array($mysqli_result3))
			{
				$y = $row3[num2];
				$seed3_table[$y] += $row3[percent_wa];
			}

			# filter dups

			$max_array = array_fill (0, 2, 0);

			for ($z = 0; $z < 3; $z++)
			{
				$maxs = array_keys($seed3_table, max($seed3_table));
				$max_array[$z] = $maxs[0];
				echo "maxs = $max_array[$z]<br>";
				$seed3_table[$maxs[0]] = 0.0;
			}

			shuffle ($max_array);
			echo "seed3_table = ";
			print_r ($seed3_table);
			echo "<p>";
			#mt_srand((float) microtime() * 10000000);
			$rand_id = mt_rand(0,2);
			echo "rand_id = $rand_id<br>";
			$matrix[2][2] = $max_array[$rand_id];

			print_matrix ($matrix,$balls_drawn);

			# --04-- seed corners -------------------------------------------------------------------------------------

			echo "<hr>";
			echo "<b>Seed 4 - 4,0</b><p>";

			# ---- ba 4 ---- 4,0
			$query = "SELECT * FROM $draw_prefix";
			$query .= "filter_limits ";
			$query .= "WHERE col1 = '$col1' "; 
			$query .= "AND col_pos = '4' "; 
			$query .= "AND date = '$curr_date' ";
			$query .= "AND limit_type = 'ba' ";

			print("<p>$query</p>");
		
			$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

			if (!$num_rows_limit = mysqli_num_rows($mysqli_result))
			{
				die ("No limit found - ba $col1");
			}

			$row_limit_ba = mysqli_fetch_array($mysqli_result);
			# ---- ba 4 end ----

			$seed4_table = array_fill(0, $balls+1, 0.0);
			$y = 0;

			$query = "SELECT * FROM $draw_prefix";
			$query .= "temp_pairs_5000 ";
			$query .= "WHERE num1 = {$matrix[0][0]} ";
			#$query .= "AND num2 > {$matrix[1][3]} ";
			$query .= "AND num2 > {$matrix[2][2]} ";
			$query .= "AND num2 > {$matrix[0][2]} ";
			$query .= "AND num2 >= $row_limit_ba[low] ";
			$query .= "AND num2 <= $row_limit_ba[high] ";
			$query .= "ORDER BY percent_wa DESC ";

			print("<p>$query</p>");
		
			$mysqli_result3 = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

			while($row3 = mysqli_fetch_array($mysqli_result3))
			{
				$y = $row3[num2];
				$seed4_table[$y] += $row3[percent_wa];
			}

			$query = "SELECT * FROM $draw_prefix";
			$query .= "temp_pairs_5000 ";
			$query .= "WHERE num1 = {$matrix[4][2]} ";
			$query .= "AND num2 > {$matrix[2][2]} ";
			$query .= "AND num2 > {$matrix[0][4]} ";
			$query .= "AND num2 >= $row_limit_ba[low] ";
			$query .= "AND num2 <= $row_limit_ba[high] ";
			$query .= "ORDER BY percent_wa DESC ";

			print("<p>$query</p>");
		
			$mysqli_result3 = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

			$num_rows_3 = mysqli_num_rows($mysqli_result3);

			print("<p>num_rows_3 = $num_rows_3</p>");

			while($row3 = mysqli_fetch_array($mysqli_result3))
			{
				$y = $row3[num2];
				$seed4_table[$y] += $row3[percent_wa];
			}

			# filter dups

			$query = "SELECT * FROM $draw_prefix";
			$query .= "temp_pairs_5000 ";
			$query .= "WHERE num1 = {$matrix[1][3]} ";
			$query .= "AND num2 > {$matrix[2][2]} ";
			$query .= "AND num2 >= $row_limit_ba[low] ";
			$query .= "AND num2 <= $row_limit_ba[high] ";
			$query .= "ORDER BY percent_wa DESC ";

			print("<p>$query</p>");
		
			$mysqli_result3 = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

			$num_rows_3 = mysqli_num_rows($mysqli_result3);

			print("<p>num_rows_3 = $num_rows_3</p>");

			while($row3 = mysqli_fetch_array($mysqli_result3))
			{
				$y = $row3[num2];
				$seed4_table[$y] += $row3[percent_wa];
			}

			# filter dups

			$query = "SELECT * FROM $draw_prefix";
			$query .= "temp_pairs_5000 ";
			$query .= "WHERE num1 = {$matrix[2][2]} ";
			$query .= "AND num2 > {$matrix[4][2]} ";
			$query .= "AND num2 >= $row_limit_ba[low] ";
			$query .= "AND num2 <= $row_limit_ba[high] ";
			$query .= "ORDER BY percent_wa DESC ";

			print("<p>$query</p>");
		
			$mysqli_result3 = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

			$num_rows_3 = mysqli_num_rows($mysqli_result3);

			print("<p>num_rows_3 = $num_rows_3</p>");

			while($row3 = mysqli_fetch_array($mysqli_result3))
			{
				$y = $row3[num2];
				$seed4_table[$y] += $row3[percent_wa];
			}

			# filter dups

			$max_array = array_fill (0, 2, 0);

			for ($z = 0; $z < 3; $z++)
			{
				$maxs = array_keys($seed4_table, max($seed4_table));
				$max_array[$z] = $maxs[0];
				echo "maxs = $max_array[$z]<br>";
				$seed4_table[$maxs[0]] = 0.0;
			}

			shuffle ($max_array);
			echo "seed4_table = ";
			print_r ($seed4_table);
			echo "<p>";
			#mt_srand((float) microtime() * 10000000);
			$rand_id = mt_rand(0,2);
			echo "rand_id = $rand_id<br>";
			$matrix[4][0] = $max_array[$rand_id];

			print_matrix ($matrix,$balls_drawn);

			# --05-- seed corner --------------------------------------------------------------------------------------

			echo "<hr>";
			echo "<b>Seed 5 - 0,4</b><p>";

			# ---- ba 2 ---- 0,4
			$query = "SELECT * FROM $draw_prefix";
			$query .= "filter_limits ";
			$query .= "WHERE col1 = '$col1' "; 
			$query .= "AND col_pos = '2' "; 
			$query .= "AND date = '$curr_date' ";
			$query .= "AND limit_type = 'ba' ";

			print("<p>$query</p>");
		
			$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

			if (!$num_rows_limit = mysqli_num_rows($mysqli_result))
			{
				die ("No limit found - ba $col1");
			}

			$row_limit_ba = mysqli_fetch_array($mysqli_result);
			# ---- ba 2 end ----

			$seed2_table = array_fill(0, $balls+1, 0.0);
			$y = 0;

			$query = "SELECT * FROM $draw_prefix";
			$query .= "temp_pairs_5000 ";
			$query .= "WHERE num1 = {$matrix[0][0]} ";
			$query .= "AND num2 > {$matrix[1][3]} ";
			$query .= "AND num2 < {$matrix[2][2]} ";
			$query .= "AND num2 > {$matrix[3][4]} ";
			$query .= "AND num2 > {$matrix[0][0]} ";
			$query .= "AND num2 >= $row_limit_ba[low] ";
			$query .= "AND num2 <= $row_limit_ba[high] ";
			$query .= "ORDER BY percent_wa DESC ";

			print("<p>$query</p>");
		
			$mysqli_result3 = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

			while($row3 = mysqli_fetch_array($mysqli_result3))
			{
				$y = $row3[num2];
				$seed2_table[$y] += $row3[percent_wa];
			}

			# filter dups

			$query = "SELECT * FROM $draw_prefix";
			$query .= "temp_pairs_5000 ";
			$query .= "WHERE num1 = {$matrix[3][4]} ";
			$query .= "AND num2 > {$matrix[1][3]} ";
			$query .= "AND num2 < {$matrix[2][2]} ";
			$query .= "AND num2 > {$matrix[3][4]} ";
			$query .= "AND num2 > {$matrix[0][0]} ";
			$query .= "AND num2 >= $row_limit_ba[low] ";
			$query .= "AND num2 <= $row_limit_ba[high] ";
			$query .= "ORDER BY percent_wa DESC ";

			print("<p>$query</p>");
		
			$mysqli_result3 = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

			$num_rows_3 = mysqli_num_rows($mysqli_result3);

			print("<p>num_rows_3 = $num_rows_3</p>");

			while($row3 = mysqli_fetch_array($mysqli_result3))
			{
				$y = $row3[num2];
				$seed2_table[$y] += $row3[percent_wa];
			}

			# filter dups

			$query = "SELECT * FROM $draw_prefix";
			$query .= "temp_pairs_5000 ";
			$query .= "WHERE num1 = {$matrix[1][3]} ";
			$query .= "AND num2 > {$matrix[1][3]} ";
			$query .= "AND num2 < {$matrix[2][2]} ";
			$query .= "AND num2 > {$matrix[3][4]} ";
			$query .= "AND num2 > {$matrix[0][0]} ";
			$query .= "AND num2 >= $row_limit_ba[low] ";
			$query .= "AND num2 <= $row_limit_ba[high] ";
			$query .= "ORDER BY percent_wa DESC ";

			print("<p>$query</p>");
		
			$mysqli_result3 = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

			$num_rows_3 = mysqli_num_rows($mysqli_result3);

			print("<p>num_rows_3 = $num_rows_3</p>");

			while($row3 = mysqli_fetch_array($mysqli_result3))
			{
				$y = $row3[num2];
				$seed2_table[$y] += $row3[percent_wa];
			}

			# filter dups

			$query = "SELECT * FROM $draw_prefix";
			$query .= "temp_pairs_5000 ";
			$query .= "WHERE num1 = {$matrix[2][2]} ";
			$query .= "AND num2 > {$matrix[1][3]} ";
			$query .= "AND num2 < {$matrix[2][2]} ";
			$query .= "AND num2 > {$matrix[3][4]} ";
			$query .= "AND num2 > {$matrix[0][0]} ";
			$query .= "AND num2 >= $row_limit_ba[low] ";
			$query .= "AND num2 <= $row_limit_ba[high] ";
			$query .= "ORDER BY percent_wa DESC ";

			print("<p>$query</p>");
		
			$mysqli_result3 = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

			$num_rows_3 = mysqli_num_rows($mysqli_result3);

			print("<p>num_rows_3 = $num_rows_3</p>");

			while($row3 = mysqli_fetch_array($mysqli_result3))
			{
				$y = $row3[num2];
				$seed2_table[$y] += $row3[percent_wa];
			}

			# filter dups

			$query = "SELECT * FROM $draw_prefix";
			$query .= "temp_pairs_5000 ";
			$query .= "WHERE num1 = {$matrix[4][0]} ";
			$query .= "AND num2 > {$matrix[1][3]} ";
			$query .= "AND num2 < {$matrix[2][2]} ";
			$query .= "AND num2 > {$matrix[3][4]} ";
			$query .= "AND num2 > {$matrix[0][0]} ";
			$query .= "AND num2 >= $row_limit_ba[low] ";
			$query .= "AND num2 <= $row_limit_ba[high] ";
			$query .= "ORDER BY percent_wa DESC ";

			print("<p>$query</p>");
		
			$mysqli_result3 = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

			$num_rows_3 = mysqli_num_rows($mysqli_result3);

			print("<p>num_rows_3 = $num_rows_3</p>");

			while($row3 = mysqli_fetch_array($mysqli_result3))
			{
				$y = $row3[num2];
				$seed2_table[$y] += $row3[percent_wa];
			}

			# filter dups

			$max_array = array_fill (0, 2, 0);

			for ($z = 0; $z < 3; $z++)
			{
				$maxs = array_keys($seed2_table, max($seed2_table));
				$max_array[$z] = $maxs[0];
				echo "maxs = $max_array[$z]<br>";
				$seed2_table[$maxs[0]] = 0.0;
			}

			shuffle ($max_array);
			echo "max_array = ";
			print_r ($max_array);
			echo "<p>";
			#mt_srand((float) microtime() * 10000000);
			$rand_id = mt_rand(0,2);
			echo "rand_id = $rand_id<br>";
			$matrix[0][4] = $max_array[$rand_id];

			print_matrix ($matrix,$balls_drawn);

			# --06-- seed corner --------------------------------------------------------------------------------------

			echo "<hr>";
			echo "<b>Seed 6 - 4,4</b><p>";

			# ---- ba 5 ---- 4,4
			$query = "SELECT * FROM $draw_prefix";
			$query .= "filter_limits ";
			$query .= "WHERE col1 = '$col1' "; 
			$query .= "AND col_pos = '5' "; 
			$query .= "AND date = '$curr_date' ";
			$query .= "AND limit_type = 'ba' ";

			print("<p>$query</p>");
		
			$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

			if (!$num_rows_limit = mysqli_num_rows($mysqli_result))
			{
				die ("No limit found - ba $col1");
			}

			$row_limit_ba = mysqli_fetch_array($mysqli_result);
			# ---- ba 5 end ----

			$seed5_table = array_fill(0, $balls+1, 0.0);
			$y = 0;

			$query = "SELECT * FROM $draw_prefix";
			$query .= "temp_pairs_5000 ";
			$query .= "WHERE num1 = {$matrix[3][4]} ";
			$query .= "AND num2 > {$matrix[2][2]} ";
			$query .= "AND num2 > {$matrix[0][4]} ";
			$query .= "AND num2 > {$matrix[4][0]} ";
			$query .= "AND num2 >= $row_limit_ba[low] ";
			$query .= "AND num2 <= $row_limit_ba[high] ";
			$query .= "ORDER BY percent_wa DESC ";

			print("<p>$query</p>");
		
			$mysqli_result3 = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

			while($row3 = mysqli_fetch_array($mysqli_result3))
			{
				$y = $row3[num2];
				$seed5_table[$y] += $row3[percent_wa];
			}

			# filter dups

			$query = "SELECT * FROM $draw_prefix";
			$query .= "temp_pairs_5000 ";
			$query .= "WHERE num1 = {$matrix[4][2]} ";
			$query .= "AND num2 > {$matrix[2][2]} ";
			$query .= "AND num2 > {$matrix[0][4]} ";
			$query .= "AND num2 > {$matrix[4][0]} ";
			$query .= "AND num2 >= $row_limit_ba[low] ";
			$query .= "AND num2 <= $row_limit_ba[high] ";
			$query .= "ORDER BY percent_wa DESC ";

			print("<p>$query</p>");
		
			$mysqli_result3 = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

			$num_rows_3 = mysqli_num_rows($mysqli_result3);

			print("<p>num_rows_3 = $num_rows_3</p>");

			while($row3 = mysqli_fetch_array($mysqli_result3))
			{
				$y = $row3[num2];
				$seed5_table[$y] += $row3[percent_wa];
			}

			# filter dups

			$max_array = array_fill (0, 2, 0);

			for ($z = 0; $z < 3; $z++)
			{
				$maxs = array_keys($seed5_table, max($seed5_table));
				$max_array[$z] = $maxs[0];
				echo "maxs = $max_array[$z]<br>";
				$seed5_table[$maxs[0]] = 0.0;
			}

			shuffle ($max_array);
			echo "seed5_table = ";
			print_r ($seed5_table);
			echo "<p>";
			#mt_srand((float) microtime() * 10000000);
			$rand_id = mt_rand(0,2);
			echo "rand_id = $rand_id<br>";
			$matrix[4][4] = $max_array[$rand_id];

			print_matrix ($matrix,$balls_drawn);

			# --07-- Diag 1 --------------------------------------------------------------------------------------

			echo "<hr>";
			echo "<b>Seed 7 - 4,0 - 0,4 - check wheel</b><p>";

			# ---- ba 5 ---- 4,4
			$query = "SELECT * FROM $draw_prefix";
			$query .= "filter_limits ";
			$query .= "WHERE col1 = '$col1' "; 
			$query .= "AND col_pos = '5' "; 
			$query .= "AND date = '$curr_date' ";
			$query .= "AND limit_type = 'ba' ";

			print("<p>$query</p>");
		
			$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

			if (!$num_rows_limit = mysqli_num_rows($mysqli_result))
			{
				die ("No limit found - ba $col1");
			}

			$row_limit_ba = mysqli_fetch_array($mysqli_result);
			# ---- ba 5 end ----

			$seed5_table = array();
			$y = 0;

			$query = "SELECT * FROM $draw_prefix";
			$query .= "filter_a_";
			if ($matrix[1][3] < 10)
			{
				$query .= "0{$matrix[1][3]} ";
			} else {
				$query .= "{$matrix[1][3]} ";
			}
			$query .= "WHERE b1 = {$matrix[1][3]} ";
			$query .= "AND b2 = {$matrix[0][4]} ";
			$query .= "AND b3 = {$matrix[2][2]} ";
			$query .= "AND b4 = {$matrix[4][0]} ";
			$query .= "AND b5 >= $row_limit_ba[low] ";
			$query .= "AND b5 <= $row_limit_ba[high] ";

			print("<p>$query</p>");
		
			$mysqli_result3 = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

			$num_rows_3 = mysqli_num_rows($mysqli_result3);

			print("<p>num_rows_3 = $num_rows_3</p>");

			$y = 0;

			while($row3 = mysqli_fetch_array($mysqli_result3))
			{
				$seed5_table[$y++] += $row3[b5];
			}

			shuffle ($seed5_table);
			#mt_srand((float) microtime() * 10000000);
			$rand_id = mt_rand(0,count($seed5_table)-1);
			echo "seed5_table = ";
			print_r ($seed5_table);
			echo "<p>";
			echo "rand_id = $rand_id<p>";
			$matrix[3][1] = $seed5_table[$rand_id];

			print_matrix ($matrix,$balls_drawn);

			# --08-- Diag 2 --------------------------------------------------------------------------------------

			echo "<hr>";
			echo "<b>Seed 8 - 0,0 - 4,4</b><p>";

			# ---- ba 2 ---- 4,4
			$query = "SELECT * FROM $draw_prefix";
			$query .= "filter_limits ";
			$query .= "WHERE col1 = '$col1' "; 
			$query .= "AND col_pos = '2' "; 
			$query .= "AND date = '$curr_date' ";
			$query .= "AND limit_type = 'ba' ";

			print("<p>$query</p>");
		
			$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

			if (!$num_rows_limit = mysqli_num_rows($mysqli_result))
			{
				die ("No limit found - ba $col1");
			}

			$row_limit_ba2 = mysqli_fetch_array($mysqli_result);
			# ---- ba 2 end ----

			# ---- ba 4 ---- 4,4
			$query = "SELECT * FROM $draw_prefix";
			$query .= "filter_limits ";
			$query .= "WHERE col1 = '$col1' "; 
			$query .= "AND col_pos = '4' "; 
			$query .= "AND date = '$curr_date' ";
			$query .= "AND limit_type = 'ba' ";

			print("<p>$query</p>");
		
			$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

			if (!$num_rows_limit = mysqli_num_rows($mysqli_result))
			{
				die ("No limit found - ba $col1");
			}

			$row_limit_ba4 = mysqli_fetch_array($mysqli_result);
			# ---- ba 4 end ----


			$seed5_table = array();
			$y = 0;

			$query = "SELECT * FROM $draw_prefix";
			$query .= "filter_a_";
			if ($matrix[0][0] > 9)
			{
				$query .= "{$matrix[0][0]} ";
			} else {
				$query .= "0{$matrix[0][0]} ";
			}
			$query .= "WHERE b1 = {$matrix[0][0]} ";
			$query .= "AND b3 = {$matrix[2][2]} ";
			$query .= "AND b5 = {$matrix[4][4]} ";
			$query .= "AND b1 < {$matrix[0][4]} ";
			$query .= "AND b2 > {$matrix[2][1]} ";
			$query .= "AND b2 > {$matrix[1][3]} ";
			$query .= "AND b3 > {$matrix[4][2]} ";
			$query .= "AND b4 > {$matrix[3][4]} ";
			#$query .= "AND b5 < {$matrix[0][4]} ";
			$query .= "AND b2 >= $row_limit_ba2[low] ";
			$query .= "AND b2 <= $row_limit_ba2[high] ";
			$query .= "AND b4 >= $row_limit_ba4[low] ";
			$query .= "ORDER BY RAND() ";
			#$query .= "LIMIT 1 ";
			
			print("<p>$query</p>");
		
			$mysqli_result3 = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

			$num_rows_3 = mysqli_num_rows($mysqli_result3);

			print("<p>num_rows_3 = $num_rows_3</p>");

			$row3 = mysqli_fetch_array($mysqli_result3);

			$matrix[1][1] = $row3[b2];
			$matrix[3][3] = $row3[b4];

			print_matrix ($matrix,$balls_drawn);

			# --9-- 4,0 - 4,4 - Top row ----------------------------------------------------------------------------

			echo "<hr>";
			echo "<b>Seed 9 - 4,0 - 4,4</b><p>";

			# ---- ba 2 ---- 4,3
			$query = "SELECT * FROM $draw_prefix";
			$query .= "filter_limits ";
			$query .= "WHERE col1 = '$col1' "; 
			$query .= "AND col_pos = '2' "; 
			$query .= "AND date = '$curr_date' ";
			$query .= "AND limit_type = 'ba' ";

			print("<p>$query</p>");
		
			$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

			if (!$num_rows_limit = mysqli_num_rows($mysqli_result))
			{
				die ("No limit found - ba $col1");
			}

			$row_limit_ba2 = mysqli_fetch_array($mysqli_result);
			# ---- ba 2 end ----

			# ---- ba 3 ---- 4,1
			$query = "SELECT * FROM $draw_prefix";
			$query .= "filter_limits ";
			$query .= "WHERE col1 = '$col1' "; 
			$query .= "AND col_pos = '3' "; 
			$query .= "AND date = '$curr_date' ";
			$query .= "AND limit_type = 'ba' ";

			print("<p>$query</p>");
		
			$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

			if (!$num_rows_limit = mysqli_num_rows($mysqli_result))
			{
				die ("No limit found - ba $col1");
			}

			$row_limit_ba3 = mysqli_fetch_array($mysqli_result);
			# ---- ba 4 end ----


			$seed5_table = array();
			$y = 0;

			$query = "SELECT * FROM $draw_prefix";
			$query .= "filter_a_";
			if ($matrix[4][2] > 9)
			{
				$query .= "{$matrix[4][2]} ";
			} else {
				$query .= "0{$matrix[4][2]} ";
			}
			$query .= "WHERE b1 = {$matrix[4][2]} ";
			$query .= "AND b4 = {$matrix[4][0]} ";
			$query .= "AND b5 = {$matrix[4][4]} ";
			$query .= "AND b2 < {$matrix[3][3]} ";
			$query .= "AND b3 > {$matrix[1][1]} ";
			$query .= "AND b4 < {$matrix[3][1]} ";
			$query .= "AND b2 >= $row_limit_ba2[low] ";
			$query .= "AND b2 <= $row_limit_ba2[high] ";
			$query .= "AND b3 >= $row_limit_ba3[low] ";
			$query .= "AND b3 <= $row_limit_ba3[high] ";
			$query .= "ORDER BY RAND() ";
			#$query .= "LIMIT 1 ";

			print("<p>$query</p>");
		
			$mysqli_result3 = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

			$num_rows_3 = mysqli_num_rows($mysqli_result3);

			print("<p>num_rows_3 = $num_rows_3</p>");

			$row3 = mysqli_fetch_array($mysqli_result3);

			$matrix[4][3] = $row3[b2];
			$matrix[4][1] = $row3[b3];

			print_matrix ($matrix,$balls_drawn);

			# --10/11-- 0,1 - 4,1 ---------------------------------------------------------------------
			/*
			echo "<hr>";
			echo "<b>Seed 10/11 - 0,1 - 4,1</b><p>";

			# ---- ba 5 ---- 4,3
			$query = "SELECT * FROM $draw_prefix";
			$query .= "filter_limits ";
			$query .= "WHERE col1 = '$col1' "; 
			$query .= "AND col_pos = '5' "; 
			$query .= "AND date = '$curr_date' ";
			$query .= "AND limit_type = 'ba' ";

			print("<p>$query</p>");
		
			$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

			if (!$num_rows_limit = mysqli_num_rows($mysqli_result))
			{
				die ("No limit found - ba $col1");
			}

			$row_limit_ba5 = mysqli_fetch_array($mysqli_result);
			# ---- ba 5 end ----


			$seed5_table = array();
			$y = 0;

			$query = "SELECT * FROM $draw_prefix";
			$query .= "filter_a_";
			if ($matrix[2][1] > 9)
			{
				$query .= "{$matrix[2][1]} ";
			} else {
				$query .= "0{$matrix[2][1]} ";
			}
			$query .= "WHERE b1 = {$matrix[2][1]} ";
			$query .= "AND b2 = {$matrix[1][1]} ";
			$query .= "AND b3 = {$matrix[4][1]} ";
			$query .= "AND b4 = {$matrix[3][1]} ";
			$query .= "AND b5 > {$matrix[3][1]} ";
			$query .= "AND b5 >= $row_limit_ba5[low] ";
			$query .= "AND b5 <= $row_limit_ba5[high] ";
			$query .= "ORDER BY RAND() ";

			print("<p>$query</p>");
		
			$mysqli_result3 = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

			$num_rows = mysqli_num_rows($mysqli_result3);
			print "num_rows = $num_rows<p>";

			$row3 = mysqli_fetch_array($mysqli_result3);

			$matrix[0][1] = $row3[b5];

			print_matrix ($matrix,$balls_drawn);
			*/

			# --10/11-- 0,0 - 0,4 - Second column ---------------------------------------------------------------------

			echo "<hr>";
			echo "<b>Seed 10/11 - 0,0 - 0,4</b><p>";

			# ---- ba 3 ---- 0,3
			$query = "SELECT * FROM $draw_prefix";
			$query .= "filter_limits ";
			$query .= "WHERE col1 = '$col1' "; 
			$query .= "AND col_pos = '3' "; 
			$query .= "AND date = '$curr_date' ";
			$query .= "AND limit_type = 'ba' ";

			print("<p>$query</p>");
		
			$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

			if (!$num_rows_limit = mysqli_num_rows($mysqli_result))
			{
				die ("No limit found - ba $col1");
			}

			$row_limit_ba3 = mysqli_fetch_array($mysqli_result);
			# ---- ba 3 end ----

			# ---- ba 5 ---- 0,2
			$query = "SELECT * FROM $draw_prefix";
			$query .= "filter_limits ";
			$query .= "WHERE col1 = '$col1' "; 
			$query .= "AND col_pos = '5' "; 
			$query .= "AND date = '$curr_date' ";
			$query .= "AND limit_type = 'ba' ";

			print("<p>$query</p>");
		
			$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

			if (!$num_rows_limit = mysqli_num_rows($mysqli_result))
			{
				die ("No limit found - ba $col1");
			}

			$row_limit_ba5 = mysqli_fetch_array($mysqli_result);
			# ---- ba 5 end ----

			$seed5_table = array();
			$y = 0;

			$query = "SELECT * FROM $draw_prefix";
			$query .= "filter_a_";
			if ($matrix[0][0] > 9)
			{
				$query .= "{$matrix[0][0]} ";
			} else {
				$query .= "0{$matrix[0][0]} ";
			}
			$query .= "WHERE b1 = {$matrix[0][0]} ";
			$query .= "AND b2 = {$matrix[0][4]} ";
			#$query .= "AND b4 = {$matrix[0][1]} ";
			$query .= "AND b3 > {$matrix[4][3]} ";
			$query .= "AND b4 > {$matrix[0][0]} ";
			$query .= "AND b4 < {$matrix[3][1]} ";
			$query .= "AND b3 < {$matrix[3][3]} ";
			$query .= "AND b5 > {$matrix[2][2]} ";
			$query .= "AND b3 >= $row_limit_ba3[low] ";
			$query .= "AND b3 <= $row_limit_ba3[high] ";
			$query .= "AND b5 >= $row_limit_ba5[low] ";
			$query .= "AND b5 <= $row_limit_ba5[high] "; 
			$query .= "ORDER BY RAND() ";

			print("<p>$query</p>");
		
			$mysqli_result3 = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

			$num_rows = mysqli_num_rows($mysqli_result3);
			print "num_rows = $num_rows<p>";

			$row3 = mysqli_fetch_array($mysqli_result3);

			$matrix[0][3] = $row3[b3];
			$matrix[0][1] = $row3[b4];
			$matrix[0][2] = $row3[b5];

			print_matrix ($matrix,$balls_drawn);

			# --12-- 0,3 - 4,3 ---------------------------------------------------------------------

			echo "<hr>";
			echo "<b>Seed 12 - 0,3 - 4,3</b><p>";

			# ---- ba 5 ---- 2,3
			$query = "SELECT * FROM $draw_prefix";
			$query .= "filter_limits ";
			$query .= "WHERE col1 = '$col1' "; 
			$query .= "AND col_pos = '5' "; 
			$query .= "AND date = '$curr_date' ";
			$query .= "AND limit_type = 'ba' ";

			print("<p>$query</p>");
		
			$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

			if (!$num_rows_limit = mysqli_num_rows($mysqli_result))
			{
				die ("No limit found - ba $col1");
			}

			$row_limit_ba5 = mysqli_fetch_array($mysqli_result);
			# ---- ba 5 end ----

			$seed5_table = array();
			$y = 0;

			$query = "SELECT * FROM $draw_prefix";
			$query .= "filter_a_";
			if ($matrix[1][3] > 9)
			{
				$query .= "{$matrix[1][3]} ";
			} else {
				$query .= "0{$matrix[1][3]} ";
			}
			$query .= "WHERE b1 = {$matrix[1][3]} ";
			$query .= "AND b2 = {$matrix[4][3]} ";
			$query .= "AND b3 = {$matrix[0][3]} ";
			$query .= "AND b4 = {$matrix[3][3]} ";
			$query .= "AND b5 > {$matrix[2][2]} ";
			$query .= "AND b5 >= $row_limit_ba5[low] ";
			$query .= "AND b5 <= $row_limit_ba5[high] ";
			$query .= "ORDER BY RAND() ";

			print("<p>$query</p>");
		
			$mysqli_result3 = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

			$num_rows = mysqli_num_rows($mysqli_result3);
			print "num_rows = $num_rows<p>";

			$row3 = mysqli_fetch_array($mysqli_result3);

			$matrix[2][3] = $row3[b5];

			print_matrix ($matrix,$balls_drawn);

			# --13-- 2,0 - 2,4 ---------------------------------------------------------------------

			echo "<hr>";
			echo "<b>Seed 13 - 2,0 - 2,4</b><p>";

			# ---- ba 2 ---- 2,0
			$query = "SELECT * FROM $draw_prefix";
			$query .= "filter_limits ";
			$query .= "WHERE col1 = '$col1' "; 
			$query .= "AND col_pos = '2' "; 
			$query .= "AND date = '$curr_date' ";
			$query .= "AND limit_type = 'ba' ";

			print("<p>$query</p>");
		
			$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

			if (!$num_rows_limit = mysqli_num_rows($mysqli_result))
			{
				die ("No limit found - ba $col1");
			}

			$row_limit_ba3 = mysqli_fetch_array($mysqli_result);
			# ---- ba 2 end ----

			# ---- ba 4 ---- 2,4
			$query = "SELECT * FROM $draw_prefix";
			$query .= "filter_limits ";
			$query .= "WHERE col1 = '$col1' "; 
			$query .= "AND col_pos = '4' "; 
			$query .= "AND date = '$curr_date' ";
			$query .= "AND limit_type = 'ba' ";

			print("<p>$query</p>");
		
			$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

			if (!$num_rows_limit = mysqli_num_rows($mysqli_result))
			{
				die ("No limit found - ba $col1");
			}

			$row_limit_ba4 = mysqli_fetch_array($mysqli_result);
			# ---- ba 4 end ----

			$seed5_table = array();
			$y = 0;

			$d1 = $matrix[2][1];

			$query = "SELECT * FROM $draw_prefix";
			$query .= "filter_a_";
			if ($matrix[2][1] > 9)
			{
				$query .= "{$matrix[2][1]} ";
			} else {
				$query .= "0{$matrix[2][1]} ";
			}
			$query .= "WHERE b1 = {$matrix[2][1]} ";
			$query .= "AND b3 = {$matrix[2][2]} ";
			$query .= "AND b5 = {$matrix[2][3]} ";
			$query .= "AND b2 > {$matrix[0][0]} ";
			$query .= "AND b2 < {$matrix[4][0]} ";
			$query .= "AND b4 < {$matrix[4][4]} ";
			$query .= "AND b2 >= $row_limit_ba2[low] ";
			$query .= "AND b2 <= $row_limit_ba2[high] ";
			$query .= "AND b4 >= $row_limit_ba4[low] ";
			$query .= "AND b4 <= $row_limit_ba4[high] "; 
			$query .= "ORDER BY RAND() ";

			print("<p>$query</p>");
		
			$mysqli_result3 = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));
	
			$num_rows = mysqli_num_rows($mysqli_result3);
			print "num_rows = $num_rows<p>";

			$row3 = mysqli_fetch_array($mysqli_result3);

			$matrix[2][0] = $row3[b2];
			$matrix[2][4] = $row3[b4];

			print_matrix ($matrix,$balls_drawn);

			# --14-- 1,4 ---------------------------------------------------------------------

			echo "<hr>";
			echo "<b>Seed 14 - 1,4</b><p>";

			# ---- ba 3 ---- 1,4
			$query = "SELECT * FROM $draw_prefix";
			$query .= "filter_limits ";
			$query .= "WHERE col1 = '$col1' "; 
			$query .= "AND col_pos = '3' "; 
			$query .= "AND date = '$curr_date' ";
			$query .= "AND limit_type = 'ba' ";

			print("<p>$query</p>");
		
			$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

			if (!$num_rows_limit = mysqli_num_rows($mysqli_result))
			{
				die ("No limit found - ba $col1");
			}

			$row_limit_ba5 = mysqli_fetch_array($mysqli_result);
			# ---- ba 5 end ----

			$seed5_table = array();
			$y = 0;

			$query = "SELECT * FROM $draw_prefix";
			$query .= "filter_a_";
			if ($matrix[3][4] > 9)
			{
				$query .= "{$matrix[3][4]} ";
			} else {
				$query .= "0{$matrix[3][4]} ";
			}
			$query .= "WHERE b1 = {$matrix[3][4]} ";
			$query .= "AND b2 = {$matrix[0][4]} ";
			$query .= "AND b4 = {$matrix[2][4]} ";
			$query .= "AND b5 = {$matrix[4][4]} ";
			$query .= "AND b3 > {$matrix[0][4]} ";
			$query .= "AND b3 < {$matrix[2][4]} ";
			$query .= "AND b3 > {$matrix[1][1]} ";
			$query .= "AND b3 >= $row_limit_ba3[low] ";
			$query .= "AND b3 <= $row_limit_ba3[high] ";
			$query .= "ORDER BY RAND() ";

			print("<p>$query</p>");
		
			$mysqli_result3 = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

			$num_rows = mysqli_num_rows($mysqli_result3);
			print "num_rows = $num_rows<p>";

			$row3 = mysqli_fetch_array($mysqli_result3);

			$matrix[1][4] = $row3[b3];

			print_matrix ($matrix,$balls_drawn);

			# --15-- 1,0 - 1,4 ---------------------------------------------------------------------

			echo "<hr>";
			echo "<b>Seed 15 - 1,0 - 1,4</b><p>";

			# ---- ba 4 ---- 1,2
			$query = "SELECT * FROM $draw_prefix";
			$query .= "filter_limits ";
			$query .= "WHERE col1 = '$col1' "; 
			$query .= "AND col_pos = '4' "; 
			$query .= "AND date = '$curr_date' ";
			$query .= "AND limit_type = 'ba' ";

			print("<p>$query</p>");
		
			$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

			if (!$num_rows_limit = mysqli_num_rows($mysqli_result))
			{
				die ("No limit found - ba $col1");
			}

			$row_limit_ba4 = mysqli_fetch_array($mysqli_result);
			# ---- ba 4 end ----

			# ---- ba 5 ---- 1,0
			$query = "SELECT * FROM $draw_prefix";
			$query .= "filter_limits ";
			$query .= "WHERE col1 = '$col1' "; 
			$query .= "AND col_pos = '5' "; 
			$query .= "AND date = '$curr_date' ";
			$query .= "AND limit_type = 'ba' ";

			print("<p>$query</p>");
		
			$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

			if (!$num_rows_limit = mysqli_num_rows($mysqli_result))
			{
				die ("No limit found - ba $col1");
			}

			$row_limit_ba5 = mysqli_fetch_array($mysqli_result);
			# ---- ba 5 end ----

			$seed5_table = array();
			$y = 0;

			$d1 = $matrix[2][1];

			$query = "SELECT * FROM $draw_prefix";
			$query .= "filter_a_";
			if ($matrix[1][3] > 9)
			{
				$query .= "{$matrix[1][3]} ";
			} else {
				$query .= "0{$matrix[1][3]} ";
			}
			$query .= "WHERE b1 = {$matrix[1][3]} ";
			$query .= "AND b2 = {$matrix[1][1]} ";
			$query .= "AND b3 = {$matrix[1][4]} ";
			$query .= "AND b4 > {$matrix[2][2]} ";
			$query .= "AND b4 < {$matrix[0][2]} ";
			$query .= "AND b5 > {$matrix[4][0]} ";
			$query .= "AND b4 >= $row_limit_ba4[low] ";
			$query .= "AND b4 <= $row_limit_ba4[high] ";
			$query .= "AND b5 >= $row_limit_ba5[low] ";
			$query .= "AND b5 <= $row_limit_ba5[high] "; 
			$query .= "ORDER BY RAND() ";

			print("<p>$query</p>");
		
			$mysqli_result3 = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

			$num_rows = mysqli_num_rows($mysqli_result3);
			print "num_rows = $num_rows<p>";

			$row3 = mysqli_fetch_array($mysqli_result3);

			$matrix[1][2] = $row3[b4];
			$matrix[1][0] = $row3[b5];

			print_matrix ($matrix,$balls_drawn);

			# --16-- 3,0 - 3,4 ---------------------------------------------------------------------

			echo "<hr>";
			echo "<b>Seed 16 - 3,0 - 3,4</b><p>";

			# ---- ba 2 ---- 3,2
			$query = "SELECT * FROM $draw_prefix";
			$query .= "filter_limits ";
			$query .= "WHERE col1 = '$col1' "; 
			$query .= "AND col_pos = '2' "; 
			$query .= "AND date = '$curr_date' ";
			$query .= "AND limit_type = 'ba' ";

			print("<p>$query</p>");
		
			$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

			if (!$num_rows_limit = mysqli_num_rows($mysqli_result))
			{
				die ("No limit found - ba $col1");
			}

			$row_limit_ba3 = mysqli_fetch_array($mysqli_result);
			# ---- ba 2 end ----

			# ---- ba 3 ---- 3,0
			$query = "SELECT * FROM $draw_prefix";
			$query .= "filter_limits ";
			$query .= "WHERE col1 = '$col1' "; 
			$query .= "AND col_pos = '3' "; 
			$query .= "AND date = '$curr_date' ";
			$query .= "AND limit_type = 'ba' ";

			print("<p>$query</p>");
		
			$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

			if (!$num_rows_limit = mysqli_num_rows($mysqli_result))
			{
				die ("No limit found - ba $col1");
			}

			$row_limit_ba3 = mysqli_fetch_array($mysqli_result);
			# ---- ba 3 end ----

			$seed5_table = array();
			$y = 0;

			$d1 = $matrix[2][1];

			$query = "SELECT * FROM $draw_prefix";
			$query .= "filter_a_";
			if ($matrix[3][4] > 9)
			{
				$query .= "{$matrix[3][4]} ";
			} else {
				$query .= "0{$matrix[3][4]} ";
			}
			$query .= "WHERE b1 = {$matrix[3][4]} ";
			$query .= "AND b4 = {$matrix[3][3]} ";
			$query .= "AND b5 = {$matrix[3][1]} ";
			$query .= "AND b2 > {$matrix[4][2]} ";
			$query .= "AND b2 < {$matrix[2][2]} ";
			$query .= "AND b3 > {$matrix[2][0]} ";
			$query .= "AND b3 < {$matrix[4][0]} ";
			$query .= "AND b2 >= $row_limit_ba2[low] ";
			$query .= "AND b2 <= $row_limit_ba2[high] ";
			$query .= "AND b3 >= $row_limit_ba3[low] ";
			$query .= "AND b3 <= $row_limit_ba3[high] "; 
			$query .= "ORDER BY RAND() ";

			print("<p>$query</p>");
		
			$mysqli_result3 = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

			$num_rows = mysqli_num_rows($mysqli_result3);
			print "num_rows = $num_rows<p>";

			$row3 = mysqli_fetch_array($mysqli_result3);

			$matrix[3][2] = $row3[b2];
			$matrix[3][0] = $row3[b3];

			print_matrix ($matrix,$balls_drawn);

			die ("Die");

			# seed 2 ---- seed 5 pair sigmas 0,0/1,1/2,2/3,3/4,4


			# seed 3 ----


			# row 0,0-0,4

			# row 2,0-2,4

			# row 4,0-4,4

			# row 0,0-4,0

			# row 0,2-4,2

			# row 0,4-4,4

			# row 3,0-4,3

			# cell 2,3

			# cell 1,1

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
	
	//start HTML page
	print("<HTML>\n");
	print("<HEAD>\n");
	print("<TITLE>$game_name Matrix - $game_name</TITLE>\n");
	print("</HEAD>\n");
	
	print("<BODY>\n");

	lot_matrix ($col1=0,$limit=1,$delete_temp=0);
	
	print("</BODY>\n");
?>
