<?php

	$game = 1;	

	$combin = $_GET["combin"];
	$drange = $_GET["drange"];

	echo "combin = $combin<br>";
	echo "drange = $drange<br>";

	#$sumeo_sum = $_GET["sum"];
	#$sumeo_even = $_GET["even"];
	#$sumeo_odd = $_GET["odd"];

	require ("includes/games_switch.incl");

	require ("includes/mysqli.php");

	//start HTML page
	print("<HTML>\n");
	print("<HEAD>\n");
	print("<TITLE>Build combo_ $combin __42_drange $drange</TITLE>\n");
	print("</HEAD>\n");
	
	print("<BODY bgcolor=\"#FFFFFF\" text=\"#000000\">\n");

	$curr_date = date('Y-m-d');
	
	$query4 = "DROP TABLE IF EXISTS combo_";
	$query4 .= "$combin";
	$query4 .= "_42_drange";
	$query4 .= "$drange "; 

	print "$query4<p>";
	
	$mysqli_result4 = mysqli_query($mysqli_link, $query4) or die (mysqli_error($mysqli_link));

	#$query =  "CREATE TABLE IF NOT EXISTS combo_4__42_drange[1] ( ";
	$query = "CREATE TABLE IF NOT EXISTS combo_";
	$query .= "$combin";
	$query .= "_42_drange";
	$query .= "$drange ( "; 
	$query .= "  `id` int(10) unsigned NOT NULL auto_increment, ";
	$query .= "  `combo_id` int(10) unsigned NOT NULL default '0', ";

	switch ($drange)
	{
		case 2:
			$query .= "  `d2_1` tinyint(1) unsigned NOT NULL default '0', ";
			$query .= "  `d2_2` tinyint(1) unsigned NOT NULL default '0', ";
			break;
		case 3:
			$query .= "  `d3_1` tinyint(1) unsigned NOT NULL default '0', ";
			$query .= "  `d3_2` tinyint(1) unsigned NOT NULL default '0', ";
			$query .= "  `d3_3` tinyint(1) unsigned NOT NULL default '0', ";
			break;
		case 4:
			$query .= "  `d4_1` tinyint(1) unsigned NOT NULL default '0', ";
			$query .= "  `d4_2` tinyint(1) unsigned NOT NULL default '0', ";
			$query .= "  `d4_3` tinyint(1) unsigned NOT NULL default '0', ";
			$query .= "  `d4_4` tinyint(1) unsigned NOT NULL default '0', ";
			break;
		case 5:
			$query .= "  `d5_1` tinyint(1) unsigned NOT NULL default '0', ";
			$query .= "  `d5_2` tinyint(1) unsigned NOT NULL default '0', ";
			$query .= "  `d5_3` tinyint(1) unsigned NOT NULL default '0', ";
			$query .= "  `d5_4` tinyint(1) unsigned NOT NULL default '0', ";
			$query .= "  `d5_5` tinyint(1) unsigned NOT NULL default '0', ";
			break;
		case 6:
			$query .= "  `d6_1` tinyint(1) unsigned NOT NULL default '0', ";
			$query .= "  `d6_2` tinyint(1) unsigned NOT NULL default '0', ";
			$query .= "  `d6_3` tinyint(1) unsigned NOT NULL default '0', ";
			$query .= "  `d6_4` tinyint(1) unsigned NOT NULL default '0', ";
			$query .= "  `d6_5` tinyint(1) unsigned NOT NULL default '0', ";
			$query .= "  `d6_6` tinyint(1) unsigned NOT NULL default '0', ";
			break;
		case 7:
			$query .= "  `d7_1` tinyint(1) unsigned NOT NULL default '0', ";
			$query .= "  `d7_2` tinyint(1) unsigned NOT NULL default '0', ";
			$query .= "  `d7_3` tinyint(1) unsigned NOT NULL default '0', ";
			$query .= "  `d7_4` tinyint(1) unsigned NOT NULL default '0', ";
			$query .= "  `d7_5` tinyint(1) unsigned NOT NULL default '0', ";
			$query .= "  `d7_6` tinyint(1) unsigned NOT NULL default '0', ";
			$query .= "  `d7_7` tinyint(1) unsigned NOT NULL default '0', ";
			break;
		default:
			echo "error = drange = $drange<br";
			die();
	}

	$query .= "  PRIMARY KEY  (`id`) ";
	$query .= ") ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 "; 

	print "$query<p>";

	$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));
	
	#$query = "SELECT * FROM combo_4__42_drange[1] a ";
	$query = "SELECT * FROM combo_";
	$query .= "$combin";
	$query .= "_42_drange";
	$query .= "$drange a "; 
	#$query .= "JOIN combo_4_42 b ";
	$query .= "JOIN combo_";
	$query .= "$combin";
	$query .= "_42 b ";
	$query .= "ON a.combo_id = b.id ";
	$query .= "ORDER BY b.id DESC ";
	$query .= "LIMIT 1 ";

	print "$query<p>";
	
	$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

	$row = mysqli_fetch_array($mysqli_result);

	$row[b1] = 1;		###########################################################################
	$row[combo_id] = 0; ###########################################################################

	echo "row[b1] = $row[b1]<br>";
	echo "row[combo_id] = $row[combo_id]<br>";

	$b1_lim = 43-$combin;

	for ($v = $row[b1]; $v <= $b1_lim; $v++) ### 22 ###
	{
		$query2 = "SELECT * FROM combo_";
		$query2 .= "$combin";
		$query2 .= "_42 ";
		$query2 .= "WHERE b1 = $v ";
		$query2 .= "AND id >= $row[combo_id] ";
		$query2 .= "ORDER BY id ASC ";

		print "$query2<p>";
		
		$mysqli_result2 = mysqli_query($mysqli_link, $query2) or die (mysqli_error($mysqli_link));

		$num_rows = mysqli_num_rows($mysqli_result2);

		echo "<b>num_rows = $num_rows</b><br>";
		
		while($row2 = mysqli_fetch_array($mysqli_result2))
		{
			$query2c = "SELECT * FROM combo_";
			$query2c .= "$combin";
			$query2c .= "_42_drange";
			$query2c .= "$drange "; 
			$query2c .= "WHERE combo_id = $row2[id] ";
			$query2c .= "ORDER BY id ASC ";

			print "$query2c<p>";
			
			$mysqli_result2c = mysqli_query($mysqli_link, $query2c) or die (mysqli_error($mysqli_link));

			$row2c = mysqli_fetch_array($mysqli_result2c);

			$num_rows2c = mysqli_num_rows($mysqli_result2c);

			if ($num_rows2c)
			{
				$found = 1;
			} else {
				$found = 0;
			}

			if (!$found)
			{
				$draw = array ();

				for ($x = 1; $x <= $combin; $x++) #################################################
				{
					array_push($draw, $row2[$x]);
				}

				switch ($drange)
				{
					case 2:
						$drange_array = array_fill (0,10,0);
						$range = array_fill (0,10,0);

						for ($y = 1; $y < $drange; $y++)
						{
							$range[$y] = intval(((42/$drange)*$y));
						}

						reset ($draw); 
					
						foreach ($draw as $val) 
						{ 
							if ($val > $range[1]) { 
								$drange_array[2]++;
							} else {
								$drange_array[1]++;
							}
						} 
						break;
					case 3:
						$drange_array = array_fill (0,10,0);
						$range = array_fill (0,10,0);

						for ($y = 1; $y < $drange; $y++)
						{
							$range[$y] = intval(((42/$drange)*$y));
						}

						reset ($draw); 
					
						foreach ($draw as $val) 
						{ 
							if ($val > $range[2]) { 
								$drange_array[3]++;
							} elseif ($val > $range[1]) { 
								$drange_array[2]++;
							} else {
								$drange_array[1]++;
							} 
						}
						break;
					case 4:
						$drange_array = array_fill (0,10,0);
						$range = array_fill (0,10,0);

						for ($y = 1; $y < $drange; $y++)
						{
							$range[$y] = intval(((42/$drange)*$y));
						}

						$range_lim = $drange-1;
						echo "1 range_lim = $range_lim<br>";
					
						foreach ($draw as $val) 
						{ 
							if ($val > $range[3]) { 
								$drange_array[4]++;
							} elseif ($val > $range[2]) { 
								$drange_array[3]++;
							} elseif ($val > $range[1]) { 
								$drange_array[2]++;
							} else {
								$drange_array[1]++;
							} 
						}
						break;
					case 5:
						$drange_array = array_fill (0,10,0);
						$range = array_fill (0,10,0);

						for ($y = 1; $y < $drange; $y++)
						{
							$range[$y] = intval(((42/$drange)*$y));
						}

						reset ($draw); 
					
						foreach ($draw as $val) 
						{ 
							if ($val > $range[4]) { 
								$drange_array[5]++;
							} elseif ($val > $range[3]) { 
								$drange_array[4]++;
							} elseif ($val > $range[2]) { 
								$drange_array[3]++;
							} elseif ($val > $range[1]) { 
								$drange_array[2]++;
							} else {
								$drange_array[1]++;
							} 
						}
						break;
					case 6:
						$drange_array = array_fill (0,10,0);
						$range = array_fill (0,10,0);

						for ($y = 1; $y < $drange; $y++)
						{
							$range[$y] = intval(((42/$drange)*$y));
						}

						reset ($draw); 
					
						foreach ($draw as $val) 
						{ 
							if ($val > $range[5]) { 
								$drange_array[6]++;
							} elseif ($val > $range[4]) { 
								$drange_array[5]++;
							} elseif ($val > $range[3]) { 
								$drange_array[4]++;
							} elseif ($val > $range[2]) { 
								$drange_array[3]++;
							} elseif ($val > $range[1]) { 
								$drange_array[2]++;
							} else {
								$drange_array[1]++;
							} 
						}
						break;
					case 7:
						$drange_array = array_fill (0,10,0);
						$range = array_fill (0,10,0);

						for ($y = 1; $y < $drange; $y++)
						{
							$range[$y] = intval(((42/$drange)*$y));
						}

						reset ($draw); 
					
						foreach ($draw as $val) 
						{ 
							if ($val > $range[6]) { 
								$drange_array[7]++;
							} elseif ($val > $range[5]) { 
								$drange_array[6]++;
							} elseif ($val > $range[4]) { 
								$drange_array[5]++;
							} elseif ($val > $range[3]) { 
								$drange_array[4]++;
							} elseif ($val > $range[2]) { 
								$drange_array[3]++;
							} elseif ($val > $range[1]) { 
								$drange_array[2]++;
							} else {
								$drange_array[1]++;
							} 
						}
						break;
					case 8:
						$drange_array = array_fill (0,10,0);
						$range = array_fill (0,10,0);

						for ($y = 1; $y < $drange; $y++)
						{
							$range[$y] = intval(((42/$drange)*$y));
						}

						reset ($draw); 
					
						foreach ($draw as $val) 
						{ 
							if ($val > $range[1]) { # > 28
								$drange_array[3]++;
							} elseif ($val > $range[2]) { 
								$drange_array[3]++;
							} elseif ($val > $range[2]) { 
								$drange_array[3]++;
							} elseif ($val > $range[2]) { 
								$drange_array[3]++;
							} elseif ($val > $range1) { # > 14
								$drange_array[2]++;
							} else {
								$drange_array[1]++;
							} 
						}
						break;
					case 9:
						$drange_array = array_fill (0,10,0);
						$range = array_fill (0,10,0);

						for ($y = 1; $y < $drange; $y++)
						{
							$range[$y] = intval(((42/$drange)*$y));
						}

						reset ($draw); 
					
						foreach ($draw as $val) 
						{ 
							if ($val > $range[1]) { # > 28
								$drange_array[3]++;
							} elseif ($val > $range[2]) { 
								$drange_array[3]++;
							} elseif ($val > $range[2]) { 
								$drange_array[3]++;
							} elseif ($val > $range[2]) { 
								$drange_array[3]++;
							} elseif ($val > $range1) { # > 14
								$drange_array[2]++;
							} else {
								$drange_array[1]++;
							} 
						}
						break;
					case 10:
						$drange_array = array_fill (0,10,0);
						$range = array_fill (0,10,0);

						for ($y = 1; $y < $drange; $y++)
						{
							$range[$y] = intval(((42/$drange)*$y));
						}
						reset ($draw); 
					
						foreach ($draw as $val) 
						{ 
							if ($val > $range[1]) { # > 28
								$drange_array[3]++;
							} elseif ($val > $range1) { # > 14
								$drange_array[2]++;
							} elseif ($val > $range[2]) { 
								$drange_array[3]++;
							} elseif ($val > $range[2]) { 
								$drange_array[3]++;
							} elseif ($val > $range[2]) { 
								$drange_array[3]++;
							} else {
								$drange_array[1]++;
							} 
						}
						break;
				}

				$query4 = "INSERT INTO combo_";
				$query4 .= "$combin";
				$query4 .= "_42_drange";
				$query4 .= "$drange "; 
				$query4 .= "VALUES('0', ";
				$query4 .= "'$row2[id]', ";
				
				for ($d = 1; $d < $drange; $d++)
				{
					$query4 .= "'$drange_array[$d]', ";
				}
				
					$query4 .= "'$drange_array[$d]') ";

				print("$query4<br>");
				
				$mysqli_result4 = mysqli_query($mysqli_link, $query4) or die (mysqli_error($mysqli_link));
			} else {
				
				while($row2d = mysqli_fetch_array($mysqli_result2c))
				{
					#$query2d = "DELETE FROM combo_4__42_drange[1] ";
					$query2d = "DELETE FROM combo_";
					$query2d .= "$combin";
					$query2d .= "_42_drange";
					$query2d .= "$drange "; 
					$query2d .= "WHERE combo_id = $row2d[id] ";

					print("$query2d<br>");
				
					$mysqli_result2d = mysqli_query($mysqli_link, $query2d) or die (mysqli_error($mysqli_link));
				}	
			}
		}
	}
?>