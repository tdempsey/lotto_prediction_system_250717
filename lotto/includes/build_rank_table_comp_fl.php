<?php
	function BuildRankTableComp($date)
	{
		global $debug;

		require ("includes/mysqli.php");

		if ($debug)
		{
			print("BuildRankTableComp - $date<br>");
		}

		for($index=1; $index <= 53; $index++)
		{
			$num_array[$index]=0;
			$num_date[$index]="1962-08-17";
		}

		$query = "DROP TABLE IF EXISTS fl_rank_comp";

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

		$query = "CREATE TABLE fl_rank_comp ( ";
		$query .= "num tinyint(3) unsigned NOT NULL, ";
		$query .= "count tinyint(3) unsigned NOT NULL, ";
		$query .= "date date NOT NULL default '1962-08-17', ";
		$query .= "PRIMARY KEY (num), ";
		$query .= "KEY num (num), ";
		$query .= "UNIQUE num_2 (num) ";
		$query .= ") ";

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

		// get everything from catalog table
		$query2 = "SELECT * FROM fl_draws ";
		$query2 .= "WHERE date < '$date' ";
		$query2 .= "ORDER BY date DESC ";
		$query2 .= "LIMIT 26 ";

		$mysqli_result2 = mysqli_query($mysqli_link, $query2) or die (mysqli_error($mysqli_link));

		if ($debug)
		{
			$num_rows = mysqli_num_rows($mysqli_result2);
			print "fl_draws - $num_rows rows<br>";
		}

		while($row = mysqli_fetch_row($mysqli_result2))
		{
			$PICK1 = $row[1];
			$PICK2 = $row[2];
			$PICK3 = $row[3];
			$PICK4 = $row[4];
			$PICK5 = $row[5];
			$PICK6 = $row[6];
			
			$num_array[$PICK1]++;
			if ($num_date[$PICK1] == "1962-08-17")
			{
				$num_date[$PICK1] = $row[0];
			}
			$num_array[$PICK2]++;
			$num_date[$PICK2] = $row[0];
			$num_array[$PICK3]++;
			$num_date[$PICK3] = $row[0];
			$num_array[$PICK4]++;
			$num_date[$PICK4] = $row[0];
			$num_array[$PICK5]++;
			$num_date[$PICK5] = $row[0];
			$num_array[$PICK6]++;
			$num_date[$PICK6] = $row[0];
		}

		for($index=1; $index <= 53; $index++)
		{
			$query = "INSERT INTO fl_rank_comp ";
			$query .= "VALUES ('$index', ";
			$query .= "'$num_array[$index]', ";
			$query .= "'$num_date[$index]')";

			$mysqli_result3 = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));
		}
		
		return 0;
	}
?>
