<?php
header("Content-Type: application/javascript");

$rmode = $_SERVER['REQUEST_METHOD'];
if(file_exists(__DIR__.'/user-methods/types/'.strtolower($rmode).'.php')) {
    include_once __DIR__.'/user-methods/types/'.strtolower($rmode).'.php';
    $reqm = new ReqMethod();
    $reqm->execute();
    $enc = json_encode($reqm->result, JSON_NUMERIC_CHECK);
    $mdenc = md5($enc);
    if(isset(getallheaders()['My-Hash-Last']) && getallheaders()['My-Hash-Last'] === $mdenc){
        $enc = json_encode(array(), JSON_NUMERIC_CHECK);
    }

    header("My-Hash-New: ".$mdenc);
    print($enc);
}else {
    die("file doesnt exists!");
}
