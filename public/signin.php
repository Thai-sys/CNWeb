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
                            class="mt-1 p-2 border border-gray-300 text-gray-900 rounded w-full" />
                    </div>
                    <div class="mb-4">
                        <label for="password" class="block text-sm font-medium">Mật Khẩu</label>
                        <input type="password" id="password" name="password" required
                            class="mt-1 p-2 border border-gray-300 text-gray-900 rounded w-full" />
                    </div>
                    <div class="mb-4">
                        <label for="avatar" class="block text-sm font-medium">Chọn Hình Ảnh Đại Diện</label>
                        <input type="file" id="avatar" name="avatar" accept="image/*"
                            class="mt-1 p-2 border border-gray-300 rounded w-full" />
                    </div>
                    <button type="submit" class="bg-coffee-dark text-coffee-light py-2 px-4 rounded w-full">Đăng
                        Ký</button>
                </form>

                <p class="mt-4 text-center">
                    Đã có tài khoản? <a href="login.php" class="text-coffee-dark underline">Đăng Nhập</a>
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