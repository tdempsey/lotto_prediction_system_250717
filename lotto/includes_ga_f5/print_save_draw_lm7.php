<?php
	function PrintAndSaveDraw7($draw_average,$even,$odd,$d501,$d502,$percent,$total,$drawn_all)
	{
		global $sorted_nums,$next_draw_Ymd,$DA_id,$draw_prefix;

		// error checking ----------------------------------------------------------------------------------------------	
		if (is_null($sorted_nums) || count($sorted_nums) == 0)
			{
				exit("<h2>Error - function print_save_draw_lm5.php - <font color=\"#FF0000\">array undefined</font></h2>");
			}

		if (is_null($even) || is_null($odd) || is_null($d501) || is_null($d502) || is_null($percent) || 
			is_null($total) || is_null($drawn_all))
		{
			exit("<h2>Error - function print_save_draw_lm5.php - <font color=\"#FF0000\">parameter undefined - even = $even, odd = $odd, d501 = $d501, d502 = $d502, percent = $percent, total = $total, drawn_all = $drawn_all</font></h2>");
		}
		// error checking ----------------------------------------------------------------------------------------------
		
		require ("includes/mysqli.php");

		print("<TR>\n");
		print("<TD>$draw_average</TD>\n");
		for ($count = 0 ; $count <= 5; $count++)
		{
			print("<TD>$sorted_nums[$count]</TD>\n");
		}
		print("<TD>&nbsp;</TD>\n");
		print("<TD>$percent</TD>\n");
		print("<TD>$total</TD>\n");
		print("<TD>$drawn_all</TD>\n");
		print("<TD>$even</TD>\n");
		print("<TD>$odd</TD>\n");
		print("<TD>$d501</TD>\n");
		print("<TD>$d502</TD>\n");

		print("</TR>\n");

		$temp_table = $draw_prefix;
		$temp_table .= "picks_lm5_auto_";
		$temp_table .= $next_draw_Ymd;

		$query3 = "INSERT INTO $temp_table "; 
		$query3 .= "VALUES (0, ";
		$query3 .= "'$sorted_nums[0]', ";
		$query3 .= "'$sorted_nums[1]', ";
		$query3 .= "'$sorted_nums[2]', ";
		$query3 .= "'$sorted_nums[3]', ";
		$query3 .= "'$sorted_nums[4]', ";
		$query3 .= "'$sorted_nums[5]', ";
		$query3 .= "'$DA_id')";

		$mysqli_result3 = mysqli_query($mysqli_link, $query3) or die (mysqli_error($mysqli_link));
	}
?>