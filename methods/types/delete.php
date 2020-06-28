<?php
include_once '../requirements.php';
class ReqMethod extends ReqCompany implements ReqInterface {
    public function execute(){
        switch ($this->mode) {
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
                    if (!isset($val['name'])) {
                        $this->result = array("error" => "you need to specify a resource-name");
                        break;
                    }
                    $configurationDeleted = $this->company->deleteConfiguration($this->details, $val['name']);
                    $this->result = array("result" => $configurationDeleted);
                }
                break;
            default:
                $removed = $this->company->remove();
                $this->result = array("result" => array("removed" => $removed));
                break;
        }
    }
}