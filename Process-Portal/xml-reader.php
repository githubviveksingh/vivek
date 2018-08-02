<?php
$permissionArray = array();
$permissionFile = $_SERVER["DOCUMENT_ROOT"].$DS."process-permission.xml";
$xml=simplexml_load_file("process-permission.xml");

foreach($xml->process as $processName){
    $permissionArray["$processName[name]"] = array();
    foreach($processName->state as $state){
        $permissionArray["$processName[name]"]["$state[current]"] = array();
        if(isset($state->changeState)){
            $permissionArray["$processName[name]"]["$state[current]"]["changeState"] = explode(",", $state->changeState);
        }
        if(isset($state->role)){
            $permissionArray["$processName[name]"]["$state[current]"]["role"] = explode(",", $state->role);
        }
        if(isset($state->approval)){
            $permissionArray["$processName[name]"]["$state[current]"]["approval"] = explode(",", $state->approval);
        }
        if(isset($state->notification)){
            $permissionArray["$processName[name]"]["$state[current]"]["notification"] = explode(",", $state->notification);
        }
        if(isset($state->edit)){
            $permissionArray["$processName[name]"]["$state[current]"]["edit"] = (string)$state->edit;
        }

    }
}
var_dump($permissionArray);
?>
