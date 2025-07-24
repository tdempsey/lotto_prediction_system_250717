<?php
	function print_table_header($table_header,$table_header_color) 
	{  
		global $debug, $game;

		// error checking ----------------------------------------------------------------------------------------------	
		if (is_null($table_header) || count($table_header) == 0)
		{
			exit("<h2>Error - function print_table_header.php - <font color=\"#FF0000\">array table_header undefined</font></h2>");
		}

		if (is_null($table_header_color) || count($table_header_color) == 0)
		{
			exit("<h2>Error - function print_table_header.php - <font color=\"#FF0000\">array table_header_color undefined</font></h2>");
		}
		// error checking ----------------------------------------------------------------------------------------------

		
		  
		return 0; 
	}
?>