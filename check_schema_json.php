<?php
include 'config/db.php';
header('Content-Type: application/json');
try {
    $stmt = $pdo->query("DESCRIBE nilai");
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC), JSON_PRETTY_PRINT);
} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
