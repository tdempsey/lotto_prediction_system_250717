<?php
	// Game ----------------------------- Game
	$game = 7; // Powerball
	// Game ----------------------------- Game
	
	require ("includes/games_switch.incl");

	require ("includes/mysqli.php");

	echo "Start...<br>";
	
	$debug = 0;

	$today  = mktime (0,0,0,date("m"),date("d"),date("Y"));

	$query_all = "SELECT * FROM $draw_table_name ";

	$mysqli_result_all = mysqli_query($query_all, $mysqli_link) or die (mysqli_error($mysqli_link));

	$num_rows_all = mysqli_num_rows($mysqli_result_all);

	$curr_date = date("Y-m-d");
	echo "$curr_date<br>";
	$num_rows_sum = 0;

	for ($wsum = 75; $wsum < 240; $wsum++)
	{
		###################################################################################
		for ($col1 = 1; $col1 <= 20; $col1++)
		{
			$query1 = "SELECT * FROM combo_5_59_$col1 ";
			$query1 .= "WHERE sum = $wsum ";

			#echo "$query1<br>";

			$mysqli_result1 = mysqli_query($mysqli_link, $query1) or die (mysqli_error($mysqli_link));

			$num_rows_sum += mysqli_num_rows($mysqli_result1);
		}

		###################################################################################
		
		$query = "SELECT * FROM pb_eo50 ";
		echo "$query<br";
	
		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

		while($row = mysqli_fetch_array($mysqli_result))
		{
			$num_rows = 0;

			for ($col1 = 1; $col1 <= 10; $col1++)
			{	
				$query2 = "SELECT * FROM combo_5_59_$col1 ";
				$query2 .= "WHERE sum	= $wsum ";
				$query2 .= "AND	even	= $row[even] ";
				$query2 .= "AND	odd		= $row[odd] ";
				$query2 .= "AND	d501	= $row[d501] ";
				$query2 .= "AND	d502	= $row[d502] ";

				#echo "$query2<br";
		
				$mysqli_result2 = mysqli_query($mysqli_link, $query2) or die (mysqli_error($mysqli_link));

				$num_rows += mysqli_num_rows($mysqli_result2);
			}

			if ($num_rows)
			{
				// 10
				$temp_date = gmstrftime("%Y-%m-%d", ($today-(86400*10)));

				$query3 = "SELECT * FROM $draw_table_name ";
				$query3 .= "WHERE sum	= $wsum ";
				$query3 .= "AND	even	= $row[even] ";
				$query3 .= "AND	odd		= $row[odd] ";
				$query3 .= "AND	d501	= $row[d501] ";
				$query3 .= "AND	d502	= $row[d502] ";
				$query3 .= "AND	date	> '$temp_date' ";
				$query3 .= "LIMIT 10 ";

				#echo "$query3<br";
		
				$mysqli_result3 = mysqli_query($mysqli_link, $query3) or die (mysqli_error($mysqli_link));

				$num_rows_10 = mysqli_num_rows($mysqli_result3);

				// 30
				$temp_date = gmstrftime("%Y-%m-%d", ($today-(86400*30)));

				$query3 = "SELECT * FROM $draw_table_name ";
				$query3 .= "WHERE sum	= $wsum ";
				$query3 .= "AND	even	= $row[even] ";
				$query3 .= "AND	odd		= $row[odd] ";
				$query3 .= "AND	d501	= $row[d501] ";
				$query3 .= "AND	d502	= $row[d502] ";
				$query3 .= "AND	date	> '$temp_date' ";
		
				$mysqli_result3 = mysqli_query($mysqli_link, $query3) or die (mysqli_error($mysqli_link));

				$num_rows_30 = mysqli_num_rows($mysqli_result3);

				// 50
				$temp_date = gmstrftime("%Y-%m-%d", ($today-(86400*50)));

				$query3 = "SELECT * FROM $draw_table_name ";
				$query3 .= "WHERE sum	= $wsum ";
				$query3 .= "AND	even	= $row[even] ";
				$query3 .= "AND	odd		= $row[odd] ";
				$query3 .= "AND	d501	= $row[d501] ";
				$query3 .= "AND	d502	= $row[d502] ";
				$query3 .= "AND	date	> '$temp_date' ";
		
				$mysqli_result3 = mysqli_query($mysqli_link, $query3) or die (mysqli_error($mysqli_link));

				$num_rows_50 = mysqli_num_rows($mysqli_result3);

				// 100
				$temp_date = gmstrftime("%Y-%m-%d", ($today-(86400*100)));

				$query3 = "SELECT * FROM $draw_table_name ";
				$query3 .= "WHERE sum	= $wsum ";
				$query3 .= "AND	even	= $row[even] ";
				$query3 .= "AND	odd		= $row[odd] ";
				$query3 .= "AND	d501	= $row[d501] ";
				$query3 .= "AND	d502	= $row[d502] ";
				$query3 .= "AND	date	> '$temp_date' ";
		
				$mysqli_result3 = mysqli_query($mysqli_link, $query3) or die (mysqli_error($mysqli_link));

				$num_rows_100 = mysqli_num_rows($mysqli_result3);

				// 365
				$temp_date = gmstrftime("%Y-%m-%d", ($today-(86400*365)));

				$query3 = "SELECT * FROM $draw_table_name ";
				$query3 .= "WHERE sum	= $wsum ";
				$query3 .= "AND	even	= $row[even] ";
				$query3 .= "AND	odd		= $row[odd] ";
				$query3 .= "AND	d501	= $row[d501] ";
				$query3 .= "AND	d502	= $row[d502] ";
				$query3 .= "AND	date	> '$temp_date' ";
		
				$mysqli_result3 = mysqli_query($mysqli_link, $query3) or die (mysqli_error($mysqli_link));

				$num_rows_365 = mysqli_num_rows($mysqli_result3);

				// 500
				$temp_date = gmstrftime("%Y-%m-%d", ($today-(86400*500)));

				$query3 = "SELECT * FROM $draw_table_name ";
				$query3 .= "WHERE sum	= $wsum ";
				$query3 .= "AND	even	= $row[even] ";
				$query3 .= "AND	odd		= $row[odd] ";
				$query3 .= "AND	d501	= $row[d501] ";
				$query3 .= "AND	d502	= $row[d502] ";
				$query3 .= "AND	date	> '$temp_date' ";
		
				$mysqli_result3 = mysqli_query($mysqli_link, $query3) or die (mysqli_error($mysqli_link));

				$num_rows_500 = mysqli_num_rows($mysqli_result3);

				// 1000
				$temp_date = gmstrftime("%Y-%m-%d", ($today-(86400*1000)));

				$query3 = "SELECT * FROM $draw_table_name ";
				$query3 .= "WHERE sum	= $wsum ";
				$query3 .= "AND	even	= $row[even] ";
				$query3 .= "AND	odd		= $row[odd] ";
				$query3 .= "AND	d501	= $row[d501] ";
				$query3 .= "AND	d502	= $row[d502] ";
				$query3 .= "AND	date	> '$temp_date' ";
		
				$mysqli_result3 = mysqli_query($mysqli_link, $query3) or die (mysqli_error($mysqli_link));

				$num_rows_1000 = mysqli_num_rows($mysqli_result3);

				// 5000 - all
				$prev_draw = '1962-08-17';
				$last_draw = '1962-08-17';

				$query4 = "SELECT * FROM $draw_table_name ";
				$query4 .= "WHERE sum	= $wsum ";
				$query4 .= "AND	even	= $row[even] ";
				$query4 .= "AND	odd		= $row[odd] ";
				$query4 .= "AND	d501	= $row[d501] ";
				$query4 .= "AND	d502	= $row[d502] ";
				$query4 .= "ORDER BY date DESC ";

				echo "$query4<br>";

				$mysqli_result4 = mysqli_query($mysqli_link, $query4) or die (mysqli_error($mysqli_link));

				$num_rows_5000 = mysqli_num_rows($mysqli_result4);

				if ($num_rows_5000)
				{
					$row_date = mysqli_fetch_array($mysqli_result4);
					$last_draw = $row_date[date];
				}

				if ($num_rows_5000 > 1)
				{
					$row_date = mysqli_fetch_array($mysqli_result4);
					$prev_draw = $row_date[date];
				}


				$num_rows_temp_365 = number_format(($num_rows_365/365)*100,1);

				$num_rows_temp_5000 = number_format(($num_rows_5000/$num_rows_sum)*100,1);
				
				$weighted_average = (
					($num_rows_10/10*100*0.05) +
					($num_rows_30/30*100*0.25) +
					($num_rows_100/100*100*0.25) +
					($num_rows_365/365*100*0.30) +
					($num_rows_500/500*100*0.05) +
					($num_rows_1000/1000*100*0.05) +
					($num_rows_5000/$num_rows_sum*100*0.05));

				$num_rows_temp_wa = number_format($weighted_average,1);
				
				if ($num_rows_temp_wa > 0.0 || $num_rows > 0)
				{
					//print("Percent = $percent - $row[even],$row[odd],$row[d501],$row[d502] - eo50 = $row[id]\n");
					//generate_wheel_table($wsum,$row[id],$even,$odd,$d501,$d502,$num_rows,$percent,$num_rows_365,$num_rows_all,$date);
					$query9 = "INSERT INTO pb_wheels_generated ";
					$query9 .= "VALUES (0, 
									$wsum, 
									$row[id], 
									$num_rows_sum,
									$num_rows_10, 
									$num_rows_30, 
									$num_rows_50, 
									$num_rows_100, 
									$num_rows_365, 
									$num_rows_500, 
									$num_rows_1000, 
									$num_rows_5000,
									$num_rows_30,
									$num_rows_365,
									$num_rows_1000,
									$num_rows_5000,
									$num_rows_temp_wa,
									0.0,
									'$prev_draw',
									'$last_draw', 
									'$curr_date') ";

					echo "$query9<br>";
				
					$mysqli_result9 = mysqli_query($mysqli_link, $query9) or die (mysqli_error($mysqli_link));
					
					#die ();
				} # if
			} # if
		} # while 
	} # for 
	
	print "<h3>Table <font color=\"#ff0000\">pb_wheels_generated</font> Updated!</h3>";
?>