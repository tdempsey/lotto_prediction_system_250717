<?php
	function SaveDrawAnalysis(&$DA_id,$draw_average,$even,$odd,$d2_1,$d2_2)
	{
		global	$draw_range,$rank_count_range,$curr_date,$average_average_high,$average_average_low,
				$remove_dups,$draw_prefix;	

		// temp
		$average_average_high = 0;
		$average_average_low = 0;

		// error checking ----------------------------------------------------------------------------------------------	
		if (is_null($draw_average) || count($draw_average) == 0)
		{
			exit("<h2>Error - function save_draw_analysis_lm5.php - <font color=\"#FF0000\">array draw_average undefined</font></h2>");
		}
		/*
		if (is_null($even) || is_null($odd) || is_null($d2_1) || is_null($d2_2) || is_null($draw_range) || 
			is_null($rank_count_range) || is_null($curr_date) || is_null($average_average_high) || 
			is_null($average_average_low) || is_null($remove_dups) ||
			is_null($draw_prefix))
		{
			exit("<h2>Error - function save_draw_analysis_lm5.php - <font color=\"#FF0000\">parameter undefined - even = $even, odd = $odd, d2_1 = $d2_1, d2_2 = $d2_2, more...</font></h2>");
		}
		*/
		// error checking ----------------------------------------------------------------------------------------------
				
		require ("includes/mysqli.php");

		$temp_table = $draw_prefix;
		$temp_table .= "draw_analysis";

		//------------------------------------------------------------------------------- 
		$query4 = "INSERT INTO $temp_table "; // test and fix
		$query4 .= "VALUES (0, ";
		$query4 .= "'$curr_date', ";
		$query4 .= "'$draw_average', ";
		$query4 .= "'$draw_average', ";
		$query4 .= "'$average_average_high', ";
		$query4 .= "'$average_average_low', ";
		$query4 .= "'0', ";
		$query4 .= "'0', ";
		$query4 .= "'$even', ";
		$query4 .= "'$even', ";
		$query4 .= "'$odd', ";
		$query4 .= "'$odd', ";
		$query4 .= "'$d2_1', ";
		$query4 .= "'$d2_1', ";
		$query4 .= "'$d2_2', ";
		$query4 .= "'$d2_2', ";
		$query4 .= "'{$draw_range[0][1]}', ";
		$query4 .= "'{$draw_range[0][0]}', ";
		$query4 .= "'{$draw_range[1][1]}', ";
		$query4 .= "'{$draw_range[1][0]}', ";
		$query4 .= "'{$draw_range[2][1]}', ";
		$query4 .= "'{$draw_range[2][0]}', ";
		$query4 .= "'{$draw_range[3][1]}', ";
		$query4 .= "'{$draw_range[3][0]}', ";
		$query4 .= "'{$draw_range[4][1]}', ";
		$query4 .= "'{$draw_range[4][0]}', ";
		$query4 .= "'{$draw_range[5][1]}', ";
		$query4 .= "'{$draw_range[5][0]}', ";
		$query4 .= "'{$rank_count_range[0][1]}', ";
		$query4 .= "'{$rank_count_range[0][0]}', ";
		$query4 .= "'{$rank_count_range[1][1]}', ";
		$query4 .= "'{$rank_count_range[1][0]}', ";
		$query4 .= "'{$rank_count_range[2][1]}', ";
		$query4 .= "'{$rank_count_range[2][0]}', ";
		$query4 .= "'{$rank_count_range[3][1]}', ";
		$query4 .= "'{$rank_count_range[3][0]}', ";
		$query4 .= "'{$rank_count_range[4][1]}', ";
		$query4 .= "'{$rank_count_range[4][0]}', ";
		$query4 .= "'{$rank_count_range[5][1]}', ";
		$query4 .= "'{$rank_count_range[5][0]}', ";
		$query4 .= "'{$rank_count_range[6][1]}', ";
		$query4 .= "'{$rank_count_range[6][0]}', ";
		$query4 .= "'$remove_dups')";

		//print "$query4<p>";

		$mysqli_result4 = mysqli_query($mysqli_link, $query4) or die (mysqli_error($mysqli_link));
		
		$DA_id = mysqli_insert_id();
	}
?>