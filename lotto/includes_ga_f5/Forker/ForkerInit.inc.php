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


require_once(FORKER_HOME_DIR . "/Forker/ForkerManager.inc.php");

class ForkerInit {

	private static $instance = null;
	private $GUID = null;
	private $id = 0;
	private $index = 0;
	private $command = null;
	private $outputType = 'html';
	
	private function __construct($id, $GUID) {
		$this->id = $id;
		$this->GUID = $GUID;

		$this->getCommand();
	}

	public function getOutputType() {
		return $this->outputType;
	}

	public function setOutputType($outputType) {
		$this->outputType = $outputType;
	}

	public function getGUID() {
		return $this->GUID;
	}

	public function getID() {
		return $this->id;
	}

	public function getDir() {
		return ForkerManager::getDir($this->GUID);
	}

	public static function getInstance() {
		if (self::$instance == null) {

			global $argv;

			$id = $argv[2];
			$GUID = $argv[1];

			self::$instance = new ForkerInit($id, $GUID);

			ForkerManager::setForkCommandBuffer(self::$instance);
		}

		return self::$instance;
	}

	
	private function getCommand() {
		$commandFile = ForkerManager::getCommandsFile($this->GUID);

		$xml = simplexml_load_file($commandFile);

		foreach($xml as $command) {
			if ((int) $command->id == $this->id) {
				$this->command = $command;
				break;
			}
		}

		$this->handleArgs();
	}

	private function handleArgs() {
		global $ARGV;

		$names = $this->command->names->name;

		$i = 0;

		foreach ($this->command->args->arg as $arg) {
			$name = (string) $names[$i];
			$ARGV[$name] = (string) $arg;
			
			$i++;
		}
	}

	public function saveData() {
		$data = ob_get_contents();
		ob_end_clean();

		$commandFile = ForkerManager::getCommandFile($this->GUID, $this->id);
		file_put_contents($commandFile, $data);

		$statusFile = ForkerManager::getStatusFile($this->GUID, $this->id);
		file_put_contents($statusFile, $this->outputType);
	}
}

$ARGV = array();

$forker = ForkerInit::getInstance();
?>