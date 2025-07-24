<?php
	function Last10Count($date)
	{	
		global $debug, $draw_prefix, $balls_drawn, $draw_table_name;
		require ("includes/mysqli.php");

		$num_array = array_fill (0,70,0);
		
		$query = "SELECT * FROM $draw_table_name "; // test
		$query .= "WHERE date < '$date' ";
		$query .= "ORDER BY date DESC ";
		$query .= "LIMIT 10 ";

		//print "$query<br>";

		$mysqli_result = mysqli_query($mysqli_link, $query);

		// get each row
		while($row = mysqli_fetch_array($mysqli_result))
		{
			// fix 
			$PICK1 = $row[1];
			$PICK2 = $row[2];
			$PICK3 = $row[3];
			$PICK4 = $row[4];
			$PICK5 = $row[5];
			//$PICK6 = $row[b6];

			$num_array[$PICK1]++;
			$num_array[$PICK2]++;
			$num_array[$PICK3]++;
			$num_array[$PICK4]++;
			$num_array[$PICK5]++;
			//$num_array[$PICK6]++;
		}

		return $num_array;
	}
?>
