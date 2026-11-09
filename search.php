<?php
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: index.php"); exit; }
$results = []; $error = ""; $reg_input = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $reg_input = trim($_POST["registration_number"] ?? "");
    if ($reg_input !== "") {
        try {
            $pdo = new PDO("mysql:host=db;dbname=contact_db;charset=utf8mb4", "root", "secret_pass", [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
            $stmt = $pdo->prepare("SELECT * FROM contacts WHERE registration_number = ?");
            $stmt->execute([$reg_input]);
            $results = $stmt->fetchAll();
            if (count($results) === 0) $error = "No records found.";
        } catch (PDOException $e) { $error = "Search failed."; }
    }
}
?>
<!DOCTYPE html>
<html>
<head><title>Search Contact</title></head>
<body>
<h2>Task D: Search Contact Record</h2>
<form method="POST">
    Registration Number: <input type="text" name="reg_number" value="<?php echo htmlspecialchars($reg_input); ?>" required>
    <button type="submit">Search</button>
</form>
<a href="dashboard.php">Back to Dashboard</a>
<?php if ($error) echo "<p style='color:red;'>$error</p>"; ?>
<?php if ($results): ?>
<table border="1">
    <tr><th>ID</th><th>Reg Number</th><th>Mobile</th><th>Email</th><th>Address</th></tr>
    <?php foreach ($results as $row): ?>
    <tr>
        <td><?php echo $row['id']; ?></td>
        <td><?php echo $row['registration_number']; ?></td>
        <td><?php echo $row['mobile_number']; ?></td>
        <td><?php echo $row['email']; ?></td>
        <td><?php echo $row['address']; ?></td>
    </tr>
    <?php endforeach; ?>
</table>
<?php endif; ?>
</body>
</html>