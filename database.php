<?php

require_once 'db-config.php';

class DatabaseTransactions extends PDOStatement
{
    public function getOrders($searchString): ?array
    {
        $sql = "SELECT `xem_hikashop_address`.`address_firstname`, `xem_hikashop_address`.`address_lastname`, `xem_hikashop_user`.`user_email`, `xem_hikashop_product`.`product_parent_id`, `xem_hikashop_order_product`.* 
        FROM `xem_hikashop_order` 
        INNER JOIN `xem_hikashop_address` ON `xem_hikashop_address`.`address_id` = `xem_hikashop_order`.`order_billing_address_id` 
        INNER JOIN `xem_hikashop_order_product` ON `xem_hikashop_order_product`.`order_id` = `xem_hikashop_order`.`order_id`
        INNER JOIN `xem_hikashop_product` ON `xem_hikashop_product`.`product_id` = `xem_hikashop_order_product`.`product_id` 
        LEFT OUTER JOIN `xem_hikashop_user` ON `xem_hikashop_order`.`order_user_id` = `xem_hikashop_user`.`user_id` 
        WHERE `xem_hikashop_order`.`order_status` = 'confirmed' 
        AND `xem_hikashop_order_product`.`product_id` IN(
                 SELECT `xem_hikashop_product`.`product_id` FROM `xem_hikashop_product` 
                 WHERE `xem_hikashop_product`.`product_code` = :searchString
                 OR `xem_hikashop_product`.`product_parent_id` = (SELECT `xem_hikashop_product`.`product_id` FROM `xem_hikashop_product` 
                 WHERE `xem_hikashop_product`.`product_code` = :searchString)
                 )";
        try {
            $connection = $this->connection();
            $statement = $connection->prepare($sql);
            $statement->bindValue(':searchString', trim($searchString));
            $statement->execute();
            $result = $statement->fetchAll();
            $connection = null;
            return $result;
            } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
            }
    }

    private function connection() {
        $connection = new PDOConfig();
        if($connection === false){
        echo "ERROR: Could not connect. " . mysqli_connect_error();
        }
        return $connection;
    }

    public function getCustomFields(int $product_id, int $parent_id): array
    {
        $productFieldsQuery = "SELECT field_realname, field_namekey FROM xem_hikashop_field WHERE find_in_set(:product_id, field_products) OR find_in_set(:parent_id, field_products)";

        $connection = $this->connection();
        $statement = $connection->prepare($productFieldsQuery);
        $statement->bindValue(':product_id', $product_id);            
        $statement->bindValue(':parent_id', $parent_id);
        $statement->execute();
        $productFields = $statement->fetchAll();

        $categoryFieldsQuery = "SELECT field_realname, field_namekey FROM xem_hikashop_field WHERE find_in_set(:product_id, field_categories) OR find_in_set(:parent_id, field_categories)"; 

        $connection = null;
        $results = $productFields;
        return $results;
    }
}
