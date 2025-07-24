<?php

	$game = 1; // Georgia Fantasy 5

	require ($_SERVER['DOCUMENT_ROOT'].'/lotto/includes/games_switch.incl');

	require ($_SERVER['DOCUMENT_ROOT'].'/lotto/includes/mysqli.php');

	$mysqli_link = mysqli_connect('localhost', 'root', '', 'ga_f5_lotto');

	if (!$mysqli_link) {
		die('Connect Error (' . mysqli_connect_errno() . ') '
				. mysqli_connect_error());
	}

	echo 'Success... ' . mysqli_get_host_info($mysqli_link) . "\n";

	$debug = 1;

	if ($debug)
	{
		ini_set('display_errors', '1');
		ini_set('display_startup_errors', '1');
		error_reporting(E_ALL);
	}

	//start HTML page
	print("<HTML>\n");
	print("<HEAD>\n");
	print("<TITLE>$game_name Missing - $game_name</TITLE>\n");
	print("</HEAD>\n");
	
	print("<BODY>\n");

	$curr_date = date('Y-m-d');

	$date = strtotime($curr_date);
    $date = strtotime("-1 day", $date);
    $last_updated = date('Y-m-d', $date);

	echo "$last_updated<br>";

	$date = explode('-', $last_updated);

	print_r ($date);

	$lastupdated = $date[0];
	$lastupdated .= $date[1];
	$lastupdated .= $date[2];

	echo "$lastupdated<br>";

	$today_unix = time ();

	echo "$today_unix<br>";

	$d1510_unix = mktime ('10','1','2015');

	$year1510 = $today_unix - $d1510_unix;	        # 1510 2015-10-01

	echo "$year1510<br>";

	$query = "SELECT date FROM $draw_table_name ";
	$query .= "WHERE date >= '2015-10-1' ";
	$query .= "ORDER BY date ASC "; 

	print "$query<p>";

	$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

	while($row = mysqli_fetch_array($mysqli_result))
	{
	#echo "date = $row[date] <br>";

		$draw_date = explode('-', $row['date']);

		print_r ($draw_date);		

		echo "<br>";

		#echo "$draw_date_unix<br>";

		$date_temp = $draw_date[0] . '-' . $draw_date[1] . '-' . $draw_date[2];

		echo "date_temp - $date_temp<br>";

		$previous_draw = date('Y-m-d',(strtotime ( '-1 day' , strtotime ($date_temp) ) ));

		$query2 = "SELECT date FROM $draw_table_name ";
		$query2 .= "WHERE date = '$previous_draw' ";
		#$query2 .= "ORDER BY date DESC "; 

		print "$query2<p>";

		$mysqli_result2 = mysqli_query($mysqli_link, $query2) or die (mysqli_error($mysqli_link));

		$num_rows_all = mysqli_num_rows($mysqli_result2);

		$row2 = mysqli_fetch_array($mysqli_result2);

		if ($num_rows_all)
		{
			echo "previous date $row2[date] found<br><br>";
		} else {
			echo "<b>previous date $previous_draw not found</b><br><br>";
		}
	}
	

?>