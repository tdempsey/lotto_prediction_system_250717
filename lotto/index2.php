<?php

require('guestbook/setup.php');

$smarty = new Smarty_GuestBook();

$smarty->assign('name','Tom');

$smarty->display('index.tpl');
?>