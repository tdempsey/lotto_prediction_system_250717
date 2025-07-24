<?php
   function DrawMain($draw_average)
   {
		global	$debug,$combo,$DA_id,$debug,$megaball,$sorted_nums,$sum_average_low,$sum_average_high,
				$even_high,$even_low,$odd_high,$odd_low,$range501_high,$range501_low,$range502_high,
				$range502_low,$even_last,$odd_last,$range501_last,$range502_last,$saved_nums_array,
				$wheel_recent,$wheel_percent,$draw_prefix;

		// error checking ----------------------------------------------------------------------------------------------
		if (is_null($draw_average))
		{
			exit("<h2>Error - function draw_main_LM7.php - <font color=\"#FF0000\">draw_average  undefined</font></h2>");
		}

		if (is_null($sum_average_low) || is_null($sum_average_high) || 
			is_null($even_high) || is_null($even_low) || is_null($odd_high) || is_null($odd_low) || is_null($range501_high) || is_null($range501_low) || is_null($range502_high) ||
			is_null($range502_low) || is_null($wheel_recent) || is_null($wheel_percent))
		{
			exit("<h2>Error - function draw_main_LM7.php - <font color=\"#FF0000\">parameter undefined - sum_average_low = $sum_average_low, sum_average_high = $sum_average_high, even_high = $even_high, even_low = $even_low, odd_high = $odd_high, odd_low = $odd_low, range501_high = $range501_high, range501_low = $range501_low, range502_high = $range502_high,  range502_low = $range502_low, even_last = $even_last, odd_last = $odd_last, range501_last = $range501_last, range502_last = $range502_last, wheel_recent = $wheel_recent, wheel_percent = $wheel_percent</font></h2>");
		}
		
		// error checking ----------------------------------------------------------------------------------------------

		require ("includes/mysqli.php");

		log_info("-------- $draw_average --------\n");

		log_info("--- DrawMain()\n");

		// fix in test_nums
		$sum_average_low = $draw_average;
		$sum_average_high = $draw_average;

		$even = 0;
		$odd = 0;
		$d2_1 = 0;
		$d2_2 = 0;

		$blank_row = "<TR>\n";
		$blank_row .= "<TD>$draw_average</TD>\n";
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

		$query_wheels = "SELECT DISTINCT sum,eo50,total,percent,all_drawn FROM $draw_prefix";
		$query_wheels .= "wheels_generated ";
		$query_wheels .= "WHERE sum = $draw_average ";
		$query_wheels .= "AND   recent > $wheel_recent "; 
		$query_wheels .= "AND   percent > $wheel_percent "; 

		//print "query_wheels = $query_wheels<p>";

		log_info("$query_wheels\n");

		$mysqli_result_wheels = mysqli_query($query_wheels, $mysqli_link) or die (mysqli_error($mysqli_link));
		
		$num_rows_wheels = mysqli_num_rows($mysqli_result_wheels);

		log_info("Wheels num_rows_wheels = $num_rows_wheels for $draw_average\n");
		
		if ($num_rows_wheels == 0)
		{
			print($blank_row);
			return 0;
		}
		else
		{
			//while($row = mysqli_fetch_array($mysqli_result_wheels))
			//{
				//print "calling EO50 with $row[eo50],$even,$odd,$d2_1,$d2_2<p>";
				//$num_rows_query = LookUpEO50($row[eo50],$even,$odd,$d2_1,$d2_2);

				$failed = false;

				log_info("--- processing $row[eo50],$even,$odd,$d2_1,$d2_2\n");
				
				if ($num_rows_query == 0)
				{
					print($blank_row);
					log_info("rejected EO50 - zero rows\n");
					$failed = true;
				}
				else
				{
					$combo_count = query_combo_table($draw_average,$even,$odd,$d2_1,$d2_2);

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
						SaveDrawAnalysis(&$DA_id,$draw_average,$even,$odd,$d2_1,$d2_2);	
						PrintAndSaveDraw($draw_average,$even,$odd,$d2_1,$d2_2,
							$row[percent],$row[total],$row[all_drawn]);
						SaveComboTemp();
						SaveDrawArray();
					}
				}
			//}
		}
		
		require ("includes/print_error_table_log.incl");
		require ("includes/log_save_nums_array.incl");

		return 1;
   }
?>