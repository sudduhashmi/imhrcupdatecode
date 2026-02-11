<?php
session_start();
require_once __DIR__ . '/db.php';
// Define roles
define('ROLE_ADMIN', 'admin');
define('ROLE_MANAGER', 'manager');
define('ROLE_STAFF', 'staff');
// Redirect to login if not authenticated
function requireLogin() {
    if (!isset($_SESSION['admin_id']) || empty($_SESSION['admin_id'])) {
        header('Location: ' . BASE_URL . 'login.php');
        exit;
    }
}

// Redirect to dashboard if already logged in
function redirectIfLoggedIn() {
    if (isset($_SESSION['admin_id']) && !empty($_SESSION['admin_id'])) {
        header('Location: ' . BASE_URL . 'index.php');
        exit;
    }
    if (isset($_SESSION['student_id']) && !empty($_SESSION['student_id'])) {
        header('Location: ' . BASE_URL . 'student-dashboard.php');
        exit;
    }
}

// Check if user has specific role
function hasRole($role) {
    return isset($_SESSION['admin_role']) && $_SESSION['admin_role'] === $role;
}

// Check if user has any of the roles
function hasAnyRole($roles = []) {
    if (!is_array($roles)) {
        $roles = [$roles];
    }
    return isset($_SESSION['admin_role']) && in_array($_SESSION['admin_role'], $roles);
}

// Require specific role
function requireRole($role) {
    requireLogin();
    if (!hasRole($role)) {
        die('Access Denied: You do not have permission to access this page.');
    }
}

// Login user
function loginAdmin($email, $password) {
    $email = sanitize($email);
    $password = sanitize($password);
    
    // Query to find admin
    $sql = "SELECT * FROM admins WHERE Email = '$email' AND Password = '$password' AND IsActive = 1";
    $admin = getRow($sql);
    
    if ($admin) {
        $_SESSION['admin_id'] = $admin['AdminId'];
        $_SESSION['admin_name'] = $admin['Name'];
        $_SESSION['admin_email'] = $admin['Email'];
        $_SESSION['admin_role'] = $admin['Role'];
        $_SESSION['admin_photo'] = $admin['Photo'] ?? null;
        
        // Update last login
        $lastLogin = date('Y-m-d H:i:s');
        $updateSql = "UPDATE admins SET LastLogin = '$lastLogin' WHERE AdminId = '{$admin['AdminId']}'";
        query($updateSql);
        
        return true;
    }
    
    return false;
}

// Logout user
function logoutAdmin() {
    session_destroy();
}

// Get current logged-in admin
function getCurrentAdmin() {
    if (isset($_SESSION['admin_id'])) {
        $id = sanitize($_SESSION['admin_id']);
        return getRow("SELECT * FROM admins WHERE AdminId = '$id'");
    }
    return null;
}

// Get current admin ID
function getCurrentAdminId() {
    return $_SESSION['admin_id'] ?? null;
}

// Alias for backward compatibility
function currentUserId() {
    return getCurrentAdminId();
}

// Get admin display name
function getAdminName() {
    return $_SESSION['admin_name'] ?? 'Admin';
}

// Get admin email
function getAdminEmail() {
    return $_SESSION['admin_email'] ?? '';
}

// Get admin photo
function getAdminPhoto() {
    return $_SESSION['admin_photo'] ?? asset('vendors/images/default-avatar.png');
}

// Check permission for specific action
function canPerform($action) {
    $role = $_SESSION['admin_role'] ?? null;
    
    // Admin can do everything
    if ($role === ROLE_ADMIN) {
        return true;
    }
    
    // Define permissions for each role
    $permissions = [
        ROLE_MANAGER => [
            'view_dashboard',
            'view_students',
            'manage_admissions',
            'view_courses',
            'manage_experts',
            'view_reports',
            'manage_services'
        ],
        ROLE_STAFF => [
            'view_dashboard',
            'view_students',
            'view_courses',
            'manage_services'
        ]
    ];
    
    return isset($permissions[$role]) && in_array($action, $permissions[$role]);
}

// Log admin activity
function logActivity($action, $details = '') {
    $admin_id = sanitize($_SESSION['admin_id'] ?? 'unknown');
    $action = sanitize($action);
    $details = sanitize($details);
    $timestamp = date('Y-m-d H:i:s');
    
    $sql = "INSERT INTO admin_logs (AdminId, Action, Details, CreatedAt) 
            VALUES ('$admin_id', '$action', '$details', '$timestamp')";
    execute($sql);
}
?>
