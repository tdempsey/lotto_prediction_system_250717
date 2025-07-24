<?php
	function PrintStats()
  	{
		global	$sum_average_high,$sum_average_low,$average_average_high,$average_average_low,$even_high,
				$even_low,$odd_high,$odd_low,$range501_high,$range501_low,$range502_high,$range502_low;
	
		//start table
		print("<TABLE BORDER=\"1\">\n");

		//create header row
		print("<TR>\n");

		print("<TD BGCOLOR=\"#CCCCCC\">Even High</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Even Low</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Odd High</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Odd Low</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">501 High</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">501 Low</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">502 High</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">502 Low</TD>\n");
		print("</TR>\n");
		
		print("<TR>\n");

		print("<TD>$even_high</TD>\n");
		print("<TD>$even_low</TD>\n");
		print("<TD>$odd_high</TD>\n");
		print("<TD>$odd_low</TD>\n");
		print("<TD>$range501_high</TD>\n");
		print("<TD>$range501_low</TD>\n");
		print("<TD>$range502_high</TD>\n");
		print("<TD>$range502_low</TD>\n");
		print("</TR>\n");
		
		//end table
		print("</TABLE><p>\n");
	}
?>