
<?php
require_once __DIR__ . '/../data/db.php';

$customerId = $_GET['CustomerID'] ?? null;

if (!$customerId) {
    echo "No customer ID provided.";
    exit;
}

$stmt = $db->prepare("SELECT * FROM customers WHERE CustomerID = :id");
$stmt->execute([':id' => $customerId]);
$customer = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$customer) {
    echo "Customer not found.";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Customer Details</title>
</head>
<body>
    <h1>Customer Details</h1>
    <p><strong>ID:</strong> <?= htmlspecialchars($customer['CustomerID']) ?></p>
    <p><strong>Name:</strong> <?= htmlspecialchars($customer['firstname'] . ' ' . $customer['lastname']) ?></p>
    <p><strong>Email:</strong> <?= htmlspecialchars($customer['email']) ?></p>
    <p><strong>Phone:</strong> <?= htmlspecialchars($customer['phone']) ?></p>
    <p><strong>Address:</strong> <?= htmlspecialchars($customer['address']) ?>, <?= htmlspecialchars($customer['city']) ?>, <?= htmlspecialchars($customer['state']) ?>, <?= htmlspecialchars($customer['postalCode']) ?>, <?= htmlspecialchars($customer['countryCode']) ?></p>
    <br>
    <a href="customerManager.php">Back to Customer List</a>
</body>
</html>
<!-- <?php
require_once __DIR__ . '/../data/db.php';

$customerId = $_GET['CustomerID'] ?? null;

if (!$customerId) {
    echo "No customer ID provided.";
    exit;
}

$stmt = $db->prepare("SELECT * FROM customers WHERE CustomerID = :id");
$stmt->execute([':id' => $customerId]);
$customer = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$customer) {
    echo "Customer not found.";
    exit;
}
?>
