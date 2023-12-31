
<?php
class Order {
    private $userId;
    private $cart;
    private $db;

    public function __construct(PDO $dataobj, $userId, Cart $cart) {
        $this->db = $dataobj;
        $this->userId = $userId;
        $this->cart = $cart;
    }

    public function processCheckout() 
    {
        $totalPrice = $this->cart->getTotalPrice();

        try {
            $this->db->beginTransaction();

            $stmt = $this->db->prepare("INSERT INTO orders (user_id, total_price) VALUES (:user_id, :total_price)");
            $stmt->bindParam(':user_id', $this->userId);
            $stmt->bindParam(':total_price', $totalPrice);
            $stmt->execute();

            $orderID = $this->db->lastInsertId();

            // Insert each item in the cart into the order_items table
            foreach ($this->cart->getCartItems() as $cartItem) {
                $productID = $cartItem['product_id'];
                $quantity = $cartItem['quantity'];
                $price = $cartItem['price'];

                $stmt = $this->db->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (:order_id, :product_id, :quantity, :price)");
                $stmt->bindParam(':order_id', $orderID);
                $stmt->bindParam(':product_id', $productID);
                $stmt->bindParam(':quantity', $quantity);
                $stmt->bindParam(':price', $price);
                $stmt->execute();
            }
            $this->cart->clear();

            $this->db->commit();

        } catch (PDOException $e) {
            $this->db->rollBack();
            echo "Error: " . $e->getMessage();
           
        }
    }
}
?>
