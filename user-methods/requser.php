<?php
include_once __DIR__.'/../utils/mydb.php';
include_once __DIR__.'/../utils/user.php';

/**
 * [Description ReqCompany]
 * Requirements of the company
 */
class ReqUser {
    protected string $uuid;
    protected string $mode;
    protected string $details;
    protected array $values;
    protected User $user;
    public bool $exists;
    public $result;
    public function __construct(){
        $this->uuid = $_REQUEST['uuid'];
        $this->mode = $_REQUEST['mode'];
        $this->details = $_REQUEST['details'];
        $this->values = $this->valuesParser($_REQUEST['values'] != null ? $_REQUEST['values'] : "");

        $this->user = new User($this->uuid);
        switch ($mode) {
            case 'status':
                $this->result = array("status" => $this->user->status($this->details));
                break;
            default:
            $this->result = array("status" => $this->user->status('online'));
                break;
        }

        $reqs = array_keys($_REQUEST);
        $found = false;
        
    }
    public function valuesParser(string $data):array {
        $vals = array();
        $result = array();
        if( strpos( $data, ":" ) !== false) {
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
