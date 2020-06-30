<?php
include_once __DIR__.'/../requirements.php';

class ReqMethod extends ReqCompany implements ReqInterface {
    public function execute(){
        switch ($this->mode) {
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
                        $valOldName = $val['oldname'];
                        unset($val['name']);
                        unset($val['oldname']);
                        $valObj = array("name" => $valName, "oldname" => $valOldName, "values" => $val);
                        $updated = $this->company->updateConfiguration($this->details, $valObj['oldname'], $valObj['name'], $valObj['values']);
                        $this->result = array("result" => array("updated" => $updated));
                    }
                }else {
                    $this->result = array("error" => Company::$COMPANY_NONEXISTING);
                }
                break;
            case 'deactivate':
                if ($this->exists) {
                    $deactivated = $this->company->deactivate();
                    $this->result = array("result" => array("deactivated" => $deactivated));
                }else {
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
                }else {
                    $this->result = array("error" => Company::$COMPANY_NONEXISTING);
                }
                break;
            default:
                $this->result = array("error" => "you need to specify a mode");
                break;
        }
    }
}