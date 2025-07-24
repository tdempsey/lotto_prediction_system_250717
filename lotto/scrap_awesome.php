<?php
	#require('wp-blog-header.php');

	$category_list = array_fill(0,400,'');

	#$category_ids = get_all_category_ids();
	
	foreach($category_ids as $cat_id) {
		$cat_name = get_cat_name($cat_id);
		#$category_list_by_names = get_category_parents($cat_id, FALSE, ' &raquo; ');

		$flag = 1;

		if(stristr($category_list_by_names, "Source") !== FALSE)
		{
			$flag = 0;
		} elseif(stristr($category_list_by_names, "Restaurants") !== FALSE){
			$flag = 0;
		} elseif(stristr($category_list_by_names, "black") !== FALSE){
			$flag = 0;
		}

		if ($flag)
		{
			$category_list[$cat_id] = $cat_name;
		}
	}

	#print_r ($category_list);
	
	#die ();

	function GetBetween($content,$start,$end)
	{
		$r = explode($start, $content);
		if (isset($r[1])){
			$r = explode($end, $r[1]);
			return $r[0];
		}
		
		return '';
	}

	$mysqli_link = mysqli_connect("localhost", "root", "") or die ('Not connected : ' . mysqli_error());
			
	mysqli_select_db("awesomerecipes2", $mysqli_link) or die ('Can\'t use startgate: ' . mysqli_error()); 

	$query = "SELECT * FROM ar2_posts ";
	$query .= "WHERE ID <= 38783 ";
	$query .= "AND ID >= 15 ";
	$query .= "ORDER BY id ASC ";

	echo "$query<br>";

	$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

	while($row = mysqli_fetch_array($mysqli_result))
	{
		$row[post_content] = str_replace('----------------------', '<P>', $row[post_content]);
		$row[post_content] = str_replace('---------------------', '<P>', $row[post_content]);
		$row[post_content] = str_replace('--------------------', '<P>', $row[post_content]);
		$row[post_content] = str_replace('-------------', '<P>', $row[post_content]);
		$row[post_content] = str_replace('--', ' ', $row[post_content]);
		$row[post_content] = str_replace("'","\\'", $row[post_content]);

		#echo "$row[post_content]<p>";

		#die();

		$pieces = explode("<P>", $row[post_content]);

		#print_r ($pieces);
		#print "<br>";

		$pieces_count = count($pieces);

		echo "pieces_count = $pieces_count<br>";

		$last_ingredient = $pieces_count-1;

		echo "last_ingredient = $last_ingredient<br>";

		for ($q = $last_ingredient-1; $q > 0; $q--)
		{
			if (strlen($pieces[$q]) > 50)
			{
				$last_ingredient--;
				echo "last_ingredient = $last_ingredient<br>";
			} else {
				break;
			}
		}

		$rewrite_post = "<p><b>Ingredients:</b></p>";
		$rewrite_post .= "<ul>";

		for ($r = 1; $r < $last_ingredient; $r++)
		{
			if (strlen($pieces[$r]))
			{
				$item = $pieces[$r];

				if (ctype_upper(str_replace(' ', '', $item)))
				{
					$rewrite_post .= "<p>$pieces[$r]</p>";
				} else {
					$rewrite_post .= "<li>$pieces[$r]</li>";
				}
			}
		}
		
		$rewrite_post .= "</ul>";

		$rewrite_post .= "<p><b>Directions:</b></p>";

		for (;$r < $pieces_count; $r++)
		{
			$rewrite_post .= "$pieces[$r]";
		}
		#$rewrite_post .= "$pieces[$pieces_count]";

		#echo "$rewrite_post";

		$query9 = "UPDATE ar2_posts ";
		$query9 .= "SET post_content = '$rewrite_post' ";
		$query9 .= "WHERE ID = $row[ID] ";

		echo "$row[ID] updated<br>";
		
		$mysqli_result9 = mysqli_query($mysqli_link, $query9) or die (mysqli_error($mysqli_link));
	}

	die();

	# change C:\My Web Sites\Wlliams-Sonoma\www.williams-sonoma.com\recipe\test
	# to 'C:\My Web Sites\Wlliams-Sonoma\www.williams-sonoma.com\recipe' to 

	if ($handle = opendir('C:\My Web Sites\Wlliams-Sonoma\www.williams-sonoma.com\recipe')) 
	{
		#echo "Directory handle: $handle\n";
		#echo "Files:\n";

		echo("<html>\n");
		echo("<head>\n");
		echo("<title>Scrap - Williams-Sonoma</title>\n");
		echo("</head>\n");

		# randomize files here

		/* This is the correct way to loop over the directory. */
		while (false !== ($file = readdir($handle))) 
		{
			#echo "$file<br>";
			$filename = 'C:\My Web Sites\Wlliams-Sonoma\www.williams-sonoma.com\recipe';
			$filename .= '\\';
			$filename .= $file;
			#echo "$filename<br>";
			$fp = fopen("$filename", "r");

			#$file_size = filesize($filename);
			#echo "file_size = $file_size<br>";

			if (filesize($filename) > 10000)
			{
				if (!$fp) 
				{
					echo 'Could not open filename';
				}

				$contents = fread($fp, filesize($filename));
				
				print("<body>\n");

				$start = "<h1 class=\"fn\">";
				$end = "</h1>";

				$title = GetBetween($contents,$start,$end);

				echo "<h1>$title</h1>";

				$start = "<div id=\"hero-area\">";
				$end = "<div id=\"wine-pairing\"";

				$image = GetBetween($contents,$start,$end);

				$pattern = '..';
				$replacement = './wp-content/uploads';
				$image = str_replace($pattern,$replacement,$image);

				echo "<p><center>$image</center></p>";

				$start = "<div class=\"directions\">";
				$end = "</div>";

				$directions = GetBetween($contents,$start,$end);

				#$directions = strip_tags($directions, '<br>');

				$start = "<h2>Ingredients:</h2>";
				$end = "<h2>Directions:</h2>";

				$ingredients = GetBetween($contents,$start,$end);

				#$pattern = 'H3';
				#$replacement = 'p';
				#$ingredients = str_replace($pattern,$replacement,$ingredients);

				$ingredients = strip_tags($ingredients, '<ul><li>');

				echo "<b>Ingredients:</b><br>";

				echo "$ingredients";

				echo "<b>Directions:</b><br>";

				echo "<p>$directions</p>";

				$start = "Mirrored from ";
				$end = " by HTTrack Website";

				$url = GetBetween($contents,$start,$end);

				echo "<p>Source: <a href=\"http://";
				echo $url;
				echo "\">Williams-Sonoma</a>";

				$fcontents = ("<html>\n");
				$fcontents .= ("<head>\n");
				$fcontents .= ("<title>$title</title>\n");
				$fcontents .= ("</head>\n");
				$fcontents .= ("<body>\n");
				#$fcontents .= "<h1>$title</h1>";
				$fcontents .= "<center>$image</center>";
				$fcontents .= "<p><b>Ingredients:</b></p>";
				$fcontents .= $ingredients;
				$fcontents .= "<p><b>Dirctions:</b></p>";
				$fcontents .= $directions;
				$fcontents .= "<p>Source: <a href=\"http://";
				$fcontents .= $url;
				$fcontents .= "\">Williams-Sonoma</a>";
				$fcontents .= ("</body>\n");
				$fcontents .= ("</html>\n");

				$wp_content = "<center><p>$image</p></center>";
				$wp_content .= "<p><b>Ingredients:</b></p>";
				$wp_content .= $ingredients;
				$wp_content .= "<p><b>Dirctions:</b></p>";
				$wp_content .= $directions;
				$wp_content .= "<p>Source: <a href=\"http://";
				$wp_content .= $url;
				$wp_content .= "\">Williams-Sonoma</a>";

				#$wfilename = 'C:\wamp\www\recipe3\import\ws\\';
				#$wfilename .= $file;

				#file_put_contents($wfilename, $fcontents);

				#echo "$wp_content</p>";

				$categories = array(451);

				$title_array = explode (" ", $title);
				# preg_split("/[\s-()]+/", $title, -1, PREG_SPLIT_NO_EMPTY);

				for ($x = 0; $x < count($title_array); $x++)
				{
					for ($y = 0; $y < count($category_list); $y++) 
					{
						$trimmed = rtrim($title_array[$x], "s");
						if (strlen($title_array[$x]) > 3)
						{
							if(stristr($category_list[$y], $title_array[$x]) !== FALSE)
								##$category_list[$y], $title_array[$x]) 
							{
								array_push($categories, $y);
							}
						} else {
							break;
						}
					}
				}

				# create post object
				$my_post = array();
				$my_post['post_title'] = $title;
				$my_post['post_content'] = $wp_content;
				$my_post['post_status'] = 'publish';
				$my_post['post_author'] = 1;
				$my_post['ping_status'] = 'closed';
				$my_post['post_category'] = $categories;
				#$my_post['tags_input'] = 'almond';

				# insert the post into the database
				wp_insert_post($my_post);

				echo "<hr>";
			}
		}
	}

	die ();
    
	if ($handle = opendir('C:\My Web Sites\Wlliams-Sonoma\www.williams-sonoma.com\recipe')) {
		echo "Directory handle: $handle\n";
		echo "Files:\n";

		/* This is the correct way to loop over the directory. */
		while (false !== ($file = readdir($handle))) {
			echo "$file<br>";
		}

		closedir($handle);
	}

	print("</body>\n");

	fclose($fp);

?>