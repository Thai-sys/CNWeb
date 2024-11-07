<?php
class Contact
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function emailExists($email)
    {
        $sql = "SELECT COUNT(*) FROM contact WHERE email = :email";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    public function save($firstName, $lastName, $email, $phoneNumber, $message)
    {
        if ($this->emailExists($email)) {
            echo "Email này đã được sử dụng. Vui lòng chọn email khác.";
            return false;
        }
        $sql = "INSERT INTO contact (first_name, last_name, email, phone, message) VALUES (:first_name, :last_name, :email, :phone, :message)";
        $stmt = $this->conn->prepare($sql);

        $stmt->bindParam(':first_name', $firstName);
        $stmt->bindParam(':last_name', $lastName);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phoneNumber);
        $stmt->bindParam(':message', $message);

        return $stmt->execute();
    }
    public function getAllContacts()
    {
        $sql = "SELECT * FROM contact";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Lấy tất cả bản ghi dưới dạng mảng kết hợp
    }
    public function deleteContact($id)
    {
        $sql = "DELETE FROM contact WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function displayContacts()
    {
        $contacts = $this->getAllContacts();
        echo '<div class="container mx-auto p-6">';

        // Tiêu đề
        echo '<h1 class="text-3xl font-semibold text-gray-800 mb-6">Danh sách Liên hệ</h1>';

        // Bảng danh sách liên hệ
        echo '<div class="overflow-x-auto bg-white shadow-md rounded-lg">';  // Thêm background trắng, shadow và rounded corners
        echo '<table class="min-w-full table-auto">';  // Thêm lớp table-auto để tự động điều chỉnh chiều rộng của các cột

        // Tiêu đề bảng
        echo '<thead>';
        echo '<tr class="bg-gray-200 text-gray-600 text-left">';  // Đặt màu nền cho tiêu đề bảng
        echo '<th class="py-3 px-6 border-b text-sm font-medium">Họ</th>';
        echo '<th class="py-3 px-6 border-b text-sm font-medium">Tên</th>';
        echo '<th class="py-3 px-6 border-b text-sm font-medium">Email</th>';
        echo '<th class="py-3 px-6 border-b text-sm font-medium">Số điện thoại</th>';
        echo '<th class="py-3 px-6 border-b text-sm font-medium">Tin nhắn</th>';
        echo '<th class="py-3 px-6 border-b text-sm font-medium">Hành động</th>';  // Thêm cột Hành động
        echo '</tr>';
        echo '</thead>';

        // Dữ liệu bảng
        echo '<tbody>';
        foreach ($contacts as $contact) {
            echo "<tr class='hover:bg-gray-50'>";  // Thêm hiệu ứng hover nhẹ
            echo "<td class='py-3 px-6 border-b text-sm'>{$contact['first_name']}</td>";
            echo "<td class='py-3 px-6 border-b text-sm'>{$contact['last_name']}</td>";
            echo "<td class='py-3 px-6 border-b text-sm'>{$contact['email']}</td>";
            echo "<td class='py-3 px-6 border-b text-sm'>{$contact['phone']}</td>";
            echo "<td class='py-3 px-6 border-b text-sm'>{$contact['message']}</td>";
            echo "<td class='py-3 px-6 border-b text-sm'>";
            echo "<form action='/admin/contact/delete' method='POST' onsubmit='return confirm(\"Bạn có chắc chắn muốn xóa liên hệ này không?\");'>";
            echo "<input type='hidden' name='id' value='{$contact['id']}'>";
            echo "<button type='submit' class='text-red-600 hover:text-red-800 focus:outline-none'>Đã liên hệ qua mail</button>";  // Thêm nút xóa với hiệu ứng hover
            echo "</form>";
            echo "</td>";
            echo "</tr>";
        }
        echo '</tbody>';

        echo '</table>';
        echo '</div>';  // Đóng thẻ div chứa bảng
        echo '</div>';  // Đóng thẻ container
    }
}
