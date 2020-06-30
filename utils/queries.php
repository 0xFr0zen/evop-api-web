<?php
class Queries {
    private static $s;
    private static $querymap = array(
        "company" => array(
            
            "create" => "INSERT INTO company(`name`, `tables`) values(?, ?)",

            "remove" => "DELETE FROM company WHERE company.name = ?",

            "information-little" => "SELECT `name`, COUNT(company_tables.id) FROM company,company_table WHERE company.name = ? AND company.id = company_table.company_id",

            "information-all" => "SELECT `name`,COUNT(company_tables.id) FROM company,company_table WHERE company.name = ? AND company.id = company_table.company_id",

            "user-count" => "SELECT count(user.id) as 'amount'
                                FROM user, company, company_has_user
                                WHERE user.id = company_has_user.user_id
                                AND company.id = company_has_user.company_id",
            "product-count" => "SELECT count(product.id) as 'amount'
                                FROM product, company
                                WHERE company.id = product.company_id
                                AND company.name = ?",
            "read-colors" => "SELECT color.name as 'name', color.r as 'r', color.g as 'g', color.b as 'b', color.a as 'a'
                                FROM color, company_has_color, company
                                WHERE company_has_color.company_id = company.id
                                AND company_has_color.color_id = color.id
                                AND company.name = ?",
            "read-strings" => "SELECT `string`.`name` as 'name', `string`.`value` as 'value'
                                FROM `string`, company_has_string, company
                                WHERE company_has_string.company_id = company.id
                                AND company_has_string.string_id = `string`.id
                                AND company.name = ?",
            "read-textstyles" => "SELECT textstyle.name as 'name', textstyle.fontsize as 'fontsize', textstyle.fontfamily as 'fontfamily', textstyle.fontweight as 'fontweight'
                                FROM textstyle, company_has_textstyle, company
                                WHERE company_has_textstyle.company_id = company.id
                                AND company_has_textstyle.textstyle_id = textstyle.id
                                AND company.name = ?",
            "products" => "SELECT product.name as 'name', product.description as 'description', product.price as 'price', product_group.name as 'groupname', product_subgroup.name as 'subgroupname'
                                FROM company, product, product_has_product_group, product_has_product_subgroup, product_group, product_subgroup
                                WHERE company.name = ?
                                AND company.id = product.company_id
                                AND product.active = 1
                                AND product_has_product_group.product_id = product.id
                                AND product_has_product_group.product_group_id = product_group.id
                                AND product_has_product_subgroup.product_id = product.id
                                AND product_has_product_subgroup.product_subgroup_id = product_subgroup.id",
            "product-groups" => "SELECT product_group.name as 'groupname', product_subgroup.name as 'subgroupname'
                                FROM company, product, product_has_product_group, product_has_product_subgroup, product_group, product_subgroup
                                WHERE company.name = ?
                                AND company.id = product.company_id
                                AND product.active = 1
                                AND product_has_product_group.product_id = product.id
                                AND product_has_product_group.product_group_id = product_group.id",
            "product-subgroups" => "SELECT product_group.name as 'groupname', product_subgroup.name as 'subgroupname'
                                FROM company, product, product_has_product_group, product_has_product_subgroup, product_group, product_subgroup
                                WHERE company.name = ?
                                AND company.id = product.company_id
                                AND product.active = 1
                                AND product_has_product_subgroup.product_id = product.id
                                AND product_has_product_subgroup.product_subgroup_id = product_subgroup.id"
            
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