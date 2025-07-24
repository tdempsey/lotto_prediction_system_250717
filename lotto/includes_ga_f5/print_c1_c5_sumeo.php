<?php
	// ----------------------------------------------------------------------------------
	function split_sumeo_5($sum,$even,$odd)
	{
		global $debug, $draw_table_name, $balls, $balls_drawn, $draw_prefix, $game, $hml; 

		require ("includes/mysqli.php"); 

		$curr_date = date('Y-m-d');

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

		$query89 = "SELECT * FROM ga_f5_dup_100 ";
		$query89 .= "WHERE dcount = 2 "; #3
		$query89 .= "AND count >= 10 "; #7/8
		$query89 .= "ORDER BY count DESC ";
		
		echo "$query89<p>";

		$mysqli_result89 = mysqli_query($mysqli_link, $query89) or die (mysqli_error($mysqli_link));

		// get each row
		while($row89 = mysqli_fetch_array($mysqli_result89))
		{
			//create header row
			print("<TR><B>\n");
			print("<TD BGCOLOR=\"#CCCCCC\" align=\"center\">Combin</TD>\n");
			print("<TD BGCOLOR=\"#CCCCCC\"><center>b1</center></TD>\n");
			print("<TD BGCOLOR=\"#CCCCCC\"><center>b2</center></TD>\n");
			print("<TD BGCOLOR=\"#CCCCCC\"><center>b3</center></TD>\n");
			print("<TD BGCOLOR=\"#CCCCCC\"><center>b4</center></TD>\n");
			print("<TD BGCOLOR=\"#CCCCCC\"><center>b5</center></TD>\n");
			print("<TD BGCOLOR=\"#CCCCCC\"><center>Count</center></TD>\n");
			print("</B></TR>\n");

			$temp3 = $row89[dup2]+1;
			$temp4 = $temp3+1;

			############################################################################################
			$query10 = "SELECT * FROM $table_temp ";

			$query10 .= "WHERE comb2 >= 9 AND comb5 = 0 ";
			#$query10 .= "AND   b1 <= '4' ";
			$query10 .= "AND rank0 <= 1 AND rank1 <= 2 AND rank2 <= 1 AND rank3 <= 2 AND rank4 <= 3 AND rank5 <= 1 AND rank6 <= 1 AND rank7 <= 1 ";
			#$query10 .= "AND dup1 = $row89[dup1] AND dup2 = $row89[dup2] AND dup3 = $row89[dup3]";
			$query10 .= "AND dup1 = $row89[dup1] AND dup2 = $row89[dup2] AND dup3 <= $temp3 AND dup4 <= $temp3 ";

			$query10 .= " AND   last_updated = '$curr_date' ";
			
			$query10 .= "ORDER BY y1_sum DESC ";
			$query10 .= "LIMIT 10 ";
			#$query10 .= AND dup20 >= 4 and dup25 = 5 ";
			
			#echo "$query10<p>";

			$mysqli_result10 = mysqli_query($mysqli_link, $query10) or die (mysqli_error($mysqli_link));

			// get each row
			while($row10 = mysqli_fetch_array($mysqli_result10))
			{
				print("<TR>\n");
				print("<TD><center>$row10[combin]</center></TD>\n");
				print("<TD ><center>$row10[b1]</center></TD>\n");
				print("<TD ><center>$row10[b2]</center></TD>\n");
				print("<TD ><center>$row10[b3]</center></TD>\n");
				print("<TD ><center>$row10[b4]</center></TD>\n");
				print("<TD ><center>$row10[b5]</center></TD>\n");
				print("<TD align=center>1</TD>\n");
				if ($row10[y1_sum] >= 25.5)
				{
					print("<TD align=center><b>$row10[y1_sum]</b></TD>\n");
				} else {
					print("<TD align=center>$row10[y1_sum]</TD>\n");
				}
				
				print("<TD align=center>$row10[dup1]</TD>\n");
				print("<TD align=center>$row10[dup2]</TD>\n");
				print("<TD align=center>$row10[dup3]</TD>\n");
				print("<TD align=center>$row10[dup4]</TD>\n");

				print("<TD ><center>$row10[rank0]</center></TD>\n");
				print("<TD ><center>$row10[rank1]</center></TD>\n");
				print("<TD ><center>$row10[rank2]</center></TD>\n");
				print("<TD ><center>$row10[rank3]</center></TD>\n");
				print("<TD ><center>$row10[rank4]</center></TD>\n");
				print("<TD ><center>$row10[rank5]</center></TD>\n");
				print("<TD ><center>$row10[rank6]</center></TD>\n");
				print("<TD ><center>$row10[rank7]</center></TD>\n");

				print("</TR>\n");
			}

			print("<TR><B>\n");
			print("<TD BGCOLOR=\"#CCCCCC\" align=\"center\">Combin</TD>\n");
			print("<TD BGCOLOR=\"#CCCCCC\"><center>b1</center></TD>\n");
			print("<TD BGCOLOR=\"#CCCCCC\"><center>b2</center></TD>\n");
			print("<TD BGCOLOR=\"#CCCCCC\"><center>b3</center></TD>\n");
			print("<TD BGCOLOR=\"#CCCCCC\"><center>b4</center></TD>\n");
			print("<TD BGCOLOR=\"#CCCCCC\"><center>b5</center></TD>\n");
			print("<TD BGCOLOR=\"#CCCCCC\"><center>Count</center></TD>\n");
			print("</B></TR>\n");
		}
	}
?>