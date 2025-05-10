<?php
include 'includes/db.php';

$id = intval($_GET['id']);

// Fetch product and suppliers before HTML output
$product = $conn->query("SELECT * FROM products WHERE product_id = $id")->fetch_assoc();
$suppliers = $conn->query("SELECT * FROM suppliers");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $stmt = $conn->prepare("UPDATE products SET name=?, description=?, category=?, price=?, supplier_id=? WHERE product_id=?");
    $stmt->bind_param(
        "sssdii",
        $_POST['name'],
        $_POST['description'],
        $_POST['category'],
        $_POST['price'],
        $_POST['supplier_id'],
        $id
    );
    $stmt->execute();
    header("Location: products.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Product</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php include 'includes/nav.php'; ?>
<div class="container">
    <h2>Edit Product</h2>
    <form method="post">
        <label>Name:</label>
        <input type="text" name="name" value="<?= htmlspecialchars($product['name']) ?>" required>

        <label>Description:</label>
        <textarea name="description"><?= htmlspecialchars($product['description']) ?></textarea>

        <label>Category:</label>
        <input type="text" name="category" value="<?= htmlspecialchars($product['category']) ?>">

        <label>Price:</label>
        <input type="number" step="0.01" name="price" value="<?= htmlspecialchars($product['price']) ?>" required>

        <label>Supplier:</label>
        <select name="supplier_id" required>
            <option value="">-- Select Supplier --</option>
            <?php while($s = $suppliers->fetch_assoc()): ?>
                <option value="<?= $s['supplier_id'] ?>" <?= $s['supplier_id'] == $product['supplier_id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($s['name']) ?>
                </option>
            <?php endwhile; ?>
        </select>

        <div class="button-wrapper">
            <button type="submit" class="btn btn-edit">Update Product</button>
        </div>
    </form>
</div>
<?php include 'includes/footer.php'; ?>
</body>
</html>
