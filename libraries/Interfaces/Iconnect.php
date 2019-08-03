<?php  

interface Iconnect
{
	private $username;
	private $password;
	private $port;
	private $url;

	public connect();
	public executeCommand($connection, $command);
	public transferFile($source, $destination);
}


?>