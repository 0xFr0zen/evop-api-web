<?php
include_once '../../essentials/DBConnector.php';
$myconfig = json_decode(file_get_contents("../../essentials/config.json"), true)['db']['connectors'];
class MyAdminDBConnector extends DBConnector {
    
    public function __construct(){
        $myconfig2 = $myconfig['admin'];
        parent::__construct($myconfig2['username'],$myconfig2['password'],$myconfig2['database']);
    }
}
class MyCompanyDBConnector extends DBConnector {
    
    public function __construct(){
        $myconfig2 = $myconfig['company'];
        parent::__construct($myconfig2['username'],$myconfig2['password'],$myconfig2['database']);
    }
}