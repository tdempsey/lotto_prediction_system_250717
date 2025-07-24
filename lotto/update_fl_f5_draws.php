<?php
	$game = 4; // Florida Fantasy 5

	// include to connect to database
	require ("includes/mysqli.php");
	require ("includes/count_2_seq.php");
	require ("includes/count_3_seq.php");
	#require ("includes_fl/build_rank_table_fl.php");
	#require ("includes_fl/calculate_rank_fl.php");
	require ("includes_fl/last_draws_fl.php");
	require ("includes_fl/combin.incl");

	//start HTML page
	print("<HTML>\n");
	print("<HEAD>\n");
	print("<TITLE>Update Combo 5/36 </TITLE>\n");
	print("</HEAD>\n");
	
	print("<BODY bgcolor=\"#FFFFFF\" text=\"#000000\">\n");

	print("<p align=\"center\"><b><font face=\"Arial, Helvetica, sans-serif\">Update Combo 5/36</font></b></p>");

	$curr_date = date('Y-m-d');

	$query = "SELECT * FROM combo_5_36 ";
	$query .= "WHERE devsq = '0.0' ";

	$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

	include_once 'C:\wamp\www\lotto\PEAR\Math\Stats.php';

	$count = 0;
	$display_count = 0;

	while($row = mysqli_fetch_array($mysqli_result))
	{
		#if ($row[devsq] == 0.0)
		#{
			$draw_array = array ($row[b1],$row[b2],$row[b3],$row[b4],$row[b5]);

			$s = new Math_Stats();
			$s->setData($draw_array);
			$stats = $s->calcBasic();

			$draw_average = $row[sum]/5;
			$median = $row[b3];
			$harmean = $s->harmonicMean();
			$quart1 = ($row[b1]+$row[b2])/2;
			$quart2 = ($row[b1]+$row[b2]+$row[b3])/2;
			$quart3 = ($row[b1]+$row[b2]+$row[b3]+$row[b4])/2;
			$stdev = $s->stDev();
			$variance = $s->variance();
			$avedev = $s->__calcAbsoluteDeviation();
			$kurtosis = $s->kurtosis();
			$skew = $s->skewness();
			$geomean = $s->geometricMean();
			$devsq = calc_devsq ($draw_array,$draw_average);

			$query9 = "UPDATE combo_5_36 ";
			$query9 .= "SET draw_average = $draw_average, ";
			$query9 .= "    median = $median, ";
			$query9 .= "    harmean = $harmean, ";
			$query9 .= "    quart1 = $quart1, ";
			$query9 .= "    quart2 = $quart2, ";
			$query9 .= "    quart3 = $quart3, ";
			$query9 .= "    stdev = $stdev, ";
			$query9 .= "    variance = $variance, ";
			$query9 .= "    avedev = $avedev, ";
			$query9 .= "    kurt = $kurtosis, ";
			$query9 .= "    skew = $skew, ";
			$query9 .= "    geomean = $geomean, ";
			$query9 .= "    devsq = $devsq ";
			#$query9 .= "WHERE date = '$row[date]' ";
			$query9 .= "WHERE b1 = $row[b1] ";
			$query9 .= "AND   b2 = $row[b2] ";
			$query9 .= "AND   b3 = $row[b3] ";
			$query9 .= "AND   b4 = $row[b4] ";
			$query9 .= "AND   b5 = $row[b5] ";

			#print "$query9<p>";
			#die();
			$count++;

			if ($display_count == 1000)
			{
				$display_count = 0;
				echo "$query9<p>";
			} else {
				$display_count++;
			}
			
			$mysqli_result9 = mysqli_query($mysqli_link, $query9) or die (mysqli_error($mysqli_link));

			set_time_limit(0);
		#}
	}

	function calc_devsq ($draw,$average)
	{
		$average = array_sum($draw)/5;
		$devsq = 0.0;
		for ($x = 0; $x < 5; $x++)
		{
			$temp = $draw[$x]-$average;
			$devsq += $temp*$temp;
		}

		#echo "devsq = $devsq<p>";

		return $devsq;
	}

	print("</table>");

	print("<h2>Count = $count</h2>");

	print("</body>");
	print("</html>");

?>