<?php
require 'db.php'; 

try {
    // Fetch all products from the database
    $sql = "SELECT * FROM products";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching products: " . $e->getMessage());
}

// Handle adding a new product
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add-product'])) {
        $name = $_POST['name'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $category_id = $_POST['category_id'];

        try {
            $sql = "INSERT INTO products (name, description, price, category_id) VALUES (:name, :description, :price, :category_id)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':name' => $name,
                ':description' => $description,
                ':price' => $price,
                ':category_id' => $category_id
            ]);
            header("Location: inventory_management.php"); // Reload the page
            exit;
        } catch (PDOException $e) {
            die("Error adding product: " . $e->getMessage());
        }
    }

    // Handle updating product quantity
    if (isset($_POST['update-product'])) {
        $product_id = $_POST['product_id'];
        $description = $_POST['description'];

        try {
            $sql = "UPDATE products SET description = :description WHERE product_id = :product_id";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':description' => $description,
                ':product_id' => $product_id
            ]);
            header("Location: inventory_management.php"); // Reload the page
            exit;
        } catch (PDOException $e) {
            die("Error updating product: " . $e->getMessage());
        }
    }

    // Handle deleting a product
    if (isset($_POST['delete-product'])) {
        $product_id = $_POST['product_id'];

        try {
            $sql = "DELETE FROM products WHERE product_id = :product_id";
            $stmt = $conn->prepare($sql);
            $stmt->execute([':product_id' => $product_id]);
            header("Location: inventory_management.php"); // Reload the page
            exit;
        } catch (PDOException $e) {
            die("Error deleting product: " . $e->getMessage());
        }
    }
}
?>
