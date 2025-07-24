<?php
	function NumberDue($last_draw, $skip, $count) 
	{
		global $debug;

		$today  = mktime (0,0,0,date("m"),date("d"),date("Y"));
		$days_since_draw = ($today - $last_draw)/86400; //from last draw to next draw in days
		$skip_unix = $skip * 86400; //convert skip average into unix days
		$next_draw = $last_draw + $skip_unix; //next expected draw based on skip average in unix
		$days_due = ($next_draw - $today)/86400; //in days

		if ($days_due > 0 && $days_due < 10 && $days_since_draw < 30 && $count > 2)
		{
			return 1;
		}
		elseif ($days_due > 0 && $days_due < 17 && $days_since_draw < 30 && $count > 2)
		{
			return 2;
		} 
		else
		{
			return 0;
		}
	} 
?>
