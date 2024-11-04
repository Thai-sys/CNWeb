<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coffee Time</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="css/style.css"> <!-- Đường dẫn chính xác -->
    <script src="js/js.js" defer></script> <!-- Đường dẫn chính xác -->
</head>

<body class="bg-coffee text-coffee-light">
    <header class="bg-coffee-dark py-4 ">
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
                    <a class="text-coffee-light" href="/">HOME</a>
                </div>
                <div class="nav-item">
                    <a class="text-coffee-light" href="/about">ABOUT</a>
                </div>
                <div class="nav-item">
                    <a class="text-coffee-light" href="/product">PRODUCT</a>
                </div>
                <div class="nav-item">
                    <a class="text-coffee-light" href="/contact">CONTACT</a>
                </div>
            </nav>

            <div class="flex justify-between items-center">
                <div class="relative inline-block">
                    <i class="icon_search fa-solid fa-magnifying-glass left-3 top-1/2 transform -translate-y-1/2"></i>
                    <input class="input_search pl-10 pr-10" type="text" placeholder="Searching...">
                </div>
                <button type="button" class="relative cart inline-block ml-2" onclick="location.href='/cart'">
                    <span class="bg-coffee-dark text-white p-2 cursor-pointer no-hover cart-item">
                        <i class="fas fa-shopping-cart text-2xl"></i>
                    </span>
                    <?php if (!empty($_SESSION['cart_count']) && $_SESSION['cart_count'] > 0): ?>
                        <span
                            class="absolute top-0 right-0 transform translate-x-1/2 -translate-y-1/2 bg-red-600 text-white text-xs font-bold rounded-full w-6 h-6 flex items-center justify-center no-hover">
                            <?= $_SESSION['cart_count'] ?>
                        </span>
                    <?php endif; ?>
                </button>
                <?php if (isset($_SESSION['username'])): ?>
                    <div class="relative inline-block group">
                        <img src="/img/avatars/<?php echo htmlspecialchars($_SESSION['avatar_login']); ?>" alt="Avatar"
                            class="w-8 h-8 rounded-full ml-2 hover:cursor-pointer">
                        <span
                            class="absolute bg-white text-coffee-dark rounded hidden group-hover:block"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                    </div>
                    <a href="/logout" class="m-1 text-coffee-light hover:text-coffee"><i
                            class="fa-solid fa-right-from-bracket"></i></a>
                <?php else: ?>
                    <a href="/login"><i class="p-1 icon_user fa-solid fa-user"></i></a>
                <?php endif; ?>
            </div>

        </div>
        <button id="menu-toggle" class="text-coffee-light md:hidden">
            <i class="fas fa-bars"></i>
        </button>

        <nav id="dropdown-menu" class="sm:hidden hidden">
            <ul class="bg-coffee-light text-coffee-dark space-y-2 p-4">
                <li><a class="block hover:text-coffee" href="/">HOME</a></li>
                <li><a class="block hover:text-coffee" href="/about">ABOUT</a></li>
                <li><a class="block hover:text-coffee" href="/product">PRODUCT</a></li>
                <li><a class="block hover:text-coffee" href="/contact">CONTACT</a></li>
            </ul>
        </nav>
        <script>
            const menuToggle = document.getElementById('menu-toggle');
            const dropdownMenu = document.getElementById('dropdown-menu');
            menuToggle.addEventListener('click', () => {
                dropdownMenu.classList.toggle('hidden');
            });
        </script>
    </header>