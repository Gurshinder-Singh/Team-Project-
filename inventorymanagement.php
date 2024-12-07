<?php
require 'db.php';

try {
    $sql = "SELECT product_id, name, description, price, image, brand, color FROM products";
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
        $brand = trim($_POST['brand']);
        $color = trim($_POST['color']);

        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $imageTmpPath = $_FILES['image']['tmp_name'];
            $imageName = basename($_FILES['image']['name']);
            $imageDestination = "asset/" . $imageName;

            if (!move_uploaded_file($imageTmpPath, $imageDestination)) {
                die("Error saving the uploaded image.");
            }
        } else {
            die("Image upload failed. Please upload a valid image.");
        }

        if (empty($name) || empty($description) || !is_numeric($price) || $price < 0 || empty($brand) || empty($color)) {
            die("All fields are required, and price must be a non-negative number.");
        }

        try {
            $sql = "INSERT INTO products (name, description, price, image, brand, color) 
                    VALUES (:name, :description, :price, :image, :brand, :color)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':name' => $name,
                ':description' => $description,
                ':price' => "£" . $price,
                ':image' => $imageDestination,
                ':brand' => $brand,
                ':color' => $color,
            ]);
            header("Location: inventorymanagement.php");
            exit;
        } catch (PDOException $e) {
            die("Error adding product: " . $e->getMessage());
        }
    }

    if (isset($_POST['update-product'])) {
        $product_id = $_POST['product_id'];
        $name = trim($_POST['name']);
        $description = trim($_POST['description']);
        $price = $_POST['price'];
        $brand = trim($_POST['brand']);
        $color = trim($_POST['color']);

        $imageDestination = $_POST['existing_image'];
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $imageTmpPath = $_FILES['image']['tmp_name'];
            $imageName = basename($_FILES['image']['name']);
            $imageDestination = "asset/" . $imageName;

            if (!move_uploaded_file($imageTmpPath, $imageDestination)) {
                die("Error saving the uploaded image.");
            }
        }

        if (empty($product_id) || empty($name) || empty($description) || !is_numeric($price) || $price < 0 || empty($brand) || empty($color)) {
            die("All fields are required, and price must be a non-negative number.");
        }

        try {
            $sql = "UPDATE products 
                    SET name = :name, description = :description, price = :price, image = :image, brand = :brand, color = :color 
                    WHERE product_id = :product_id";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':name' => $name,
                ':description' => $description,
                ':price' => "£" . $price,
                ':image' => $imageDestination,
                ':brand' => $brand,
                ':color' => $color,
                ':product_id' => $product_id,
            ]);
            header("Location: inventorymanagement.php");
            exit;
        } catch (PDOException $e) {
            die("Error updating product: " . $e->getMessage());
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
    <link rel="stylesheet" href="stylesheet.css">
    <title>Inventory Management</title>
</head>
<body>
    <div class="navbar">
        <a href="#menu">HOME</a>
        <a href="#search">SEARCH</a>
        <img src="asset/LUXUS_logo.png" alt="LUXUS_logo" id="luxusLogo">
        <?php if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']): ?>
            <a href="#wishlist">PROFILE</a>
        <?php endif; ?>
        <a href="#cart">BASKET</a>
        <a href="admin_page.php">ADMIN</a>
    </div>

    <h1>Inventory Management</h1>

    <h2>Current Inventory</h2>
    <table border="1" style="width: 80%; margin: 0 auto; text-align: left; border-collapse: collapse;">
        <thead>
            <tr>
                <th>Product ID</th>
                <th>Name</th>
                <th>Description</th>
                <th>Price</th>
                <th>Image</th>
                <th>Brand</th>
                <th>Color</th>
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
                        <td><img src="<?= htmlspecialchars($product['image']); ?>" alt="Product Image" width="50"></td>
                        <td><?= htmlspecialchars($product['brand']); ?></td>
                        <td><?= htmlspecialchars($product['color']); ?></td>
                        <td>
                            <form action="inventorymanagement.php" method="post" enctype="multipart/form-data" style="display: inline;">
                                <input type="hidden" name="product_id" value="<?= $product['product_id']; ?>">
                                <input type="hidden" name="existing_image" value="<?= htmlspecialchars($product['image']); ?>">
                                <input type="text" name="name" value="<?= htmlspecialchars($product['name']); ?>" required>
                                <input type="text" name="description" value="<?= htmlspecialchars($product['description']); ?>" required>
                                <input type="number" name="price" value="<?= htmlspecialchars($product['price']); ?>" step="0.01" required>
                                <input type="file" name="image">
                                <input type="text" name="brand" value="<?= htmlspecialchars($product['brand']); ?>" required>
                                <input type="text" name="color" value="<?= htmlspecialchars($product['color']); ?>" required>
                                <button type="submit" name="update-product">Update</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8">No products found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <h2>Add New Product</h2>
    <form action="inventorymanagement.php" method="post" enctype="multipart/form-data">
        <label for="name">Name:</label>
        <input type="text" name="name" id="name" required><br><br>
        <label for="description">Description:</label>
        <input type="text" name="description" id="description" required><br><br>
        <label for="price">Price:</label>
        <input type="number" name="price" id="price" step="0.01" required><br><br>
        <label for="image">Image:</label>
        <input type="file" name="image" id="image" required><br><br>
        <label for="brand">Brand:</label>
        <input type="text" name="brand" id="brand" required><br><br>
        <label for="color">Color:</label>
        <input type="text" name="color" id="color" required><br><br>
        <button type="submit" name="add-product">Add Product</button>
    </form>
</body>
</html>
