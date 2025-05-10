<?php include 'includes/db.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Products</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php include 'includes/nav.php'; ?>
    <div class="container">
        <h2>Product List</h2>
        <a href="add_product.php" class="button">Add Product</a>
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Supplier</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $sql = "SELECT p.*, s.name AS supplier_name FROM products p LEFT JOIN suppliers s ON p.supplier_id = s.supplier_id";
            $result = $conn->query($sql);
            while ($row = $result->fetch_assoc()):
            ?>
                <tr>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['category']) ?></td>
                    <td>₹<?= $row['price'] ?></td>
                    <td><?= htmlspecialchars($row['supplier_name']) ?></td>
                    <td>
                        <a href="edit_product.php?id=<?= $row['product_id'] ?>"class="btn btn-edit">Edit</a> |
                        <a href="delete_product.php?id=<?= $row['product_id'] ?>"class="btn btn-delete" onclick="return confirm('Delete this product?')">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
<?php include 'includes/footer.php'; ?>
</html>
