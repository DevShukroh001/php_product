<?php
require_once '../includes/connect.php';

// Fetch categories for the dropdown
$categoryStmt = $connect->prepare("SELECT ID, Name FROM categories");
$categoryStmt->execute();
$categoryResult = $categoryStmt->get_result();
$categories = $categoryResult->fetch_all(MYSQLI_ASSOC);

$errors = [];
$title = $price = $description = $category_id = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $price = trim($_POST['price']);
    $description = trim($_POST['description']);
    $category_id = $_POST['category_id'];

    // Validate fields
    if (empty($title)) $errors[] = "Title is required.";
    if (!is_numeric($price)) $errors[] = "Valid price is required.";
    if (empty($category_id)) $errors[] = "Category is required.";

    //  image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $imageName = time() . '_' . basename($_FILES['image']['name']);
        $targetPath = '../../uploads/' . $imageName;
        $imageFileType = strtolower(pathinfo($targetPath, PATHINFO_EXTENSION));

        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($imageFileType, $allowedTypes)) {
            if (!move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                $errors[] = "Failed to upload image.";
            }
        } else {
            $errors[] = "Only JPG, JPEG, PNG & GIF files are allowed.";
        }
    } else {
        $errors[] = "Product image is required.";
    }

    if (empty($errors)) {
        $stmt = $connect->prepare("INSERT INTO products (title, price, description, category_id, image, status) VALUES (?, ?, ?, ?, ?, 1)");
        $stmt->bind_param("sdsis", $title, $price, $description, $category_id, $imageName);
        $stmt->execute();
        header("Location: index.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5" style="max-width: 600px;">
    <h2 class="mb-4">Add Product</h2>

    <?php if ($errors): ?>
        <div class="alert alert-danger">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="create.php" method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" name="title" value="<?= htmlspecialchars($title) ?>" class="form-control">
        </div>
        <div class="mb-3">
            <label for="price" class="form-label">Price</label>
            <input type="text" name="price" value="<?= htmlspecialchars($price) ?>" class="form-control">
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" class="form-control"><?= htmlspecialchars($description) ?></textarea>
        </div>
        <div class="mb-3">
            <label for="category_id" class="form-label">Category</label>
            <select name="category_id" class="form-select">
                <option value="">Select category</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['ID'] ?>" <?= $cat['ID'] == $category_id ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['Name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="image" class="form-label">Product Image</label>
            <input type="file" name="image" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Save</button>
        <a href="index.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
</body>
</html>
