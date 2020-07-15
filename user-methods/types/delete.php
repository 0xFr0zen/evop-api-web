<?php
include_once __DIR__.'/../requirements.php';

class ReqMethod extends ReqUser implements ReqInterface {
    public function execute(){
        switch ($this->mode) {
            case 'configuration':
                if ($this->exists) {
                    if (!isset($this->values) || empty($this->values)) {
                        $this->result = array("error" => "you need to put values");
                    } else {
                        if (!isset($this->values['name'])) {
                            $this->result = array("error" => "you need to specify a resource-name");
                            break;
                        }
                        $configurationDeleted = $this->company->deleteConfiguration($this->details, $this->values['name']);
                        $this->result = array("result" => $configurationDeleted);

                    }
                } else {
                    $this->result = array("error" => Company::$COMPANY_NONEXISTING);
                }
                break;
            default:
                
                break;
        }
    }
}