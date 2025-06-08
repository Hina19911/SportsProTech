<?php 
require_once __DIR__ .'/../data/db.php';
$customers = [];
$search = $_GET['lastname'] ?? '';
if ($search) {
  $query = "SELECT * FROM customers WHERE lastname LIKE :lastname";
  $stmt = $db->prepare($query);
  $stmt->execute([':lastname' => $search]);
  $customers = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
?>
<!DOCTYPE html>
<html>
<head>
    <title>Search Customers</title>
</head>
<body>
    <h1>Search Customers by Last Name</h1>

    <form method="get">
        <label>Last Name:
            <input type="text" name="lastname" value="<?= htmlspecialchars($search) ?>" required>
        </label>
        <button type="submit">Search</button>
    </form>
    <?php if ($customers): ?>
        <h2>Results:</h2>
        <table border="1" cellpadding="8">
            <tr>
                <th>Customer ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Select</th>
            </tr>
            <?php foreach ($customers as $customer): ?>
            <tr>
                <td><?= htmlspecialchars($customer['CustomerID']) ?></td>
                <td><?= htmlspecialchars($customer['firstname'] . ' ' . $customer['lastname']) ?></td>
                <td><?= htmlspecialchars($customer['email']) ?></td>
                <td>
                    <form method="get" action="viewCustomer.php">
                        <input type="hidden" name="CustomerID" value="<?= $customer['CustomerID'] ?>">
                        <button type="submit">Select</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    <?php elseif ($search): ?>
        <p>No customers found with that last name.</p>
    <?php endif; ?>
    <br><br>
    <a href="viewCustomer.php">Back to Customer List</a>
</body>
</html>
