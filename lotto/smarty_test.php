<?php
	require 'C:\Users\tomde\vendor/autoload.php';
	$smarty = new Smarty();

	#require 'path/to/smarty/libs/Smarty.class.php';

	#$smarty = new Smarty();

	$smarty->template_dir = 'templates';
	$smarty->compile_dir = 'templates_c';
	$smarty->cache_dir = 'cache';
	$smarty->config_dir = 'configs';

	// Assign variables to the template
	$smarty->assign('name', 'John Doe');
	$smarty->assign('age', 28);

	// Display the template
	$smarty->display('index.tpl');

?>
