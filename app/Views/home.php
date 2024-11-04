<?php
require_once '../app/Models/Product.php'; // Bao gồm model

$productModel = new Product($this->productModel->getPdo()); // 


// Kiểm tra và hiển thị thông báo lỗi
if (isset($_SESSION['error'])) {
    echo '<div class="alert alert-danger">' . $_SESSION['error'] . '</div>';
    unset($_SESSION['error']); // Xóa thông báo sau khi hiển thị
}
?>
<!-- thông báo sản phẩm được thêm vào giỏ hàng -->
<?php if (isset($_SESSION['success'])): ?>
    <div class="flex items-center p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg" role="alert">
        <svg class="w-5 h-5 mr-2 text-green-600" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path d="M10 0a10 10 0 1 1 0 20 10 10 0 0 1 0-20zm1 15l-5-5 1.414-1.414L10 12.586l4.586-4.586L16 8l-5 5z" />
        </svg>
        <span><?php echo $_SESSION['success']; ?></span>
        <button type="button"
            class="ml-auto -mx-1.5 -my-1.5 rounded-md focus:ring-2 focus:ring-green-600 p-1.5 hover:bg-green-200 inline-flex items-center justify-center"
            aria-label="Close" onclick="this.parentElement.remove()">
            <span class="sr-only">Close</span>
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd"
                    d="M10 9.293l-3.646-3.646-1.414 1.414L8.586 10l-3.646 3.646 1.414 1.414L10 10.414l3.646 3.646 1.414-1.414L11.414 10l3.646-3.646-1.414-1.414L10 9.293z"
                    clip-rule="evenodd" />
            </svg>
        </button>
    </div>
    <?php unset($_SESSION['success']); // Xóa thông báo sau khi hiển thị 
    ?>
<?php endif; ?>

<!-- hiển thị thông báo chưa đăng nhập khi nhấp add cart -->
<?php if ($error_message): ?>
    <div id="login-modal" class="relative z-10" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div
                    class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div
                                class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                                <h3 class="text-base font-semibold leading-6 text-gray-900" id="modal-title">Chưa đăng
                                    nhập
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">Bạn cần đăng nhập để thêm sản phẩm vào giỏ hàng.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                        <a href="/login"
                            class="inline-flex w-full justify-center rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 sm:ml-3 sm:w-auto">Đăng
                            nhập</a>
                        <button id="cancel-button" type="button"
                            class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">Hủy</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
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