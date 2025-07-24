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

/*
 * forker.cpp
 * forker will execute the different execve functions depending 
 * on the argument given. 
 *
 * to compile: g++ -o exec forker.cpp
 */

#include <iostream>
#include <string>

#include <fstream>
#include <stdio.h>
#include <stdlib.h>

#include <vector>

#include <sys/types.h>
#include <unistd.h>

using namespace std;

string FORKER_DIR = "/opt/lampp/htdocs/php-forker";
string PATH = "/opt/lampp/bin/php";
string GUID = "";
string FORKER_COMMAND_DIR = FORKER_DIR + "/Forker/bin/command";

int PATH_INDEX = 0;
int FILE_NAME_INDEX = 1;
int GUID_INDEX = 2;
int ID_INDEX = 3;

struct forkCommand{
	string id;
	string name;
	string command;
	string status;
	vector<string> args1;
	string args;
};


vector<forkCommand> fetchCommands(string dir);
forkCommand parseXML(string xml);
int runFork(forkCommand command, string name, string id, string cmdString, string args);

int main(int argc, char* argv[]) {
	GUID = argv[1];

	pid_t pID;

	vector<forkCommand> forkCommands = fetchCommands(GUID);

	for(vector<forkCommand>::const_iterator it = forkCommands.begin(); it != forkCommands.end(); ++it) {
		forkCommand command = *it;
		pID = runFork(command, command.name, command.id, command.command, command.args);
	}

	// Code only executed by parent process
	//cout << "Parent Process:" << endl;
	
	return pID;
}

using namespace std;
forkCommand parseXML(string xml) {
	forkCommand x;
	//cout << xml;

	int start = 0;
	int end = 0;
	string value;

	// set name
	start = xml.find("<name>");
	end = xml.find("</name>");
	value = xml.substr(start + 6, end - start - 6);
	x.name = value;


	// set cmd
	start = xml.find("<cmd>");
	end = xml.find("</cmd>");
	value = xml.substr(start + 5, end - start - 5);
	x.command = value;


	// set id
	start = xml.find("<id>");
	end = xml.find("</id>");
	value = xml.substr(start + 4, end - start - 4);
	x.id = value;

	// set status
	start = xml.find("<status>");
	end = xml.find("</status>");
	value = xml.substr(start + 8, end - start - 8);
	x.status = value;

	// set args
	start = xml.find("<args>");
	end = xml.find("</args>");
	value = xml.substr(start + 6, end - start - 6);	

	string v;
	string args;
	
	vector<string> args1(0);

	int argPosition = value.find("<arg>", argPosition);
	
	while (value.find("<arg>", argPosition) < value.length()){
		start = value.find("<arg>", argPosition);
		argPosition = value.find("<arg>", argPosition + 5);
		
		end = value.find("</arg>", start);

		v = value.substr(start + 5, end - start - 5);
		args += v + " ";
		args1.push_back(v);
	}

	x.args = args;
	x.args1 = args1;

	return x;
}

vector<forkCommand> fetchCommands(string dir) {

	// read commands
	ifstream inFile;

	string fileName = FORKER_COMMAND_DIR + "/" + dir + "/commands.txt";

	inFile.open(fileName.c_str());

	if (!inFile) {
		cout << "Unable to open file " << fileName;
	    exit(1);   // call system to stop
	}

	string c;

	vector<forkCommand> forkCommands(0);

	forkCommand command;

	//while (inFile >> c) {
	while( getline( inFile, c ) ) {
		if (c.find("<command>") == 0) {
			command = parseXML(c);
			forkCommands.push_back(command);
		}
	}

	inFile.close();
	
	return forkCommands;
}

int runFork(forkCommand commandStruct, string name, string id, string cmdString, string args) {
	pid_t pID = 0;

	pID = fork();

	if (pID == 0) {
		// Code only executed by child process

		char **Env_envp = {(char **) 0};

		int size = 5;//commandStruct.args1.size() + 3;

		char *Env_argv[size];
		Env_argv[PATH_INDEX] = (char *) PATH.c_str();
		Env_argv[FILE_NAME_INDEX] = (char *) commandStruct.command.c_str();
		Env_argv[GUID_INDEX] = (char *) GUID.c_str();
		Env_argv[ID_INDEX] = (char *) commandStruct.id.c_str();
		Env_argv[size - 1] = (char *) 0;

		/*try {
			int index = 2;

			for(vector<string>::const_iterator it = commandStruct.args1.begin(); it != commandStruct.args1.end(); ++it) {
				string arg = *it;
				//cout << arg << endl;
				Env_argv[index] = (char *) "asda";//arg;

				index++;
			}
		}
		catch( char * str ) {
		  cout << "Exception raised: " << str << '\n';
		}*/
		
		try {
			cout << "start execve: " << commandStruct.name << endl;
			int execReturn = execve (Env_argv[0], Env_argv, Env_envp);	
			cout << "end execve" << endl;
		}
		catch( char * str ) {
			cout << "Exception raised: " << str << '\n';
		}		
	}
	// failed to fork
	else if (pID < 0) {
		cerr << "Failed to fork" << endl;
		// Throw exception
	}

	return pID;
}
