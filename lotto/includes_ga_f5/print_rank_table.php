<?php
	function PrintRankTableFL()
  	{
		global $zero_nums,$one_nums,$two_nums,$three_nums,$four_nums,$five_nums,$six_nums;
	
		//start sorted table
		print("<P>");
		print("<TABLE BORDER=\"1\">\n");

		//create header row
		print("<TR><B>\n");

		print("<TD BGCOLOR=\"#CCCCCC\">Number</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Total</TD>\n");
		print("</B></TR>\n");
		for ($count=0; $count < count($six_nums); $count++) 
		{
			print("<TR>\n");
			print("<TD>$six_nums[$count]</TD>\n");
			print("<TD>6+</TD>\n");
			print("</TR>\n");
		}
		for ($count=0; $count < count($five_nums); $count++) 
		{
			print("<TR>\n");
			print("<TD>$five_nums[$count]</TD>\n");
			print("<TD>5</TD>\n");
			print("</TR>\n");
		}
		for ($count=0; $count < count($four_nums); $count++) 
		{
			print("<TR>\n");
			print("<TD>$four_nums[$count]</TD>\n");
			print("<TD>4</TD>\n");
			print("</TR>\n");
		}
		for ($count=0; $count < count($three_nums); $count++) 
		{
			print("<TR>\n");
			print("<TD>$three_nums[$count]</TD>\n");
			print("<TD>3</TD>\n");
			print("</TR>\n");
		}
		for ($count=0; $count < count($two_nums); $count++) 
		{
			print("<TR>\n");
			print("<TD>$two_nums[$count]</TD>\n");
			print("<TD>2</TD>\n");
			print("</TR>\n");
		}
		for ($count=0; $count < count($one_nums); $count++) 
		{
			print("<TR>\n");
			print("<TD>$one_nums[$count]</TD>\n");
			print("<TD>1</TD>\n");
			print("</TR>\n");
		}
		for ($count=0; $count < count($zero_nums); $count++) 
		{
			print("<TR>\n");
			print("<TD>$zero_nums[$count]</TD>\n");
			print("<TD>0</TD>\n");
			print("</TR>\n");
		}
		print("</TABLE>\n");
		print("<p>\n");
	}
?>