<?php
	function LookUpRank($num, $draw_prefix)
	{
		// error checking 						----------------------------------------------------------------------------------------------	
		if (is_null($num) || is_null($draw_prefix))
		{
			exit("<h2>Error - function look_up_rank.php - <font color=\"#FF0000\">parameter undefined - num = $num, draw_prefix = $draw_prefix</font></h2>");
		}
		// error checking ----------------------------------------------------------------------------------------------
		require("includes/mysqli.php");

		$table_temp = $draw_prefix . "temp";

		$query = "SELECT count FROM $table_temp ";
		$query .= "WHERE num=$num ";
		
		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

		$row = mysqli_fetch_row($mysqli_result);

		$count = $row[0];

		return $count;
	}
?>