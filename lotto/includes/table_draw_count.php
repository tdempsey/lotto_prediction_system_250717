<?php
	function TableDrawCount($table,$draw,&$num_rows)
	{
		require ("includes/mysqli.php");

		global $debug;

		// error checking ----------------------------------------------------------------------------------------------	
		if (is_null($draw) || count($draw) == 0)
		{
			exit("<h2>Error - function table_draw_count.php - <font color=\"#FF0000\">draw undefined</font></h2>");
		}
		
		if (is_null($table))
		{
			exit("<h2>Error - function table_draw_count.php - <font color=\"#FF0000\">parameter undefined - table = $table, num_rows = $num_rows</font></h2>");
		}
		// error checking ----------------------------------------------------------------------------------------------

		$nums = array();
		
		if (!TableExists($table)) 
		{
			$query1 = "CREATE TABLE $table ( ";
			$query1 .= "num tinyint(3) unsigned NOT NULL ";
			$query1 .= ") ";

			$mysqli_result1 = mysqli_query($mysqli_link, $query1) or die (mysqli_error($mysqli_link));

			$quer2 = "INSERT INTO $table_temp ";
			$query2 .= "VALUES ('$num')";
	
			mysqli_query($mysqli_link, $query2) or die (mysqli_error($mysqli_link));
		}

		$x = 0;
		$count = 0;

		// select num from table
		$query = "SELECT num FROM $table ";

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

		$num_rows = mysqli_num_rows($mysqli_result);

		// build num array 
		while($row = mysqli_fetch_array($mysqli_result))
		{
			$nums[$x] = $row[num];
			$x++;
		}

		// count number of draws in num table
		for ($x = 0; $x <= count($draw)-1; $x++)
		{
			if (in_array($draw[$x], $nums))
			{
				$count++;
			}
		}

		return $count;
	}
?>
