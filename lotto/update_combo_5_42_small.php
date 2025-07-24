<?php

	$game = 1; // Georgia F5

	$hml = 10091;
	$even = 2;
	$odd = 3;
	$d4_1 = 2;
	$d4_2 = 1;
	$d4_3 = 1;
	$d4_4 = 1;

	echo "d4_1 = $d4_1/d4_2 = $d4_2/d4_3 = $d4_3/d4_4 = $d4_4<p>";

	$col1_select = 1;

	require ("includes/games_switch.incl");

	require_once ("includes/hml_switch.incl");	

	// include to connect to database
	require ("includes/mysqli.php");
	require ("includes/count_2_seq.php");
	require ("includes/count_3_seq.php");
	#require ("includes_fl/build_rank_table_fl.php");
	#require ("includes_fl/calculate_rank_fl.php");
	require ("includes_fl_f5/last_draws_fl_f5.php");
	require ("includes_fl_f5/combin.incl");

	//start HTML page
	print("<HTML>\n");
	print("<HEAD>\n");
	print("<TITLE>Build Combo Draw Model EO4</TITLE>\n");
	print("</HEAD>\n");
	
	print("<BODY bgcolor=\"#FFFFFF\" text=\"#000000\">\n");

	date_default_timezone_set('America/New_York');

	$curr_date = date('Y-m-d');

	build_rank_count_hml($hml);

	for ($col = 1; $col <= 5; $col++)
	{
		print_column_test_eo4($col,$even,$odd,$d4_1,$d4_2,$d4_3,$d4_4);
	}

	for ($combo = 1; $combo <= 10; $combo++)
	{
		print_combo_pair_eo4($combo,$even,$odd,$d4_1,$d4_2,$d4_3,$d4_4);
	}

	$combo_draw_model = "ga_f5_combol_model_eo4";

	$query = "DROP TABLE IF EXISTS $combo_draw_model";
	#$query .= "$hml";
	#$query .= "_eo4 ";

	$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

	$query =  "CREATE TABLE IF NOT EXISTS $combo_draw_model ";
	$query .= "LIKE ga_f5_draws "; 

	print("$query<br>");

	$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

	### Combo 1

	$query1  = "SELECT * FROM combo_5_42 ";
	$query1  = "SELECT * FROM combo_5_42 ";
	$query1 .= "Where sum >= $range_low ";
	$query1 .= "AND   sum <= $range_high ";
	$query1 .= "AND   even = $range_high ";
	$query1 .= "AND   odd = $range_high ";
	$query1 .= "AND   d4_1 = $d4_1 ";
	$query1 .= "AND   d4_2 = $d4_2 ";
	$query1 .= "AND   d4_3 = $d4_3 ";
	$query1 .= "AND   d4_4 = $d4_4 ";
	$query1 .= "AND   b1 = $num1 ";
	$query1 .= "AND   b2 = $num2 ";
	$query1 .= "ORDER BY percent_wa desc ";

	$mysqli_result1 = mysqli_query($mysqli_link, $query1) or die (mysqli_error($mysqli_link));

	while($row = mysqli_fetch_array($mysqli_result1))
	{

		# select into for c1

	}

	### Combo 2-10


	$combo_table = "ga_f5_combo_pair_";
	$combo_table .= "$hml";
	$combo_table .= "_eo4 ";

	$query_temp  = "SELECT * FROM ga_f5_combo_pair_";
	$query_temp .= "_";
	$query_temp .= "1";
	$query_temp .= "_";
	$query_temp .= "$hml";
	$query_temp .= "_";
	$query_temp .= "$even";
	$query_temp .= "$odd";
	$query_temp .= "_";
	$query_temp .= "$d4_1";
	$query_temp .= "$d4_2";
	$query_temp .= "$d4_3";
	$query_temp .= "$d4_4";
	$query_temp .= "_eo4";
	$query_temp .= "ORDER BY WA desc ";

	echo "$query_temp<p>";

	$mysqli_result = mysqli_query($mysqli_link, $query_temp) or die (mysqli_error($mysqli_link));

	while($row = mysqli_fetch_array($mysqli_result))
	{
		$query1  = "SELECT * FROM combo_5_42 ";
		$query1 .= "Where sum >= $range_low ";
		$query1 .= "AND   sum <= $range_high ";
		$query1 .= "AND   even = $range_high ";
		$query1 .= "AND   odd = $range_high ";
		$query1 .= "AND   d4_1 = $d4_1 ";
		$query1 .= "AND   d4_2 = $d4_2 ";
		$query1 .= "AND   d4_3 = $d4_3 ";
		$query1 .= "AND   d4_4 = $d4_4 ";
		$query1 .= "AND   b1 = $num1 ";
		$query1 .= "AND   b2 = $num2 ";
		$query1 .= "ORDER BY percent_wa desc ";

		$mysqli_result1 = mysqli_query($mysqli_link, $query1) or die (mysqli_error($mysqli_link));

		while($row = mysqli_fetch_array($mysqli_result1))
		{

			# select into for c1

		}



	}




















	$drop_tables = 1;
	
	if ($drop_tables)
	{
		$combo_table = "combo_2_36";

		$query = "DROP TABLE IF EXISTS $combo_table ";

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

		$query =  "CREATE TABLE IF NOT EXISTS  $combo_table ( ";
		$query .= "  `id` int(10) unsigned NOT NULL auto_increment, ";
		$query .= "  `b1` tinyint(2) unsigned NOT NULL default '0', ";
		$query .= "  `b2` tinyint(2) unsigned NOT NULL default '0', ";
		#$query .= "  `b3` tinyint(2) unsigned NOT NULL default '0', ";
		#$query .= "  `b4` tinyint(2) unsigned NOT NULL default '0', ";
		#$query .= "  `b5` tinyint(2) unsigned NOT NULL default '0', ";
		$query .= "  `sum` int(5) unsigned NOT NULL default '0', ";
		$query .= "  `even` tinyint(1) unsigned NOT NULL default '0', ";
		$query .= "  `odd` tinyint(1) unsigned NOT NULL default '0', ";
		$query .= "  `d2_1` tinyint(1) unsigned NOT NULL default '0', ";
		$query .= "  `d2_2` tinyint(1) unsigned NOT NULL default '0', ";
		$query .= "  `d3_1` tinyint(1) unsigned NOT NULL default '0', ";
		$query .= "  `d3_2` tinyint(1) unsigned NOT NULL default '0', ";
		$query .= "  `d3_3` tinyint(1) unsigned NOT NULL default '0', ";
		$query .= "  `d4_1` tinyint(1) unsigned NOT NULL default '0', ";
		$query .= "  `d4_2` tinyint(1) unsigned NOT NULL default '0', ";
		$query .= "  `d4_3` tinyint(1) unsigned NOT NULL default '0', ";
		$query .= "  `d4_4` tinyint(1) unsigned NOT NULL default '0', ";
		$query .= "  `d0` tinyint(1) unsigned NOT NULL default '0', ";
		$query .= "  `d1` tinyint(1) unsigned NOT NULL default '0', ";
		$query .= "  `d2` tinyint(1) unsigned NOT NULL default '0', ";
		$query .= "  `d3` tinyint(1) unsigned NOT NULL default '0', ";
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

		print("$query<p>");

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));
	}
	
	
	### build rank table

	$num_rank_array = array_fill(0, 54, 0);

	$query = "SELECT * FROM fl_f5_draws ";
	$query .= "WHERE date < '$curr_date' ";
	$query .= "ORDER BY date DESC ";
	$query .= "LIMIT 26 ";

	$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

	while($row = mysqli_fetch_array($mysqli_result))
	{
		$num_rank_array[$row[b1]]++;
		$num_rank_array[$row[b2]]++;
		$num_rank_array[$row[b3]]++;
		$num_rank_array[$row[b4]]++;
		$num_rank_array[$row[b5]]++;
	}

	### end build rank table

	### build lastxdraws
	
	for ($x = 1; $x <= 10; $x++)
	{
		${last_.$x._draws} = array_fill (0,51,0);
	}

	for ($x = 1; $x <= 10; $x++)
	{
		${last_.$x._draws} = LastxDraws($curr_date,$x);
	}	
	
	### end lastxdraws

	#################################################################################################
	#################################################################################################
	#################################################################################################

	$k = 1; # <----------------------------------------------------------------------------------------------
	
	### calculate sum limits

	$sum_31 = array_fill (0,15,0);
	
	$query = "SELECT date,sum FROM fl_f5_draws ";
	#$query .= "WHERE b1 = $k ";
	$query .= "WHERE b1 = $k ";
	$query .= "ORDER BY date DESC ";
	$query .= "LIMIT 0,31 ";

	$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

	$n = 0;

	while($row = mysqli_fetch_array($mysqli_result))
	{
		$sum_31[$n] = $row[sum];
		$n++;
	}

	$sum_low = intval(($sum_31[0]*0.1)+($sum_31[1]*0.4)+($sum_31[2]*0.4)+($sum_31[3]*0.1));

	# ----------------------------------------------------------------------

	$n = 0;

	rsort ($sum_31);

	$sum_high = intval(($sum_31[0]*0.1)+($sum_31[1]*0.4)+($sum_31[2]*0.4)+($sum_31[3]*0.1)+1);

	### END calculate sum limits

	$max_num = 36;

	$count = 0;
	$print_flag = 0;

	#$draw_num = array_fill (0,6,0);

	$draw_num[1] = $k;
	$draw_num[2] = $k+1;
	#$draw_num[3] = $k+2;
	#$draw_num[4] = $k+3;
	#$draw_num[5] = $k+4;

	#$draw_num[2] = 7;
	#$draw_num[3] = 11;
	#$draw_num[4] = 47;
	#$draw_num[5] = 49;

	print("<p align=\"center\"><b><font face=\"Arial, Helvetica, sans-serif\">Build Combo 2/$max_num</font></b></p>");

	while($draw_num[1] <= 35)
	{
		$num_rows = 0;

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

			$total_combin = array_fill (0,7,0);
			$num_count = array_fill (0,7,0);
			$rank_count = array_fill (0,7,0);
			$mod = array_fill (0,7,0);
			$dup_count = array_fill (0,11,0);

			$draw_array = array ($draw_num[1],$draw_num[2]);
			$draw_array_0 = array (0,$draw_num[1],$draw_num[2]);

			$sum =	$draw_num[1] + $draw_num[2];

			#print "<h3>$draw_num[1] - $draw_num[2] - $draw_num[3] - $draw_num[4] - $draw_num[5] - $draw_num[6]</h3>";
			
			#print "sum = $sum<br>";

			$seq2 = Count2Seq($draw_array);
			$seq3 = Count3Seq($draw_array);

			#$total_combin = test_combin($draw_num);

			for ($index = 1; $index <= 2; $index++)
			{ 
				if ($draw_num[$index] > 18) {
					$d502++;
				} else {
					$d501++;
				}

				if ($draw_num[$index] > 24) {
					$d3_array[2]++;
				} elseif ($draw_num[$index] > 12) {
					$d3_array[1]++;
				} else {
					$d3_array[0]++;
				}

				if ($draw_num[$index] > 27) {
					$d4_array[3]++;
				} elseif ($draw_num[$index] > 18) {
					$d4_array[2]++;
				} elseif ($draw_num[$index] > 9) {
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
			for ($x = 1; $x <= 2; $x++) 
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
				else {
					$y = $draw_num[$x] - 30;
					$mod[$y]++;
					$num_count[3]++;
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
		
			if (#$sum >= $sum_low && 
				#$sum <= $sum_high && 
				#$seq2 <= 1 && 
				#$seq3 <= 0 &&
				$mod_total <= 100) # <---------------------------------
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
				if ($total_combin[2] >= 0 # == 15
					#$total_combin[3] >= 7 &&
					#$total_combin[4] <= 1 &&
					#$total_combin[5] == 0 &&
					#$total_combin[6] == 0 
					)
				{
				
					include_once 'C:\wamp\php\pear\Math\Stats.php';

					$s = new Math_Stats();
					$s->setData($draw_array);
					$stats = $s->calcBasic();
					
					$avg = $sum/4;
					$median = ($draw_num[1]+$draw_num[2])/2;
					$quart1 = ($draw_num[1]+$draw_num[2])/2;
					$quart2 = ($draw_num[1]+$draw_num[2])/2;
					$quart3 = ($draw_num[1]+$draw_num[2])/2;
					$stdev = $s->stDev();
					$variance = $s->variance();
					#$avedev = $s->harmonicMean();
					$avedev = 0;
					$kurtosis = $s->kurtosis();
					$skew = $s->skewness();

					$draw_array = array ($draw_num[1],$draw_num[2]);

					#$average = $row[sum]/5;
					#$median = $row[b3];
					$harmean = $s->harmonicMean();
					#$quart1 = ($row[b1]+$row[b2])/2;
					#$quart2 = ($row[b1]+$row[b2]+$row[b3])/2;
					#$quart3 = ($row[b1]+$row[b2]+$row[b3]+$row[b4])/2;
					#$stdev = $s->stDev();
					#$variance = $s->variance();
					$avedev = $s->__calcAbsoluteDeviation();
					#$kurtosis = $s->kurtosis();
					#$skew = $s->skewness();
					$geomean = $s->geometricMean();
					$devsq = calc_devsq ($draw_array,$average);

					/*
					###########################################################################
					#		EO50
					###########################################################################

					$query2 =  "SELECT * FROM fl_f5_eo50 ";
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

					$query3 =  "SELECT * FROM fl_f5_";
					$query3 .= "wheels_generated ";
					$query3 .= "WHERE eo50 = $wheel_id ";
					$query3 .= "AND sum = $sum ";

					$mysqli_result3 = mysqli_query($mysqli_link, $query3) or die (mysqli_error($mysqli_link));

					$row3 = mysqli_fetch_array($mysqli_result3);

					$wheel_generated_rows = mysqli_num_rows($mysqli_result3); 

					if ($wheel_generated_rows)
					{
						$wheel_generated_wa = $row3[percent_wa];
					} else {
						$wheel_generated_wa = 0.0;
					}
					*/
					$wheel_generated_rows = 0;
					$wheel_generated_wa = 0.0;
					$pair_sum = 0;
					$draw_count = 0;

					$query7 = "SELECT * FROM fl_f5_2_36 ";
					$query7 .= "WHERE d1 = '$draw_num[1]' ";
					$query7 .= "AND   d2 = '$draw_num[2]' ";
					#$query7 .= "AND   d3 = '$draw_num[3]' ";
					#$query7 .= "AND   d4 = '$draw_num[4]' ";
					#$query7 .= "AND   b5 = '$draw_num[5]' ";
					$query7 .= "ORDER BY date DESC ";

					$mysqli_result7 = mysqli_query($query7, $mysqli_link) or die (mysqli_error($mysqli_link));

					if ($draw_count = mysqli_num_rows($mysqli_result7))
					{
						$row7 = mysqli_fetch_array($mysqli_result7);
						$draw_last = $row7[date];
					} else {
						$draw_last = '1962-08-17';
					}

					$combo_table = "combo_2_36";
					
					$query = "INSERT INTO $combo_table ";
					$query .= "VALUES ('0', ";
					$query .= "'$draw_num[1]', ";
					$query .= "'$draw_num[2]', ";
					#$query .= "'$draw_num[3]', ";
					#$query .= "'$draw_num[4]', ";
					#$query .= "'$draw_num[5]', ";
					$query .= "'$sum', ";
					$query .= "'$even', ";
					$query .= "'$odd', ";
					$query .= "'$d501', ";
					$query .= "'$d502', ";

					for($z = 0; $z < 3; $z++)
					{
						$query .= "$d3_array[$z], ";
					}

					for($z = 0; $z < 4; $z++)
					{
						$query .= "$d4_array[$z], ";
					}

					$query .= "'$num_count[0]', ";
					$query .= "'$num_count[1]', ";
					$query .= "'$num_count[2]', ";
					$query .= "'$num_count[3]', ";

					$query .= "'$rank_count[0]', ";
					$query .= "'$rank_count[1]', ";
					$query .= "'$rank_count[2]', ";
					$query .= "'$rank_count[3]', ";
					$query .= "'$rank_count[4]', ";
					$query .= "'$rank_count[5]', ";
					$query .= "'$rank_count[6]', ";

					$query .= "'$mod_total', ";
					$query .= "'$mod_x', ";
					#$query .= "'0', ";
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

					$query .= "'$pair_sum', ";

					$query .= "'$avg', ";
					$query .= "'$median', ";
					$query .= "'$harmean', ";
					$query .= "'$geomean', ";
					$query .= "'$quart1', ";
					$query .= "'$quart2', ";
					$query .= "'$quart3', ";
					$query .= "'$stdev', ";
					$query .= "'$variance', ";
					$query .= "'$avedev', ";
					$query .= "'$kurtosis', ";
					$query .= "'$skew', ";
					$query .= "'$devsq', ";
					$query .= "'$wheel_generated_rows', ";
					$query .= "'$wheel_generated_wa', ";

					$query .= "'$draw_last', ";
					$query .= "'$draw_count', ";
					
					$query .= "'$curr_date')";

					#print "$query";
					
					$mysqli_result2 = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

					#die("insert die");

					$count++;
				}
			}
		#}

		if ($draw_num[2] < $max_num) {
				$draw_num[2] = $draw_num[2] + 1;
		} else {
				$draw_num[1] = $draw_num[1] + 1;	
				$draw_num[2] = $draw_num[1] + 1;
		}
		
		if ($print_flag == 50000)
		{
			print "$draw_num[1],$draw_num[2],$draw_num[3]<br>";
			$print_flag = 0;
		}

		$print_flag++;

		set_time_limit(0);
	}

	function calc_devsq ($draw,$average)
	{
		$average = array_sum($draw)/2;
		$devsq = 0.0;
		for ($x = 0; $x < 2; $x++)
		{
			$temp = $draw[$x]-$average;
			$devsq += $temp*$temp;
		}

		#echo "devsq = $devsq<p>";

		return $devsq;
	}

	print("</table>");

	print("<h2>Count = $count</h2>");

	print("</body>");
	print("</html>");

?>