<?php
class AnalyticQueries {
    private static $queries = array(

        "companies" => array(

            "all" => "SELECT * FROM company", //DEFAULT-QUERY

            "active" => "SELECT * FROM company WHERE company.active = 1",

            "inactive" => "SELECT * FROM company WHERE company.active = 0"

        ),

        "company" => array(

            "all" => "", //DEFAULT-QUERY

        ),

        "user" => array(

            "all" => "", //DEFAULT-QUERY

        ),

        "users" => array(

            "inactive" => "SELECT count(user.id) as 'amount', company.name as 'company'
                            FROM user, company, company_has_user
                            WHERE user.id = company_has_user.user_id
                            AND company.id = company_has_user.company_id
                            AND company.active = 1
                            AND user.active = 0",

            "active" => "SELECT count(user.id) as 'amount', company.name as 'company'
                            FROM user, company, company_has_user
                            WHERE user.id = company_has_user.user_id
                            AND company.id = company_has_user.company_id
                            AND company.active = 1
                            AND user.active = 1",

            "all" => "SELECT count(user.id) as 'amount', company.name as 'company'
                            FROM user, company, company_has_user
                            WHERE user.id = company_has_user.user_id
                            AND company.id = company_has_user.company_id
                            AND company.active = 1", //DEFAULT-QUERY

        ),

    );
    public static function get(string $area, string $type){
        $query = AnalyticQueries::$queries[$area][$type];
        return $query;
    }
}