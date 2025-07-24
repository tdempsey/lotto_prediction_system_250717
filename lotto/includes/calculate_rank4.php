<?php
function calculate_rank_count($date,$draw,&$rank_count) 
{  
	global $debug, $game;

	$rank_table = BuildRankTable($date);

	$rank_count = array_fill (0, 8, 0);
	
	for ($index = 0; $index <= 3; $index++) ### 210107
	{
		$val = $draw[$index];

		$count = $rank_table[$val]; 
	
		switch ($count)
		{
				case "0":
					$rank_count[0]++;
					break;
				case "1":
					$rank_count[1]++;
					break;
				case "2":
					$rank_count[2]++;
					break;
				case "3":
					$rank_count[3]++;
					break;
				case "4":
					$rank_count[4]++;
					break;
				case "5":
					$rank_count[5]++;
					break;
				case "6":
					$rank_count[6]++;
					break;
				default:
					$rank_count[7]++;	
		}	
	}
      
	return 0; 
}
?>