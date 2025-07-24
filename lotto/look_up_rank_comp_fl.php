<?php
function LookUpRankComp($num)
   	{
		require ("includes/mysqli.php");

		// get count from fl_temp table
		$query2 = "SELECT count FROM fl_rank_comp ";
		$query2 .= "WHERE num=$num ";
		
		$mysqli_result2 = mysqli_query($mysqli_link, $query2) or die (mysqli_error($mysqli_link));

		$row2 = mysqli_fetch_row($mysqli_result2);

		$count = $row2[0];

		return $count;
   	}
?>