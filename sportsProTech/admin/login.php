
<?php
require_once __DIR__ . '/../data/db.php';

?>

    <h1>Admin Login</h1>
    <form method="post">
        <label>Username:</label><br>
        <input type="text" name="username"><br><br>
        <label>Password:</label><br>
        <input type="password" name="password"><br><br>
        <button type="submit">Login</button>
    </form>
</body>
</html>