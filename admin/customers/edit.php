<?php
require_once '../includes/connect.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: index.php");
    exit;
}

$stmt = $connect->prepare("SELECT * FROM customers WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$customer = $result->fetch_assoc();
$stmt->close();

if (!$customer) {
    die("Customer not found.");
}

$firstname = $customer['firstname'];
$lastname = $customer['lastname'];
$phone = $customer['phone_number'];
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $phone = $_POST['phone'];

    if (empty($firstname)) $errors[] = "First name is required";
    if (empty($lastname)) $errors[] = "Last name is required";
    if (empty($phone)) $errors[] = "Phone number is required";

    if (empty($errors)) {
        $stmt = $connect->prepare("UPDATE customers SET firstname = ?, lastname = ?, phone_number = ? WHERE id = ?");
        $stmt->bind_param("sssi", $firstname, $lastname, $phone, $id);
        if ($stmt->execute()) {
            header("Location: index.php");
            exit;
        } else {
            $errors[] = "Failed to update customer";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Customer</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
  <h2>Edit Customer</h2>
  <a href="index.php" class="btn btn-secondary mb-3">Back to List</a>

  <?php if ($errors): ?>
    <div class="alert alert-danger">
      <ul>
        <?php foreach ($errors as $err): ?>
          <li><?= htmlspecialchars($err) ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <form method="post">
    <div class="mb-3">
      <label>First Name</label>
      <input type="text" name="firstname" value="<?= htmlspecialchars($firstname) ?>" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>Last Name</label>
      <input type="text" name="lastname" value="<?= htmlspecialchars($lastname) ?>" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>Phone Number</label>
      <input type="text" name="phone" value="<?= htmlspecialchars($phone) ?>" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary">Update Customer</button>
  </form>
</div>
</body>
</html>
