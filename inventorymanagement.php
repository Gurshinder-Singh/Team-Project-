<?php
require 'db.php';

try {
    $sql = "SELECT product_id, name, description, price FROM products";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching products: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add-product'])) {
        $name = trim($_POST['name']);
        $description = trim($_POST['description']);
        $price = $_POST['price'];

        if (empty($name) || empty($description) || !is_numeric($price) || $price < 0) {
            die("All fields are required, and price must be a non-negative number.");
        }

        try {
            $sql = "INSERT INTO products (name, description, price) VALUES (:name, :description, :price)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':name' => $name,
                ':description' => $description,
                ':price' => $price,
            ]);
            header("Location: inventorymanagement.php");
            exit;
        } catch (PDOException $e) {
            die("Error adding product: " . $e->getMessage());
        }
    }

    // Handle updating a product
    if (isset($_POST['update-product'])) {
        $product_id = $_POST['product_id'];
        $name = trim($_POST['name']);
        $description = trim($_POST['description']);
        $price = $_POST['price'];

        if (empty($product_id) || empty($name) || empty($description) || !is_numeric($price) || $price < 0) {
            die("All fields are required, and price must be a non-negative number.");
        }

        try {
            $sql = "UPDATE products SET name = :name, description = :description, price = :price WHERE product_id = :product_id";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':name' => $name,
                ':description' => $description,
                ':price' => $price,
                ':product_id' => $product_id,
            ]);
            header("Location: inventorymanagement.php");
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
            header("Location: inventorymanagement.php");
            exit;
        } catch (PDOException $e) {
            die("Error deleting product: " . $e->getMessage());
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="stylesheet.css"> <!-- Link to external stylesheet -->
    <title>Inventory Management</title>
</head>
<body>

   <!-- Navigation bar -->
<div class="navbar" id="navbar">
    <a href="#menu">HOME</a>
    <a href="#search">SEARCH</a>
    <img src="asset/LUXUS_logo.png" alt="LUXUS_logo" id="luxusLogo">
    <a href="#wishlist">PROFILE</a>
    <a href="#cart">BASKET</a>
</div>

<!-- CSS -->
<style>
    .navbar {
        height: 50px; /* Set your desired navbar height */
        display: flex;
        align-items: center;
    }

    .navbar img {
        height: 170%; /* Adjust this percentage to make the image bigger */
        max-height: 170%;
    }
</style>


    <script>
        let prevScrollpos = window.pageYOffset;
        window.onscroll = function() {
            let currentScrollPos = window.pageYOffset;
            if (prevScrollpos > currentScrollPos) {
                document.getElementById("navbar").style.top = "0";
            } else {
                document.getElementById("navbar").style.top = "-50px";
            }
            prevScrollpos = currentScrollPos;
        }
    </script>
    <h1>Inventory Management</h1>

    <h2>Current Inventory</h2>
    <table border="1" style="width: 80%; margin: 0 auto; text-align: left; border-collapse: collapse;">
    <thead>
            <tr>
                <th>Product ID</th>
                <th>Name</th>
                <th>Description</th>
                <th>Price</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($products)): ?>
                <?php foreach ($products as $product): ?>
                    <tr>
                        <td><?= $product['product_id']; ?></td>
                        <td><?= htmlspecialchars($product['name']); ?></td>
                        <td><?= htmlspecialchars($product['description']); ?></td>
                        <td><?= htmlspecialchars($product['price']); ?></td>
                        <td>
                            <form action="inventorymanagement.php" method="post" style="display: inline;">
                                <input type="hidden" name="product_id" value="<?= $product['product_id']; ?>">
                                <input type="text" name="name" value="<?= htmlspecialchars($product['name']); ?>" required>
                                <input type="text" name="description" value="<?= htmlspecialchars($product['description']); ?>" required>
                                <input type="number" name="price" value="<?= htmlspecialchars($product['price']); ?>" step="0.01" required>
                                <button type="submit" name="update-product">Update</button>
                            </form>
                            <form action="inventorymanagement.php" method="post" style="display: inline;">
                                <input type="hidden" name="product_id" value="<?= $product['product_id']; ?>">
                                <button type="submit" name="delete-product" style="color: red;">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">No products found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <h2>Add New Product</h2>
    <form action="inventorymanagement.php" method="post">
        <label for="name">Name:</label>
        <input type="text" name="name" id="name" required><br><br>
        <label for="description">Description:</label>
        <input type="text" name="description" id="description" required><br><br>
        <label for="price">Price:</label>
        <input type="number" name="price" id="price" step="0.01" required><br><br>
        <button type="submit" name="add-product">Add Product</button>
    </form>
</body>
</html>
