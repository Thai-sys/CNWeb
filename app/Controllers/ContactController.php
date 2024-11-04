<?php
session_start(); // Khởi động phiên
require '../app/Models/Contact.php';

class ContactController
{
    private $contactModel;

    public function __construct($db)
    {
        $this->contactModel = new Contact($db);
    }

    public function showForm()
    {
        include '../app/Views/contact.php'; // Gọi view hiển thị biểu mẫu
    }

    public function handleFormSubmission()
    {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $firstName = $_POST['first-name'];
            $lastName = $_POST['last-name'];
            $email = $_POST['email'];
            $phone = $_POST['phone'];
            $message = $_POST['message'];

            if ($this->contactModel->emailExists($email)) {
                $_SESSION['error_contact'] = "Email này đã được sử dụng. Vui lòng chọn email khác.";
                header("Location: /contact"); // Chuyển hướng về trang contact
                exit();
            }
            // Gọi phương thức save để lưu dữ liệu vào cơ sở dữ liệu
            if ($this->contactModel->save($firstName, $lastName, $email, $phone, $message)) {
                $_SESSION['success'] = "Cảm ơn bạn đã gửi thông tin!";
            } else {
                $_SESSION['error_contact'] = "Đã có lỗi xảy ra. Vui lòng thử lại.";
            }

            header("Location: /contact"); // Chuyển hướng về trang contact
            exit();
        }
    }
}
