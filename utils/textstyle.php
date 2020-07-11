<?php
include_once 'resource.php';
include_once 'mydb.php';

class TextStyle extends Resourcer
{

    public function add($value): bool
    {
        $result = false;
        if (
            (!isset($value['fontsize']) || !isset($value['fontfamily']) || !isset($value['fontweight'])) &&
            ($value['fontsize'] == null || $value['fontfamily'] == null || $value['fontweight'] == null)
        ) {
            throw new Exception("You need to pass all values: fontsize, fontfamily, fontweight", 1);
        }
        $dbconn = new MyCompanyDBConnector();
        $sql = "INSERT INTO textstyle(`name`, fontsize, fontfamily, fontweight) VALUES
            (
                ?,
                ?,
                ?,
                ?
            )";
        $sqlCheck = "SELECT * FROM textstyle WHERE `name` = ?";
        if ($dbconn->check($sqlCheck, $this->resourcename)) {
            $result = false;
        } else {
            $result = $dbconn->insert($sql, $this->resourcename, $value['fontsize'], $value['fontfamily'], $value['fontweight']);
            if ($result) {
                $sqlConnectTextStyleWithCompany = "INSERT INTO company_has_textstyle(textstyle_id,company_id)
                VALUES
                (
                    (SELECT textstyle.id FROM textstyle WHERE textstyle.name = ?),
                    (SELECT company.id FROM company WHERE company.name = ?)
                )";
                $result = $dbconn->insert($sqlConnectTextStyleWithCompany, $this->companyname."-".$this->resourcename, $this->companyname);
            }
        }

        return $result;
    }
    public function del(): bool
    {
        $dbconn = new MyCompanyDBConnector();
        $sql = "DELETE FROM textstyle WHERE `name` = ?";
        return $dbconn->deleteRow($sql, $this->companyname."-".$this->resourcename);
    }
    public function upd(string $oldname, $value): bool
    {
        $stpos = strpos($this->companyname, $oldname);
        if (gettype($stpos) === "boolean" && $stpos == false) {
            $oldname = $this->companyname . "-" . $oldname;
        }

        $dbconn = new MyCompanyDBConnector();
        $sql = "UPDATE textstyle SET `name` = ?, fontsize = ?, fontfamily = ?, fontweight = ? WHERE `name` = ?";
        return $dbconn->update($sql, $this->companyname."-".$this->resourcename, $value['fontsize'], $value['fontfamily'], $value['fontweight'], $oldname);
    }
}
