<?php
require_once __DIR__ . '/../data/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $phone = $_POST['phone'];

    $sql = 'INSERT INTO technicians (firstName, lastName, email, password, phone) VALUES (?, ?, ?, ?, ?)';
    $stmt = $db->prepare($sql);
    $stmt->execute([$firstName, $lastName, $email, $password, $phone]);

    header('Location: techManager.php');
    exit();
}
?>

<h1>Add Technician</h1>
<form method="post">
    First Name: <input type="text" name="firstName" required><br>
    Last Name: <input type="text" name="lastName" required><br>
    Email: <input type="email" name="email" required><br>
    Password: <input type="text" name="password" required><br>
    Phone: <input type="text" name="phone" required><br>
    <button type="submit">Add</button>
</form>
<a href="techManager.php">Back to Technicians</a>
