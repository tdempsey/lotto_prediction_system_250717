<?php
	function select_all($table,&$result,&$num_rows,$link)
	{
		// include to connect to database
		require("includes/mysqli.php");

		// --------------------------------------------------------
		$query = "SELECT * FROM $table ";

		$result = mysqli_query($query, $link) or die (mysqli_error($mysqli_link));

		$num_rows = mysqli_num_rows($mysqli_result_pair);

		return 0;
	}
?>