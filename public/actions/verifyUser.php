<?php

declare(strict_types=1);
$pdo = require '../../config/database.php';

$users = $pdo->query(
    'SELECT * FROM utilizadores'
)->fetchAll();

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

if ($email === '' || $password === '') {
    header('Location: ../admin/index.php');
    exit;
}

$usersByEmail = array_column($users, null, 'email');
$user = $usersByEmail[$email] ?? null;

if ($user === null || !password_verify($password, $user['password'])) {
    header('Location: ../admin/index.php?error=invalid_credentials');
    exit;
}

session_start();
$_SESSION['user_id'] = $user['id'];
$_SESSION['user_email'] = $user['email'];
$_SESSION['user_name'] = $user['nome'];

header('Location: ../admin/dashboard.php');
exit;
