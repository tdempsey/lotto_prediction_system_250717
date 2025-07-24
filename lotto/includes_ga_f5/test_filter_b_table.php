<?php
function TestFilterBTable($draw)
   { 
		global 	$debug, $game, $draw_prefix;

		require ("includes/mysqli.php");

		/*
		// get everything from catalog table
		if ($draw[0] < 10)
		{
			$query_test = "SELECT b1, b2, b3, b4, b5, b6 FROM combo_6_53_0$draw[0] ";
		} elseif ($draw[0] < 29) {
			$query_test = "SELECT b1, b2, b3, b4, b5, b6 FROM combo_6_53_$draw[0] ";
		} else {
			return 0;
		}
		*/
		
		if ($draw[0] < 15)
		{
			$query_test = "SELECT * FROM $draw_prefix";
			$query_test .= "filter_b_";
			if ($draw[0] > 9)
			{
				$query_test .= "{$draw[0]} ";
			} else {
				$query_test .= "0{$draw[0]} ";
			}
			$query_test .= "WHERE  b1 = $draw[0] ";
			/*
			for ($x = 1; $x < $balls_drawn; $x++)
			{
				$query_test .= "AND    b.$x = $draw[$x] ";
			}
			*/
			$query_test .= "AND    b2 = $draw[1] ";
			$query_test .= "AND    b3 = $draw[2] ";
			$query_test .= "AND    b4 = $draw[3] ";
			$query_test .= "AND    b5 = $draw[4] ";

			//print "$query_test<br>";
			
			$mysqli_result_test = mysqli_query($query_test, $mysqli_link) or die (mysqli_error($mysqli_link));

			$num_rows = mysqli_num_rows($mysqli_result_test);
		} else {
			return 0;
		}

		//print "TestDrawTable - num_rows = $num_rows<br>";
		
		if ($num_rows > 0)
		{
			return 1;
		} else {
			return 0;
		}
   }
?>