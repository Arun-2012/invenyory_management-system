<?php include 'includes/db.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Transactions</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php include 'includes/nav.php'; ?>
<div class="container">
    <h2>Transactions</h2>
    <a href="add_transaction.php" class="button">Add Transaction</a>
    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th>Type</th>
                <th>Quantity</th>
                <th>Date</th>
                <th>Notes</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $sql = "SELECT t.*, p.name AS product_name FROM transactions t LEFT JOIN products p ON t.product_id = p.product_id ORDER BY transaction_date DESC";
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()):
        ?>
            <tr>
                <td><?= htmlspecialchars($row['product_name']) ?></td>
                <td><?= strtoupper($row['type']) ?></td>
                <td><?= $row['quantity'] ?></td>
                <td><?= $row['transaction_date'] ?></td>
                <td><?= htmlspecialchars($row['notes']) ?></td>
                <td>
                    <a href="edit_transaction.php?id=<?= $row['transaction_id'] ?>"class="btn btn-edit">Edit</a> |
                    <a href="delete_transaction.php?id=<?= $row['transaction_id'] ?>"class="btn btn-delete" onclick="return confirm('Delete this transaction?')">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
<?php include 'includes/footer.php'; ?>
</html>
