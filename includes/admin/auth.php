<?php
require_once __DIR__ . '/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function attempt_login($email, $password)
{
    global $mysqli;

    // admin_users (secure hash)
    $stmt = $mysqli->prepare('SELECT id, email, password, name FROM admin_users WHERE email = ? LIMIT 1');
    if ($stmt) {
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($row = $res->fetch_assoc()) {
            $hash = $row['password'] ?? null;
            if (!empty($hash) && password_verify($password, $hash)) {
                $_SESSION['role'] = 'admin';
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['email'] = $row['email'];
                $_SESSION['name'] = $row['name'];
                return true;
            }
        }
    }

    // studentlogin (legacy plain-text)
    $stmt = $mysqli->prepare('SELECT Id, Name, Email, Password FROM studentlogin WHERE Email = ? LIMIT 1');
    if ($stmt) {
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($row = $res->fetch_assoc()) {
            if ($row['Password'] === $password) {
                $_SESSION['role'] = 'student';
                $_SESSION['user_id'] = $row['Id'];
                $_SESSION['email'] = $row['Email'];
                $_SESSION['name'] = $row['Name'];
                return true;
            }
        }
    }

    // centerlogin
    $stmt = $mysqli->prepare('SELECT Email, Password FROM centerlogin WHERE Email = ? LIMIT 1');
    if ($stmt) {
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($row = $res->fetch_assoc()) {
            if ($row['Password'] === $password) {
                $_SESSION['role'] = 'center';
                $_SESSION['email'] = $row['Email'];
                $_SESSION['name'] = $row['Email'];
                return true;
            }
        }
    }

    // patientlogin
    $stmt = $mysqli->prepare('SELECT Email, Password FROM patientlogin WHERE Email = ? LIMIT 1');
    if ($stmt) {
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($row = $res->fetch_assoc()) {
            if ($row['Password'] === $password) {
                $_SESSION['role'] = 'patient';
                $_SESSION['email'] = $row['Email'];
                $_SESSION['name'] = $row['Email'];
                return true;
            }
        }
    }

    return false;
}

function require_admin()
{
    if (empty($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
        header('Location: ' . ADMIN_BASE . '/login.php');
        exit;
    }
}

function require_login()
{
    if (empty($_SESSION['role'])) {
        header('Location: ' . ADMIN_BASE . '/login.php');
        exit;
    }
}

function logout()
{
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params['path'], $params['domain'],
            $params['secure'], $params['httponly']
        );
    }
    session_destroy();
}

?>
