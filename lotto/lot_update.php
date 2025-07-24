<?php

	ini_set('implicit_flush', 1);

	// add tables population for combos, pairs
	// add test for missing draws
	// recalculate draw includes
	// add db class
	// error checking - all modules
	// fix pair count
	#CREATE TABLE recipes_new LIKE production.recipes;
	#INSERT INTO recipes_new SELECT * FROM production.recipes;
	#$random_row = mysqli_fetch_row(mysqli_query("select * from YOUR_TABLE order by rand() limit 1"));
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
	#$hml = 110;	

	set_time_limit(0);

	$debug = 0;

	if ($debug)
	{
		ini_set('display_errors', '1');
		ini_set('display_startup_errors', '1');
		error_reporting(E_ALL);
	}

	echo "start<br>";
	
	require ("includes/games_switch.incl");

	require ("includes/mysqli.php");

	// ----------------------------------------------------------------------------------
	function update_counts($count,$draw_date) 
	{
		require ("includes/mysqli.php"); 

		global $debug, $game, $draw_prefix, $draw_table_name, $balls, $balls_drawn, $eo50_sum, $eo50_even, $eo50_odd, $eo50_d2_1, $eo50_d2_2;

		$table_temp = $draw_prefix . $count . "_" . $balls;

		$query_up1 = "SELECT * FROM $draw_table_name ";
		$query_up1 .= "WHERE date >= '$draw_date' ";
		$query_up1 .= "ORDER BY date ASC "; 

		print "$query_up1<p>";

		$mysqli_result_up1 = mysqli_query($mysqli_link, $query_up1) or die (mysqli_error($mysqli_link));

		$temp_include = "combin_" . $count . "_" . $balls_drawn . ".incl";

		### check for hml count and update
		while($row = mysqli_fetch_array($mysqli_result_up1))
		{
			$query5_up1 = "SELECT * FROM $table_temp ";
			$query5_up1 .= "WHERE date = '$row[0]' ";
			$query5_up1 .= "AND hml = 0 "; # = all

			echo "$query5_up1<br>";

			echo "query5_up1 --- checking ---<br>";

			$mysqli_result5_up1 = mysqli_query($mysqli_link, $query5_up1) or die (mysqli_error($mysqli_link));

			if (!$num_rows = mysqli_num_rows($mysqli_result5_up1))
			{
				echo "--- updating includes/$temp_include---<br>";
				require ("includes/$temp_include");
			}
		}


		print "<h3>Table <font color=\"#ff0000\">$table_temp</font> Updated!</h3>";
	}

	// ----------------------------------------------------------------------------------
	function update_counts_hml($count,$draw_date) 
	{
		require ("includes/mysqli.php"); 

		global $debug, $game, $draw_prefix, $draw_table_name, $balls, $balls_drawn, $eo50_sum, $eo50_even, $eo50_odd, $eo50_d2_1, $eo50_d2_2;

		$table_temp = $draw_prefix . $count . "_" . $balls;

		$query = "SELECT * FROM $draw_table_name ";
		$query .= "WHERE date >= '$draw_date' ";
		$query .= "ORDER BY date ASC "; 

		#print "$query<p>";

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

		$temp_include = "combin_" . $count . "_" . $balls_drawn . "_hml" . ".incl";

		### check for hml count and update
		while($row = mysqli_fetch_array($mysqli_result))
		{
			$hml = (intval($row[sum]/10)) * 10; # = decade

			$query5 = "SELECT * FROM $table_temp ";
			$query5 .= "WHERE date = '$row[0]' ";
			$query5 .= "AND hml = $hml ";

			echo "$query5<br>";

			#echo "$query5 --- checking ---<br>";

			$mysqli_result5 = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));

			if (!$num_rows = mysqli_num_rows($mysqli_result5))
			{
				#echo "--- updating ---<br>";
				require ("includes/$temp_include");
			}
		}

		print "<h3>Table <font color=\"#ff0000\">$table_temp hml=$hml</font> Updated!</h3>";
	}

	// ----------------------------------------------------------------------------------
	function update_counts_hml_sum($count,$draw_date) 
	{
		require ("includes/mysqli.php"); 

		global $debug, $game, $draw_prefix, $draw_table_name, $balls, $balls_drawn, $eo50_sum, $eo50_even, $eo50_odd, $eo50_d2_1, $eo50_d2_2;

		$table_temp = $draw_prefix . $count . "_" . $balls;

		$row = array(); #200914

		$query = "SELECT * FROM $draw_table_name ";
		$query .= "WHERE date >= '$draw_date' ";
		$query .= "ORDER BY date ASC "; 

		#print "$query<p>";

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

		$temp_include = "combin_" . $count . "_" . $balls_drawn . "_hml" . ".incl";

		### check for hml count and update
		while($row = mysqli_fetch_array($mysqli_result))
		{
			$hml = $row[sum] + 500; # = sum

			$query5 = "SELECT * FROM $table_temp ";
			$query5 .= "WHERE date = '$row[0]' ";
			$query5 .= "AND hml = $hml ";

			#echo "$query5 --- checking ---<br>";

			$mysqli_result5 = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));

			if (!$num_rows = mysqli_num_rows($mysqli_result5))
			{
				#echo "--- updating ---<br>";
				require ("includes/$temp_include");
			}
		}

		print "<h3>Table <font color=\"#ff0000\">$table_temp hml=$hml</font> Updated!</h3>";
	}
	
	#########################################################################################################
	/*
	$table_temp = $draw_prefix . "2_" . $balls;

	$query2 = "SELECT * FROM $table_temp ";
	$query2 .= "WHERE hml <> 0 ";
	$query2 .= "AND date >= '2015-10-01' ";
	$query2 .= "ORDER BY date DESC ";

	print "$query2<p>";

	$mysqli_result2 = mysqli_query($mysqli_link, $query2) or die (mysqli_error($mysqli_link));

	$row_date = mysqli_fetch_array($mysqli_result2);

	$row_date[1] = "1962-08-16"; ### 201223 date

	update_counts(2,'2021-10-01');

	update_counts_hml(2,'2021-10-01');

	update_counts_hml_sum(2,'2021-10-01');
	*/
	#########################################################################################################

	$table_temp = $draw_prefix . "3_" . $balls;

	$query3 = "SELECT * FROM $table_temp ";
	$query3 .= "WHERE hml <> 0 ";
	$query3 .= "AND date >= '2015-10-01' ";
	$query3 .= "ORDER BY date DESC ";

	print "$query3<p>";

	$mysqli_result3 = mysqli_query($mysqli_link, $query3) or die (mysqli_error($mysqli_link));

	$row_date = mysqli_fetch_array($mysqli_result3);

	$row_date[1] = "1962-08-16"; ### 201223 date

	update_counts(3,'2021-10-01');

	update_counts_hml(3,'2021-10-01');

	update_counts_hml_sum(3,'2021-10-01');

	#########################################################################################################

	$table_temp = $draw_prefix . "4_" . $balls;

	$query4 = "SELECT * FROM $table_temp ";
	$query4 .= "WHERE hml <> 0 ";
	$query4 .= "AND date >= '2015-10-01' ";
	$query4 .= "ORDER BY date DESC ";

	print "$query4<p>";

	$mysqli_result4 = mysqli_query($mysqli_link, $query4) or die (mysqli_error($mysqli_link));

	$row_date = mysqli_fetch_array($mysqli_result4);

	$row_date[1] = "1962-08-16"; ### 201223 date

	update_counts(4,'2021-10-01');

	update_counts_hml(4,'2021-10-01');

	update_counts_hml_sum(4,'2021-10-01');

	#########################################################################################################

	echo "entering update_drange2.php<br>";
	require ("update_drange2.php");
	echo "exiting update_drange2.php<br>";

	echo "entering update_drange3.php<br>";
	require ("update_drange3.php");
	echo "exiting update_drange3.php<br>";

	echo "entering update_drange4.php<br>";
	require ("update_drange4.php");
	echo "exiting update_drange4.php<br>";

	echo "entering update_drange5.php<br>";
	require ("update_drange5.php");
	echo "exiting update_drange5.php<br>";

	echo "entering update_drange6.php<br>";
	require ("update_drange6.php");
	echo "exiting update_drange6.php<br>";

	echo "entering update_drange7.php<br>";
	require ("update_drange7.php");
	echo "exiting update_drange7.php<br>";

	echo "entering update_drange8.php<br>";
	require ("update_drange8.php");
	echo "exiting update_drange8.php<br>";

	echo "entering update_drange9.php<br>";
	require ("update_drange9.php");
	echo "exiting update_drange9.php<br>";

	print "<a href=\"lot_analyze.php\" target=\"_blank\">Open lot_analyze.php</a>"; 
	
	print("</BODY>\n");
?>
