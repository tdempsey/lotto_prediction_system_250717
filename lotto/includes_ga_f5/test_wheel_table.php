<?php
function TestWheelTable($even,$odd,$d2_1,$d2_2,$sum,&$table_wheel_total,&$prev_wheel_percent)
   { 
		global 	$debug, $game, $draw_prefix;

		require ("includes/mysqli.php");

		$query_test = "SELECT id FROM $draw_prefix";
		$query_test .= "eo50 ";
		$query_test .= "WHERE  even = $even ";
		$query_test .= "AND    odd  = $odd ";
		$query_test .= "AND    d2_1 = $d2_1 ";
		$query_test .= "AND    d2_2 = $d2_2 ";

		//print "$query_test<br>";
		
		$mysqli_result_test = mysqli_query($query_test, $mysqli_link) or die (mysqli_error($mysqli_link));

		$num_rows = mysqli_num_rows($mysqli_result_test);

		if ($num_rows == 0)
		{
			print "<font color=\"#FF0000\">Error - TestWheelTable - EO50 not found</font><br>";
			return 0;
		}

		$row = mysqli_fetch_array($mysqli_result_test);

		$query_test = "SELECT * FROM $draw_prefix";
		$query_test .= "wheel_sum_table  ";
		$query_test .= "WHERE  sum = $sum ";
		$query_test .= "AND    eo50 = $row[id] ";

		//print "$query_test<br>";
		
		$mysqli_result_test = mysqli_query($query_test, $mysqli_link) or die (mysqli_error($mysqli_link));

		$num_rows = mysqli_num_rows($mysqli_result_test);

		$row_eo50 = mysqli_fetch_array($mysqli_result_test);

		$table_wheel_total = $row_eo50[count];

		$prev_wheel_percent = $row_eo50[percent_5000];
		
		if ($num_rows > 0)
		{
			return $row_eo50[percent_5000];
		} else {
			return 0;
		}
   }

   function TestWheelTablePrevious($even,$odd,$d2_1,$d2_2,$sum,&$table_wheel_total,&$prev_wheel_percent)
   { 
		global 	$debug, $game, $draw_prefix;

		require ("includes/mysqli.php");

		$query_test = "SELECT id FROM $draw_prefix";
		$query_test .= "eo50 ";
		$query_test .= "WHERE  even = $even ";
		$query_test .= "AND    odd  = $odd ";
		$query_test .= "AND    d2_1 = $d2_1 ";
		$query_test .= "AND    d2_2 = $d2_2 ";

		//print "$query_test<br>";
		
		$mysqli_result_test = mysqli_query($query_test, $mysqli_link) or die (mysqli_error($mysqli_link));

		$num_rows = mysqli_num_rows($mysqli_result_test);

		if ($num_rows == 0)
		{
			print "<font color=\"#FF0000\">Error - TestWheelTable - EO50 not found</font><br>";
			return 0;
		}

		$row = mysqli_fetch_array($mysqli_result_test);

		$query_test = "SELECT * FROM $draw_prefix";
		$query_test .= "wheel_sum_table  ";
		$query_test .= "WHERE  sum = $sum ";
		$query_test .= "AND    eo50 = $row[id] ";

		//print "$query_test<br>";
		
		$mysqli_result_test = mysqli_query($query_test, $mysqli_link) or die (mysqli_error($mysqli_link));

		$num_rows = mysqli_num_rows($mysqli_result_test);

		$row_eo50 = mysqli_fetch_array($mysqli_result_test);

		$table_wheel_total = $row_eo50[count];

		$prev_wheel_percent = $row_eo50[percent_5000];
		
		if ($num_rows > 0)
		{
			return $row_eo50[percent_5000];
		} else {
			return 0;
		}
   }
?>