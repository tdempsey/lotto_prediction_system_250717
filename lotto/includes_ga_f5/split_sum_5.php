<?php
	// ----------------------------------------------------------------------------------
	function split_sum_5($sum)
	{
		global $debug, $draw_table_name, $balls, $balls_drawn, $draw_prefix, $game, $hml, $dateDiff; 

		require ("includes/mysqli.php"); 

		$curr_date = date('Y-m-d');

		###############################################################################################

		$table_temp1 = 'temp_filter_' . $balls_drawn . . '_' . $balls . '_' . $sum . '_nofilter';

		$query = "DROP TABLE IF EXISTS $table_temp1 ";

		#echo "$query<br>";

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

		/*
		#echo "low = <br>";
		print_r ($low);
		#echo "<br>";

		#echo "high = <br>";
		print_r ($high);
		#echo "<br>";
		*/

		$query_copy = "CREATE TABLE $table_temp1 SELECT * FROM combo_";
		$query_copy .= "$balls_drawn";
		$query_copy .= "_$balls ";
		$query_copy .= "WHERE sum  = $sum ";
		

		#echo "no filter - $query_copy<br>";

		$mysqli_result = mysqli_query($mysqli_link, $query_copy) or die (mysqli_error($mysqli_link));

		$query5 = "SELECT count(sum) FROM $table_temp1 ";

		#echo "$query5<br>";

		$mysqli_result = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));

		$cnt = mysqli_fetch_array($mysqli_result);

		#echo "no filter - num_rows copied = $cnt[0]<p>";

		##################### LIMIT ###################################################################

		$limit_temp = array_fill(0,2,0);
		$limit_array = array_fill(0,5,$limit_temp);

		$date_temp2 = explode('-',$curr_date);

		#$sum_table = "ga_f5_limits_by_sum_20" . $date_temp2[1] . ($date_temp2[2]-1);
		$sum_table = "ga_f5_limits_by_sum";

		#$sum_table = "ga_f5_limits_by_sum"; #201011
		
		$query_limit = "SELECT * FROM $sum_table WHERE sum = $sum ORDER BY col ASC ";

		#echo "<b>$query_limit</b><br>";

		$mysqli_result = mysqli_query($mysqli_link, $query_limit) or die (mysqli_error($mysqli_link));

		while($row_limit = mysqli_fetch_array($mysqli_result))
		{
			$col_temp = $row_limit[2];

			$limit_array[$col_temp][0] = $row_limit[5];
			$limit_array[$col_temp][1] = $row_limit[6];
		} 

		#echo "limit_array = <br>";
		#print_r ($limit_array);
		#echo "<br>";

		###############################################################################################

		$table_temp1 = 'temp_filter_' . $balls_drawn . '_' . $balls . '_' . $sum;

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

		/*
		#echo "low = <br>";
		print_r ($low);
		#echo "<br>";

		#echo "high = <br>";
		print_r ($high);
		#echo "<br>";
		*/

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
		
		$query_copy .= "AND   seq2  <= 1 ";
		$query_copy .= "AND   seq3  = 0 ";
		$query_copy .= "AND   mod_tot  <= 1 ";
		#$query_copy .= "AND   mod_x  = 0 ";

		#cho "filter by range - $query_copy<br>";

		$mysqli_result = mysqli_query($mysqli_link, $query_copy) or die (mysqli_error($mysqli_link));

		$query5 = "SELECT count(*) FROM $table_temp1 ";

		#echo "table_temp1 - $query5<br>";

		$mysqli_result = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));

		$cnt = mysqli_fetch_array($mysqli_result);

		#echo "num_rows copied = $cnt[0]<p>";

		##################### sum5 ########################################################################

		$table_temp2 = 'temp_5_' . $balls_drawn . '_' . $balls . '_' . $sum;

		$query = "DROP TABLE IF EXISTS $table_temp2 ";

		#echo "$query<br>";

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

		$query3 = "CREATE TABLE $table_temp2 LIKE $table_temp1 ";

		#echo "$query3<br>";

		$mysqli_result = mysqli_query($mysqli_link, $query3) or die (mysqli_error($mysqli_link));

		#----------------------------------------------------------------------------------------------------
		
		$query_drange4 = "SELECT * FROM `ga_f5_sum_drange4` WHERE sum = $sum AND percent_wa > 0.010 ORDER BY `ga_f5_sum_drange4`.`percent_wa` DESC ";

		#echo "<b>$query_drange4</b><br>";

		$mysqli_result_drange4 = mysqli_query($mysqli_link, $query_drange4) or die (mysqli_error($mysqli_link));

		while ($row_drange4 = mysqli_fetch_array($mysqli_result_drange4))
		{
			$query_sum5 = "SELECT * FROM combo_5_42_drange4 a ";
			$query_sum5 .= "JOIN $table_temp1 b ";
			$query_sum5 .= "ON  a.combo_id = b.id ";
			$query_sum5 .= "WHERE a.d4_1 = $row_drange4[d4_1] ";
			$query_sum5 .= "AND   a.d4_2 = $row_drange4[d4_2] ";
			$query_sum5 .= "AND   a.d4_3 = $row_drange4[d4_3] ";
			$query_sum5 .= "AND   a.d4_4 = $row_drange4[d4_4] ";
			#$query_sum5 .= "AND   a.d4_5 = $row_drange4[d4_5] ";
			#$query_sum5 .= "AND   a.d4_6 = $row_drange4[d4_6] ";
			#$query_sum5 .= "AND   a.d4_7 = $row_drange4[d4_7] ";

			#echo "<b>$query_sum5</b><br>";

			$mysqli_result_sum5 = mysqli_query($mysqli_link, $query_sum5) or die (mysqli_error($mysqli_link));

			$num_rows_sum5 = mysqli_num_rows($mysqli_result_sum5);

			#echo "num_rows_sum5 = $num_rows_sum5<p>";

			while ($row_sum5 = mysqli_fetch_array($mysqli_result_sum5))
			{
				$query5 = "INSERT INTO $table_temp2 ";
				$query5 .= "SELECT * FROM $table_temp1 ";
				$query5 .= "WHERE id = $row_sum5[id] ";
				
				#echo "$query5<p>";
			
				$mysqli_result5 = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));
			}
		}

		$query5 = "SELECT count(*) FROM $table_temp2 ";

		#echo "$query5<br>";

		$mysqli_result = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));

		$cnt = mysqli_fetch_array($mysqli_result);

		#echo "num_rows copied = $cnt[0]<p>";

		###########################################################################################

		$table_temp3 = 'temp_split_count_1_2_' . $sum;

		$query6 = "DROP TABLE IF EXISTS $table_temp3 ";

		#echo "$query6<br>";

		$mysqli_result = mysqli_query($mysqli_link, $query6) or die (mysqli_error($mysqli_link));

		$query7 = "CREATE TABLE `$table_temp3` (
			  `id` smallint NOT NULL AUTO_INCREMENT,
			  `b1` tinyint NOT NULL,
			  `b2` tinyint NOT NULL,
			  `count` mediumint NOT NULL,
			  `y1_sum` float(4,2) NOT NULL, 
			  PRIMARY KEY (`id`),
			  KEY `date` (`id`)
			) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;
		";

		#echo "$query7<br>";

		$mysqli_result = mysqli_query($mysqli_link, $query7) or die (mysqli_error($mysqli_link));

		$query = "SELECT b1, b2, count(*) FROM $table_temp2 ";
		$query .= "GROUP BY b1, b2 ";
		
		#echo "table_temp2 - $query<p>";

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

		// get each row
		while($row = mysqli_fetch_array($mysqli_result))
		{
			if (1) #25
			{
				$y1_sum = 0.00;
	
				$query_col1 = "SELECT * FROM temp_column_sum";
				$query_col1 .= "_";
				$query_col1 .= "$sum";
				$query_col1 .= "_1 ";
				$query_col1 .= " WHERE num = $row[b1] ";

				#echo "$query_col1<br>";

				$mysqli_result_col1 = mysqli_query($mysqli_link, $query_col1) or die (mysqli_error($mysqli_link));

				$row_col1 = mysqli_fetch_array($mysqli_result_col1);

				$y1_sum += $row_col1[20];

				#echo "row_col1[20] = $row_col1[20]<br>";

				$query_col2 = "SELECT * FROM temp_column_sum";
				$query_col2 .= "_";
				$query_col2 .= "$sum";
				
				$query_col2 .= "_2 ";
				$query_col2 .= " WHERE num = $row[b2] ";

				#echo "$query_col2<br>";

				$mysqli_result_col2 = mysqli_query($mysqli_link, $query_col2) or die (mysqli_error($mysqli_link));

				$row_col2 = mysqli_fetch_array($mysqli_result_col2);

				$y1_sum += $row_col2[20];

				#$query5 = "INSERT INTO $table_temp3 (`id`, `b1`, `b2`, `count`, `y1_sum`) VALUES (0, '$row[b1]', '$row[b2]', '$row[2]', '$y1_sum'); ";

				$query5 = "INSERT INTO $table_temp3 (`id`, `b1`, `b2`, `count`, `y1_sum`) VALUES (0, '$row[b1]', '$row[b2]', '0', '$y1_sum'); ";
				
				#print "$query5<p>";
			
				$mysqli_result5 = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));
			}
		}

		print("<h3>Count - Col1/Col2 - SumEO = $sum</h3>\n");
		
		print("<TABLE BORDER=\"1\">\n");

		//create header row
		print("<TR><B>\n");

		print("<TD BGCOLOR=\"#CCCCCC\" align=\"center\">Col1</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Col2</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Count</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Y1_Sum</center></TD>\n");
		print("</B></TR>\n");
		
		$query = "SELECT * FROM $table_temp3 ";
		$query .= "ORDER BY b1 ASC, y1_sum DESC ";
		
		#echo "$query<p>";

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

		// get each row
		while($row = mysqli_fetch_array($mysqli_result))
		{
			print("<TR>\n");
			print("<TD><center>$row[b1]</center></TD>\n");
			print("<TD><center>$row[b2]</center></TD>\n");
			print("<TD align=center>$row[2]</TD>\n");
			print("<TD align=center>$y1_sum</TD>\n");
			print("</TR>\n");
		}
		
		print("<TR><B>\n");
		print("<TD BGCOLOR=\"#CCCCCC\" align=\"center\">Col1</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Col2</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Count</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Y1_Sum</center></TD>\n");
		print("</B></TR>\n");

		//end table
		print("</TABLE>\n");

		###########################################################################################

		print("<h3>Count - Col1/Col3 - SumEO = $sum</h3>\n");
		
		print("<TABLE BORDER=\"1\">\n");

		//create header row
		print("<TR><B>\n");

		print("<TD BGCOLOR=\"#CCCCCC\" align=\"center\">Col1</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Col3</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Count</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Y1_Sum</center></TD>\n");
		print("</B></TR>\n");

		$table_temp3 = 'temp_split_count_1_3_' . $sum;

		$query6 = "DROP TABLE IF EXISTS $table_temp3 ";

		#echo "$query6<br>";

		$mysqli_result = mysqli_query($mysqli_link, $query6) or die (mysqli_error($mysqli_link));

		$query7 = "CREATE TABLE `$table_temp3` (
			  `id` smallint NOT NULL AUTO_INCREMENT,
			  `b1` tinyint(2) NOT NULL,
			  `b3` tinyint(2) NOT NULL,
			  `count` int(5) NOT NULL,
			  `y1_sum` float(4,2) NOT NULL, 
			  PRIMARY KEY (`id`),
			  KEY `date` (`id`)
			) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;
		";

		#echo "$query7<br>";

		$mysqli_result7 = mysqli_query($mysqli_link, $query7) or die (mysqli_error($mysqli_link));

		$query8 = "SELECT b1, b3, count(*) FROM $table_temp2 ";
		$query8 .= "GROUP BY b1, b3 ";
		
		#echo "table_temp2 - $quer8<p>";

		$mysqli_result8 = mysqli_query($mysqli_link, $query8) or die (mysqli_error($mysqli_link));

		// get each row
		while($row = mysqli_fetch_array($mysqli_result8))
		{
			if (1) #25
			{
				$y1_sum = 0.00;
	
				$query_col1 = "SELECT * FROM temp_column_sum";
				$query_col1 .= "_";
				$query_col1 .= "$sum";
				$query_col1 .= "_1 ";
				$query_col1 .= " WHERE num = $row[b1] ";

				#echo "$query_col1<br>";

				$mysqli_result_col1 = mysqli_query($mysqli_link, $query_col1) or die (mysqli_error($mysqli_link));

				$row_col1 = mysqli_fetch_array($mysqli_result_col1);

				$y1_sum += $row_col1[20];

				#echo "row_col1[20] = $row_col1[20]<br>";

				$query_col3 = "SELECT * FROM temp_column_sum";
				$query_col3 .= "_";
				$query_col3 .= "$sum";
				$query_col3 .= "_3 ";
				$query_col3 .= " WHERE num = $row[b3] ";

				#echo "$query_col2<br>";

				$mysqli_result_col3 = mysqli_query($mysqli_link, $query_col3) or die (mysqli_error($mysqli_link));

				$row_col3 = mysqli_fetch_array($mysqli_result_col3);

				$y1_sum += $row_col3[20];

				$query5 = "INSERT INTO $table_temp3 (`id`, `b1`, `b3`, `count`, `y1_sum`) VALUES (0, '$row[b1]', '$row[b3]', '$row[2]', '$y1_sum'); ";
				
				#print "$query5<p>";
			
				$mysqli_result5 = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));
			}
		}

		$query = "SELECT * FROM $table_temp3 ";
		$query .= "ORDER BY b1 ASC, y1_sum DESC ";
		
		#echo "$query<p>";

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

		// get each row
		while($row = mysqli_fetch_array($mysqli_result))
		{
			print("<TR>\n");
			print("<TD><center>$row[b1]</center></TD>\n");
			print("<TD><center>$row[b3]</center></TD>\n");
			print("<TD align=center>---</TD>\n");
			print("<TD align=center>$y1_sum</TD>\n");
			print("</TR>\n");
		}
		
		print("<TR><B>\n");
		print("<TD BGCOLOR=\"#CCCCCC\" align=\"center\">Col1</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Col3</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Count</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Y1_Sum</center></TD>\n");
		print("</B></TR>\n");

		//end table
		print("</TABLE>\n");
		print "<br>";

		###########################################################################################

		print("<h3>Count - Col1/Col5 - SumEO = $sum</h3>\n");
		
		print("<TABLE BORDER=\"1\">\n");

		//create header row
		print("<TR><B>\n");

		print("<TD BGCOLOR=\"#CCCCCC\" align=\"center\">Col1</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Col5</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Count</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Y1_Sum</center></TD>\n");
		print("</B></TR>\n");

		$table_temp3 = 'temp_split_count_1_5_' . $sum;

		$query6 = "DROP TABLE IF EXISTS $table_temp3 ";

		#echo "$query6<br>";

		$mysqli_result = mysqli_query($mysqli_link, $query6) or die (mysqli_error($mysqli_link));

		$query7 = "CREATE TABLE `$table_temp3` (
			  `id` smallint NOT NULL AUTO_INCREMENT,
			  `b1` tinyint NOT NULL,
			  `b5` tinyint NOT NULL,
			  `count` mediumint NOT NULL,
			  `y1_sum` float(4,2) NOT NULL, 
			  PRIMARY KEY (`id`),
			  KEY `date` (`id`)
			) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;
		";

		$mysqli_result = mysqli_query($mysqli_link, $query7) or die (mysqli_error($mysqli_link));

		$query = "SELECT b1, b5, count(*) FROM $table_temp2 ";
		$query .= "GROUP BY b1, b5 ";
		
		#echo "$query<p>";

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

		// get each row
		while($row = mysqli_fetch_array($mysqli_result))
		{
			if (1) #25
			{
				$y1_sum = 0.00;
	
				$query_col1 = "SELECT * FROM temp_column_sum";
				$query_col1 .= "_";
				$query_col1 .= "$sum";
				$query_col1 .= "_1 ";
				$query_col1 .= " WHERE num = $row[b1] ";

				#echo "$query_col1<br>";

				$mysqli_result_col1 = mysqli_query($mysqli_link, $query_col1) or die (mysqli_error($mysqli_link));

				$row_col1 = mysqli_fetch_array($mysqli_result_col1);

				$y1_sum += $row_col1[20];

				#echo "row_col1[20] = $row_col1[20]<br>";

				$query_col5 = "SELECT * FROM temp_column_sum";
				$query_col5 .= "_";
				$query_col5 .= "$sum";
				$query_col5 .= "_5 ";
				$query_col5 .= " WHERE num = $row[b5] ";

				#echo "$query_col5<br>";

				$mysqli_result_col5 = mysqli_query($mysqli_link, $query_col5) or die (mysqli_error($mysqli_link));

				$row_col5 = mysqli_fetch_array($mysqli_result_col5);

				$y1_sum += $row_col5[20];

				print("<TR>\n");
				print("<TD><center>$row[b1]</center></TD>\n");
				print("<TD><center>$row[b5]</center></TD>\n");
				print("<TD align=center>---</TD>\n");
				print("<TD align=center><font size=\"-1\">$y1_sum</font></TD>\n");
				print("</TR>\n");

				$query5 = "INSERT INTO $table_temp3 (`id`, `b1`, `b5`, `count`, `y1_sum`) VALUES (0, '$row[b1]', '$row[b5]', '$row[2]', '$y1_sum'); ";
				
				#print "$query5<p>";
			
				$mysqli_result5 = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));
			}
		}
		
		/*
		$query = "SELECT * FROM $table_temp3 ";
		$query .= "ORDER BY b1 ASC, y1_sum DESC ";
		
		#echo "$query<p>";

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

		// get each row
		while($row = mysqli_fetch_array($mysqli_result))
		{
			print("<TR>\n");
			print("<TD><center>$row[b1]</center></TD>\n");
			print("<TD><center>$row[b2]</center></TD>\n");
			print("<TD align=center>$row[2]</TD>\n");
			print("<TD align=center>$y1_sum</TD>\n");
			print("</TR>\n");
		}
		*/
		
		print("<TR><B>\n");
		print("<TD BGCOLOR=\"#CCCCCC\" align=\"center\">Col1</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Col5</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Count</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Y1_Sum</center></TD>\n");
		print("</B></TR>\n");

		//end table
		print("</TABLE>\n");
		print "<br>";

		###########################################################################################

		print("<h3>Count - Col1/Col3/Col5 - SumEO = $sum</h3>\n");
		
		print("<TABLE BORDER=\"1\">\n");

		//create header row
		print("<TR><B>\n");
		print("<TD BGCOLOR=\"#CCCCCC\" align=\"center\">Col1</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Col3</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Col5</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Count</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Y1_Sum</center></TD>\n");
		print("</B></TR>\n");

		$table_temp3 = 'temp_split_count_1_3_5_' . $sum . '_' . $even . '_' . $odd;

		$query6 = "DROP TABLE IF EXISTS $table_temp3 ";

		#echo "$query6<br>";

		$mysqli_result = mysqli_query($mysqli_link, $query6) or die (mysqli_error($mysqli_link));

		$query7 = "CREATE TABLE `$table_temp3` (
			  `id` smallint NOT NULL AUTO_INCREMENT,
			  `b1` tinyint NOT NULL,
			  `b3` tinyint NOT NULL,
			  `b5` tinyint NOT NULL,
			  `count` mediumint NOT NULL,
			  `y1_sum` float(4,2) NOT NULL, 
			  PRIMARY KEY (`id`),
			  KEY `date` (`id`)
			) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;
		";

		#echo "$query7<p>";

		$mysqli_result7 = mysqli_query($mysqli_link, $query7) or die (mysqli_error($mysqli_link));

		$query8 = "SELECT b1, b3, b5, count(*) FROM $table_temp2 ";
		$query8 .= "GROUP BY b1, b3, b5 ";
		
		#echo "table_temp2 - $query8<p>";

		$mysqli_result8 = mysqli_query($mysqli_link, $query8) or die (mysqli_error($mysqli_link));

		// get each row
		while($row = mysqli_fetch_array($mysqli_result8))
		{
			if ($row[3] > 0) 
			#if (1) 
			{
				$y1_sum = 0.00;
	
				$query_col1 = "SELECT * FROM temp_column_sum";
				$query_col1 .= "_";
				$query_col1 .= "$sum";
				$query_col1 .= "_1 ";
				$query_col1 .= " WHERE num = $row[b1] ";

				#echo "$query_col1<br>";

				$mysqli_result_col1 = mysqli_query($mysqli_link, $query_col1) or die (mysqli_error($mysqli_link));

				$row_col1 = mysqli_fetch_array($mysqli_result_col1);

				$y1_sum += $row_col1[20];

				#echo "row_col1[20] = $row_col1[20]<br>";

				$query_col3 = "SELECT * FROM temp_column_sum";
				$query_col3 .= "_";
				$query_col3 .= "$sum";
				$query_col3 .= "_3 ";
				$query_col3 .= " WHERE num = $row[b3] ";

				#echo "$query_col3<br>";

				$mysqli_result_col3 = mysqli_query($mysqli_link, $query_col3) or die (mysqli_error($mysqli_link));

				$row_col3 = mysqli_fetch_array($mysqli_result_col3);

				$y1_sum += $row_col3[20];

				$query_col5 = "SELECT * FROM temp_column_sum";
				$query_col5 .= "_";
				$query_col5 .= "$sum";
				$query_col5 .= "_5 ";
				$query_col5 .= " WHERE num = $row[b5] ";

				#echo "$query_col5<br>";

				$mysqli_result_col5 = mysqli_query($mysqli_link, $query_col5) or die (mysqli_error($mysqli_link));

				$row_col5 = mysqli_fetch_array($mysqli_result_col5);

				$y1_sum += $row_col5[20];

				$query5 = "INSERT INTO $table_temp3 (`id`, `b1`, `b3`,`b5`, `count`, `y1_sum`) VALUES (0, '$row[b1]', '$row[b3]', '$row[b5]','$row[2]', '$y1_sum'); ";
				
				#print "$query5<p>";
			
				$mysqli_result5 = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));
			}
		}

		$rcount = 0;
		
		$query = "SELECT * FROM $table_temp3 ";
		$query .= "ORDER BY b1 ASC, y1_sum DESC ";
		
		#echo "$query<p>";

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

		// get each row
		while($row = mysqli_fetch_array($mysqli_result))
		{
			print("<TR>\n");
			print("<TD><center>$row[b1]</center></TD>\n");
			print("<TD><center>$row[b3]</center></TD>\n");
			print("<TD><center>$row[b5]</center></TD>\n");
			print("<TD align=center>---</TD>\n");
			print("<TD align=center>$row[y1_sum]</TD>\n");
			print("</TR>\n");

			if ($rcount > 20)
			{
				print("<TR><B>\n");
				print("<TD BGCOLOR=\"#CCCCCC\" align=\"center\">Col1</TD>\n");
				print("<TD BGCOLOR=\"#CCCCCC\"><center>Col3</center></TD>\n");
				print("<TD BGCOLOR=\"#CCCCCC\"><center>Col5</center></TD>\n");
				print("<TD BGCOLOR=\"#CCCCCC\"><center>Count</center></TD>\n");
				print("<TD BGCOLOR=\"#CCCCCC\"><center>Y1_Sum</center></TD>\n");
				print("</B></TR>\n");

				$rcount = 0;
			} else {
				$rcount++;
			}
		}
		
		print("<TR><B>\n");
		print("<TD BGCOLOR=\"#CCCCCC\" align=\"center\">Col1</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Col3</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Col5</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Count</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Y1_Sum</center></TD>\n");
		print("</B></TR>\n");

		//end table
		print("</TABLE>\n");
		print "<br>";
		
		###########################################################################################

		# copy current table into dated table
		#$curr_date = date("ymd");

		$table_temp_date = $table_temp2 . "_" . $dateDiff;

		$query = "DROP TABLE IF EXISTS $table_temp_date";

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

		$query_copy = "CREATE TABLE $table_temp_date SELECT * FROM $table_temp2";

		#echo "$query_copy<br>";

		$mysqli_result = mysqli_query($mysqli_link, $query_copy) or die (mysqli_error($mysqli_link));
	}
?>