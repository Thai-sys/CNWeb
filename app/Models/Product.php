<?php

class Product
{
    private $pdo;

    // Constructor để nhận PDO instance
    public function __construct($db)
    {
        // Kiểm tra xem $db có phải là một instance của PDO không
        if (!$db instanceof PDO) {
            throw new InvalidArgumentException("Invalid PDO instance provided.");
        }
        $this->pdo = $db; // Gán PDO instance vào thuộc tính
    }

    public function getPdo()
    {
        return $this->pdo; // Trả về PDO instance
    }
    public function getNewProducts($limit = 6)
    {
        $sql = "SELECT id, name, description, price, image_url FROM coffee_products WHERE is_new = TRUE ORDER BY RAND() LIMIT :limit";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getFeaturedProducts($limit = 6)
    {
        $sql = "SELECT id, name, description, price, image_url FROM coffee_products WHERE is_featured = TRUE ORDER BY RAND() LIMIT :limit";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getBestSellerProducts($limit = 6)
    {
        $sql = "SELECT id, name, description, price, image_url FROM coffee_products WHERE is_best_seller = TRUE ORDER BY RAND() LIMIT :limit";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function displayProducts($products)
    {
        foreach ($products as $row) {
            echo '<div class="product-item bg-coffee-light p-8 rounded hover:shadow-lg transition-shadow duration-300">';
            echo '<img alt="' . htmlspecialchars($row["image_url"]) . '" class="rounded-full mx-auto mb-4 transition-transform duration-300 hover:scale-110" height="100" src="' . htmlspecialchars($row["image_url"]) . '" width="100" />';
            echo '<h3 class="text-2xl font-bold mb-2">' . htmlspecialchars($row["name"]) . '</h3>';
            echo '<p>' . htmlspecialchars($row["description"]) . '</p>';
            echo '<p class="font-bold text-lg">Giá: ' . number_format($row["price"], 0, ',', '.') . ' VND</p>';
            echo '<form action="/cart/add" method="POST">';
            echo '<input type="hidden" name="product_id" value="' . $row['id'] . '">';
            echo '<button class="btn-cart" type="submit"><i class="fas fa-shopping-cart"></i> Add Cart</button>';
            echo '</form>';
            echo '<button class="btn-heart"><i class="fas fa-heart"></i></button>';
            echo '</div>';
        }
    }
}
