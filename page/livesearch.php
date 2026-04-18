<?php
include '../base.php';

header('Content-Type: application/json');

$query = $_GET['q'] ?? '';

if (strlen($query) > 0) {
    $stmt = $_db->prepare("SELECT productID, productName FROM products WHERE productName LIKE ? LIMIT 5");
    $stmt->execute(["%$query%"]);
    
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC); 
    
    echo json_encode($results);
} else {
    echo json_encode([]);
}
?>