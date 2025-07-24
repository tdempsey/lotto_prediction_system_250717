<?php
	// ----------------------------------------------------------------------------------
	function split_sumeo_3_m2($sum,$sum_even,$sum_odd)
	{
		global $debug, $draw_table_name, $balls, $balls_drawn, $draw_prefix, $game, $hml, $range_low, $range_high, $even, $odd; 

		require ("includes/mysqli.php"); 
		require ("includes/unix.incl"); 

		$sumeo_table = 'combo_5_' . $balls . '_' . $sum . '_' . $sum_even . '_' . $sum_odd . '_m2';

		$table_temp = 'combo_3_' . $balls . '_' . $sum . '_' . $sum_even . '_' . $sum_odd . '_m2';

		$query = "DROP TABLE IF EXISTS $table_temp";

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

		$query4 = "CREATE TABLE $table_temp LIKE ga_f5_draws_3 ";

		#echo "$query4<br>";

		$mysqli_result = mysqli_query($mysqli_link, $query4) or die (mysqli_error($mysqli_link));

		$query2 = "SELECT * FROM $sumeo_table ";
		$query2 .= "ORDER BY id ASC ";

		print "$query<p>";

		$mysqli_result2 = mysqli_query($mysqli_link, $query2) or die (mysqli_error($mysqli_link));

		while($row = mysqli_fetch_array($mysqli_result2))
		{
			#echo "<h3>draw = $row[b1]-$row[b2]-$row[b3]-$row[b4]-$row[b5]</h3>";

			### c1 ###
			$draw = array ($row[b1],$row[b2],$row[b3]);

			$sum = array_sum($draw);

			$even = 0;
			$odd = 0;

			even_odd($draw,$even,$odd);

			$draw_count = array_fill (0, 6, 0);

			$draw_count = calculate_draw_count($draw);

			$query9 = "INSERT INTO $table_temp ";
			#$query9 .= "draws_3 ";
			$query9 .= "VALUES ('0', ";
			$query9 .= "'1962-08-17', ";
			$query9 .= "'1', "; #combin
			$query9 .= "'$row[1]', ";
			$query9 .= "'$row[2]', ";
			$query9 .= "'$row[3]', ";
			$query9 .= "'$sum', ";			#combin sum
			$query9 .= "'$row[sum]', ";		#draw sum
			$query9 .= "'$even', ";	
			$query9 .= "'$odd', ";		
			$query9 .= "'$draw_count[0]', ";
			$query9 .= "'$draw_count[1]', ";
			$query9 .= "'$draw_count[2]', ";
			$query9 .= "'$draw_count[3]', ";
			$query9 .= "'$draw_count[4]', ";

			for ($d = 0; $d <= 8; $d++) 
			{
				$query9 .= "'0', ";
			}

			$query9 .= "'1962-08-17') ";

			#echo "$query9<p>";
	
			$mysqli_result9 = mysqli_query($mysqli_link, $query9) or die (mysqli_error($mysqli_link));

			$query3 = "SELECT count(*) FROM $table_temp ";
			$query3 .= "WHERE b1 = $row[1] ";
			$query3 .= "AND   b2 = $row[2] ";
			$query3 .= "AND   b3 = $row[3] ";
			$query3 .= "AND   combin = 1 ";
			
			#print "$query3<p>";

			$mysqli_result3 = mysqli_query($mysqli_link, $query3) or die (mysqli_error($mysqli_link));

			$row3 = mysqli_fetch_array($mysqli_result3);

			#echo "cnt = $row3[0]<br>";

			$query4 = "UPDATE $table_temp ";
			$query4 .= "SET s61510 = $row3[0] ";
			$query4 .= "WHERE b1 = $row[1] ";
			$query4 .= "AND   b2 = $row[2] ";
			$query4 .= "AND   b3 = $row[3] ";
			$query4 .= "AND   combin = 1 ";

			#print "$query4<p>";

			$mysqli_result4 = mysqli_query($mysqli_link, $query4) or die (mysqli_error($mysqli_link));
			
			### end ###

			### c2 ###
			$draw = array ($row[b1],$row[b2],$row[b4]);

			$sum = array_sum($draw);

			even_odd($draw,$even,$odd);

			$draw_count = array_fill (0, 6, 0);

			$draw_count = calculate_draw_count($draw);

			$query9 = "INSERT INTO $table_temp ";
			#$query9 .= "draws_3 ";
			$query9 .= "VALUES ('0', ";
			$query9 .= "'1962-08-17', ";
			$query9 .= "'2', "; #combin
			$query9 .= "'$row[1]', ";
			$query9 .= "'$row[2]', ";
			$query9 .= "'$row[4]', ";
			$query9 .= "'$sum', ";			#combin sum
			$query9 .= "'$row[sum]', ";		#draw sum
			$query9 .= "'$even', ";	
			$query9 .= "'$odd', ";		
			$query9 .= "'$draw_count[0]', ";
			$query9 .= "'$draw_count[1]', ";
			$query9 .= "'$draw_count[2]', ";
			$query9 .= "'$draw_count[3]', ";
			$query9 .= "'$draw_count[4]', ";

			for ($d = 0; $d <= 8; $d++) 
			{
				$query9 .= "'0', ";
			}

			$query9 .= "'1962-08-17') ";

			#echo "$query9<p>";
	
			$mysqli_result9 = mysqli_query($mysqli_link, $query9) or die (mysqli_error($mysqli_link));

			$query3 = "SELECT count(*) FROM $table_temp ";
			$query3 .= "WHERE b1 = $row[1] ";
			$query3 .= "AND   b2 = $row[2] ";
			$query3 .= "AND   b3 = $row[4] ";
			$query3 .= "AND   combin = 2 ";
			
			#print "$query3<p>";

			$mysqli_result3 = mysqli_query($mysqli_link, $query3) or die (mysqli_error($mysqli_link));

			$row3 = mysqli_fetch_array($mysqli_result3);

			#echo "cnt = $row3[0]<br>";

			$query4 = "UPDATE $table_temp ";
			$query4 .= "SET s61510 = $row3[0] ";
			$query4 .= "WHERE b1 = $row[1] ";
			$query4 .= "AND   b2 = $row[2] ";
			$query4 .= "AND   b3 = $row[4] ";
			$query4 .= "AND   combin = 2 ";

			#print "$query4<p>";

			$mysqli_result4 = mysqli_query($mysqli_link, $query4) or die (mysqli_error($mysqli_link));
			
			### end ###

			### c3 ###
			$draw = array ($row[b1],$row[b2],$row[b5]);

			$sum = array_sum($draw);

			even_odd($draw,$even,$odd);

			$draw_count = array_fill (0, 6, 0);

			$draw_count = calculate_draw_count($draw);

			$query9 = "INSERT INTO $table_temp ";
			#$query9 .= "draws_3 ";
			$query9 .= "VALUES ('0', ";
			$query9 .= "'1962-08-17', ";
			$query9 .= "'3', "; #combin
			$query9 .= "'$row[1]', ";
			$query9 .= "'$row[2]', ";
			$query9 .= "'$row[5]', ";
			$query9 .= "'$sum', ";			#combin sum
			$query9 .= "'$row[sum]', ";		#draw sum
			$query9 .= "'$even', ";	
			$query9 .= "'$odd', ";		
			$query9 .= "'$draw_count[0]', ";
			$query9 .= "'$draw_count[1]', ";
			$query9 .= "'$draw_count[2]', ";
			$query9 .= "'$draw_count[3]', ";
			$query9 .= "'$draw_count[4]', ";

			for ($d = 0; $d <= 8; $d++) 
			{
				$query9 .= "'0', ";
			}

			$query9 .= "'1962-08-17') ";

			#echo "$query9<p>";
	
			$mysqli_result9 = mysqli_query($mysqli_link, $query9) or die (mysqli_error($mysqli_link));

			$query3 = "SELECT count(*) FROM $table_temp ";
			$query3 .= "WHERE b1 = $row[1] ";
			$query3 .= "AND   b2 = $row[2] ";
			$query3 .= "AND   b3 = $row[5] ";
			$query3 .= "AND   combin = 3 ";
			
			#print "$query3<p>";

			$mysqli_result3 = mysqli_query($mysqli_link, $query3) or die (mysqli_error($mysqli_link));

			$row3 = mysqli_fetch_array($mysqli_result3);

			#echo "cnt = $row3[0]<br>";

			$query4 = "UPDATE $table_temp ";
			$query4 .= "SET s61510 = $row3[0] ";
			$query4 .= "WHERE b1 = $row[1] ";
			$query4 .= "AND   b2 = $row[2] ";
			$query4 .= "AND   b3 = $row[5] ";
			$query4 .= "AND   combin = 3 ";

			#print "$query4<p>";

			$mysqli_result4 = mysqli_query($mysqli_link, $query4) or die (mysqli_error($mysqli_link));
			
			### end ###

			### c4 ###
			$draw = array ($row[b1],$row[b3],$row[b4]);

			$sum = array_sum($draw);

			even_odd($draw,$even,$odd);

			$draw_count = array_fill (0, 6, 0);

			$draw_count = calculate_draw_count($draw);

			$query9 = "INSERT INTO $table_temp ";
			#$query9 .= "draws_3 ";
			$query9 .= "VALUES ('0', ";
			$query9 .= "'1962-08-17', ";
			$query9 .= "'4', "; #combin
			$query9 .= "'$row[1]', ";
			$query9 .= "'$row[3]', ";
			$query9 .= "'$row[4]', ";
			$query9 .= "'$sum', ";			#combin sum
			$query9 .= "'$row[sum]', ";		#draw sum
			$query9 .= "'$even', ";	
			$query9 .= "'$odd', ";		
			$query9 .= "'$draw_count[0]', ";
			$query9 .= "'$draw_count[1]', ";
			$query9 .= "'$draw_count[2]', ";
			$query9 .= "'$draw_count[3]', ";
			$query9 .= "'$draw_count[4]', ";

			for ($d = 0; $d <= 8; $d++) 
			{
				$query9 .= "'0', ";
			}

			$query9 .= "'1962-08-17') ";

			#echo "$query9<p>";
	
			$mysqli_result9 = mysqli_query($mysqli_link, $query9) or die (mysqli_error($mysqli_link));

			$query3 = "SELECT count(*) FROM $table_temp ";
			$query3 .= "WHERE b1 = $row[1] ";
			$query3 .= "AND   b2 = $row[3] ";
			$query3 .= "AND   b3 = $row[4] ";
			$query3 .= "AND   combin = 4 ";
			
			#print "$query3<p>";

			$mysqli_result3 = mysqli_query($mysqli_link, $query3) or die (mysqli_error($mysqli_link));

			$row3 = mysqli_fetch_array($mysqli_result3);

			#echo "cnt = $row3[0]<br>";

			$query4 = "UPDATE $table_temp ";
			$query4 .= "SET s61510 = $row3[0] ";
			$query4 .= "WHERE b1 = $row[1] ";
			$query4 .= "AND   b2 = $row[3] ";
			$query4 .= "AND   b3 = $row[4] ";
			$query4 .= "AND   combin = 4 ";

			#print "$query4<p>";

			$mysqli_result4 = mysqli_query($mysqli_link, $query4) or die (mysqli_error($mysqli_link));
			
			### end ###

			### c5 ###
			$draw = array ($row[b1],$row[b3],$row[b5]);

			$sum = array_sum($draw);

			even_odd($draw,$even,$odd);

			$draw_count = array_fill (0, 6, 0);

			$draw_count = calculate_draw_count($draw);

			$query9 = "INSERT INTO $table_temp ";
			#$query9 .= "draws_3 ";
			$query9 .= "VALUES ('0', ";
			$query9 .= "'1962-08-17', ";
			$query9 .= "'5', "; #combin
			$query9 .= "'$row[1]', ";
			$query9 .= "'$row[3]', ";
			$query9 .= "'$row[5]', ";
			$query9 .= "'$sum', ";			#combin sum
			$query9 .= "'$row[sum]', ";		#draw sum
			$query9 .= "'$even', ";	
			$query9 .= "'$odd', ";		
			$query9 .= "'$draw_count[0]', ";
			$query9 .= "'$draw_count[1]', ";
			$query9 .= "'$draw_count[2]', ";
			$query9 .= "'$draw_count[3]', ";
			$query9 .= "'$draw_count[4]', ";

			for ($d = 0; $d <= 8; $d++) 
			{
				$query9 .= "'0', ";
			}

			$query9 .= "'1962-08-17') ";

			#echo "$query9<p>";
	
			$mysqli_result9 = mysqli_query($mysqli_link, $query9) or die (mysqli_error($mysqli_link));

			$query3 = "SELECT count(*) FROM $table_temp ";
			$query3 .= "WHERE b1 = $row[1] ";
			$query3 .= "AND   b2 = $row[3] ";
			$query3 .= "AND   b3 = $row[5] ";
			$query3 .= "AND   combin = 5 ";
			
			#print "$query3<p>";

			$mysqli_result3 = mysqli_query($mysqli_link, $query3) or die (mysqli_error($mysqli_link));

			$row3 = mysqli_fetch_array($mysqli_result3);

			#echo "cnt = $row3[0]<br>";

			$query4 = "UPDATE $table_temp ";
			$query4 .= "SET s61510 = $row3[0] ";
			$query4 .= "WHERE b1 = $row[1] ";
			$query4 .= "AND   b2 = $row[3] ";
			$query4 .= "AND   b3 = $row[5] ";
			$query4 .= "AND   combin = 5 ";

			#print "$query4<p>";

			$mysqli_result4 = mysqli_query($mysqli_link, $query4) or die (mysqli_error($mysqli_link));
			
			### end ###

			### c6 ###
			$draw = array ($row[b1],$row[b4],$row[b5]);

			$sum = array_sum($draw);

			even_odd($draw,$even,$odd);

			$draw_count = array_fill (0, 6, 0);

			$draw_count = calculate_draw_count($draw);

			$query9 = "INSERT INTO $table_temp ";
			#$query9 .= "draws_3 ";
			$query9 .= "VALUES ('0', ";
			$query9 .= "'1962-08-17', ";
			$query9 .= "'6', "; #combin
			$query9 .= "'$row[1]', ";
			$query9 .= "'$row[4]', ";
			$query9 .= "'$row[5]', ";
			$query9 .= "'$sum', ";			#combin sum
			$query9 .= "'$row[sum]', ";		#draw sum
			$query9 .= "'$even', ";	
			$query9 .= "'$odd', ";		
			$query9 .= "'$draw_count[0]', ";
			$query9 .= "'$draw_count[1]', ";
			$query9 .= "'$draw_count[2]', ";
			$query9 .= "'$draw_count[3]', ";
			$query9 .= "'$draw_count[4]', ";

			for ($d = 0; $d <= 8; $d++) 
			{
				$query9 .= "'0', ";
			}

			$query9 .= "'1962-08-17') ";

			#echo "$query9<p>";
	
			$mysqli_result9 = mysqli_query($mysqli_link, $query9) or die (mysqli_error($mysqli_link));

			$query3 = "SELECT count(*) FROM $table_temp ";
			$query3 .= "WHERE b1 = $row[1] ";
			$query3 .= "AND   b2 = $row[4] ";
			$query3 .= "AND   b3 = $row[5] ";
			$query3 .= "AND   combin = 6 ";
			
			#print "$query3<p>";

			$mysqli_result3 = mysqli_query($mysqli_link, $query3) or die (mysqli_error($mysqli_link));

			$row3 = mysqli_fetch_array($mysqli_result3);

			#echo "cnt = $row3[0]<br>";

			$query4 = "UPDATE $table_temp ";
			$query4 .= "SET s61510 = $row3[0] ";
			$query4 .= "WHERE b1 = $row[1] ";
			$query4 .= "AND   b2 = $row[4] ";
			$query4 .= "AND   b3 = $row[5] ";
			$query4 .= "AND   combin = 6 ";

			#print "$query4<p>";

			$mysqli_result4 = mysqli_query($mysqli_link, $query4) or die (mysqli_error($mysqli_link));
			
			### end ###

			### c7 ###
			$draw = array ($row[b2],$row[b3],$row[b4]);

			$sum = array_sum($draw);

			even_odd($draw,$even,$odd);

			$draw_count = array_fill (0, 6, 0);

			$draw_count = calculate_draw_count($draw);

			$query9 = "INSERT INTO $table_temp ";
			#$query9 .= "draws_3 ";
			$query9 .= "VALUES ('0', ";
			$query9 .= "'1962-08-17', ";
			$query9 .= "'7', "; #combin
			$query9 .= "'$row[2]', ";
			$query9 .= "'$row[3]', ";
			$query9 .= "'$row[4]', ";
			$query9 .= "'$sum', ";			#combin sum
			$query9 .= "'$row[sum]', ";		#draw sum
			$query9 .= "'$even', ";	
			$query9 .= "'$odd', ";		
			$query9 .= "'$draw_count[0]', ";
			$query9 .= "'$draw_count[1]', ";
			$query9 .= "'$draw_count[2]', ";
			$query9 .= "'$draw_count[3]', ";
			$query9 .= "'$draw_count[4]', ";

			for ($d = 0; $d <= 8; $d++) 
			{
				$query9 .= "'0', ";
			}

			$query9 .= "'1962-08-17') ";

			#echo "$query9<p>";
	
			$mysqli_result9 = mysqli_query($mysqli_link, $query9) or die (mysqli_error($mysqli_link));

			$query3 = "SELECT count(*) FROM $table_temp ";
			$query3 .= "WHERE b1 = $row[2] ";
			$query3 .= "AND   b2 = $row[3] ";
			$query3 .= "AND   b3 = $row[4] ";
			$query3 .= "AND   combin = 7 ";
			
			#print "$query3<p>";

			$mysqli_result3 = mysqli_query($mysqli_link, $query3) or die (mysqli_error($mysqli_link));

			$row3 = mysqli_fetch_array($mysqli_result3);

			#echo "cnt = $row3[0]<br>";

			$query4 = "UPDATE $table_temp ";
			$query4 .= "SET s61510 = $row3[0] ";
			$query4 .= "WHERE b1 = $row[2] ";
			$query4 .= "AND   b2 = $row[3] ";
			$query4 .= "AND   b3 = $row[4] ";
			$query4 .= "AND   combin = 7 ";

			#print "$query4<p>";

			$mysqli_result4 = mysqli_query($mysqli_link, $query4) or die (mysqli_error($mysqli_link));
			
			### end ###

			### c8 ###
			$draw = array ($row[b2],$row[b3],$row[b5]);

			$sum = array_sum($draw);

			even_odd($draw,$even,$odd);

			$draw_count = array_fill (0, 6, 0);

			$draw_count = calculate_draw_count($draw);

			$query9 = "INSERT INTO $table_temp ";
			#$query9 .= "draws_3 ";
			$query9 .= "VALUES ('0', ";
			$query9 .= "'1962-08-17', ";
			$query9 .= "'8', "; #combin
			$query9 .= "'$row[2]', ";
			$query9 .= "'$row[3]', ";
			$query9 .= "'$row[5]', ";
			$query9 .= "'$sum', ";			#combin sum
			$query9 .= "'$row[sum]', ";		#draw sum
			$query9 .= "'$even', ";	
			$query9 .= "'$odd', ";		
			$query9 .= "'$draw_count[0]', ";
			$query9 .= "'$draw_count[1]', ";
			$query9 .= "'$draw_count[2]', ";
			$query9 .= "'$draw_count[3]', ";
			$query9 .= "'$draw_count[4]', ";

			for ($d = 0; $d <= 8; $d++) 
			{
				$query9 .= "'0', ";
			}

			$query9 .= "'1962-08-17') ";

			#echo "$query9<p>";
	
			$mysqli_result9 = mysqli_query($mysqli_link, $query9) or die (mysqli_error($mysqli_link));

			$query3 = "SELECT count(*) FROM $table_temp ";
			$query3 .= "WHERE b1 = $row[2] ";
			$query3 .= "AND   b2 = $row[3] ";
			$query3 .= "AND   b3 = $row[5] ";
			$query3 .= "AND   combin = 8 ";
			
			#print "$query3<p>";

			$mysqli_result3 = mysqli_query($mysqli_link, $query3) or die (mysqli_error($mysqli_link));

			$row3 = mysqli_fetch_array($mysqli_result3);

			#echo "cnt = $row3[0]<br>";

			$query4 = "UPDATE $table_temp ";
			$query4 .= "SET s61510 = $row3[0] ";
			$query4 .= "WHERE b1 = $row[2] ";
			$query4 .= "AND   b2 = $row[3] ";
			$query4 .= "AND   b3 = $row[5] ";
			$query4 .= "AND   combin = 8 ";

			#print "$query4<p>";

			$mysqli_result4 = mysqli_query($mysqli_link, $query4) or die (mysqli_error($mysqli_link));
			
			### end ###

			### c9 ###
			$draw = array ($row[b2],$row[b4],$row[b5]);

			$sum = array_sum($draw);

			even_odd($draw,$even,$odd);

			$draw_count = array_fill (0, 6, 0);

			$draw_count = calculate_draw_count($draw);

			$query9 = "INSERT INTO $table_temp ";
			#$query9 .= "draws_3 ";
			$query9 .= "VALUES ('0', ";
			$query9 .= "'1962-08-17', ";
			$query9 .= "'9', "; #combin
			$query9 .= "'$row[2]', ";
			$query9 .= "'$row[4]', ";
			$query9 .= "'$row[5]', ";
			$query9 .= "'$sum', ";			#combin sum
			$query9 .= "'$row[sum]', ";		#draw sum
			$query9 .= "'$even', ";	
			$query9 .= "'$odd', ";		
			$query9 .= "'$draw_count[0]', ";
			$query9 .= "'$draw_count[1]', ";
			$query9 .= "'$draw_count[2]', ";
			$query9 .= "'$draw_count[3]', ";
			$query9 .= "'$draw_count[4]', ";

			for ($d = 0; $d <= 8; $d++) 
			{
				$query9 .= "'0', ";
			}

			$query9 .= "'1962-08-17') ";

			#echo "$query9<p>";
	
			$mysqli_result9 = mysqli_query($mysqli_link, $query9) or die (mysqli_error($mysqli_link));

			$query3 = "SELECT count(*) FROM $table_temp ";
			$query3 .= "WHERE b1 = $row[2] ";
			$query3 .= "AND   b2 = $row[4] ";
			$query3 .= "AND   b3 = $row[5] ";
			$query3 .= "AND   combin = 9 ";
			
			#print "$query3<p>";

			$mysqli_result3 = mysqli_query($mysqli_link, $query3) or die (mysqli_error($mysqli_link));

			$row3 = mysqli_fetch_array($mysqli_result3);

			#echo "cnt = $row3[0]<br>";

			$query4 = "UPDATE $table_temp ";
			$query4 .= "SET s61510 = $row3[0] ";
			$query4 .= "WHERE b1 = $row[2] ";
			$query4 .= "AND   b2 = $row[4] ";
			$query4 .= "AND   b3 = $row[5] ";
			$query4 .= "AND   combin = 9 ";

			#print "$query4<p>";

			$mysqli_result4 = mysqli_query($mysqli_link, $query4) or die (mysqli_error($mysqli_link));
			
			### end ###

			### c10 ###
			$draw = array ($row[b3],$row[b4],$row[b5]);

			$sum = array_sum($draw);

			even_odd($draw,$even,$odd);

			$draw_count = array_fill (0, 6, 0);

			$draw_count = calculate_draw_count($draw);

			$query9 = "INSERT INTO $table_temp ";
			#$query9 .= "draws_3 ";
			$query9 .= "VALUES ('0', ";
			$query9 .= "'1962-08-17', ";
			$query9 .= "'10', "; #combin
			$query9 .= "'$row[3]', ";
			$query9 .= "'$row[4]', ";
			$query9 .= "'$row[5]', ";
			$query9 .= "'$sum', ";			#combin sum
			$query9 .= "'$row[sum]', ";		#draw sum
			$query9 .= "'$even', ";	
			$query9 .= "'$odd', ";		
			$query9 .= "'$draw_count[0]', ";
			$query9 .= "'$draw_count[1]', ";
			$query9 .= "'$draw_count[2]', ";
			$query9 .= "'$draw_count[3]', ";
			$query9 .= "'$draw_count[4]', ";

			for ($d = 0; $d <= 8; $d++) 
			{
				$query9 .= "'0', ";
			}

			$query9 .= "'1962-08-17') ";

			#echo "$query9<p>";
	
			$mysqli_result9 = mysqli_query($mysqli_link, $query9) or die (mysqli_error($mysqli_link));

			$query3 = "SELECT count(*) FROM $table_temp ";
			$query3 .= "WHERE b1 = $row[3] ";
			$query3 .= "AND   b2 = $row[4] ";
			$query3 .= "AND   b3 = $row[5] ";
			$query3 .= "AND   combin = 10 ";
			
			#print "$query3<p>";

			$mysqli_result3 = mysqli_query($mysqli_link, $query3) or die (mysqli_error($mysqli_link));

			$row3 = mysqli_fetch_array($mysqli_result3);

			#echo "cnt = $row3[0]<br>";

			$query4 = "UPDATE $table_temp ";
			$query4 .= "SET s61510 = $row3[0] ";
			$query4 .= "WHERE b1 = $row[3] ";
			$query4 .= "AND   b2 = $row[4] ";
			$query4 .= "AND   b3 = $row[5] ";
			$query4 .= "AND   combin = 10 ";

			#print "$query4<p>";

			$mysqli_result4 = mysqli_query($mysqli_link, $query4) or die (mysqli_error($mysqli_link));
			
			### end ###
		}		
	}
?>