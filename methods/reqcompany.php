<?php
include_once __DIR__.'/../utils/mydb.php';
include_once __DIR__.'/../utils/company.php';
class ReqCompany {
    protected $company;
    protected $mode;
    protected $details;
    private $comp;
    public $exists;
    public $result;
    public function __construct(){
        if(!isset($_REQUEST['company'])){
            $this->result = array("error" => Company::$SPECIFY_A_COMPANYNAME);
            die(json_encode($this->result, JSON_NUMERIC_CHECK));
        }else {
            $this->mode = $_REQUEST['mode'];
            $this->details = $_REQUEST['details'];
            $this->comp = $_REQUEST['company'];

            if(isset($_REQUEST['companies'])){
                $dbconn = new MyCompanyDBConnector();
                $sql = "SELECT `name`, `tables` FROM company";
                $resultCompanies = $dbconn->query($sql);
                $resulter = array();
                while (($r = $resultCompanies->fetch_assoc()) != null) {
                    array_push($resulter, $r);
                }
                $this->result = array("result" => $resulter);
                die(json_encode($this->result, JSON_NUMERIC_CHECK));
            }else {
                $this->company = new Company($this->comp);
                $this->exists = $this->company->exists();
            }
        }
        
    }
}
