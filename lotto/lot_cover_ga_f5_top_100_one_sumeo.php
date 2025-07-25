<?php

	### starting over 240602 ###
	
	$game = 1; // Georgia F5
	$draw_table_name = "ga_f5_draws";

	echo "c:\wamp\www\lotto\<br>";

	set_time_limit(0);

	$k = 1;

	$debug = 1;

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
	require ("includes/print_column_test_sumeo_no_tables.php"); #240623
	require ("includes/dateDiffInDays.php");
	require ("includes/first_draw_unix.php");
	require ("includes/last_draw_unix.php");
	require ("includes/last_draws.php");
	require ("includes_ga_f5/last_draws_ga_f5.php");
	require ("includes_ga_f5/combin.incl");
	require ("includes_ga_f5/split_sumeo_2_no_tables.php");
	require ("includes_ga_f5/split_sumeo_3_no_tables.php");
	require ("includes_ga_f5/split_sumeo_4_no_tables.php");
	require ("includes_ga_f5/split_sumeo_5_no_tables.php");
	require ("includes/unix.incl");
	require ("includes_ga_f5/check_dup_rank.php");

	date_default_timezone_set('America/New_York');

	//start HTML page
	print("<HTML>\n");
	print("<HEAD>\n");
	print("<TITLE>Lotto Cover 100 - 5/42</TITLE>\n");
	print("</HEAD>\n");
	
	print("<BODY bgcolor=\"#FFFFFF\" text=\"#000000\">\n");

	$curr_date = date('Y-m-d');
	$currdate = date('ymd');

	$drop_tables = 1;	### <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<

	#$drop_tables = $_GET["drop_tables"];

	#$update_level = 1;	### ALL

	$update_level = 2;	### dup, rank

	$max_num = 42;

	$count = 1;
	$count_all = 0;
	$print_flag = 0;
	
	### build last_draw and rank tables
	$last_dup = array_fill (0, 51, 0);

	for ($x = 1; $x <= 5; $x++)
	{
		${"last_".$x."_draws"} = LastDraws($curr_date,$x);
		echo "<b>last $x draws -</b> <br>";
		print_r (${"last_".$x."_draws"});
		echo "<br>";
	}

	$rank_table_count = array_fill (0, 8, 0);
	$h = 0;

	$query = "SELECT * FROM $draw_prefix";
	$query .= "rank_limit ";
	$query .= "WHERE date = '$curr_date' ";
	$query .= "AND   draw_limit = 30 ";

	#echo "rank query - $query<br>";

	$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

	#$row_count = mysqli_fetch_array($mysqli_result);

	$row = mysqli_fetch_array($mysqli_result);

	for ($g = 3; $g <= 10; $g++)
	{
		$rank_table_count[$h] = $row[$g];
		$h++;
	}
	
	echo "<b>rank_table_count -</b> <br>";
	print_r ($rank_table_count);
	echo "<br>";

	### scaffolding tables ###
	$temp_table1 = 'temp_cover_100_count_' .  $currdate;

	$temp_table2 = 'temp_cover_100_scaffolding_135_' .  $currdate;

	$temp_table4 = 'temp_cover_100_candidates_scaffolding_' .  $currdate;

	echo "<p>############################################ scaffolding_drop_tables ####################################################</p>";
	require ("includes_ga_f5/scaffolding_drop_tables_100.incl");	### 240716 ###
	
	echo "<p>############################################ build_dup_rank_tables ####################################################</p>";
	require ("includes_ga_f5/build_dup_rank_tables.incl");	### 240716 ###

	echo "<p>############################################ build 100 counts ####################################################</p>";
	### build 100 counts ###
	require ("includes_ga_f5/scaffolding_count_100.incl");	### 240716 ###
	
	echo "<p>############################################ Col 1 ####################################################</p>";
	### scaffolding col1 ###
	require ("includes_ga_f5/scaffolding_col1_count_100.incl");	### 240716 ###
	#die(); ##########################################################################################################################################

	echo "<p>############################################ Col 5 ####################################################</p>";
	### scaffolding col5 ###
	require ("includes_ga_f5/scaffolding_col5_count_100.incl");	### 241130 move col3 to below ###
	die(); ##########################################################################################################################################
	
	echo "<p>############################################ Col 3 ####################################################</p>";
	### scaffolding col5 ###
	#require ("includes_ga_f5/scaffolding_col5_count_100.incl");	### 240716 ###
	#die(); ##########################################################################################################################################
	
	echo "<p>############################################ Col 2/3/4 ####################################################</p>";

	### scaffolding col3 ###
	require ("includes_ga_f5/scaffolding_col234_count_100.incl");	### 240716 ###

	echo "<p>############################################ Col 2/Col4 ####################################################</p>";

	### scaffolding col52/col4 ###
	### if col2_4 fails, change col3 ###
	require ("includes_ga_f5/scaffolding_col2_col4_100.incl");	### 241031 ###
	
	die();   

	#SELECT * FROM `combo_5_42` WHERE `b1` = 10 AND `b3` = 23 AND `b5` = 35 AND `sum` = 116 AND `even` = 3 AND `odd` = 2

	# update y1_sum - use col2/col4

	###################################################################################################################
	### todo - add col1 filter		### 240602 ###
	echo "<h3>##### todo - add col1 filter #####</h3>";
	###################################################################################################################
	
	# select 1k_count for each sumeo
	$query3 = "SELECT DISTINCT sum,even,odd,k_count FROM $temp_table1 ";
	$query3 .= "ORDER BY `k_count` DESC  ";

	#echo "<p>query3 - $query3</p>";

	$mysqli_result3 = mysqli_query($mysqli_link, $query3) or die (mysqli_error($mysqli_link));

	while($row3 = mysqli_fetch_array($mysqli_result3))	###############################################################
	{
		echo "<b>$row3[sum], $row3[even], $row3[odd] - $row3[k_count]</b><br>";

		# build draws table based on sumeos

		# 1 - build temp draw table for sumeo

		$temp_table3 = 'temp_sumeo_draw_' . $row3['sum'] . '_' . $row3['even'] . '_' . $row3['odd'];

		$temp_table_sumeo_col1 = 'temp2_column_sumeo_' . $row3['sum'] . '_' . $row3['even'] . '_' . $row3['odd']. '_1' ;

		if ($drop_tables)
		{
			$query4 = "DROP TABLE IF EXISTS $temp_table3 ";

			echo "<p>$query4</p>";

			$mysqli_result4 = mysqli_query($mysqli_link, $query4) or die (mysqli_error($mysqli_link));

			$query4 =  "CREATE TABLE $temp_table3 LIKE combo_5_42 ";

			echo "<p>$query4</p>";

			$mysqli_result4 = mysqli_query($mysqli_link, $query4) or die (mysqli_error($mysqli_link));

			if ($row3['sum'] < 80)
			{
				$query_dc = "SELECT * FROM `ga_f5_draw_summary_by_sumeo2` 
								WHERE `sum` = $row3[sum] AND `even` = $row3[even] AND `odd` = $row3[odd]
								ORDER BY `percent_wa` DESC";	###240326
			} else {
				$query_dc = "SELECT * FROM `ga_f5_draw_summary_by_sumeo2` 
								WHERE `sum` = $row3[sum] AND `even` = $row3[even] AND `odd` = $row3[odd]
								AND year1 > 0 AND percent_wa >= 0.1 ORDER BY `percent_wa` DESC";	###240326
			}

			#echo "$query_dc<br>";

			$mysqli_result_dc = mysqli_query($mysqli_link, $query_dc) or die (mysqli_error($mysqli_link));

			$num_rows_dc = mysqli_num_rows($mysqli_result_dc);

			if (!$num_rows_dc)
			{
				echo "no num_rows_dc<br>";

				for ($col = 1; $col <= 5; $col++)
				{
					print_column_test_sumeo_no_tables($col, $sumeo_sum, $sumeo_even, $sumeo_odd); #200915
				}
				#die();
			} else {
				while($row_dc = mysqli_fetch_array($mysqli_result_dc))
				{
					# select 1k_count for each sumeo
					$query5 = "SELECT * FROM $temp_table_sumeo_col1 ";
					$query5 .= "WHERE percent_wa > 0.100  ";
					$query5 .= "ORDER BY percent_wa DESC  ";

					echo "<p>$query5</p>";

					$mysqli_result5 = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));

					while($row5 = mysqli_fetch_array($mysqli_result5))	###############################################################
					{
						$query4 = "INSERT INTO $temp_table3 SELECT * FROM combo_5_42 WHERE sum = $row3[sum] AND even = $row3[even] AND odd = $row3[odd]
						AND seq2 <= 1 AND seq3 = 0 AND mod_tot <= 1 AND mod_x = 0
						AND d0 = $row_dc[d0] AND d1 = $row_dc[d1] AND d2 = $row_dc[d2] AND d3 = $row_dc[d3] AND d4 = $row_dc[d4] 
						AND b1 = $row5[num] ";	###2400606

						#echo "<p>$query4</p>";

						$mysqli_result4 = mysqli_query($mysqli_link, $query4) or die (mysqli_error($mysqli_link));
					}
				}
			}
		}
	}

		die();	### 240602 next ###

		#split_sumeo_5 ($row3['sum'],$row3['even'],$row3['odd']);

		#split_sumeo_4 ($row3['sum'],$row3['even'],$row3['odd']);

		#split_sumeo_3 ($row3['sum'],$row3['even'],$row3['odd']);

		#split_sumeo_2 ($row3['sum'],$row3['even'],$row3['odd']);

		###############################################################################################################
		### update draws table - rank, comb, dup, y1_sum
		###############################################################################################################
		$query9 = "SELECT COUNT(*) AS cnt 
				  FROM INFORMATION_SCHEMA.TABLES 
				  WHERE TABLE_SCHEMA = :dbName 
				  AND TABLE_NAME = :temp_table3";

		$query9 = "SHOW TABLES LIKE '$temp_table3'";
		#$result = mysqli_query($conn, $query);

		echo "<p>9 - $query9</p>";

		$mysqli_result9 = mysqli_query($mysqli_link, $query9) or die (mysqli_error($mysqli_link));

		$num_rows9 = mysqli_num_rows($mysqli_result9);

		if ($num_rows9 > 0) {
			$query4 = "SELECT * FROM $temp_table3 ";
			$query4 .= "WHERE last_updated <= '$curr_date' ";

			#echo "789 - $query4<br>";

			$mysqli_result4 = mysqli_query($mysqli_link, $query4) or die (mysqli_error($mysqli_link));

			$num_rows_4 = mysqli_num_rows($mysqli_result4);

			echo "num_rows_4 = $num_rows_4<br>";

			while ($row4d = mysqli_fetch_array($mysqli_result4))
			{
				###########################################################################################################
				### calculate y1_sum
				###########################################################################################################
				$y1_sum = 0.00;
						
				for ($d = 1; $d <= 5; $d++)
				{
					$query_col2 = "SELECT * FROM temp2_column_sumeo";
					$query_col2 .= "_";
					$query_col2 .= "$row4d[sum]";
					$query_col2 .= "_";
					$query_col2 .= "$row4d[even]";
					$query_col2 .= "_";
					$query_col2 .= "$row4d[odd]";
					$query_col2 .= "_";
					$query_col2 .= "$d ";
					$query_col2 .= " WHERE num = $row4d[$d] ";

					#echo "$query_col2<br>";

					$mysqli_result_col2 = mysqli_query($mysqli_link, $query_col2) or die (mysqli_error($mysqli_link));

					$row_col2 = mysqli_fetch_array($mysqli_result_col2);

					$y1_sum += $row_col2[20];	###240402
				}

				
				###########################################################################################################
				### calculate dup count
				###########################################################################################################
				$last_dup = array_fill (0, 51, 0);

				//count repeating numbers
				for ($x = 1 ; $x <= 4; $x++)
				{
					for ($y = 1 ; $y <= 5; $y++)
					{	
						$temp = 'last_' . $x . '_draws';
						if (array_search($row4d[$y], ${$temp}) !== FALSE)
						{
							$last_dup[$x]++;
						}
					}
				}

				###########################################################################################################
				### calculate rank count
				###########################################################################################################
				$draw_rank_count = array_fill (0, 9, 0); 
				
				for($y = 1; $y <= 5; $y++)
				{
					$temp1 = $rank_count[$row4d[$y]];

					if ($rank_count[$row4d[$y]] >= 7) 
					{
						$draw_rank_count[7]++; 
					} else {
						$draw_rank_count[$rank_count[$row4d[$y]]]++;
					}
				}

				$query9 = "UPDATE $temp_table3 ";
				$query9 .= "SET y1_sum = $y1_sum, ";	###240402
				$query9 .= "dup1 = '$last_dup[1]', ";
				$query9 .= "dup2 = '$last_dup[2]', ";
				$query9 .= "dup3 = '$last_dup[3]', ";
				$query9 .= "dup4 = '$last_dup[4]', ";
				$query9 .= "rank0 = '$draw_rank_count[0]', ";
				$query9 .= "rank1 = '$draw_rank_count[1]', ";
				$query9 .= "rank2 = '$draw_rank_count[2]', ";
				$query9 .= "rank3 = '$draw_rank_count[3]', ";
				$query9 .= "rank4 = '$draw_rank_count[4]', ";
				$query9 .= "rank5 = '$draw_rank_count[5]', ";
				$query9 .= "rank6 = '$draw_rank_count[6]', ";
				$query9 .= "rank7 = '$draw_rank_count[7]', ";
				$query9 .= "last_updated = '$curr_date' ";
				$query9 .= "WHERE id = '$row4d[id]' ";

				#echo "$query9<br>";
				
				$mysqli_result9 = mysqli_query($mysqli_link, $query9) or die (mysqli_error($mysqli_link));
			}

			$query_update = "UPDATE $temp_table1 ";
			$query_update .= "SET last_updated = '$curr_date' ";
			$query_update .= "WHERE sum  = $row3[sum] ";
			$query_update .= "AND   even = $row3[even] ";
			$query_update .= "AND   odd  = $row3[odd] ";

			#echo "$query_update<p>";

			$mysqli_result_update = mysqli_query ($mysqli_link, $query_update) or die (mysqli_error($mysqli_link));
		} else {
			echo ">>>>> Table does not exist.";
		}
	#}

	### add test for k_count per sumeo ###240402 

	#########################################################################################################
	### read draw_count table for each sumeos	###240319
	#########################################################################################################
	# select 1k_count for each sumeo
	$query3 = "SELECT DISTINCT * FROM $temp_table1 ";
	$query3 .= "ORDER BY `k_count` DESC  ";

	echo "<p>$query3</p>";

	$mysqli_result3 = mysqli_query($mysqli_link, $query3) or die (mysqli_error($mysqli_link));

	while($row3 = mysqli_fetch_array($mysqli_result3))	#################################### 240325
	{
		echo "$row3[sum], $row3[even], $row3[odd] - $row3[k_count]<br>";

		$temp_table3 = 'temp_sumeo_draw_' . $row3['sum'] . '_' . $row3['even'] . '_' . $row3['odd'];

		### filter $temp_table3 240325 ###
		$query_dc = "SELECT * FROM `ga_f5_draw_summary_by_sumeo2` WHERE `sum` = $row3[sum] AND `even` = $row3[even] AND `odd` = $row3[odd] ORDER BY `percent_wa` DESC";

		#echo "$query_dc<br>";

		$mysqli_result_dc = mysqli_query($mysqli_link, $query_dc) or die (mysqli_error($mysqli_link));

		#$row_dc = mysqli_fetch_array($mysqli_result_dc);

		#$num_rows_dc = mysqli_num_rows($mysqli_result_dc);

		while($row_dc = mysqli_fetch_array($mysqli_result_dc))
		{
			$query5 = "SELECT * FROM $temp_table3 ";
			$query5 .= "WHERE d0 = $row_dc[d0] ";
			$query5 .= "AND   d1 = $row_dc[d1] ";
			$query5 .= "AND   d2 = $row_dc[d2] ";
			$query5 .= "AND   d3 = $row_dc[d3] ";
			$query5 .= "AND   d4 = $row_dc[d4] ";
			$query5 .= "AND   dup1 <= 1 ";	
			$query5 .= "AND   dup2 <= 2 ";	
			$query5 .= "AND   dup3 <= 3 ";	
			$query5 .= "AND   dup4 <= 4 ";	
			$query5 .= "AND   rank0 <= 0 ";
			$query5 .= "AND   rank1 <= 1 ";
			$query5 .= "AND   rank2 <= 3 ";
			$query5 .= "AND   rank3 <= 3 ";
			$query5 .= "AND   rank4 <= 2 ";
			$query5 .= "AND   rank5 <= 3 ";
			$query5 .= "AND   rank6 <= 2 ";
			$query5 .= "AND   rank7 <= 0 ";
			$query5 .= "ORDER BY y1_sum DESC ";
			$query5 .= "LIMIT 10 ";	###########################################################
			### add test for k_count per sumeo ###240402 

			#echo "#################################################################	###<br>";
			#echo "$query5<br>";

			$mysqli_result5 = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));

			$num_rows = mysqli_num_rows($mysqli_result5);
			
			#echo "##     num_rows = $num_rows										###<br>";
			#echo "###############################################################	###<br>";

			while ($row5e = mysqli_fetch_array($mysqli_result5))
			{
				#echo "*** draw $row5e[1]-$row5e[2]-$row5e[3]-$row5e[4]-$row5e[5] - $row5e[y1_sum] (sum = $row5e[sum])<br>";

				$query7= "INSERT INTO $temp_table2 ";
				$query7.= "VALUES ('0', ";
				$query7.= "'$row5e[1]', ";
				$query7.= "'$row5e[2]', ";
				$query7.= "'$row5e[3]', ";
				$query7.= "'$row5e[4]', ";
				$query7.= "'$row5e[5]', ";
				for ($t = 6; $t <= 59; $t++)
				{
					$query7.= "'$row5e[$t]', ";
				}
				$query7.= "'1962-08-17') ";

				#echo "$query7<br>";
			
				$mysqli_result_7 = mysqli_query($mysqli_link, $query7) or die (mysqli_error($mysqli_link));
			}
		}
	}

	$query5 = "SELECT * FROM $temp_table1 ORDER BY k_count DESC ";

	echo "<p>$query5</p>";

	$mysqli_result5 = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));

	$count = 1;
	$wa_sum = 0.0;

	###################################################################################################################
	### reade k_counts for each sumeo
	###################################################################################################################
	while($row5 = mysqli_fetch_array($mysqli_result5))
	{
		$query6 = "SELECT * FROM $temp_table2 WHERE sum = $row5[sum] AND even = $row5[even] AND odd = $row5[odd] ";
		$query6 .= "AND comb5 = 0 ";
		$query6 .= "ORDER BY y1_sum DESC ";
		$query6 .= "LIMIT $row5[k_count] ";

		echo "<p>$query6</p>";

		$mysqli_result6 = mysqli_query($mysqli_link, $query6) or die (mysqli_error($mysqli_link));

		while($row6 = mysqli_fetch_array($mysqli_result6))
		{
			$query7 = "INSERT INTO $temp_table4 ";
			$query7.= "VALUES ('0', ";

			for ($t = 1; $t <= 59; $t++)
			{
				$query7.= "'$row6[$t]', ";
			}

			$query7.= "'1962-08-17') ";

			#echo "$query7<br>";
		
			$mysqli_result_7 = mysqli_query($mysqli_link, $query7) or die (mysqli_error($mysqli_link));
		}
	}

	### select 1k_count for each sumeo ################################################################################
	$query3 = "SELECT DISTINCT sum,even,odd,k_count FROM $temp_table1 ";
	$query3 .= "ORDER BY `k_count` DESC  ";

	echo "<p>$query3</p>";

	$mysqli_result3 = mysqli_query($mysqli_link, $query3) or die (mysqli_error($mysqli_link));

	while($row3 = mysqli_fetch_array($mysqli_result3))	
	{
		echo "<b>$row3[sum], $row3[even], $row3[odd] - $row3[k_count]</b><br>";

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
			for ($t = 6; $t <= 55; $t++)
			{
				$query7.= "'0', ";
			}
			$query7.= "'1962-08-17') ";

			$mysqli_result_7 = mysqli_query($mysqli_link, $query7) or die (mysqli_error($mysqli_link));

			#echo "$query7<br>";
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

	# $temp_table2 -> draw table

	###############################################################################################
	### build 
	###############################################################################################

	### ga_f5_column2_1

	$col1_num = array_fill (0,11,0);
	$col2_num = array_fill (0,11,0);
	$col3_num = array_fill (0,11,0);
	$col4_num = array_fill (0,11,0);
	$col5_num = array_fill (0,11,0);

	$draw_num[1] = $k;
	$draw_num[2] = $k+1;
	$draw_num[3] = $k+2;
	$draw_num[4] = $k+3;
	$draw_num[5] = $k+4;

	#print_r ($draw_num);
	
	$draw_num[1] = 8;
	$draw_num[2] = 11;
	$draw_num[3] = 21;
	$draw_num[4] = 22;

	print("<p align=\"center\"><b><font face=\"Arial, Helvetica, sans-serif\">Build Combo 4/$max_num</font></b></p>");

	while($draw_num[1] <= 37)
	{
		$num_rows = 0;

		$temp_table = "combo_4_42";

		/*
		### check for existing table row and skip
		$query6 = "SELECT * FROM $temp_table ";
		$query6 .= "WHERE b1 = '$draw_num[1]' ";
		$query6 .= "AND   b2 = '$draw_num[2]' ";
		$query6 .= "AND   b3 = '$draw_num[3]' ";
		$query6 .= "AND   b4 = '$draw_num[4]' ";
		$query6 .= "AND   b5 = '$draw_num[5]' ";

		echo $query6;

		$mysqli_result6 = mysqli_query($query6, $mysqli_link) or die (mysqli_error($mysqli_link));

		$num_rows = mysqli_num_rows($mysqli_result6);
		
		if ($num_row == 0) # good
		{
			*/
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

			$draw_array = array ($draw_num[1],$draw_num[2],$draw_num[3],$draw_num[4]);
			$draw_array_0 = array (0,$draw_num[1],$draw_num[2],$draw_num[3],$draw_num[4]);

			$sum =	$draw_num[1] + $draw_num[2] + $draw_num[3] + $draw_num[4];

			print "<h3>$draw_num[1] - $draw_num[2] - $draw_num[3] - $draw_num[4]</h3>";
			
			#print "sum = $sum<br>";

			$seq2 = Count2Seq($draw_array);
			$seq3 = Count3Seq($draw_array);

			foreach ($draw_array as $val) 
			{ 
				if(!is_int($val/2)) 
				{ 
					$odd++; 
				} else { 
					$even++; 
				}
			}

			#$total_combin = test_combin($draw_num);

			// test modulus
			// test modulus
			for ($x = 1; $x <= 4; $x++) 
			{ 
				if ($draw_num[$x] > 0 && $draw_num[$x] < 10) {
					$y = $draw_num[$x];
					$mod[$y]++;
					$num_count[0]++;
				} elseif ($draw_num[$x] > 9 && $draw_num[$x] < 20) {
					$y = $draw_num[$x] - 10;
					$mod[$y]++;
					$num_count[1]++;
				} elseif ($draw_num[$x] > 19 && $draw_num[$x] < 30) {
					$y = $draw_num[$x] - 20;
					$mod[$y]++;
					$num_count[2]++;
				} elseif ($draw_num[$x] > 29 && $draw_num[$x] < 40) {
					$y = $draw_num[$x] - 30;
					$mod[$y]++;
					$num_count[3]++;
				} else {
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
					$avg = $sum/4;
					$median = ($draw_num[2]+$draw_num[3])/2;
					$quart1 = ($draw_num[1]+$draw_num[2])/2;
					$quart2 = ($draw_num[1]+$draw_num[2]+$draw_num[3])/2;
					$quart3 = ($draw_num[1]+$draw_num[2]+$draw_num[3]+$draw_num[4])/2;
					#$stdev = $s->stDev();
					#$variance = $s->variance();
					#$avedev = $s->harmonicMean();
					$avedev = 0;
					#$kurtosis = $s->kurtosis();
					#$skew = $s->skewness();

					$draw_array = array ($draw_num[1],$draw_num[2],$draw_num[3],$draw_num[4]);

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

					$query7 = "SELECT * FROM combo_4_42 ";
					$query7 .= "WHERE b1 = '$draw_num[1]' ";
					$query7 .= "AND   b2 = '$draw_num[2]' ";
					$query7 .= "AND   b3 = '$draw_num[3]' ";
					$query7 .= "AND   b4 = '$draw_num[4]' ";
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
							$query_delete = "DELETE FROM combo_4_42 ";
							$query_delete .= "WHERE id = $row7[id] ";

							#echo "$query_delete<br>";
						
							$mysqli_result_combin = mysqli_query($mysqli_link, $query_delete) or die (mysqli_error($mysqli_link));
						}
					} else {
						$draw_last = '1962-08-17';
					
						$hml = intval($sum/10)*10;

						#$temp_table = "combo_4_42";

						$query = "INSERT INTO `combo_4_42` (`id`, `b1`, `b2`, `b3`, `b4`, `sum`, `hml`, `even`, `odd`, `d0`, `d1`, `d2`, `d3`, `d4`, `rank0`, `rank1`, `rank2`, `rank3`, `rank4`, `rank5`, `rank6`, `rank7`, `mod_tot`, `mod_x`, `seq2`, `seq3`, `comb2`, `comb3`, `comb4`, `comb5`, `dup1`, `dup2`, `dup3`, `dup4`, `dup5`, `dup6`, `dup7`, `dup8`, `dup9`, `dup10`, `pair_sum`, `avg`, `median`, `harmean`, `geomean`, `quart1`, `quart2`, `quart3`, `stdev`, `variance`, `avedev`, `kurt`, `skew`, `devsq`, `wheel_cnt5000`, `wheel_percent_wa`, `draw_last`, `draw_count`, `y1_sum`, `last_updated`) VALUES ('0', '$draw_num[1]', '$draw_num[2]', '$draw_num[3]', '$draw_num[4]', 
						'$sum', '$hml', '$even', '$odd', 
						'$num_count[0]', '$num_count[1]', '$num_count[2]', '$num_count[3]', '$num_count[4]', 
						'0', '0', '0', '0', '0', '0', '0', '0', 
						'$mod_total', '$mod_x', 
						'$seq2', '$seq3', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0', '0.00', '1962-08-17', '0', '0.00', '1962-08-17')";

						$count++;
					}
				}
			}
		#}

		/*
		if ($draw_num[4] < $max_num) {
				$draw_num[4] = $draw_num[4] + 1;
		} elseif ($draw_num[3] < $max_num-1) {
				$draw_num[3] = $draw_num[3] + 1;
		} elseif ($draw_num[2] < $max_num-2) {
				$draw_num[2] = $draw_num[2] + 1;
				$draw_num[3] = $draw_num[2] + 1;
		} elseif ($draw_num[1] < $max_num-3) {
				$draw_num[1] = $draw_num[1] + 1;
				$draw_num[2] = $draw_num[1] + 1;
				$draw_num[3] = $draw_num[2] + 1;
		} */

		if ($draw_num[4] < $max_num) {
				$draw_num[4] = $draw_num[4] + 1;
		} elseif ($draw_num[3] < $max_num-1) {
				$draw_num[3] = $draw_num[3] + 1;
				$draw_num[4] = $draw_num[3] + 1;
		} elseif ($draw_num[2] < $max_num-2) {
				$draw_num[2] = $draw_num[2] + 1;
				$draw_num[3] = $draw_num[2] + 1;
				$draw_num[4] = $draw_num[3] + 1;
		} else {
				$draw_num[1] = $draw_num[1] + 1;	
				$draw_num[2] = $draw_num[1] + 1;
				$draw_num[3] = $draw_num[2] + 1;
				$draw_num[4] = $draw_num[3] + 1;
		}
				/*
				### calculate sum limits
				$k++;

				$sum_31 = array_fill (0,15,0);
				
				$query = "SELECT date,sum FROM fl_draws ";
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

				$sum_low = intval((($sum_31[0]*0.1)+($sum_31[1]*0.4)+($sum_31[2]*0.4)+($sum_31[3]*0.1)));

				# ----------------------------------------------------------------------

				$n = 0;

				rsort ($sum_31);

				$sum_high = intval((($sum_31[0]*0.1)+($sum_31[1]*0.4)+($sum_31[2]*0.4)+($sum_31[3]*0.1))+1);
				*/
				### END calculate sum limits
		

		if ($print_flag == 50000)
		{
			print "$draw_num[1],$draw_num[2],$draw_num[3],$draw_num[4]<br>";
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

	// Your array
	$array = [0, 0, 0, 0, 1, 19, 52, 55, 111, 166, 165, 115, 120, 67, 58, 39, 27, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];

	// Begin the table
	echo "<table border='1'>"; // Add table border for visibility
	echo "<tr><th>Index</th><th>Value</th></tr>"; // Table headers

	// Loop through the array to create table rows
	foreach ($array as $index => $value) {
		echo "<tr><td>$index</td><td>$value</td></tr>";
	}

	// End the table
	echo "</table>";

	#SELECT * FROM `ga_f5_sumeo_drange_summary` ORDER BY `ga_f5_sumeo_drange_summary`.`percent_wa` DESC

	### 1 - select sum_count_sum for sumeo
	#SELECT * FROM `ga_f5_sum_count_sum` WHERE `even` > 0 AND `odd`> 0 AND `year1`>=1 ORDER BY `ga_f5_sum_count_sum`.`percent_wa` DESC;

	### 2 - loop to build tables for sumeo

	### 3 - 

	### 4 - 

	### 5 - 

	### 6 - 

	print("</body>");
	print("</html>");

?>3/8/2024