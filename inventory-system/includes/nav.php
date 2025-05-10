<style>
    .navbar {
    background-color: #2c3e50;
    padding: 1rem 2rem;
    color: white;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.nav-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.logo {
    font-size: 1.5rem;
    font-weight: bold;
    color: white;
    text-decoration: none;
}

.nav-links {
    list-style: none;
    display: flex;
    gap: 1.5rem;
}

.nav-links li a {
    color: white;
    text-decoration: none;
    font-size: 1rem;
}

.nav-links li a:hover {
    text-decoration: underline;
}
    </style>
<nav class="navbar">
    <div class="nav-container">
        <a href="index.php" class="logo">InventorySys</a>
        <ul class="nav-links">
            <li><a href="products.php">Products</a></li>
            <li><a href="suppliers.php">Suppliers</a></li>
            <li><a href="transactions.php">Transactions</a></li>
            <li><a href="inventory.php">Inventory</a></li>
        </ul>
    </div>
</nav>
