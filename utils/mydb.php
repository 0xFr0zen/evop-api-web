<?php
include_once __DIR__.'/../../essentials/DBConnector.php';

class MyAdminDBConnector extends DBConnector {
    private $json = null;
    private $myconfig = null;
    function __construct(){
        $json = json_decode(file_get_contents(__DIR__."/../../essentials/config.json"), true);
        $myconfig = $json['db']['connectors'];
        parent::__construct(
            $myconfig['admin']['username'],
            $myconfig['admin']['password'],
            $myconfig['admin']['database']
        );
    }
}
class MyCompanyDBConnector extends DBConnector {
    
    private $json = null;
    private $myconfig = null;
    function __construct(){
        $json = json_decode(file_get_contents(__DIR__."/../../essentials/config.json"), true);
        $myconfig = $json['db']['connectors'];
        parent::__construct(
            $myconfig['company']['username'],
            $myconfig['company']['password'],
            $myconfig['company']['database']
        );
    }
}
class MyUseryDBConnector extends DBConnector {
    
    private $json = null;
    private $myconfig = null;
    function __construct(){
        $json = json_decode(file_get_contents(__DIR__."/../../essentials/config.json"), true);
        $myconfig = $json['db']['connectors'];
        parent::__construct(
            $myconfig['user']['username'],
            $myconfig['user']['password'],
            $myconfig['user']['database']
        );
    }
}