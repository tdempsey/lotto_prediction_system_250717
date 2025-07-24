<?php
function findFirstDrawDateUnix($game,$draw_table_name) 
{
	global $debug;

	require ("includes/mysqli.php");

	$query5 = "SELECT * FROM $draw_table_name ";
	$query5 .= "ORDER BY date ASC ";
	$query5 .= "LIMIT 1 "; 

	#print("<P>$query5<p>");

	$mysqli_result5 = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));

	$row1 = mysqli_fetch_array($mysqli_result5);

	#print("<P>date = $row1[date]<p>");

	$draw_date_array = array_fill(0,3,0);

	$draw_date_array = explode("-","$row1[0]"); ### 210104
				$draw_date_unix = mktime (0,0,0,$draw_date_array[1],$draw_date_array[2],$draw_date_array[0]); ### 210104	

	return ($draw_date_unix);
}
?>