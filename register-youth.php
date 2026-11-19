<?php
session_start();
require 'db_connect.php';

// Only a logged-in admin can register youth accounts
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

/**
 * Go back to the Register Youth tab with an error code,
 * keeping what the admin typed (except passwords) so they don't retype it.
 */
function backToForm(string $error, array $oldInput): void
{
    $_SESSION['register_old'] = $oldInput;
    header("Location: admin-dashboard.php?tab=register&reg_error=" . urlencode($error));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: admin-dashboard.php?tab=register");
    exit;
}

$fullName = trim($_POST['full_name'] ?? '');
$username = trim($_POST['username'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$confirmPassword = $_POST['confirm_password'] ?? '';

$oldInput = ['full_name' => $fullName, 'username' => $username, 'email' => $email];

// Make sure the form came from our own admin page
if (!hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'] ?? '')) {
    backToForm('expired', $oldInput);
}

if ($fullName === '' || $username === '' || $email === '' || $password === '') {
    backToForm('missing', $oldInput);
}

if (mb_strlen($fullName) > 100) {
    backToForm('name', $oldInput);
}

// Letters, numbers and underscore only, 4–50 characters (fits the username column)
if (!preg_match('/^[A-Za-z0-9_]{4,50}$/', $username)) {
    backToForm('username', $oldInput);
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 100) {
    backToForm('email', $oldInput);
}

if (strlen($password) < 8) {
    backToForm('short', $oldInput);
}

if ($password !== $confirmPassword) {
    backToForm('mismatch', $oldInput);
}

// Username and email must not belong to another account
$stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = :username OR email = :email");
$stmt->execute([':username' => $username, ':email' => $email]);

if ($stmt->fetchColumn() > 0) {
    backToForm('taken', $oldInput);
}

// Save the password as a hash so login-process.php (password_verify) accepts it
$stmt = $pdo->prepare(
    "INSERT INTO users (username, email, password, role, full_name)
     VALUES (:username, :email, :password, 'youth', :full_name)"
);
$stmt->execute([
    ':username' => $username,
    ':email' => $email,
    ':password' => password_hash($password, PASSWORD_DEFAULT),
    ':full_name' => $fullName,
]);

header("Location: admin-dashboard.php?tab=register&registered=" . urlencode($username));
exit;
