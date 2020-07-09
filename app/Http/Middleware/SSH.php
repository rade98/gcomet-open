<?php
	class Ssh_Connection {
		// Variables
		private $szLatestError = "None";
		
		private $szHost = null;
		private $iPort = 22;
		private $szUser = null;
		private $szPass = null;
		
		private $iConnection;
		private $szSeparator = "__COMMAND_FINISHED__";
		private $szCommands;
		private $iTimeOut = 5;
		
		// Constructor
		// @return void
		public function __construct( $szHost, $iPort = 22, $szUser, $szPass ) {
			$this->szHost = $szHost;
			$this->iPort = $iPort;
			$this->szUser = $szUser;
			$this->szPass = $szPass;
		}

		// Destructor
		// @return void
		public function __destruct( ) {
			$this->Clear( );
		}

		// Function for memory freeing
		// @return void
		public function Clear( ) {
			unset( $this );
		}

		// Function for connecting to remote SSH server
		// @return boolean - True on success, false otherwise
		public function OpenConnection( ) {
			// Chech if SSH2 PHP extension is not installed
			if( !function_exists( "ssh2_connect" ) ) {
				$this->szLatestError = "SSH Extension is not installed!";
				return false;
			}

			// Chech if host is missing
			if( empty( $this->szHost ) ) {
				$this->szLatestError = "Host is missing!";
				return false;
			}
			
			// Chech if username is missing
			if( empty( $this->szUser ) ) {
				$this->szLatestError = "Username is missing!";
				return false;
			}
			
			// Chech if password is missing
			if( empty( $this->szPass ) ) {
				$this->szLatestError = "Password is missing!";
				return false;
			}
			
			// Connect to remote SSH server
			if( !( $this->iConnection = ssh2_connect( $this->szHost, $this->iPort ) ) ) {
				$this->szLatestError = "SSH Connection failed";
				return false;
			}
			
			// Login
			if( !ssh2_auth_password( $this->iConnection, $this->szUser, $this->szPass ) ) {
				$this->szLatestError = "SSH Login failed";
				return false;
			}
			
			return true;
		}

		// Function for adding command to command quee
		// @return void
		public function AddCommand( $szCommand ) {
			if( is_array( $szCommand ) ) {
				// If string is an array add it to commmand quee
				foreach( $szCommand AS $szCmd ) {
						$this->szCommands .= $this->EscapeCommand( $szCmd . ";echo \"[SEPARATOR]\";" );
				}
			} else {
				// If string is not array add it to commmand quee
				$this->szCommands .= $this->EscapeCommand( $szCommand . ";echo \"[SEPARATOR]\";" );
			}
		}

		// Function for cleaning command quee
		// @return void
		public function ClearCommands( ) {
			$this->szCommands = "";
		}

		// Function for protection aginst SSH injections
		// @return String - Esacped SSH command
		public function EscapeCommand( $szCommand ) {
			return escapeshellarg( trim( $szCommand ) );
		}

		// Function for executing commands from command quee
		// @return String
		public function ExecuteCommands( ) {
			// Chech if connection is lost
			if( !( $iStream = ssh2_exec( $this->GetConnection( ), $this->szCommands . " echo ". $this->szSeparator ) ) ) {
				$this->szLatestError = "Connection lost!";
				$Executed = false;
			} else {
				// Block stream to other connections
				stream_set_blocking( $iStream, false );
				
				// Variables
				$szOutput = "";
				$iStarted = time( );

				// Do a while loop for each command
				while( true ) {
					$szOutput .= fread( $iStream, 4096 );
					
					// Get output
					if( strpos( $szOutput, $this->szSeparator ) !== false ) {
						$szOutput = preg_replace( "/$this->szSeparator/", '', $szOutput );
						$Executed = $szOutput;
						break;
					}

					// Check for timeout
					if( time( ) - $iStarted > $this->iTimeOut ) {
						$this->szLatestError = "Command execution timed out!";
						$Executed = false;
						break;
					}
				}

				// Close file stream
				fclose( $iStream );
			}

			// Return output
			$Executed = explode( "[SEPARATOR]\n", $Executed );
			unset( $Executed[ count( $Executed ) - 1 ] );
			return $Executed;
		}

		// Function for getting connection ssh object
		// @return ssh class object
		public function GetConnection( ) {
			return $this->iConnection;
		}

		// Function for getting lastest error
		// @return String - Lastest error
		public function GetLatestError( ) {
			return $this->szLatestError;
		}
	}
?>
