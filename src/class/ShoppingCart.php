<?php
class ShoppingCart {
    private $conn;
    private $table_name = "shopping_cart";
    private $items_table = "shopping_cart_item";

    public $cart_id;
    public $user_id;
    public $items = [];

    public function __construct($db) {
        $this->conn = $db;
    }

    public function addItem($product_id, $quantity) {
        // Validate input
        if ($quantity <= 0) {
            throw new InvalidArgumentException('Quantity must be greater than zero.');
        }

        // Check if the item already exists in the cart
        $query = "SELECT * FROM " . $this->items_table . " WHERE cart_id = :cart_id AND product_id = :product_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':cart_id', $this->cart_id);
        $stmt->bindParam(':product_id', $product_id);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            // Update quantity if item already exists
            $query = "UPDATE " . $this->items_table . " SET quantity = quantity + :quantity WHERE cart_id = :cart_id AND product_id = :product_id";
        } else {
            // Insert new item into the cart
            $query = "INSERT INTO " . $this->items_table . " (cart_id, product_id, quantity) VALUES (:cart_id, :product_id, :quantity)";
        }

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':cart_id', $this->cart_id);
        $stmt->bindParam(':product_id', $product_id);
        $stmt->bindParam(':quantity', $quantity);
        return $stmt->execute();
    }

    public function updateItem($product_id, $quantity) {
        if ($quantity < 0) {
            throw new InvalidArgumentException('Quantity cannot be negative.');
        }

        $query = "UPDATE " . $this->items_table . " SET quantity = :quantity WHERE cart_id = :cart_id AND product_id = :product_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':cart_id', $this->cart_id);
        $stmt->bindParam(':product_id', $product_id);
        $stmt->bindParam(':quantity', $quantity);
        return $stmt->execute();
    }

    public function checkout($paymentDetails) {
        // Validate payment details
        if (empty($paymentDetails['method'])) {
            throw new InvalidArgumentException('Payment method is required.');
        }

        // Process payment (this is where you would integrate with a payment gateway)
        $paymentSuccess = true; // Replace with actual payment processing logic

        if ($paymentSuccess) {
            // Assuming payment is successful, create an order
            $query = "INSERT INTO orders (user_id, total_amount) VALUES (:user_id, :total_amount)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':user_id', $this->user_id);
            $stmt->bindParam(':total_amount', $this->calculateTotal());
            $stmt->execute();

            // Clear the cart after successful checkout
            $this->clearCart();
            return true;
        } else {
            throw new RuntimeException('Payment processing failed.');
        }
    }

    private function calculateTotal() {
        $query = "SELECT SUM(price * quantity) as total FROM " . $this->items_table . " INNER JOIN products ON shopping_cart_item.product_id = products.product_id WHERE cart_id = :cart_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':cart_id', $this->cart_id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

    private function clearCart() {
        $query = "DELETE FROM " . $this->items_table . " WHERE cart_id = :cart_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':cart_id', $this->cart_id);
        return $stmt->execute();
    }
}
?>