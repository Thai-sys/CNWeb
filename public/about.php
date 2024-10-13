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


    <div class="relative isolate overflow-hidden  py-24 sm:py-32">
        <img src="https://images.unsplash.com/photo-1521737604893-d14cc237f11d?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&crop=focalpoint&fp-y=.8&w=2830&h=1500&q=80&blend=111827&sat=-100&exp=15&blend-mode=multiply"
            alt="" class="absolute inset-0 -z-10 h-full w-full object-cover object-right md:object-center">

        <div class="hidden sm:absolute sm:-top-10 sm:right-1/2 sm:-z-10 sm:mr-10 sm:block sm:transform-gpu sm:blur-3xl"
            aria-hidden="true">
            <div class="aspect-[1097/845] w-[68.5625rem] bg-gradient-to-tr from-[#ff4694] to-[#776fff] opacity-20"
                style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)">
            </div>
        </div>

        <div class="absolute -top-52 left-1/2 -z-10 -translate-x-1/2 transform-gpu blur-3xl sm:top-[-28rem] sm:ml-16 sm:translate-x-0 sm:transform-gpu"
            aria-hidden="true">
            <div class="aspect-[1097/845] w-[68.5625rem] bg-gradient-to-tr from-[#ff4694] to-[#776fff] opacity-20"
                style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)">
            </div>
        </div>

        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="mx-auto max-w-2xl lg:mx-0">
                <h2 class="text-4xl font-bold tracking-tight text-white sm:text-6xl">about us</h2>
                <p class="mt-6 text-lg leading-8 text-gray-300">Chào mừng đến với Coffee Time!<br>
                    Tại Coffee Time, chúng tôi không chỉ cung cấp cà phê; chúng tôi tạo ra những trải nghiệm. Được thành
                    lập với sứ mệnh mang đến cho bạn những hương vị cà phê tươi ngon nhất từ những nguồn nguyên liệu hữu
                    cơ chất lượng hàng đầu, chúng tôi tự hào là một phần trong hành trình thưởng thức cà phê của bạn.
                </p>
            </div>

            <div class="mx-auto mt-10 max-w-2xl lg:mx-0 lg:max-w-none">
                <div
                    class="grid grid-cols-1 gap-x-8 gap-y-6 text-base font-semibold leading-7 text-white sm:grid-cols-2 md:flex lg:gap-x-10">
                    <a href="index.php">Home <span aria-hidden="true">&rarr;</span></a>
                    <a href="product.php">Product<span aria-hidden="true">&rarr;</span></a>
                    <a href="contact.php">contact <span aria-hidden="true">&rarr;</span></a>
                    <a href="#">Meet our leadership <span aria-hidden="true">&rarr;</span></a>
                </div>
                <dl class="mt-16 grid grid-cols-1 gap-8 sm:mt-20 sm:grid-cols-2 lg:grid-cols-4">
                    <div class="flex flex-col-reverse">
                        <dt class="text-base leading-7 text-gray-300">Offices worldwide</dt>
                        <dd class="text-2xl font-bold leading-9 tracking-tight text-white">12</dd>
                    </div>
                    <div class="flex flex-col-reverse">
                        <dt class="text-base leading-7 text-gray-300">Full-time colleagues</dt>
                        <dd class="text-2xl font-bold leading-9 tracking-tight text-white">300+</dd>
                    </div>
                    <div class="flex flex-col-reverse">
                        <dt class="text-base leading-7 text-gray-300">Hours per week</dt>
                        <dd class="text-2xl font-bold leading-9 tracking-tight text-white">40</dd>
                    </div>
                    <div class="flex flex-col-reverse">
                        <dt class="text-base leading-7 text-gray-300">Paid time off</dt>
                        <dd class="text-2xl font-bold leading-9 tracking-tight text-white">Unlimited</dd>
                    </div>
                </dl>
            </div>
        </div>
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