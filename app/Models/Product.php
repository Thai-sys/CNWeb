<?php

class Product
{
    private $pdo;

    // Constructor để nhận PDO instance
    public function __construct($db)
    {
        // Kiểm tra xem $db có phải là một instance của PDO không
        if (!$db instanceof PDO) {
            throw new InvalidArgumentException("Invalid PDO instance provided.");
        }
        $this->pdo = $db; // Gán PDO instance vào thuộc tính
    }

    public function getPdo()
    {
        return $this->pdo; // Trả về PDO instance
    }
    public function getNewProducts($limit = 6)
    {
        $sql = "SELECT id, name, description, price, image_url, quantity FROM coffee_products WHERE is_new = TRUE ORDER BY RAND() LIMIT :limit";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getFeaturedProducts($limit = 6)
    {
        $sql = "SELECT id, name, description, price, image_url, quantity FROM coffee_products WHERE is_featured = TRUE ORDER BY RAND() LIMIT :limit";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getBestSellerProducts($limit = 6)
    {
        $sql = "SELECT id, name, description, price, image_url, quantity  FROM coffee_products WHERE is_best_seller = TRUE ORDER BY RAND() LIMIT :limit";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function displayProducts($products)
    {
        foreach ($products as $row) {
            // Kiểm tra số lượng
            $isOutOfStock = $row["quantity"] == 0;

            echo '<div class="product-item bg-coffee-light p-8 rounded hover:shadow-lg transition-shadow duration-300 relative">';

            // Nếu hết hàng, thêm lớp phủ trắng và thông báo
            if ($isOutOfStock) {
                echo '<div class="absolute inset-0 bg-white opacity-75 flex items-center justify-center z-10">';
                echo '<p class="text-xl font-bold text-black">Sản phẩm tạm hết hàng</p>';
                echo '</div>';
            }

            // Hiển thị ảnh sản phẩm
            echo '<img alt="' . htmlspecialchars($row["image_url"]) . '" class="rounded-full mx-auto mb-4 transition-transform duration-300 hover:scale-110" height="100" src="' . htmlspecialchars($row["image_url"]) . '" width="100" />';

            // Hiển thị tên và mô tả sản phẩm
            echo '<h3 class="text-2xl font-bold mb-2">' . htmlspecialchars($row["name"]) . '</h3>';
            echo '<p>' . htmlspecialchars($row["description"]) . '</p>';

            // Hiển thị giá sản phẩm
            echo '<p class="font-bold text-lg">Giá: ' . number_format($row["price"], 0, ',', '.') . ' VND</p>';

            // Hiển thị số lượng nếu có
            if (!$isOutOfStock) {
                echo '<p class="text-sm text-green-600">Số lượng còn lại: ' . htmlspecialchars($row["quantity"]) . '</p>';
            } else {
                // Nếu hết hàng thì không cho thêm vào giỏ hàng
                echo '<p class="text-sm text-red-600">Sản phẩm tạm hết hàng</p>';
            }

            // Form thêm vào giỏ hàng (chỉ hiển thị nếu còn hàng)
            if (!$isOutOfStock) {
                echo '<form action="/cart/add" method="POST">';
                echo '<input type="hidden" name="product_id" value="' . $row['id'] . '">';
                echo '<button class="btn-cart" type="submit"><i class="fas fa-shopping-cart"></i> Add Cart</button>';
                echo '</form>';
            }

            // Nút yêu thích
            echo '<button class="btn-heart"><i class="fas fa-heart"></i></button>';

            echo '</div>';
        }
    }


    //thêm sản phẩm 
    public function addProduct($name, $description, $price, $image_url, $coffee_type, $origin, $weight, $flavor, $production_date, $expiry_date, $status, $rating, $quantity, $is_new, $is_featured, $is_best_seller, $is_normal)
    {
        $status = $quantity > 0 ? 'in_stock' : 'out_of_stock';
        $sql = "INSERT INTO coffee_products (name, description, price, image_url, coffee_type, origin, weight, flavor, production_date, expiry_date, status, rating, quantity, is_new, is_featured, is_best_seller, is_normal)
                VALUES (:name, :description, :price, :image_url, :coffee_type, :origin, :weight, :flavor, :production_date, :expiry_date, :status, :rating, :quantity, :is_new, :is_featured, :is_best_seller, :is_normal)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':image_url', $image_url);
        $stmt->bindParam(':coffee_type', $coffee_type);
        $stmt->bindParam(':origin', $origin);
        $stmt->bindParam(':weight', $weight);
        $stmt->bindParam(':flavor', $flavor);
        $stmt->bindParam(':production_date', $production_date);
        $stmt->bindParam(':expiry_date', $expiry_date);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':rating', $rating);
        $stmt->bindParam(':quantity', $quantity);
        $stmt->bindParam(':is_new', $is_new, PDO::PARAM_BOOL);
        $stmt->bindParam(':is_featured', $is_featured, PDO::PARAM_BOOL);
        $stmt->bindParam(':is_best_seller', $is_best_seller, PDO::PARAM_BOOL);
        $stmt->bindParam(':is_normal', $is_normal, PDO::PARAM_BOOL);
        $stmt->execute();
    }

    //xóa sản phẩm 
    public function deleteProduct($id)
    {
        try {
            // Bước 1: Tắt ràng buộc khóa ngoại
            $this->pdo->exec("SET FOREIGN_KEY_CHECKS=0;");

            // Bước 2: Lấy đường dẫn hình ảnh từ cơ sở dữ liệu
            $sql = "SELECT image_url FROM coffee_products WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            // Kiểm tra xem có sản phẩm nào không
            $product = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($product) {
                // Bước 3: Lấy đường dẫn hình ảnh
                $imagePath = '../public/img/' . basename($product['image_url']);

                // Bước 4: Xóa tệp hình ảnh nếu nó tồn tại
                if (file_exists($imagePath)) {
                    unlink($imagePath); // Xóa tệp hình ảnh
                }

                // Bước 5: Xóa sản phẩm khỏi cơ sở dữ liệu
                $sql = "DELETE FROM coffee_products WHERE id = :id";
                $stmt = $this->pdo->prepare($sql);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();

                // Bước 6: Xóa các bản ghi liên quan trong cart_items
                $sql = "DELETE FROM cart_items WHERE product_id = :product_id";
                $stmt = $this->pdo->prepare($sql);
                $stmt->bindParam(':product_id', $id, PDO::PARAM_INT);
                $stmt->execute();
            } else {
                throw new Exception("Product not found.");
            }
        } catch (Exception $e) {
            // Xử lý lỗi
            echo "Lỗi: " . $e->getMessage();
        } finally {
            // Bước 7: Bật lại ràng buộc khóa ngoại
            $this->pdo->exec("SET FOREIGN_KEY_CHECKS=1;");
        }
    }


    // Lấy tất cả các sản phẩm
    public function getAllProducts()
    {
        $sql = "SELECT id, name, description, price, image_url, quantity FROM coffee_products";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Trả về mảng các sản phẩm
    }

    // Hiển thị tất cả sản phẩm
    public function displayAllProducts()
    {
        // Gọi phương thức getAllProducts() để lấy tất cả sản phẩm
        $products = $this->getAllProducts();

        // Nút thêm sản phẩm
        echo '<div class="mb-4">';
        echo '  <button onclick="showAddProductForm()" class="bg-blue-500 text-white p-2 rounded">Thêm Sản Phẩm</button>';
        echo '</div>';

        // Bảng hiển thị sản phẩm
        echo '<table class="min-w-full bg-white">';
        echo '  <thead>';
        echo '    <tr class="w-full bg-gray-200 text-gray-600 uppercase text-sm leading-normal">';
        echo '      <th class="py-3 px-6 text-left">ID</th>';
        echo '      <th class="py-3 px-6 text-left">Tên sản phẩm</th>';
        echo '      <th class="py-3 px-6 text-left">Mô tả</th>';
        echo '      <th class="py-3 px-6 text-left">Giá</th>';
        echo '      <th class="py-3 px-6 text-left">Số lượng</th>';
        echo '      <th class="py-3 px-6 text-left">Hình ảnh</th>';
        echo '      <th class="py-3 px-6 text-left">Hành động</th>';
        echo '    </tr>';
        echo '  </thead>';
        echo '  <tbody class="text-gray-600 text-sm font-light">';

        foreach ($products as $row) {
            echo '    <tr class="hover:bg-gray-100 border-b border-gray-200">';
            echo '      <td class="py-3 px-6 text-left">' . htmlspecialchars($row["id"]) . '</td>';
            echo '      <td class="py-3 px-6 text-left">' . htmlspecialchars($row["name"]) . '</td>';
            echo '      <td class="py-3 px-6 text-left">' . htmlspecialchars($row["description"]) . '</td>';
            echo '      <td class="py-3 px-6 text-left">' . number_format($row["price"], 0, ',', '.') . ' VND</td>';
            echo '      <td class="py-3 px-6 text-left">' . htmlspecialchars($row["quantity"]) . '</td>';
            echo '      <td class="py-3 px-6 text-left">';
            echo '        <img src="' . htmlspecialchars($row["image_url"]) . '" alt="' . htmlspecialchars($row["name"]) . '" class="w-20 h-20 object-cover rounded"/>';
            echo '      </td>';
            echo '      <td class="py-3 px-6 text-left">';
            echo '        <form action="/admin/product/delete" method="POST" onsubmit="return confirm(\'Bạn có chắc chắn muốn xóa sản phẩm này?\');">';
            echo '          <input type="hidden" name="id" value="' . $row['id'] . '">';
            echo '          <button type="submit" class="text-red-600 hover:text-red-800">Xóa</button>';
            echo '        </form>';
            echo '      </td>';
            echo '    </tr>';
        }

        echo '  </tbody>';
        echo '</table>';
    }


    //hiển thị add_product 
    public function renderAddProductForm()
    {
        return '
        <body class="bg-gray-100">
            <div class="container mx-auto py-8">
                <h1 class="text-4xl font-semibold text-center mb-6 text-gray-800">Thêm sản phẩm mới</h1>
                <form action="/admin/product/add" method="POST" enctype="multipart/form-data" class="bg-white p-8 rounded-lg shadow-lg max-w-xl mx-auto">
                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <label class="block text-gray-700 font-medium">Tên sản phẩm</label>
                            <input type="text" name="name" required class="border rounded w-full py-2 px-3 focus:outline-none focus:border-green-500">
                        </div>
                        <div>
                            <label class="block text-gray-700 font-medium">Mô tả</label>
                            <textarea name="description" required class="border rounded w-full py-2 px-3 focus:outline-none focus:border-green-500"></textarea>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-gray-700 font-medium">Giá</label>
                                <input type="number" name="price" required class="border rounded w-full py-2 px-3 focus:outline-none focus:border-green-500">
                            </div>
                            <div>
                                <label class="block text-gray-700 font-medium">Số lượng</label>
                                <input type="number" name="quantity" required class="border rounded w-full py-2 px-3 focus:outline-none focus:border-green-500">
                            </div>
                        </div>
                        <div>
                            <label class="block text-gray-700 font-medium">Hình ảnh</label>
                            <input type="file" name="image" accept="image/*" required class="border rounded w-full py-2 px-3 focus:outline-none focus:border-green-500">
                        </div>
                        <div>
                            <label class="block text-gray-700 font-medium">Loại cà phê</label>
                            <input type="text" name="coffee_type" class="border rounded w-full py-2 px-3 focus:outline-none focus:border-green-500">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-gray-700 font-medium">Nguồn gốc</label>
                                <input type="text" name="origin" class="border rounded w-full py-2 px-3 focus:outline-none focus:border-green-500">
                            </div>
                            <div>
                                <label class="block text-gray-700 font-medium">Khối lượng</label>
                                <input type="text" name="weight" class="border rounded w-full py-2 px-3 focus:outline-none focus:border-green-500">
                            </div>
                        </div>
                        <div>
                            <label class="block text-gray-700 font-medium">Hương vị</label>
                            <input type="text" name="flavor" class="border rounded w-full py-2 px-3 focus:outline-none focus:border-green-500">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-gray-700 font-medium">Ngày sản xuất</label>
                                <input type="date" name="production_date" class="border rounded w-full py-2 px-3 focus:outline-none focus:border-green-500">
                            </div>
                            <div>
                                <label class="block text-gray-700 font-medium">Ngày hết hạn</label>
                                <input type="date" name="expiry_date" class="border rounded w-full py-2 px-3 focus:outline-none focus:border-green-500">
                            </div>
                        </div>
                        <div>
                            <label class="block text-gray-700 font-medium">Trạng thái</label>
                            <select name="status" class="border rounded w-full py-2 px-3 focus:outline-none focus:border-green-500">
                                <option value="Còn hàng">Còn hàng</option>
                                <option value="Hết hàng">Hết hàng</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-gray-700 font-medium">Đánh giá</label>
                            <input type="number" name="rating" min="0" max="5" class="border rounded w-full py-2 px-3 focus:outline-none focus:border-green-500">
                        </div>
                        <div class="flex items-center space-x-4">
                            <label class="block text-gray-700 font-medium">Mới</label>
                            <input type="checkbox" name="is_new" class="rounded">
                        </div>
                        <div class="flex items-center space-x-4">
                            <label class="block text-gray-700 font-medium">Nổi bật</label>
                            <input type="checkbox" name="is_featured" class="rounded">
                        </div>
                        <div class="flex items-center space-x-4">
                            <label class="block text-gray-700 font-medium">Bán chạy</label>
                            <input type="checkbox" name="is_best_seller" class="rounded">
                        </div>
                        <div class="flex items-center space-x-4">
                            <label class="block text-gray-700 font-medium">Bình thường</label>
                            <input type="checkbox" name="is_normal" class="rounded">
                        </div>
                    </div>
                    <button type="submit" class="mt-6 bg-green-500 text-white px-6 py-2 rounded-lg hover:bg-green-600 transition-colors duration-200">Thêm sản phẩm</button>
                </form>
            </div>
        </body>
        ';
    }


    public function getProductById($product_id)
    {
        try {
            $stmt = $this->pdo->prepare("SELECT name, price, image_url FROM coffee_products WHERE id = :product_id");
            $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    // Thêm phương thức getProductCount
    public function getProductCount()
    {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM coffee_products");
        return $stmt->fetchColumn(); // Trả về số lượng sản phẩm
    }
}
