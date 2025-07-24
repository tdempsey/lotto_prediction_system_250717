<?php

	// add tables population for combos, pairs
	// add test for missing draws
	// recalcu;ate draw includes
	// add db class
	// error checking - all modules
	// fix pair count

	//////////////////////////////////////////
	//////////// uncomment combin.incl ga f5
	//////////////////////////////////////////

	// Game ----------------------------- Game
	$game = 1; // Georgia Fantasy 5
	//$game = 2; // Georgia Mega Millions
	//$game = 3; // Georgia Lotto South
	#$game = 4; // Florida Fantasy 5
	//$game = 5; // Florida Mega Money
	//$game = 6; // Florida Lotto
	#$game = 7; // Powerball
	// Game ----------------------------- Game

	set_time_limit(0);
	
	date_default_timezone_set('America/New_York');	
	
	$curr_date = date('Y-m-d');
	
	require ("includes/games_switch.incl");
	require ("includes/last_draws.php");
	require ("includes/calculate_rank.php");
	require ("includes/build_rank_table.php");

	//require ("includes/mysqli.php");
	require ("includes/mysqli.php");
	
	$debug = 1;

	if ($debug)
	{
		ini_set('display_errors', '1');
		ini_set('display_startup_errors', '1');
		error_reporting(E_ALL);
	}

	###################################################################################################################
	function check_dup_rank($draw)
	{
		global $dup_temp, $rank_limit_temp, $rank_count_temp, $last_1_draws, $last_2_draws, $last_3_draws,$last_4_draws, 
			   $dup_pass_fail, $rank_pass_fail;
		
		$dup_pass_fail = 1; 
		$rank_pass_fail = 1;

		// Calculate duplicates in the current draw
		
		### calculate dup_pass_fail ###################################################################################
		$draw_rank_count = array_fill (0, 7, 0);

		// Count matches for each array
		$count_dup1 = count(array_intersect($draw, $last_1_draws));
		$count_dup2 = count(array_intersect($draw, $last_2_draws));
		$count_dup3 = count(array_intersect($draw, $last_3_draws));
		$count_dup4 = count(array_intersect($draw, $last_4_draws));
		
		// Output the results
		echo "<br>Count in dup1: $count_dup1<br>";
		echo "Count in dup2: $count_dup2<br>";
		echo "Count in dup3: $count_dup3<br>";
		echo "Count in dup4: $count_dup4<br><br>";
		
		if ($count_dup1 > 1) {
			$dup_pass_fail = 0; 
			echo "<b>dup_pass_fail1 = $dup_pass_fail</b><br><br>";
		} elseif ($count_dup2 > 2) {
			$dup_pass_fail = 0; 
			echo "<b>dup_pass_fail2 = $dup_pass_fail</b><br><br>";
		} elseif ($count_dup3 > 3) {
			$dup_pass_fail = 0; 
			echo "<b>dup_pass_fail3 = $dup_pass_fail</b><br><br>";
		} elseif ($count_dup4 > 4) {  	
			$dup_pass_fail = 0; 
			echo "<b>dup_pass_fail4 = $dup_pass_fail</b><br><br>";
		}
		
		echo "<b>dup_pass_fail = $dup_pass_fail</b><br><br>";
		
		### calculate rank_pass_fail ##################################################################################
		$rank_draw_temp = array_fill(0,8,0);
		
		// Calculate rank in the current draw
		foreach ($draw as $num) {
			if ($rank_count_temp[$num] > 7) {
				$temp = 7;
				$rank_draw_temp[$temp]++;
				echo "Count in rank_draw_temp[$temp] = $rank_draw_temp[$temp]<br><br>";
			} else {
				$temp = $rank_count_temp[$num];
				$rank_draw_temp[$temp]++;
				echo "Count in rank_draw_temp[$temp] = $rank_draw_temp[$temp]<br><br>";
			}
		}	
		
		echo "rank_draw_temp:<br>";
		print_r($rank_draw_temp);
		echo "<p>";
		
		// Compare rank totals in the current draw
		for ($k = 0; $k <= 7; $k++) {
			if ($rank_draw_temp[$k] > $rank_limit_temp[$k]) {
				$rank_pass_fail = 0; 
				echo "<b>rank_draw_temp[$k] ($rank_draw_temp[$k]) > rank_limit_temp[$k] ($rank_limit_temp[$k])</b><br><br>";
			}
		}	
		
		echo "<b>rank_pass_fail = $rank_pass_fail</b><br><br>";
	}
	###################################################################################################################
	
	### Dup 5 last draws
	$dup_temp = array_fill(0,8,0);
	
	print("<h3>Dups</h3>\n");

	// Process the last draws for different ranges
	for ($x = 1; $x <= 4; $x++) {
		${"last_".$x."_draws"} = LastDraws($curr_date, $x);
		// Debugging output for verification (can be removed or commented out in production)
		print_r(${"last_".$x."_draws"});
		echo "<br>";
	}
	
	### Rank Count
	$rank_count_temp = array_fill(0,43,0);
	
	print("<h3>Rank Count</h3>\n");

	print("<TABLE BORDER=\"1\">\n");

	print("<TR>\n");

	for ($x = 1; $x <= 42; $x++)
	{
		//create header row
		print("<TD BGCOLOR=\"#CCCCCC\" align=center width=20>$x</TD>\n");
	}
	
	print("</TR>\n");

	print("<TR>\n");
	
	### 250110 TDD
	$query = "SELECT * FROM $draw_prefix";
	$query .= "rank_count_table ";
	$query .= "WHERE date = '$curr_date' ";
	$query .= "AND draw_limit = '30' ";

	echo "$query<br>";

	$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

	$row_count = mysqli_fetch_array($mysqli_result);

	$h = 1;

	for ($g = 3; $g <= 44; $g++)
	{
		$rank_count_temp[$h] = $row_count[$g];
		print("<TD align=center width=20>$row_count[$g]</TD>\n");
		$h++;
	}
	
	echo "rank_count_temp:<br>";
	print_r($rank_count_temp);
	echo "<p>";

	print("</TR>\n");

	//end table
	print("</TABLE>\n");
	
	#################
	
	### Rank limit
	$rank_limit_temp = array_fill(0,8,0);
	
	print("<h3>Rank Limit</h3>\n");

	print("<TABLE BORDER=\"1\">\n");

	print("<TR>\n");

	for ($x = 0; $x <= 7; $x++)
	{
		//create header row
		print("<TD BGCOLOR=\"#CCCCCC\" align=center width=20>$x</TD>\n");
	}
	
	print("</TR>\n");

	print("<TR>\n");

	$query = "SELECT * FROM $draw_prefix";
	$query .= "rank_limit ";
	$query .= "WHERE date = '$curr_date' ";
	$query .= "AND   draw_limit = 30 ";

	echo "query - $query<br>";

	$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

	$row_count = mysqli_fetch_array($mysqli_result);

	$h = 0;

	for ($g = 3; $g <= 10; $g++)
	{
		$rank_limit_temp[$h] = $row_count[$g];
		print("<TD align=center width=20>$row_count[$g]</TD>\n");
		$h++;
	}
	
	echo "rank_limit_temp:<br>";
	print_r($rank_limit_temp);
	echo "<p>";

	print("</TR>\n");

	//end table
	print("</TABLE>\n");
	
	// Output for debugging or further usage
	
	//start HTML page
	print("<HTML>\n");
	print("<HEAD>\n");
	print("<TITLE>Small Test 2</TITLE>\n");
	print("</HEAD>\n");
	
	print("<BODY>\n");

	### get count draw table
	$query5 = "SELECT * FROM $draw_table_name ";
	$query5 .= "WHERE date >= '2015-10-01' ";
	$query5 .= "ORDER BY date DESC ";
	#$query5 .= "LIMIT 1,100 ";

	echo "<p>$query5</p>";

	$mysqli_result5 = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));

	$num_rows5 = mysqli_num_rows($mysqli_result5);

	echo "<p>num_rows5 = $num_rows5</p>";

	while ($row5 = mysqli_fetch_array($mysqli_result5))
	{
		$draw = array ();

		for ($x = 1; $x <= $balls_drawn; $x++)
		{
			array_push($draw, $row5[$x]);
		}

		sort ($draw);
		
		echo "<b>draw: #############################################################################################</b><br>";
		print_r ($draw);
		echo "<br>";
		
		$dup_pass_fail = 1; 
		$rank_pass_fail = 1;
		
		check_dup_rank($draw);
		
		#die();
	}

	print("<h2>Completed!</h2>");

	print("</BODY>\n");
?>
