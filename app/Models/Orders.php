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
    // Lấy thông tin tất cả đơn hàng
    public function getAllOrders()
    {
        $stmt = $this->db->query("SELECT * FROM orders");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Xóa đơn hàng
    public function deleteOrder($orderId)
    {
        $stmt = $this->db->prepare("DELETE FROM orders WHERE id = ?");
        return $stmt->execute([$orderId]);
    }
    
    // Hiển thị đơn hàng
    public function displayOrders($order)
    {
        // Giả sử 'items' là một cột chứa thông tin sản phẩm dưới dạng JSON
        $items = json_decode($order['items'], true)['products'];
        $orderTotal = 0;

        echo '<div class="bg-white shadow-lg rounded-lg p-6 mb-6">';
        echo '<h2 class="text-2xl font-semibold text-gray-800">Đơn Hàng</h2>';
        echo '<p class="mt-2 text-sm text-gray-600"><strong>Tên người nhận:</strong> ' . htmlspecialchars($order['name']) . '</p>';
        echo '<p class="text-sm text-gray-600"><strong>Địa chỉ:</strong> ' . htmlspecialchars($order['address']) . '</p>';
        echo '<p class="text-sm text-gray-600"><strong>Số điện thoại:</strong> ' . htmlspecialchars($order['phone']) . '</p>';
        echo '<h3 class="font-semibold mt-4 text-lg text-gray-800">Sản phẩm:</h3>';

        // Hiển thị các sản phẩm
        foreach ($items as $item) {
            $product = $this->productModel->getProductById($item['id']);

            if ($product) {
                $itemTotal = $product['price'] * $item['quantity'];
                $orderTotal += $itemTotal;

                echo '<div class="flex items-center justify-between border-b border-gray-300 py-4">';
                echo '<div class="flex items-center">';
                echo '<img src="../' . htmlspecialchars($product['image_url']) . '" alt="' . htmlspecialchars($product['name']) . '" class="w-20 h-20 mr-4 rounded-md">';
                echo '<div>';
                echo '<strong class="text-gray-700">' . htmlspecialchars($product['name']) . '</strong><br>';
                echo '<span class="text-sm text-gray-500">Giá: ' . number_format($product['price']) . '₫, Số lượng: ' . $item['quantity'] . '</span>';
                echo '</div></div>';
                echo '<span class="font-semibold text-gray-800">' . number_format($itemTotal) . '₫</span>';
                echo '</div>';
            } else {
                echo '<div class="text-red-600">Sản phẩm không tồn tại.</div>';
            }
        }

        echo '<h4 class="font-semibold mt-4 text-xl text-red-600">Tổng tiền: ' . number_format($orderTotal) . '₫</h4>';

        // Hiển thị trạng thái đơn hàng và nút tương ứng
        if ($order['status'] == 0) {
            // Chưa xử lý
            echo '<form action="/admin/order" method="POST" class="mt-4">';
            echo '<input type="hidden" name="orderId" value="' . $order['id'] . '">';
            echo '<button type="submit" class="bg-blue-500 text-white py-2 px-6 rounded-lg hover:bg-blue-600 focus:outline-none transition">Đã xử lý</button>';
            echo '</form>';
        } elseif ($order['status'] == 1) {
            // Đã xử lý
            echo '<form action="/admin/order" method="POST" class="mt-4">';
            echo '<input type="hidden" name="orderId" value="' . $order['id'] . '">';
            echo '<button type="submit" class="bg-red-500 text-white py-2 px-6 rounded-lg hover:bg-red-600 focus:outline-none transition">Xóa</button>';
            echo '</form>';
        }

        echo '</div>';
    }
    
    public function displayPendingOrders()
    {
        // Lấy danh sách đơn hàng chưa xử lý
        $stmt = $this->db->prepare("SELECT * FROM orders WHERE status = 0");
        $stmt->execute();
        $pendingOrders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($pendingOrders) {
            foreach ($pendingOrders as $order) {
                $this->displayOrders($order);
            }
        } else {
            echo '<p class="text-gray-500 text-center">Không có đơn đặt hàng chưa xử lý nào để hiển thị.</p>';
        }
    }


    public function displayProcessedOrders()
    {
        // Lấy danh sách đơn hàng đã xử lý
        $stmt = $this->db->prepare("SELECT * FROM orders WHERE status = 1");
        $stmt->execute();
        $processedOrders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($processedOrders) {
            foreach ($processedOrders as $order) {
                $this->displayOrders($order);
            }
        } else {
            echo '<p class="text-gray-500 text-center">Không có đơn đặt hàng nào đã xử lý.</p>';
        }
    }


    public function getOrderStatus($orderId)
    {
        $stmt = $this->db->prepare("SELECT status FROM orders WHERE id = ?");
        $stmt->execute([$orderId]);
        return $stmt->fetchColumn(); // Trả về giá trị status
    }

    public function updateOrderStatus($orderId, $status)
    {
        $sql = "UPDATE orders SET status = :status WHERE id = :id";
        $stmt = $this->db->prepare($sql);

        // Gán giá trị cho tham số
        $stmt->bindParam(':status', $status, PDO::PARAM_INT);
        $stmt->bindParam(':id', $orderId, PDO::PARAM_INT);

        return $stmt->execute();
    }
    // Hàm lấy tổng doanh thu từ các đơn hàng đã xử lý
    public function getTotalRevenue()
    {
        $stmt = $this->db->prepare("SELECT SUM(total_price) FROM orders WHERE status = 1");
        $stmt->execute();
        return $stmt->fetchColumn(); // Trả về tổng doanh thu
    }

    // Hàm lấy số lượng đơn hàng đang chờ xử lý
    public function getPendingOrderCount()
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM orders WHERE status = 0");
        $stmt->execute();
        return $stmt->fetchColumn(); // Trả về số lượng đơn hàng đang chờ
    }
}
