<?php
include_once __DIR__."/../essentials/moderator.php";
$result = array();
if (isset($_REQUEST['did']) && isset($_REQUEST['sid'])) {
    $moderator = new Moderator(
        $_REQUEST['did'],
        $_REQUEST['sid']
    );
    $reqs = array_keys($_REQUEST);
    foreach($reqs as $key => $value){
        $r = $_REQUEST[$key];
        if(!$found){
            switch ($variable) {
                case 'modrequests':
                    $result = array("result" => array("requested" => $moderator->request()));
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
}

print(json_encode($result, JSON_NUMERIC_CHECK));