<?php
function calculate_rank_mb($date, $draw_table, $mega_balls, $mb) 
{  
	require ("includes/mysqli.php"); 

	// initialize variables
	$rank_array_mb = array_fill (0, $mega_balls+1, 0);

	$query = "SELECT mb FROM $draw_table ";
	$query .= "WHERE date < '$date' ";
	$query .= "ORDER BY date DESC ";
	$query .= "LIMIT 26 ";

	$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

	while($row = mysqli_fetch_row($mysqli_result))
	{
		$rank_array_mb[$row[0]]++;
	}

	$rank_mb = $rank_array_mb[$mb];
      
	return $rank_mb; 
}
?>