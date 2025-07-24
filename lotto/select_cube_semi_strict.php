<?php

	// Game ----------------------------- Game
	//$game = 1; // Georgia Fantasy 5
	#$game = 2; // Mega Millions
	//$game = 3; // Georgia Lotto South
	#$game = 4; // Florida Fantasy 5
	//$game = 5; // Florida Mega Money
	$game = 6; // Florida Lotto
	#$game = 7; // Powerball
	// Game ----------------------------- Game
	
	require ("includes/games_switch.incl");

	require ("includes/mysqli.php");
	
	$debug = 0;
	
	$col1 = 7; # <-------------------------------------------------------------------

	$temp_array = array_fill (0,5,0);
	$matrix = array_fill (0,5,$temp_array);
	$lock_array = array_fill (0,5,$temp_array);
	$number_lock = array_fill(0,54,0);
	$column_limit = array_fill(0,7,$temp_array);
	$row_lock = array_fill(0,9,0);

	#--------------------------------------------------------------------------------
	# Set column limits - Test!!!!!!!!!!
	#--------------------------------------------------------------------------------
		$query = "SELECT * FROM fl_draws ";
		$query .= "WHERE b1 = $col1 "; 
		$query .= "ORDER BY date DESC "; 

		print "$query<p>";

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

		#$num_rows = mysqli_num_rows($mysqli_result);
		
		for ($y = 2; $y <= 6; $y++)
		{
			mysqli_data_seek($mysqli_result, 0);

			$temp_array = array_fill (0,19,0);
			$a = 0;

			while($row = mysqli_fetch_array($mysqli_result) && ($a <= 19))
			{
				$temp_array[$a++] = $row[$y];
			}

			sort $temp_array;
			
			$column_limit[$y][0] = $temp_array[0];
			#$column_limit[$y][1] = $temp_array[19];
			$column_limit[2][1] = $temp_array[count($temp-array)-1];
		}

		print_r ($column_limit);

	#--------------------------------------------------------------------------------
	# Seed matrix - col1 diag [0,0 - 4,4] - change to column selection!!!!!!!!!!!!!
	#--------------------------------------------------------------------------------
		$x = 0;

		for ($y = 2; $y <= 6; $y++)
		{
			#$query = "SELECT DISTINCT num2,sigma FROM fl_temp_2_5000 ";
			#$query .= "WHERE num1 = $col1 ";
			#$query .= "AND (num2 >= $column_limit[$y][0] AND num2 <= $column_limit[$y][1])";
			#$query .= "ORDER BY sigma DESC ";

			$query = "SELECT DISTINCT num1,num2,sigma "
			. "FROM `fl_temp_2_5000` "
			. "WHERE num1 = $col1 "
			. "AND (num2 >= $column_limit[$y][0] AND num2 <= $column_limit[$y][1]) "
			. "AND num2 > $col1 ORDER BY `fl_temp_2_5000`.`sigma` ASC ";

			print "$query<p>";

			$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

			$row = mysqli_fetch_array($mysqli_result);
			
			$matrix[$x][$x] = $row[num2];
			$x++; # added for readability
		}

	#--------------------------------------------------------------------------------
	# Seed matrix - sigma - 4,0 - 0,4 - function needed and test!!!!!!!!!!!!!
	#--------------------------------------------------------------------------------

		$sigma_array = array_fill (0,54,0);

		#$query = "SELECT DISTINCT num2,sigma FROM fl_temp_2_5000 ";
		#$query .= "WHERE num1 = $matrix[0][0] ";
		#$query .= "AND num2 != $matrix[4][4] ";
		#$query .= "AND (num2 >= $column_limit[5][0] AND num2 <= $column_limit[5][1])";

		$query = "SELECT DISTINCT num1,num2,sigma "
		. "FROM `fl_temp_2_5000` "
		. "WHERE num1 = $matrix[0][0] "
		. "AND num2 != $matrix[4][4] ";
		. "AND (num2 >= $column_limit[5][0] AND num2 <= $column_limit[5][1]) "
		. "AND num2 > $col1 ORDER BY `fl_temp_2_5000`.`sigma` ASC ";

		print "$query<p>";

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

		while($row = mysqli_fetch_array($mysqli_result))
		{
			$sigma_array[$row[num2]] += $row[sigma];
		}

		#$query = "SELECT DISTINCT num2,sigma FROM fl_temp_2_5000 ";
		#$query .= "WHERE num1 = $matrix[4][4] ";
		#$query .= "AND num2 != $matrix[0][0] ";
		#$query .= "AND (num2 >= $column_limit[5][0] AND num2 <= $column_limit[5][1])";

		$query = "SELECT DISTINCT num1,num2,sigma "
		. "FROM `fl_temp_2_5000` "
		. "WHERE num1 = $matrix[4][4] "
		. "AND num2 != $matrix[0][0] "
		. "AND (num2 >= $column_limit[5][0] AND num2 <= $column_limit[5][1]) "
		. "AND num2 > $col1 ORDER BY `fl_temp_2_5000`.`sigma` ASC ";

		print "$query<p>";

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

		while($row = mysqli_fetch_array($mysqli_result))
		{
			$sigma_array[$row[num2]] += $row[sigma];
		}

		$z = 0;

		for ($y = 0; $y <= 53; $y++)
		{
			if ($sigma_array[$y] > $z)
			{
				$matrix[0][4] = $sigma_array[$y];
			}
		}

	#--------------------------------------------------------------------------------

		$sigma_array = array_fill (0,54,0);

		$query = "SELECT DISTINCT num2,sigma FROM fl_temp_2_5000 ";
		$query .= "WHERE num1 = $matrix[0][0] ";
		$query .= "AND num2 != $matrix[4][4] ";
		$query .= "AND num2 != $matrix[0][4] ";
		$query .= "AND (num2 >= $column_limit[5][0] AND num2 <= $column_limit[5][1])";

		print "$query<p>";

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

		while($row = mysqli_fetch_array($mysqli_result))
		{
			$sigma_array[$row[num2]] += $row[sigma];
		}

		$query = "SELECT DISTINCT num2,sigma FROM fl_temp_2_5000 ";
		$query .= "WHERE num1 = $matrix[4][4] ";
		$query .= "AND num2 != $matrix[0][0] ";
		$query .= "AND num2 != $matrix[0][4] ";
		$query .= "AND (num2 >= $column_limit[5][0] AND num2 <= $column_limit[5][1])";

		print "$query<p>";

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

		while($row = mysqli_fetch_array($mysqli_result))
		{
			$sigma_array[$row[num2]] += $row[sigma];
		}

		$z = 0;

		for ($y = 0; $y <= 53; $y++)
		{
			if ($sigma_array[$y] > $z)
			{
				$matrix[4][0] = $sigma_array[$y];
			}
		}


	#--------------------------------------------------------------------------------
	#--- Draw 1 - 0,0-0,4 - and fail!!!!!!!!!!!!! - restrict sum
	#--------------------------------------------------------------------------------

	$filter_table = "filter_b_6_53";
	if ($draw_num[1] < 10)
	{
		$filter_table .= "_0$draw_num[1]";
	} else {
		$filter_table .= "_$draw_num[1]";
	}
	#$filter_table .= "_";
	$filter_table .= "$col1";

	#add sigma range ----------------------------------------------

	$query = "SELECT * FROM $filter_table  ";
	$query .= " WHERE (b1 = '{$matrix[0][0]}'  
					OR b2 = '{$matrix[0][0]}' 
					OR b3 = '{$matrix[0][0]}' 
					OR b4 = '{$matrix[0][0]}' 
					OR b5 = '{$matrix[0][0]}' 
					OR b6 = '{$matrix[0][0]}') 
					AND b6 != '{$matrix[4][4]}' "; 
	$query .= "ORDER BY b2,b3 ASC "; # ??? - sigma???
	#$query .= "ORDER RAND "; # ??? - sigma???

	print "$query<p>";

	$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

	$num_rows = mysqli_num_rows($mysqli_result);

	print "num_rows = $num_rows<p>";

	$temp_array = array_fill (0,$num_rows-1,0);
	$a = 0;

	while($row = mysqli_fetch_array($mysqli_result))
	{
		$temp_array[$a++] = $row[id];
	}

	mt_srand((float) microtime() * 10000000);

	$r = mt_rand(0,$num_rows-1);

	$query1 = "SELECT * FROM $filter_table "
	."WHERE id = '$temp_array[$r]' ";	#------------ fix

	print "$query1<p>";

	$mysqli_result1 = mysqli_query($mysqli_link, $query1) or die (mysqli_error($mysqli_link));

	$row_rand = mysqli_fetch_array($mysqli_result1);
	
	$failed = 0;

	if ($row_rand[b5] == $matrix[0][3])
	{
		$failed = 1;
	} elseif ($row_rand[b4] == $matrix[0][2]) {
		$failed = 1;
	} elseif ($row_rand[b3] == $matrix[0][1]) {
		$failed = 1;
	} elseif ($row_rand[b2] == $matrix[0][0]) { # ???
		$failed = 1;
	}

	$matrix[0][1] = $row_rand[b4]; 
	$matrix[0][2] = $row_rand[b6]; 
	$matrix[0][3] = $row_rand[b3]; 
	$matrix[0][4] = $row_rand[b5]; 

	print "<h3>Draw 1 - $row_rand[b1],$row_rand[b2],$row_rand[b3],$row_rand[b4],$row_rand[b5],$row_rand[b6]</h3>";

	#--------------------------------------------------------------------------------
	#--- end - Draw 1 - 0,0-0,4
	#--------------------------------------------------------------------------------
	die ("die - draw 1");
	#--------------------------------------------------------------------------------
	#--- Draw 2 - 4,0-4,4 -  - and fail!!!!!!!!!!!!!
	#--------------------------------------------------------------------------------

	$filter_table = "filter_b_6_53";
	if ($draw_num[1] < 10)
	{
		$filter_table .= "_0$draw_num[1]";
	} else {
		$filter_table .= "_$draw_num[1]";
	}
	$filter_table .= "_";
	$filter_table .= "$col1";

	$query = "SELECT * FROM $filter_table  ";
	$query .= " WHERE (b1 = $matrix[4][0] 
					OR b2 = $matrix[4][0] 
					OR b3 = $matrix[4][0] 
					OR b4 = $matrix[4][0] 
					OR b5 = $matrix[4][0] 
					OR b6 = $matrix[4][0])
					AND (b1 = $matrix[4][4] 
					  OR b2 = $matrix[4][4] 
					  OR b3 = $matrix[4][4] 
					  OR b4 = $matrix[4][4] 
					  OR b5 = $matrix[4][4] 
					  OR b6 = $matrix[4][4]) ";
	$query .= "ORDER BY id DESC "; # ??? - sigma???
	$query .= "ORDER BY RAND "; # ??? - sigma???

	print "$query<p>";

	$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

	$num_rows = mysqli_num_rows($mysqli_result);

	mt_srand((float) microtime() * 10000000);

	$r = mt_rand(0,$num_rows);

	$query1 = "SELECT * FROM $filter_table  ";
	$query1 .= "WHERE id = '$r' ";	#---------------- fix

	print "$query1<p>";

	$mysqli_result1 = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

	$row_rand = mysqli_fetch_array($mysqli_result1);

	$failed = 0;

	if ($row_rand[b5] == $matrix[0][3])
	{
		$failed = 1;
	} elseif ($row_rand[b4] == $matrix[0][2]) {
		$failed = 1;
	} elseif ($row_rand[b3] == $matrix[0][1]) {
		$failed = 1;
	} elseif ($row_rand[b2] == $matrix[0][0]) { # ???
		$failed = 1;
	}

	$matrix[4][3] = $row_rand[b3]; 
	$matrix[4][2] = $row_rand[b4]; 
	$matrix[4][1] = $row_rand[b5]; 
	$matrix[4][0] = $row_rand[b6];  # sigma???

	print "<h3>Draw 2 - $row_rand[b1],$row_rand[b2],$row_rand[b3],$row_rand[b4],$row_rand[b5],$row_rand[b6]</h3>";

	#--------------------------------------------------------------------------------
	#--- end - Draw 2 - 4,0-4,4
	#--------------------------------------------------------------------------------

	#--------------------------------------------------------------------------------
	#--- Draw 3 - 0,0-4,0  - and fail!!!!!!!!!!!!!
	#--------------------------------------------------------------------------------

	$filter_table = "filter_b_6_53";
	if ($draw_num[1] < 10)
	{
		$filter_table .= "_0$draw_num[1]";
	} else {
		$filter_table .= "_$draw_num[1]";
	}
	$filter_table .= "_";
	$filter_table .= "$col1";

	$query = "SELECT * FROM $filter_table  ";
	$query .= " WHERE (b1 = $matrix[0][0] 
					OR b2 = $matrix[0][0] 
					OR b3 = $matrix[0][0] 
					OR b4 = $matrix[0][0] 
					OR b5 = $matrix[0][0] 
					OR b6 = $matrix[0][0])
					AND (b1 = $matrix[4][0] 
					  OR b2 = $matrix[4][0] 
					  OR b3 = $matrix[4][0] 
					  OR b4 = $matrix[4][0] 
					  OR b5 = $matrix[4][0] 
					  OR b6 = $matrix[4][0]) ";
	$query .= "ORDER BY id DESC "; # ??? - sigma???
	$query .= "ORDER BY RAND "; # ??? - sigma???

	print "$query<p>";

	$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

	$num_rows = mysqli_num_rows($mysqli_result);

	mt_srand((float) microtime() * 10000000);

	$r = mt_rand(0,$num_rows);

	$query1 = "SELECT * FROM $filter_table  ";
	$query1 .= "WHERE id = '$r' ";	#---------------- fix

	print "$query1<p>";

	$mysqli_result1 = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

	$row_rand = mysqli_fetch_array($mysqli_result1);

	if ($row_rand[b5] == $matrix[0][3])
	{
		$failed = 1;
	} elseif ($row_rand[b4] == $matrix[0][2]) {
		$failed = 1;
	} elseif ($row_rand[b3] == $matrix[0][1]) {
		$failed = 1;
	} elseif ($row_rand[b2] == $matrix[0][0]) { # ???
		$failed = 1;
	}

	$matrix[4][3] = $row_rand[b3]; 
	$matrix[4][2] = $row_rand[b4]; 
	$matrix[4][1] = $row_rand[b5]; 
	$matrix[4][0] = $row_rand[b6];  # sigma???


	print "<h3>Draw 2 - $row_rand[b1],$row_rand[b2],$row_rand[b3],$row_rand[b4],$row_rand[b5],$row_rand[b6]</h3>";

	#--------------------------------------------------------------------------------
	#--- end - Draw 3 - 0,0-4,0
	#--------------------------------------------------------------------------------

	#--------------------------------------------------------------------------------
	#--- Draw 4 - 4,0-4,4 - and fail!!!!!!!!!!!!!
	#--------------------------------------------------------------------------------

	$filter_table = "filter_b_6_53";
	if ($draw_num[1] < 10)
	{
		$filter_table .= "_0$draw_num[1]";
	} else {
		$filter_table .= "_$draw_num[1]";
	}
	$filter_table .= "_";
	$filter_table .= "$col1";

	$query = "SELECT * FROM $filter_table  ";
	$query .= " WHERE (b1 = $matrix[4][4] 
					OR b2 = $matrix[4][4] 
					OR b3 = $matrix[4][4] 
					OR b4 = $matrix[4][4] 
					OR b5 = $matrix[4][4] 
					OR b6 = $matrix[4][4])
					AND (b1 = $matrix[0][4] 
					  OR b2 = $matrix[0][4] 
					  OR b3 = $matrix[0][4] 
					  OR b4 = $matrix[0][4] 
					  OR b5 = $matrix[0][4] 
					  OR b6 = $matrix[0][4]) ";
	$query .= "ORDER BY id DESC "; # ??? - sigma???
	$query .= "ORDER BY RAND "; # ??? - sigma???

	print "$query<p>";

	$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

	$num_rows = mysqli_num_rows($mysqli_result);

	mt_srand((float) microtime() * 10000000);

	$r = mt_rand(0,$num_rows);

	$query1 = "SELECT * FROM $filter_table  ";
	$query1 .= "WHERE id = '$r' ";	#---------------- fix

	print "$query1<p>";

	$mysqli_result1 = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

	$row_rand = mysqli_fetch_array($mysqli_result1);

	if ($row_rand[b5] == $matrix[0][3])
	{
		$failed = 1;
	} elseif ($row_rand[b4] == $matrix[0][2]) {
		$failed = 1;
	} elseif ($row_rand[b3] == $matrix[0][1]) {
		$failed = 1;
	} elseif ($row_rand[b2] == $matrix[0][0]) { # ???
		$failed = 1;
	}

	$matrix[4][3] = $row_rand[b3]; 
	$matrix[4][2] = $row_rand[b4]; 
	$matrix[4][1] = $row_rand[b5]; 
	$matrix[4][0] = $row_rand[b6];  # sigma???

	print "<h3>Draw 2 - $row_rand[b1],$row_rand[b2],$row_rand[b3],$row_rand[b4],$row_rand[b5],$row_rand[b6]</h3>";

	#--------------------------------------------------------------------------------
	#--- end - Draw 4 - 4,0-4,4
	#--------------------------------------------------------------------------------

	#--------------------------------------------------------------------------------
	#--- Draw 5 - 4,0-4,4 - and fail!!!!!!!!!!!!!
	#--------------------------------------------------------------------------------

	$filter_table = "filter_b_6_53";
	if ($draw_num[1] < 10)
	{
		$filter_table .= "_0$draw_num[1]";
	} else {
		$filter_table .= "_$draw_num[1]";
	}
	$filter_table .= "_";
	$filter_table .= "$col1";

	$query = "SELECT * FROM $filter_table  ";
	$query .= " WHERE (b1 = $matrix[4][4] 
					OR b2 = $matrix[4][4] 
					OR b3 = $matrix[4][4] 
					OR b4 = $matrix[4][4] 
					OR b5 = $matrix[4][4] 
					OR b6 = $matrix[4][4])
					AND (b1 = $matrix[0][4] 
					  OR b2 = $matrix[0][4] 
					  OR b3 = $matrix[0][4] 
					  OR b4 = $matrix[0][4] 
					  OR b5 = $matrix[0][4] 
					  OR b6 = $matrix[0][4]) ";
	$query .= "ORDER BY id DESC "; # ??? - sigma???
	$query .= "ORDER BY RAND "; # ??? - sigma???

	print "$query<p>";

	$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

	$num_rows = mysqli_num_rows($mysqli_result);

	mt_srand((float) microtime() * 10000000);

	$r = mt_rand(0,$num_rows);

	$query1 = "SELECT * FROM $filter_table  ";
	$query1 .= "WHERE id = '$r' ";	#---------------- fix

	print "$query1<p>";

	$mysqli_result1 = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

	$row_rand = mysqli_fetch_array($mysqli_result1);

	if ($row_rand[b5] == $matrix[0][3])
	{
		$failed = 1;
	} elseif ($row_rand[b4] == $matrix[0][2]) {
		$failed = 1;
	} elseif ($row_rand[b3] == $matrix[0][1]) {
		$failed = 1;
	} elseif ($row_rand[b2] == $matrix[0][0]) { # ???
		$failed = 1;
	}

	$matrix[4][3] = $row_rand[b3]; 
	$matrix[4][2] = $row_rand[b4]; 
	$matrix[4][1] = $row_rand[b5]; 
	$matrix[4][0] = $row_rand[b6];  # sigma???

	print "<h3>Draw 2 - $row_rand[b1],$row_rand[b2],$row_rand[b3],$row_rand[b4],$row_rand[b5],$row_rand[b6]</h3>";

	#--------------------------------------------------------------------------------
	#--- end - Draw 5 - 4,0-4,4
	#--------------------------------------------------------------------------------

	#--------------------------------------------------------------------------------
	#--- Draw 6 - 4,0-4,4 - and fail!!!!!!!!!!!!!
	#--------------------------------------------------------------------------------

	$filter_table = "filter_b_6_53";
	if ($draw_num[1] < 10)
	{
		$filter_table .= "_0$draw_num[1]";
	} else {
		$filter_table .= "_$draw_num[1]";
	}
	$filter_table .= "_";
	$filter_table .= "$col1";

	$query = "SELECT * FROM $filter_table  ";
	$query .= " WHERE (b1 = $matrix[4][4] 
					OR b2 = $matrix[4][4] 
					OR b3 = $matrix[4][4] 
					OR b4 = $matrix[4][4] 
					OR b5 = $matrix[4][4] 
					OR b6 = $matrix[4][4])
					AND (b1 = $matrix[0][4] 
					  OR b2 = $matrix[0][4] 
					  OR b3 = $matrix[0][4] 
					  OR b4 = $matrix[0][4] 
					  OR b5 = $matrix[0][4] 
					  OR b6 = $matrix[0][4]) ";
	$query .= "ORDER BY id DESC "; # ??? - sigma???
	$query .= "ORDER BY RAND "; # ??? - sigma???

	print "$query<p>";

	$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

	$num_rows = mysqli_num_rows($mysqli_result);

	mt_srand((float) microtime() * 10000000);

	$r = mt_rand(0,$num_rows);

	$query1 = "SELECT * FROM $filter_table  ";
	$query1 .= "WHERE id = '$r' ";	#---------------- fix

	print "$query1<p>";

	$mysqli_result1 = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

	$row_rand = mysqli_fetch_array($mysqli_result1);

	if ($row_rand[b5] == $matrix[0][3])
	{
		$failed = 1;
	} elseif ($row_rand[b4] == $matrix[0][2]) {
		$failed = 1;
	} elseif ($row_rand[b3] == $matrix[0][1]) {
		$failed = 1;
	} elseif ($row_rand[b2] == $matrix[0][0]) { # ???
		$failed = 1;
	}

	$matrix[4][3] = $row_rand[b3]; 
	$matrix[4][2] = $row_rand[b4]; 
	$matrix[4][1] = $row_rand[b5]; 
	$matrix[4][0] = $row_rand[b6];  # sigma???

	print "<h3>Draw 2 - $row_rand[b1],$row_rand[b2],$row_rand[b3],$row_rand[b4],$row_rand[b5],$row_rand[b6]</h3>";

	#--------------------------------------------------------------------------------
	#--- end - Draw 6 - 4,0-4,4
	#--------------------------------------------------------------------------------

	#--------------------------------------------------------------------------------
	#--- Draw 7 - 4,0-4,4 - and fail!!!!!!!!!!!!!
	#--------------------------------------------------------------------------------

	$filter_table = "filter_b_6_53";
	if ($draw_num[1] < 10)
	{
		$filter_table .= "_0$draw_num[1]";
	} else {
		$filter_table .= "_$draw_num[1]";
	}
	$filter_table .= "_";
	$filter_table .= "$col1";

	$query = "SELECT * FROM $filter_table  ";
	$query .= " WHERE (b1 = $matrix[4][4] 
					OR b2 = $matrix[4][4] 
					OR b3 = $matrix[4][4] 
					OR b4 = $matrix[4][4] 
					OR b5 = $matrix[4][4] 
					OR b6 = $matrix[4][4])
					AND (b1 = $matrix[0][4] 
					  OR b2 = $matrix[0][4] 
					  OR b3 = $matrix[0][4] 
					  OR b4 = $matrix[0][4] 
					  OR b5 = $matrix[0][4] 
					  OR b6 = $matrix[0][4]) ";
	$query .= "ORDER BY id DESC "; # ??? - sigma???
	$query .= "ORDER BY RAND "; # ??? - sigma???

	print "$query<p>";

	$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

	$num_rows = mysqli_num_rows($mysqli_result);

	mt_srand((float) microtime() * 10000000);

	$r = mt_rand(0,$num_rows);

	$query1 = "SELECT * FROM $filter_table  ";
	$query1 .= "WHERE id = '$r' ";	#---------------- fix

	print "$query1<p>";

	$mysqli_result1 = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

	$row_rand = mysqli_fetch_array($mysqli_result1);

	if ($row_rand[b5] == $matrix[0][3])
	{
		$failed = 1;
	} elseif ($row_rand[b4] == $matrix[0][2]) {
		$failed = 1;
	} elseif ($row_rand[b3] == $matrix[0][1]) {
		$failed = 1;
	} elseif ($row_rand[b2] == $matrix[0][0]) { # ???
		$failed = 1;
	}

	$matrix[4][3] = $row_rand[b3]; 
	$matrix[4][2] = $row_rand[b4]; 
	$matrix[4][1] = $row_rand[b5]; 
	$matrix[4][0] = $row_rand[b6];  # sigma???

	print "<h3>Draw 2 - $row_rand[b1],$row_rand[b2],$row_rand[b3],$row_rand[b4],$row_rand[b5],$row_rand[b6]</h3>";

	#--------------------------------------------------------------------------------
	#--- end - Draw 7 - 4,0-4,4
	#--------------------------------------------------------------------------------

	#--------------------------------------------------------------------------------
	#--- Draw 8 - 4,0-4,4 - and fail!!!!!!!!!!!!!
	#--------------------------------------------------------------------------------

	$filter_table = "filter_b_6_53";
	if ($draw_num[1] < 10)
	{
		$filter_table .= "_0$draw_num[1]";
	} else {
		$filter_table .= "_$draw_num[1]";
	}
	$filter_table .= "_";
	$filter_table .= "$col1";

	$query = "SELECT * FROM $filter_table  ";
	$query .= " WHERE (b1 = $matrix[4][4] 
					OR b2 = $matrix[4][4] 
					OR b3 = $matrix[4][4] 
					OR b4 = $matrix[4][4] 
					OR b5 = $matrix[4][4] 
					OR b6 = $matrix[4][4])
					AND (b1 = $matrix[0][4] 
					  OR b2 = $matrix[0][4] 
					  OR b3 = $matrix[0][4] 
					  OR b4 = $matrix[0][4] 
					  OR b5 = $matrix[0][4] 
					  OR b6 = $matrix[0][4]) ";
	$query .= "ORDER BY id DESC "; # ??? - sigma???
	$query .= "ORDER BY RAND "; # ??? - sigma???

	print "$query<p>";

	$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

	$num_rows = mysqli_num_rows($mysqli_result);

	mt_srand((float) microtime() * 10000000);

	$r = mt_rand(0,$num_rows);

	$query1 = "SELECT * FROM $filter_table  ";
	$query1 .= "WHERE id = '$r' ";	#---------------- fix

	print "$query1<p>";

	$mysqli_result1 = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

	$row_rand = mysqli_fetch_array($mysqli_result1);

	if ($row_rand[b5] == $matrix[0][3])
	{
		$failed = 1;
	} elseif ($row_rand[b4] == $matrix[0][2]) {
		$failed = 1;
	} elseif ($row_rand[b3] == $matrix[0][1]) {
		$failed = 1;
	} elseif ($row_rand[b2] == $matrix[0][0]) { # ???
		$failed = 1;
	}

	$matrix[4][3] = $row_rand[b3]; 
	$matrix[4][2] = $row_rand[b4]; 
	$matrix[4][1] = $row_rand[b5]; 
	$matrix[4][0] = $row_rand[b6];  # sigma???

	print "<h3>Draw 2 - $row_rand[b1],$row_rand[b2],$row_rand[b3],$row_rand[b4],$row_rand[b5],$row_rand[b6]</h3>";

	#--------------------------------------------------------------------------------
	#--- end - Draw 8 - 4,0-4,4
	#--------------------------------------------------------------------------------






	$query = "SELECT * FROM $filter_table  ";
	$query .= "WHERE id = '$r' ";
	#$query .= "AND (b3 = 45 OR b4 = 45 OR b5 = 45) ";
	#$query .= "AND (b4 = 53 OR b5 = 53 or b6 = 53) ";
	#$query .= "AND (b4 = 44 OR b5 = 44 or b6 = 44) ";
	#$query .= "AND (b4 = 36 OR b5 = 36 or b6 = 36) ";
	#$query .= "AND (b4 = 52 OR b5 = 52 or b6 = 52) ";
	#$query .= "OR (b4 =  OR b4 = ) ";
	#$query .= "OR (b5 =  OR b4 = ) ";
	#$query .= "OR (b6 =  OR b4 = ) ";

	print "$query<p>";

	$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

	$num_rows = mysqli_num_rows($mysqli_result);

	mt_srand((float) microtime() * 10000000);

	$y = mt_rand(0,$num_rows);

	print "rand = $y<p>";

	# look up rand function for mysql
	$query1 = "SELECT  b1,b2,b3,b4,b5 FROM $filter_table ";
	$query1 .= "LIMIT $y, 1 ";

	print "$query1<p>";

	$mysqli_result1 = mysqli_query($mysqli_link, $query1) or die (mysqli_error($mysqli_link));

	$row1 = mysqli_fetch_array($mysqli_result1);

	for ($x = 1; $x <= 5; $x++)
	{
		$square_array[0][$x] = $row[$x];
	}

	$row4_not_found = 0;

	while ($row4_not_found)
	{
		mt_srand((float) microtime() * 10000000);

		$y = mt_rand(0,$num_rows);

		print "rand = $y<p>";

		# look up rand function for mysql
		$query5 = "SELECT  b1,b2,b3,b4,b5 FROM $filter_table ";
		$query5 = "WHERE (b2 != $row[1] AND b3 != $row[1] AND b4 != $row[1] AND b5 != $row[1] AND b6 != $row[1]) "; 
		$query5 = "AND (b2 != $row[2] AND b3 != $row[2] AND b4 != $row[2] AND b5 != $row[2] AND b6 != $row[2]) "; 
		$query5 = "AND (b2 != $row[3] AND b3 != $row[3] AND b4 != $row[3] AND b5 != $row[3] AND b6 != $row[3]) "; 
		$query5 = "AND (b2 != $row[4] AND b3 != $row[4] AND b4 != $row[4] AND b5 != $row[4] AND b6 != $row[4]) "; 
		$query5 = "AND (b2 != $row[5] AND b3 != $row[5] AND b4 != $row[5] AND b5 != $row[5] AND b6 != $row[5]) "; 
		$query5 = "AND (b2 != $row[6] AND b3 != $row[6] AND b4 != $row[6] AND b5 != $row[6] AND b6 != $row[6]) "; 
		$query5 .= "LIMIT rand(), 1 ";

		print "$query5<p>";

		$mysqli_result5 = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));

		$row4_not_found = mysqli_num_rows($mysqli_result);
	}
	
	$row5 = mysqli_fetch_array($mysqli_result5);

	for ($x = 4; $x <= 1; $x--)
	{
		$square_array[4][$x] = $row[$x];
	}

	# find combined sigma for 0,2/4,3 for star

	
	
	
	
	
	
	
	
	
	
	
	{
		print "$x - $row[b1],$row[b2],$row[b3],$row[b4],$row[b5],$row[b6]<br>";
		$x++;
	}

?>