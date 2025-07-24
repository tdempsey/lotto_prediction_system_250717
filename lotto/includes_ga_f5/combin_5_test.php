<?php
function combin5testFL($draw) 
{	
	global $game;

	require ("includes/mysqli.php");

	$num_rows_total = 0;

	array_unshift ($draw, 0);

	for ($c = 1; $c <= 6; $c++)
	{
		switch ($c) { 
		   case 1: 
			   $d1 = $draw[2];
			   $d2 = $draw[3];
			   $d3 = $draw[4];
			   $d4 = $draw[5];
			   $d5 = $draw[6];
			   break; 
		   case 2: 
			   $d1 = $draw[1];
			   $d2 = $draw[3];
			   $d3 = $draw[4];
			   $d4 = $draw[5];
			   $d5 = $draw[6]; 
			   break; 
		   case 3: 
			   $d1 = $draw[1];
			   $d2 = $draw[2];
			   $d3 = $draw[4];
			   $d4 = $draw[5];
			   $d5 = $draw[6];
			   break; 
		   case 4: 
			   $d1 = $draw[1];
			   $d2 = $draw[2];
			   $d3 = $draw[3];
			   $d4 = $draw[5];
			   $d5 = $draw[6]; 
			   break;
		   case 5: 
			   $d1 = $draw[1];
			   $d2 = $draw[2];
			   $d3 = $draw[3];
			   $d4 = $draw[4];
			   $d5 = $draw[6];
			   break;
		   case 6: 
			   $d1 = $draw[1];
			   $d2 = $draw[2];
			   $d3 = $draw[3];
			   $d4 = $draw[4];
			   $d5 = $draw[5];
			   break;
		} 

		$query2 = "SELECT * FROM fl_5_53 ";
		$query2 .= "WHERE d1 = $d1 and ";
		$query2 .= "	  d2 = $d2 and ";
		$query2 .= "	  d3 = $d3 and ";
		$query2 .= "	  d4 = $d4 and ";
		$query2 .= "	  d5 = $d5 ";

		//print "$query2<br>";

		$mysqli_result2 = mysqli_query($mysqli_link, $query2) or die (mysqli_error($mysqli_link));

		//$draw2 = mysqli_fetch_array($mysqli_result2);

		if ($num_rows = mysqli_num_rows($mysqli_result2))
		{
			$num_rows_total++;
		} else {
			//return 0;
		}
	}

	return $num_rows_total;
}
?>