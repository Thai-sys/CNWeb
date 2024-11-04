<?php
// app/Controllers/CartController.php

session_start();
require '../app/Models/Cart.php'; // Nhập mô hình Cart

class CartController
{
    private $cart;
    public function __construct($db)
    {
        $this->cart = new Cart($db);
    }
    public function showCart()
    {
        if (!isset($_SESSION['username'])) {
            $_SESSION['error'] = "Chưa đăng nhập.";
            header("Location:/");
            exit();
        }

        // Bạn có thể lấy thông tin giỏ hàng từ mô hình Cart ở đây
        $cartItems = $this->cart->getCartItems($_SESSION['user_id']); // phương thức getCartItems trong mô hình Cart
        // Tính tổng tiền giỏ hàng
        $totalAmount = 0; // Khởi tạo biến tổng tiền
        foreach ($cartItems as $item) {
            $totalAmount += $item['price'] * $item['quantity']; // Giá từng sản phẩm nhân với số lượng
        }

        $_SESSION['totalAmount'] = $totalAmount;
        // Truyền dữ liệu vào view
        require '../app/Views/cart.php';
    }
    public function addToCart()
    {
        if (!isset($_SESSION['username'])) {
            //lưu trang hiện tại
            $current_url = $_SERVER['HTTP_REFERER'] ?? '/';
            $_SESSION['error'] = "Chưa đăng nhập.";
            header("Location:$current_url");
            exit();
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $user_id = $_SESSION['user_id'];
            $product_id = $_POST['product_id'];
            $quantity = 1;

            //lưu trang hiện tại
            $current_url = $_SERVER['HTTP_REFERER'] ?? '/';

            if ($this->cart->addToCart($user_id, $product_id, $quantity)) {
                $_SESSION['success'] = "Sản phẩm đã được thêm vào giỏ hàng.";
                // tính tổng tiền
                $cartItems = $this->cart->getCartItems($user_id);

                $_SESSION['totalAmount'] = 0; // Khởi tạo lại tổng tiền

                // Tính tổng tiền
                foreach ($cartItems as $item) {
                    $_SESSION['totalAmount'] += $item['price'] * $item['quantity'];
                }
            } else {
                $_SESSION['error'] = "Có lỗi xảy ra. Không thể thêm sản phẩm vào giỏ hàng.";
            }
            // Cập nhật số lượng sản phẩm trong giỏ hàng
            $_SESSION['cart_count'] = $this->cart->getTotalQuantity($user_id);
            header("Location: $current_url");
            exit();
        }
    }

    public function updateCart()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $cart_item_id = $_POST['cart_item_id'];
            $quantity = $_POST['quantity'];

            if ($this->cart->updateCart($cart_item_id, $quantity)) {
                $_SESSION['success'] = "Số lượng sản phẩm đã được cập nhật.";
                // tính tổng tiền
                $cartItems = $this->cart->getCartItems($_SESSION['user_id']);
                $_SESSION['totalAmount'] = 0; // Khởi tạo lại tổng tiền

                // Tính tổng tiền
                foreach ($cartItems as $item) {
                    $_SESSION['totalAmount'] += $item['price'] * $item['quantity'];
                }
            } else {
                $_SESSION['error'] = "Có lỗi xảy ra. Không thể cập nhật số lượng sản phẩm.";
            }
            // Cập nhật số lượng sản phẩm trong giỏ hàng
            $_SESSION['cart_count'] = $this->cart->getTotalQuantity($_SESSION['user_id']);
            header("Location: /cart");
            exit();
        }
    }

    public function removeFromCart()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $cart_item_id = $_POST['cart_item_id'];

            if ($this->cart->removeFromCart($cart_item_id)) {
                $_SESSION['success'] = "Sản phẩm đã được xóa khỏi giỏ hàng.";
                // tính tổng tiền
                $cartItems = $this->cart->getCartItems($_SESSION['user_id']);
                $_SESSION['totalAmount'] = 0; // Khởi tạo lại tổng tiền

                // Tính tổng tiền
                foreach ($cartItems as $item) {
                    $_SESSION['totalAmount'] += $item['price'] * $item['quantity'];
                }
            } else {
                $_SESSION['error'] = "Có lỗi xảy ra. Không thể xóa sản phẩm khỏi giỏ hàng.";
            }
            // Cập nhật số lượng sản phẩm trong giỏ hàng
            $_SESSION['cart_count'] = $this->cart->getTotalQuantity($_SESSION['user_id']);
            header("Location: /cart");
            exit();
        }
    }
}
