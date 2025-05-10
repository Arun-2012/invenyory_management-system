<?php include 'includes/db.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Inventory</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .container {
            padding: 2rem;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1.5rem;
        }

        table th, table td {
            border: 1px solid #ccc;
            padding: 0.75rem;
            text-align: left;
        }

        table th {
            background-color: #f7f7f7;
        }

        h2 {
            margin-bottom: 1rem;
        }

        .red-stock {
            color: red;
            font-weight: bold;
        }
        .green-stock{
            color:green;
            font-weight:bold;
        }
    </style>
</head>
<body>
<?php include 'includes/nav.php'; ?>

<div class="container">
    <h2>Inventory Overview</h2>
    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th>Category</th>
                <th>Supplier</th>
                <th>Price</th>
                <th>Total In</th>
                <th>Total Out</th>
                <th>Available</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT p.product_id, p.name, p.category, p.supplier_id, p.price,
                        COALESCE(SUM(CASE WHEN t.type = 'IN' THEN t.quantity ELSE 0 END), 0) AS total_in,
                        COALESCE(SUM(CASE WHEN t.type = 'OUT' THEN t.quantity ELSE 0 END), 0) AS total_out
                    FROM products p
                    LEFT JOIN transactions t ON p.product_id = t.product_id
                    GROUP BY p.product_id";
            $result = $conn->query($sql);

            while ($row = $result->fetch_assoc()):
                $available = $row['total_in'] - $row['total_out'];
                $color = 'black';
                if ($available <= 10) {
                    $color = 'red';
                } elseif ($available > 10) {
                    $color = 'green';
                }
            ?>
                <tr>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['category']) ?></td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td>₹<?= number_format($row['price'], 2) ?></td>
                    <td><?= $row['total_in'] ?></td>
                    <td><?= $row['total_out'] ?></td>
                    <td style="color: <?= $color ?>;"><?= $available ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include 'includes/footer.php'; ?>
</body>
</html>