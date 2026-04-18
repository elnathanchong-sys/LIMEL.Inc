<?php

require_once '../base.php';

$search_query = $_GET['q'] ?? '';

if (!empty($search_query)) {
    $stmt = $_db->prepare("SELECT * FROM products WHERE productName LIKE ?");
    $stmt->execute(["%$search_query%"]);
    $results = $stmt->fetchAll();
} else {
    $results = [];
}
?>