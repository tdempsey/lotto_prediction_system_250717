<?php
	function log_text ($text)
	{
		$now = date("D M j G:i:s T Y");

		$text_date = $text . " - " . $now;
		
		$file = 'php_log.txt';

		file_put_contents($file, $text_date, FILE_APPEND | LOCK_EX);
	}
>