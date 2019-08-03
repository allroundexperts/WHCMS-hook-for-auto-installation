<?php  

interface Iconnect
{
	private $username;
	private $password;
	private $port;
	private $url;

	// public connect();
	public executeCommand($command);
	// public transferFile($source, $destination);
}


?>