<?php
include_once __DIR__.'/../../essentials/DBConnector.php';
include_once __DIR__.'/analytics/queries.php';

class Analytics {
    private static $INTERESTS = array("company", "companies", "user", "users");
    private static $QUERIES = array("all", "active", "inactive");
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
        if(!in_array(strtolower($interest), Analytics::$INTERESTS)){
            throw new Exception(Analytics::$INTEREST_DOESNT_EXIST, 1);
        }
        $this->interest = $interest;
    }

    public function setQuery(string $query){
        if(!in_array(strtolower($query), Analytics::$QUERIES)){
            throw new Exception(Analytics::$QUERY_DOESNT_EXIST, 1);
        }
        $this->query = $query;
    }

    public function execute($expectedOneRowAsObject = false){
        $result = array();
        $dbconn = new MyAnalyticsDBConnector();
        $sqlHolder = AnalyticQueries::get($this->interest, $this->query);
        $res = $dbconn->query(
            $sqlHolder['sql']
        );
        if($sqlHolder['one-object']) {
            $result = $res->fetch_assoc();
        }else {
            while(($row = $res->fetch_assoc()) != null){
                array_push($result, $row);
            }
        }
        return $result;
    }
}
class MyAnalyticsDBConnector extends DBConnector {
    private $json = null;
    private $myconfig = null;
    function __construct(string $db = "evop-company"){
        $json = json_decode(file_get_contents(__DIR__."/../../essentials/config.json"), true);
        $myconfig = $json['db']['connectors'];
        parent::__construct(
            $myconfig['analytics']['username'],
            $myconfig['analytics']['password'],
            $db
        );
    }
}