<?php
require_once '../includes/connect.php';

$id = $_GET['id'] ?? null;

if ($id) {
    $stmt = $connect->prepare("DELETE FROM categories WHERE ID = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->close();
}

header('Location: index.php');
exit;
