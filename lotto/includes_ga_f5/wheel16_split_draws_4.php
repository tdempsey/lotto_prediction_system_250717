<?php
	// ----------------------------------------------------------------------------------
	function wheel16_split_draws_4($limit)
	{
		global $debug, $draw_table_name, $balls, $balls_drawn, $draw_prefix, $game, $hml, $range_low, $range_high, $even, $odd; 

		require ("includes/mysqli.php"); 
		require ("includes/unix.incl"); 
		require ("includes/even_odd.php");

		#echo "### 3 ###<br>";
		
		$query = "SELECT * FROM $draw_table_name ";
		$query .= "WHERE date >= '2015-10-01' ";
		$query .= "ORDER BY date DESC ";
		$query .= "LIMIT $limit ";

		#print "$query<p>";

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));
		
		while($row = mysqli_fetch_array($mysqli_result))
		{
			#echo "<h3>draw = $row[1]-$row[2]-$row[3]-$row[4]-$row[5]</h3>";

			$query2 = "SELECT * FROM $draw_prefix";
			$query2 .= "draws_4 ";
			$query2 .= "WHERE date = '$row[0]' ";

			#print "$query2<p>";

			$mysqli_result2 = mysqli_query($mysqli_link, $query2) or die (mysqli_error($mysqli_link));

			$num_rows_all = mysqli_num_rows($mysqli_result2);

			#$row = mysqli_fetch_array($mysqli_result2);

			#echo "num_rows_all = $num_rows_all<br>";

			#echo "row[even] = $row[even]<br>";

			#print_r("$row");
			
			if (!$num_rows_all)
			{
				### c1 ###
				$sum = $row[1]+$row[2]+$row[3]+$row[4];

				#

				$draw = array ($row[1],$row[2],$row[3],$row[4]);

				#print_r ($draw);

				$even = 0;
				$odd = 0;

				even_odd ($draw,$even,$odd);

				$draw_count = array_fill (0, 6, 0);

				$draw_count = calculate_draw_count($draw);

				$query9 = "INSERT INTO $draw_prefix";
				$query9 .= "draws_4 ";
				$query9 .= "VALUES ('0', ";
				$query9 .= "'$row[0]', ";
				$query9 .= "'1', "; #combin
				$query9 .= "'$row[1]', ";
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
				$query9 .= "'$draw_count[4]') ";

				#for ($d = 0; $d <= 810 $d++) 
				#{
				#	$query9 .= "'0', ";
				#}

				#$query9 .= "'1962-08-17') ";

				##echo "$query9<p>";
		
				$mysqli_result9 = mysqli_query($mysqli_link, $query9) or die (mysqli_error($mysqli_link));
				
				### end ###

				### c2 ###
				$sum = $row[1]+$row[2]+$row[3]+$row[5];

				$draw = array ($row[1],$row[2],$row[3],$row[5]);

				even_odd($draw,$even,$odd);

				$draw_count = array_fill (0, 6, 0);

				$draw_count = calculate_draw_count($draw);

				$query9 = "INSERT INTO $draw_prefix";
				$query9 .= "draws_4 ";
				$query9 .= "VALUES ('0', ";
				$query9 .= "'$row[0]', ";
				$query9 .= "'2', "; #combin
				$query9 .= "'$row[1]', ";
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
				$query9 .= "'$draw_count[4]') ";

				#for ($d = 0; $d <= 810 $d++) 
				#{
				#	$query9 .= "'0', ";
				#}

				#$query9 .= "'1962-08-17') ";

				##echo "$query9<p>";
		
				$mysqli_result9 = mysqli_query($mysqli_link, $query9) or die (mysqli_error($mysqli_link));
				
				### end ###
				
				### c3 ###
				$sum = $row[1]+$row[2]+$row[4]+$row[5];

				$draw = array ($row[1],$row[2],$row[4],$row[5]);

				even_odd($draw,$even,$odd);

				$draw_count = array_fill (0, 6, 0);

				$draw_count = calculate_draw_count($draw);

				$query9 = "INSERT INTO $draw_prefix";
				$query9 .= "draws_4 ";
				$query9 .= "VALUES ('0', ";
				$query9 .= "'$row[0]', ";
				$query9 .= "'3', "; #combin
				$query9 .= "'$row[1]', ";
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
				$query9 .= "'$draw_count[4]') ";

				#for ($d = 0; $d <= 810 $d++) 
				#{
				#	$query9 .= "'0', ";
				#}

				#$query9 .= "'1962-08-17') ";

				##echo "$query9<p>";
		
				$mysqli_result9 = mysqli_query($mysqli_link, $query9) or die (mysqli_error($mysqli_link));
				
				### end ###

				### c4 ###
				$sum = $row[1]+$row[3]+$row[4]+$row[5];

				$draw = array ($row[1],$row[3],$row[4],$row[5]);

				even_odd($draw,$even,$odd);

				$draw_count = array_fill (0, 6, 0);

				$draw_count = calculate_draw_count($draw);

				$query9 = "INSERT INTO $draw_prefix";
				$query9 .= "draws_4 ";
				$query9 .= "VALUES ('0', ";
				$query9 .= "'$row[0]', ";
				$query9 .= "'4', "; #combin
				$query9 .= "'$row[1]', ";
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
				$query9 .= "'$draw_count[4]') ";

				#for ($d = 0; $d <= 810 $d++) 
				#{
				#	$query9 .= "'0', ";
				#}

				#$query9 .= "'1962-08-17') ";

				##echo "$query9<p>";
		
				$mysqli_result9 = mysqli_query($mysqli_link, $query9) or die (mysqli_error($mysqli_link));
				
				### end ###

				### c5 ###

				$draw = array ($row[2],$row[3],$row[4],$row[5]);

				$sum = array_sum($draw);

				even_odd($draw,$even,$odd);

				$draw_count = array_fill (0, 6, 0);

				$draw_count = calculate_draw_count($draw);

				$query9 = "INSERT INTO $draw_prefix";
				$query9 .= "draws_4 ";
				$query9 .= "VALUES ('0', ";
				$query9 .= "'$row[0]', ";
				$query9 .= "'5', "; #combin
				$query9 .= "'$row[2]', ";
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
				$query9 .= "'$draw_count[4]') ";

				#for ($d = 0; $d <= 810 $d++) 
				#{
				#	$query9 .= "'0', ";
				#}

				#$query9 .= "'1962-08-17') ";

				##echo "$query9<p>";
		
				$mysqli_result9 = mysqli_query($mysqli_link, $query9) or die (mysqli_error($mysqli_link));
				
				### end ###
			}		
		}

		#echo "### 4 ###<br>";
	}
?>