<?php
include_once __DIR__.'/../requirements.php';

class ReqMethod extends ReqCompany implements ReqInterface {
    public function execute(){
        switch ($this->mode) {
            case 'create':
                if (!$this->exists) {
                    $created = $this->company->create();
                    $this->result = array("result" => array("created" => $created));
                } else {
                    $this->result = array("error" => Company::$COMPANY_ALREADY_EXISTS);
                }
                break;
            case 'add-product':
                if (!$this->exists) {
                    if($this->details !== null && count($this->details) > 0){
                        $values = explode(",", $_REQUEST['values']);
                        $vals = array();
                        foreach ($values as $value) {
                            $splitted = explode(":", $value);
                            $vals[$splitted[0]] = $splitted[1];
                        }
                        $val = json_decode(json_encode($vals, JSON_NUMERIC_CHECK), true);
                        
                        $created = $this->company->addProduct($this->details, $val);
                        $this->result = array("result" => array("created" => $created));
                    }else {
                        $error = array("error" => "You need to put a productname.");
                    }
                    
                } else {
                    $this->result = array("error" => Company::$COMPANY_ALREADY_EXISTS);
                }
                break;
            case 'configuration':
                if ($this->exists) {
                    if (!isset($_REQUEST['values'])) {
                        $this->result = array("error" => "you need to put values");
                    } else {
                        $values = explode(",", $_REQUEST['values']);
                        $vals = array();
                        foreach ($values as $value) {
                            $splitted = explode(":", $value);
                            $vals[$splitted[0]] = $splitted[1];
                        }
                        $val = json_decode(json_encode($vals, JSON_NUMERIC_CHECK), true);
                        $valName = $val['name'];
                        unset($val['name']);
                        $valObj = array("name" => $valName, "values" => $val);
                        $added = $this->company->addConfiguration($this->details, $valObj['name'], $valObj['values']);
                        if ($added) {
                            $this->result = array("result" => array("added" => $added));
                        } else {
                            $this->result = array("result" => array("added" => $added, "message" => Company::$CONFIGURATION_ALREADY_EXISTS));

                        }
                    }
                } else {
                    $this->result = array("error" => Company::$COMPANY_NONEXISTING);
                }
                break;
            case 'create-table':
                if ($this->exists) {
                    if (empty($this->details)) {
                        $this->result = array("error" => Company::$COMPANY_TABLENAME_MISSING);
                    } else {

                        $table = $this->company->addTable($this->details);
                        $this->result = array("result" => array("added" => $table));
                    }
                } else {
                    $this->result = array("error" => Company::$COMPANY_NONEXISTING);
                }
                break;
            case 'remove-table':
                if ($this->exists) {
                    if (empty($this->details)) {
                        $this->result = array("error" => Company::$COMPANY_TABLENAME_MISSING);
                    } else {

                        $table = $this->company->removeTable($this->details);
                        $this->result = array("result" => array("removed" => $table));
                    }
                } else {
                    $this->result = array("error" => Company::$COMPANY_NONEXISTING);
                }
                break;
            case 'update-table':
                if ($this->exists) {
                    if (empty($this->details)) {
                        $this->result = array("error" => Company::$COMPANY_TABLENAME_MISSING);
                    } else {
                        if(!isset($_REQUEST['values'])){
                            $this->result = array("error" => Company::$COMPANY_NEW_TABLENAME_MISSING);
                        }else {
                            $table = $this->company->updateTable($this->details, $_REQUEST['values']);
                            $this->result = array("result" => array("removed" => $table));
                        }
                    }
                } else {
                    $this->result = array("error" => Company::$COMPANY_NONEXISTING);
                }
                break;
            default:
                $this->result = array("error" => "you need to specify a mode");
                break;
        }
    }
}

