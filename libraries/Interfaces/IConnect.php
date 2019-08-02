<?php  

interface IConnect
{
	private $Username;
	private $Password;
	private $Port;
	private $Url;

	public connect();
	public ExecuteCommand($connection, $command);
	public TransferFile($source, $destination);
}


?>