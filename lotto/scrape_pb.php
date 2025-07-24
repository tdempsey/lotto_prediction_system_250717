<?php

	$game = 7; // Mega Millions

	require ("includes/games_switch.incl");

	$debug = 1;

	if ($debug)
	{
		ini_set('display_errors', '1');
		ini_set('display_startup_errors', '1');
		error_reporting(E_ALL);
	}

	require ("includes/mysqli.php");
	#require ("includes/db.class");
	require ('includes/db.class.php');

	$filename = 'C:\Bitnami\wampstack-7.4.12-0\apache2\htdocs\lotto\pb201130.txt';
	echo "$filename<p>";
	$fp = fopen("$filename", "r");

	$contents = fread($fp, filesize($filename));

	preg_match_all("(\d{2}\/\d{2}\/\d{2})", $contents, $matches_date);

	print_r ($matches_date);

	preg_match_all("(\d{2} \d{2} \d{2} \d{2} \d{2})", $contents, $matches_nums);

	print_r ($matches_nums);

	preg_match_all("(Megaball: \d{2})", $contents, $matches_mm);

	print_r ($matches_mm);

	$size_date = count($matches_date, COUNT_RECURSIVE);

	echo "<p>size_date = $size_date<\br>";

	for ($x = 0; $x <= ($size_date-2); $x++)
	{
		$date = explode("/", $matches_date[0][$x]);

		$temp_date = "20" . $date[2];

		print "$temp_date/$date[0]/$date[1]&nbsp;&nbsp;&nbsp;";

		$draws = explode(" ", $matches_nums[0][$x]);

		#$pb_ball = explode(" ", $matches_mm[0][$x]);

		#print_r ($pb_ball);

		print "$draws[0]-$draws[1]-$draws[2]-$draws[3]-$draws[4] MM $pb_ball[1]<br> ";

		// get from draw table
		$query5 = "SELECT * FROM $draw_table_name ";
		$query5 .= "WHERE date = '$date[2]-$date[0]-$date[1]'  ";

		echo "$query5<br>";
	
		$mysqli_result5 = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));

		$num_rows5 = mysqli_num_rows($mysqli_result5);

		echo "num_rows5 = $num_rows5<br>";
				
		if (!$num_rows5)
		{
			#$query_insert = "INSERT INTO `pb_draws` (`date`, `b1`, `b2`, `b3`, `b4`, `b5`, `pb`, `sum`, `even`, `odd`, `d501`, `d502`, `draw0`, `draw1`, `draw2`, `draw3`, `draw4`, `draw5`, `rank0`, `rank1`, `rank2`, `rank3`, `rank4`, `rank5`, `rank6`, `pair_sum`, `draw_average`, `median`, `harmean`, `geomean`, `quart1`, `quart2`, `quart3`, `stdev`, `variance`, `avedev`, `kurt`, `skew`, `devsq`, `last_updated`) VALUES ('$temp_date-$date[0]-$date[1]', '$draws[0]', '$draws[1]', '$draws[2]', '$draws[3]', '$draws[4]', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0.0', '0.0', '0.0', '0.0', '0.0', '0.0', '0.0', '0.0', '0.0', '0.0', '0.0', '0.0', '0.0', '1962-08-17');";

			$query_insert = "INSERT INTO `pb_draws` (`date`, `b1`, `b2`, `b3`, `b4`, `b5`, `pb`, `sum`, `even`, `odd`, `d501`, `d502`, `draw0`, `draw1`, `draw2`, `draw3`, `draw4`, `draw5`, `draw6`, `rank0`, `rank1`, `rank2`, `rank3`, `rank4`, `rank5`, `rank6`, `pair_sum`, `draw_average`, `median`, `harmean`, `geomean`, `quart1`, `quart2`, `quart3`, `stdev`, `variance`, `avedev`, `kurt`, `skew`, `devsq`, `last_updated`) VALUES ('$temp_date-$date[0]-$date[1]', '$draws[0]', '$draws[1]', '$draws[2]', '$draws[3]', '$draws[4]', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1962-08-17');";

			echo "$query_insert<p>";
		
			$mysqli_result_insert = mysqli_query($mysqli_link, $query_insert) or die (mysqli_error($mysqli_link));
		}
	}

?>