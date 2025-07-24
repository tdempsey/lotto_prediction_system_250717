<?php
// ----------------------------------------------------------- 
   function draw_count_total($draw_count) 
   { 
		$draw_count_total = array_fill (0, 4, 0);

		while(list($key,$val) = each($draw_count)) { 
			$draw_count_total[$val]++;
		} 

		return $draw_count_total; 
   }
?>