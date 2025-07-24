<?php
	$game = 1; 
	
	require ("includes/games_switch.incl");

	// include to connect to database
	require ("includes/mysqli.php");
	#require ("includes/count_2_seq.php");
	#require ("includes/count_3_seq.php");
	#require ("$game_includes/combin.incl");

	//start HTML page
	print("<HTML>\n");
	print("<HEAD>\n");
	print("<TITLE>Lotto Verify</TITLE>\n");
	print("</HEAD>\n");
	
	print("<BODY bgcolor=\"#FFFFFF\" text=\"#000000\">\n");

	print "<center><h1>Draw Analyze Grid</h1></center>";

	$limit = 104;

	//start table pair_draw_6
	print("<h3>Last $limit draws</h3>\n");
	print("<TABLE BORDER=\"1\">\n");

	//create header row
	print("<TR><B>\n");
	print("<TD BGCOLOR=\"#CCCCCC\" nowrap align=center>Date</TD>\n");
	print("<TD BGCOLOR=\"#CCCCCC\" align=center><center>pair_sum</center></TD>\n");
	print("<T/R><B>\n");

	$query = "SELECT * FROM $draw_table_name ";
	$query .= "ORDER BY date DESC ";
	$query .= "LIMIT $limit ";

	//print("$query<p>");

	$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

	while($row = mysqli_fetch_array($mysqli_result))
	{
		print("<TR>\n");
		print("<TD align=center>$row[date]</TD>\n");
		print("<TD align=center>$row[pair_sum]</TD>\n");
		print("<TR>\n");
	}
	
	print("</TABLE>\n");

	//start table pair_draw_5
	print("<h3>Last $limit draws</h3>\n");
	print("<TABLE BORDER=\"1\">\n");

	//create header row
	print("<TR><B>\n");
	print("<TD BGCOLOR=\"#CCCCCC\" nowrap align=center>Date</TD>\n");
	print("<TD BGCOLOR=\"#CCCCCC\" align=center><center>combin_5_1</center></TD>\n");
	print("<TD BGCOLOR=\"#CCCCCC\" align=center><center>combin_5_2</center></TD>\n");
	print("<TD BGCOLOR=\"#CCCCCC\" align=center><center>combin_5_3</center></TD>\n");
	print("<TD BGCOLOR=\"#CCCCCC\" align=center><center>combin_5_4</center></TD>\n");
	print("<TD BGCOLOR=\"#CCCCCC\" align=center><center>combin_5_5</center></TD>\n");
	print("<TD BGCOLOR=\"#CCCCCC\" align=center><center>combin_5_6</center></TD>\n");
	print("<TD BGCOLOR=\"#CCCCCC\" align=center><center>combin_sum</center></TD>\n");
	print("<T/R><B>\n");

	$query = "SELECT * FROM $draw_table_name ";
	$query .= "ORDER BY date DESC ";
	$query .= "LIMIT $limit ";

	//print("$query<p>");

	$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

	while($row = mysqli_fetch_array($mysqli_result))
	{
		print("<TR>\n");
		print("<TD align=center>$row[date]</TD>\n");
		print("<TD align=center>$row[26]</TD>\n");
		print("<TD align=center>$row[27]</TD>\n");
		print("<TD align=center>$row[28]</TD>\n");
		print("<TD align=center>$row[29]</TD>\n");
		print("<TD align=center>$row[30]</TD>\n");
		print("<TD align=center>$row[31]</TD>\n");

		$combin_5_sum = $row[26] + $row[27] + $row[28] + $row[29] + $row[30] + $row[31];

		print("<TD align=center>$combin_5_sum</TD>\n");

		print("<TR>\n");
	}
	
	print("</TABLE>\n");

	print("</body>");
	print("</html>");

?>