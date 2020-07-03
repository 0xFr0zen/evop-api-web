<?php
include_once __DIR__.'/mydb.php';
include_once __DIR__.'/color.php';
include_once __DIR__.'/stringer.php';
include_once __DIR__.'/textstyle.php';
include_once __DIR__.'/../../essentials/queries.php';

class Company
{
    private $name = "";

    public static $COMPANY_NONEXISTING = "Company doesnt exists!";
    public static $COMPANY_EXISTING = "Company exists!";
    public static $COMPANY_ALREADY_EXISTS = "Company already exists!";
    public static $REMOVE_ERROR = "Couldn't remove company!";
    public static $CONFIGURATION_ALREADY_EXISTS = "Ressource already exists!";
    public static $SPECIFY_A_VALUE_ERROR = "Please specify a value first!";
    public static $SPECIFY_A_TYPE_ERROR = "Please specify a type first!";
    public static $SPECIFY_A_COMPANYNAME = "Please specify a companyname first!";
    public static $COMPANY_TABLENAME_MISSING = "You need to put a table name!";
    public static $COMPANY_NEW_TABLENAME_MISSING = "You need to put a NEW table name!";
    public static $UNEXPECTED_ERROR = "Sorry, something happend and we dont know quite yet why...";

    public function __construct(string $name)
    {
        $this->name = $name;
    }
    public function exists()
    {
        $res = false;
        $dbconn = new MyCompanyDBConnector();
        $res = $dbconn->check(
            Queries::get("company","exists"),
            $this->name
        );
        return $res;
    }
    public function create(int $tables = 1)
    {
        $res = array();
        $dbconn = new MyCompanyDBConnector();
        $created = $dbconn->insert(
            Queries::get('company', 'create'),
            $this->name
        );

        $res = array("status" => $created);
        if (!$created) {
            $res['message'] = Company::$COMPANY_ALREADY_EXISTS;
        }
        return $res;
    }
    public function addTable(string $name)
    {
        $res = false;
        $dbconn = new MyCompanyDBConnector();
        $res = $dbconn->insert(
            Queries::get('table', 'create'),
            $name,
            $this->name
        );
        return $res;
    }
    public function updateTable(string $oldname, string $newname)
    {
        $res = false;
        $dbconn = new MyCompanyDBConnector();
        $res = $dbconn->update(
            Queries::get('table', 'update'),
            $newname,
            $oldname,
            $this->name
        );

        return $res;
    }
    public function removeTable(string $name)
    {
        $res = false;
        $dbconn = new MyCompanyDBConnector();
        $res = $dbconn->deleteRow(
            Queries::get('table', 'remove'),
            $name,
            $this->name
        );
        return $res;
    }
    public function isActive()
    {
        $res = false;
        $dbconn = new MyCompanyDBConnector();
        $res = $dbconn->check(
            Queries::get('company','is-active'),
            $this->name
        );
        return $res;
    }
    public function deactivate()
    {
        $res = false;
        $dbconn = new MyCompanyDBConnector();
        $removed = $dbconn->update(
            Queries::get('company','deactivate'),
            $this->name
        );

        $res = array("status" => $removed);
        if (!$removed) {
            $res['message'] = Company::$REMOVE_ERROR;
        }
        return $res;
    }
    public function activate()
    {
        $res = false;
        $dbconn = new MyCompanyDBConnector();
        $removed = $dbconn->update(
            Queries::get('company','activate'),
            $this->name
        );

        $res = array("status" => $removed);
        if (!$removed) {
            $res['message'] = Company::$REMOVE_ERROR;
        }
        return $res;
    }
    public function information($detailed = false)
    {
        $res = array();
        $dbconn = new MyCompanyDBConnector();
        $res2 = null;
        if(!$detailed){
            $res2 = $dbconn->query(
                Queries::get('company','information-little'),
                $this->name
            );
            
        }else {
            $res2 = $dbconn->query(
                Queries::get('company','information-all'),
                $this->name
            );
        }
        if($res2 != null){
            $res = $res2->fetch_assoc();
        }else {
            $res = array("error" => Company::$UNEXPECTED_ERROR);
        }
        
        return $res;
    }
    public function getUsersCount()
    {
        $result = array();
        $dbconn = new MyCompanyDBConnector();
        $result = $dbconn->query(
            Queries::get('company','user-count')
        )->fetch_assoc();
        return $result;
    }
    public function getProductsCount()
    {
        $result = array();
        $dbconn = new MyCompanyDBConnector();
        $result = $dbconn->query(
            Queries::get('company','product-count'),
            $this->name
        )->fetch_assoc();
        return $result;
    }
    private function getColors()
    {
        $result = array();
        $dbconn = new MyCompanyDBConnector();
        $resultColors = $dbconn->query(
            Queries::get('company','read-colors'),
            $this->name
        );

        while (($r = $resultColors->fetch_assoc()) != null) {
            array_push($result, $r);
        }

        return $result;
    }
    private function getStrings()
    {
        $result = array();
        $dbconn = new MyCompanyDBConnector();
        $resultStrings = $dbconn->query(
            Queries::get('company','read-strings'),
            $this->name
        );
        while (($r = $resultStrings->fetch_assoc()) != null) {
            array_push($result, $r);
        }

        return $result;
    }
    private function getTextStyles()
    {
        $result = array();
        $dbconn = new MyCompanyDBConnector();
        $resultTextStyles = $dbconn->query(
            Queries::get('company','read-textstyles'),
            $this->name
        );
        while (($r = $resultTextStyles->fetch_assoc()) != null) {
            array_push($result, $r);
        }

        return $result;
    }
    public function getConfiguration(...$types)
    {
        $result = array();
        if (sizeof($types) > 0) {
            foreach ($types as $type) {
                switch ($type) {
                    case 'colors':
                        $result['colors'] = $this->getColors();
                        break;
                    case 'strings':

                        $result['strings'] = $this->getStrings();
                        break;
                    case 'textstyles':

                        $result['textstyles'] = $this->getTextStyles();
                        break;
                }
            }
        } else {
            $result['colors'] = $this->getColors();
            $result['strings'] = $this->getStrings();
            $result['textstyles'] = $this->getTextStyles();
        }

        return $result;
    }
    public function addConfiguration($type, $name, $value)
    {
        $result = array();
        switch ($type) {
            case 'color':
                $c = new Color($this->name, $name);
                $result = $c->add($value);
                break;
            case 'string':
                $s = new Stringer($this->name, $name);
                $result = $s->add($value);
                break;
            case 'textstyle':
                $tst = new TextStyle($this->name, $name);
                $result = $tst->add($value);
                break;
            default:
                $result = array("error" => array("message" => Company::$SPECIFY_A_TYPE_ERROR));
                break;
        }
        return $result;
    }
    public function updateConfiguration($type, $oldname, $name, $value)
    {
        $result = array();
        switch ($type) {
            case 'color':
                $c = new Color($this->name, $name);
                $result = $c->upd($oldname, $value);
                break;
            case 'string':
                $s = new Stringer($this->name, $name);
                $result = $s->upd($oldname, $value);
                break;
            case 'textstyle':
                $tst = new TextStyle($this->name, $name);
                $result = $tst->upd($oldname, $value);
                break;

            default:
                $result = array("error" => array("message" => Company::$SPECIFY_A_TYPE_ERROR));
                break;
        }
        return $result;
    }

    public function deleteConfiguration($type, $name)
    {
        $result = array();
        switch ($type) {
            case 'color':
                $c = new Color($this->name, $name);
                $result = $c->del();
                break;
            case 'string':
                $s = new Stringer($this->name, $name);
                $result = $s->del();
                break;
            case 'textstyle':
                $tst = new TextStyle($this->name, $name);
                $result = $tst->del();
                break;
            default:
                $result = array("error" => array("message" => Company::$SPECIFY_A_TYPE_ERROR));
                break;
        }
        return $result;
    }
    public function getProducts(){
        $result = array();
        $dbconn = new MyCompanyDBConnector();
        $res = $dbconn->query(Queries::get('company','products'),
            $this->name
        );
        while(($row = $res->fetch_assoc()) != null){
            array_push($row);
        }
        return $result;
    }
    public function getProductGroups(){
        $result = array();
        $dbconn = new MyCompanyDBConnector();
        $res = $dbconn->query(
            Queries::get('company','product-groups'),
            $this->name
        );
        while(($row = $res->fetch_assoc()) != null){
            array_push($row);
        }
        return $result;
    }
    public function getProductSubgroups(){
        $result = array();
        $dbconn = new MyCompanyDBConnector();
        $res = $dbconn->query(Queries::get('company','product-subgroups'),
            $this->name
        );
        while(($row = $res->fetch_assoc()) != null){
            array_push($row);
        }
        return $result;
    }
    public static function find(string $name){
        $result = array();
        $dbconn = new MyCompanyDBConnector();
        $res = $dbconn->query(Queries::get('company', 'resolver'),
            "%".$name."%"
        );
        while(($row = $res->fetch_assoc()) != null){
            array_push($row);
        }
        return $result;

    }
}
