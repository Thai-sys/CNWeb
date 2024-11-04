<?php
// app/Controllers/AboutController.php

class AboutController
{
    public function index()
    {
        session_start(); // Khởi động phiên
        include '../app/Views/partials/header.php';
        include '../app/Views/about.php';
        include '../app/Views/partials/footer.php';
    }
}
