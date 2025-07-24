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

	$debug = 1;

	if ($debug)
	{
		ini_set('display_errors', '1');
		ini_set('display_startup_errors', '1');
		error_reporting(E_ALL);
	}

	set_time_limit(0);

	$combin = $_GET["combin"];

	$sumeo_sum = $_GET["sum"];
	$sumeo_even = $_GET["even"];
	$sumeo_odd = $_GET["odd"];
	
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
	require ("includes/display.php");
	#require ("includes_ga_f5/split_draws_2.php");
	require ("includes_ga_f5/split_sumeo_2.php");
	require ("includes_ga_f5/split_sumeo_3.php");
	require ("includes_ga_f5/split_sumeo_4.php");
	require ("includes_ga_f5/split_sumeo_5.php");
	require ("includes_ga_f5/update_combo_sumeo_5.php");
	require ("includes/print_column_test_sumeo.php"); #200915
	#require ("includes_ga_f5/update_combo_sumeo_4.php");
	#require ("includes_ga_f5/update_combo_sumeo_3.php");
	#require ("includes_ga_f5/update_combo_sumeo_2.php");
	require ("includes_ga_f5/combin_sumeo.incl");

	require ("includes/dateDiffInDays.php");
	require ("includes/unix.incl");

	date_default_timezone_set('America/New_York');

	require_once ("includes/hml_switch.incl");	
	
	$debug = 0;

	//start HTML page
	print("<HTML>\n");
	print("<HEAD>\n");
	print("<TITLE>$game_name SumEO Split - $game_name</TITLE>\n");
	print("</HEAD>\n");
	
	print("<BODY>\n");

	print "<h3><a href=\"#26\">Limit 26</a> | <a href=\"#5000\">Limit 5000</a></h3>";

	################################################################################################

	$curr_date = date('Y-m-d');

	$date = strtotime($curr_date);
    $date = strtotime("-1 day", $date);
    $last_updated = date('Y-m-d', $date);

	echo "$last_updated<br>";

	$date = explode('-', $last_updated);

	$lastupdated = $date[0];
	$lastupdated .= $date[1];
	$lastupdated .= $date[2];

	$sum = $sumeo_sum;
	#$row3[numx] = $sumeo_sum;	###220213
	$even = $sumeo_even;
	#$row3[even] = $sumeo_even;	###220213
	$odd = $sumeo_odd;
	#$row3[odd] = $sumeo_odd;	###220213

	$sumeo = 5; ### <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<

		echo "<h2>*** SumEO = $sum,$even,$odd = sumeo $sumeo</h2>";

		test_combin_print_all_sumeo(100,$sumeo_sum,$sumeo_even,$sumeo_odd);

		die();

		for ($col = 1; $col <= 5; $col++)
		{
			#echo "### print_column_test_sumeo ###<br>";
			print_column_test_sumeo($col, $sumeo_sum, $sumeo_even, $sumeo_odd); #200915
		}

		echo "### 1 ###<br>";
		
		if (1) #changeme
		{
			split_sumeo_5($sumeo_sum,$sumeo_even,$sumeo_odd);   

			#die();

			update_combo_sumeo_5($sumeo_sum,$sumeo_even,$sumeo_odd);
		}   

		#die();

		echo "### 2 ###<br>";
		
		#split_sumeo_4 ($row3[numx],$row3[even],$row3[odd]); #from #1
		/*
		for ($g = 1; $g <= 5; $g++)
		{
			$combin = $g;

			require ("includes_ga_f5/print_sumeo_split_4.incl");
		}
		
		echo "### 3 ###<br>";

		split_sumeo_3 ($sum,$even,$odd); #from #1

		echo "<h2>Combin 1</h2>";
		for ($g = 1; $g <= 1; $g++)
		{
			$combin = $g;

			require ("includes_ga_f5/print_sumeo_split_3_stacked.incl");
		}

		echo "<h2>Combin 5</h2>";
		for ($g = 5; $g <= 5; $g++)
		{
			$combin = $g;

			require ("includes_ga_f5/print_sumeo_split_3_stacked.incl");
		}

		die();

		echo "### 4 ###<br>";

		#split_sumeo_2 ($row3[numx],$row3[even],$row3[odd]); #from #1

		for ($g = 1; $g <= 10; $g++)
		{
			$combin = $g;

			#require ("includes_ga_f5/print_sumeo_split_2.incl");
		}

		*/

		require ("includes_ga_f5/print_sumeo_5.incl");

	die();

	#split_draws_4 (10000);

	echo "### 3 ###<br>";

	#lot_display4 (10000);

	die();
	
	split_draws_4 (1470);
	
	lot_display4 (10);

	lot_display4 (14);

	lot_display4 (26);

	lot_display4 (28);
	
	lot_display4 (30);

	split_draws_4 (1470);

	lot_display4 (1470); #190704

	split_draws_4 (10000);
	
	lot_display4 (10000);

	#split_draws_3 (10000);

	#split_draws_2 (10000);
	
	#lot_display4_4 (5);
	
	for ($x = 2; $x < $balls_drawn; $x++)
	{
		if ($game == 7)
		{
			$table_temp = $draw_prefix . $x. "_75";
		} else {
			$table_temp = $draw_prefix . $x. "_" . $balls;
		}

		$query = "SELECT * FROM $table_temp ";
		$query .= "WHERE hml <> 0 ";
		$query .= "ORDER BY date DESC ";

		print "$query<p>";

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

		$row_date = mysqli_fetch_array($mysqli_result);

		$row_date[date] = "1962-08-16";

		#echo "update_counts $row_date[date]<br>";
		update_counts($x,'2019-06-10');

		#update_counts_hml($x,$row_date[date]);
		#echo "update_counts 2019-05-01<br>";
		update_counts_hml($x,'2019-06-10');

		#update_counts_hml_sum($x,$row_date[date]);
		#echo "update_counts2019-05-01<br>";
		update_counts_hml_sum($x,'2019-06-10');
	}

	print "<a href=\"lot_analyze.php\" target=\"_blank\">Open lot_analyze.php</a>"; 
	
	print("</BODY>\n");
?>
