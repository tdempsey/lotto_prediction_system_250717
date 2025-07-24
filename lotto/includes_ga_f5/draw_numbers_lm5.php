<?php

	function DrawNumbersLM5()
	{ 
		global 	$debug,$combo,$sorted_nums,$next_draw_Ymd;

		// error checking ----------------------------------------------------------------------------------------------
		/*
		if (is_null($sorted_nums) || count(sorted_nums) == 0)
		{
			exit("<h2>Error - function draw_numbers_lm5.php - <font color=\"#FF0000\">array sorted_nums undefined</font></h2>");
		}
		*/
		
		if (is_null($next_draw_Ymd))
		{
			exit("<h2>Error - function draw_numbers_lm5.php - <font color=\"#FF0000\">parameter undefined - next_draw_Ymd = $next_draw_Ymd</font></h2>");
		}
		// error checking ----------------------------------------------------------------------------------------------

		log_info("--- DrawNumbersLM5()\n");

		if (count($combo) == 0)
		{
			return 1;
		}
		
		mt_srand((float) microtime() * 10000000);

		$x = mt_rand(0, count($combo)-1);

		log_info("x = $x\n");

		$sorted_nums[0] = $combo[$x][0];
		$sorted_nums[1] = $combo[$x][1];
		$sorted_nums[2] = $combo[$x][2];
		$sorted_nums[3] = $combo[$x][3];
		$sorted_nums[4] = $combo[$x][4];
		$sorted_nums[5] = $combo[$x][5];

		array_splice($combo, $x, 1);

		$y = count($combo);

		log_info("combo count = $y\n");

		log_info("------ DrawNumbers() $sorted_nums[0],$sorted_nums[1],$sorted_nums[2],$sorted_nums[3],$sorted_nums[4],$sorted_nums[5]\n");
		
		$draw_status = TestPicksLM5();

		return $draw_status;
	}
?>