<?php
    declare(strict_types = 1);

    require_once __DIR__ . '/../vendor/autoload.php';

    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
    $dotenv -> safeLoad();

    $dotenv -> required(['DB_DSN', 'DB_USER']) -> notEmpty();
    
    return new PDO(
        $_ENV['DB_DSN'],
        $_ENV['DB_USER'],
        $_ENV['DB_PASSWORD'] ?? '',
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );

?>