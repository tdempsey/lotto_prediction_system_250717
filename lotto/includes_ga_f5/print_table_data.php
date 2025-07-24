<?php
	function print_table_data($table_data,$table_data_color,$table_data_background_color) 
	{  
		global $debug, $game;

		// error checking ----------------------------------------------------------------------------------------------	
		if (is_null($table_data) || count($table_data) == 0)
		{
			exit("<h2>Error - function print_table.php - <font color=\"#FF0000\">array table_data undefined</font></h2>");
		}

		if (is_null($table_data_color) || count($table_data_color) == 0)
		{
			exit("<h2>Error - function print_table_data.php - <font color=\"#FF0000\">array table_data_color undefined</font></h2>");
		}

		if (is_null($table_data_background_color) || count($table_data_background_color) == 0)
		{
			exit("<h2>Error - function print_table_data.php - <font color=\"#FF0000\">array table_data_background_color undefined</font></h2>");
		}
		// error checking ----------------------------------------------------------------------------------------------

		foreach
		
		  
		return 0; 
	}
?>