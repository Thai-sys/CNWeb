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
        $stmt = $this->pdo->prepare("SELECT * FROM user_datas WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC); // Trả về một mảng kết quả hoặc null nếu không tìm thấy
    }
    public function findByUsername($username)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM user_datas WHERE user_name = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC); // Trả về một mảng kết quả hoặc null nếu không tìm thấy
    }

    public function register($username, $hashedPassword, $avatarPath) // Thêm $avatarPath vào tham số
    {
        // Chuẩn bị câu lệnh SQL để thêm người dùng mới
        $stmt = $this->pdo->prepare("INSERT INTO user_datas (user_name, user_password, avatar) VALUES (:username, :user_password, :avatar)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':user_password', $hashedPassword);
        $stmt->bindParam(':avatar', $avatarPath); // Sử dụng $avatarPath đã được truyền vào
        return $stmt->execute(); // Trả về true nếu thực hiện thành công, false nếu không
    }


    // Thêm phương thức xóa người dùng khỏi cơ sở dữ liệu
    private function deleteUserFromDB($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM user_datas WHERE id = :id");
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

                // Xóa người dùng khỏi cơ sở dữ liệu
                $this->deleteUserFromDB($id);
                header('Location: /admin'); // Chuyển hướng về trang danh sách người dùng
                exit();
            } else {
                echo "Người dùng không tồn tại."; // Thông báo nếu không tìm thấy người dùng
            }
        }
    }
}
