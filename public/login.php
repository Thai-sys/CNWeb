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


    <script src="https://cdn.tailwindcss.com"></script>


    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet" />

    <link rel="stylesheet" href="style.css">
</head>

<body class="bg-coffee text-coffee-light">
    <header class="bg-coffee-dark py-4">
        <div class="container mx-auto flex justify-between items-center">
            <div class="text-center">
                <img alt="Coffee Time Logo" class="mx-auto" height="100"
                    src="https://storage.googleapis.com/a1aa/image/PC1fTEHtLTQlIajemnhwfKjwD9JqWFlKVJmhNY63L0iFktGnA.jpg"
                    width="100" />
                <h1 class="text-2xl font-bold">Coffee Time</h1>
                <p class="text-sm">100% Organic Quality Coffee</p>
            </div>




            <nav id="navbar" class="hidden md:flex space-x-6">
                <div class="nav-item">
                    <a class="text-coffee-light" href="index.php">HOME</a>
                </div>
                <div class="nav-item">
                    <a class="text-coffee-light" href="about.php">ABOUT</a>
                </div>
                <div class="nav-item">
                    <a class="text-coffee-light" href="product.php">PRODUCT</a>
                </div>
                <div class="nav-item">
                    <a class="text-coffee-light" href="contact.php">CONTACT</a>
                </div>
            </nav>



            <div class="flex justify-between items-center">
                <div class="relative inline-block">
                    <i class="icon_search fa-solid fa-magnifying-glass  left-3 top-1/2 transform -translate-y-1/2"></i>
                    <input class="input_search pl-10 pr-10" type="text" placeholder="Searching...">
                </div>

                <?php if (isset($_SESSION['username'])): ?>
                <div class="relative inline-block">
                    <img src="data:image/jpeg;base64,<?php echo htmlspecialchars($_SESSION['avatar']); ?>" alt="Avatar"
                        class="w-8 h-8 rounded-full ml-2 hover:cursor-pointer">


                    <span class="absolute  bg-white text-coffee-dark rounded hidden group-hover:block">
                        <?php echo htmlspecialchars($_SESSION['username']); ?>
                    </span>
                </div>
                <a href="logout.php" class="m-1 text-coffee-light hover:text-coffee"><i
                        class="fa-solid fa-right-from-bracket"></i></a>
                <?php else: ?>
                <a href="login.php"><i class="p-1 icon_user fa-solid fa-user"></i></a>
                <?php endif; ?>
            </div>
        </div>
        <button id="menu-toggle" class="text-coffee-light md:hidden">
            <i class="fas fa-bars"></i>
        </button>

        <nav id="dropdown-menu" class="sm:hidden hidden">
            <ul class="bg-coffee-light text-coffee-dark space-y-2 p-4">
                <li><a class="block hover:text-coffee" href="index.php">HOME</a></li>
                <li><a class="block hover:text-coffee" href="about.php">ABOUT</a></li>
                <li><a class="block hover:text-coffee" href="product.php">PRODUCT</a></li>
                <li><a class="block hover:text-coffee" href="contact.php">CONTACT</a></li>
            </ul>
        </nav>
        <script>
        const menuToggle = document.getElementById('menu-toggle');
        const dropdownMenu = document.getElementById('dropdown-menu');
        const navbar = document.getElementById('navbar');


        menuToggle.addEventListener('click', () => {

            dropdownMenu.classList.toggle('hidden');

        });
        </script>
    </header>

    <main class="flex items-center justify-center h-screen">
        <div class="login-form bg-coffee-light p-8 rounded-lg shadow-lg w-full max-w-md">
            <h2 class="text-2xl font-bold mb-4 text-center">Đăng Nhập</h2>

            <!-- Hiển thị thông báo lỗi -->
            <?php if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($error)): ?>
            <p class="text-red-500 mb-4 text-center"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>

            <!-- Form đăng nhập -->
            <form method="POST" action="login.php" enctype="multipart/form-data">
                <div class="mb-4">
                    <label for="username" class="block text-sm font-medium">Tên Đăng Nhập</label>
                    <input type="text" id="username" name="username" required
                        class="mt-1 p-2 border border-gray-300  text-gray-900 rounded w-full focus:ring-2 focus:ring-coffee-light" />
                </div>
                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium">Mật Khẩu</label>
                    <input type="password" id="password" name="password" required
                        class="mt-1 p-2 border border-gray-300 text-gray-900 rounded w-full focus:ring-2 focus:ring-coffee-light" />
                </div>

                <button type="submit"
                    class="bg-coffee-dark text-coffee-light py-2 px-4 rounded w-full hover:bg-coffee-light transition duration-300">Đăng
                    Nhập</button>
            </form>

            <p class="mt-4 text-center">
                Chưa có tài khoản? <a href="signin.php" class="text-coffee-dark underline hover:text-coffee-light">Đăng
                    Ký</a>
            </p>
        </div>
    </main>

    <footer class="bg-coffee py-4">
        <div class="container mx-auto flex justify-between items-center">
            <div class="flex space-x-4">
                <a class="text-coffee-light hover:text-coffee" href="#">PRIVACY POLICY</a>
                <a class="text-coffee-light hover:text-coffee" href="#">TERMS OF USE</a>
            </div>
            <div class="flex space-x-4">
                <a class="text-coffee-light hover:text-coffee" href="#"><i class="fab fa-facebook-f"></i></a>
                <a class="text-coffee-light hover:text-coffee" href="#"><i class="fab fa-twitter"></i></a>
                <a class="text-coffee-light hover:text-coffee" href="#"><i class="fab fa-instagram"></i></a>
            </div>
        </div>
    </footer>
</body>

</html>