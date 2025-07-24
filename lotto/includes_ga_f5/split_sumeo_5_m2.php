<?php
	// ----------------------------------------------------------------------------------
	function split_sumeo_5($sum,$even,$odd)
	{
		global $debug, $draw_table_name, $balls, $balls_drawn, $draw_prefix, $game, $hml; 

		require ("includes/mysqli.php"); 

		##################### LIMIT ###################################################################

		$limit_temp = array_fill(0,2,0);
		$limit_array = array_fill(0,5,$limit_temp);
		
		#$query_limit = "SELECT * FROM ga_f5_limits_by_sumeo WHERE sum = $sum AND even = $even AND odd = $odd ORDER BY col ASC ";
		$query_limit = "SELECT * FROM ga_f5_limits_by_sumeo WHERE sum = $sum AND even = $even AND odd = $odd ORDER BY col ASC ";

		echo "<b>$query_limit</b><br>";

		$mysqli_result = mysqli_query($mysqli_link, $query_limit) or die (mysqli_error($mysqli_link));

		while($row_limit = mysqli_fetch_array($mysqli_result))
		{
			$col_temp = $row_limit[col];

			$limit_array[$col_temp][0] = $row_limit[low];
			$limit_array[$col_temp][1] = $row_limit[high];
		} 

		###############################################################################################

		$table_temp = 'combo_5_' . $balls_drawn . '_' . $balls . '_' . $sum . '_' . $even . '_' . $odd;

		$query = "DROP TABLE IF EXISTS $table_temp";

		#echo "$query<br>";

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

		$query_copy = "CREATE TABLE $table_temp SELECT * FROM combo_";
		$query_copy .= "$balls_drawn";
		$query_copy .= "_$balls ";
		$query_copy .= "WHERE sum  = $sum ";
		$query_copy .= "AND   b1   >= {$limit_array[1][0]} "; #200203
		$query_copy .= "AND   b1   <= {$limit_array[1][1]} "; #200203
		$query_copy .= "AND   b2   >= {$limit_array[2][0]} "; #200203
		$query_copy .= "AND   b2   <= {$limit_array[2][1]} "; #200203
		$query_copy .= "AND   b3   >= {$limit_array[3][0]} "; #200203
		$query_copy .= "AND   b3   <= {$limit_array[3][1]} "; #200203
		$query_copy .= "AND   b4   >= {$limit_array[4][0]} "; #200203
		$query_copy .= "AND   b4   <= {$limit_array[4][1]} "; #200203
		$query_copy .= "AND   b5   >= {$limit_array[5][0]} "; #200203
		$query_copy .= "AND   b5   <= {$limit_array[5][1]} "; #200203
		$query_copy .= "AND   even = $even ";
		$query_copy .= "AND   odd  = $odd ";
		$query_copy .= "AND   seq2  <= 1 ";
		$query_copy .= "AND   seq3  = 0 ";
		$query_copy .= "AND   mod_tot  <= 1 ";
		$query_copy .= "AND   mod_x  = 0 ";

		echo "<b>m2</b> - $query_copy<br>";

		$mysqli_result = mysqli_query($mysqli_link, $query_copy) or die (mysqli_error($mysqli_link));

		$query5 = "SELECT count(*) FROM $table_temp ";

		echo "<b>m2</b> - $query5<br>";

		$mysqli_result = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));

		$cnt = mysqli_fetch_array($mysqli_result);

		#echo "cnt = ";
		#print_r($cnt);

		echo "<b>m2</b> - num_rows copied = $cnt[0]<p>";

		#print("<h2>Numbers Sorted - Limit $limit</h2>\n");
		#print("<P>");
		print("<TABLE BORDER=\"1\">\n");

		//create header row
		print("<TR><B>\n");

		print("<TD BGCOLOR=\"#CCCCCC\" align=\"center\">Col1</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Col5</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Count</center></TD>\n");
		print("</B></TR>\n");

		############################################################################################
		$query = "SELECT b1, b5, count(*) FROM $table_temp ";
		$query .= "GROUP BY b1, b5 ";
		
		echo "<b>m2</b> - $query<p>";

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

		// get each row
		while($row = mysqli_fetch_array($mysqli_result))
		{
			print("<TR>\n");
			print("<TD><center>$row[b1]</center></TD>\n");
			print("<TD><center>$row[b5]</center></TD>\n");
			print("<TD align=center>$row[2]</TD>\n");
			print("</TR>\n");
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