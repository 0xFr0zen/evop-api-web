<?php
include_once './utilsmydb.php';
include_once './utils/color.php';
include_once './utils/stringer.php';
include_once './utils/textstyle.php';

class Company
{
    private $name = "";

    public static $COMPANY_NONEXISTING = "Company doesnt exists!";
    public static $COMPANY_EXISTING = "Company exists!";
    public static $COMPANY_ALREADY_EXISTS = "Company already exists!";
    public static $REMOVE_ERROR = "Couldn't remove company!";
    public static $CONFIGURATION_ALREADY_EXISTS = "Ressource already exists!";
    public function __construct(string $name)
    {
        $this->name = $name;
    }
    public function exists()
    {
        $res = false;
        $dbconn = new MyCompanyDBConnector();
        $res = $dbconn->check(
            "SELECT * FROM company WHERE company.name = ?",
            $this->name
        );
        return $res;
    }
    public function create(int $tables = 1)
    {
        $res = array();
        $dbconn = new MyCompanyDBConnector();
        $created = $dbconn->insert(
            "INSERT INTO company(`name`, `tables`) values(?, ?)",
            $this->name,
            $tables
        );

        $res = array("status" => $created);
        if (!$created) {
            $res['message'] = Company::$COMPANY_ALREADY_EXISTS;
        }
        return $res;
    }
    public function updateTables(int $tables = 1)
    {
        $res = false;
        $dbconn = new MyCompanyDBConnector();
        $res = $dbconn->update(
            "UPDATE company SET `tables` = ? WHERE company.name = ?",
            $tables,
            $this->name
        );

        return $res;
    }
    public function remove()
    {
        $res = false;
        $dbconn = new MyCompanyDBConnector();
        $removed = $dbconn->deleteRow(
            "DELETE FROM company WHERE company.name = ?",
            $this->name
        );

        $res = array("status" => $removed);
        if (!$removed) {
            $res['message'] = Company::$REMOVE_ERROR;
        }
        return $res;
    }
    public function information()
    {
        $res = false;
        $dbconn = new MyCompanyDBConnector();
        $res = $dbconn->query(
            "SELECT `name`,`tables` FROM company WHERE company.name = ?",
            $this->name
        )->fetch_assoc();
        return $res;
    }
    public function getUsersCount()
    {
        $result = array();
        $dbconn = new MyCompanyDBConnector();
        $result = $dbconn->query(
            "SELECT count(user.id) as 'amount'
            FROM user, company, company_has_user
            WHERE user.id = company_has_user.user_id
            AND company.id = company_has_user.company_id"
        )->fetch_assoc();
        return $result;
    }
    public function getProductsCount()
    {
        $result = array();
        $dbconn = new MyCompanyDBConnector();
        $result = $dbconn->query(
            "SELECT count(product.id) as 'amount'
            FROM product, company
            WHERE company.id = product.company_id"
        )->fetch_assoc();
        return $result;
    }
    private function getColors()
    {
        $result = array();
        $dbconn = new MyCompanyDBConnector();
        $sqlcolor = "SELECT color.name as 'name', color.r as 'r', color.g as 'g', color.b as 'b', color.a as 'a'
                FROM color, company_has_color, company
                WHERE company_has_color.company_id = company.id
                AND company_has_color.color_id = color.id
                AND company.name = ?";
        $resultColors = $dbconn->query(
            $sqlcolor, $this->name
        );

        while ($r = $resultColors->fetch_assoc()) {
            array_push($result, $r);
        }

        return $result;
    }
    private function getStrings()
    {
        $result = array();
        $dbconn = new MyCompanyDBConnector();
        $sqlstrings = "SELECT `string`.`name` as 'name', `string`.`value` as 'value'
                FROM `string`, company_has_string, company
                WHERE company_has_string.company_id = company.id
                AND company_has_string.string_id = `string`.id
                AND company.name = ?";
        $resultStrings = $dbconn->query(
            $sqlstrings, $this->name
        );
        while ($r = $resultStrings->fetch_assoc()) {
            array_push($result, $r);
        }

        return $result;
    }
    private function getTextStyles()
    {
        $result = array();
        $dbconn = new MyCompanyDBConnector();
        $sqltextstyle = "SELECT textstyle.name as 'name', textstyle.fontsize as 'fontsize', textstyle.fontfamily as 'fontfamily', textstyle.fontweight as 'fontweight'
                FROM textstyle, company_has_textstyle, company
                WHERE company_has_textstyle.company_id = company.id
                AND company_has_textstyle.textstyle_id = textstyle.id
                AND company.name = ?";

        $resultTextStyles = $dbconn->query(
            $sqltextstyle, $this->name
        );
        while ($r = $resultTextStyles->fetch_assoc()) {
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
        }
        return $result;
    }
}
