<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}
?>

<?php
require '../rbac.php';
session_start();

$userId = $_SESSION['user_id']; // Get the logged-in user ID

// Fetch user details
$stmt = $pdo->prepare("SELECT name, username FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("User not found.");
}

if (!hasPermission($userId, 'edit_articles')) {
    die("Access denied."); // Ensure only editors can access this page
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editor Dashboard</title>
</head>

<body>
    <h1>Editor Dashboard</h1>
    <p>Welcome, <?php echo htmlspecialchars($user['name']); ?> (<?php echo htmlspecialchars($user['username']); ?>)!</p>

    <h2>Permissions</h2>
    <ul>
        <?php
        $permissions = [
            'edit_articles' => 'Edit Articles',
            'view_dashboard' => 'View Dashboard'
        ];

        foreach ($permissions as $permissionKey => $permissionName) {
            if (hasPermission($userId, $permissionKey)) {
                echo "<li>$permissionName</li>";
            }
        }
        ?>
    </ul>
    <a href="../logout.php">Logout</a>
</body>

</html>