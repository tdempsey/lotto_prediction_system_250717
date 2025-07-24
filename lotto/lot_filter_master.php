<?php

	// add tables population for combos, pairs
	// add test for missing draws
	// recalculate draw includes
	// add db class
	// error checking - all modules
	// fix pair count
	
	# filters - sum,even,odd,d501,d502,mod,modx,seq2,seq3

	// Game ----------------------------- Game
	$game = 1; // Georgia Fantasy 5
	#$game = 2; // Mega Millions
	//$game = 3; // Georgia Lotto South
	#$game = 4; // Florida Fantasy 5
	//$game = 5; // Florida Mega Money
	#$game = 6; // Florida Lotto
	#$game = 7; // Powerball
	// Game ----------------------------- Game
	
	require ("includes/games_switch.incl");

	date_default_timezone_set('America/New_York');

	$hml = 0;
	#$hml = 1;		#= high
	#$hml = 2;		#= medium
	#$hml = 3;		#= low
	#$hml = 4;		#= min
	#$hml = 5;		#= max
	#$hml = 130;		

	require_once ("includes/hml_switch.incl");
	
	$debug = 0;

	//require ("includes/mysqli.php");
	require ("includes/db.class");
	require ("includes/even_odd.php");
	require ("includes/calculate_50_50.php");
	require ("includes/build_rank_table.php");
	require ("includes/calculate_draw.php"); 
	require ("includes/calculate_rank.php");
	require ("includes/first_draw_unix.php");
	require ("includes/last_draw_unix.php");
	require ("includes/next_draw.php");
	require ("includes/count_2_seq.php");
	require ("includes/count_3_seq.php");
	require ("includes_ga_f5/last_draws_ga_f5.php"); # fix
	require ("$game_includes/combin.incl");
	require ("includes/mysqli.php");
	require ("includes/draw_count_total.php");
	require ("includes/x10.php");
	require ("includes/count_mod.php");

	require ("includes/limit_functions.incl");

	date_default_timezone_set('America/New_York');

	### verify draw data
/*
	### run display.php
	require ("includes/master_display.incl");

	### run analysis.php
	require ("includes/master_analyze.incl");

	### run test.php
	require ("includes/master_test.incl");
	
	### update combo tables
	require ("includes_ga_f5/master_combo.incl");

	### update limits.php
	require ("includes/master_limits.incl");

	### update limits_decades.php
	require ("includes/master_limits_decades.incl");

	### update limit_sums.php --- once a week

	### run filter a - add reject tables
	require ("includes_ga_f5/master_filter_a.incl");

	### run filter b - add reject tables
	require ("includes_ga_f5/master_filter_b.incl");
	

	### run filter c - add reject tables
	require ("includes_ga_f5/master_filter_c.incl");
	*/

	### run filter d - add reject tables
	require ("includes_ga_f5/master_filter_d.incl");

	### update filter d tables

	### calculate rank limits

	### build dup_run table

	### build combo_run table

	### build sum_table

	### build profile_draws

	### filter_report_d - all profiles
		# store draws

	### print draws


	die();
	

	# rrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrr
	
	$rank_array = array(1,1,2,2,2,1,2);

	$dup_array = array(0,0,0,0,0);

	$even_low = 2;
	$even_high = 4;
	$odd_low = 1;
	$odd_high = 3;
	$d501_low = 2;
	$d501_high = 3;
	$d502_low = 2;
	$d502_high = 3;

	for ($x = 1; $x <= 10; $x++)
	{
		lot_filter_summary ($col1=$x,$comb4=1); # using 1 and 2 override

		$query = "SELECT DISTINCT `d1`,`d2`,`combo`,`combo_count`\n"
			 . "FROM `ga_f5_2_39_b` \n"
			 . "WHERE combo = 1\n"
			 . "ORDER BY `ga_f5_2_39_b`.`combo_count` DESC LIMIT 10";

		print "$query<p>";

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

		while($row = mysqli_fetch_array($mysqli_result))
		{
			echo "2 - $row[d1]-$row[d2] <b>count=$row[combo_count]</b><br>";
		}

		$query = "SELECT DISTINCT `d1`,`d2`,`d3`,`combo`,`combo_count`\n"
			 . "FROM `ga_f5_3_39_b` \n"
			 . "WHERE combo = 1\n"
			 . "ORDER BY `ga_f5_3_39_b`.`combo_count` DESC LIMIT 10";

		echo "<p>";
		print "$query<p>";

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

		while($row = mysqli_fetch_array($mysqli_result))
		{
			echo "<p>3 - $row[d1]-$row[d2]-$row[d3] <b>count=$row[combo_count]</b><br>";

			$query_temp = "SELECT * FROM filter_b_temp ";
			$query_temp .= "WHERE b1 = $row[d1] ";
			$query_temp .= "AND   b2 = $row[d2] ";
			$query_temp .= "AND   b3 = $row[d3] ";
			$query_temp .= "ORDER BY wa DESC ";

			print "$query_temp<p>";

			$mysqli_result_temp = mysqli_query($mysqli_link, $query_temp) or die (mysqli_error($mysqli_link));

			print "<table border=1>";

			while($row_temp = mysqli_fetch_array($mysqli_result_temp))
			{
				 print "<tr>";
				 print "<td>$row_temp[b1]</td>";
				 print "<td>$row_temp[b2]</td>";
				 print "<td>$row_temp[b3]</td>";
				 print "<td>$row_temp[b4]</td>";
				 print "<td>$row_temp[b5]</td>";
				 print "<td><b>$row_temp[wa]</b></td>";
				 print "</tr>";
			}

			print "</table>";
		}

		$query = "SELECT DISTINCT `d1`,`d2`,`d3`,`d4`,`combo`,`combo_count`\n"
			 . "FROM `ga_f5_4_39_b` \n"
			 . "WHERE combo = 3\n"
			 . "ORDER BY `ga_f5_4_39_b`.`combo_count` DESC LIMIT 10";

		echo "<p>";
		print "$query<p>";

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

		while($row = mysqli_fetch_array($mysqli_result))
		{
			echo "<p>4 - $row[d1]-$row[d2]-$row[d3]-$row[d4] <b>count=$row[combo_count]</b><br>";
			
			$query_temp = "SELECT * FROM filter_b_temp ";
			$query_temp .= "WHERE b1 = $row[d1] ";
			$query_temp .= "AND   b2 = $row[d2] ";
			$query_temp .= "AND   b4 = $row[d3] ";
			$query_temp .= "AND   b5 = $row[d4] ";
			$query_temp .= "ORDER BY wa DESC ";

			print "$query_temp<p>";

			$mysqli_result_temp = mysqli_query($mysqli_link, $query_temp) or die (mysqli_error($mysqli_link));

			print "<table border=1>";
			print "<tr>";

			while($row_temp = mysqli_fetch_array($mysqli_result_temp))
			{
				 print "<tr>";
				 print "<td>$row_temp[b1]</td>";
				 print "<td>$row_temp[b2]</td>";
				 print "<td>$row_temp[b3]</td>";
				 print "<td>$row_temp[b4]</td>";
				 print "<td>$row_temp[b5]</td>";
				 print "<td><b>$row_temp[wa]</b></td>";
				 print "</tr>";
			}

			print "</table>";
		}
	}

	print("</BODY>\n");
?>




