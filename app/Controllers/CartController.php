<?php
// app/Controllers/CartController.php

session_start();
require '../app/Models/Cart.php'; // Nhập mô hình Cart
require '../app/Models/Orders.php'; // nhập mô hình oredr để sử lí đặt hàng
require_once '../app/Models/Product.php'; //nhập mô hình product lấy thông tin sản phẩm vào sử lí đặt hàng

class CartController
{
    private $cart;
    private $ordersModel;
    private $productModel;
    public function __construct($db)
    {
        $this->cart = new Cart($db);
        $this->ordersModel = new Orders($db, $this->productModel);
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
        $_SESSION['cart'] = $cartItems;
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

                $_SESSION['cart'] = $cartItems;
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
                $_SESSION['cart'] = $cartItems;
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
                $_SESSION['cart'] = $cartItems;
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

    // Hàm xử lý đặt hàng
    public function placeOrder()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Lấy thông tin từ form
            $userId = $_SESSION['user_id'];
            $name = $_POST['name'];
            $address = $_POST['address'];
            $phone = $_POST['phone'];
            $totalPrice = $_SESSION['totalAmount']; // Tổng tiền từ giỏ hàng

            // Lấy sản phẩm từ giỏ hàng
            $items = [];
            foreach ($_SESSION['cart'] as $item) {
                $items[] = [
                    'id' => $item['product_id'],
                    'quantity' => $item['quantity']
                ];
            }

            // Chuyển đổi mảng thành chuỗi JSON
            $itemsJson = json_encode(['products' => $items]);

            // Tạo đơn hàng
            if ($this->ordersModel->createOrder($userId, $name, $address, $phone, $itemsJson, $totalPrice)) {

                // Nếu lưu thành công, xóa giỏ hàng

                $this->cart->clearCart($userId); // Gọi hàm clearCart để xóa tất cả sản phẩm trong giỏ hàng
                // Cập nhật số lượng sản phẩm trong giỏ hàng
                $_SESSION['cart_count'] = $this->cart->getTotalQuantity($_SESSION['user_id']);
                unset($_SESSION['cart']);
                $_SESSION['success'] = "Bạn đã đặt hàng thành công, chúng tui sẽ liên hệ với bạn xác nhận và gửi hàng trong 24h tới!";
                header("Location: /cart");
                exit;
            } else {
                $_SESSION['error'] = "Đặt hàng không thành công. Vui lòng thử lại.";
            }
        }
    }
}
