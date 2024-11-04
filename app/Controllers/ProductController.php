<?php
require_once '../config/config.php'; // Kết nối đến config
require_once '../app/Models/Product.php'; // Kết nối đến mô hình Product

class ProductController
{
    private $productModel;

    public function __construct($db)
    {
        $this->productModel = new Product($db);
    }
    public function index()
    {
        session_start(); // Khởi động phiên
        // Lấy dữ liệu sản phẩm
        // Kiểm tra thông báo lỗi từ add_to_cart.php
        $error_message = isset($_SESSION['error']) ? $_SESSION['error'] : null;
        if (isset($_SESSION['error'])) {
            unset($_SESSION['error']); // Xóa thông báo sau khi hiển thị
        }
        $newProducts = $this->productModel->getNewProducts();
        $featuredProducts = $this->productModel->getFeaturedProducts();
        $bestSellerProducts = $this->productModel->getBestSellerProducts();
        // đưa model vào biến để truy cập trong view
        $productModel = $this->productModel;
        // Include các phần view
        include '../app/Views/partials/header.php';
        include '../app/Views/partials/banner.php';
        include '../app/Views/product.php'; // File view của trang product
        include '../app/Views/partials/footer.php';
    }
}
