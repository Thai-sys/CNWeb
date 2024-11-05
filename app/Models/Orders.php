<?php
// app/Models/Orders.php
class Orders
{
    private $db;
    private $productModel; // Thêm biến productModel để sử dụng model Product

    public function __construct($db, $productModel)
    {
        $this->db = $db; // PDO instance
        $this->productModel = $productModel; // Model sản phẩm
    }

    // Thêm đơn đặt hàng
    public function createOrder($userId, $name, $address, $phone, $items, $totalPrice)
    {
        $stmt = $this->db->prepare("INSERT INTO orders (user_id, name, address, phone, items, total_price) VALUES (?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$userId, $name, $address, $phone, $items, $totalPrice]);
    }
}
