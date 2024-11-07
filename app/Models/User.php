<?php
// app/Models/User.php

class User
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function findById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM user_data WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC); // Trả về một mảng kết quả hoặc null nếu không tìm thấy
    }
    public function findByUsername($username)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM user_data WHERE user_name = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC); // Trả về một mảng kết quả hoặc null nếu không tìm thấy
    }

    public function register($username, $hashedPassword, $avatarPath) // Thêm $avatarPath vào tham số
    {
        // Chuẩn bị câu lệnh SQL để thêm người dùng mới
        $stmt = $this->pdo->prepare("INSERT INTO user_data (user_name, user_password, avatar) VALUES (:username, :user_password, :avatar)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':user_password', $hashedPassword);
        $stmt->bindParam(':avatar', $avatarPath); // Sử dụng $avatarPath đã được truyền vào
        return $stmt->execute(); // Trả về true nếu thực hiện thành công, false nếu không
    }

    // trả về mảng người dùng 
    public function getAllUsers()
    {
        $stmt = $this->pdo->query("SELECT  id, user_name, avatar FROM user_data");
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Trả về mảng chứa tất cả người dùng
    }


    // Thêm phương thức xóa người dùng khỏi cơ sở dữ liệu
    private function deleteUserFromDB($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM user_data WHERE id = :id");
        $stmt->bindParam(':id', $id);
        return $stmt->execute(); // Trả về true nếu xóa thành công, false nếu không
    }
    public function deleteUser()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];

            // Lấy thông tin người dùng trước khi xóa
            $user = $this->findById($id); // Thêm phương thức findById vào User model để lấy thông tin người dùng

            if ($user) {
                // Đường dẫn đến file avatar
                $avatarPath = '../public/img/avatars/' . $user['avatar'];

                // Xóa file avatar nếu tồn tại
                if (file_exists($avatarPath)) {
                    unlink($avatarPath); // Xóa file avatar
                }
                  // Xóa các bản ghi liên quan trong bảng cart_items
                  $stmtCartItems = $this->pdo->prepare("DELETE FROM cart_items WHERE user_id = :user_id");
                  $stmtCartItems->bindParam(':user_id', $id);
                  $stmtCartItems->execute();
                  // Xóa các bản ghi liên quan trong bảng orders (nếu có)
                $stmtOrders = $this->pdo->prepare("DELETE FROM orders WHERE user_id = :user_id");
                $stmtOrders->bindParam(':user_id', $id);
                $stmtOrders->execute();
                // Xóa người dùng khỏi cơ sở dữ liệu
                $this->deleteUserFromDB($id);
                // Chuyển hướng về trang danh sách người dùng
                header('Location: /admin');
                exit();
            } else {
                echo "Người dùng không tồn tại."; // Thông báo nếu không tìm thấy người dùng
            }
        }
    }
    
    public function renderUserList()
    {
        $users = $this->getAllUsers(); // Lấy danh sách người dùng

        if (empty($users)) {
            echo "<p class='text-center text-gray-500'>Không có người dùng nào.</p>";
            return;
        }

        echo "<div class='overflow-x-auto bg-white shadow-lg rounded-lg'>";  // Tạo khung cho bảng
        echo "<table class='min-w-full table-auto'>";  // Thêm table-auto để điều chỉnh cột tự động

        // Tiêu đề bảng
        echo "<thead class='bg-gray-200 text-gray-700'>";
        echo "<tr>";
        echo "<th class='py-3 px-6 border-b text-left text-sm font-medium'>Avatar</th>";
        echo "<th class='py-3 px-6 border-b text-left text-sm font-medium'>Tên người dùng</th>";
        echo "<th class='py-3 px-6 border-b text-left text-sm font-medium'>Thao tác</th>";
        echo "</tr>";
        echo "</thead>";

        // Dữ liệu bảng
        echo "<tbody>";
        foreach ($users as $user) {
            echo "<tr class='hover:bg-gray-50'>";  // Thêm hiệu ứng hover khi di chuột qua dòng
            echo "<td class='py-3 px-6 border-b text-center'>";
            echo "<img src='../img/avatars/{$user['avatar']}' alt='Avatar' class='rounded-full w-12 h-12 object-cover'>";  // Avatar hình tròn
            echo "</td>";
            echo "<td class='py-3 px-6 border-b'>{$user['user_name']}</td>";
            echo "<td class='py-3 px-6 border-b'>";
            echo "<form action='/admin/user/delete' method='POST' style='display:inline;' onsubmit='return confirm(\"Bạn có chắc chắn muốn xóa người dùng này, Sau khi xóa các đơn hàng liên quan đến người dùng này sẽ bị xóa?\");'>";
            echo "<input type='hidden' name='id' value='{$user['id']}'>";
            echo "<button type='submit' class='text-red-600 hover:text-red-800 focus:outline-none text-sm'>Xóa</button>";  // Nút xóa với hiệu ứng hover
            echo "</form>";
            echo "</td>";
            echo "</tr>";
        }
        echo "</tbody>";
        echo "</table>";
        echo "</div>";
    }
    
    // Thêm phương thức getUserCount
    public function getUserCount()
    {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM user_data");
        return $stmt->fetchColumn(); // Trả về số lượng người dùng
    }
}
