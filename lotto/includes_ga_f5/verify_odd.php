<?php
	function verify_sum ($limit)
	{ 
		global $debug, $game, $game_name, $balls, $balls_drawn, $mega_balls, $draw_table_name, $draw_prefix, $game_includes, $hml, $range_low, $range_high, $eo50_sum, $eo50_even, $eo50_odd, $eo50_d2_1, $eo50_d2_2, $combin;
	
		require ("includes/mysqli.php");
		#require ('includes/db.class.php');

		#require ("$game_includes/config.incl");

		require ("includes/dateDiffInDays.php");
		require ("includes/unix.incl");

		print("<a name=\"$limit\"><H2>Lotto Verify Sum - $game_name - Limit $limit</H2></a>\n");

		#require ("includes_ga_f5/display4nav.incl");

		// initalize variables [include]
		require ("includes/init_display.incl");
		
		//start table
		print("<TABLE BORDER=\"1\">\n");
	
		//create header row
		print("<TR><B>\n");
		print("<TD BGCOLOR=\"#CCCCCC\" width=20>&nbsp;</TD>\n");
		print("<TD BGCOLOR=\"#57FC39\">Pass (80-129)</TD>\n");
		print("<TD BGCOLOR=\"#57FC39\">Pass %</TD>\n");
		print("<TD BGCOLOR=\"#FC5B39\">Fail (80-129)</TD>\n");
		print("<TD BGCOLOR=\"#FC5B39\">Fail %</TD>\n");
		
		print("</B></TR>\n");

		$today_unix = time ();

		#echo "today_unix = $today_unix<br>";

		$date_temp_seconds = $last_draw_unix - (86400*($limit+1));	

		#echo "<h2>date_temp = "; 
		#echo date('Y/m/d', $date_temp_seconds);
		#echo " for $date_temp</h2>";
					
		// Start date 
		$date_temp = date('Y/m/d', $date_temp_seconds); 

		#echo "date_temp = $date_temp<br>";

		// get count draw table
		$query5 = "SELECT count(*) FROM $draw_table_name ";
		$query5 .= "WHERE date >= '$date_temp' ";
		$query5 .= "AND (sum BETWEEN 90 and 129) ";

		#echo "$query5<br>";
	
		$mysqli_result5 = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));

		$row5 = mysqli_fetch_array($mysqli_result5);

		$temp1 = ($row5[0]/$limit)*100;

		$temp2 = $limit-$row5[0];

		$temp3 = ((($limit-$row5[0])/$limit)*100);

		print("<TR>\n");
		
		print("<TD align=center><b>$dcount</b></TD>\n");
		print("<TD align=center>$row5[0]</TD>\n");
		print("<TD align=center>$temp1 %</TD>\n");
		print("<TD align=center>$temp2</TD>\n");
		print("<TD align=center>$temp3 %</TD>\n");
	}
?>