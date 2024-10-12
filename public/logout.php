<?php
session_start(); // Khởi động phiên
session_unset(); // Xóa tất cả biến phiên
session_destroy(); // Hủy phiên
header("Location: index.php"); // Chuyển hướng về trang đăng nhập
exit(); // Dừng thực thi mã sau khi chuyển hướng
?>