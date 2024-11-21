# **Role-Based Access Control (RBAC) System | PHP**

## **Overview**

This is a PHP-based Role-Based Access Control (RBAC) system designed for managing user authentication, role assignments, and permission-based access. It includes a simple and extendable framework for handling roles and permissions, with secure login, logout, and session handling.

## **Features**

- User authentication (Sign Up, Login, Logout)
- Role-based access:
  - Admin
  - Editor
  - Viewer
- Permission-based access to specific functionalities
- Dynamic user management by the Admin
- Dashboard views tailored to user roles
- Secure session handling
- Extensible design for adding new roles and permissions

## **Project Structure**

```plaintext
.
├── dashboard/
│   ├── admin_dashboard.php     # Admin-specific dashboard
│   ├── editor_dashboard.php    # Editor-specific dashboard
│   ├── viewer_dashboard.php    # Viewer-specific dashboard
├── utils/
│   ├── check_permissions.php   # Utility to check user permissions
│   ├── utils.php               # General utility functions
├── db.php                      # Database connection file
├── login.php                   # Login script
├── logout.php                  # Logout script
├── rbac.php                    # Role and permission utility functions
├── redirect.php                # Handles redirection based on roles
├── signup.php                  # User registration script
├── index.php                   # Entry point for the application
```
## **Getting Started**

### **Prerequisites**

- Web Server: Apache (e.g., XAMPP, MAMP, or LAMP).
- PHP: Version 7.4 or higher.
- Database: MySQL.

### **Setup Instructions**
1. Clone the repository
```
git clone https://github.com/your-username/rbac-system.git
cd rbac-system
```
2. Import the database schema:
  - Use the rbac.sql file to set up the database tables (users, roles, permissions, etc.).


```
CREATE DATABASE rbac_system;
USE rbac_system;

-- Roles table
CREATE TABLE roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    role_name VARCHAR(50) NOT NULL UNIQUE
);

-- Users table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    contact VARCHAR(20),
    role_id INT NOT NULL,
    FOREIGN KEY (role_id) REFERENCES roles(id)
);
```

3. Update database connection:
  - Edit db.php to match your database credentials:

```
$host = 'localhost';
$dbname = 'rbac_system';
$username = 'your_db_username';
$password = 'your_db_password';

```
4. Start the application:
  - Place the project folder in your web server’s root directory (e.g., htdocs for XAMPP).
  - Visit the application in your browser:

