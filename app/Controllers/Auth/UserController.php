<?php
require '../app/Models/User.php'; // Import Model User
require '../app/Models/Cart.php';
class UserController
{
    private $userModel;
    private $cart;
    public function __construct($db)
    {
        $this->userModel = new User($db);
        $this->cart = new Cart($db);
    }

    public function login()
    {
        session_start();
        // Kiểm tra thông báo đăng ký thành công
        $success_message = isset($_SESSION['success']) ? $_SESSION['success'] : null;
        if (isset($_SESSION['success'])) {
            unset($_SESSION['success']); // Xóa thông báo sau khi hiển thị
        }

        $error = '';

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $username = $_POST['username'];
            $password = $_POST['password'];

            //  tìm kiếm người dùng trong bảng user_data
            $row = $this->userModel->findByUsername($username);

            if ($row) {
                // Kiểm tra mật khẩu trong cơ sở dữ liệu
                if (password_verify($password, $row['user_password'])) {
                    // Đăng nhập thành công
                    $_SESSION['username'] = $row['user_name'];
                    $_SESSION['user_id'] = $row['id'];
                    $_SESSION['avatar_login'] = $row['avatar']; // Lưu avatar vào session
                    // Cập nhật số lượng sản phẩm trong giỏ hàng
                    $_SESSION['cart_count'] = $this->cart->getTotalQuantity($_SESSION['user_id']);
                    // Kiểm tra nếu tài khoản là admin với mật khẩu '123456'
                    if ($username === 'admin' && $password === '123456') {
                        // Chuyển hướng đến trang admin
                        header("Location: /admin");
                        exit();
                    } else {
                        header("Location: /"); // Chuyển hướng đến trang chính
                        exit();
                    }
                } else {
                    $error = "Tên đăng nhập hoặc mật khẩu không đúng.";
                }
            } else {
                $error = "Tên đăng nhập hoặc mật khẩu không đúng.";
            }
        }


        // Nếu có lỗi, bạn có thể xử lý ở đây (ví dụ: hiển thị thông báo lỗi)



        // Gọi các phần view
        $isLoggedIn = isset($_SESSION['user_id']);
        include '../app/Views/partials/header.php';
        include '../app/Views/auth/login.php';
        include '../app/Views/partials/footer.php';
    }

    public function logout()
    {
        session_start();
        // Xóa tất cả các biến phiên
        session_unset();
        // Hủy phiên
        session_destroy();
        // Chuyển hướng về trang đăng nhập
        header("Location:/login");
        exit();
    }

    public function register()
    {
        session_start();

        $error = '';
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $username = $_POST['username'];
            $password = $_POST['password'];
            $confirmPassword = $_POST['confirm_password'];

            // Kiểm tra mật khẩu
            if ($password !== $confirmPassword) {
                $error = "Mật khẩu không khớp.";
            } else {
                // Kiểm tra tên đăng nhập
                if ($this->userModel->findByUsername($username)) {
                    $error = "Tên đăng nhập đã tồn tại.";
                } else {
                    // Mã hóa mật khẩu
                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                    $avatarPath = null;

                    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
                        $fileTmpPath = $_FILES['avatar']['tmp_name'];
                        $fileName = $_FILES['avatar']['name'];
                        $fileSize = $_FILES['avatar']['size'];
                        $fileNameCmps = explode(".", $fileName);
                        $fileExtension = strtolower(end($fileNameCmps));

                        // Kiểm tra kích thước tệp
                        if ($fileSize <= 500 * 1024) {
                            $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
                            $uploadFileDir = '../public/img/avatars/';
                            $dest_path = $uploadFileDir . $newFileName;

                            // Di chuyển tệp
                            if (move_uploaded_file($fileTmpPath, $dest_path)) {
                                $avatarPath = $newFileName; // Đặt giá trị cho $avatarPath
                            } else {
                                $error = "Có lỗi xảy ra khi tải ảnh lên.";
                            }
                        } else {
                            $error = "Ảnh đại diện phải nhỏ hơn 500kb.";
                        }
                    }

                    // Lưu người dùng vào cơ sở dữ liệu
                    if ($this->userModel->register($username, $hashedPassword, $avatarPath)) {
                        $_SESSION['success'] = "Đăng ký thành công!";
                        header("Location:/login");
                        exit();
                    } else {
                        $error = "Có lỗi xảy ra, vui lòng thử lại.";
                    }
                }
            }
        }

        include '../app/Views/partials/header.php';
        include '../app/Views/auth/register.php';
        include '../app/Views/partials/footer.php';
    }
}
