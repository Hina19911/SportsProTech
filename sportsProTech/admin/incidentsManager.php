<?php
require_once __DIR__ . '/../data/db.php';

// ========== HANDLE ADD ==========
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])) {
    $stmt = $db->prepare("INSERT INTO incidents 
        (customerID, productCode, techID, dateOpened, dateClosed, title, description)
        VALUES (:customerID, :productCode, :techID, :dateOpened, :dateClosed, :title, :description)");
    $stmt->execute([
        ':customerID' => $_POST['customerID'],
        ':productCode' => $_POST['productCode'],
        ':techID' => $_POST['techID'],
        ':dateOpened' => $_POST['dateOpened'],
        ':dateClosed' => $_POST['dateClosed'] ?: null,
        ':title' => $_POST['title'],
        ':description' => $_POST['description'],
    ]);
    header("Location: incidentsManager.php");
    exit;
}

// ========== HANDLE DELETE ==========
if (isset($_GET['delete'])) {
    $stmt = $db->prepare("DELETE FROM incidents WHERE incidentID = ?");
    $stmt->execute([$_GET['delete']]);
    header("Location: incidentsManager.php");
    exit;
}

// ========== HANDLE UPDATE ==========
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $stmt = $db->prepare("UPDATE incidents SET 
        customerID = :customerID,
        productCode = :productCode,
        techID = :techID,
        dateOpened = :dateOpened,
        dateClosed = :dateClosed,
        title = :title,
        description = :description
        WHERE incidentID = :incidentID");

    $stmt->execute([
        ':customerID' => $_POST['customerID'],
        ':productCode' => $_POST['productCode'],
        ':techID' => $_POST['techID'],
        ':dateOpened' => $_POST['dateOpened'],
        ':dateClosed' => $_POST['dateClosed'] ?: null,
        ':title' => $_POST['title'],
        ':description' => $_POST['description'],
        ':incidentID' => $_POST['incidentID'],
    ]);
    header("Location: incidentsManager.php");
    exit;
}

// ========== FETCH INCIDENT FOR EDIT FORM ==========
$editIncident = null;
if (isset($_GET['edit'])) {
    $stmt = $db->prepare("SELECT * FROM incidents WHERE incidentID = ?");
    $stmt->execute([$_GET['edit']]);
    $editIncident = $stmt->fetch(PDO::FETCH_ASSOC);
}

// ========== FILTER ==========
$where = [];
$params = [];

if (!empty($_GET['filter_customer'])) {
    $where[] = "customerID = :customerID";
    $params[':customerID'] = $_GET['filter_customer'];
}
if (!empty($_GET['filter_product'])) {
    $where[] = "productCode = :productCode";
    $params[':productCode'] = $_GET['filter_product'];
}

$sql = "SELECT * FROM incidents";
if ($where) {
    $sql .= " WHERE " . implode(" AND ", $where);
}
$sql .= " ORDER BY dateOpened DESC";

$stmt = $db->prepare($sql);
$stmt->execute($params);
$incidents = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Incident Manager (Admin)</title>
</head>
<body>
    <h1>Incident Manager (Admin)</h1>

    <!-- FILTER FORM -->
    <form method="get">
        <strong>Filter:</strong>
        Customer ID: <input name="filter_customer" value="<?= $_GET['filter_customer'] ?? '' ?>">
        Product Code: <input name="filter_product" value="<?= $_GET['filter_product'] ?? '' ?>">
        <button type="submit">Search</button>
        <a href="incidentsManager.php">Clear</a>
    </form>
    <hr>

    <!-- ADD OR EDIT FORM -->
    <h2><?= $editIncident ? "Edit Incident #{$editIncident['incidentID']}" : "Add New Incident" ?></h2>
    <form method="post">
        <?php if ($editIncident): ?>
            <input type="hidden" name="incidentID" value="<?= $editIncident['incidentID'] ?>">
            <input type="hidden" name="update" value="1">
        <?php else: ?>
            <input type="hidden" name="add" value="1">
        <?php endif; ?>

        Customer ID: <input name="customerID" value="<?= $editIncident['customerID'] ?? '' ?>" required><br>
        Product Code: <input name="productCode" value="<?= $editIncident['productCode'] ?? '' ?>" required><br>
        Tech ID: <input name="techID" value="<?= $editIncident['techID'] ?? '' ?>"><br>
        Date Opened: <input type="datetime-local" name="dateOpened" value="<?= isset($editIncident['dateOpened']) ? str_replace(' ', 'T', $editIncident['dateOpened']) : '' ?>" required><br>
        Date Closed: <input type="datetime-local" name="dateClosed" value="<?= isset($editIncident['dateClosed']) ? str_replace(' ', 'T', $editIncident['dateClosed']) : '' ?>"><br>
        Title: <input name="title" value="<?= $editIncident['title'] ?? '' ?>" required><br>
        Description:<br><textarea name="description" rows="4" cols="60"><?= $editIncident['description'] ?? '' ?></textarea><br>
        <button type="submit"><?= $editIncident ? "Update" : "Add" ?></button>
        <?php if ($editIncident): ?>
            <a href="incidentsManager.php">Cancel Edit</a>
        <?php endif; ?>
    </form>

    <hr>

    <!-- INCIDENTS TABLE -->
    <h2>All Incidents</h2>
    <table border="1" cellpadding="6">
        <tr>
            <th>ID</th><th>Customer</th><th>Product</th><th>Tech</th>
            <th>Opened</th><th>Closed</th><th>Title</th><th>Description</th><th>Actions</th>
        </tr>
        <?php foreach ($incidents as $i): ?>
            <tr>
                <td><?= $i['incidentID'] ?></td>
                <td><?= $i['customerID'] ?></td>
                <td><?= $i['productCode'] ?></td>
                <td><?= $i['techID'] ?></td>
                <td><?= $i['dateOpened'] ?></td>
                <td><?= $i['dateClosed'] ?></td>
                <td><?= htmlspecialchars($i['title']) ?></td>
                <td><?= htmlspecialchars($i['description']) ?></td>
                <td>
                    <a href="?edit=<?= $i['incidentID'] ?>">Edit</a> |
                    <a href="?delete=<?= $i['incidentID'] ?>" onclick="return confirm('Delete this incident?')">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

</body>
</html>
