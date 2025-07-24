<?php

	// ----------------------------------------------------------------------------------
	function print_column_test_pairs($col, $sum, $even, $odd)
	{
		global $debug, $draw_table_name, $balls, $balls_drawn, $draw_prefix, $game, $hml, $range_low, $range_high,			$col1_select; 

		require ("includes/mysqli.php"); 
		require ("includes/unix.incl"); 

		$z = 0;

		#echo "### print_column_pairs_test_sumeo inside ###<br>";

		$query4 = "DROP TABLE IF EXISTS temp_";
		$query4 .= "column_pairs_";
		$query4 .= "$sum";
		$query4 .= "_$even";
		$query4 .= "_$odd";
		$query4 .= "_$col ";

		#echo "$query4<p>";

		$mysqli_result4 = mysqli_query($mysqli_link, $query4) or die (mysqli_error($mysqli_link));

		$query4 = "CREATE TABLE temp_";
		$query4 .= "column_pairs_";
		$query4 .= "$sum";
		$query4 .= "_$even";
		$query4 .= "_$odd";
		$query4 .= "_$col ( ";
		$query4 .= "num1 int(5) unsigned NOT NULL,";
		$query4 .= "num2 int(5) unsigned NOT NULL, ";
		$query4 .= "nums_total_2 int(5) unsigned NOT NULL, ";
		$query4 .= "combo_total_2 int(5) unsigned NOT NULL, ";
		$query4 .= "nums_total_3 int(5) unsigned NOT NULL, ";
		$query4 .= "combo_total_3 int(5) unsigned NOT NULL, ";
		$query4 .= "nums_total_4 int(5) unsigned NOT NULL, ";
		$query4 .= "combo_total_4 int(5) unsigned NOT NULL";
		#$query4 .= "UNIQUE KEY `num1_2` (`num1`),";
		#$query4 .= "KEY `num` (`num1`)";
		$query4 .= ") ENGINE=InnoDB DEFAULT CHARSET=latin1;";

		print "$query4<p>";
	
		$mysqli_result4 = mysqli_query($mysqli_link, $query4) or die (mysqli_error($mysqli_link));

		$hml_temp = $sum + 500;

		$query = "SELECT * FROM ga_f5_2_42 a ";
		$query .= "JOIN ga_f5_draws b ";
		$query .= "WHERE a.date = b.date ";
		$query .= "AND a.date >= '2015-10-01' ";
		$query .= "AND a.combo = '$col' ";
		$query .= "AND a.hml = '$hml_temp' ";
		#$query .= "AND b.sum = $sum "; 
		$query .= "AND b.even = $even ";
		$query .= "AND b.odd = $odd ";
		$query .= "ORDER BY a.d1 ASC, a.d2 ASC ";

		print "$query<p>";

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

		$num_rows_all = mysqli_num_rows($mysqli_result);

		//start table
		print("<h3>Combo $col $sum-$even-$odd</h3>\n");
		print("<TABLE BORDER=\"1\">\n");

		//create header row
		print("<TR>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Num1</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Num2</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Nums_Total_2</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Combo_Totals_2</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Nums_Total_3</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Combo_Totals_3</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Nums_Total_4</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Combo_Totals_4</TD>\n");
		print("</TR>\n");

		$draw = 1;

		$temp_array = array_fill (0,17,0);
		$temp_array_aon = array_fill (0,17,0);
		$column_pairs_count_array = array_fill (0,$balls+1,$temp_array);
		$column_pairs_count_array_aon = array_fill (0,$balls+1,$temp_array);
		$week_count_array = array_fill (0,28,$temp_array);

		while($row = mysqli_fetch_array($mysqli_result))
		{
			print("<TR>\n");
			print("<TD align=center>$row[2]</TD>\n");
			print("<TD align=center>$row[3]</TD>\n");
			print("<TD align=center>$row[46]</TD>\n");
			print("<TD align=center>$row[47]</TD>\n");
			print("<TD align=center>$row[48]</TD>\n");
			print("<TD align=center>$row[49]</TD>\n");
			print("<TD align=center>$row[50]</TD>\n");
			print("<TD align=center>$row[51]</TD>\n");
			print("</TR>\n");

			$query5 = "INSERT INTO temp_";
			$query5 .= "column_pairs_";
			$query5 .= "$sum";
			$query5 .= "_$even";
			$query5 .= "_$odd";
			$query5 .= "_$col ";
			$query5 .= "VALUES ('$row[2]', '$row[3]', ";
			$query5 .= "'$row[46]', '$row[47]', ";
			$query5 .= "'$row[48]', '$row[49]', ";
			$query5 .= "'$row[50]', '$row[51]') ";
			
			#print "$query5<p>";
		
			$mysqli_result5 = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));
		}

		print("<TR>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Num1</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Num2</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Nums_Total_2</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Combo_Totals_2</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Nums_Total_3</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Combo_Totals_3</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Nums_Total_4</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Combo_Totals_4</TD>\n");
		print("</TR>\n");

		print("</TABLE>\n");

		print "<h3>Table <font color=\"#ff0000\">temp_column_pairs_$col, $sum, $even, $odd</font> Updated!</h3>";

		#die();
	}
?>