<?php

	$game = 1; // Georgia Fantasy 5

	require ("includes/games_switch.incl");

	require ("includes/mysqli.php");
	#require ("includes/db.class");
	require ('includes/db.class.php');

	#$nums = array(0,1,7,8,18,19,20,22,24,31,37,39,41,42);

	$nums = array(0,1,3,8,11,12,13,21,22,28,32,37,39,40);

	echo "<h2>1 = $nums[1]-$nums[2]-$nums[5]-$nums[7]-$nums[12] = ";				$num_sum=$nums[1]+$nums[2]+$nums[5]+$nums[7]+$nums[12];
	echo "$num_sum</h2>";

	echo "<h2>2 = $nums[1]-$nums[2]-$nums[8]-$nums[11]-$nums[13] = ";
	$num_sum=$nums[1]+$nums[2]+$nums[8]+$nums[11]+$nums[13];
	echo "$num_sum</h2>";

	echo "<h2>3 = $nums[1]-$nums[3]-$nums[4]-$nums[5]-$nums[11] = ";
	$num_sum=$nums[1]+$nums[3]+$nums[4]+$nums[5]+$nums[11];
	echo "$num_sum</h2>";
	
	echo "<h2>4 = $nums[1]-$nums[3]-$nums[6]-$nums[8]-$nums[10] = ";
	$num_sum=$nums[1]+$nums[3]+$nums[6]+$nums[8]+$nums[10];
	echo "$num_sum</h2>";
	
	echo "<h2>5 = $nums[2]-$nums[3]-$nums[4]-$nums[7]-$nums[9] = ";
	$num_sum=$nums[2]+$nums[3]+$nums[4]+$nums[7]+$nums[9];
	echo "$num_sum</h2>";
	
	echo "<h2>6 = $nums[2]-$nums[4]-$nums[6]-$nums[8]-$nums[10] = ";
	$num_sum=$nums[2]+$nums[4]+$nums[6]+$nums[8]+$nums[10];
	echo "$num_sum</h2>";
	
	echo "<h2>7 = $nums[3]-$nums[4]-$nums[8]-$nums[12]-$nums[13] = ";
	$num_sum=$nums[3]+$nums[4]+$nums[8]+$nums[12]+$nums[13];
	echo "$num_sum</h2>";
	
	echo "<h2>8 = $nums[5]-$nums[6]-$nums[7]-$nums[10]-$nums[13] = ";
	$num_sum=$nums[5]+$nums[6]+$nums[7]+$nums[10]+$nums[13];
	echo "$num_sum</h2>";
	
	echo "<h2>9 = $nums[5]-$nums[7]-$nums[8]-$nums[9]-$nums[11] = ";
	$num_sum=$nums[5]+$nums[7]+$nums[8]+$nums[9]+$nums[11];
	echo "$num_sum</h2>";
	
	echo "<h2>10 = $nums[6]-$nums[9]-$nums[10]-$nums[11]-$nums[12] = ";
	$num_sum=$nums[6]+$nums[9]+$nums[10]+$nums[11]+$nums[12];
	echo "$num_sum</h2>";
?>