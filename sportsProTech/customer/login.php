<?php
require_once __DIR__ . '/../data/db.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstname = $_POST['firstname'] ?? '';
    $lastname = $_POST['lastname'] ?? '';
    $address = $_POST['address'] ?? '';
    $city = $_POST['city'] ?? '';
    $state = $_POST['state'] ?? '';
    $postalCode = $_POST['postalCode'] ?? '';
    $countryCode = $_POST['countryCode'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Simple validation
    if ($firstname && $lastname && $email && $password) {
        try {
            $query = "INSERT INTO customers 
                (firstname, lastname, address, city, state, postalCode, countryCode, phone, email, password) 
                VALUES 
                (:firstname, :lastname, :address, :city, :state, :postalCode, :countryCode, :phone, :email, :password)";
            
            $stmt = $db->prepare($query);
            $stmt->execute([
                ':firstname' => $firstname,
                ':lastname' => $lastname,
                ':address' => $address,
                ':city' => $city,
                ':state' => $state,
                ':postalCode' => $postalCode,
                ':countryCode' => $countryCode,
                ':phone' => $phone,
                ':email' => $email,
                ':password' => password_hash($password, PASSWORD_DEFAULT) // hash password
            ]);

            $success = "Customer added successfully!";
            $newCustomerId = $db->lastInsertId();
header("Location: viewCustomer.php?CustomerID=" . $newCustomerId);
exit;

        } catch (PDOException $e) {
            $error = "Error: " . $e->getMessage();
        }
    } else {
        $error = "Please fill in all required fields.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Customer</title>
</head>
<body>
    <h1>Add Customer</h1>

    <?php if ($error): ?>
        <p style="color:red;"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>
    <?php if ($success): ?>
        <p style="color:green;"><?php echo htmlspecialchars($success); ?></p>
    <?php endif; ?>

    <form method="post">
        <label>First Name: <input type="text" name="firstname" required></label><br><br>
        <label>Last Name: <input type="text" name="lastname" required></label><br><br>
        <label>Address: <input type="text" name="address"></label><br><br>
        <label>City: <input type="text" name="city"></label><br><br>
        <label>State: <input type="text" name="state"></label><br><br>
        <label>Postal Code: <input type="text" name="postalCode"></label><br><br>
        <label>Country Code: <input type="text" name="countryCode"></label><br><br>
        <label>Phone: <input type="text" name="phone"></label><br><br>
        <label>Email: <input type="email" name="email" required></label><br><br>
        <label>Password: <input type="password" name="password" required></label><br><br>
        <input type="submit" value="Add Customer">
    </form>
    <!-- Separate search button -->
<form action="search_customer.php" method="get" style="margin-top: 20px;">
    <input type="submit" value="Search Customer">
</form>
</body>
</html>
