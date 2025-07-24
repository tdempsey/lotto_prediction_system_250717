<?php

	$game = 1; // Georgia Fantasy 5

	ini_set('display_errors', '1');
	ini_set('display_startup_errors', '1');
	error_reporting(E_ALL);

	require ($_SERVER['DOCUMENT_ROOT'].'/lotto/includes/games_switch.incl');

	require ($_SERVER['DOCUMENT_ROOT'].'/lotto/includes/mysqli.php');

	$debug = 1;

	if ($debug)
	{
		ini_set('display_errors', '1');
		ini_set('display_startup_errors', '1');
		error_reporting(E_ALL);
	}

	$mysqli_link = mysqli_connect('localhost', 'root', '', 'ga_f5_lotto');

	if (!$mysqli_link) {
		die('Connect Error (' . mysqli_connect_errno() . ') '
				. mysqli_connect_error());
	}

	echo 'Success... ' . mysqli_get_host_info($mysqli_link) . "\n";

	#mysqli_close($mysqli_link);	
	#require ("includes/db.class");
	#require ('includes/db.class.php');

	$filename = 'C:\wamp64\www\lotto\gaf5_170615.txt';
	echo "$filename<p>";
	$fp = fopen("$filename", "r");

	$contents = fread($fp, filesize($filename));

	echo "contents = $contents<br>";

	preg_match_all("(\d{2}\/\d{2}\/\d{4})", $contents, $matches_date);

	print_r ($matches_date);

	preg_match_all("(\d{2} \d{2} \d{2} \d{2} \d{2})", $contents, $matches_nums);

	print_r ($matches_nums);

	$size_date = count($matches_date, COUNT_RECURSIVE);

	echo "size_date = $size_date<p>";

	for ($x = 0; $x <= ($size_date-2); $x++)
	{
		$date = explode("/", $matches_date[0][$x]);

		print "$date[2]/$date[0]/$date[1]&nbsp;&nbsp;&nbsp;";

		$draws = explode(" ", $matches_nums[0][$x]);

		print "$draws[0]-$draws[1]-$draws[2]-$draws[3]-$draws[4]<br> ";

		// get from draw table
		$query5 = "SELECT * FROM $draw_table_name ";
		$query5 .= "WHERE date = '$date[2]-$date[0]-$date[1]'  ";

		echo "$query5<br>";
	
		$mysqli_result5 = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));

		$num_rows5 = mysqli_num_rows($mysqli_result5);

		echo "num_rows5 = $num_rows5<br>";
				
		if (!$num_rows5)
		{
			$query_insert = "INSERT INTO `ga_f5_lotto`.`ga_f5_draws` (`date`, `b1`, `b2`, `b3`, `b4`, `b5`, `sum`, `even`, `odd`, `eo50_wa`, `draw0`, `draw1`, `draw2`, `draw3`, `draw4`, `rank0`, `rank1`, `rank2`, `rank3`, `rank4`, `rank5`, `rank6`, `pair_sum`, `draw_average`, `median`, `harmean`, `geomean`, `quart1`, `quart2`, `quart3`, `stdev`, `variance`, `avedev`, `kurt`, `skew`, `devsq`, `nums_total_2`, `combo_total_2`, `nums_total_3`, `combo_total_3`, `nums_total_4`, `combo_total_4`, `s1510`, `s61510`, `last_updated`) VALUES ('$date[2]-$date[0]-$date[1]', $draws[0], $draws[1], $draws[2], $draws[3], $draws[4], 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0.00, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '1962-08-17');"; 

			echo "$query_insert<p>";

			// Check for errors
			if(mysqli_connect_errno()){
				echo mysqli_connect_error();
			}

			$mysqli_result_insert = mysqli_query($mysqli_link, $query_insert) or die (mysqli_error($mysqli_link));
		}
	}

?>