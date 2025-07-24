<?php

	$game = 1; // All or Nothing
	// Game ----------------------------- Game

	$hml = 100;

	$col1_select = 1;

	require ("includes/games_switch.incl");

	require_once ("includes/hml_switch.incl");	

	// include to connect to database
	require ("includes/mysqli.php");
	require ("includes/count_2_seq.php");
	require ("includes/count_3_seq.php");
	require ("includes/first_draw_unix.php");
	require ("includes/last_draw_unix.php");
	require ("includes/last_draws.php");
	require ("includes/unix.incl");
	require ("includes_ga_f5/last_draws_ga_f5.php");
	require ("includes/build_rank_table.php");
	require ("includes/calculate_draw.php"); 
	require ("includes/calculate_rank.php");
	#require ("includes_fl/calculate_rank_fl.php");
	#require ("includes_ga_f5/last_draws_ga_f5.php");
	require ("includes_ga_f5/combin.incl");

	date_default_timezone_set('America/New_York');

	$curr_date = date("Y-m-d");

	for ($col = 1; $col <= 2; $col++)
	{
		print_column_test($col);
	}

	#---------------------------------------------------------------------------------------------------------------
	# Update 
	
	### build lastxdraws
	
		for ($x = 1; $x <= 50; $x++)
		{
			${last_.$x._draws} = array_fill (0,51,0);
		}

		for ($x = 1; $x <= 25; $x++)
		{
			${last_.$x._draws} = LastDraws($curr_date,$x);
			echo "last_.$x._draws<br>";
			print_r (${last_.$x._draws});
			echo "<p>";
		}	

		#$rank_count = BuildRankTable($curr_date); // array 0..balls with total draws for last 2

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

		# update dup\\
		
		echo "<h1>Begin</h1>";

		echo "<h2>hml = $hml</h2>";

		require ("includes_ga_f5/build_draw_count_hml.incl");	
		require ("includes_ga_f5/decade_draw_range.incl");
		require ("includes_ga_f5/build_combo_count_hml.incl");
		require ("includes_ga_f5/build_dup_count_hml.incl");
		require ("includes_ga_f5/build_dup_limit_all.incl");
		require ("includes_ga_f5/build_even_odd_limit_all_hml.incl");

		$hml9 = $hml + 9;

		$query_dup = "SELECT * FROM combo_5_39 ";
		
		$query_dup .= "AND   last_updated < '$curr_date' ";
		$query_dup .= "ORDER BY b2, b3 ASC ";

		echo "$query_dup<br>";

		$mysqli_result_dup = mysqli_query($query_dup, $mysqli_link) or die (mysqli_error($mysqli_link));

		$num_rows_dup = mysqli_num_rows($mysqli_result_dup);

		echo "dup rows to update - $num_rows_dup<br>";

		while($row = mysqli_fetch_array($mysqli_result_dup))
		{
			$reject = 0;

			$c = 1;

			echo "<strong>$row[b1], $row[b2], $row[b3], $row[b4], $row[b5]</strong><br>";
			
			$dup_count = array_fill (0,7,0);

			//count repeating numbers
			for ($x = 1 ; $x <= 25; $x++)
			{
				for ($y = 1 ; $y <= 5; $y++)
				{	
					$temp_b = "b" . $y;

					if (array_search($row[$temp_b], ${last_.$x._draws}) !== FALSE)
					{
						$dup_count[$x]++;
						#echo "dup - $row[$temp_b] found - dup_count[$x] = $dup_count[$x]<br>";
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
				if ($rank_count[$row[$y]] >= 6)
				{
					$draw_rank_count[6]++;
					$temp_rank = 6;
				} else {
					$temp_rank = $rank_count[$row[$y]];
					$draw_rank_count[$temp_rank]++;
				}
				#echo "rank -- $row[$y] = $temp_rank<br>";
			}

			$total_combin = array_fill (0,7,0);

			$total_combin = test_combin($row);

			$query_eo50 = "SELECT * FROM ga_f5_wheel_sum_table  ";
			$query_eo50 .= "WHERE sum = $row[sum] ";
			$query_eo50 .= "AND   even = $row[even] ";
			$query_eo50 .= "AND   odd = $row[odd] ";
			$query_eo50 .= "AND   d501 = $row[d2_1] ";
			$query_eo50 .= "AND   d502 = $row[d2_2] ";

			#print "$query_eo50<br>";

			$mysqli_result_eo50 = mysqli_query($query_eo50, $mysqli_link) or die (mysqli_error($mysqli_link));

			$row_eo50 = mysqli_fetch_array($mysqli_result_eo50);

			require ("includes_ga_f5/m6_sum.incl");

			$query9 = "UPDATE combo_5_39 ";
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

			if ($row[last_updated] < '2014-12-14')
			{
				$row[date] = $curr_date;

				require ("includes/combo_count_date.incl");

				$query9 .= "    nums_total_2 = $temp_nums_count_2, ";
				$query9 .= "    combo_total_2 = $temp_combo_count_2, ";
				$query9 .= "    nums_total_3 = $temp_nums_count_3, ";
				$query9 .= "    combo_total_3 = $temp_combo_count_3, ";
				$query9 .= "    nums_total_4 = $temp_nums_count_4, ";
				$query9 .= "    combo_total_4 = $temp_combo_count_4, ";
			}

			$query9 .= "    wheel_percent_wa = $row_eo50[wa], ";
			$query9 .= "    m6_sum = $m6_sum, ";
			$query9 .= "    last_updated = '$curr_date' ";
			$query9 .= "WHERE id = '$row[id]' ";

			echo "$query9<br>";
			
			$mysqli_result9 = mysqli_query($mysqli_link, $query9) or die (mysqli_error($mysqli_link));


	#---------------------------------------------------------------------------------------------------------------

		global $debug, $draw_table_name, $balls, $balls_drawn, $draw_prefix, $game, $hml, $range_low, $range_high; 

		require ("includes/mysqli.php");

		$hml9 = $hml + 9;
		
		$sum_array = array();

		$query_sm = "SELECT * FROM ga_f5_sum_table ";
		$query_sm .= "WHERE sum >= $hml ";
		$query_sm .= "AND   sum <= $hml9 ";
		#$query_sm .= "AND   month1 > 0 ";
		$query_sm .= "AND   month6 > 1 ";

		print "$query_sm<br>";

		$mysqli_result_sm = mysqli_query($query_sm, $mysqli_link) or die (mysqli_error($mysqli_link));

		$num_rows_sm = mysqli_num_rows($mysqli_result_sm);

		echo "num_rows_sm = $num_rows_sm<br>";

		while($row_sm = mysqli_fetch_array($mysqli_result_sm))
		{
			array_push ($sum_array, $row_sm[sum]);	
		}

		echo "<strong>sum_array = </strong>";
		print_r ($sum_array);
		echo "<br>";

		$count_col12_all = 0;

		#while($row_dt = mysqli_fetch_array($mysqli_result_dt))
		foreach ($sum_array as $sum_select)
		{
			### b1 ##########################################################################################
			
			$col1_array = array();

			$query_ct = "SELECT * FROM ga_f5_column_1_$hml ";
			$query_ct .= "WHERE percent_m6 > 2.0";

			print "$query_ct<br>";

			$mysqli_result_ct = mysqli_query($query_ct, $mysqli_link) or die (mysqli_error($mysqli_link));

			$num_rows_ct = mysqli_num_rows($mysqli_result_ct);

			echo "num_rows_ct = $num_rows_ct<br>";

			while($row_ct = mysqli_fetch_array($mysqli_result_ct))
			{
				array_push ($col1_array, $row_ct[num]);	
			}

			echo "<strong>col1_array = </strong>";
			print_r ($col1_array);
			echo "<br>";

			### b2 ##########################################################################################
			
			$col2_array = array();

			$query_ct = "SELECT * FROM ga_f5_column_2_$hml ";
			$query_ct .= "WHERE percent_m6 > 2.0";

			print "$query_ct<br>";

			$mysqli_result_ct = mysqli_query($query_ct, $mysqli_link) or die (mysqli_error($mysqli_link));

			$num_rows_ct = mysqli_num_rows($mysqli_result_ct);

			echo "num_rows_ct = $num_rows_ct<br>";

			while($row_ct = mysqli_fetch_array($mysqli_result_ct))
			{
				array_push ($col2_array, $row_ct[num]);	
			}

			echo "<strong>col2_array =</strong> ";
			print_r ($col2_array);
			echo "<br>";

			#foreach ($sum_array as $sum_select)
			#{
				foreach ($col1_array as $col1_select)
				{
					$count_col12 = 0;

					foreach ($col2_array as $col2_select)
					{
						$query_dup = "SELECT * FROM combo_5_39_ends_dup_$hml ";
						$query_dup .= "WHERE ";
						$query_dup .= "sum = $sum_select ";
						$query_dup .= "AND seq2 = 0 ";
						#$query_dup .= "AND seq2 = 1 ";
						$query_dup .= "AND   b1 = $col1_select ";
						$query_dup .= "AND   b2 = $col2_select ";
						#$query_dup .= "AND   combo_total_3 <= 1 ";
						$query_dup .= "AND   combo_total_3 <= 2 ";
						$query_dup .= "AND   combo_total_4 = 0 ";
						#$query_dup .= "AND   combo_total_4 <= 1 ";
						#$query_dup .= "AND   last_updated = '$curr_date' ";
						$query_dup .= "ORDER BY m6_sum DESC ";

						#echo "$query_dup<br>";

						$mysqli_result_col12 = mysqli_query($query_dup, $mysqli_link) or die (mysqli_error($mysqli_link));

						$num_rows_col12 = mysqli_num_rows($mysqli_result_col12);

						if ($num_rows_col12)
						{
							$row_col12 = mysqli_fetch_array($mysqli_result_col12);
							echo "$row_col12[sum]-$row_col12[b1]-$row_col12[b2]-$row_col12[b3]-$row_col12[b4]-$row_col12[b5]--$row_col12[m6_sum]<br>";
							$count_col12++;
							$count_col12_all++;
						}
					}

					echo "<strong>count_col12 = $count_col12 - seq2=0</strong><p>";
				}

				echo "<strong>count_col12_all = $count_col12_all - seq2=0</strong><p>";
			#}
		}

	// ----------------------------------------------------------------------------------
	function print_column_test($col)
	{
		global $debug, $draw_table_name, $balls, $balls_drawn, $draw_prefix, $game, $hml, $range_low, $range_high; 

		require ("includes/mysqli.php"); 
		require ("includes/unix.incl"); 

		$query4 = "DROP TABLE IF EXISTS $draw_prefix";
		$query4 .= "column_$col";
		if ($hml)
		{
			$query4 .= "_$hml";
		}

		echo "$query4<p>";

		$mysqli_result4 = mysqli_query($mysqli_link, $query4) or die (mysqli_error($mysqli_link));

		$query4 = "CREATE TABLE $draw_prefix";
		$query4 .= "column_$col";
		if ($hml)
		{
			$query4 .= "_$hml (";
		} else {
			$query4 .= " (";
		}
		$query4 .= "`num` tinyint(3) unsigned NOT NULL,";
		$query4 .= "day1 tinyint(3) unsigned NOT NULL default '0', ";
		$query4 .= "week1 tinyint(3) unsigned NOT NULL default '0', ";
		$query4 .= "week2 tinyint(3) unsigned NOT NULL default '0', ";
		$query4 .= "month1 tinyint(3) unsigned NOT NULL default '0', ";
		$query4 .= "month3 tinyint(3) unsigned NOT NULL default '0', ";
		$query4 .= "month6 tinyint(3) unsigned NOT NULL default '0', ";
		$query4 .= "year1 tinyint(3) unsigned NOT NULL default '0', ";
		$query4 .= "year2 tinyint(3) unsigned NOT NULL default '0', ";
		$query4 .= "year3 tinyint(3) unsigned NOT NULL default '0', ";
		$query4 .= "year4 tinyint(3) unsigned NOT NULL default '0', ";
		$query4 .= "year5 int(5) unsigned NOT NULL default '0', ";
		$query4 .= "year6 int(5) unsigned NOT NULL default '0', ";
		$query4 .= "year7 int(5) unsigned NOT NULL default '0', ";
		$query4 .= "year8 int(5) unsigned NOT NULL default '0', ";
		$query4 .= "year9 int(5) unsigned NOT NULL default '0', ";
		$query4 .= "year10 int(5) unsigned NOT NULL default '0', ";
		$query4 .= "count int(5) unsigned NOT NULL default '0', ";
		$query4 .= "percent_w1 float(4,1) unsigned NOT NULL default '0', ";
		$query4 .= "percent_w2 float(4,1) unsigned NOT NULL default '0', ";
		$query4 .= "percent_m1 float(4,1) unsigned NOT NULL default '0', ";
		$query4 .= "percent_m3 float(4,1) unsigned NOT NULL default '0', ";
		$query4 .= "percent_m6 float(4,1) unsigned NOT NULL default '0', ";
		$query4 .= "percent_y1 float(4,1) unsigned NOT NULL default '0', ";
		$query4 .= "percent_y5 float(4,1) unsigned NOT NULL default '0', ";
		$query4 .= "percent_wa float(4,1) unsigned NOT NULL default '0', ";
		$query4 .= "previous_date date NOT NULL default '1962-08-17', ";
		$query4 .= "last_date date NOT NULL default '1962-08-17', ";
		$query4 .= "PRIMARY KEY  (`num`),";
		$query4 .= "UNIQUE KEY `num_2` (`num`),";
		$query4 .= "KEY `num` (`num`)";
		$query4 .= ") ENGINE=InnoDB DEFAULT CHARSET=latin1;";

		print "$query4<p>";
	
		$mysqli_result4 = mysqli_query($mysqli_link, $query4) or die (mysqli_error($mysqli_link));

		$query = "SELECT * FROM $draw_table_name ";
		if ($hml)
		{
			$query .= "WHERE sum >= $range_low  ";
			$query .= "AND   sum <= $range_high  ";
		}
		$query .= "ORDER BY date DESC ";

		print "$query<p>";

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

		$num_rows_all = mysqli_num_rows($mysqli_result);

		//start table
		print("<h3>Column $col Test</h3>\n");
		print("<TABLE BORDER=\"1\">\n");

		//create header row
		print("<TR>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Num</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Last</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Week1</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Week2</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Month1</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Month3</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Month6</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Year1</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Year2</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Year3</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Year4</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Year5</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Year6</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Year7</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Year8</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Year9</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Year10</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Total</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Prev</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Last</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Sigma</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>w1</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>w2</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>m1</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>m3</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>m6</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>wa1</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>wa5</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>wa10</center></TD>\n");
		print("</TR>\n");

		$draw = 1;

		$temp_array = array_fill (0,17,0);
		$column_count_array = array_fill (0,$balls+1,$temp_array);

		while($row = mysqli_fetch_array($mysqli_result))
		{
			$temp_draw = array (0);

			if ($game == 10 OR $game == 20)
			{
				for ($v = 1; $v <= $balls_drawn; $v++)
				{
					$w = $v + 2;
					array_push($temp_draw, $row[$w]);
				}
			} else {
				for ($v = 1; $v <= $balls_drawn; $v++)
				{
					array_push($temp_draw, $row[$v]);
				}
			}

			sort ($temp_draw);

			$x = $temp_draw[$col];

			$draw_date_array = date_parse("$row[date]");
			$draw_date_unix = mktime (0,0,0,$draw_date_array[month],$draw_date_array[day],$draw_date_array[year]);
			
			if ($draw_date_unix == $day1)
			{ 
				for ($d = 0; $d <= 15; $d++) {$column_count_array[$x][$d]++;}
			} elseif ($draw_date_unix > $week1) {
				for ($d = 1; $d <= 15; $d++) {$column_count_array[$x][$d]++;}
			} elseif ($draw_date_unix > $week2) {
				for ($d = 2; $d <= 15; $d++) {$column_count_array[$x][$d]++;}
			} elseif ($draw_date_unix > $month1) {
				for ($d = 3; $d <= 15; $d++) {$column_count_array[$x][$d]++;}
			} elseif ($draw_date_unix > $month3) {
				for ($d = 4; $d <= 15; $d++) {$column_count_array[$x][$d]++;}
			} elseif ($draw_date_unix > $month6) {
				for ($d = 5; $d <= 15; $d++) {$column_count_array[$x][$d]++;}
			} elseif ($draw_date_unix > $year1) {
				for ($d = 6; $d <= 15; $d++) {$column_count_array[$x][$d]++;}
			} elseif ($draw_date_unix > $year2) {
				for ($d = 7; $d <= 15; $d++) {$column_count_array[$x][$d]++;}
			} elseif ($draw_date_unix > $year3) {
				for ($d = 8; $d <= 15; $d++) {$column_count_array[$x][$d]++;}
			} elseif ($draw_date_unix > $year4) {
				for ($d = 9; $d <= 15; $d++) {$column_count_array[$x][$d]++;}
			} elseif ($draw_date_unix > $year5) {
				for ($d = 10; $d <= 15; $d++) {$column_count_array[$x][$d]++;}
			} elseif ($draw_date_unix > $year6) {
				for ($d = 11; $d <= 15; $d++) {$column_count_array[$x][$d]++;}
			} elseif ($draw_date_unix > $year7) {
				for ($d = 12; $d <= 15; $d++) {$column_count_array[$x][$d]++;}
			} elseif ($draw_date_unix > $year8) {
				for ($d = 13; $d <= 15; $d++) {$column_count_array[$x][$d]++;}
			} elseif ($draw_date_unix > $year9) {
				for ($d = 14; $d <= 15; $d++) {$column_count_array[$x][$d]++;}
			} elseif ($draw_date_unix > $year10) {
				for ($d = 15; $d <= 15; $d++) {$column_count_array[$x][$d]++;}
			}
	
			$column_count_array[$x][16]++;

			#add 1 year to clear
			if ($first_draw_unix > $year7) {
				for ($d = 13; $d <= 15; $d++) {$column_count_array[$x][$d]=0;}
			} elseif ($first_draw_unix > $year8) {
				for ($d = 14; $d <= 15; $d++) {$column_count_array[$x][$d]=0;}
			} elseif ($first_draw_unix > $year9) {
				for ($d = 15; $d <= 15; $d++) {$column_count_array[$x][$d]=0;}
			} elseif ($first_draw_unix > $year10) {
				for ($d = 16; $d <= 16; $d++) {$column_count_array[$x][$d]=0;}
			}

			$draw++;
		}

		
		for ($x = 1 ; $x <= $balls; $x++)
		{	
			if ($x == intval($balls/2+1))
			{
				print("<TR>\n");
				print("<TD BGCOLOR=\"#CCCCCC\">Num</TD>\n");
				print("<TD BGCOLOR=\"#CCCCCC\"><center>Last</center></TD>\n");
				print("<TD BGCOLOR=\"#CCCCCC\"><center>Week1</center></TD>\n");
				print("<TD BGCOLOR=\"#CCCCCC\"><center>Week2</center></TD>\n");
				print("<TD BGCOLOR=\"#CCCCCC\"><center>Month1</center></TD>\n");
				print("<TD BGCOLOR=\"#CCCCCC\"><center>Month3</center></TD>\n");
				print("<TD BGCOLOR=\"#CCCCCC\"><center>Month6</center></TD>\n");
				print("<TD BGCOLOR=\"#CCCCCC\"><center>Year1</center></TD>\n");
				print("<TD BGCOLOR=\"#CCCCCC\"><center>Year2</center></TD>\n");
				print("<TD BGCOLOR=\"#CCCCCC\"><center>Year3</center></TD>\n");
				print("<TD BGCOLOR=\"#CCCCCC\"><center>Year4</center></TD>\n");
				print("<TD BGCOLOR=\"#CCCCCC\"><center>Year5</center></TD>\n");
				print("<TD BGCOLOR=\"#CCCCCC\"><center>Year6</center></TD>\n");
				print("<TD BGCOLOR=\"#CCCCCC\"><center>Year7</center></TD>\n");
				print("<TD BGCOLOR=\"#CCCCCC\"><center>Year8</center></TD>\n");
				print("<TD BGCOLOR=\"#CCCCCC\"><center>Year9</center></TD>\n");
				print("<TD BGCOLOR=\"#CCCCCC\"><center>Year10</center>	</TD>\n");
				print("<TD BGCOLOR=\"#CCCCCC\" nowrap><center>Total</center></TD>\n");
				print("<TD BGCOLOR=\"#CCCCCC\" nowrap><center>Prev</center></TD>\n");
				print("<TD BGCOLOR=\"#CCCCCC\" nowrap><center>Last</center></TD>\n");
				print("<TD BGCOLOR=\"#CCCCCC\" nowrap><center>Sigma</center></TD>\n");
				print("<TD BGCOLOR=\"#CCCCCC\" nowrap><center>w1</center></TD>\n");
				print("<TD BGCOLOR=\"#CCCCCC\" nowrap><center>w2</center></TD>\n");
				print("<TD BGCOLOR=\"#CCCCCC\" nowrap><center>m1</center></TD>\n");
				print("<TD BGCOLOR=\"#CCCCCC\"><center>m3</center></TD>\n");
				print("<TD BGCOLOR=\"#CCCCCC\"><center>m6</center></TD>\n");
				print("<TD BGCOLOR=\"#CCCCCC\" nowrap><center>wa1</center></TD>\n");
				print("<TD BGCOLOR=\"#CCCCCC\" nowrap><center>wa5</center></TD>\n");
				print("<TD BGCOLOR=\"#CCCCCC\" nowrap><center>wa10</center></TD>\n");
				print("</TR>\n");
			}
			print("<TR>\n");
			print("<TD><CENTER><b>$x</b></CENTER></TD>\n");

			for ($d = 0; $d <= 15; $d++)
			{
				if ($column_count_array[$x][$d] > 79)
				{
					print("<TD bgcolor=\"#FF0033\" align=center>{$column_count_array[$x][$d]}</TD>\n");
				} elseif ($column_count_array[$x][$d] > 15) {
					print("<TD bgcolor=\"#CCFFFF\" align=center>{$column_count_array[$x][$d]}</TD>\n");
				} elseif ($column_count_array[$x][$d] > 1) {
					print("<TD bgcolor=\"#CCFF66\" align=center>{$column_count_array[$x][$d]}</TD>\n");
				} elseif ($column_count_array[$x][$d] == 1) {
					print("<TD bgcolor=\"#F1F1F1\" align=center>{$column_count_array[$x][$d]}</TD>\n");
				} else {
					print("<TD align=center>{$column_count_array[$x][$d]}</TD>\n");
				}
			} 

			if ($column_count_array[$x][16] > 79)
			{
				print("<TD bgcolor=\"#FF0000\" align=center><font color=\"#ffffff\"><b>{$column_count_array[$x][16]}</b></font></TD>\n");
			} else {
				print("<TD align=center>{$column_count_array[$x][16]}</TD>\n");
			}

			# prev/last
			$prev_date = '1962-08-17';
			$last_date = '1962-08-17';

			$query_date = "SELECT * FROM $draw_table_name ";
			if ($game != 10)
			{
				$query_date .= "WHERE b$col = $x "; #aon
				if ($hml)
				{
					$query_date .= "AND sum >= $range_low  ";
					$query_date .= "AND   sum <= $range_high  ";
				}
			} else {
			if ($hml)
				{
					$query_date .= "WHERE sum >= $range_low  ";
					$query_date .= "AND   sum <= $range_high  ";
				}
			}
			$query_date .= "ORDER BY date DESC ";

			#echo "$query_date<p>";

			$mysqli_result_date = mysqli_query($query_date, $mysqli_link) or die (mysqli_error($mysqli_link));

			if ($row_date = mysqli_fetch_array($mysqli_result_date))
			{
				$last_date = $row_date[date];
			}

			if ($row_date = mysqli_fetch_array($mysqli_result_date))
			{
				$prev_date = $row_date[date];
			}

			if ($prev_date == '1962-08-17')
			{
				print("<TD align=center>---</TD>\n");
			} elseif ((strtotime ("$prev_date") - $month6) < 0) {
				print("<TD nowrap><font color=\"#ff0000\">$prev_date</font></TD>\n");
			} else {
				print("<TD nowrap>$prev_date</TD>\n");
			}

			if ($last_date == '1962-08-17')
			{
				print("<TD align=center>---</TD>\n");
			} elseif ((strtotime ("$prev_date") - $month6) < 0) {
				print("<TD nowrap><font color=\"#ff0000\">$last_date</font></TD>\n");
			} else {
				print("<TD nowrap>$last_date</TD>\n");
			}

			# sigma
			$sigma_sum = 0;

			for ($d = 0; $d <= 15; $d++)
			{
				$sigma_sum += $column_count_array[$x][$d];
			}

			print("<TD align=center>$sigma_sum</TD>\n");

			$sum_temp_w1 = number_format(($column_count_array[$x][1]/7)*100,2);

			if ($sum_temp_w1 >= 1.0)
			{
				print("<TD align=center><font size=\"-1\"><b>$sum_temp_w1 %</b></font></TD>\n");
			} else {
				print("<TD align=center><font size=\"-1\">$sum_temp_w1 %</font></TD>\n");
			}

			$sum_temp_w2 = number_format(($column_count_array[$x][2]/14)*100,2);

			if ($sum_temp_w2 >= 1.0)
			{
				print("<TD align=center><font size=\"-1\"><b>$sum_temp_w2 %</b></font></TD>\n");
			} else {
				print("<TD align=center><font size=\"-1\">$sum_temp_w2 %</font></TD>\n");
			}

			$sum_temp_m1 = number_format(($column_count_array[$x][3]/30)*100,2);

			if ($sum_temp_m1 >= 1.0)
			{
				print("<TD align=center><font size=\"-1\"><b>$sum_temp_m1 %</b></font></TD>\n");
			} else {
				print("<TD align=center><font size=\"-1\">$sum_temp_m1 %</font></TD>\n");
			}

			$sum_temp_m3 = number_format(($column_count_array[$x][4]/60)*100,2);

			if ($sum_temp_m3 >= 1.0)
			{
				print("<TD align=center><font size=\"-1\"><b>$sum_temp_m3 %</b></font></TD>\n");
			} else {
				print("<TD align=center><font size=\"-1\">$sum_temp_m3 %</font></TD>\n");
			}

			$sum_temp_m6 = number_format(($column_count_array[$x][5]/90)*100,2);

			if ($sum_temp_m6 >= 1.0)
			{
				print("<TD align=center><font size=\"-1\"><b>$sum_temp_m6 %</b></font></TD>\n");
			} else {
				print("<TD align=center><font size=\"-1\">$sum_temp_m6 %</font></TD>\n");
			}

			$sum_temp_y1 = number_format(($column_count_array[$x][6]/(365))*100,2);

			if ($sum_temp_y1 >= 1.0)
			{
				print("<TD align=center><font size=\"-1\"><b>$sum_temp_y1 %</b></font></TD>\n");
			} else {
				print("<TD align=center><font size=\"-1\">$sum_temp_y1 %</font></TD>\n");
			}

			$sum_temp_y5 = number_format(($column_count_array[$x][10]/(365*5))*100,2);

			if ($sum_temp_y5 >= 1.0)
			{
				print("<TD align=center><font size=\"-1\"><b>$sum_temp_y5 %</b></font></TD>\n");
			} else {
				print("<TD align=center><font size=\"-1\">$sum_temp_y5 %</font></TD>\n");
			}

			$weighted_average = (
				($column_count_array[$x][1]/7*100*0.10) + #week1
				($column_count_array[$x][3]/30*100*0.10) + #month1
				($column_count_array[$x][5]/(365/2)*100*0.15) + #month6
				($column_count_array[$x][6]/365*100*0.15) + #year1
				($column_count_array[$x][10]/(365*5)*100*0.25) + #year5
				($column_count_array[$x][13]/(365*8)*100*0.25)); #year8

			$sum_temp = number_format($weighted_average,1);
			if ($sum_temp >= 5.0){
				print("<TD align=center><font size=\"-1\"><b>$sum_temp %</b></font></TD>\n");
			} else {
				print("<TD align=center><font size=\"-1\">$sum_temp %</font></TD>\n");
			}

			print("</TR>\n");

			#print("x = $x<br>");

			#print_r ("$column_count_array");
			#die ("$column_count_array");

			$query5 = "INSERT INTO $draw_prefix";
			$query5 .= "column_$col";
			if ($hml)
			{
				$query5 .= "_$hml ";
			} else {
				$query5 .= " ";
			}
			$query5 .= "VALUES ('$x', ";
			for ($d = 0; $d <= 15; $d++) 
			{
				$query5 .= "'{$column_count_array[$x][$d]}', ";
			}
			#$query5 .= "'$num_rows', ";
			$query5 .= "'{$column_count_array[$x][16]}', ";
			$query5 .= "'$sum_temp_w1', ";
			$query5 .= "'$sum_temp_w2', ";
			$query5 .= "'$sum_temp_m1', ";
			$query5 .= "'$sum_temp_m3', ";
			$query5 .= "'$sum_temp_m6', ";
			$query5 .= "'$sum_temp_y1', ";
			$query5 .= "'$sum_temp_y5', ";
			$query5 .= "'$weighted_average', ";
			$query5 .= "'$row_last[last_date]',"; 
			$query5 .= "'$row_prev[last_date]')";

			#print "$query5<p>";
			#die ("column_count_array");
		
			$mysqli_result = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));
		}

		print("<TR>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Num</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Last</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Week1</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Week2</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Month1</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Month3</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Month6</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Year1</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Year2</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Year3</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Year4</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Year5</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Year6</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Year7</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Year8</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Year9</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Year10</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Total</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Prev</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Last</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Sigma</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>w1</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>w2</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>m1</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>m3</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>m6</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>wa1</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>wa5</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>wa10</center></TD>\n");
		print("</TR>\n");

		print("</TABLE>\n");

		print "<h3>Table <font color=\"#ff0000\">{$draw_prefix}column_$col</font> Updated!</h3>";
	}
?>