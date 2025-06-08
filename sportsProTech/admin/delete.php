<?php

require_once __DIR__ . '/../data/db.php';

$code = $_GET['productCode'];

$stmt = $db->prepare('DELETE FROM products WHERE productCode = ?');
$stmt->execute([$code]);

header('Location: project_manager.php');
exit();
?>
