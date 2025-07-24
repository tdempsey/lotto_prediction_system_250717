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
	#$game = 1; // Georgia Fantasy 5
	#$game = 2; // Mega Millions
	//$game = 3; // Georgia 5
	#$game = 4; // Jumbo
	#$game = 5; // Florida Mega Money
	#$game = 6; // Florida Lotto
	$game = 7; // Powerball
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

	echo "1<br>";

	require ("includes/mysqli.php");

	echo "2<br>";

	/*
	require ($_SERVER['DOCUMENT_ROOT'].'/lotto/includes/mysqli.php');

	$mysqli_link = mysqli_connect('localhost', 'root', 'wef5esuv', 'pb_lotto');

	if (!$mysqli_link) {
		die('Connect Error (' . mysqli_connect_errno() . ') '
				. mysqli_connect_error());
	}

	echo 'Success... ' . mysqli_get_host_info($mysqli_link) . "\n";
	#require ("includes/db.class");
	require ('includes/db.class.php');
	*/

	require ("includes/even_odd.php");
	if ($game == 10 OR $game == 20)
	{
		require ("includes_aon/build_rank_table_aon.php");
		require ("includes_aon/combin.incl");
	} else {
		require ("includes_pb/build_rank_table.php");
	}
	require ("includes/calculate_draw.php"); 
	require ("includes_pb/calculate_rank.php");
	require ("includes/first_draw_unix.php");
	require ("includes/last_draw_unix.php");
	require ("includes/next_draw.php");

	echo "3<br>";
	/*
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
	#require ("includes_pb/split_draws_2.php");
	#require ("includes_pb/split_draws_3.php");
	require ("includes_pb/split_draws_4.php");
	require ("includes/dateDiffInDays.php");
	require ("includes/unix.incl");
	*/

	require ("includes_pb/split_draws_2.php");
	require ("includes_pb/split_draws_3.php");
	require ("includes_pb/split_draws_4.php");

	require ("includes_pb/display.php");
	
	require ("includes/dateDiffInDays.php");
	echo "dateDiffInDays.php<br>";
	require ("includes/unix.incl");
	echo "unix.incl<br>";
	#require ("includes/log_text.incl");

	echo "4<br>";

	date_default_timezone_set('America/New_York');

	require_once ("includes/hml_switch.incl");	
	
	$debug = 0;

	require ("includes/display_include.incl");

	//start HTML page
	print("<HTML>\n");
	print("<HEAD>\n");
	print("<TITLE>$game_name Display - $game_name</TITLE>\n");
	print("</HEAD>\n");
	
	print("<BODY>\n");

	print "<h3><a href=\"#26\">Limit 26</a> | <a href=\"#5000\">Limit 5000</a></h3>";

	#log_text("$game_name Display Start");

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

	echo "$lastupdated<br>";

	$drop_tables = 0;
	
	if ($drop_tables)
	{
		$profile_table = "combo_5_69_updated_";
		$profile_table .= "$lastupdated";

		$query = "DROP TABLE IF EXISTS $profile_table ";

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

		$query =  "CREATE TABLE IF NOT EXISTS $profile_table LIKE combo_5_69" ;

		print("$query<p>");

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

		$query = "INSERT INTO $profile_table ";
		$query .= "(SELECT * FROM combo_5_69  ";
		$query .= "WHERE last_updated = '$last_updated') ";

		print "$query<br>";
		
		$mysqli_result2 = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));
	}

	################################################################################################

	#split_draws_4 (10000);

	#split_draws_3 (10000);

	#split_draws_2 (10000);

	#die();

	flush();
	ob_flush();

	#lot_display ($dateDiff); #190704
	
	#die();

	echo "lot_display (5)<br>";

	#lot_display ($dateDiff); #190704

	#die();

	#lot_display (5);

	#die();
	
	lot_display (7);

	lot_display (14);

	lot_display (21);

	#lot_display (28);
	
	lot_display (30);
	
	lot_display ($dateDiff); #190704
	
	#die();

	#lot_display (10000);

	#split_draws_4 (10000);

	#split_draws_3 (10000);

	#split_draws_2 (10000);
	
	#lot_display_4 (5);
	
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
		$query .= "AND date >= '1999-10-01' ";
		$query .= "ORDER BY date DESC ";

		print "$query<p>";

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

		$row_date = mysqli_fetch_array($mysqli_result);

		$row_date[1] = "1962-08-16"; ### 201223 date

		#echo "update_counts $row_date[date]<br>";
		update_counts($x,'2020-06-01');

		#update_counts_hml($x,$row_date[date]);
		#echo "update_counts 2019-05-01<br>";
		update_counts_hml($x,'2020-06-01');

		#update_counts_hml_sum($x,$row_date[date]);
		#echo "update_counts2019-05-01<br>";
		update_counts_hml_sum($x,'2020-06-01');
	}

	for ($z = 2; $z < 10; $z++)
	{
		$temp_drange = 'update_drange' . $z . '.php';
		echo "temp_drange = $temp_drange<br>";
		require ("$temp_drange");
	}

	print "<a href=\"lot_analyze.php\" target=\"_blank\">Open lot_analyze.php</a>"; 
	
	print("</BODY>\n");
?>
