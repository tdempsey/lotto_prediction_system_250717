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

	// ----------------------------------------------------------------------------------
	function test_combin_print_sum($limit) #200103
	{
		require ("includes/mysqli.php"); 

		global $debug,$draw_prefix, $hml, $range_low, $range_high;

		print("<h3>Combin Report - Sum - Limit $limit</h3>\n");

		$query = "SELECT * FROM ga_f5_draws ";
		if ($hml)
		{
			$query .= "WHERE sum >= $range_low  ";
			$query .= "AND   sum <= $range_high  ";
		}
		$query .= "ORDER BY date DESC ";
		$query .= "LIMIT $limit ";

		#echo "$query</br>";

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

		print("<TABLE BORDER=\"1\">\n");

		//create header row
		print("<TR><B>\n");
		print("<TD BGCOLOR=\"#CCCCCC\" align=center>Date</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>2</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">&nbsp;3&nbsp;</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">&nbsp;4&nbsp;</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">&nbsp;5&nbsp;</TD>\n");
		print("</B></TR>\n");

		$total2_failed = 0;
		$total3_failed = 0;
		$total4_failed = 0;
		$total5_failed = 0;

		$last_date = '1962-08-17';

		while($row_draw = mysqli_fetch_array($mysqli_result))
		{
			$hml = $row_draw[sum] + 500;
			
			print("<TR>\n");  
			print("<TD>$row_draw[date]</TD>\n");

			# update combo table
			$query3 = "SELECT * FROM $draw_prefix";
			$query3 .= "combo_table_sum ";
			$query3 .= "WHERE date = '$row_draw[date]' ";

			#echo "$query3<br>";

			$mysqli_result3 = mysqli_query($mysqli_link, $query3) or die (mysqli_error($mysqli_link));

			$num_rows_combo = mysqli_num_rows($mysqli_result3); 

			#echo "num_rows_combo = $num_rows_combo<br>";

			if ($num_rows_combo)
			{
				$row_combo = mysqli_fetch_array($mysqli_result3); 
				$total2 = $row_combo[comb2];
				$total3 = $row_combo[comb3];
				$total4 = $row_combo[comb4];
				$total5 = $row_combo[comb5];
			} else {
				$total2 = 0;
				$total3 = 0;
				$total4 = 0;
				$total5 = 0;

				// count 2
				for ($c = 1; $c <= 10; $c++)
				{
					switch ($c) { 
					   case 1: 
						   $d1 = $row_draw[1];
						   $d2 = $row_draw[2];
						   break; 
					   case 2: 
						   $d1 = $row_draw[1];
						   $d2 = $row_draw[3];
						   break; 
					   case 3: 
						   $d1 = $row_draw[1];
						   $d2 = $row_draw[4];
						   break; 
					   case 4: 
						   $d1 = $row_draw[1];
						   $d2 = $row_draw[5];
						   break;
					   case 5: 
						   $d1 = $row_draw[2];
						   $d2 = $row_draw[3];
						   break;
					   case 6: 
						   $d1 = $row_draw[2];
						   $d2 = $row_draw[4];
						   break; 
					   case 7: 
						   $d1 = $row_draw[2];
						   $d2 = $row_draw[5];
						   break; 
					   case 8: 
						   $d1 = $row_draw[3];
						   $d2 = $row_draw[4];
						   break;
					   case 9: 
						   $d1 = $row_draw[3];
						   $d2 = $row_draw[5];
						   break;
					   case 10: 
						   $d1 = $row_draw[4];
						   $d2 = $row_draw[5];
						   break;
					} 

					$query2 = "SELECT DISTINCT * FROM ga_f5_2_42 ";
					$query2 .= "WHERE d1 = $d1 ";
					$query2 .= "  AND d2 = $d2 ";
					$query2 .= "  AND combo = $c "; #200103
					$query2 .= "  AND hml = $hml "; #200103
					$query2 .= "  AND date < '$row_draw[date]' ";

					#echo "$query2<br>";

					$mysqli_result2 = mysqli_query($mysqli_link, $query2) or die (mysqli_error($mysqli_link));
					
					if ($num_rows = mysqli_num_rows($mysqli_result2))
					{
						//$total2 += $num_rows;
						$total2++;
					}

					#echo "num_rows = $num_rows<br>";
				}

				// count 3
				for ($c = 1; $c <= 10; $c++)
				{
					switch ($c) { 
					   case 1: 
						   $d1 = $row_draw[1];
						   $d2 = $row_draw[2];
						   $d3 = $row_draw[3];
						   break; 
					   case 2: 
						   $d1 = $row_draw[1];
						   $d2 = $row_draw[2];
						   $d3 = $row_draw[4];
						   break; 
					   case 3: 
						   $d1 = $row_draw[1];
						   $d2 = $row_draw[2];
						   $d3 = $row_draw[5];
						   break; 
					   case 4: 
						   $d1 = $row_draw[1];
						   $d2 = $row_draw[3];
						   $d3 = $row_draw[4];
						   break;
					   case 5: 
						   $d1 = $row_draw[1];
						   $d2 = $row_draw[3];
						   $d3 = $row_draw[5];
						   break;
					   case 6: 
						   $d1 = $row_draw[1];
						   $d2 = $row_draw[4];
						   $d3 = $row_draw[5];
						   break; 
					   case 7: 
						   $d1 = $row_draw[2];
						   $d2 = $row_draw[3];
						   $d3 = $row_draw[4];
						   break;
					   case 8: 
						   $d1 = $row_draw[2];
						   $d2 = $row_draw[3];
						   $d3 = $row_draw[5];
						   break;
					   case 9: 
						   $d1 = $row_draw[2];
						   $d2 = $row_draw[4];
						   $d3 = $row_draw[5];
						   break;
					   case 10: 
						   $d1 = $row_draw[3];
						   $d2 = $row_draw[4];
						   $d3 = $row_draw[5];
						   break;
					} 

					$query3 = "SELECT DISTINCT * FROM ga_f5_3_42 ";
					$query3 .= "WHERE d1 = $d1 ";
					$query3 .= "  AND d2 = $d2 ";
					$query3 .= "  AND d3 = $d3 ";
					$query3 .= "  AND combo = $c "; #200103
					$query3 .= "  AND hml = $hml "; #200103
					$query3 .= "  AND date < '$row_draw[date]' ";

					#echo "$query3<br>";

					$mysqli_result3 = mysqli_query($mysqli_link, $query3) or die (mysqli_error($mysqli_link));
					
					if ($num_rows = mysqli_num_rows($mysqli_result3))
					{
						//$total3 += $num_rows;
						$total3++;
					}
				}

				// count 4
				for ($c = 1; $c <= 5; $c++)
				{
					switch ($c) { 
					   case 1: 
						   $d1 = $row_draw[1];
						   $d2 = $row_draw[2];
						   $d3 = $row_draw[3];
						   $d4 = $row_draw[4];
						   break; 
					   case 2: 
						   $d1 = $row_draw[1];
						   $d2 = $row_draw[2];
						   $d3 = $row_draw[3];
						   $d4 = $row_draw[5];
						   break; 
					   case 3: 
						   $d1 = $row_draw[1];
						   $d2 = $row_draw[2];
						   $d3 = $row_draw[4];
						   $d4 = $row_draw[5];
						   break;
					   case 4: 
						   $d1 = $row_draw[1];
						   $d2 = $row_draw[3];
						   $d3 = $row_draw[4];
						   $d4 = $row_draw[5];
						   break; 
					   case 5: 
						   $d1 = $row_draw[2];
						   $d2 = $row_draw[3];
						   $d3 = $row_draw[4];
						   $d4 = $row_draw[5];
						   break;
					} 

					$query4 = "SELECT DISTINCT * FROM ga_f5_4_42 ";
					$query4 .= "WHERE d1 = $d1 ";
					$query4 .= "  AND d2 = $d2 ";
					$query4 .= "  AND d3 = $d3 ";
					$query4 .= "  AND d4 = $d4 ";
					$query4 .= "  AND combo = $c "; #200103
					$query4 .= "  AND hml = $hml "; #200103
					$query4 .= "  AND date < '$row_draw[date]' ";

					#echo "$query4<br>";

					$mysqli_result4 = mysqli_query($mysqli_link, $query4) or die (mysqli_error($mysqli_link));
					
					if ($num_rows = mysqli_num_rows($mysqli_result4))
					{
						//$total4 += $num_rows;
						$total4++;
					}
				}

				// count 5
				$query6 = "SELECT DISTINCT * FROM ga_f5_draws ";
				$query6 .= "WHERE b1 = $row_draw[b1] ";
				$query6 .= "  AND b2 = $row_draw[b2] ";
				$query6 .= "  AND b3 = $row_draw[b3] ";
				$query6 .= "  AND b4 = $row_draw[b4] ";
				$query6 .= "  AND b5 = $row_draw[b5] ";
				$query6 .= "  AND date < '$row_draw[date]' ";

				#echo "$query6<br>";

				$mysqli_result6 = mysqli_query($mysqli_link, $query6) or die (mysqli_error($mysqli_link));
				
				if ($num_rows = mysqli_num_rows($mysqli_result6))
				{
					//$total5 += $num_rows;
					$total5++;
				}

				# update combo table	
				$query3 = "SELECT * FROM $draw_prefix";
				$query3 .= "combo_table_sum ";
				$query3 .= "WHERE date = '$row_draw[date]' ";

				#echo "$query3<br>";

				$mysqli_result3 = mysqli_query($mysqli_link, $query3) or die (mysqli_error($mysqli_link));

				$num_rows_combo = mysqli_num_rows($mysqli_result3); 

				combo_table_update_sum($row_draw[date],$total2,$total3,$total4,$total5);
			}

			if ($total2 == 10)
			{
				print("<TD align=center>$total2</TD>\n");
			} else {
				print("<TD align=center><font color=\"#ff0000\"><b>$total2</b></font></TD>\n");
				$total2_failed++;
			}

			if ($total3 == 10)
			{
				print("<TD align=center>$total3</TD>\n");
			} else {
				print("<TD align=center><font color=\"#ff0000\"><b>$total3</b></font></TD>\n");
				$total3_failed++;
			}

			if ($total4 <= 2)
			{
				print("<TD align=center>$total4</TD>\n");
			} else {
				print("<TD align=center><font color=\"#ff0000\"><b>$total4</b></font></TD>\n");
				$total4_failed++;
			}

			if ($total5 == 0)
			{
				print("<TD align=center>$total5</TD>\n");
			} else {
				print("<TD align=center><font color=\"#ff0000\"><b>$total5</b></font></TD>\n");
				$total5_failed++;
			}

			print("</TR>\n");

			$last_date = $row_draw[date];
		}

		print("<TR><B>\n");
		print("<TD align=center align=center>&nbsp;</TD>\n");
		print("<TD align=center><font color=\"#ff0000\">$total2_failed</font></TD>\n");
		print("<TD align=center><font color=\"#ff0000\">$total3_failed</font></TD>\n");
		print("<TD align=center><font color=\"#ff0000\">$total4_failed</font></TD>\n");
		print("<TD align=center><font color=\"#ff0000\">$total5_failed</font></TD>\n");
		print("</B></TR>\n");

		print("</TABLE>\n");

		return ($last_date);
	}

	// ----------------------------------------------------------------------------------
	function test_combin($draw_num) 
	{
		#echo "test_combin()</b>";

		require ("includes/mysqli.php"); 

		global $debug;

		$total_combin = array_fill (0,7,0);
		
		// count 2
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
				   $d1 = $draw_num[4];
				   $d2 = $draw_num[5];
				   break;
			} 

			$query2 = "SELECT DISTINCT * FROM ga_f5_2_42 ";
			$query2 .= "WHERE d1 = $d1 ";
			$query2 .= "  AND d2 = $d2 ";
			$query2 .= "  AND combo = $c ";

			#echo "$query2<br>";

			$mysqli_result2 = mysqli_query($mysqli_link, $query2) or die (mysqli_error($mysqli_link));

			$temp = mysqli_num_rows($mysqli_result2);
			#echo "mysqli_num_rows = $temp<br>";
			
			if (mysqli_num_rows($mysqli_result2))
			{
				$total_combin[2]++;
			}
		}
		
		// count 3
		for ($c = 1; $c <= 10; $c++)
		{
			switch ($c) { 
			   case 1: 
				   $d1 = $draw_num[1];
				   $d2 = $draw_num[2];
				   $d3 = $draw_num[3];
				   break; 
			   case 2: 
				   $d1 = $draw_num[1];
				   $d2 = $draw_num[2];
				   $d3 = $draw_num[4];
				   break; 
			   case 3: 
				   $d1 = $draw_num[1];
				   $d2 = $draw_num[2];
				   $d3 = $draw_num[5];
				   break; 
			   case 4: 
				   $d1 = $draw_num[1];
				   $d2 = $draw_num[3];
				   $d3 = $draw_num[4];
				   break;
			   case 5: 
				   $d1 = $draw_num[1];
				   $d2 = $draw_num[3];
				   $d3 = $draw_num[5];
				   break;
			   case 6: 
				   $d1 = $draw_num[1];
				   $d2 = $draw_num[4];
				   $d3 = $draw_num[5];
				   break; 
			   case 7: 
				   $d1 = $draw_num[2];
				   $d2 = $draw_num[3];
				   $d3 = $draw_num[4];
				   break;
			   case 8: 
				   $d1 = $draw_num[2];
				   $d2 = $draw_num[3];
				   $d3 = $draw_num[5];
				   break;
			   case 9: 
				   $d1 = $draw_num[2];
				   $d2 = $draw_num[4];
				   $d3 = $draw_num[5];
				   break;
			   case 10: 
				   $d1 = $draw_num[3];
				   $d2 = $draw_num[4];
				   $d3 = $draw_num[5];
				   break;
			} 

			$query3 = "SELECT DISTINCT * FROM ga_f5_3_42 ";
			$query3 .= "WHERE d1 = $d1 ";
			$query3 .= "  AND d2 = $d2 ";
			$query3 .= "  AND d3 = $d3 ";
			$query3 .= "  AND combo = $c ";

			#echo "$query3<br>";

			$mysqli_result3 = mysqli_query($mysqli_link, $query3) or die (mysqli_error($mysqli_link));

			$temp = mysqli_num_rows($mysqli_result3);
			#echo "mysqli_num_rows = $temp<br>";
			
			if (mysqli_num_rows($mysqli_result3))
			{
				$total_combin[3]++;
			}
		}

		// count 4
		for ($c = 1; $c <= 5; $c++)
		{
			switch ($c) { 
			   case 1: 
				   $d1 = $draw_num[1];
				   $d2 = $draw_num[2];
				   $d3 = $draw_num[3];
				   $d4 = $draw_num[4];
				   break; 
			   case 2: 
				   $d1 = $draw_num[1];
				   $d2 = $draw_num[2];
				   $d3 = $draw_num[3];
				   $d4 = $draw_num[5];
				   break; 
			   case 3: 
				   $d1 = $draw_num[1];
				   $d2 = $draw_num[2];
				   $d3 = $draw_num[4];
				   $d4 = $draw_num[5];
				   break;
			   case 4: 
				   $d1 = $draw_num[1];
				   $d2 = $draw_num[3];
				   $d3 = $draw_num[4];
				   $d4 = $draw_num[5];
				   break; 
			   case 5: 
				   $d1 = $draw_num[2];
				   $d2 = $draw_num[3];
				   $d3 = $draw_num[4];
				   $d4 = $draw_num[5];
				   break;
			} 

			$query4 = "SELECT DISTINCT * FROM ga_f5_4_42 ";
			$query4 .= "WHERE d1 = $d1 ";
			$query4 .= "  AND d2 = $d2 ";
			$query4 .= "  AND d3 = $d3 ";
			$query4 .= "  AND d4 = $d4 ";
			$query4 .= "  AND combo = $c ";

			#echo "$query4<br>";

			$mysqli_result4 = mysqli_query($mysqli_link, $query4) or die (mysqli_error($mysqli_link));
			
			if (mysqli_num_rows($mysqli_result4))
			{
				$total_combin[4]++;
			}
		}
		
		// count 5
		$query6 = "SELECT DISTINCT * FROM ga_f5_draws ";
		$query6 .= "WHERE b1 = $draw_num[1] ";
		$query6 .= "  AND b2 = $draw_num[2] ";
		$query6 .= "  AND b3 = $draw_num[3] ";
		$query6 .= "  AND b4 = $draw_num[4] ";
		$query6 .= "  AND b5 = $draw_num[5] ";

		#echo "$query6<br>";

		$mysqli_result6 = mysqli_query($mysqli_link, $query6) or die (mysqli_error($mysqli_link));
		
		if ($num_draw_nums = mysqli_num_rows($mysqli_result6))
		{
			$total_combin[5]++;
		}

		return $total_combin;
	}

	//start HTML page
	print("<HTML>\n");
	print("<HEAD>\n");
	print("<TITLE>Lotto Update drange2 - $game_name - Rank Limit</TITLE>\n");
	print("</HEAD>\n");
	
	print("<BODY>\n");

	### build whell16_array exclude last two draws - 001 ##########################################

	#$wheel16_array = array(0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16); ############################

	$wheel16_array = array(0,2,6,9,13,14,15,17,20,21,22,30,32,34,38,40,41); #######################

	$wheel16_array2 = array(0,1,5,6,9,13,14,17,20,22,28,30,32,34,38,40,41); #######################

	$query4 = "INSERT INTO `ga_f5_wheel16_array` (`id`, `date`, `b1`, `b2`, `b3`, `b4`, `b5`, `b6`, `b7`, `b8`, `b9`, `b10`, `b11`, `b12`, `b13`, `b14`, `b15`, `b16`) VALUES ('0', '$curr_date_dash', ";

	for ($k = 1; $k <= 15; $k++)
	{
		$query4 .= "$wheel16_array2[$k], ";
	}

	$query4 .= "$wheel16_array2[16]) ";

	print("$query4<br>");

	$mysqli_result4 = mysqli_query($mysqli_link, $query4) or die (mysqli_error($mysqli_link));
		
	###############################################################################################


	### build rank table ##########################################################################

	$rank_table = BuildRankTable($curr_date_dash);

	### build lastxdraws ##########################################################################
	
	for ($x = 1; $x <= 50; $x++)
	{
		${last_.$x._draws} = array_fill (0,51,0);
	}

	for ($x = 1; $x <= 50; $x++)
	{
		${last_.$x._draws} = LastDraws($curr_date_dash,$x);
	}	


	### get count draw table ######################################################################
	$query5 = "SELECT DISTINCT d1,d2,d3,d4,d5 FROM wheels16 ";
	$query5 .= "WHERE id > 1366 ";
	$query5 .= "ORDER BY d1 ASC, d2 ASC, d3 ASC, d4 ASC, d5 ASC ";

	echo "$query5<br>";

	$mysqli_result5 = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));

	while ($row5 = mysqli_fetch_array($mysqli_result5))
	{
		if ($row5[0] > 8) {
			break;
		}

		$draw = array_fill (0,5,0);

		$even = 0;
		$odd = 0;

		$temp1 = $row5[d1];
		$draw[0] = $wheel16_array2[$temp1];
		$temp2 = $row5[d2];
		$draw[1] = $wheel16_array2[$temp2];
		$temp3 = $row5[d3];
		$draw[2] = $wheel16_array2[$temp3];
		$temp4 = $row5[d4];
		$draw[3] = $wheel16_array2[$temp4];
		$temp5 = $row5[d5];
		$draw[4] = $wheel16_array2[$temp5];

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
				for ($y = 0 ; $y <= 4; $y++)
				{	
					if (array_search($draw[$y], ${last_.$x._draws}) !== FALSE)
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
			
			#echo "$query1<p>";

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
			'0', '0', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1962-08-17')";

			print("$query4<br>");

			$mysqli_result4 = mysqli_query($mysqli_link, $query4) or die (mysqli_error($mysqli_link));

			#die();

			#require ("includes_ga_f5/print_wheels16_draws.incl");
	}

	print("<h2>Completed!</h2>");

	wheel16_split_draws_4 ($dateDiff);

	print("</BODY>\n");
?>




