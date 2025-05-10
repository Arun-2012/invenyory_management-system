<?php
include 'includes/db.php';
$id = intval($_GET['id']);

$stmt = $conn->prepare("DELETE FROM transactions WHERE transaction_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: transactions.php");
?>
