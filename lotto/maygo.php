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

	$mysqli_link = mysqli_connect('localhost', 'root', 'wef5esuv', 'ga_f5_lotto');

	if (!$mysqli_link) {
		die('Connect Error (' . mysqli_connect_errno() . ') '
				. mysqli_connect_error());
	}

	echo 'Success... ' . mysqli_get_host_info($mysqli_link) . "\n";

	#mysqli_close($mysqli_link);	
	#require ("includes/db.class");
	#require ('includes/db.class.php');

	###############################################################################################

	$drop_tables = 1;
	
	if ($drop_tables)
	{
		$fdraw_table = "`magayo_fdraws`";

		$query = "DROP TABLE IF EXISTS $fdraw_table ";

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

		$query =  "CREATE TABLE IF NOT EXISTS  $fdraw_table ( ";
		$query .= "  `id` int(10) unsigned NOT NULL auto_increment, ";
		$query .= "  `b1` tinyint(2) unsigned NOT NULL default '0', ";
		$query .= "  `b2` tinyint(2) unsigned NOT NULL default '0', ";
		$query .= "  `b3` tinyint(2) unsigned NOT NULL default '0', ";
		$query .= "  `b4` tinyint(2) unsigned NOT NULL default '0', ";
		$query .= "  `b5` tinyint(2) unsigned NOT NULL default '0', ";
		$query .= "  `sum` int(5) unsigned NOT NULL default '0', ";
		$query .= "  `hml` int(3) unsigned NOT NULL default '0', ";
		$query .= "  `even` tinyint(1) unsigned NOT NULL default '0', ";
		$query .= "  `odd` tinyint(1) unsigned NOT NULL default '0', ";
		$query .= "  `d2_1` tinyint(1) unsigned NOT NULL default '0', ";
		$query .= "  `d2_2` tinyint(1) unsigned NOT NULL default '0', ";
		$query .= "  `d3_1` tinyint(1) unsigned NOT NULL default '0', ";
		$query .= "  `d3_2` tinyint(1) unsigned NOT NULL default '0', ";
		$query .= "  `d3_3` tinyint(1) unsigned NOT NULL default '0', ";
		$query .= "  `d4_1` tinyint(1) unsigned NOT NULL default '0', ";
		$query .= "  `d4_2` tinyint(1) unsigned NOT NULL default '0', ";
		$query .= "  `d4_3` tinyint(1) unsigned NOT NULL default '0', ";
		$query .= "  `d4_4` tinyint(1) unsigned NOT NULL default '0', ";
		$query .= "  `d0` tinyint(1) unsigned NOT NULL default '0', ";
		$query .= "  `d1` tinyint(1) unsigned NOT NULL default '0', ";
		$query .= "  `d2` tinyint(1) unsigned NOT NULL default '0', ";
		$query .= "  `d3` tinyint(1) unsigned NOT NULL default '0', ";
		$query .= "  `d4` tinyint(1) unsigned NOT NULL default '0', ";
		$query .= "  `rank0` tinyint(1) unsigned NOT NULL default '0', ";
		$query .= "  `rank1` tinyint(1) unsigned NOT NULL default '0', ";
		$query .= "  `rank2` tinyint(1) unsigned NOT NULL default '0', ";
		$query .= "  `rank3` tinyint(1) unsigned NOT NULL default '0', ";
		$query .= "  `rank4` tinyint(1) unsigned NOT NULL default '0', ";
		$query .= "  `rank5` tinyint(1) unsigned NOT NULL default '0', ";
		$query .= "  `rank6` tinyint(1) unsigned NOT NULL default '0', ";
		$query .= "  `mod_tot` tinyint(1) unsigned NOT NULL default '0', ";
		$query .= "  `mod_x` tinyint(1) unsigned NOT NULL default '0', ";
		$query .= "  `seq2` tinyint(1) unsigned NOT NULL default '0', ";
		$query .= "  `seq3` tinyint(1) unsigned NOT NULL default '0', ";
		$query .= "  `comb2` tinyint(1) unsigned NOT NULL default '0', ";
		$query .= "  `comb3` tinyint(1) unsigned NOT NULL default '0', ";
		$query .= "  `comb4` tinyint(1) unsigned NOT NULL default '0', ";
		$query .= "  `comb5` tinyint(1) unsigned NOT NULL default '0', ";
		$query .= "  `comb6` tinyint(1) unsigned NOT NULL default '0', ";
		$query .= "  `dup1` tinyint(1) unsigned NOT NULL default '0', ";
		$query .= "  `dup2` tinyint(1) unsigned NOT NULL default '0', ";
		$query .= "  `dup3` tinyint(1) unsigned NOT NULL default '0', ";
		$query .= "  `dup4` tinyint(1) unsigned NOT NULL default '0', ";
		$query .= "  `dup5` tinyint(1) unsigned NOT NULL default '0', ";
		$query .= "  `dup6` tinyint(1) unsigned NOT NULL default '0', ";
		$query .= "  `dup7` tinyint(1) unsigned NOT NULL default '0', ";
		$query .= "  `dup8` tinyint(1) unsigned NOT NULL default '0', ";
		$query .= "  `dup9` tinyint(1) unsigned NOT NULL default '0', ";
		$query .= "  `dup10` tinyint(1) unsigned NOT NULL default '0', ";
		$query .= "  `pair_sum` mediumint(8) unsigned NOT NULL default '0', ";
		$query .= "  `avg` float(4,2) unsigned NOT NULL default '0.00', ";
		$query .= "  `median` float(4,2) unsigned NOT NULL default '0.00', ";
		$query .= "  `harmean` float(4,2) unsigned NOT NULL default '0.00', ";
		$query .= "  `geomean` float(4,2) unsigned NOT NULL default '0.00', ";
		$query .= "  `quart1` float(4,2) unsigned NOT NULL default '0.00', ";
		$query .= "  `quart2` float(4,2) unsigned NOT NULL default '0.00', ";
		$query .= "  `quart3` float(4,2) unsigned NOT NULL default '0.00', ";
		$query .= "  `stdev` float(4,2) unsigned NOT NULL default '0.00', ";
		$query .= "  `variance` float(6,2) unsigned NOT NULL default '0.00', ";
		$query .= "  `avedev` float(4,2) unsigned NOT NULL default '0.00', ";
		$query .= "  `kurt` float(4,2) NOT NULL default '0.00', ";
		$query .= "  `skew` float(4,2) NOT NULL default '0.00', ";
		$query .= "  `devsq` float(6,2) unsigned NOT NULL default '0.00', ";
		$query .= "  `wheel_cnt5000` mediumint(5) unsigned NOT NULL default '0', ";
		$query .= "  `wheel_percent_wa` float(4,2) unsigned NOT NULL default '0.00', ";
		$query .= "  `draw_last` date NOT NULL default '1962-08-17', ";
		$query .= "  `draw_count` tinyint(3) unsigned NOT NULL default '0', ";
		$query .= "  `last_updated` date NOT NULL default '1962-08-17', ";
		$query .= "  PRIMARY KEY  (`id`) ";
		$query .= ") ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 "; 

		print("$query<p>");

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));
	}

	###############################################################################################

	$filename = 'C:\Users\tomde\Documents\magayo\20230910_Fantasy 5_filtered.txt';
	$filename .= 'C:\Users\tomde\Documents\magayo\20230910_Fantasy 5_filtered.txt';
	$filename .= 'C:\Users\tomde\Documents\magayo\20230910_Fantasy 5_filtered.txt';
	#                                            ~~~~~~~~
	echo "$filename<p>";
	$fp = fopen("$filename", "r");

	$contents = fread($fp, filesize($filename));

	echo "contents = $contents<br>";

	preg_match_all("(\d{2} \d{2} \d{2} \d{2} \d{2})", $contents, $matches_draws);

	print_r ($matches_draws);

	$size_draws = count($matches_draws, COUNT_RECURSIVE);

	echo "size_date = $size_date<p>";

	for ($x = 0; $x <= ($size_date-2); $x++)
	{
		$draws = explode(" ", $matches_draws[0][$x]);

		print "$draws[0]-$draws[1]-$draws[2]-$draws[3]-$draws[4]<br> ";
		
		$query_insert = "INSERT INTO `magayo`.`fdraw_table` (`0`, `b1`, `b2`, `b3`, `b4`, `b5`, `sum`, `even`, `odd`, `eo50_wa`, `draw0`, `draw1`, `draw2`, `draw3`, `draw4`, `rank0`, `rank1`, `rank2`, `rank3`, `rank4`, `rank5`, `rank6`, `pair_sum`, `draw_average`, `median`, `harmean`, `geomean`, `quart1`, `quart2`, `quart3`, `stdev`, `variance`, `avedev`, `kurt`, `skew`, `devsq`, `nums_total_2`, `combo_total_2`, `nums_total_3`, `combo_total_3`, `nums_total_4`, `combo_total_4`, `s1510`, `s61510`, `last_updated`) VALUES ('$date[2]-$date[0]-$date[1]', $draws[0], $draws[1], $draws[2], $draws[3], $draws[4], 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0.00, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '1962-08-17');"; 

			echo "$query_insert<p>";

			// Check for errors
			if(mysqli_connect_errno()){
				echo mysqli_connect_error();
			}

			$mysqli_result_insert = mysqli_query($mysqli_link, $query_insert) or die (mysqli_error($mysqli_link));
	}

?>