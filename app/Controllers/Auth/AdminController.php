<?php

require_once '../app/Models/Product.php';
require_once '../app/Models/User.php';
require_once '../app/Models/Contact.php';
require_once '../app/Models/Orders.php';
class AdminController
{
    private $productModel;
    private $userModel;
    private $contactModel;
    private $orderModel;
    public function __construct($db)
    {
        $this->productModel = new Product($db);
        $this->userModel = new User($db);
        $this->contactModel = new Contact($db);
        $this->orderModel = new Orders($db, $this->productModel);
    }

    public function index()
    {
        session_start();
        if (!isset($_SESSION['username'])) {
            $_SESSION['success'] = "Bạn chưa đăng nhập vui lòng đăng nhập để truy cập trang này!";
            header("Location:/");
            exit();
        } // Kiểm tra xem người dùng có phải là admin không
        if ($_SESSION['username'] !== 'admin') {
            echo "Bạn không có quyền truy cập trang này.";
            exit();
        }

        // Nếu đã đăng nhập và là admin, lấy dữ liệu cần thiết
        $userCount = $this->getUserCount();
        $productCount = $this->getProductCount();
        $totalRevenue = $this->getTotalRevenue();
        $pendingOrderCount = $this->getPendingOrderCount();

        // Điều hướng tới view
        include '../app/Views/admin.php';
    }

    public function addProduct()
    {
        session_start();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Kiểm tra và xử lý upload file
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $imageTmpPath = $_FILES['image']['tmp_name'];
                $imageName = basename($_FILES['image']['name']);
                $imagePath = '../public/img/' . $imageName;

                // Di chuyển file upload vào thư mục img
                if (move_uploaded_file($imageTmpPath, $imagePath)) {
                    // Nếu upload thành công, lấy tên file để lưu vào CSDL

                    $name = $_POST['name'];
                    $description = $_POST['description'];
                    $price = $_POST['price'];
                    $image_url = 'img/' . $imageName;
                    $coffee_type = $_POST['coffee_type'];
                    $origin = $_POST['origin'];
                    $weight = $_POST['weight'];
                    $flavor = $_POST['flavor'];
                    $production_date = $_POST['production_date'];
                    $expiry_date = $_POST['expiry_date'];
                    $status = $_POST['status'];
                    $rating = $_POST['rating'];
                    $quantity = $_POST['quantity'];
                    $is_new = isset($_POST['is_new']);
                    $is_featured = isset($_POST['is_featured']);
                    $is_best_seller = isset($_POST['is_best_seller']);
                    $is_normal = isset($_POST['is_normal']);

                    $this->productModel->addProduct($name, $description, $price, $image_url, $coffee_type, $origin, $weight, $flavor, $production_date, $expiry_date, $status, $rating, $quantity, $is_new, $is_featured, $is_best_seller, $is_normal);
                    // Thêm thông báo thành công vào session
                    $_SESSION['successAdmin'] = 'Sản phẩm đã được thêm thành công.';
                    header('Location: /admin'); // Chuyển hướng về trang quản lý
                    exit();
                } else {
                    // Xử lý lỗi khi upload file không thành công
                    echo "Có lỗi xảy ra khi upload file.";
                }
            } else {
                // Xử lý lỗi upload file
                echo "Vui lòng chọn file để upload.";
            }
        }
    }

    public function deleteProduct()
    {
        session_start();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $this->productModel->deleteProduct($id);
            // Thêm thông báo thành công vào session
            $_SESSION['successAdmin'] = 'Sản phẩm đã được xóa thành công.';
            header('Location: /admin'); // Chuyển hướng về trang quản lý
            exit();
        }
    }
    public function deleteUser()
    {
        session_start();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Gọi phương thức deleteUser từ User model
            $this->userModel->deleteUser(); // Xóa người dùng
            // Thêm thông báo thành công vào session
            $_SESSION['successAdmin'] = 'Người dùng đã được xóa thành công.';
            header('Location: /admin'); // Chuyển hướng về trang quản lý
            exit();
        }
    }

    public function deleteContact()
    {
        session_start();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Lấy ID của liên hệ cần xóa
            $id = $_POST['id'];

            // Gọi phương thức deleteContact từ Contact model
            if ($this->contactModel->deleteContact($id)) {
                // Thêm thông báo thành công vào session
                $_SESSION['successAdmin'] = 'Liên hệ đã được xóa thành công.';
            } else {
                // Thêm thông báo lỗi vào session nếu có vấn đề xảy ra
                $_SESSION['errorAdmin'] = 'Có lỗi xảy ra khi xóa liên hệ.';
            }

            // Chuyển hướng về trang quản lý
            header('Location: /admin');
            exit();
        }
    }


    //hàm sử lý đơn hàng
    public function updateOrder()
    {
        session_start();

        // Lấy dữ liệu từ yêu cầu POST
        if (isset($_POST['orderId'])) {
            $orderId = intval($_POST['orderId']);
            // Kiểm tra trạng thái của đơn hàng trước khi xử lý
            $orderStatus = $this->orderModel->getOrderStatus($orderId);

            if ($orderStatus === 0) {
                // Nếu đơn hàng chưa được xử lý, cập nhật trạng thái thành 1
                $result = $this->orderModel->updateOrderStatus($orderId, 1); // Truyền `1` để cập nhật trạng thái
                $_SESSION['successAdmin'] = 'Đơn hàng đã được xử lý thành công!';
                header('Location: /admin');
            } else {
                // Nếu đơn hàng đã được xử lý, chuyển đổi nút thành nút xóa
                $result = $this->orderModel->deleteOrder($orderId); // Gọi phương thức deleteOrder để xóa
                $_SESSION['successAdmin'] = 'Đơn hàng đã được xóa thành công!';
                header('Location: /admin');
            }
        } else {
            // Nếu không có orderId trong POST, chuyển hướng về danh sách đơn hàng
            header('Location: /admin');
        }
    }

    public function getUserCount()
    {
        // Gọi phương thức getUserCount từ mô hình User
        $userCount = $this->userModel->getUserCount();
        return $userCount; // Trả về số lượng người dùng
    }

    public function getProductCount()
    {
        // Gọi phương thức getProductCount từ mô hình Product
        $productCount = $this->productModel->getProductCount();
        return $productCount; // Trả về số lượng sản phẩm
    }

    public function getTotalRevenue()
    {
        // Gọi phương thức getTotalRevenue từ mô hình Orders
        // Chỉ lấy tổng doanh thu của các hóa đơn đã xử lý (status = 1)
        $totalRevenue = $this->orderModel->getTotalRevenue();
        return $totalRevenue; // Trả về tổng doanh thu
    }

    public function getPendingOrderCount()
    {
        // Gọi phương thức getPendingOrderCount từ mô hình Orders
        // Chỉ lấy số đơn hàng đang chờ xử lý (status = 0)
        $pendingOrderCount = $this->orderModel->getPendingOrderCount();
        return $pendingOrderCount; // Trả về số đơn hàng đang chờ xử lý
    }
}
