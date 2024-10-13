<?php

$servername = "localhost";  // Tên máy chủ
$username = "root";         // Tên đăng nhập MySQL
$password = "";             // Mật khẩu MySQL (để trống nếu dùng XAMPP)
$dbname = "coffee-time";
// Tạo kết nối
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Xử lý dữ liệu form khi được gửi
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST['first-name'];
    $last_name = $_POST['last-name'];
    $company = $_POST['company'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone-number'];
    $message = $_POST['message'];

    // Chuẩn bị câu lệnh SQL để chèn dữ liệu
    $stmt = $conn->prepare("INSERT INTO contact (first_name, last_name, company, email, phone_number, message) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $first_name, $last_name, $company, $email, $phone_number, $message);

    // Thực hiện câu lệnh và kiểm tra kết quả
    if ($stmt->execute()) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Đóng câu lệnh
    $stmt->close();
}

// Đóng kết nối
$conn->close();
?>
<?php
// contact.php
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

    <div class="isolate  px-6 py-24 sm:py-32 lg:px-8">
        <div class="absolute inset-x-0 top-[-10rem] -z-10 transform-gpu overflow-hidden blur-3xl sm:top-[-20rem]"
            aria-hidden="true">
            <div class="relative left-1/2 -z-10 aspect-[1155/678] w-[36.125rem] max-w-none -translate-x-1/2 rotate-[30deg] bg-gradient-to-tr from-[#ff80b5] to-[#9089fc] opacity-30 sm:left-[calc(50%-40rem)] sm:w-[72.1875rem]"
                style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)">
            </div>
        </div>
        <div class="mx-auto max-w-2xl text-center">
            <h2 class="text-3xl font-bold tracking-tight text-white sm:text-4xl">Contact sales</h2>
            <p class="mt-2 text-lg leading-8 text-white">Aute magna irure deserunt veniam aliqua magna enim
                voluptate.</p>
        </div>
        <form action="#" method="POST" class="mx-auto mt-16 max-w-xl sm:mt-20">
            <div class="grid grid-cols-1 gap-x-8 gap-y-6 sm:grid-cols-2">
                <div>
                    <label for="first-name" class="block text-sm font-semibold leading-6 text-white">First
                        name</label>
                    <div class="mt-2.5">
                        <input type="text" name="first-name" id="first-name" autocomplete="given-name"
                            class="block w-full rounded-md border-0 px-3.5 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                    </div>
                </div>
                <div>
                    <label for="last-name" class="block text-sm font-semibold leading-6 text-white">Last name</label>
                    <div class="mt-2.5">
                        <input type="text" name="last-name" id="last-name" autocomplete="family-name"
                            class="block w-full rounded-md border-0 px-3.5 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                    </div>
                </div>
                <div class="sm:col-span-2">
                    <label for="company" class="block text-sm font-semibold leading-6 text-white">Company</label>
                    <div class="mt-2.5">
                        <input type="text" name="company" id="company" autocomplete="organization"
                            class="block w-full rounded-md border-0 px-3.5 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                    </div>
                </div>
                <div class="sm:col-span-2">
                    <label for="email" class="block text-sm font-semibold leading-6 text-white">Email</label>
                    <div class="mt-2.5">
                        <input type="email" name="email" id="email" autocomplete="email"
                            class="block w-full rounded-md border-0 px-3.5 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                    </div>
                </div>
                <div class="sm:col-span-2">
                    <label for="phone-number" class="block text-sm font-semibold leading-6 text-white">Phone
                        number</label>
                    <div class="relative mt-2.5">
                        <div class="absolute inset-y-0 left-0 flex items-center">
                            <label for="country" class="sr-only">Country</label>
                            <select id="country" name="country"
                                class="h-full rounded-md border-0 bg-transparent bg-none py-0 pl-4 pr-9 text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm">
                                <option>US</option>
                                <option>VN</option>
                                <option>EU</option>
                            </select>
                            <svg class="pointer-events-none absolute right-3 top-0 h-full w-5 text-gray-400"
                                viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" data-slot="icon">
                                <path fill-rule="evenodd"
                                    d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <input type="tel" name="phone-number" id="phone-number" autocomplete="tel"
                            class="block w-full rounded-md border-0 px-3.5 py-2 pl-20 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                    </div>
                </div>
                <div class="sm:col-span-2">
                    <label for="message" class="block text-sm font-semibold leading-6 text-white">Message</label>
                    <div class="mt-2.5">
                        <textarea name="message" id="message" rows="4"
                            class="block w-full rounded-md border-0 px-3.5 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"></textarea>
                    </div>
                </div>
                <div class="flex gap-x-4 sm:col-span-2">
                    <div class="flex h-6 items-center">
                        <button type="button"
                            class="flex w-8 flex-none cursor-pointer rounded-full bg-gray-200 p-px ring-1 ring-inset ring-gray-900/5 transition-colors duration-200 ease-in-out focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                            role="switch" aria-checked="false" aria-labelledby="switch-1-label">
                            <span class="sr-only">Agree to policies</span>
                            <span aria-hidden="true"
                                class="h-4 w-4 translate-x-0 transform rounded-full bg-white shadow-sm ring-1 ring-gray-900/5 transition duration-200 ease-in-out"></span>
                        </button>
                    </div>
                    <label class="text-sm leading-6 text-gray-600" id="switch-1-label">
                        By selecting this, you agree to our
                        <a href="#" class="font-semibold text-indigo-600">privacy&nbsp;policy</a>.
                    </label>
                </div>
            </div>
            <div class="mt-10">
                <button type="submit"
                    class="block w-full rounded-md bg-indigo-600 px-3.5 py-2.5 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                    Submit
                </button>
            </div>
        </form>
    </div>

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