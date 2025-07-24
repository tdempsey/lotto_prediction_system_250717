			$z = intval($draw_sum/10);

			$table_sum_temp = $draw_prefix . "temp_sum";

			$query6 = "SELECT * FROM $table_sum_temp ";
			$query6 .= "WHERE num = $z ";
		
			$mysqli_result6 = mysqli_query($query6, $mysqli_link) or die (mysqli_error($mysqli_link));

			$row_sum = mysqli_fetch_array($mysqli_result6);