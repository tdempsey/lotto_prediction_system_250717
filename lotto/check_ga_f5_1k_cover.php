<?php

	$game = 1; // Florida F5

	require ("includes/games_switch.incl");

	// include to connect to database
	require ("includes/mysqli.php");
	require ("includes/count_2_seq_4.php");
	require ("includes/count_3_seq_4.php");
	#require ("includes_fl/build_rank_table_fl.php");
	#require ("includes_fl/calculate_rank_fl.php");
	require ("includes/first_draw_unix.php");
	require ("includes/last_draw_unix.php");
	require ("includes/dateDiffInDays.php");

	//start HTML page
	print("<HTML>\n");
	print("<HEAD>\n");
	print("<TITLE>Check Georgia Fantasy 5 1K Cover</TITLE>\n");
	print("</HEAD>\n");
	
	print("<body bgcolor=\"#FFFFFF\" text=\"#000000\">\n");

	require ("includes/unix.incl");
	#require ("includes/dateDiffInDays.php");

	$first_draw = mktime (0,0,0,date(1),date(1),date(2003));
	
	$curr_date = date('Y-m-d');
	$prev_date = date('Y-m-d', strtotime('-1 day'));
	$currdate = date('ymd');
	$drawdate = date('ymd', strtotime('-1 day'));

	echo "first_draw = $first_draw<p>";

	$check_date = date ('Y-m-d', $last_draw_unix);

	echo "check_date = $check_date<p>";

	$temp_table2 = 'temp_cover_1k_scaffolding_135_' .  $drawdate;
	
	echo "temp_table2 = $temp_table2<p>";
	
	### read ga_f5_draws
	$query4 = "SELECT * FROM ga_f5_draws ";
	$query4 .= "WHERE date = '$prev_date' ";
	$query4 .= "LIMIT 1 ";

	echo "<p>query4 - $query4</p>";

	$mysqli_result4 = mysqli_query($mysqli_link, $query4) or die (mysqli_error($mysqli_link));
	
	$num_rows4 = mysqli_num_rows($mysqli_result4);

	echo "<h3>num_rows4 = $num_rows4</h3>";
	
	while($row4 = mysqli_fetch_array($mysqli_result4))
	{
		$winning_numbers = [
			$row4['b1'],
			$row4['b2'],
			$row4['b3'],
			$row4['b4'],
			$row4['b5']];
	}
	
	print_r($winning_numbers);
	echo "<br>";
	
	### read $temp_table2
	$query3 = "SELECT * FROM $temp_table2 ";
	$query3 .= "ORDER BY id ASC ";

	#echo "<p>query3 - $query3</p>";

	$mysqli_result3 = mysqli_query($mysqli_link, $query3) or die (mysqli_error($mysqli_link));
	
	$num_rows3 = mysqli_num_rows($mysqli_result3);

	echo "<h3>num_rows3 = $num_rows3</h3>";
	
	$two_wins = 0;
	$three_wins = 0;
	$four_wins = 0;
	$five_wins = 0;
	
	while($row3 = mysqli_fetch_array($mysqli_result3))
	{
		$match_count = 0; 
		
		$numbers_in_row = [
			$row3['b1'],
			$row3['b2'],
			$row3['b3'],
			$row3['b4'],
			$row3['b5']];
			
		// Calculate the count of winning numbers in this row
		$match_count = count(array_intersect($winning_numbers, $numbers_in_row));
		
		echo "$row3[id] = match_count = $match_count<br>";
		
		// Categorize the row based on the match count
		switch ($match_count) {
			case 2:
				$two_wins++;
				break;
			case 3:
				$three_wins++;
				break;
			case 4:
				$four_wins++;
				break;
			case 5:
				$five_wins++;
				break;
		}
	}
	
	echo "<br><b>2 - $two_wins<br>";
	echo "3 - $three_wins<br>";
	echo "4 - $four_wins<br>";
	echo "5 - $five_wins<b><br>";

	/*
	// Output the results
	function displayResults($category, $rows) {
		echo "<h2>$category</h2>";
		if (!empty($rows)) {
			echo "<table border='1'>";
			echo "<tr><th>Row ID</th><th>Numbers</th></tr>";
			foreach ($rows as $row) {
				echo "<tr><td>{$row['id']}</td><td>" . implode(", ", array_slice($row, 1, 5)) . "</td></tr>";
			}
			echo "</table><br>";
		} else {
			echo "<p>No rows with $category</p>";
		}
	}

	// Display categorized results
	echo "<h1>Lottery Results</h1>";
	displayResults("2 Wins", $two_wins);
	displayResults("3 Wins", $three_wins);
	displayResults("4 Wins", $four_wins);
	displayResults("5 Wins", $five_wins);
	*/

	########################################################################################### 241111

?>