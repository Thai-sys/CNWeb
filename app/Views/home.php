<?php
require_once '../app/Models/Product.php'; // Bao gồm model

$productModel = new Product($this->productModel->getPdo()); // 

?>
<!-- Phần sản phẩm -->
<section class="py-16 bg-coffee-dark">
    <div class="container mx-auto text-center">
        <!-- Sản phẩm mới -->
        <h2 class="text-3xl font-bold mb-6">Sản phẩm mới</h2>
        <div class="grid grid-cols-3 gap-8">
            <?php
            // Hiển thị sản phẩm mới
            $newProducts = $productModel->getNewProducts();
            $productModel->displayProducts($newProducts);
            ?>
        </div>

        <h2 class="text-3xl font-bold my-6">Sản phẩm nổi bật</h2>
        <div class="grid grid-cols-3 gap-8">
            <?php
            // Hiển thị sản phẩm nổi bật
            $featuredProducts = $productModel->getFeaturedProducts();
            $productModel->displayProducts($featuredProducts);
            ?>
        </div>
        <!-- Sản phẩm bán chạy -->
        <h2 class="text-3xl font-bold my-6">Sản phẩm bán chạy</h2>
        <div class="grid grid-cols-3 gap-8">
            <?php
            // Hiển thị sản phẩm bán chạy
            $bestSellerProducts = $productModel->getBestSellerProducts();
            $productModel->displayProducts($bestSellerProducts);
            $pdo = null;
            ?>
        </div>
    </div>
</section>