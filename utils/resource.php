<?php
interface Resource
{

    public function del(): bool;
    public function upd(string $oldname, $value): bool;
    public function add($value): bool;
}
class Resourcer implements Resource
{
    protected $resourcename = "";
    protected $companyname = "";

    public function __construct(string $companyname, string $resourcename)
    {
        $this->companyname = $companyname;
        $this->resourcename = $this->companyname . "-" . $resourcename;
    }
    public function del(): bool
    {
        return false;
    }
    public function upd(string $oldname, $value): bool
    {
        return false;
    }
    public function add($value): bool
    {
        return false;
    }
}
