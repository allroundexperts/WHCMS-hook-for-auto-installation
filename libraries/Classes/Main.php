<?php  



require_once ('/var/www/html/clients/includes/hooks/libraries/Classes/Utilities.php');
require_once ('/var/www/html/clients/includes/hooks/libraries/Classes/Connect implements IConnect.php');


class Main {
	private $orderID;
	private $helperFunctions;
	private $connection;
	
	function __construct($order)
	{
		$this->orderID = $order;
		$this->helperFunctions = new Utilities();
	}

	public function getCustomFields()
	{
		// Using the order ID, I will fetch the details of the order.
	    // This detail doesn't have custom field data. So i will extract client id at the moment

	    $postDataGetOrders = array('id' => $this->orderID,);
	    $resultsGetOrdersObj = $this->helperFunctions->request('GetOrders',$postDataGetOrders);
	    $userId = $resultsGetOrdersObj->orders->order[0]->userid;

	    //Now, i have userid and orderID. The Api call that will give me custom field data is 'GetClientProducts'
	    //In order to handle an edge case, I have to find the number of orders the client have made till date.

	    $postDataGetClientDetails = array ('clientid' => $userid, 'stats' => true,);
	    $resultsGetClientDetailsObj = $this->helperFunctions->request('GetClientsDetails',$postDataGetClientDetails);
	    $totalActiveOrder = $resultsGetClientDetailsObj->stats->productsnumactiveother;

	    // Fetching custom field data

	    $postDataGetClientsProduct = array ('clientid' => $userId, 'stats' => true, 'limitnum' => ($totalActiveOrder+1000),);
	    $resultsGetClientsProductObj = $this->helperFunctions->request('GetClientsProducts',$postDataGetClientsProduct);
	    $list_product = $resultsGetClientsProductObj->products->product;
	    $total_result = $resultsGetClientsProductObj->totalresults;

	    $ip = $this->helperFunctions->returnvalue($list_product,$total_result,$this->orderID,"Server IP");
	    $username = $this->helperFunctions->returnvalue($list_product,$total_result,$this->orderID,"Username");
	    $password = $this->helperFunctions->returnvalue($list_product,$total_result,$this->orderID,"Password");
	    $port = $this->helperFunctions->returnvalue($list_product,$total_result,$this->orderID,"SSH Port");
	    
	    return array($ip, $username, $password, $port);
	}
	
	public function checkServer($url, $username, $password, $port){
		logActivity("Printing values 2",0);
		logActivity($url,0);
		logActivity($username,0);
		logActivity($password,0);
		logActivity($port,0);

		$this->connection = new Connect($url, $username, $password, $port);
		$conn = $this->connection->connect();
		$output = $this->connection->executeCommand($conn, "sudo uname -o");
		logActivity($output,0);


		if (strpos($output, 'sudo') !== false) {
        	logActivity("Server not linux",0);
        	return 0;
	    } elseif (strpos($output, 'Linux') !== false) {
	        logActivity("Server is linux");
	        return 1;
	    }
	}
	
	public function transferFiles($source, $destination){
		$conn = $this->connection->connect();
		$status = ssh2_scp_send($conn, $source, $destination, 0644);
        logActivity(json_encode($status),0);
        return $status;
	}
	
	public function installPanel(){
		$conn = $this->connection->connect();

		$output = $this->connection->executeCommand($conn,'sudo dpkg --get-selections | grep apache2');
        if ($output == ""){
            $output = $this->connection->executeCommand($conn,'sudo apt install -y apache2');
        }
        logActivity($output,0);

        $output = $this->connection->executeCommand($conn,'sudo dpkg --get-selections | grep mysql-server');
        if ($output == ""){
            $output = $this->connection->executeCommand($conn,'sudo apt install -y mysql-server');
        }
        logActivity($output,0);

        $output = $this->connection->executeCommand($conn,'sudo dpkg --get-selections | grep php7.');
        if ($output == ""){
            $output = $this->connection->executeCommand($conn, 'sudo apt install -y php7.2 libapache2-mod-php7.2 php-mysql');
        }
        logActivity($output,0);

        $output = $this->connection->executeCommand($conn, 'sudo dpkg --get-selections | grep unzip');
        if ($output == ""){
            $output = $this->connection->executeCommand($conn, 'sudo apt install -y unzip');      
        }
        $output = $this->connection->executeCommand($conn, "unzip MultiCS_Panel.zip -d /root/iptv_files");

	}
}





?>