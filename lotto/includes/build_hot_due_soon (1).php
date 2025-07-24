<?php
	function BuildHotDueSoon($next_draw_Ymd)
	{
		global $debug,$hot_array,$warm_array,$due_array,$soon_array,$cold_array,$draw_prefix;
		
		require ("includes/mysqli.php");

		//******************************************** read due ****************
		$query = "SELECT num FROM fl_due_$next_draw_Ymd "; // test

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));
		
		$index = 0;
		
		while($row = mysqli_fetch_array($mysqli_result))
		{
			$due_array[$index] = $row[num];
			$index++;
		}
		
		//***************************************** read soon *****************
		$query = "SELECT num FROM fl_soon_$next_draw_Ymd ";

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));
		
		$index = 0;
		
		while($row = mysqli_fetch_array($mysqli_result))
		{
			$soon_array[$index] = $row[num];
			$index++;
		}
		
		//******************************************* read hot *****************
		$query = "SELECT num FROM fl_hot_$next_draw_Ymd ";

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));
		
		$index = 0;
		
		while($row = mysqli_fetch_array($mysqli_result))
		{
			$hot_array[$index] = $row[num];
			$index++;
		}

		//******************************************* read hot *****************
		$query = "SELECT num FROM fl_warm_$next_draw_Ymd ";

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));
		
		$index = 0;
		
		while($row = mysqli_fetch_array($mysqli_result))
		{
			$warm_array[$index] = $row[num];
			$index++;
		}

		//******************************************* read cold ****************
		$query = "SELECT num FROM fl_cold_$next_draw_Ymd ";

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));
		
		$index = 0;
		
		while($row = mysqli_fetch_array($mysqli_result))
		{
			$cold_array[$index] = $row[num];
			$index++;
		}
	}
?>