<?php
	// ----------------------------------------------------------------------------------
	function split_sumeo_5($sum,$even,$odd)
	{
		global $debug, $draw_table_name, $balls, $balls_drawn, $draw_prefix, $game, $hml, $dateDiff; 

		require ("includes/mysqli.php"); 

		$curr_date = date('Y-m-d');

		###############################################################################################

		$table_temp1 = 'temp2_filter_' . $balls_drawn . '_' . $balls . '_' . $sum . '_' . $even . '_' . $odd;

		$query = "DROP TABLE IF EXISTS $table_temp1 ";

		echo "$query<br>";

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

		/*
		echo "low = <br>";
		print_r ($low);
		echo "<br>";

		echo "high = <br>";
		print_r ($high);
		echo "<br>";
		*/

		$query_copy = "CREATE TABLE $table_temp1 SELECT * FROM combo_";
		$query_copy .= "$balls_drawn";
		$query_copy .= "_$balls ";
		$query_copy .= "WHERE sum  = $sum ";
		$query_copy .= "AND   even = $even ";
		$query_copy .= "AND   odd  = $odd ";

		echo "no filter - $query_copy<br>";

		$mysqli_result = mysqli_query($mysqli_link, $query_copy) or die (mysqli_error($mysqli_link));

		$query5 = "SELECT count(*) FROM $table_temp1 ";

		echo "$query5<br>";

		$mysqli_result = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));

		$cnt = mysqli_fetch_array($mysqli_result);

		echo "no filter - num_rows copied = $cnt[0]<p>";

		##################### LIMIT ###################################################################

		$limit_temp = array_fill(0,2,0);
		$limit_array = array_fill(0,5,$limit_temp);

		$date_temp2 = explode('-',$curr_date);

		#$sumeo_table = "ga_f5_limits_by_sumeo_20" . $date_temp2[1] . ($date_temp2[2]-1);
		$sumeo_table = "ga_f5_limits_by_sumeo";

		#$sumeo_table = "ga_f5_limits_by_sumeo"; #201011
		
		/*	### 240601
		$query_limit = "SELECT * FROM $sumeo_table WHERE sum = $sum AND even = $even AND odd = $odd ORDER BY col ASC ";

		echo "<b>$query_limit</b><br>";

		$mysqli_result = mysqli_query($mysqli_link, $query_limit) or die (mysqli_error($mysqli_link));

		while($row_limit = mysqli_fetch_array($mysqli_result))
		{
			$col_temp = $row_limit[2];

			$limit_array[$col_temp][0] = $row_limit[5];
			$limit_array[$col_temp][1] = $row_limit[6];
		} 

		echo "limit_array = <br>";
		#print_r ($limit_array);
		echo "<br>";
		*/

		###############################################################################################
		/*	### 240602
		$table_temp1 = 'temp2_filter_' . $balls_drawn . '_'. $balls . '_' . $sum . '_' . $even . '_' . $odd . '_nofilter';

		$query = "DROP TABLE IF EXISTS $table_temp1 ";

		echo "$query<br>";

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

		$low = array_fill(0,6,0);
		$high = array_fill(0,6,0);

		for ($y = 1; $y <=5; $y++)
		{
			$low[$y] = $limit_array[$y][0] - 2;
			$high[$y] = $limit_array[$y][1] + 2;
		}

			### 240601
		$query_copy = "CREATE TABLE $table_temp1 SELECT * FROM combo_";
		$query_copy .= "$balls_drawn";
		$query_copy .= "_$balls ";
		$query_copy .= "WHERE sum  = $sum ";
		$query_copy .= "AND   b1   >= 1 "; 
		$query_copy .= "AND   b1   <= $high[1] "; 
		$query_copy .= "AND   b2   >= $low[2] "; 
		$query_copy .= "AND   b2   <= $high[2] "; 
		$query_copy .= "AND   b3   >= $low[3] "; 
		$query_copy .= "AND   b3   <= $high[3] "; 
		$query_copy .= "AND   b4   >= $low[4] "; 
		$query_copy .= "AND   b4   <= $high[4] "; 
		$query_copy .= "AND   b5   >= $low[5] "; 
		$query_copy .= "AND   b5   <= 42 "; 
		$query_copy .= "AND   even = $even ";
		$query_copy .= "AND   odd  = $odd ";
		$query_copy .= "AND   seq2  <= 1 ";
		$query_copy .= "AND   seq3  = 0 ";
		$query_copy .= "AND   mod_tot  <= 1 ";

		#cho "filter by range - $query_copy<br>";

		$mysqli_result = mysqli_query($mysqli_link, $query_copy) or die (mysqli_error($mysqli_link));

		$query5 = "SELECT count(*) FROM $table_temp1 ";

		echo "$query5<br>";

		$mysqli_result = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));

		$cnt = mysqli_fetch_array($mysqli_result);

		echo "num_rows copied = $cnt[0]<p>";
		*/

		##################### sumeo5 ########################################################################

		$table_temp2 = 'temp2_5_42_' . $sum . '_' . $even . '_' . $odd;

		$query = "DROP TABLE IF EXISTS $table_temp2 ";

		echo "$query<br>";

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

		$query3 = "CREATE TABLE $table_temp2 LIKE $table_temp1 ";

		echo "$query3<br>";

		$mysqli_result = mysqli_query($mysqli_link, $query3) or die (mysqli_error($mysqli_link));

		#--------------------------------------------------------- each ga_f5_sumeo_drange4 ### 240705
		$query_drange4 = "SELECT * FROM `ga_f5_sumeo_drange4` WHERE sum = $sum AND even = $even AND odd = $odd AND percent_wa > 0.010 ORDER BY `ga_f5_sumeo_drange4`.`percent_wa` DESC ";

		echo "<b>query_drange4 - $query_drange4</b><br>";

		$mysqli_result_drange4 = mysqli_query($mysqli_link, $query_drange4) or die (mysqli_error($mysqli_link));

		while ($row_drange4 = mysqli_fetch_array($mysqli_result_drange4))
		{
			$query_sumeo5 = "SELECT * FROM combo_5_42_drange4 a ";
			$query_sumeo5 .= "JOIN $table_temp1 b ";
			$query_sumeo5 .= "ON  a.combo_id = b.id ";
			$query_sumeo5 .= "WHERE a.d4_1 = $row_drange4[d4_1] ";
			$query_sumeo5 .= "AND   a.d4_2 = $row_drange4[d4_2] ";
			$query_sumeo5 .= "AND   a.d4_3 = $row_drange4[d4_3] ";
			$query_sumeo5 .= "AND   a.d4_4 = $row_drange4[d4_4] ";

			echo "<b>$query_sumeo5</b><br>";

			$mysqli_result_sumeo5 = mysqli_query($mysqli_link, $query_sumeo5) or die (mysqli_error($mysqli_link));

			$num_rows_sumeo5 = mysqli_num_rows($mysqli_result_sumeo5);

			echo "num_rows_sumeo5 = $num_rows_sumeo5<p>";

			$count = 1;

			while ($row_sumeo5 = mysqli_fetch_array($mysqli_result_sumeo5))
			{
				$query5 = "INSERT INTO $table_temp2 ";
				$query5 .= "SELECT * FROM $table_temp1 ";
				$query5 .= "WHERE id = $row_sumeo5[id] ";
				
				if ($count == 300)
				{
					echo "$query5<p>";
					$count = 0;
				} else {
					$count++;
				}
			
				$mysqli_result5 = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));
			}
		}

		$query5 = "SELECT count(*) FROM $table_temp2 ";

		echo "$query5<br>";

		$mysqli_result = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));

		$cnt = mysqli_fetch_array($mysqli_result);

		echo "num_rows copied = $cnt[0]<p>";

		###############################################################################################################

		for ($a = 1; $a <= 4; $a++)
		{
			for ($b = $a+1; $b <= 5; $b++)
			{
				#$table_temp3 = 'temp2_split_count_1_2_' . $sum . '_' . $even . '_' . $odd;

				$table_temp3 = 'temp2_split_count_' . $a . '_' . $b . '_' . $sum . '_' . $even . '_' . $odd;

				$query6 = "DROP TABLE IF EXISTS $table_temp3 ";

				echo "$query6<br>";

				$mysqli_result = mysqli_query($mysqli_link, $query6) or die (mysqli_error($mysqli_link));

				$query7 = "CREATE TABLE `$table_temp3` (
					  `id` smallint NOT NULL AUTO_INCREMENT,
					  `b1` tinyint NOT NULL,
					  `b2` tinyint NOT NULL,
					  `count` mediumint NOT NULL,
					  `y1_sum` float(5,3) NOT NULL, 
					  PRIMARY KEY (`id`),
					  KEY `date` (`id`)
					) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;
				";

				echo "$query7<br>";

				$mysqli_result = mysqli_query($mysqli_link, $query7) or die (mysqli_error($mysqli_link));

				$b1 = 'b' . $a;
				$b2 = 'b' . $b;

				$query = "SELECT $b1, $b2, count(*) FROM $table_temp2 ";
				$query .= "GROUP BY $b1, $b2 ";
				
				echo "$query<p>";

				$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

				// get each row
				while($row = mysqli_fetch_array($mysqli_result))
				{
					if (1) #25
					{
						$y1_sum = 0.000;
			
						$query_col1 = "SELECT * FROM temp2_column_sumeo";
						$query_col1 .= "_";
						$query_col1 .= "$sum";
						$query_col1 .= "_";
						$query_col1 .= "$even";
						$query_col1 .= "_";
						$query_col1 .= "$odd";
						$query_col1 .= "_";
						$query_col1 .= "$a ";
						$query_col1 .= " WHERE num = $row[0] ";

						echo "$query_col1<br>";

						$mysqli_result_col1 = mysqli_query($mysqli_link, $query_col1) or die (mysqli_error($mysqli_link));

						$row_col1 = mysqli_fetch_array($mysqli_result_col1);

						$y1_sum += $row_col1[20];

						echo "row_col1[20] = $row_col1[20]<br>";

						$query_col2 = "SELECT * FROM temp2_column_sumeo";
						$query_col2 .= "_";
						$query_col2 .= "$sum";
						$query_col2 .= "_";
						$query_col2 .= "$even";
						$query_col2 .= "_";
						$query_col2 .= "$odd";
						$query_col2 .= "_";
						$query_col2 .= "$b ";
						$query_col2 .= " WHERE num = $row[1] ";

						echo "$query_col2<br>";

						$mysqli_result_col2 = mysqli_query($mysqli_link, $query_col2) or die (mysqli_error($mysqli_link));

						$row_col2 = mysqli_fetch_array($mysqli_result_col2);

						$y1_sum += $row_col2[20];

						$query5 = "INSERT INTO $table_temp3 (`id`, `b1`, `b2`, `count`, `y1_sum`) VALUES (0, '$row[0]', '$row[1]', '$row[2]', '$y1_sum'); ";
						
						echo "$query5<p>";
					
						$mysqli_result5 = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));
					}
				}

				print("<h3>Count - Col$a/Col$b - SumEO = $sum,$even,$odd</h3>\n");
				
				print("<TABLE BORDER=\"1\">\n");

				//create header row
				print("<TR><B>\n");
				print("<TD BGCOLOR=\"#CCCCCC\" align=\"center\">Col$a</TD>\n");
				print("<TD BGCOLOR=\"#CCCCCC\"><center>Col$b</center></TD>\n");
				print("<TD BGCOLOR=\"#CCCCCC\"><center>Count</center></TD>\n");
				print("<TD BGCOLOR=\"#CCCCCC\"><center>Y1_Sum</center></TD>\n");
				print("</B></TR>\n");
				
				$query = "SELECT * FROM $table_temp3 ";
				$query .= "WHERE y1_sum > 0.00 ";
				#$query .= "ORDER BY b1 ASC, y1_sum DESC ";
				$query .= "ORDER BY y1_sum DESC, count DESC ";
				
				echo "$query<p>";

				$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

				// get each row
				while($row = mysqli_fetch_array($mysqli_result))
				{
					print("<TR>\n");
					print("<TD><center>$row[b1]</center></TD>\n");
					print("<TD><center>$row[b2]</center></TD>\n");
					print("<TD align=center>$row[count]</TD>\n");
					print("<TD align=center><font size=\"-1\">$row[y1_sum]</font></TD>\n");
					print("</TR>\n");
				}
				
				print("<TR><B>\n");
				print("<TD BGCOLOR=\"#CCCCCC\" align=\"center\">Col$a</TD>\n");
				print("<TD BGCOLOR=\"#CCCCCC\"><center>Col$b</center></TD>\n");
				print("<TD BGCOLOR=\"#CCCCCC\"><center>Count</center></TD>\n");
				print("<TD BGCOLOR=\"#CCCCCC\"><center>Y1_Sum</center></TD>\n");
				print("</B></TR>\n");

				//end table
				print("</TABLE>\n");
			}
		}

		###############################################################################################################

		for ($a = 1; $a <= 3; $a++)
		{
			for ($b = $a+1; $b <= 4; $b++)
			{
					for ($c = $b+1; $c <= 5; $c++)
					{
						$table_temp4 = 'temp2_split_count_' . $a . '_' . $b . '_' . $c . '_' . $sum . '_' . $even . '_' .	 
							$odd;

						$query6 = "DROP TABLE IF EXISTS $table_temp4 ";

						echo "$query6<br>";

						$mysqli_result = mysqli_query($mysqli_link, $query6) or die (mysqli_error($mysqli_link));

						$query7 = "CREATE TABLE `$table_temp4` (
							  `id` smallint NOT NULL AUTO_INCREMENT,
							  `b1` tinyint NOT NULL,
							  `b2` tinyint NOT NULL,
							  `b3` tinyint NOT NULL,
							  `count` mediumint NOT NULL,
							  `y1_sum` float(5,3) NOT NULL, 
							  PRIMARY KEY (`id`),
							  KEY `date` (`id`)
							) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;
						";

						echo "$query7<br>";

						$mysqli_result = mysqli_query($mysqli_link, $query7) or die (mysqli_error($mysqli_link));

						$b1 = 'b' . $a;
						$b2 = 'b' . $b;
						$b3 = 'b' . $c;

						$query = "SELECT $b1, $b2, $b3, count(*) FROM $table_temp2 ";
						$query .= "GROUP BY $b1, $b2, $b3 ";
						
						echo "$query<p>";

						$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

						// get each row
						while($row = mysqli_fetch_array($mysqli_result))
						{
							if (1) #25
							{
								$y1_sum = 0.000;
					
								$query_col1 = "SELECT * FROM temp2_column_sumeo";
								$query_col1 .= "_";
								$query_col1 .= "$sum";
								$query_col1 .= "_";
								$query_col1 .= "$even";
								$query_col1 .= "_";
								$query_col1 .= "$odd";
								$query_col1 .= "_";
								$query_col1 .= "$a ";
								$query_col1 .= " WHERE num = $row[0] ";

								echo "$query_col1<br>";

								$mysqli_result_col1 = mysqli_query($mysqli_link, $query_col1) or die			  
									(mysqli_error($mysqli_link));

								$row_col1 = mysqli_fetch_array($mysqli_result_col1);

								$y1_sum += $row_col1[20];

								$query_col2 = "SELECT * FROM temp2_column_sumeo";
								$query_col2 .= "_";
								$query_col2 .= "$sum";
								$query_col2 .= "_";
								$query_col2 .= "$even";
								$query_col2 .= "_";
								$query_col2 .= "$odd";
								$query_col2 .= "_";
								$query_col2 .= "$b ";
								$query_col2 .= " WHERE num = $row[1] ";

								echo "$query_col2<br>";

								$mysqli_result_col2 = mysqli_query($mysqli_link, $query_col2) or die 
									(mysqli_error($mysqli_link));

								$row_col2 = mysqli_fetch_array($mysqli_result_col2);

								$y1_sum += $row_col2[20];

								$query_col3 = "SELECT * FROM temp2_column_sumeo";
								$query_col3 .= "_";
								$query_col3 .= "$sum";
								$query_col3 .= "_";
								$query_col3 .= "$even";
								$query_col3 .= "_";
								$query_col3 .= "$odd";
								$query_col3 .= "_";
								$query_col3 .= "$c ";
								$query_col3 .= " WHERE num = $row[2] ";

								echo "$query_col2<br>";

								$mysqli_result_col3 = mysqli_query($mysqli_link, $query_col3) or die        
									(mysqli_error($mysqli_link));

								$row_col3 = mysqli_fetch_array($mysqli_result_col3);

								$y1_sum += $row_col3[20];

								$query5 = "INSERT INTO $table_temp4 (`id`, `b1`, `b2`, `b3`, `count`, `y1_sum`) VALUES (0, '$row[0]', '$row[1]', '$row[2]', '$row[3]', '$y1_sum'); ";
								
								echo "$query5<p>";
							
								$mysqli_result5 = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));
							}
						}

						print("<h3>Count - Col$a/Col$b/Col$c - SumEO = $sum,$even,$odd</h3>\n");
						
						print("<TABLE BORDER=\"1\">\n");

						//create header row
						print("<TR><B>\n");
						print("<TD BGCOLOR=\"#CCCCCC\" align=\"center\">Col$a</TD>\n");
						print("<TD BGCOLOR=\"#CCCCCC\"><center>Col$b</center></TD>\n");
						print("<TD BGCOLOR=\"#CCCCCC\"><center>Col$c</center></TD>\n");
						print("<TD BGCOLOR=\"#CCCCCC\"><center>Count</center></TD>\n");
						print("<TD BGCOLOR=\"#CCCCCC\"><center>Y1_Sum</center></TD>\n");
						print("</B></TR>\n");
						
						$query = "SELECT * FROM $table_temp4 ";
						$query .= "WHERE y1_sum > 0.00 ";
						$query .= "ORDER BY y1_sum DESC, count DESC, b1 ASC ";
						
						echo "$query<p>";

						$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

						// get each row
						while($row = mysqli_fetch_array($mysqli_result))
						{
							print("<TR>\n");
							print("<TD><center>$row[b1]</center></TD>\n");
							print("<TD><center>$row[b2]</center></TD>\n");
							print("<TD><center>$row[b3]</center></TD>\n");
							print("<TD align=center>$row[count]</TD>\n");
							print("<TD align=center><font size=\"-1\">$row[y1_sum]</font></TD>\n");
							print("</TR>\n");
						}
						
						print("<TR><B>\n");
						print("<TD BGCOLOR=\"#CCCCCC\" align=\"center\">Col$a</TD>\n");
						print("<TD BGCOLOR=\"#CCCCCC\"><center>Col$b</center></TD>\n");
						print("<TD BGCOLOR=\"#CCCCCC\"><center>Col$c</center></TD>\n");
						print("<TD BGCOLOR=\"#CCCCCC\"><center>Count</center></TD>\n");
						print("<TD BGCOLOR=\"#CCCCCC\"><center>Y1_Sum</center></TD>\n");
						print("</B></TR>\n");

						//end table
						print("</TABLE>\n");
					}
			}
		}
		
		###########################################################################################

		# copy current table into dated table
		#$curr_date = date("ymd");

		$table_temp2_date = $table_temp2 . "_" . $dateDiff;

		$query = "DROP TABLE IF EXISTS $table_temp2_date";

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

		$query_copy = "CREATE TABLE $table_temp2_date SELECT * FROM $table_temp2";

		echo "$query_copy<br>";

		$mysqli_result = mysqli_query($mysqli_link, $query_copy) or die (mysqli_error($mysqli_link));

		$table_temp2_date = $table_temp3 . "_" . $dateDiff;

		$query = "DROP TABLE IF EXISTS $table_temp2_date";

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

		$query_copy = "CREATE TABLE $table_temp2_date SELECT * FROM $table_temp2";

		echo "$query_copy<br>";

		$mysqli_result = mysqli_query($mysqli_link, $query_copy) or die (mysqli_error($mysqli_link));

		$table_temp2_date = $table_temp4 . "_" . $dateDiff;

		$query = "DROP TABLE IF EXISTS $table_temp2_date";

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

		$query_copy = "CREATE TABLE $table_temp2_date SELECT * FROM $table_temp2";

		echo "$query_copy<br>";

		$mysqli_result = mysqli_query($mysqli_link, $query_copy) or die (mysqli_error($mysqli_link));
	}
?>