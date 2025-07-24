<?php

	$game = 6; // Florida Lotto

	require ("includes/games_switch.incl");
	require ("includes/mysqli.php");

	#$handle = printer_open();
	$handle = printer_open("Lexmark 730 Series");
	#$handle = printer_open("HP DeskJet 690C");

	printer_set_option($handle, PRINTER_SCALE, 100);
	printer_set_option($handle, PRINTER_TEXT_ALIGN, PRINTER_TA_LEFT);

	printer_set_option($handle, PRINTER_RESOLUTION_X, 300);
	printer_set_option($handle, PRINTER_RESOLUTION_Y, 300);

	$pen = printer_create_pen(PRINTER_PEN_SOLID, 2, "000000");
	printer_select_pen($handle, $pen);

	$brush = printer_create_brush(PRINTER_BRUSH_SOLID, "000000");
	printer_select_brush($handle, $brush);

	$temp_array = array (0,0);
	$print_array = array_fill (0, 5, $temp_array);

	#$panel = array (E,J,D,I,C,H,B,G,A,F);
	$panel = array (A,B,C,D,E,F,G,H,I,J);
	
	$query2 = "SELECT * FROM combo_6_53_temp2 ";
	#$query2 .= "WHERE b4 = 41 ";
	$query2 .= "ORDER BY pair_sum DESC ";
	$query2 .= "LIMIT 10 ";

	print "$query2<br>";

	$mysqli_result2 = mysqli_query($mysqli_link, $query2) or die (mysqli_error($mysqli_link));
	
	$x = 1;
	$y = 0;
	$pflag = 0;

	while($row = mysqli_fetch_array($mysqli_result2))
	{
		print "$row[pair_sum]<p>";

		if ($x == 1)
		{
			printer_start_doc($handle, "My Document");
			printer_start_page($handle);
			$pflag = 1;
		}

		#print_panel($panel[$y],101);

		for ($z = 0; $z < 6; $z++)
		{
			print_panel($panel[$y],$row[$z]);
		}

		$y++;
		
		if ($x == 10)
		{
			printer_end_page($handle);
			printer_end_doc($handle);
			$pflag = 0;
			$x = 1;
			$y = 0;
		} else {
			$x++;
		}
		
		$query3 = "DELETE FROM combo_6_53_temp2 ";
		$query3 .= "WHERE b1 = $row[b1] AND ";
		$query3 .= "      b2 = $row[b2] AND ";
		$query3 .= "      b3 = $row[b3] AND ";
		$query3 .= "      b4 = $row[b4] AND ";
		$query3 .= "      b5 = $row[b5] AND ";
		$query3 .= "      b6 = $row[b6]  ";

		print "$query3<p>";

		$mysqli_result3 = mysqli_query($mysqli_link, $query3) or die (mysqli_error($mysqli_link));
		
	}

	if ($pflag)
	{
		printer_end_page($handle);
		printer_end_doc($handle);
	}
	
	
	function print_panel($pid,$pnum)
	{
		require ("includes/mysqli.php");

		global $handle, $game;

		$x_variance = 10;
		$y_variance = 0;

		$query = "SELECT * FROM fl_print_slip ";
		$query .= "WHERE panel_id = '$pid' ";
		$query .= "AND panel_number = '$pnum' ";

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

		print "$query<br>";

		if ($rown = mysqli_fetch_array($mysqli_result))
		{	
			if ($pid == 'F' or
				$pid == 'G' or
				$pid == 'H' or
				$pid == 'I' or
				$pid == 'J')
			{
				$print_x = $rown[x] - 0;
				$print_y = $rown[y] + 0;
			} else {
				$print_x = $rown[x] - 0;
				$print_y = $rown[y] + 0;
			}

			if ($rown[x])
			{
				printer_draw_bmp($handle, "black60.bmp", $print_x, $print_y);
			} else {
				print "<h2>--- $pid --- $pnum ---</h2>";
			}
		}
	}
	/*
	printer_start_doc($handle, "My Document");
	printer_start_page($handle);
	
	print_panel('E',101);
	print_panel('E',102);
	print_panel('E',103);
	for ($x = 1; $x <= 53; $x++)
	{
		print_panel('E',$x);
	}

	print_panel('J',101);
	print_panel('J',102);
	print_panel('J',103);
	for ($x = 1; $x <= 53; $x++)
	{
		print_panel('J',$x);
	}
	
	printer_end_page($handle);
	printer_end_doc($handle);
	
	printer_delete_brush($brush);
	printer_delete_pen($pen);

	printer_close($handle);

	print "<center><h2>Done!</h2></center>";
	
	
	$query3 = "DELETE FROM combo_6_53_temp ";
	$query3 .= "WHERE b1 = $row[b1] AND ";
	$query3 .= "      b2 = $row[b2] AND ";
	$query3 .= "      b3 = $row[b3] AND ";
	$query3 .= "      b4 = $row[b4] AND ";
	$query3 .= "      b5 = $row[b5] AND ";
	$query3 .= "      b6 = $row[b6]  ";

	print "$query3<p>";

	$mysqli_result3 = mysqli_query($mysqli_link, $query3) or die (mysqli_error($mysqli_link));
	*/
?> 
