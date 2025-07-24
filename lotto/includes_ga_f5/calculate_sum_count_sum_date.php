<?php
function calulate_sum_count_sum_date ($sum, $even, $odd)
{
	echo "\includes_ga_f5\calulate_sum_count_sum_date<br>";

	global $draw_table_name, $balls, $balls_drawn, $draw_prefix, $col1_select, $hml, $range_low, $range_high; 
	
	$sum_tot_temp_array = array_fill (0,17,0);
	$sum_tot_array = array_fill (0,200,$sum_tot_temp_array);

	$query9 = "SELECT date, sum, even, odd FROM $draw_table_name ";
	$query9 .= "WHERE date >= '2015-10-01' ";
	$query9 .= "AND sum = $sum ";
	$query9 .= "AND even = $even ";
	$query9 .= "AND odd = $odd ";
	$query9 .= "ORDER BY date DESC ";

	#print "$query9<p>";

	$mysqli_result9 = mysqli_query($mysqli_link, $query9) or die (mysqli_error($mysqli_link));

	$num_rows_all = mysqli_num_rows($mysqli_result9);
	
	while($row = mysqli_fetch_array($mysqli_result9))
	{
		#echo "row[date] = $row[0]<br>";

		# calculate unix date
		$draw_date_array = explode("-","$row[0]"); ### 210104
		$draw_date_unix = mktime (0,0,0,$draw_date_array[1],$draw_date_array[2],$draw_date_array[0]); ### 210104

		echo "draw_date_unix = $draw_date_unix<br>";
		echo "year1 = $year1<br>";

		#$num_array_count[$y][$z]++;
		
		$y = $row[sum];
	
		# calculate history
		if ($draw_date_unix == $day1)
		{ 
			for ($d = 0; $d <= 15; $d++) {$sum_tot_array[$y][$d]++;}
		} elseif ($draw_date_unix > $week1) {
			for ($d = 1; $d <= 15; $d++) {$sum_tot_array[$y][$d]++;}
		} elseif ($draw_date_unix > $week2) {
			for ($d = 2; $d <= 15; $d++) {$sum_tot_array[$y][$d]++;}
		} elseif ($draw_date_unix > $month1) {
			for ($d = 3; $d <= 15; $d++) {$sum_tot_array[$y][$d]++;}
		} elseif ($draw_date_unix > $month3) {
			for ($d = 4; $d <= 15; $d++) {$sum_tot_array[$y][$d]++;}
		} elseif ($draw_date_unix > $month6) {
			for ($d = 5; $d <= 15; $d++) {$sum_tot_array[$y][$d]++;}
		} elseif ($draw_date_unix > $year1) {
			for ($d = 6; $d <= 15; $d++) {$sum_tot_array[$y][$d]++;}
		} elseif ($draw_date_unix > $year2) {
			for ($d = 7; $d <= 15; $d++) {$sum_tot_array[$y][$d]++;}
		} elseif ($draw_date_unix > $year3) {
			for ($d = 8; $d <= 15; $d++) {$sum_tot_array[$y][$d]++;}
		} elseif ($draw_date_unix > $year4) {
			for ($d = 9; $d <= 15; $d++) {$sum_tot_array[$y][$d]++;}
		} elseif ($draw_date_unix > $year5) {
			for ($d = 10; $d <= 15; $d++) {$sum_tot_array[$y][$d]++;}
		} elseif ($draw_date_unix > $year6) {
			for ($d = 11; $d <= 15; $d++) {$sum_tot_array[$y][$d]++;}
		} elseif ($draw_date_unix > $year7) {
			for ($d = 12; $d <= 15; $d++) {$sum_tot_array[$y][$d]++;}
		} elseif ($draw_date_unix > $year8) {
			for ($d = 13; $d <= 15; $d++) {$sum_tot_array[$y][$d]++;}
		} elseif ($draw_date_unix > $year9) {
			for ($d = 14; $d <= 15; $d++) {$sum_tot_array[$y][$d]++;}
		} elseif ($draw_date_unix > $year10) {
			for ($d = 15; $d <= 15; $d++) {$sum_tot_array[$y][$d]++;}
		}

		#add 1 year to clear
		if ($first_draw_unix > $year7) {
			for ($d = 13; $d <= 15; $d++) {$sum_tot_array[$y][$d]=0;}
		} elseif ($first_draw_unix > $year8) {
			for ($d = 14; $d <= 15; $d++) {$sum_tot_array[$y][$d]=0;}
		} elseif ($first_draw_unix > $year9) {
			for ($d = 15; $d <= 15; $d++) {$sum_tot_array[$y][$d]=0;}
		} elseif ($first_draw_unix > $year10) {
			for ($d = 16; $d <= 16; $d++) {$sum_tot_array[$y][$d]=0;}
		}

		/*
		if ($prev_date[$y] == '1962-08-17' || $prev_date[$y] == $num_date[$y])
		{
			$prev_date[$y] = $row[0];
		}

		if ($row[0] > $num_date[$y])
		{	
			$prev_date[$y] = $num_date[$y];
			$num_date[$y] = $row[0];
		}
		*/
	}

	for ($x = 1; $x <= 1; $x++)
	{
		$col_temp_y1 = number_format(($sum_tot_array[$y][6]/365)*100,1);
		$col_temp_y4 = number_format(($sum_tot_array[$y][9]/(365*4))*100,1);

		$weighted_average = (
			#($sum_tot_array[$y][1]/7*100*0.10) + #week1
			($sum_tot_array[$y][3]/30*100*0.10) + #month1
			($sum_tot_array[$y][5]/(365/2)*100*0.15) + #month6
			($sum_tot_array[$y][6]/365*100*0.25) + #year1
			($sum_tot_array[$y][8]/(365*3)*100*0.25) + #year3
			($sum_tot_array[$y][9]/(365*4)*100*0.25)); #year4

		$query5 = "INSERT INTO $draw_prefix";
		$query5 .= "sum_count_sum ";
		$query5 .= "VALUES ('0', "; 
		$query5 .= "'$row_eo[sum]',";
		$query5 .= "'$row_eo[even]',";
		$query5 .= "'$row_eo[odd]',";
		for ($d = 0; $d <= 15; $d++)
		{
			$query5 .= "'{$sum_tot_array[$y][$d]}',";
		}
		$query5 .= "'{$sum_tot_array[$y][16]}',";
		#$query5 .= "'0',"; 
		$query5 .= "'$col_temp_y1',";
		$query5 .= "'$col_temp_y4',";
		$query5 .= "'$weighted_average')";
		
		#print "$query5<p>";
	
		$mysqli_result5 = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));
	}

	return ($weighted_average);
}
?>