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
}
