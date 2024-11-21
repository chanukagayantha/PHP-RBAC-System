<?php
session_start();
require 'rbac.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$userId = $_SESSION['user_id'];
$role = getUserRole($userId);

switch ($role) {
    case 'Admin':
        header('Location: dashboard/admin_dashboard.php');
        break;
    case 'Editor':
        header('Location: dashboard/editor_dashboard.php');
        break;
    case 'Viewer':
        header('Location: dashboard/viewer_dashboard.php');
        break;
    default:
        die("Unauthorized access.");
}
?>