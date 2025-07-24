<?php
function log_info($content) 
{  
	$handle = fopen ("c:\lm6.log", "a");
	
	fwrite($handle, $content);

	fclose($handle);
}
?>