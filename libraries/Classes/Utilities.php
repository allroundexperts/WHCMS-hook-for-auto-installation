<?php  


//This function converts the json returend from API call to a php
function returnobj($outputfromAPI){
    $str = json_encode($outputfromAPI);
    $phpobj = json_decode($str);
    return $phpobj;
}

function returnvalue ($array_product, $totalproduct, $orderID, $valuetofind){
    for ($x = 0; $x <= $totalproduct; $x++){
        if ($array_product[$x]->id == $orderID){
            if ($array_product[$x]->pid == 1){
                $field = $array_product[$x]->customfields->customfield;
                for ($y = 0; $y <= 4; $y++){
                    if ($field[$y]->name == $valuetofind){
                        return $field[$y]->value;
                    }
                }
            }
        }
    }
}

function connect ($ip, $username, $password, $port){
    $conn = ssh2_connect($ip,$port);
    ssh2_auth_password($conn, $username, $password);
    return $conn;
}

function execute_command($conn,$command){

    $stream = ssh2_exec($conn, $command);
    stream_set_blocking($stream, true);
    $stream_out = ssh2_fetch_stream($stream, SSH2_STREAM_STDIO);
    $output = stream_get_contents($stream_out);
    return $output;
}









?>