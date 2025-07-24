<?php
	// ----------------------------------------------------------------------------------
	function split_sumeo6_5($sum,$even,$odd)
	{
		global $debug, $draw_table_name, $balls, $balls_drawn, $draw_prefix, $game, $hml; 

		require ("includes/mysqli.php"); 

		$curr_date = date('Y-m-d');

		##################### LIMIT ###################################################################

		$limit_temp = array_fill(0,2,0);
		$limit_array = array_fill(0,5,$limit_temp);

		$date_temp2 = explode('-',$curr_date);

		$sumeo_table = "ga_f5_limits_by_sumeo_20" . $date_temp2[1] . ($date_temp2[2]-1);
		
		$query_limit = "SELECT * FROM $sumeo_table WHERE sum = $sum AND even = $even AND odd = $odd ORDER BY col ASC ";
		#$query_limit = "SELECT * FROM ga_f5_limits_by_sumeo_m2 WHERE sum = $sum AND even = $even AND odd = $odd ORDER BY col ASC ";

		echo "<b>$query_limit</b><br>";

		$mysqli_result = mysqli_query($mysqli_link, $query_limit) or die (mysqli_error($mysqli_link));

		while($row_limit = mysqli_fetch_array($mysqli_result))
		{
			$col_temp = $row_limit[col];

			$limit_array[$col_temp][0] = $row_limit[low];
			$limit_array[$col_temp][1] = $row_limit[high];
		} 

		#echo "limit_array = <br>";
		#print_r ($limit_array);
		#echo "<br>";

		###############################################################################################

		$table_temp1 = 'temp_2a_' . $balls_drawn . '_' . $balls . '_' . $sum . '_' . $even . '_' . $odd;

		$query = "DROP TABLE IF EXISTS $table_temp1 ";

		#echo "$query<br>";

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

		$low = array_fill(0,6,0);
		$high = array_fill(0,6,0);

		for ($y = 1; $y <=5; $y++)
		{
			$low[$y] = $limit_array[$y][0] - 2;
			$high[$y] = $limit_array[$y][1] + 2;
		}

		echo "low = <br>";
		print_r ($low);
		echo "<br>";


		echo "high = <br>";
		print_r ($high);
		echo "<br>";


		$query_copy = "CREATE TABLE $table_temp1 SELECT * FROM combo_";
		$query_copy .= "$balls_drawn";
		$query_copy .= "_$balls ";
		$query_copy .= "WHERE sum  = $sum ";
		#$query_copy .= "AND   b1   >= {$limit_array[1][0]} "; #200203
		$query_copy .= "AND   b1   >= 1 "; #200912
		$query_copy .= "AND   b1   <= $high[1] "; #200912
		$query_copy .= "AND   b2   >= $low[2] "; #200912
		$query_copy .= "AND   b2   <= $high[2] "; #200912
		$query_copy .= "AND   b3   >= $low[3] "; #200912
		$query_copy .= "AND   b3   <= $high[3] "; #200912
		$query_copy .= "AND   b4   >= $low[4] "; #200912
		$query_copy .= "AND   b4   <= $high[4] "; #200912
		$query_copy .= "AND   b5   >= $low[5] "; #200912
		$query_copy .= "AND   b5   <= 42 "; #200912

		/*
		$query_copy .= "AND   b1   <= {$limit_array[1][1]} "; #200203
		$query_copy .= "AND   b2   >= {$limit_array[2][0]} "; #200203
		$query_copy .= "AND   b2   <= {$limit_array[2][1]} "; #200203
		$query_copy .= "AND   b3   >= {$limit_array[3][0]} "; #200203
		$query_copy .= "AND   b3   <= {$limit_array[3][1]} "; #200203
		$query_copy .= "AND   b4   >= {$limit_array[4][0]} "; #200203
		$query_copy .= "AND   b4   <= {$limit_array[4][1]} "; #200203
		$query_copy .= "AND   b5   >= {$limit_array[5][0]} "; #200203
		#$query_copy .= "AND   b5   <= {$limit_array[5][1]} "; #200203
		*/
		
		$query_copy .= "AND   even = $even ";
		$query_copy .= "AND   odd  = $odd ";
		$query_copy .= "AND   seq2  <= 1 ";
		$query_copy .= "AND   seq3  = 0 ";
		$query_copy .= "AND   mod_tot  <= 1 ";
		#$query_copy .= "AND   mod_x  = 0 ";

		#echo "$query_copy<br>";

		$mysqli_result = mysqli_query($mysqli_link, $query_copy) or die (mysqli_error($mysqli_link));

		$query5 = "SELECT count(*) FROM $table_temp1 ";

		#echo "$query5<br>";

		$mysqli_result = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));

		$cnt = mysqli_fetch_array($mysqli_result);

		echo "3 - num_rows copied = $cnt[0]<p>";

		##################### SumEO6 ########################################################################

		$table_temp2 = 'temp_5a_' . $balls_drawn . '_' . $balls . '_' . $sum . '_' . $even . '_' . $odd;

		$query = "DROP TABLE IF EXISTS $table_temp2 ";

		#echo "$query<br>";

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

		$query3 = "CREATE TABLE $table_temp2 LIKE $table_temp1 ";

		#echo "$query3<br>";

		$mysqli_result = mysqli_query($mysqli_link, $query3) or die (mysqli_error($mysqli_link));

		#----------------------------------------------------------------------------------------------------
		
		$query_drange6 = "SELECT * FROM `ga_f5_sumeo_drange6` WHERE sum = $sum AND even = $even AND odd = $odd AND percent_wa > 0.10 ORDER BY `ga_f5_sumeo_drange6`.`percent_wa` DESC ";

		echo "<b>$query_drange6</b><br>";

		$mysqli_result_drange6 = mysqli_query($mysqli_link, $query_drange6) or die (mysqli_error($mysqli_link));

		while ($row_drange6 = mysqli_fetch_array($mysqli_result_drange6))
		{
			$query_sumeo6 = "SELECT * FROM combo_5_42_drange6 a ";
			$query_sumeo6 .= "JOIN $table_temp1 b ";
			$query_sumeo6 .= "ON  a.combo_id = b.id ";
			$query_sumeo6 .= "WHERE a.d6_1 = $row_drange6[d6_1] ";
			$query_sumeo6 .= "AND   a.d6_2 = $row_drange6[d6_2] ";
			$query_sumeo6 .= "AND   a.d6_3 = $row_drange6[d6_3] ";
			$query_sumeo6 .= "AND   a.d6_4 = $row_drange6[d6_4] ";
			$query_sumeo6 .= "AND   a.d6_5 = $row_drange6[d6_5] ";
			$query_sumeo6 .= "AND   a.d6_6 = $row_drange6[d6_6] ";
			#$query_sumeo6 .= "AND   a.d6_7 = $row_drange6[d6_7] ";

			echo "<b>$query_sumeo6</b><br>";

			$mysqli_result_sumeo6 = mysqli_query($mysqli_link, $query_sumeo6) or die (mysqli_error($mysqli_link));

			$num_rows_sumeo6 = mysqli_num_rows($mysqli_result_sumeo6);

			echo "num_rows_sumeo6 = $num_rows_sumeo6<p>";

			while ($row_sumeo6 = mysqli_fetch_array($mysqli_result_sumeo6))
			{
				$query5 = "INSERT INTO $table_temp2 ";
				$query5 .= "SELECT * FROM $table_temp1 ";
				$query5 .= "WHERE id = $row_sumeo6[id] ";
				
				#print "$query5<p>";
			
				$mysqli_result5 = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));
			}
		}

		$query5 = "SELECT count(*) FROM $table_temp2 ";

		#echo "$query5<br>";

		$mysqli_result = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));

		$cnt = mysqli_fetch_array($mysqli_result);

		echo "4 - num_rows copied = $cnt[0]<p>";

		######################################################################################################

		print("<h3>Count - Col1/Col5 - SumEO = $sum,$even,$odd</h3>\n");
		#print("<P>");
		print("<TABLE BORDER=\"1\">\n");

		//create header row
		print("<TR><B>\n");

		print("<TD BGCOLOR=\"#CCCCCC\" align=\"center\">Col1</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Col5</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Count</center></TD>\n");
		print("</B></TR>\n");

		############################################################################################
		$query = "SELECT b1, b5, count(*) FROM $table_temp2 ";
		$query .= "GROUP BY b1, b5 ";
		#$query .= "ORDER b1 ASC, b5 ASC ";
		
		#echo "$query<p>";

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

		// get each row
		while($row = mysqli_fetch_array($mysqli_result))
		{
			if ($row[2] > 0) #25
			{
				print("<TR>\n");
				print("<TD><center>$row[b1]</center></TD>\n");
				print("<TD><center>$row[b5]</center></TD>\n");
				print("<TD align=center>$row[2]</TD>\n");
				print("</TR>\n");
			}
		}

		print("<TR><B>\n");

		print("<TD BGCOLOR=\"#CCCCCC\" align=\"center\">Col1</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Col5</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Sum</center></TD>\n");
		print("</B></TR>\n");

		//end table
		print("</TABLE>\n");
	}
?>