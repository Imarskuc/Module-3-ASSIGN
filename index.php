<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}
$error = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $host = 'db';
    $db   = 'contact_db';
    $user = 'root';
    $pass = 'secret_pass';
    $charset = 'utf8mb4';
    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    try {
        $pdo = new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
        $username_input = $_POST['username'];
        $password_input = $_POST['password'];
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username_input]);
        $user_record = $stmt->fetch();
        if ($user_record && password_verify($password_input, $user_record['password'])) {
            $_SESSION['user_id'] = $user_record['id'];
            $_SESSION['username'] = $user_record['username'];
            header("Location: dashboard.php");
            exit;
        } else {
            $error = "Invalid username or password!";
        }
    } catch (\PDOException $e) {
        $error = "Database Connection Failed: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>System Login</title>
</head>
<body>
<h2>System Login</h2>
<?php if (!empty($error)): ?><div style="color:red;"><?php echo $error; ?></div><?php endif; ?>
<form action="index.php" method="POST">
    <label>Username</label><input type="text" name="username" required><br>
    <label>Password</label><input type="password" name="password" required><br>
    <button type="submit">Login</button>
</form>
<a href="forgot-password.php">Forgot Password?</a> | <a href="register.php">Create Account</a>
</body>
</html>