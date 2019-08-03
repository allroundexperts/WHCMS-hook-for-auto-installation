<?php  

interface Iconnect
{
	// private $username;
	// private $password;
	// private $port;
	// private $url;

	// public connect();
	function connect();
	function executeCommand($conn, $command);

	// public transferFile($source, $destination);
}


?>