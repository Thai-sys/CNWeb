<?php
require '../app/Models/Product.php'; // Import Model sản phẩm

class HomeController
{
    private $productModel;

    public function __construct($db)
    {
        $this->productModel = new Product($db);
    }
    public function index()
    {
        session_start(); // Khởi động phiên
        $newProducts = $this->productModel->getNewProducts();
        $featuredProducts = $this->productModel->getFeaturedProducts();
        $bestSellerProducts = $this->productModel->getBestSellerProducts();

        // Include các phần view
        include '../app/Views/partials/header.php';
        include '../app/Views/partials/banner.php';
        include '../app/Views/home.php'; // File view của trang chủ
        include '../app/Views/partials/footer.php';
    }
}
