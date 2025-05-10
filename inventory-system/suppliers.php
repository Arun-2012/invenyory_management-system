<?php include 'includes/db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Suppliers</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include 'includes/nav.php'; ?>
    <div class="container">
        <h2>Suppliers</h2>
        <a href="add_supplier.php" class="button">Add Supplier</a>
        <table class="styled-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Contact Person</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $result = $conn->query("SELECT * FROM suppliers");
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                        <td>{$row['name']}</td>
                        <td>{$row['contact_person']}</td>
                        <td>{$row['phone']}</td>
                        <td>{$row['email']}</td>
                        <td>
                            <a href='edit_supplier.php?id={$row['supplier_id']}' class='btn btn-edit'>Edit</a> |
                            <a href='delete_supplier.php?id={$row['supplier_id']}' class='btn btn-delete' onclick=\"return confirm('Are you sure you want to delete this supplier?');\">Delete</a>
                        </td>
                    </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <?php include 'includes/footer.php'; ?>
</body>
</html>
