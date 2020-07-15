<?php
include_once __DIR__.'/../requirements.php';
class ReqMethod extends ReqUser implements ReqInterface {
    public function execute(){
        switch ($this->mode) {
            case 'lookup':
                $this->result = array("result" => true);
                break;
            case 'information':
                if ($this->exists) {
                    switch ($this->details) {
                        case 'minimum':
                            $information = $this->company->information();
                            $this->result = array("result" => $information);
                            break;
                        case 'all':
                            $information = $this->company->information(true);
                            $this->result = array("result" => $information);
                            break;
                        case 'users':
                            $information = $this->company->getUsersCount(true);
                            $this->result = array("result" => $information);
                            break;
                        default:
                            $information = $this->company->information();
                            $this->result = array("result" => $information);
                            break;
                    }
                } else {
                    $this->result = array("error" => Company::$COMPANY_NONEXISTING);
                }
                break;
            case 'configuration':
                if ($this->exists) {
                    switch ($this->details) {
                        case 'colors':
                            $configurationColor = $this->company->getConfiguration('colors');
                            $this->result = array("result" => $configurationColor);
                            break;
                        case 'textstyles':
                            $configurationTextstyle = $this->company->getConfiguration('textstyles');
                            $this->result = array("result" => $configurationTextstyle);
                            break;
                        case 'strings':
                            $configurationStrings = $this->company->getConfiguration('strings');
                            $this->result = array("result" => $configurationStrings);
                            break;
                        default:
                            $configuration = $this->company->getConfiguration();
                            $this->result = array("result" => $configuration);
                            break;
                    }
                } else {
                    $this->result = array("error" => Company::$COMPANY_NONEXISTING);
                }
                break;
            case 'amount':
                if ($this->exists) {
                    switch ($this->details) {
                        case 'users':
                            $users = $this->company->getUsersCount();
                            $this->result = array("result" => array("users" => array("amount" => $users['amount'])));
                            break;
                        case 'products':
                            $products = $this->company->getProductsCount();
                            $this->result = array("result" => array("products" => array("amount" => $products['amount'])));
                            break;
                        default:
                            $this->result = array("result" => array("error" => Company::$SPECIFY_A_VALUE_ERROR));
                            break;
                    }
                } else {
                    $this->result = array("error" => Company::$COMPANY_NONEXISTING);
                }
                break;
            
            case 'list':
                if ($this->exists) {
                    switch ($this->details) {
                        case 'product-groups':
                            $productsGroups = $this->company->getProductGroups();
                            $this->result = array("result" => array("product-groups" => $productsGroups));
                            break;
                        // case 'product-subgroups':
                        //     $productsSubgroups = $this->company->getProductSubgroups();
                        //     $this->result = array("result" => array("product-subgroups" => $productsSubgroups));
                        //     break;
                        case 'products':
                            if(!isset($this->values) || (isset($this->values) && empty($this->values))){
                                $products = $this->company->getProducts();
                                $this->result = array("result" => array("all-products" => $products));
                            }else {
                                $products = $this->company->getProducts($this->values[0]);
                                $this->result = array("result" => array("products-from-group" => $products));
                            }
                            break;
                        default:
                            $this->result = array("result" => array("error" => Company::$SPECIFY_A_VALUE_ERROR));
                            break;
                    }
                } else {
                    $this->result = array("error" => Company::$COMPANY_NONEXISTING);
                }
                break;
            
            default:
                $this->result = array("error" => "you need to specify a query");
                break;
        }
        
    }
}

