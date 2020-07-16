<?php
include_once __DIR__.'/../requirements.php';
class ReqMethod extends ReqUser implements ReqInterface {
    public function execute(){
        switch ($this->mode) {
            case 'status':
                $this->result = array("status" => $this->user->status($this->details));
                break;
            default:
            $this->result = array("status" => $this->user->status('online'));
                break;
        }
        
    }
}

