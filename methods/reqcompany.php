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
        $this->mode = $_REQUEST['mode'];
        $this->details = $_REQUEST['details'];
        $this->comp = $_REQUEST['company'];
        $reqs = array_keys($_REQUEST);
        $found = false;
        foreach ($reqs as $key => $value) {
            if(!$found) {
                switch ($reqs[$key]) {
                    case 'companies':
                        $dbconn = new MyCompanyDBConnector();
                        $sql = "SELECT company.`name` as `name`, COUNT(company_table.id) as `tables` FROM company LEFT JOIN company_table ON company.id = company_table.company_id";
                        $resultCompanies = $dbconn->query($sql);
                        $resulter = array();
                        while (($r = $resultCompanies->fetch_assoc()) != null) {
                            array_push($resulter, $r);
                        }
                        $this->result = array("result" => $resulter);
                        $found = true;
                        die(json_encode($this->result, JSON_NUMERIC_CHECK));
                        break;
                    case 'company':
                        $this->company = new Company($this->comp);
                        $this->exists = $this->company->exists();
                        $found = true;
                        break;
                    
                    default:
                        $this->result = array("error" => Company::$SPECIFY_A_COMPANYNAME);
                        $found = true;
                        die(json_encode($this->result, JSON_NUMERIC_CHECK));
                        break;
                }
            }
        }
        
        
        
    }
}
