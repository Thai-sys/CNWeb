<?php
session_start(); // Khởi động phiên
// Thông tin kết nối cơ sở dữ liệu
$servername = "localhost";  // Tên máy chủ
$username = "root";         // Tên đăng nhập MySQL
$password = "";             // Mật khẩu MySQL (để trống nếu dùng XAMPP)
$dbname = "coffee-time";    // Tên cơ sở dữ liệu

// Tạo kết nối
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Khởi tạo biến lỗi
$error = '';

// Xử lý form đăng nhập
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_name = $_POST['username'];
    $user_password = $_POST['password'];

    // Truy vấn để kiểm tra thông tin đăng nhập
    $sql = "SELECT * FROM user_datas WHERE user_name=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $user_name);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc(); // Lấy dữ liệu người dùng

        // Kiểm tra mật khẩu
        if (password_verify($user_password, $row['user_password'])) {
            // Đăng nhập thành công
            $_SESSION['username'] = $row['user_name'];
            $_SESSION['avatar'] = base64_encode($row['avatar']); // Lưu avatar vào phiên dưới dạng chuỗi base64

            header("Location: index.php"); // Chuyển hướng đến trang index.php
            exit(); // Dừng thực thi mã sau khi chuyển hướng
        } else {
            // Lưu thông báo lỗi nếu mật khẩu không đúng
            $error = "Tên đăng nhập hoặc mật khẩu không đúng.";
        }
    } else {
        // Lưu thông báo lỗi nếu không tìm thấy người dùng
        $error = "Tên đăng nhập hoặc mật khẩu không đúng.";
    }
}

// Đóng kết nối
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập - Coffee Time</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet" />

    <!-- Custom CSS -->
    <link rel="stylesheet" href="style.css">
</head>

<body class="bg-coffee text-coffee-light">
    <header class="bg-coffee-dark py-4">
        <div class="container mx-auto text-center">
            <h1 class="text-3xl font-bold">Coffee Time</h1>
            <p class="text-sm">Đăng Nhập Để Mua Sắm</p>
        </div>
    </header>

    <main class="flex items-center justify-center h-screen">
        <div class="bg-coffee-light p-8 rounded-lg shadow-lg w-full max-w-md">
            <h2 class="text-2xl font-bold mb-4 text-center">Đăng Nhập</h2>

            <?php if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($error)): ?>
                <p class="text-red-500 mb-4 text-center"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>

            <form method="POST" action="login.php" enctype="multipart/form-data">
                <div class="mb-4">
                    <label for="username" class="block text-sm font-medium">Tên Đăng Nhập</label>
                    <input type="text" id="username" name="username" required class="mt-1 p-2 border border-gray-300 rounded w-full" />
                </div>
                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium">Mật Khẩu</label>
                    <input type="password" id="password" name="password" required class="mt-1 p-2 border border-gray-300 rounded w-full" />
                </div>
                
                <button type="submit" class="bg-coffee-dark text-coffee-light py-2 px-4 rounded w-full">Đăng Nhập</button>
            </form>

            <p class="mt-4 text-center">
                Chưa có tài khoản? <a href="signin.php" class="text-coffee-dark underline">Đăng Ký</a>
            </p>
        </div>
    </main>

    <footer class="bg-coffee py-4">
        <div class="container mx-auto text-center">
            <p class="text-sm">© 2024 Coffee Time. All rights reserved.</p>
        </div>
    </footer>
</body>

</html>