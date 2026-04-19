<?php


$basePath = './product-listing/base.php';
if (file_exists($basePath)) {
    require_once $basePath;
} else {
    die("base.php not found");
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: order.php');
    exit;
}

$orderID     = trim($_POST['orderID'] ?? '');
$orderStatus = trim($_POST['orderStatus'] ?? '');

$allowed = ['Processing', 'Shipped', 'Delivered', 'Cancelled'];

if (empty($orderID) || !in_array($orderStatus, $allowed)) {
    header('Location: order.php');
    exit;
}

try {
    $stmt = $_db->prepare("UPDATE orders SET orderStatus = ? WHERE orderID = ?");
    $stmt->execute([$orderStatus, $orderID]);
} catch (PDOException $e) {

}

header('Location: order.php');
exit;
