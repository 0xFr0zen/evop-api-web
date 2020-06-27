<?php
include_once 'resource.php';

include_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'DB.php';

class Color extends Resourcer
{

    public function add($value): bool
    {
        $result = false;
        if (
            (!isset($value['r']) || !isset($value['g']) || !isset($value['b']) || !isset($value['a'])) &&
            ($value['r'] == null || $value['g'] == null || $value['b'] == null || $value['a'] == null)
        ) {
            throw new Exception("You need to pass all values: r, g, b, a", 1);
        }
        $dbconn = new DBConnector();
        $sql = "INSERT INTO color(`name`, r, g, b, a) VALUES
            (
                ?,
                ?,
                ?,
                ?,
                ?
            )";
        $sqlCheck = "SELECT * FROM color WHERE `name` = ?";
        if ($dbconn->check($sqlCheck, $this->resourcename)) {
            $result = false;
        } else {
            $result = $dbconn->insert($sql, $this->resourcename, $value['r'], $value['g'], $value['b'], $value['a']);
            if ($result) {
                $sqlConnectColorWithCompany = "INSERT INTO company_has_color(color_id,company_id)
                VALUES
                (
                    (SELECT color.id FROM color WHERE color.name = ?),
                    (SELECT company.id FROM company WHERE company.name = ?)
                )";
                $result = $dbconn->insert($sqlConnectColorWithCompany, $this->resourcename, $this->companyname);
            }
        }
        return $result;
    }
    public function upd($oldname, $value): bool
    {
        if (!isset($value['r']) && !isset($value['g']) && !isset($value['b']) && !isset($value['a'])) {
            return false;
        }
        $stpos = strpos($this->companyname, $oldname);
        if (gettype($stpos) === "boolean" && $stpos == false) {
            $oldname = $this->companyname . "_" . $oldname;
        }

        $dbconn = new DBConnector();
        $sql = "UPDATE color SET `name` = ?, r = ?, g = ?, b = ?, a = ? WHERE `name` = ?";
        return $dbconn->update($sql, $this->resourcename, $value['r'], $value['g'], $value['b'], $value['a'], $oldname);
    }
    public function del(): bool
    {
        $dbconn = new DBConnector();
        $sql = "DELETE FROM color WHERE `name` = ?";
        return $dbconn->deleteRow($sql, $this->resourcename);
    }

}
