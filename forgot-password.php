<?php
$message = ""; $error = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $host = 'db'; $db = 'contact_db'; $user = 'root'; $pass = 'secret_pass';
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$_POST['email']]);
        if ($user_record = $stmt->fetch()) {
            $token = bin2hex(random_bytes(16));
            $pdo->prepare("UPDATE users SET reset_token = ? WHERE email = ?")->execute([$token, $_POST['email']]);
            
            ini_set("SMTP", "mail"); ini_set("smtp_port", "1025");
            $link = "http://localhost:8080/reset-password.php?token=" . $token;
            mail($_POST['email'], "Password Reset", "Click link to reset: " . $link, "From: noreply@kabarak.ac.ke");
            $message = "Reset link sent! Check Mailpit at http://localhost:8025";
        } else {
            $error = "Email not found.";
        }
    } catch (\PDOException $e) { $error = "DB Error: " . $e->getMessage(); }
}
?>
<!DOCTYPE html>
<html>
<head><title>Forgot Password</title></head>
<body>
<h2>Forgot Password</h2>
<?php if ($message) echo "<p style='color:green;'>$message</p>"; ?>
<?php if ($error) echo "<p style='color:red;'>$error</p>"; ?>
<form method="POST">
    Email: <input type="email" name="email" required><br>
    <button type="submit">Send Reset Link</button>
</form>
<a href="index.php">Back to Login</a>
</body>
</html>