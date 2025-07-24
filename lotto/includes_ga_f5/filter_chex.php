<?php
function filter_chex($draw) 
{  
	global $date, $curr_date_dash;	### 240701 ###

	// error checking ----------------------------------------------------------------------------------------------
	if (count($draw) == 0)
	{
		exit("<h2>Error - function filter_chex.php - <font color=\"#FF0000\">draw undefined</font></h2>");
	}

	if (array_sum($draw) == 0)
	{
		exit("<h2>Error - function filter_chex.php - <font color=\"#FF0000\">draw array_sum = 0</font></h2>");
	}

	if (is_null($max_draw) || $max_draw == 0)
	{
		exit("<h2>Error - function filter_chex.php - <font color=\"#FF0000\">max_draw undefined - max_draw = $max_draw</font></h2>");
	}

	// error checking ----------------------------------------------------------------------------------------------
	
	sort ($draw);	### 240701 v### 

	$reject_code = 0;

	$count = count(array_filter($array, function($value) {
	  return $value > 0;
	}));

	echo "Count of values > 0: $count\n";

	### seq	2/3 ### -1

	### mod/modx ### -2

	### dc ### -3

	### dup ### -4

	### rank ### -5
	$num_rows_rank_limit = 0;

	$query = "SELECT * FROM ga_f5_rank_limit";	### 240701 ###
	$query .= "WHERE date = '$curr_date_dash' ";
	$query .= "AND draw_limit = '30' ";	### 240701 ###

	echo "$query<br>";

	$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

	$num_rows_rank_limit = mysqli_num_rows($mysqli_result);

	echo "num_rows_rank_limit - $num_rows_rank_limit<br>";

	if ($num_rows_rank_limit)
	{
		### build rank_limit_array
		$rank_limit = mysqli_fetch_array($mysqli_result);	### 240701 ### 3-10 ###

		for ($r = 3; $r <= 10; $r++) 
		{
			$row_rank_limit[$r]	
		}

		$rank_count = array_fill(0,7,0);

		### calc rank of draw - use includes\calculate_rank.php
		calculate_rank_count($date,$draw,&$rank_count);

		### check rank of draw
		for ($r = 0; $r <= 7; $r++) 
		{
			$s = $r + 3;	### offset table read ###
			if ($rank_count[$r] <= $rank_limit[$r]) { 
				continue;
			} else {
				return $reject_code = -5;
			}
		}
	}
	
	
	return $reject_code;
}
?>