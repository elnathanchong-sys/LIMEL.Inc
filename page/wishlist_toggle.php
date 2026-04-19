<?php
include '../base.php';

// This file is called by product.php via fetch() (AJAX)
// It toggles a product in/out of the user's wishlist
// Returns JSON: { "wishlisted": true/false }

header('Content-Type: application/json');

// Must be logged in
if (!isset($_SESSION['users'])) {
    echo json_encode(['error' => 'not_logged_in']);
    exit;
}

$userID    = $_SESSION['users']->userID;
$productID = $_GET['productID'] ?? '';

if (!$productID) {
    echo json_encode(['error' => 'missing_product']);
    exit;
}

// Check if already wishlisted
$check = $_db->prepare("SELECT wishlistID FROM wishlist WHERE userID = ? AND productID = ?");
$check->execute([$userID, $productID]);
$existing = $check->fetch();

if ($existing) {
    // Already in wishlist — remove it
    $del = $_db->prepare("DELETE FROM wishlist WHERE userID = ? AND productID = ?");
    $del->execute([$userID, $productID]);
    echo json_encode(['wishlisted' => false]);
} else {
    // Not in wishlist — add it
    $ins = $_db->prepare("INSERT INTO wishlist (userID, productID) VALUES (?, ?)");
    $ins->execute([$userID, $productID]);
    echo json_encode(['wishlisted' => true]);
}
