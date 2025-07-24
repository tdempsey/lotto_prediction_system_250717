<?php
	function verify_display ($limit)
	{ 
		global $debug, $game, $game_name, $balls, $balls_drawn, $mega_balls, $draw_table_name, $draw_prefix, $game_includes, $hml, $range_low, $range_high, $eo50_sum, $eo50_even, $eo50_odd, $eo50_d2_1, $eo50_d2_2, $combin;
	
		#require ("includes/mysqli.php");
		#require ('includes/db.class.php');

		#require ("$game_includes/config.incl");

		print("<a name=\"$limit\"><H2>Lotto VerifySum/$combin - $game_name - Combin $combin - Limit $limit</H2></a>\n");

		#require ("includes_ga_f5/display4nav.incl");

		// initalize variables [include]
		require ("includes/init_display.incl");
		
		//start table
		print("<TABLE BORDER=\"1\">\n");
	
		//create header row
		print("<TR><B>\n");
		print("<TD BGCOLOR=\"#CCCCCC\" width=20>&nbsp;</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Date</center></TD>\n");

		print("<TD BGCOLOR=\"#CCCCCC\">Pass (80-129)</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Pass %</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Fail (80-129)</TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Fail %</TD>\n");
		
		print("</B></TR>\n");

		/*

		$today_unix = time ();

		$date_temp_seconds = $last_draw_unix - (86400*7);

		$date_temp = $today_unix - $date_temp_seconds;	        

		echo "<h2>date_temp  = "; 
		echo date(_'m/d/Y', $date_temp);
		echo " for $date_temp </h2>";
					_
		// Start date 
		$date1 = "$date_temp"; 
		  
		// End date 
		$date2 = date("Y-m-d"); 
		  
		// Function call to find date difference 
		$dateDiff = dateDiffInDays($date1, $date2); 
		  
		// Display the result 
		printf("Difference between $date1 and $date2: "
		   . $dateDiff . " Days<br> "); 

		die();

		*/

		// get from draw table
		$query5 = "SELECT * FROM ga_f5_draws_4 ";
		$query5 .= "WHERE combin = $combin ";
		$query5 .= "ORDER BY date DESC ";
		$query5 .= "LIMIT $limit "; 

		#echo "$query5<br>";
	
		$mysqli_result5a = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));
	}
?>