<?php
/*
Licensed to the Apache Software Foundation (ASF) under one
or more contributor license agreements.  See the NOTICE file
distributed with this work for additional information
regarding copyright ownership.  The ASF licenses this file
to you under the Apache License, Version 2.0 (the
"License"); you may not use this file except in compliance
with the License.  You may obtain a copy of the License at

http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing,
software distributed under the License is distributed on an
"AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY
KIND, either express or implied.  See the License for the
specific language governing permissions and limitations
under the License.
*/


$GUID = $argv[1];
require_once("C:\wamp\www\lotto\includes\Forker\Defines.inc.php");
require_once("C:\wamp\www\lotto\includes\Forker\ForkerManager.inc.php");

$commandFile = ForkerManager::getCommandsFile($GUID);

$xml = simplexml_load_file($commandFile);

//exec( " /opt/lampp/bin/php /opt/lampp/htdocs/php-forker/sleep.php name$i  2>/dev/null >&- <& - >/dev/null &");

foreach($xml as $command) {
	$cmd = ForkerManager::$PATH . " " . $command->cmd . " " . $GUID . " " . $command->id;
	exec("$cmd  2>/dev/null >&- <& - >/dev/null &");
}

?>