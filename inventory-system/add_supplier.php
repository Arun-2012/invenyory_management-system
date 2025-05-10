<?php include 'includes/db.php'; ?>
<?php include 'includes/nav.php'; ?>
<link rel="stylesheet" href="css/style.css">
<div class="container">
    <h2>Add Supplier</h2>
    <form method="POST">
        <input type="text" name="name" placeholder="Supplier Name" required>
        <input type="text" name="contact_person" placeholder="Contact Person">
        <input type="text" name="phone" placeholder="Phone">
        <input type="email" name="email" placeholder="Email">
        <textarea name="address" placeholder="Address"></textarea>
        <div class="button-wrapper">
            <button type="submit"class="btn btn-edit" name="save">Save</button>
        </div>
    </form>
</div>
<?php include 'includes/footer.php'; ?>
<?php
if (isset($_POST['save'])) {
    $stmt = $conn->prepare("INSERT INTO suppliers (name, contact_person, phone, email, address) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $_POST['name'], $_POST['contact_person'], $_POST['phone'], $_POST['email'], $_POST['address']);
    $stmt->execute();
    header("Location: suppliers.php");
}
?>
