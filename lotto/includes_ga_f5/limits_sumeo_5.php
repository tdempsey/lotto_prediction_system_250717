<?php
	// ----------------------------------------------------------------------------------
	function limits_sumeo_5($sum,$even,$odd)
	{
		global $debug, $draw_table_name, $balls, $balls_drawn, $draw_prefix, $game, $hml; 

		require ("includes/mysqli.php"); 

		print("<P>");
		print("<TABLE BORDER=\"1\">\n");

		//create header row
		print("<TR><B>\n");
		print("<TD BGCOLOR=\"#CCCCCC\" align=\"center\">Sum</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Col</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Even</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Odd</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Low</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>High</center></TD>\n");
		print("</B></TR>\n");

		############################################################################################
		#$query5 = "SELECT * FROM ga_f5_limits_by_sumeo_200119 ";
		$query5 = "SELECT * FROM ga_f5_limits_by_sumeo ";
		$query5 .= "WHERE sum = $sum ";
		$query5 .= "AND   even = $even ";
		$query5 .= "AND   odd = $odd ";
		$query5 .= "ORDER BY col ASC ";

		echo "<p>$query5</p>";

		$mysqli_result = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));

		// get each row
		while($row = mysqli_fetch_array($mysqli_result))
		{
			print("<TR>\n");
			print("<TD><center>$row[sum]</center></TD>\n");
			print("<TD><center>$row[col]</center></TD>\n");
			print("<TD align=center>$row[even]</TD>\n");
			print("<TD align=center>$row[odd]</TD>\n");
			print("<TD align=center>$row[low]</TD>\n");
			print("<TD align=center>$row[high]</TD>\n");
			print("</TR>\n");
		}

		print("<TR><B>\n");
		print("<TD BGCOLOR=\"#CCCCCC\" align=\"center\">Sum</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Col</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Even</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Odd</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Low</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>High</center></TD>\n");
		print("</B></TR>\n");

		//end table
		print("</TABLE>\n");
	}

	// ----------------------------------------------------------------------------------
	function limits_sumeo_5_m2($sum,$even,$odd)
	{
		global $debug, $draw_table_name, $balls, $balls_drawn, $draw_prefix, $game, $hml; 

		require ("includes/mysqli.php"); 

		print("<P>");
		print("<TABLE BORDER=\"1\">\n");

		//create header row
		print("<TR><B>\n");
		print("<TD BGCOLOR=\"#CCCCCC\" align=\"center\">Sum</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Col</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Even</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Odd</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Low</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>High</center></TD>\n");
		print("</B></TR>\n");

		############################################################################################
		#$query5 = "SELECT * FROM ga_f5_limits_by_sumeo_200119 ";
		$query5 = "SELECT * FROM ga_f5_limits_by_sumeo_m2 ";
		$query5 .= "WHERE sum = $sum ";
		$query5 .= "AND   even = $even ";
		$query5 .= "AND   odd = $odd ";
		$query5 .= "ORDER BY col ASC ";

		echo "<p>$query5</p>";

		$mysqli_result = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));

		// get each row
		while($row = mysqli_fetch_array($mysqli_result))
		{
			print("<TR>\n");
			print("<TD><center>$row[sum]</center></TD>\n");
			print("<TD><center>$row[col]</center></TD>\n");
			print("<TD align=center>$row[even]</TD>\n");
			print("<TD align=center>$row[odd]</TD>\n");
			print("<TD align=center>$row[low]</TD>\n");
			print("<TD align=center>$row[high]</TD>\n");
			print("</TR>\n");
		}

		print("<TR><B>\n");
		print("<TD BGCOLOR=\"#CCCCCC\" align=\"center\">Sum</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Col</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Even</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Odd</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Low</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>High</center></TD>\n");
		print("</B></TR>\n");

		//end table
		print("</TABLE>\n");
	}
?>