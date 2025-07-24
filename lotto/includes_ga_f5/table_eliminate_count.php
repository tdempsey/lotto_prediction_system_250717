<?php
	function TableEliminateCount($table,$x,$y)
	{
		require ("includes/mysqli.php");

		global $debug;

		$nums = array();

		if (TableExists($table)) {
			$query_pair = "SELECT * FROM fl_temp_all_2 ";
			$query_pair .= "WHERE num1 = $x ";
			$query_pair .= "AND num2 = $y ";
			$query_pair .= "AND count = 0 ";

			$mysqli_result_pair = mysqli_query($query_pair, $mysqli_link) or die (mysqli_error($mysqli_link));

			$num_rows = mysqli_num_rows($mysqli_result_pair);

			return $num_rows;
		}
		else {
			return -1;
		}
	}
?>
