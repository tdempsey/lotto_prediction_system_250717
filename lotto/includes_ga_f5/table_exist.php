<?php
function TableExists($tablename) 
{
	require ("includes/mysqli.php");

	// error checking ----------------------------------------------------------------------------------------------	
	if (is_null($tablename))
	{
		exit("<h2>Error - function table_exist.php - <font color=\"#FF0000\">parameter undefined - tablename = $tablename</font></h2>");
	}
	// error checking ----------------------------------------------------------------------------------------------

	$query = "SHOW TABLES";
	
	$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

	$rcount = mysqli_num_rows($mysqli_result);

	// check for a match.
	while($row = mysqli_fetch_row($mysqli_result)) {
		if ($row[0] == $tablename) return true;
	}

	return false;
} 

?>