<?php
	function pebug ($str)
	{
		global $debug;
		
		if ($debug)
		{
			echo "<p>$str</p>";
		}

		return 0;
	}
?>