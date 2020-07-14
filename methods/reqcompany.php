<?php
include_once __DIR__.'/../utils/mydb.php';
include_once __DIR__.'/../utils/company.php';

/**
 * [Description ReqCompany]
 * Requirements of the company
 */
class ReqCompany {

    protected Company $company;
    protected string $mode;
    protected string $details;
    protected array $values;
    private string $comp;
    public bool $exists;
    public $result;
    public function __construct(){
        $this->mode = $_REQUEST['mode'];
        $this->details = $_REQUEST['details'];
        $this->values = $this->valuesParser($_REQUEST['values'] != null ? $_REQUEST['values'] : "");
        $this->comp = $_REQUEST['company'];
        $reqs = array_keys($_REQUEST);
        $found = false;
        foreach ($reqs as $key => $value) {
            if(!$found) {
                switch ($reqs[$key]) {
                    case 'companies':
                        $dbconn = new MyCompanyDBConnector();
                        $sql = Queries::get("companies", "list");
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
                    case 'resolver':
                        if(isset($_REQUEST['name'])){
                            $this->result = array("result" => Company::find($_REQUEST['name']));
                        }else {
                            $this->result = array("error" => Company::$SPECIFY_A_COMPANYNAME);
                        }
                        die(json_encode($this->result, JSON_NUMERIC_CHECK));
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
    public function valuesParser(string $data):array {
        $vals = array();
        $result = array();
        if( strpos( $data, ":" ) !== false && strpos( $data, "," ) !== false) {
            $values = explode(",", $data);
            foreach ($values as $value) {
                $splitted = explode(":", $value);
                $vals[$splitted[0]] = $splitted[1];
            }
            $result = json_decode(json_encode($vals, JSON_NUMERIC_CHECK), true);
        }else {
            if(is_numeric($data)){
                $result = array(intval($data));
            }else if(strpos( $data, "," ) !== false){
                $result = $values = explode(",", $data);
            }else {
                $result = array($data);
            }
        }
        
        return $result;
    }
}
