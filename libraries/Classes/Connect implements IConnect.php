<?php  


include ('/var/www/html/clients/includes/hooks/libraries/Interfaces/Iconnect.php');
// include ('/home/hamza/Desktop/freelancing/WHCMS-hook-for-auto-installation/libraries/Interfaces/Iconnect.php');

class Connect implements Iconnect {
	private $username;
	private $password;
	private $port;
	private $url;

	function __construct($url, $username, $password, $port){
		$this->url = $url;
		$this->username = $username;
		$this->password = $password;
		$this->port = $port;
	}

	function connect(){
		$conn = ssh2_connect($this->url, $this->port);
		ssh2_auth_password($conn, $this->username, $this->password);
		return $conn;
	}

	function executeCommand($conn, $command){
		$stream = ssh2_exec($conn, $command);
	    stream_set_blocking($stream, true);
	    $stream_out = ssh2_fetch_stream($stream, SSH2_STREAM_STDIO);
	    $output = stream_get_contents($stream_out);
	    logActivity($output,0);
	    return $output;
	} 
}


?>