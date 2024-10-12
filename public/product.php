<?php
session_start(); // Khởi động phiên

// Thông tin kết nối cơ sở dữ liệu
$servername = "localhost";  // Tên máy chủ
$username = "root";         // Tên đăng nhập MySQL
$password = "";             // Mật khẩu MySQL (để trống nếu dùng XAMPP)
$dbname = "coffee-time";    // Tên cơ sở dữ liệu


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

$sql_new = "SELECT name, description, price, image_url FROM coffee_products WHERE is_new = TRUE ORDER BY RAND() LIMIT 6";
$result_new = $conn->query($sql_new);


$sql_featured = "SELECT name, description, price, image_url FROM coffee_products WHERE is_featured = TRUE ORDER BY RAND() LIMIT 6";
$result_featured = $conn->query($sql_featured);


$sql_best_seller = "SELECT name, description, price, image_url FROM coffee_products WHERE is_best_seller = TRUE ORDER BY RAND() LIMIT 6";
$result_best_seller = $conn->query($sql_best_seller);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coffee Time</title>


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
                <a class="text-coffee-light hover:text-coffee" href="index.php">HOME</a>
                <a class="text-coffee-light hover:text-coffee" href="#">ABOUT</a>
                <a class="text-coffee-light hover:text-coffee" href="product.php">PRODUCT</a>
                <a class="text-coffee-light hover:text-coffee" href="#">CONTACT</a>
            </nav>
            

            <div class="flex justify-between items-center">
                <div class="relative inline-block">
                <i class="icon_search fa-solid fa-magnifying-glass  left-3 top-1/2 transform -translate-y-1/2"
                "></i>
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
                <li><a class="block hover:text-coffee" href="#">HOME</a></li>
                <li><a class="block hover:text-coffee" href="#">ABOUT</a></li>
                <li><a class="block hover:text-coffee" href="#">PRODUCT</a></li>
                <li><a class="block hover:text-coffee" href="#">CONTACT</a></li>
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
   
    
    <main>
    
        <div class="main_banner">

            <i onclick="prevSlide()" class="prev_button fa-solid fa-chevron-left"></i>
            <img class="banner_img " src="/img/banner/banner1.png" alt="">
            <img class="banner_img " src="/img/banner/banner2.png" alt="">

            <i onclick="nextSlide()" class="next_button fa-solid fa-chevron-right"></i>
        </div>
            
        <script src="js.js"></script>
        
        <section class="py-16 bg-coffee-dark" id="product-section">
            
        <div class="text-center my-4">
            <button onclick="showProducts('best_seller')"
                class="product-button mx-2 py-2 px-4 bg-coffee-dark text-coffee-light rounded">Sản phẩm bán
                chạy</button>
            <button onclick="showProducts('featured')"
                class="product-button mx-2 py-2 px-4 bg-coffee-dark text-coffee-light rounded">Sản phẩm nổi bật</button>
            <button onclick="showProducts('new')"
                class="product-button mx-2 py-2 px-4 bg-coffee-dark text-coffee-light rounded">Sản phẩm mới ra
                mắt</button>
            <button onclick="showProducts('domestic')"
                class="product-button mx-2 py-2 px-4 bg-coffee-dark text-coffee-light rounded">Sản phẩm nội địa</button>
            <button onclick="showProducts('imported')"
                class="product-button mx-2 py-2 px-4 bg-coffee-dark text-coffee-light rounded">Sản phẩm ngoại
                địa</button>
        </div>
            <div class="container mx-auto text-center" id="product-container">
                <h2 class="text-3xl font-bold mb-6" id="product-title">Sản phẩm bán chạy</h2>
                <div class="grid grid-cols-3 gap-8" id="best-seller-products">
                    <?php
                    if ($result_best_seller->num_rows > 0) {
                        while ($row = $result_best_seller->fetch_assoc()) {
                            echo '<div class="bg-coffee-light p-8 rounded">';
                            echo '<img alt="' . htmlspecialchars($row["image_url"]) . '" class="rounded-full mx-auto mb-4" height="100" src="' . htmlspecialchars($row["image_url"]) . '" width="100" />';
                            echo '<h3 class="text-2xl font-bold mb-2">' . htmlspecialchars($row["name"]) . '</h3>';
                            echo '<p>' . htmlspecialchars($row["description"]) . '</p>';
                            echo '<p class="font-bold text-lg">Giá: ' . number_format($row["price"], 0, ',', '.') . ' VND</p>';
                            echo '</div>';
                        }
                    }
                    ?>
                </div>

                <!-- Các phần sản phẩm khác sẽ được ẩn đi -->
                <div id="featured-products" class="hidden grid grid-cols-3 gap-8">
                    <?php
                    if ($result_featured->num_rows > 0) {
                        while ($row = $result_featured->fetch_assoc()) {
                            echo '<div class="bg-coffee-light p-8 rounded">';
                            echo '<img alt="' . htmlspecialchars($row["image_url"]) . '" class="rounded-full mx-auto mb-4" height="100" src="' . htmlspecialchars($row["image_url"]) . '" width="100" />';
                            echo '<h3 class="text-2xl font-bold mb-2">' . htmlspecialchars($row["name"]) . '</h3>';
                            echo '<p>' . htmlspecialchars($row["description"]) . '</p>';
                            echo '<p class="font-bold text-lg">Giá: ' . number_format($row["price"], 0, ',', '.') . ' VND</p>';
                            echo '</div>';
                        }
                    }
                    ?>
                </div>

                <div id="new-products" class="hidden grid grid-cols-3 gap-8">
                    <?php
                    if ($result_new->num_rows > 0) {
                        while ($row = $result_new->fetch_assoc()) {
                            echo '<div class="bg-coffee-light p-8 rounded">';
                            echo '<img alt="' . htmlspecialchars($row["image_url"]) . '" class="rounded-full mx-auto mb-4" height="100" src="' . htmlspecialchars($row["image_url"]) . '" width="100" />';
                            echo '<h3 class="text-2xl font-bold mb-2">' . htmlspecialchars($row["name"]) . '</h3>';
                            echo '<p>' . htmlspecialchars($row["description"]) . '</p>';
                            echo '<p class="font-bold text-lg">Giá: ' . number_format($row["price"], 0, ',', '.') . ' VND</p>';
                            echo '</div>';
                        }
                    }
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