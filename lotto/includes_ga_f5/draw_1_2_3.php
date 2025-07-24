<?php
// ----------------------------------------------------------- 
   function draw_1_2_3($draw_count,&$one_count,&$two_count,&$three_count) 
   { 
		$one_count = 0;
		$two_count = 0;
		$three_count = 0;

		while(list($key,$val) = each($draw_count)) { 
			if ($val == 1) 
			{
				$one_count++; 
			}
			elseif ($val == 2) 
			{
				$two_count++; 
			}
			elseif ($val == 3) 
			{
				$three_count++; 
			}
		} 

		return 0; 
   }
?>