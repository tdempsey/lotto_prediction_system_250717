<?php

	$game = 1; // Georgia F5

	$k = 0;

	// include to connect to database
	require ("includes/mysqli.php");
	#require ("includes/count_2_seq.php");
	#require ("includes/count_3_seq.php");
	#require ("includes_fl/build_rank_table_fl.php");
	#require ("includes_fl/calculate_rank_fl.php");
	#require ("includes_ga_f5/last_draws_fl_f5.php");
	#require ("includes_ga_f5/combin.incl");

	//start HTML page
	print("<HTML>\n");
	print("<HEAD>\n");
	print("<TITLE>Build D6</TITLE>\n");
	print("</HEAD>\n");
	
	print("<BODY bgcolor=\"#FFFFFF\" text=\"#000000\">\n");

	$curr_date = date('Y-m-d');

	$drop_tables = 1;
	
	if ($drop_tables)
	{
		$combo_table = "ga_f5_combo8_3";

		$query = "DROP TABLE IF EXISTS $combo_table ";

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

		$query =  "CREATE TABLE IF NOT EXISTS  $combo_table ( ";
		$query .= "  `id` int(10) unsigned NOT NULL auto_increment, ";
		$query .= "  `d1` tinyint(2) unsigned NOT NULL default '0', ";
		$query .= "  `d2` tinyint(2) unsigned NOT NULL default '0', ";
		$query .= "  `d3` tinyint(2) unsigned NOT NULL default '0', ";
		$query .= "  `d4` tinyint(2) unsigned NOT NULL default '0', ";
		$query .= "  `d5` tinyint(2) unsigned NOT NULL default '0', ";
		$query .= "  `d6` tinyint(2) unsigned NOT NULL default '0', ";
		$query .= "  `d7` tinyint(2) unsigned NOT NULL default '0', ";
		$query .= "  `d8` tinyint(2) unsigned NOT NULL default '0', ";
		$query .= "  PRIMARY KEY  (`id`) ";
		$query .= ") ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 "; 

		print("$query<p>");

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));
		/*
		$combo_table_combo4 = "ga_f5_draw_range4";

		$query = "DROP TABLE IF EXISTS $combo_table_combo4 ";

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

		$query =  "CREATE TABLE IF NOT EXISTS  $combo_table_combo4 ( ";
		$query .= "  `id` int(10) unsigned NOT NULL auto_increment, ";
		$query .= "  `date` date NOT NULL DEFAULT '1962-08-17', ";
		$query .= "  `d1` tinyint(2) unsigned NOT NULL default '0', ";
		$query .= "  `d2` tinyint(2) unsigned NOT NULL default '0', ";
		$query .= "  `d3` tinyint(2) unsigned NOT NULL default '0', ";
		$query .= "  `d4` tinyint(2) unsigned NOT NULL default '0', ";
		$query .= "  PRIMARY KEY  (`id`) ";
		$query .= ") ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 "; 

		print("$query<p>");

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));
		*/
	}

	#$array_d = array_fill (0,7,0);

	for ($d1 = 0; $d1 <= 3; $d1++)
	{
		for ($d2 = 0; $d2 <= 3; $d2++)
		{
			for ($d3 = 0; $d3 <= 3; $d3++)
			{
				for ($d4 = 0; $d4 <= 3; $d4++)
				{
					for ($d5 = 0; $d5 <= 3; $d5++)
					{
						for ($d6 = 0; $d6 <= 3; $d6++)
						{
							for ($d7 = 0; $d7 <= 3; $d7++)
							{
								for ($d8 = 0; $d8 <= 3; $d8++)
								{
										if (($d1 + $d2 + $d3 + $d4 + $d5 + $d6 + $d7 + $d8) == 3)
										{
											echo "$d1 - $d2 - $d3 - $d4 - $d5 - $d6 - $d7 - $d8<br>";

											$query = "INSERT INTO $combo_table ";
											$query .= "VALUES ('0', ";
											$query .= "'$d1', ";
											$query .= "'$d2', ";
											$query .= "'$d3', ";
											$query .= "'$d4', ";
											$query .= "'$d5', ";
											$query .= "'$d6', ";
											$query .= "'$d7', ";
											$query .= "'$d8') ";

											$mysqli_result2 = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));
										}
								}
							}
						}
					}
				}
			}
		}
	}
	
	die();
?>