<?php
	$game = 1; // Georgia F5

	set_time_limit(0);

	require ("includes/mysqli.php");

	// Current draw 
	#$currentDraw = [9,21,22,30,36];
	$currentDraw = [13,19,26,27,32];

	#$query5 = "SELECT * FROM temp_cover_1k_240404 ORDER BY `id` ASC ";	###########################
	$query5 = "SELECT * FROM temp_cover_1k_candidates_240409 ORDER BY `id` ASC ";	###########################
	$query5 .= "LIMIT 1000";

	echo "<p>$query5</p>";

	$mysqli_result5 = mysqli_query($mysqli_link, $query5) or die (mysqli_error($mysqli_link));

	$count = 0;
	$index = 1;

	while($row5 = mysqli_fetch_array($mysqli_result5))
	{
		$draw = array($row5[1],$row5[2],$row5[3],$row5[4],$row5[5]);
		$matchingNumbers = countMatchingNumbers($draw, $currentDraw);
		
		if ($matchingNumbers >= 2) {
			echo "Draw #" . ($row5['id']) . " won with $matchingNumbers matching numbers: ";
			echo implode(", ", array_intersect($draw, $currentDraw)) . PHP_EOL;
			 echo "<br>";
		}
	}

	// Function to compare draws
	function countMatchingNumbers($draw, $currentDraw) {
		return count(array_intersect($draw, $currentDraw));
	}
?>
