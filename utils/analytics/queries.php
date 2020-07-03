<?php
class AnalyticQueries {
    private static $queries = array(

        "companies" => array(

            "all" => array(
                "sql" => "SELECT * 
                            FROM company",
                "one-object" => false
            ), //DEFAULT-QUERY

            "active" => array(
                "sql" =>"SELECT * 
                            FROM company
                            WHERE company.active = 1",
                "one-object" => false
            ),

            "inactive" => array(
                "sql" =>"SELECT * 
                            FROM company
                            WHERE company.active = 0",
                "one-object" => false
            ),

        ),

        "company" => array(

            "all" => array(
                "sql" =>"",
                "one-object" => true
            ), //DEFAULT-QUERY

        ),

        "user" => array(

            "all" => array(
                "sql" => "",
                "one-object" => true
            ), //DEFAULT-QUERY

        ),

        "users" => array(

            "active" => array(
                "sql" => "SELECT count(user.id) as 'amount'
                            FROM user
                            WHERE user.active = 1",
                "one-object" => true
            ),

            "inactive" => array(
                "sql" => "SELECT count(user.id) as 'amount'
                            FROM user
                            WHERE user.active = 0",
                "one-object" => true
            ),


            "all" => array(
                "sql" => "SELECT count(user.id) as 'amount'
                            FROM user",
                "one-object" => true
            ), //DEFAULT-QUERY

        ),

    );
    public static function get(string $area, string $type){
        $query = AnalyticQueries::$queries[$area][$type];
        return $query;
    }
}