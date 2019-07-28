<?php 

//This function converts the json returend from API call to a php
function returnobj($outputfromAPI){
    $str = json_encode($outputfromAPI);
    $phpobj = json_decode($str);
    return $phpobj;
}

add_hook('AcceptOrder', 1, function($vars) {

    //The AcceptOrder hook only give us access to the orderID

    $orderid = $vars['orderid'];

    // Using the order ID, I will fetch the details of the order.
    // This detail doesn't have custom field data. So i will extract client id at the moment

    $postData_getorders = array('id' => $orderid,);
    $results_getorders = localAPI('GetOrders', $postData_getorders);
    $results_getorders_obj = returnobj($results_getorders);

    $userid = $results_getorders_obj->orders->order[0]->userid;

    //Now, i have userid and orderid. The Api call that will give me custom field data is 'GetClientProducts'
    //In order to handle an edge case, I have to find the number of orders the client have made till date.

    $postData_getclientdetails = array ('clientid' => $userid, 'stats' => true,);
    $results_getclientdetails = localAPI('GetClientsDetails', $postData_getclientdetails);
    $results_getclientdetails_obj = returnobj($results_getclientdetails);
    $total_active_order = $results_getclientdetails_obj->stats->productsnumactiveother;

    logActivity("order",0); 
    logActivity($total_active_order,0);

    //Fetching custom field data

    $postData_getclientsproduct = array ('clientid' => $userid, 'stats' => true, 'limitnum' => ($total_active_order+1000),);
    $results_getclientsproduct = localAPI('GetClientsProducts',$postData_getclientsproduct);

    logActivity(json_encode($results_getclientsproduct));

    $results_getclientsproduct_obj = returnobj($results_getclientsproduct);
    $list_product = $results_getclientsproduct_obj->products->product;
    $total_result = $results_getclientsproduct_obj->totalresults;




    logActivity(json_encode($results_getclientsproduct_obj->products->product[0]->customfields),0);


});
