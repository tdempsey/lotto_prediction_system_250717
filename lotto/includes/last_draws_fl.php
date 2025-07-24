<?php
   function LastxDraws($date,$limit)
   {		
		global $debug;

		// error checking ----------------------------------------------------------------------------------------------
		if (is_null($date) || is_null($limit))
		{
			exit("<h2>Error - function last_draws_fl.php - <font color=\"#FF0000\">date = $date, limit = $limit undefined</font></h2>");
		}
		// error checking ----------------------------------------------------------------------------------------------

		require ("includes/mysqli.php");

		// get everything from catalog table
		$query_ld = "SELECT * FROM fl_draws ";
		$query_ld .= "WHERE date < '$date' ";
		$query_ld .= "ORDER BY date DESC ";
		$query_ld .= "LIMIT $limit ";
		
		$mysqli_result_ld = mysqli_query($query_ld, $mysqli_link) or die (mysqli_error($mysqli_link));
	
		$x = 0;

		while($row = mysqli_fetch_array($mysqli_result_ld))
		{
		
			for ($index = 1; $index <= 6; $index++)
			{
				$array[$x] = $row[$index];
				$x++;
			}
		}

		sort ($array);

		//$result = array_unique($array);

		//print "<p>---------------- $date/$limit ------------------------</p>";
		//print_r ($array);

		for ($y = 0; $y <= count($array)-2; $y++)
		{
			if ($array[$y] ==  $array[$y+1])
			{
				array_splice($array,$y,1);
				$y--;
				$x--;
			}
		}
		/*
			print "there are $x last_draws (for $limit draws) < $date - ";
			for ($y = 0; $y < count($array); $y++)
			{
				$z = $array[$y];
				print "$array[$y] - ";
			}
			print "end<br>";
		*/
		return $array;
   }
?>