<?php
require_once __DIR__ . '/../data/db.php';

$query = $db->query('SELECT * FROM technicians ORDER BY lastName');
$technicians = $query->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Technician Management</title>
</head>
<body>
    <h1>Technicians</h1>
    <a href="addTechnician.php">Add New Technician</a>
    <table cellpadding="8" >
        <tr>
             <th>ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email</th>
            <th>Password</th>
            <th>Phone</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($technicians as $tech): ?>
        <tr>
            <td><?= htmlspecialchars($tech['techID']) ?></td>
            <td><?= htmlspecialchars($tech['firstName']) ?></td>
            <td><?= htmlspecialchars($tech['lastName']) ?></td>
            <td><?= htmlspecialchars($tech['email']) ?></td>
            <td><?= htmlspecialchars($tech['password']) ?></td>
            <td><?= htmlspecialchars($tech['phone']) ?></td>
            <td>
                <a href="editTechnician.php?techID=<?= urlencode($tech['techID']) ?>">Edit</a> |
                <a href="deleteTechnician.php?techID=<?= urlencode($tech['techID']) ?>" onclick="return confirm('Are you sure?')">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
