	<?php
	
	/* connect to server */
	$mysqli_link_mm = mysqli_connect("localhost", "root", "") or die ('Not connected : ' . mysqli_error());

	/* select the database */
	mysqli_select_db("mega_millions", $mysqli_link_mm) or die ('Can\'t use mega_millions: ' . mysqli_error()); 

	?>