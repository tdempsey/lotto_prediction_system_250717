<?php
// ----------------------------------------------------------- 
   function draw_1_2($draw_count,&$one_count,&$two_count) 
   { 
		$one_count = 0;
		$two_count = 0;

		while(list($key,$val) = each($draw_count)) { 
			if ($val == 1) 
			{
				$one_count++; 
			}
			elseif ($val == 2) 
			{
				$two_count++; 
			}
		} 

		return 0; 
   }
?>