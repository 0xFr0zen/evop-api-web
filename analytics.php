<?php
header("Content-Type: application/javascript");
if(getallheaders()['User-Agent'] !== "Evop-User-Agent"){
    print(json_encode(array("error"=> "You are not allowed to view this page!")));
}else {
    include_once __DIR__."/utils/analytics.php";
    $result = array();
    $analytics = new Analytics();
    try {
        $analytics->setInterest($_GET['interest']);
        $analytics->setQuery($_GET['query']);
        $result = $analytics->execute();
    
    } catch (\Throwable $th) {
        $result = array("error" => $th->message);
    }
    
    $enc = json_encode($result, JSON_NUMERIC_CHECK);
    $mdenc = md5($enc);
    if(isset(getallheaders()['My-Hash-Last']) && getallheaders()['My-Hash-Last'] === $mdenc){
        $enc = json_encode(array(), JSON_NUMERIC_CHECK);
    }
    
    header("My-Hash-New: ".$mdenc);
    print($enc);
}
