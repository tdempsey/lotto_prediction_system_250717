<?php
function PairExcluded($x,$y,$table) 
{
	require ("includes/mysqli.php");
	
	$query = "SELECT last_date FROM $table ";
	$query .= "WHERE num1 = $x AND num2 = $y";
	
	$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

	$rcount = mysqli_num_rows($mysqli_result);

	// -----------------------------------------
	if ($rcount)
	{
		$row = mysqli_fetch_array($mysqli_result);
		return $row[last_date];
	}

	return false;
} 

?>