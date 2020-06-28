<?php
include_once __DIR__.'/../requirements.php';

class ReqMethod extends ReqCompany implements ReqInterface {
    public function execute(){
        print("test");
        switch ($this->mode) {
            case 'create':
                if (!$this->exists) {
                    $c = $this->company;
                    $created = $c->create();
                    $this->result = array("result" => array("created" => $created));
                } else {
                    $this->result = array("error" => Company::$COMPANY_ALREADY_EXISTS);
                }
                break;
            case 'configuration':
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
                    $valOldName = $val['oldname'];
                    unset($val['name']);
                    unset($val['oldname']);
                    $valObj = array("name" => $valName, "oldname" => $valOldName, "values" => $val);
                    $updated = $this->company->updateConfiguration($this->details, $valObj['oldname'], $valObj['name'], $valObj['values']);
                    $this->result = array("result" => array("updated" => $updated));
                }
                break;
            case 'tables':
                if ($this->details < 1) {
                    $this->result = array("error" => "you need to put a number >= 1");
                } else {

                    $updated = $this->company->updateTables($this->details);
                    $this->result = array("result" => array("updated" => $updated));
                }
                break;
            default:
                $this->result = array("error" => "you need to specify a mode");
                break;
        }
    }
}

