<?php
// wishlist_toggle.php
// AJAX endpoint — toggles a product in/out of the logged-in user's wishlist
// Returns JSON: {"wishlisted": true/false}

include '../base.php';

header('Content-Type: application/json');

// Must be logged in
$sessionUser = $_SESSION['users'] ?? null;
if (!$sessionUser) {
    echo json_encode(['error' => 'Not logged in']);
    exit;
}

$productID = trim($_GET['productID'] ?? '');
if (empty($productID)) {
    echo json_encode(['error' => 'No product ID']);
    exit;
}

$userID = $sessionUser->userID;

// Check if already wishlisted
$stmt = $_db->prepare("SELECT wishlistID FROM wishlist WHERE userID = ? AND productID = ?");
$stmt->execute([$userID, $productID]);
$existing = $stmt->fetch();

if ($existing) {
    // Already wishlisted — remove it
    $del = $_db->prepare("DELETE FROM wishlist WHERE userID = ? AND productID = ?");
    $del->execute([$userID, $productID]);
    echo json_encode(['wishlisted' => false]);
} else {
    // Not wishlisted — add it
    $ins = $_db->prepare("INSERT INTO wishlist (userID, productID) VALUES (?, ?)");
    $ins->execute([$userID, $productID]);
    echo json_encode(['wishlisted' => true]);
}
