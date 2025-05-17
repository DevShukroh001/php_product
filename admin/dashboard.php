<?php
require_once 'includes/connect.php';

$totalCategories = getTotalCategories($connect);
$totalProducts = getTotalProducts($connect);
$totalCustomers = getTotalCustomers($connect);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body>
   <?php require_once 'includes/header.php'; ?>

    <div class="container-fluid">
        <div class="row">
            <nav class="col-md-2 d-none d-md-block bg-light sidebar py-4">
                <div class="position-sticky">
                    <ul class="nav flex-column" style="width: 220px; min-height: 100vh;>
                        <li class="nav-item"><a class="nav-link active" href="category/index.php"><i class="fas fa-folder"></i>Category</a></li>
                        <li class="nav-item"><a class="nav-link" href="products/index.php"><i class="fas fa-box"></i> Products</a></li>
                        <li class="nav-item"><a class="nav-link" href="customers/index.php"><i class="fas fa-users"></i> Customers</a></li>
                    </ul>
                </div>
            </nav>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
                <h2>Welcome, Admin</h2>
                <div class="row mt-4">
                    <div class="col-md-4">
                        <div class="card text-white bg-primary mb-3">
                            <div class="card-body">
                                <h5 class="card-title">Total Categories</h5>
                                <p class="card-text"><?= $totalCategories ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card text-white bg-success mb-3">
                            <div class="card-body">
                                <h5 class="card-title">Total Products</h5>
                                <p class="card-text"><?= $totalProducts ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card text-white bg-info mb-3">
                            <div class="card-body">
                                <h5 class="card-title">Total Customers</h5>
                                <p class="card-text"><?= $totalCustomers ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
