<?php
require 'rbac.php';

// Example user ID
$userId = 1; // Admin

// Check if the user can manage users
if (hasPermission($userId, 'manage_users')) {
    echo "User has permission to manage users.";
} else {
    echo "Access denied.";
}

?>