<?php

	$game = 1; // GA F5

	$k = 1;

	// include to connect to database
	require ("includes/mysqli.php");
	require ("includes/count_2_seq.php");
	require ("includes/count_3_seq.php");
	#require ("includes_fl/build_profile_table_fl.php");
	#require ("includes_fl/calculate_rank_fl.php");
	require ("includes_ga_f5/last_draws_ga_f5.php");
	require ("includes_ga_f5/combin.incl");

	//start HTML page
	print("<HTML>\n");
	print("<HEAD>\n");
	print("<TITLE>Count 5/36</TITLE>\n");
	print("</HEAD>\n");
	
	print("<BODY bgcolor=\"#FFFFFF\" text=\"#000000\">\n");

	$curr_date = date('Y-m-d');

	$drop_tables = 0;
	
	if ($drop_tables)
	{
		$profile_table = "ga_f5_draw_profile";

		$query = "DROP TABLE IF EXISTS $profile_table ";

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

		$query =  "CREATE TABLE IF NOT EXISTS $profile_table (
		  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
		  `col1` tinyint(2) unsigned NOT NULL,
		  `hml` mediumint(3) unsigned NOT NULL,
		  `comb2` tinyint(2) unsigned NOT NULL,
		  `comb3` tinyint(2) unsigned NOT NULL,
		  `comb4` tinyint(2) unsigned NOT NULL,
		  `comb5` tinyint(2) unsigned NOT NULL,
		  `dup0` tinyint(1) unsigned NOT NULL,
		  `dup1` tinyint(1) unsigned NOT NULL,
		  `dup2` tinyint(1) unsigned NOT NULL,
		  `dup3` tinyint(1) unsigned NOT NULL,
		  `d0` tinyint(1) unsigned NOT NULL,
		  `d2` tinyint(1) unsigned NOT NULL,
		  `d3` tinyint(1) unsigned NOT NULL,
		  `d4` tinyint(1) unsigned NOT NULL,
		  `r0` tinyint(1) unsigned NOT NULL,
		  `r1` tinyint(2) unsigned NOT NULL,
		  `r2` tinyint(3) unsigned NOT NULL,
		  `r3` tinyint(3) unsigned NOT NULL,
		  `r4` tinyint(3) unsigned NOT NULL,
		  `r5` tinyint(3) unsigned NOT NULL,
		  `r6` tinyint(3) unsigned NOT NULL,
		  `seq2` tinyint(3) unsigned NOT NULL,
		  `seq3` tinyint(3) unsigned NOT NULL,
		  `mod` tinyint(3) unsigned NOT NULL,
		  `modx` tinyint(1) unsigned NOT NULL,
		  `eo50` tinyint(3) unsigned NOT NULL,
		  `eo50_count` tinyint(3) unsigned NOT NULL DEFAULT '0',
		  `eo50_last` date NOT NULL,
		  `wa` float(4,2) unsigned NOT NULL,
		  PRIMARY KEY (`id`),
		  KEY `hml` (`hml`),
		  KEY `col1` (`col1`),
		  KEY `eo50` (`eo50`)
		) ENGINE=InnoDB  DEFAULT CHARSET=latin1 " ;

		

		print("$query<p>");

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));
	}

	print("<p align=\"center\"><b><font face=\"Arial, Helvetica, sans-serif\">Count 5/39</font></b></p>");

	$bold_array = array(54,58,64,66,69,78,79,86,87,91,96,98,104,106,114,124,125,132,140,144,151);

	echo "<h2>combo_5_39</h2>";

	for ($m = 1; $m <= 11; $m++)
	{
		$query = "SELECT * FROM combo_5_39 ";
		$query .= "WHERE b1 = $m ";

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

		$num_rows_all = mysqli_num_rows($mysqli_result);

		$temp_format = number_format($num_rows_all);
		
		echo "<p>combo_5_39 - b1 = $m = $temp_format</p>";

		print "<table border=1>";

		print "<tr>";
		print "<td><b><center>Sum</center></b></td>";
		print "<td><b><center>All</center></b></td>";
		print "<td><b><center>Filter A</center></b></td>";
		print "<td><b><center>Filter B</center></b></td>";
		print "</tr>";
			
		for ($n = 50; $n <= 155; $n++)
		{
			$query = "SELECT * FROM combo_5_39 ";
			$query .= "WHERE b1 = $m ";
			$query .= "AND   sum = $n ";

			$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

			$num_rows_all = mysqli_num_rows($mysqli_result);

			$temp_format = number_format($num_rows_all);

			print "<tr>";
			if (in_array($n,$bold_array))
			{
				print "<td><b>$n</b></td>";
				print "<td><b>$temp_format</b></td>";
			} else {
				print "<td>$n</td>";
				print "<td>$temp_format</td>";
			}

			$query = "SELECT * FROM ga_f5_filter_a_";
			if ($m < 10)
			{
				$query .= "0$m ";
			} else {
				$query .= "$m ";
			}
			$query .= "WHERE sum = $n ";

			$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

			$num_rows_all = mysqli_num_rows($mysqli_result);

			$temp_format = number_format($num_rows_all);

			if (in_array($n,$bold_array))
			{
				print "<td><b>$temp_format</b></td>";
			} else {
				print "<td>$temp_format</td>";
			}

			$query = "SELECT * FROM ga_f5_filter_b_";
			if ($m < 10)
			{
				$query .= "0$m ";
			} else {
				$query .= "$m ";
			}
			$query .= "WHERE sum = $n ";

			$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

			$num_rows_all = mysqli_num_rows($mysqli_result);

			$temp_format = number_format($num_rows_all);

			if (in_array($n,$bold_array))
			{
				print "<td><b>$temp_format</b></td>";
			} else {
				print "<td>$temp_format</td>";
			}

			print "</tr>";
		}
		
		print "</table>";
	}

	echo "<h2>Draw Profile A - Filter B - EO50</h2>";

	print "<table border=1>";

	print "<tr>";
	print "<td><b><center>b1</center></b></td>";
	print "<td><b><center>Sum</center></b></td>";
	print "<td><b><center>EO50</center></b></td>";
	print "<td><b><center>Even</center></b></td>";
	print "<td><b><center>Odd</center></b></td>";
	print "<td><b><center>D501</center></b></td>";
	print "<td><b><center>D502</center></b></td>";
	print "<td><b><center>Total</center></b></td>";
	print "</tr>";

	for ($m = 1; $m <= 11; $m++)
	{
		$query = "SELECT * FROM ga_f5_draw_profile_a ";
		#$query .= "WHERE b1 = $m ";

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

		$num_rows_all = mysqli_num_rows($mysqli_result);

		while($row = mysqli_fetch_array($mysqli_result))
		{
			$query_temp = "SELECT * FROM ga_f5_eo50 ";
			$query_temp .= "WHERE id = $row[eo50] ";

			#print "$query_temp<br>";

			$mysqli_result_temp = mysqli_query($mysqli_link, $query_temp) or die (mysqli_error($mysqli_link));

			$row_temp = mysqli_fetch_array($mysqli_result_temp);

			$query_temp2 = "SELECT * FROM ga_f5_filter_b_";
			if ($m < 10)
			{
				$query_temp2 .= "0$m ";
			} else {
				$query_temp2 .= "$m ";
			}
			$query_temp2 .= "WHERE even = $row_temp[even] ";
			$query_temp2 .= "AND   odd  = $row_temp[odd] ";
			$query_temp2 .= "AND   d2_1 = $row_temp[d501] ";
			$query_temp2 .= "AND   d2_2 = $row_temp[d502] ";
			$query_temp2 .= "AND   sum  = $row[hml] ";

			#print "$query_temp2<br>";

			$mysqli_result_temp2 = mysqli_query($query_temp2, $mysqli_link) or die (mysqli_error($mysqli_link));

			$num_rows_all2 = mysqli_num_rows($mysqli_result_temp2);

			#echo "$row[hml] - $m - eo50=$row[eo50]($row_temp[even]/$row_temp[odd]/$row_temp[d501]/$row_temp[d502]) = $num_rows_all2<br>";

			print "<tr>";
			print "<td><center>$m</center></td>";
			print "<td><center>$row[hml]</center></td>";
			print "<td><center>$row[eo50]</center></td>";
			print "<td><center>$row_temp[even]</center></td>";
			print "<td><center>$row_temp[odd]</center></td>";
			print "<td><center>$row_temp[d501]</center></td>";
			print "<td><center>$row_temp[d502]</center></td>";
			print "<td><center>$num_rows_all2</center></td>";
			print "</tr>";
		}

		print "</table>";
	}
		
	echo "<h2>Draw Profile A - Filter B - Combo</h2>";

	print "<table border=1>";

	print "<tr>";
	print "<td><b><center>b1</center></b></td>";
	print "<td><b><center>Sum</center></b></td>";
	print "<td><b><center>C2</center></b></td>";
	print "<td><b><center>C3</center></b></td>";
	print "<td><b><center>C4</center></b></td>";
	print "<td><b><center>C5</center></b></td>";
	print "<td><b><center>Total</center></b></td>";
	print "</tr>";

	for ($m = 1; $m <= 11; $m++)
	{
		for ($n = 50; $n <= 155; $n++)
		{
			$query = "SELECT * FROM ga_f5_combo_count_profile ";
			$query .= "WHERE comb2 >= 8 ";
			$query .= "ORDER by comb2 ASC ";
			#$query .= "WHERE b1 = $m ";

			$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

			$num_rows_all = mysqli_num_rows($mysqli_result);

			while($row = mysqli_fetch_array($mysqli_result))
			{
				$query_temp2 = "SELECT * FROM ga_f5_filter_b_";
				if ($m < 10)
				{
					$query_temp2 .= "0$m ";
				} else {
					$query_temp2 .= "$m ";
				}
				$query_temp2 .= "WHERE comb2 = $row[comb2] ";
				$query_temp2 .= "AND   comb3 = $row[comb3] ";
				$query_temp2 .= "AND   comb4 = $row[comb4] ";
				$query_temp2 .= "AND   comb5 = $row[comb5] ";
				$query_temp2 .= "AND   sum  = $n ";

				#print "$query_temp2<br>";

				$mysqli_result_temp2 = mysqli_query($query_temp2, $mysqli_link) or die (mysqli_error($mysqli_link));

				$num_rows_all2 = mysqli_num_rows($mysqli_result_temp2);

				print "<tr>";
				print "<td><center>$m</center></td>";
				print "<td><center>$n</center></td>";
				print "<td><center>$row[comb2]</center></td>";
				print "<td><center>$row[comb3]</center></td>";
				print "<td><center>$row[comb4]</center></td>";
				print "<td><center>$row[comb5]</center></td>";
				print "<td><center>$num_rows_all2</center></td>";
				print "</tr>";
			}
		}
	}

	print "</table>";

	die();
	##################################################################################################
	$col1 = 1;
	$hml = 50;

	while($row = mysqli_fetch_array($mysqli_result))
	{
		if ($row[count] > 1)
		{		
			$query_temp = "SELECT * FROM ga_f5_eo50 ";
			$query_temp .= "WHERE even = $row[even] ";
			$query_temp .= "AND   odd = $row[odd] ";
			$query_temp .= "AND   d501 = $row[d501] ";
			$query_temp .= "AND   d502 = $row[d502] ";

			#print "$query_temp<br>";

			$mysqli_result_temp = mysqli_query($mysqli_link, $query_temp) or die (mysqli_error($mysqli_link));

			$row_temp = mysqli_fetch_array($mysqli_result_temp);

			$query = "INSERT INTO $profile_table ";
			$query .= "VALUES ('0', ";
			$query .= "'$col1', ";
			$query .= "'$row[sum]', ";
			$query .= "'0', ";
			$query .= "'0', ";
			$query .= "'0', ";
			$query .= "'0', ";
			$query .= "'0', ";
			$query .= "'0', ";
			$query .= "'0', ";
			$query .= "'0', ";
			$query .= "'0', ";
			$query .= "'0', ";
			$query .= "'0', ";
			$query .= "'0', ";
			$query .= "'0', ";
			$query .= "'0', ";
			$query .= "'0', ";
			$query .= "'0', ";
			$query .= "'0', ";
			$query .= "'0', ";
			$query .= "'0', ";
			$query .= "'1', ";
			$query .= "'0', ";
			$query .= "'1', ";
			$query .= "'0', ";
			$query .= "'$row_temp[id]', ";
			$query .= "'$row[count]', ";
			$query .= "'$row[last_date]', ";
			$query .= "'$row[wa]') ";

			print "$query<br>";
			
			$mysqli_result2 = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

			#die("insert die");
		}
	}

	print("</body>");
	print("</html>");

?>