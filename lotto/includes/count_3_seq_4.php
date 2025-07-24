<?php
	function Count3Seq($draw)
	{
		$seq3 = 0;

		// error checking ----------------------------------------------------------------------------------------------
		if (count($draw) == 0)
		{
			exit("<h2>Error - function count_3_seq.php - <font color=\"#FF0000\">\$draw undefined</font></h2>");
		}

		if (count($draw) > 4)
		{
			exit("<h2>Error - function count_3_seq.php - <font color=\"#FF0000\">\$draw >4</font></h2>");
		}

		if (array_sum($draw) == 0)
		{
			exit("<h2>Error - function count_3_seq.php - <font color=\"#FF0000\">\$draw array_sum = 0</font></h2>");
		}

		if ($draw[0] == 0)
		{
			exit("<h2>Error - function count_2_seq.php - <font color=\"#FF0000\">\$draw[0] == 0</font></h2>");
		}
		// error checking ----------------------------------------------------------------------------------------------

		sort($draw);
		
		for ($count = 0 ; $count <= count($draw)-4; $count++)
		{
			$num1 = $draw[$count];
			$num2 = $draw[$count+1]-1;
			$num3 = $draw[$count+2]-2;
			$num4 = $draw[$count+3]-3;
			if ($num1 == $num2 && $num2 == $num3)
			{
				$seq3++;
			}
		}

		return $seq3;
	}
?>