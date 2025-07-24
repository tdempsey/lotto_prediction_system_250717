<?php
	function PrintDrawTable()
  	{
		global $debug,$draw_range;
		
		//start table
		print("<TABLE BORDER=\"1\">\n");
		
		//create header row
		print("<TR>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Draw</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Low</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">High</TD>\n");
		print("</TR>\n");
		
		print("<TR>\n");
		print("<TD>0x</TD>\n");
		print("<TD>{$draw_range[0][0]}</TD>\n");
		print("<TD>{$draw_range[0][1]}</TD>\n");
		print("</TR>\n");
		
		print("<TR>\n");
		print("<TD>1x</TD>\n");
		print("<TD>{$draw_range[1][0]}</TD>\n");
		print("<TD>{$draw_range[1][1]}</TD>\n");
		print("</TR>\n");
		
		print("<TR>\n");
		print("<TD>2x</TD>\n");
		print("<TD>{$draw_range[2][0]}</TD>\n");
		print("<TD>{$draw_range[2][1]}</TD>\n");
		print("</TR>\n");
		
		print("<TR>\n");
		print("<TD>3x</TD>\n");
		print("<TD>{$draw_range[3][0]}</TD>\n");
		print("<TD>{$draw_range[3][1]}</TD>\n");
		print("</TR>\n");
		
		print("<TR>\n");
		print("<TD>4x</TD>\n");
		print("<TD>{$draw_range[4][0]}</TD>\n");
		print("<TD>{$draw_range[4][1]}</TD>\n");
		print("</TR>\n");
		
		print("<TR>\n");
		print("<TD>5x</TD>\n");
		print("<TD>{$draw_range[5][0]}</TD>\n");
		print("<TD>{$draw_range[5][1]}</TD>\n");
		print("</TR>\n");
		
		//end table
		print("</TABLE><p>\n");
	}
?>