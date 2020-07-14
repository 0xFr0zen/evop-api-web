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
    public static $UPDATE_ERROR = "Couldn't update company settings!";
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
    /**
     * Check if the company exists on the database
     * 
     * @return bool
     */
    public function exists():bool
    {
        $res = false;
        $dbconn = new MyCompanyDBConnector();
        $res = $dbconn->check(
            Queries::get("company","exists"),
            $this->name
        );
        return $res;
    }
    /**
     * Creates the company on the database
     * 
     * The return value represents if the action is either done or has error.
     * 
     * @return array
     */
    public function create(int $tables = 1):array
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

    /**
     * Adds a table for reservations on the database
     * 
     * @return bool
     */
    public function addTable(string $name):bool
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

    /**
     * Updates a table name on the database
     * 
     * @return bool
     */
    public function updateTable(string $oldname, string $newname):bool
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

    /**
     * Removes a table name on the database
     * 
     * @return bool
     */
    public function removeTable(string $name):bool
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

    /**
     * Checks if the company is activated
     * 
     * @return bool
     */
    public function isActive():bool
    {
        $res = false;
        $dbconn = new MyCompanyDBConnector();
        $res = $dbconn->check(
            Queries::get('company','is-active'),
            $this->name
        );
        return $res;
    }

    /**
     * Deactivates the company
     * 
     * @return bool
     */
    public function deactivate():bool
    {
        $res = false;
        $dbconn = new MyCompanyDBConnector();
        $updated = $dbconn->update(
            Queries::get('company','deactivate'),
            $this->name
        );

        $res = array("status" => $updated);
        if (!$updated) {
            $res['message'] = Company::$UPDATE_ERROR;
        }
        return $res;
    }

    /**
     * Activates the company
     * 
     * @return bool
     */
    public function activate():bool
    {
        $res = false;
        $dbconn = new MyCompanyDBConnector();
        $updated = $dbconn->update(
            Queries::get('company','activate'),
            $this->name
        );

        $res = array("status" => $updated);
        if (!$updated) {
            $res['message'] = Company::$UPDATE_ERROR;
        }
        return $res;
    }

    /**
     * Reads out information from the database
     * 
     * @param bool $detailed
     * 
     * @return array
     */
    public function information($detailed = false):array
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

    /**
     * Returns the amount of users connected to the company
     * @return array
     */
    public function getUsersCount():array
    {
        $result = array();
        $dbconn = new MyCompanyDBConnector();
        $result = $dbconn->query(
            Queries::get('company','user-count')
        )->fetch_assoc();
        return $result;
    }


    /**
     * Returns the amount of products
     * @return array
     */
    public function getProductsCount():array
    {
        $result = array();
        $dbconn = new MyCompanyDBConnector();
        $result = $dbconn->query(
            Queries::get('company','product-count'),
            $this->name
        )->fetch_assoc();
        return $result;
    }

    /**
     * Returns the colors from the company-configuration
     * @return array
     */
    private function getColors():array
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

    /**
     * Returns the strings from the company-configuration
     * @return array
     */
    private function getStrings():array
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

    /**
     * Returns the textstyles from the company-configuration
     * @return array
     */
    private function getTextStyles():array
    {
        $result = array();
        $dbconn = new MyCompanyDBConnector();
        $resultTextStyles = $dbconn->query(
            Queries::get('company','read-textstyles'),
            $this->name
        );
        while (($r = $resultTextStyles->fetch_assoc()) != null) {
            $r['name'] = str_replace($this->name."-", "", $r['name']);
            $r['color'] = str_replace($this->name."-", "", $r['color']);
            $r['background-color'] = str_replace($this->name."-", "", $r['background-color']);
            array_push($result, $r);
        }

        return $result;
    }

    /**
     * Returns the company-configurations
     * @param mixed ...$types
     * 
     * @return array
     */
    public function getConfiguration(...$types):array
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

    /**
     * Adds a company-configuration
     * @param mixed $type 'color','string','textstyle'
     * @param mixed $name the name of the resource
     * @param mixed $value values for the resource
     * 
     * @return array
     */
    public function addConfiguration($type, $name, $value):array
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

    /**
     * Updates a company-configuration
     * 
     * @param mixed $type 'color','string','textstyle'
     * @param mixed $oldname the old name of the resource
     * @param mixed $name the name of the resource
     * @param mixed $value values for the resource
     * 
     * @return array
     */
    public function updateConfiguration($type, $oldname, $name, $value):array
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

    /**
     * Removes a company-configuration
     * @param mixed $type 'color','string','textstyle'
     * @param mixed $name the name of the resource
     * 
     * @return array
     */
    public function deleteConfiguration($type, $name):array
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

    /**
     * Adds a product  to the company.
     * @param string $name
     * @param string|null $description
     * @param mixed $price
     * @param string $group
     * @param string|null $subgroup
     * 
     * @return bool
     */
    public function addProduct(string $name, string $description = null, $price, string $group, string $subgroup = null):bool
    {
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


    /**
     * Adds a product group to the company
     * @param string $name
     * @param string|null $icon
     * 
     * @return bool
     */
    public function addProductGroup(string $name, string $icon = null):bool
    {
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

    /**
     * Adds product subgroup.
     * @param string $name subgroup name
     * 
     * @return bool
     */
    public function addProductSubgroup(string $name):bool
    {
        $result = false;
        
        $dbconn = new MyCompanyDBConnector();
        $result = $dbconn->insert(
            Queries::get('products','add-subgroup'),
            $name
        );
        return $result;
    }

    /**
     * Returns all products (from the given group)
     * @param int $groupid 
     * 
     * @return array
     */
    public function getProducts($groupid = -1):array
    {
        $result = array();
        $dbconn = new MyCompanyDBConnector();
        if(is_numeric($groupid) && $groupid == -1){
            $res = $dbconn->query(Queries::get('company','products'),
                $this->name
            );
        }else if(is_numeric($groupid) && $groupid != -1){
            $res = $dbconn->query(Queries::get('company','products-from-group'),
                $groupid,
                $this->name
            );
        }else if(is_string($groupid)){
            $res = $dbconn->query(Queries::get('company','products-from-group-like'),
                "%".$groupid."%",
                $this->name
            );
        }
        
        while(($row = $res->fetch_assoc()) != null){
            array_push($result, $row);
        }
        return $result;
    }
    
    /**
     * Returns product-groups (or if given group-name, likewise product-groups)
     * @param string $name 
     * 
     * @return array
     */
    public function getProductGroups(string $name = ""):array
    {
        $result = array();
        $dbconn = new MyCompanyDBConnector();
        if($name === ""){
            $res = $dbconn->query(
                Queries::get('company','product-groups'),
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
    // public function getProductSubgroups(string $groupname){
    //     $productgroup = new ProductGroup($groupname);
    //     return $productgroup->getSubgroups();
    // }


    /**
     * Returns likewise-companies
     * @param string $name
     * 
     * @return array
     */
    public static function find(string $name):array
    {
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
/**
 * [Description Product]
 * Product class with static error messages
 * 
 */
class Product {
    public static $PRODUCT_EXISTS_ALREADY = "This product exists already.";
    public static $PRODUCT_VALUES_MISSING = "Please put in some product values.";
    public static $PRODUCTNAME_MISSING = "Please put in a product name.";
    public static $PRODUCTGROUP_EXISTS_ALREADY = "This product-group exists already.";
    public static $PRODUCTSUBGROUP_EXISTS_ALREADY = "This product-subgroup exists already.";
    public static $COULDNT_CONNECT_GROUP_TO_PRODUCT = "Couldn't connect the product-group to the product.";
    public static $COULDNT_CONNECT_SUBGROUP_TO_PRODUCT = "Couldn't connect the product-subgroup to the product.";
}

/**
 * [Description ProductGroup]
 * Functions for product groups
 */
class ProductGroup {
    private $name;
    private $products = array();
    private $subgroups = array();
    private $companyname;
    private $icon;    

    /**
     * Constructor
     * @param string $companyname
     * @param string $name
     */
    function __construct(string $companyname, string $name){
        $this->name = $name;
        $this->companyname = $companyname;
        if($this->exists()){
            $this->products = $this->loadProducts();
            $this->icon = $this->loadIcon();
            $this->subgroups = $this->loadSubgroups();
        }
    }

    /**
     * Sets the icon of the group
     * @param string $name
     * 
     * @return void
     */
    public function setIcon(string $name):void
    {
        $this->icon = $name;
    }
    
    /**
     * Returns the icon-name
     * @return string
     */
    public function getIcon():string
    {
        return $this->icon;
    }

    /**
     * Returns the products of the group
     * @return array
     */
    public function getProducts():array
    {
        return $this->products;
    }


    /**
     * Checks if the product-group exists or not
     * @return bool
     */
    public function exists():bool
    {
        $result = false;
        $dbconn = new MyCompanyDBConnector();
        $result = $dbconn->check(Queries::get('company', 'has-productgroup'));
        return $result;
    }


    /**
     * Creates the product-group
     * @return bool
     */
    public function create():bool
    {
        $result = false;
        $dbconn = new MyCompanyDBConnector();
        $result = $dbconn->insert(Queries::get('company', 'add-product-group'));
        return $result;
    }

    
    /**
     * Loads all products
     * @return array
     */
    private function loadProducts():array
    {
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


    /**
     * Loads Icon-name
     * @return string
     */
    private function loadIcon():string
    {
        $dbconn = new MyCompanyDBConnector();
        $res = $dbconn->query(
            Queries::get('product', 'product-group-icon'),
            $this->name,
            $this->companyname
        );
        $result = $res;
        return $result;
    }


    // /**
    //  * Loads Subgroups
    //  * @return array
    //  */
    // private function loadSubgroups():array
    // {
    //     $dbconn = new MyCompanyDBConnector();
    //     $result = array();
    //     $res = $dbconn->query(
    //         Queries::get('company', 'get-product-subgroups-from-product-group'),
    //         $this->name,
    //         $this->companyname
    //     );
    //     while(($row = $res->fetch_assoc()) != null){
    //         array_push($result, $row);
    //     }
    //     return $result;
    // }

    // public function addSubgroup(string $name):bool 
    // {
    //     $result = false;
    //     $dbconn = new MyCompanyDBConnector();
    //     if(!$dbconn->check(Queries::get('company', 'product-has-subgroup'))){
    //         $dbconn->insert(Queries::get('company', 'add-product-subgroup'), $this);
    //     }
    // }
    // public function getSubgroups(){
    //     return $this->subgroups;
    // }
    

}