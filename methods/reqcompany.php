<?php
include_once __DIR__.'/../utils/mydb.php';
include_once __DIR__.'/../utils/company.php';
class ReqCompany {
    protected $company;
    protected $mode;
    protected $details;
    public $exists;
    public $result;
    public function __construct(){
        
        $this->mode = $_REQUEST['mode'];
        $this->details = $_REQUEST['details'];
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
            if(isset($_REQUEST['company'])){
                $comp = $_REQUEST['company'];
                $this->company = new Company($comp);
                $this->exists = $this->company->exists();
                if(!$this->exists){
                    $this->result = array("error" => Company::$COMPANY_NONEXISTING);
                    die(json_encode($this->result, JSON_NUMERIC_CHECK));
                }
            }else {
                $this->result = array("error" => Company::$SPECIFY_A_COMPANYNAME);
                die(json_encode($this->result, JSON_NUMERIC_CHECK));
            }
            
        }
    }
}
