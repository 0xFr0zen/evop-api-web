<?php

if (isset($_REQUEST['modrequests'])) {
    $dbconn = new MyAdminDBConnector();
    
    if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_REQUEST['did']) && isset($_REQUEST['sid'])) {
        $did = $_REQUEST['did'];
        $sid = $_REQUEST['sid'];
        $lgd = $dbconn->addModRequest($did, $sid);
    }
    $mrqs = $dbconn->getModRequests();
    $result = array("result" => array("mod_requests" => $mrqs));
    die(json_encode($result));
}
if (isset($_REQUEST['loginstatus'])) {
    $dbconn = new MyAdminDBConnector();
    $mrqs = $dbconn->checklogin();
    if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_REQUEST['did']) && isset($_REQUEST['sid'])) {
        $did = $_REQUEST['did'];
        $sid = $_REQUEST['sid'];
        $mrqs = $dbconn->addModRequest($did, $sid);
    }else {
        if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_REQUEST['did']) && isset($_REQUEST['sid'])) {
            $did = $_REQUEST['did'];
            $sid = $_REQUEST['sid'];
            $mrqs = $dbconn->addModRequest($did, $sid);
        }
    }
    $result = array("result" => array("requestparams" => $mrqs));
    die(json_encode($result));
}