<?php

	$game = 1; // Georgia Fantasy 5

	require ($_SERVER['DOCUMENT_ROOT'].'/lotto/includes/games_switch.incl');

	require ($_SERVER['DOCUMENT_ROOT'].'/lotto/includes/mysqli.php');
	#require ("includes/db.class");
	#require ('includes/db.class.php');
	
	$handle = fopen('C:\Users\tomde\Documents\backups\MyActivity.html','r'); 
	$f = 1; //new file number
	while(!feof($handle))
	{
		$newfile = fopen('C:\Users\tomde\Documents\backups\MyActivity.'.$f.'.txt','w'); //create new file to write to with file number
		for($i = 1; $i <= 5000; $i++) //for 5000 lines
		{
			$import = fgets($handle);
			print_r($import);
			#fwrite($newfile,$import);
			if(feof($handle))
			{break;} //If file ends, break loop
		}
		fclose($newfile);

		$f++; //Increment newfile number
	}
	fclose($handle);

	die();

	for ($f = 1; $f <= 33; $f++)
	{
		$filename = 'C:\Users\tomde\Documents\backups\MyActivity.';
		$filename .= $f . ".txt";

		echo "$filename<p>";
		$fp = fopen("$filename", "r");

		$contents = fread($fp, filesize($filename));

		preg_match_all('/<div class="content-cell mdl-cell mdl-cell--6-col mdl-typography--body-1">[Watched](?:.*)<\/a><br>/', $contents, $matches_name);

		print_r ($matches_name);

		foreach ($matches_name as $val) {
			echo "matched: " . $val[0] . "\n";
			echo "part 1: " . $val[1] . "\n";
			echo "part 2: " . $val[2] . "\n";
			echo "part 3: " . $val[3] . "\n";
			echo "part 4: " . $val[4] . "\n\n";
		}

		#preg_match_all("()", $contents, $matches_url);

		#print_r ($matches_url);

		$size_date = count($matches_name, COUNT_RECURSIVE);

		echo "size_date = $size_date<p>";

		$start = "<h1 class=\"fn\">";
		$end = "</h1>";

		$title = GetBetween($contents,$start,$end);

		for ($x = 0; $x <= ($size_date-2); $x++)
		{
			$name = explode(" ", $matches_name[0][$x]);

			#print "$name[0]<br> ";

			$url = explode(" ", $matches_url[0][$x]);

			#print "$url[0]<br> ";
			
			$date = explode("/", $matches_date[0][$x]);

			#print "$date[2]/$date[0]/$date[1]&nbsp;&nbsp;&nbsp;";

			// get from draw table
			$query5 = "SELECT * FROM $draw_table_name ";
			$query5 .= "WHERE date = '$date[2]-$date[0]-$date[1]'  ";

			#echo "$query5<br>";
		
			$mysqli_result5 = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));

			$num_rows5 = mysqli_num_rows($mysqli_result5);

			#echo "num_rows5 = $num_rows5<br>";
					
			#if (!$num_rows5)
			if (1)
			{
				$query_insert = "INSERT INTO `videos` (`id`, `name`, `url`, `date_viewed`) VALUES (NULL, 'test name', 'http://test.com', '2019-11-01')"; 

				echo "$query_insert<p>";

				// Check for errors
				if(mysqli_connect_errno()){
					echo mysqli_connect_error();
				}

				#$mysqli_result_insert = mysqli_query($mysqli_link, $query_insert) or die (mysqli_error($mysqli_link));
			}
		}

		die();
	}
	
	function GetBetween($content,$start,$end)
	{
		$r = explode($start, $content);
		if (isset($r[1])){
			$r = explode($end, $r[1]);
			return $r[0];
		}
		
		return '';
	}
?>