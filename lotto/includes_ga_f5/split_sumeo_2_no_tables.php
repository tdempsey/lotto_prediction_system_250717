<?php
	// ----------------------------------------------------------------------------------
	function split_sumeo_2_no_tables ($sum,$sum_even,$sum_odd)
	{
		global $debug, $draw_table_name, $balls, $balls_drawn, $draw_prefix, $game, $hml; 

		require ("includes/mysqli.php"); 

		$curr_date = date("Y-m-d");

		$sumeo_table = 'temp2_' . $balls_drawn . '_' . $balls . '_' . $sum . '_' . $sum_even . '_' . $sum_odd;

		$table_temp = 'temp2_2_' . $balls . '_'. $sum . '_' . $sum_even . '_' . $sum_odd;

		$query = "DROP TABLE IF EXISTS $table_temp ";

		#echo "$query<br>";

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

		$query4 = "CREATE TABLE $table_temp LIKE temp2_2_42_sumeo ";

		#echo "$query4<br>";

		$mysqli_result = mysqli_query($mysqli_link, $query4) or die (mysqli_error($mysqli_link));

		$query2 = "SELECT * FROM $sumeo_table ";
		$query2 .= "ORDER BY id ASC ";

		#echo "$query2<p>";

		$mysqli_result2 = mysqli_query($mysqli_link, $query2) or die (mysqli_error($mysqli_link));

		$num_rows = mysqli_num_rows($mysqli_result2);

		while($row = mysqli_fetch_array($mysqli_result2))
		{
			if ($num_rows)
			{
				### c1 ###
				$draw = array ($row[1],$row[2]);

				$sum = array_sum($draw);

				#print_r ($draw);

				$even = 0;
				$odd = 0;

				even_odd ($draw,$even,$odd);

				#echo "------ even = $even</br>";
				#echo "------ odd = $odd</br>";

				$draw_count = array_fill (0, 6, 0);

				$draw_count = calculate_draw_count2($draw);

				$query9 = "INSERT INTO $table_temp ";
				$query9 .= "VALUES ('0', ";
				$query9 .= "'$row[1]', ";
				$query9 .= "'$row[2]', ";
				$query9 .= "'$sum', ";			#combin sum
				$query9 .= "'$even', ";	
				$query9 .= "'$odd', ";		
				$query9 .= "'$row[6]', ";		#draw sum
				$query9 .= "'1', "; #combin
				$query9 .= "'0', ";
				$query9 .= "'0', ";
				$query9 .= "'0') ";

				#echo "$query9<p>";
		
				$mysqli_result9 = mysqli_query($mysqli_link, $query9) or die (mysqli_error($mysqli_link));
				
				### end ###

				### c2 ###
				$draw = array ($row[1],$row[3]);

				$sum = array_sum($draw);

				even_odd($draw,$even,$odd);

				$draw_count = array_fill (0, 6, 0);

				$draw_count = calculate_draw_count2($draw);

				$query9 = "INSERT INTO $table_temp ";
				$query9 .= "VALUES ('0', ";
				$query9 .= "'$row[1]', ";
				$query9 .= "'$row[3]', ";
				$query9 .= "'$sum', ";			#combin sum
				$query9 .= "'$even', ";	
				$query9 .= "'$odd', ";		
				$query9 .= "'$row[6]', ";		#draw sum
				$query9 .= "'2', "; #combin
				$query9 .= "'0', ";
				$query9 .= "'0', ";
				$query9 .= "'0') ";

				#echo "$query9<p>";
		
				$mysqli_result9 = mysqli_query($mysqli_link, $query9) or die (mysqli_error($mysqli_link));
				
				### end ###

				### c3 ###
				$draw = array ($row[1],$row[4]);

				$sum = array_sum($draw);

				even_odd($draw,$even,$odd);

				$draw_count = array_fill (0, 6, 0);

				$draw_count = calculate_draw_count2($draw);

				$query9 = "INSERT INTO $table_temp ";
				$query9 .= "VALUES ('0', ";
				$query9 .= "'$row[1]', ";
				$query9 .= "'$row[4]', ";
				$query9 .= "'$sum', ";			#combin sum
				$query9 .= "'$even', ";	
				$query9 .= "'$odd', ";		
				$query9 .= "'$row[6]', ";		#draw sum
				$query9 .= "'3', "; #combin
				$query9 .= "'0', ";
				$query9 .= "'0', ";
				$query9 .= "'0') ";


				#echo "$query9<p>";
		
				$mysqli_result9 = mysqli_query($mysqli_link, $query9) or die (mysqli_error($mysqli_link));
				
				### end ###

				### c4 ###
				$draw = array ($row[1],$row[5]);

				$sum = array_sum($draw);

				even_odd($draw,$even,$odd);

				$draw_count = array_fill (0, 6, 0);

				$draw_count = calculate_draw_count2($draw);

				$query9 = "INSERT INTO $table_temp ";
				$query9 .= "VALUES ('0', ";
				$query9 .= "'$row[1]', ";
				$query9 .= "'$row[5]', ";
				$query9 .= "'$sum', ";			#combin sum
				$query9 .= "'$even', ";	
				$query9 .= "'$odd', ";		
				$query9 .= "'$row[6]', ";		#draw sum
				$query9 .= "'4', "; #combin
				$query9 .= "'0', ";
				$query9 .= "'0', ";
				$query9 .= "'0') ";

				#echo "$query9<p>";
		
				$mysqli_result9 = mysqli_query($mysqli_link, $query9) or die (mysqli_error($mysqli_link));
				
				### end ###

				### c5 ###
				$draw = array ($row[2],$row[3]);

				$sum = array_sum($draw);

				even_odd($draw,$even,$odd);

				$draw_count = array_fill (0, 6, 0);

				$draw_count = calculate_draw_count2($draw);

				$query9 = "INSERT INTO $table_temp ";
				$query9 .= "VALUES ('0', ";
				$query9 .= "'$row[2]', ";
				$query9 .= "'$row[3]', ";
				$query9 .= "'$sum', ";			#combin sum
				$query9 .= "'$even', ";	
				$query9 .= "'$odd', ";		
				$query9 .= "'$row[6]', ";		#draw sum
				$query9 .= "'5', "; #combin
				$query9 .= "'0', ";
				$query9 .= "'0', ";
				$query9 .= "'0') ";

				#echo "$query9<p>";
		
				$mysqli_result9 = mysqli_query($mysqli_link, $query9) or die (mysqli_error($mysqli_link));
				
				### end ###

				### c6 ###
				$draw = array ($row[2],$row[4]);

				$sum = array_sum($draw);

				even_odd($draw,$even,$odd);

				$draw_count = array_fill (0, 6, 0);

				$draw_count = calculate_draw_count2($draw);

				$query9 = "INSERT INTO $table_temp ";
				$query9 .= "VALUES ('0', ";
				$query9 .= "'$row[2]', ";
				$query9 .= "'$row[4]', ";
				$query9 .= "'$sum', ";			#combin sum
				$query9 .= "'$even', ";	
				$query9 .= "'$odd', ";		
				$query9 .= "'$row[6]', ";		#draw sum
				$query9 .= "'6', "; #combin
				$query9 .= "'0', ";
				$query9 .= "'0', ";
				$query9 .= "'0') ";

				#echo "$query9<p>";
		
				$mysqli_result9 = mysqli_query($mysqli_link, $query9) or die (mysqli_error($mysqli_link));
				
				### end ###

				### c7 ###
				$draw = array ($row[2],$row[5]);

				$sum = array_sum($draw);

				even_odd($draw,$even,$odd);

				$draw_count = array_fill (0, 6, 0);

				$draw_count = calculate_draw_count2($draw);

				$query9 = "INSERT INTO $table_temp ";
				$query9 .= "VALUES ('0', ";
				$query9 .= "'$row[2]', ";
				$query9 .= "'$row[5]', ";
				$query9 .= "'$sum', ";			#combin sum
				$query9 .= "'$even', ";	
				$query9 .= "'$odd', ";		
				$query9 .= "'$row[6]', ";		#draw sum
				$query9 .= "'7', "; #combin
				$query9 .= "'0', ";
				$query9 .= "'0', ";
				$query9 .= "'0') ";

				#echo "$query9<p>";
		
				$mysqli_result9 = mysqli_query($mysqli_link, $query9) or die (mysqli_error($mysqli_link));
				
				### end ###

				### c8 ###
				$draw = array ($row[3],$row[4]);

				$sum = array_sum($draw);

				even_odd($draw,$even,$odd);

				$draw_count = array_fill (0, 6, 0);

				$draw_count = calculate_draw_count2($draw);

				$query9 = "INSERT INTO $table_temp ";
				$query9 .= "VALUES ('0', ";
				$query9 .= "'$row[3]', ";
				$query9 .= "'$row[4]', ";
				$query9 .= "'$sum', ";			#combin sum
				$query9 .= "'$even', ";	
				$query9 .= "'$odd', ";		
				$query9 .= "'$row[6]', ";		#draw sum
				$query9 .= "'8', "; #combin
				$query9 .= "'0', ";
				$query9 .= "'0', ";
				$query9 .= "'0') ";

				#echo "$query9<p>";
		
				$mysqli_result9 = mysqli_query($mysqli_link, $query9) or die (mysqli_error($mysqli_link));
				
				### end ###

				### c9 ###
				$draw = array ($row[3],$row[5]);

				$sum = array_sum($draw);

				even_odd($draw,$even,$odd);

				$draw_count = array_fill (0, 6, 0);

				$draw_count = calculate_draw_count2($draw);

				$query9 = "INSERT INTO $table_temp ";
				$query9 .= "VALUES ('0', ";
				$query9 .= "'$row[3]', ";
				$query9 .= "'$row[5]', ";
				$query9 .= "'$sum', ";			#combin sum
				$query9 .= "'$even', ";	
				$query9 .= "'$odd', ";		
				$query9 .= "'$row[6]', ";		#draw sum
				$query9 .= "'9', "; #combin
				$query9 .= "'0', ";
				$query9 .= "'0', ";
				$query9 .= "'0') ";

				#echo "$query9<p>";
		
				$mysqli_result9 = mysqli_query($mysqli_link, $query9) or die (mysqli_error($mysqli_link));
				
				### end ###

				### c10 ###
				$draw = array ($row[4],$row[5]);

				$sum = array_sum($draw);

				even_odd($draw,$even,$odd);

				$draw_count = array_fill (0, 6, 0);

				$draw_count = calculate_draw_count2($draw);

				$query9 = "INSERT INTO $table_temp ";
				$query9 .= "VALUES ('0', ";
				$query9 .= "'$row[4]', ";
				$query9 .= "'$row[5]', ";
				$query9 .= "'$sum', ";			#combin sum
				$query9 .= "'$even', ";	
				$query9 .= "'$odd', ";		
				$query9 .= "'$row[6]', ";		#draw sum
				$query9 .= "'10', "; #combin
				$query9 .= "'0', ";
				$query9 .= "'0', ";
				$query9 .= "'0') ";

				#echo "$query9<p>";
		
				$mysqli_result9 = mysqli_query($mysqli_link, $query9) or die (mysqli_error($mysqli_link));
				
				### end ###
			}		
		}

		echo "2 - add comb4 count summary<br>";
	}
?>