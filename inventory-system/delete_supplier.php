<?php
include 'includes/db.php';
$id = intval($_GET['id']);

$stmt = $conn->prepare("DELETE FROM suppliers WHERE supplier_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: suppliers.php");
?>
