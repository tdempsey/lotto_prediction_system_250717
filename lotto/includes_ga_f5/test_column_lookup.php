<?php
	function test_column_lookup($cola,$colb,$x,$y)
    {
		global $debug, $game, $draw_prefix; 

		//log_info("test_column_lookup -  start\n");

		//$game = 6;

		require("includes/mysqli.php");

		$query_col = "SELECT count FROM ";
		$query_col .= "$draw_prefix";
		$query_col .= "temp_col_";
		$query_col .= "$cola";
		$query_col .= "_";
		$query_col .= "$colb ";
		$query_col .= "WHERE num1 = $x ";
		$query_col .= "and   num2 = $y ";

		#print "$query_col<p>";

		$mysqli_result_col = mysqli_query($query_col, $mysqli_link) or die (mysqli_error($mysqli_link));

		$row = mysqli_fetch_array($mysqli_result_col);

		$temp = $row[count];

		//log_info("test_column_lookup -  temp = $temp\n");
		//log_info("test_column_lookup -  end\n");

		return $temp;
    }
?>