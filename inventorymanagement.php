<?php
require 'db.php';

try {
    $sql = "SELECT product_id, name, description, price, image, brand, color, stock FROM products";
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
        $stock = $_POST['stock'];

        if (!is_numeric($stock) || $stock < 0) {
            die("Stock must be a positive integer.");
        }

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
            die("Fill all fields, price must be positive, and stock must be a valid number.");
        }

        try {
            $sql = "INSERT INTO products (name, description, price, image, brand, color, stock) 
                    VALUES (:name, :description, :price, :image, :brand, :color, :stock)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':name' => $name,
                ':description' => $description,
                ':price' => "£" . $price,
                ':image' => $imageDestination,
                ':brand' => $brand,
                ':color' => $color,
                ':stock' => $stock
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
        $stock = $_POST['stock'];

        $imageDestination = $_POST['existing_image'];
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $imageTmpPath = $_FILES['image']['tmp_name'];
            $imageName = basename($_FILES['image']['name']);
            $imageDestination = "asset/" . $imageName;

            if (!move_uploaded_file($imageTmpPath, $imageDestination)) {
                die("Image uploading error.");
            }
        }

        if (empty($product_id) || empty($name) || empty($description) || !is_numeric($price) || $price < 0 || empty($brand) || empty($color) || !is_numeric($stock) || $stock < 0) {
            die("Fill all fields, price must be positive, and stock must be a valid number.");
        }

        try {
            $sql = "UPDATE products 
                    SET name = :name, description = :description, price = :price, image = :image, brand = :brand, color = :color, stock = :stock 
                    WHERE product_id = :product_id";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':name' => $name,
                ':description' => $description,
                ':price' => "£" . $price,
                ':image' => $imageDestination,
                ':brand' => $brand,
                ':color' => $color,
                ':stock' => $stock,
                ':product_id' => $product_id
            ]);
            header("Location: inventorymanagement.php");
            exit;
        } catch (PDOException $e) {
            die("Error updating product: " . $e->getMessage());
        }
    }

    if (isset($_POST['delete-product'])) {
        $product_id = $_POST['product_id'];

        try {
            $sql = "DELETE FROM products WHERE product_id = :product_id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
            $stmt->execute();

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
    <title>Inventory Management</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }

        .navbar {
            display: flex;
            justify-content: space-around;
            align-items: center;
            background-color: #333;
            color: white;
            padding: 10px 0;
        }

        .navbar a {
            color: white;
            text-decoration: none;
            padding: 8px 16px;
        }

        .inventory-table {
            width: 90%;
            margin: 20px auto;
            border-collapse: collapse;
            text-align: center;
        }

        .inventory-table th, .inventory-table td {
            border: 1px solid #ddd;
            padding: 10px;
        }

        .inventory-table th {
            background-color: #f2f2f2;
        }

        .inventory-table img {
            max-width: 50px;
            height: auto;
        }

        form {
            text-align: center;
            width: 60%;
            margin: 20px auto;
        }

        form input, form button {
            display: block;
            margin: 10px auto;
            padding: 10px;
            width: 80%;
        }

        form button {
            background-color: #333;
            color: white;
            border: none;
            cursor: pointer;
        }

        form button:hover {
            background-color: #555;
        }

        h1, h2 {
            text-align: center;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <a href="homepage.php">HOME</a>
        <a href="products_page.php">PRODUCTS</a>
        <a href="checkout.php">BASKET</a>
        <a href="admin_page.php">ADMIN</a>
    </div>

    <h1>Inventory Management</h1>

    <h2>Current Inventory</h2>
    <table class="inventory-table">
        <thead>
            <tr>
                <th>Product ID</th>
                <th>Name</th>
                <th>Description</th>
                <th>Price</th>
                <th>Image</th>
                <th>Brand</th>
                <th>Color</th>
                <th>Stock</th>
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
                        <td><img src="<?= htmlspecialchars($product['image']); ?>"></td>
                        <td><?= htmlspecialchars($product['brand']); ?></td>
                        <td><?= htmlspecialchars($product['color']); ?></td>
                        <td><?= htmlspecialchars($product['stock']); ?></td>
                        <td>
                            <form action="inventorymanagement.php" method="post" enctype="multipart/form-data" style="display: inline;">
                                <input type="hidden" name="product_id" value="<?= $product['product_id']; ?>">
                                <input type="hidden" name="existing_image" value="<?= htmlspecialchars($product['image']); ?>">
                                <input type="text" name="name" value="<?= htmlspecialchars($product['name']); ?>" required>
                                <input type="text" name="description" value="<?= htmlspecialchars($product['description']); ?>" required>
                                <input type="number" name="price" value="<?= htmlspecialchars($product['price']); ?>" required>
                                <input type="file" name="image">
                                <input type="text" name="brand" value="<?= htmlspecialchars($product['brand']); ?>" required>
                                <input type="text" name="color" value="<?= htmlspecialchars($product['color']); ?>" required>
                                <input type="text" name="stock" value="<?= htmlspecialchars($product['stock']); ?>" required>
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
                    <td colspan="8">No products found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    <button class="print-button" onclick="printTable()">Print Stock Levels</button>

        <script>
        function printTable() {
        var table = document.querySelector('.inventory-table');
        var newWin = window.open('', 'Print-Window');

        newWin.document.open();
        newWin.document.write('<html><head><title>Products Stock</title><style>' + getComputedStyleString() + '</style></head><body>' + table.outerHTML + '</body></html>');

        newWin.document.close();
        newWin.focus();
        newWin.print();
        newWin.close();
        }

        function getComputedStyleString() {
        var styles = '';
        for (var i = 0; i < document.styleSheets.length; i++) {
            var sheet = document.styleSheets[i];
            try {
            var rules = sheet.cssRules || sheet.rules;
            if (rules) {
                for (var j = 0; j < rules.length; j++) {
                styles += rules[j].cssText + '\n';
                }
            }
            } catch (e) {
            console.error('Error accessing stylesheet:', sheet.href, e);
            }
        }
        return styles;
        }
        </script>
    <h2>Add New Product</h2>
    <form action="inventorymanagement.php" method="post" enctype="multipart/form-data">
        <label for="name">Name:</label>
        <input type="text" name="name" id="name" required>
        <label for="description">Description:</label>
        <input type="text" name="description" id="description" required>
        <label for="price">Price:</label>
        <input type="number" name="price" id="price" step="0.01" required>
        <label for="image">Image:</label>
        <input type="file" name="image" id="image" required>
        <label for="brand">Brand:</label>
        <input type="text" name="brand" id="brand" required>
        <label for="color">Color:</label>
        <input type="text" name="color" id="color" required>
        <label for="color">Stock:</label>
        <input type="text" name="stock" id="stock" required>
        <button type="submit" name="add-product">Add Product</button>
    </form>
</body>
</html>
