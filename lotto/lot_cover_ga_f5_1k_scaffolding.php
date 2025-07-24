<?php

	$game = 1; // Georgia F5
	$draw_table_name = "ga_f5_draws";

	set_time_limit(0);

	$k = 1;

	$debug = 1;

	error_reporting(1);

	if ($debug)
	{
		ini_set('display_errors', '1');
		ini_set('display_startup_errors', '1');
		error_reporting(E_ALL);
	} else {
		error_reporting(0);
	}

	require ("includes/games_switch.incl");

	// include to connect to database
	require ("includes/mysqli.php");
	require ("includes/build_rank_table.php");
	require ("includes/count_2_seq.php");
	require ("includes/count_3_seq.php");
	#require ("includes_fl/build_rank_table_fl.php");
	#require ("includes_fl/calculate_rank_fl.php");
	require ("includes/dateDiffInDays.php");
	require ("includes/first_draw_unix.php");
	require ("includes/last_draw_unix.php");
	require ("includes/last_draws.php");
	require ("includes_ga_f5/last_draws_ga_f5.php");
	require ("includes_ga_f5/combin.incl");
	require ("includes_ga_f5/split_sumeo_2.php");
	require ("includes_ga_f5/split_sumeo_3.php");
	require ("includes_ga_f5/split_sumeo_4.php");
	require ("includes_ga_f5/split_sumeo_5.php");

	date_default_timezone_set('America/New_York');

	//start HTML page
	print("<HTML>\n");
	print("<HEAD>\n");
	print("<TITLE>Lotto Cover 1K - 5/42 - Scaffolding</TITLE>\n");
	print("</HEAD>\n");
	
	print("<BODY bgcolor=\"#FFFFFF\" text=\"#000000\">\n");

	$curr_date = date('Y-m-d');
	$currdate = date('ymd');

	$drop_tables = 0;	### <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<

	#$update_level = 1;	### ALL

	$update_level = 2;	### dup, rank

	$temp_table1 = 'temp_cover_1k_count_' .  $currdate;

	$temp_table2 = 'temp_cover_1k_scaffolding_' .  $currdate;

	$temp_table4 = 'temp_cover_1k_candidates_scaffolding_' .  $currdate;
	
	if ($drop_tables)
	{
		$query = "DROP TABLE IF EXISTS $temp_table1 ";

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

		$query =  "CREATE TABLE IF NOT EXISTS  $temp_table1 ( ";
		$query .= "  `id` int(10) unsigned NOT NULL auto_increment, ";
		$query .= "  `sum` int(3) unsigned NOT NULL default '0', ";
		$query .= "  `even` tinyint(1) unsigned NOT NULL default '0', ";
		$query .= "  `odd` tinyint(1) unsigned NOT NULL default '0', ";
		$query .= "  `k_count` tinyint(2) unsigned NOT NULL default '0', ";
		$query .= "  `last_updated` date NOT NULL default '1962-08-17', ";
		$query .= "  PRIMARY KEY  (`id`) ";
		$query .= ") ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 "; 

		print("$query<p>");

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

	}

		$query = "DROP TABLE IF EXISTS $temp_table2 ";

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

		$query = "DROP TABLE IF EXISTS $temp_table2 ";

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));
		
		$query =  "CREATE TABLE IF NOT EXISTS  $temp_table2 ( ";
		$query .= "  `id` int(10) unsigned NOT NULL auto_increment, ";
		$query .= "  `b1` tinyint(2) unsigned NOT NULL default '0', ";
		$query .= "  `b2` tinyint(2) unsigned NOT NULL default '0', ";
		$query .= "  `b3` tinyint(2) unsigned NOT NULL default '0', ";
		$query .= "  `b4` tinyint(2) unsigned NOT NULL default '0', ";
		$query .= "  `b5` tinyint(2) unsigned NOT NULL default '0', ";
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
		$query .= "  `rank7` tinyint(1) unsigned NOT NULL default '0', ";
		$query .= "  `mod_tot` tinyint(1) unsigned NOT NULL default '0', ";
		$query .= "  `mod_x` tinyint(1) unsigned NOT NULL default '0', ";
		$query .= "  `seq2` tinyint(1) unsigned NOT NULL default '0', ";
		$query .= "  `seq3` tinyint(1) unsigned NOT NULL default '0', ";
		$query .= "  `comb2` tinyint(1) unsigned NOT NULL default '0', ";
		$query .= "  `comb3` tinyint(1) unsigned NOT NULL default '0', ";
		$query .= "  `comb4` tinyint(1) unsigned NOT NULL default '0', ";
		$query .= "  `comb5` tinyint(1) unsigned NOT NULL default '0', ";
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
		$query .= "  `y1_sum` float(4,2) NOT NULL default '0.00', ";
		$query .= "  `last_updated` date NOT NULL default '1962-08-17', ";
		$query .= "  PRIMARY KEY  (`id`) ";
		$query .= ") ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 "; 

		echo("$query<p>");

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

	$query = "DROP TABLE IF EXISTS $temp_table4 ";

	$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

	$query =  "CREATE TABLE IF NOT EXISTS  $temp_table4 ( ";
	$query .= "  `id` int(10) unsigned NOT NULL auto_increment, ";
	$query .= "  `b1` tinyint(2) unsigned NOT NULL default '0', ";
	$query .= "  `b2` tinyint(2) unsigned NOT NULL default '0', ";
	$query .= "  `b3` tinyint(2) unsigned NOT NULL default '0', ";
	$query .= "  `b4` tinyint(2) unsigned NOT NULL default '0', ";
	$query .= "  `b5` tinyint(2) unsigned NOT NULL default '0', ";
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
	$query .= "  `rank7` tinyint(1) unsigned NOT NULL default '0', ";
	$query .= "  `mod_tot` tinyint(1) unsigned NOT NULL default '0', ";
	$query .= "  `mod_x` tinyint(1) unsigned NOT NULL default '0', ";
	$query .= "  `seq2` tinyint(1) unsigned NOT NULL default '0', ";
	$query .= "  `seq3` tinyint(1) unsigned NOT NULL default '0', ";
	$query .= "  `comb2` tinyint(1) unsigned NOT NULL default '0', ";
	$query .= "  `comb3` tinyint(1) unsigned NOT NULL default '0', ";
	$query .= "  `comb4` tinyint(1) unsigned NOT NULL default '0', ";
	$query .= "  `comb5` tinyint(1) unsigned NOT NULL default '0', ";
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
	$query .= "  `y1_sum` float(4,2) NOT NULL default '0.00', ";
	$query .= "  `last_updated` date NOT NULL default '1962-08-17', ";
	$query .= "  PRIMARY KEY  (`id`) ";
	$query .= ") ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 "; 

	echo("$query<p>");

	$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

	$max_num = 42;

	$count = 0;
	$count_all = 0;
	$print_flag = 0;

	// ----------------------------------------------------------------------------------
	function print_sum_grid_sum4_sumeo($combin,$sumeo_sum,$sumeo_even,$sumeo_odd)
	{
		global $draw_table_name, $balls, $balls_drawn, $draw_prefix, $col1_select, $hml, $range_low, $range_high; 

		require ("includes/mysqli.php");
	
		require ("includes/calculate_sum_count_sum4_sumeo.incl");

		require ("includes/print_sum_table_sum4_sumeo.incl");

		#require ("includes/print_sum_table_sum_top25_4_sumeo.incl");

		#print "<h3>Table <font color=\"#ff0000\">{$draw_prefix}sum $combin</font> Updated!</h3>";
	}

	### select 1k_count for each sumeo ################################################################################
	$query3 = "SELECT DISTINCT sum,even,odd,k_count FROM $temp_table1 ";
	$query3 .= "ORDER BY `k_count` DESC  ";

	#echo "<p>$query3</p>";

	$mysqli_result3 = mysqli_query($mysqli_link, $query3) or die (mysqli_error($mysqli_link));

	while($row3 = mysqli_fetch_array($mysqli_result3))	
	{
		echo "<b>row3 - $row3[sum], $row3[even], $row3[odd] - $row3[k_count]</b><br>";

		# build draws table sumeo and k_count
		for ($c = 1; $c <= $row3[3]; $c++)
		{
			$query7= "INSERT INTO $temp_table2 ";
			$query7.= "VALUES ('0', ";
			$query7.= "'0', ";
			$query7.= "'0', ";
			$query7.= "'0', ";
			$query7.= "'0', ";
			$query7.= "'0', ";
			$query7.= "'$row3[sum]', ";
			$query7.= "'0', ";
			$query7.= "'$row3[even]', ";
			$query7.= "'$row3[odd]', ";
			for ($t = 6; $t <= 52; $t++)
			{
				$query7.= "'0', ";
			}
			$query7.= "'1962-08-17', ";
			$query7.= "'0', ";
			$query7.= "'0', ";
			$query7.= "'1962-08-17') ";

			#echo "$query7<br>";

			$mysqli_result_7 = mysqli_query($mysqli_link, $query7) or die (mysqli_error($mysqli_link));
		}	
	}
	### reset mysqli pointer ### 
	mysqli_data_seek($mysqli_result3,0);

	### reread sum_count_sum and add col1 based on temp_count
	while($row3 = mysqli_fetch_array($mysqli_result3))	
	{
		$temp_table4 = 'temp2_column_sumeo_' . $row3['sum'] . '_' . $row3['even'] . '_' . $row3['odd'] . '_1';

		### build col1 array
		$col1_array = [];

		#$temp_table4 = 'temp2_column_sumeo_' . $row3['sum'] . '_' . $row3['even'] . '_' . $row3['odd'] . '_1';

		$query2 = "SELECT * FROM $temp_table4 ";
		$query2 .= "WHERE percent_wa >= 0.1 ";
		$query2 .= "ORDER BY percent_wa DESC ";

		#echo "<p>$query2</p>";

		$mysqli_result_2 = mysqli_query($mysqli_link, $query2) or die (mysqli_error($mysqli_link));

		$num_rows_2 = mysqli_num_rows($mysqli_result_2);

		if (!$num_rows_2)
		{
			$query2 = "SELECT * FROM $temp_table4 ";
			#$query2 .= "WHERE percent_wa >= 0.1 ";
			$query2 .= "ORDER BY percent_wa DESC ";

			#echo "<p>$query2</p>";

			$mysqli_result_2 = mysqli_query($mysqli_link, $query2) or die (mysqli_error($mysqli_link));

			$num_rows_2 = mysqli_num_rows($mysqli_result_2);
		}

		while($row2 = mysqli_fetch_array($mysqli_result_2))
		{	
			$col1_array[] = $row2['num'];
		}

		#print_r ($col1_array);	

		### read sumeo from $temp_table2
		$d = 0;

		$query8 = "SELECT * FROM $temp_table2  ";
		$query8 .= "WHERE sum = $row3[sum] ";
		$query8 .= "AND even = $row3[even] ";
		$query8 .= "AND odd = $row3[odd] ";

		#echo "<p>$query8</p>";

		$mysqli_result8 = mysqli_query($mysqli_link, $query8) or die (mysqli_error($mysqli_link));

		$q = 0;

		while($row8 = mysqli_fetch_array($mysqli_result8))
		{
			#echo "id = $row8[id]<br>";

			$query_update = "UPDATE $temp_table2 ";
			$query_update .= "SET b1 = $col1_array[$q] ";
			$query_update .= "WHERE id = $row8[id] ";

			#echo "$query_update<p>";

			$mysqli_result_update = mysqli_query ($mysqli_link, $query_update) or die (mysqli_error($mysqli_link));

			if ($q > ($num_rows_2-2))
			{
				$q = 0;
			} else {
				$q++;
			}
		}
	}

	#########################################################################################################
	### echo col1 count
	#########################################################################################################
	print "<p>";

	// Begin the table
	print "<table border='1'>"; // Add table border for visibility
	print "<tr><th>Col1</th><th>Count</th></tr>"; // Table headers

	$query2 = "SELECT b1,count(b1) FROM $temp_table2 ";
	$query2 .= "GROUP BY b1 ";
	$query2 .= "ORDER BY count(b1) DESC ";

	#echo "<p>$query2</p>";

	$mysqli_result_2 = mysqli_query($mysqli_link, $query2) or die (mysqli_error($mysqli_link));

	while($row2 = mysqli_fetch_array($mysqli_result_2))
	{
		print "<tr><td>$row2[0]</td><td>$row2[1]</td></tr>";
	}

	#########################################################################################################
	### echo col1 count group by sumeo
	#########################################################################################################
	// Begin the table
	echo "<p><table border='1'></p>"; // Add table border for visibility
	echo "<tr><th>Sum</th><th>Even</th><th>Odd</th><th>Col1</th><th>Count</th></tr>"; // Table headers

	#$query2 = "SELECT sum,b1,count(b1) FROM $temp_table2 ";	### 240525
	#$query2 .= "GROUP BY sum ";
	#$query2 .= "ORDER BY count(b1) DESC ";

	$query2 = "SELECT DISTINCT `sum`, `even`, `odd`, `b1`  
	FROM $temp_table2  
	ORDER BY `sum` ASC";

	#echo "<p>$query2</p>";

	$mysqli_result_2 = mysqli_query($mysqli_link, $query2) or die (mysqli_error($mysqli_link));

	while($row2 = mysqli_fetch_array($mysqli_result_2))
	{
		$query3 = "SELECT `b1`, count(`b1`)
		FROM $temp_table2 
		WHERE sum=$row2[sum] AND even=$row2[even] AND odd=$row2[odd] AND b1=$row2[b1]";

		#echo "<p>$query3</p>";

		$mysqli_result_3 = mysqli_query($mysqli_link, $query3) or die (mysqli_error($mysqli_link));

		$row3 = mysqli_fetch_array($mysqli_result_3);

		echo "<tr><td align=center>$row2[0]</td><td align=center>$row2[1]</td><td align=center>$row2[2]</td><td align=center><b>$row2[3]</b></td><td align=center>$row3[1]</td></tr>";
	}

	// End the table
	echo "</table>";

	### 240620 copied

	### reset mysqli pointer ### 
	mysqli_data_seek($mysqli_result3,0);

	### reread sum_count_sum 
	while($row3 = mysqli_fetch_array($mysqli_result3))	
	{
		### read sum_count_sum and b1 DISTINCT
		$query4 = "SELECT DISTINCT b1 FROM $temp_table2 ";
		$query4 .= "WHERE sum = $row3[sum] ";
		$query4 .= "AND even = $row3[even] ";
		$query4 .= "AND odd = $row3[odd] ";
		#$query4 .= "AND b1  <= 15 ";	### 240430

		echo "<p>4- $query4</p>";

		$mysqli_result4 = mysqli_query($mysqli_link, $query4) or die (mysqli_error($mysqli_link));

		$num_rows4 = mysqli_num_rows($mysqli_result4);

		echo "num_rows4 = $num_rows4<br>";

		while($row4 = mysqli_fetch_array($mysqli_result4))
		{
			### read sum_count_sum and each b1 
			$query5 = "SELECT * FROM $temp_table2 ";
			$query5 .= "WHERE sum = $row3[sum] ";
			$query5 .= "AND even = $row3[even] ";
			$query5 .= "AND odd = $row3[odd] ";
			$query5 .= "AND b1 = $row4[b1] ";	### b1 ###

			echo "<p>5b - $query5</p>";

			$mysqli_result5 = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));

			$num_rows5 = mysqli_num_rows($mysqli_result5);

			echo "num_rows5 = $num_rows5<br>";

			$q = 0;
			$update5 = 0;

			while($row5 = mysqli_fetch_array($mysqli_result5))
			{ 
				if ($update5 == 0)
				{
					### build col5 array
					$col5_array = [];

					$tableName = 'temp_split_count_1_3_5_' . $row5['sum'] . '_' . $row5['even'] . '_' . $row5['odd'];

					/*
					$query9 = "SELECT COUNT(*) AS cnt 
							  FROM INFORMATION_SCHEMA.TABLES 
							  WHERE TABLE_SCHEMA = :dbName 
							  AND TABLE_NAME = :tableName";
					*/

					$query9 = "SHOW TABLES LIKE '$tableName'";

					echo "<p>9 - $query9</p>";

					$mysqli_result9 = mysqli_query($mysqli_link, $query9) or die (mysqli_error($mysqli_link));

					$num_rows9 = mysqli_num_rows($mysqli_result9);

					echo "<p>num_rows9 - $num_rows9</p>";

					if ($num_rows9 > 0) {
						echo "Table exists.";

						### read sum_count_sum and b1 to build col5_array #####################################################
						$query6 = "SELECT DISTINCT b5 FROM temp_split_count_1_3_5_";
						$query6 .= "$row3[sum]_";
						$query6 .= "$row3[even]_";
						$query6 .= "$row3[odd] ";
						$query6 .= "WHERE b1 = $row5[b1] ";	### b1 ###
						$query6 .= "ORDER BY `y1_sum` DESC "; 

						echo "<p>6 - $query6</p>";

						$mysqli_result6 = mysqli_query($mysqli_link, $query6) or die (mysqli_error($mysqli_link));

						$num_rows6 = mysqli_num_rows($mysqli_result6);

						echo "num_rows6 = $num_rows6<br>";

						while($row6 = mysqli_fetch_array($mysqli_result6))
						{
							$col5_array[] = $row6['b5'];
						}

						print_r ($col5_array);

						$update5++;

						if ($num_rows6)	### 240430
						{
							$query_update = "UPDATE $temp_table2 ";
							$query_update .= "SET b5 = $col5_array[$q] ";
							$query_update .= "WHERE id = $row5[id] ";

							if ($q > ($num_rows6-2))
							{
								$q = 0;
							} else {
								$q++;
							}

							#echo "<p>update - $query_update<p>";

							$mysqli_result_update = mysqli_query ($mysqli_link, $query_update) or die (mysqli_error($mysqli_link));
						}
					} else {
						echo ">>>>> Table does not exist.<br>";
						echo ">>>>> running split_sumeo_5 - $row5[sum],$row5[even],$row5[odd]<br>";
						#split_sumeo_5 ($row5['sum'],$row5['even'],$row5['odd']);	### 240620 ###
						#die();
					}

				}
			}
			
			### 240429
			
			foreach ($col5_array as $col5_value) {
				### read sum_count_sum and each b1 to build col3_array ################################################
				### build col3 array
				$col3_array = [];
				
				$query7 = "SELECT DISTINCT b3 FROM temp_split_count_1_3_5_";
				$query7 .= "$row3[sum]_";
				$query7 .= "$row3[even]_";
				$query7 .= "$row3[odd] ";
				$query7 .= "WHERE  b1 = $row4[b1] ";	### b1 ###
				$query7 .= "AND    b5 = $col5_value ";	### b5 ### 
				$query7 .= "ORDER BY `y1_sum` DESC "; 

				echo "<p>7a - $query7</p>";

				$mysqli_result7 = mysqli_query($mysqli_link, $query7) or die (mysqli_error($mysqli_link));

				$num_rows7 = mysqli_num_rows($mysqli_result7);

				echo "num_rows7 = $num_rows7<br>";

				while($row7 = mysqli_fetch_array($mysqli_result7))
				{
					$col3_array[] = $row7['b3'];
				}

				print_r ($col3_array);

				### read sum_count_sum and each b1 
				$query5 = "SELECT * FROM $temp_table2 ";
				$query5 .= "WHERE sum = $row3[sum] ";
				$query5 .= "AND even = $row3[even] ";
				$query5 .= "AND odd = $row3[odd] ";
				$query5 .= "AND b1 = $row4[b1] ";	### b1 ###
				$query5 .= "AND b3 = 0 ";	### b3 ###
				$query5 .= "AND b5 = $col5_value ";	### b3 ### 240429 <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<

				echo "<p>5b - $query5</p>";

				$mysqli_result5 = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));

				$num_rows5 = mysqli_num_rows($mysqli_result5);

				echo "num_rows5 = $num_rows5<br>";

				$q = 0;
				$update5 = 0;

				while($row5 = mysqli_fetch_array($mysqli_result5))
				{
					echo "q = $q<br>";

					$query_update = "UPDATE $temp_table2 ";
					$query_update .= "SET b3 = $col3_array[$q] ";
					$query_update .= "WHERE id = $row5[id] ";

					if ($q > ($num_rows7-2))
					{
						$q = 0;
					} else {
						$q++;
					}

					echo "<p>update - $query_update<p>";

					$mysqli_result_update = mysqli_query ($mysqli_link, $query_update) or die (mysqli_error($mysqli_link));
				}
			}

			###############################################################################################################
			### select temp_sumeo_draw_
			###############################################################################################################
			$query7 = "SELECT * FROM $temp_table2  ";
			$query7 .= "WHERE sum = $row3[sum] ";
			$query7 .= "AND even = $row3[even] ";
			$query7 .= "AND odd = $row3[odd] ";
			$query7 .= "AND b1 = $row4[b1] ";	### b1 ### 

			echo "<p>7b - $query7</p>";

			$mysqli_result7 = mysqli_query($mysqli_link, $query7) or die (mysqli_error($mysqli_link));

			$num_rows7 = mysqli_num_rows($mysqli_result7);

			echo "num_rows7 = $num_rows7<br>";

			while($row7 = mysqli_fetch_array($mysqli_result7))
			{
				$temp_table3 = 'temp_sumeo_draw_' . $row3['sum'] . '_' . $row3['even'] . '_' . $row3['odd'];

				### filter $temp_table3 240325 ###
				$query_dc = "SELECT * FROM $temp_table3  ";
				$query_dc .= "WHERE sum = $row3[sum] ";
				$query_dc .= "AND even = $row3[even] ";
				$query_dc .= "AND odd = $row3[odd] ";
				$query_dc .= "AND b1 = $row7[b1] ";	### b1 ### 
				$query_dc .= "AND b3 = $row7[b3] ";	### b3 ###
				$query_dc .= "AND b5 = $row7[b5] ";	### b5 ###
				$query_dc .= "ORDER BY y1_sum DESC ";	### b5 ###
				$query_dc .= "LIMIT 1 ";	### b5 ###

				echo "$query_dc<br>";

				$mysqli_result_dc = mysqli_query($mysqli_link, $query_dc) or die (mysqli_error($mysqli_link));

				while($row_dc = mysqli_fetch_array($mysqli_result_dc))
				{
					$query_update = "UPDATE $temp_table2 ";
					$query_update .= "SET b2 = $row_dc[b2], ";
					$query_update .= "b4 = $row_dc[b4] ";
					$query_update .= "WHERE id = $row7[id] ";

					#echo "<p>update - $query_update<p>";

					$mysqli_result_update = mysqli_query ($mysqli_link, $query_update) or die (mysqli_error($mysqli_link));
				}
			}
		}
	}
?>