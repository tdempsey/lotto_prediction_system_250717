<?php
	function BuildColumnLimitTable($column)
	{
		global $debug, $game, $balls, $draw_prefix, $balls_drawn;

		// error checking ----------------------------------------------------------------------------------------------
		if ($column == NULL || $column == 0)
		{
			exit("<h2>Error - function build_column_limit_table.php - <font color=\"#FF0000\">date undefined</font></h2>");
		}

		// error checking ----------------------------------------------------------------------------------------------

		require ("includes/mysqli.php");

		//$temp_array = array 

		$column_array = array_fill(0, $balls+1 , 0.0);

		$query = "SELECT * FROM $draw_prefix";
		$query .= "column_$column ";
		$query .= "ORDER BY num ASC ";

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

		//print "$query<br>";

		$z = 1;

		while($row = mysqli_fetch_array($mysqli_result))
		{
			$column_array[$row[num]] = $row[percent_wa];

			//print "$z = {$column_array[$z]}<br>";
			$z++;
		}

		//print_r ($column_array);
		
		return $column_array;
	}
?>
