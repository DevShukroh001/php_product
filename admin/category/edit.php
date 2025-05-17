<?php
session_start();
include_once("../includes/database.php");

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: index.php");
    exit;
}

$errors = [];
$name = '';

// Fetch category data
$stmt = $connect->prepare("SELECT Name FROM categories WHERE ID = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($name);
if (!$stmt->fetch()) {
    $stmt->close();
    header("Location: index.php");
    exit;
}
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    if (empty($name)) {
        $errors[] = "Category name is required";
    }

    if (empty($errors)) {
        $stmt = $connect->prepare("UPDATE categories SET Name = ? WHERE ID = ?");
        $stmt->bind_param("si", $name, $id);
        if ($stmt->execute()) {
            header("Location: index.php");
            exit;
        } else {
            $errors[] = "Error updating category";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Edit Category</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
<div class="container mt-4">
    <h2>Edit Category</h2>

    <?php if ($errors): ?>
        <div class="alert alert-danger">
            <ul>
                <?php foreach ($errors as $err): ?>
                    <li><?= htmlspecialchars($err) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="post" action="edit.php?id=<?= $id ?>">
        <div class="mb-3">
            <label>Category Name</label>
            <input type="text" name="name" value="<?= htmlspecialchars($name) ?>" class="form-control" required />
        </div>
        <button type="submit" class="btn btn-primary">Update Category</button>
        <a href="index.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
</body>
</html>
