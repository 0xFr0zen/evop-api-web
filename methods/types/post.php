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
                if ($this->exists) {
                    if($this->details !== null && count($this->details) > 0){
                        if(!isset($this->values) || empty($this->values)){
                            $this->result = array("result" => array("error" => Product::$PRODUCT_VALUES_MISSING));
                        }else {
                            $created = $this->company->addProduct($this->details, $this->values);
                            $this->result = array("result" => array("created" => $created));

                        }
                    }else {
                        $this->result = array("result" => array("error" => Product::$PRODUCTNAME_MISSING));
                    }
                } else {
                    $this->result = array("error" => Company::$COMPANY_NONEXISTING);
                }
                break;
            case 'configuration':
                if ($this->exists) {
                    if (!isset($this->values) || empty($this->values)) {
                        $this->result = array("error" => "you need to put values");
                    } else {
                        $valName = $this->values['name'];
                        unset($this->values['name']);
                        $valObj = array("name" => $valName, "values" => $this->values);
                        $added = $this->company->addConfiguration($this->details, $valObj['name'], $valObj['values']);
                        if (isset($added["result"])) {
                            if($added["result"]["added"]){
                                $this->result = array("result" => array("added" => $added["result"]["added"]));
                            }else {
                                $this->result = array("result" => array("error" => array("message" => Company::$CONFIGURATION_ALREADY_EXISTS)));
                            }
                        } else if(isset($added["error"])){
                            $this->result = array("result" => array("error" => $added["error"]));
                        }else {
                            $this->result = array("result" => array("error" => array("message" => Company::$UNEXPECTED_ERROR)));
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
                        if(!isset($this->values) || empty($this->values)){
                            $this->result = array("error" => Company::$COMPANY_NEW_TABLENAME_MISSING);
                        }else {
                            $table = $this->company->updateTable($this->details, $this->values[0]);
                            $this->result = array("result" => array("updated" => $table));
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

