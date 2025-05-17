<?php
require_once '../includes/connect.php';

$product_id = $_GET['id'] ?? null;
if (!$product_id) {
    die('Product ID is required.');
}

// Fetch product
$productQuery = $connect->prepare("SELECT * FROM products WHERE ID = ?");
$productQuery->bind_param("i", $product_id);
$productQuery->execute();
$productResult = $productQuery->get_result();
$product = $productResult->fetch_assoc();
if (!$product) {
    die('Product not found.');
}

// Fetch categories
$categories = [];
$result = $connect->query("SELECT * FROM categories");
while ($row = $result->fetch_assoc()) {
    $categories[] = $row;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $price = trim($_POST['price']);
    $description = trim($_POST['description']);
    $category_id = $_POST['category_id'];
    $status = $_POST['status'];

    // Handle image upload if new image is uploaded
    if ($_FILES['image']['name']) {
        $image_name = time() . '_' . $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], "../../uploads/" . $image_name);
    } else {
        $image_name = $product['image']; // retain old image
    }

    $updateStmt = $connect->prepare("UPDATE products SET title=?, price=?, description=?, category_id=?, image=?, status=? WHERE ID=?");
    $updateStmt->bind_param("sdsissi", $title, $price, $description, $category_id, $image_name, $status, $product_id);
    if ($updateStmt->execute()) {
        header("Location: index.php");
        exit;
    } else {
        echo "Error updating product.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-4">

    <h2>Edit Product</h2>

    <form action="" method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label>Title</label>
            <input type="text" name="title" value="<?= htmlspecialchars($product['title']) ?>" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Price</label>
            <input type="number" step="0.01" name="price" value="<?= $product['price'] ?>" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Description</label>
            <textarea name="description" class="form-control"><?= htmlspecialchars($product['description']) ?></textarea>
        </div>

        <div class="mb-3">
            <label>Category</label>
            <select name="category_id" class="form-control" required>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>" <?= $cat['id'] == $product['category_id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label>Status</label>
            <select name="status" class="form-control" required>
                <option value="active" <?= $product['status'] === 'active' ? 'selected' : '' ?>>Active</option>
                <option value="inactive" <?= $product['status'] === 'inactive' ? 'selected' : '' ?>>Inactive</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Image</label><br>
            <img src="../../uploads/<?= $product['image'] ?>" alt="" width="120"><br>
            <input type="file" name="image" class="form-control mt-2">
        </div>

        <button type="submit" class="btn btn-primary">Update Product</button>
        <a href="index.php" class="btn btn-secondary">Cancel</a>
    </form>

</body>
</html>
