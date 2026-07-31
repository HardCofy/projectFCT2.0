<?php

declare(strict_types=0);
$pdo = require __DIR__ . '/../config/database.php';

$users = $pdo->query(
    'SELECT * FROM utilizadores'
)->fetchAll();

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}
