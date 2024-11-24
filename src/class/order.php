<?php

class Order {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function checkout($cart_id, $user_id, $payment_method_id, $address_id) {
        try {
            $this->db->beginTransaction();

            $stmt = $this->db->prepare("INSERT INTO shop_order (order_id, user_id, payment_method_id, address_id, total_amount, order_status) VALUES (UUID(), ?, ?, ?, ?, 'pending')");
            $stmt->execute([$user_id, $payment_method_id, $address_id, $this->calculateTotalAmount($cart_id)]);

            $order_id = $this->db->lastInsertId();

            $stmt = $this->db->prepare("INSERT INTO order_items (order_id, product_id, quantity) SELECT ?, product_id, quantity FROM shopping_cart_item WHERE cart_id = ?");
            $stmt->execute([$order_id, $cart_id]);

            $stmt = $this->db->prepare("DELETE FROM shopping_cart_item WHERE cart_id = ?");
            $stmt->execute([$cart_id]);

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    private function calculateTotalAmount($cart_id) {
        $stmt = $this->db->prepare("SELECT SUM(p.price * sci.quantity) AS total_amount FROM shopping_cart_item sci JOIN products p ON sci.product_id = p.product_id WHERE sci.cart_id = ?");
        $stmt->execute([$cart_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total_amount'];
    }
}
?>