if ($row_upd['even'] = 0 AND $row_upd['even'] = 0) 
			{
				$even_upd = 0;
				$odd_upd = 0;

				$draw_upd = array ($row_upd['d1'],$row_upd['d2']);

				even_odd($draw_upd,$even_upd,$odd_upd);

				$query_update = "UPDATE $table_temp ";
				$query_update .= "SET even = $even_upd, ";
				$query_update .= "SET odd = $odds_upd, ";
				$query_update .= "WHERE id = $row_sum['id'] ";

				print "$query_update<p>";

				$mysqli_result_update = mysqli_query ($mysqli_link, $query_update) or die (mysqli_error($mysqli_link));
			}

			if ($row_upd['sum_even'] = 0 AND $row_upd['sum_even'] = 0) 
			{
				$query_sum = "SELECT * FROM $table_temp ";
				$query_sum .= "WHERE date >= $row_upd['$draw_date'] ";
				$query_sum .= "ORDER BY date ASC "; 

				print "$query_sum<p>";

				$mysqli_result_sum = mysqli_query($mysqli_link, $query_sum) or die (mysqli_error($mysqli_link));

				$row_sum = mysqli_fetch_array($mysqli_result_sum)

				$query_update = "UPDATE $table_temp ";
				$query_update .= "SET sum_even = $row_sum['even'], ";
				$query_update .= "SET sum_odd = $row_sum['odd'], ";
				$query_update .= "WHERE id = $row_sum['id'] ";

				print "$query_update<p>";

				$mysqli_result_update = mysqli_query ($mysqli_link, $query_update) or die (mysqli_error($mysqli_link));