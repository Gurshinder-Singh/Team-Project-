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

        try {
            $sql = "INSERT INTO products (name, description, price) VALUES (:name, :description, :price)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':name' => $name,
                ':description' => $description,
                ':price' => $price
            ]);
            header("Location: inventory_management.php"); // Reload the page
            exit;
        } catch (PDOException $e) {
            die("Error adding product: " . $e->getMessage());
        }
    }

    // Handle updating product details
    if (isset($_POST['update-product'])) {
        $product_id = $_POST['product_id'];
        $name = $_POST['name'];
        $description = $_POST['description'];
        $price = $_POST['price'];

        try {
            $sql = "UPDATE products SET name = :name, description = :description, price = :price WHERE product_id = :product_id";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':name' => $name,
                ':description' => $description,
                ':price' => $price,
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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Management</title>
</head>
<body>
    <h1>Inventory Management</h1>

    <h2>Current Inventory</h2>
    <table border="1" style="width: 100%; text-align: left;">
        <thead>
            <tr>
                <th>Product ID</th>
                <th>Product Name</th>
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
                            <!-- Update Form -->
                            <form action="inventory_management.php" method="post" style="display: inline;">
                                <input type="hidden" name="product_id" value="<?= $product['product_id']; ?>">
                                <input type="text" name="name" placeholder="Update Name" value="<?= htmlspecialchars($product['name']); ?>" required>
                                <input type="text" name="description" placeholder="Update Description" value="<?= htmlspecialchars($product['description']); ?>" required>
                                <input type="number" name="price" placeholder="Update Price" value="<?= htmlspecialchars($product['price']); ?>" step="0.01" required>
                                <button type="submit" name="update-product">Update</button>
                            </form>
                            <!-- Delete Form -->
                            <form action="inventory_management.php" method="post" style="display: inline;">
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
    <form action="inventory_management.php" method="post">
        <label for="name">Name:</label>
        <input type="text" name="name" id="name" required><br><br>
        <label for="description">Description:</label>
        <input type="text" name="description" id="description"><br><br>
        <label for="price">Price:</label>
        <input type="number" name="price" id="price" step="0.01" required><br><br>
        <button type="submit" name="add-product">Add Product</button>
    </form>
</body>
</html>
