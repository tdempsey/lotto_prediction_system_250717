<?php
function DrawPB()
   {
		// pb table removed - add logic here
   		// test this function
		global $debug,$sorted_nums,$pb_nums,$pb_last,$exclude_pb,$pb_dup;

		// error checking ----------------------------------------------------------------------------------------------
		if (is_null($sorted_nums) || count(sorted_nums) == 0)
		{
			exit("<h2>Error - function draw_pb.php - <font color=\"#FF0000\">array sorted_nums undefined</font></h2>");
		}

		if (is_null($exclude_pb) || count(exclude_pb) == 0)
		{
			exit("<h2>Error - function draw_pb.php - <font color=\"#FF0000\">array exclude_pb undefined</font></h2>");
		}

		if (is_null($combo) || count(combo) == 0)
		{
			exit("<h2>Error - function draw_pb.php - <font color=\"#FF0000\">array combo undefined</font></h2>");
		}
		
		if (is_null($pb_last) || is_null($pb_dup))
		{
			exit("<h2>Error - function draw_pb.php - <font color=\"#FF0000\">parameter undefined - pb_last = $pb_last, pb_dup = $pb_dup</font></h2>");
		}
		// error checking ----------------------------------------------------------------------------------------------

		mt_srand((float) microtime() * 10000000);
		
		$megaball = $pb_nums[mt_rand(0, count($pb_nums)-1)];

		if (($megaball == $sorted_nums[0] || $megaball == $sorted_nums[1] || $megaball == $sorted_nums[2] || $megaball == $sorted_nums[3] || $megaball == $sorted_nums[4]) && $pb_dup == 0)
		{
			print "rejected - megaball in draw - $megaball</B><br>";
			return 0;
		}

		if ($megaball == $pb_last)
		{
			print "rejected - last draw megaball - $megaball</B><br>";
			return 0;
		}

		for ($y = 0; $y < count($exclude_pb); $y++)
		{
			if ($megaball == $exclude_pb[$y])
			{
				print "rejected - excluded megaball - <B>$exclude_pb[$y]</B><br>";
				return 0;
			}
		}

		if ($debug)
		{
			print "DrawPB() - $megaball<br>";
		}

		return $megaball;
   }
?>