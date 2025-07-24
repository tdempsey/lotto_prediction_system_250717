<?php
   function LastxDraws($date,$limit)
   {		
		global $debug;

		require ("includes/mysqli.php");

		// get everything from catalog table
		$query_ld = "SELECT * FROM ga_f5_draws ";
		$query_ld .= "WHERE date < '$date' ";
		$query_ld .= "ORDER BY date DESC ";
		$query_ld .= "LIMIT $limit ";

		#echo "$query_ld<p>";
		
		$mysqli_result_ld = mysqli_query($mysqli_link, $query_ld) or die (mysqli_error($mysqli_link));
	
		#$array[0] = 0;
		$array = array (0);
		$x = 1;

		while($row = mysqli_fetch_array($mysqli_result_ld))
		{
		
			for ($index = 1; $index <= 6; $index++)
			{
				$array[$x] = $row[$index];
				$x++;
			}
		}

		sort ($array);

		for ($y = 0; $y <= count($array)-2; $y++)
		{
			if ($array[$y] ==  $array[$y+1])
			{
				array_splice($array,$y,1);
				$y--;
				$x--;
			}
		}

		return $array;
   }
?>