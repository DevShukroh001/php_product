<?php
session_start();
include_once("../includes/database.php");

$name = '';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    if (empty($name)) {
        $errors[] = "Category name is required";
    }

    if (empty($errors)) {
        $stmt = $connect->prepare("INSERT INTO categories (Name) VALUES (?)");
        $stmt->bind_param("s", $name);
        if ($stmt->execute()) {
            header("Location: index.php");
            exit;
        } else {
            $errors[] = "Error adding category";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Add Category</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
<div class="container mt-4">
    <h2>Add Category</h2>

    <?php if ($errors): ?>
        <div class="alert alert-danger">
            <ul>
                <?php foreach ($errors as $err): ?>
                    <li><?= htmlspecialchars($err) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="post" action="create.php">
        <div class="mb-3">
            <label>Category Name</label>
            <input type="text" name="name" value="<?= htmlspecialchars($name) ?>" class="form-control" required />
        </div>
        <button type="submit" class="btn btn-success">Add Category</button>
        <a href="index.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
</body>
</html>
