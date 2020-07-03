<?php
class AnalyticQueries {
    private static $queries = array(

        "companies" => array(

            "default" => "",

        ),

        "company" => array(

            "default" => "",

        ),

        "user" => array(

            "default" => "",

            "inactive" => "",

            "active" => "",

        ),

        "users" => array(

            "default" => "",

            "inactive" => "",

            "active" => "",

        ),

    );
    public static function get(string $area, string $type = ""){
        $query = null;
        if($type === ""){
            $query = AnalyticQueries::$queries[$area]['default'];
        }else {
            $query = AnalyticQueries::$queries[$area][$type];
        }
        return $query;
    }
}