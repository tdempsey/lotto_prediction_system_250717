<?php
	function Count5Seq($draw)
	{
		$seq5 = 0;

		// error checking ----------------------------------------------------------------------------------------------
		if (count($draw) == 0)
		{
			exit("<h2>Error - function count_5_seq.php - <font color=\"#FF0000\">\$draw undefined</font></h2>");
		}

		if (array_sum($draw) == 0)
		{
			exit("<h2>Error - function count_5_seq.php - <font color=\"#FF0000\">\$draw array_sum = 0</font></h2>");
		}

		if ($draw[0] == 0)
		{
			exit("<h2>Error - function count_5_seq.php - <font color=\"#FF0000\">\$draw[0] == 0</font></h2>");
		}
		// error checking ----------------------------------------------------------------------------------------------

		sort($draw);
		
		for ($count = 0 ; $count <= count($draw)-5; $count++)
		{
			$num1 = $draw[$count];
			$num2 = $draw[$count+1];
			$num3 = $draw[$count+2];
			$num4 = $draw[$count+3];
			$num5 = $draw[$count+4];
			if ($num1 == ($num2-1) && $num2 == ($num3-1) && $num3 == ($num4-1) && $num4 == ($num5-1))
			{
				$seq5++;
			}
		}

		return $seq5;
	}
?>