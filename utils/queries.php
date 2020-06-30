<?php
class Queries {
    private static $s;
    private static $querymap = array(
        "companies" => array(
            "list" => "SELECT c.name as 'name', count(DISTINCT ct.id) as 'tables'
                        FROM company c
                        LEFT JOIN company_table ct
                        ON ct.company_id = c.id
                        WHERE c.active = 1
                        GROUP BY c.id"
        ),
        "company" => array(
            
            "create" => "INSERT INTO company(`name`) values(?)",

            "is-active" => "SELECT * FROM company WHERE company.name = ? AND active = 1 LIMIT 1",

            "exists" => "SELECT * FROM company WHERE company.name = ?",

            "deactivate" => "UPDATE company SET active = 0 WHERE company.name = ?",

            "activate" => "UPDATE company SET active = 1 WHERE company.name = ?",

            "information-little" => "SELECT company.name, COUNT(company_table.id) as `tables` FROM company, company_table WHERE company.name = ? AND company.id = company_table.company_id AND company.active = 1",

            "information-all" => "SELECT company.name, COUNT(company_table.id) as `tables` FROM company, company_table WHERE company.name = ? AND company.id = company_table.company_id AND company.active = 1",

            "user-count" => "SELECT count(user.id) as 'amount'
                                FROM user, company, company_has_user
                                WHERE user.id = company_has_user.user_id
                                AND company.id = company_has_user.company_id
                                AND company.active = 1",
            "product-count" => "SELECT count(product.id) as 'amount'
                                FROM product, company
                                WHERE company.id = product.company_id
                                AND company.name = ?
                                AND company.active = 1",
            "read-colors" => "SELECT color.name as 'name', color.r as 'r', color.g as 'g', color.b as 'b', color.a as 'a'
                                FROM color, company_has_color, company
                                WHERE company_has_color.company_id = company.id
                                AND company_has_color.color_id = color.id
                                AND company.name = ?
                                AND company.active = 1",
            "read-strings" => "SELECT `string`.`name` as 'name', `string`.`value` as 'value'
                                FROM `string`, company_has_string, company
                                WHERE company_has_string.company_id = company.id
                                AND company_has_string.string_id = `string`.id
                                AND company.name = ?
                                AND company.active = 1",
            "read-textstyles" => "SELECT textstyle.name as 'name', textstyle.fontsize as 'fontsize', textstyle.fontfamily as 'fontfamily', textstyle.fontweight as 'fontweight'
                                FROM textstyle, company_has_textstyle, company
                                WHERE company_has_textstyle.company_id = company.id
                                AND company_has_textstyle.textstyle_id = textstyle.id
                                AND company.name = ?
                                AND company.active = 1",
            "products" => "SELECT product.name as 'name', product.description as 'description', product.price as 'price', product_group.name as 'groupname', product_subgroup.name as 'subgroupname'
                                FROM company, product, product_has_product_group, product_has_product_subgroup, product_group, product_subgroup
                                WHERE company.name = ?
                                AND company.id = product.company_id
                                AND product.active = 1
                                AND product_has_product_group.product_id = product.id
                                AND product_has_product_group.product_group_id = product_group.id
                                AND product_has_product_subgroup.product_id = product.id
                                AND product_has_product_subgroup.product_subgroup_id = product_subgroup.id
                                AND company.active = 1",
            "product-groups" => "SELECT product_group.name as 'groupname', product_subgroup.name as 'subgroupname'
                                FROM company, product, product_has_product_group, product_has_product_subgroup, product_group, product_subgroup
                                WHERE company.name = ?
                                AND company.id = product.company_id
                                AND product.active = 1
                                AND product_has_product_group.product_id = product.id
                                AND product_has_product_group.product_group_id = product_group.id
                                AND company.active = 1",
            "product-subgroups" => "SELECT product_group.name as 'groupname', product_subgroup.name as 'subgroupname'
                                FROM company, product, product_has_product_group, product_has_product_subgroup, product_group, product_subgroup
                                WHERE company.name = ?
                                AND company.id = product.company_id
                                AND product.active = 1
                                AND product_has_product_subgroup.product_id = product.id
                                AND product_has_product_subgroup.product_subgroup_id = product_subgroup.id
                                AND company.active = 1"
        ),
        "table" => array(
            "create" => "INSERT INTO company_table(`name`, company_id) VALUES(?, (SELECT id from company WHERE `name` = ?))",

            "update" => "UPDATE company_table SET `name` = ? WHERE `name` = ? company_id = (SELECT id from company WHERE `name` = ?))",

            "remove" => "DELETE from company_table WHERE `name` = ? company_id = (SELECT id from company WHERE `name` = ?))",
        )
    );

    public static function get(string $area, string $key){
        return Queries::$querymap[$area][$key];
    }
}