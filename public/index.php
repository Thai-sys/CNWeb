<?php
// Gọi file db.php để kết nối cơ sở dữ liệu
include '../src/php/db.php';

// Truy vấn lấy dữ liệu từ bảng coffee_products (giả sử bảng này có các cột name, description, price, image_url)
$sql = "SELECT name, description, price, image_url FROM coffee_products";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coffee Time</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet" />

    <!-- Custom CSS -->
    <link rel="stylesheet" href="style.css">
</head>

<body class="bg-coffee text-coffee-light">
    <header class="bg-coffee-dark py-4">
        <div class="container mx-auto flex justify-between items-center">
            <div class="text-center">
                <img alt="Coffee Time Logo" class="mx-auto" height="100" src="https://storage.googleapis.com/a1aa/image/PC1fTEHtLTQlIajemnhwfKjwD9JqWFlKVJmhNY63L0iFktGnA.jpg" width="100"/>
                <h1 class="text-2xl font-bold">Coffee Time</h1>
                <p class="text-sm">100% Organic Quality Coffee</p>
            </div>
            <nav class="flex space-x-6">
                <a class="text-coffee-light hover:text-coffee" href="#">HOME</a>
                <a class="text-coffee-light hover:text-coffee" href="#">ABOUT</a>
                <a class="text-coffee-light hover:text-coffee" href="#">OUR COFFEE</a>
                <a class="text-coffee-light hover:text-coffee" href="#">EDUCATION</a>
                <a class="text-coffee-light hover:text-coffee" href="#">EDITORIAL</a>
                <a class="text-coffee-light hover:text-coffee" href="#">CONTACT</a>
            </nav>
            <div>
        <div class="search"></div>


        <div class="relative inline-block ">
          
            <i class="icon_search fa-solid fa-magnifying-glass absolute left-2 top-1/2 transform -translate-y-1/2 "></i>
            <input class="input_search pl-10 pr-10" type="text" placeholder="Searching...">
        </div>

            <a href="dangnhap.html"><i class="icon_user fa-solid fa-user"></i></a>
            
        </div>
      </div>

        </div>

        
    </header>

    <!-- Main Content -->
    <main class="text-center py-16 bg-cover" style="background-image: url('https://placehold.co/1200x400');">
        <h2 class="text-4xl font-bold mb-4">LOREM IPSUM DOLOR SIT AMET</h2>
        <p class="text-lg mb-6">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer ac mi libero.</p>
        <button class="bg-coffee-light text-coffee-dark py-2 px-6 rounded">READ MORE</button>
    </main>

    <!-- Products Section -->
    <section class="py-16 bg-coffee-dark">
        <div class="container mx-auto grid grid-cols-3 gap-8 text-center">
            <?php
            // Kiểm tra nếu có kết quả từ truy vấn
            if ($result->num_rows > 0) {
                // Lặp qua từng dòng dữ liệu và hiển thị
                while($row = $result->fetch_assoc()) {
                    echo '<div class="bg-coffee-light p-8 rounded">';
                    echo '<img alt="' . htmlspecialchars($row["image_url"]) . '" class="mx-auto mb-4" height="100" src="' . htmlspecialchars($row["image_url"]) . '" width="100" />';
                    echo '<h3 class="text-2xl font-bold mb-2">' . htmlspecialchars($row["name"]) . '</h3>';
                    echo '<p>' . htmlspecialchars($row["description"]) . '</p>';
                    echo '<p class="font-bold text-lg">Giá: ' . number_format($row["price"], 0, ',', '.') . ' VND</p>';
                    echo '</div>';
                }
            } else {
                echo "<p>Không có sản phẩm nào để hiển thị.</p>";
            }

            // Đóng kết nối
            $conn->close();
            ?>
        </div>
    </section>

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