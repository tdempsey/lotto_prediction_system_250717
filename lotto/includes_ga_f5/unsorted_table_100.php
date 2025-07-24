<?php
	//start table unsorted --------------------------------------------------------
	print("<P>");
	print("<h2>Numbers Unsorted - Limit $limit</h2>\n");
	print("<TABLE BORDER=\"1\">\n");

	//create header row
	print("<TR><B>\n");
	
	print("<TD BGCOLOR=\"#CCCCCC\">Numbers Unsorted</TD>\n");
	for ($x = 1; $x <= 100; $x++)
	{
		print("<TD BGCOLOR=\"#CCCCCC\" align=center width=20>$x</TD>\n");
	}
	print("<TD BGCOLOR=\"#CCCCCC\"><center>Total</center></TD>\n");
	print("<TD BGCOLOR=\"#CCCCCC\"><center>Prev</center></TD>\n");
	print("<TD BGCOLOR=\"#CCCCCC\"><center>Last</center></TD>\n");
	print("</B></TR>\n");

	$table_temp = $draw_prefix . "temp2a_" . $limit;

	// Table
	$query9 = "DROP TABLE IF EXISTS $table_temp ";

	//print("$query9\n");
	$mysqli_result = mysqli_query($mysqli_link, $query9) or die (mysqli_error($mysqli_link));

	$query9 = "CREATE TABLE $table_temp ( ";
	$query9 .= "num tinyint(3) unsigned NOT NULL, ";
	$query9 .= "count int(5) unsigned NOT NULL, ";
	$query9 .= "prev_date date NOT NULL default '1962-08-17', ";
	$query9 .= "date date NOT NULL default '1962-08-17', ";
	$query9 .= "PRIMARY KEY (num), ";
	$query9 .= "KEY num (num), ";
	$query9 .= "UNIQUE num_2 (num) ";
	$query9 .= ") ";

	#echo "$query9<br>";

	$mysqli_result = mysqli_query($mysqli_link, $query9) or die (mysqli_error($mysqli_link));

	for($index=1; $index <= $balls; $index++)
	{
		print("<TR>\n");
		print("<TD>$index</TD>\n");
		for ($x = 1; $x <= 100; $x++)
		{
			#echo "draw_count_array<br>"; #201108
			#print_r ($draw_count_array);
			#echo "<br>";
			
			if ($draw_count_array[$index][$x])
			{
				print("<TD BGCOLOR=\"#000000\" width=20><font color=\"ffffff\"><center>x<center></font></TD>\n");
			} else {
				print("<TD align=center width=20>-</TD>\n");
			}
		}
		print("<TD align=center>$num_array[$index]</TD>\n");
		print("<TD>$prev_date[$index]</TD>\n");
		print("<TD>$num_date[$index]</TD>\n");
		print("</TR>\n");

		$query9 = "INSERT INTO $table_temp ";
		$query9 .= "VALUES ('$index', ";
		$query9 .= "'$num_array[$index]', ";
		$query9 .= "'1962-08-17', ";
		$query9 .= "'1962-08-17') ";
		
		echo("$query9\n");

		$mysqli_result = mysqli_query($mysqli_link, $query9) or die (mysqli_error($mysqli_link));
	}

	//end table
	print("</TABLE>\n");

	print "<h3>Table <font color=\"#ff0000\">$table_temp</font> Updated!</h3>";
?>