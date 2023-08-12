<?php
require_once "database.php";
class Order{

    private $itemcode;
    private $db;

    public function __construct($itemcode)
    {
        $this->itemcode = $itemcode;
        $this->db = new DatabaseTransactions();
    }

    public function getOrderDetails(): ?array
    {
        $orders = $this->db->getOrders($this->itemcode);
        $total = array_sum(array_column($orders, 'order_product_quantity'));
        $product_name = (isset($orders[0]['order_product_name'])) ? $orders[0]['order_product_name'] : 'Product';

        foreach ($orders as &$order) { 
            $fields = $this->db->getCustomFields((int) $order['product_id'], $order['product_parent_id']);
            if (!isset($order['custom_fields']) && !empty($fields)) {
                $order['custom_fields'] = array();
            }
            foreach ($fields as $field) {
                $order['custom_fields'][$field['field_realname']] = $order[$field['field_namekey']];
            }
        }
        unset($order);
        $orderDetails = [
            'total' => $total,
            'product_name' => $product_name,
            'orders' => $orders,
        ];
        return $orderDetails;
    }
}