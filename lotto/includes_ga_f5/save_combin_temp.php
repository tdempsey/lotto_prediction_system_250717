<?php
function SaveComboTemp()	
{
	require ("includes/mysqli.php");

	global $sorted_nums,$debug;

	$draw = $sorted_nums;

	array_unshift ($draw, 0);

	// combin 5 save
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

		$query5 = "Insert INTO fl_5_53_save ";
		$query5 .= "VALUES ( '0', ";
		$query5 .= "         $d1, ";
		$query5 .= "	     $d2, ";
		$query5 .= "	     $d3, ";
		$query5 .= "	     $d4, ";
		$query5 .= "	     $d5) ";
		
		//print "$query5<p>";
		
		$mysqli_result5 = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));
	}

	// combin 4 save
	for ($c = 1; $c <= 15; $c++)
	{
		switch ($c) 
		{ 
		   case 1: 
			   $d1 = $draw[1];
			   $d2 = $draw[2];
			   $d3 = $draw[3];
			   $d4 = $draw[4];
			   break; 
		   case 2: 
			   $d1 = $draw[1];
			   $d2 = $draw[2];
			   $d3 = $draw[3];
			   $d4 = $draw[5];
			   break; 
		   case 3: 
			   $d1 = $draw[1];
			   $d2 = $draw[2];
			   $d3 = $draw[3];
			   $d4 = $draw[6];
			   break; 
		   case 4: 
			   $d1 = $draw[1];
			   $d2 = $draw[2];
			   $d3 = $draw[4];
			   $d4 = $draw[5];
			   break;
		   case 5: 
			   $d1 = $draw[1];
			   $d2 = $draw[2];
			   $d3 = $draw[4];
			   $d4 = $draw[6];
			   break;
		   case 6: 
			   $d1 = $draw[1];
			   $d2 = $draw[2];
			   $d3 = $draw[5];
			   $d4 = $draw[6];
			   break;
		   case 7: 
			   $d1 = $draw[1];
			   $d2 = $draw[3];
			   $d3 = $draw[4];
			   $d4 = $draw[5];
			   break; 
		   case 8: 
			   $d1 = $draw[1];
			   $d2 = $draw[3];
			   $d3 = $draw[4];
			   $d4 = $draw[6];
			   break; 
		   case 9: 
			   $d1 = $draw[1];
			   $d2 = $draw[3];
			   $d3 = $draw[5];
			   $d4 = $draw[6];
			   break; 
		   case 10: 
			   $d1 = $draw[1];
			   $d2 = $draw[4];
			   $d3 = $draw[5];
			   $d4 = $draw[6];
			   break;
		   case 11: 
			   $d1 = $draw[2];
			   $d2 = $draw[3];
			   $d3 = $draw[4];
			   $d4 = $draw[5];
			   break;
		   case 12: 
			   $d1 = $draw[2];
			   $d2 = $draw[3];
			   $d3 = $draw[4];
			   $d4 = $draw[6];
			   break;
		   case 13: 
			   $d1 = $draw[2];
			   $d2 = $draw[3];
			   $d3 = $draw[5];
			   $d4 = $draw[6];
			   break;
		   case 14: 
			   $d1 = $draw[2];
			   $d2 = $draw[4];
			   $d3 = $draw[5];
			   $d4 = $draw[6];
			   break;
		   case 15: 
			   $d1 = $draw[3];
			   $d2 = $draw[4];
			   $d3 = $draw[5];
			   $d4 = $draw[6];
			   break;
		} 

		$query4 = "Insert INTO fl_4_53_save ";
		$query4 .= "VALUES ( '0', ";
		$query4 .= "         $d1, ";
		$query4 .= "	     $d2, ";
		$query4 .= "	     $d3, ";
		$query4 .= "	     $d4) ";
		
		//print "$query4<p>";
		
		$mysqli_result4 = mysqli_query($mysqli_link, $query4) or die (mysqli_error($mysqli_link));
	}

	// combin 3 save
	for ($c = 1; $c <= 20; $c++)
	{
		switch ($c) { 
		   case 1: 
			   $d1 = $draw[1];
			   $d2 = $draw[2];
			   $d3 = $draw[3];
			   break; 
		   case 2: 
			   $d1 = $draw[1];
			   $d2 = $draw[2];
			   $d3 = $draw[4];
			   break; 
		   case 3: 
			   $d1 = $draw[1];
			   $d2 = $draw[2];
			   $d3 = $draw[5];
			   break; 
		   case 4: 
			   $d1 = $draw[1];
			   $d2 = $draw[2];
			   $d3 = $draw[6];
			   break;
		   case 5: 
			   $d1 = $draw[1];
			   $d2 = $draw[3];
			   $d3 = $draw[5];
			   break;
		   case 6: 
			   $d1 = $draw[1];
			   $d2 = $draw[3];
			   $d3 = $draw[6];
			   break;
		   case 7: 
			   $d1 = $draw[1];
			   $d2 = $draw[4];
			   $d3 = $draw[5];
			   break; 
		   case 8: 
			   $d1 = $draw[1];
			   $d2 = $draw[4];
			   $d3 = $draw[6];
			   break; 
		   case 9: 
			   $d1 = $draw[1];
			   $d2 = $draw[5];
			   $d3 = $draw[6];
			   break; 
		   case 10: 
			   $d1 = $draw[2];
			   $d2 = $draw[3];
			   $d3 = $draw[4];
			   break;
		   case 11: 
			   $d1 = $draw[2];
			   $d2 = $draw[3];
			   $d3 = $draw[5];
			   break;
		   case 12: 
			   $d1 = $draw[2];
			   $d2 = $draw[3];
			   $d3 = $draw[6];
			   break;
		   case 13: 
			   $d1 = $draw[2];
			   $d2 = $draw[4];
			   $d3 = $draw[5];
			   break;
		   case 14: 
			   $d1 = $draw[2];
			   $d2 = $draw[4];
			   $d3 = $draw[6];
			   break;
		   case 15: 
			   $d1 = $draw[2];
			   $d2 = $draw[5];
			   $d3 = $draw[6];
			   break;
		   case 16: 
			   $d1 = $draw[3];
			   $d2 = $draw[4];
			   $d3 = $draw[5];
			   break;
		   case 17: 
			   $d1 = $draw[3];
			   $d2 = $draw[4];
			   $d3 = $draw[6];
			   break;
		   case 18: 
			   $d1 = $draw[3];
			   $d2 = $draw[5];
			   $d3 = $draw[6];
			   break;
		   case 19: 
			   $d1 = $draw[4];
			   $d2 = $draw[5];
			   $d3 = $draw[6];
			   break;
		   case 20: 
			   $d1 = $draw[1];
			   $d2 = $draw[3];
			   $d3 = $draw[4];
			   break;
		} 

		$query3 = "Insert INTO fl_3_53_save ";
		$query3 .= "VALUES ( '0', ";
		$query3 .= "         $d1, ";
		$query3 .= "	     $d2, ";
		$query3 .= "	     $d3) ";
		
		//print "$query3<p>";
		
		$mysqli_result3 = mysqli_query($mysqli_link, $query3) or die (mysqli_error($mysqli_link));
	}

	// combin 2 save
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

		$query3 = "Insert INTO fl_2_53_save ";
		$query3 .= "VALUES ( '0', ";
		$query3 .= "         $d1, ";
		$query3 .= "	     $d2) ";
		
		//print "$query3<p>";
		
		$mysqli_result3 = mysqli_query($mysqli_link, $query3) or die (mysqli_error($mysqli_link));
	}
}	
?>
