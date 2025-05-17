<?php
require_once '../includes/database.php';
require_once '../includes/header.php';
require_once '../includes/sidebar.php';

// Fetch all products with their category names
$sql = "SELECT p.ID, p.title, p.price, p.status, c.Name AS category_name 
        FROM products p 
        LEFT JOIN categories c ON p.category_id = c.ID 
        ORDER BY p.ID DESC";

$result = $connect->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Products</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
<div class="container mt-5">
    <h2>Products</h2>
    <a href="create.php" class="btn btn-success mb-3">Add New Product</a>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th><th>Title</th><th>Price</th><th>Category</th><th>Status</th><th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['ID']) ?></td>
                        <td><?= htmlspecialchars($row['title']) ?></td>
                        <td>$<?= htmlspecialchars(number_format($row['price'], 2)) ?></td>
                        <td><?= htmlspecialchars($row['category_name'] ?? 'Uncategorized') ?></td>
                        <td><?= $row['status'] ? 'Active' : 'Inactive' ?></td>
                        <td>
                            <a href="edit.php?id=<?= $row['ID'] ?>" class="btn btn-primary btn-sm">Edit</a>
                            <a href="delete.php?id=<?= $row['ID'] ?>" onclick="return confirm('Delete this product?');" class="btn btn-danger btn-sm">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="6" class="text-center">No products found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>
