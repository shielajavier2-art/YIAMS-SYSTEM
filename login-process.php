<?php
session_start();
// ✅ Make sure filename is db_connect.php
require 'db_connect.php';

$role = trim($_POST['role']);
$userCred = trim($_POST['userCred']);
$password = $_POST['password'];

// ✅ Normal dash -> $pdo->prepare
$stmt = $pdo->prepare("SELECT id, username, email, password, role, full_name FROM users WHERE username = :cred OR email = :cred");
$stmt->bindParam(':cred', $userCred);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    header("Location: login.php?error=notfound");
    exit;
}

if (!password_verify($password, $user['password'])) {
    header("Location: login.php?error=wrongpass");
    exit;
}

if ($user['role'] !== $role) {
    header("Location: login.php?error=role");
    exit;
}

$_SESSION['user_id'] = $user['id'];
$_SESSION['username'] = $user['username'];
$_SESSION['full_name'] = $user['full_name'];
$_SESSION['role'] = $user['role'];

if ($user['role'] === 'admin') {
    header("Location: admin-dashboard.php");
} else {
    header("Location: youth-dashboard.php");
}
exit;
?>