<?php
require_once __DIR__ . '/app.php';

try {
    $dsn = sprintf('mysql:host=%s;dbname=%s;charset=utf8mb4', env_value('DB_HOST', 'localhost'), env_value('DB_NAME', 'employment_management'));
    $pdo = new PDO($dsn, env_value('DB_USER', 'root'), env_value('DB_PASS', ''), [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
} catch (PDOException $exception) {
    http_response_code(500);
    exit('Database connection failed. Configure your .env file and import database/schema.sql.');
}
