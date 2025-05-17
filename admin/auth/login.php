<?php
session_start();

include_once(__DIR__ . '/../includes/database.php');

if (!$connect) {
  die("Database connection failed.");
}

if ($connect->connect_error) {
  die("Database connection error: " . $connect->connect_error);
}

$errors = [];
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = trim($_POST['email']);
  $password = $_POST['password'];

  if (empty($email)) {
    $errors[] = "Email is required";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Enter a valid email";
  }

  if (empty($password)) {
    $errors[] = "Password is required";
  }

  if (empty($errors)) {
    $stmt = $connect->prepare("SELECT id, username, password FROM users WHERE email = ?");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 1) {
      $stmt->bind_result($id, $username, $hashed_password);
      $stmt->fetch();

      if (password_verify($password, $hashed_password)) {
        // Password matches, log user in
        $_SESSION['user_id'] = $id;
        $_SESSION['username'] = $username;
        header("Location: dashboard.php");
        exit;
      } else {
        $errors[] = "Incorrect password";
      }
    } else {
      $errors[] = "Email not found";
    }

    $stmt->close();
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>

<body class="bg-light">
  <?php
  include_once("../includes/header.php");
  ?>
  <div class="container mt-5" style="max-width: 500px;">
    <h2 class="mb-4">Login</h2>

    <?php if ($errors): ?>
      <div class="alert alert-danger">
        <ul>
          <?php foreach ($errors as $err): ?>
            <li><?= htmlspecialchars($err) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>

    <form action="login.php" method="post">
      <div class="mb-3">
        <label>Email</label>
        <input type="email" name="email" value="<?= htmlspecialchars($email) ?>" class="form-control" required />
      </div>
      <div class="mb-3">
        <label>Password</label>
        <input type="password" name="password" class="form-control" required />
      </div>
      <button type="submit" class="btn btn-primary w-100">Login</button>
      <p class="mt-3 text-center">Don't have an account? <a href="register.php">Register here</a></p>
    </form>
  </div>

</body>

</html>