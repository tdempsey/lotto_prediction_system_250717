<?php

	// add tables population for combos, pairs
	// add test for missing draws
	// recalcu;ate draw includes
	// add db class
	// error checking - all modules
	// fix pair count

	// Game ----------------------------- Game
	//$game = 1; // Georgia Fantasy 5
	//$game = 2; // Georgia Mega Millions
	//$game = 3; // Georgia Lotto South
	$game = 4; // Florida Fantasy 5
	//$game = 5; // Florida Mega Money
	//$game = 6; // Florida Lotto
	//$game = 7; // Powerball
	// Game ----------------------------- Game
	
	require ("includes/games_switch.incl");

	//require ("includes/mysqli.php");
	require ("includes/db.class");
	require ("includes/even_odd.php");
	require ("includes/calculate_50_50.php");
	require ("includes/build_rank_table.php");
	require ("includes/calculate_draw.php"); 
	require ("includes/calculate_rank.php");
	require ("includes/next_draw.php");
	
	$debug = 0;

	function column_total ($col)
	{ 
		global $debug, $game, $game_name, $balls, $balls_drawn, $mega_balls, $draw_table_name, $draw_prefix;
	
		require ("includes/mysqli.php");
		
		print("<a name=\"$limit\"><H2>$game_name - Column $col Totals - $draw_prefix filter_a</H2></a>\n");
	
		//start table
		print("<TABLE BORDER=\"1\">\n");
	
		//create header row
		print("<TR>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Col$col</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Total</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Comb4<=1</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Comb3>=8</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Comb34 Tot</center></TD>\n");
		print("</TR>\n");


		$col_array = array_fill (0, $balls+1, 0);

		$comb41_array = array_fill (0, $balls+1, 0);
		$comb38_array = array_fill (0, $balls+1, 0);
		$comb34_array = array_fill (0, $balls+1, 0);

		$total = 0;

		// get all from draw table
		$query5 = "SELECT * FROM $draw_prefix";
		$query5 .= "filter_a";

		$mysqli_result5 = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));
	
		// get each row
		while($row = mysqli_fetch_array($mysqli_result5))
		{
			$col_array[$row[b.$col]]++;
			$total++;
			
			if ($row[comb3] >= 8) 
			{
				$comb38_array[$row[b.$col]]++;
			} 

			if ($row[comb4] <= 1) 
			{
				$comb41_array[$row[b.$col]]++;
			} 

			if ($row[comb3] >= 8 && $row[comb4] <= 1) 
			{
				$comb34_array[$row[b.$col]]++;
			} 
		}

		for ($x = 1; $x <= $balls; $x++)
		{
			print("<TR>\n");
			print("<TD><B><center>$x</center></B></TD>\n");
			$num_temp = number_format($col_array[$x]);
			print("<TD><center>$num_temp</center></TD>\n");
			$num_temp = number_format($comb41_array[$x]);
			print("<TD><center>$num_temp</center></TD>\n");
			$num_temp = number_format($comb38_array[$x]);
			print("<TD><center>$num_temp</center></TD>\n");
			$num_temp = number_format($comb34_array[$x]);
			print("<TD><center>$num_temp</center></TD>\n");
			print("</TR>\n");
		}
		
		$num_temp = number_format($total);
	
		print("<TR>\n");
		print("<TD><center>Total</center></TD>\n");
		print("<TD><center>$num_temp</center></TD>\n");
		print("<TD>&nbsp;</TD>\n");
		print("<TD>&nbsp;</TD>\n");
		print("<TD>&nbsp;</TD>\n");
		print("</TR>\n");
	
		//end table
		print("</TABLE>\n");
	}

	function column_sum_total ($col)
	{ 
		global $debug, $game, $game_name, $balls, $balls_drawn, $mega_balls, $draw_table_name, $draw_prefix;
	
		require ("includes/mysqli.php");
		
		print("<a name=\"$limit\"><H2>$game_name - Column $col Sum Totals - $draw_prefix filter_a</H2></a>\n");
	
		//start table
		print("<TABLE BORDER=\"1\">\n");
	
		//create header row
		print("<TR>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Col$col</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Sum</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Total</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Comb4<=1</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Comb3>=8</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Comb34 Tot</center></TD>\n");
		print("</TR>\n");

		$col_array = array_fill (0, 291, 0);

		$col_sum_array = array_fill (0, $balls+1, $col_array);

		$comb41_array = array_fill (0, $balls+1, $col_array);
		$comb38_array = array_fill (0, $balls+1, $col_array);
		$comb34_array = array_fill (0, $balls+1, $col_array);

		$total = 0;

		// get all from draw table
		$query5 = "SELECT * FROM $draw_prefix";
		$query5 .= "filter_a";
	
		$mysqli_result5 = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));
	
		// get each row
		while($row = mysqli_fetch_array($mysqli_result5))
		{
			$col_sum_array[$row[b.$col]][$row[sum]]++;
			$total++;

			if ($row[comb3] >= 8) 
			{
				$comb38_array[$row[b.$col]][$row[sum]]++;
			} 

			if ($row[comb4] <= 1) 
			{
				$comb41_array[$row[b.$col]][$row[sum]]++;
			} 

			if ($row[comb3] >= 8 &&$row[comb4] <= 1) 
			{
				$comb34_array[$row[b.$col]][$row[sum]]++;
			} 

		}

		for ($x = 1; $x <= $balls; $x++)
		{
			$count = 0;
			for ($y = 20; $y <= 290; $y++)
			{
				if ($col_sum_array[$x][$y] != 0)
				{
					print("<TR>\n");
					print("<TD><B><center>$x</center></B></TD>\n");
					print("<TD><B><center>$y</center></B></TD>\n");
					$num_temp = number_format($col_sum_array[$x][$y]);
					print("<TD><center>$num_temp</center></TD>\n");
					$num_temp = number_format($comb41_array[$x][$y]);
					print("<TD><center>$num_temp</center></TD>\n");
					$num_temp = number_format($comb38_array[$x][$y]);
					print("<TD><center>$num_temp</center></TD>\n");
					$num_temp = number_format($comb34_array[$x][$y]);
					print("<TD><center>$num_temp</center></TD>\n");
					print("</TR>\n");
					$count++;
				}
			}

			if ($count)
			{
				print("<TR>\n");
				print("<TD BGCOLOR=\"#CCCCCC\"><center>Col$col</center></TD>\n");
				print("<TD BGCOLOR=\"#CCCCCC\"><center>Sum</center></TD>\n");
				print("<TD BGCOLOR=\"#CCCCCC\"><center>Total</center></TD>\n");
				print("<TD>&nbsp;</TD>\n");
				print("<TD>&nbsp;</TD>\n");
				print("<TD>&nbsp;</TD>\n");
				print("</TR>\n");
			}
		}
		
		$num_temp = number_format($total);
	
		print("<TR>\n");
		print("<TD><center>Total</center></TD>\n");
		print("<TD><center>$num_temp</center></TD>\n");
		print("</TR>\n");
	
		//end table
		print("</TABLE>\n");
	}

	function column_pairs_total ($cola,$colb)
	{ 
		global $debug, $game, $game_name, $balls, $balls_drawn, $mega_balls, $draw_table_name, $draw_prefix;
	
		require ("includes/mysqli.php");
		
		print("<a name=\"$limit\"><H2>$game_name - Col$cola/Col$colb Pairs Totals - $draw_prefix filter_a</H2></a>\n");
	
		//start table
		print("<TABLE BORDER=\"1\">\n");
	
		//create header row
		print("<TR>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Col$cola</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Col$colb</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Total</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Mod0</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Mod1</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Mod2</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Seq20</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Seq21</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Seq22</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Seq30</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Seq31</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Seq32</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Comb4<=1</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Comb3>=8</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Comb34 Tot</center></TD>\n");
		print("</TR>\n");

		$col_array = array_fill (0, $balls+1, 0);

		$cola_colb_array = array_fill (0, $balls+1, $col_array);
		$mod0_array = array_fill (0, $balls+1, $col_array);
		$mod1_array = array_fill (0, $balls+1, $col_array);
		$mod2_array = array_fill (0, $balls+1, $col_array);
		$seq20_array = array_fill (0, $balls+1, $col_array);
		$seq21_array = array_fill (0, $balls+1, $col_array);
		$seq22_array = array_fill (0, $balls+1, $col_array);
		$seq30_array = array_fill (0, $balls+1, $col_array);
		$seq31_array = array_fill (0, $balls+1, $col_array);
		$seq32_array = array_fill (0, $balls+1, $col_array);
		$comb41_array = array_fill (0, $balls+1, $col_array);
		$comb38_array = array_fill (0, $balls+1, $col_array);
		$comb34_array = array_fill (0, $balls+1, $col_array);

		$total = 0;

		// get all from draw table
		$query5 = "SELECT * FROM $draw_prefix";
		$query5 .= "filter_a";
	
		$mysqli_result5 = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));
	
		// get each row
		while($row = mysqli_fetch_array($mysqli_result5))
		{
			$cola_colb_array[$row[b.$cola]][$row[b.$colb]]++;
			if ($row[mod_tot] == 0)
			{
				$mod0_array[$row[b.$cola]][$row[b.$colb]]++;
			} elseif ($row[mod_tot] == 1){
				$mod1_array[$row[b.$cola]][$row[b.$colb]]++;
			} elseif ($row[mod_tot] == 2){
				$mod2_array[$row[b.$cola]][$row[b.$colb]]++;
			}

			if ($row[seq2] == 0) 
			{
				$seq20_array[$row[b.$cola]][$row[b.$colb]]++;
			} elseif ($row[seq2] == 1){
				$seq21_array[$row[b.$cola]][$row[b.$colb]]++;
			} elseif ($row[seq2] == 2){
				$seq22_array[$row[b.$cola]][$row[b.$colb]]++;
			}

			if ($row[seq3] == 0) 
			{
				$seq30_array[$row[b.$cola]][$row[b.$colb]]++;
			} elseif ($row[seq3] == 1){
				$seq31_array[$row[b.$cola]][$row[b.$colb]]++;
			} elseif ($row[seq3] == 2){
				$seq32_array[$row[b.$cola]][$row[b.$colb]]++;
			}	

			if ($row[comb3] >= 8) 
			{
				$comb38_array[$row[b.$cola]][$row[b.$colb]]++;
			} 

			if ($row[comb4] <= 1) 
			{
				$comb41_array[$row[b.$cola]][$row[b.$colb]]++;
			} 

			if ($row[comb3] >= 8 && $row[comb4] <= 1) 
			{
				$comb34_array[$row[b.$cola]][$row[b.$colb]]++;
			} 

			$total++;
		}

		$colab_total = 0;

		for ($x = 1; $x < $balls; $x++)
		{
			for ($y = $x+1; $y <= $balls; $y++)
			{
				if ($cola_colb_array[$x][$y])
				{
					print("<TR>\n");
					print("<TD><B><center>$x</center></B></TD>\n");
					print("<TD><B><center>$y</center></B></TD>\n");
					$num_temp = number_format($cola_colb_array[$x][$y]);
					print("<TD><center>$num_temp</center></TD>\n");
					$num_temp = number_format($mod0_array[$x][$y]);
					print("<TD><center>$num_temp</center></TD>\n");
					$num_temp = number_format($mod1_array[$x][$y]);
					print("<TD><center>$num_temp</center></TD>\n");
					$num_temp = number_format($mod2_array[$x][$y]);
					print("<TD><center>$num_temp</center></TD>\n");

					$num_temp = number_format($seq20_array[$x][$y]);
					print("<TD><center>$num_temp</center></TD>\n");
					$num_temp = number_format($seq21_array[$x][$y]);
					print("<TD><center>$num_temp</center></TD>\n");
					$num_temp = number_format($seq22_array[$x][$y]);
					print("<TD><center>$num_temp</center></TD>\n");

					$num_temp = number_format($seq30_array[$x][$y]);
					print("<TD><center>$num_temp</center></TD>\n");
					$num_temp = number_format($seq31_array[$x][$y]);
					print("<TD><center>$num_temp</center></TD>\n");
					$num_temp = number_format($seq32_array[$x][$y]);
					print("<TD><center>$num_temp</center></TD>\n");

					$num_temp = number_format($comb41_array[$x][$y]);
					print("<TD><center>$num_temp</center></TD>\n");
					$num_temp = number_format($comb38_array[$x][$y]);
					print("<TD><center>$num_temp</center></TD>\n");
					$num_temp = number_format($comb34_array[$x][$y]);
					print("<TD><center>$num_temp</center></TD>\n");

					print("</TR>\n");

					$colab_total++;
				}
			}

			if ($colab_total)
			{
				print("<TR>\n");
				print("<TD BGCOLOR=\"#CCCCCC\"><center>Col$cola</center></TD>\n");
				print("<TD BGCOLOR=\"#CCCCCC\"><center>Col$colb</center></TD>\n");
				print("<TD BGCOLOR=\"#CCCCCC\"><center>Total</center></TD>\n");
				print("<TD BGCOLOR=\"#CCCCCC\"><center>Mod0</center></TD>\n");
				print("<TD BGCOLOR=\"#CCCCCC\"><center>Mod1</center></TD>\n");
				print("<TD BGCOLOR=\"#CCCCCC\"><center>Mod2</center></TD>\n");
				print("<TD BGCOLOR=\"#CCCCCC\"><center>Seq20</center></TD>\n");
				print("<TD BGCOLOR=\"#CCCCCC\"><center>Seq21</center></TD>\n");
				print("<TD BGCOLOR=\"#CCCCCC\"><center>Seq22</center></TD>\n");
				print("<TD BGCOLOR=\"#CCCCCC\"><center>Seq30</center></TD>\n");
				print("<TD BGCOLOR=\"#CCCCCC\"><center>Seq31</center></TD>\n");
				print("<TD BGCOLOR=\"#CCCCCC\"><center>Seq32</center></TD>\n");
				print("<TD BGCOLOR=\"#CCCCCC\"><center>Comb4<=1</center></TD>\n");
				print("<TD BGCOLOR=\"#CCCCCC\"><center>Comb3>=8</center></TD>\n");
				print("<TD BGCOLOR=\"#CCCCCC\"><center>Comb34 Tot</center></TD>\n");
				print("</TR>\n");

				$colab_total = 0;
			}
		}
		
		$num_temp = number_format($total);
	
		print("<TR>\n");
		print("<TD colspan=\"2\"><center>Total</center></TD>\n");
		print("<TD><center>$num_temp</center></TD>\n");
		print("<TD>&nbsp;</TD>\n");
		print("<TD>&nbsp;</TD>\n");
		print("<TD>&nbsp;</TD>\n");
		print("<TD>&nbsp;</TD>\n");
		print("<TD>&nbsp;</TD>\n");
		print("<TD>&nbsp;</TD>\n");
		print("<TD>&nbsp;</TD>\n");
		print("<TD>&nbsp;</TD>\n");
		print("<TD>&nbsp;</TD>\n");
		print("<TD>&nbsp;</TD>\n");
		print("<TD>&nbsp;</TD>\n");
		print("<TD>&nbsp;</TD>\n");
		print("</TR>\n");
	
		//end table
		print("</TABLE>\n");
	}

	function column_pair_filter ($cola,$colb)
	{ 
		global $debug, $game, $game_name, $balls, $balls_drawn, $mega_balls, $draw_table_name, $draw_prefix;
	
		require ("includes/mysqli.php");
		
		print("<a name=\"$limit\"><H2>$game_name - Col$cola/Col$colb Pairs Totals - $draw_prefix filter_a</H2></a>\n");
	
		//start table
		print("<TABLE BORDER=\"1\">\n");
	
		//create header row
		print("<TR>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Col$cola</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Col$colb</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Total</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Mod0</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Mod1</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Mod2</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Seq20</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Seq21</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Seq22</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Seq30</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Seq31</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Seq32</center></TD>\n");
		print("</TR>\n");

		$col_array = array_fill (0, $balls+1, 0);

		$cola_colb_array = array_fill (0, $balls, $col_array);
		$mod0_array = array_fill (0, $balls, $col_array);
		$mod1_array = array_fill (0, $balls, $col_array);
		$mod2_array = array_fill (0, $balls, $col_array);
		$seq20_array = array_fill (0, $balls, $col_array);
		$seq21_array = array_fill (0, $balls, $col_array);
		$seq22_array = array_fill (0, $balls, $col_array);
		$seq30_array = array_fill (0, $balls, $col_array);
		$seq31_array = array_fill (0, $balls, $col_array);
		$seq32_array = array_fill (0, $balls, $col_array);

		$total = 0;

		// get all from draw table
		$query5 = "SELECT * FROM $draw_prefix";
		$query5 .= "filter_a";
	
		$mysqli_result5 = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));
	
		// get each row
		while($row = mysqli_fetch_array($mysqli_result5))
		{
			$cola_colb_array[$row[b.$cola]][$row[b.$colb]]++;
			if ($row[mod_tot] == 0)
			{
				$mod0_array[$row[b.$cola]][$row[b.$colb]]++;
			} elseif ($row[mod_tot] == 1){
				$mod1_array[$row[b.$cola]][$row[b.$colb]]++;
			} elseif ($row[mod_tot] == 2){
				$mod2_array[$row[b.$cola]][$row[b.$colb]]++;
			}

			if ($seq2 == 0) // add to combo table
			{
				$seq20_array[$row[b.$cola]][$row[b.$colb]]++;
			} elseif ($seq2 == 1){
				$seq21_array[$row[b.$cola]][$row[b.$colb]]++;
			} elseif ($seq2 == 2){
				$seq22_array[$row[b.$cola]][$row[b.$colb]]++;
			}

			if ($seq3 == 0) // add to combo table
			{
				$seq30_array[$row[b.$cola]][$row[b.$colb]]++;
			} elseif ($seq3 == 1){
				$seq31_array[$row[b.$cola]][$row[b.$colb]]++;
			} elseif ($seq3 == 2){
				$seq32_array[$row[b.$cola]][$row[b.$colb]]++;
			}	

			$total++;
		}

		for ($x = 1; $x < $balls; $x++)
		{
			for ($y = $x+1; $y <= $balls; $y++)
			{
				print("<TR>\n");
				print("<TD><B><center>$x</center></B></TD>\n");
				print("<TD><B><center>$y</center></B></TD>\n");
				$num_temp = number_format($cola_colb_array[$x][$y]);
				print("<TD><center>$num_temp</center></TD>\n");
				$num_temp = number_format($mod0_array[$x][$y]);
				print("<TD><center>$num_temp</center></TD>\n");
				$num_temp = number_format($mod1_array[$x][$y]);
				print("<TD><center>$num_temp</center></TD>\n");
				$num_temp = number_format($mod2_array[$x][$y]);
				print("<TD><center>$num_temp</center></TD>\n");
				print("</TR>\n");
			}

			print("<TR>\n");
			print("<TD BGCOLOR=\"#CCCCCC\"><center>Col$cola</center></TD>\n");
			print("<TD BGCOLOR=\"#CCCCCC\"><center>Col$colb</center></TD>\n");
			print("<TD BGCOLOR=\"#CCCCCC\"><center>Total</center></TD>\n");
			print("<TD BGCOLOR=\"#CCCCCC\"><center>Mod0</center></TD>\n");
			print("<TD BGCOLOR=\"#CCCCCC\"><center>Mod1</center></TD>\n");
			print("<TD BGCOLOR=\"#CCCCCC\"><center>Mod2</center></TD>\n");
			print("<TD BGCOLOR=\"#CCCCCC\"><center>Seq20</center></TD>\n");
			print("<TD BGCOLOR=\"#CCCCCC\"><center>Seq21</center></TD>\n");
			print("<TD BGCOLOR=\"#CCCCCC\"><center>Seq22</center></TD>\n");
			print("<TD BGCOLOR=\"#CCCCCC\"><center>Seq30</center></TD>\n");
			print("<TD BGCOLOR=\"#CCCCCC\"><center>Seq31</center></TD>\n");
			print("<TD BGCOLOR=\"#CCCCCC\"><center>Seq32</center></TD>\n");
			print("</TR>\n");
		}
		
		$num_temp = number_format($total);
	
		print("<TR>\n");
		print("<TD colspan=\"2\"><center>Total</center></TD>\n");
		print("<TD><center>$num_temp</center></TD>\n");
		print("<TD>&nbsp;</TD>\n");
		print("<TD>&nbsp;</TD>\n");
		print("<TD>&nbsp;</TD>\n");
		print("<TD>&nbsp;</TD>\n");
		print("<TD>&nbsp;</TD>\n");
		print("<TD>&nbsp;</TD>\n");
		print("<TD>&nbsp;</TD>\n");
		print("<TD>&nbsp;</TD>\n");
		print("<TD>&nbsp;</TD>\n");
		print("</TR>\n");
	
		//end table
		print("</TABLE>\n");
	}

	//start HTML page
	print("<HTML>\n");
	print("<HEAD>\n");
	print("<TITLE>Lotto Column Summary - $game_name - filter_a</TITLE>\n");
	print("</HEAD>\n");
	
	print("<BODY>\n");
		
	column_total (1);

	//column_total (5);

	column_sum_total (1);

	column_pairs_total (1,2);

	column_pairs_total (1,5);

	//column_pair_filter (1,5);

	print("</BODY>\n");
?>
