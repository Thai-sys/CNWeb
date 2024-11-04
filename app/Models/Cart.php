<?php
// app/Models/Cart.php

class Cart
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getCartItems($user_id)
    {
        $sql = "SELECT ci.*, p.price, p.image_url, p.name as product_name 
            FROM cart_items ci
            JOIN coffee_products p ON ci.product_id = p.id 
            WHERE ci.user_id = :user_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addToCart($user_id, $product_id, $quantity = 1)
    {
        $sql = "INSERT INTO cart_items (user_id, product_id, quantity) VALUES (:user_id, :product_id, :quantity)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':product_id', $product_id);
        $stmt->bindParam(':quantity', $quantity);

        return $stmt->execute();
    }

    public function updateCart($cart_item_id, $quantity)
    {
        $sql = "UPDATE cart_items SET quantity = :quantity WHERE id = :cart_item_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':quantity', $quantity);
        $stmt->bindParam(':cart_item_id', $cart_item_id);

        return $stmt->execute();
    }

    public function removeFromCart($cart_item_id)
    {
        $sql = "DELETE FROM cart_items WHERE id = :cart_item_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':cart_item_id', $cart_item_id);

        return $stmt->execute();
    }
    // Hàm lấy tổng số lượng sản phẩm trong giỏ hàng của người dùng
    public function getTotalQuantity($user_id)
    {
        $query = "SELECT SUM(quantity) AS total_quantity FROM cart_items WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total_quantity'] ? (int)$result['total_quantity'] : 0; // Trả về tổng số lượng hoặc 0
    }
}
