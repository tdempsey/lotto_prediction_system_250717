<?php
function combin2testFL($draw) 
{	
	global $game;

	require ("includes/mysqli.php");

	$num_rows_total = 0;

	array_unshift ($draw, 0);

	// count 2
	for ($c = 1; $c <= 15; $c++)
	{
		switch ($c) { 
		   case 1: 
			   $d1 = $draw[1];
			   $d2 = $draw[2];
			   break; 
		   case 2: 
			   $d1 = $draw[1];
			   $d2 = $draw[3];
			   break; 
		   case 3: 
			   $d1 = $draw[1];
			   $d2 = $draw[4];
			   break; 
		   case 4: 
			   $d1 = $draw[1];
			   $d2 = $draw[5];
			   break;
		   case 5: 
			   $d1 = $draw[1];
			   $d2 = $draw[6];
			   break;
		   case 6: 
			   $d1 = $draw[2];
			   $d2 = $draw[3];
			   break;
		   case 7: 
			   $d1 = $draw[2];
			   $d2 = $draw[4];
			   break; 
		   case 8: 
			   $d1 = $draw[2];
			   $d2 = $draw[5];
			   break; 
		   case 9: 
			   $d1 = $draw[2];
			   $d2 = $draw[6];
			   break; 
		   case 10: 
			   $d1 = $draw[3];
			   $d2 = $draw[4];
			   break;
		   case 11: 
			   $d1 = $draw[3];
			   $d2 = $draw[5];
			   break;
		   case 12: 
			   $d1 = $draw[3];
			   $d2 = $draw[6];
			   break;
		   case 13: 
			   $d1 = $draw[4];
			   $d2 = $draw[5];
			   break;
		   case 14: 
			   $d1 = $draw[4];
			   $d2 = $draw[6];
			   break;
		   case 15: 
			   $d1 = $draw[5];
			   $d2 = $draw[6];
			   break;
		} 

		$query2 = "SELECT * FROM fl_2_53 ";
		$query2 .= "WHERE d1 = $d1 ";
		$query2 .= "  AND d2 = $d2 ";

		$mysqli_result2 = mysqli_query($mysqli_link, $query2) or die (mysqli_error($mysqli_link));

		if ($num_rows = mysqli_num_rows($mysqli_result2))
		{
			$num_rows_total++;
		} 
	}

	return $num_rows_total;
}
?>