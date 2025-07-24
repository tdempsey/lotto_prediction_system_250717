<?php
	// ----------------------------------------------------------------------------------
	function table_format_array ($count_array,$title,$column_label,$limit,$color_limit_1,$color_limit_2)
	{
		//start sorted table --------------------------------------------------------
		print("<h2>$title - Limit $limit</h2>\n");
		print("<P>");
		print("<TABLE BORDER=\"1\">\n");

		//create header row
		print("<TR><B>\n");

		print("<TD BGCOLOR=\"#CCCCCC\" align=\"center\">$column_label</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Last</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Week1</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Week2</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Month1</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Month3</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Month6</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Year1</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Year2</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Year3</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Year4</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Year5</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Year6</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Year7</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Year8</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Year9</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Year10</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Total</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Prev</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Last</center></TD>\n");
		print("</B></TR>\n");

		$start_array = 0;
		$end_array = 0;
		
		for ($x = 0; $x < count($count_array) || $start_array; $x++)
		{
			if ($count_array[$x] != 0 && $x > 4)
			{
				$start_array = $x - 3;
			}
		}

		for ($x = count($count_array); $x >= count($count_array)-4 || $end_array; $x--)
		{
			if ($count_array[$x] != 0 && $x > count($count_array)
			{
				$end_array = $x + 3;
			}
		}

		for ($x = $start_array; $x <= $end_array; $x++)
		{
			#$x = $row[num];
			print("<TR>\n");

			print("<TD align=center>$x</TD>\n");	

			for ($d = 0; $d <= 15; $d++)
			{
				if ($count_array[$x][$d] > 79) #$color_limit_1
				{
					print("<TD bgcolor=\"#FF0033\" align=center>{$count_array[$x][$d]}</TD>\n");
				} elseif ($draw_count_array[$x][$d] > 15) { #$color_limit_2
					print("<TD bgcolor=\"#CCFFFF\" align=center>{$count_array[$x][$d]}</TD>\n");
				} elseif ($draw_count_array[$x][$d] >= 1) {
					print("<TD bgcolor=\"#CCFF66\" align=center>{$count_array[$x][$d]}</TD>\n");
				} elseif ($draw_count_array[$x][$d] == 1) {
					print("<TD bgcolor=\"#F1F1F1\" align=center>{$draw_count_array[$x][$d]}</TD>\n");
				} else {
					print("<TD align=center>{$count_array[$x][$d]}</TD>\n");
				}
			} 

			if ($row[count] > $sorted_limit[16])
			{
				print("<TD bgcolor=\"#FF0000\" align=center><font color=\"#ffffff\"><b>$row[count]</b></font></TD>\n");
			} else {
				print("<TD align=center>$row[count]</TD>\n");
			}
			
			if ((strtotime ("$row[prev_date]") - $month6) < 0)
			{
				print("<TD nowrap><font color=\"#ff0000\">$row[prev_date]</font></TD>\n");
			} else {
				print("<TD nowrap>$row[prev_date]</TD>\n");
			}

			print("<TD nowrap>$row[0]</TD>\n");

			print("</TR>\n");
			$dcount++;

			if ($dcount == intval($balls/2))
			{
				print("<TR><B>\n");

				print("<TD BGCOLOR=\"#CCCCCC\" align=\"center\">Number</TD>\n");
				print("<TD BGCOLOR=\"#CCCCCC\"><center>Last</center></TD>\n");
				print("<TD BGCOLOR=\"#CCCCCC\"><center>Week1</center></TD>\n");
				print("<TD BGCOLOR=\"#CCCCCC\"><center>Week2</center></TD>\n");
				print("<TD BGCOLOR=\"#CCCCCC\"><center>Month1</center></TD>\n");
				print("<TD BGCOLOR=\"#CCCCCC\"><center>Month3</center></TD>\n");
				print("<TD BGCOLOR=\"#CCCCCC\"><center>Month6</center></TD>\n");
				print("<TD BGCOLOR=\"#CCCCCC\"><center>Year1</center></TD>\n");
				print("<TD BGCOLOR=\"#CCCCCC\"><center>Year2</center></TD>\n");
				print("<TD BGCOLOR=\"#CCCCCC\"><center>Year3</center></TD>\n");
				print("<TD BGCOLOR=\"#CCCCCC\"><center>Year4</center></TD>\n");
				print("<TD BGCOLOR=\"#CCCCCC\"><center>Year5</center></TD>\n");
				print("<TD BGCOLOR=\"#CCCCCC\"><center>Year6</center></TD>\n");
				print("<TD BGCOLOR=\"#CCCCCC\"><center>Year7</center></TD>\n");
				print("<TD BGCOLOR=\"#CCCCCC\"><center>Year8</center></TD>\n");
				print("<TD BGCOLOR=\"#CCCCCC\"><center>Year9</center></TD>\n");
				print("<TD BGCOLOR=\"#CCCCCC\"><center>Year10</center></TD>\n");
				print("<TD BGCOLOR=\"#CCCCCC\"><center>Total</center></TD>\n");
				print("<TD BGCOLOR=\"#CCCCCC\"><center>Prev</center></TD>\n");
				print("<TD BGCOLOR=\"#CCCCCC\"><center>Last</center></TD>\n");
				print("</B></TR>\n");
			}
		}

		//create footer row
		print("<TR><B>\n");

		print("<TD BGCOLOR=\"#CCCCCC\" align=\"center\">Number</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Last</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Week1</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Week2</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Month1</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Month3</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Month6</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Year1</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Year2</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Year3</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Year4</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Year5</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Year6</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Year7</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Year8</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Year9</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Year10</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Total</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Prev</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Last</center></TD>\n");
		print("</B></TR>\n");

		//end table
		print("</TABLE>\n");
	
	}

	/*
		$draw_date_array = explode("-","$row2[0]"); ### 210104
		$draw_date_unix = mktime (0,0,0,$draw_date_array[1],$draw_date_array[2],$draw_date_array[0]); ### 210104

		$x = $row[sum];
		
		if ($draw_date_unix == $day1)
		{ 
			for ($d = 0; $d <= 15; $d++) {$count_array[$x][$d]++;}
		} elseif ($draw_date_unix > $week1) {
			for ($d = 1; $d <= 15; $d++) {$count_array[$x][$d]++;}
		} elseif ($draw_date_unix > $week2) {
			for ($d = 2; $d <= 15; $d++) {$count_array[$x][$d]++;}
		} elseif ($draw_date_unix > $month1) {
			for ($d = 3; $d <= 15; $d++) {$count_array[$x][$d]++;}
		} elseif ($draw_date_unix > $month3) {
			for ($d = 4; $d <= 15; $d++) {$count_array[$x][$d]++;}
		} elseif ($draw_date_unix > $month6) {
			for ($d = 5; $d <= 15; $d++) {$count_array[$x][$d]++;}
		} elseif ($draw_date_unix > $year1) {
			for ($d = 6; $d <= 15; $d++) {$count_array[$x][$d]++;}
		} elseif ($draw_date_unix > $year2) {
			for ($d = 7; $d <= 15; $d++) {$count_array[$x][$d]++;}
		} elseif ($draw_date_unix > $year3) {
			for ($d = 8; $d <= 15; $d++) {$count_array[$x][$d]++;}
		} elseif ($draw_date_unix > $year4) {
			for ($d = 9; $d <= 15; $d++) {$count_array[$x][$d]++;}
		} elseif ($draw_date_unix > $year5) {
			for ($d = 10; $d <= 15; $d++) {$count_array[$x][$d]++;}
		} elseif ($draw_date_unix > $year6) {
			for ($d = 11; $d <= 15; $d++) {$count_array[$x][$d]++;}
		} elseif ($draw_date_unix > $year7) {
			for ($d = 12; $d <= 15; $d++) {$count_array[$x][$d]++;}
		} elseif ($draw_date_unix > $year8) {
			for ($d = 13; $d <= 15; $d++) {$count_array[$x][$d]++;}
		} elseif ($draw_date_unix > $year9) {
			for ($d = 14; $d <= 15; $d++) {$count_array[$x][$d]++;}
		} elseif ($draw_date_unix > $year10) {
			for ($d = 15; $d <= 15; $d++) {$count_array[$x][$d]++;}
		}

		$count_array[$x][16]++;
	*/

?>