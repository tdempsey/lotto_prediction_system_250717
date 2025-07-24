<?php
// ----------------------------------------------------------- 
   function draw_count_total($draw_count) 
   { 
		//print_r ($draw_count);
		$draw_count_total = array_fill (0, 6, 0);

		while(list($key,$val) = each($draw_count)) { ### Not working in LAMP
			if ($val == 1){
				$draw_count_total[1]++;
			} elseif ($val == 2) {
				$draw_count_total[2]++;
			} elseif ($val == 3) {
				$draw_count_total[3]++;
			} elseif ($val == 4) {
				$draw_count_total[4]++;
			} elseif ($val == 5) {
				$draw_count_total[5]++;
			}
		} 

		return $draw_count_total; 
		//print_r ($draw_count_total);
   }
?>