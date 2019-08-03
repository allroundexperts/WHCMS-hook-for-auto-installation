<?php  

class Utilities{
    public function request($command, $postData){
        $outputFromAPI = localAPI($command, $postData);
        $str = json_encode($outputFromAPI);
        $phpObj = json_decode($str);
        return $phpObj;    
    }

    public function returnvalue($array_product, $totalproduct, $orderID, $valuetofind){
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
}

?>