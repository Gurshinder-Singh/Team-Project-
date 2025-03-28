<?php
session_start();

require 'db.php';

try {
    $sql = "SELECT product_id, name, description, price, image, brand, color, stock, gender FROM products"; 
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
        $gender = $_POST['gender']; 

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

        if (empty($name) || empty($description) || !is_numeric($price) || $price < 0 || empty($brand) || empty($color) || empty($gender)) { 
            die("Fill all fields, price must be positive, and stock must be a valid number. Gender must be selected");
        }

        try {
            $sql = "INSERT INTO products (name, description, price, image, brand, color, stock, gender)
                            VALUES (:name, :description, :price, :image, :brand, :color, :stock, :gender)"; 
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':name' => $name,
                ':description' => $description,
                ':price' => "£" . $price,
                ':image' => $imageDestination,
                ':brand' => $brand,
                ':color' => $color,
                ':stock' => $stock,
                ':gender' => $gender, 
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
        $gender = $_POST['gender']; 

        $imageDestination = $_POST['existing_image'];
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $imageTmpPath = $_FILES['image']['tmp_name'];
            $imageName = basename($_FILES['image']['name']);
            $imageDestination = "asset/" . $imageName;

            if (!move_uploaded_file($imageTmpPath, $imageDestination)) {
                die("Image uploading error.");
            }
        }

        if (empty($product_id) || empty($name) || empty($description) || !is_numeric($price) || $price < 0 || empty($brand) || empty($color) || !is_numeric($stock) || $stock < 0 || empty($gender)) { 
            die("Fill all fields, price must be positive, and stock must be a valid number. Gender must be selected");
        }

        try {
            $sql = "UPDATE products
                            SET name = :name, description = :description, price = :price, image = :image, brand = :brand, color = :color, stock = :stock, gender = :gender
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
                ':gender' => $gender, 
                ':product_id' => $product_id,
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
if (isset($_SESSION['last_activity']) && time() - $_SESSION['last_activity'] > 600) {
    session_destroy();
    header("Location: login.php");
    exit();
}

$_SESSION['last_activity'] = time();
?>

<script>
    setTimeout(function() {
        window.location.href = 'login.php';
    }, 600000); // 600000ms = 10 minutes
</script>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    	<link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="stylesheet.css">
    <title>Inventory Management</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
    
        }


        .inventory-table {
            width: 90%;
            margin: 20px auto;
            border-collapse: collapse;
            text-align: center;
            background:white;
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
 
      .navbar {
            height: 75px;
            display: flex;
            align-items: center;
            position: fixed;
            width: 100%;
            top: 0;
            background-color: #363636;
            transition: top 0.3s ease-in-out;
            will-change: transform;
            z-index: 100;
        }

        .navbar a,
        .navbar-logo {
            color: white;
            text-decoration: none;
            padding: 14px 20px;
            flex: 1;
            text-align: center;
            transform: translateX(-100px);
        }

        .navbar-logo {
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            max-width: 200px;
        }

        .navbar-logo img {
            height: 95px;
            width: auto;
            margin: 0 auto;
        }

        .dropdown {
            position: relative;
            display: inline-block;
            flex: 1;
        }

        .dropbtn {
            background-color: #363636;
            color: white;
            padding: 14px 20px;
            width: 70px;
            height: 70px;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .menu-icon {
            height: 50px;
            width: auto;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #363636;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
            transition: transform 0.3s ease-in-out;
        }
        .dropdown-content a {
            color: white;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            text-align: left;
            transform: translateX(0);
            transition: transform 0.3s ease-in-out;
        }

        .dropdown-content a:hover {
            background-color: #ddd;
            color: black;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }
.print-button {
    background-color: #4CAF50;
    color: white;
    padding: 12px 25px;
    font-size: 16px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-weight: bold;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.print-button:hover {
    background-color: #45a049;
    transform: scale(1.05);
    box-shadow: 0 6px 10px rgba(0, 0, 0, 0.3);
}

.print-button i {
    font-size: 18px;
}


    </style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body>

    <div class="content">
       
    <!-- NAV BAR -->
       <div class="navbar" id="navbar">
            <div class="dropdown">
                <button class="dropbtn">
                    <img src="asset/menu_icon.png" alt="Menu Icon" class="menu-icon">
                </button>
                <div class="dropdown-content">
                    <a href="about.php"><i class="fas fa-info-circle"></i> About Us</a>
                    <a href="contact.php"><i class="fas fa-envelope"></i> Contact Us</a>
                    <a href="FAQ.php"><i class="fas fa-question-circle"></i> FAQs</a>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="returns.php"><i class="fas fa-undo-alt"></i> Returns</a>
                        <a href="logout.php"><i class="fas fa-sign-out-alt"></i> LOGOUT</a>
                    <?php endif; ?>
                    <a href="javascript:void(0);" id="darkModeToggle">
                        <i class="fas fa-moon"></i> <span>Dark Mode</span>
                    </a>
                </div>
            </div>
            <a href="homepage.php"><i class="fas fa-home"></i> HOME</a>
            <a href="products_page.php"><i class="fas fa-box-open"></i> PRODUCTS</a>
            <div class="navbar-logo">
                <img src="asset/LUXUS_logo.png" alt="LUXUS_logo" id="luxusLogo">
            </div>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="profile.php"><i class="fas fa-user"></i> PROFILE</a>
            <?php elseif (isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
                <a href="admin_page.php"><i class="fas fa-user-shield"></i> ADMIN</a>
                <a href="logout.php"><i class="fas fa-sign-out-alt"></i> LOGOUT</a>
            <?php else: ?>
                <a href="login.php"><i class="fas fa-sign-in-alt"></i> LOGIN</a>
            <?php endif; ?>
            <?php if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']): ?>
                <a href="cart.php"><i class="fas fa-shopping-basket"></i> BASKET</a>
            <?php endif; ?>
        </div>

    <h1>Inventory Management</h1>

<!-- EDIT CURRENT PRODUCT -->
            
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
                <th>Gender</th>
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
                        <td><?= htmlspecialchars($product['gender']); ?></td>
                        <td style="width: 200px;">
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
   							 <label for="gender">Select Gender:</label>
						    <select name="gender" id="gender" required>
        					<option value="Male" <?= ($product['gender'] == 'Men') ? 'selected' : ''; ?>>Men</option>
        					<option value="Female" <?= ($product['gender'] == 'Women') ? 'selected' : ''; ?>>Women</option>
        					<option value="Unisex" <?= ($product['gender'] == 'Unisex') ? 'selected' : ''; ?>>Unisex</option>
    						</select>

						    <button type="submit" name="update-product">Update</button>
							</form>

                            <form action="inventorymanagement.php" method="post" style="display: inline;">
                                <input type="hidden" name="product_id" value="<?= $product['product_id']; ?>">
                                <button type="submit" name="delete-product" style="color: red;">Delete</button>
                            </form>
                        </td>
                    </tr>
         <?php if ($product['stock'] < 5): ?>
                <div id="low-stock-message-<?= $product['product_id']; ?>" style="color: red;">
                    <?= htmlspecialchars($product['name']); ?> - Low Stock!
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="8">No products found.</td>
        </tr>
    <?php endif; ?>
        </tbody>
    </table>
    
        <!-- PRINT -->

<button class="print-button" onclick="printTable()">Print Stock Levels</button>

<script>
function printTable() {
    const allowedColumns = ['product id', 'name', 'price', 'stock', 'brand', 'colour'];
    
    var table = document.querySelector('.inventory-table').cloneNode(true);

    var headerRow = table.querySelector('tr');
    var headers = headerRow.querySelectorAll('th');

    let removeIndexes = [];
    headers.forEach((th, index) => {
        const headerText = th.textContent.trim().toLowerCase();
        if (!allowedColumns.includes(headerText)) {
            removeIndexes.push(index);
        }
    });

    var rows = table.querySelectorAll('tr');
    rows.forEach(row => {
        removeIndexes.sort((a, b) => b - a);
        removeIndexes.forEach(index => {
            if (row.cells[index]) {
                row.removeChild(row.cells[index]);
            }
        });
    });

    var newWin = window.open('', 'Print-Window');
    newWin.document.open();
    newWin.document.write('<html><head><title>Products Stock</title><style>' + getTableStyle() + '</style></head><body>' + table.outerHTML + '</body></html>');
    newWin.document.close();
    newWin.focus();
    newWin.print();
    newWin.close();
}

function getTableStyle() {
    return `
        .inventory-table { width: 100%; border-collapse: collapse; text-align: center; font-family: Arial, sans-serif; }
        .inventory-table th, .inventory-table td { border: 1px solid #ddd; padding: 10px; }
        .inventory-table th { background-color: #f2f2f2; font-weight: bold; }
    `;
}
</script>

        <!-- NEW PRODUCT -->

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
        <label for="gender">Gender:</label>
    <select name="gender" id="gender" required>
        <option value="Men">Men</option>
        <option value="Women">Women</option>
        <option value="Unisex">Unisex</option>
    </select>
        <label for="brand">Brand:</label>
        <input type="text" name="brand" id="brand" required>
        <label for="color">Color:</label>
        <input type="text" name="color" id="color" required>
        <label for="color">Stock:</label>
        <input type="text" name="stock" id="stock" required>
        <button type="submit" name="add-product">Add Product</button>
    </form>
        <!-- FOOTER -->
<footer style="
            background-color: #2c2c2c;
            color: white;
            padding: 10px 15px;
            text-align: center;
            font-size: 13px;
            margin-top: 50px;
            position: relative;
            width: 100%;
            z-index: 2;
        ">
            <div style="margin-bottom: 10px; font-size: 18px;">
                <a href="#" style="color: white; margin: 0 8px;"><i class="fab fa-facebook-f"></i></a>
                <a href="#" style="color: white; margin: 0 8px;"><i class="fab fa-twitter"></i></a>
                <a href="#" style="color: white; margin: 0 8px;"><i class="fab fa-instagram"></i></a>
                <a href="#" style="color: white; margin: 0 8px;"><i class="fab fa-linkedin-in"></i></a>
            </div>
            <p style="margin: 0;">&copy; <?= date("Y") ?> LUXUS. All rights reserved.</p>
        </footer>
</body>
</html>
