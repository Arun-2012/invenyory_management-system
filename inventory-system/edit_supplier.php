<?php include 'includes/db.php'; ?>
<?php
$id = $_GET['id'];
$result = $conn->query("SELECT * FROM suppliers WHERE supplier_id = $id");
$row = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html>
<head><title>Edit Product</title><link rel="stylesheet" href="css/style.css"></head>
<body>
<?php include 'includes/nav.php'; ?> 
<div class="container">
    <h2>Edit Supplier</h2>
    <form method="POST">
        <input type="text" name="name" value="<?= $row['name'] ?>" required>
        <input type="text" name="contact_person" value="<?= $row['contact_person'] ?>">
        <input type="text" name="phone" value="<?= $row['phone'] ?>">
        <input type="email" name="email" value="<?= $row['email'] ?>">
        <textarea name="address"><?= $row['address'] ?></textarea>
        <div class="button-wrapper">
            <button type="submit" class="btn btn-edit">Update Supplier</button>
        </div>
    </form>
</div>
<?php include 'includes/footer.php'; ?>
</body>
</html>
<?php
include 'includes/db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $transaction_id = $_POST['transaction_id'];
    $product_id = $_POST['product_id'];
    $new_type = $_POST['type'];
    $new_quantity = (int) $_POST['quantity'];
    $transaction_date = $_POST['transaction_date'];
    $notes = $_POST['notes'];

    // Fetch the original transaction
    $stmt = $conn->prepare("SELECT type, quantity FROM transactions WHERE transaction_id = ?");
    $stmt->bind_param("i", $transaction_id);
    $stmt->execute();
    $stmt->bind_result($old_type, $old_quantity);
    $stmt->fetch();
    $stmt->close();

    // Calculate net quantity difference
    $quantity_change = 0;

    if ($old_type === 'IN') $quantity_change -= $old_quantity;
    else if ($old_type === 'OUT') $quantity_change += $old_quantity;

    if ($new_type === 'IN') $quantity_change += $new_quantity;
    else if ($new_type === 'OUT') $quantity_change -= $new_quantity;

    // Get current available stock
    $stock_sql = "SELECT 
                    COALESCE(SUM(CASE WHEN type = 'IN' THEN quantity ELSE 0 END), 0) -
                    COALESCE(SUM(CASE WHEN type = 'OUT' THEN quantity ELSE 0 END), 0) AS available
                  FROM transactions 
                  WHERE product_id = ? AND transaction_id != ?";
    $stmt = $conn->prepare($stock_sql);
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
?>

