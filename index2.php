<?php
header("Content-Type: application/javascript");

$rmode = $_SERVER['REQUEST_METHOD'];
if(file_exists(__DIR__.'/methods/types/'.strtolower($rmode).'.php')) {
    include_once __DIR__.'/methods/types/'.strtolower($rmode).'.php';
    $reqm = new ReqMethod();
    if($reqm->exists) {
        $reqm->execute();
        print(json_encode($reqm->result, JSON_NUMERIC_CHECK));
    }
}else {
    // header(""); 404 error
}
