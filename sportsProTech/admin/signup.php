<?php
// Turn on error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// PDO database connection
$dsn = 'mysql:host=localhost;dbname=techsupport';
$username = 'root'; // default XAMPP user
$password = '';     // default XAMPP password (empty)

try {
    $db = new PDO($dsn, $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
    exit();
}

// Handle form submission
$message = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!empty($_POST['username']) && !empty($_POST['password'])) {
        $user = trim($_POST['username']);
        $pass = password_hash(trim($_POST['password']), PASSWORD_DEFAULT);

        try {
            $query = "INSERT INTO users(username, password) VALUES (:username, :password)";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':username', $user);
            $stmt->bindParam(':password', $pass);
            $stmt->execute();

            $message = "<p style='color: green;'>User registered successfully!</p>";
        } catch (PDOException $e) {
            $message = "<p style='color: red;'>Database error: " . htmlspecialchars($e->getMessage()) . "</p>";
        }
    } else {
        $message = "<p style='color: orange;'>Both username and password are required.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register User</title>
</head>
<body>
    <h1>Register</h1>
    <?php if (!empty($message)) echo $message; ?>
    <form method="post">
        <label>Username:</label><br>
        <input type="text" name="username" required><br><br>

        <label>Password:</label><br>
        <input type="password" name="password" required><br><br>

        <button type="submit">Register</button>
    </form>
</body>
</html>

