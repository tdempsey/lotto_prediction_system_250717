<?php

	// Game ----------------------------- Game
	$game = 1; // Georgia Fantasy 5
	#$game = 2; // Mega Millions
	//$game = 3; // Georgia 5
	#$game = 4; // Jumbo
	#$game = 5; // Florida Fantasy 5
	#$game = 6; // Florida Lotto
	#$game = 7; // Powerball
	// Game ----------------------------- Game

	$hml = 0;
	#$hml = 1;		#= high
	#$hml = 2;		#= medium
	#$hml = 3;		#= low
	#$hml = 4;		#= min
	#$hml = 5;		#= max
	#$hml = 100;

	set_time_limit(0);

	$low = $_GET["low"];
	$high = $_GET["high"];
	$even = $_GET["even"];
	$odd = $_GET["odd"];

	#echo "top - low = $low<br>";

	$debug = 1;

	if ($debug)
	{
		ini_set('display_errors', '1');
		ini_set('display_startup_errors', '1');
		error_reporting(E_ALL);
	}

	$col1_select = 0;
	
	require_once ("includes/games_switch.incl");
	require_once ("includes/even_odd.php");
	require_once ("includes/last_draws.php");
	require_once ("includes/calculate_rank.php");
	require_once ("includes/look_up_rank.php"); 
	require_once ("includes/build_rank_table.php");
	require_once ("includes/test_column_lookup.php");
	require_once ("includes/calculate_rank_mb.php");
	require_once ("includes/next_draw.php");
	require_once ("includes/number_due.php");
	require_once ("includes/first_draw_unix.php");
	require_once ("includes/last_draw_unix.php"); 
	require_once ("includes/count_2_seq.php");
	require_once ("includes/count_3_seq.php");
	require_once ("includes/mysqli.php"); 
	require_once ("$game_includes/combin.incl");
	require_once ("includes/dateDiffInDays.php");
	require_once ("includes/print_sumeo_drange.incl");	###220413
	
	#require_once ("includes/log_text.incl");

	require_once ("includes/limit_functions.incl");

	date_default_timezone_set('America/New_York');

	#echo "<b>col1_select = $col1_select in lot_display.php</b><p>";

	require_once ("includes/hml_switch.incl");	

	$debug = 0;
	
	// ----------------------------------------------------------------------------------
	function print_table_col_range($low,$high,$even,$odd,$col,$date_limit)
	{

		global $draw_table_name, $balls, $balls_drawn, $game_includes, $draw_prefix, $game_includes, $hml, $range_low, $range_high, $col1_select; 

		require ("includes/mysqli.php"); 

		require ("$game_includes/config.incl");

		$query4 = "DROP TABLE IF EXISTS temp_col_range_";
		$query4 .= "$low";
		$query4 .= "_$high";
		$query4 .= "_$col ";
		
		#print "$query4<p>";

		$mysqli_result4 = mysqli_query($mysqli_link, $query4) or die (mysqli_error($mysqli_link));

		$query4 = "CREATE TABLE temp_col_range_";
		$query4 .= "$low";
		$query4 .= "_$high";
		$query4 .= "_$col (";
		$query4 .= " id tinyint(3) unsigned NOT NULL auto_increment, ";
		$query4 .= "num tinyint(2) unsigned NOT NULL default '0',";
		$query4 .= "count tinyint(2) unsigned NOT NULL,";
		$query4 .= "percent_all float(4,2) unsigned NOT NULL,";
		$query4 .= "PRIMARY KEY  (`id`),";
		$query4 .= "UNIQUE KEY `id_2` (`id`),";
		$query4 .= "KEY `id` (`id`)";
		$query4 .= ") ENGINE=InnoDB DEFAULT CHARSET=latin1;";

		#print "$query4<p>";

		$mysqli_result4 = mysqli_query($mysqli_link, $query4) or die (mysqli_error($mysqli_link));

		$total_col = 0;

		$query = "SELECT b$col, count(*) FROM $draw_table_name ";
		$query .= "WHERE sum >= $low ";
		$query .= "AND   sum <= $high ";
		$query .= "AND   even = $even ";
		$query .= "AND   odd = $odd ";
		$query .= "AND   date >= '$date_limit' ";
		$query .= "GROUP BY b$col ";
		$query .= "ORDER BY count(*) DESC ";

		echo "$query<p>";

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

		while($row = mysqli_fetch_array($mysqli_result))
		{
			$total_col += $row[1];
		}

		mysqli_data_seek($mysqli_result,0);

		//start table
		#print("<h3>Last $limit draws</h3>\n");
		print("<TABLE BORDER=\"1\">\n");

		//create header row
		print("<TR>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Col$col</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Count</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Percent</center></TD>\n");

		while($row = mysqli_fetch_array($mysqli_result))
		{
			print("<TR>\n");
			print("<TD align=center>$row[0]</TD>\n");
			print("<TD align=center>$row[1]</TD>\n");

			$col_percent = intval(($row[1]/$total_col)*100);

			print("<TD align=center><font size=\"-1\">$col_percent%</font></TD>\n");
			print("</TR>\n");

			$temp_percent = $col_percent/100;

			$query_insert = "INSERT INTO temp_col_range_";
			$query_insert .= "$low";
			$query_insert .= "_$high";
			$query_insert .= "_$col ";
			$query_insert .= "VALUES ('0', ";
			$query_insert .= "'$row[0]', ";
			$query_insert .= "'$row[1]', ";
			$query_insert .= "'$temp_percent') "; 

			#echo "$query_insert<p>";
		
			$mysqli_result_insert = mysqli_query($mysqli_link, $query_insert) or die (mysqli_error($mysqli_link));
		}

		print("</TABLE>\n");

		print "<p>";

	}

	// ----------------------------------------------------------------------------------
	function build_col_range_cover($low,$high,$even,$odd)
	{

		global $draw_table_name, $balls, $balls_drawn, $game_includes, $draw_prefix, $game_includes, $hml, $range_low, $range_high, $col1_select; 

		require ("includes/mysqli.php"); 

		require ("$game_includes/config.incl");

		$query4 = "DROP TABLE IF EXISTS temp_col_range_cover_";
		$query4 .= "$low";
		$query4 .= "_$high ";
		
		echo "$query4<p>";

		$mysqli_result4 = mysqli_query($mysqli_link, $query4) or die (mysqli_error($mysqli_link));

		$query4 = "CREATE TABLE temp_col_range_cover_";
		$query4 .= "$low";
		$query4 .= "_$high (";	
		$query4 .= " id int(5) unsigned NOT NULL auto_increment, ";
		$query4 .= "b1 tinyint(2) unsigned NOT NULL default '0', ";
		$query4 .= "b2 tinyint(2) unsigned NOT NULL default '0', ";
		$query4 .= "b3 tinyint(2) unsigned NOT NULL default '0', ";
		$query4 .= "b4 tinyint(2) unsigned NOT NULL default '0', ";
		$query4 .= "b5 tinyint(2) unsigned NOT NULL default '0', ";
		$query4 .= "sum tinyint(3) unsigned NOT NULL,";
		$query4 .= "even tinyint(1) unsigned NOT NULL,";
		$query4 .= "odd tinyint(1) unsigned NOT NULL,";
		$query4 .= "percent_all float(4,2) unsigned NOT NULL,";
		$query4 .= "PRIMARY KEY  (`id`),";
		$query4 .= "UNIQUE KEY `id_2` (`id`),";
		$query4 .= "KEY `id` (`id`)";
		$query4 .= ") ENGINE=InnoDB DEFAULT CHARSET=latin1;";

		echo "$query4<p>";

		$mysqli_result4 = mysqli_query($mysqli_link, $query4) or die (mysqli_error($mysqli_link));

		$count = 1;

		$query1 = "SELECT * FROM temp_col_range_";
		$query1 .= "$low";
		$query1 .= "_$high";
		$query1 .= "_1 ";
		$query1 .= "WHERE percent_all >= 0.07 ";
		$query1 .= "ORDER BY count DESC ";

		#echo "$query1<p>";

		$mysqli_result1 = mysqli_query($mysqli_link, $query1) or die (mysqli_error($mysqli_link));

		while($row1 = mysqli_fetch_array($mysqli_result1))
		{
			$query2 = "SELECT * FROM temp_col_range_";
			$query2 .= "$low";
			$query2 .= "_$high";
			$query2 .= "_2 ";
			$query2 .= "WHERE percent_all >= 0.07 ";
			$query2 .= "ORDER BY count DESC ";

			#echo "$query2<p>";

			$mysqli_result2 = mysqli_query($mysqli_link, $query2) or die (mysqli_error($mysqli_link));

			while($row2 = mysqli_fetch_array($mysqli_result2))
			{
				$query3 = "SELECT * FROM temp_col_range_";
				$query3 .= "$low";
				$query3 .= "_$high";
				$query3 .= "_3 ";
				$query3 .= "WHERE percent_all >= 0.07 ";
				$query3 .= "ORDER BY count DESC ";

				#echo "$query3<p>";

				$mysqli_result3 = mysqli_query($mysqli_link, $query3) or die (mysqli_error($mysqli_link));

				while($row3 = mysqli_fetch_array($mysqli_result3))
				{
					$query4 = "SELECT * FROM temp_col_range_";
					$query4 .= "$low";
					$query4 .= "_$high";
					$query4 .= "_4 ";
					$query4 .= "WHERE percent_all >= 0.07 ";
					$query4 .= "ORDER BY count DESC ";

					#echo "$query4<p>";

					$mysqli_result4 = mysqli_query($mysqli_link, $query4) or die (mysqli_error($mysqli_link));

					while($row4 = mysqli_fetch_array($mysqli_result4))
					{
						$query5 = "SELECT * FROM temp_col_range_";
						$query5 .= "$low";
						$query5 .= "_$high";
						$query5 .= "_5 ";
						$query5 .= "WHERE percent_all >= 0.07 ";
						$query5 .= "ORDER BY count DESC ";

						#echo "$query5<p>";

						$mysqli_result5 = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));

						while($row5 = mysqli_fetch_array($mysqli_result5))
						{
							if ($row1[1] <> $row2[1] AND
								$row2[1] <> $row3[1] AND
								$row3[1] <> $row4[1] AND
								$row4[1] <> $row5[1])
							{
								$percent_all = 0;
								$sum_even = 0;
								$sum_odd = 0;
								$draw = array ($row1[1],$row2[1],$row3[1],$row4[1],$row5[1]);

								$draw_sum = array_sum ($draw);

								foreach ($draw as $val) 
								{ 
									if(!is_int($val/2)) 
									{ 
										$sum_odd++; 
									} 
									else 
									{ 
										$sum_even++; 
									}
								}
								
								if ($draw_sum >= $low AND $draw_sum <= $high AND $sum_even = $even AND $sum_odd=$odd)
								{	
									echo "$count - $row1[1],$row2[1],$row3[1],$row4[1],$row5[1] ($draw_sum,$sum_even,$sum_odd)<br>";
									$count++;

									$percent_all = $row1[3]+$row2[3]+$row3[3]+$row4[3]+$row5[3];	
									
									$query_insert = "INSERT INTO temp_col_range_cover_";
									$query_insert .= "$low";
									$query_insert .= "_$high ";
									$query_insert .= "VALUES ('0', ";
									$query_insert .= "'$row1[1]', ";
									$query_insert .= "'$row2[1]', ";
									$query_insert .= "'$row3[1]', ";
									$query_insert .= "'$row4[1]', ";
									$query_insert .= "'$row5[1]', ";
									$query_insert .= "'$draw_sum', ";
									$query_insert .= "'$sum_even', ";
									$query_insert .= "'$sum_odd', ";
									$query_insert .= "'$percent_all') "; 
									
									echo "$query_insert<p>";	
								
									$mysqli_result_insert = mysqli_query($mysqli_link, $query_insert) or die       
										(mysqli_error($mysqli_link));
								} 
							}
						}
					}
				}
			}
		}

		mysqli_data_seek($mysqli_result1,0);

		die();

		//start table
		#print("<h3>Last $limit draws</h3>\n");
		print("<TABLE BORDER=\"1\">\n");

		//create header row
		print("<TR>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Col$col</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Count</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Percent</center></TD>\n");

		while($row = mysqli_fetch_array($mysqli_result))
		{
			print("<TR>\n");
			print("<TD align=center>$row[0]</TD>\n");
			print("<TD align=center>$row[1]</TD>\n");

			$col_percent = intval(($row[1]/$total_col)*100);

			print("<TD align=center><font size=\"-1\">$col_percent%</font></TD>\n");
			print("</TR>\n");

			$temp_percent = $col_percent/100;

			$query_insert = "INSERT INTO temp_col_range_";
			$query_insert .= "$low";
			$query_insert .= "_$high";
			$query_insert .= "_$col ";
			$query_insert .= "VALUES ('0', ";
			$query_insert .= "'$row[0]', ";
			$query_insert .= "'$row[1]', ";
			$query_insert .= "'$temp_percent') "; 

			#echo "$query_insert<p>";
		
			$mysqli_result_insert = mysqli_query($mysqli_link, $query_insert) or die (mysqli_error($mysqli_link));
		}

		print("</TABLE>\n");

		print "<p>";

	}

	// ------------------------------------------------------------------------
	//start HTML page
	#log_text("$game_name Analyle Start");
	print("<HTML>\n");
	print("<HEAD>\n");
	print("<TITLE>GAF5 $low/$high</TITLE>\n");
	print("</HEAD>\n");
	
	print("<BODY>\n");
	print("<H1>$game_name Column Range $low/$high</H1>\n");
	
	$curr_date = date("Y-m-d");
	$curr_date_explode = explode('-',$curr_date);

	flush();
	ob_flush();

	echo "date_limit =  2015-10-01<p>";

	for ($col = 1; $col <= 5; $col++)
	{
		print_table_col_range($low,$high,$even,$odd,$col,'2015-10-01');
	}

	build_col_range_cover($low,$high,$even,$odd);

	#print "<p><a href=\"lot_test.php\" target=\"_blank\">Open lot_test.php</a></p>"; 

	//close page
	print("</BODY>\n");
	print("</HTML>\n");

?>
