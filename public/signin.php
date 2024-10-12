<?php

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

// Xử lý form đăng ký
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_name = $_POST['username'];
    $user_password = $_POST['password'];

    // Mã hóa mật khẩu
    $hashed_password = password_hash($user_password, PASSWORD_DEFAULT);

    $avatar = null;
    // Kiểm tra xem có tệp được tải lên hay không
    if (isset($_FILES['avatar'])) {
        // Kiểm tra kích thước tệp (dưới 200kb)
        if ($_FILES['avatar']['size'] > 0.5 * 1024 * 1024) {
            $error = "Kích thước hình ảnh phải dưới 500kb.";
        } else {
            // Đọc nội dung tệp hình ảnh
            $avatar = file_get_contents($_FILES['avatar']['tmp_name']);
        }
    }

    // Kiểm tra xem tên đăng nhập đã tồn tại hay chưa
    $sql_check = "SELECT * FROM user_datas WHERE user_name=?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("s", $user_name);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        // Tên đăng nhập đã tồn tại
        $error = "Tên đăng nhập đã tồn tại.";
    } elseif (empty($avatar) && isset($_FILES['avatar']) && $_FILES['avatar']['error'] == 0) {
        // Nếu không có avatar được tải lên
        $error = "Có lỗi xảy ra khi tải lên hình ảnh.";
    } else {
        // Nếu tên đăng nhập chưa tồn tại, thêm người dùng mới
        $sql_insert = "INSERT INTO user_datas (user_name, user_password, avatar) VALUES (?, ?, ?)";
        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_insert->bind_param("sss", $user_name, $hashed_password, $avatar);
        
        if ($stmt_insert->execute()) {
            // Chuyển hướng về trang đăng nhập
            header("Location: login.php");
            exit();
        } else {
            $error = "Có lỗi xảy ra. Vui lòng thử lại.";
        }
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
    <title>Đăng Ký - Coffee Time</title>

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
            <p class="text-sm">Đăng Ký Tài Khoản</p>
        </div>
    </header>

    <main class="flex items-center justify-center h-screen">
        <div class="bg-coffee-light p-8 rounded-lg shadow-lg w-full max-w-md">
            <h2 class="text-2xl font-bold mb-4 text-center">Đăng Ký</h2>

            <?php if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($error)): ?>
                <p class="text-red-500 mb-4 text-center"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>

            <form method="POST" action="signin.php" enctype="multipart/form-data">
                <div class="mb-4">
                    <label for="username" class="block text-sm font-medium">Tên Đăng Nhập</label>
                    <input type="text" id="username" name="username" required
                        class="mt-1 p-2 border border-gray-300 rounded w-full" />
                </div>
                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium">Mật Khẩu</label>
                    <input type="password" id="password" name="password" required
                        class="mt-1 p-2 border border-gray-300 rounded w-full" />
                </div>
                <div class="mb-4">
                    <label for="avatar" class="block text-sm font-medium">Chọn Hình Ảnh Đại Diện</label>
                    <input type="file" id="avatar" name="avatar" accept="image/*"
                        class="mt-1 p-2 border border-gray-300 rounded w-full" />
                </div>
                <button type="submit" class="bg-coffee-dark text-coffee-light py-2 px-4 rounded w-full">Đăng Ký</button>
            </form>

            <p class="mt-4 text-center">
                Đã có tài khoản? <a href="login.php" class="text-coffee-dark underline">Đăng Nhập</a>
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