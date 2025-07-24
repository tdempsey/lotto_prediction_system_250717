<?php
	function BuildRankTable($date)
	{
		global $debug, $game, $balls, $draw_table_name, $balls_drawn, $hml, $range_low, $range_high;

		#echo "date = $date<br>";

		$debug = 0;

		// error checking ----------------------------------------------------------------------------------------------
		if ($date == NULL || $date == 0)
		{
			exit("<h2>Error - function build_rank_table.php - <font color=\"#FF0000\">date undefined</font></h2>");
		}

		// error checking ----------------------------------------------------------------------------------------------

		require ("includes/mysqli.php");

		$num_array = array_fill(0, $balls+1 , 0);

		$query = "SELECT * FROM $draw_table_name ";
		$query .= "WHERE date < '$date' ";
		if ($hml)
		{
			$query .= "AND   sum >= $range_low  ";
			$query .= "AND   sum <= $range_high  ";
		}
		$query .= "ORDER BY date DESC ";
		$query .= "LIMIT 30 "; #26

		#echo "<b>build_rank_table >>></b> $query<br>";

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

		if ($debug)
		{
			$num_rows = mysqli_num_rows($mysqli_result);
			#echo "draws - $num_rows rows<br>";
		}

		while($row = mysqli_fetch_array($mysqli_result))
		{
			for($z = 1; $z <= $balls_drawn; $z++)
			{
				$num_array[$row[$z]]++;
				#echo "num_array[$row[$z]] = {$num_array[$row[$z]]}<br>";
			}
		}
		
		#print_r($num_array);
		#echo "<br>";
		
		return $num_array;
	}

	/*
	function BuildRankTableNew($date)
	{
		global $debug, $game, $balls, $draw_table_name, $balls_drawn, $hml, $range_low, $range_high;

		// error checking ----------------------------------------------------------------------------------------------
		if ($date == NULL || $date == 0)
		{
			exit("<h2>Error - function build_rank_table.php - <font color=\"#FF0000\">date undefined</font></h2>");
		}

		// error checking ----------------------------------------------------------------------------------------------

		require ("includes/mysqli.php");

		$num_array = array_fill(0, $balls+1 , 0);

		$query = "SELECT * FROM ga_f5_temp_26  ";
		$query .= "ORDER BY count DESC ";

		#print "$query<br>";

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

		if ($debug)
		{
			$num_rows = mysqli_num_rows($mysqli_result);
			print "draws - $num_rows rows<br>";
		}

		while($row = mysqli_fetch_array($mysqli_result))
		{
			if ($row[count] < 6)
			{
				$num_array[$row[num]]++;
				#echo "num_array[$row[$z]] = {$num_array[$row[$z]]}<br>";
			}
		}
		
		return $num_array;
	}

	function BuildRankTableNoHML($date)
	{
		global $debug, $game, $balls, $draw_table_name, $balls_drawn, $hml, $range_low, $range_high;

		// error checking ----------------------------------------------------------------------------------------------
		if ($date == NULL || $date == 0)
		{
			exit("<h2>Error - function build_rank_table.php - <font color=\"#FF0000\">date undefined</font></h2>");
		}

		// error checking ----------------------------------------------------------------------------------------------

		require ("includes/mysqli.php");

		$num_array = array_fill(0, $balls+1 , 0);

		$query = "SELECT * FROM $draw_table_name ";
		$query .= "WHERE date < '$date' ";
		$query .= "ORDER BY date DESC ";
		$query .= "LIMIT 26 ";

		#print "$query<br>";

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

		if ($debug)
		{
			$num_rows = mysqli_num_rows($mysqli_result);
			print "draws - $num_rows rows<br>";
		}

		while($row = mysqli_fetch_array($mysqli_result))
		{
			for($z = 1; $z <= $balls_drawn; $z++)
			{
				$num_array[$row[$z]]++;
			}
		}
		
		return $num_array;
	}
	*/
?>
