<?php

	// ----------------------------------------------------------------------------------
	function print_column_test_sumeo_temp($col, $sum, $even, $odd)
	{
		global $debug, $draw_table_name, $balls, $balls_drawn, $draw_prefix, $game, $hml, $range_low, $range_high,			$col1_select; 

		require ("includes/mysqli.php"); 
		require ("includes/unix.incl"); 

		$z = 0;

		echo "### print_column_sumeo_test_sumeo inside ###<br>";

		$query4 = "DROP TABLE IF EXISTS temp_";
		$query4 .= "column_sumeo_";
		$query4 .= "$sum";
		$query4 .= "_$even";
		$query4 .= "_$odd";
		$query4 .= "_$col ";
		if ($hml)
		{
			$query4 .= "_$hml";
		}

		#echo "$query4<p>";

		$mysqli_result4 = mysqli_query($mysqli_link, $query4) or die (mysqli_error($mysqli_link));

		$query4 = "CREATE TABLE temp_";
		$query4 .= "column_sumeo_";
		$query4 .= "$sum";
		$query4 .= "_$even";
		$query4 .= "_$odd";
		$query4 .= "_$col ";
		if ($hml)
		{
			$query4 .= "_$hml (";
		} else {
			$query4 .= " (";
		}
		$query4 .= "`num` tinyint(3) unsigned NOT NULL,";
		$query4 .= "day1 tinyint(3) unsigned NOT NULL default '0', ";
		$query4 .= "week1 tinyint(3) unsigned NOT NULL default '0', ";
		$query4 .= "week2 tinyint(3) unsigned NOT NULL default '0', ";
		$query4 .= "month1 tinyint(3) unsigned NOT NULL default '0', ";
		$query4 .= "month3 tinyint(3) unsigned NOT NULL default '0', ";
		$query4 .= "month6 tinyint(3) unsigned NOT NULL default '0', ";
		$query4 .= "year1 tinyint(3) unsigned NOT NULL default '0', ";
		$query4 .= "year2 tinyint(3) unsigned NOT NULL default '0', ";
		$query4 .= "year3 tinyint(3) unsigned NOT NULL default '0', ";
		$query4 .= "year4 tinyint(3) unsigned NOT NULL default '0', ";
		$query4 .= "year5 int(5) unsigned NOT NULL default '0', ";
		$query4 .= "year6 int(5) unsigned NOT NULL default '0', ";
		$query4 .= "year7 int(5) unsigned NOT NULL default '0', ";
		$query4 .= "year8 int(5) unsigned NOT NULL default '0', ";
		$query4 .= "year9 int(5) unsigned NOT NULL default '0', ";
		$query4 .= "year10 int(5) unsigned NOT NULL default '0', ";
		$query4 .= "count int(5) unsigned NOT NULL default '0', ";
		$query4 .= "percent_y1 float(4,1) unsigned NOT NULL default '0', ";
		$query4 .= "percent_y5 float(4,1) unsigned NOT NULL default '0', ";
		$query4 .= "percent_wa float(4,1) unsigned NOT NULL default '0', ";
		$query4 .= "previous_date date NOT NULL default '1962-08-17', ";
		$query4 .= "last_date date NOT NULL default '1962-08-17', ";
		$query4 .= "PRIMARY KEY  (`num`),";
		$query4 .= "UNIQUE KEY `num_2` (`num`),";
		$query4 .= "KEY `num` (`num`)";
		$query4 .= ") ENGINE=InnoDB DEFAULT CHARSET=latin1;";

		print "$query4<p>";
	
		$mysqli_result4 = mysqli_query($mysqli_link, $query4) or die (mysqli_error($mysqli_link));

		$query = "SELECT * FROM $draw_table_name ";
		$query .= "WHERE date >= '2015-10-01' ";
		$query .= "AND sum = $sum "; #210103
		$query .= "AND even >= $even ";
		$query .= "AND odd >= $odd ";
		if ($hml)
		{
			$query .= "AND sum >= $range_low  ";
			$query .= "AND sum <= $range_high  ";
			if ($col1_select)
			{
				$query .= "AND b1 = $col1_select ";
			}
		} elseif ($col1_select)
		{
			$query .= "WHERE b1 = $col1_select ";
		}
		$query .= "ORDER BY date DESC ";

		print "$query<p>";

		$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

		$num_rows_all = mysqli_num_rows($mysqli_result);

		//start table
		print("<h3>Column $col Test</h3>\n");
		print("<TABLE BORDER=\"1\">\n");

		//create header row
		print("<TR>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Num</TD>\n");
		if ($game == 10 or $game == 20)
		{
			print("<TD BGCOLOR=\"#CCCCCC\"><center>1</center></TD>\n");
			print("<TD BGCOLOR=\"#CCCCCC\"><center>2</center></TD>\n");
			print("<TD BGCOLOR=\"#CCCCCC\"><center>3</center></TD>\n");
			print("<TD BGCOLOR=\"#CCCCCC\"><center>4</center></TD>\n");
			print("<TD BGCOLOR=\"#CCCCCC\"><center>5</center></TD>\n");
			print("<TD BGCOLOR=\"#CCCCCC\"><center>6</center></TD>\n");
			print("<TD BGCOLOR=\"#CCCCCC\"><center>7</center></TD>\n");
			print("<TD BGCOLOR=\"#CCCCCC\"><center>8</center></TD>\n");
			print("<TD BGCOLOR=\"#CCCCCC\"><center>9</center></TD>\n");
			print("<TD BGCOLOR=\"#CCCCCC\"><center>10</center></TD>\n");
			print("<TD BGCOLOR=\"#CCCCCC\"><center>11</center></TD>\n");
			print("<TD BGCOLOR=\"#CCCCCC\"><center>12</center></TD>\n");
			print("<TD BGCOLOR=\"#CCCCCC\"><center>13</center></TD>\n");
			print("<TD BGCOLOR=\"#CCCCCC\"><center>14</center></TD>\n");
			print("<TD BGCOLOR=\"#CCCCCC\"><center>15</center></TD>\n");
			print("<TD BGCOLOR=\"#CCCCCC\"><center>16</center></TD>\n");
			print("<TD BGCOLOR=\"#CCCCCC\"><center>17</center></TD>\n");
			print("<TD BGCOLOR=\"#CCCCCC\"><center>18</center></TD>\n");
			print("<TD BGCOLOR=\"#CCCCCC\"><center>19</center></TD>\n");
			print("<TD BGCOLOR=\"#CCCCCC\"><center>20</center></TD>\n");
			print("<TD BGCOLOR=\"#CCCCCC\"><center>21</center></TD>\n");
			print("<TD BGCOLOR=\"#CCCCCC\"><center>22</center></TD>\n");
			print("<TD BGCOLOR=\"#CCCCCC\"><center>23</center></TD>\n");
			print("<TD BGCOLOR=\"#CCCCCC\"><center>24</center></TD>\n");
			print("<TD BGCOLOR=\"#CCCCCC\"><center>25</center></TD>\n");
			print("<TD BGCOLOR=\"#CCCCCC\"><center>26</center></TD>\n");
			print("<TD BGCOLOR=\"#CCCCCC\"><center>27</center></TD>\n");
			print("<TD BGCOLOR=\"#CCCCCC\"><center>28</center></TD>\n");
		} else {
			print("<TD BGCOLOR=\"#CCCCCC\"><center>Last</center></TD>\n");
			print("<TD BGCOLOR=\"#CCCCCC\"><center>Week1</center></TD>\n");
		}
		
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Week2</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Month1</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Month3</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Month6</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Year1</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Year2</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Year3</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Year4</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Year5</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Year6</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Year7</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Year8</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Year9</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Year10</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Total</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Prev</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Last</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Sigma</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>wa1</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>wa5</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>wa</center></TD>\n");
		print("</TR>\n");

		$draw = 1;

		$temp_array = array_fill (0,17,0);
		$temp_array_aon = array_fill (0,17,0);
		$column_sumeo_count_array = array_fill (0,$balls+1,$temp_array);
		$column_sumeo_count_array_aon = array_fill (0,$balls+1,$temp_array);
		$week_count_array = array_fill (0,28,$temp_array);

		while($row = mysqli_fetch_array($mysqli_result))
		{
			$temp_draw = array (0);

			if ($game == 10 OR $game == 20)
			{
				for ($v = 1; $v <= $balls_drawn; $v++)
				{
					$w = $v + 2;
					array_push($temp_draw, $row[$w]);
				}
			} else {
				for ($v = 1; $v <= $balls_drawn; $v++)
				{
					array_push($temp_draw, $row[$v]);
				}
			}

			sort ($temp_draw);

			$x = $temp_draw[$col];

			$draw_date_array = explode("-","$row[0]"); ### 210104
			$draw_date_unix = mktime (0,0,0,$draw_date_array[1],$draw_date_array[2],$draw_date_array[0]); ### 210104

			if ($game == 10 or $game == 20)
			{
				$query2 = "SELECT * FROM $draw_table_name ";
				if ($hml)
				{
					$query2 .= "WHERE sum >= $range_low  ";
					$query2 .= "AND   sum <= $range_high  ";
					if ($col1_select)
					{
						$query2 .= "AND b1 = $col1_select  ";
					}
				} elseif ($col1_select) {
					$query2 .= "WHERE b1 = $col1_select  ";
				}
				
				$query2 .= "ORDER BY date DESC ";
				$query2 .= "LIMIT 27 ";

				#print("$query2<p>");

				$mysqli_result2 = mysqli_query($mysqli_link, $query2) or die (mysqli_error($mysqli_link));

				while($row2 = mysqli_fetch_array($mysqli_result2))
				{
					$temp_draw_aon = array (0);

					for ($v = 1; $v <= $balls_drawn; $v++)
					{
						$w = $v + 2;
						array_push($temp_draw_aon, $row2[$w]);
					}

					sort ($temp_draw_aon);

					$y = $temp_draw_aon[$col];

					for ($d = $z; $d <= 27; $d++) {$column_sumeo_count_array_aon[$y][$d]++;}
					$z++;
				}
					
			} elseif ($draw_date_unix == $day1) { 
					#echo "row[date] = $row[0]<br>";
					#echo "draw_date_unix = $draw_date_unix<br>"; #170407
					#echo "day1 = $day1<p>";
					for ($d = 0; $d <= 15; $d++) {$column_sumeo_count_array[$x][$d]++;}
				#}
			} elseif ($draw_date_unix > $week1) {
			#if ($draw_date_unix > $week1) {
				#echo "row[date] = $row[0]<br>";
				#echo "draw_date_unix = $draw_date_unix<br>"; #170407
				#echo "week1 = $week1<br>";
				for ($d = 1; $d <= 15; $d++) {$column_sumeo_count_array[$x][$d]++;}
			} elseif ($draw_date_unix > $week2) {
				for ($d = 2; $d <= 15; $d++) {$column_sumeo_count_array[$x][$d]++;}
			} elseif ($draw_date_unix > $month1) {
				for ($d = 3; $d <= 15; $d++) {$column_sumeo_count_array[$x][$d]++;}
			} elseif ($draw_date_unix > $month3) {
				for ($d = 4; $d <= 15; $d++) {$column_sumeo_count_array[$x][$d]++;}
			} elseif ($draw_date_unix > $month6) {
				for ($d = 5; $d <= 15; $d++) {$column_sumeo_count_array[$x][$d]++;}
			} elseif ($draw_date_unix > $year1) {
				for ($d = 6; $d <= 15; $d++) {$column_sumeo_count_array[$x][$d]++;}
			} elseif ($draw_date_unix > $year2) {
				for ($d = 7; $d <= 15; $d++) {$column_sumeo_count_array[$x][$d]++;}
			} elseif ($draw_date_unix > $year3) {
				for ($d = 8; $d <= 15; $d++) {$column_sumeo_count_array[$x][$d]++;}
			} elseif ($draw_date_unix > $year4) {
				for ($d = 9; $d <= 15; $d++) {$column_sumeo_count_array[$x][$d]++;}
			} elseif ($draw_date_unix > $year5) {
				for ($d = 10; $d <= 15; $d++) {$column_sumeo_count_array[$x][$d]++;}
			} elseif ($draw_date_unix > $year6) {
				for ($d = 11; $d <= 15; $d++) {$column_sumeo_count_array[$x][$d]++;}
			} elseif ($draw_date_unix > $year7) {
				for ($d = 12; $d <= 15; $d++) {$column_sumeo_count_array[$x][$d]++;}
			} elseif ($draw_date_unix > $year8) {
				for ($d = 13; $d <= 15; $d++) {$column_sumeo_count_array[$x][$d]++;}
			} elseif ($draw_date_unix > $year9) {
				for ($d = 14; $d <= 15; $d++) {$column_sumeo_count_array[$x][$d]++;}
			} elseif ($draw_date_unix > $year10) {
				for ($d = 15; $d <= 15; $d++) {$column_sumeo_count_array[$x][$d]++;}
			}
	
			$column_sumeo_count_array[$x][16]++;

			#add 1 year to clear
			if ($first_draw_unix > $year7) {
				for ($d = 13; $d <= 15; $d++) {$column_sumeo_count_array[$x][$d]=0;}
			} elseif ($first_draw_unix > $year8) {
				for ($d = 14; $d <= 15; $d++) {$column_sumeo_count_array[$x][$d]=0;}
			} elseif ($first_draw_unix > $year9) {
				for ($d = 15; $d <= 15; $d++) {$column_sumeo_count_array[$x][$d]=0;}
			} elseif ($first_draw_unix > $year10) {
				for ($d = 16; $d <= 16; $d++) {$column_sumeo_count_array[$x][$d]=0;}
			}

			$draw++;
		}

		
		for ($x = 1 ; $x <= $balls; $x++)
		{	
			if ($x == intval($balls/2+1))
			{
				print("<TR>\n");
				print("<TD BGCOLOR=\"#CCCCCC\">Num</TD>\n");
				if ($game == 10 or $game == 20)
				{
					print("<TD BGCOLOR=\"#CCCCCC\"><center>1</center></TD>\n");
					print("<TD BGCOLOR=\"#CCCCCC\"><center>2</center></TD>\n");
					print("<TD BGCOLOR=\"#CCCCCC\"><center>3</center></TD>\n");
					print("<TD BGCOLOR=\"#CCCCCC\"><center>4</center></TD>\n");
					print("<TD BGCOLOR=\"#CCCCCC\"><center>5</center></TD>\n");
					print("<TD BGCOLOR=\"#CCCCCC\"><center>6</center></TD>\n");
					print("<TD BGCOLOR=\"#CCCCCC\"><center>7</center></TD>\n");
					print("<TD BGCOLOR=\"#CCCCCC\"><center>8</center></TD>\n");
					print("<TD BGCOLOR=\"#CCCCCC\"><center>9</center></TD>\n");
					print("<TD BGCOLOR=\"#CCCCCC\"><center>10</center></TD>\n");
					print("<TD BGCOLOR=\"#CCCCCC\"><center>11</center></TD>\n");
					print("<TD BGCOLOR=\"#CCCCCC\"><center>12</center></TD>\n");
					print("<TD BGCOLOR=\"#CCCCCC\"><center>13</center></TD>\n");
					print("<TD BGCOLOR=\"#CCCCCC\"><center>14</center></TD>\n");
					print("<TD BGCOLOR=\"#CCCCCC\"><center>15</center></TD>\n");
					print("<TD BGCOLOR=\"#CCCCCC\"><center>16</center></TD>\n");
					print("<TD BGCOLOR=\"#CCCCCC\"><center>17</center></TD>\n");
					print("<TD BGCOLOR=\"#CCCCCC\"><center>18</center></TD>\n");
					print("<TD BGCOLOR=\"#CCCCCC\"><center>19</center></TD>\n");
					print("<TD BGCOLOR=\"#CCCCCC\"><center>20</center></TD>\n");
					print("<TD BGCOLOR=\"#CCCCCC\"><center>21</center></TD>\n");
					print("<TD BGCOLOR=\"#CCCCCC\"><center>22</center></TD>\n");
					print("<TD BGCOLOR=\"#CCCCCC\"><center>23</center></TD>\n");
					print("<TD BGCOLOR=\"#CCCCCC\"><center>24</center></TD>\n");
					print("<TD BGCOLOR=\"#CCCCCC\"><center>25</center></TD>\n");
					print("<TD BGCOLOR=\"#CCCCCC\"><center>26</center></TD>\n");
					print("<TD BGCOLOR=\"#CCCCCC\"><center>27</center></TD>\n");
					print("<TD BGCOLOR=\"#CCCCCC\"><center>28</center></TD>\n");
				} else {
					print("<TD BGCOLOR=\"#CCCCCC\"><center>Last</center></TD>\n");
					print("<TD BGCOLOR=\"#CCCCCC\"><center>Week1</center></TD>\n");
				}

				print("<TD BGCOLOR=\"#CCCCCC\"><center>Week2</center></TD>\n");
				print("<TD BGCOLOR=\"#CCCCCC\"><center>Month1</center></TD>\n");
				print("<TD BGCOLOR=\"#CCCCCC\"><center>Month3</center></TD>\n");
				print("<TD BGCOLOR=\"#CCCCCC\"><center>Month6</center></TD>\n");
				print("<TD BGCOLOR=\"#CCCCCC\"><center>Year1</center></TD>\n");
				print("<TD BGCOLOR=\"#CCCCCC\"><center>Year2</center></TD>\n");
				print("<TD BGCOLOR=\"#CCCCCC\"><center>Year3</center></TD>\n");
				print("<TD BGCOLOR=\"#CCCCCC\"><center>Year4</center></TD>\n");
				print("<TD BGCOLOR=\"#CCCCCC\"><center>Year5</center></TD>\n");
				print("<TD BGCOLOR=\"#CCCCCC\"><center>Year6</center></TD>\n");
				print("<TD BGCOLOR=\"#CCCCCC\"><center>Year7</center></TD>\n");
				print("<TD BGCOLOR=\"#CCCCCC\"><center>Year8</center></TD>\n");
				print("<TD BGCOLOR=\"#CCCCCC\"><center>Year9</center></TD>\n");
				print("<TD BGCOLOR=\"#CCCCCC\"><center>Year10</center>	</TD>\n");
				print("<TD BGCOLOR=\"#CCCCCC\" nowrap><center>Total</center></TD>\n");
				print("<TD BGCOLOR=\"#CCCCCC\" nowrap><center>Prev</center></TD>\n");
				print("<TD BGCOLOR=\"#CCCCCC\" nowrap><center>Last</center></TD>\n");
				print("<TD BGCOLOR=\"#CCCCCC\" nowrap><center>Sigma</center></TD>\n");
				print("<TD BGCOLOR=\"#CCCCCC\" nowrap><center>wa1</center></TD>\n");
				print("<TD BGCOLOR=\"#CCCCCC\" nowrap><center>wa5</center></TD>\n");
				print("<TD BGCOLOR=\"#CCCCCC\" nowrap><center>wa</center></TD>\n");
				print("</TR>\n");
			}
			print("<TR>\n");
			print("<TD><CENTER><b>$x</b></CENTER></TD>\n");

			if ($game == 10 or $game == 20)
			{
				for ($d = 0; $d <= 26; $d++)
				{
					if ($column_sumeo_count_array_aon[$x][$d] > 30)
					{
						print("<TD bgcolor=\"#FF0033\" align=center>{$column_sumeo_count_array_aon[$x][$d]}</TD>\n");
					} elseif ($column_sumeo_count_array_aon[$x][$d] > 5) {
						print("<TD bgcolor=\"#CCFFFF\" align=center>{$column_sumeo_count_array_aon[$x][$d]}</TD>\n");
					} elseif ($column_sumeo_count_array_aon[$x][$d] > 1) {
						print("<TD bgcolor=\"#CCFF66\" align=center>{$column_sumeo_count_array_aon[$x][$d]}</TD>\n");
					} elseif ($column_sumeo_count_array_aon[$x][$d] == 1) {
						print("<TD bgcolor=\"#F1F1F1\" align=center>{$column_sumeo_count_array_aon[$x][$d]}</TD>\n");
					} else {
						print("<TD align=center>{$column_sumeo_count_array_aon[$x][$d]}</TD>\n");
					}
				} 
			}

			if ($game == 10 or $game == 20)
			{
				for ($d = 1; $d <= 15; $d++)
				{
					if ($column_sumeo_count_array[$x][$d] > 30)
					{
						print("<TD bgcolor=\"#FF0033\" align=center>{$column_sumeo_count_array[$x][$d]}</TD>\n");
					} elseif ($column_sumeo_count_array[$x][$d] > 5) {
						print("<TD bgcolor=\"#CCFFFF\" align=center>{$column_sumeo_count_array[$x][$d]}</TD>\n");
					} elseif ($column_sumeo_count_array[$x][$d] > 1) {
						print("<TD bgcolor=\"#CCFF66\" align=center>{$column_sumeo_count_array[$x][$d]}</TD>\n");
					} elseif ($column_sumeo_count_array[$x][$d] == 1) {
						print("<TD bgcolor=\"#F1F1F1\" align=center>{$column_sumeo_count_array[$x][$d]}</TD>\n");
					} else {
						print("<TD align=center>{$column_sumeo_count_array[$x][$d]}</TD>\n");
					}
				}
			} else {
				for ($d = 0; $d <= 15; $d++)
				{
					if ($column_sumeo_count_array[$x][$d] > 79)
					{
						print("<TD bgcolor=\"#FF0033\" align=center>{$column_sumeo_count_array[$x][$d]}</TD>\n");
					} elseif ($column_sumeo_count_array[$x][$d] > 15) {
						print("<TD bgcolor=\"#CCFFFF\" align=center>{$column_sumeo_count_array[$x][$d]}</TD>\n");
					} elseif ($column_sumeo_count_array[$x][$d] > 1) {
						print("<TD bgcolor=\"#CCFF66\" align=center>{$column_sumeo_count_array[$x][$d]}</TD>\n");
					} elseif ($column_sumeo_count_array[$x][$d] == 1) {
						print("<TD bgcolor=\"#F1F1F1\" align=center>{$column_sumeo_count_array[$x][$d]}</TD>\n");
					} else {
						print("<TD align=center>{$column_sumeo_count_array[$x][$d]}</TD>\n");
					}
				}
			}
			

			if ($column_sumeo_count_array[$x][16] > 79)
			{
				print("<TD bgcolor=\"#FF0000\" align=center><font color=\"#ffffff\"><b>{$column_sumeo_count_array[$x][16]}</b></font></TD>\n");
			} else {
				print("<TD align=center>{$column_sumeo_count_array[$x][16]}</TD>\n");
			}

			# prev/last
			$prev_date = '1962-08-17';
			$last_date = '1962-08-17';

			$row_date = array();

			$query_date = "SELECT * FROM $draw_table_name ";
			if ($game != 10 OR $game != 20)
			{
				$query_date .= "WHERE b$col = $x "; #aon 
				if ($hml)
				{
					$query_date .= "AND sum >= $range_low  ";
					$query_date .= "AND   sum <= $range_high  ";
				}
			} else {
			if ($hml)
				{
					$query_date .= "WHERE sum >= $range_low  ";
					$query_date .= "AND   sum <= $range_high  ";
				}
			}
			$query_date .= "ORDER BY date DESC ";

			#echo "$query_date<p>";

			$mysqli_result_date = mysqli_query($mysqli_link, $query_date) or die (mysqli_error($mysqli_link));

			if ($row_date = mysqli_fetch_array($mysqli_result_date))
			{
				$last_date = $row_date[date];
			}

			if ($row_date = mysqli_fetch_array($mysqli_result_date))
			{
				$prev_date = $row_date[date];
			}

			if ($prev_date == '1962-08-17')
			{
				print("<TD align=center>---</TD>\n");
			} elseif ((strtotime ("$prev_date") - $month6) < 0) {
				print("<TD nowrap><font color=\"#ff0000\">$prev_date</font></TD>\n");
			} else {
				print("<TD nowrap>$prev_date</TD>\n");
			}

			if ($last_date == '1962-08-17')
			{
				print("<TD align=center>---</TD>\n");
			} elseif ((strtotime ("$prev_date") - $month6) < 0) {
				print("<TD nowrap><font color=\"#ff0000\">$last_date</font></TD>\n");
			} else {
				print("<TD nowrap>$last_date</TD>\n");
			}

			# sigma
			$sigma_sum = 0;

			for ($d = 0; $d <= 15; $d++)
			{
				$sigma_sum += $column_sumeo_count_array[$x][$d];
			}

			print("<TD align=center>$sigma_sum</TD>\n");

			$sum_temp_y1 = number_format(($column_sumeo_count_array[$x][6]/365)*100,1);

			if ($sum_temp_y1 >= 1.0)
			{
				print("<TD align=center><font size=\"-1\"><b>$sum_temp_y1 %</b></font></TD>\n");
			} else {
				print("<TD align=center><font size=\"-1\">$sum_temp_y1 %</font></TD>\n");
			}

			$sum_temp_y5 = number_format(($column_sumeo_count_array[$x][10]/(365*5))*100,1);

			if ($sum_temp_y5 >= 1.0)
			{
				print("<TD align=center><font size=\"-1\"><b>$sum_temp_y5 %</b></font></TD>\n");
			} else {
				print("<TD align=center><font size=\"-1\">$sum_temp_y5 %</font></TD>\n");
			}

			$weighted_average = (
				($column_sumeo_count_array[$x][1]/7*100*0.05) + #week1
				($column_sumeo_count_array[$x][3]/30*100*0.05) + #month1
				($column_sumeo_count_array[$x][5]/(365/2)*100*0.20) + #month6
				($column_sumeo_count_array[$x][6]/365*100*0.30) + #year1
				($column_sumeo_count_array[$x][8]/(365*3)*100*0.20) + #year3
				($column_sumeo_count_array[$x][10]/(365*5)*100*0.20)); #year5

			$sum_temp = number_format($weighted_average,1);
			if ($sum_temp >= 5.0){
				print("<TD align=center><font size=\"-1\"><b>$sum_temp %</b></font></TD>\n");
			} else {
				print("<TD align=center><font size=\"-1\">$sum_temp %</font></TD>\n");
			}

			print("</TR>\n");

			#print("x = $x<br>");

			#print_r ("$column_sumeo_count_array");
			#die ("$column_sumeo_count_array");

			$query5 = "INSERT INTO temp_";
			$query5 .= "column_sumeo_";
			$query5 .= "$sum";
			$query5 .= "_$even";
			$query5 .= "_$odd";
			$query5 .= "_$col ";
			if ($hml)
			{
				$query5 .= "_$hml ";
			} else {
				$query5 .= " ";
			}
			$query5 .= "VALUES ('$x', ";
			for ($d = 0; $d <= 15; $d++) 
			{
				$query5 .= "'{$column_sumeo_count_array[$x][$d]}', ";
			}
			#$query5 .= "'$num_rows', ";
			$query5 .= "'{$column_sumeo_count_array[$x][16]}', ";
			$query5 .= "'$sum_temp_y1', ";
			$query5 .= "'$sum_temp_y5', ";
			$query5 .= "'$weighted_average', ";
			$query5 .= "'1962-08-17',"; 
			$query5 .= "'1962-08-17')";
			#$query5 .= "'$row_last[last_date]',"; 
			#$query5 .= "'$row_prev[last_date]')";

			#print "$query5<p>";
			#die ("column_sumeo_count_array");
		
			$mysqli_result = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));
		}

		print("<TR>\n");
		print("<TD BGCOLOR=\"#CCCCCC\">Num</TD>\n");
		if ($game == 10 or $game == 20)
		{
			print("<TD BGCOLOR=\"#CCCCCC\"><center>&nbsp;1</center></TD>\n");
			print("<TD BGCOLOR=\"#CCCCCC\"><center>&nbsp;2</center></TD>\n");
			print("<TD BGCOLOR=\"#CCCCCC\"><center>&nbsp;3</center></TD>\n");
			print("<TD BGCOLOR=\"#CCCCCC\"><center>&nbsp;4</center></TD>\n");
			print("<TD BGCOLOR=\"#CCCCCC\"><center>&nbsp;5</center></TD>\n");
			print("<TD BGCOLOR=\"#CCCCCC\"><center>&nbsp;6</center></TD>\n");
			print("<TD BGCOLOR=\"#CCCCCC\"><center>&nbsp;7</center></TD>\n");
			print("<TD BGCOLOR=\"#CCCCCC\"><center>&nbsp;8</center></TD>\n");
			print("<TD BGCOLOR=\"#CCCCCC\"><center>&nbsp;9</center></TD>\n");
			print("<TD BGCOLOR=\"#CCCCCC\"><center>10</center></TD>\n");
			print("<TD BGCOLOR=\"#CCCCCC\"><center>11</center></TD>\n");
			print("<TD BGCOLOR=\"#CCCCCC\"><center>12</center></TD>\n");
			print("<TD BGCOLOR=\"#CCCCCC\"><center>13</center></TD>\n");
			print("<TD BGCOLOR=\"#CCCCCC\"><center>14</center></TD>\n");
			print("<TD BGCOLOR=\"#CCCCCC\"><center>15</center></TD>\n");
			print("<TD BGCOLOR=\"#CCCCCC\"><center>16</center></TD>\n");
			print("<TD BGCOLOR=\"#CCCCCC\"><center>17</center></TD>\n");
			print("<TD BGCOLOR=\"#CCCCCC\"><center>18</center></TD>\n");
			print("<TD BGCOLOR=\"#CCCCCC\"><center>19</center></TD>\n");
			print("<TD BGCOLOR=\"#CCCCCC\"><center>20</center></TD>\n");
			print("<TD BGCOLOR=\"#CCCCCC\"><center>21</center></TD>\n");
			print("<TD BGCOLOR=\"#CCCCCC\"><center>22</center></TD>\n");
			print("<TD BGCOLOR=\"#CCCCCC\"><center>23</center></TD>\n");
			print("<TD BGCOLOR=\"#CCCCCC\"><center>24</center></TD>\n");
			print("<TD BGCOLOR=\"#CCCCCC\"><center>25</center></TD>\n");
			print("<TD BGCOLOR=\"#CCCCCC\"><center>26</center></TD>\n");
			print("<TD BGCOLOR=\"#CCCCCC\"><center>27</center></TD>\n");
			print("<TD BGCOLOR=\"#CCCCCC\"><center>28</center></TD>\n");
		} else {
			print("<TD BGCOLOR=\"#CCCCCC\"><center>Last</center></TD>\n");
			print("<TD BGCOLOR=\"#CCCCCC\"><center>Week1</center></TD>\n");
		}
		
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Week2</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Month1</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Month3</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Month6</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Year1</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Year2</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Year3</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Year4</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Year5</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Year6</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Year7</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Year8</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Year9</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Year10</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Total</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Prev</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Last</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>Sigma</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>wa1</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>wa5</center></TD>\n");
		print("<TD BGCOLOR=\"#CCCCCC\"><center>wa</center></TD>\n");
		print("</TR>\n");

		print("</TABLE>\n");

		print "<h3>Table <font color=\"#ff0000\">{temp_}column_sumeo_$col</font> Updated!</h3>";

		#die();
	}
?>