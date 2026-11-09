<?php
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: index.php"); exit; }
$message = ""; $error = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $host = 'db'; $db = 'contact_db'; $user = 'root'; $pass = 'secret_pass';
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        $stmt = $pdo->prepare("INSERT INTO contacts (registration_number, mobile_number, email, address) VALUES (?, ?, ?, ?)");
        $stmt->execute([$_POST['reg_number'], $_POST['mobile_number'], $_POST['email'], $_POST['address']]);
        $message = "Contact saved successfully!";
    } catch (\PDOException $e) {
        $error = ($e->getCode() == 23000) ? "Registration number already exists." : "Error: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html>
<head><title>Dashboard</title></head>
<body>
<h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></h2>
<a href="logout.php">Logout</a> | <a href="search.php">Go to Task D Search</a>
<hr>
<h3>Task C: Register Contact</h3>
<?php if ($message) echo "<p style='color:green;'>$message</p>"; ?>
<?php if ($error) echo "<p style='color:red;'>$error</p>"; ?>
<form method="POST">
    Registration Number: <input type="text" name="reg_number" required><br>
    Mobile Number: <input type="text" name="mobile_number" required><br>
    Email: <input type="email" name="email" required><br>
    Address: <textarea name="address" required></textarea><br>
    <button type="submit">Save Contact</button>
</form>
</body>
</html>