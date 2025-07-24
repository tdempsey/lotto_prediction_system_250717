<?php
 
function return_row($cell){
	return floor($cell/6);
}
 
function return_col($cell){
	return $cell % 6;
}
 
function is_possible_row($number,$row,$sudoku){
	$possible = true;
	for($x=0;$x<=5;$x++){		
		if($sudoku[$row*6+$x] == $number)
		{
			$possible = false;
		}		
	}
	return $possible;
}
 
function is_possible_col($number,$col,$sudoku){
	$possible = true;
	for($x=0;$x<=5;$x++){
		if($sudoku[$col+6*$x] == $number){
			$possible = false;
		}
	}
	return $possible;
}
 
function is_possible_number($cell,$number,$sudoku){
	$row = return_row($cell);
	$col = return_col($cell);
	return is_possible_row($number,$row,$sudoku) and is_possible_col($number,$col,$sudoku); 
}	
 
function print_sudoku($sudoku){
	$html = "<table bgcolor = \"#000000\" cellspacing = \"1\" cellpadding = \"2\">";
	for($x=0;$x<=5;$x++){
		$html .= "<tr bgcolor = \"white\" align = \"center\">";
		for($y=0;$y<=5;$y++){
			$html.= "<td width = \"20\" height = \"20\">".$sudoku[$x*6+$y]."</td>";
		}
		$html .= "</tr>";
	}
	$html .= "</table>";
	return $html;
}
 
function is_correct_row($row,$sudoku){
	for($x=0;$x<=5;$x++){
		$row_temp[$x] = $sudoku[$row*6+$x];
	}
	return count(array_diff(array(1,2,3,4,5,6),$row_temp)) == 0;
}
 
function is_correct_col($col,$sudoku){
	for($x=0;$x<=5;$x++){
		$col_temp[$x] = $sudoku[$col+$x*6];
	}
	return count(array_diff(array(1,2,3,4,5,6),$col_temp)) == 0;
}

function is_strict_diag_1eft($sudoku){
	$diag_temp = array ($sudoku[35],$sudoku[28],$sudoku[21],$sudoku[14],$sudoku[7],$sudoku[0]);

	echo "diag_left $sudoku[35],$sudoku[28],$sudoku[21],$sudoku[14],$sudoku[7],$sudoku[0]<br>";

	return count(array_diff(array(1,2,3,4,5,6),$diag_temp)) == 0;
}

function is_strict_diag_right($sudoku){
	$diag_temp = array ($sudoku[5],$sudoku[10],$sudoku[15],$sudoku[20],$sudoku[25],$sudoku[30]);

	echo "diag_right $sudoku[5],$sudoku[10],$sudoku[15],$sudoku[20],$sudoku[25],$sudoku[30]<br>";

	return count(array_diff(array(1,2,3,4,5,6),$diag_temp)) == 0;
}
 
function is_solved_sudoku($sudoku){
	for($x=0;$x<=5;$x++){
		if(!is_correct_row($x,$sudoku) or !is_correct_col($x,$sudoku)){
			return false;
			break;
		}
	}
	return true;
}
 
function determine_possible_values($cell,$sudoku){
	$possible = array();
	for($x=1;$x<=6;$x++){
		if(is_possible_number($cell,$x,$sudoku)){
			array_unshift($possible,$x);
		} 
	}
	return $possible;
}
 
function determine_random_possible_value($possible,$cell){
	return $possible[$cell][rand(0,count($possible[$cell])-1)];
}
 
function scan_sudoku_for_unique($sudoku){
	for($x=0;$x<=35;$x++){
		if($sudoku[$x] == 0){
			$possible[$x] = determine_possible_values($x,$sudoku);
			if(count($possible[$x])==0){
				return(false);
				break;
			}
		}
	}
	return($possible);
}
 
function remove_attempt($attempt_array,$number){
	$new_array = array();
	for($x=0;$x<count($attempt_array);$x++){
		if($attempt_array[$x] != $number){
			array_unshift($new_array,$attempt_array[$x]);
		}
	}
	return $new_array;
}
 
 
function print_possible($possible){
	$html = "<table bgcolor = \"#ff0000\" cellspacing = \"1\" cellpadding = \"2\">";
	for($x=0;$x<=5;$x++){
		$html .= "<tr bgcolor = \"yellow\" align = \"center\">";
		for($y=0;$y<=5;$y++){
			$values = "";
			for($z=0;$z<=count($possible[$x*6+$y]);$z++){
				$values .= $possible[$x*6+$y][$z];
			}
			$html.= "<td width = \"20\" height = \"20\">$values</td>";
		}
		$html .= "</tr>";
	}
	$html .= "</table>";
	return $html;
}	
 
function next_random($possible){
	$max = 6;
	for($x=0;$x<=35;$x++){
		if ((count($possible[$x])<=$max) and (count($possible[$x])>0)){
			$max = count($possible[$x]);
			$min_choices = $x;
		}
	}
	return $min_choices;
}

function insert_array($sudoku){

	global $debug, $game, $game_name, $balls, $balls_drawn, $mega_balls, $draw_table_name, $draw_prefix;

	$game = 6; // Florida Lotto
	
	require ("includes/games_switch.incl");
	require ("includes/mysqli.php");

	$query5 = "SELECT * FROM combo_6_grids ";
	$query5 .= "WHERE cg_00 = '$sudoku[30]' AND ";
	$query5 .= "cg_01 = '$sudoku[31]' AND ";
	$query5 .= "cg_02 = '$sudoku[32]' AND ";
	$query5 .= "cg_03 = '$sudoku[33]' AND ";
	$query5 .= "cg_04 = '$sudoku[34]' AND ";
	$query5 .= "cg_05 = '$sudoku[35]' AND ";
	$query5 .= "cg_10 = '$sudoku[24]' AND ";
	$query5 .= "cg_11 = '$sudoku[25]' AND ";
	$query5 .= "cg_12 = '$sudoku[26]' AND ";
	$query5 .= "cg_13 = '$sudoku[27]' AND ";
	$query5 .= "cg_14 = '$sudoku[28]' AND ";
	$query5 .= "cg_15 = '$sudoku[29]' AND ";
	$query5 .= "cg_20 = '$sudoku[18]' AND ";
	$query5 .= "cg_21 = '$sudoku[19]' AND ";
	$query5 .= "cg_22 = '$sudoku[20]' AND ";
	$query5 .= "cg_23 = '$sudoku[21]' AND ";
	$query5 .= "cg_24 = '$sudoku[22]' AND ";
	$query5 .= "cg_25 = '$sudoku[23]' AND ";
	$query5 .= "cg_30 = '$sudoku[12]' AND ";
	$query5 .= "cg_31 = '$sudoku[13]' AND ";
	$query5 .= "cg_32 = '$sudoku[14]' AND ";
	$query5 .= "cg_33 = '$sudoku[15]' AND ";
	$query5 .= "cg_34 = '$sudoku[16]' AND ";
	$query5 .= "cg_35 = '$sudoku[17]' AND ";
	$query5 .= "cg_40 = '$sudoku[6]' AND ";
	$query5 .= "cg_41 = '$sudoku[7]' AND ";
	$query5 .= "cg_42 = '$sudoku[8]' AND ";
	$query5 .= "cg_43 = '$sudoku[9]' AND ";
	$query5 .= "cg_44 = '$sudoku[10]' AND ";
	$query5 .= "cg_45 = '$sudoku[11]' AND ";
	$query5 .= "cg_50 = '$sudoku[0]' AND ";
	$query5 .= "cg_51 = '$sudoku[1]' AND ";
	$query5 .= "cg_52 = '$sudoku[2]' AND ";
	$query5 .= "cg_53 = '$sudoku[3]' AND ";
	$query5 .= "cg_54 = '$sudoku[4]' AND ";
	$query5 .= "cg_55 = '$sudoku[5]' ";

	#print "<p>$query5<p>";
	
	$mysqli_result5 = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));

	if ($num_rows = mysqli_num_rows($mysqli_result5))
	{
		print "<p>$query5<p>";
		return 1;
	} 

	$query3 = "Insert INTO combo_6_grids ";
			$query3 .= "VALUES ( '0', ";

			for($z = 30; $z <= 35; $z++)
			{
				$query3 .= "$sudoku[$z], ";
			}

			for($z = 24; $z <= 29; $z++)
			{
				$query3 .= "$sudoku[$z], ";
			}

			for($z = 18; $z <= 23; $z++)
			{
				$query3 .= "$sudoku[$z], ";
			}

			for($z = 12; $z <= 17; $z++)
			{
				$query3 .= "$sudoku[$z], ";
			}

			for($z = 6; $z <= 11; $z++)
			{
				$query3 .= "$sudoku[$z], ";
			}

			for($z = 0; $z <= 5; $z++)
			{
				$query3 .= "$sudoku[$z], ";
			}

			if(!is_strict_diag_1eft($sudoku) or !is_strict_diag_right($sudoku)){
				$query3 .= "FALSE ";
			} else {
				$query3 .= "TRUE ";
			}

			$query3 .= "	     ) ";

	print "$query3<p>";
	
	$mysqli_result3 = mysqli_query($mysqli_link, $query3) or die (mysqli_error($mysqli_link));

	return 1;
}
 
function solve($sudoku){
	$start = microtime();
	$saved = array();	
	$saved_sud = array();
		
	while(!is_solved_sudoku($sudoku)){
		$x+=1;
		$next_move = scan_sudoku_for_unique($sudoku);
		if($next_move == false){
			$next_move = array_pop($saved);
			$sudoku = array_pop($saved_sud);
		}

		$what_to_try = next_random($next_move);	
		$attempt = determine_random_possible_value($next_move,$what_to_try);
		if(count($next_move[$what_to_try])>1){								
			$next_move[$what_to_try] = remove_attempt($next_move[$what_to_try],$attempt);
			array_push($saved,$next_move);
			array_push($saved_sud,$sudoku);
		}
		$sudoku[$what_to_try] = $attempt;	
		#die ('solve');
	}
	$end = microtime();
	$ms_start = explode(" ",$start);
	$ms_end = explode(" ",$end);
	$total_time = round(($ms_end[1] - $ms_start[1] + $ms_end[0] - $ms_start[0]),2);
	echo "completed in $x steps in $total_time seconds";
	echo print_sudoku($sudoku);
	print_r ($sudoku);
	insert_array($sudoku);
	echo "<p>";
}
 
for($z = 1; $z <= 1000; $z++)
{
	$sudoku = array_fill (0,36,0);
	 
	solve($sudoku);
}

?>