<?php

	$game = 1; // Georgia F5

	require ("includes/games_switch.incl");

	// include to connect to database
	require ("includes/mysqli.php");
	require ("includes/count_2_seq_4.php");
	require ("includes/count_3_seq_4.php");
	#require ("includes_fl/build_rank_table_fl.php");
	#require ("includes_fl/calculate_rank_fl.php");
	require ("includes/first_draw_unix.php");
	require ("includes/last_draw_unix.php");

	//start HTML page
	print("<HTML>\n");
	print("<HEAD>\n");
	print("<TITLE>Check Georgia Fantasy 5 Draw Results</TITLE>\n");
	print("</HEAD>\n");
	
	print("<body bgcolor=\"#FFFFFF\" text=\"#000000\">\n");

	require ("includes/unix.incl");

	$check_date = '2014-11-02';
	
	$win3 = 10;
	$win4 = 100;
	$win5 = 151000;

	$total = 0;
	$free = 0;

	print "check_date = $check_date<p>";

	$query1 = "SELECT * FROM ga_f5_draws ";
	$query1 .= "WHERE date = '$check_date' ";

	print "$query1<p>";

	$mysqli_result1 = mysqli_query($mysqli_link, $query1) or die (mysqli_error($mysqli_link));

	$row1 = mysqli_fetch_array($mysqli_result1);
	
	$query2 = "SELECT * FROM combo_5_39_ends_dup ";
	$query2 .= "ORDER BY sum ASC ";

	print "$query2<p>";

	$mysqli_result2 = mysqli_query($mysqli_link, $query2) or die (mysqli_error($mysqli_link));

	$num_rows_all = mysqli_num_rows($mysqli_result2);

	echo "num_rows_all = $num_rows_all<p>";

	echo "<h2>draw = $row1[b1],$row1[b2],$row1[b3],$row1[b4],$row1[b5]</h2>";

	print "<table border=1>";

	print "<tr>";
	print "<td align=center>d1</td>";
	print "<td align=center>d2</td>";
	print "<td align=center>d3</td>";
	print "<td align=center>d4</td>";
	print "<td align=center>d5</td>";
	print "<td align=center>sum</td>";
	print "<td align=center>2</td>";
	print "<td align=center>3</td>";
	print "<td align=center>4</td>";
	print "<td align=center>5</td>";
	print "</tr>";

	while ($row2 = mysqli_fetch_array($mysqli_result2)) 
	{
		#echo "result = $row1[b1],$row1[b2],$row1[b3],$row1[b4],$row1[b5]<br>";
		#echo "draw = $row2[b1],$row2[b2],$row2[b3],$row2[b4],$row2[b5]<p>";
		#test5
		if ($row2[b1] == $row1[b1] &&
			$row2[b2] == $row1[b2] &&
			$row2[b3] == $row1[b3] &&
			$row2[b4] == $row1[b4] &&
			$row2[b5] == $row1[b5]) 
			{
			print "<tr>";
			print "<td align=center>$row2[b1]</td>";
			print "<td align=center>$row2[b2]</td>";
			print "<td align=center>$row2[b3]</td>";
			print "<td align=center>$row2[b4]</td>";
			print "<td align=center>$row2[b5]</td>";
			print "<td align=center>$row2[sum]</td>";
			print "<td align=center>---</td>";
			print "<td align=center>---</td>";
			print "<td align=center>---</td>";
			print "<td align=center>$win5</td>";
			print "</tr>";

			$total += $win5;
		} elseif (($row2[b1] == $row1[b1] && $row2[b2] == $row1[b2] && $row2[b3] == $row1[b3] && $row2[b4] == $row1[b4]) or 
			      ($row2[b1] == $row1[b1] && $row2[b2] == $row1[b2] && $row2[b3] == $row1[b3] && $row2[b5] == $row1[b5]) or
			      ($row2[b1] == $row1[b1] && $row2[b2] == $row1[b2] && $row2[b4] == $row1[b4] && $row2[b5] == $row1[b5]) or
			      ($row2[b1] == $row1[b1] && $row2[b3] == $row1[b3] && $row2[b4] == $row1[b4] && $row2[b5] == $row1[b5]) or
			      ($row2[b2] == $row1[b2] && $row2[b3] == $row1[b3] && $row2[b4] == $row1[b4] && $row2[b5] == $row1[b5]))
				  {
				  print "<tr>";
				  print "<td align=center>$row2[b1]</td>";
				  print "<td align=center>$row2[b2]</td>";
			      print "<td align=center>$row2[b3]</td>";
				  print "<td align=center>$row2[b4]</td>";
				  print "<td align=center>$row2[b5]</td>";
				  print "<td align=center>$row2[sum]</td>";
				  print "<td align=center>---</td>";
				  print "<td align=center>---</td>";
				  print "<td align=center>$win4</td>";
				  print "<td align=center>---</td>";
				  print "</tr>";

				  $total += $win4;
		} elseif (($row2[b1] == $row1[b1] && $row2[b2] == $row1[b2] && $row2[b3] == $row1[b3]) or 
			      ($row2[b1] == $row1[b1] && $row2[b2] == $row1[b2] && $row2[b4] == $row1[b4]) or
			      ($row2[b1] == $row1[b1] && $row2[b2] == $row1[b2] && $row2[b5] == $row1[b5]) or
			      ($row2[b1] == $row1[b1] && $row2[b3] == $row1[b3] && $row2[b4] == $row1[b4]) or
					($row2[b1] == $row1[b1] && $row2[b3] == $row1[b3] && $row2[b5] == $row1[b5]) or
					($row2[b1] == $row1[b1] && $row2[b4] == $row1[b4] && $row2[b5] == $row1[b5]) or
					($row2[b2] == $row1[b2] && $row2[b3] == $row1[b3] && $row2[b4] == $row1[b4]) or
					($row2[b2] == $row1[b2] && $row2[b3] == $row1[b3] && $row2[b5] == $row1[b5]) or
					($row2[b2] == $row1[b2] && $row2[b4] == $row1[b4] && $row2[b5] == $row1[b5]) or
			      ($row2[b3] == $row1[b3] && $row2[b4] == $row1[b4] && $row2[b5] == $row1[b5]))
				  {
				  print "<tr>";
				  print "<td align=center>$row2[b1]</td>";
				  print "<td align=center>$row2[b2]</td>";
			      print "<td align=center>$row2[b3]</td>";
				  print "<td align=center>$row2[b4]</td>";
				  print "<td align=center>$row2[b5]</td>";
				  print "<td align=center>$row2[sum]</td>";
				  print "<td align=center>---</td>";
				  print "<td align=center>$win3</td>";
				  print "<td align=center>---</td>";
				  print "<td align=center>---</td>";
				  print "</tr>";

				  $total += $win3;
		} elseif (($row2[b1] == $row1[b1] && $row2[b2] == $row1[b2]) or 
			      ($row2[b1] == $row1[b1] && $row2[b3] == $row1[b3]) or
			      ($row2[b1] == $row1[b1] && $row2[b4] == $row1[b4]) or
			      ($row2[b1] == $row1[b1] && $row2[b5] == $row1[b5]) or
					($row2[b2] == $row1[b2] && $row2[b3] == $row1[b3]) or
					($row2[b2] == $row1[b2] && $row2[b4] == $row1[b4]) or
					($row2[b2] == $row1[b2] && $row2[b5] == $row1[b5]) or
					($row2[b3] == $row1[b3] && $row2[b4] == $row1[b4]) or
					($row2[b3] == $row1[b3] && $row2[b5] == $row1[b5]) or
			      ($row2[b4] == $row1[b4] && $row2[b5] == $row1[b5]))
				  {
				  print "<tr>";
				  print "<td align=center>$row2[b1]</td>";
				  print "<td align=center>$row2[b2]</td>";
			      print "<td align=center>$row2[b3]</td>";
				  print "<td align=center>$row2[b4]</td>";
				  print "<td align=center>$row2[b5]</td>";
				  print "<td align=center>$row2[sum]</td>";
				  print "<td align=center>Free</td>";
				  print "<td align=center>---</td>";
				  print "<td align=center>---</td>";
				  print "<td align=center>---</td>";
				  print "</tr>";

				  $free += 1;
				  }
	}

	print "</table>";

	print "<h2>total = $total</h2>";
	print "<h2>free = $free</h2>";

	print("</body>");
	print("</html>");

?>