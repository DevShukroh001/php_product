<?php
require_once '../includes/connect.php';

if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$id = intval($_GET['id']);

// Fetch product to get image path
$stmt = $connect->prepare("SELECT image FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();
$stmt->close();

if (!$product) {
    header('Location: index.php');
    exit;
}

// Delete image from uploads folder if exists
if (!empty($product['image'])) {
    $imagePath = '../../' . $product['image'];
    if (file_exists($imagePath)) {
        unlink($imagePath);
    }
}

// Delete product from database
$stmt = $connect->prepare("DELETE FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->close();

header('Location: index.php');
exit;
