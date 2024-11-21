<?php
require 'db.php';

// Get the role of a user
function getUserRole($userId) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT roles.role_name FROM roles 
                           JOIN users ON users.role_id = roles.id 
                           WHERE users.id = ?");
    $stmt->execute([$userId]);
    $role = $stmt->fetch(PDO::FETCH_ASSOC);
    return $role ? $role['role_name'] : null;
}

// Check if a user has a specific permission (Optional)
function hasPermission($userId, $permission) {
    global $pdo;
    $stmt = $pdo->prepare("
        SELECT p.permission_name 
        FROM permissions p
        JOIN role_permissions rp ON p.id = rp.permission_id
        JOIN roles r ON rp.role_id = r.id
        JOIN users u ON u.role_id = r.id
        WHERE u.id = ? AND p.permission_name = ?
    ");
    $stmt->execute([$userId, $permission]);
    return $stmt->rowCount() > 0;
}
?>