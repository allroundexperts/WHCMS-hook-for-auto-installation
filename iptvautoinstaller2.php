<?php  

require_once '/var/www/html/clients/includes/hooks/libraries/Classes/Main.php';

add_hook('AcceptOrder', 1, function($vars) {


    $orderId = $vars['orderid'];
    logActivity($orderId,0);
    $initializeObj = new Main($orderId);
    $output = $initializeObj->getCustomFields();
    logActivity(json_encode($output),0);





})

















?>