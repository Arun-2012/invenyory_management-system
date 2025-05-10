<?php include 'includes/db.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Inventory Dashboard</title>
    <link rel="stylesheet" href="assets/style.css">
    <style>
        .dashboard {
            display: flex;
            gap: 2rem;
            flex-wrap: wrap;
            justify-content: center;
            padding: 2rem;
        }

        .card {
            background: #fff;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            width: 220px;
            text-align: center;
        }

        .card h3 {
            margin-bottom: 0.5rem;
            font-size: 1.2rem;
            color: #333;
        }

        .card p {
            font-size: 2rem;
            color: #007bff;
            margin: 0;
        }

        .card a {
            display: inline-block;
            margin-top: 0.5rem;
            font-size: 0.9rem;
            color: #555;
            text-decoration: none;
        }

        .card a:hover {
            text-decoration: underline;
        }

        h1 {
            text-align: center;
            margin-top: 2rem;
        }
    </style>
</head>
<body>
<?php include 'includes/nav.php'; ?>
    <h1>Inventory System Dashboard</h1>
    <div class="dashboard">
        <?php
            $productCount = $conn->query("SELECT COUNT(*) AS count FROM products")->fetch_assoc()['count'];
            $supplierCount = $conn->query("SELECT COUNT(*) AS count FROM suppliers")->fetch_assoc()['count'];
            $transactionCount = $conn->query("SELECT COUNT(*) AS count FROM transactions")->fetch_assoc()['count'];
        ?>
        <div class="card">
            <h3>Total Products</h3>
            <p><?= $productCount ?></p>
            <a href="products.php">View Products</a>
        </div>
        <div class="card">
            <h3>Total Suppliers</h3>
            <p><?= $supplierCount ?></p>
            <a href="suppliers.php">View Suppliers</a>
        </div>
        <div class="card">
            <h3>Total Transactions</h3>
            <p><?= $transactionCount ?></p>
            <a href="transactions.php">View Transactions</a>
        </div>
    </div>
</body>
<?php include 'includes/footer.php'; ?>

</html>
