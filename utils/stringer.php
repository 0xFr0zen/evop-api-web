<?php
include_once 'resource.php';
include_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'DB.php';

class Stringer extends Resourcer
{

    public function add($value): bool
    {
        $result = false;
        if (!isset($value['text']) || $value['text'] == null || gettype($value['text']) !== "string") {
            throw new Exception("Your need to pass a string value", 1);
        }
        $sql = "INSERT INTO `string`(`name`, `value`)
        VALUES
        (
            ?,
            ?
        )";
        $dbconn = new DBConnector();

        if ($dbconn->check("SELECT * FROM `string` WHERE `name` = ?", $this->resourcename)) {
            $result = false;
        } else {
            $result = $dbconn->insert($sql, $this->resourcename, $value['text']);
            if ($result) {

                $sqlConnectStringWithCompany = "INSERT INTO company_has_string(string_id,company_id)
                VALUES
                (
                    (SELECT `string`.id FROM `string` WHERE `string`.name = ?),
                    (SELECT company.id FROM company WHERE company.name = ?)
                )";
                $result = $dbconn->insert($sqlConnectStringWithCompany, $this->resourcename, $this->companyname);
            }
        }
        return $result;
    }

    public function del(): bool
    {
        $dbconn = new DBConnector();
        $sql = "DELETE FROM `string` WHERE `name` = ?";
        return $dbconn->deleteRow($sql, $this->resourcename);
    }

    public function upd(string $oldname, $value): bool
    {
        $stpos = strpos($this->companyname, $oldname);
        if (gettype($stpos) === "boolean" && $stpos == false) {
            $oldname = $this->companyname . "_" . $oldname;
        }
        $dbconn = new DBConnector();
        $sql = "UPDATE `string` SET `name` = ?, `value` = ? WHERE `name` = ?";
        return $dbconn->update($sql, $this->resourcename, $value['text'], $oldname);
    }
}
