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


class Forker {
	private $GUID = null;
	private $name = '';
	private $commands = array();

	private $outputs = array();

	private $index = 0;
	private $asynchronous = false;

	private $status = FORKER_COMMAND_WAITING_STATUS;

	
	public function __construct($name, $asynchronous = false, $GUID = '') {
		$this->name = $name;

		$this->asynchronous = $asynchronous;

		if ($GUID == '') {
			$GUID = ForkerManager::getGUID();
		}

		$this->GUID = $GUID;
	}

	public function getGUID() {
		return $this->GUID;
	}

	public function setCommands($commands) {
		foreach ($commands as $command) {
			$this->commands[$command[0]] = array($command[1], $args[2], $command[3]);
		}

		$this->status = FORKER_COMMAND_RUNNING_STATUS;
	}


	public function addCommand($name, $command, $args = array()) {
		if (array_key_exists($name, $this->commands)) {
			return FORKER_COMMAND_EXIST;
		}

		$this->commands[$name] = array($command, $args, $this->index);

		$this->index++;

		return FORKER_COMMAND_ADDED;
	}

	public function deleteCommand($name) {
		if (!array_key_exists($name, $this->commands)) {
			return FORKER_COMMAND_ERROR;
		}

		unset($this->commands[$name]);

		return FORKER_COMMAND_DELETED;
	}

	public function runComplete() {
		$status = true;

		if ($this->status == FORKER_COMMAND_WAITING_STATUS) {
			// currently running
			return false;
		}

		foreach ($this->commands as $name => $command) {
			$file = ForkerManager::getStatusFile($this->GUID, $command[2]);

			if (!file_exists($file)) {
				$status = false;
				ForkerManager::debug("Forker::runComplete - status file '$file' does not exist");
				break;
			}
		}

		$outError = false;

		if ($status === true) {
			$this->setOutput($outError);
		}

		return $status;
	}

	public function run() {
		if ($this->status != FORKER_COMMAND_WAITING_STATUS) {
			return false;
		}

		$this->status = FORKER_COMMAND_RUNNING_STATUS;
		$this->saveCommandsForExec();		

		$output = array();
		$return = -1;

		$command = $this->getCommand();

		ForkerManager::debug("fork exec command: '$command'");

		exec ($command, $output, $return);
		print_r($output);
		print_r($return);

		$this->postRunHandler();

		return true;
	}

	private function postRunHandler() {
		$this->status = FORKER_COMMAND_STOPPED_STATUS;
		if ($this->asynchronous !== false) {
			return;
		}

		$outError = false;

		$this->setOutput($outError);

		$this->deleteForker($outError);		
	}

	private function deleteForker(&$outError = false) {
		foreach ($this->commands as $name => $command) {
			$id = $command[2];
			
			$file = ForkerManager::getCommandFile($this->GUID, $id);			
			//unlink($file);

			$file = ForkerManager::getStatusFile($this->GUID, $id);
			//unlink($file);
		}

		$dir = ForkerManager::getDir($this->GUID);

		$outPutDir = $dir . "/output";
		//rmdir($outPutDir);

		$outPutDir = $dir . "/status";
		//rmdir($outPutDir);

		$file = ForkerManager::getCommandsFile($this->GUID);
		//unlink($file);

		//rmdir($dir);

	}

	private function getCommand() {
		if ($this->asynchronous == false) {
			$command = ForkerManager::$COMMAND . " " . $this->GUID;
		}
		else {
			$command = ForkerManager::$PATH . " " . ForkerManager::$ASYNC_PATH . " " . $this->GUID;
		}

		return $command;
	}

	private function saveCommandsForExec() {
		$commandText = ForkerManager::transformCommandsForExec($this->commands, $this->name, (int) $this->asynchronous);
		$file = $this->getCommandFile();
		ForkerManager::debug("Fork commands file: " . $file);
		file_put_contents($file, $commandText);
	}

	public function setOutput(&$outError = false) {
		$outError = false;

		if (count($this->outputs) > 0) {
			return;
		}

		foreach ($this->commands as $name => $command) {
			$id = $command[2];
			
			$file = ForkerManager::getCommandFile($this->GUID, $id);
			
			$data = file_get_contents($file);

			$file = ForkerManager::getStatusFile($this->GUID, $id);
			
			$outputType = file_get_contents($file);
			
			if ($outputType == FORKER_OUTPUT_OBJECT_TYPE) {
				$data = unserialize($data);
			}

			$this->outputs[$name] = $data;			
		}
	}

	public function getOutput($name, &$outError = FORKER_COMMAND_OUTPUT_SUCCESS) {
		$this->setOutput();

		$this->outputs[$name];
	}

	public function getAllOutputs(&$outError = FORKER_COMMAND_OUTPUT_SUCCESS) {
		$this->setOutput();

		return $this->outputs;
	}

	public function getCommandFile() {
		$file = ForkerManager::getCommandsFile($this->GUID);
		if (!file_exists($file)) {
			touch($file);
			chmod($file, 0777);
		}

		return $file;
	}


	public function getDir() {
		return ForkerManager::getDir($this->GUID);
	}
}
?>