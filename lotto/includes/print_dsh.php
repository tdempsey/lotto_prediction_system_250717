<?php
	function PrintHotDueSoon()
	{
	    global $debug,$hot_array,$warm_array,$cold_array,$due_array,$soon_array;

		//start table
		print("<TABLE BORDER=\"1\">\n");
		
		//create header row
		print("<TR>\n");
		print("<TD BGCOLOR=\"#99CCFFa\">Due&nbsp;</TD>\n");
		print("</TR>\n");
		
		for ($index = 0; $index < count($due_array); $index++)
		{
			print("<TR>\n");
			print("<TD>$due_array[$index]</TD>\n");
			print("</TR>\n");
		}
		
		if (count($due_array) == 0)
		{
			print("<TR>\n");
			print("<TD>None</TD>\n");
			print("</TR>\n");
		}

		//end table
		print("</TABLE><p>\n");
		
		//start table
		print("<TABLE BORDER=\"1\">\n");
		
		//create header row
		print("<TR>\n");
		print("<TD BGCOLOR=\"#99CCFFa\">Soon</TD>\n");
		print("</TR>\n");
		
		for ($index = 0; $index < count($soon_array); $index++)
		{
			print("<TR>\n");
			print("<TD>$soon_array[$index]</TD>\n");
			print("</TR>\n");
		}
		
		if (count($soon_array) == 0)
		{
			print("<TR>\n");
			print("<TD>None</TD>\n");
			print("</TR>\n");
		}
		
		//end table
		print("</TABLE><p>\n");

		//start table
		print("<TABLE BORDER=\"1\">\n");
		
		//create header row
		print("<TR>\n");
		print("<TD BGCOLOR=\"#99CCFFa\">Hot&nbsp;</TD>\n");
		print("</TR>\n");
		
		for ($index = 0; $index < count($hot_array); $index++)
		{
			print("<TR>\n");
			print("<TD>$hot_array[$index]</TD>\n");
			print("</TR>\n");
		}
		
		if (count($hot_array) == 0)
		{
			print("<TR>\n");
			print("<TD>None</TD>\n");
			print("</TR>\n");
		}

		//end table
		print("</TABLE><p>\n");

		//start table
		print("<TABLE BORDER=\"1\">\n");
		
		//create header row
		print("<TR>\n");
		print("<TD BGCOLOR=\"#99CCFFa\">Warm&nbsp;</TD>\n");
		print("</TR>\n");
		
		for ($index = 0; $index < count($warm_array); $index++)
		{
			print("<TR>\n");
			print("<TD>$warm_array[$index]</TD>\n");
			print("</TR>\n");
		}
		
		if (count($warm_array) == 0)
		{
			print("<TR>\n");
			print("<TD>None</TD>\n");
			print("</TR>\n");
		}

		//end table
		print("</TABLE><p>\n");

		//start table
		print("<TABLE BORDER=\"1\">\n");
		
		//create header row
		print("<TR>\n");
		print("<TD BGCOLOR=\"#99CCFFa\">Cold&nbsp;</TD>\n");
		print("</TR>\n");
		
		for ($index = 0; $index < count($cold_array); $index++)
		{
			print("<TR>\n");
			print("<TD>$cold_array[$index]</TD>\n");
			print("</TR>\n");
		}
		
		if (count($cold_array) == 0)
		{
			print("<TR>\n");
			print("<TD>None</TD>\n");
			print("</TR>\n");
		}

		//end table
		print("</TABLE><p>\n");
	}
?>