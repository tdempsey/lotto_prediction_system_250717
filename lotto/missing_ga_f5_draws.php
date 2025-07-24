<?php
	$game = 1; // GA F5

	#$hml = 0;
	#$hml = 1;		#= high
	#$hml = 2;		#= medium
	#$hml = 3;		#= low
	#$hml = 4;		#= min
	#$hml = 5;		#= max
	#$hml = 90;	

	$debug = 1;

	if ($debug)
	{
		ini_set('display_errors', '1');
		ini_set('display_startup_errors', '1');
		error_reporting(E_ALL);
	}

	require_once ("includes/hml_switch.incl");

	// include to connect to database
	require ("includes/mysqli.php");

	require ("includes/games_switch.incl");

	print("<HTML>\n");
	print("<HEAD>\n");
	print("<TITLE>Missing Georgia Fantasy 5 Draws</TITLE>\n");
	print("</HEAD>\n");

	print("<BODY bgcolor=\"#FFFFFF\" text=\"#000000\">\n");

	print("<p align=\"center\"><b><font face=\"Arial, Helvetica, sans-serif\">Missing Georgia Fantasy 5 Draws</font></b></p>");

	print("</BODY>\n");

	$last_date = '0000-00-00';

	echo $last_date;

	### loop date from 10/01/2015
	### select date from draw table
	### increment by one till today

	### get count draw table
	$query5 = "SELECT * FROM $draw_table_name ";
	$query5 .= "WHERE date >= '2015-10-01' ";
	$query5 .= "ORDER BY date ASC ";

	echo "$query5<br>";

	$mysqli_result5 = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));

	while ($row5 = mysqli_fetch_array($mysqli_result5))
	{
		$date = new DateTime($row5[0]); // Fixed: removed single quotes around $row[0]
		$date->modify('-1 day');
		$prev_date = $date->format('Y-m-d');

		echo "draw date = $row5[0]<br>";

		if ($last_date != $prev_date)
		{
			echo ">>> missing - date $prev_date<br>";
		}

		$last_date = $row5[0]; // Fixed: removed single quotes around $row[0]
	}

	print("<h2>Completed!</h2>");

	print("</BODY>\n");
	print("</HTML>\n");


?>