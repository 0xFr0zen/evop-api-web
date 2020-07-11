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
            $r['name'] = str_replace($this->name."-", "", $r['name']);
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
            $r['name'] = str_replace($this->name."-", "", $r['name']);
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
            $r['name'] = str_replace($this->name."-", "", $r['name']);
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
    public function addProduct(string $name, string $description = null, $price, string $group, string $subgroup = null){
        $result = false;
        $dbconn = new MyCompanyDBConnector();
        $result = $dbconn->insert(
            Queries::get('company','add-product'),
            $name,
            $description,
            doubleval($price),
            $this->name
        );

        if($result){
            $result &= $this->addProductGroup($group);
            if($result){
                $result &= $dbconn->insert(
                    Queries::get('company','product-connect-group'),
                    $name,
                    $group,
                    $this->name
                );
                if($result && $subgroup != null){
                    $result &= $this->addProductSubroup($subgroup);
                    if($result){
                        $result &= $dbconn->insert(
                            Queries::get('company','product-connect-subgroup'),
                            $name,
                            $subgroup,
                            $this->name
                        );
                        if(!$result){
                            throw new Exception(Product::$COULDNT_CONNECT_SUBGROUP_TO_PRODUCT, 1);
                        }
                    }else {
                        throw new Exception(Product::$PRODUCTSUBGROUP_EXISTS_ALREADY, 1);
                    }
                }else {
                    if(!$result){
                        throw new Exception(Product::$COULDNT_CONNECT_GROUP_TO_PRODUCT, 1);
                    }
                }
            }else {
                throw new Exception(Product::$PRODUCTGROUP_EXISTS_ALREADY, 1);
            }
        }else {
            throw new Exception(Product::$PRODUCT_EXISTS_ALREADY, 1);
        }
        return $result;
    }
    public function addProductGroup(string $name, string $icon = null){
        $result = false;
        
        $pg = new ProductGroup($this->name, $name);
        if(!$pg->exists()){
            if($icon != null){
                $pg->setIcon($icon);
            }
            $pg->create();

        }
        return $result;
    }
    public function addProductSubgroup(string $name){
        $result = false;
        
        $dbconn = new MyCompanyDBConnector();
        $result = $dbconn->insert(
            Queries::get('products','add-subgroup'),
            $name
        );
        return $result;
    }
    public function getProducts(){
        $result = array();
        $dbconn = new MyCompanyDBConnector();
        $res = $dbconn->query(Queries::get('products','all-from-company'),
            $this->name
        );
        while(($row = $res->fetch_assoc()) != null){
            array_push($result, $row);
        }
        return $result;
    }
    
    public function getProductGroups($name = ""){
        $result = array();
        $dbconn = new MyCompanyDBConnector();
        if($name === ""){
            $res = $dbconn->query(
                Queries::get('products','get-groups'),
                $this->name
            );
            while(($row = $res->fetch_assoc()) != null){
                array_push($result, $row);
            }
        }else {
            $preparedname = "%".$name."%";
            $res = $dbconn->query(
                Queries::get('products','get-groups-like'),
                $preparedname,
                $this->name
            );
            while(($row = $res->fetch_assoc()) != null){
                array_push($result, $row);
            }
        }
        return $result;
    }
    public function getProductSubgroups(string $groupname){
        $productgroup = new ProductGroup($groupname);
        return $productgroup->getSubgroups();
    }
    public static function find(string $name){
        $result = array();
        $dbconn = new MyCompanyDBConnector();
        $preparedname = "%".$name."%";
        $res = $dbconn->query(Queries::get('company', 'resolver'),
            $preparedname
        );
        while(($row = $res->fetch_assoc()) != null){
            array_push($result, $row);
        }
        return $result;

    }
}
class Product {
    public static $PRODUCT_EXISTS_ALREADY = "This product exists already.";
    public static $PRODUCTGROUP_EXISTS_ALREADY = "This product-group exists already.";
    public static $PRODUCTSUBGROUP_EXISTS_ALREADY = "This product-subgroup exists already.";
    public static $COULDNT_CONNECT_GROUP_TO_PRODUCT = "Couldn't connect the product-group to the product.";
    public static $COULDNT_CONNECT_SUBGROUP_TO_PRODUCT = "Couldn't connect the product-subgroup to the product.";
}

class ProductGroup {
    private $name;
    private $products = array();
    private $subgroups = array();
    private $companyname;
    private $icon;    

    function __construct(string $companyname, string $name){
        $this->name = $name;
        $this->companyname = $companyname;
        if($this->exists()){
            $this->products = $this->loadProducts();
            $this->icon = $this->loadIcon();
            $this->subgroups = $this->loadSubgroups();
        }
    }
    public function setIcon(string $name){
        $this->icon = $name;
    }
    
    public function getIcon(){
        return $this->icon;
    }

    public function getProducts(){
        return $this->products;
    }
    public function exists(){
        $result = false;
        $dbconn = new MyCompanyDBConnector();
        $result = $dbconn->check(Queries::get('company', 'has-productgroup'));
        return $result;
    }
    public function create(){
        $result = false;
        $dbconn = new MyCompanyDBConnector();
        $result = $dbconn->insert(Queries::get('company', 'add-product-group'));
        return $result;
    }
    private function loadProducts(){
        $dbconn = new MyCompanyDBConnector();
        $result = array();
        $res = $dbconn->query(
            Queries::get('company', 'get-products-from-product-group'),
            $this->name,
            $this->companyname
        );
        while(($row = $res->fetch_assoc()) != null){
            array_push($result, $row);
        }
        return $result;
    }
    private function loadIcon(){
        $dbconn = new MyCompanyDBConnector();
        $res = $dbconn->query(
            Queries::get('product', 'product-group-icon'),
            $this->name,
            $this->companyname
        );
        $result = $res;
        return $result;
    }
    private function loadSubgroups(){
        $dbconn = new MyCompanyDBConnector();
        $result = array();
        $res = $dbconn->query(
            Queries::get('company', 'get-product-subgroups-from-product-group'),
            $this->name,
            $this->companyname
        );
        while(($row = $res->fetch_assoc()) != null){
            array_push($result, $row);
        }
        return $result;
    }
    public function addSubgroup(){
        $result = false;
        $dbconn = new MyCompanyDBConnector();
        if(!$dbconn->check(Queries::get('company', 'product-has-subgroup'))){
            $dbconn->insert(Queries::get('company', 'add-product-subgroup'));
        }
    }
    public function getSubgroups(){
        return $this->subgroups;
    }
    

}