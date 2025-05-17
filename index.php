<?php
include_once('admin/includes/database.php');

session_start();
if (!isset($_SESSION['username'])) {
    header("Location: admin/auth/login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Welcome, <?= htmlspecialchars($_SESSION['username']) ?></h1>
        <a href="admin/auth/logout.php" class="btn btn-danger">Logout</a>
    </div>

    <div class="row">
        <div class="col-md-4">
            <a href="admin/category/index.php" class="btn btn-primary w-100 mb-3">Manage Categories</a>
        </div>
        <div class="col-md-4">
            <a href="admin/products/index.php" class="btn btn-success w-100 mb-3">Manage Products</a>
        </div>
        <div class="col-md-4">
            <a href="admin/customers/index.php" class="btn btn-info w-100 mb-3">View Customers</a>
        </div>
    </div>
</div>

</body>
</html>
