<?php
	function TableEO50($sum,$even,$odd,$d2_1,$d2_2)
	{
		require ("includes/mysqli.php");

		global $debug;

		$query_eo50 = "SELECT * FROM fl_eo50 ";
		$query_eo50 .= "WHERE sum = $sum ";
		$query_eo50 .= "AND even = $even ";
		$query_eo50 .= "AND odd = $odd ";
		$query_eo50 .= "AND d2_1 = $d2_1 ";
		$query_eo50 .= "AND d2_2 = $d2_2 ";

		$mysqli_result_eo50 = mysqli_query($query_eo50, $mysqli_link) or die (mysqli_error($mysqli_link));

		//$num_rows = mysqli_num_rows($mysqli_result_eo50);

		if ($num_rows = mysqli_num_rows($mysqli_result_eo50)
		{
			$row = mysqli_fetch_array($mysqli_result_eo50);
			return $row[id];
		} else {
			return 0;
		}	
	}
?>
