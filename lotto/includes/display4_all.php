<?php
	function lot_display4_all ($limit)
	{ 
		global $debug, $game, $game_name, $balls, $balls_drawn, $mega_balls, $draw_table_name, $draw_prefix, $game_includes, $hml, $range_low, $range_high, $eo50_sum, $eo50_even, $eo50_odd, $eo50_d2_1, $eo50_d2_2, $combin;
	
		require ("includes/mysqli.php");
		#require ('includes/db.class.php');

		require ("$game_includes/config.incl");

		
		for ($combin = 1; $combin <= 5; $combin++)
		{
			print("<a name=\"$limit\"><H2>Lotto Display4/$combin - $game_name - Combin $combin - Limit $limit</H2></a>\n");

			require ("includes_ga_f5/display4nav.incl");

			// initalize variables [include]
			require ("includes/init_display.incl");
			
			if ($limit > 105 )
			{
				require ("includes_ga_f5/unsorted_table_4_all.incl");
				require ("includes_ga_f5/sorted_table_4_all.incl");
			} elseif ($limit == 100) {
				require ("includes_ga_f5/unsorted_table_4_all.incl");
				require ("includes_ga_f5/sorted_table_4_all.incl");
			} else {
				require ("includes_ga_f5/unsorted_table_4_all.incl");
				require ("includes_ga_f5/sorted_table_4_all.incl");
			}

			/*
			if ($limit > 105 )
			{
				require ("includes_ga_f5/sorted_table_large_4_all.incl");
			} elseif ($limit == 100) {
				require ("includes_ga_f5/sorted_table_100_4_all.incl");
			} else {
				require ("includes_ga_f5/sorted_table_4_all.incl");
			}
			*/
		}
	}
?>