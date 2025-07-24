<?php
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

	require ("includes/mysqli.php");
	
	require ("includes/build_rank_table.php");
	require ("includes/calculate_rank.php");
	require ("includes/first_draw_unix.php");
	require ("includes/last_draw_unix.php");
	require ("includes/next_draw.php");
	require ("includes/last_draws.php");
	require ("includes/unix.incl");

	date_default_timezone_set('America/New_York');

	//start HTML page
	print("<HTML>\n");
	print("<HEAD>\n");
	print("<TITLE>$game_name</TITLE>\n");
	print("</HEAD>\n");

	print("<BODY>\n");
	print("<H1>$game_name Quick Pick 4</H1>\n");
	
	$curr_date = date("Ymd");
	$curr_date_dash = date("Y-m-d");
	
	$debug = 0;

	### create table ###
	$table_temp = $draw_prefix . "quick_pick_4 ";

	$query = "DROP TABLE IF EXISTS $table_temp";

	print "$query<p>";

	$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

	$query = "CREATE TABLE $table_temp LIKE ";
	$query .= "combo_5_42 ";

	print "$query<p>";

	$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

	### select eo4 ###
	$query1 = "SELECT * FROM $draw_prefix";
	$query1 .= "wheel_sum_table_eo4 ";
	$query1 .= "ORDER BY wa DESC, percent_1 DESC ";
	$query1 .= "LIMIT 25 ";

	print "$query1<p>";

	$mysqli_result1 = mysqli_query($mysqli_link, $query1) or die (mysqli_error($mysqli_link));

	while($row1 = mysqli_fetch_array($mysqli_result1))
	{	
		### select combo_5_42 on eo4 ###
		$query2 = "SELECT * FROM combo_5_42 a ";
		$query2 .= "JOIN combo_5_42_dr4 b ";
		$query2 .= "ON a.id = b.id ";
		$query2 .= "WHERE a.sum  = $row1[sum] ";
		$query2 .= "AND   a.even = $row1[even] ";
		$query2 .= "AND   a.odd  = $row1[odd] ";
		$query2 .= "AND   b.d1   = $row1[d1] ";
		$query2 .= "AND   b.d2   = $row1[d2] ";
		$query2 .= "AND   b.d3   = $row1[d3] ";
		$query2 .= "AND   b.d4   = $row1[d4] ";
		$query2 .= "AND   a.seq2 <= 1 ";
		$query2 .= "AND   a.seq3 =  0 ";
		$query2 .= "AND   a.mod_tot <= 1 ";
		$query2 .= "AND   a.mod_x = 0 ";
		$query2 .= "AND   a.b1 <= 10 ";

		print "$query2<p>";

		$mysqli_result2 = mysqli_query($mysqli_link, $query2) or die (mysqli_error($mysqli_link));

		while($row2 = mysqli_fetch_array($mysqli_result2))
		{
			### insert combo_5_42 into temp_table ###
			$query3 = "INSERT INTO $table_temp SELECT * FROM combo_";
			$query3 .= "5_42 ";
			$query3 .= "WHERE id = $row2[id] ";

			#print "$query3<p>";

			$mysqli_result3 = mysqli_query($mysqli_link, $query3) or die (mysqli_error($mysqli_link));

			### update wheel count and wa ###
			$query5 = "UPDATE $table_temp "	;
			$query5 .= "SET ";
			$query5 .= "wheel_cnt5000=$row1[sum_count], ";
			$query5 .= "wheel_percent_wa=$row1[wa] "; 
			$query5 .= "WHERE id = '$row2[id]' "; 

			#print "$query5<p>";

			$mysqli_result5 = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));

			#$row5 = mysqli_fetch_array($mysqli_result5);
		}
	}

	### update table_temp ###
	$query4a = "SELECT * FROM $table_temp";
	$query4a .= "ORDER BY wheel_percent_wa DESC ";

	print "$query4a<p>";

	$mysqli_result4a = mysqli_query($query4a, $mysqli_link) or die (mysqli_error($mysqli_link));

	while($row4a = mysqli_fetch_array($mysqli_result4a))
	{
		$draw = array ();

		for ($x = 1; $x <= $balls_drawn; $x++)
		{
			array_push($draw, $row4a[$x]);
		}

		### calculate rank ### 
		#echo "calculate rank<br>";
		calculate_rank_count($curr_date_dash,$draw,$rank_count); #date???

		### calculate nums_combo_count ###
		#echo "calculate nums_combo_count<br>";
		require ("includes/combo_count_date_quick_pick.incl");

		### calculate dup ###
		#echo "calculate dup<br>";
		$last_dup = array_fill (0, 51, 0);

		for ($x = 1; $x <= 50; $x++)
		{
			${last_.$x._draws} = LastDraws($curr_date_dash,$x);
		}

		for ($x = 1 ; $x <= 50; $x++)
		{
			for ($y = 1 ; $y <= $balls_drawn; $y++)
			{	
				if (array_search($row4a[b.$y], ${last_.$x._draws}) !== FALSE)
				{
					$last_dup[$x]++;
				}
			}
		}

		### calculate combin ###
		#echo "calculate nums_combo_count<br>";
		require ("includes_ga_f5/combin_quick_pick.incl");

		### update table_temp ###
		$query6 = "UPDATE $table_temp ";
		$query6 .= "SET ";
		$query6 .= "rank0=$rank_count[0], rank1=$rank_count[1], rank2=$rank_count[2], rank3=$rank_count[3], ";
		$query6 .= "rank4=$rank_count[4], rank5=$rank_count[5], rank6=$rank_count[6], ";
		for ($d = 1; $d <= 25; $d++)
		{
			$query6 .= "dup$d=$last_dup[$d], ";
		}
		$query6 .= "comb2=$total2, ";
		$query6 .= "comb3=$total3, ";
		$query6 .= "comb4=$total4, ";
		$query6 .= "comb5=$total5, ";
		$query6 .= "nums_total_2=$temp_nums_count_2, ";
		$query6 .= "combo_total_2=$temp_combo_count_2, ";
		$query6 .= "nums_total_3=$temp_nums_count_3, ";
		$query6 .= "combo_total_3=$temp_combo_count_3, ";
		$query6 .= "nums_total_4=$temp_nums_count_4, ";
		$query6 .= "combo_total_4=$temp_combo_count_4, "; 
		$query6 .= "last_updated='$curr_date_dash' "; 
		$query6 .= "WHERE id = '$row4a[id]' "; 

		#print("$query6<p>");

		$mysqli_result6 = mysqli_query($query6, $mysqli_link) or die (mysqli_error($mysqli_link));
	}

	### update temp_table ###
	# rank
	# combx
	# dupx
	# wheel_cnt
	# wheel_percent_wa
	# draw_count???
	# nums_total_x
	# combo_total_x

	
	### select draws based on filters ###
	### filters
	

	//close page
	print("</BODY>\n");
	print("</HTML>\n");

?>