<?php
   function LastDraws4($combin,$date, $limit)
   {		
		global $debug, $game, $draw_table_name, $draw_prefix, $balls_drawn,$hml,$range_low,$range_high;
		
		require ("includes/mysqli.php");

		// error checking ----------------------------------------------------------------------------------------------
		if ($balls_drawn < 2 || is_null($balls_drawn))
		{
			exit("<h2>Error - function last_draws.php - <font color=\"#FF0000\">balls_drawn undefined - balls_drawn = $balls_drawn</font></h2>");
		}

		if (is_null($draw_table_name))
		{
			exit("<h2>Error - function last_draws.php - <font color=\"#FF0000\">draw_table_name undefined - draw_table_name = $draw_table_name</font></h2>");
		}

		if (is_null($limit))
		{
			exit("<h2>Error - function last_draws.php - <font color=\"#FF0000\">limit undefined - limit = $limit</font></h2>");
		}

		if (is_null($date))
		{
			exit("<h2>Error - function last_draws.php - <font color=\"#FF0000\">date undefined - date = $date</font></h2>");
		}
		// error checking ----------------------------------------------------------------------------------------------

		#echo "hml = $hml<br>";
		
		$query_ld = "SELECT * FROM $draw_prefix";
		$query_ld .= "draws_4 ";
		$query_ld .= "WHERE date < '$date' ";
		$query_ld .= "AND   combin = $combin ";
		if ($hml)
		{
			#$query_ld .= "AND   sum >= $range_low ";
			#$query_ld .= "AND   sum <= $range_high ";
		}
		$query_ld .= "ORDER BY date DESC ";
		$query_ld .= "LIMIT $limit ";

		#print("$query_ld<p>");
		
		$mysqli_result_ld = mysqli_query($mysqli_link, $query_ld) or die (mysqli_error($mysqli_link));

		$array = array();

		while($row = mysqli_fetch_array($mysqli_result_ld))
		{
			for ($index = 1; $index <= 4; $index++)
			{
				array_push($array, $row[b.$index]);
			}
		}

		$result = array_unique($array); 

		#print_r ($array);
		#echo "<br>";

		sort ($result);
		
		return $result;
   }
?>