<?php  


include ('/var/www/html/clients/includes/hooks/libraries/Interfaces/Iconnect.php')


class Connect implements Iconnect {
	private $conn;


	function _construct($url, $username, $password, $port){
		$this->url = $url;
		$this->username = $username;
		$this->password = $password;
		$this->port = $port;
	
		$this->conn = ssh2_connect($ip,$port);
		ssh2_auth_password($this->conn, $username, $password);
	}

	function executeCommand($command){
		$stream = ssh2_exec($this->conn, $command);
	    stream_set_blocking($stream, true);
	    $stream_out = ssh2_fetch_stream($stream, SSH2_STREAM_STDIO);
	    $output = stream_get_contents($stream_out);
	    return $output;
	} 
}


?>