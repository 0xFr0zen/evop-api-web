<?php
include_once __DIR__."/../essentials/moderator.php";
header("Content-Type: application/javascript");
$result = array();
if (isset($_REQUEST['did']) && isset($_REQUEST['sid'])) {
    $moderator = new Moderator(
        $_REQUEST['did'],
        $_REQUEST['sid']
    );
    unset($_REQUEST['sid']);
    unset($_REQUEST['did']);
    $reqs = array_keys($_REQUEST);
    $found = false;
    foreach($reqs as $key => $value){
        if(!$found){
            switch ($reqs[$key]) {
                case 'modrequests':
                    $r = $moderator->request();
                    if($r != null){

                        $result = array("result" => array("requested" => $r));
                    }else {
                        $result = array("result" => array("error" => array("error" => array("message" => "This SID and DID was already requested!"))));
                    }
                    $found = true;
                    break;
                case 'loginstatus':
                    $result = array("result" => array("status" => $moderator->checklogin()));
                    $found = true;
                    break;
                case 'login':
                    $result = array("result" => array("loggedin" => $moderator->login()));
                    $found = true;
                break;
            }
        }
        
    }
}else {
    $result = array("error" => array("message" => "You need to specify, SIDs and DIDs"));
}

print(json_encode($result, JSON_NUMERIC_CHECK));