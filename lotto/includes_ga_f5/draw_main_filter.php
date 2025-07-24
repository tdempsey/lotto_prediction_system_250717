<?php
   function DrawMain7($sum,$even,$odd,$d2_1,$d2_2)
   {
		global	$debug,$combo,$DA_id,$debug,$megaball,$sorted_nums,
				$even_high,$even_low,$odd_high,$odd_low,$range501_high,$range501_low,$range502_high,
				$range502_low,$even_last,$odd_last,$range501_last,$range502_last,$saved_nums_array,
				$wheel_recent,$wheel_percent,$draw_prefix;

		// error checking ----------------------------------------------------------------------------------------------
		if (is_null($sum))
		{
			exit("<h2>Error - function draw_main_filter.php - <font color=\"#FF0000\">sum  undefined</font></h2>");
		}
 
		if (is_null($even) || is_null($odd) || is_null($d2_1) || is_null($d2_2) ||
			is_null($wheel_recent) || is_null($wheel_percent))
		{
			exit("<h2>Error - function draw_main_filter.php - <font color=\"#FF0000\">parameter undefined - even = $even, odd = $odd, d2_1 = $d2_1, d2_2 = $d2_2,  wheel_recent = $wheel_recent, wheel_percent = $wheel_percent</font></h2>");
		}
		// error checking ----------------------------------------------------------------------------------------------

		require ("includes/mysqli.php");

		log_info("-------- $sum: $even-$odd-$d2_1-$d2_2--------\n");

		log_info("--- DrawMain7()\n");

		$blank_row = "<TR>\n";
		$blank_row .= "<TD>$sum</TD>\n";
		$blank_row .= "<TD><CENTER>-</CENTER></TD>\n";
		$blank_row .= "<TD><CENTER>-</CENTER></TD>\n";
		$blank_row .= "<TD><CENTER>-</CENTER></TD>\n";
		$blank_row .= "<TD><CENTER>-</CENTER></TD>\n";
		$blank_row .= "<TD><CENTER>-</CENTER></TD>\n";
		$blank_row .= "<TD><CENTER>-</CENTER></TD>\n";
		$blank_row .= "<TD><CENTER>&nbsp;</CENTER></TD>\n";
		$blank_row .= "<TD><CENTER>-</CENTER></TD>\n";
		$blank_row .= "<TD><CENTER>-</CENTER></TD>\n";
		$blank_row .= "<TD><CENTER>-</CENTER></TD>\n";
		$blank_row .= "<TD><CENTER>-</CENTER></TD>\n";
		$blank_row .= "<TD><CENTER>-</CENTER></TD>\n";
		$blank_row .= "<TD><CENTER>-</CENTER></TD>\n";
		$blank_row .= "<TD><CENTER>-</CENTER></TD>\n";
		$blank_row .= "</TR>\n";

		$combo_count = query_combo_table7($sum,$even,$odd,$d2_1,$d2_2);

		log_info("combo count = $combo_count\n");

		do {
			$draw_status = DrawNumbersLM7();
			log_info("draw_status = $draw_status\n");
		} while ($draw_status != 1);

		if (count($combo) == 0)
		{
			print "$blank_row";
			log_info("rejected combo=0\n");
			$failed = true;
		}

		if (!$failed)
		{
			SaveDrawAnalysis(&$DA_id,$sum,$even,$odd,$d2_1,$d2_2);	
			PrintAndSaveDraw($sum,$even,$odd,$d2_1,$d2_2,
				$row[percent],$row[total],$row[all_drawn]);
			SaveComboTemp();
			SaveDrawArray();
			// add limit tables
		}
	
		require ("includes/print_error_table_log.incl");
		require ("includes/log_save_nums_array.incl");

		return 1;
   }
?>