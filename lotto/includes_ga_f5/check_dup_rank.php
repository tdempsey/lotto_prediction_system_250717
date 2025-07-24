<?php
	function check_dup_rank($draw)	###250126###
	{
		global $dup_temp, $rank_limit_temp, $rank_count_temp, $last_1_draws, $last_2_draws, $last_3_draws,$last_4_draws, 
			   $dup_pass_fail, $rank_pass_fail;
		
		$dup_pass_fail = 1; 
		$rank_pass_fail = 1;

		// Calculate duplicates in the current draw
		
		### calculate dup_pass_fail ###################################################################################
		$draw_rank_count = array_fill (0, 7, 0);

		// Count matches for each array
		$count_dup1 = count(array_intersect($draw, $last_1_draws));
		$count_dup2 = count(array_intersect($draw, $last_2_draws));
		$count_dup3 = count(array_intersect($draw, $last_3_draws));
		$count_dup4 = count(array_intersect($draw, $last_4_draws));
		
		// Output the results
		echo "<br>Count in dup1: $count_dup1<br>";
		echo "Count in dup2: $count_dup2<br>";
		echo "Count in dup3: $count_dup3<br>";
		echo "Count in dup4: $count_dup4<br><br>";
		
		if ($count_dup1 > 1) {
			$dup_pass_fail = 0; 
			echo "<b>dup_pass_fail1 = $dup_pass_fail</b><br><br>";
		} elseif ($count_dup2 > 2) {
			$dup_pass_fail = 0; 
			echo "<b>dup_pass_fail2 = $dup_pass_fail</b><br><br>";
		} elseif ($count_dup3 > 3) {
			$dup_pass_fail = 0; 
			echo "<b>dup_pass_fail3 = $dup_pass_fail</b><br><br>";
		} elseif ($count_dup4 > 4) {  	
			$dup_pass_fail = 0; 
			echo "<b>dup_pass_fail4 = $dup_pass_fail</b><br><br>";
		}
		
		echo "<b>dup_pass_fail = $dup_pass_fail</b><br><br>";
		
		### calculate rank_pass_fail ##################################################################################
		$rank_draw_temp = array_fill(0,8,0);
		
		// Calculate rank in the current draw
		foreach ($draw as $num) {
			if ($rank_count_temp[$num] > 7) {
				$temp = 7;
				$rank_draw_temp[$temp]++;
				echo "Count in rank_draw_temp[$temp] = $rank_draw_temp[$temp]<br><br>";
			} else {
				$temp = $rank_count_temp[$num];
				$rank_draw_temp[$temp]++;
				echo "Count in rank_draw_temp[$temp] = $rank_draw_temp[$temp]<br><br>";
			}
		}	
		
		echo "rank_draw_temp:<br>";
		print_r($rank_draw_temp);
		echo "<p>";
		
		// Compare rank totals in the current draw
		for ($k = 0; $k <= 7; $k++) {
			if ($rank_draw_temp[$k] > $rank_limit_temp[$k]) {
				$rank_pass_fail = 0; 
				echo "<b>rank_draw_temp[$k] ($rank_draw_temp[$k]) > rank_limit_temp[$k] ($rank_limit_temp[$k])</b><br><br>";
			}
		}	
		
		echo "<b>rank_pass_fail = $rank_pass_fail</b><br><br>";
	}
?>