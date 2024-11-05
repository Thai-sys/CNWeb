<?php
// public/index.php

require_once '../config/config.php'; // Kết nối với cơ sở dữ liệu
require_once '../src/Core/Router.php'; // Router

$router = new Router();

// Định nghĩa các route
$router->add('GET', '/', 'HomeController@index'); // Route cho trang chủ
$router->add('GET', '/product', 'ProductController@index'); // Route cho trang product
$router->add('GET', '/about', 'AboutController@index'); // Route cho trang about
$router->add('GET', '/contact', 'ContactController@showForm'); // Route hiển thị form liên hệ
$router->add('POST', '/contact', 'ContactController@handleFormSubmission'); // Route xử lý gửi form liên hệ
// route cho đăng nhập đăng kí
$router->add('GET', '/login', 'UserController@login'); // Route cho trang đăng nhập
$router->add('GET', '/register', 'UserController@register'); // Route cho trang đăng ký
$router->add('POST', '/login', 'UserController@login'); // Route cho đăng nhập (phương thức POST)
$router->add('POST', '/register', 'UserController@register'); // Route cho đăng ký (phương thức POST)
$router->add('GET', '/logout', 'UserController@logout'); // Route cho đăng xuất
// router cho giỏ hàng
$router->add('POST', '/cart/add', 'CartController@addToCart'); // Route cho thêm sản phẩm vào giỏ hàng
$router->add('POST', '/cart/update', 'CartController@updateCart'); // Route cho cập nhật giỏ hàng
$router->add('POST', '/cart/remove', 'CartController@removeFromCart'); // Route cho xóa sản phẩm khỏi giỏ hàng
$router->add('GET', '/cart', 'CartController@showCart'); // Route cho hiển thị giỏ hàng
$router->add('POST', '/cart/Orders', 'CartController@placeOrder'); // route sử lí đặt hàng
// ... các route khác ...


// Lấy URI và phương thức HTTP
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

// Tìm kiếm controller
$controller = $router->route($uri, $method);
if ($controller) {
    list($controllerName, $methodName) = explode('@', $controller);

    if ($controllerName === 'UserController') {
        require_once "../app/Controllers/Auth/UserController.php";
    } else {
        require_once "../app/Controllers/{$controllerName}.php"; // Đối với các controller khác
    }

    // Khởi tạo controller với PDO instance
    $controllerInstance = new $controllerName($pdo); // Truyền PDO instance
    // Gọi phương thức trong controller
    $controllerInstance->$methodName();
} else {
    http_response_code(404);
    echo "404 Not Found: Route not found.";
}
