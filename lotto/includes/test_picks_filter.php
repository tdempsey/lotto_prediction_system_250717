<?php
	function TestPicksLM7()
	{
		global 	$debug,$sorted_nums,$stdev_low,$stdev_high,$even_high,$odd_high,$even_low,$odd_low,$sum_average_high,
		$sum_average_low,$average_average_high,$average_average_low,
		$last_1_draws,$last_2_draws,$last_3_draws,$last_4_draws,$last_5_draws,$last_6_draws,$last_7_draws,
		$last_8_draws,$last_9_draws,$last_10_draws,$last_11_draws,$last_12_draws,$last_13_draws,
		$last_14_draws,$last_15_draws,$last_16_draws,$last_17_draws,
		$last_18_draws,$last_19_draws,$last_20_draws,$last_21_draws,$last_22_draws,$last_23_draws,
		$last_24_draws,$last_25_draws,$last_26_draws,$last_27_draws,$last_28_draws,$last_29_draws,
		$last_30_draws,$last_31_draws,$last_32_draws,$last_33_draws,$last_34_draws,$last_35_draws,
		$last_36_draws,$last_37_draws,$last_38_draws,$last_39_draws,$last_40_draws,$last_41_draws,
		$last_42_draws,$last_43_draws,$last_44_draws,$last_45_draws,$last_46_draws,$last_47_draws,
		$last_48_draws,$last_49_draws,$last_50_draws,
		$error_table_count,$draw_table,$look_back,
		$draw_range_last,$rank_range_last,$dup_count_limit,$curr_date_dash,
		$dup_count_last,$exclude_num_limit,$exclude_count_limit_1,$exclude_count_limit_2,
		$exclude_count_limit_3,$exclude_numbers,
		$date_nodash,$hot_array,$warm_array,$cold_array,$even_last,$odd_last,
		$range501_last,$range502_last,
		$mod_limit,$mod_two_count_limit,$width_limit,$exclude_numbers_1,$exclude_numbers_2,
		$exclude_numbers_3,
		$exclude_numbers_col_two,$exclude_numbers_col_three,$exclude_numbers_col_four,$exclude_numbers_col_five,$exclude_numbers_col_six,
		$combin3min,$combin3max,$combin2min,$combin4max,
		$col1_low,$col1_high,$col2_low,$col2_high,$col3_low,$col3_high,
		$col4_low,$col4_high,$col5_low,$col5_high,$col6_low,$col6_high,$saved_nums_array,
		$required_numbers,$required_numbers_min,$test_switch;
		
		$failed = 0;

		////////////////////////////////////////////////////////////////////////////
		// 
		//    Config
		//
		////////////////////////////////////////////////////////////////////////////

		require ("includes/fl_config.incl");

		log_info("--- TestPicksLM7()\n");
		
		if (!$failed)
		{
			////////////////////////////////////////////////////////////////////////////
			// 
			//    Range Analysis
			//
			////////////////////////////////////////////////////////////////////////////
			$range_failed = range_analysis_LM7($sorted_nums,$even,$odd);

			if ($range_failed > 0)
			{
				$failed = 1;
				$error_table_count[20]++;
				return 0;
			} 
			else
			{
				if ($test_switch[7])
				{
					// test cols --------------------------------------------------------------------------
					if (!$failed) 
					{ 
						// col 1
						if ($sorted_nums[0] < $col1_low || $sorted_nums[0] > $col1_high)
						{
							$failed = 1;
							//print "<strong><font color=\"#FF0000\">rejected</font> col 1</strong> - $sorted_nums[0]<br>";
							log_info("rejected col 1 - $sorted_nums[0]\n");
							$error_table_count[21]++;
							return 0;
						}
						// col 2
						elseif ($sorted_nums[1] < $col2_low || $sorted_nums[1] > $col2_high)
						{
							$failed = 1;
							log_info("rejected col 2 - $sorted_nums[1]\n");
							$error_table_count[22]++;
							return 0;
						}
						// col 3
						elseif ($sorted_nums[2] < $col3_low  || $sorted_nums[2] > $col3_high)
						{
							$failed = 1;
							log_info("rejected col 3 - $sorted_nums[2]\n");
							$error_table_count[23]++;
							return 0;
						}
						// col 4
						elseif ($sorted_nums[3] < $col4_low || $sorted_nums[3] > $col4_high) 
						{
							$failed = 1;
							log_info("rejected col 4 - $sorted_nums[3]\n");
							$error_table_count[24]++;
							return 0;
						}
						// col 5
						elseif ($sorted_nums[4] < $col5_low || $sorted_nums[4] > $col5_high) 
						{
							$failed = 1;
							log_info("rejected col 5 - $sorted_nums[4]\n");
							$error_table_count[25]++;
							return 0;
						}
						// col 6
						elseif ($sorted_nums[5] < $col6_low || $sorted_nums[5] > $col6_high)
						{
							$failed = 1;
							log_info("rejected col 6 - $sorted_nums[5]\n");
							$error_table_count[26]++;
							return 0;
						}
					}
				} else {
					log_info("test_switch[7] off - col_low_high\n");
				}

				if (!$failed)
				{
					//dup count check (min/max) ----------------------------------------------------------------------
					$dup_count = array_fill (0, 41, 0);

					//count repeating numbers
					for ($x = 1 ; $x <= 40; $x++)
					{
						for ($count = 0 ; $count <= 5; $count++)
						{	
							if (array_search($sorted_nums[$count], ${last_.$x._draws}) !== FALSE) {
								$dup_count[$x]++;
							}
						}
					}
					
					if ($test_switch[8])
					{
						for ($x = 1 ; $x <= 40; $x++)
						{
							if ($dup_count[$x] > $dup_count_limit[$x] || $dup_count[$x] < $dup_count_minim[$x]) 
							{
								$failed = 1;
								log_info("rejected dup_count_$x = $dup_count[$x] -- Min = $dup_count_minim[$x] -- Max = $dup_count_limit[$x]\n");
								$error_table_count[27]++;
								return 0;
							} 
						}
					} else {
						log_info("test_switch[8] off - dup_check\n");
					}
				}

				// exclude numbers -------------------------------------------------------------------------------------
				if ($test_switch[9])
				{
					if (!$failed) 
					{ 
						$exclude_count = 0;
						$exclude_count_1 = 0;
						$exclude_count_2 = 0;
						$exclude_count_3 = 0;
						$required_numbers_count = 0;

						//------------------------------------------------------------------
						// exclude_numbers
						for ($x = 0; $x <= 5; $x++)
						{
							for ($y = 0; $y < count($exclude_numbers); $y++)
							{
								if ($sorted_nums[$x] == $exclude_numbers[$y])
								{
									$exclude_count++;
								}
							}
						}

						if ($exclude_count > $exclude_num_limit)
						{
							$failed = 1;
							log_info("rejected excluded number count > exclude_num_limit = $exclude_count\n");
							$error_table_count[44]++;
							return 0;
						}
						/*
						//------------------------------------------------------------------
						for ($x = 0; $x <= 5; $x++)
						{
							for ($y = 0; $y < count($exclude_numbers_1); $y++)
							{
								if ($sorted_nums[$x] == $exclude_numbers_1[$y])
								{
									$exclude_count_1++;
								}
							}
						}

						if ($exclude_count_1 > $exclude_count_limit_1)
						{
							$failed = 1;
							log_info("rejected excluded count > exclude_count_limit_1 = $exclude_count_1\n");
							$error_table_count[28]++;
							return 0;
						}
						*/
						//------------------------------------------------------------------
						// exclude_numbers_2
						for ($x = 0; $x <= 5; $x++)
						{
							for ($y = 0; $y < count($exclude_numbers_2); $y++)
							{
								if ($sorted_nums[$x] == $exclude_numbers_2[$y])
								{
									$exclude_count_2++;
								}
							}
						}

						if ($exclude_count_2 > $exclude_count_limit_2)
						{
							$failed = 1;
							log_info("rejected excluded count > exclude_count_limit_2 = $exclude_count_2\n");
							$error_table_count[29]++;
							return 0;
						}

						// exclude_numbers_3
						for ($x = 0; $x <= 5; $x++)
						{
							for ($y = 0; $y < count($exclude_numbers_3); $y++)
							{
								if ($sorted_nums[$x] == $exclude_numbers_3[$y])
								{
									$exclude_count_3++;
								}
							}
						}

						if ($exclude_count_3 > $exclude_count_limit_3)
						{
							$failed = 1;
							log_info("rejected excluded count > exclude_count_limit_3 = $exclude_count_3\n");
							$error_table_count[30]++;
							return 0;
						}

						//------------------------------------------------------------------
						// required_numbers
						for ($x = 0; $x <= 5; $x++)
						{
							for ($y = 0; $y < count($required_numbers); $y++)
							{
								if ($sorted_nums[$x] == $required_numbers[$y])
								{
									$required_numbers_count++;
								}
							}
						}

						if ($required_numbers_count < $required_numbers_min)
						{
							$failed = 1;
							log_info("rejected required count < required_numbers_count = $required_numbers_count\n");
							$error_table_count[57]++;
							return 0;
						}
						
						// exclude_numbers_col_two
						if ($test_switch[11])
						{
							for ($y = 0; $y < count($exclude_numbers_col_two); $y++)
							{
								if ($sorted_nums[1] == $exclude_numbers_col_two[$y])
								{
									$failed = 1;
									log_info("rejected excluded column 2 = $exclude_numbers_col_two[$y]\n");
									$error_table_count[48]++;
									return 0;
								}
							}
						} else {
							log_info("test_switch[11] off - exclude_col_two\n");
						}

						// exclude_numbers_col_three
						if ($test_switch[11])
						{
							for ($y = 0; $y < count($exclude_numbers_col_three); $y++)
							{
								if ($sorted_nums[2] == $exclude_numbers_col_three[$y])
								{
									$failed = 1;
									log_info("rejected excluded column 3 = $exclude_numbers_col_three[$y]\n");
									$error_table_count[48]++;
									return 0;
								}
							}
						} else {
							log_info("test_switch[11] off - exclude_col_three\n");
						}

						// exclude_numbers_col_four
						if ($test_switch[11])
						{
							for ($y = 0; $y < count($exclude_numbers_col_four); $y++)
							{
								if ($sorted_nums[3] == $exclude_numbers_col_four[$y])
								{
									$failed = 1;
									log_info("rejected excluded column 4 = $exclude_numbers_col_four[$y]\n");
									$error_table_count[48]++;
									return 0;
								}
							}
						} else {
							log_info("test_switch[11] off - exclude_col_four\n");
						}

						// exclude_numbers_col_five
						if ($test_switch[11])
						{
							for ($y = 0; $y < count($exclude_numbers_col_five); $y++)
							{
								if ($sorted_nums[4] == $exclude_numbers_col_five[$y])
								{
									$failed = 1;
									log_info("rejected excluded column 5 = $exclude_numbers_col_five[$y]\n");
									$error_table_count[48]++;
									return 0;
								}
							}
						} else {
							log_info("test_switch[11] off - exclude_col_five\n");
						}

						// exclude_numbers_col_six
						if ($test_switch[11])
						{
							for ($y = 0; $y < count($exclude_numbers_col_six); $y++)
							{
								if ($sorted_nums[5] == $exclude_numbers_col_six[$y])
								{
									$failed = 1;
									log_info("rejected excluded column 6 = $exclude_numbers_col_six[$y]\n");
									$error_table_count[48]++;
									return 0;
								}
							}
						} else {
							log_info("test_switch[11] off - exclude_col_six\n");
						}

						// exclude saved_nums_array
						if ($test_switch[0])
						{
							for ($x = 0; $x <= 5; $x++)
							{
								for ($y = 0; $y < count($saved_nums_array); $y++)
								{
									if ($sorted_nums[$x] == $saved_nums_array[$y])
									{
										$failed = 1;
										log_info("rejected excluded saved_num_array = $saved_nums_array[$y]\n");
										$error_table_count[56]++;
										return 0;
									}
								}
							}
						} else {
							log_info("test_switch[0] off - saved_nums_array\n");
						}
					}
				} else {
						log_info("test_switch[9] off - exclude_count\n");
				}

				// test combin -----------------------------------------------------
				if ($test_switch[12]) // split for combin_save
				{
					if (!$failed) 
					{ 
						// test combin 5 
						log_info("combin5testFL---------------------\n");
						$combin_found = combin5testFL($sorted_nums);
						if ($combin_found)
						{
							$failed = 1;
							log_info("rejected combin 5 - $combin_found times\n");
							$error_table_count[46]++;
							return 0;
						}
						
						// saved combin 5 test 
						log_info("combin5saveFL---------------------\n");
						$combin_found = combin5saveFL($sorted_nums);
						if ($combin_found)
						{
							log_info("rejected combin 5 - $combin_found saves\n");
							$error_table_count[54]++;
							return 0;
						}
						
						// test combin 4 
						log_info("combin4testFL---------------------\n");
						$combin_found = combin4testFL($sorted_nums);
						if ($combin_found > $combin4max)
						{
							$failed = 1;
							log_info("rejected combin 4 - $combin_found times\n");
							$error_table_count[47]++;
							return 0;
						}
						
						// saved combin 4 test 
						log_info("combin4saveFL---------------------\n");
						$combin_found = combin4saveFL($sorted_nums);
						//if ($combin_found > 2) /////////////////////////////////// 
						if ($combin_found)
						{
							$failed = 1;
							log_info("rejected combin 4 - $combin_found saves\n");
							$error_table_count[52]++;
							return 0;
						}
						
						// test combin 3 
						log_info("combin3testFL---------------------\n");
						$combin_found = combin3testFL($sorted_nums);
						if ($combin_found > $combin3max || $combin_found < $combin3min )
						{
							$failed = 1;
							log_info("rejected combin 3 - $combin_found times\n");
							$error_table_count[49]++;
							return 0;
						}
						
						// saved combin 3 test 
						log_info("combin3saveFL---------------------\n");
						$combin_found = combin3saveFL($sorted_nums);
						if ($combin_found > $combin3min) /////////////////////////////////////////
						{
							$failed = 1;
							log_info("rejected combin 3 - $combin_found saves\n");
							$error_table_count[53]++;
							return 0;
						}
						
						// test combin 2 - always 15
						log_info("combin2testFL---------------------\n");
						$combin_found = combin2testFL($sorted_nums);
						if ($combin_found < $combin2min)
						{
							$failed = 1;
							log_info("rejected combin 2 - $combin_found times\n");
							$error_table_count[50]++;
							return 0;
						}
						/*
						// saved combin 2 test 
						log_info("combin2saveFL---------------------\n");
						$combin_found = combin2saveFL($sorted_nums);
						if ($combin_found)
						{
							$failed = 1;
							log_info("rejected combin 2 - $combin_found saves\n");
							$error_table_count[55]++;
							return 0;
						}
						*/
					}
				} else {
					log_info("test_switch[12] off - combin_test\n");
				}

				// hot, warm, cold, due, soon numbers -------------------------------------------------------------------------------------
				if ($test_switch[14]) 
				{	
					if (!$failed) { 
						// ------------------------------- hot ------------------------------
						$hot_count = TableDrawCount("fl_hot_$date_nodash", $sorted_nums, $temp);
						//print "hot_count = $hot_count<br>";

						if ($hot_count < $hot_min || $hot_count > $hot_max)
						{
							$failed = 1;
							log_info("rejected hot_count - $hot_count\n");
							$error_table_count[36]++;
							return 0;
						}

						// ------------------------------- warm ------------------------------
						$warm_count = TableDrawCount("fl_warm_$date_nodash", $sorted_nums, $temp);
						//print "warm_count = $warm_count<br>";

						if ($warm_count < $warm_min || $warm_count > $warm_max)
						{
							$failed = 1;
							log_info("rejected warm_count - $warm_count\n");
							$error_table_count[37]++;
							return 0;
						}

						// ------------------------------- cold ------------------------------
						$cold_count = TableDrawCount("fl_cold_$date_nodash", $sorted_nums, $temp);
						//print "cold_count = $cold_count<br>";

						if ($cold_count < $cold_min || $cold_count > $cold_max)
						{
							$failed = 1;
							log_info("rejected cold_count - $cold_count\n");
							$error_table_count[38]++;
							return 0;
						}

						// ------------------------------- due ------------------------------
						$due_count = TableDrawCount("fl_due_$date_nodash", $sorted_nums, $temp);
						//print "due_count = $due_count<br>";

						if ($due_count > $due_count_limit)
						{
							$failed = 1;
							log_info("rejected due_count - $due_count\n");
							$error_table_count[39]++;
							return 0;
						}

						// ------------------------------- soon ------------------------------
						$soon_count = TableDrawCount("fl_soon_$date_nodash", $sorted_nums, $temp);
						//print "soon_count = $soon_count<br>";

						if ($soon_count > $soon_count_limit)
						{
							$failed = 1;
							log_info("rejected soon_count - $soon_count\n");
							$error_table_count[40]++;
							return 0;
						}
					}
				} else {
					log_info("test_switch[14] off - hot/warm/cold\n");
				}

				// test width ------------------------------------------------------------------
				if ($test_switch[16]) 
				{	
					if (!$failed) 
					{ 
						$width = $sorted_nums[5] - $sorted_nums[0];

						if ($width < $width_limit)
						{
							$failed = 1;
							log_info("rejected width = $width\n");
							$error_table_count[41]++;
							return 0;
						}
					}
				} else {
					log_info("test_switch[16] off - width\n");
				}
				

				// test modulus -----------------------------------------------------------------------------------------
				if ($test_switch[17]) 
				{	
					if (!$failed) { 
						//print "<B>processing modulus test</B><br>";
						for ($x = 0; $x <= 9; $x++)
						{
							$mod[$x] = 0;
						}
						
						for ($x = 0; $x <= 5; $x++)
						{
							if ($sorted_nums[$x] < 10) {
								$y = $sorted_nums[$x];
								$mod[$y]++;
							} elseif ($sorted_nums[$x] > 9 && $sorted_nums[$x] < 20) {
								$y = $sorted_nums[$x] - 10;
								$mod[$y]++;
							} elseif ($sorted_nums[$x] > 19 && $sorted_nums[$x] < 30) {
								$y = $sorted_nums[$x] - 20;
								$mod[$y]++;
							} elseif ($sorted_nums[$x] > 29 && $sorted_nums[$x] < 40) {
								$y = $sorted_nums[$x] - 30;
								$mod[$y]++;
							} elseif ($sorted_nums[$x] > 39 && $sorted_nums[$x] < 50) {
								$y = $sorted_nums[$x] - 40;
								$mod[$y]++;
							} else {
								$y = $sorted_nums[$x] - 50;
								$mod[$y]++;
							}
						}

						$mod_total = 0;

						$mod_two_count = 0;

						for ($x = 0; $x <= 9; $x++)
						{
							if ($mod[$x] > $mod_limit || $mod_two_count > $mod_two_count_limit)
							{
								$failed = 1;
								log_info("rejected modulus $x = $mod[$x], mod_two_count = $mod_two_count\n");
								$error_table_count[42]++;
								return 0;
							}
							elseif ($mod[$x] == 2)
							{
								$mod_two_count++;
							}
						}
					}
				} else {
					log_info("test_switch[17] off - modulus\n");
				}
			}
		}

		if ($failed)
		{
			return 0;
		}

		return 1;
	}
?>