<?php
// ----------------------------------------------------------------------------------
	function dup_table_update($row)
	{
		global $debug,$min_test,$max_test,$date_test,$draw_prefix,$balls_drawn,$hml,$range_low,$range_high;

		require ("includes/mysqli.php"); 

		$last_dup = array_fill (0, 51, 0);

		for ($x = 1; $x <= 50; $x++)
		{
			${"last_".$x."_draws"} = LastDraws($row[0],$x);
		}

		//count repeating numbers
		for ($x = 1 ; $x <= 50; $x++)
		{
			for ($y = 1 ; $y <= $balls_drawn; $y++)
			{	
				$temp = 'last_' . $x . '_draws';
				if (array_search($row[$y], ${$temp}) !== FALSE)
				{
					$last_dup[$x]++;
				}
			}
		}

		$query_dup = "INSERT INTO $draw_prefix";
		if ($hml)
		{
			$query_dup .= "dup_table ";
			#$query_dup .= "$hml ";
		} else {
			$query_dup .= "dup_table ";
		}
		$query_dup .= "VALUES ('$row[0]', ";
		$query_dup .= "'$last_dup[1]', ";
		$query_dup .= "'$last_dup[2]', ";
		$query_dup .= "'$last_dup[3]', ";
		$query_dup .= "'$last_dup[4]', ";
		$query_dup .= "'$last_dup[5]', ";
		$query_dup .= "'$last_dup[6]', ";
		$query_dup .= "'$last_dup[7]', ";
		$query_dup .= "'$last_dup[8]', ";
		$query_dup .= "'$last_dup[9]', ";
		$query_dup .= "'$last_dup[10]', ";
		$query_dup .= "'$last_dup[11]', ";
		$query_dup .= "'$last_dup[12]', ";
		$query_dup .= "'$last_dup[13]', ";
		$query_dup .= "'$last_dup[14]', ";
		$query_dup .= "'$last_dup[15]', ";
		$query_dup .= "'$last_dup[16]', ";
		$query_dup .= "'$last_dup[17]', ";
		$query_dup .= "'$last_dup[18]', ";
		$query_dup .= "'$last_dup[19]', ";
		$query_dup .= "'$last_dup[20]', ";
		$query_dup .= "'$last_dup[21]', ";
		$query_dup .= "'$last_dup[22]', ";
		$query_dup .= "'$last_dup[23]', ";
		$query_dup .= "'$last_dup[24]', ";
		$query_dup .= "'$last_dup[25]', ";
		$query_dup .= "'$last_dup[26]', ";
		$query_dup .= "'$last_dup[27]', ";
		$query_dup .= "'$last_dup[28]', ";
		$query_dup .= "'$last_dup[29]', ";
		$query_dup .= "'$last_dup[30]', ";
		$query_dup .= "'$last_dup[31]', ";
		$query_dup .= "'$last_dup[32]', ";
		$query_dup .= "'$last_dup[33]', ";
		$query_dup .= "'$last_dup[34]', ";
		$query_dup .= "'$last_dup[35]', ";
		$query_dup .= "'$last_dup[36]', ";
		$query_dup .= "'$last_dup[37]', ";
		$query_dup .= "'$last_dup[38]', ";
		$query_dup .= "'$last_dup[39]', ";
		$query_dup .= "'$last_dup[40]', ";
		$query_dup .= "'$last_dup[41]', ";
		$query_dup .= "'$last_dup[42]', ";
		$query_dup .= "'$last_dup[43]', ";
		$query_dup .= "'$last_dup[44]', ";
		$query_dup .= "'$last_dup[45]', ";
		$query_dup .= "'$last_dup[46]', ";
		$query_dup .= "'$last_dup[47]', ";
		$query_dup .= "'$last_dup[48]', ";
		$query_dup .= "'$last_dup[49]', ";
		$query_dup .= "'$last_dup[50]')";

		echo "$query_dup<br>";

		$mysqli_result_dup = mysqli_query($mysqli_link, $query_dup) or die (mysqli_error($mysqli_link));

		return ($last_dup);
	}

?>