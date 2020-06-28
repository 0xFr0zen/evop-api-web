<?php
include_once __DIR__.'/../utils/mydb.php';
include_once __DIR__.'/../utils/company.php';
class ReqCompany {
    protected $company;
    protected $mode;
    protected $details;
    protected $exists;
    protected $result;
    public function __construct(){
        if(isset($_REQUEST['companies'])){
            $dbconn = new MyCompanyDBConnector();
            $sql = "SELECT `name`, `tables` FROM company";
            $resultCompanies = $dbconn->query($sql);
            $resulter = array();
            while ($r = $resultCompanies->fetch_assoc()) {
                array_push($resulter, $r);
            }
            $this->result = array("result" => $resulter);
            die(json_encode($this->result, JSON_NUMERIC_CHECK));
        }else {
            $comp = $_REQUEST['company'];
            $this->company = new Company($comp);
            $this->mode = $_REQUEST['mode'];
            $this->details = $_REQUEST['details'];
            $this->exists = $this->company->exists();
            if(!$this->exists){
                $this->result = array("error" => Company::$COMPANY_NONEXISTING);
                die(json_encode($this->result, JSON_NUMERIC_CHECK));
            }
        }
    }
}
