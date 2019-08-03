<?php  



require_once ('/var/www/html/clients/includes/hooks/libraries/Classes/Utilities.php');


class Main {
	private $orderID;
	
	function __construct($order)
	{
		$this->orderID = $order;
	}

	public function getCustomFields()
	{
		// Using the order ID, I will fetch the details of the order.
	    // This detail doesn't have custom field data. So i will extract client id at the moment

	    $postData_getorders = array('id' => $this->orderID,);
	    $results_getorders = localAPI('GetOrders', $postData_getorders);
	    $results_getorders_obj = returnobj($results_getorders);

	    $userid = $results_getorders_obj->orders->order[0]->userid;

	    // //Now, i have userid and orderID. The Api call that will give me custom field data is 'GetClientProducts'
	    // //In order to handle an edge case, I have to find the number of orders the client have made till date.

	    $postData_getclientdetails = array ('clientid' => $userid, 'stats' => true,);
	    $results_getclientdetails = localAPI('GetClientsDetails', $postData_getclientdetails);
	    $results_getclientdetails_obj = returnobj($results_getclientdetails);
	    $total_active_order = $results_getclientdetails_obj->stats->productsnumactiveother;


	    // //Fetching custom field data

	    $postData_getclientsproduct = array ('clientid' => $userid, 'stats' => true, 'limitnum' => ($total_active_order+1000),);
	    $results_getclientsproduct = localAPI('GetClientsProducts',$postData_getclientsproduct);

	    $results_getclientsproduct_obj = returnobj($results_getclientsproduct);
	    $list_product = $results_getclientsproduct_obj->products->product;
	    $total_result = $results_getclientsproduct_obj->totalresults;

	    $ip = returnvalue($list_product,$total_result,$this->orderID,"Server IP");
	    $username = returnvalue($list_product,$total_result,$this->orderID,"Username");
	    $password = returnvalue($list_product,$total_result,$this->orderID,"Password");
	    $port = returnvalue($list_product,$total_result,$this->orderID,"SSH Port");
	    
	    logActivity($ip,0);
	    logActivity($username,0);
	    logActivity($password,0);
	    logActivity($port,0);

	    return array($ip, $username, $password, $port);
	}
	
	// public checkServer(){

	// }
	
	// public transferFiles();
	
	// public installPanel();
}





?>