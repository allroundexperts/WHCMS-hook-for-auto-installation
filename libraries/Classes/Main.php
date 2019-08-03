<?php  



require_once ('/var/www/html/clients/includes/hooks/libraries/Classes/Utilities.php');


class Main {
	private $orderID;
	private $helperFunctions;
	
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
	
	// public checkServer(){

	// }
	
	// public transferFiles();
	
	// public installPanel();
}





?>