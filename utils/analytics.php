<?php

class Analytics {
    private static $INTERESTS = array("company", "companies", "user", "users");
    private static $QUERIES = array("all", "user", "user:active", "user:inactive");
    public static $INTEREST_DOESNT_EXIST = "This interest doesn't exist, try another one.";
    public static $QUERY_DOESNT_EXIST = "This query doesn't exist, try another one.";
    public static $QUERY_SYNTAX_ERROR = "The query syntax is wrong, please check.";

    private $interest = null;
    private $query = null;
    function __construct(...$presettings){
        if(sizeof($presettings) > 0){
            $this->setInterest($presettings[0]);
            if(sizeof($presettings) > 1){
                $this->setQuery($presettings[1]);
            }
        }
    }
    public function setInterest(string $interest){
        if(!empty($interest) || strlen($interest) == 0 || !in_array(strtolower($interest), Analytics::$INTERESTS)){
            throw new Exception(Analytics::$INTEREST_DOESNT_EXIST, 1);
        }
        $this->interest = $interest;
    }
    public function setQuery(string $query){
        if(!empty($query) || strlen($query) == 0 || !in_array(strtolower($query), Analytics::$QUERIES)){
            throw new Exception(Analytics::$QUERY_DOESNT_EXIST, 1);
        }
        $this->query = $query;
    }
    public function execute(){
        $result = array();

        return $result;
    }
}