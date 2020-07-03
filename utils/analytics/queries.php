<?php
class AnalyticQueries {
    private static $queries = array(

        "companies" => array(

            "all" => "SELECT * FROM company"
        ),

        "company" => array(

        ),

        "user" => array(

            "inactive" => "",

            "active" => "",

        ),

        "users" => array(

            "inactive" => "",

            "active" => "",

        ),

    );
    public static function get(string $area, string $type){
        $query = AnalyticQueries::$queries[$area][$type];
        return $query;
    }
}