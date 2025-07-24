<?php
	$game = 6; // Florida Lotto
	
	require ("includes/games_switch.incl");

	// include to connect to database
	require ("includes/mysqli.php");
	require ("includes/count_2_seq.php");
	require ("includes/count_3_seq.php");
	require ("includes_fl/look_up_rank_fl.php");
	require ("$game_includes/combin.incl");

	//start HTML page
	print("<HTML>\n");
	print("<HEAD>\n");
	print("<TITLE>Build Combo 6/53</TITLE>\n");
	print("</HEAD>\n");
	
	print("<BODY bgcolor=\"#FFFFFF\" text=\"#000000\">\n");

	$query =  "SELECT * FROM combo_6_53_temp";

	print "$query<p>";

	$draw_num = array_fill (0, 7, 0);

	$pair_sum = 0;

	$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

	while($row = mysqli_fetch_array($mysqli_result))
	{
		for ($x = 1; $x <= 5; $x++)
		{
			$y = $x - 1;
			$draw_num[$x] = $row[$y];
		}

		#print_r ($draw_num);
	
		$pair_sum = pair_sum_count_6 ($draw_num);

		$query9 = "UPDATE combo_6_53_temp ";
		$query9 .= "SET pair_sum = $pair_sum ";
		$query9 .= "WHERE b1 = $row[b1] AND  ";
		$query9 .= " b2 = $row[b2] AND ";
		$query9 .= " b3 = $row[b3] AND ";
		$query9 .= " b4 = $row[b4] AND ";
		$query9 .= " b5 = $row[b5] AND ";
		$query9 .= " b6 = $row[b6] ";

		print "$query9<p>";

		$mysqli_result9 = mysqli_query($mysqli_link, $query9) or die (mysqli_error($mysqli_link));
					
	}

	function pair_sum_count_6 ($draw_num)
	{ 
		global $debug;
	
		require ("includes/mysqli.php");

		$pair_sum = 0;
					
		// pair count 
		for ($c = 1; $c <= 15; $c++)
		{
			switch ($c) { 
				case 1: 
				   $d1 = $draw_num[1];
				   $d2 = $draw_num[2];
				   break; 
				case 2: 
				   $d1 = $draw_num[1];
				   $d2 = $draw_num[3];
				   break; 
				case 3: 
				   $d1 = $draw_num[1];
				   $d2 = $draw_num[4];
				   break; 
				case 4: 
				   $d1 = $draw_num[1];
				   $d2 = $draw_num[5];
				   break;
				case 5: 
				   $d1 = $draw_num[1];
				   $d2 = $draw_num[6];
				   break;
				case 6: 
				   $d1 = $draw_num[2];
				   $d2 = $draw_num[3];
				   break; 
				case 7: 
				   $d1 = $draw_num[2];
				   $d2 = $draw_num[4];
				   break; 
				case 8: 
				   $d1 = $draw_num[2];
				   $d2 = $draw_num[5];
				   break;
				case 9: 
				   $d1 = $draw_num[2];
				   $d2 = $draw_num[6];
				   break;
				case 10: 
				   $d1 = $draw_num[3];
				   $d2 = $draw_num[4];
				   break;
				case 11: 
				   $d1 = $draw_num[3];
				   $d2 = $draw_num[5];
				   break;
				case 12: 
				   $d1 = $draw_num[3];
				   $d2 = $draw_num[6];
				   break;
				case 13: 
				   $d1 = $draw_num[4];
				   $d2 = $draw_num[5];
				   break;
				case 14: 
				   $d1 = $draw_num[4];
				   $d2 = $draw_num[6];
				   break;
				case 15: 
				   $d1 = $draw_num[5];
				   $d2 = $draw_num[6];
				   break;
			} 

			$query8 = "SELECT num1, num2, count FROM fl_temp_2_5000 ";
			$query8 .= "WHERE num1 = $d1 ";
			$query8 .= "  AND num2 = $d2 ";

			#print "$query8<p>";

			$mysqli_result8 = mysqli_query($mysqli_link, $query8) or die (mysqli_error($mysqli_link));

			$row8 = mysqli_fetch_array($mysqli_result8);
			
			$pair_sum+= $row8[count];
		} 

		return $pair_sum;
	}

	// ----------------------------------------------------------------------------------
	function print_sum_table($limit)
	{
		global $debug, $draw_table_name, $draw_prefix, $balls, $balls_drawn, $game; 

		require ("includes/mysqli.php");

		for ($x = 0; $x < 264; $x++)
		{
			$sum_table[$x] = 0;
			$sum_table_date[$x] = "1962-08-17";
			$sum_table_date_prev[$x] = "1962-08-17";
			$sum_table_10[$x] = 0;
			$sum_table_30[$x] = 0;
			$sum_table_50[$x] = 0;
			$sum_table_100[$x] = 0;
			$sum_table_200[$x] = 0;
			$sum_table_365[$x] = 0;
			$sum_table_500[$x] = 0;
			$sum_table_1000[$x] = 0;
			$sum_table_5000[$x] = 0;
		}
	
		$query = "SELECT sum,date FROM $draw_table_name ";
		$query .= "ORDER BY date DESC ";
		$query .= "LIMIT $limit ";
	
		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

		$num_rows_all = mysqli_num_rows($mysqli_result); 

		//start table
		print("<h3>Sum Table - $limit</h3>\n");
		print("<TABLE BORDER=\"1\">\n");
	
		//create header row
		print("<TR>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Sum</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Count</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\" align=center>10</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\" align=center><b>30</b></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\" align=center>50</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\" align=center>100</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\" align=center>200</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\" align=center><b>365</b></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\" align=center>500</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\" align=center>1000</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\" align=center>5000</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\" align=center>Prev</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\" align=center>Last</TD>\n");
		#print("<TD BGCOLOR=\"#CCCCCC\" align=center>7</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\" align=center>30</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\" align=center>365</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\" align=center>1000</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\" align=center>$num_rows_all</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\" align=center>wa</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\" align=center>wa_prev</TD>\n");
		print("</TR>\n");

		$z = 0;
	
		while($row = mysqli_fetch_array($mysqli_result))
		{
			$sum_table[$row[0]]++;
			if ($row[1] > $sum_table_date[$row[0]]) {
				$sum_table_date_prev[$row[0]] = $sum_table_date[$row[0]];
				$sum_table_date[$row[0]] = $row[1];
			} else if ($sum_table_date_prev[$row[0]] == '1962-08-17') {
				$sum_table_date_prev[$row[0]] = $row[1];
			}

			if ($z < 10) {
				$sum_table_10[$row[0]]++;
				$sum_table_30[$row[0]]++;
				$sum_table_50[$row[0]]++;
				$sum_table_100[$row[0]]++;
				$sum_table_200[$row[0]]++;
				$sum_table_365[$row[0]]++;
				$sum_table_500[$row[0]]++;
				$sum_table_1000[$row[0]]++;
				$sum_table_5000[$row[0]]++;
			} elseif ($z < 30) {
				$sum_table_30[$row[0]]++;
				$sum_table_50[$row[0]]++;
				$sum_table_100[$row[0]]++;
				$sum_table_200[$row[0]]++;
				$sum_table_365[$row[0]]++;
				$sum_table_500[$row[0]]++;
				$sum_table_1000[$row[0]]++;
				$sum_table_5000[$row[0]]++;
			} elseif ($z < 50) {
				$sum_table_50[$row[0]]++;
				$sum_table_100[$row[0]]++;
				$sum_table_200[$row[0]]++;
				$sum_table_365[$row[0]]++;
				$sum_table_500[$row[0]]++;
				$sum_table_1000[$row[0]]++;
				$sum_table_5000[$row[0]]++;
			} elseif ($z < 100) {
				$sum_table_100[$row[0]]++;
				$sum_table_200[$row[0]]++;
				$sum_table_365[$row[0]]++;
				$sum_table_500[$row[0]]++;
				$sum_table_1000[$row[0]]++;
				$sum_table_5000[$row[0]]++;
			} elseif ($z < 200) {
				$sum_table_200[$row[0]]++;
				$sum_table_365[$row[0]]++;
				$sum_table_500[$row[0]]++;
				$sum_table_1000[$row[0]]++;
				$sum_table_5000[$row[0]]++;
			} elseif ($z < 365) {
				$sum_table_365[$row[0]]++;
				$sum_table_500[$row[0]]++;
				$sum_table_1000[$row[0]]++;
				$sum_table_5000[$row[0]]++;
			} elseif ($z < 500) {
				$sum_table_500[$row[0]]++;
				$sum_table_1000[$row[0]]++;
				$sum_table_5000[$row[0]]++;
			} elseif ($z < 1000) {
				$sum_table_1000[$row[0]]++;
				$sum_table_5000[$row[0]]++;
			} elseif ($z < 5000) {
				$sum_table_5000[$row[0]]++;
			}

			$z++;
		}

		$sub_sum = array_fill (0, 6, 0);
		$sub_sum_tot = 0;
		$weighted_average_prev = 0;

		for ($x = 40; $x < 240; $x++)
		{
			print("<TR>\n");
			print("<TD>$x</TD>\n");
			if ($sum_table[$x] > 14) {
				print("<TD align=center><B><font color=\"#009900\">-$sum_table[$x]-</font></B></TD>\n");
			} else {
				print("<TD><CENTER><B>$sum_table[$x]</B></CENTER></TD>\n");
			}
			$sub_sum_tot += $sum_table[$x];

			if ($sum_table_10[$x] > 1) {
				print("<TD BGCOLOR=\"#CCFFFF\" align=center><CENTER><b>$sum_table_10[$x]</b></CENTER></TD>\n");
			} elseif ($sum_table_10[$x] == 1) {
				print("<TD align=center><CENTER>1</CENTER></TD>\n");
			} else {
				print("<TD align=center><CENTER>-</CENTER></TD>\n");
			}
			$sub_sum[0] += $sum_table_10[$x];

			if ($sum_table_30[$x] > 1) {
				print("<TD BGCOLOR=\"#CCFFFF\" align=center><CENTER><b>$sum_table_30[$x]</b></CENTER></TD>\n");
			} elseif ($sum_table_30[$x] == 1) {
				print("<TD align=center><CENTER>1</CENTER></TD>\n");
			} else {
				print("<TD align=center><CENTER>-</CENTER></TD>\n");
			}
			$sub_sum[1] += $sum_table_30[$x];

			if ($sum_table_50[$x] > 1) {
				print("<TD BGCOLOR=\"#CCFFFF\"><CENTER><b>$sum_table_50[$x]</b></CENTER></TD>\n");
			} elseif ($sum_table_50[$x] == 1) {
				print("<TD><CENTER>1</CENTER></TD>\n");
			} else {
				print("<TD><CENTER>-</CENTER></TD>\n");
			}
			$sub_sum[2] += $sum_table_50[$x];

			if ($sum_table_100[$x] > 1) {
				print("<TD BGCOLOR=\"#CCFFFF\"><CENTER><b>$sum_table_100[$x]</b></CENTER></TD>\n");
			} elseif ($sum_table_100[$x] == 1) {
				print("<TD><CENTER>1</CENTER></TD>\n");
			} else {
				print("<TD><CENTER>-</CENTER></TD>\n");
			}
			$sub_sum[3] += $sum_table_100[$x];

			if ($sum_table_200[$x] > 1) {
				print("<TD BGCOLOR=\"#CCFFFF\"><CENTER><b>$sum_table_200[$x]</b></CENTER></TD>\n");
			} elseif ($sum_table_200[$x] == 1) {
				print("<TD><CENTER>1</CENTER></TD>\n");
			} else {
				print("<TD><CENTER>-</CENTER></TD>\n");
			}
			$sub_sum[4] += $sum_table_200[$x];

			
			if ($sum_table_365[$x] > 1) {
				print("<TD BGCOLOR=\"#CCFFFF\" align=center><CENTER><b>$sum_table_365[$x]</b></CENTER></TD>\n");
			} elseif ($sum_table_365[$x] == 1) {
				print("<TD align=center><CENTER>1</CENTER></TD>\n");
			} else {
				print("<TD align=center><CENTER>-</CENTER></TD>\n");
			}
			$sub_sum[5] += $sum_table_365[$x];

			if ($sum_table_500[$x] > 1) {
				print("<TD BGCOLOR=\"#CCFFFF\" align=center><CENTER><b>$sum_table_500[$x]</b></CENTER></TD>\n");
			} elseif ($sum_table_500[$x] == 1) {
				print("<TD align=center><CENTER>1</CENTER></TD>\n");
			} else {
				print("<TD align=center><CENTER>-</CENTER></TD>\n");
			}
			$sub_sum[6] += $sum_table_500[$x];

			if ($sum_table_1000[$x] > 1) {
				print("<TD BGCOLOR=\"#CCFFFF\" align=center><CENTER><b>$sum_table_1000[$x]</b></CENTER></TD>\n");
			} elseif ($sum_table_1000[$x] == 1) {
				print("<TD align=center><CENTER>1</CENTER></TD>\n");
			} else {
				print("<TD align=center><CENTER>-</CENTER></TD>\n");
			}
			$sub_sum[7] += $sum_table_1000[$x];

			if ($sum_table_5000[$x] > 1) {
				print("<TD BGCOLOR=\"#CCFFFF\" align=center><CENTER><b>$sum_table_5000[$x]</b></CENTER></TD>\n");
			} elseif ($sum_table_5000[$x] == 1) {
				print("<TD align=center><CENTER>1</CENTER></TD>\n");
			} else {
				print("<TD align=center><CENTER>-</CENTER></TD>\n");
			}
			$sub_sum[8] += $sum_table_5000[$x];

			if ($sum_table_date_prev[$x] == "1962-08-17") {
				print("<TD align=center><center>-</center></TD>\n");
			} elseif ($sum_table_date_prev[$x] < "2006-01-01") {
				print("<TD align=center><FONT COLOR=\"#ff0000\">$sum_table_date_prev[$x]</FONT></TD>\n");
			} elseif ($sum_table_date_prev[$x] < "2007-01-01") {
				print("<TD align=center><FONT COLOR=\"#ff6600\">$sum_table_date_prev[$x]</FONT></TD>\n");
			} else {
				print("<TD align=center><FONT COLOR=\"#000000\">$sum_table_date_prev[$x]</FONT></TD>\n");
			}

			if ($sum_table_date[$x] == "1962-08-17") {
				print("<TD align=center><center>-</center></TD>\n");
			} elseif ($sum_table_date[$x] < "2006-01-01") {
				print("<TD><FONT COLOR=\"#ff0000\" align=center>$sum_table_date[$x]</FONT></TD>\n");
			} elseif ($sum_table_date[$x] < "2007-01-01") {
				print("<TD><FONT COLOR=\"#ff6600\" align=center>$sum_table_date[$x]</FONT></TD>\n");
			} else {
				print("<TD><FONT COLOR=\"#000000\" align=center>$sum_table_date[$x]</FONT></TD>\n");
			}

			$sum_temp_30 = number_format(($sum_table_30[$x]/30)*100,1);

			if ($sum_temp_30 >= 1.0)
			{
				print("<TD align=right align=center><font size=\"-1\"><b>$sum_temp_30 %</b></font></TD>\n");
			} else {
				print("<TD align=right align=center><font size=\"-1\">$sum_temp_30 %</font></TD>\n");
			}

			$sum_temp_365 = number_format(($sum_table_365[$x]/365)*100,1);

			if ($sum_temp_365 >= 1.0)
			{
				print("<TD align=right align=center><font size=\"-1\"><b>$sum_temp_365 %</b></font></TD>\n");
			} else {
				print("<TD align=right align=center><font size=\"-1\">$sum_temp_365 %</font></TD>\n");
			}

			$sum_temp_1000 = number_format(($sum_table_1000[$x]/1000)*100,1);

			if ($sum_temp_1000 >= 1.0)
			{
				print("<TD align=center><font size=\"-1\"><b>$sum_temp_1000 %</b></font></TD>\n");
			} else {
				print("<TD align=center><font size=\"-1\">$sum_temp_1000 %</font></TD>\n");
			}

			$sum_temp_5000 = number_format(($sum_table_5000[$x]/$num_rows_all)*100,1);

			if ($sum_temp_5000 >= 1.0)
			{
				print("<TD align=right align=center><font size=\"-1\"><b>$sum_temp_5000 %</b></font></TD>\n");
			} else {
				print("<TD align=right align=center><font size=\"-1\">$sum_temp_5000 %</font></TD>\n");
			}

			if ($game == 6)
			{
				$weighted_average = (
					($sum_table_10[$x]/10*100*0.05) +
					($sum_table_30[$x]/30*100*0.10) +
					#($sum_table_50[$x]/50*100*0.10) +
					($sum_table_100[$x]/100*100*0.20) +
					#($sum_table_200[$x]/100*100*0.05) +
					($sum_table_365[$x]/365*100*0.25) +
					($sum_table_500[$x]/500*100*0.20) +
					#($sum_table_1000[$x]/1000*100*0.05) +
					($sum_table_1000[$x]/$num_rows_all*100*0.10));
			} else {
				$weighted_average = (
				($sum_table_10[$x]/10*100*0.05) +
				($sum_table_30[$x]/30*100*0.10) +
				#($sum_table_50[$x]/50*100*0.06) +
				($sum_table_100[$x]/100*100*0.15) +
				#($sum_table_200[$x]/200*100*0.15) + // extra
				($sum_table_365[$x]/365*100*0.20) +
				($sum_table_500[$x]/500*100*0.10) +
				($sum_table_1000[$x]/1000*100*0.20) +
				($sum_table_5000[$x]/$num_rows_all*100*0.20));

				if ($sum_table_10[$x] > 0)
				{
					$temp10 = $sum_table_10[$x] - 1;
				} else {
					$temp10 = 0;
				}

				if ($sum_table_30[$x] > 0)
				{
					$temp30 = $sum_table_30[$x] - 1;
				} else {
					$temp30 = 0;
				}

				if ($sum_table_50[$x] > 0)
				{
					$temp50 = $sum_table_50[$x] - 1;
				} else {
					$temp50 = 0;
				}

				if ($sum_table_100[$x] > 0)
				{
					$temp100 = $sum_table_100[$x] - 1;
				} else {
					$temp100 = 0;
				}

				if ($sum_table_200[$x] > 0)
				{
					$temp200 = $sum_table_200[$x] - 1;
				} else {
					$temp200 = 0;
				}

				if ($sum_table_365[$x] > 0)
				{
					$temp365 = $sum_table_365[$x] - 1;
				} else {
					$temp365 = 0;
				}

				if ($sum_table_500[$x] > 0)
				{
					$temp500 = $sum_table_500[$x] - 1;
				} else {
					$temp500 = 0;
				}

				if ($sum_table_1000[$x] > 0)
				{
					$temp1000 = $sum_table_1000[$x] - 1;
				} else {
					$temp1000 = 0;
				}

				if ($sum_table_5000[$x] > 0)
				{
					$temp5000 = $sum_table_5000[$x] - 1;
				} else {
					$temp5000 = 0;
				}

				#print "sum_table_5000[$x] = $sum_table_5000[$x]<br>";
				#print "temp5000 = $temp5000<br>";

				$weighted_average_prev = (
				($temp10/10*100*0.05) +
				($temp30/30*100*0.10) +
				#($temp50/50*100*0.06) +
				($temp100/100*100*0.15) +
				#($temp200/200*100*0.15) + // extra
				($temp365/365*100*0.20) +
				($temp500/500*100*0.10) +
				($temp1000/1000*100*0.20) +
				($temp5000/($num_rows_all-1)*100*0.20));

				#print "weighted_average_prev = $weighted_average_prev<br>";
			}

			$sum_temp_wa = number_format($weighted_average,1);

			$sum_temp_wa_prev = number_format($weighted_average_prev,1);

			if ($sum_temp_wa >= 1.0)
			{
				print("<TD align=right align=center><font size=\"-1\" align=center><b>$sum_temp_wa %</b></font></TD>\n");
			} else {
				print("<TD align=right align=center><font size=\"-1\" align=center>$sum_temp_wa %</font></TD>\n");
			}

			if ($sum_temp_wa_prev >= 1.0)
			{
				print("<TD align=right align=center><font size=\"-1\"><center><b>$sum_temp_wa_prev %</b></center></font></TD>\n");
			} else {
				print("<TD align=right align=center><font size=\"-1\"><center>$sum_temp_wa_prev %</center></font></TD>\n");
			}

			print("</TR>\n");

			if ($x == 49 || $x == 59 || $x == 69 || $x == 79 || $x == 89 ||
				$x == 99 || $x == 109 || $x == 119 || $x == 129 || $x == 139 ||
				$x == 149 || $x == 159 || $x == 169 || $x == 179 || $x == 189 ||
				$x == 199 || $x == 209 || $x == 219 || $x == 229)
			{
				print("<TR>\n");
				print("<TD>&nbsp;</TD>\n");
				print("<TD><center>$sub_sum_tot</center></TD>\n");
				print("<TD><center>$sub_sum[0]</center></TD>\n");
				print("<TD><center>$sub_sum[1]</center></TD>\n");
				print("<TD><center>$sub_sum[2]</center></TD>\n");
				print("<TD><center>$sub_sum[3]</center></TD>\n");
				print("<TD><center>$sub_sum[4]</center></TD>\n");
				print("<TD><center>$sub_sum[5]</center></TD>\n");
				print("<TD><center>$sub_sum[6]</center></TD>\n");
				print("<TD><center>$sub_sum[7]</center></TD>\n");
				print("<TD><center>$sub_sum[8]</center></TD>\n");
				print("<TD><center>&nbsp;</center></TD>\n");
				print("<TD><center>&nbsp;</center></TD>\n");

				$sub_temp_30 = number_format(($sub_sum[1]/30)*100,1);

				if ($sub_temp_30 >= 10.0)
				{
					print("<TD align=right><font size=\"-1\" align=center><b>$sub_temp_30 %</b></font></TD>\n");
				} else {
					print("<TD align=right><font size=\"-1\" align=center>$sub_temp_30 %</font></TD>\n");
				}

				$sub_temp_365 = number_format(($sub_sum[5]/365)*100,1);

				if ($sub_temp_365 >= 10.0)
				{
					print("<TD align=right><font size=\"-1\" align=center><b>$sub_temp_365 %</b></font></TD>\n");
				} else {
					print("<TD align=right><font size=\"-1\" align=center>$sub_temp_365 %</font></TD>\n");
				}

				$sub_temp_1000 = number_format(($sub_sum[7]/1000)*100,1);

				if ($sub_temp_1000 >= 10.0)
				{
					print("<TD align=right><font size=\"-1\" align=center><b>$sub_temp_1000 %</b></font></TD>\n");
				} else {
					print("<TD align=right><font size=\"-1\" align=center>$sub_temp_1000 %</font></TD>\n");
				}

				$sub_temp_5000 = number_format(($sub_sum[8]/$num_rows_all)*100,1);

				if ($sub_temp_5000 >= 10.0)
				{
					print("<TD align=right><font size=\"-1\" align=center><b>$sub_temp_5000 %</b></font></TD>\n");
				} else {
					print("<TD align=right><font size=\"-1\" align=center>$sub_temp_5000 %</font></TD>\n");
				}

				$weighted_average_sub = (
				($sum_table_10[$x]/10*100*0.05) +
				($sum_table_30[$x]/30*100*0.10) +
				#($sum_table_50[$x]/50*100*0.06) +
				($sum_table_100[$x]/100*100*0.15) +
				#($sum_table_200[$x]/200*100*0.15) + // extra
				($sum_table_365[$x]/365*100*0.20) +
				($sum_table_500[$x]/500*100*0.10) +
				($sum_table_1000[$x]/1000*100*0.20) +
				($sum_table_5000[$x]/$num_rows_all*100*0.20));

				#print("<TD><center>&nbsp;</center></TD>\n");
				#print("<TD><center>&nbsp;</center></TD>\n");
				print("<TD><center>&nbsp;</center></TD>\n");
				print("<TD><center>&nbsp;</center></TD>\n");
				print("</TR>\n");
				
				print("<TR>\n");
				print("<TD BGCOLOR=\"#eeeeee\">Sum</TD>\n");
				print("<TD BGCOLOR=\"#eeeeee\">Count</TD>\n");
				print("<TD BGCOLOR=\"#eeeeee\"><center>10</center></TD>\n");
				print("<TD BGCOLOR=\"#eeeeee\"><center><b>30</b></center></TD>\n");
				print("<TD BGCOLOR=\"#eeeeee\"><center>50</center></TD>\n");
				print("<TD BGCOLOR=\"#eeeeee\"><center>100</center></TD>\n");
				print("<TD BGCOLOR=\"#eeeeee\"><center>200</center></TD>\n");
				print("<TD BGCOLOR=\"#CCCCCC\"><center><b>365</b></center></TD>\n");
				print("<TD BGCOLOR=\"#CCCCCC\"><center>500</center></TD>\n");
				print("<TD BGCOLOR=\"#CCCCCC\"><center>1000</center></TD>\n");
				print("<TD BGCOLOR=\"#CCCCCC\"><center>5000</center></TD>\n");
				print("<TD BGCOLOR=\"#eeeeee\"><center>Prev</center></TD>\n");
				print("<TD BGCOLOR=\"#eeeeee\"><center>Last</center></TD>\n");
				print("<TD BGCOLOR=\"#CCCCCC\" align=center>30</TD>\n");
				print("<TD BGCOLOR=\"#CCCCCC\" align=center>365</TD>\n");
				print("<TD BGCOLOR=\"#CCCCCC\" align=center>1000</TD>\n");
				print("<TD BGCOLOR=\"#CCCCCC\" align=center>$num_rows_all</TD>\n");
				print("<TD BGCOLOR=\"#CCCCCC\" align=center>wa</TD>\n");
				print("<TD BGCOLOR=\"#CCCCCC\" align=center>wa_prev</TD>\n");
				print("</TR>\n");

				$sub_sum = array_fill (0,9, 0);
				$sub_sum_tot = 0;
			}

			$query4 = "Insert INTO $draw_prefix";
			$query4 .= "sum_table ";
			$query4 .= "VALUES ( '0', ";
			$query4 .= "         $x, ";
			$query4 .= "         $sum_table_10[$x], ";
			$query4 .= "         $sum_table_30[$x], ";
			$query4 .= "         $sum_table_50[$x], ";
			$query4 .= "         $sum_table_100[$x], ";
			$query4 .= "         $sum_table_200[$x], ";
			$query4 .= "         $sum_table_365[$x], ";
			$query4 .= "         $sum_table_500[$x], ";
			$query4 .= "         $sum_table_1000[$x], ";
			$query4 .= "         $sum_table_5000[$x], ";
			$query4 .= "         $sum_temp_30, ";
			$query4 .= "         $sum_temp_365, ";
			$query4 .= "         $sum_temp_1000, ";
			$query4 .= "         $sum_temp_5000, ";
			$query4 .= "         $weighted_average, ";
			$query4 .= "         $weighted_average_prev, ";
			$query4 .= "         '$sum_table_date_prev[$x]', ";
			$query4 .= "	     '$sum_table_date[$x]') ";

			#print "$query4<p>";
			
			$mysqli_result4 = mysqli_query($mysqli_link, $query4) or die (mysqli_error($mysqli_link));
		}

		print("</TABLE>\n");

		print "<h3>Table <font color=\"#ff0000\">{$draw_prefix}sum_table</font> Updated!</h3>";
	}
	
	print("</table>");

	print("<h2>Count = $count</h2>");

	print("</body>");
	print("</html>");

?>