<?php

header('Content-Type: application/json; charset=utf-8');

$results = [
    'postgres' => ['status' => 'error'],
    'mariadb' => ['status' => 'error'],
];
$failed = false;
$passwords = [getenv('PGPASSWORD'), getenv('MARIADB_PASSWORD')];

function diagnosticoSeguro(string $message, array $passwords): string
{
    foreach ($passwords as $password) {
        if (is_string($password) && $password !== '') {
            $message = str_replace($password, '[redacted]', $message);
        }
    }

    return $message;
}

try {
    $connection = new PDO(
        'pgsql:host=' . getenv('PGHOST') . ';port=' . getenv('PGPORT')
            . ';dbname=' . getenv('PGDATABASE'),
        getenv('PGUSER'),
        getenv('PGPASSWORD'),
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    $results['postgres'] = [
        'status' => 'ok',
        'database' => $connection->query('SELECT current_database()')->fetchColumn(),
    ];
} catch (PDOException $error) {
    $failed = true;
    error_log(
        'PostgreSQL PDO test failed [' . $error->getCode() . ']: '
            . diagnosticoSeguro($error->getMessage(), $passwords)
    );
}

try {
    $connection = new PDO(
        'mysql:host=' . getenv('MARIADB_HOST') . ';port=' . getenv('MARIADB_PORT')
            . ';dbname=' . getenv('MARIADB_DATABASE') . ';charset=utf8mb4',
        getenv('MARIADB_USER'),
        getenv('MARIADB_PASSWORD'),
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    $results['mariadb'] = [
        'status' => 'ok',
        'database' => $connection->query('SELECT DATABASE()')->fetchColumn(),
    ];
} catch (PDOException $error) {
    $failed = true;
    error_log(
        'MariaDB PDO test failed [' . $error->getCode() . ']: '
            . diagnosticoSeguro($error->getMessage(), $passwords)
    );
}

http_response_code($failed ? 503 : 200);
echo json_encode($results, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
