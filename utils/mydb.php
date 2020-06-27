<?php
include_once __DIR__.'/../../essentials/DBConnector.php';
$myconfig = json_decode(file_get_contents(__DIR__."/../../essentials/config.json"), true)['db']['connectors'];
class MyAdminDBConnector extends DBConnector {
    
    function __construct(){
        parent::__construct(
            $myconfig['admin']['username'],
            $myconfig['admin']['password'],
            $myconfig['admin']['database']
        );
    }
}
class MyCompanyDBConnector extends DBConnector {
    
    function __construct(){
        parent::__construct(
            $myconfig['company']['username'],
            $myconfig['company']['password'],
            $myconfig['company']['database']
        );
    }
}