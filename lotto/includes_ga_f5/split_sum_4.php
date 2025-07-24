<?php
	// ----------------------------------------------------------------------------------
	function split_sum_4 ($sum)
	{
		global $debug, $draw_table_name, $balls, $balls_drawn, $draw_prefix, $game, $hml, $even, $odd; 

		require ("includes/mysqli.php"); 

		$curr_date = date("Y-m-d");

		$sum_table = 'temp_5_' . $balls . '_' . $sum;

		$table_temp = 'temp_4_' . $balls . '_' . $sum;

		$query = "DROP TABLE IF EXISTS $table_temp ";

		#echo "$query<br>";

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

		$query4 = "CREATE TABLE $table_temp LIKE ga_f5_draws_4 ";

		#echo "$query4<br>";

		$mysqli_result = mysqli_query($mysqli_link, $query4) or die (mysqli_error($mysqli_link));

		$query4 = "ALTER TABLE temp_4_42";
		$query4 .= "_$sum";
		$query4 .= "DROP date; ";

		#echo "$query4<br>";

		$mysqli_result = mysqli_query($mysqli_link, $query4) or die (mysqli_error($mysqli_link));

		$query2 = "SELECT * FROM $sum_table ";
		$query2 .= "ORDER BY id ASC ";

		echo "$query2<p>";

		$mysqli_result2 = mysqli_query($mysqli_link, $query2) or die (mysqli_error($mysqli_link));

		$num_rows = mysqli_num_rows($mysqli_result2);

		while($row = mysqli_fetch_array($mysqli_result2))
		{
			if ($num_rows)
			{
				### c1 ###
				$sum = $row[1]+$row[2]+$row[3]+$row[4];

				$draw = array ($row[1],$row[2],$row[3],$row[4]);

				#print_r ($draw);

				$even = 0;
				$odd = 0;

				even_odd ($draw,$even,$odd);

				#echo "------ even = $even</br>";
				#echo "------ odd = $odd</br>";

				$draw_count = array_fill (0, 6, 0);

				$draw_count = calculate_draw_count4($draw);

				$query9 = "INSERT INTO $table_temp ";
				#$query9 .= "draws_4 ";
				$query9 .= "VALUES ('0', ";
				#$query9 .= "'1962-08-17', ";
				$query9 .= "'1', "; #combin
				$query9 .= "'$row[1]', ";
				$query9 .= "'$row[2]', ";
				$query9 .= "'$row[3]', ";
				$query9 .= "'$row[4]', ";
				$query9 .= "'$sum', ";			#combin sum
				$query9 .= "'$row[6]', ";		#draw sum
				$query9 .= "'$even', ";	
				$query9 .= "'$odd', ";		
				$query9 .= "'$draw_count[0]', ";
				$query9 .= "'$draw_count[1]', ";
				$query9 .= "'$draw_count[2]', ";
				$query9 .= "'$draw_count[3]', ";
				$query9 .= "'$draw_count[4]') ";
				/*
				for ($d = 0; $d <= 8; $d++) 
				{
					$query9 .= "'0', ";
				}

				$query9 .= "'$row[60]', ";
				$query9 .= "'1962-08-17') ";
				*/
				#echo "$query9<p>";
		
				$mysqli_result9 = mysqli_query($mysqli_link, $query9) or die (mysqli_error($mysqli_link));
				
				### end ###

				### c2 ###
				$sum = $row[1]+$row[2]+$row[3]+$row[5];

				$draw = array ($row[1],$row[2],$row[3],$row[5]);

				even_odd($draw,$even,$odd);

				$draw_count = array_fill (0, 6, 0);

				$draw_count = calculate_draw_count4($draw);

				$query9 = "INSERT INTO $table_temp ";
				#$query9 .= "draws_4 ";
				$query9 .= "VALUES ('0', ";
				#$query9 .= "'1962-08-17', ";
				$query9 .= "'2', "; #combin
				$query9 .= "'$row[1]', ";
				$query9 .= "'$row[2]', ";
				$query9 .= "'$row[3]', ";
				$query9 .= "'$row[5]', ";
				$query9 .= "'$sum', ";			#combin sum
				$query9 .= "'$row[6]', ";		#draw sum
				$query9 .= "'$even', ";	
				$query9 .= "'$odd', ";		
				$query9 .= "'$draw_count[0]', ";
				$query9 .= "'$draw_count[1]', ";
				$query9 .= "'$draw_count[2]', ";
				$query9 .= "'$draw_count[3]', ";
				$query9 .= "'$draw_count[4]') ";

				#echo "$query9<p>";
		
				$mysqli_result9 = mysqli_query($mysqli_link, $query9) or die (mysqli_error($mysqli_link));
				
				### end ###

				### c3 ###
				$sum = $row[1]+$row[2]+$row[4]+$row[5];

				$draw = array ($row[1],$row[2],$row[4],$row[5]);

				even_odd($draw,$even,$odd);

				$draw_count = array_fill (0, 6, 0);

				$draw_count = calculate_draw_count4($draw);

				$query9 = "INSERT INTO $table_temp ";
				#$query9 .= "draws_4 ";
				$query9 .= "VALUES ('0', ";
				#$query9 .= "'1962-08-17', ";
				$query9 .= "'3', "; #combin
				$query9 .= "'$row[1]', ";
				$query9 .= "'$row[2]', ";
				$query9 .= "'$row[4]', ";
				$query9 .= "'$row[5]', ";
				$query9 .= "'$sum', ";			#combin sum
				$query9 .= "'$row[6]', ";		#draw sum
				$query9 .= "'$even', ";	
				$query9 .= "'$odd', ";		
				$query9 .= "'$draw_count[0]', ";
				$query9 .= "'$draw_count[1]', ";
				$query9 .= "'$draw_count[2]', ";
				$query9 .= "'$draw_count[3]', ";
				$query9 .= "'$draw_count[4]') ";

				#echo "$query9<p>";
		
				$mysqli_result9 = mysqli_query($mysqli_link, $query9) or die (mysqli_error($mysqli_link));
				
				### end ###

				### c4 ###
				$sum = $row[1]+$row[3]+$row[4]+$row[5];

				$draw = array ($row[1],$row[3],$row[4],$row[5]);

				even_odd($draw,$even,$odd);

				$draw_count = array_fill (0, 6, 0);

				$draw_count = calculate_draw_count4($draw);

				$query9 = "INSERT INTO $table_temp ";
				#$query9 .= "draws_4 ";
				$query9 .= "VALUES ('0', ";
				#$query9 .= "'1962-08-17', ";
				$query9 .= "'4', "; #combin
				$query9 .= "'$row[1]', ";
				$query9 .= "'$row[3]', ";
				$query9 .= "'$row[4]', ";
				$query9 .= "'$row[5]', ";
				$query9 .= "'$sum', ";			#combin sum
				$query9 .= "'$row[6]', ";		#draw sum
				$query9 .= "'$even', ";	
				$query9 .= "'$odd', ";		
				$query9 .= "'$draw_count[0]', ";
				$query9 .= "'$draw_count[1]', ";
				$query9 .= "'$draw_count[2]', ";
				$query9 .= "'$draw_count[3]', ";
				$query9 .= "'$draw_count[4]') ";

				#echo "$query9<p>";
		
				$mysqli_result9 = mysqli_query($mysqli_link, $query9) or die (mysqli_error($mysqli_link));
				
				### end ###

				### c5 ###

				$draw = array ($row[2],$row[3],$row[4],$row[5]);

				$sum = array_sum($draw);

				even_odd($draw,$even,$odd);

				$draw_count = array_fill (0, 6, 0);

				$draw_count = calculate_draw_count4($draw);

				$query9 = "INSERT INTO $table_temp ";
				#$query9 .= "draws_4 ";
				$query9 .= "VALUES ('0', ";
				#$query9 .= "'1962-08-17', ";
				$query9 .= "'5', "; #combin
				$query9 .= "'$row[2]', ";
				$query9 .= "'$row[3]', ";
				$query9 .= "'$row[4]', ";
				$query9 .= "'$row[5]', ";
				$query9 .= "'$sum', ";			#combin sum
				$query9 .= "'$row[6]', ";		#draw sum
				$query9 .= "'$even', ";	
				$query9 .= "'$odd', ";		
				$query9 .= "'$draw_count[0]', ";
				$query9 .= "'$draw_count[1]', ";
				$query9 .= "'$draw_count[2]', ";
				$query9 .= "'$draw_count[3]', ";
				$query9 .= "'$draw_count[4]') ";

				#echo "$query9<p>";
		
				$mysqli_result9 = mysqli_query($mysqli_link, $query9) or die (mysqli_error($mysqli_link));
				
				### end ###
			}		
		}

		echo "add comb4 count summary<br>";
	}
?>