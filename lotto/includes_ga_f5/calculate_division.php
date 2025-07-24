<?php
function calculate_div_2($draw,&$d2_array,$max_draw) 
{  
	$d2_array = array_fill (0,2,0);
	
	$half = intval ($max_draw/2);

	#echo "half = $half<br>";

	// error checking ----------------------------------------------------------------------------------------------
	if (count($draw) == 0)
	{
		exit("<h2>Error - function calculate_d2.php - <font color=\"#FF0000\">draw undefined</font></h2>");
	}

	if (array_sum($draw) == 0)
	{
		exit("<h2>Error - function calculate_d2.php - <font color=\"#FF0000\">draw array_sum = 0</font></h2>");
	}

	if (is_null($max_draw) || $max_draw == 0)
	{
		exit("<h2>Error - function calculate_d2.php - <font color=\"#FF0000\">max_draw undefined - max_draw = $max_draw</font></h2>");
	}

	// error checking ----------------------------------------------------------------------------------------------
	
	reset ($draw); 
	
	foreach ($draw as $val) 
	{ 
		if ($val > $half) {
			$d2_array[1]++;
		} else {
			$d2_array[0]++;
		}
	} 

	if (array_sum ($d2_array) == 0)
	{
		exit("<font color=\"#FF0000\"><h2>Error - function calculate_div_2.php - array d2 = 0</h2></font>");
	}
	
	return true;
}

function calculate_div_3($draw,&$d3_array,$max_draw) 
{  
	$d3_array = array_fill (0,3,0);

	$div1 = intval ($max_draw/3);
	$div2 = intval ($div1 * 2);

	// error checking ----------------------------------------------------------------------------------------------
	if (count($draw) == 0)
	{
		exit("<h2>Error - function calculate_d3.php - <font color=\"#FF0000\">draw undefined</font></h2>");
	}

	if (array_sum($draw) == 0)
	{
		exit("<h2>Error - function calculate_d3.php - <font color=\"#FF0000\">draw array_sum = 0</font></h2>");
	}

	if (is_null($max_draw) || $max_draw == 0)
	{
		exit("<h2>Error - function calculate_d3.php - <font color=\"#FF0000\">max_draw undefined - max_draw = $max_draw</font></h2>");
	}

	// error checking ----------------------------------------------------------------------------------------------
	
	reset ($draw); 
	
	foreach ($draw as $val) 
	{ 
		if ($val > $div2) {
			$d3_array[2]++;
		} elseif ($val > $div1) {
			$d3_array[1]++;
		} else {
			$d3_array[0]++;
		}
	} 

	if (array_sum ($d3_array) == 0)
	{
		exit("<font color=\"#FF0000\"><h2>Error - function calculate_div_3.php - array d3 = 0</h2></font>");
	}
	
	return true;
}

function calculate_div_4($draw,&$d4_array,$max_draw) 
{  
	$d4_array = array_fill (0,4,0);

	$div1 = intval ($max_draw/4);
	$div2 = intval ($div1 * 2);
	$div3 = intval ($div1 * 3);

	// error checking ----------------------------------------------------------------------------------------------
	if (count($draw) == 0)
	{
		exit("<h2>Error - function calculate_d4.php - <font color=\"#FF0000\">draw undefined</font></h2>");
	}

	if (array_sum($draw) == 0)
	{
		exit("<h2>Error - function calculate_d4.php - <font color=\"#FF0000\">draw array_sum = 0</font></h2>");
	}

	if (is_null($max_draw) || $max_draw == 0)
	{
		exit("<h2>Error - function calculate_d4.php - <font color=\"#FF0000\">max_draw undefined - max_draw = $max_draw</font></h2>");
	}

	// error checking ----------------------------------------------------------------------------------------------
	
	reset ($draw); 
	
	foreach ($draw as $val) 
	{ 
		if ($val > $div3) {
			$d4_array[3]++;
		} elseif ($val > $div2) {
			$d4_array[2]++;
		} elseif ($val > $div1) {
			$d4_array[1]++;
		} else {
			$d4_array[0]++;
		}
	} 

	if (array_sum ($d4_array) == 0)
	{
		exit("<font color=\"#FF0000\"><h2>Error - function calculate_div_4.php - array d4 = 0</h2></font>");
	}
	
	return true;
}

function calculate_div_6($draw,&$d6_array,$max_draw) 
{  
	$d6_array = array_fill (0,6,0);

	$div1 = intval ($max_draw/6);
	$div2 = intval ($div1 * 2);
	$div3 = intval ($div1 * 3);
	$div4 = intval ($div1 * 4);
	$div5 = intval ($div1 * 5);

	// error checking ----------------------------------------------------------------------------------------------
	if (count($draw) == 0)
	{
		exit("<h2>Error - function calculate_d6.php - <font color=\"#FF0000\">draw undefined</font></h2>");
	}

	if (array_sum($draw) == 0)
	{
		exit("<h2>Error - function calculate_d6.php - <font color=\"#FF0000\">draw array_sum = 0</font></h2>");
	}

	if (is_null($max_draw) || $max_draw == 0)
	{
		exit("<h2>Error - function calculate_d6.php - <font color=\"#FF0000\">max_draw undefined - max_draw = $max_draw</font></h2>");
	}

	// error checking ----------------------------------------------------------------------------------------------
	
	reset ($draw); 
	
	foreach ($draw as $val) 
	{ 
		if ($val > $div5) {
			$d6_array[5]++;
		} elseif ($val > $div4) {
			$d6_array[4]++;
		} elseif ($val > $div3) {
			$d6_array[3]++;
		} elseif ($val > $div2) {
			$d6_array[2]++;
		} elseif ($val > $div1) {
			$d6_array[1]++;
		} else {
			$d6_array[0]++;
		}
	} 

	if (array_sum ($d6_array) == 0)
	{
		exit("<font color=\"#FF0000\"><h2>Error - function calculate_div_6.php - array d6 = 0</h2></font>");
	}
	
	return true;
}

function calculate_div_12($draw,&$d12_array,$max_draw) 
{  
	$d12_array = array_fill (0,12,0);

	$div1 = intval ($max_draw/12);
	$div2 = intval ($div1 * 2);
	$div3 = intval ($div1 * 3);
	$div4 = intval ($div1 * 4);
	$div5 = intval ($div1 * 5);
	$div6 = intval ($div1 * 6);
	$div7 = intval ($div1 * 7);
	$div8 = intval ($div1 * 8);
	$div9 = intval ($div1 * 9);
	$div10 = intval ($div1 * 10);
	$div11 = intval ($div1 * 11);

	// error checking ----------------------------------------------------------------------------------------------
	if (count($draw) == 0)
	{
		exit("<h2>Error - function calculate_d12.php - <font color=\"#FF0000\">draw undefined</font></h2>");
	}

	if (array_sum($draw) == 0)
	{
		exit("<h2>Error - function calculate_d12.php - <font color=\"#FF0000\">draw array_sum = 0</font></h2>");
	}

	if (is_null($max_draw) || $max_draw == 0)
	{
		exit("<h2>Error - function calculate_d12.php - <font color=\"#FF0000\">max_draw undefined - max_draw = $max_draw</font></h2>");
	}

	// error checking ----------------------------------------------------------------------------------------------
	
	reset ($draw); 
	
	foreach ($draw as $val) 
	{ 
		if ($val > $div11) {
			$d12_array[11]++;
		} elseif ($val > $div10) {
			$d12_array[10]++;
		} elseif ($val > $div9) {
			$d12_array[9]++;
		} elseif ($val > $div8) {
			$d12_array[8]++;
		} elseif ($val > $div7) {
			$d12_array[7]++;
		} elseif ($val > $div6) {
			$d12_array[6]++;
		} elseif ($val > $div5) {
			$d12_array[5]++;
		} elseif ($val > $div4) {
			$d12_array[4]++;
		} elseif ($val > $div3) {
			$d12_array[3]++;
		} elseif ($val > $div2) {
			$d12_array[2]++;
		} elseif ($val > $div3) {
			$d12_array[1]++;
		} else {
			$d12_array[0]++;
		}
	} 

	if (array_sum ($d12_array) == 0)
	{
		exit("<font color=\"#FF0000\"><h2>Error - function calculate_div_12.php - array d12 = 0</h2></font>");
	}
	
	return true;
}

function calculate_div_18($draw,&$d18_array,$max_draw) 
{  
	$d18_array = array_fill (0,18,0);

	$div1 = intval ($max_draw/18);
	$div2 = intval ($div1 * 2);
	$div3 = intval ($div1 * 3);
	$div4 = intval ($div1 * 4);
	$div5 = intval ($div1 * 5);
	$div6 = intval ($div1 * 6);
	$div7 = intval ($div1 * 7);
	$div8 = intval ($div1 * 8);
	$div9 = intval ($div1 * 9);
	$div10 = intval ($div1 * 10);
	$div11 = intval ($div1 * 11);
	$div12 = intval ($div1 * 12);
	$div13 = intval ($div1 * 13);
	$div14 = intval ($div1 * 14);
	$div15 = intval ($div1 * 15);
	$div16 = intval ($div1 * 16);
	$div17 = intval ($div1 * 17);

	// error checking ----------------------------------------------------------------------------------------------
	if (count($draw) == 0)
	{
		exit("<h2>Error - function calculate_d18.php - <font color=\"#FF0000\">draw undefined</font></h2>");
	}

	if (array_sum($draw) == 0)
	{
		exit("<h2>Error - function calculate_d18.php - <font color=\"#FF0000\">draw array_sum = 0</font></h2>");
	}

	if (is_null($max_draw) || $max_draw == 0)
	{
		exit("<h2>Error - function calculate_d18.php - <font color=\"#FF0000\">max_draw undefined - max_draw = $max_draw</font></h2>");
	}

	// error checking ----------------------------------------------------------------------------------------------
	
	reset ($draw); 
	
	foreach ($draw as $val) 
	{ 
		if ($val > $div17) {
			$d18_array[17]++;
		} elseif ($val > $div16) {
			$d18_array[16]++;
		} elseif ($val > $div15) {
			$d18_array[15]++;
		} elseif ($val > $div14) {
			$d18_array[14]++;
		} elseif ($val > $div13) {
			$d18_array[13]++;
		} elseif ($val > $div12) {
			$d18_array[12]++;
		} elseif ($val > $div11) {
			$d18_array[11]++;
		} elseif ($val > $div10) {
			$d18_array[10]++;
		} elseif ($val > $div9) {
			$d18_array[9]++;
		} elseif ($val > $div8) {
			$d18_array[8]++;
		} elseif ($val > $div7) {
			$d18_array[7]++;
		} elseif ($val > $div6) {
			$d18_array[6]++;
		} elseif ($val > $div5) {
			$d18_array[5]++;
		} elseif ($val > $div4) {
			$d18_array[4]++;
		} elseif ($val > $div3) {
			$d18_array[3]++;
		} elseif ($val > $div2) {
			$d18_array[2]++;
		} elseif ($val > $div3) {
			$d18_array[1]++;
		} else {
			$d18_array[0]++;
		}
	} 

	if (array_sum ($d18_array) == 0)
	{
		exit("<font color=\"#FF0000\"><h2>Error - function calculate_div_18.php - array d18 = 0</h2></font>");
	}
	
	return true;
}
?>