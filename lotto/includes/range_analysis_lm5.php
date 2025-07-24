<?php
  function range_analysis_LM5($array,$even,$odd) 
  { 

	global	$debug,$draw_range,$rank_count_range,$range501_low,$range502_low,$range501_high,$range502_high,
			$even_last,$odd_last,$range501_last,$range502_last,$error_table_count,$test_switch;

	log_info("range_analysis_LM5\n");

	// error checking ----------------------------------------------------------------------------------------------	
	if (is_null($array) || count($array) == 0)
		{
			exit("<h2>Error - function range_analysis_lm5.php - <font color=\"#FF0000\">array undefined</font></h2>");
		}
	/*
	if (is_null($draw_range) || is_null($even) || is_null($odd) || 
		is_null($rank_count_range) || is_null($even_last) || is_null($odd_last) || 
		is_null($range501_high) || is_null($range501_low) || is_null($range502_high) ||
		is_null($range502_low) || is_null($error_table_count))
	{
		exit("<h2>Error - function range_analysis_lm5.php - <font color=\"#FF0000\">parameter undefined - draw_range = $draw_range, even = $even, odd = $odd, rank_count_range = $rank_count_range, even_last = $even_last, odd_last = $odd_last, odd_low = $odd_low, range501_high = $range501_high, range501_low = $range501_low, range502_high = $range502_high,  range502_low = $range502_low, error_table_count = $error_table_count</font></h2>");
	}
	*/
	// error checking ----------------------------------------------------------------------------------------------

	$num_count = array_fill (0, 7, 0);

	$rank_count = array_fill (0, 10, 0);

	$range501 = 0;
	$range502 = 0;

    while(list($key,$val) = each($array)) 
	{ 
		if ($val < 10) {
			$num_count[0]++;
		} 
		elseif ($val > 9 && $val < 20) {
			$num_count[1]++;
		}
		elseif ($val > 19 && $val < 30) {
			$num_count[2]++;
		}
		elseif ($val > 29 && $val < 40) {
			$num_count[3]++;
		}
		elseif ($val > 39 && $val < 50) {
			$num_count[4]++;
		}
		else {
			$num_count[5]++;
		}

		if ($val < 27)
		{
			$range501++;	
		}
		else
		{
			$range502++;
		}

		$count_index = LookUpCount($val);
		$rank_count[$count_index]++;
	} 

	reset($num_count);

	$one_count = 0;
	$two_count = 0;
	$three_count = 0;
	
	if ($test_switch[1])
	{
		if ($num_count[0] < $draw_range[0][0] || $num_count[0] > $draw_range[0][1]) 
		{
			log_info("rejected range 0x $num_count[0], {$draw_range[0][0]}/{$draw_range[0][1]}\n");
			$error_table_count[0]++;
			return 10; 
		} 
		elseif ($num_count[1] < $draw_range[1][0] || $num_count[1] > $draw_range[1][1]) 
		{
			log_info("rejected range 1x $num_count[1], {$draw_range[1][0]}/{$draw_range[1][1]}\n");
			$error_table_count[1]++;
			return 11;  
		}
		elseif ($num_count[2] < $draw_range[2][0] || $num_count[2] > $draw_range[2][1]) 
		{
			log_info("rejected range 2x $num_count[2], {$draw_range[2][0]}/{$draw_range[2][1]}\n");
			$error_table_count[2]++;
			return 12;  
		}
		elseif ($num_count[3] < $draw_range[3][0] || $num_count[3] > $draw_range[3][1]) 
		{
			log_info("rejected range 3x $num_count[3], {$draw_range[3][0]}/{$draw_range[3][1]}\n");
			$error_table_count[3]++;
			return 13;  
		}
		elseif ($num_count[4] < $draw_range[4][0] || $num_count[4] > $draw_range[4][1]) 
		{
			log_info("rejected range 4x $num_count[4], {$draw_range[4][0]}/{$draw_range[4][1]}\n");
			$error_table_count[4]++;
			return 14;  
		}
		elseif ($num_count[5] < $draw_range[5][0] || $num_count[5] > $draw_range[5][1]) 
		{
			log_info("rejected range 5x $num_count[5], {$draw_range[5][0]}/{$draw_range[5][1]}\n");
			$error_table_count[5]++;
			return 15;  
		}
	} else {
		log_info("test_switch[1] off - draw_range\n");
	}

	/*
	log_info("rank_count_range\n");
	log_info("rank_count_range[0][0] = {$rank_count_range[0][0]}\n");
	log_info("rank_count_range[0][1] = {$rank_count_range[0][1]}\n");
	log_info("rank_count_range[1][0] = {$rank_count_range[1][0]}\n");
	log_info("rank_count_range[1][1] = {$rank_count_range[1][1]}\n");
	log_info("rank_count_range[2][0] = {$rank_count_range[2][0]}\n");
	log_info("rank_count_range[2][1] = {$rank_count_range[2][1]}\n");
	log_info("rank_count_range[3][0] = {$rank_count_range[3][0]}\n");
	log_info("rank_count_range[3][1] = {$rank_count_range[3][1]}\n");
	log_info("rank_count_range[4][0] = {$rank_count_range[4][0]}\n");
	log_info("rank_count_range[4][1] = {$rank_count_range[4][1]}\n");
	log_info("rank_count_range[5][0] = {$rank_count_range[5][0]}\n");
	log_info("rank_count_range[5][1] = {$rank_count_range[5][1]}\n");
	log_info("rank_count_range[6][0] = {$rank_count_range[6][0]}\n");
	log_info("rank_count_range[6][1] = {$rank_count_range[6][1]}\n");
	*/
	
	if ($test_switch[2])
	{
		if ($rank_count[0] < $rank_count_range[0][0] || $rank_count[0] > $rank_count_range[0][1]) 
		{
			log_info("rejected rank_count_0 $rank_count[0], {$rank_count_range[0][0]}/{$rank_count_range[0][1]}\n");
			$error_table_count[6]++;
			return 50;  
		}
		elseif ($rank_count[1] < $rank_count_range[1][0] || $rank_count[1] > $rank_count_range[1][1]) 
		{
			log_info("rejected rank_count_1 $rank_count[1], {$rank_count_range[1][0]}/{$rank_count_range[1][1]}\n");
			$error_table_count[7]++;
			return 51;  
		}
		elseif ($rank_count[2] < $rank_count_range[2][0] || $rank_count[2] > $rank_count_range[2][1]) 
		{
			log_info("rejected rank_count_2 $rank_count[2], {$rank_count_range[2][0]}/{$rank_count_range[2][1]}\n");
			$error_table_count[8]++;
			return 52;  
		}
		elseif ($rank_count[3] < $rank_count_range[3][0] || $rank_count[3] > $rank_count_range[3][1]) 
		{
			log_info("rejected rank_count_3 $rank_count[3], {$rank_count_range[3][0]}/{$rank_count_range[3][1]}\n");
			$error_table_count[9]++;
			return 53;  
		}
		elseif ($rank_count[4] < $rank_count_range[4][0] || $rank_count[4] > $rank_count_range[4][1]) 
		{
			log_info("rejected rank_count_4 $rank_count[4], {$rank_count_range[4][0]}/{$rank_count_range[4][1]}\n");
			$error_table_count[10]++;
			return 54;  
		}
		elseif ($rank_count[5] < $rank_count_range[5][0] || $rank_count[5] > $rank_count_range[5][1]) 
		{
			log_info("rejected rank_count_5 $rank_count[5], {$rank_count_range[5][0]}/{$rank_count_range[5][1]}\n");
			$error_table_count[11]++;
			return 55;  
		}
		elseif ($rank_count[6] < $rank_count_range[6][0] || $rank_count[6] > $rank_count_range[6][1]) 
		{
			log_info("rejected rank_count_6 $rank_count[6], {$rank_count_range[6][0]}/{$rank_count_range[6][1]}\n");
			$error_table_count[12]++;
			return 56;  
		}
	} else {
		log_info("test_switch[2] off - rank_count\n");
	}

    while(list($key,$val) = each($num_count)) { 
        if ($val == 1) {
			$one_count++; 
		} elseif ($val == 2) {
			$two_count++; 
		} elseif ($val == 3) {
			$three_count++; 
		} elseif ($val > 3) {
			if ($test_switch[3])
			{
				log_info("rejected range count > 3\n");
				$error_table_count[13]++;
				return 4; 
			} else {
				log_info("test_switch[3] off - greater_three_count\n");
			}
		}
    } 

	if ($three_count == 2) 
	{
		if ($test_switch[4])
		{
			log_info("rejected DC3 = $three_count\n");
			$error_table_count[17]++;
			return 3; 
		} else {
			log_info("test_switch[4] off - three_count\n");
		}
	} 
	elseif ($two_count == 3) 
	{
		if ($test_switch[5])
		{
			log_info("rejected DC2 = $two_count\n");
			$error_table_count[18]++;
			return 2; 
		} else {
			log_info("test_switch[5] off - two_count\n");
		}
	} 
	elseif ($one_count == 3 || $one_count == 6) // 3? 4?
	{
		if ($test_switch[6])
		{
			log_info("rejected DC1 = $one_count\n");
			$error_table_count[19]++;
			return 6; 
		} else {
			log_info("test_switch[6] off - two_count\n");
		}
	} 
	
    return 0; 
  }
?>