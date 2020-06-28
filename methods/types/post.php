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
            case 'tables':
                if ($this->exists) {
                    if ($this->details < 1) {
                        $this->result = array("error" => "you need to put a number >= 1");
                    } else {

                        $updated = $this->company->updateTables($this->details);
                        $this->result = array("result" => array("updated" => $updated));
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

