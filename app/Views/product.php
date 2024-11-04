<section class="py-16 bg-coffee-dark" id="product-section">

    <div class="text-center my-4">
        <button onclick="showProducts('best_seller')"
            class="product-button mx-2 py-2 px-4 bg-coffee-dark text-coffee-light rounded">Sản phẩm bán
            chạy</button>
        <button onclick="showProducts('featured')"
            class="product-button mx-2 py-2 px-4 bg-coffee-dark text-coffee-light rounded">Sản phẩm nổi
            bật</button>
        <button onclick="showProducts('new')"
            class="product-button mx-2 py-2 px-4 bg-coffee-dark text-coffee-light rounded">Sản phẩm mới ra
            mắt</button>
        <button onclick="showProducts('domestic')"
            class="product-button mx-2 py-2 px-4 bg-coffee-dark text-coffee-light rounded">Sản phẩm nội
            địa</button>
        <button onclick="showProducts('imported')"
            class="product-button mx-2 py-2 px-4 bg-coffee-dark text-coffee-light rounded">Sản phẩm ngoại
            địa</button>
    </div>
    <div class="container mx-auto text-center" id="product-container">
        <h2 class="text-3xl font-bold mb-6" id="product-title">Sản phẩm bán chạy</h2>
        <div class="grid grid-cols-3 gap-8" id="best-seller-products">
            <?php
            // Hiển thị sản phẩm bán chạy
            $productModel->displayProducts($bestSellerProducts);
            ?>
        </div>

        <!-- Các phần sản phẩm khác sẽ được ẩn đi -->
        <div id="featured-products" class="hidden grid grid-cols-3 gap-8">
            <?php
            // Hiển thị sản phẩm nổi bật
            $productModel->displayProducts($featuredProducts);
            ?>
        </div>

        <div id="new-products" class="hidden grid grid-cols-3 gap-8">
            <?php
            // Hiển thị sản phẩm mới
            $productModel->displayProducts($newProducts);
            ?>
        </div>

        <div id="domestic-products" class="hidden grid grid-cols-3 gap-8">
            <!-- Thêm mã PHP cho sản phẩm nội địa ở đây -->
        </div>

        <div id="imported-products" class="hidden grid grid-cols-3 gap-8">
            <!-- Thêm mã PHP cho sản phẩm ngoại địa ở đây -->
        </div>
    </div>
</section>