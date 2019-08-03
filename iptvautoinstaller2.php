<?php  

require_once '/var/www/html/clients/includes/hooks/libraries/Classes/Main.php';

add_hook('AcceptOrder', 1, function($vars) {


    $orderId = $vars['orderid'];
    logActivity($orderId,0);
    $initializeObj = new Main($orderId);
    $output = $initializeObj->getCustomFields();
    logActivity(json_encode($output),0);
    $result = $initializeObj->checkServer($output[0], $output[1], $output[2], $output[3]);
    
    if ($result == 1){
    	$result = $initializeObj->transferFiles('/var/www/html/clients/includes/hooks/MultiCS_Panel.zip','/root/MultiCS_Panel.zip');
    	if ($result == 0){
    		logActivity("File could not be transfered",0);
    	}else if ($result == 1){
    		$initializeObj->installPanel();
    	}
    }
})
?>