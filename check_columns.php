<?php
include 'config/db.php';

function getColumns($pdo, $table)
{
    $stmt = $pdo->query("DESCRIBE $table");
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
}

echo "Siswa Columns:\n";
print_r(getColumns($pdo, 'siswa'));

echo "\nGuru Columns:\n";
print_r(getColumns($pdo, 'guru'));
