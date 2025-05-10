<?php
include 'includes/db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $transaction_id = $_POST['transaction_id'];
    $product_id = $_POST['product_id'];
    $new_type = $_POST['type'];
    $new_quantity = (int) $_POST['quantity'];
    $transaction_date = $_POST['transaction_date'];
    $notes = $_POST['notes'];

    // Fetch original transaction
    $stmt = $conn->prepare("SELECT type, quantity FROM transactions WHERE transaction_id = ?");
    $stmt->bind_param("i", $transaction_id);
    $stmt->execute();
    $stmt->bind_result($old_type, $old_quantity);
    $stmt->fetch();
    $stmt->close();

    // Calculate quantity change
    $quantity_change = 0;
    if ($old_type === 'IN') $quantity_change -= $old_quantity;
    else if ($old_type === 'OUT') $quantity_change += $old_quantity;

    if ($new_type === 'IN') $quantity_change += $new_quantity;
    else if ($new_type === 'OUT') $quantity_change -= $new_quantity;

    // Get current available stock (excluding this transaction)
    $stmt = $conn->prepare("
        SELECT 
            COALESCE(SUM(CASE WHEN type = 'IN' THEN quantity ELSE 0 END), 0) -
            COALESCE(SUM(CASE WHEN type = 'OUT' THEN quantity ELSE 0 END), 0) AS available
        FROM transactions 
        WHERE product_id = ? AND transaction_id != ?");
    $stmt->bind_param("ii", $product_id, $transaction_id);
    $stmt->execute();
    $stmt->bind_result($available);
    $stmt->fetch();
    $stmt->close();

    $final_stock = $available + $quantity_change;

    if ($final_stock < 0) {
        echo "<script>alert('Error: Editing this transaction will result in negative stock.'); window.history.back();</script>";
        exit;
    }

    // Update transaction
    $stmt = $conn->prepare("UPDATE transactions SET product_id = ?, type = ?, quantity = ?, transaction_date = ?, notes = ? WHERE transaction_id = ?");
    $stmt->bind_param("isissi", $product_id, $new_type, $new_quantity, $transaction_date, $notes, $transaction_id);
    $stmt->execute();
    $stmt->close();

    header("Location: transactions.php");
    exit;
}

// Load data for editing
$transaction = null;
$products = $conn->query("SELECT * FROM products");

if (isset($_GET['id'])) {
    $stmt = $conn->prepare("SELECT * FROM transactions WHERE transaction_id = ?");
    $stmt->bind_param("i", $_GET['id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $transaction = $result->fetch_assoc();
    $stmt->close();

    if (!$transaction) {
        echo "<script>alert('Transaction not found.'); window.location.href = 'transactions.php';</script>";
        exit;
    }
} else {
    echo "<script>alert('No transaction ID provided.'); window.location.href = 'transactions.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Transaction</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php include 'includes/nav.php'; ?>
<div class="container">
    <h2>Edit Transaction</h2>
    <form method="post">
        <input type="hidden" name="transaction_id" value="<?= $transaction['transaction_id'] ?>">

        <label>Product:</label>
        <select name="product_id" required>
            <?php while ($p = $products->fetch_assoc()): ?>
                <option value="<?= $p['product_id'] ?>" <?= ($transaction['product_id'] == $p['product_id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($p['name']) ?>
                </option>
            <?php endwhile; ?>
        </select>

        <label>Type:</label>
        <select name="type" required>
            <option value="IN" <?= ($transaction['type'] === 'IN') ? 'selected' : '' ?>>IN</option>
            <option value="OUT" <?= ($transaction['type'] === 'OUT') ? 'selected' : '' ?>>OUT</option>
        </select>

        <label>Quantity:</label>
        <input type="number" name="quantity" min="1" value="<?= $transaction['quantity'] ?>" required>

        <label>Date:</label>
        <input type="date" name="transaction_date" value="<?= $transaction['transaction_date'] ?>" required>

        <label>Notes:</label>
        <textarea name="notes"><?= htmlspecialchars($transaction['notes']) ?></textarea>
        <div class="button-wrapper">
            <button type="submit" class="btn btn-edit">Update Transaction</button>
        </div>
    </form>
</div>
<?php include 'includes/footer.php'; ?>
</body>
</html>
