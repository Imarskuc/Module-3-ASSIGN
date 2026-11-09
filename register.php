<?php
session_start();
$message = ""; $error = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $host = 'db'; $db = 'contact_db'; $user = 'root'; $pass = 'secret_pass'; $charset = 'utf8mb4';
    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    try {
        $pdo = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->execute([trim($_POST['username']), trim($_POST['email']), password_hash($_POST['password'], PASSWORD_BCRYPT)]);
        $message = "Account created successfully!";
    } catch (\PDOException $e) {
        $error = ($e->getCode() == 23000) ? "Username or email already registered." : "Error: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html>
<head><title>Register</title></head>
<body>
<h2>Create Account</h2>
<?php if ($message) echo "<p style='color:green;'>$message</p>"; ?>
<?php if ($error) echo "<p style='color:red;'>$error</p>"; ?>
<form method="POST">
    Username: <input type="text" name="username" required><br>
    Email: <input type="email" name="email" required><br>
    Password: <input type="password" name="password" required><br>
    <button type="submit">Sign Up</button>
</form>
<a href="index.php">Back to Login</a>
</body>
</html>