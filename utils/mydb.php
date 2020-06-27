<?php
include_once '../../essentials/DBConnector.php';
class MyAdminDBConnector extends DBConnector {
    
    public function __construct(){
        $myconfig = json_decode(file_get_contents("./config.json"), true)['db']['connectors']['admin'];
        parent::__construct($myconfig['username'],$myconfig['password'],$myconfig['database']);
    }
}
class MyCompanyDBConnector extends DBConnector {
    
    public function __construct(){
        $myconfig = json_decode(file_get_contents("./config.json"), true)['db']['connectors']['company'];
        parent::__construct($myconfig['username'],$myconfig['password'],$myconfig['database']);
    }
}