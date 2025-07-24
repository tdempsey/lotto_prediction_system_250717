<?php
	function Count2Seq($draw)
	{
		$seq2 = 0;

		// error checking ----------------------------------------------------------------------------------------------
		if (count($draw) == 0)
		{
			exit("<h2>Error - function count_2_seq.php - <font color=\"#FF0000\">\$draw undefined</font></h2>");
		}

		if (array_sum($draw) == 0)
		{
			exit("<h2>Error - function count_2_seq.php - <font color=\"#FF0000\">\$draw array_sum = 0</font></h2>");
		}

		if ($draw[0] == 0)
		{
			exit("<h2>Error - function count_2_seq.php - <font color=\"#FF0000\">\$draw[0] == 0</font></h2>");
		}
		// error checking ----------------------------------------------------------------------------------------------

		sort($draw);
		
		for ($x = 0 ; $x <= count($draw)-2; $x++)
		{
			$num1 = $draw[$x];
			$num2 = $draw[$x+1];
			#echo "$draw[0],$draw[1],$draw[2],$draw[3],$draw[4]<br>";
			#echo "num1 = $num1 and num2 = $num2<br>";
			if ($num1 == ($num2-1))
			{
				$seq2++;
				#echo "seq2 = $seq2<br>";
			}
		}

		return $seq2;
	}
?>