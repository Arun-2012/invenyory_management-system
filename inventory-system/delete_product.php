<?php
include 'includes/db.php';

$id = intval($_GET['id']);
$conn->query("DELETE FROM products WHERE product_id = $id");

header("Location: products.php?deleted=1");
exit;
?>