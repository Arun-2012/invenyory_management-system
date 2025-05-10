<?php
include 'includes/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $stmt = $conn->prepare("INSERT INTO products (name, description, category, price, quantity, supplier_id) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssdis", $_POST['name'], $_POST['description'], $_POST['category'], $_POST['price'], $_POST['quantity'], $_POST['supplier_id']);
    $stmt->execute();
    header("Location: products.php");
    exit;
}

$suppliers = $conn->query("SELECT * FROM suppliers");
?>

<!DOCTYPE html>
<html>
<head><title>Add Product</title><link rel="stylesheet" href="css/style.css"></head>
<body>
<?php include 'includes/nav.php'; ?>
<div class="container">
    <h2>Add Product</h2>
    <form method="post">
        <label>Name:</label><input type="text" name="name" required>
        <label>Description:</label><textarea name="description"></textarea>
        <label>Category:</label><input type="text" name="category">
        <label>Price:</label><input type="number" step="0.01" name="price" required>
        <label>Quantity:</label><input type="number" name="quantity" required>
        <label>Supplier:</label>
        <select name="supplier_id">
            <option value="">-- Select Supplier --</option>
            <?php while($s = $suppliers->fetch_assoc()): ?>
                <option value="<?= $s['supplier_id'] ?>"><?= $s['name'] ?></option>
            <?php endwhile; ?>
        </select>
        <div class="button-wrapper">
            <button type="submit" class="btn btn-edit">Save</button>
        </div>
    </form>
</div>
<?php include 'includes/footer.php'; ?>
</body>
</html>