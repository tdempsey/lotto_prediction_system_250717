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
	#$game = 5; // Florida Mega Money
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

	date_default_timezone_set('America/New_York');

	$debug = 1;

	if ($debug)
	{
		ini_set('display_errors', '1');
		ini_set('display_startup_errors', '1');
		error_reporting(E_ALL);
	}

	set_time_limit(0);

	#$combin = $_GET["combin"];

	$sumeo_sum = $_GET["sum"];
	$sumeo_even = $_GET["even"];
	$sumeo_odd = $_GET["odd"];

	$curr_date = date('Y-m-d');
	$currdate = date('ymd');
	
	require ("includes/games_switch.incl");

	require ("includes/mysqli.php");
	#require ("includes/db.class");
	require ('includes/db.class.php');

	require ("includes/even_odd.php");
	require ("includes/build_rank_table.php");
	require ("includes/calculate_draw.php"); 
	require ("includes/calculate_rank.php");
	require ("includes/first_draw_unix.php");
	require ("includes/last_draw_unix.php");
	require ("includes/last_draws.php");
	require ("includes/next_draw.php");
	#require ("includes_ga_f5/combin.incl");
	require_once ("$game_includes/calc_devsq.php");
	require ("includes/calculate_50_50.php");
	require ("includes/calculate_drange4.php");
	require ("includes/calculate_drange5.php");
	require ("includes/calculate_drange6.php");
	require ("includes/calculate_drange7.php");
	require ("includes/calculate_drange8.php");
	require ("includes/calculate_drange9.php");
	require ("includes/calculate_drange10.php");
	require ("includes/display_sumeo.php");
	#require ("includes_ga_f5/split_draws_2.php");
	require ("includes_ga_f5/split_sumeo_2_no_tables.php");
	require ("includes_ga_f5/split_sumeo_3_no_tables.php");
	require ("includes_ga_f5/split_sumeo_4_no_tables.php");
	require ("includes_ga_f5/split_sumeo_5_no_tables.php");
	require ("includes_ga_f5/update_combo_sumeo_5.php");
	require ("includes/print_column_test_sumeo_no_tables.php"); #200915
	#require ("includes_ga_f5/update_combo_sumeo_4.php");
	#require ("includes_ga_f5/update_combo_sumeo_3.php");
	#require ("includes_ga_f5/update_combo_sumeo_2.php");
	require ("includes_ga_f5/combin_sumeo.incl");

	require_once ("includes/dateDiffInDays.php");
	require_once ("includes/unix.incl");
	require_once ("includes/print_sumeo_drange.incl");	###220413

	date_default_timezone_set('America/New_York');

	require_once ("includes/hml_switch.incl");	

	// ----------------------------------------------------------------------------------
	function print_sum_grid_sum4_sumeo_no_tables($combin,$sumeo_sum,$sumeo_even,$sumeo_odd)
	{
		global $draw_table_name, $balls, $balls_drawn, $draw_prefix, $col1_select, $hml, $range_low, $range_high; 

		require ("includes/mysqli.php");
	
		require ("includes/calculate_sum_count_sum4_sumeo.incl");

		#require ("includes/print_sum_table_sum4_sumeo.incl");

		#require ("includes/print_sum_table_sum_top25_4_sumeo.incl");

		#print "<h3>Table <font color=\"#ff0000\">{$draw_prefix}sum $combin</font> Updated!</h3>";
	}

	// ----------------------------------------------------------------------------------
	function print_sum_grid_sum3_sumeo_no_tables($combin,$sumeo_sum,$sumeo_even,$sumeo_odd)
	{
		global $draw_table_name, $balls, $balls_drawn, $draw_prefix, $col1_select, $hml, $range_low, $range_high; 

		require ("includes/mysqli.php");
	
		require ("includes/calculate_sum_count_sum3_sumeo.incl");

		#require ("includes/print_sum_table_sum3_sumeo.incl");

		#require ("includes/print_sum_table_sum_top25_4_sumeo.incl");

		#print "<h3>Table <font color=\"#ff0000\">{$draw_prefix}sum $combin</font> Updated!</h3>";
	}

	// ----------------------------------------------------------------------------------
	function print_sum_grid_sum2_sumeo_no_tables($combin,$sumeo_sum,$sumeo_even,$sumeo_odd)
	{
		global $draw_table_name, $balls, $balls_drawn, $draw_prefix, $col1_select, $hml, $range_low, $range_high; 

		require ("includes/mysqli.php");
	
		require ("includes/calculate_sum_count_sum2_sumeo.incl");

		#require ("includes/print_sum_table_sum2_sumeo.incl");

		#require ("includes/print_sum_table_sum_top25_4_sumeo.incl");

		#print "<h3>Table <font color=\"#ff0000\">{$draw_prefix}sum $combin</font> Updated!</h3>";
	}

	//start HTML page
	print("<HTML>\n");
	print("<HEAD>\n");
	print("<TITLE>$game_name Update Split - $game_name</TITLE>\n");
	print("</HEAD>\n");
	
	print("<BODY>\n");

	print "<h3><a href=\"#26\">Limit 26</a> | <a href=\"#5000\">Limit 5000</a></h3>";

	################################################################################################

	$curr_date = date('Y-m-d');

	$date = strtotime($curr_date);
    $date = strtotime("-1 day", $date);
    $last_updated = date('Y-m-d', $date);

	#echo "$last_updated<br>";

	$date = explode('-', $last_updated);

	$lastupdated = $date[0];
	$lastupdated .= $date[1];
	$lastupdated .= $date[2];

	$sum = $sumeo_sum;
	$even = $sumeo_even;
	$odd = $sumeo_odd;

	$sumeo = 5; ### <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<

	echo "<h2>*** SumEO = $sum,$even,$odd - sumeo $sumeo</h2>";

	echo "fix lot_display_sumeo<p>";

	#lot_display_sumeo($sumeo_sum,$sumeo_even,$sumeo_odd);

	#test_combin_print_all_sumeo(100,$sumeo_sum,$sumeo_even,$sumeo_odd);

	#print_draw_summary($sum,$even,$odd);
	/*
	require ("includes/print_sum_table_sumeo.incl");

	require ("includes/print_sumeo_table_drange2_sumeo.incl");

	print_sumeo_drange2_sumeo($sumeo_sum,$sumeo_even,$sumeo_odd,$drange=2)

	require ("includes/print_sumeo_table_drange3_sumeo.incl");

	require ("includes/print_sumeo_table_drange4_sumeo.incl");

	require ("includes/print_sumeo_table_drange5_sumeo.incl");

	require ("includes/print_sumeo_table_drange6_sumeo.incl");

	require ("includes/print_sumeo_table_drange7_sumeo.incl");

	require ("includes/print_sumeo_table_drange8_sumeo.incl");
	*/
	#die();

	echo "<h2>print_column_test_sumeo_no_tables</h2>";

	for ($col = 1; $col <= 5; $col++)
	{
		print_column_test_sumeo_no_tables($col, $sumeo_sum, $sumeo_even, $sumeo_odd); #200915
	}

	split_sumeo_5_no_tables ($sum,$even,$odd);

	split_sumeo_4_no_tables ($sum,$even,$odd);

	split_sumeo_3_no_tables ($sumeo_sum,$sumeo_even,$sumeo_odd);

	split_sumeo_2_no_tables ($sumeo_sum,$sumeo_even,$sumeo_odd);

	for ($s = 1; $s <= 5; $s++)
	{
		print_sum_grid_sum4_sumeo_no_tables($combin=$s,$sumeo_sum,$sumeo_even,$sumeo_odd);
	}

	for ($s = 1; $s <= 10; $s++)
	{
		print_sum_grid_sum3_sumeo_no_tables($combin=$s,$sumeo_sum,$sumeo_even,$sumeo_odd);
	}

	for ($s = 1; $s <= 10; $s++)
	{
		print_sum_grid_sum2_sumeo_no_tables($combin=$s,$sumeo_sum,$sumeo_even,$sumeo_odd);
	}

	#print "<a href=\"lot_analyze.php\" target=\"_blank\">Open lot_analyze.php</a>"; 

	#######################################################################################################################################
	function print_draw_summary($sum,$even,$odd)
	{
		global $draw_table_name, $balls, $balls_drawn, $draw_prefix, $col1_select, $hml, $range_low, $range_high, $game; 

		#echo "print_draw_summary - $sum,$even,$odd<br>";

		require ("includes/unix.incl");

		require ("includes/mysqli.php");
	
		require ("includes/calculate_draw_summary_sumeo.incl");

		require ("includes/print_draw_summary_sumeo.incl");

		echo "add range & summary<br>";

		#print "<h3>Table <font color=\"#ff0000\">{$draw_prefix}sum</font> Updated!</h3>";
	}
	#######################################################################################################################################

	$temp_table1 = 'temp_cover_1k_count_' .  $currdate;

	# select 1k_count for each sumeo
	$query3 = "SELECT DISTINCT sum,even,odd,k_count FROM $temp_table1 ";
	$query3 .= "WHERE sum=$sumeo_sum AND even=$sumeo_even AND odd=$sumeo_odd ";	### 240529
	#$query3 .= "ORDER BY `k_count` DESC  ";

	echo "<p>$query3</p>";

	$mysqli_result3 = mysqli_query($mysqli_link, $query3) or die (mysqli_error($mysqli_link));

	while($row3 = mysqli_fetch_array($mysqli_result3))	###############################################################
	{
		echo "$row3[sum], $row3[even], $row3[odd] - $row3[k_count]<br>";

		# build draws table based on sumeos

		# 1 - build temp draw table for sumeo

		$temp_table3 = 'temp_sumeo_draw_' . $row3['sum'] . '_' . $row3['even'] . '_' . $row3['odd'];

		$query4 = "DROP TABLE IF EXISTS $temp_table3 ";

		echo "<p>$query4</p>";

		$mysqli_result4 = mysqli_query($mysqli_link, $query4) or die (mysqli_error($mysqli_link));

		$query4 =  "CREATE TABLE $temp_table3 LIKE combo_5_42 ";

		echo "<p>$query4</p>";

		$mysqli_result4 = mysqli_query($mysqli_link, $query4) or die (mysqli_error($mysqli_link));

		if ($row3['sum'] < 80)
		{
			$query_dc = "SELECT * FROM `ga_f5_draw_summary_by_sumeo2` 
							WHERE `sum` = $row3[sum] AND `even` = $row3[even] AND `odd` = $row3[odd]
							ORDER BY `percent_wa` DESC";	###240326
		} else {
			$query_dc = "SELECT * FROM `ga_f5_draw_summary_by_sumeo2` 
							WHERE `sum` = $row3[sum] AND `even` = $row3[even] AND `odd` = $row3[odd]
							AND year1 > 0 AND percent_wa >= 0.1 ORDER BY `percent_wa` DESC";	###240326
		}

		echo "$query_dc<br>";

		$mysqli_result_dc = mysqli_query($mysqli_link, $query_dc) or die (mysqli_error($mysqli_link));

		$num_rows_dc = mysqli_num_rows($mysqli_result_dc);

		if (!$num_rows_dc)
		{
			echo "no num_rows_dc<br>";
			#die();
		} else {
			while($row_dc = mysqli_fetch_array($mysqli_result_dc))
			{
				$query4 = "INSERT INTO $temp_table3 SELECT * FROM combo_5_42 WHERE sum = $row3[sum] AND even = $row3[even] AND odd = $row3[odd]
				AND seq2 <= 1 AND seq3 = 0 AND mod_tot <= 1 AND mod_x = 0
				AND d0 = $row_dc[d0] AND d1 = $row_dc[d1] AND d2 = $row_dc[d2] AND d3 = $row_dc[d3] AND d4 = $row_dc[d4] ";	###240326

				echo "<p>temp_table3 - $query4</p>";

				$mysqli_result4 = mysqli_query($mysqli_link, $query4) or die (mysqli_error($mysqli_link));
			}
		}
	}
	
	print("</BODY>\n");
?>
