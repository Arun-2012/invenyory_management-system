<?php
include 'includes/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = (int) $_POST['product_id'];
    $quantity = (int) $_POST['quantity'];
    $type = $_POST['type'];
    $notes = $_POST['notes'];

    // Check current available stock for OUT transaction
    if ($type === 'OUT') {
        $stmt = $conn->prepare("
            SELECT 
                COALESCE(SUM(CASE WHEN type = 'IN' THEN quantity ELSE 0 END), 0) -
                COALESCE(SUM(CASE WHEN type = 'OUT' THEN quantity ELSE 0 END), 0) AS available
            FROM transactions 
            WHERE product_id = ?
        ");
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $stmt->bind_result($available);
        $stmt->fetch();
        $stmt->close();

        if ($quantity > $available) {
            echo "<script>alert('Error: Not enough stock available.'); window.history.back();</script>";
            exit;
        }
    }

    // Insert transaction
    $stmt = $conn->prepare("INSERT INTO transactions (product_id, quantity, type, notes) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiss", $product_id, $quantity, $type, $notes);
    $stmt->execute();
    $stmt->close();

    header("Location: transactions.php");
    exit;
}

$products = $conn->query("SELECT * FROM products");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Transaction</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php include 'includes/nav.php'; ?>
<div class="container">
    <h2>Add Transaction</h2>
    <form method="post">
        <label>Product:</label>
        <select name="product_id" required>
            <option value="">-- Select Product --</option>
            <?php while ($p = $products->fetch_assoc()): ?>
                <option value="<?= $p['product_id'] ?>"><?= htmlspecialchars($p['name']) ?></option>
            <?php endwhile; ?>
        </select>

        <label>Type:</label>
        <select name="type" required>
            <option value="IN">IN</option>
            <option value="OUT">OUT</option>
        </select>

        <label>Quantity:</label>
        <input type="number" name="quantity" min="1" required>

        <label>Notes:</label>
        <textarea name="notes"></textarea>
        <div class="button-wrapper">
            <button type="submit"class="btn btn-edit">Add Transaction</button>
        </div>
    </form>
</div>
<?php include 'includes/footer.php'; ?>
</body>
</html>
