<?php
session_start();
$message = ""; $error = "";
$token = $_GET['token'] ?? '';
$host = 'db'; $db = 'contact_db'; $user = 'root'; $pass = 'secret_pass';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    $stmt = $pdo->prepare("SELECT * FROM users WHERE reset_token = ?");
    $stmt->execute([$token]);
    $user_record = $stmt->fetch();
    if (!$token || !$user_record) $error = "Invalid or expired token.";
} catch (\PDOException $e) { die("DB Error"); }

if ($_SERVER["REQUEST_METHOD"] == "POST" && !$error) {
    $pdo->prepare("UPDATE users SET password = ?, reset_token = NULL WHERE id = ?")->execute([password_hash($_POST['password'], PASSWORD_BCRYPT), $user_record['id']]);
    $message = "Password updated successfully!";
}
?>
<!DOCTYPE html>
<html>
<head><title>Reset Password</title></head>
<body>
<h2>Reset Password</h2>
<?php if ($message) echo "<p style='color:green;'>$message</p>"; ?>
<?php if ($error) echo "<p style='color:red;'>$error</p>"; ?>
<?php if (!$message && !$error): ?>
<form method="POST">
    New Password: <input type="password" name="password" required><br>
    <button type="submit">Update Password</button>
</form>
<?php endif; ?>
<a href="index.php">Login</a>
</body>
</html>