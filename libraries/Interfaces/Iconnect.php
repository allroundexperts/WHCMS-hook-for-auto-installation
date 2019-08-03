<?php  

interface Iconnect
{

	function connect();
	function executeCommand($conn, $command);
}


?>