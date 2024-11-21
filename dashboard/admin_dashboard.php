<?php
session_start();

require '../db.php';
require '../rbac.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

$userId = $_SESSION['user_id']; // Get the logged-in user ID

// Fetch user details
$stmt = $pdo->prepare("SELECT name, username FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("User not found.");
}


// Ensure only admins can access this page
if (!hasPermission($userId, 'manage_users')) {
    die("Access denied.");
}

// Fetch all users and their roles
$stmt = $pdo->prepare("
    SELECT users.id, users.name, users.username, users.contact, roles.role_name
    FROM users
    JOIN roles ON users.role_id = roles.id
");
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch all roles for the dropdown
$rolesStmt = $pdo->prepare("SELECT id, role_name FROM roles");
$rolesStmt->execute();
$roles = $rolesStmt->fetchAll(PDO::FETCH_ASSOC);

// Handle role update request
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['user_id'], $_POST['role_id'])) {
    $userIdToUpdate = $_POST['user_id'];
    $newRoleId = $_POST['role_id'];

    // Update the user's role in the database
    $updateStmt = $pdo->prepare("UPDATE users SET role_id = ? WHERE id = ?");
    if ($updateStmt->execute([$newRoleId, $userIdToUpdate])) {
        $successMessage = "User role updated successfully.";
        // Refresh the page to show the updated roles
        header("Location: admin_dashboard.php");
        exit;
    } else {
        $errorMessage = "Failed to update user role.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
    table {
        width: 100%;
        border-collapse: collapse;
        margin: 20px 0;
    }

    table th,
    table td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: left;
    }

    table th {
        background-color: #f4f4f4;
    }

    .message {
        color: green;
        font-weight: bold;
    }

    .error {
        color: red;
        font-weight: bold;
    }
    </style>
</head>

<body>
    <h1>Admin Dashboard</h1>
    <p>Welcome, <?php echo htmlspecialchars($user['name']); ?> (<?php echo htmlspecialchars($user['username']); ?>)!</p>

    <h2>Permissions</h2>
    <ul>
        <?php
        // Define permissions
        $permissions = [
            'manage_users' => 'Manage Users',
            'edit_articles' => 'Edit Articles',
            'view_dashboard' => 'View Dashboard'
        ];

        // Display permissions the admin has
        foreach ($permissions as $permissionKey => $permissionName) {
            if (hasPermission($userId, $permissionKey)) {
                echo "<li>$permissionName</li>";
            }
        }
        ?>
    </ul>

    <h2>All Users</h2>
    <?php if (isset($successMessage)): ?>
    <p class="message"><?php echo $successMessage; ?></p>
    <?php endif; ?>
    <?php if (isset($errorMessage)): ?>
    <p class="error"><?php echo $errorMessage; ?></p>
    <?php endif; ?>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Username</th>
                <th>Contact</th>
                <th>Role</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
            <tr>
                <td><?php echo htmlspecialchars($user['id']); ?></td>
                <td><?php echo htmlspecialchars($user['name']); ?></td>
                <td><?php echo htmlspecialchars($user['username']); ?></td>
                <td><?php echo htmlspecialchars($user['contact']); ?></td>
                <td><?php echo htmlspecialchars($user['role_name']); ?></td>
                <td>
                    <form method="POST" action="admin_dashboard.php">
                        <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                        <select name="role_id" required>
                            <?php foreach ($roles as $role): ?>
                            <option value="<?php echo $role['id']; ?>"
                                <?php echo $role['role_name'] === $user['role_name'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($role['role_name']); ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                        <button type="submit">Update</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <a href="../logout.php">Logout</a>
</body>

</html>