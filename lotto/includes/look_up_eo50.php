<?php

function LookUpEO50($id,&$even,&$odd,&$d2_1,&$d2_2)
   {
		global $debug,$draw_prefix;

		// error checking ----------------------------------------------------------------------------------------------	
		if (is_null($id) || is_null($even) || is_null($odd) || is_null($d2_1) || is_null($d2_2))
		{
			exit("<h2>Error - function look_up_eo50.php - <font color=\"#FF0000\">parameter undefined - id = $id, even = $even, odd = $odd, d2_1 = $d2_1, d2_2 = $d2_2</font></h2>");
		}
		// error checking ----------------------------------------------------------------------------------------------
		
		require("includes/mysqli.php");

		$query_eo50 = "SELECT * FROM $draw_prefix";
		$query_eo50 .= "eo50 ";
		$query_eo50 .= "WHERE id = $id ";

		//print "$query_eo50 = $$query_eo50<p>";

		$mysqli_result_eo50 = mysqli_query($query_eo50, $mysqli_link) or die (mysqli_error($mysqli_link));
		
		$num_rows = mysqli_num_rows($mysqli_result_eo50);

		log_info("LookUpEO50 num_rows = $num_rows for $id\n");
		
		if ($num_rows != 0)
		{
			$row = mysqli_fetch_array($mysqli_result_eo50);
			$even = $row[even];
			$odd = $row[odd];
			$d2_1 = $row[d2_1];
			$d2_2 = $row[d2_2];
		}

		return $num_rows;
   }
?>